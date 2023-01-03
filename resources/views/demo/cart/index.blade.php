@php
    if (!function_exists('currency_format')) {
        function currency_format($number, $suffix = 'đ')
        {
            if (!empty($number)) {
                return number_format($number, 0, ',', '.') . "{$suffix}";
            }
        }
    }
@endphp
<!DOCTYPE html>
<html>

<head>
    <title>Unitop Store</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ url('/') }}/public/cart/css/bootstrap/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/public/cart/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/public/cart/reset.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/public/cart/css/font-awesome/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('/') }}/public/cart/style.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/public/cart/responsive.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/public/cart/css/import/cart.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/public/cart/css/import/global.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/public/cart/css/import/header.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/public/cart/css/import/footer.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/public/cart/css/import/home.css" rel="stylesheet" type="text/css" />

    <script src="{{ url('/') }}/public/cart/js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/public/cart/js/bootstrap/bootstrap.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/public/cart/js/main.js" type="text/javascript"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    <div id="site">
        <div id="container">
            <div id="header-wp" class="clearfix">
                <div class="wp-inner">
                    <a href="?page=home" title="" id="logo" class="fl-left">Quốc Mạnh</a>
                    <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i></div>
                    <div id="cart-wp" class="fl-right">
                        <a href="?page=cart" title="" id="btn-cart">
                            <span id="icon"><img src="public/images/icon-cart.png" alt=""></span>
                            <span id="num">
                                @if ($numOrder > 0)
                                    {{ $numOrder }}
                                @endif
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div id="main-content-wp" class="cart-page">
                <div class="section" id="breadcrumb-wp">
                    <div class="wp-inner">
                        <div class="section-detail">
                            <h3 class="title">Giỏ hàng</h3>
                        </div>
                    </div>
                </div>
                <form action="{{ route('demo.cart.update') }}" method='post'>
                    @csrf
                    <div id="wrapper" class="wp-inner clearfix">
                        <?php
                        if(!empty($cartBuy)){
                            ?>

                        <div class="section" id="info-cart-wp">
                            <div class="section-detail table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td>Mã sản phẩm</td>
                                            <td>Ảnh sản phẩm</td>
                                            <td>Tên sản phẩm</td>
                                            <td>Giá sản phẩm</td>
                                            <td>Số lượng</td>
                                            <td colspan="2">Thành tiền</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cartBuy as $item)
                                            <tr>
                                                <td>{{ $item['session_id'] }}</td>
                                                <td>
                                                    <a href="" title="" class="thumb">
                                                        <img src="{{ Avatar::create($item['image'])->toBase64() }}"
                                                            alt="">
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="" title=""
                                                        class="name-product">{{ $item['title'] }}</a>
                                                </td>
                                                <td>{{ currency_format($item['price']) }}</td>
                                                <td>
                                                    <input type="number" name="qty[{{ $item['id'] }}]"
                                                        value="{{ $item['qty'] }}" class="num-order" min='1'
                                                        max='5' data-id='{{ $item['id'] }}'>
                                                </td>
                                                <td id='sub-total-{{ $item['id'] }}'>{{ currency_format($item['sub_total']) }}</td>
                                                <td>
                                                    <a href="{{ route('demo.cart.delete', $item['id']) }}"
                                                        title="" class="del-product"><i
                                                            class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7">
                                                <div class="clearfix">
                                                    <p id="total-price" class="fl-right">Tổng giá:
                                                        <span>{{ currency_format($getTotalCart) }}</span>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <div class="clearfix">
                                                    <div class="fl-right">
                                                        <input type="submit" name='btn_update_cart'
                                                            value='Cập nhật giỏ hàng'>
                                                        <a href="#" title="" id="checkout-cart">Thanh
                                                            toán</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="section" id="action-cart-wp">
                            <div class="section-detail">
                                <p class="title">Click vào <span>“Cập nhật giỏ hàng”</span> để cập nhật số lượng. Nhập
                                    vào
                                    số lượng <span>0</span> để xóa sản phẩm khỏi giỏ hàng. Nhấn vào thanh toán để hoàn
                                    tất
                                    mua hàng.</p>
                                <a href="#" title="" id="buy-more">Mua tiếp</a><br />
                                <a href="{{ route('demo.cart.delete_all') }}" title="" id="delete-cart">Xóa
                                    giỏ hàng</a>
                            </div>
                        </div>
                        <?php
                        }else {
                            ?>
                        <p>Kkhông có sản phẩm nào trong giỏ hàng</p>
                        <?php
                        }
                    ?>
                    </div>
                </form>
            </div>
            <div id="footer-wp">
                <div class="wp-inner">
                    <p id="copyright">©2023 Quốc Mạnh</p>
                </div>
            </div>
        </div>
        <div id="menu-respon">
            <a href="?page=home" title="" class="logo">Quốc Mạnh</a>
            <div id="menu-respon-wp">
                <ul class="" id="main-menu-respon">
                    <li>
                        <a href="?page=home" title="Trang chủ">Trang chủ</a>
                    </li>
                    <li>
                        <a href="?page=detail_news" title="Giới thiệu">Giới thiệu</a>
                    </li>
                    <li>
                        <a href="?page=category_product" title="">Laptop</a>
                    </li>
                    <li>
                        <a href="?page=category_product" title="">Điện thoại</a>
                    </li>
                    <li>
                        <a href="?page=category_product" title="">Máy tính bảng</a>
                    </li>
                    <li>
                        <a href="?page=detail_news" title="Liên hệ">Liên hệ</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    $('.num-order').change(function() {
        let id = $(this).attr('data-id');
        let qty = $(this).val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ route('demo.cart.update_ajax') }}',
            type: 'POST',
            data: {
                id: id,
                qty: qty
            },
            success: function(data) {
                $('#sub-total-' + id).text(data.data.sub_total);
                $('#total-price span').text(data.data.total);
                $('#num').text(data.data.num_order);
            }
        });
    });
</script>
