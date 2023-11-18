@extends('backend.layouts.master')

@section('title')
    {{ localize('Paramètres d\'authentification') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Paramètres d\'authentification') }}</h2>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
                        class="pb-650">
                        @csrf

                        <!--login settings-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Connexion et inscription') }}</h5>

                                <div class="mb-3">
                                    <label for="registration_with"
                                        class="form-label">{{ localize(' Inscription client') }}</label>
                                    <input type="hidden" name="types[]" value="registration_with">
                                    <select id="registration_with" class="form-control text-uppercase select2"
                                        name="registration_with" data-toggle="select2">
                                        <option value="email"
                                            {{ getSetting('registration_with') == 'email' ? 'selected' : '' }}>
                                            {{ localize('Email requis') }}</option>
                                        <option value="email_and_phone"
                                            {{ getSetting('registration_with') == 'email_and_phone' ? 'selected' : '' }}>
                                            {{ localize(' Email et téléphone tous deux requis') }}</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="registration_verification_with"
                                        class="form-label">{{ localize('Vérification de l\'inscription') }}</label>
                                    <input type="hidden" name="types[]" value="registration_verification_with">
                                    <select id="registration_verification_with" class="form-control text-uppercase select2"
                                        name="registration_verification_with" data-toggle="select2">
                                        <option value="disable"
                                            {{ getSetting('registration_verification_with') == 'disable' ? 'selected' : '' }}>
                                            {{ localize('Désactivé') }}</option>
                                        <option value="email"
                                            {{ getSetting('registration_verification_with') == 'email' ? 'selected' : '' }}>
                                            {{ localize('Vérification par email') }}</option>
                                        <option value="phone"
                                            {{ getSetting('registration_verification_with') == 'phone' ? 'selected' : '' }}>
                                            {{ localize('Vérification OTP') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--login settings-->

                        <!--recaptcha settings-->
                        <div class="card mb-4" id="section-2">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Google Recaptcha V3') }}</h5>


                                <div class="mb-3">
                                    <label for="RECAPTCHAV3_SITEKEY"
                                        class="form-label">{{ localize('Clé de site Recaptcha') }}</label>
                                    <input type="hidden" name="types[]" value="RECAPTCHAV3_SITEKEY">
                                    <input type="text" id="RECAPTCHAV3_SITEKEY" name="RECAPTCHAV3_SITEKEY"
                                        class="form-control" value="{{ env('RECAPTCHAV3_SITEKEY') }}">
                                </div>


                                <div class="mb-3">
                                    <label for="RECAPTCHAV3_SECRET"
                                        class="form-label">{{ localize('Clé secrète Recaptcha') }}</label>
                                    <input type="hidden" name="types[]" value="RECAPTCHAV3_SECRET">
                                    <input type="text" id="RECAPTCHAV3_SECRET" name="RECAPTCHAV3_SECRET"
                                        class="form-control" value="{{ env('RECAPTCHAV3_SECRET') }}">
                                </div>


                                <div class="mb-3">
                                    <label for="enable_recaptcha"
                                        class="form-label">{{ localize('Activer Recaptcha') }}</label>
                                    <input type="hidden" name="types[]" value="enable_recaptcha">
                                    <select id="enable_recaptcha" class="form-control text-uppercase select2"
                                        name="enable_recaptcha" data-toggle="select2">
                                        <option value="1" {{ getSetting('enable_recaptcha') == 1 ? 'selected' : '' }}>
                                            {{ localize('Activé') }}</option>
                                        <option value="0" {{ getSetting('enable_recaptcha') == 0 ? 'selected' : '' }}>
                                            {{ localize('Désactivé') }}</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <!--recaptcha settings-->

                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">
                                <i data-feather="save" class="me-1"></i> {{ localize('Enregistrer Configuration') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!--right sidebar-->
                <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
                    <div class="card tt-sticky-sidebar">
                        <div class="card-body">
                            <h5 class="mb-4">{{ localize('Configurer les paramètres généraux') }}</h5>
                            <div class="tt-vertical-step">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#section-1" class="active">{{ localize('Connexion et inscription') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-2" class="">{{ localize('Google Recaptcha') }}</a>
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

        // runs when the document is ready --> for media files
        $(document).ready(function() {
            getChosenFilesCount();
            showSelectedFilePreviewOnLoad();
        });
    </script>
@endsection
