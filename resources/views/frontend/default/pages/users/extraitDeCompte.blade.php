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
                        <div >
                            <button id="download-selected" class="btn btn-primary" disabled>
                                {{ localize('Télécharger les sélectionnés') }}
                            </button>
                        </div>
                        <br>


                        <div class="table-responsive">
                            <table class="account-statement-table table">
                                <thead>
                                    <tr>
                                        <th>{{ localize('Sélectionner') }}</th> <!-- New column for checkboxes -->
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
                                            <td>
                                                <input type="checkbox" name="selected_extraits[]" value="{{ $extrait['Iddoc'] }}">
                                            </td>
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

                                                <a href="{{ route('client.orders.downloadInvoice', $extrait['Iddoc']) }}" class="download-invoice fs-xs" data-bs-toggle="tooltip"
                                                   data-bs-placement="top" data-bs-title="{{ localize('Télécharger la facture') }}">
                                                   <i class="fas fa-download" width="18"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle checkbox changes
            $('input[name="selected_extraits[]"]').change(function() {
                var anyChecked = $('input[name="selected_extraits[]"]:checked').length > 0;
                // Example: Enable/disable a button based on selection
                $('#download-selected').prop('disabled', !anyChecked);
            });

            // Handle click event for Download Selected button
            $('#download-selected').click(function(e) {
        e.preventDefault();
        var selectedIds = $('input[name="selected_extraits[]"]:checked').map(function() {
            return $(this).val();
        }).get();
        // Construct download URL using route helper
        var downloadUrl = "{{ route('client.orders.downloadSelectedInvoices') }}?ids=" + selectedIds.join(',');
        window.location.href = downloadUrl;
    });
        });
        $(document).ready(function(){
            function updateButtonState() {
                const checkboxes = document.querySelectorAll('input[name="selected_extraits[]"]');
                const button = document.getElementById('download-selected');
                let isChecked = false;

                // Check if at least one checkbox is checked
                checkboxes.forEach(function(checkbox) {
                    if (checkbox.checked) {
                        isChecked = true;
                    }
                });

                // Enable or disable the button
                button.disabled = !isChecked;
            }

            // Add event listeners to checkboxes
            const checkboxes = document.querySelectorAll('input[name="selected_extraits[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateButtonState);
            });

        });
    </script>
@endsection
