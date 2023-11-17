@extends('frontend.default.layouts.master')

@section('title')
    {{ localize('Customer Profile') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
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
                    <div class="update-profile bg-white py-5 px-4">
<<<<<<< HEAD
                        <h6 class="mb-4">{{ localize('Mettre à jour Profile') }}</h6>
=======
                        <h6 class="mb-4">{{ localize('Mettre à jour le profil') }}</h6>
>>>>>>> 896ea115bc135163b1977ee3f1b1f40fdc6b7f9b
                        <form class="profile-form" action="{{ route('customers.updateProfile') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="info">
                            <div class="file-upload text-center rounded-3 mb-5">
                                <input type="file" name="avatar">
                                <img src="{{ staticAsset('frontend/default/assets/img/icons/image.svg') }}" alt="dp"
                                    class="img-fluid">
                                <p class="text-dark fw-bold mb-2 mt-3">{{ localize('Déposez vos fichiers ici ou parcourez') }}</p>
                                <p class="mb-0 file-name"></p>
                            </div>
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="label-input-field">
                                        <label>{{ localize('Nom') }}</label>
                                        <input type="text" name="name" placeholder="{{ localize('Votre nom') }}"
                                            value="{{ $user->name }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Adresse e-mail') }}</label>
                                        <input type="email" name="email" placeholder="{{ localize('Votre adresse e-mail') }}"
                                            value="{{ $user->email }}" disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Téléphone') }}</label>
                                        <input type="text" name="phone" placeholder="{{ localize('Votre téléphone') }}"
                                            value="{{ $user->phone }}">
                                    </div>
                                </div>
                            </div>
<<<<<<< HEAD
                            <button type="submit" class="btn btn-primary mt-6">{{ localize('Mettre à jour Profile') }}</button>
=======
                            <button type="submit" class="btn btn-primary mt-6">{{ localize('Mettre à jour le profil') }}</button>
>>>>>>> 896ea115bc135163b1977ee3f1b1f40fdc6b7f9b
                        </form>
                    </div>

                    <div class="change-password bg-white py-5 px-4 mt-4 rounded">
                        <h6 class="mb-4">{{ localize('Changer le mot de passe') }}</h6>
                        <form class="password-reset-form" action="{{ route('customers.updateProfile') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="password">
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Nouveau mot de passe') }}</label>
                                        <input type="password" name="password" placeholder="******" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="label-input-field">
                                        <label>{{ localize('Re-tapez le mot de passe') }}</label>
                                        <input type="password" name="password_confirmation" placeholder="******" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-6">{{ localize('Changer le mot de passe') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
