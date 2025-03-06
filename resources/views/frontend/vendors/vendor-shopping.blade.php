<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>B2B Platform Shopping</title>

    <meta name="keywords" content="HTML5 Template"/>
    <meta name="description" content="eCommerce Template">
    <meta name="author" content="SW-THEMES">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('fashion/assets/images/icons/favicon.png')}}">


    <script>
        WebFontConfig = {
            google: {
                families: ['Open+Sans:300,400,600,700', 'Poppins:300,400,500,600,700,800', 'Playfair+Display:900', 'Shadows+Into+Light:400']
            }
        };
        (function (d) {
            var wf = d.createElement('script'),
                s = d.scripts[0];
            wf.src = '{{ asset('fashion/assets/js/webfont.js') }}';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="{{asset('fashion/assets/css/bootstrap.min.css')}}">

    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{asset('fashion/assets/css/demo7.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('fashion/assets/vendor/fontawesome-free/css/all.min.css')}}">
</head>

<body>
<div class="page-wrapper">
    <header class="header">
        <div class="header-top text-uppercase">
            <div class="container">
                <div class="header-left">
                    <div class="header-dropdown mr-3 pr-1">
                    </div>
                    <!-- End .header-dropown -->
                    <div class="header-dropdown mr-auto">
                    </div>
                    <!-- End .header-dropown -->
                </div>
                <!-- End .header-left -->

                <div class="header-right header-dropdowns ml-0 ml-sm-auto">
                    <div class="header-dropdown dropdown-expanded mr-3">
                        <div class="header-menu">
                            <ul>
                                <li><a href="#">My Account</a></li>
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">My Wishlist</a></li>
                                <li><a href="#">Cart</a></li>
                                <li><a href="#" class="login-link">Log In</a></li>
                            </ul>
                        </div>
                        <!-- End .header-menu -->
                    </div>
                    <!-- End .header-dropown -->

                    <span class="separator d-none d-lg-inline-block"></span>

                    <div class="social-icons">
                        <a href="#" class="social-icon social-facebook icon-facebook" target="_blank"></a>
                        <a href="#" class="social-icon social-twitter icon-twitter" target="_blank"></a>
                        <a href="#" class="social-icon social-instagram icon-instagram mr-1" target="_blank"></a>
                    </div>
                    <!-- End .social-icons -->
                </div>
                <!-- End .header-right -->
            </div>
            <!-- End .container -->
        </div>
        <!-- End .header-top -->

        <div class="header-middle sticky-header">
            <div class="container">
                <div class="header-left">
                    <button class="mobile-menu-toggler" type="button">
                        <i class="fas fa-bars"></i>
                    </button>

                    <a href="#" class="logo w-100">
                        <img src="{{asset('fashion/assets/images/b2b-logo.png')}}" alt="B2B Logo">
                    </a>

                    <nav class="main-nav w-100">
                        <ul class="menu">
                            <li class="active">
                                <a href="#">Home</a>
                            </li>
                            <li>
                                <a href="#">Categories</a>
                                <div class="megamenu megamenu-fixed-width megamenu-3cols">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <ul class="submenu">
                                                <li><a href="#">Fashion</a></li>
                                                <li><a href="#">Jewellery</a></li>
                                                <li><a href="#">Kids Fashion</a></li>
                                                <li><a href="#">Accessories</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- End .megamenu -->
                            </li>
                            <li>
                                <a href="#">Products</a>
                                <div class="megamenu megamenu-fixed-width">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <a href="#" class="nolink">PRODUCT PAGES</a>
                                            <ul class="submenu">
                                                <li><a href="#">SIMPLE PRODUCT</a></li>
                                                <li><a href="#">VARIABLE PRODUCT</a></li>
                                                <li><a href="#">SALE PRODUCT</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- End .row -->
                                </div>
                                <!-- End .megamenu -->
                            </li>
                            <li>
                                <a href="#">Pages</a>
                                <ul>
                                    <li><a href="#">Wishlist</a></li>
                                    <li><a href="#">Shopping Cart</a></li>
                                    <li><a href="#">Checkout</a></li>
                                    <li><a href="#">Dashboard</a></li>
                                    <li><a href="#">Login</a></li>
                                    <li><a href="#">Forgot Password</a></li>
                                </ul>
                            </li>


                        </ul>
                    </nav>

                    <div class="header-search header-search-popup header-search-category d-none d-lg-block ml-xl-5">
                        <a href="#" class="search-toggle" role="button"><i class="icon-magnifier"></i></a>
                        <form action="#" method="get">
                            <div class="header-search-wrapper">
                                <input type="search" class="form-control bg-white" name="q" id="q"
                                       placeholder="I'm searching for..." required="">
                                <div class="select-custom bg-white">
                                    <select id="cat" name="cat">
                                        <option value="">All Categories</option>
                                        <option value="4">Fashion</option>
                                        <option value="12">- Women</option>
                                        <option value="13">- Men</option>
                                        <option value="66">- Jewellery</option>
                                        <option value="67">- Kids Fashion</option>
                                        <option value="5">Electronics</option>
                                        <option value="21">- Smart TVs</option>
                                        <option value="22">- Cameras</option>
                                        <option value="7">Home &amp; Garden</option>
                                        <option value="11">Motors</option>
                                        <option value="33">- Parts &amp; Accessories</option>
                                    </select>
                                </div>
                                <!-- End .select-custom -->
                                <button class="btn bg-white icon-search-3" type="submit"></button>
                            </div>
                            <!-- End .header-search-wrapper -->
                        </form>
                    </div>
                </div>
                <!-- End .header-left -->

                <div class="header-right">

                    <a href="#" class="header-icon header-icon-user d-lg-none d-block" title="login"><i
                                class="icon-user-2"></i></a>

                    <a href="#" class="header-icon d-lg-none d-block" title="wishlist"><i
                                class="icon-wishlist-2"></i></a>

                    <span class="separator d-lg-inline-block d-none"></span>

                    <div class="dropdown cart-dropdown">
                        <a href="#" title="Cart" class="dropdown-toggle dropdown-arrow cart-toggle" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                            <i class="icon-shopping-cart"></i>
                            <span class="cart-count badge-circle">3</span>
                        </a>

                        <div class="cart-overlay"></div>

                        <div class="dropdown-menu mobile-cart">
                            <a href="#" title="Close (Esc)" class="btn-close">×</a>

                            <div class="dropdownmenu-wrapper custom-scrollbar">
                                <div class="dropdown-cart-header">Shopping Cart</div>
                                <!-- End .dropdown-cart-header -->

                                <div class="dropdown-cart-products">
                                    <div class="product">
                                        <div class="product-details">
                                            <h4 class="product-title">
                                                <a href="product.html">Ultimate 3D Bluetooth Speaker</a>
                                            </h4>

                                            <span class="cart-product-info">
                                                    <span class="cart-product-qty">1</span> × $99.00
                                                </span>
                                        </div>
                                        <!-- End .product-details -->

                                        <figure class="product-image-container">
                                            <a href="product.html" class="product-image">
                                                <img src="{{asset('assets/images/products/product-1.jpg')}}"
                                                     alt="product" width="80" height="80">
                                            </a>

                                            <a href="#" class="btn-remove" title="Remove Product"><span>×</span></a>
                                        </figure>
                                    </div>
                                    <!-- End .product -->

                                    <div class="product">
                                        <div class="product-details">
                                            <h4 class="product-title">
                                                <a href="product.html">Brown Women Casual HandBag</a>
                                            </h4>

                                            <span class="cart-product-info">
                                                    <span class="cart-product-qty">1</span> × $35.00
                                                </span>
                                        </div>
                                        <!-- End .product-details -->

                                        <figure class="product-image-container">
                                            <a href="product.html" class="product-image">
                                                <img src="assets/images/products/product-2.jpg" alt="product" width="80"
                                                     height="80">
                                            </a>

                                            <a href="#" class="btn-remove" title="Remove Product"><span>×</span></a>
                                        </figure>
                                    </div>
                                    <!-- End .product -->

                                    <div class="product">
                                        <div class="product-details">
                                            <h4 class="product-title">
                                                <a href="#">Circled Ultimate 3D Speaker</a>
                                            </h4>

                                            <span class="cart-product-info">
                                                    <span class="cart-product-qty">1</span> × $35.00
                                                </span>
                                        </div>
                                        <!-- End .product-details -->

                                        <figure class="product-image-container">
                                            <a href="product.html" class="product-image">
                                                <img src="assets/images/products/product-3.jpg" alt="product" width="80"
                                                     height="80">
                                            </a>
                                            <a href="#" class="btn-remove" title="Remove Product"><span>×</span></a>
                                        </figure>
                                    </div>
                                    <!-- End .product -->
                                </div>
                                <!-- End .cart-product -->

                                <div class="dropdown-cart-total">
                                    <span>SUBTOTAL:</span>

                                    <span class="cart-total-price float-right">$134.00</span>
                                </div>
                                <!-- End .dropdown-cart-total -->

                                <div class="dropdown-cart-action">
                                    <a href="cart.html" class="btn btn-gray btn-block view-cart">View
                                        Cart</a>
                                    <a href="checkout.html" class="btn btn-dark btn-block">Checkout</a>
                                </div>
                                <!-- End .dropdown-cart-total -->
                            </div>
                            <!-- End .dropdownmenu-wrapper -->
                        </div>
                        <!-- End .dropdown-menu -->
                    </div>
                    <!-- End .dropdown -->
                </div>
                <!-- End .header-right -->
            </div>
            <!-- End .container -->
        </div>
        <!-- End .header-middle -->
    </header>
    <!-- End .header -->

    <main class="main">
        <div class="home-top-container appear-animate" data-animation-name="fadeIn" data-animation-delay="100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 mb-2">
                        <div class="home-banner banner banner1 banner-md-vw banner-sm-vw d-flex align-items-center">
                            <figure class="w-100">
                                <img src="{{asset('fashion/assets/images/demoes/demo7/banners/fashion-sale_banner.png')}}"
                                     style="background-color: #4a4a4a;" alt="banner">
                            </figure>
                            <div class="banner-layer">
                            </div>
                            <!-- End .banner-layer -->
                        </div>
                        <!-- End .home-slide -->
                    </div>

                    <div class="col-lg-3 col-sm-6 mb-2">
                        <div class="banner banner4 text-uppercase banner-hover-shadow d-flex align-items-center">
                            <figure class="w-100">
                                <img src="{{asset('fashion/assets/images/demoes/demo7/banners/banner-4.jpg')}}"
                                     style="background-color: #555;" alt="banner">
                            </figure>

                            <div class="banner-layer banner-layer-middle d-flex align-items-end flex-column">
                                <div class="coupon-sale-content">
                                    <h3 class="text-white font1">Leather Jackets</h3>
                                    <h5 class="coupon-sale-text text-white ls-0 p-0"><i class="ls-0 font1">UP
                                            TO</i><b class="text-white bg-secondary">$100</b><sub>OFF</sub></h5>
                                    <a href="#"
                                       class="btn bg-white btn-lg ls-10 d-block d-lg-inline-block  text-dark">Get
                                        Yours!</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- End .col-lg-5 -->
            </div>
            <!-- End .row -->
        </div>
        <!-- End .container -->

        <div class="container">
            <section class="featured-products-section appear-animate" data-animation-name="fadeInUpShorter"
                     data-animation-delay="100">
                <h2 class="section-title text-center d-flex align-items-center">Featured Products
                </h2>

                <div class="owl-carousel owl-theme dots-top dots-small" data-owl-options="{
                            'dots': true,
                            'margin': 20,
                            'nav': false,
                            'loop': false,
                            'responsive': {
                                '0': {
                                    'items': 2
                                },
                                '768': {
                                    'items': 3
                                },
                                '991': {
                                    'items': 4
                                }
                            }
                        }">


                    @foreach($all_products as $product)
                        <div class="product-default left-details">
                            <figure>
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('fashion/assets/images/demoes/demo7/products/product-8.jpg') }}"
                                         alt="{{ $product->name }}" width="300" height="300">

                                    <img src="{{asset('fashion/assets/images/demoes/demo7/products/product-8-2.jpg')}}" alt="product"
                                         width="300" height="300">
                                </a>
                                <div class="label-group">
                                    <span class="product-label label-hot">HOT</span>
                                    <span class="product-label label-sale">-15%</span>
                                </div>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <a href="#" class="product-category">T-shirts</a>,
                                    <a href="#" class="product-category">Toys</a>
                                </div>
                                <h3 class="product-title"><a href="#">{{$product->name}}</a></h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <span class="old-price">$1,999.00</span>
                                    <span class="product-price">$1,699.00</span>
                                </div>
                                <!-- End .price-box -->
                                <div class="product-action">
                                    <a href="#" class="btn-icon btn-add-cart product-type-simple"><i
                                                class="icon-shopping-cart"></i><span>ADD TO CART</span></a>
                                    <a href="#" class="btn-icon-wish" title="wishlist"><i
                                                class="icon-heart"></i></a>
                                    <a href="#" class="btn-quickview" title="Quick View"><i
                                                class="fas fa-external-link-alt"></i></a>
                                </div>
                            </div>
                            <!-- End .product-details -->
                        </div>
                    @endforeach

                </div>
            </section>
        </div>

        <div class="categories-section bg-primary">
            <div class="container">
                <h2 class="section-title border-0 title-decorate text-center text-white d-flex align-items-center appear-animate"
                    data-animation-name="fadeInUpShorter">
                        <span>BROWSE
                            OUR
                            CATEGORIES</span></h2>
                <div class="owl-carousel owl-theme appear-animate show-nav-hover" data-animation-name="fadeInUpShorter"
                     data-animation-delay="200" data-owl-options="{
                        'dots': false,
                        'margin': 20,
                        'loop': false,
                        'navText': [ '<i class=icon-left-open-big>', '<i class=icon-right-open-big>' ],
                        'nav': true,
                        'responsive': {
                            '0': {
                                'items': 2
                            },
                            '768': {
                                'items': 3
                            },
                            '991': {
                                'items': 4,
                                'nav': false
                            }
                        }
                    }">


                    @forelse($product_categories as $categories)
                        <div class="banner banner-image">
                            <a href="#">
                                <img src="{{asset('fashion/assets/images/demoes/demo7/banners/cats/banner-1.jpg')}}" width="272" height="231"
                                     alt="banner">
                            </a>
                            <div class="banner-layer banner-layer-middle">
                                <h3>{{$categories->title}}</h3>
                                <span>2 PRODUCTS </span>
                            </div>
                        </div>
                        <!-- End .banner -->
                    @empty
                        <div class="col-12 text-center">
                            <p>No Category found.</p>
                        </div>
                    @endforelse

                </div>
                <!-- End .cat-carousel -->
            </div>
            <!-- End .container -->
        </div>
        <!-- End .banners-section -->

        <div class="arrival-products-section appear-animate" data-animation-name="fadeIn" data-animation-delay="100">
            <div class="container">
                <h2 class="section-title text-center d-flex align-items-center">JUST ARRIVED
                </h2>

                <div class="row">
                    <div class="col-6 col-lg-3 col-md-4 col-xl-5col">
                        <div class="product-default left-details">
                            <figure>
                                <a href="#">
                                    <img src="{{asset('fashion/assets/images/demoes/demo7/products/product-13.jpg')}}" alt="product"
                                         width="300" height="300">
                                    <img src="{{asset('fashion/assets/images/demoes/demo7/products/product-13-2.jpg')}}" alt="product"
                                         width="300" height="300">
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <a href="#" class="product-category">clothing</a>,
                                    <a href="#" class="product-category">shoes</a>
                                </div>
                                <h3 class="product-title"><a href="#">Men Black Glasses</a></h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <span class="product-price">$99.00 – $109.00</span>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-4 col-xl-5col">
                        <div class="product-default left-details">
                            <figure>
                                <a href="#">
                                    <img src="assets/images/demoes/demo7/products/product-1.jpg" alt="product"
                                         width="300" height="300">
                                    <img src="assets/images/demoes/demo7/products/product-1-2.jpg" alt="product"
                                         width="300" height="300">
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <a href="#" class="product-category">clothing</a>,
                                    <a href="#" class="product-category">shoes</a>
                                </div>
                                <h3 class="product-title"><a href="#">Black Glasses</a></h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <span class="product-price">$99.00 – $109.00</span>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-4 col-xl-5col">
                        <div class="product-default left-details">
                            <figure>
                                <a href="#">
                                    <img src="assets/images/demoes/demo7/products/product-10.jpg" alt="product"
                                         width="300" height="300">
                                    <img src="assets/images/demoes/demo7/products/product-10-2.jpg" alt="product"
                                         width="300" height="300">
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <a href="#" class="product-category">clothing</a>,
                                    <a href="#" class="product-category">shoes</a>
                                </div>
                                <h3 class="product-title"><a href="#">Men Black Shoes</a></h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <span class="product-price">$99.00 – $109.00</span>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-4 col-xl-5col">
                        <div class="product-default left-details">
                            <figure>
                                <a href="#">
                                    <img src="assets/images/demoes/demo7/products/product-14.jpg" alt="product"
                                         width="300" height="300">
                                    <img src="assets/images/demoes/demo7/products/product-14-2.jpg" alt="product"
                                         width="300" height="300">
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <a href="#" class="product-category">clothing</a>,
                                    <a href="#" class="product-category">shoes</a>
                                </div>
                                <h3 class="product-title"><a href="#">Men Cap</a></h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <span class="product-price">$99.00 – $109.00</span>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-4 col-xl-5col">
                        <div class="product-default left-details">
                            <figure>
                                <a href="#">
                                    <img src="assets/images/demoes/demo7/products/product-5.jpg" alt="product"
                                         width="300" height="300">
                                    <img src="assets/images/demoes/demo7/products/product-5-2.jpg" alt="product"
                                         width="300" height="300">
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <a href="#" class="product-category">clothing</a>,
                                    <a href="#" class="product-category">shoes</a>
                                </div>
                                <h3 class="product-title"><a href="#">Brown Belt</a></h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <span class="product-price">$99.00 – $109.00</span>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-4 col-xl-5col">
                        <div class="product-default left-details">
                            <figure>
                                <a href="#">
                                    <img src="assets/images/demoes/demo7/products/product-6.jpg" alt="product"
                                         width="300" height="300">
                                    <img src="assets/images/demoes/demo7/products/product-6-2.jpg" alt="product"
                                         width="300" height="300">
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <a href="#" class="product-category">clothing</a>,
                                    <a href="#" class="product-category">shoes</a>
                                </div>
                                <h3 class="product-title"><a href="#">Men Gentle Shoes</a></h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <span class="product-price">$99.00 – $109.00</span>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-4 col-xl-5col">
                        <div class="product-default left-details">
                            <figure>
                                <a href="#">
                                    <img src="assets/images/demoes/demo7/products/product-4.jpg" alt="product"
                                         width="300" height="300">
                                    <img src="assets/images/demoes/demo7/products/product-4-2.jpg" alt="product"
                                         width="300" height="300">
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <a href="#" class="product-category">clothing</a>,
                                    <a href="#" class="product-category">shoes</a>
                                </div>
                                <h3 class="product-title"><a href="#">Men Black Belt</a></h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <span class="product-price">$99.00 – $109.00</span>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-4 col-xl-5col">
                        <div class="product-default left-details">
                            <figure>
                                <a href="#">
                                    <img src="assets/images/demoes/demo7/products/product-2.jpg" alt="product"
                                         width="300" height="300">
                                    <img src="assets/images/demoes/demo7/products/product-2-2.jpg" alt="product"
                                         width="300" height="300">
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <a href="#" class="product-category">clothing</a>,
                                    <a href="#" class="product-category">shoes</a>
                                </div>
                                <h3 class="product-title"><a href="#">Jeans Pants</a></h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <span class="product-price">$99.00 – $109.00</span>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-4 col-xl-5col">
                        <div class="product-default left-details">
                            <figure>
                                <a href="#">
                                    <img src="assets/images/demoes/demo7/products/product-3.jpg" alt="product"
                                         width="300" height="300">
                                    <img src="assets/images/demoes/demo7/products/product-3-2.jpg" alt="product"
                                         width="300" height="300">
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <a href="#" class="product-category">clothing</a>,
                                    <a href="#" class="product-category">shoes</a>
                                </div>
                                <h3 class="product-title"><a href="#">Jeans Trouser</a></h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <span class="product-price">$99.00 – $109.00</span>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-4 col-xl-5col">
                        <div class="product-default left-details">
                            <figure>
                                <a href="#">
                                    <img src="assets/images/demoes/demo7/products/product-7.jpg" alt="product"
                                         width="300" height="300">
                                    <img src="assets/images/demoes/demo7/products/product-7-2.jpg" alt="product"
                                         width="300" height="300">
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <a href="#" class="product-category">clothing</a>,
                                    <a href="#" class="product-category">shoes</a>
                                </div>
                                <h3 class="product-title"><a href="#">Men Gray Cap</a></h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <span class="product-price">$99.00 – $109.00</span>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                    </div>
                </div>

                <hr class="mt-1 mb-4">
            </div>
        </div>

        <div class="container">
            <div class="product-widgets row pb-2 appear-animate" data-animation-name="fadeIn"
                 data-animation-delay="100">
                <div class="col-md-4 col-sm-6 pb-5">
                    <h4 class="section-title border-0 text-left text-uppercase">Best Selling Products</h4>
                    <div class="heading-spacer"></div>
                    <div class="product-default left-details product-widget mb-2">
                        <figure>
                            <a href="#">
                                <img src="assets/images/demoes/demo7/products/product-2.jpg" width="175" height="175"
                                     alt="product"/>
                                <img src="assets/images/demoes/demo7/products/product-2-2.jpg" width="175" height="175"
                                     alt="product"/>
                            </a>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">caps</a>,
                                <a href="#" class="product-category">fashion</a>,
                                <a href="#" class="product-category">t-shirts</a>
                            </div>
                            <h3 class="product-title"><a href="#">Jeans Pants</a></h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    <span class="ratings" style="width:100%"></span>
                                    <!-- End .ratings -->
                                    <span class="tooltiptext tooltip-top"></span>
                                </div>
                                <!-- End .product-ratings -->
                            </div>
                            <!-- End .product-container -->
                            <div class="price-box">
                                <span class="product-price">$69.00</span>
                            </div>
                            <!-- End .price-box -->
                        </div>
                        <!-- End .product-details -->
                    </div>
                    <div class="product-default left-details product-widget mb-2">
                        <figure>
                            <a href="#">
                                <img src="assets/images/demoes/demo7/products/product-7.jpg" width="175" height="175"
                                     alt="product"/>
                                <img src="assets/images/demoes/demo7/products/product-7-2.jpg" width="175" height="175"
                                     alt="product"/>
                            </a>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">Fashion</a>,
                                <a href="#" class="product-category">Shoes</a>,
                                <a href="#" class="product-category">Toys</a>
                            </div>
                            <h3 class="product-title"><a href="#">Men Sports Cap</a></h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    <span class="ratings" style="width:80%"></span>
                                    <!-- End .ratings -->
                                    <span class="tooltiptext tooltip-top">4.00</span>
                                </div>
                                <!-- End .product-ratings -->
                            </div>
                            <!-- End .product-container -->
                            <div class="price-box">
                                <span class="product-price">$39.00 – $108.00</span>
                            </div>
                            <!-- End .price-box -->
                        </div>
                        <!-- End .product-details -->
                    </div>
                    <div class="product-default left-details product-widget mb-2">
                        <figure>
                            <a href="#">
                                <img src="assets/images/demoes/demo7/products/product-13.jpg" width="175" height="175"
                                     alt="product"/>
                                <img src="assets/images/demoes/demo7/products/product-13-2.jpg" width="175" height="175"
                                     alt="product"/>
                            </a>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">Fashion</a>,
                                <a href="#" class="product-category">Shoes</a>,
                                <a href="#" class="product-category">Toys</a>
                            </div>
                            <h3 class="product-title"><a href="#">Men Black Glasses</a></h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    <span class="ratings" style="width:80%"></span>
                                    <!-- End .ratings -->
                                    <span class="tooltiptext tooltip-top">4.00</span>
                                </div>
                                <!-- End .product-ratings -->
                            </div>
                            <!-- End .product-container -->
                            <div class="price-box">
                                <span class="product-price">$39.00 – $108.00</span>
                            </div>
                            <!-- End .price-box -->
                        </div>
                        <!-- End .product-details -->
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 pb-5">
                    <h4 class="section-title border-0 text-left text-uppercase">Top Rated Products</h4>
                    <div class="heading-spacer"></div>
                    <div class="product-default left-details product-widget mb-2">
                        <figure>
                            <a href="#">
                                <img src="assets/images/demoes/demo7/products/product-3.jpg" width="175" height="175"
                                     alt="product"/>
                                <img src="assets/images/demoes/demo7/products/product-3-2.jpg" width="175" height="175"
                                     alt="product"/>
                            </a>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">Fashion</a>
                            </div>
                            <h3 class="product-title"><a href="#">Jeana Trouser</a></h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    <span class="ratings" style="width:0%"></span>
                                    <!-- End .ratings -->
                                    <span class="tooltiptext tooltip-top"></span>
                                </div>
                                <!-- End .product-ratings -->
                            </div>
                            <!-- End .product-container -->
                            <div class="price-box">
                                <span class="product-price">$199.00 – $209.00</span>
                            </div>
                            <!-- End .price-box -->
                        </div>
                        <!-- End .product-details -->
                    </div>
                    <div class="product-default left-details product-widget mb-2">
                        <figure>
                            <a href="#">
                                <img src="assets/images/demoes/demo7/products/product-5.jpg" width="175" height="175"
                                     alt="product"/>
                                <img src="assets/images/demoes/demo7/products/product-5-2.jpg" width="175" height="175"
                                     alt="product"/>
                            </a>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">Electronics</a>,
                                <a href="#" class="product-category">Fashion</a>,
                                <a href="#" class="product-category">Watches</a>
                            </div>
                            <h3 class="product-title"><a href="#">Men Brown Belts</a></h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    <span class="ratings" style="width:0%"></span>
                                    <!-- End .ratings -->
                                    <span class="tooltiptext tooltip-top"></span>
                                </div>
                                <!-- End .product-ratings -->
                            </div>
                            <!-- End .product-container -->
                            <div class="price-box">
                                <span class="product-price">$101.00 – $111.00</span>
                            </div>
                            <!-- End .price-box -->
                        </div>
                        <!-- End .product-details -->
                    </div>
                    <div class="product-default left-details product-widget mb-2">
                        <figure>
                            <a href="#">
                                <img src="assets/images/demoes/demo7/products/product-1.jpg" width="175" height="175"
                                     alt="product"/>
                                <img src="assets/images/demoes/demo7/products/product-1-2.jpg" width="175" height="175"
                                     alt="product"/>
                            </a>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">T-shirts</a>,
                                <a href="#" class="product-category">Toys</a>,
                                <a href="#" class="product-category">Trousers</a>
                            </div>
                            <h3 class="product-title"><a href="#">Black Glasses</a></h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    <span class="ratings" style="width:0%"></span>
                                    <!-- End .ratings -->
                                    <span class="tooltiptext tooltip-top"></span>
                                </div>
                                <!-- End .product-ratings -->
                            </div>
                            <!-- End .product-container -->
                            <div class="price-box">
                                <span class="product-price">$101.00 – $111.00</span>
                            </div>
                            <!-- End .price-box -->
                        </div>
                        <!-- End .product-details -->
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 pb-5">
                    <h4 class="section-title border-0 text-left text-uppercase">Featured Products</h4>
                    <div class="heading-spacer"></div>
                    <div class="product-default left-details product-widget mb-2">
                        <figure>
                            <a href="#">
                                <img src="{{asset('fashion/assets/images/demoes/demo7/products/product-7.jpg')}}" width="175" height="175"
                                     alt="product"/>
                                <img src="{{asset('fashion/assets/images/demoes/demo7/products/product-7-2.jpg')}}" width="175" height="175"
                                     alt="product"/>
                            </a>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">Fashion</a>,
                                <a href="#" class="product-category">Shoes</a>,
                                <a href="#" class="product-category">Toys</a>
                            </div>
                            <h3 class="product-title"><a href="#">Men Sports Cap</a></h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    <span class="ratings" style="width:80%"></span>
                                    <!-- End .ratings -->
                                    <span class="tooltiptext tooltip-top">4.00</span>
                                </div>
                                <!-- End .product-ratings -->
                            </div>
                            <!-- End .product-container -->
                            <div class="price-box">
                                <span class="product-price">$39.00 – $108.00</span>
                            </div>
                            <!-- End .price-box -->
                        </div>
                        <!-- End .product-details -->
                    </div>
                    <div class="product-default left-details product-widget mb-2">
                        <figure>
                            <a href="#">
                                <img src="assets/images/demoes/demo7/products/product-13.jpg" width="175" height="175"
                                     alt="product"/>
                                <img src="assets/images/demoes/demo7/products/product-13-2.jpg" width="175" height="175"
                                     alt="product"/>
                            </a>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">Fashion</a>,
                                <a href="#" class="product-category">Shoes</a>,
                                <a href="#" class="product-category">Toys</a>
                            </div>
                            <h3 class="product-title"><a href="#">Men Black Glasses</a></h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    <span class="ratings" style="width:80%"></span>
                                    <!-- End .ratings -->
                                    <span class="tooltiptext tooltip-top">4.00</span>
                                </div>
                                <!-- End .product-ratings -->
                            </div>
                            <!-- End .product-container -->
                            <div class="price-box">
                                <span class="product-price">$39.00 – $108.00</span>
                            </div>
                            <!-- End .price-box -->
                        </div>
                        <!-- End .product-details -->
                    </div>
                    <div class="product-default left-details product-widget mb-2">
                        <figure>
                            <a href="#">
                                <img src="{{asset('fashion/assets/images/demoes/demo7/products/product-5.jpg')}}"
                                     width="175" height="175" alt="product"/>
                                <img src="assets/images/demoes/demo7/products/product-5-2.jpg" width="175" height="175"
                                     alt="product"/>
                            </a>
                        </figure>
                        <div class="product-details">
                            <div class="category-list">
                                <a href="#" class="product-category">Electronics</a>,
                                <a href="#" class="product-category">Fashion</a>,
                                <a href="#" class="product-category">Watches</a>
                            </div>
                            <h3 class="product-title"><a href="#">Men Brown Belts</a></h3>
                            <div class="ratings-container">
                                <div class="product-ratings">
                                    <span class="ratings" style="width:0%"></span>
                                    <!-- End .ratings -->
                                    <span class="tooltiptext tooltip-top"></span>
                                </div>
                                <!-- End .product-ratings -->
                            </div>
                            <!-- End .product-container -->
                            <div class="price-box">
                                <span class="product-price">$101.00 – $111.00</span>
                            </div>
                            <!-- End .price-box -->
                        </div>
                        <!-- End .product-details -->
                    </div>
                </div>
            </div>
            <!-- End .product-widgets -->

            <hr class="m-0"/>
        </div>

        <!-- End .container -->
    </main>

    <footer class="footer bg-dark position-relative">
        <div class="footer-middle">
            <div class="container position-static">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 pb-5 pb-sm-0">
                        <div class="widget">
                            <h4 class="widget-title">About Us</h4>
                            <a href="demo7.html">
                                <img src="{{asset('fashion/assets/images/b2b-logo.png')}}" alt="Logo"
                                     class="logo-footer">
                            </a>
                            <p class="m-b-4 ls-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec
                                vestibulum magna, et dapibus lacus. Duis nec vestibulum magna, et dapibus lacus.</p>
                            <div class="social-icons">
                                <a href="#" class="social-icon social-facebook icon-facebook" target="_blank"
                                   title="Facebook"></a>
                                <a href="#" class="social-icon social-twitter icon-twitter" target="_blank"
                                   title="Twitter"></a>
                                <a href="#" class="social-icon social-linkedin fab fa-linkedin-in" target="_blank"
                                   title="Linkedin"></a>
                            </div>
                            <!-- End .social-icons -->
                        </div>
                        <!-- End .widget -->
                    </div>
                    <!-- End .col-lg-3 -->

                    <div class="col-lg-3 col-sm-6 pb-5 pb-sm-0">
                        <div class="widget mb-2">
                            <h4 class="widget-title pb-1">Customer Service</h4>

                            <ul class="links">
                                <li><a href="#">Help & FAQs</a></li>
                                <li><a href="#">Order Tracking</a></li>
                                <li><a href="#">Shipping & Delivery</a></li>
                                <li><a href="#">Orders History</a></li>
                                <li><a href="#">Advanced Search</a></li>
                                <li><a href="dashboard.html">My Account</a></li>
                                <li><a href="#">Careers</a></li>
                                <li><a href="demo7-about.html">About Us</a></li>
                                <li><a href="#">Corporate Sales</a></li>
                                <li><a href="#">Privacy</a></li>
                            </ul>
                        </div>
                        <!-- End .widget -->
                    </div>
                    <!-- End .col-lg-3 -->

                    <div class="col-lg-3 col-sm-6 pb-5 pb-sm-0">
                        <div class="widget widget-post">
                            <h4 class="widget-title pb-1">Latest News</h4>

                            <ul class="links">
                                <li><a href="#">Top Jeans Collection<br/><span class="font1">JULY 23,
                                                2021</span></a></li>
                                <li><a href="#">Post Format Standard<br/><span class="font1">FEBRUARY 26,
                                                2020</span></a></li>
                                <li><a href="#">Post Format Video<br/><span class="font1">FEBRUARY 26,
                                                2019</span></a></li>
                            </ul>
                        </div>
                        <!-- End .widget -->
                    </div>
                    <!-- End .col-lg-3 -->

                    <div class="col-lg-3 col-sm-6 pb-5 pb-sm-0">
                        <div class="widget widget-newsletter">
                            <h4 class="widget-title">Subscribe newsletter</h4>
                            <p class="mb-2 ls-0">Get all the latest information on events, sales and offers. Sign up for
                                newsletter:
                            </p>
                            <form action="#" class="mb-0">
                                <input type="email" class="form-control" placeholder="Email address" required>

                                <input type="submit" class="btn btn-primary ls-10 shadow-none" value="Subscribe">
                            </form>
                        </div>
                        <!-- End .widget -->
                    </div>
                    <!-- End .col-lg-3 -->
                </div>
                <!-- End .row -->
            </div>
            <!-- End .container -->
        </div>
        <!-- End .footer-middle -->

        <div class="container">
            <div class="footer-bottom d-sm-flex align-items-center">
                <div class="footer-left">
                    <span class="footer-copyright">© eCommerce. 2025. All Rights Reserved</span>
                </div>

                <div class="footer-right ml-auto mt-1 mt-sm-0">
                    <div class="payment-icons mr-0">
                        <span class="payment-icon visa"
                              style="background-image: url(assets/images/payments/payment-visa.svg)"></span>
                        <span class="payment-icon paypal"
                              style="background-image: url(assets/images/payments/payment-paypal.svg)"></span>
                        <span class="payment-icon stripe"
                              style="background-image: url(assets/images/payments/payment-stripe.png)"></span>
                        <span class="payment-icon verisign"
                              style="background-image:  url(assets/images/payments/payment-verisign.svg)"></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- End .footer-bottom -->
    </footer>
    <!-- End .footer -->
</div>
<!-- End .page-wrapper -->

<div class="loading-overlay">
    <div class="bounce-loader">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>

<div class="mobile-menu-overlay"></div>
<!-- End .mobil-menu-overlay -->

<div class="mobile-menu-container">
    <div class="mobile-menu-wrapper">
        <span class="mobile-menu-close"><i class="fa fa-times"></i></span>
        <nav class="mobile-nav">
            <ul class="mobile-menu">
                <li><a href="demo7.html">Home</a></li>
                <li>
                    <a href="#">Categories</a>
                    <ul>
                        <li><a href="category.html">Full Width Banner</a></li>

                    </ul>
                </li>
                <li>
                    <a href="#">Products</a>
                    <ul>
                        <li>
                            <a href="#" class="nolink">PRODUCT PAGES</a>
                            <ul>
                                <li><a href="product.html">SIMPLE PRODUCT</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="nolink">PRODUCT LAYOUTS</a>
                            <ul>
                                <li><a href="product-extended-layout.html">EXTENDED LAYOUT</a></li>
                                <li><a href="#">BUILD YOUR OWN</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

            </ul>

            <ul class="mobile-menu mt-2 mb-2">
                <li class="border-0">
                    <a href="#">
                        Special Offer!
                    </a>
                </li>
                <li class="border-0">
                    <a href="https://1.envato.market/DdLk5" target="_blank">
                        Buy Porto!
                        <span class="tip tip-hot">Hot</span>
                    </a>
                </li>
            </ul>

            <ul class="mobile-menu">
                <li><a href="login.html">My Account</a></li>
                <li><a href="demo7-contact.html">Contact Us</a></li>
                <li><a href="blog.html">Blog</a></li>
                <li><a href="wishlist.html">My Wishlist</a></li>
                <li><a href="cart.html">Cart</a></li>
                <li><a href="login.html" class="login-link">Log In</a></li>
            </ul>
        </nav>
        <!-- End .mobile-nav -->

        <form class="search-wrapper mb-2" action="#">
            <input type="text" class="form-control mb-0" placeholder="Search..." required/>
            <button class="btn icon-search text-white bg-transparent p-0" type="submit"></button>
        </form>

        <div class="social-icons">
            <a href="#" class="social-icon social-facebook icon-facebook" target="_blank">
            </a>
            <a href="#" class="social-icon social-twitter icon-twitter" target="_blank">
            </a>
            <a href="#" class="social-icon social-instagram icon-instagram" target="_blank">
            </a>
        </div>
    </div>
    <!-- End .mobile-menu-wrapper -->
</div>
<!-- End .mobile-menu-container -->

<div class="sticky-navbar">
    <div class="sticky-info">
        <a href="demo7.html">
            <i class="icon-home"></i>Home
        </a>
    </div>
    <div class="sticky-info">
        <a href="#" class="">
            <i class="icon-bars"></i>Categories
        </a>
    </div>
    <div class="sticky-info">
        <a href="wishlist.html" class="">
            <i class="icon-wishlist-2"></i>Wishlist
        </a>
    </div>
    <div class="sticky-info">
        <a href="login.html" class="">
            <i class="icon-user-2"></i>Account
        </a>
    </div>
    <div class="sticky-info">
        <a href="cart.html" class="">
            <i class="icon-shopping-cart position-relative">
                <span class="cart-count badge-circle">3</span>
            </i>Cart
        </a>
    </div>
</div>


<a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>

<!-- Plugins JS File -->
<script src="{{asset('fashion/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('fashion/assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('fashion/assets/js/plugins.min.js')}}"></script>
<script src="{{asset('fashion/assets/js/jquery.appear.min.js')}}"></script>

<!-- Main JS File -->
<script src="{{asset('fashion/assets/js/main.min.js')}}"></script>
</body>


</html>