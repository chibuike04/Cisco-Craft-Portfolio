# Models

The models use the shared PDO connection from `App\Core\Database` and the reusable base class `App\Models\Model`. Each concrete model maps to a table in the existing Phinx migration.

## Models included

`Role`, `User`, `Profile`, `SocialLink`, `SiteSetting`, `Media`, `ProjectCategory`, `Project`, `Skill`, `Experience`, `Education`, `BlogCategory`, `Blog`, `BlogTag`, `BlogTagRelation`, `Message`, and `NewsletterSubscriber`.

## Example

```php
use App\Models\Project;

$projects = new Project();
$project = $projects->find(1);
$recent = $projects->all(10, 0, 'created_at', 'DESC');
$newId = $projects->create([
    'title' => 'Portfolio Website',
    'slug' => 'portfolio-website',
    'status' => 'published',
]);
$projects->update($newId, ['title' => 'Updated Portfolio Website']);
$projects->delete($newId);
```

`Project` and `Blog` use soft deletes. Their `delete()` method sets `deleted_at`; normal reads exclude those records. Use `restore($id)` to restore one. Other models use normal deletion.

All data values are sent through PDO prepared statements. Inserts and updates are restricted to each model's `$fillable` fields.
