@extends('frontend.layouts.app')

@section('content')

    <main class="main">
        <section class="welcome-section">
            <div class="container">
                <h2 class="section-title text-center text-uppercase appear-animate mb-4"
                    data-animation-name="fadeInUpShorter" data-animation-delay="200">B2B Platform || Best Online Platform</h2>
                <div class="row mb-2">
                    @foreach($allCat as $cat)
                        <div class="col-md-4 col-lg-3">
                            <a class="fancy" href="{{ route('categories.show', $cat->slug) }}">
                                <div class="card primary-card">
                                    <img src="{{asset('storage/'.$cat->image) }}"
                                         class="card-img-top card-img-height" alt="banner">
                                    <div class="card-body">
                                        <h5 class="card-title">{{$cat->title}}</h5>
                                        <p class="card-text">{{$cat->description}}</p>
                                    </div>
                                </div>

                                <span class="top-key"></span>
                                <span class="text">View our {{$cat->title}}</span>
                                <span class="bottom-key-1"></span>
                                <span class="bottom-key-2"></span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <?php
        $shops = [
            ['name' => 'Easy Life', 'items' => 16, 'categories' => 4, 'rating' => 5.0, 'status' => 'Online', 'logo' => asset('assets/images/shops/Online-Store-Toy-Shop.jpg')],
            ['name' => 'ShopMax', 'items' => 12, 'categories' => 3, 'rating' => 4.5, 'status' => 'Offline', 'logo' => asset('assets/images/shops/Orange-Online-Shop.jpg')],
            ['name' => 'StyleHub', 'items' => 20, 'categories' => 5, 'rating' => 4.8, 'status' => 'Online', 'logo' => asset('assets/images/shops/Red-Online-Store.png')],
            ['name' => 'Gadget World', 'items' => 30, 'categories' => 8, 'rating' => 4.9, 'status' => 'Online', 'logo' => asset('assets/images/shops/Online-Store-Toy-Shop.jpg')],
            ['name' => 'Home Essentials', 'items' => 10, 'categories' => 2, 'rating' => 4.3, 'status' => 'Busy', 'logo' => asset('assets/images/shops/Online-Store-Toy-Shop.jpg')],
            ['name' => 'DailyMart', 'items' => 25, 'categories' => 6, 'rating' => 4.7, 'status' => 'Online', 'logo' => asset('assets/images/shops/Online-Store-Toy-Shop.jpg')],
        ];

        $items = [
            ['name' => 'Easy Life', 'items' => 16, 'categories' => 4, 'rating' => 5.0, 'status' => 'Online', 'logo' => asset('assets/images/shops/Online-Store-Toy-Shop.jpg')],
            ['name' => 'ShopMax', 'items' => 12, 'categories' => 3, 'rating' => 4.5, 'status' => 'Offline', 'logo' => asset('assets/images/shops/Orange-Online-Shop.jpg')],
            ['name' => 'StyleHub', 'items' => 20, 'categories' => 5, 'rating' => 4.8, 'status' => 'Online', 'logo' => asset('assets/images/shops/Red-Online-Store.png')],
            ['name' => 'Gadget World', 'items' => 30, 'categories' => 8, 'rating' => 4.9, 'status' => 'Online', 'logo' => asset('assets/images/shops/Online-Store-Toy-Shop.jpg')],
            ['name' => 'Home Essentials', 'items' => 10, 'categories' => 2, 'rating' => 4.3, 'status' => 'Busy', 'logo' => asset('assets/images/shops/Online-Store-Toy-Shop.jpg')],

        ];
        ?>
        <section id="top-rated-shops">
            <div class="container mt-4">
                <h2 class="section-title text-center text-uppercase appear-animate mb-4"
                    data-animation-name="fadeInUpShorter" data-animation-delay="200">Top Shops</h2>
                <div class="row">
                    <?php foreach ($shops as $shop): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow"
                             style="border-radius: 15px; overflow: hidden; position: relative;">
                            <!-- Status Badge -->
                            <div class="badge badge-<?php echo strtolower($shop['status']) === 'online' ? 'success' : 'warning'; ?>"
                                 style="position: absolute; top: 10px; right: 10px; font-size: 0.85rem; padding: 5px 10px; border-radius: 12px;">
                                    <?php echo $shop['status']; ?>
                            </div>
                            <!-- Logo Section -->
                            <div class="text-center p-3" style="background-color: #f4f4f4;">
                                <img style="height: 150px; width: 100%" src="<?php echo $shop['logo']; ?>"
                                     alt="Shop Logo" class="">
                            </div>
                            <div class="card-body text-white"
                                 style="background-color: #e91e63; border-radius: 0 0 15px 15px;">
                                <h5 class="card-title mb-2"><?php echo $shop['name']; ?></h5>
                                <p class="card-text mb-1">
                                    <span><?php echo $shop['items']; ?> + Items</span> &nbsp; | &nbsp;
                                    <span><?php echo $shop['categories']; ?> + Categories</span> &nbsp; | &nbsp;
                                    <span><i class="fa fa-star text-warning"></i> <?php echo number_format($shop['rating'], 1); ?></span>
                                </p>
                                <a href="#" class="btn btn-outline-light btn-sm">Visit Store →</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>


        <div class="brands-section" style="background: #65829d !important;">
            <div class="container">
                <div class="row">
                    <h2 class="section-title text-center text-uppercase appear-animate mb-4"
                        data-animation-name="fadeInUpShorter" data-animation-delay="200" style="color: #ffffff !important;">Popular Category</h2>
                </div>

                <div class="brands-slider owl-carousel owl-theme images-center appear-animate"
                     data-animation-name="fadeIn" data-animation-delay="400" data-owl-options="{
                        'margin': 30,
                        'loop': false,
                        'responsive': {
                            '0': {
                                'items': 1
                            },
                            '768': {
                                'items': 2
                            },
                            '1200': {
                                'items': {{ count($productCat) }}
                            }
                        }
                    }">

                    @forelse($productCat as $pCategory)
                        <div class="category-card">
                            <img src="{{asset('assets/images/category_diagram.png') }}"
                                 width="200" height="100" alt="{{ $pCategory->title }}">
                            <p class="text-title">{{ $pCategory->title }}</p>
                        </div>
                    @empty
                        <div class="text-center w-100">
                            <p class="text-muted">No Data Available</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        <div class="products-container appear-animate" data-animation-name="fadeIn" data-animation-delay="200">
            <div class="container-lg container">
                <h2 class="section-title text-center text-uppercase appear-animate mb-4"
                    data-animation-name="fadeInUpShorter" data-animation-delay="200">Popular Products</h2>

            </div>


            <div class="container">
                <div class="row">
                    @foreach($products as $index => $item)
                        @if($index % 4 == 0 && $index != 0)
                </div>
                <div class="row">
                    @endif
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="product-default inner-quickview inner-icon">
                                <figure>
                                    <a href="demo23-product.html">
                                        <img src="{{asset('storage/'.$item->image) }}" width="217"
                                             height="217" alt="product">
                                    </a>
                                    <div class="label-group">
                                        <div class="product-label label-hot">HOT</div>
                                        <div class="product-label label-sale">-13%</div>
                                    </div>
                                    <div class="btn-icon-group">
                                        <a href="#" title="Add To Cart"
                                           class="btn-icon btn-add-cart product-type-simple"><i
                                                    class="icon-shopping-cart"></i></a>
                                    </div>
                                    <a href="ajax/product-quick-view.html" class="btn-quickview"
                                       title="Quick View">Quick
                                        View</a>
                                </figure>
                                <div class="product-details">
                                    <div class="category-wrap">
                                        <div class="category-list">
                                            <p>{{$item->product_categorie_id}}</p>
                                        </div>
                                        <a href="wishlist.html" title="Add to Wishlist" class="btn-icon-wish"><i
                                                    class="icon-heart"></i></a>
                                    </div>
                                    <h3 class="product-title">
                                        <a href="demo23-product.html">{{$item->name}}</a>
                                    </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:0%"></span><!-- End .ratings -->
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div><!-- End .product-ratings -->
                                    </div><!-- End .product-container -->
                                    <div class="price-box">
                                        <span class="old-price">{{$item->price}}</span>
                                        <span class="product-price">{{$item->sale_price}}</span>
                                    </div><!-- End .price-box -->
                                </div><!-- End .product-details -->
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


    </main><!-- End .main -->

@endsection