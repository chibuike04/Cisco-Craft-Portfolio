<?php

declare(strict_types=1);

namespace App\Helpers;

final class Validation
{
    private function __construct()
    {
    }

    /**
     * Validate input using rules such as required, email, min:3 and max:255.
     * Returns an array of field errors; an empty array means validation passed.
     */
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $fieldRules = is_array($fieldRules) ? $fieldRules : explode('|', (string) $fieldRules);
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                [$ruleName, $parameter] = array_pad(explode(':', (string) $rule, 2), 2, null);
                $message = self::checkRule($ruleName, $parameter, $value);

                if ($message !== null) {
                    $errors[$field][] = $message;
                }
            }
        }

        return $errors;
    }

    public static function passes(array $data, array $rules): bool
    {
        return self::validate($data, $rules) === [];
    }

    private static function checkRule(string $rule, ?string $parameter, mixed $value): ?string
    {
        $isEmpty = $value === null || (is_string($value) && trim($value) === '');

        if ($rule === 'required') {
            return $isEmpty ? 'This field is required.' : null;
        }

        // Do not apply optional-field rules when no value was supplied.
        if ($isEmpty) {
            return null;
        }

        return match ($rule) {
            'email' => filter_var($value, FILTER_VALIDATE_EMAIL) ? null : 'Please enter a valid email address.',
            'url' => filter_var($value, FILTER_VALIDATE_URL) ? null : 'Please enter a valid URL.',
            'string' => is_string($value) ? null : 'This field must be text.',
            'numeric' => is_numeric($value) ? null : 'This field must be a number.',
            'integer' => filter_var($value, FILTER_VALIDATE_INT) !== false ? null : 'This field must be a whole number.',
            'min' => self::lengthOrNumber($value) >= (float) $parameter ? null : "This field must be at least {$parameter}.",
            'max' => self::lengthOrNumber($value) <= (float) $parameter ? null : "This field must not exceed {$parameter}.",
            'in' => in_array((string) $value, explode(',', (string) $parameter), true) ? null : 'The selected value is invalid.',
            default => null,
        };
    }

    private static function lengthOrNumber(mixed $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        return is_string($value) ? (float) (function_exists('mb_strlen') ? mb_strlen($value) : strlen($value)) : 0.0;
    }
}
