@extends('backend.layouts.master')

@section('title')
    {{ localize('Ajouter une Zone d\'expédition') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection


@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Ajouter une Zone d\'expédition') }}</h2>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.logisticZones.store') }}" method="POST" class="pb-650">
                        @csrf
                        <!--basic information start-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Informations de base') }}</h5>

                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ localize('Nom de la Zone ') }}</label>
                                    <input class="form-control" type="text" id="name"
                                        placeholder="{{ localize('Saisir Nom de la Zone') }}" name="name" required>
                                </div>

                                <div class="mb-4">
                                    <label for="logistic_id" class="form-label">{{ localize('Logistique') }}</label>
                                    <select class="form-control select2" name="logistic_id" class="w-100" id="logistic_id"
                                        data-toggle="select2" required>
                                        <option value="">{{ localize('Sélectionner Logistique') }}</option>
                                        @foreach ($logistics as $logistic)
                                            <option value="{{ $logistic->id }}">
                                                {{ $logistic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">{{ localize('Villes') }}</label>
                                    <select class="form-control select2" name="city_ids[]" class="w-100" id="city_ids"
                                        data-toggle="select2" data-placeholder="{{ localize('Sélectionner les Villes') }}" multiple
                                        required>
                                    </select>
                                </div>


                                <div class="mb-4">
                                    <label for="name"
                                        class="form-label">{{ localize('Frais de livraison standard') }}</label>
                                    <input type="number" step="0.001" name="standard_delivery_charge"
                                        id="standard_delivery_charge"
                                        placeholder="{{ localize('Frais de livraison standard') }}" class="form-control"
                                        min="0" required>
                                </div>



                                <div class="mb-4">
                                    <label for="name"
                                        class="form-label">{{ localize('Franco de port') }}</label>
                                    <input type="number" step="0.001" name="franco_port"
                                        id="franco_port"
                                        placeholder="{{ localize('Franco de port') }}" class="form-control"
                                        min="0" required >
                                </div>

                                <div class="mb-4">
                                    <label for="name"
                                        class="form-label">{{ localize('Temps de livraison standard') }}</label>
                                    <input type="text" name="standard_delivery_time" id="standard_delivery_time"
                                        placeholder="{{ localize('1 - 3 jours') }}" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <!--basic information end-->

                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="save" class="me-1"></i> {{ localize('Enregistrer la Zone') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- submit button end -->

                    </form>
                </div>

                <!--right sidebar-->
                <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
                    <div class="card tt-sticky-sidebar d-none d-xl-block">
                        <div class="card-body">
                            <h5 class="mb-4">{{ localize('Zone Informations') }}</h5>
                            <div class="tt-vertical-step">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#section-1" class="active">{{ localize('Informations de base') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        "use strict";

        //  get states on country change
        $(document).on('change', '[name=logistic_id]', function() {
            var logistic_id = $(this).val();
            getLogisticCities(logistic_id);
        });

        //  get cities
        function getLogisticCities(logistic_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('admin.logisticZones.getLogisticCities') }}",
                type: 'POST',
                data: {
                    logistic_id: logistic_id
                },
                success: function(response) {
                    $('[name="city_ids[]"]').html("");
                    $('[name="city_ids[]"]').html(JSON.parse(response));
                }
            });
        }
    </script>
@endsection
