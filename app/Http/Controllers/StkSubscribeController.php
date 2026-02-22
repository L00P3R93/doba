<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mpesa\Init as Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StkSubscribeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        try {
            $user = User::query()->where('account_no', $request->account_no)->first();
            if (! $user) {
                return redirect()->back()->with('error', 'User Not Found');
            }
            $user_params = [
                'Amount' => intval($request->amount),
                'AccountReference' => "DP-{$user->account_no}-{$request->subscription_id}",
                'PartyA' => $user->phone,
                'PhoneNumber' => $user->phone,
            ];
            $response_json = Mpesa::stkPush($user_params);
            Log::channel('mpesa')->info("MPESA StkPush Subscribe Response: {$response_json}", ["request" => $user_params]);

            return redirect()->back()->with('success', 'STK push initiated successfully. Please check your phone to complete the payment.');
        } catch (\Exception $e) {
            Log::channel('mpesa')->error("StkPush Subscribe Error: {$e->getMessage()}");

            return redirect()->back()->with('error', 'Payment initiation failed: '.$e->getMessage());
        }
    }
}
