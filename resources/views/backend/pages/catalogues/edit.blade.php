@extends('backend.layouts.master')

@section('title')
    {{ localize('Modifier Catalogue') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Modifier Catalogue') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.catalogues.update', $catalog->id) }}" method="POST" class="pb-650" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!--basic information start-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Informations sur le catalogue') }}</h5>

                                <!-- Existing Name -->
                                <div class="mb-3">
                                    <label for="existing_name" class="form-label">{{ localize('Titre du catalogue existant') }}</label>
                                    <p>{{ $catalog->name }}</p>
                                </div>

                                <!-- Update Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ localize('Mettre à jour le Titre du catalogue') }}</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $catalog->name) }}">
                                </div>

                                <!-- Existing PDF File -->
                                <div class="mb-3">
                                    <label for="existing_pdf_file" class="form-label">{{ localize('Catalogue PDF existant') }}</label>
                                    <p>{{ $catalog->file_path }}</p>
                                </div>

                                <!-- New PDF File -->
                                <div class="mb-3">
                                    <label for="pdf_file" class="form-label">{{ localize('Mettre à jour le catalogue PDF') }}</label>
                                    <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept=".pdf">
                                </div>

                                <!-- Existing Banner Image -->
                                <div class="mb-3">
                                    <label for="existing_banner" class="form-label">{{ localize('Image de bannière existante') }}</label>
                                    <p>{{ $catalog->banner }}</p>
                                </div>

                                <!-- New Banner Image -->
                                <div class="mb-4">
                                    <label for="banner" class="form-label">{{ localize('Mettre à jour l\'image de la bannière') }}</label>
                                    <div class="tt-image-drop rounded">
                                        <span class="fw-semibold">{{ localize('Choisir une nouvelle image de bannière') }}</span>
                                        <!-- Choose media for the banner -->
                                        <div class="tt-product-thumb show-selected-files mt-3">
                                            <div class="avatar avatar-xl cursor-pointer choose-media"
                                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                                                onclick="showMediaManager(this)" data-selection="single">
                                                <input type="hidden" name="banner">
                                                <div class="no-avatar rounded-circle">
                                                    <span><i data-feather="plus"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Choose media for the banner -->
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--basic information end-->

                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="upload" class="me-1"></i> {{ localize(' Mettre à jour le catalogue') }}
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
                            <h5 class="mb-3">{{ localize('Informations sur le catalogue') }}</h5>
                            <div class="tt-vertical-step">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#section-1" class="active">{{ localize('Informations sur le catalogue') }}</a>
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
    <script type="text/javascript">
        $(document).ready(function() {
            "use strict"

            // Handle the change event for the banner image
            $('#banner').on('change', function() {
                // Fetch and display additional information or perform actions if needed
            });
        });
    </script>
@endsection
