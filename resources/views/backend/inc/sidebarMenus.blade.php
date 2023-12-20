<ul class="tt-side-nav">

    <!-- dashboard -->
    <li class="side-nav-item nav-item">
        <a href="{{ route('admin.dashboard') }}" class="side-nav-link"> 
            <span class="tt-nav-link-icon"><i data-feather="pie-chart"></i></span>
            <span class="tt-nav-link-text">{{ localize('Tableau de bord') }}</span>
            
        </a>
    </li>

    <!-- products -->
    @php
        $productsActiveRoutes = ['admin.brands.index', 'admin.brands.edit', 'admin.units.index', 'admin.units.edit', 'admin.variations.index', 'admin.variations.edit', 'admin.variationValues.index', 'admin.variationValues.edit', 'admin.taxes.index', 'admin.taxes.edit', 'admin.categories.index', 'admin.categories.create', 'admin.categories.edit', 'admin.products.index', 'admin.products.create', 'admin.products.edit'];
    @endphp

    @canany(['products', 'categories', 'variations', 'brands', 'units', 'taxes'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($productsActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#sidebarProducts"
                aria-expanded="{{ areActiveRoutes($productsActiveRoutes, 'true') }}" aria-controls="sidebarProducts"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="shopping-bag"></i></span>
                <span class="tt-nav-link-text">{{ localize('Produits') }}</span>
                
            </a>

            <div class="collapse {{ areActiveRoutes($productsActiveRoutes, 'show') }}" id="sidebarProducts">
                <ul class="side-nav-second-level">

                    @can('products')
                        <li
                            class="{{ areActiveRoutes(['admin.products.index', 'admin.products.create', 'admin.products.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.products.index') }}"
                                class="{{ areActiveRoutes(['admin.products.index', 'admin.products.create', 'admin.products.edit']) }}">{{ localize('Produits') }}</a>
                                
                        </li>
                    @endcan

                    @can('categories')
                        <li
                            class="{{ areActiveRoutes(['admin.categories.index', 'admin.categories.create', 'admin.categories.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.categories.index') }}"
                                class="{{ areActiveRoutes(['admin.categories.index', 'admin.categories.create', 'admin.categories.edit']) }}">{{ localize('Catégories') }}</a>
                               
                        </li>
                    @endcan

                    <!-- @can('variations')
                        <li
                            class="{{ areActiveRoutes(
                                ['admin.variations.index', 'admin.variations.edit', 'admin.variationValues.index', 'admin.variationValues.edit'],
                                'tt-menu-item-active',
                            ) }}">
                            <a href="{{ route('admin.variations.index') }}"
                                class="{{ areActiveRoutes([
                                    'admin.variations.index',
                                    'admin.variations.edit',
                                    'admin.variationValues.index',
                                    'admin.variationValues.edit',
                                ]) }}">{{ localize('Variations') }}</a>
                        </li>
                    @endcan -->

                    <!-- @can('brands')
                        <li class="{{ areActiveRoutes(['admin.brands.index', 'admin.brands.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.brands.index') }}"
                                class="{{ areActiveRoutes(['admin.brands.index', 'admin.brands.edit']) }}">{{ localize('Marques') }}</a>
                                
                        </li>
                    @endcan -->

                    <!-- @can('units')
                        <li class="{{ areActiveRoutes(['admin.units.index', 'admin.units.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.units.index') }}"
                                class="{{ areActiveRoutes(['admin.units.index']) }}">{{ localize('Unités') }}</a>
                                
                        </li>
                    @endcan -->

                    <!-- @can('taxes')
                        <li class="{{ areActiveRoutes(['admin.taxes.index', 'admin.taxes.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.taxes.index') }}"
                                class="{{ areActiveRoutes(['admin.taxes.index']) }}">{{ localize('Taxes') }}</a>
                                
                        </li>
                    @endcan -->
                </ul>
            </div>
        </li>
    @endcan


    @php
    $catalogActiveRoutes = ['admin.catalogues.create', 'admin.catalogues.index', 'admin.catalogues.edit'];
@endphp
<!--
@canany(['add_catalog', 'list_catalogues'])
    <li class="side-nav-item nav-item {{ areActiveRoutes($catalogActiveRoutes, 'tt-menu-item-active') }}">
        <a data-bs-toggle="collapse" href="#manageCatalog"
            aria-expanded="{{ areActiveRoutes($catalogActiveRoutes, 'true') }}" aria-controls="manageCatalog"
            class="side-nav-link tt-menu-toggle">
            <span class="tt-nav-link-icon"><i data-feather="book"></i></span>
            <span class="tt-nav-link-text">{{ localize('Catalogues') }}</span>
        </a>
        <div class="collapse {{ areActiveRoutes($catalogActiveRoutes, 'show') }}" id="manageCatalog">
            <ul class="side-nav-second-level">

                @can('add_catalog')
                    <li class="{{ areActiveRoutes(['admin.catalogues.create'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.catalogues.create') }}">{{ localize('Ajouter Catalogue') }}</a>
                    </li>
                @endcan

                @can('list_catalogues')
                    <li
                        class="{{ areActiveRoutes(['admin.catalogues.index', 'admin.catalogues.edit'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.catalogues.index') }}">{{ localize('Catalogues') }}</a>
                    </li>
                @endcan
            </ul>
        </div>
    </li>
@endcan -->



    <!-- pos 
    @canany(['pos'])
        <li class="side-nav-item nav-item">
            <a href="{{ route('admin.pos.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"><i data-feather="table"></i></span>
                <span class="tt-nav-link-text">{{ localize('Pos Système') }}</span>
                
            </a>
        </li>
    @endcan -->

    <!-- orders -->
    @can('orders')
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['admin.orders.index', 'admin.orders.show'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.orders.index') }}"
                class="side-nav-link {{ areActiveRoutes(['admin.orders.index', 'admin.orders.show']) }}">
                <span class="tt-nav-link-icon"><i data-feather="shopping-cart"></i></span>
                <span class="tt-nav-link-text">
                    <span>{{ localize('Commandes') }}</span>
                    

                    @php
                        $newOrdersCount = \App\Models\Order::isPlaced()->count();
                    @endphp

                    @if ($newOrdersCount > 0)
                        <small class="badge bg-danger">{{ localize('Nouveau') }}</small>
                        
                    @endif
                </span>
            </a>
        </li>
    @endcan
     <!-- Support -->
     <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('Support') }}</span>
    </li>

    @can('contact_us_messages')
        <li class="side-nav-item nav-item {{ areActiveRoutes(['admin.queries.index'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.queries.index') }}"
                class="side-nav-link {{ areActiveRoutes(['admin.queries.index']) }}">
                <span class="tt-nav-link-icon"><i data-feather="mail"></i></span>
                <span class="tt-nav-link-text">
                    <span>{{ localize('Messages') }}</span>
                    

                    @php
                        $newMsgCount = \App\Models\ContactUsMessage::where('is_seen', 0)->count();
                    @endphp

                    @if ($newMsgCount > 0)
                        <small class="badge bg-danger">{{ localize('Nouveau') }}</small>
                         


                        
                    @endif
                </span>
            </a>
        </li>
    @endcan


    <!-- stock 
    @php
        $stockActiveRoutes = ['admin.stocks.create', 'admin.locations.index', 'admin.locations.create', 'admin.locations.edit'];
    @endphp
    @canany(['add_stock', 'show_locations'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($stockActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#manageStock"
                aria-expanded="{{ areActiveRoutes($stockActiveRoutes, 'true') }}" aria-controls="manageStock"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="database"></i></span>
                <span class="tt-nav-link-text">{{ localize('Stocks') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($stockActiveRoutes, 'show') }}" id="manageStock">
                <ul class="side-nav-second-level">

                    @can('add_stock')
                        <li class="{{ areActiveRoutes(['admin.stocks.create'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.stocks.create') }}"
                                class="{{ areActiveRoutes(['admin.stocks.create']) }}">{{ localize('Ajouter Stock') }}</a>
                                
                        </li>
                    @endcan

                    @can('show_locations')
                        <li
                            class="{{ areActiveRoutes(['admin.locations.index', 'admin.locations.create', 'admin.locations.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.locations.index') }}">{{ localize('Emplacements') }}</a>
                            
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan -->


    <!-- Refunds 
    @php
        $refundsActiveRoutes = ['admin.refund.configurations', 'admin.refund.requests', 'admin.refund.refunded', 'admin.refund.rejected'];
    @endphp

    @canany(['refund_configurations', 'refund_requests', 'approved_refunds', 'rejected_refunds'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($refundsActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#manageRefunds"
                aria-expanded="{{ areActiveRoutes($refundsActiveRoutes, 'true') }}" aria-controls="manageRefunds"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="corner-up-left"></i></span>
                <span class="tt-nav-link-text">{{ localize('Remboursements') }}</span>
                
            </a>
            <div class="collapse {{ areActiveRoutes($refundsActiveRoutes, 'show') }}" id="manageRefunds">
                <ul class="side-nav-second-level">

                    @can('refund_configurations')
                        <li class="{{ areActiveRoutes(['admin.refund.configurations'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.refund.configurations') }}"
                                class="{{ areActiveRoutes(['admin.refund.configurations']) }}">{{ localize('Configurations de remboursement') }}</a>
                                
                        </li>
                    @endcan

                    @can('refund_requests')
                        <li class="{{ areActiveRoutes(['admin.refund.requests'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.refund.requests') }}">{{ localize('Demandes de remboursement') }}</a>
                            
                        </li>
                    @endcan

                    @can('approved_refunds')
                        <li class="{{ areActiveRoutes(['admin.refund.refunded'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.refund.refunded') }}">{{ localize('Remboursements approuvés') }}</a>
                            
                        </li>
                    @endcan

                    @can('rejected_refunds')
                        <li class="{{ areActiveRoutes(['admin.refund.rejected'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.refund.rejected') }}">{{ localize('Remboursements rejetés') }}</a>
                            
                        </li>
                    @endcan

                </ul>
            </div>
        </li>
    @endcan -->


    <!-- Rewards & Wallet 
    @php
        $rewardsActiveRoutes = ['admin.rewards.configurations', 'admin.rewards.setPoints', 'admin.wallet.configurations'];
    @endphp
    @canany(['reward_configurations', 'set_reward_points'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($rewardsActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#manageRewards"
                aria-expanded="{{ areActiveRoutes($rewardsActiveRoutes, 'true') }}" aria-controls="manageRewards"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="award"></i></span>
                <span class="tt-nav-link-text">{{ localize('Récompenses & Portefeuille') }}</span>
                
            </a>
            <div class="collapse {{ areActiveRoutes($rewardsActiveRoutes, 'show') }}" id="manageRewards">
                <ul class="side-nav-second-level">

                    @can('reward_configurations')
                        <li class="{{ areActiveRoutes(['admin.rewards.configurations'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.rewards.configurations') }}"
                                class="{{ areActiveRoutes(['admin.rewards.configurations']) }}">{{ localize('Configurations de récompense') }}</a>
                               
                        </li>
                    @endcan

                    @can('set_reward_points')
                        <li class="{{ areActiveRoutes(['admin.rewards.setPoints'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.rewards.setPoints') }}">{{ localize('Définir les points de récompense') }}</a>
                            
                        </li>
                    @endcan

                    @can('wallet_configurations')
                        <li class="{{ areActiveRoutes(['admin.wallet.configurations'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.wallet.configurations') }}"
                                class="{{ areActiveRoutes(['admin.wallet.configurations']) }}">{{ localize('Configurations du portefeuille') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan -->

    <!-- Users -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize(' UTILISATEURS') }}</span>
    </li>

    <!-- customers -->
    @can('customers')
        <li class="side-nav-item nav-item">
            <a href="{{ route('admin.customers.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="users"></i></span>
                <span class="tt-nav-link-text">{{ localize('Clients') }}</span>
            </a>
        </li>
    @endcan

    <!-- staffs -->
    @can('staffs')
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['admin.staffs.index', 'admin.staffs.create', 'admin.staffs.edit'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.staffs.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="user-check"></i></span>
                <span class="tt-nav-link-text">{{ localize('Employés') }}</span>
                
            </a>
        </li>
    @endcan

    <!-- Contents -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('Contenu') }}</span>
        
    </li>

    <!-- tags -->
    @php
        $tagsActiveRoutes = ['admin.tags.index', 'admin.tags.edit'];
    @endphp
    @can('tags')
        <li class="side-nav-item nav-item {{ areActiveRoutes($tagsActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.tags.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="tag"></i></span>
                <span class="tt-nav-link-text">{{ localize('Tags') }}</span>
            </a>
        </li>
    @endcan

    <!-- pages -->
    @php
        $pagesActiveRoutes = ['admin.pages.index', 'admin.pages.create', 'admin.pages.edit'];
    @endphp
    @can('pages')
        <li class="side-nav-item nav-item {{ areActiveRoutes($pagesActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.pages.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="copy"></i></span>
                <span class="tt-nav-link-text">{{ localize('Pages') }}</span>
            </a>
        </li>
    @endcan


    <!-- Blog Systems
    @php
        $blogActiveRoutes = ['admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit', 'admin.blogCategories.index', 'admin.blogCategories.edit'];
    @endphp
    @canany(['blogs', 'blog_categories'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($blogActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#blogSystem"
                aria-expanded="{{ areActiveRoutes($blogActiveRoutes, 'true') }}" aria-controls="blogSystem"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="file-text"></i></span>
                <span class="tt-nav-link-text">{{ localize('Blogs') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($blogActiveRoutes, 'show') }}" id="blogSystem">
                <ul class="side-nav-second-level">
                    @can('blogs')
                        <li
                            class="{{ areActiveRoutes(['admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.blogs.index') }}"
                                class="{{ areActiveRoutes(['admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit']) }}">{{ localize('Blogs') }}</a>
                        </li>
                    @endcan

                    @can('blog_categories')
                        <li
                            class="{{ areActiveRoutes(['admin.blogCategories.index', 'admin.blogCategories.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.blogCategories.index') }}">{{ localize('Catégories') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan -->

    <!-- media manager -->
    @can('media_manager')
        <li class="side-nav-item">
            <a href="{{ route('admin.mediaManager.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="folder"></i></span>
                <span class="tt-nav-link-text">{{ localize('Gestionnaire de médias') }}</span>
                
            </a>
        </li>
    @endcan

    <!-- Promotions 
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('Promotions') }}</span>
    </li> -->
    <!-- newsletter 
    @php
        $newsletterActiveRoutes = ['admin.newsletters.index', 'admin.subscribers.index'];
    @endphp
    @canany(['newsletters', 'subscribers'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($newsletterActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#newsletter"
                aria-expanded="{{ areActiveRoutes($newsletterActiveRoutes, 'true') }}" aria-controls="newsletter"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="map"></i></span>
                <span class="tt-nav-link-text">{{ localize('Newsletters') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($newsletterActiveRoutes, 'show') }}" id="newsletter">
                <ul class="side-nav-second-level">

                    @can('newsletters')
                        <li class="{{ areActiveRoutes(['admin.newsletters.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.newsletters.index') }}"
                                class="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('E-mails en masse') }}</a>
                                
                        </li>
                    @endcan

                    @can('subscribers')
                        <li class="{{ areActiveRoutes(['admin.subscribers.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.subscribers.index') }}"
                                class="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('Abonnés') }}</a>
                                
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan -->

    <!-- Coupons 
    @can('coupons')
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['admin.coupons.index', 'admin.coupons.create', 'admin.coupons.edit'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.coupons.index') }}"
                class="side-nav-link {{ areActiveRoutes(['admin.coupons.index', 'admin.coupons.create', 'admin.coupons.edit']) }}">
                <span class="tt-nav-link-icon"> <i data-feather="scissors"></i></span>
                <span class="tt-nav-link-text">{{ localize('Coupons') }}</span>
            </a>
        </li>
    @endcan

     campaigns
    @can('campaigns')
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['admin.campaigns.index', 'admin.campaigns.create', 'admin.campaigns.edit'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.campaigns.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="zap"></i></span>
                <span class="tt-nav-link-text">{{ localize('Campagnes') }}</span>
                
            </a>
        </li>
        
    @endcan  -->

    <!-- Fulfillment -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('EXÉCUTION') }}</span>
        
    </li>
    <!-- Logistics -->
    @can('logistics')
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['admin.logistics.index', 'admin.logistics.create', 'admin.logistics.edit'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.logistics.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"><i data-feather="cpu"></i></span>
                <span class="tt-nav-link-text">{{ localize('Logistique') }}</span>
                
            </a>
        </li>
    @endcan

    <!-- shipping zones -->
    @php
        $logisticZoneActiveRoutes = ['admin.logisticZones.index', 'admin.logisticZones.create', 'admin.logisticZones.edit', 'admin.countries.index', 'admin.states.index', 'admin.states.create', 'admin.states.edit', 'admin.cities.index', 'admin.cities.create', 'admin.cities.edit'];
    @endphp
    @can('shipping_zones')
        <li class="side-nav-item nav-item {{ areActiveRoutes($logisticZoneActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.logisticZones.index') }}" class="side-nav-link">
                <i class="uil-ship"></i>
                <span class="tt-nav-link-icon"><i data-feather="truck"></i></span>
                <span class="tt-nav-link-text">{{ localize('Zones d expédition') }}</span>
                
            </a>
        </li>
    @endcan

    <!-- Reports -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('RAPPORTS') }}</span>
        
    </li>

    <!-- reports -->
    @php
        $reportActiveRoutes = ['admin.reports.orders', 'admin.reports.sales', 'admin.reports.categorySales', 'admin.reports.salesAmount', 'admin.reports.deliveryStatus'];
    @endphp

    @canany(['order_reports', 'product_sale_reports', 'category_sale_reports', 'sales_amount_reports',
        'delivery_status_reports'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($reportActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#reports"
                aria-expanded="{{ areActiveRoutes($reportActiveRoutes, 'true') }}" aria-controls="reports"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="bar-chart"></i></span>
                <span class="tt-nav-link-text">{{ localize('Rapports') }}</span>
                
            </a>
            <div class="collapse {{ areActiveRoutes($reportActiveRoutes, 'show') }}" id="reports">
                <ul class="side-nav-second-level">

                    <!-- @can('order_reports')
                        <li class="{{ areActiveRoutes(['admin.reports.orders'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.orders') }}"
                                class="{{ areActiveRoutes(['admin.reports.orders']) }}">{{ localize('Rapport des commandes') }}</a>
                                
                        </li>
                    @endcan -->

                    @can('product_sale_reports')
                        <li class="{{ areActiveRoutes(['admin.reports.sales'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.sales') }}"
                                class="{{ areActiveRoutes(['admin.reports.sales']) }}">{{ localize('Ventes de produits') }}</a>
                                
                        </li>
                    @endcan

                    @can('category_sale_reports')
                        <li class="{{ areActiveRoutes(['admin.reports.categorySales'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.categorySales') }}"
                                class="{{ areActiveRoutes(['admin.reports.categorySales']) }}">{{ localize('Ventes par catégorie') }}</a>
                                
                        </li>
                    @endcan

                    @can('sales_amount_reports')
                        <li class="{{ areActiveRoutes(['admin.reports.salesAmount'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.salesAmount') }}"
                                class="{{ areActiveRoutes(['admin.reports.salesAmount']) }}">{{ localize('Rapport du montant des ventes') }}</a>
                                
                        </li>
                    @endcan

                    <!-- @can('delivery_status_reports')
                        <li class="{{ areActiveRoutes(['admin.reports.deliveryStatus'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.deliveryStatus') }}"
                                class="{{ areActiveRoutes(['admin.reports.deliveryStatus']) }}">{{ localize('Rapport de l \'état de livraison') }}</a>
                                
                        </li>
                    @endcan -->
                </ul>
            </div>
        </li>
    @endcan


   
    <!-- Settings -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('PARAMÈTRES') }}</span>
        
    </li>


    <!-- affiliateSystem -->
    {{-- @php
        $affiliateSystemActiveRoutes = ['admin.newsletters.aasd'];
    @endphp
    @canany(['newsletters', 'subscribers'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($affiliateSystemActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#affiliateSystem"
                aria-expanded="{{ areActiveRoutes($affiliateSystemActiveRoutes, 'true') }}"
                aria-controls="affiliateSystem" class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="percent"></i></span>
                <span class="tt-nav-link-text">{{ localize('Affiliate System') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($affiliateSystemActiveRoutes, 'show') }}" id="affiliateSystem">
                <ul class="side-nav-second-level">
                    <li class="{{ areActiveRoutes(['admin.affiliate.configurations'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.affiliate.configurations') }}"
                            class="{{ areActiveRoutes(['admin.affiliate.configurations']) }}">{{ localize('Configurations') }}</a>
                    </li>

                    <li class="{{ areActiveRoutes(['admin.subscribers.index'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.subscribers.index') }}"
                            lass="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('Withdraw Request') }}</a>
                    </li>

                    <li class="{{ areActiveRoutes(['admin.subscribers.index'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.subscribers.index') }}"
                            lass="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('Earning Histories') }}</a>
                    </li>

                    <li class="{{ areActiveRoutes(['admin.subscribers.index'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.subscribers.index') }}"
                            lass="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('Payment Histories') }}</a>
                    </li>
                </ul>
            </div>
        </li>
    @endcan --}}

    <!-- Appearance -->
    @php
        $appearanceActiveRoutes = ['admin.appearance.header', 'admin.appearance.homepage.hero', 'admin.appearance.homepage.editHero', 'admin.appearance.homepage.topCategories', 'admin.appearance.homepage.topTrendingProducts', 'admin.appearance.homepage.featuredProducts', 'admin.appearance.homepage.bannerOne', 'admin.appearance.homepage.editBannerOne', 'admin.appearance.homepage.bestDeals', 'admin.appearance.homepage.bannerTwo', 'admin.appearance.homepage.clientFeedback', 'admin.appearance.homepage.editClientFeedback', 'admin.appearance.homepage.bestSelling', 'admin.appearance.homepage.customProductsSection', 'admin.appearance.products.index', 'admin.appearance.products.details', 'admin.appearance.products.details.editWidget', 'admin.appearance.about-us.popularBrands', 'admin.appearance.about-us.features', 'admin.appearance.about-us.editFeatures', 'admin.appearance.about-us.whyChooseUs', 'admin.appearance.about-us.editWhyChooseUs'];
        
        $homepageActiveRoutes = ['admin.appearance.homepage.hero', 'admin.appearance.homepage.editHero', 'admin.appearance.homepage.topCategories', 'admin.appearance.homepage.topTrendingProducts', 'admin.appearance.homepage.featuredProducts', 'admin.appearance.homepage.bannerOne', 'admin.appearance.homepage.editBannerOne', 'admin.appearance.homepage.bestDeals', 'admin.appearance.homepage.bannerTwo', 'admin.appearance.homepage.clientFeedback', 'admin.appearance.homepage.editClientFeedback', 'admin.appearance.homepage.bestSelling', 'admin.appearance.homepage.customProductsSection'];
        
    @endphp

    @canany(['homepage', 'product_page', 'product_details_page', 'about_us_page', 'header', 'footer'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($appearanceActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#Appearance"
                aria-expanded="{{ areActiveRoutes($appearanceActiveRoutes, 'true') }}" aria-controls="Appearance"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="layout"></i></span>
                <span class="tt-nav-link-text">{{ localize('Apparence') }}</span>
                
            </a>
            <div class="collapse {{ areActiveRoutes($appearanceActiveRoutes, 'show') }}" id="Appearance">
                <ul class="side-nav-second-level">

                    @can('homepage')
                        <li class="{{ areActiveRoutes($homepageActiveRoutes, 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.homepage.hero') }}"
                                class="{{ areActiveRoutes($homepageActiveRoutes) }}">{{ localize('Page d\'accueil') }}</a>
                                
                        </li>
                    @endcan


                    @can('product_page')
                        <li class="{{ areActiveRoutes(['admin.appearance.products.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.products.index') }}"
                                class="{{ areActiveRoutes(['admin.appearance.products.index']) }}">{{ localize('Page des produits') }}</a>
                                
                        </li>
                    @endcan

                    @can('product_details_page')
                        <li
                            class="{{ areActiveRoutes(['admin.appearance.products.details', 'admin.appearance.products.details.editWidget'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.products.details') }}"
                                class="{{ areActiveRoutes(['admin.appearance.products.details']) }}">{{ localize('Détails du produit') }}</a>
                                
                        </li>
                    @endcan

                    @can('about_us_page')
                        @php
                            $aboutUsActiveRoutes = ['admin.appearance.about-us.index', 'admin.appearance.about-us.popularBrands', 'admin.appearance.about-us.features', 'admin.appearance.about-us.editFeatures', 'admin.appearance.about-us.whyChooseUs', 'admin.appearance.about-us.editWhyChooseUs'];
                        @endphp

                        <li class="{{ areActiveRoutes($aboutUsActiveRoutes, 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.about-us.index') }}"
                                class="{{ areActiveRoutes($aboutUsActiveRoutes) }}">{{ localize('À propos de nous') }}</a>
                                
                        </li>
                    @endcan

                    @can('header')
                        <li class="{{ areActiveRoutes(['admin.appearance.header'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.header') }}"
                                class="{{ areActiveRoutes(['admin.appearance.header']) }}">{{ localize('En-tête') }}</a>
                                
                        </li>
                    @endcan

                    @can('footer')
                        <li class="{{ areActiveRoutes(['admin.appearance.footer'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.footer') }}"
                                class="{{ areActiveRoutes(['admin.appearance.footer']) }}">{{ localize('Pied de page') }}</a>
                                
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcanany


    <!-- Roles & Permission -->
    @php
        $rolesActiveRoutes = ['admin.roles.index', 'admin.roles.create', 'admin.roles.edit'];
    @endphp
    @can('roles_and_permissions')
        <li class="side-nav-item nav-item {{ areActiveRoutes($rolesActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.roles.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"><i data-feather="unlock"></i></span>
                <span class="tt-nav-link-text">{{ localize('Rôles & Permissions') }}</span>
                
            </a>
        </li>
    @endcan


    <!-- system settings -->
    @php
        $settingsActiveRoutes = ['admin.generalSettings', 'admin.orderSettings', 'admin.timeslot.edit', 'admin.languages.index', 'admin.languages.edit', 'admin.currencies.index', 'admin.currencies.edit', 'admin.languages.localizations', 'admin.smtpSettings.index'];
    @endphp

    @canany(['smtp_settings', 'general_settings', 'currency_settings', 'language_settings'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($settingsActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#systemSetting"
                aria-expanded="{{ areActiveRoutes($settingsActiveRoutes, 'true') }}" aria-controls="systemSetting"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="settings"></i></span>
                <span class="tt-nav-link-text">{{ localize('Paramètres du système') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($settingsActiveRoutes, 'show') }}" id="systemSetting">
                <ul class="side-nav-second-level">

                    @can('auth_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.authSettings'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.authSettings') }}"
                                class="{{ areActiveRoutes(['admin.settings.authSettings']) }}">{{ localize('Paramètres d\'authentification') }}</a>
                        </li>
                    @endcan

                    @can('otp_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.otpSettings'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.otpSettings') }}"
                                class="{{ areActiveRoutes(['admin.settings.otpSettings']) }}">{{ localize('Paramètres OTP') }}</a>
                        </li>
                    @endcan

                    @can('order_settings')
                        <li
                            class="{{ areActiveRoutes(['admin.orderSettings', 'admin.timeslot.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.orderSettings') }}"
                                class="{{ areActiveRoutes(['admin.generalSettings']) }}">{{ localize(' Paramètres de commande') }}</a>
                        </li>
                    @endcan

                    <li class="d-none {{ areActiveRoutes(['admin.smtpSettings.index'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.smtpSettings.index') }}"
                            class="{{ areActiveRoutes(['admin.smtpSettings.index']) }}">{{ localize('Magasin administrateur') }}</a>
                    </li>

                    @can('smtp_settings')
                        <li class="{{ areActiveRoutes(['admin.smtpSettings.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.smtpSettings.index') }}"
                                class="{{ areActiveRoutes(['admin.smtpSettings.index']) }}">{{ localize('Paramètres SMTP') }}</a>
                        </li>
                    @endcan

                    @can('general_settings')
                        <li class="{{ areActiveRoutes(['admin.generalSettings'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.generalSettings') }}"
                                class="{{ areActiveRoutes(['admin.generalSettings']) }}">{{ localize('Paramètres généraux') }}</a>
                        </li>
                    @endcan

                    @can('payment_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.paymentMethods'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.paymentMethods') }}"
                                class="{{ areActiveRoutes(['admin.settings.paymentMethods']) }}">{{ localize('Méthodes de paiement') }}</a>
                        </li>
                    @endcan

                    <!-- @can('social_login_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.socialLogin'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.socialLogin') }}"
                                class="{{ areActiveRoutes(['admin.settings.socialLogin']) }}">{{ localize('Connexion via les réseaux sociaux') }}</a>
                        </li>
                    @endcan -->

                  <!-- @can('language_settings')
                        <li
                            class="{{ areActiveRoutes(
                                ['admin.languages.index', 'admin.languages.edit', 'admin.languages.localizations'],
                                'tt-menu-item-active',
                            ) }}">
                            <a href="{{ route('admin.languages.index') }}"
                                class="{{ areActiveRoutes(['admin.languages.index', 'admin.languages.edit', 'admin.languages.localizations']) }}">{{ localize('Multilingual Settings') }}</a>
                        </li>
                    @endcan -->

                     <!-- @can('currency_settings')
                        <li
                            class="{{ areActiveRoutes(
                                ['admin.currencies.index', 'admin.currencies.edit', 'admin.currencies.localizations'],
                                'tt-menu-item-active',
                            ) }}">
                            <a href="{{ route('admin.currencies.index') }}"
                                class="{{ areActiveRoutes(['admin.currencies.index', 'admin.currencies.edit', 'admin.currencies.localizations']) }}">{{ localize('Paramètres de devise multiples') }}</a>
                        </li>
                    @endcan -->
                </ul>
            </div>
        </li>
    @endcan
</ul>
