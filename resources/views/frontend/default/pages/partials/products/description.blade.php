<div class="product-info-tab bg-white rounded-2 overflow-hidden pt-6 mt-4">
    <ul class="nav nav-tabs border-bottom justify-content-center gap-5 pt-info-tab-nav">
        <li><a href="#description" class="active" data-bs-toggle="tab">{{ localize('Description') }}</a></li>
        <!-- <li><a href="#childProducts"  data-bs-toggle="tab">{{ localize('Réferences') }}</a></li> -->

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
        @if ($product->description)
                {!! $product->collectLocalization('Infos_complementaires') !!}
            @else
                <div class="text-dark text-center border py-2">{{ localize('Non disponible') }}</div>
            @endif
            </div>

        </div>
        
        <div class="tab-pane fade px-4 py-5" id="ficheTechnique">
            <div class="thumbnail position-relative text-center p-4">
                @if ($product->fiche_technique)
                <a href="{{ asset('public/storage/' . $product->fiche_technique) }}" target="_blank" class="btn btn-primary">
            <i class="fas fa-file-pdf"></i> {{ localize('Consulter') }}</a>
                @else
                    <div class="text-dark text-center border py-2">{{ localize('Non disponible') }}</div>
                @endif
            </div>
        </div>

        <!-- Réferences Tab Content
        <div class="tab-pane fade px-4 py-5" id="childProducts" style="margin-top: 0;">
            @if ($product->children->isNotEmpty())
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>{{ localize('Image') }}</th>
                                <th>{{ localize('Réference') }}</th>
                                <th>{{ localize('Nom') }}</th>
                                <th>{{ localize('Volume') }}</th>
                                <th>{{ localize('Dimension') }}</th>
                                <th>{{ localize('Couleur') }}</th>
                                <th>{{ localize('Quantité') }}</th>
                                <th>{{ localize('Disponibilité') }}</th>
                                <th>{{ localize('Prix HT') }}</th>
                                <th>{{ localize('Fiche Technique') }}</th> 
                                <th>{{ localize('Action') }}</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->children as $childProduct)
                                <tr>
                                    <td class="align-middle">
                                        @if($childProduct->thumbnail_image)
                                            <a href="{{ route('products.show', $childProduct->slug) }}">
                                                <img src="{{ uploadedAsset($childProduct->thumbnail_image) }}" alt="{{ $childProduct->name }}" class="img-fluid" style="max-width: 40px; max-height: 40px;">
                                            </a>
                                        @else
                                        {{ $childProduct->slug }}
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('products.show', $childProduct->slug) }}">{{ $childProduct->slug }}</a>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('products.show', $childProduct->slug) }}">{{ $childProduct->name }}</a>
                                    </td>
                                    <td class="align-middle">{{ $childProduct->total_volume }}</td>
                                    <td class="align-middle">{{ $childProduct->dimensions }}</td>
                                    <td class="align-middle">{{ $childProduct->color }}</td>
                                    <td class="align-middle">{{ $childProduct->Qty_Unit }}</td>
                                    <td class="align-middle">
                                        @if($childProduct->stock_qty > 0)
                                            <span class="text-success h1">&bull;</span>
                                        @else
                                            <span class="text-danger h1">&bull;</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ formatPrice($childProduct->min_price) }}</td>
                                    <td class="align-middle">  
                                         Fiche Technique  
                                        <a href="" class="btn btn-info btn-sm">
                                            <i class="fas fa-file-pdf fa-sm"></i> 
                                        </a>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <button type="button" class="btn btn-primary btn-sm me-2" onclick="showProductDetailsModal({{ $childProduct->id }})">
                                                <i class="fas fa-shopping-cart fa-sm"></i>
                                            </button> 

                                            <button type="button" class="btn btn-success btn-sm" onclick="addToWishlist({{ $childProduct->id }})">
                                                <i class="fas fa-heart fa-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-dark text-center border py-2">{{ localize('Non disponible') }}</div>
            @endif
        </div> -->

        
    </div>
</div>

<!-- <script>
    // Script to handle quantity increase and decrease
    function handleQuantity(action, productId) {
        const quantityInput = document.getElementById(`quantityInput_${productId}`);
        let currentQuantity = parseInt(quantityInput.value);

        if (action === 'increase') {
            currentQuantity++;
        } else if (action === 'decrease' && currentQuantity > 1) {
            currentQuantity--;
        }

        quantityInput.value = currentQuantity;
    }

</script>  -->
