<?php
/** @var \App\PaymentProfile[] $profiles */
/** @var \App\PaymentMethod[] $methods */
?>
@if(!empty($methodsEnabled) || !empty($methodsDisabled))
    <div class="payment-methods">
        @if(!empty($methodsEnabled))
            <h4 class="font-weight-bold ml-4">Active Payment Methods</h4>
            <ul>
                @foreach($methodsEnabled as $method)
                    <li>
                        <div>
                            <span class="status status-green"></span>
                            {{ $method->getName() }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
        @if(!empty($methodsDisabled))
            <h4 class="font-weight-bold ml-4">Inactive Payment Methods</h4>
            <ul>
                @foreach($methodsDisabled as $method)
                    <li>
                        <div>
                            <span class="status status-red"></span>
                            {{ $method->getName() }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
