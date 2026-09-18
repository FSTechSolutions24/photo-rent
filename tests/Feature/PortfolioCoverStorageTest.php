<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureUserIsPhotographer;
use App\Models\Photographer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioCoverStorageTest extends TestCase
{
    use RefreshDatabase;

    public function test_portfolio_cover_is_stored_on_wasabi_and_replaces_the_previous_cover(): void
    {
        Storage::fake('wasabi');
        $this->withoutMiddleware(EnsureUserIsPhotographer::class);

        $user = User::factory()->create(['type' => 'photographer']);
        $photographer = Photographer::create([
            'user_id' => $user->id,
            'subdomain' => 'portfolio-cover-owner',
            'active' => true,
            'portfolio_cover_path' => 'users/' . $user->id . '/portfolio/cover/old-cover.jpg',
        ]);
        Storage::disk('wasabi')->put($photographer->portfolio_cover_path, 'old cover');

        $response = $this->actingAs($user)->put(route('dashboard.portfolio.update'), [
            'portfolio_title' => 'My Portfolio',
            'portfolio_bio' => 'A photography portfolio.',
            'portfolio_theme' => 'bold',
            'portfolio_primary_color' => '#173f67',
            'portfolio_accent_color' => '#c5965d',
            'portfolio_show_contact' => '1',
            'portfolio_cover' => UploadedFile::fake()->image('cover.jpg', 1600, 900),
        ]);

        $response->assertRedirect();

        $newPath = $photographer->fresh()->portfolio_cover_path;
        $this->assertStringStartsWith('users/' . $user->id . '/portfolio/cover/', $newPath);
        Storage::disk('wasabi')->assertExists($newPath);
        Storage::disk('wasabi')->assertMissing('users/' . $user->id . '/portfolio/cover/old-cover.jpg');
    }
}
