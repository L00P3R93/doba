<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerSubscriptionRequest;
use App\Http\Requests\UpdateCustomerSubscriptionRequest;
use App\Http\Resources\CustomerSubscriptionResource;
use App\Services\CustomerSubscriptionService;

class CustomerSubscriptionController extends Controller
{
    protected CustomerSubscriptionService $customerSubscriptionService;

    public function __construct(CustomerSubscriptionService $customerSubscriptionService)
    {
        $this->customerSubscriptionService = $customerSubscriptionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CustomerSubscriptionResource::collection($this->customerSubscriptionService->getAllCustomerSubscriptions());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerSubscriptionRequest $request)
    {
        try {
            $customerSubscriptions = $this->customerSubscriptionService->createCustomerSubscription($request->validated());

            return response()->json([
                'message' => 'Customer Subscription created successfully',
                'data' => $customerSubscriptions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create customer subscription',
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
            $customerSubscription = $this->customerSubscriptionService->getCustomerSubscriptionById($id);

            return response()->json([
                'data' => $customerSubscription,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Customer Subscription not found',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerSubscriptionRequest $request, $id)
    {
        try {
            $customerSubscription = $this->customerSubscriptionService->getCustomerSubscriptionById($id);
            $updatedCustomerSubscription = $this->customerSubscriptionService->updateCustomerSubscription($customerSubscription, $request->validated());

            return response()->json([
                'message' => 'Customer Subscription updated successfully',
                'data' => $updatedCustomerSubscription,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update customer subscription',
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
            $customerSubscription = $this->customerSubscriptionService->getCustomerSubscriptionById($id);
            $this->customerSubscriptionService->deleteCustomerSubscription($customerSubscription);

            return response()->json([
                'message' => 'Customer Subscription deleted successfully',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete customer subscription',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
