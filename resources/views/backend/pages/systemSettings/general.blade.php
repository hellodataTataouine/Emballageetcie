@extends('backend.layouts.master')

@section('title')
    {{ localize('Paramètres généraux') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Paramètres généraux') }}</h2>
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

                        <!--general settings-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Informations générales') }}</h5>

                                <div class="mb-3">
                                    <label for="system_title" class="form-label">{{ localize('Titre du système') }}</label>
                                    <input type="hidden" name="types[]" value="system_title">
                                    <input type="text" id="system_title" name="system_title" class="form-control"
                                        value="{{ getSetting('system_title') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="title_separator"
                                        class="form-label">{{ localize('Séparateur de titre d\'onglet de navigateur') }}</label>
                                    <input type="hidden" name="types[]" value="title_separator">
                                    <input type="text" id="title_separator" name="title_separator" class="form-control"
                                        value="{{ getSetting('title_separator') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="site_address" class="form-label">{{ localize('Adresse') }}</label>
                                    <input type="hidden" name="types[]" value="site_address">
                                    <input type="text" id="site_address" name="site_address" class="form-control"
                                        value="{{ getSetting('site_address') }}">
                                </div>
                            </div>
                        </div>
                        <!--general settings-->



                        <!--logo settings-->
                        <div class="card mb-4" id="section-3">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Logo et favicon du tableau de bord') }}</h5>
                                <div class="mb-3">
                                    <label for="admin_panel_logo"
                                        class="form-label">{{ localize('Logo du tableau de bord') }}</label>
                                    <input type="hidden" name="types[]" value="admin_panel_logo">
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choisissez le logo du tableau de bord') }}</span>
                                        <!-- choose media -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="admin_panel_logo"
                                                    value="{{ getSetting('admin_panel_logo') }}">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- choose media -->
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="favicon" class="form-label">{{ localize('Favicon') }}</label>
                                    <input type="hidden" name="types[]" value="favicon">
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choisir Favicon') }}</span>
                                        <!-- choose media -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="favicon" value="{{ getSetting('favicon') }}">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- choose media -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--logo settings-->

                        <!--maintenance mode settings-->
                        <div class="card mb-4" id="section-4">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Mode maintenance') }}</h5>
                                <div class="mb-3">
                                    <label for="enable_maintenance_mode"
                                        class="form-label">{{ localize('Activer le mode maintenance') }}</label>
                                    <input type="hidden" name="types[]" value="enable_maintenance_mode">
                                    <select id="enable_maintenance_mode" class="form-control text-uppercase select2"
                                        name="enable_maintenance_mode" data-toggle="select2">
                                        <option value="" disabled selected>{{ localize('Définir le mode maintenance') }}
                                        </option>
                                        <option value="1"
                                            {{ getSetting('enable_maintenance_mode') == '1' ? 'selected' : '' }}>
                                            {{ localize('Activé') }}</option>
                                        <option value="0"
                                            {{ getSetting('enable_maintenance_mode') == '0' ? 'selected' : '' }}>
                                            {{ localize('Désactivé') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--maintenance mode settings-->

                        <!--seo meta description start-->
                        <div class="card mb-4" id="section-5">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Configuration SEO Meta') }}</h5>

                                <div class="mb-4">
                                    <label for="global_meta_title"
                                        class="form-label">{{ localize('Titre Meta') }}</label>
                                    <input type="hidden" name="types[]" value="global_meta_title">
                                    <input type="text" name="global_meta_title" id="global_meta_title"
                                        placeholder="{{ localize('Saisir Titre Meta') }}" class="form-control"
                                        value="{{ getSetting('global_meta_title') }}">
                                    <span class="fs-sm text-muted">
                                        {{ localize('Définir un titre de balise meta. Recommandé d\'être simple et unique.') }}
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <label for="global_meta_description"
                                        class="form-label">{{ localize('Description Meta') }}</label>
                                    <input type="hidden" name="types[]" value="global_meta_description">
                                    <textarea class="form-control" name="global_meta_description" id="global_meta_description" rows="4"
                                        placeholder="{{ localize('Saisir votre Description Meta') }}">{{ getSetting('global_meta_description') }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label for="global_meta_keywords"
                                        class="form-label">{{ localize('Mots-clés Meta') }}</label>

                                    <input type="hidden" name="types[]" value="global_meta_keywords">
                                    <textarea class="form-control" name="global_meta_keywords" id="global_meta_keywords" placeholder="Mots-clés, Mots-clés">{{ getSetting('global_meta_keywords') }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">{{ localize('Meta Image') }}</label>
                                    <input type="hidden" name="types[]" value="global_meta_image">
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choisir Meta Image') }}</span>
                                        <!-- choose media -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="global_meta_image"
                                                    value="{{ getSetting('global_meta_image') }}">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- choose media -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--seo meta description end-->

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
                                        <a href="#section-1" class="active">{{ localize('Informations générales') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-3">{{ localize('Logo et favicon du tableau de bord') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-4">{{ localize('Mode maintenance') }}</a>
                                    </li>
                                    <li>
                                        <a href="#section-5">{{ localize('Configuration SEO') }}</a>
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
