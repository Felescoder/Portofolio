<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->admin()->create());
    }

    public function test_admin_can_create_update_and_delete_a_service(): void
    {
        $created = $this->postJson('/api/admin/services', [
            'number' => '04',
            'icon' => 'fa-solid fa-server',
            'title' => 'Layanan Pengujian',
            'description' => 'Deskripsi layanan pengujian.',
            'tags' => ['Laravel', 'MySQL'],
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $created
            ->assertCreated()
            ->assertJsonPath('data.title', 'Layanan Pengujian')
            ->assertJsonPath('data.tags.0', 'Laravel');

        $serviceId = $created->json('data.id');
        $this->assertDatabaseHas('services', [
            'id' => $serviceId,
            'title' => 'Layanan Pengujian',
        ]);

        $this->putJson("/api/admin/services/{$serviceId}", [
            'number' => '04',
            'icon' => 'fa-solid fa-server',
            'title' => 'Layanan Diperbarui',
            'description' => 'Deskripsi layanan yang sudah diperbarui.',
            'tags' => ['Vue'],
            'sort_order' => 4,
            'is_active' => false,
        ])
            ->assertOk()
            ->assertJsonPath('data.title', 'Layanan Diperbarui')
            ->assertJsonPath('data.is_active', false);

        $this->assertDatabaseHas('services', [
            'id' => $serviceId,
            'title' => 'Layanan Diperbarui',
            'is_active' => false,
        ]);

        $this->deleteJson("/api/admin/services/{$serviceId}")->assertOk();
        $this->assertDatabaseMissing('services', ['id' => $serviceId]);
    }

    public function test_admin_can_update_section_copy_and_meta(): void
    {
        $section = Section::query()->create([
            'key' => 'skills',
            'kicker' => 'Keahlian',
            'title' => 'Keahlian',
            'highlight' => 'awal.',
            'description' => 'Deskripsi awal.',
            'meta' => ['stats' => [['value' => '3', 'label' => 'Projek']]],
        ]);

        $this->putJson('/api/admin/sections/skills', [
            'kicker' => 'Keahlian Baru',
            'title' => 'Teknologi Baru',
            'highlight' => 'terukur.',
            'description' => 'Deskripsi baru.',
            'meta' => ['stats' => [['value' => '4', 'label' => 'Projek']]],
        ])
            ->assertOk()
            ->assertJsonPath('data.title', 'Teknologi Baru')
            ->assertJsonPath('data.meta.stats.0.value', '4');

        $section->refresh();

        $this->assertSame('Keahlian Baru', $section->kicker);
        $this->assertSame('Teknologi Baru', $section->title);
        $this->assertSame('4', $section->meta['stats'][0]['value']);
        $this->assertSame('Projek', $section->meta['stats'][0]['label']);
    }

    public function test_admin_can_upload_replace_and_delete_a_project_image(): void
    {
        Storage::fake('public');

        $created = $this->post('/api/admin/projects', [
            'title' => 'Projek Gambar',
            'category' => 'Aplikasi Web',
            'description' => 'Projek untuk pengujian unggah gambar.',
            'image_alt' => 'Pratinjau projek pengujian',
            'tags' => json_encode(['Laravel', 'Vue']),
            'url' => 'https://example.com/projek-gambar',
            'sort_order' => 5,
            'image' => UploadedFile::fake()->image('preview.jpg', 1280, 720),
        ], ['Accept' => 'application/json']);

        $created
            ->assertCreated()
            ->assertJsonPath('data.title', 'Projek Gambar')
            ->assertJsonPath('data.width', 1280)
            ->assertJsonPath('data.height', 720);

        $project = Project::query()->firstOrFail();
        $originalImage = $project->image_path;

        $this->assertNotNull($originalImage);
        Storage::disk('public')->assertExists($originalImage);

        $replacement = UploadedFile::fake()->image('replacement.png', 800, 600);

        $this->post("/api/admin/projects/{$project->id}", [
            'title' => 'Projek Gambar Diperbarui',
            'category' => 'Aplikasi Web',
            'description' => 'Projek dengan gambar pengganti.',
            'image_alt' => 'Pratinjau projek pengganti',
            'tags' => json_encode(['Vue']),
            'url' => 'https://example.com/projek-gambar-baru',
            'sort_order' => 6,
            'image' => $replacement,
        ], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonPath('data.title', 'Projek Gambar Diperbarui');

        $project->refresh();
        $replacementImage = $project->image_path;

        $this->assertNotSame($originalImage, $replacementImage);
        Storage::disk('public')->assertMissing($originalImage);
        Storage::disk('public')->assertExists($replacementImage);

        $this->deleteJson("/api/admin/projects/{$project->id}")->assertOk();

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        Storage::disk('public')->assertMissing($replacementImage);
    }

    public function test_public_site_reflects_inactive_content_changes(): void
    {
        $this->createProfile();

        $service = Service::query()->create([
            'title' => 'Layanan Publik',
            'description' => 'Layanan yang awalnya tampil.',
            'tags' => ['Laravel'],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->getJson('/api/site')
            ->assertOk()
            ->assertJsonCount(1, 'data.services')
            ->assertJsonPath('data.services.0.title', 'Layanan Publik');

        $this->putJson("/api/admin/services/{$service->id}", [
            'title' => $service->title,
            'description' => $service->description,
            'tags' => ['Laravel'],
            'sort_order' => 1,
            'is_active' => false,
        ])->assertOk();

        $this->getJson('/api/site')
            ->assertOk()
            ->assertJsonCount(0, 'data.services');
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
            'hero_description' => 'Deskripsi profil untuk pengujian.',
            'location' => 'Jakarta, Indonesia',
            'email' => 'profile@example.com',
        ]);
    }
}
