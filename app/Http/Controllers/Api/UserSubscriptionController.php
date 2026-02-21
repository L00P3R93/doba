<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserSubscriptionRequest;
use App\Http\Requests\UpdateUserSubscriptionRequest;
use App\Http\Resources\UserSubscriptionResource;
use App\Models\UserSubscription;
use App\Services\UserSubscriptionService;
use Illuminate\Http\Request;

class UserSubscriptionController extends Controller
{
    protected UserSubscriptionService $userSubscriptionService;

    public function __construct(UserSubscriptionService $userSubscriptionService)
    {
        $this->userSubscriptionService = $userSubscriptionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserSubscriptionResource::collection($this->userSubscriptionService->getAllUserSubscriptions());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserSubscriptionRequest $request)
    {
        try {
            $userSubscriptions = $this->userSubscriptionService->createUserSubscription($request->validated());

            return response()->json([
                'message' => 'User Subscription created successfully',
                'data' => $userSubscriptions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create user subscription',
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
            $userSubscriptions = $this->userSubscriptionService->createUserSubscription($request->validated());

            return response()->json([
                'message' => 'User Subscription created successfully',
                'data' => $userSubscriptions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create user subscription',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserSubscriptionRequest $request, $id)
    {
        try {
            $userSubscription = $this->userSubscriptionService->getUserSubscriptionById($id);
            $updatedUserSubscription = $this->userSubscriptionService->updateUserSubscription($userSubscription, $request->validated());

            return response()->json([
                'message' => 'User Subscription updated successfully',
                'data' => $updatedUserSubscription,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update user subscription',
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
            $userSubscription = $this->userSubscriptionService->getUserSubscriptionById($id);
            $this->userSubscriptionService->deleteUserSubscription($userSubscription);

            return response()->json([
                'message' => 'User Subscription deleted successfully',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user subscription',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
