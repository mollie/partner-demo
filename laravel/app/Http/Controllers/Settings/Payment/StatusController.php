<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings\Payment;

use App\Services\Mollie\StatusService;
use Illuminate\Support\Facades\Auth;

class StatusController
{
    /** @var StatusService */
    private $service;

    public function __construct(StatusService $service)
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        $user = Auth::user();

        $status = $this->service->status($user);

        return view('settings.payment.status');
    }
}
