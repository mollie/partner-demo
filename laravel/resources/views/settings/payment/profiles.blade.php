<?php
/** @var \App\PaymentProfile[] $profiles */
/** @var \App\PaymentMethod[] $methods */
?>
<hr style="margin: 40px 0">
<div class="form-group">
    <label for="profile">Web Profile</label>

    <form action="{{ route('payment_status') }}">
        <select class="form-control" name="profile" id="profile" onchange="this.form.submit()">
            @foreach($profiles as $profile)
                <option value="{{ $profile->getId() }}" {{ $profile === $selected ? 'selected' : '' }}>
                    {{ $profile->getName() }} - {{ $profile->getWebsite() }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<br>

<div>
    <label for="profile">Payment methods</label>

    <ul>
        @foreach($methods as $method)
            <li>
                <img src="{{ $method->getImage() }}" alt="" height="25px">
                {{ $method->getName() }}
                <input type="checkbox" {{ $method->isActive() ? 'checked' : '' }}>
            </li>
        @endforeach
    </ul>
</div>
