<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) config('admin.email');
        $password = (string) config('admin.password');

        if ($email === '' || $password === '') {
            throw new RuntimeException('ADMIN_EMAIL dan ADMIN_PASSWORD wajib diisi sebelum seeding.');
        }

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => (string) config('admin.name'),
                'password' => Hash::make($password),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        );

        User::query()
            ->where('id', '!=', $admin->id)
            ->update(['is_admin' => false]);
    }
}
