<?php
namespace App\Controller;

use App\Core\Request;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Skill;
use App\View\View;

final class HomeController extends ControllerSupport
{
    /**
     * Display the portfolio homepage.
     */
    public function index(Request $request): void
    {
        // Retrieve visible skills, ordered by sort_order.
        $skills = (new Skill())->where('is_visible', 1, 100);

        usort(
            $skills,
            static fn(array $a, array $b): int =>
                ((int) ($a['sort_order'] ?? 0))
                <=>
                ((int) ($b['sort_order'] ?? 0))
        );

        // Retrieve published projects.
        $allPublishedProjects = (new Project())->where(
            'status',
            'published',
            100
        );

        // Keep only featured projects.
        $featuredProjects = array_values(
            array_filter(
                $allPublishedProjects,
                static fn(array $project): bool =>
                    (int) ($project['is_featured'] ?? 0) === 1
            )
        );

        // Order featured projects by sort_order.
        usort(
            $featuredProjects,
            static fn(array $a, array $b): int =>
                ((int) ($a['sort_order'] ?? 0))
                <=>
                ((int) ($b['sort_order'] ?? 0))
        );

        // Limit homepage projects to six.
        $featuredProjects = array_slice($featuredProjects, 0, 6);

        // Retrieve project categories.
        $categories = (new ProjectCategory())->all(
            100,
            0,
            'sort_order',
            'ASC'
        );

        // Create a category lookup for the project cards.
        $categoryLookup = [];

        foreach ($categories as $category) {
            $categoryLookup[(int) $category['id']] = $category;
        }

        // Attach category information to each featured project.
        foreach ($featuredProjects as &$project) {
            $categoryId = (int) ($project['category_id'] ?? 0);

            $category = $categoryLookup[$categoryId] ?? [];

            $project['category_name'] = $category['name'] ?? 'Uncategorized';
            $project['category_slug'] = $category['slug'] ?? '';
        }

        unset($project);

        // Pass homepage data to the view.
        View::render('public/landingPage', [
            'skills' => $skills,
            'featuredProjects' => $featuredProjects,
            'categories' => $categories,
            'pageTitle' => 'Home',
        ]);
    }
}
