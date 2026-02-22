<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mpesa\Init as Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StkSubscribeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $user = User::query()->where('account_no', $request->account_no)->firstOrFail();
            if (! $user) {
                return response()->json(['error' => 'Customer not found'], 404);
            }
            $user_params = [
                'Amount' => intval($request->amount),
                'AccountReference' => "DP-{$user->account_no}-{$request->subscription_id}",
                'PartyA' => $user->phone_no,
                'PhoneNumber' => $user->phone_no,
            ];
            $response_json = Mpesa::stkPush($user_params);
            Log::channel('mpesa')->info('MPESA StkPush Subscribe Response: '.$response_json);

            return response()->json(['message' => 'success'], 201);
        } catch (\Exception $e) {
            Log::channel('mpesa')->error("StkPush Subscribe Error: {$e->getMessage()}");

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
