<?php
/** @var \App\PaymentProfile[] $profiles */
/** @var \App\PaymentMethod[] $methods */
?>

@if(!empty($methods))
    <div class="payment-methods">
        <h4 class="font-weight-bold ml-4">Active Payment Methods</h4>
        <ul>
            @foreach($methods as $method)
                <li>
                    <div>
                        <span class="status status-green"></span>
                        {{ $method->getName() }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
