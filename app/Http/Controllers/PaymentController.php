<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PaymentTransition;

class PaymentController extends Controller
{
    public function paymentIssue(Request $request, Order $order)
    {
        $paymentTransition = PaymentTransition::where('order_id', $order->id)->first();

        $issuePolicyResult = json_decode((string)($order->issue_policy_result), true);
        if (!is_array($issuePolicyResult)) {
            $issuePolicyResult = [];
        }

        $message = trim((string)$request->message);
        if ($message === '') {
            $message = trim((string)($issuePolicyResult['errorMessage'] ?? ''));
        }

        if ($message === '') {
            $message = "We received your payment, but the policy could not be issued automatically.";
        }

        
        return view('payment_issue', [
            'title' => "We received your payment, but the policy could not be issued automatically.",
            'order' => $order,
            'payment' => $paymentTransition,
            'issuePolicyResult' => $issuePolicyResult,
            'message' => $message
        ]);
    }

    public function receipt(Order $order)
    {

    }

    public function createPaymentTransition() 
    {

    }

    public function inquiryKBankPaymentTransition()
    {

    }
}
