<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactDetailController;
use App\Http\Controllers\Admin\ContactOpportunityController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SkillGroupController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/site', SiteController::class);

Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/dashboard', DashboardController::class);

        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/profile', [ProfileController::class, 'update']);

        Route::get('/sections/{key}', [SectionController::class, 'show']);
        Route::put('/sections/{key}', [SectionController::class, 'update']);

        Route::apiResource('services', ServiceController::class)->except(['show']);

        Route::apiResource('skill-groups', SkillGroupController::class)
            ->parameters(['skill-groups' => 'skillGroup'])
            ->except(['show']);

        Route::apiResource('experiences', ExperienceController::class)->except(['show']);

        Route::apiResource('projects', ProjectController::class)->except(['show', 'update']);
        Route::match(['put', 'patch', 'post'], '/projects/{project}', [ProjectController::class, 'update']);

        Route::apiResource('social-links', SocialLinkController::class)
            ->parameters(['social-links' => 'socialLink'])
            ->except(['show']);

        Route::apiResource('contact-details', ContactDetailController::class)
            ->parameters(['contact-details' => 'contactDetail'])
            ->except(['show']);

        Route::apiResource('contact-opportunities', ContactOpportunityController::class)
            ->parameters(['contact-opportunities' => 'contactOpportunity'])
            ->except(['show']);
    });
});
