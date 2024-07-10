@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Extrait de Compte') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="my-account pt-6 pb-120">
        <div class="container">

            @include('frontend.default.pages.users.partials.customerHero')

            <div class="row g-4">
                <div class="col-xl-3">
                    @include('frontend.default.pages.users.partials.customerSidebar')
                </div>

                <div class="col-xl-9">
                    <div class="account-statement bg-white rounded py-5 px-4">
                        <h6 class="mb-4">{{ localize('Extrait de Compte') }}</h6>
                        <div class="table-responsive">
                            <table class="account-statement-table table">
                                <thead>
                                    <tr>
                                        <th>{{ localize('Date') }}</th>
                                        <th>{{ localize('Designation') }}</th>
                                        <th>{{ localize('Debit') }}</th>
                                        <th>{{ localize('Credit') }}</th>
                                        <th>{{ localize('Solde') }}</th>
                                        <th>{{ localize('Statut') }}</th>
                                        
                                        <th>{{ localize('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($extraits as $extrait)
                                        <tr>
                                            <td>{{ date('d M, Y', strtotime($extrait['Date'])) }}</td>
                                            <td>{{ $extrait['Designation'] }}</td>
                                            <td>{{ $extrait['Debit'] }}</td>
                                            <td>{{ $extrait['Credit'] }}</td>
                                            <td>{{ $extrait['Solde'] }}</td>
                                           @if($extrait['Payee'] == 1)
                                            <td>Payée </td>
                                            @else
                                            <td>En attente de paiement</td>
                                            @endif
                                            <td>
    <a href="{{ route('customers.extraitDetail', ['iddoc' => $extrait['Iddoc']]) }}" class="view-transaction fs-xs" data-bs-toggle="tooltip"
       data-bs-placement="top" data-bs-title="{{ localize('Voir les détails') }}">
       <i class="fas fa-eye"></i>
    </a>

    <a href="{{ route('client.orders.downloadInvoice', $extrait['Iddoc']) }}" class="view-transaction fs-xs" data-bs-toggle="tooltip"
       data-bs-placement="top" data-bs-title="{{ localize('Télécharger la facture') }}">
       <i class="fas fa-download" width="18"></i>
    </a>
</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="text-end">
                            <div class="card d-inline-block p-3 mb-4">
                                <div class="font-weight-bold">
                                    <p class="mb-1">{{ localize('Total Debit: ') }} <span class="text-success">{{ $totalDebit }}</span></p>
                                    <p>{{ localize('Total Credit: ') }} <span class="text-danger">{{ $totalCredit }}</span></p>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
