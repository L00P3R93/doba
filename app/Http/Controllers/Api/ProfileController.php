<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return ProfileResource::collection($this->profileService->getAllProfiles());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to get profiles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileRequest $request)
    {
        try {
            $profile = $this->profileService->createProfile($request->validated());

            return response()->json([
                'message' => 'Profile created successfully',
                'data' => new ProfileResource($profile),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $profile = $this->profileService->getProfileById($id);
            if (! $profile) {
                return response()->json([
                    'message' => 'Profile not found',
                ], 404);
            }

            return response()->json([
                'data' => new ProfileResource($profile),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to get profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, $id)
    {
        try {
            $profile = $this->profileService->getProfileById($id);
            $updateProfile = $this->profileService->updateProfile($profile, $request->validated());

            return response()->json([
                'message' => 'Profile updated successfully',
                'data' => new ProfileResource($updateProfile),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $profile = $this->profileService->getProfileById($id);
            $this->profileService->deleteProfile($profile);

            return response()->json([
                'message' => 'Profile deleted successfully',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
