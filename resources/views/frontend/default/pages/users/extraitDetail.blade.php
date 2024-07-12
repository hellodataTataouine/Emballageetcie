@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Détail de la Transaction') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="invoice-section pt-6 pb-120">
        <div class="container">
            <div class="invoice-box bg-white rounded p-4 p-sm-6">
                <div class="row g-5 justify-content-between">
                    <div class="col-lg-6">
                        <div class="invoice-title d-flex align-items-center">
                            <h3>{{ localize('Détail de la Transaction') }}</h3>
                            <!-- You can add more details here if needed -->
                        </div>
                        <div class="col-lg-5 col-md-8">
                            <div class="text-lg-end">
                                <a href="{{ route('home') }}"><img src="{{ uploadedAsset(getSetting('navbar_logo')) }}"
                                        alt="logo" class="img-fluid"></a>
                                <h6 class="mb-0 text-gray mt-4">{{ getSetting('site_address') }}</h6>
                            </div>
                        </div>
                        <table class="invoice-table-sm">
                            <!-- Add transaction-specific details here -->
                        </table>
                    </div>
                    <div class="col-lg-5 col-md-8">
                        <div class="text-lg-end">
                            <!-- Add any other details you want to display -->
                        </div>
                    </div>
                </div>
                <span class="my-6 w-100 d-block border-top"></span>
                <div class="table-responsive mt-6">
                    <table class="table invoice-table">
                    <thead>
            <tr>
                <th>{{ localize('S/L') }}</th>
                <th>{{ localize('Produits') }}</th>
                <th>{{ localize('Qte') }}</th>
               
                <th>{{ localize('Prix HT') }}</th>
                <th>{{ localize('Prix TVA') }}</th>
                <th>{{ localize('Prix TTC') }}</th>
                @if (getSetting('enable_refund_system') == 1)
                    <th>{{ localize('Remboursement') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php $index = 1; @endphp
            @foreach($extraitsDetails as $detail)
                <tr>
                    <td>{{ $index++ }}</td>
                    <td>{{ $detail['LibProd'] }}</td>
                    <td>{{ $detail['Quantité'] }}</td>
                   
                    <td>{{ $detail['TotaleHT'] }}</td>
                    <td>{{ $detail['totaletva'] }}</td>
                    <td>{{ $detail['PRIX_details'] }}</td>
                    @if (getSetting('enable_refund_system') == 1)
                        <td>{{ $detail['Remboursement'] }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>


                </div>
                  <div class="text-end">
                            <div class="card d-inline-block p-3 mb-4">
                                <div class="font-weight-bold">
                                    <p class="mb-1">{{ localize('Total HT: ') }} <span class="text-success">{{ $totalHT }}</span></p>
                                    <p class="mb-1">{{ localize('Total TVA: ') }} <span class="text-success">{{ $totalTVA }}</span></p>
                                    <p>{{ localize('Total TTC: ') }} <span class="text-success">{{ $totalTTC }}</span></p>
                                </div>
                            </div>
                        </div> 
                <div class="mt-4 table-responsive">
                 
                    <a href="{{ route('client.orders.downloadInvoice', $iddoc) }}" class="view-transaction fs-xs" data-bs-toggle="tooltip"
       data-bs-placement="top" data-bs-title="{{ localize('Télécharger la facture') }}">
       <i class="fas fa-download" width="18"></i>Télécharger la Facture
    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Add any other sections or modals you need -->
    <!--refund modal-->
    <div class="modal fade refundModal" id="refundModal">
        <!-- Modal content for refund goes here -->
    </div>
    <!--rejection modal-->
    @include('frontend.default.pages.checkout.inc.rejectionModal')
@endsection

@section('scripts')
    <script>
        "use strict";

        // Add any additional scripts or functions you need
    </script>
@endsection
