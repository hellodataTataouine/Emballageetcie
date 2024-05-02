<div class="row align-items-center g-4 mt-3">
    <form action="{{ route('address.update') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $address->id }}">
        <div class="row g-4">
            <div class="col-sm-6">
                <div class="w-100 label-input-field">
                    <label>{{ localize('Pays') }}</label>
                    <select class="select2Address" name="country_id" required>
                        <option value="">{{ localize('Sélectionner le pays') }}</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" @if ($address->country_id == $country->id) selected @endif>
                                {{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                                     <div class="w-100 label-input-field">
                                         <label>{{ localize('Ville') }}</label>
                                        
                                         <input type="text" class="form-control" required name="city" value="{{ $address->city }}">
                                     </div>
                                 </div>
                                 <div class="col-sm-6">
                                     <div class="w-100 label-input-field">
                                         <label>{{ localize('Code Postal') }}</label>
                                         <input type="text" class="form-control" required name="postal_code" value="{{ $address->codepostal }}">

                                     </div>
                                 </div>
            <div class="col-sm-6">
                <div class="w-100 label-input-field">
                    <label>{{ localize('Adresse par défaut ?') }}</label>
                    <select class="select2Address" name="is_default">
                        <option value="0" @if ($address->is_default == 0) selected @endif>{{ localize('Non') }}
                        </option>
                        <option value="1" @if ($address->is_default == 1) selected @endif>
                            {{ localize('Définir par défaut') }}</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="label-input-field">
                    <label>{{ localize('Adresse') }}</label>
                    <textarea rows="4" placeholder="{{ localize('18 Rue du Clos Barrois, 60180 Nogent-sur-Oise, France') }}" name="address" required>{{ $address->address }}</textarea>
                </div>
            </div>

        </div>
        <div class="mt-6 d-flex">
            <button type="submit" class="btn btn-secondary btn-md me-3">{{ localize('Mettre à jour') }}</button>
        </div>
    </form>
</div>
