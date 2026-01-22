<?php

namespace App\Services;

use App\Models\Profile;
use Illuminate\Support\Facades\Log;

class ProfileService
{
    public function getAllProfiles(): \Illuminate\Database\Eloquent\Collection
    {
        return Profile::all();
    }

    /**
     * @throws \Exception
     */
    public function createProfile(array $data): Profile
    {
        try {
            return Profile::query()->create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create profile: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function getProfileById($id): ?Profile
    {
        try {
            return Profile::query()->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Profile Not Found: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function updateProfile(Profile $profile, array $data): Profile
    {
        try {
            $profile->update($data);
            return $profile->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update profile: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteProfile(Profile $profile): bool
    {
        try {
            $profile->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete profile: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Get profile by customer ID
     * @throws \Exception
     */
    public function getProfileByCustomerId(int $customerId): ?Profile
    {
        try {
            return Profile::query()->where('customer_id', $customerId)->first();
        } catch (\Exception $e) {
            Log::error('Failed to get profile by customer ID: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Create or update profile for a customer
     * @throws \Exception
     */
    public function createOrUpdateProfileForCustomer(int $customerId, array $data): Profile
    {
        try {
            return Profile::query()->updateOrCreate(
                ['customer_id' => $customerId],
                $data
            );
        } catch (\Exception $e) {
            Log::error('Failed to create or update profile for customer: '.$e->getMessage());
            throw $e;
        }
    }

}
