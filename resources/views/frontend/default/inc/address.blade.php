<address class="fs-sm mb-0">
    <strong>{{ $address->address }}</strong>
</address>

<strong> {{ localize('Ville') }}: </strong>{{ $address->city->name }}
<br>

<strong>{{ localize('État') }}: </strong>{{ $address->state->name }}

<br>
<strong>{{ localize('Pays') }}: </strong> {{ $address->country->name }}
