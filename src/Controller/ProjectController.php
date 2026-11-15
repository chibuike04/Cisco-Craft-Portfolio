<?php

namespace App\Controller;

use App\Core\Request;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Technology;
use App\Models\ProjectTechnology;
use App\View\View;

final class ProjectController extends ControllerSupport
{
    /**
     * Display all published projects.
     */
    public function index(Request $request): void
    {
        // Retrieve published projects.
        $allProjects = (new Project())->where(
            'status',
            'published',
            500
        );

        // Sort projects by their configured display order.
        usort(
            $allProjects,
            static fn(array $a, array $b): int =>
                ((int) ($a['sort_order'] ?? 0))
                <=>
                ((int) ($b['sort_order'] ?? 0))
        );

        // Retrieve project categories.
        $categories = (new ProjectCategory())->all(
            100,
            0,
            'sort_order',
            'ASC'
        );

        // Build category lookup.
        $categoryLookup = [];

        foreach ($categories as $category) {
            $categoryLookup[(int) $category['id']] = $category;
        }

        // Retrieve all technologies and build a lookup.
        $allTechnologies = (new Technology())->all(
            500,
            0,
            'name',
            'ASC'
        );

        $technologyLookup = [];

        foreach ($allTechnologies as $technology) {
            $technologyLookup[(int) $technology['id']] = $technology;
        }

        // Category names mapped to the existing frontend filter values.
        $categoryFilterMap = [
            'fullstack' => 'fullstack',
            'frontendui' => 'frontend',
            'fintechweb3' => 'web3',
            'aitools' => 'ai',
        ];

        // Attach category, technology and media information.
        $projectTechnologyModel = new ProjectTechnology();

        foreach ($allProjects as &$project) {
            // Attach category information.
            $category = $categoryLookup[
                (int) ($project['category_id'] ?? 0)
            ] ?? [];

            $project['category_name'] =
                $category['name'] ?? 'Uncategorized';

            $project['category_slug'] =
                $category['slug'] ?? '';

            // Match the category to the existing filter buttons.
            $normalizedCategory = preg_replace(
                '/[^a-z0-9]/',
                '',
                strtolower($project['category_name'])
            );

            $project['category_filter'] =
                $categoryFilterMap[$normalizedCategory] ?? 'all';

            // Attach related technologies.
            $project['technologies'] = [];

            $relations = $projectTechnologyModel->where(
                'project_id',
                (int) $project['id'],
                100
            );

            foreach ($relations as $relation) {
                $technologyId = (int) ($relation['technology_id'] ?? 0);

                if (isset($technologyLookup[$technologyId])) {
                    $project['technologies'][] =
                        $technologyLookup[$technologyId];
                }
            }

            // Media records have not been populated yet.
            $project['thumbnail_url'] = null;
            $project['image_url'] = null;
        }

        unset($project);

        // Render the projects page.
        View::render('public/project', [
            'projects' => $allProjects,
            'categories' => $categories,
            'pageTitle' => 'Projects',
        ]);
    }
}
