@extends('backend.layouts.master')

@section('title')
    {{ localize('Configurer les points de récompense') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection


@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Configurer les points de récompense') }}</h2>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">


                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1 pb-650">

                    <!--products-->
                    <div class="col-12">
                        <div class="card mb-4" id="section-1">
                            <form class="app-search" action="{{ Request::fullUrl() }}" method="GET">
                                <div class="card-header border-bottom-0">
                                    <div class="row justify-content-between g-3">
                                        <div class="col-auto flex-grow-1">
                                            <div class="tt-search-box">
                                                <div class="input-group">
                                                    <span class="position-absolute top-50 start-0 translate-middle-y ms-2">
                                                        <i data-feather="search"></i></span>
                                                    <input class="form-control rounded-start w-100" type="text"
                                                        id="search" name="search" placeholder="{{ localize('Recherche') }}"
                                                        @isset($searchKey)
                                                    value="{{ $searchKey }}"
                                                    @endisset>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-secondary">
                                                <i data-feather="search" width="18"></i>
                                                {{ localize('Recherche') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <table class="table tt-footable border-top align-middle" data-use-parent-width="true">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{ localize('S/L') }}
                                        </th>
                                        <th>{{ localize('Nom du produit') }}</th>
                                        <th data-breakpoints="xs sm">{{ localize('Prix de base') }}</th>
                                        <th data-breakpoints="xs sm md" class="text-end">{{ localize('Points') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td class="text-center">
                                                {{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                            <td>
                                                <a href="javascript:void(0);" class="d-flex align-items-center"
                                                    target="_blank">
                                                    <div class="avatar avatar-sm">
                                                        <img class="rounded-circle"
                                                            src="{{ uploadedAsset($product->thumbnail_image) }}"
                                                            alt=""
                                                            onerror="this.onerror=null;this.src='{{ staticAsset('backend/assets/img/placeholder-thumb.png') }}';" />
                                                    </div>
                                                    <h6 class="fs-sm mb-0 ms-2">{{ $product->collectLocalization('name') }}
                                                    </h6>
                                                </a>
                                            </td>

                                            <td>
                                                <div class="tt-tb-price fs-sm fw-bold">
                                                    <span class="text-accent">

                                                        {{ formatPrice($product->min_price) }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="text-end">
                                                <input type="number" min="0" value="{{ $product->reward_points }}"
                                                    data-product="{{ $product->id }}" class="form-control points-input">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!--pagination start-->
                            <div class="d-flex align-items-center justify-content-between px-4 pb-4">
                                <span>{{ localize('Affichage') }} 
                                    {{ $products->firstItem() }}-{{ $products->lastItem() }} {{ localize('sur') }} 
                                    {{ $products->total() }} {{ localize('résultats') }}  </span>
                                <nav>
                                    {{ $products->appends(request()->input())->links() }}
                                </nav>
                            </div>
                            <!--pagination end-->
                        </div>
                    </div>
                    <!--products end-->


                    <form action="{{ route('admin.rewards.storePoints') }}" method="POST" class="mb-4">
                        @csrf

                        <input type="hidden" name="points_for" value="price_range_wise">
                        <!--basic information start-->
                        <div class="card mb-4" id="section-2">
                            <div class="card-body">
                                <h5 class="mb-3">{{ localize('Selon la plage de prix') }}</h5>

                                <div class="mb-3">
                                    <label for="points" class="form-label">{{ localize('Points') }}</label>
                                    <input class="form-control" type="number" min="0" id="points"
                                        placeholder="{{ localize('Saisir les points de récompense') }}" name="points"
                                        value="{{ getSetting('points') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="min_price" class="form-label">{{ localize('Prix minimum') }}</label>
                                    <input class="form-control" type="number" min="0" id="min_price"
                                        name="min_price" required>
                                </div>
                                <div class="mb-3">
                                    <label for="max_price" class="form-label">{{ localize('Prix maximum') }}</label>
                                    <input class="form-control" type="number" min="0" id="max_price"
                                        name="max_price" required>
                                </div>
                            </div>
                        </div>
                        <!--basic information end-->

                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="save" class="me-1"></i> {{ localize('Enregistrer Points') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- submit button end -->
                    </form>


                    <form action="{{ route('admin.rewards.storePoints') }}" method="POST" class="pb-650">
                        @csrf
                        <input type="hidden" name="points_for" value="all">
                        <div class="card mb-4" id="section-3">
                            <div class="card-body">
                                <h5 class="mb-3">{{ localize('Pour tous les produits') }}</h5>
                                <div class="mb-3">
                                    <label for="points" class="form-label">{{ localize('Points') }}</label>
                                    <input class="form-control" type="number" min="0" id="points"
                                        placeholder="{{ localize('Saisir les points de récompense') }}" name="points"
                                        value="{{ getSetting('points') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="save" class="me-1"></i> {{ localize('Enregistrer Points') }}
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
                            <h5 class="mb-3">{{ localize('Points de récompense') }}</h5>
                            <div class="tt-vertical-step">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#section-1" class="active">{{ localize('Pour chaque produit') }}</a>
                                    </li>

                                    <li>
                                        <a href="#section-2" class="">{{ localize('Plage de prix') }}</a>
                                    </li>

                                    <li>
                                        <a href="#section-3" class="">{{ localize('Pour tous les produits') }}</a>
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
