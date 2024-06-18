@extends('backend.layouts.master')

@section('title')
    {{ localize('Visites') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection
@section('contents')
<table style="margin: 15px;">
    <thead>
        <tr>
            <th>Page</th>
            <th>Nombre de visites</th>
        </tr>
    </thead>
    <tbody>
        @foreach($visitCounts as $routeName => $count)
            <tr>
                <td>{{ $routeName }}</td>
                <td>{{ $count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection