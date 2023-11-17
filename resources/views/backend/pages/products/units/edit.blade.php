@extends('backend.layouts.master')


@section('title')
    {{ localize('Mise à jour de l\'unité') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto flex-grow-1">
                                    <div class="tt-page-title">
                                        <h2 class="h5 mb-0">{{ localize('Mise à jour de l\'unité') }} </h2>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">

                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.units.update') }}" method="POST" class="pb-650">
                        @csrf
                        <input type="hidden" name="id" value="{{ $unit->id }}">
                        <input type="hidden" name="lang_key" value="{{ $lang_key }}">
                        <!--basic information start-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Informations de base') }}</h5>

                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ localize('Nom de l\'unité') }}</label>
                                    <input type="text" name="name" id="name"
                                        placeholder="{{ localize('Tapez le nom de l\'unité') }}" class="form-control" required
                                        value="{{ $unit->collectLocalization('name', $lang_key) }}">
                                </div>

                                @if (env('DEFAULT_LANGUAGE') == $lang_key)
                                    <div class="mb-4">
                                        <label for="slug" class="form-label">{{ localize('Slug de l\'unité') }}</label>
                                        <input type="text" name="slug" id="slug"
                                            placeholder="{{ localize('Tapez le slug de l\'unité') }}" class="form-control" required
                                            value="{{ $unit->slug }}">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!--basic information end-->

                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="save" class="me-1"></i> {{ localize('Enregistrer les modifications ') }}
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
                            <h5 class="mb-4">{{ localize('Informations sur l\'unité') }}</h5>
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
