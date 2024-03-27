@extends('layouts.auth')

@section('title')
    {{ localize('S\'inscrire') }}
@endsection

@section('contents')
    <section class="login-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                {{-- todo:: make banner dynamic --}}
                <div class="col-lg-5 col-12 tt-login-img"
                    data-background="{{ staticAsset('frontend/default/assets/img/banner/login-banner.jpg') }}"></div>
                <div class="col-lg-5 col-12 bg-white d-flex p-0 tt-login-col shadow">
                    <form class="tt-login-form-wrap p-3 p-md-6 p-lg-6 py-7 w-100 " action="{{ route('register') }}"
                        method="POST" id="login-form">
                        @csrf

                        {!! RecaptchaV3::field('recaptcha_token') !!}
                        <div class="mb-7">
                            <a href="{{ route('home') }}">
                                <img src="{{ uploadedAsset(getSetting('navbar_logo')) }}" alt="logo">
                            </a>
                        </div>
                        <h2 class="mb-4 h3">{{ localize('"Salut !') }}
                            <br>{{ localize('S\'inscrire en tant que client') }}
                        </h2>
                      
                        <div id="verification-section">
                            <div class="row g-3">

                                <!-- Display Numero Client Field and Verify Button -->
                                <div class="col-sm-12 mb-3">
                                    <button type="button" class="btn btn-primary w-100"
                                        onclick="showNumeroClientPopup()">{{ localize('Client existant') }}</button>
                                </div>
                            </div>


                            <!-- Modal for Numéro de client -->
                            <div class="modal fade" id="numeroClientModal" tabindex="-1"
                                aria-labelledby="numeroClientModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="numeroClientModalLabel">{{ localize('Numéro de client') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>{{ localize('Veuillez saisir votre numéro de client.') }}</p>
                                            <input type="text" id="client_number_modal" name="client_number_modal" class="form-control"
                                                placeholder="{{ localize('Entrez votre numéro de client') }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">{{ localize('Fermer') }}</button>
                                            <button type="button" class="btn btn-primary"
                                                onclick="onNumeroClientConfirmed()">{{ localize('Confirmer') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nouveau client section -->
                        <div id="nouveau-client-section">
                            <div class="row g-3">
                                <!-- Display Nouveau Client Button -->
                                <div class="col-sm-12 mb-3">
                                    <button type="button" class="btn btn-primary w-100" onclick="showRegistrationForm()">{{ localize('Nouveau client') }}</button>
                                </div>
                            </div>
                        </div>


                        <!-- Display the rest of the form after verification -->
                        <div id="rest-of-form" style="display: none;">
                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <div class="input-field">
                                        <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Société') }}<sup
                                                class="text-danger">*</sup>
                                        </label>
                                        <input type="text" id="name" name="name"
                                            placeholder="{{ localize('Entrez votre nom') }}" class="theme-input"
                                            value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                    <div class="input-field">
                                        <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Email') }}<sup
                                                class="text-danger">*</sup></label>
                                        <input type="email" id="email" name="email"
                                            placeholder="{{ localize('Entrez votre email') }}" class="theme-input"
                                            value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="input-field">
                                        <label class="fw-bold text-dark fs-sm mb-1">
                                            @if (getSetting('registration_with') == 'email_and_phone')
                                                {{ localize('Téléphone') }}<sup class="text-danger">*</sup>
                                            @else
                                                {{ localize('Téléphone') }}
                                            @endif
                                            <!-- <small>({{ localize('Entrez le numéro de téléphone avec le code pays') }})</small> -->
                                        </label>
                                        <input type="text" id="phone" name="phone" placeholder="+xxxxxxxxxx"
                                            class="theme-input" value="{{ old('phone') }}"
                                            @if (getSetting('registration_with') == 'email_and_phone') required @endif>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="input-field check-password">
                                        <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Mot de passe') }}<sup
                                                class="text-danger">*</sup></label>
                                        <div class="check-password">
                                            <input type="password" name="password" placeholder="{{ localize('Mot de passe') }}"
                                                class="theme-input" required>
                                            <span class="eye eye-icon"><i class="fa-solid fa-eye"></i></span>
                                            <span class="eye eye-slash"><i class="fa-solid fa-eye-slash"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="input-field check-password">
                                        <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Confirmer le mot de passe') }}<sup
                                                class="text-danger">*</sup></label>
                                        <div class="check-password">
                                            <input type="password" name="password_confirmation"
                                                placeholder="{{ localize('Confirmer le mot de passe') }}" class="theme-input" required>
                                            <span class="eye eye-icon"><i class="fa-solid fa-eye"></i></span>
                                            <span class="eye eye-slash"><i class="fa-solid fa-eye-slash"></i></span>
                                        </div>
                                    </div>
                                </div>


                                <!-- Add these hidden fields within your form -->
                                <input type="hidden" id="codetiers" name="codetiers">
                                <input type="hidden" id="IDClient" name="IDClient">
                                <input type="hidden" id="postal_code" name="postal_code">
                                <input type="hidden" id="address" name="address">


                        
                            <div class="row g-4 mt-3">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary w-100 sign-in-btn"
                                        onclick="handleSubmit()">{{ localize('S\'inscrire') }}</button>
                                </div>
                            </div>
                        </div>

                        <p id="login-link" class="mb-0 fs-xs mt-4">{{ localize('Vous avez déjà un compte ?') }} <a
                                href="{{ route('login') }}">{{ localize('Se connecter') }}</a></p>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <script>
"use strict";

$(document).ready(function () {
});

function showNumeroClientPopup() {
    $('#numeroClientModal').modal('show');
}
function showRegistrationForm() {
    $('#verification-section').hide();
    $('#nouveau-client-section').hide(); 
    $('#rest-of-form').show();
    $('#login-link').show();
}



function onNumeroClientConfirmed() {
    var codeTiers = document.getElementById('client_number_modal').value;

    $.ajax({
        url: '/verify-client/' + codeTiers,
        type: 'GET',
        success: function(response) {
            if (response.exists) {
                // console.log('User exists:', response.data);
                fillFormWithClientData(response.data);
            } else {
                console.error(response.error);
                if (!codeTiers || response.data.length === 0) {
                   
                    displayMessage('Vous n\'êtes pas encore enregistré en tant que client. Veuillez créer un compte.');
                } else {
                    displayMessage('Le client n\'existe pas ou une erreur s\'est produite lors de la vérification.');
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error verifying client:', status, error);
            displayMessage('Erreur lors de la vérification du client.');
        }
    });
}

function displayMessage(message) {
    alert(message);
}


function fillFormWithClientData(clientData) {
    try {
        // console.log('Received clientData:', clientData);

        if (clientData.length > 0) {
            const firstClient = clientData[0];
            const fullName = firstClient.Société;
            const email = firstClient.EMail;
            const phone = extractPhoneNumber(firstClient);
            const codetiers = firstClient.CODETIERS;
            const IDClient = String(firstClient.IDClient);
            const postalCode = firstClient.CodePostal;
            const address = firstClient.Adresse;
console.log(IDClient);
           // console.log('Extracted values:', { fullName, email, phone, codetiers, postalCode, address });

            setFieldValueAndReadonly('#name', fullName);
            setFieldValueAndReadonly('#email', email);
            setFieldValueAndReadonly('#phone', phone);
            setFieldValueAndReadonly('#codetiers', codetiers);
            setFieldValueAndReadonly('#IDClient', IDClient);
            setFieldValueAndReadonly('#postal_code', postalCode);
            setFieldValueAndReadonly('#address', address); 

            $('#numeroClientModal').modal('hide');
            $('#verification-section').hide();
            $('#rest-of-form').show();
            $('#login-link').show();
        } else {
            console.error('No data for the given codeTiers.');
            resetForm();
            $('#numeroClientModal').modal('hide');
            $('#nouveau-client-section').hide();
            $('#verification-section').hide();
            $('#rest-of-form').show();
            $('#login-link').show();
        }
    } catch (error) {
        console.error('Error in fillFormWithClientData:', error);
        resetForm();
        $('#numeroClientModal').modal('hide');
        $('#nouveau-client-section').hide();
        $('#verification-section').hide();
        $('#rest-of-form').show();
        $('#login-link').show();
    }
}


function extractPhoneNumber(client) {
    const phoneFields = ["Portable", "T\u00e9l\u00e9phone", "Mobile"];

    // Find the first phone number field that is not null or undefined
    const validPhoneField = phoneFields.find(field => client[field] != null);

    // Use the value of the valid phone field, or an empty string if none is found
    return validPhoneField ? client[validPhoneField] : '';
}


function resetForm() {

    setFieldValueAndReadonly('#name', '');
    setFieldValueAndReadonly('#email', '');
    setFieldValueAndReadonly('#phone', '');
}
function setFieldValueAndReadonly(selector, value) {
    const field = $(selector);

    if (field.length > 0) {
        if (value && value.trim() !== '') {
            field.val(value).prop('readonly', true).addClass('filled-readonly');
        } else {
            field.val('').prop('readonly', false).removeClass('filled-readonly');
        }
    } else {
        console.error(`Field with selector '${selector}' not found.`);
    }
}

function continueWithRegistrationForm() {
    $('#numeroClientModal').modal('hide');
    $('#nouveau-client-section').hide();
    $('#verification-section').hide();
    $('#rest-of-form').show();
    $('#login-link').show();
}


function handleSubmit() {
    $('#login-form').on('submit', function (e) {
        $('.sign-in-btn').prop('disabled', true);
    });
}

</script>

<style>.filled-readonly {
    background-color: #f0f0f0; 
    color: #777; 
    cursor: not-allowed;
}
</style>

@endsection
