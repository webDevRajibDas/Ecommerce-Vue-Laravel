@extends('frontend.ict.layouts.page')
@section('title', 'B2B Smart Card')
@section('content')

    <div class="slider-container rev_slider_wrapper" style="height: 100vh;">
        <div id="revolutionSlider" class="slider rev_slider" data-version="5.4.8" data-plugin-revolution-slider data-plugin-options="{'sliderLayout': 'fullscreen', 'delay': 9000, 'gridwidth': 1170, 'gridheight': 700, 'disableProgressBar': 'on', 'responsiveLevels': [4096,1200,992,500], 'parallax': { 'type': 'scroll', 'origo': 'enterpoint', 'speed': 1000, 'levels': [2,3,4,5,6,7,8,9,12,50], 'disable_onmobile': 'on' }}">
            <ul>
                <li class="slide-overlay" data-transition="fade">
                    <img src="{{asset('assets/ict/slides/slide-corporate-7-1.jpg')}}"
                         alt=""
                         data-bgposition="center center"
                         data-bgfit="cover"
                         data-bgrepeat="no-repeat"
                         class="rev-slidebg">

                    <div class="tp-caption"
                         data-x="center" data-hoffset="['-165','-165','-165','-215']"
                         data-y="center" data-voffset="['-110','-110','-110','-135']"
                         data-start="1000"
                         data-transform_in="x:[-300%];opacity:0;s:500;"
                         data-transform_idle="opacity:0.2;s:500;"><img src="{{asset('img/ict/images/slide-title-border.png')}}" alt=""></div>

                    <div class="tp-caption text-color-light font-weight-normal"
                         data-x="center"
                         data-y="center" data-voffset="['-110','-110','-110','-135']"
                         data-start="700"
                         data-fontsize="['22','22','22','40']"
                         data-lineheight="['25','25','25','45']"
                         data-transform_in="y:[-50%];opacity:0;s:500;">WE ROCK AND WE MAKE</div>

                    <div class="tp-caption"
                         data-x="center" data-hoffset="['165','165','165','215']"
                         data-y="center" data-voffset="['-110','-110','-110','-135']"
                         data-start="1000"
                         data-transform_in="x:[300%];opacity:0;s:500;"
                         data-transform_idle="opacity:0.2;s:500;"><img src="{{asset('img/ict/slides/slide-title-border.png')}}" alt=""></div>

                    <h1 class="tp-caption font-weight-extra-bold text-color-light negative-ls-2"
                        data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:1.5;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-x="center"
                        data-y="center" data-voffset="['-60','-60','-60','-85']"
                        data-fontsize="['50','50','50','90']"
                        data-lineheight="['55','55','55','95']"> B2B Smart Digital Card</h1>

                    <div class="tp-caption font-weight-light text-center"
                         data-frames='[{"from":"opacity:0;","speed":300,"to":"o:1;","delay":2000,"split":"chars","splitdelay":0.05,"ease":"Power2.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'
                         data-x="center"
                         data-y="center" data-voffset="['-10','-10','-10','-25']"
                         data-fontsize="['18','18','18','50']"
                         data-lineheight="['29','29','29','40']"
                         style="color: #b5b5b5;">AWESOME DESIGNS </div>


                </li>
                <li class="slide-overlay" data-transition="fade">
                    <img src="{{asset('assets/ict/slides/slide-corporate-7-1.jpg')}}"
                         alt=""
                         data-bgposition="center center"
                         data-bgfit="cover"
                         data-bgrepeat="no-repeat"
                         class="rev-slidebg">

                    <div class="tp-caption"
                         data-x="center" data-hoffset="['-165','-165','-165','-215']"
                         data-y="center" data-voffset="['-110','-110','-110','-135']"
                         data-start="1000"
                         data-transform_in="x:[-300%];opacity:0;s:500;"
                         data-transform_idle="opacity:0.2;s:500;"><img src="{{asset('img/ict/images/slide-title-border.png')}}" alt=""></div>

                    <div class="tp-caption text-color-light font-weight-normal"
                         data-x="center"
                         data-y="center" data-voffset="['-110','-110','-110','-135']"
                         data-start="700"
                         data-fontsize="['22','22','22','40']"
                         data-lineheight="['25','25','25','45']"
                         data-transform_in="y:[-50%];opacity:0;s:500;">WE ROCK AND WE MAKE</div>

                    <div class="tp-caption"
                         data-x="center" data-hoffset="['165','165','165','215']"
                         data-y="center" data-voffset="['-110','-110','-110','-135']"
                         data-start="1000"
                         data-transform_in="x:[300%];opacity:0;s:500;"
                         data-transform_idle="opacity:0.2;s:500;"><img src="{{asset('img/ict/slides/slide-title-border.png')}}" alt=""></div>

                    <h1 class="tp-caption font-weight-extra-bold text-color-light negative-ls-2"
                        data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:1.5;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                        data-x="center"
                        data-y="center" data-voffset="['-60','-60','-60','-85']"
                        data-fontsize="['50','50','50','90']"
                        data-lineheight="['55','55','55','95']"> B2B Smart Digital Card</h1>

                    <div class="tp-caption font-weight-light text-center"
                         data-frames='[{"from":"opacity:0;","speed":300,"to":"o:1;","delay":2000,"split":"chars","splitdelay":0.05,"ease":"Power2.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'
                         data-x="center"
                         data-y="center" data-voffset="['-10','-10','-10','-25']"
                         data-fontsize="['18','18','18','50']"
                         data-lineheight="['29','29','29','40']"
                         style="color: #b5b5b5;">AWESOME DESIGNS </div>


                </li>
            </ul>
        </div>
    </div>



    <!-- Full Width Section -->
    <section class="full-width-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="video-container">
                        <iframe src="https://www.youtube.com/embed/your-youtube-video-id" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-md-6 text-center-vertical">
                    <div class="bgblue">
                        <div class="promo-card">
                            <h4 class="text-white font-weight-bold text-12">B2B Smart Card</h4>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <div class="row pt-4 mt-5">
            <div class="col">
                <div class="row pt-2 clearfix">
                    <div class="col-lg-6">
                        <div class="feature-box feature-box-style-2 reverse appear-animation" data-appear-animation="fadeInRightShorter">
                            <div class="feature-box-icon">
                                <i class="icon-user-following icons text-color-primary"></i>
                            </div>
                            <div class="feature-box-info">
                                <h4 class="mb-2">Customer Support</h4>
                                <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing metus elit. Quisque rutrum pellentesque imperdiet.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="feature-box feature-box-style-2 appear-animation" data-appear-animation="fadeInLeftShorter">
                            <div class="feature-box-icon">
                                <i class="icon-layers icons text-color-primary"></i>
                            </div>
                            <div class="feature-box-info">
                                <h4 class="mb-2">Sliders</h4>
                                <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum pellentesque imperdiet. Nulla lacinia iaculis nulla.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="feature-box feature-box-style-2 reverse appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="200">
                            <div class="feature-box-icon">
                                <i class="icon-calculator icons text-color-primary"></i>
                            </div>
                            <div class="feature-box-info">
                                <h4 class="mb-2">HTML5</h4>
                                <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum pellentesque imperdiet. Nulla lacinia iaculis nulla.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="feature-box feature-box-style-2 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="200">
                            <div class="feature-box-icon">
                                <i class="icon-star icons text-color-primary"></i>
                            </div>
                            <div class="feature-box-info">
                                <h4 class="mb-2">Icons</h4>
                                <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing metus elit. Quisque rutrum pellentesque imperdiet.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="feature-box feature-box-style-2 reverse appear-animation" data-appear-animation="fadeInRightShorter" data-appear-animation-delay="400">
                            <div class="feature-box-icon">
                                <i class="icon-drop icons text-color-primary"></i>
                            </div>
                            <div class="feature-box-info">
                                <h4 class="mb-2">Colors</h4>
                                <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum pellentesque imperdiet. Nulla lacinia iaculis nulla.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="feature-box feature-box-style-2 appear-animation" data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="400">
                            <div class="feature-box-icon">
                                <i class="icon-mouse icons text-color-primary"></i>
                            </div>
                            <div class="feature-box-info">
                                <h4 class="mb-2">Buttons</h4>
                                <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum pellentesque imperdiet. Nulla lacinia iaculis nulla.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pb-5 mb-5 mt-3">
            <div class="col text-center">

            </div>
        </div>
    </div>

    <section class="section section-secondary border-0 py-0 m-0 appear-animation" data-appear-animation="fadeIn" style="height: 450px;">
        <div class="container">
            <div class="row align-items-center justify-content-center justify-content-lg-between pb-5 pb-lg-0">
                <h2 class="font-weight-bold text-color-light text-7 mb-2 mt-4">BRANDED PRODUCTS</h2>
            </div>
            <div class="row mb-5 pb-3">
                <div class="col-md-6 col-lg-4 mb-5 mb-lg-0 appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">
                    <div class="card flip-card text-center rounded-0">
                        <div class="flip-front p-5">
                            <div class="flip-content my-4">
                                <strong class="font-weight-extra-bold text-color-dark line-height-1 text-13 mb-3 d-inline-block">01</strong>
                                <h4 class="font-weight-bold text-color-primary text-4">FIRST STEP</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus blandit massa enim. Nullam id varius.</p>
                            </div>
                        </div>
                        <div class="flip-back d-flex align-items-center p-5"
                             style="background-image: url({{ asset('assets/ict/images/generic-corporate-17-1.jpg') }});
            background-size: cover;
            background-position: center;">
                            <div class="flip-content my-4">
                                <h4 class="font-weight-bold text-color-light">FIRST MEETING</h4>
                                <p class="font-weight-light text-color-light opacity-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus blandit massa enim. Nullam id varius.</p>
                                <a href="#" class="btn btn-light btn-modern text-color-dark font-weight-bold">LEARN MORE</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-height-4 bg-color-grey-scale-1 border-0 m-0 pb-5">
        <div class="container">
            <div class="row justify-content-center my-4">
                <div class="col appear-animation" data-appear-animation="fadeInUpShorter">
                    <div class="owl-carousel owl-theme nav-bottom rounded-nav" data-plugin-options="{'items': 1, 'loop': true, 'autoHeight': true}">
                        <div>
                            <div class="col">
                                <div class="testimonial testimonial-style-2 testimonial-with-quotes testimonial-quotes-dark mb-0">
                                    <div class="testimonial-author">
                                        <img src="{{asset('img/ict/clients/client-1.jpg')}}" class="img-fluid rounded-circle" alt="">
                                    </div>
                                    <blockquote>
                                        <p class="text-color-dark text-5 line-height-5">Your time is limited, so don’t waste it living someone else’s life. Don’t be trapped by dogma - which is living with the results of other people’s thinking. Don’t let the noise of others’ opinions drown out your own inner voice.</p>
                                    </blockquote>
                                    <div class="testimonial-author">
                                        <p class="opacity-10"><strong class="font-weight-extra-bold text-2">- John Smith. Okler</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="col">
                                <div class="testimonial testimonial-style-2 testimonial-with-quotes testimonial-quotes-dark mb-0">
                                    <div class="testimonial-author">
                                        <img src="{{asset('img/ict/clients/client-1.jpg')}}" class="img-fluid rounded-circle" alt="">
                                    </div>
                                    <blockquote>
                                        <p class="text-color-dark text-5 line-height-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eget risus porta, tincidunt turpis at, interdum tortor. Suspendisse potenti. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce ante tellus, convallis non consectetur sed, pharetra nec ex.</p>
                                    </blockquote>
                                    <div class="testimonial-author">
                                        <p class="opacity-10"><strong class="font-weight-extra-bold text-2">- John Smith. Okler</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="col">
                                <div class="testimonial testimonial-style-2 testimonial-with-quotes testimonial-quotes-dark mb-0">
                                    <div class="testimonial-author">
                                        <img src="{{asset('img/ict/clients/client-1.jpg')}}" class="img-fluid rounded-circle" alt="">
                                    </div>
                                    <blockquote>
                                        <p class="text-color-dark text-5 line-height-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eget risus porta, tincidunt turpis at, interdum tortor. Suspendisse potenti. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                    </blockquote>
                                    <div class="testimonial-author">
                                        <p class="opacity-10"><strong class="font-weight-extra-bold text-2">- John Smith. Okler</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row py-5 my-5">
            <div class="col">

                <div class="owl-carousel owl-theme mb-0" data-plugin-options="{'responsive': {'0': {'items': 1}, '476': {'items': 1}, '768': {'items': 5}, '992': {'items': 7}, '1200': {'items': 7}}, 'autoplay': true, 'autoplayTimeout': 3000, 'dots': false}">
                    <div>
                        <img class="img-fluid opacity-2" src="{{asset('img/ict/logos/logo-1.png')}}" alt="">
                    </div>
                    <div>
                        <img class="img-fluid opacity-2" src="{{asset('img/ict/logos/logo-2.png')}}" alt="">
                    </div>
                    <div>
                        <img class="img-fluid opacity-2" src="{{asset('img/ict/logos/logo-3.png')}}" alt="">
                    </div>
                    <div>
                        <img class="img-fluid opacity-2" src="{{asset('img/ict/logos/logo-4.png')}}" alt="">
                    </div>
                    <div>
                        <img class="img-fluid opacity-2" src="{{asset('img/ict/logos/logo-4.png')}}" alt="">
                    </div>
                    <div>
                        <img class="img-fluid opacity-2" src="{{asset('img/ict/logos/logo-6.png')}}" alt="">
                    </div>
                    <div>
                        <img class="img-fluid opacity-2" src="{{asset('img/ict/logos/logo-4.png')}}" alt="">
                    </div>
                    <div>
                        <img class="img-fluid opacity-2" src="{{asset('img/ict/logos/logo-2.png')}}" alt="">
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection