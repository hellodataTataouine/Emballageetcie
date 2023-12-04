@auth
    @if (productBasePrice($product) == discountedProductBasePrice($product))
        @if (productBasePrice($product) == productMaxPrice($product))
        
            
               
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold text-muted">Prix HT:</span>
                        <span class="fw-bold text-danger">{{ formatPrice($product->Prix_HT) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold text-muted">Prix TTC:</span>
                        <span class="fw-bold text-danger">{{ formatPrice(productBasePrice($product)) }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold text-muted">Unité:</span>
                        <span class="fw-bold text-danger">{{ $product->Unit }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold text-muted">Qté Unité:</span>
                        <span class="fw-bold text-danger">{{ $product->Qty_Unit }}</span>
                    </div>
                </div>
                
               
            
        @else
            <span class="fw-bold h4 text-danger">{{ formatPrice(productBasePrice($product)) }}</span>
            -
            <span class="fw-bold h4 text-danger">{{ formatPrice(productMaxPrice($product)) }}</span>
        @endif
    @else
        @if (discountedProductBasePrice($product) == discountedProductMaxPrice($product))
            <span class="fw-bold h4 text-danger">{{ formatPrice(discountedProductBasePrice($product)) }}</span>
        @else
            <span class="fw-bold h4 text-danger">{{ formatPrice(discountedProductBasePrice($product)) }}</span>
            -
            <span class="fw-bold h4 text-danger">{{ formatPrice(discountedProductMaxPrice($product)) }}</span>
        @endif

        @if (isset($br))
            <br>
        @endif

        @if (!isset($onlyPrice) || $onlyPrice == false)
            @if (productBasePrice($product) == productMaxPrice($product))
               
            <div>
                <span class="fw-bold h4 text-muted">Prix HT:</span>
                <span class="fw-bold h4 text-danger">{{ formatPrice($product->Prix_HT) }}</span>
            </div>
            <div>
                <span class="fw-bold h4 text-muted">Prix TTC:</span>
                <span class="fw-bold h4 text-danger">{{ formatPrice(productBasePrice($product)) }}</span>
            </div>
            @else
                <span
                    class="fw-bold h4 deleted text-muted {{ isset($br) ? '' : 'ms-1' }}">{{ formatPrice(productBasePrice($product)) }}</span>
                -
                <span class="fw-bold h4 deleted text-muted ms-1">{{ formatPrice(productMaxPrice($product)) }}</span>
            @endif
        @endif
    @endif

    @if ($product->unit)
        <small>/{{ $product->unit->collectLocalization('name') }}</small>
    @endif
@endauth
