<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePortfolioCmsSchema extends AbstractMigration
{
    public function change(): void
    {
        $options = ['collation' => 'utf8mb4_unicode_ci', 'engine' => 'InnoDB'];

        // Administration
        $this->table('roles', $options)
            ->addColumn('name', 'string', ['limit' => 80])
            ->addColumn('slug', 'string', ['limit' => 80])
            ->addColumn('description', 'string', ['limit' => 255, 'null' => true])
            ->addTimestamps()
            ->addIndex(['slug'], ['unique' => true])
            ->create();

        $this->table('users', $options)
            ->addColumn('role_id', 'integer', ['signed' => false])
            ->addColumn('name', 'string', ['limit' => 150])
            ->addColumn('email', 'string', ['limit' => 190])
            ->addColumn('password_hash', 'string', ['limit' => 255])
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'active'])
            ->addColumn('last_login_at', 'datetime', ['null' => true])
            ->addTimestamps()
            ->addIndex(['email'], ['unique' => true])
            ->addForeignKey('role_id', 'roles', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE'])
            ->create();

        // Profile and site-wide settings
        $this->table('profile', $options)
            ->addColumn('full_name', 'string', ['limit' => 150])
            ->addColumn('headline', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('bio', 'text', ['null' => true])
            ->addColumn('profile_image', 'string', ['limit' => 500, 'null' => true])
            ->addColumn('resume_file', 'string', ['limit' => 500, 'null' => true])
            ->addColumn('location', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('public_email', 'string', ['limit' => 190, 'null' => true])
            ->addColumn('phone', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('availability', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('meta_title', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('meta_description', 'string', ['limit' => 500, 'null' => true])
            ->addTimestamps()
            ->create();

        $this->table('social_links', $options)
            ->addColumn('platform', 'string', ['limit' => 80])
            ->addColumn('label', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('url', 'string', ['limit' => 500])
            ->addColumn('icon', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('sort_order', 'integer', ['default' => 0])
            ->addColumn('is_visible', 'boolean', ['default' => true])
            ->addTimestamps()
            ->create();

        $this->table('site_settings', $options)
            ->addColumn('setting_key', 'string', ['limit' => 150])
            ->addColumn('setting_value', 'text', ['null' => true])
            ->addColumn('setting_group', 'string', ['limit' => 80, 'default' => 'general'])
            ->addColumn('value_type', 'string', ['limit' => 30, 'default' => 'string'])
            ->addTimestamps()
            ->addIndex(['setting_key'], ['unique' => true])
            ->create();

        // Uploaded files and images
        $this->table('media', $options)
            ->addColumn('uploaded_by', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('disk', 'string', ['limit' => 50, 'default' => 'public'])
            ->addColumn('file_name', 'string', ['limit' => 255])
            ->addColumn('original_name', 'string', ['limit' => 255])
            ->addColumn('file_path', 'string', ['limit' => 500])
            ->addColumn('mime_type', 'string', ['limit' => 150])
            ->addColumn('size_bytes', 'biginteger', ['signed' => false])
            ->addColumn('alt_text', 'string', ['limit' => 255, 'null' => true])
            ->addTimestamps()
            ->addForeignKey('uploaded_by', 'users', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
            ->create();

        // Portfolio projects
        $this->table('project_categories', $options)
            ->addColumn('name', 'string', ['limit' => 120])
            ->addColumn('slug', 'string', ['limit' => 140])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('sort_order', 'integer', ['default' => 0])
            ->addTimestamps()
            ->addIndex(['slug'], ['unique' => true])
            ->create();

        $this->table('projects', $options)
            ->addColumn('category_id', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('thumbnail_media_id', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('title', 'string', ['limit' => 200])
            ->addColumn('slug', 'string', ['limit' => 220])
            ->addColumn('summary', 'string', ['limit' => 500, 'null' => true])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('client_name', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('role', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('project_url', 'string', ['limit' => 500, 'null' => true])
            ->addColumn('repository_url', 'string', ['limit' => 500, 'null' => true])
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'draft'])
            ->addColumn('is_featured', 'boolean', ['default' => false])
            ->addColumn('sort_order', 'integer', ['default' => 0])
            ->addColumn('published_at', 'datetime', ['null' => true])
            ->addColumn('meta_title', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('meta_description', 'string', ['limit' => 500, 'null' => true])
            ->addTimestamps()
            ->addColumn('deleted_at', 'datetime', ['null' => true])
            ->addIndex(['slug'], ['unique' => true])
            ->addIndex(['status', 'published_at'])
            ->addForeignKey('category_id', 'project_categories', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
            ->addForeignKey('thumbnail_media_id', 'media', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
            ->create();

        $this->table('skills', $options)
            ->addColumn('name', 'string', ['limit' => 120])
            ->addColumn('category', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('proficiency', 'integer', ['null' => true, 'signed' => false])
            ->addColumn('icon', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('sort_order', 'integer', ['default' => 0])
            ->addColumn('is_visible', 'boolean', ['default' => true])
            ->addTimestamps()
            ->create();

        $this->table('experiences', $options)
            ->addColumn('company', 'string', ['limit' => 180])
            ->addColumn('position', 'string', ['limit' => 180])
            ->addColumn('employment_type', 'string', ['limit' => 80, 'null' => true])
            ->addColumn('location', 'string', ['limit' => 180, 'null' => true])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('start_date', 'date')
            ->addColumn('end_date', 'date', ['null' => true])
            ->addColumn('is_current', 'boolean', ['default' => false])
            ->addColumn('sort_order', 'integer', ['default' => 0])
            ->addTimestamps()
            ->create();

        $this->table('education', $options)
            ->addColumn('institution', 'string', ['limit' => 200])
            ->addColumn('qualification', 'string', ['limit' => 180])
            ->addColumn('field_of_study', 'string', ['limit' => 180, 'null' => true])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('start_date', 'date', ['null' => true])
            ->addColumn('end_date', 'date', ['null' => true])
            ->addColumn('sort_order', 'integer', ['default' => 0])
            ->addTimestamps()
            ->create();

        // Blog content
        $this->table('blog_categories', $options)
            ->addColumn('name', 'string', ['limit' => 120])
            ->addColumn('slug', 'string', ['limit' => 140])
            ->addColumn('description', 'text', ['null' => true])
            ->addTimestamps()
            ->addIndex(['slug'], ['unique' => true])
            ->create();

        $this->table('blogs', $options)
            ->addColumn('category_id', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('author_id', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('featured_media_id', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('title', 'string', ['limit' => 220])
            ->addColumn('slug', 'string', ['limit' => 240])
            ->addColumn('excerpt', 'string', ['limit' => 600, 'null' => true])
            ->addColumn('content', 'text', ['null' => true])
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'draft'])
            ->addColumn('is_featured', 'boolean', ['default' => false])
            ->addColumn('view_count', 'biginteger', ['signed' => false, 'default' => 0])
            ->addColumn('published_at', 'datetime', ['null' => true])
            ->addColumn('meta_title', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('meta_description', 'string', ['limit' => 500, 'null' => true])
            ->addTimestamps()
            ->addColumn('deleted_at', 'datetime', ['null' => true])
            ->addIndex(['slug'], ['unique' => true])
            ->addIndex(['status', 'published_at'])
            ->addForeignKey('category_id', 'blog_categories', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
            ->addForeignKey('author_id', 'users', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
            ->addForeignKey('featured_media_id', 'media', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
            ->create();

        $this->table('blog_tags', $options)
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('slug', 'string', ['limit' => 120])
            ->addTimestamps()
            ->addIndex(['slug'], ['unique' => true])
            ->create();

        $this->table('blog_tag_relations', $options)
            ->addColumn('blog_id', 'integer', ['signed' => false])
            ->addColumn('tag_id', 'integer', ['signed' => false])
            ->addIndex(['blog_id', 'tag_id'], ['unique' => true])
            ->addForeignKey('blog_id', 'blogs', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('tag_id', 'blog_tags', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

        // Contact and newsletter
        $this->table('messages', $options)
            ->addColumn('name', 'string', ['limit' => 150])
            ->addColumn('email', 'string', ['limit' => 190])
            ->addColumn('subject', 'string', ['limit' => 200, 'null' => true])
            ->addColumn('message', 'text')
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'unread'])
            ->addColumn('ip_address', 'string', ['limit' => 45, 'null' => true])
            ->addColumn('user_agent', 'string', ['limit' => 500, 'null' => true])
            ->addColumn('read_at', 'datetime', ['null' => true])
            ->addTimestamps()
            ->addIndex(['status', 'created_at'])
            ->create();

        $this->table('newsletter_subscribers', $options)
            ->addColumn('email', 'string', ['limit' => 190])
            ->addColumn('name', 'string', ['limit' => 150, 'null' => true])
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'pending'])
            ->addColumn('subscribed_at', 'datetime', ['null' => true])
            ->addColumn('confirmed_at', 'datetime', ['null' => true])
            ->addColumn('unsubscribed_at', 'datetime', ['null' => true])
            ->addTimestamps()
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['status'])
            ->create();
    }
}
