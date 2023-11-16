@extends('backend.layouts.master')

@section('title')
    {{ localize('Mettre à jour le profil') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Mettre à jour le profil') }}</h2>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">

                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <!--basic information start-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Informations de base') }}</h5>

                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ localize('Nom') }}</label>
                                    <input class="form-control" type="text" id="name"
                                        placeholder="{{ localize('Saisir votre nom') }}" name="name" required
                                        value="{{ $user->name }}">
                                </div>


                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ localize('Email') }}</label>
                                    <input class="form-control" type="email" id="email"
                                        placeholder="{{ localize('Saisir votre email') }}" name="email" required
                                        value="{{ $user->email }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ localize('Téléphone') }}</label>
                                    <input class="form-control" type="text" id="phone"
                                        placeholder="{{ localize('Entrez votre numéro de téléphone') }}"
                                        name="phone"value="{{ $user->phone }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ localize('Avatar') }}</label>
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choisir un avatar') }}</span>
                                        <!-- choose media -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="avatar" value="{{ $user->avatar }}">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- choose media -->
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ localize('Mot de passe') }}</label>
                                    <input class="form-control" type="password" id="password"
                                        placeholder="{{ localize('Entrez votre mot de passe') }}" name="password">
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation"
                                        class="form-label">{{ localize('Confirmer votre mot de passe') }}</label>
                                    <input class="form-control" type="password" id="password_confirmation"
                                        placeholder="{{ localize('Retaper votre mot de passe') }}" name="password_confirmation">
                                </div>
                            </div>
                        </div>
                        <!--basic information end-->

                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="save" class="me-1"></i> {{ localize('Enregistrer les modifications') }}
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
                            <h5 class="mb-4">{{ localize('Informations de l\'utilisateur') }}</h5>
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

        // runs when the document is ready --> for media files
        $(document).ready(function() {
            getChosenFilesCount();
            showSelectedFilePreviewOnLoad();
        });
    </script>
@endsection
