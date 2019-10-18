<?php

namespace App\Http\Controllers\Settings\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        return view('settings.payment.index');
    }
}
