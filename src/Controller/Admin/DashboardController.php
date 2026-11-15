<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\ControllerSupport;
use App\Core\Request;
use App\Models\Blog;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;
use App\View\View;

final class DashboardController extends ControllerSupport
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request): void
    {
        // Initialize models.
        $blogModel = new Blog();
        $projectModel = new Project();
        $messageModel = new Message();
        $skillModel = new Skill();

        // Retrieve published projects.
        $publishedProjects = $projectModel->where(
            'status',
            'published',
            500
        );

        // Retrieve published blog posts.
        $publishedBlogs = $blogModel->where(
            'status',
            'published',
            500
        );

        // Retrieve visible skills.
        $visibleSkills = $skillModel->where(
            'is_visible',
            1,
            500
        );

        // Retrieve the latest contact messages.
        $recentMessages = $messageModel->all(
            5,
            0,
            'id',
            'DESC'
        );

        // Sort published projects by newest first.
        usort(
            $publishedProjects,
            static fn(array $a, array $b): int =>
                (int) $b['id'] <=> (int) $a['id']
        );

        // Sort published blogs by newest first.
        usort(
            $publishedBlogs,
            static fn(array $a, array $b): int =>
                (int) $b['id'] <=> (int) $a['id']
        );

        // Calculate featured content.
        $featuredProjects = array_filter(
            $publishedProjects,
            static fn(array $project): bool =>
                (int) ($project['is_featured'] ?? 0) === 1
        );

        $featuredBlogs = array_filter(
            $publishedBlogs,
            static fn(array $blog): bool =>
                (int) ($blog['is_featured'] ?? 0) === 1
        );

        // Dashboard statistics.
        $statistics = [
            'total_blogs' => $blogModel->count(),
            'total_messages' => $messageModel->count(),
            'active_projects' => count($publishedProjects),
            'portfolio_views' => null,
            'featured_blogs' => count($featuredBlogs),
            'featured_projects' => count($featuredProjects),
            'total_skills' => count($visibleSkills),
        ];

        // Get the five latest published blog posts.
        $recentBlogs = array_slice(
            $publishedBlogs,
            0,
            5
        );

        // Get the five latest published projects.
        $recentProjects = array_slice(
            $publishedProjects,
            0,
            5
        );

        // Render the dashboard.
        View::render('admin/dashboard', [
            'statistics' => $statistics,
            'recentBlogs' => $recentBlogs,
            'recentProjects' => $recentProjects,
            'recentMessages' => $recentMessages,
            'pageTitle' => 'Admin Dashboard',
        ]);
    }
}
