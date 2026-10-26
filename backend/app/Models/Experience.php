<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'period',
        'icon',
        'role',
        'company',
        'description',
        'tags',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
