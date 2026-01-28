<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PaymentResource::collection($this->paymentService->getAllPayments());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Map request data to Deposit
            $depositData = [
                'transaction_id' => $request->input('TransID'),
                'type' => $request->input('TransactionType'),
                'transaction_time' => date('Y-m-d H:i:s', strtotime($request->input('TransTime'))),
                'amount' => $request->input('TransAmount'),
                'short_code' => $request->input('BusinessShortCode'),
                'bill_ref_no' => $request->input('BillRefNumber'),
                'msisdn' => $request->input('MSISDN'),
                'name' => trim(
                    $request->input('FirstName').' '.
                    $request->input('MiddleName').' '.
                    $request->input('LastName')
                ),
            ];

            $this->paymentService->createPayment($depositData);

            return response()->json([
                'ResultCode' => '0',
                'ResultDesc' => 'Accepted',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'ResultCode' => 'C2B00016',
                'ResultDesc' => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $payment = $this->paymentService->getPaymentById($id);

            return response()->json([
                'data' => new PaymentResource($payment),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, $id)
    {
        try {
            $payment = $this->paymentService->getPaymentById($id);
            $this->paymentService->updatePayment($payment, $request->all());

            return response()->json([
                'message' => 'Payment updated successfully',
                'data' => new PaymentResource($payment),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment not found',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $payment = $this->paymentService->getPaymentById($id);
            $this->paymentService->deletePayment($payment);

            return response()->json([
                'message' => 'Payment deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment not found',
            ], 404);
        }
    }
}
