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
                                        <th>{{ localize('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Add your loop to display account statement data -->
                                    <tr>
                                        <td>{{ date('d M, Y') }}</td>
                                        <td>Transaction 1</td>
                                        <td>100.00</td>
                                        <td>0.00</td> <!-- Add the credit value here -->
                                        <td>500.00</td>
                                        <td>
    <a href="{{ route('customers.extraitDetail') }}" class="view-transaction fs-xs" data-bs-toggle="tooltip"
        data-bs-placement="top" data-bs-title="{{ localize('Voir les détails') }}">
        <i class="fas fa-eye"></i>
    </a>
</td>
                                    </tr>
                                    <!-- Add more rows for other transactions -->
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end">
                            <div class="card d-inline-block p-3 mb-4">
                                <div class="font-weight-bold">
                                    <p class="mb-1">{{ localize('Total Debit: ') }} <span class="text-success">500.00</span></p>
                                    <p>{{ localize('Total Credit: ') }} <span class="text-danger">0.00</span></p>
                                </div>
                            </div>
                        </div>

                        <style>
                            .text-start {
                                text-align: start; 
                            }
                        </style>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
