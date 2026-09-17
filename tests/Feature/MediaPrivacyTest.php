<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Photographer;
use App\Models\User;
use App\Http\Middleware\EnsureUserIsPhotographer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class MediaPrivacyTest extends TestCase
{
    use RefreshDatabase;

    public function test_photographer_can_mark_media_as_private(): void
    {
        $this->withoutMiddleware(EnsureUserIsPhotographer::class);
        [$user, $gallery, $folder] = $this->galleryForPhotographer('privacy-owner');
        $media = $this->media($gallery, $folder, false);

        $response = $this->actingAs($user)->patchJson(
            route('dashboard.media.privacy', $gallery),
            ['id' => $media->id, 'private' => true]
        );

        $response->assertOk()->assertJson(['success' => true, 'private' => true]);
        $this->assertTrue($media->fresh()->private);
    }

    public function test_photographer_cannot_change_media_from_another_gallery(): void
    {
        $this->withoutMiddleware(EnsureUserIsPhotographer::class);
        [$user, $gallery] = $this->galleryForPhotographer('privacy-owner');
        [, $otherGallery, $otherFolder] = $this->galleryForPhotographer('privacy-other');
        $otherMedia = $this->media($otherGallery, $otherFolder, false);

        $this->actingAs($user)->patchJson(
            route('dashboard.media.privacy', $gallery),
            ['id' => $otherMedia->id, 'private' => true]
        )->assertNotFound();

        $this->assertFalse($otherMedia->fresh()->private);
    }

    public function test_guest_visibility_scope_excludes_private_media(): void
    {
        [, $gallery, $folder] = $this->galleryForPhotographer('privacy-scope');
        $publicMedia = $this->media($gallery, $folder, false);
        $this->media($gallery, $folder, true);

        $this->assertSame(
            [$publicMedia->id],
            Media::visibleToGuests()->pluck('id')->all()
        );
    }

    private function galleryForPhotographer(string $subdomain): array
    {
        $user = User::factory()->create();
        $photographer = Photographer::create([
            'user_id' => $user->id,
            'subdomain' => $subdomain,
            'active' => true,
        ]);
        $gallery = Gallery::create([
            'photographer_id' => $photographer->id,
            'name' => 'Privacy gallery',
            'slug' => $subdomain,
            'client_password' => Crypt::encryptString('client-password'),
            'guest_password' => Crypt::encryptString('guest-password'),
            'is_public' => false,
        ]);
        $folder = Folder::create([
            'gallery_id' => $gallery->id,
            'name' => 'Main folder',
        ]);

        return [$user, $gallery, $folder];
    }

    private function media(Gallery $gallery, Folder $folder, bool $private): Media
    {
        return Media::create([
            'gallery_id' => $gallery->id,
            'folder_id' => $folder->id,
            'path' => 'media/'.uniqid('', true).'.jpg',
            'name' => 'photo.jpg',
            'disk' => 'wasabi',
            'size' => 100,
            'private' => $private,
        ]);
    }
}
