<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name', 50);
            $table->string('role');
            $table->string('typed_role');
            $table->string('hero_kicker')->default('Portofolio');
            $table->string('hero_greeting')->default('Halo, saya');
            $table->string('hero_name_line_1');
            $table->string('hero_name_line_2')->nullable();
            $table->text('hero_description');
            $table->string('availability_label')->default('Terbuka untuk PKL');
            $table->string('location');
            $table->string('school')->nullable();
            $table->string('major')->nullable();
            $table->string('email');
            $table->string('portrait_path')->nullable();
            $table->string('hero_cta_label')->default('Hubungi Saya');
            $table->string('footer_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
