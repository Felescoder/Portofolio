<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'role',
        'typed_role',
        'hero_kicker',
        'hero_greeting',
        'hero_name_line_1',
        'hero_name_line_2',
        'hero_description',
        'availability_label',
        'location',
        'school',
        'major',
        'email',
        'portrait_path',
        'hero_cta_label',
        'footer_note',
    ];
}
