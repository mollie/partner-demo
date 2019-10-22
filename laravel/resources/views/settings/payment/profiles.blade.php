<hr style="margin: 40px 0">
<div class="form-group">
    <label for="profile">Web Profile</label>

    <?php /** @var \App\PaymentProfile[] $profiles */ ?>
    <select class="form-control" name="profile" id="profile">
        @foreach($profiles as $profile)
            <option value="{{ $profile->getId() }}">{{ $profile->getName() }} - {{ $profile->getWebsite() }}</option>
        @endforeach
    </select>
</div>

<br>

<div>
    <label for="profile">Payment methods</label>

    <?php /** @var \App\PaymentMethod[] $methods */ ?>
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
