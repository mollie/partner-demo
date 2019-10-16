<?php

namespace App\Http\Controllers\Settings\Payment;

use App\Http\Controllers\Controller;
use App\Services\Mollie\AuthorizeService;

class IndexController extends Controller
{
    /** @var AuthorizeService */
    private $service;

    public function __construct(AuthorizeService $service)
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        $this->service->getAuthorizationLink();

        return view('settings.payment.index');
    }
}
