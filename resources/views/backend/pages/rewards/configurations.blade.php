@extends('backend.layouts.master')

@section('title')
    {{ localize('Configurations de récompense') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection


@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Configurations de récompense') }}</h2>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">

                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="pb-650">
                        @csrf
                        <!--basic information start-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-3">{{ localize('Informations de base') }}</h5>

                                <div class="mb-3">
                                    <label for="reward_points_per_usd"
                                        class="form-label">{{ localize('$1.000 = Combien de points ?') }}</label>
                                    <input type="hidden" name="types[]" value="reward_points_per_usd">
                                    <input class="form-control" type="number" min="1" id="reward_points_per_usd"
                                        placeholder="{{ localize('Saisir les points de récompense') }}" name="reward_points_per_usd"
                                        value="{{ getSetting('reward_points_per_usd') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="waiting_days_for_wallet_conversion"
                                        class="form-label">{{ localize('Jours d\'attente pour la conversion en portefeuille') }}</label>
                                    <input type="hidden" name="types[]" value="waiting_days_for_wallet_conversion">
                                    <input class="form-control" type="number" min="0"
                                        id="waiting_days_for_wallet_conversion"
                                        placeholder="{{ localize('Saisir les jours d\'attente') }}"
                                        name="waiting_days_for_wallet_conversion"
                                        value="{{ getSetting('waiting_days_for_wallet_conversion') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="enable_reward_points"
                                        class="form-label">{{ localize('Activer les points de récompense') }}</label>
                                    <input type="hidden" name="types[]" value="enable_reward_points">
                                    <select class="select2 form-control" name="enable_reward_points">
                                        <option value="1" @if (getSetting('enable_reward_points') == 1) selected @endif>
                                            {{ localize('Activé') }}</option>
                                        <option value="0" @if (getSetting('enable_reward_points') == 0) selected @endif>
                                            {{ localize('Désactivé') }}</option>
                                    </select>
                                </div>


                            </div>
                        </div>
                        <!--basic information end-->

                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="save" class="me-1"></i> {{ localize('Enregistrer Configurations') }}
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
                            <h5 class="mb-3">{{ localize('Information sur les récompenses') }}</h5>
                            <div class="tt-vertical-step">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#section-1" class="active">{{ localize('Information sur les récompenses') }}</a>
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
