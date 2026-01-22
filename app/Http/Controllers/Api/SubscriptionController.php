<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Services\SubscriptionService;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SubscriptionResource::collection($this->subscriptionService->getAllSubscriptions());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request)
    {
        try {
            $subscription = $this->subscriptionService->createSubscription($request->validated());

            return response()->json([
                'message' => 'Subscription created successfully',
                'data' => new SubscriptionResource($subscription),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create subscription',
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
            $subscription = $this->subscriptionService->getSubscriptionById($id);

            return response()->json([
                'data' => new SubscriptionResource($subscription),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Subscription not found',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionRequest $request, $id)
    {
        try {
            $subscription = $this->subscriptionService->getSubscriptionById($id);
            $updatedSubscription = $this->subscriptionService->updateSubscription($subscription, $request->validated());

            return response()->json([
                'message' => 'Subscription updated successfully',
                'data' => new SubscriptionResource($updatedSubscription),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update subscription',
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
            $subscription = $this->subscriptionService->getSubscriptionById($id);
            $this->subscriptionService->deleteSubscription($subscription);

            return response()->json([
                'message' => 'Subscription deleted successfully',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete subscription',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
