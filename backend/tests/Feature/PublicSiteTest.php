<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_returns_the_public_content_structure(): void
    {
        $this->createProfile();

        Section::query()->create([
            'key' => 'services',
            'kicker' => 'Layanan',
            'title' => 'Solusi digital',
            'highlight' => 'terukur.',
            'description' => 'Deskripsi layanan publik.',
            'meta' => null,
        ]);

        Service::query()->create([
            'title' => 'Layanan Aktif',
            'description' => 'Layanan yang tampil di situs publik.',
            'tags' => ['Laravel'],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Service::query()->create([
            'title' => 'Layanan Nonaktif',
            'description' => 'Layanan yang disembunyikan.',
            'tags' => ['Laravel'],
            'sort_order' => 2,
            'is_active' => false,
        ]);

        Project::query()->create([
            'title' => 'Projek Aktif',
            'category' => 'Web',
            'description' => 'Projek yang tampil di situs publik.',
            'tags' => ['Vue'],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Project::query()->create([
            'title' => 'Projek Nonaktif',
            'category' => 'Web',
            'description' => 'Projek yang disembunyikan.',
            'tags' => ['Vue'],
            'sort_order' => 2,
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/site');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'profile',
                    'sections',
                    'social_links',
                    'services',
                    'skill_groups',
                    'experiences',
                    'projects',
                    'contact_details',
                    'contact_opportunities',
                ],
            ])
            ->assertJsonPath('data.profile.name', 'Profil Pengujian')
            ->assertJsonPath('data.sections.services.title', 'Solusi digital')
            ->assertJsonCount(1, 'data.services')
            ->assertJsonPath('data.services.0.title', 'Layanan Aktif')
            ->assertJsonCount(1, 'data.projects')
            ->assertJsonPath('data.projects.0.title', 'Projek Aktif')
            ->assertJsonCount(0, 'data.skill_groups')
            ->assertJsonCount(0, 'data.experiences')
            ->assertJsonCount(0, 'data.social_links')
            ->assertJsonCount(0, 'data.contact_details')
            ->assertJsonCount(0, 'data.contact_opportunities');
    }

    public function test_site_requires_a_profile(): void
    {
        $this->getJson('/api/site')->assertNotFound();
    }

    private function createProfile(): Profile
    {
        return Profile::query()->create([
            'name' => 'Profil Pengujian',
            'short_name' => 'Pengujian',
            'role' => 'Software Engineer',
            'typed_role' => 'Backend & Full-Stack',
            'hero_name_line_1' => 'Profil',
            'hero_name_line_2' => 'Pengujian',
            'hero_description' => 'Deskripsi profil untuk pengujian API publik.',
            'location' => 'Jakarta, Indonesia',
            'email' => 'profile@example.com',
        ]);
    }
}
