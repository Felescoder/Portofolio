<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactDetailResource;
use App\Http\Resources\ContactOpportunityResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\SkillGroupResource;
use App\Http\Resources\SocialLinkResource;
use App\Models\ContactDetail;
use App\Models\ContactOpportunity;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use App\Models\SkillGroup;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $profile = Profile::query()->firstOrFail();

        $sections = Section::query()
            ->get()
            ->mapWithKeys(fn (Section $section) => [
                $section->key => (new SectionResource($section))->resolve($request),
            ]);

        $socialLinks = SocialLink::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $services = Service::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $skillGroups = SkillGroup::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $experiences = Experience::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $projects = Project::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $contactDetails = ContactDetail::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $contactOpportunities = ContactOpportunity::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => [
                'profile' => (new ProfileResource($profile))->resolve($request),
                'sections' => $sections,
                'social_links' => SocialLinkResource::collection($socialLinks)->resolve($request),
                'services' => ServiceResource::collection($services)->resolve($request),
                'skill_groups' => SkillGroupResource::collection($skillGroups)->resolve($request),
                'experiences' => ExperienceResource::collection($experiences)->resolve($request),
                'projects' => ProjectResource::collection($projects)->resolve($request),
                'contact_details' => ContactDetailResource::collection($contactDetails)->resolve($request),
                'contact_opportunities' => ContactOpportunityResource::collection($contactOpportunities)->resolve($request),
            ],
        ]);
    }
}
