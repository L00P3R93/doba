<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('public');
    }

    /** @test */
    public function it_can_upload_profile_image()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('profile.jpg');

        Livewire::test(\App\Filament\Pages\EditProfilePage::class)
            ->set('data.photo', $file)
            ->call('save');

        $user->refresh();
        
        // Check that media was saved
        $this->assertNotNull($user->getFirstMedia('profiles'));
        
        // Check that profile_url was updated
        $this->assertNotNull($user->profile_url);
        $this->assertStringContains('medium', $user->profile_url);
    }

    /** @test */
    public function it_clears_old_profile_image_when_uploading_new_one()
    {
        $user = User::factory()->create();
        
        // Add initial media
        $user->addMedia(UploadedFile::fake()->image('old.jpg'))
            ->toMediaCollection('profiles');
        
        $this->actingAs($user);
        $this->assertEquals(1, $user->getMedia('profiles')->count());

        $newFile = UploadedFile::fake()->image('new.jpg');

        Livewire::test(\App\Filament\Pages\EditProfilePage::class)
            ->set('data.photo', $newFile)
            ->call('save');

        $user->refresh();
        
        // Should still have only one media item (old one cleared)
        $this->assertEquals(1, $user->getMedia('profiles')->count());
        
        // Check that profile_url was updated
        $this->assertNotNull($user->profile_url);
    }
}
