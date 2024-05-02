<address class="fs-sm mb-0">
    <strong>{{ $address->address }}</strong>
</address>

<strong>{{ localize('Ville') }}: </strong>
@if ($address->city)
    {{ $address->city }}
@else
    
@endif
<br>

<strong>{{ localize('Code Postal') }}: </strong>
@if ($address->codepostal)
    {{ $address->codepostal }}
@else
    
@endif
<br>

<strong>{{ localize('Pays') }}: </strong>
@if ($address->country)
    {{ $address->country->name }}
@else
    
@endif
