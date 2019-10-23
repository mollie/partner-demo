<?php
/** @var \App\PaymentProfile[] $profiles */
/** @var \App\PaymentMethod[] $methods */
$methodsAvailable = [];
$methodsUnavailable = [];
foreach ($methods as $method) {
    if ($method->isActive()) {
        array_push($methodsAvailable, $method);
    } else {
        array_push($methodsUnavailable, $method);
    }
}
?>

@if(!empty($methodsAvailable) || !empty($methodsUnavailable))
    <div class="payment-methods">
        @if(!empty($methodsAvailable))
            <h4 class="font-weight-bold ml-4">Active Payment Methods</h4>
            <ul>
                @foreach($methodsAvailable as $method)
                    <li>
                        <div>
                            <span class="status status-green"></span>
                            {{ $method->getName() }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
        @if(!empty($methodsUnavailable))
            <h4 class="font-weight-bold ml-4">Inactive Payment Methods</h4>
            <ul>
                @foreach($methodsUnavailable as $method)
                    <li>
                        <div>
                            <span class="status status-red"></span>
                            {{ $method->getName() }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
        @endif
    </div>