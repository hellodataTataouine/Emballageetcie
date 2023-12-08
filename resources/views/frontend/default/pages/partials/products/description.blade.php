<div class="product-info-tab bg-white rounded-2 overflow-hidden pt-6 mt-4">
    <ul class="nav nav-tabs border-bottom justify-content-center gap-5 pt-info-tab-nav">
        <li><a href="#description" class="active" data-bs-toggle="tab">{{ localize('Description') }}</a></li>
        <li><a href="#childProducts" data-bs-toggle="tab">{{ localize('Réferences') }}</a></li>

        <li><a href="#info" data-bs-toggle="tab">{{ localize('Informations complémentaires') }}</a></li>
        <li><a href="#ficheTechnique" data-bs-toggle="tab">{{ localize('Fiche technique') }}</a></li>

    </ul>

    
    <div class="tab-content">
        <div class="tab-pane fade show active px-4 py-5" id="description">
            @if ($product->description)
                {!! $product->collectLocalization('description') !!}
            @else
                <div class="text-dark text-center border py-2">{{ localize('Non disponible') }}</div>
            @endif
        </div>

        <div class="tab-pane fade px-4 py-5" id="info">
            <table class="w-100 product-info-table">
                @forelse (generateVariationOptions($product->variation_combinations) as $variation)
                    <tr>
                        <td class="text-dark fw-semibold">{{ $variation['name'] }}</td>
                        <td>
                            @foreach ($variation['values'] as $value)
                                {{ $value['name'] }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td class="text-dark text-center" colspan="2">{{ localize('Non disponible') }}
                            </td>
                        </tr>
                    @endforelse
                </table>
            </div>

        </div>
        
        <div class="tab-pane fade px-4 py-5" id="ficheTechnique">
    <div class="thumbnail position-relative text-center p-4">
        @if ($product->fiche_technique)
            <a href="{{ asset('storage/' . $product->fiche_technique) }}" target="_blank" class="btn btn-primary">
    <i class="fas fa-file-pdf"></i> {{ localize('Consulter') }}</a>
        @else
            <div class="text-dark text-center border py-2">{{ localize('Non disponible') }}</div>
        @endif
    </div>


    
</div>

<!-- Réferences Tab Content -->
<div class="tab-pane fade px-4 py-5" id="childProducts" style="margin-top: 0;">
    @if ($product->children->isNotEmpty())
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>{{ localize('Image') }}</th>
                        <th>{{ localize('Slug') }}</th>
                        <th>{{ localize('Nom') }}</th>
                        <th>{{ localize('Volume Total') }}</th>
                        <th>{{ localize('Dimension') }}</th>
                        <th>{{ localize('Couleur') }}</th>
                        <th>{{ localize('Quantité') }}</th>
                        <th>{{ localize('Disponibilité') }}</th>
                        <th>{{ localize('Prix HT') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->children as $childProduct)
                        <tr>
                            <td class="text-center">
                                @if($childProduct->thumbnail_image)
                                    <img src="{{ uploadedAsset($childProduct->thumbnail_image) }}" alt="{{ $childProduct->name }}" class="img-fluid" style="max-width: 40px; max-height: 40px;">
                                @else
                                    {{ localize('Pas d\'image') }}
                                @endif
                            </td>
                            <td>{{ $childProduct->slug }}</td>
                            <td>{{ $childProduct->name }}</td>
                            <td>{{ $childProduct->total_volume }}</td>
                            <td>{{ $childProduct->dimensions }}</td>
                            <td>{{ $childProduct->color }}</td>
                            <td>{{ $childProduct->Qty_Unit }}</td>
                            <td>
                                @if($childProduct->stock_qty > 0)
                                    <span class="text-success h4">&bull;</span>
                                @else
                                    <span class="text-danger h4">&bull;</span>
                                @endif
                            </td>
                            <td>{{ formatPrice($childProduct->min_price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
        </div>
    @else
        <div class="text-dark text-center border py-2">{{ localize('Non disponible') }}</div>
    @endif
</div>



        
    </div>
    </div>
