<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use InvalidArgumentException;
use PDO;
use RuntimeException;

/** Shared PDO-based functionality for all application models. */
abstract class Model
{
    protected string $table;
    protected array $fillable = [];
    protected bool $softDeletes = false;
    protected PDO $db;

    public function __construct(?PDO $connection = null)
    {
        $this->db = $connection ?? Database::connection();
        if (!isset($this->table) || $this->table === '') {
            throw new RuntimeException('A model must define its database table.');
        }
        $this->assertIdentifier($this->table);
    }

    public function find(int $id): ?array
    {
        if ($id < 1) return null;
        $sql = "SELECT * FROM `{$this->table}` WHERE `id` = :id";
        if ($this->softDeletes) $sql .= ' AND `deleted_at` IS NULL';
        $statement = $this->db->prepare($sql . ' LIMIT 1');
        $statement->execute(['id' => $id]);
        $record = $statement->fetch();
        return $record === false ? null : $record;
    }

    public function all(int $limit = 100, int $offset = 0, string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $limit = max(1, min($limit, 500));
        $offset = max(0, $offset);
        $this->assertAllowedColumn($orderBy);
        $direction = strtoupper($direction);
        if (!in_array($direction, ['ASC', 'DESC'], true)) {
            throw new InvalidArgumentException('Sort direction must be ASC or DESC.');
        }
        $sql = "SELECT * FROM `{$this->table}`";
        if ($this->softDeletes) $sql .= ' WHERE `deleted_at` IS NULL';
        $sql .= " ORDER BY `{$orderBy}` {$direction} LIMIT :limit OFFSET :offset";
        $statement = $this->db->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }


    public function where(string $column, mixed $value, int $limit = 100): array
    {
        $this->assertAllowedColumn($column);

        $limit = max(1, min($limit, 500));

        $sql = "SELECT * FROM `{$this->table}` WHERE `{$column}` = :value";

        if ($this->softDeletes) {
            $sql .= ' AND `deleted_at` IS NULL';
        }

        // Do not order by `id`: some tables use composite primary keys.
        $sql .= ' LIMIT :limit';

        $statement = $this->db->prepare($sql);
        $statement->bindValue(':value', $value);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }


    public function firstWhere(string $column, mixed $value): ?array
    {
        return $this->where($column, $value, 1)[0] ?? null;
    }

    public function create(array $data): int
    {
        $data = $this->filterFillable($data);
        if ($data === []) throw new InvalidArgumentException('No valid fields were provided to create this record.');
        $columns = array_keys($data);
        $quoted = array_map(static fn(string $column): string => "`{$column}`", $columns);
        $placeholders = array_map(static fn(string $column): string => ':' . $column, $columns);
        $sql = sprintf('INSERT INTO `%s` (%s) VALUES (%s)', $this->table, implode(', ', $quoted), implode(', ', $placeholders));
        $statement = $this->db->prepare($sql);
        $statement->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        if ($id < 1) return false;
        $data = $this->filterFillable($data);
        if ($data === []) return false;
        $sets = [];
        foreach (array_keys($data) as $column) $sets[] = "`{$column}` = :{$column}";
        $sql = sprintf('UPDATE `%s` SET %s WHERE `id` = :_id', $this->table, implode(', ', $sets));
        if ($this->softDeletes) $sql .= ' AND `deleted_at` IS NULL';
        $data['_id'] = $id;
        return $this->db->prepare($sql)->execute($data);
    }

    public function delete(int $id): bool
    {
        if ($id < 1) return false;
        $sql = $this->softDeletes
            ? "UPDATE `{$this->table}` SET `deleted_at` = NOW() WHERE `id` = :id AND `deleted_at` IS NULL"
            : "DELETE FROM `{$this->table}` WHERE `id` = :id";
        return $this->db->prepare($sql)->execute(['id' => $id]);
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        if ($this->softDeletes) $sql .= ' WHERE `deleted_at` IS NULL';
        return (int) $this->db->query($sql)->fetchColumn();
    }

    public function exists(int $id): bool
    {
        return $this->find($id) !== null;
    }

    public function restore(int $id): bool
    {
        if (!$this->softDeletes || $id < 1) return false;
        $sql = "UPDATE `{$this->table}` SET `deleted_at` = NULL WHERE `id` = :id";
        return $this->db->prepare($sql)->execute(['id' => $id]);
    }

    protected function filterFillable(array $data): array
    {
        return array_intersect_key($data, array_flip($this->fillable));
    }

    protected function assertAllowedColumn(string $column): void
    {
        if (!in_array($column, array_merge(['id', 'created_at', 'updated_at'], $this->fillable), true)) {
            throw new InvalidArgumentException("Unknown or disallowed column: {$column}");
        }
    }

    private function assertIdentifier(string $identifier): void
    {
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $identifier)) {
            throw new InvalidArgumentException('Invalid SQL identifier.');
        }
    }
}
