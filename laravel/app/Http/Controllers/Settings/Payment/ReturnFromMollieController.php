<?php

namespace App\Http\Controllers\Settings\Payment;

use Illuminate\Http\Request;

class ReturnFromMollieController
{
    public function __invoke(Request $request)
    {
        if ($request->has('code')) {
            return redirect(route('oauth_confirm', $request->get('code')));
        }

        if ($request->has('error')) {
            return redirect(route('oauth_error', $request->get('error')));
        }

        return redirect(route('payment_status'));
    }
}