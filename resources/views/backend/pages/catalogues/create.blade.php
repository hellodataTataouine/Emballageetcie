@extends('backend.layouts.master')

@section('title')
    {{ localize('Ajouter catalogue') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Ajouter catalogue') }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.catalogues.store') }}" method="POST" class="pb-650" enctype="multipart/form-data">
                        @csrf
                        <!--basic information start-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-3">{{ localize('Informations sur le catalogue') }}</h5>

                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ localize('Nom du catalogue') }} </label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <!-- Other form fields... -->

                                <div class="mb-4">
    <label class="form-label">{{ localize('Banner') }}</label>
    <div class="tt-image-drop rounded">
        <span class="fw-semibold">{{ localize('Choisir Catalogue Banner') }}</span>
        <!-- choose media -->
        <div class="tt-product-thumb show-selected-files mt-3">
            <div class="avatar avatar-xl cursor-pointer choose-media"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                onclick="showMediaManager(this)" data-selection="single">
                <input type="file" name="banner" accept="image/*">
                <div class="no-avatar rounded-circle">
                    <span><i data-feather="plus"></i></span>
                </div>
            </div>
        </div>
        <!-- choose media -->
    </div>
</div>


                                <div class="mb-3">
                                    <label for="pdf_file" class="form-label">{{ localize('Importer un catalogue PDF') }}</label>
                                    <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept=".pdf" required>
                                </div>
                            </div>
                        </div>
                        <!--basic information end-->

                        <!-- Other sections of the form... -->

                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="upload" class="me-1"></i> {{ localize('Importer le catalogue') }}
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
                                        <a href="#section-1" class="active">{{ localize('Informations de catalogue') }}</a>
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
