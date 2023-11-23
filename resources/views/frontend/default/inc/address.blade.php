<address class="fs-sm mb-0">
    <strong>{{ $address->address }}</strong>
</address>

<strong>{{ localize('Ville') }}: </strong>
@if ($address->city)
    {{ $address->city->name }}
@else
    
@endif
<br>

<strong>{{ localize('Région') }}: </strong>
@if ($address->state)
    {{ $address->state->name }}
@else
    
@endif
<br>

<strong>{{ localize('Pays') }}: </strong>
@if ($address->country)
    {{ $address->country->name }}
@else
    
@endif
