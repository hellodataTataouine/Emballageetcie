<div class="sidebar-widget py-6 px-4 bg-white rounded-2">
    <div class="widget-title d-flex">
        <h5 class="mb-0 flex-shrink-0">{{ localize('Résumé de la commande') }}</h5>
        <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
    </div>
    <table class="sidebar-table w-100 mt-5">
        <tr>
            <td>(+) {{ localize('Articles') }}({{ count($carts) }}):</td>
            <td class="text-end">{{ formatPrice(getSubTotal($carts, false, '', false)) }}</td>
        </tr>

        <!-- <tr>
            <td>(+) {{ localize('Taxe') }}:</td>
            <td class="text-end">{{ formatPrice(getTotalTax($carts)) }}</td>
        </tr> -->
        @if(isset($shippingFranco) &&  getSubTotal($carts, false, '', false) >= $shippingFranco)
            <td>(+) {{ localize('Frais d\'expédition') }}:</td>
                <td class="text-end"> 0 €</td>
                <input type="hidden" name="shipping_franco" value="true">

        @elseif (isset($shippingAmount))
            <tr>
                <td>(+) {{ localize('Frais d\'expédition') }}:</td>
                <td class="text-end">{{ formatPrice($shippingAmount) }}</td>
            </tr>
        @endif

        @php
            $is_free_shipping = false;
            if (getCoupon() != '' && getCouponDiscount(getSubTotal($carts, false), getCoupon()) > 0) {
                $coupon = \App\Models\Coupon::where('code', getCoupon())->first();
                if (!is_null($coupon) && $coupon->is_free_shipping == 1) {
                    $is_free_shipping = true;
                }
            }
        @endphp

        @php
            $shipping = 0;
            if(isset($shippingFranco) &&  getSubTotal($carts, false, '', false) >= $shippingFranco){
                $shipping = 0 ;
            }
            else if (isset($shippingAmount) && $is_free_shipping == false) {
                $shipping = $shippingAmount;
            }
            
            $total = getSubTotal($carts, false, '', false) + getTotalTax($carts) + $shipping - getCouponDiscount(getSubTotal($carts, false), getCoupon());
        @endphp


        @if (getCoupon() != '')

            @if (getCouponDiscount(getSubTotal($carts, false), getCoupon()) > 0)
                <tr>
                    <td>(-) {{ localize('Remise de coupon') }}:</td>
                    <td class="text-end">{{ formatPrice(getCouponDiscount(getSubTotal($carts, false), getCoupon())) }}
                    </td>
                </tr>
            @endif

            @if ($is_free_shipping && isset($shippingAmount))
                <tr>
                    <td>(-) {{ localize('Remise sur la livraison') }}:</td>
                    <td class="text-end">{{ formatPrice($shippingAmount) }}
                    </td>
                </tr>
            @endif
        @endif
    </table>

    <span class="sidebar-spacer d-block my-4 opacity-50"></span>

    <div class="d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fs-md">{{ localize('Total') }}</h6>
        <h6 class="mb-0 fs-md">{{ formatPrice($total) }}</h6>
    </div>

    <span class="sidebar-spacer d-block my-4 opacity-50"></span>

    <div class="label-input-field mt-6">
      
    @if(isset($shippingFranco) )

    <p>Livraison gratuit à partir de {{ formatPrice($shippingFranco) }} </p>
@endif

    </div> 

    <!-- <button type="submit" class="btn btn-primary btn-md rounded mt-6 w-100" onclick="this.disabled=true;this.form.submit();">{{ localize('Passer la Commande') }}</button> -->
     <button type="submit" class="btn btn-primary btn-md rounded mt-6 w-100" >{{ localize('Passer la Commande') }}</button>


</div>
