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
                        <tr>
                            <th>{{ localize('S/L') }}</th>
                            <th>{{ localize('Produits') }}</th>
                            <th>{{ localize('Prix unitaire') }}</th>
                            <th>{{ localize('Qte') }}</th>
                            <th>{{ localize('Prix total') }}</th>
                            @if (getSetting('enable_refund_system') == 1)
                                <th>{{ localize('Remboursement') }}</th>
                            @endif
                        </tr>
                       
                    </table>
                </div>
                <div class="mt-4 table-responsive">
                    <table class="table footer-table">
                        
                    </table>
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
