@extends('backend.layouts.master')

@section('title')
    {{ localize('Produits') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
    
@endsection

@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Produits') }}</h2>
                                
                            </div>
                            <!-- <div class="tt-action">
                                <a href="{{ route('admin.products.Synchronize') }}" class="btn btn-success">
                                <i data-feather="refresh-cw"></i> {{ localize('Synchroniser Les nomations des Produits') }}</a>

                            </div> -->
                            <!-- <div class="tt-action">
                                @can('add_products')
                                    <a href="{{ route('admin.products.Synchronize') }}" class="btn btn-primary"><i class="fas fa-sync-alt"></i> {{ localize('Synchroniser les Produits') }}</a>
                                            
                                @endcan
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <div class="card mb-4" id="section-1">
                        <form class="app-search" action="{{ Request::fullUrl() }}" method="GET">
                            <div class="card-header border-bottom-0">
                                <div class="row justify-content-between g-3">
                                    <div class="col-auto flex-grow-1">
                                        <div class="tt-search-box">
                                            <div class="input-group">
                                                <span class="position-absolute top-50 start-0 translate-middle-y ms-2"> <i
                                                        data-feather="search"></i></span>
                                                <input class="form-control rounded-start w-100" type="text"
                                                    id="search" name="search" placeholder="{{ localize('Rechercher') }}"
                                                    
                                                    @isset($searchKey)
                                                value="{{ $searchKey }}"
                                                @endisset>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-auto">
                                        <div class="input-group">
                                            <select class="form-select select2" name="brand_id">
                                                <option value="">{{ localize('Sélectionner la marque') }}</option>
                                                
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        @isset($brand_id)
                                                         @if ($brand_id == $brand->id) selected @endif
                                                        @endisset>
                                                        {{ $brand->collectLocalization('name') }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="col-auto">
                                        <div class="input-group">
                                            <select class="form-select select2" name="is_published"
                                                data-minimum-results-for-search="Infinity">
                                                <option value="">{{ localize('Sélectionner le statut') }}</option>
                                                
                                                <option value="1"
                                                    @isset($is_published)
                                                         @if ($is_published == 1) selected @endif
                                                        @endisset>
                                                    {{ localize('Publié') }}</option>
                                                   
                                                <option value="0"
                                                    @isset($is_published)
                                                         @if ($is_published == 0) selected @endif
                                                        @endisset>
                                                    {{ localize('Non Publié') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-secondary">
                                            <i data-feather="search" width="18"></i>
                                            {{ localize('Rechercher') }}
                                           
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <table class="table tt-footable border-top" data-use-parent-width="true">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ localize('S/L') }}
                                    </th>
                                    <th>{{ localize('Nom du produit') }}</th>
                                    <th>{{ localize('Référence') }}</th>
                                    <!-- <th data-breakpoints="xs sm">{{ localize('Marque ') }}</th> -->
                                    <th data-breakpoints="xs sm">{{ localize('P.Equivalents') }}</th>

                                    <th data-breakpoints="xs sm">{{ localize('Catégories') }}</th>
                                   <!-- <th data-breakpoints="xs sm">{{ localize('Prix ') }}</th> -->
                                    <th data-breakpoints="xs sm md">{{ localize('Statut ') }}</th>
                                    <th data-breakpoints="xs sm">{{ localize('Afficher') }}</th>
                                    <th data-breakpoints="xs sm md" class="text-end">{{ localize('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($paginatedProducts->isEmpty())
                                    
                                @else
                                
                                @foreach ($paginatedProducts as $key => $product)
                                    <tr>
                                        <td class="text-center">
                                            {{ $key + 1 + ($paginatedProducts->currentPage() - 1) * $paginatedProducts->perPage() }} </td>
                                        <td>
                                            <a href="{{ route('products.show', $product->slug) }}"
                                                class="d-flex align-items-center" target="_blank">
                                                <div class="avatar avatar-sm">
                                                    <img class="rounded-circle"
                                                        src="{{ uploadedAsset($product->thumbnail_image) }}" alt=""
                                                        onerror="this.onerror=null;this.src='{{ staticAsset('backend/assets/img/placeholder-thumb.png') }}';" />
                                                </div>
                                                <h6 class="fs-sm mb-0 ms-2">{{ $product->name }}
                                                </h6>
                                            </a>
                                        </td>
                                       <!-- <td>
                                            <span
                                                class="fs-sm">{{ optional($product->brand)->collectLocalization('name') }}</span>
                                        </td> -->
                                          <td>
                                            <span
                                                class="fs-sm">{{ $product->collectLocalization('slug') }}</span>
                                        </td> 
                                       
                                        <td>
                                       
                                        <span
                                                class="fs-sm">
                                        {{ __(':count', ['count' => $product->parents()->count()]) }}
                                        </span>
                              

                                        </td>
                                        <td>
                                            @forelse ($product->categories as $category)
                                                <span
                                                    class="badge rounded-pill bg-secondary">{{ $category->collectLocalization('name') }}</span>

                                            @empty
                                                <span class="badge rounded-pill bg-secondary">{{ localize('N/A') }}</span>
                                            @endforelse
                                        </td>
                                       <!-- <td>
                                            <div class="tt-tb-price fs-sm fw-bold">
                                                <span class="text-accent">
                                                    @if ($product->max_price != $product->min_price)
                                                        {{ $product->min_price }}
                                                        -
                                                        {{ $product->max_price }}
                                                    @else
                                                        {{ $product->min_price }}
                                                    @endif
                                                </span>
                                            </div>
                                        </td> -->
                                       <!--  <td>
                                            @can('publish_products')
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" onchange="updatePublishedStatus(this)"
                                                        class="form-check-input"
                                                        @if ($product->is_published) checked @endif
                                                        value="{{ $product->id }}">
                                                </div>
                                            @endcan

                                        </td> -->

                                        <td>
                                            @if ($product->is_published)
                                                <span class="badge rounded-pill bg-success">{{ localize('Publié') }}</span>
                                            @else
                                                <span class="badge rounded-pill bg-secondary">{{ localize('Non Publié') }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            @can('aficher_products')
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" onchange="updateAfficherStatus(this)"
                                                        class="form-check-input"
                                                        @if ($product->afficher) checked @endif
                                                        value="{{ $product->id }}"
                                                        @if ($product->is_published == 0) disabled @endif>
                                                </div>
                                            @endcan
                                        </td>
                                        

                                        <td class="text-end">
                                            <div class="dropdown tt-tb-dropdown">
                                                <button type="button" class="btn p-0" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end shadow">
                                                    @can('edit_products')
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.products.edit', ['id' => $product->id, 'lang_key' => env('DEFAULT_LANGUAGE')]) }}">
                                                            <i data-feather="edit-3" class="me-2"></i>{{ localize('Modifier') }}
                                                        </a>
                                                    @endcan

                                                    <a class="dropdown-item"
                                                        href="{{ route('products.show', $product->slug) }}"
                                                        target="_blank">
                                                        <i data-feather="eye"
                                                            class="me-2"></i>{{ localize('Voir Détailles') }}
                                                            
                                                    </a>
                                                     <!-- Add the delete button -->
                                                @can('delete_products')
                                                    @if ($product->is_published == 0)
                                                        <a class="dropdown-item"  href="#" data-href="{{ route('admin.products.delete', $product->id) }}" onclick="confirmDelete(this)">
                                                            <i data-feather="trash" class="me-2"></i>{{ localize('Supprimer') }}
                                                        </a> 
                                                    @endif
                                                @endcan
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <!--pagination start-->
                        <div class="d-flex align-items-center justify-content-between px-4 pb-4">
                            <span>{{ localize('Affichage') }}  {{ localize('de') }}
                                {{ $paginatedProducts->firstItem() }}-{{ $paginatedProducts->lastItem() }} {{ localize('sur') }}
                                {{ $paginatedProducts->total() }} {{ localize('résultats') }} </span>
                            <span>{{ localize('Affichage') }}  {{ localize('de') }}
                                {{ $paginatedProducts->firstItem() }}-{{ $paginatedProducts->lastItem() }} {{ localize('sur') }}
                                {{ $paginatedProducts->total() }} {{ localize('résultats') }} </span>
                            <nav>
                                {{ $paginatedProducts->appends(request()->input())->links() }}
                            </nav>
                        </div>
                        <!--pagination end-->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        "use strict"

        // update feature status
        function updateFeatureStatus(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('admin.products.updateFeatureStatus') }}', {
                    _token: '{{ csrf_token() }}',
                    id: el.value,
                    status: status
                },
                function(data) {
                    if (data == 1) {
                        notifyMe('success', '{{ localize('Statut mis à jour avec succès') }}');
                    } else {
                        notifyMe('danger', '{{ localize('Something went wrong') }}');
                    }
                });
        }

        // update publish status 
        function updatePublishedStatus(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('admin.products.updatePublishedStatus') }}', {
                    _token: '{{ csrf_token() }}',
                    id: el.value,
                    status: status
                },
                function(data) {
                    if (data == 1) {
                        notifyMe('success', '{{ localize('Statut mis à jour avec succès') }}');
                    } else {
                        notifyMe('danger', '{{ localize('Something went wrong') }}');
                    }
                });
        }

        function updateAfficherStatus(el) {
            var productId = el.value;
            var status = el.checked ? 1 : 0;

            $.post('{{ route('admin.products.updateAfficherStatus') }}', {
                _token: '{{ csrf_token() }}',
                id: productId,
                status: status
            }, function (data) {
                if (data == 1) {
                    var message = (status === 1) ? '{{ localize('Produit affiché avec succès') }}' : '{{ localize('Produit masqué avec succès') }}';
                    notifyMe('success', message);
                } else {
                    notifyMe('danger', '{{ localize('Something went wrong') }}');
                }
            });
        }

    </script>
@endsection
