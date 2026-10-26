<?php

namespace Database\Seeders;

use App\Models\ContactDetail;
use App\Models\ContactOpportunity;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use App\Models\SkillGroup;
use App\Models\SocialLink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $portraitPath = $this->copyAsset('profile/hafiz-portrait.jpeg', 'profile/hafiz-portrait.jpeg');
        $projectImages = [
            'Matcha Muse' => $this->copyAsset('projects/matcha-muse.webp', 'projects/matcha-muse.webp'),
            'POS Kasir' => $this->copyAsset('projects/pos-kasir.webp', 'projects/pos-kasir.webp'),
            'E-Kalkulator' => $this->copyAsset('projects/e-kalkulator.webp', 'projects/e-kalkulator.webp'),
        ];

        Profile::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Muhammad Akbar Hafiz Zain',
                'short_name' => 'Hafiz',
                'role' => 'Software Engineer',
                'typed_role' => 'Backend & Full-Stack Architect',
                'hero_kicker' => 'Portofolio',
                'hero_greeting' => 'Halo, saya',
                'hero_name_line_1' => 'Muhammad Akbar',
                'hero_name_line_2' => 'Hafiz Zain',
                'hero_description' => 'Saya merancang backend yang terkontrol dan aplikasi full-stack yang cepat, terukur, serta siap dikembangkan untuk kebutuhan nyata.',
                'availability_label' => 'Terbuka untuk PKL',
                'location' => 'Jakarta, Indonesia',
                'school' => 'SMK Negeri 71 Jakarta',
                'major' => 'Rekayasa Perangkat Lunak',
                'email' => 'm.akbarhafizzain@gmail.com',
                'portrait_path' => $portraitPath,
                'hero_cta_label' => 'Hubungi Saya',
                'footer_note' => 'Dibangun dengan Vue.js & Tailwind CSS.',
            ],
        );

        $socialLinks = [
            [
                'label' => 'Email',
                'value' => 'm.akbarhafizzain@gmail.com',
                'href' => 'mailto:m.akbarhafizzain@gmail.com',
                'icon' => 'fa-solid fa-envelope',
                'aria_label' => 'Kirim email ke Muhammad Akbar Hafiz Zain',
                'description' => 'Jalur tercepat untuk peluang PKL, kolaborasi proyek, dan diskusi teknis.',
                'sort_order' => 1,
            ],
            [
                'label' => 'Instagram',
                'value' => '@imhafiiiiz',
                'href' => 'https://www.instagram.com/imhafiiiiz?igsh=NmRuOHd1cHpvem5w',
                'icon' => 'fa-brands fa-instagram',
                'aria_label' => 'Buka profil Instagram Hafiz',
                'description' => 'Lihat aktivitas, proses, dan karya terbaru yang sedang saya kerjakan.',
                'sort_order' => 2,
            ],
            [
                'label' => 'Facebook',
                'value' => 'Muhammad Akbar Hafiz Zain',
                'href' => 'https://www.facebook.com/share/1BHFi7FKd2/',
                'icon' => 'fa-brands fa-facebook-f',
                'aria_label' => 'Buka profil Facebook Hafiz',
                'description' => 'Terhubung melalui jejaring sosial untuk percakapan yang lebih santai.',
                'sort_order' => 3,
            ],
        ];

        foreach ($socialLinks as $socialLink) {
            SocialLink::firstOrCreate(
                ['label' => $socialLink['label']],
                $socialLink + ['is_active' => true],
            );
        }

        $sections = [
            [
                'key' => 'services',
                'kicker' => 'Layanan',
                'title' => 'Solusi digital dengan',
                'highlight' => 'kendali penuh.',
                'description' => 'Dari arsitektur backend hingga antarmuka pengguna, saya menyusun sistem yang rapi, aman, dan mudah dikembangkan.',
            ],
            [
                'key' => 'skills',
                'kicker' => 'Keahlian',
                'title' => 'Teknologi untuk',
                'highlight' => 'membangun sistem.',
                'description' => 'Saya bekerja dengan stack modern untuk merancang backend, basis data, dan antarmuka yang saling terhubung dengan rapi.',
                'meta' => [
                    'stats' => [
                        ['value' => '3', 'label' => 'Proyek Full-Stack'],
                        ['value' => 'XI', 'label' => 'Kelas · RPL'],
                    ],
                ],
            ],
            [
                'key' => 'experience',
                'kicker' => 'Pengalaman',
                'title' => 'Fondasi yang terus',
                'highlight' => 'bertumbuh.',
                'description' => 'Perjalanan saya dibangun dari pendidikan vokasi, fondasi bahasa, dan proyek nyata yang saya kerjakan secara mandiri.',
            ],
            [
                'key' => 'projects',
                'kicker' => 'Projek',
                'title' => 'Karya yang saya',
                'highlight' => 'bangun dari nol.',
                'description' => 'Tiga proyek utama yang menggabungkan arsitektur backend, basis data relasional, dan antarmuka yang berfokus pada pengguna.',
            ],
            [
                'key' => 'contact_hero',
                'kicker' => 'Kontak',
                'title' => 'Mari bangun sesuatu yang',
                'highlight' => 'berarti.',
                'description' => 'Terbuka untuk diskusi teknis, kolaborasi proyek, dan peluang Praktik Kerja Lapangan (PKL). Saya percaya produk yang baik lahir dari fondasi teknis yang jelas dan komunikasi yang terbuka.',
            ],
            [
                'key' => 'contact_channels',
                'kicker' => 'Saluran Kontak',
                'title' => 'Pilih jalur yang paling',
                'highlight' => 'nyaman.',
                'description' => 'Email menjadi jalur utama untuk peluang formal, sementara media sosial terbuka untuk percakapan yang lebih ringan.',
            ],
            [
                'key' => 'contact_opportunities',
                'kicker' => 'Peluang',
                'title' => 'Ruang untuk tumbuh dan',
                'highlight' => 'berkontribusi.',
                'description' => 'Saya mencari lingkungan yang menantang, menghargai kualitas kode, dan memberi ruang untuk belajar langsung dari praktik industri.',
            ],
            [
                'key' => 'contact_principle',
                'kicker' => 'Prinsip Kerja',
                'title' => '“Pegang kendali penuh atas arsitektur. AI adalah pengganda efisiensi, bukan pengganti proses berpikir.”',
                'highlight' => null,
                'description' => 'Setiap baris kode harus dipahami, diuji, dan dipertanggungjawabkan.',
            ],
        ];

        foreach ($sections as $section) {
            Section::firstOrCreate(['key' => $section['key']], $section);
        }

        $services = [
            [
                'number' => '01',
                'icon' => 'fa-solid fa-server',
                'title' => 'Rekayasa Backend',
                'description' => 'Perancangan API, autentikasi, logika bisnis, dan pengelolaan data yang aman, konsisten, serta siap dikembangkan.',
                'tags' => ['Laravel', 'Node.js', 'Golang'],
                'sort_order' => 1,
            ],
            [
                'number' => '02',
                'icon' => 'fa-solid fa-layer-group',
                'title' => 'Pengembangan Full-Stack',
                'description' => 'Pengembangan aplikasi web end-to-end, mulai dari struktur data dan API hingga antarmuka yang responsif dan mudah digunakan.',
                'tags' => ['Next.js', 'Nuxt', 'Tailwind CSS'],
                'sort_order' => 2,
            ],
            [
                'number' => '03',
                'icon' => 'fa-solid fa-diagram-project',
                'title' => 'Desain Sistem & Basis Data',
                'description' => 'Pemodelan alur sistem, ERD, dan skema relasional agar fondasi aplikasi tetap terukur dan mudah dirawat.',
                'tags' => ['draw.io', 'Prisma', 'MySQL'],
                'sort_order' => 3,
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['title' => $service['title']],
                $service + ['is_active' => true],
            );
        }

        $skillGroups = [
            [
                'icon' => 'fa-solid fa-code',
                'title' => 'Frontend & Full-Stack',
                'items' => ['Next.js', 'React', 'Astro.js', 'Nuxt', 'Tailwind CSS'],
                'sort_order' => 1,
            ],
            [
                'icon' => 'fa-solid fa-database',
                'title' => 'Backend & Basis Data',
                'items' => ['Golang', 'Node.js', 'Laravel', 'Express', 'PHP', 'Prisma', 'MySQL', 'MariaDB', 'PostgreSQL'],
                'sort_order' => 2,
            ],
            [
                'icon' => 'fa-solid fa-pen-ruler',
                'title' => 'Desain Sistem & Alat Bantu',
                'items' => ['draw.io', 'Figma', 'Git & GitHub', 'VS Code', 'Eclipse', 'Antigravity'],
                'sort_order' => 3,
            ],
        ];

        foreach ($skillGroups as $skillGroup) {
            SkillGroup::firstOrCreate(
                ['title' => $skillGroup['title']],
                $skillGroup + ['is_active' => true],
            );
        }

        $experiences = [
            [
                'period' => 'Proyek Mandiri',
                'icon' => 'fa-solid fa-code',
                'role' => 'Pengembang Full-Stack Mandiri',
                'company' => 'Jakarta, Indonesia',
                'description' => 'Membangun tiga proyek utama dari perancangan basis data, API, hingga antarmuka pengguna dengan fokus pada kontrol arsitektur dan kualitas kode.',
                'tags' => ['Nuxt', 'Laravel', 'MySQL', 'Tailwind CSS'],
                'sort_order' => 1,
            ],
            [
                'period' => 'Kelas XI · Sekarang',
                'icon' => 'fa-solid fa-graduation-cap',
                'role' => 'Rekayasa Perangkat Lunak',
                'company' => 'SMK Negeri 71 Jakarta',
                'description' => 'Menempuh pendidikan vokasi dengan fokus pada Pemrograman Berorientasi Objek, struktur data, dan rekayasa sistem perangkat lunak.',
                'tags' => ['RPL', 'OOP', 'Struktur Data'],
                'sort_order' => 2,
            ],
            [
                'period' => 'Fondasi Akademik',
                'icon' => 'fa-solid fa-language',
                'role' => 'Lulusan SMP Bilingual',
                'company' => 'Jakarta, Indonesia',
                'description' => 'Menguasai bahasa Inggris teknis untuk menyerap dokumentasi resmi, melakukan riset mandiri, dan mempelajari literatur teknologi global tanpa hambatan bahasa.',
                'tags' => ['Bahasa Inggris Teknis', 'Riset Mandiri'],
                'sort_order' => 3,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::firstOrCreate(
                ['role' => $experience['role']],
                $experience + ['is_active' => true],
            );
        }

        $projects = [
            [
                'title' => 'Matcha Muse',
                'category' => 'Toko Online Premium',
                'description' => 'Platform toko online matcha premium dengan katalog, kustomisasi pesanan, keranjang, pembayaran, dan panel administrasi dalam satu ekosistem.',
                'image_path' => $projectImages['Matcha Muse'],
                'image_alt' => 'Tangkapan layar beranda Matcha Muse dengan foto minuman matcha',
                'width' => 1280,
                'height' => 636,
                'tags' => ['Nuxt 4', 'Laravel 11', 'Tailwind CSS', 'MySQL'],
                'url' => 'https://example.com/matcha-muse',
                'sort_order' => 1,
            ],
            [
                'title' => 'POS Kasir',
                'category' => 'Sistem Kasir',
                'description' => 'Aplikasi kasir berbasis API untuk mengelola barang, transaksi, stok, dan riwayat penjualan secara konsisten dan langsung.',
                'image_path' => $projectImages['POS Kasir'],
                'image_alt' => 'Tangkapan layar halaman kasir POS Kasir dengan daftar barang dan keranjang',
                'width' => 1440,
                'height' => 624,
                'tags' => ['Nuxt 3', 'Laravel', 'Tailwind CSS', 'MySQL'],
                'url' => 'https://example.com/pos-kasir',
                'sort_order' => 2,
            ],
            [
                'title' => 'E-Kalkulator',
                'category' => 'Aplikasi Web',
                'description' => 'Kalkulator digital 12 digit dengan operasi dasar, persentase, memori independen, dan fitur markup untuk kebutuhan kasir.',
                'image_path' => $projectImages['E-Kalkulator'],
                'image_alt' => 'Tangkapan layar E-Kalkulator dengan tombol angka dan memori',
                'width' => 1280,
                'height' => 633,
                'tags' => ['HTML', 'CSS', 'JavaScript'],
                'url' => 'https://example.com/e-kalkulator',
                'sort_order' => 3,
            ],
        ];

        foreach ($projects as $project) {
            Project::firstOrCreate(
                ['title' => $project['title']],
                $project + ['is_active' => true],
            );
        }

        $contactDetails = [
            ['label' => 'Domisili', 'value' => 'Jakarta, Indonesia', 'icon' => 'fa-solid fa-location-dot', 'sort_order' => 1],
            ['label' => 'Status', 'value' => 'Siswa aktif kelas XI · SMK Negeri 71 Jakarta', 'icon' => 'fa-solid fa-graduation-cap', 'sort_order' => 2],
            ['label' => 'Fokus', 'value' => 'Backend, Full-Stack, dan Arsitektur Sistem', 'icon' => 'fa-solid fa-layer-group', 'sort_order' => 3],
            ['label' => 'Target', 'value' => 'PKL di software house modern', 'icon' => 'fa-solid fa-bullseye', 'sort_order' => 4],
        ];

        foreach ($contactDetails as $detail) {
            ContactDetail::firstOrCreate(['label' => $detail['label']], $detail);
        }

        $opportunities = [
            [
                'icon' => 'fa-solid fa-briefcase',
                'title' => 'Praktik Kerja Lapangan',
                'description' => 'Menargetkan software house modern dengan stack Next.js dan Laravel untuk membuka peluang rekrutmen langsung atau beasiswa industri.',
                'sort_order' => 1,
            ],
            [
                'icon' => 'fa-solid fa-people-group',
                'title' => 'Kolaborasi Proyek',
                'description' => 'Pengembangan aplikasi full-stack, mulai dari perancangan basis data, API, hingga antarmuka yang siap digunakan.',
                'sort_order' => 2,
            ],
            [
                'icon' => 'fa-solid fa-comments',
                'title' => 'Diskusi Teknis',
                'description' => 'Membahas backend, basis data, system design, atau bertukar ide seputar teknologi dan pengembangan perangkat lunak.',
                'sort_order' => 3,
            ],
        ];

        foreach ($opportunities as $opportunity) {
            ContactOpportunity::firstOrCreate(['title' => $opportunity['title']], $opportunity);
        }
    }

    private function copyAsset(string $source, string $destination): string
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($destination)) {
            $disk->put($destination, file_get_contents(database_path('seeders/assets/'.$source)));
        }

        return $destination;
    }
}
