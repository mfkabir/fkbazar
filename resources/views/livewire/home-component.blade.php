<main id="main">
    <div class="container">

        <!--MAIN SLIDE-->
        <div class="wrap-main-slide">
            <div class="slide-carousel owl-carousel style-nav-1" data-items="1" data-loop="1" data-nav="true" data-dots="false">
                @foreach($sliders as $slider)
                    <div class="item-slide">
                        <img src="{{ asset('assets/images/sliders') }}/{{ $slider->image }}" alt="{{ $slider->title }}" class="img-slide">
                        <div class="slide-info slide-1">
                            <h2 class="f-title">{{ $slider->title }}</b></h2>
                            <span class="subtitle">{{ $slider->subtitle }}</span>
                            <p class="sale-info">Only price: <span class="price">${{ $slider->price }}</span></p>
                            <a href="{{ $slider->link }}" class="btn-link">Shop Now</a>
                        </div>
                    </div>
                @endforeach                
            </div>
        </div>

        <!--BANNER-->
        <div class="wrap-banner style-twin-default">
            <div class="banner-item">
                <a href="#" class="link-banner banner-effect-1">
                    <figure><img src="{{ asset('assets/images/home-1-banner-1.jpg') }}" alt="" width="580" height="190"></figure>
                </a>
            </div>
            <div class="banner-item">
                <a href="#" class="link-banner banner-effect-1">
                    <figure><img src="{{ asset('assets/images/home-1-banner-2.jpg') }}" alt="" width="580" height="190"></figure>
                </a>
            </div>
        </div>

        <!--On Sale-->
        <div class="wrap-show-advance-info-box style-1 has-countdown">
            <h3 class="title-box">On Sale</h3>
            <div class="wrap-countdown mercado-countdown" data-expire="2021/12/12 12:34:56"></div>
            <div class="wrap-products slide-carousel owl-carousel style-nav-1 equal-container " data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"4"},"1200":{"items":"5"}}'>
                @foreach($on_sale_products as $product)
                    <div class="product product-style-2 equal-elem">
                        <div class="product-thumnail">
                            <a href="{{ route('product.details', ['slug' => $product->slug]) }}" title="{{ $product->name }}">
                                <figure><img src="{{ asset('assets/images/products') }}/{{ $product->image }}.jpg" width="800" height="800" alt="{{ $product->name }}"></figure>
                            </a>
                            <div class="group-flash">
                                <span class="flash-item sale-label">sale</span>
                            </div>
                        </div>
                        <div class="product-info">
                            <a href="{{ route('product.details', ['slug' => $product->slug]) }}" class="product-name"><span>{{ $product->name }}</span></a>
                            <div class="wrap-price"><span class="product-price">${{ $product->regular_price }}</span></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!--Latest Products-->
        <div class="wrap-show-advance-info-box style-1">
            <h3 class="title-box">Latest Products</h3>
            <div class="wrap-top-banner">
                <a href="#" class="link-banner banner-effect-2">
                    <figure><img src="{{ asset('assets/images/digital-electronic-banner.jpg') }}" width="1170" height="240" alt=""></figure>
                </a>
            </div>
            <div class="wrap-products">
                <div class="wrap-product-tab tab-style-1">
                    <div class="tab-contents">
                        <div class="tab-content-item active" id="digital_1a">
                            <div class="wrap-products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"4"},"1200":{"items":"5"}}'>
                                @foreach($latest_products as $l_product)
                                    <div class="product product-style-2 equal-elem ">
                                        <div class="product-thumnail">
                                            <a href="{{ route('product.details', ['slug' => $l_product->slug]) }}" title="{{ $l_product->name }}">
                                                <figure><img src="{{ asset('assets/images/products') }}/{{ $l_product->image }}.jpg" width="800" height="800" alt="{{ $l_product->name }}"></figure>
                                            </a>
                                        </div>
                                        <div class="product-info">
                                            <a href="{{ route('product.details', ['slug' => $l_product->slug]) }}" class="product-name"><span>{{ $l_product->name }}</span></a>
                                            <div class="wrap-price"><span class="product-price">${{ $l_product->regular_price }}</span></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Product Categories-->
        <div class="wrap-show-advance-info-box style-1">
            <h3 class="title-box">Product Categories</h3>
            <div class="wrap-top-banner">
                <a href="#" class="link-banner banner-effect-2">
                    <figure><img src="{{ asset('assets/images/fashion-accesories-banner.jpg') }}" width="1170" height="240" alt=""></figure>
                </a>
            </div>
            <div class="wrap-products">
                <div class="wrap-product-tab tab-style-1">
                    <div class="tab-control">
                        @foreach($tab_arr as $index => $tab)
                            <a href="#fashion_{{ $index }}" class="tab-control-item {{ ($index == 0) ? 'active' : '' }}">{{ $tab['title'] }}</a>
                        @endforeach
                    </div>
                    <div class="tab-contents">
                        @foreach($tab_arr as $index => $tab)
                            <div class="tab-content-item {{ ($index == 0) ? 'active' : '' }}" id="fashion_{{ $index }}">
                                <div class="wrap-products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5" data-loop="false" data-nav="true" data-dots="false" data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"4"},"1200":{"items":"5"}}'>
                                    @foreach($tab['content'] as $item)
                                        <div class="product product-style-2 equal-elem ">
                                            <div class="product-thumnail">
                                                <a href="{{ route('product.details', ['slug' => $item->slug]) }}" title="{{ $item->name }}">
                                                    <figure><img src="{{ asset('assets/images/products') }}/{{ $item->image }}.jpg" width="800" height="800" alt="{{ $item->name }}"></figure>
                                                </a>
                                            </div>
                                            <div class="product-info">
                                                <a href="{{ route('product.details', ['slug' => $item->slug]) }}" class="product-name"><span>{{ $item->name }}</span></a>
                                                <div class="wrap-price"><span class="product-price">${{ $item->regular_price }}</span></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

</main>
