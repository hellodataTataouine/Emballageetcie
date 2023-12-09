@forelse ($carts as $cart)
    <tr>
        <td class="h-100px">
            <img src="{{ uploadedAsset($cart->product_variation->product->thumbnail_image) }}"
                alt="{{ $cart->product_variation->product->collectLocalization('name') }}" class="img-fluid"
                width="100">
        </td>
        <td class="text-start product-title">
            <h6 class="mb-0">{{ $cart->product_variation->product->collectLocalization('name') }}
            </h6>
            @foreach (generateVariationOptions($cart->product_variation->combinations) as $variation)
                <span class="fs-xs">
                    {{ $variation['name'] }}:
                    @foreach ($variation['values'] as $value)
                        {{ $value['name'] }}
                    @endforeach
                    @if (!$loop->last)
                        ,
                    @endif
                </span>
            @endforeach
        </td>
        <td>
            <span class="text-dark fw-bold me-2 d-lg-none">{{ localize('Prix unitaire') }}:</span>
            <span class="text-dark fw-bold">
                {{ formatPrice($cart->product_price) }}
            </span>
        </td>

        <td>
            <div class="product-qty d-inline-flex align-items-center">
                <button class="decrese" onclick="handleCartItem('decrease',{{ $cart->id }})">-</button>
                <input type="text" readonly value="{{ $cart->qty }}">
                <button class="increase" onclick="handleCartItem('increase', {{ $cart->id }})">+</button>
            </div>
        </td>

        <td>
            <span class="text-dark fw-bold me-2 d-lg-none">{{ localize('Prix Total') }}:</span>
            <span class="text-dark fw-bold">
                {{ formatPrice(variationDiscountedPrice($cart->product_variation->product, $cart->product_price) * $cart->qty) }}
            </span>
        </td>
        <td>
            <span class="text-dark fw-bold me-2 d-lg-none">{{ localize('Supprimer') }}:</span>
            <span class="text-dark fw-bold"><button type="button" class="close-btn ms-3"
                    onclick="handleCartItem('delete', {{ $cart->id }})"><i
                        class="fas fa-close"></i></button></span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="py-4">{{ localize('Aucune donnée trouvée') }}</td>
    </tr>
@endforelse
