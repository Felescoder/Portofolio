<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\ContactDetail;
use App\Models\ContactOpportunity;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\SkillGroup;
use App\Models\SocialLink;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $timestamps = collect([
            Profile::query()->max('updated_at'),
            Service::query()->max('updated_at'),
            SkillGroup::query()->max('updated_at'),
            Experience::query()->max('updated_at'),
            Project::query()->max('updated_at'),
            SocialLink::query()->max('updated_at'),
            ContactDetail::query()->max('updated_at'),
            ContactOpportunity::query()->max('updated_at'),
        ])->filter();

        $lastUpdatedAt = $timestamps
            ->map(fn (string $timestamp) => Carbon::parse($timestamp))
            ->max();

        $recentProjects = Project::query()
            ->latest('updated_at')
            ->limit(3)
            ->get();

        return response()->json([
            'data' => [
                'stats' => [
                    'projects' => Project::query()->count(),
                    'services' => Service::query()->count(),
                    'skill_groups' => SkillGroup::query()->count(),
                    'experiences' => Experience::query()->count(),
                    'contact_details' => ContactDetail::query()->count(),
                    'contact_opportunities' => ContactOpportunity::query()->count(),
                ],
                'recent_projects' => ProjectResource::collection($recentProjects)->resolve($request),
                'last_updated_at' => $lastUpdatedAt?->toIso8601String(),
            ],
        ]);
    }
}
