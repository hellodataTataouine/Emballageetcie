@extends('backend.layouts.master')

@section('title')
    {{ localize('Envoyer des courriels en masse') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Envoyer des courriels en masse') }}</h2>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.newsletters.send') }}" method="POST" enctype="multipart/form-data"
                        class="pb-650">
                        @csrf
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Informations de base') }}</h5>

                                <input type="hidden" name="user_emails[]">
                                <div class="d-none">
                                    <label for="user_emails" class="form-label">{{ localize('Sélectionner Clients') }}</label>
                                    <select class="form-select form-control select2"
                                        data-placeholder="{{ localize('Sélectionner Clients') }}" data-toggle="select2"
                                        name="user_emails[]" multiple>
                                        @foreach ($users as $user)
                                            @if ($user->email)
                                                <option value="{{ $user->email }}">
                                                    {{ $user->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="subscriber_emails" class="form-label">{{ localize('Abonnés') }}</label>
                                    <select class="form-select form-control select2"
                                        data-placeholder="{{ localize('Sélectionner Abonnés') }}" data-toggle="select2"
                                        name="subscriber_emails[]" multiple required>
                                        @foreach ($subscribers as $subscriber)
                                            @if ($subscriber->email)
                                                <option value="{{ $subscriber->email }}">
                                                    {{ $subscriber->email }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="subject" class="form-label">{{ localize('Objet du courriel') }}</label>
                                    <input type="text" name="subject" id="subject" class="form-control" required>
                                </div>

                                <div class="mb-4">
                                    <label for="content" class="form-label">{{ localize('Corps du courriel') }}</label>
                                    <textarea id="content" class="editor form-control" name="content"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <button class="btn btn-primary" type="submit">
                                <i data-feather="save" class="me-1"></i> {{ localize('Envoyer des courriels') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!--right sidebar-->
                <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
                    <div class="card tt-sticky-sidebar d-none d-xl-block">
                        <div class="card-body">
                            <h5 class="mb-4">{{ localize('Envoyer des courriels en masse') }}</h5>
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
