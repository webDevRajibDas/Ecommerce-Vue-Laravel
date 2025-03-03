<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Landing Page</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Owl Carousel 2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <!-- Custom CSS -->
    <style>
        .hero-section {
            background: linear-gradient(rgba(100, 100, 100, 0.5), rgba(255, 255, 255, 0.5)), url("{{ asset('assets/images/aone-fashion.png') }}");
            background-size: cover;
            background-position: center;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .product-carousel .item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .product-carousel img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .card {
            background: #fff;
            box-shadow: 0 6px 10px rgba(0, 0, 0, .08), 0 0 6px rgba(0, 0, 0, .05);
            transition: .3s transform cubic-bezier(.155, 1.105, .295, 1.12), .3s box-shadow, .3s -webkit-transform cubic-bezier(.155, 1.105, .295, 1.12);
            border: 0;
            border-radius: 1rem;
        }

        .card-img,
        .card-img-top {
            border-top-left-radius: calc(1rem - 1px);
            border-top-right-radius: calc(1rem - 1px);
        }


        .card h5 {
            overflow: hidden;
            height: 55px;
            font-weight: 300;
            font-size: 1rem;
        }

        .card h5 a {
            color: black;
            text-decoration: none;
        }

        .card-img-top {
            width: 100%;
            min-height: 300px;
            max-height: 250px;
            object-fit: contain;
            padding: 20px;
        }

        .card h2 {
            font-size: 1rem;
        }


        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .12), 0 4px 8px rgba(0, 0, 0, .06);
        }

        /* Centered text */
        .label-top {
            position: absolute;
            background-color: var(--label-index);
            color: #fff;
            top: 8px;
            right: 8px;
            padding: 5px 10px 5px 10px;
            font-size: .7rem;
            font-weight: 600;
            border-radius: 3px;
            text-transform: uppercase;
        }

        .top-right {
            position: absolute;
            top: 24px;
            left: 24px;

            width: 90px;
            height: 90px;
            border-radius: 50%;
            font-size: 1rem;
            font-weight: 900;
            background: #8bc34a;
            line-height: 90px;
            text-align: center;
            color: white;
        }

        .top-right span {
            display: inline-block;
            vertical-align: middle;
            /* line-height: normal; */
            /* padding: 0 25px; */
        }

        .aff-link {
            /* text-decoration: overline; */
            font-weight: 500;
        }

        .over-bg {
            background: rgba(53, 53, 53, 0.85);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(0.0px);
            -webkit-backdrop-filter: blur(0.0px);
            border-radius: 10px;
        }

        .bold-btn {
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            padding: 5px 50px 5px 50px;
        }

        .box .btn {
            font-size: 1.5rem;
        }

        @media (max-width: 1025px) {
            .btn {
                padding: 5px 40px 5px 40px;
            }
        }

        @media (max-width: 250px) {
            .btn {
                padding: 5px 30px 5px 30px;
            }
        }

        /* START BUTTON */
        .btn-warning {
            background: var(--btnbg);
            color: var(--btnfontcolor);
            fill: #ffffff;
            border: none;
            text-decoration: none;
            outline: 0;
            /* box-shadow: -1px 6px 19px rgba(247, 129, 10, 0.25); */
            border-radius: 100px;
        }

        .btn-warning:hover {
            background: var(--btnbghover);
            color: var(--btnfontcolorhover);
            /* box-shadow: -1px 6px 13px rgba(255, 150, 43, 0.35); */
        }

        .btn-check:focus + .btn-warning, .btn-warning:focus {
            background: var(--btnbghover);
            color: var(--btnfontcolorhover);
            /* box-shadow: -1px 6px 13px rgba(255, 150, 43, 0.35); */
        }

        .btn-warning:active:focus {
            box-shadow: 0 0 0 0.25rem var(--btnactivefs);
        }

        .btn-warning:active {
            background: var(--btnbghover);
            color: var(--btnfontcolorhover);
            /* box-shadow: -1px 6px 13px rgba(255, 150, 43, 0.35); */
        }

        /* END BUTTON */

        .bg-success {
            font-size: 1rem;
            background-color: var(--btnbg) !important;
            color: var(--btnfontcolor) !important;
        }

        .bg-danger {
            font-size: 1rem;
        }


        .price-hp {
            font-size: 1rem;
            font-weight: 600;
            color: darkgray;
        }

        .amz-hp {
            font-size: .7rem;
            font-weight: 600;
            color: darkgray;
        }

        .fa-question-circle:before {
            /* content: "\f059"; */
            color: darkgray;
        }

        .fa-heart:before {
            color: crimson;
        }

        .fa-chevron-circle-right:before {
            color: darkgray;
        }


        /* Add to Cart Button css */
        button {
            position: relative;
            overflow: hidden;
            outline: none;
            cursor: pointer;
            border-radius: 50px;
            background-color: hsl(255deg 50% 40%);
            border: solid 4px hsl(50deg 100% 50%);
            font-family: inherit;
        }

        .default-btn, .hover-btn {
            background-color: hsl(255deg 50% 40%);
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 8px 12px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 500;
            text-transform: uppercase;
            transition: all .3s ease;
        }

        .hover-btn {
            position: absolute;
            inset: 0;
            background-color: hsl(255deg 50% 49%);
            transform: translate(0%, 100%);
        }

        .default-btn span {
            color: hsl(0, 0%, 100%);
        }

        .hover-btn span {
            color: hsl(50deg 100% 50%);
        }

        button:hover .default-btn {
            transform: translate(0%, -100%);
        }

        button:hover .hover-btn {
            transform: translate(0%, 0%);
        }

    </style>
</head>
<body>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">

    </div>
</section>


<div class="container-fluid bg-trasparent my-4 p-3" style="position: relative">
    <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3">
        <div class="col hp">
            <div class="card h-100" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
                <a target="_blank" href="{{asset('assets/images/products/Mens-t-shirt.png')}}">
                    <img src="{{asset('assets/images/products/Mens-t-shirt.png')}}" class="card-img-top"
                         alt="product.title" />
                </a>
                <div class="card-body">
                    <div class="clearfix mb-3">
                        <span class="float-start badge rounded-pill bg-success">1200tk</span>
                        <span class="float-end"><a href="#" class="small text-muted text-uppercase aff-link">Reviews</a></span>
                    </div>
                    <h5 class="card-title">
                        <a target="_blank" href="#">Croissant muffin powder sugar plum jujubes macaroon. Chocolate bar bear claw ice cream fruitcake cotton candy pastry. Icing pastry pastry muffin tiramisu pastry chocolate bar. Sugar plum jelly beans cupcake ice cream dragée. Chocolate cake gummi bears pie shortbread powder topping ice cream. Lollipop liquorice jujubes carrot cake apple pie. Dessert bear claw sweet roll apple pie croissant tiramisu wafer dessert. Marzipan wafer biscuit shortbread jujubes. Chocolate cake muffin gummi bears chupa chups tart. Tart halvah bonbon danish bonbon. Oat cake carrot cake brownie bonbon pudding chocolate bar cheesecake bear claw bonbon. Cupcake chupa chups biscuit muffin candy canes jelly beans liquorice jelly beans soufflé. Ice cream candy canes chupa chups jelly beans pastry topping</a>
                    </h5>

                    <div class="d-grid gap-2 my-4">
                        <button>
                            <div class="default-btn">
                                <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none"
                                     stroke-width="2" stroke="#FFF" height="20" width="20" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle r="3" cy="12" cx="12"></circle>
                                </svg>
                                <span>Add To Cart</span>
                            </div>
                            <div class="hover-btn">
                                <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none"
                                     stroke-width="2" stroke="#ffd300" height="20" width="20" viewBox="0 0 24 24">
                                    <circle r="1" cy="21" cx="9"></circle>
                                    <circle r="1" cy="21" cx="20"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span>Shop Now</span>
                            </div>
                        </button>
                    </div>
                    <div class="clearfix mb-1">
                        <span class="float-start"><a href="#"><i class="fas fa-question-circle"></i></a></span>
                        <span class="float-end">
              <i class="far fa-heart" style="cursor: pointer"></i>

            </span>
                    </div>
                </div>
            </div>
        </div> <div class="col hp">
            <div class="card h-100" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
                <a target="_blank" href="{{asset('assets/images/products/Mens-t-shirt.png')}}">
                    <img src="{{asset('assets/images/products/Mens-t-shirt.png')}}" class="card-img-top"
                         alt="product.title" />
                </a>
                <div class="card-body">
                    <div class="clearfix mb-3">
                        <span class="float-start badge rounded-pill bg-success">1200tk</span>
                        <span class="float-end"><a href="#" class="small text-muted text-uppercase aff-link">Reviews</a></span>
                    </div>
                    <h5 class="card-title">
                        <a target="_blank" href="#">Croissant muffin powder sugar plum jujubes macaroon. Chocolate bar bear claw ice cream fruitcake cotton candy pastry. Icing pastry pastry muffin tiramisu pastry chocolate bar. Sugar plum jelly beans cupcake ice cream dragée. Chocolate cake gummi bears pie shortbread powder topping ice cream. Lollipop liquorice jujubes carrot cake apple pie. Dessert bear claw sweet roll apple pie croissant tiramisu wafer dessert. Marzipan wafer biscuit shortbread jujubes. Chocolate cake muffin gummi bears chupa chups tart. Tart halvah bonbon danish bonbon. Oat cake carrot cake brownie bonbon pudding chocolate bar cheesecake bear claw bonbon. Cupcake chupa chups biscuit muffin candy canes jelly beans liquorice jelly beans soufflé. Ice cream candy canes chupa chups jelly beans pastry topping</a>
                    </h5>

                    <div class="d-grid gap-2 my-4">
                        <button>
                            <div class="default-btn">
                                <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none"
                                     stroke-width="2" stroke="#FFF" height="20" width="20" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle r="3" cy="12" cx="12"></circle>
                                </svg>
                                <span>Add To Cart</span>
                            </div>
                            <div class="hover-btn">
                                <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none"
                                     stroke-width="2" stroke="#ffd300" height="20" width="20" viewBox="0 0 24 24">
                                    <circle r="1" cy="21" cx="9"></circle>
                                    <circle r="1" cy="21" cx="20"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                <span>Shop Now</span>
                            </div>
                        </button>
                    </div>
                    <div class="clearfix mb-1">
                        <span class="float-start"><a href="#"><i class="fas fa-question-circle"></i></a></span>
                        <span class="float-end">
              <i class="far fa-heart" style="cursor: pointer"></i>

            </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="mb-5 mt-5">

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Key Features</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    <h3>Feature One</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
                <div class="col-md-4 text-center">
                    <h3>Feature Two</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
                <div class="col-md-4 text-center">
                    <h3>Feature Three</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="mb-5 mt-5">

        <!-- Product Carousel Section -->
        <section class="py-5">
            <div class="container">
                <h2 class="text-center mb-5">Product Gallery</h2>
                <div class="owl-carousel owl-theme product-carousel">
                    <div class="item">
                        <img src="https://via.placeholder.com/400x300" alt="Product 1">
                        <h4>Product 1</h4>
                        <p>Description of Product 1.</p>
                    </div>
                    <div class="item">
                        <img src="https://via.placeholder.com/400x300" alt="Product 2">
                        <h4>Product 2</h4>
                        <p>Description of Product 2.</p>
                    </div>
                    <div class="item">
                        <img src="https://via.placeholder.com/400x300" alt="Product 3">
                        <h4>Product 3</h4>
                        <p>Description of Product 3.</p>
                    </div>
                    <div class="item">
                        <img src="https://via.placeholder.com/400x300" alt="Product 4">
                        <h4>Product 4</h4>
                        <p>Description of Product 4.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="py-5 bg-primary text-white">
            <div class="container text-center">
                <h2>Ready to Get Started?</h2>
                <p>Join thousands of satisfied customers today.</p>
                <a href="#" class="btn btn-light btn-lg">Buy Now</a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-4 bg-dark text-white text-center">
            <div class="container">
                <p>&copy; 2023 Your Company. All rights reserved.</p>
            </div>
        </footer>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jQuery (Required for Owl Carousel) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Owl Carousel 2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
        <!-- Initialize Owl Carousel -->
        <script>
            $(document).ready(function() {
                $(".owl-carousel").owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: true,
                    responsive: {
                        0: {
                            items: 1,
                        },
                        600: {
                            items: 2,
                        },
                        1000: {
                            items: 3,
                        },
                    },
                });
            });
        </script>
</body>
</html>