# `src/View`

This directory contains PHP presentation templates only. It must not contain SQL queries or database connection logic.

## Structure

```text
src/View/
├── View.php
├── components/
│   ├── skill-grid.php
│   ├── project-cards.php
│   └── blog-cards.php
├── public/
│   ├── home/
│   │   ├── skills.php
│   │   └── featured-projects.php
│   ├── projects/
│   │   └── index.php
│   └── 
│       ├── index.php
│       └── show.php
└── shared/
    └── pagination.php
```

## Rendering

Controllers should retrieve records through Models and pass arrays to `View::render()`:

```php
use App\\View\\View;

View::render('public/home/skills', [
    'skills' => $skills,
]);
```

`View::render()` accepts an optional layout path as its third argument. Without a layout it renders the requested template fragment directly.

## Data expected by templates

- `public/home/skills`: `skills` (visible skill rows ordered by `sort_order`).
- `public/home/featured-projects`: `projects` (project rows with `category_name`, `category_slug`, optional `thumbnail_url`).
- `public/projects/index`: `projects`, `categories`.
- `public/blog/index`: `blogs`, `categories`, optional `pagination`.
- `public/blog/show`: `blog`, optional `tags`, `author`, `tableOfContents`, `relatedBlogs`.

The project cards support an optional `technologies` array, but no technology relationship table currently exists. Do not populate it from guesses. When that table is designed and populated, the controller can pass the joined technologies.

Image URL fields are optional until media records and project/blog media links are populated.

## Blog article HTML safety

`public/blog/show.php` displays `blog['content_html']` as HTML only when the controller supplies it. The controller must sanitize this HTML before passing it to the template. If no sanitized `content_html` is supplied, the template escapes the plain `content` field.

## Current database scope

Only the following populated content tables are represented in the public templates:

- `skills`
- `projects`
- `project_categories`
- `blogs`
- `blog_categories`
- `blog_tags`
- `blog_tag_relations`

No views or queries are added for the other tables.

## Important integration note

These are view templates and reusable page sections. They do not register routes, fetch data, or replace the existing page styles/scripts. The next controller/layout integration step must reuse the CSS and JavaScript from the existing `index.php`, `projects.html`, `blog.html`, and `blog-post.html` so the current design remains visually consistent.
