<?php

header("Content-type: text/css; charset: UTF-8");

//-------------------------------------------------------------------

if (isset($_GET['theme_color_1'])) {
    $theme_color_1 = '#' . $_GET['theme_color_1'];
} else {
    $theme_color_1 = '#0f78f2';
}
if (isset($_GET['theme_color_2'])) {
    $theme_color_2 = '#' . $_GET['theme_color_2'];
} else {
    $theme_color_2 = '#000000';
}

if (isset($_GET['text_color_1'])) {
    $text_color_1 = '#' . $_GET['text_color_1'];
} else {
    $text_color_1 = '#02020c';
}

if (isset($_GET['text_color_2'])) {
    $text_color_2 = '#' . $_GET['text_color_2'];
} else {
    $text_color_2 = '#02020c';
}

//-------------------------------------------------------------------

?>


.categories_menu_inner_horizontal > ul > li > a, .categories_menu_inner_horizontal > ul > li span > a {
    color: <?php echo $theme_color_1; ?> !important;
}

.top-header .content .right-content .list ul li .language-selector .language {
background: <?php echo $theme_color_2; ?>;}
.top-header .content .right-content .list ul li .currency-selector .currency {
background: <?php echo $theme_color_2; ?>;}

.logo-header {
background: <?php echo $theme_color_2; ?>; }

.logo-header .helpful-links ul li .wish i,
.logo-header .helpful-links ul li.my-dropdown .cart .icon i {
color: <?php echo $theme_color_2; ?>; }

.logo-header .search-box .categori-container .categoris option {
background: <?php echo $theme_color_2; ?>; }

.autocomplete-items div {
background: <?php echo "#fff"; ?>;
border: 0.5px solid <?php echo "#fff"; ?>;
z-index: 100;
}

.bottomtotop i {
    color: <?php echo $text_color_1; ?>;
}

.mybtn1,
.bottomtotop i,
.logo-header .search-box .categori-container .categoris option:hover,
.trending .item .item-img .time,
.hero-area .hero-area-slider .intro-carousel.owl-carousel .owl-controls .owl-nav .owl-prev:hover,
.hero-area .hero-area-slider .intro-carousel.owl-carousel .owl-controls .owl-nav .owl-next:hover,
.hero-area .info-box:hover .icon,
.trending .item .item-img .sale,
.trending .item .item-img .discount,
.trending .item .item-img .extra-list ul li a ,
.trending .item .item-img .extra-list ul li a:hover,
.categori-item .item .item-img .sale,
.categori-item .item .item-img .discount,
.categori-item .item .item-img .extra-list ul li a,
.categori-item .item .item-img .extra-list ul li a:hover,
.flash-deals .flas-deal-slider .item .item-img .discount,
.flash-deals .flas-deal-slider .owl-controls .owl-dots .owl-dot.active,
.hot-and-new-item .categori .section-top .section-title::after,
.blog-area .aside .slider-wrapper .owl-controls .owl-dots .owl-dot.active,
.blog-area .blog-box .blog-images .img .date,
.blog-area .blog-box .details .read-more-btn,
.footer .fotter-social-links ul li a:hover,
.product-details-page .all-item .slidPrv4.slick-arrow,
.product-details-page .all-item .slidNext4.slick-arrow,
.product-details-page .right-area .product-info .contact-seller .list li a:hover,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare a:hover,
.product-details-page #coment-area .all-comments li .single-comment .right-area .replaybtn:hover,
.product-details-page #coment-area .write-comment-area .submit-btn,
.ui-widget-header,
.ui-slider .ui-slider-handle,
.sub-categori .left-area .filter-result-area .body-area .filter-btn,
.sub-categori .left-area .tags-area .body-area .taglist li a:hover,
.sub-categori .right-area .categori-item-area .item .item-img .time,
.sub-categori .right-area .categori-item-area .item .item-img .sale,
.sub-categori .right-area .categori-item-area .item .item-img .discount,
.sub-categori .right-area .categori-item-area .item .item-img .extra-list ul li a:hover,
.sub-categori .right-area .pagination-area .pagination .page-item .page-link.active,
.sub-categori .right-area .pagination-area .pagination .page-item .page-link:hover,
.sub-categori .modal .modal-dialog .modal-header,
.sub-categori .modal .contact-form .submit-btn,
.cartpage .left-area .table tbody tr td.quantity .qty ul li .qtminus:hover,
.cartpage .left-area .table tbody tr td.quantity .qty ul li .qtplus:hover,
.cartpage .right-area .order-box .cupon-box #coupon-form button:hover,
#freight-form button:hover,
.cartpage .right-area .order-box .order-btn,
.blogpagearea .blog-box .blog-images .img .date,
.blogpagearea .blog-box .details .read-more-btn,
.blog-details .blog-content .content .tag-social-link .social-links li a,
.blog-details .comments .comment-box-area li .comment-box .left .replay,
.blog-details .comments .comment-box-area li .comment-box .left .replay:hover,
.blog-details .comments .comment-box-area li .replay-form .replay-form-close:hover,
.blog-details .comments .comment-box-area li .replay-form .replay-comment-btn,
.blog-details .comments .comment-box-area li .replay-form .replay-comment-btn:hover,
.blog-details .write-comment .submit-btn,
.blog-details .write-comment .submit-btn:hover,
.blog-details .blog-aside .tags .tags-list li a:hover,
.contact-us .left-area .contact-form .submit-btn,
.contact-us .right-area .contact-info .left .icon,
.contact-us .right-area .social-links ul li a,
.contact-us .right-area .social-links ul li a:hover,
.login-signup .login-area .submit-btn,
.ui-accordion .ui-accordion-header,
.compare-page-content-wrap .btn__bg,
.user-dashbord .user-profile-details .mycard,
.user-dashbord .user-profile-details .account-info .edit-info-area .edit-info-area-form .back:hover,
.user-dashbord .user-profile-details .account-info .edit-info-area .edit-info-area-form .submit-btn,
.single-wish .remove:hover,
.sub-categori .right-area .categori-item-area .item .item-img .extra-list ul li a
{
background: <?php echo $theme_color_1; ?>;
}

.section-top .link,
.input-field.error:-ms-input-placeholder,
.input-field.error::-moz-placeholder,
.input-field.error::-webkit-input-placeholder,
.breadcrumb-area .pages li a:hover,
.categories_menu_inner > ul > li > ul.categories_mega_menu > li > a:hover,
.categories_menu_inner_horizontal > ul > li > ul.categories_mega_menu > li > a:hover,
nav .menu li a:hover,
nav .menu li.dropdown.open > a,
.hero-area .hero-area-slider .intro-carousel .intro-content .slider-content .layer-1 .title,
.trending li.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab.ui-tabs-active.ui-state-active a,
.hero-area .info-box .icon,
.trending .item .info .stars ul li i,
.categori-item .item .info .stars ul li i,
.flash-deals .flas-deal-slider .item .stars ul li i,
.flash-deals .flas-deal-slider .item .price .new-price,
.hot-and-new-item .categori .item-list li .single-box .right-area .stars ul li i,
.footer .copy-bg .content .content a,
.footer .footer-widget ul li a:hover,
.info-link-widget .link-list li a:hover,
.info-link-widget .link-list li a:hover i,
.product-details-page .right-area .product-info .info-meta-1 ul li .stars li i,
.product-details-page .right-area .product-info .contact-seller .title,
.product-details-page .right-area .product-info .contact-seller .list li a,
.product-details-page .right-area .product-info .product-price .price,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare a,
.product-details-page #product-details-tab li.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab.ui-tabs-active.ui-state-active a,
.product-details-page #product-details-tab ul li a i,
.product-details-page #product-details-tab ul li a:hover,
.product-details-page #coment-area .all-comments li .single-comment .right-area .header-area .posttime,
.sub-categori .left-area .service-center .body-area .list li i,
.sub-categori .left-area .service-center .footer-area .list li a:hover,
.sub-categori .right-area .categori-item-area .item .info .stars ul li i,
.sub-categori .right-area .pagination-area .pagination .page-item .page-link,
.blog-details .blog-content .content .post-meta li a:hover,
.blog-details .blog-content .content blockquote,
.blog-details .blog-aside .categori .categori-list li a:hover,
.blog-details .blog-aside .categori .categori-list li a.active,
.blog-details .blog-aside .recent-post-widget .post-list li .post .post-details .post-title:hover,
.blog-details .blog-aside .archives .archives-list li a:hover,
.contact-us .contact-section-title .title,
.login-signup .login-area .header-area .title,
.login-signup .login-area .form-input i,
.login-signup .login-area .social-area .title,
.vendor-top-header .content .single-box .icon,
.compare-page-content-wrap .pro-ratting i,
.user-dashbord .user-profile-info-area .links li.active a,
.user-dashbord .user-profile-info-area .links li:hover a,
.thankyou .content .icon,
.single-wish .right .stars li i,
.single-wish .right .store-name i
{
color: <?php echo $theme_color_1; ?>;
}

.input-field.error,
.trending .item .item-img .extra-list ul li a,
.categori-item .item .item-img .extra-list ul li a,
.product-details-page li.slick-slide,
.product-details-page .right-area .product-info .product-size .siz-list li.active .box,
.sub-categori .right-area .categori-item-area .item .item-img .extra-list ul li a
{
border: 1px solid <?php echo $theme_color_1; ?>;;
}

.input-field.error:focus,
.trending .item .item-img .extra-list ul li a:hover,
.categori-item .item .item-img .extra-list ul li a:hover,
.footer .fotter-social-links ul li a:hover,
.product-details-page .right-area .product-info .contact-seller .list li a:hover,
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare a:hover,
.product-details-page #product-details-tab li.ui-tabs-tab.ui-corner-top.ui-state-default.ui-tab.ui-tabs-active.ui-state-active a,
.sub-categori .right-area .categori-item-area .item .item-img .extra-list ul li a:hover,
.cartpage .left-area .table tbody tr td.quantity .qty ul li .qtminus:hover,
.cartpage .left-area .table tbody tr td.quantity .qty ul li .qtplus:hover,
.cartpage .right-area .order-box .cupon-box #coupon-form button:hover,
#freight-form button:hover
.blog-details .comments .comment-box-area li .replay-form .replay-form-close:hover,
.blog-details .blog-aside .tags .tags-list li a:hover
{
border-color: <?php echo $theme_color_1; ?>;
}
.loader-1 .loader-outter,
.loader-1 .loader-inner
{
border: 4px solid <?php echo $theme_color_1; ?>;
}

.trending .item .item-img .sale::before,
.trending .item .item-img .discount::before,
.categori-item .item .item-img .sale::before,
.categori-item .item .item-img .discount::before,
.sub-categori .right-area .categori-item-area .item .item-img .sale::before,
.sub-categori .right-area .categori-item-area .item .item-img .discount::before
{
border-bottom: 22px solid <?php echo $theme_color_1; ?>;
}
.flash-deals .flas-deal-slider .item .item-img .discount::before {
border-bottom: 30px solid <?php echo $theme_color_1; ?>;
}
.sub-categori .modal .contact-form ul li .input-field:focus,
.contact-us .left-area .contact-form ul li .input-field:focus,
.contact-us .left-area .contact-form .captcha-area li .input-field:focus,
.user-dashbord .user-profile-details .account-info .edit-info-area .edit-info-area-form .input-field:focus
{
border-bottom: 1px solid <?php echo $theme_color_1; ?> !important;
}

.blog-details .blog-content .content blockquote {
border-left: 5px solid <?php echo $theme_color_1; ?>;
}
.blog-details .comments .comment-box-area li .comment-box .left .img {
border: 2px solid <?php echo $theme_color_1; ?>;
}
.contact-us .right-area .contact-info {
border-bottom: 2px solid <?php echo $theme_color_1; ?>;
}
.page-center ul.pagination li {
background: <?php echo $theme_color_1; ?>1a;
}

.page-center ul.pagination li.active {
background: <?php echo $theme_color_1; ?>;
}

.blogpagearea .blog-box .name_team span::after {
border-bottom: 3px solid <?php echo $theme_color_1; ?>;
}

.logo-header .helpful-links ul li.compare .compare-product .icon span {
color: #333;
border: 1px solid <?php echo $theme_color_1; ?>;
background: <?php echo $text_color_1; ?>;
}
.hero-area .info-box .icon {
background: <?php echo $theme_color_1; ?>;
}

.video-play-btn {
background-color: <?php echo $theme_color_1; ?>;

}

.product-details-page .right-area .product-info .contact-seller .title {
color: <?php echo $theme_color_1; ?>;}

.product-details-page .right-area .product-info .contact-seller .list li a {
color: <?php echo $theme_color_1; ?>; }
.product-details-page .right-area .product-info .contact-seller .list li a:hover {
background: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>; }
.product-details-page .right-area .product-info .product-price .price {
color: <?php echo $theme_color_1; ?>;}
.product-details-page .right-area .product-info .product-size .siz-list li.active .box {
border: 1px solid <?php echo $theme_color_1; ?>; }

.product-details-page .right-area .product-info .product-color .color-list li .box.color5 {
background:<?php echo $theme_color_1; ?>; }

.product-details-page #product-details-tab.ui-tabs .ui-tabs-panel .heading-area .reating-area .stars {
background: <?php echo $theme_color_1; ?>;}
.login-btn {
background-color: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>;
}
.product-details-page #product-details-tab .top-menu-area ul li a::after {
background: <?php echo $theme_color_1; ?>;}

.flash-deals .flas-deal-slider .item .price .new-price {
color: <?php echo $theme_color_1; ?>;}
.footer .footer-widget ul li a:hover {
color: <?php echo $theme_color_1; ?>; }
.info-link-widget .link-list li a:hover i {
color: <?php echo $theme_color_1; ?>; }

.login-area .header-area .title {
color: <?php echo $theme_color_1; ?>; }
.login-area .form-input i {
color: <?php echo $theme_color_1; ?>; }
.login-area .social-area .title {
color: <?php echo $theme_color_1; ?>;
}

.blog-details .blog-aside .categori .categori-list li a:hover, .blog-details .blog-aside .categori .categori-list li a.active {
color: <?php echo $theme_color_1; ?>; }
.blog-details .blog-aside .recent-post-widget .post-list li .post .post-details .post-title:hover {
color: <?php echo $theme_color_1; ?>; }
.blog-details .blog-aside .archives .archives-list li a:hover {
color: <?php echo $theme_color_1; ?>; }

.taglist a.active {
background: <?php echo $theme_color_1; ?>;
}
.login-area .submit-btn {
background:<?php echo $theme_color_1; ?>; }

.comment-log-reg-tabmenu .nav-tabs .nav-link {
background: #000000; }
.comment-log-reg-tabmenu .nav-tabs .nav-link.active {
background: <?php echo $theme_color_1; ?>;
}

.user-dashbord .user-profile-info-area .links li.active a, .user-dashbord .user-profile-info-area .links li:hover a {
color: <?php echo $theme_color_1; ?>;
}
.user-dashbord .user-profile-details .order-details .view-order-page .print-order a {
background: <?php echo $theme_color_1; ?>; }

.upload-img .file-upload-area .upload-file span {
background: <?php echo $theme_color_1; ?>; }
.thankyou .content .icon {
color: <?php echo $theme_color_1; ?>; }

#product-details-tab #replay-area .write-comment-area .submit-btn {
background: <?php echo $theme_color_1; ?>;
}
#product-details-tab #comment-area .write-comment-area .submit-btn {
background: <?php echo $theme_color_1; ?>;
}
#style-switcher h2 a {
background: <?php echo $theme_color_1; ?>;
}

.elegant-pricing-tables h3 .price-sticker,
.elegant-pricing-tables:hover,
.elegant-pricing-tables.active,
.elegant-pricing-tables:hover .price,
.elegant-pricing-tables.active .price,
.elegant-pricing-tables.style-2 .price,
.elegant-pricing-tables .btn {
background: <?php echo $theme_color_1; ?>;
}
.logo-header .helpful-links ul li.my-dropdown.profilearea .profile .img img{
border: 2px solid <?php echo $theme_color_1; ?>;
}
a.sell-btn {
background: <?php echo $theme_color_1; ?>;
}
.top-header .content .right-content .list ul li a.sell-btn:hover {
transition: 0.3s;
background: #fff;
color: <?php echo $theme_color_1; ?>;
}
.sub-categori .left-area .service-center .body-area .list li i {
color: <?php echo $theme_color_1; ?>;
}
.sub-categori .left-area .service-center .footer-area .list li a:hover {
color: <?php echo $theme_color_1; ?>; }
.breadcrumb-area .pages li a:hover {
color: <?php echo $theme_color_1; ?>; }
.cartpage .left-area .table tbody tr td.quantity .qty ul li .qtminus1:hover, .cartpage .left-area .table tbody tr td.quantity .qty ul li .qtplus1:hover {
background: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>; }
.cupon-box #coupon-form button:hover,
#freight-form button:hover {
background: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>; }
.cupon-box #check-coupon-form button:hover {
background: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>; }
.categories_menu_inner > ul > li > ul.categories_mega_menu > li > a:hover {
color: <?php echo $theme_color_1; ?>; }
.contact-us .left-area .contact-form .form-input i {
color: <?php echo $theme_color_1; ?>;
}
.message-modal .modal .modal-dialog .modal-header {
background: <?php echo $theme_color_1; ?>; }

.message-modal .modal .contact-form .submit-btn {
background: <?php echo $theme_color_1; ?>; }

.logo-header .search-box .categori-container .categoris option:focus{
background: <?php echo $theme_color_1; ?>;
}
.product-details-page .right-area .product-info .mproduct-size .siz-list li.active .box {
border: 1px solid <?php echo $theme_color_1; ?>; }

.flash-deals .owl-carousel .owl-controls .owl-nav .owl-prev,
.flash-deals .owl-carousel .owl-controls .owl-nav .owl-next {
color: <?php echo $theme_color_1; ?>;
}

.trending .owl-carousel .owl-controls .owl-nav .owl-prev,
.trending .owl-carousel .owl-controls .owl-nav .owl-next {

color: <?php echo $theme_color_1; ?>;
}

.section-top .section-title::after{
background: <?php echo $theme_color_1; ?>;
}
.item .add-to-cart-btn{
background: <?php echo $theme_color_1; ?>;
border: 1px solid <?php echo $text_color_1; ?>;
color: <?php echo $text_color_1; ?>;
}
.item .add-to-cart-btn:hover {
color: <?php echo $theme_color_1; ?>;
background: <?php echo $text_color_1; ?>;
border: 1px solid <?php echo $theme_color_1; ?>;
}

.flash-deals .flas-deal-slider .item .price .new-price {
color: <?php echo $theme_color_1; ?>;}

.flash-deals .flas-deal-slider .item .deal-counter span {
color: <?php echo $theme_color_1; ?>;
}
.hot-and-new-item .owl-carousel .owl-controls .owl-nav .owl-prev,
.hot-and-new-item .owl-carousel .owl-controls .owl-nav .owl-next {
border: 1px solid <?php echo $theme_color_1; ?>;
color: <?php echo $theme_color_1; ?>;
}
.hot-and-new-item .owl-carousel .owl-controls .owl-nav .owl-prev:hover,
.hot-and-new-item .owl-carousel .owl-controls .owl-nav .owl-next:hover {
background: <?php echo $theme_color_1; ?>!important;

}

.product-details-page .right-area .product-info .info-meta-3 .meta-list li.addtocart a {
color: <?php echo $text_color_1; ?>;
border: 1px solid <?php echo $theme_color_1; ?>;
background: <?php echo $theme_color_1; ?>; }
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.addtocart a:hover {
color: <?php echo $theme_color_1; ?>; }
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.addtocart a {
border: 1px solid <?php echo $theme_color_1; ?>;
background: <?php echo $theme_color_1; ?>;
}
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.addtocart a:hover {
color: <?php echo $theme_color_1; ?>; }

.product-details-page .right-area .product-info .info-meta-3 .meta-list li.favorite a, .product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare a {
border: 1px solid <?php echo $theme_color_1; ?>;
background: <?php echo $theme_color_1; ?>;}
.product-details-page .right-area .product-info .info-meta-3 .meta-list li.favorite a:hover, .product-details-page .right-area .product-info .info-meta-3 .meta-list li.compare a:hover {
background: #fff;
color: <?php echo $theme_color_1; ?>; }

.seller-info .content .title {
color: <?php echo $theme_color_1; ?>;
}

.seller-info .content .total-product p {
color: <?php echo $theme_color_1; ?>;
}

.seller-info .view-stor {
background: <?php echo $theme_color_1; ?>;

border: 1px solid <?php echo $theme_color_1; ?>;

}
.seller-info .view-stor:hover{
background: #fff;
color: <?php echo $theme_color_1; ?>;
}
.product-details-page .categori .owl-carousel .owl-controls .owl-nav .owl-prev,
.product-details-page .categori .owl-carousel .owl-controls .owl-nav .owl-next {
color: <?php echo $theme_color_1; ?>;
border: 1px solid <?php echo $theme_color_1; ?>;
}
.product-details-page .xzoom-container .owl-carousel .owl-controls .owl-nav .owl-prev,
.product-details-page .xzoom-container .owl-carousel .owl-controls .owl-nav .owl-next {

background: <?php echo $theme_color_1; ?>;
border: 1px solid <?php echo $theme_color_1; ?>;

}

.subscribePreloader__text {
background: <?php echo $theme_color_1; ?>c7;
}

.logo-header .helpful-links ul li.my-dropdown .my-dropdown-menu .dropdownmenu-wrapper .dropdown-cart-action .mybtn1 {
color: <?php echo $text_color_1; ?>;
border: 1px solid <?php echo $text_color_1; ?>;
background: <?php echo $theme_color_1; ?>;
}

.logo-header .helpful-links ul li.my-dropdown .my-dropdown-menu .dropdownmenu-wrapper .dropdown-cart-action .mybtn1:hover {
color: <?php echo $theme_color_1; ?>;
border: 1px solid <?php echo $theme_color_1; ?>;
background: <?php echo $text_color_1; ?>;
}

.mybtn1:hover {
color: <?php echo $theme_color_1; ?> !important;
background: <?php echo $text_color_1; ?>;
border: 1px solid <?php echo $theme_color_1; ?>;
}
.mybtn2:hover {
background: <?php echo $theme_color_1; ?>;
border: 1px solid <?php echo $theme_color_1; ?>;
}
.top-header .content .right-content .list li .track-btn:hover{
background: <?php echo $theme_color_1; ?>;
}
input[type=checkbox]:checked + label:before {
background-color: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>;
}
.radio-design .checkmark::after{
background: <?php echo $theme_color_1; ?>;
}
.order-box .order-btn {
background: <?php echo $theme_color_1; ?>;
}
.page-link {
color: <?php echo $theme_color_1; ?>;
}
.page-item.active .page-link {
background-color: <?php echo $theme_color_1; ?>;
border-color: <?php echo $theme_color_1; ?>;
}
.mybtn1 {
border: 1px solid <?php echo $text_color_1; ?>;
background: <?php echo $theme_color_1; ?>;
color: <?php echo $text_color_1; ?>;
}
.mybtn2 {
color: <?php echo $theme_color_1; ?> !important;
border: 1px solid <?php echo $theme_color_1; ?>;
}

.checkout-area .checkout-process li a:hover{
background: <?php echo $theme_color_1; ?>;

}
.checkout-area .checkout-process li a:hover::before{
border-left: 20px solid <?php echo $theme_color_1; ?>;
}
.checkout-area .checkout-process li a.active{
background: <?php echo $theme_color_1; ?>;

}
.checkout-area .checkout-process li a.active::before{
border-left: 20px solid <?php echo $theme_color_1; ?>;
}
.checkout-area .content-box .content .billing-info-area .info-list li p i{
color: <?php echo $theme_color_1; ?>;
}
.checkout-area .content-box .content .payment-information .nav a span::after{
background: <?php echo $theme_color_1; ?>;
}
.hero-area .hero-area-slider .intro-carousel .intro-content .slider-content .layer-3 a {
border: 1px solid <?php echo $theme_color_1; ?>;
}
.hero-area .hero-area-slider .intro-carousel .intro-content .slider-content .layer-3 a:hover {
border: 1px solid <?php echo $theme_color_1; ?>;
color: <?php echo $theme_color_1; ?>;
}

.order-tracking-content .track-form .mybtn1{
border: 1px solid <?php echo $theme_color_1; ?>;

}
.order-tracking-content .tracking-form .mybtn1{
border: 1px solid <?php echo $theme_color_1; ?>;
}
.tracking-steps li.done:after,
.tracking-steps li.active:after,
.tracking-steps li.active .icon{
background: <?php echo $theme_color_1; ?>;
}
.modal-header .close:hover {
background-color: <?php echo $theme_color_1; ?>;
}
.logo-header .helpful-links ul li.wishlist .wish span {
color: #333;
border: 1px solid  <?php echo $theme_color_1; ?>;
background: <?php echo $text_color_1; ?>;
}

.categories_title {
    background: <?php echo $text_color_1; ?>;
}

.mainmenu-area .categories_menu .categories_title h2, .mainmenu-area .categories_menu .categories_title h2 i.arrow-down {
    color: <?php echo $theme_color_1; ?>;
}

nav .menu li:last-child a{
color: <?php echo $theme_color_1; ?>;
}

.blog-area .blog-box .details .read-more-btn {
    color: <?php echo $text_color_1; ?>;
    background: <?php echo $theme_color_1; ?>;
    border:1px solid <?php echo $text_color_1; ?>;
}

.blog-area .blog-box .details .read-more-btn:hover {
    color: <?php echo $theme_color_1; ?>;
    background: <?php echo $text_color_1; ?>;
    border:1px solid <?php echo $text_color_1; ?>;
}

.info-area {
background: <?php echo $white; ?>;
}
<!--Alterado do dinâmico ($theme_color_1) com uma variável inexistente pois foi a melhor forma que encontrei de estilizar sem quebrar o resto do layout-->

.blogpagearea .blog-box .details .read-more-btn {
border: 1px solid <?php echo $theme_color_1; ?>;

}
.blogpagearea .blog-box .details .read-more-btn:hover {
color: <?php echo $theme_color_1; ?>;
}
.contact-us .left-area .contact-form .submit-btn {
border: 1px solid <?php echo $theme_color_1; ?>;
}
.contact-us .left-area .contact-form .submit-btn:hover {
color: <?php echo $theme_color_1; ?>;
}
.process-steps li.done:after,
.process-steps li.active:after,
.process-steps li.active .icon{
background: <?php echo $theme_color_1; ?>;
}
.sub-categori .left-area .filter-result-area .body-area .filter-btn {
border: 1px solid <?php echo $theme_color_1; ?>;
}
.sub-categori .left-area .filter-result-area .body-area .filter-btn:hover {
color: <?php echo $theme_color_1; ?>;
}
.category-page .bg-white .sub-category-menu .category-name a{
color: <?php echo $theme_color_1; ?>;

}
.category-page .bg-white .sub-category-menu ul li a:hover{
color: <?php echo $theme_color_1; ?>;
}
.category-page .bg-white .sub-category-menu ul li ul li a:hover i,
.category-page .bg-white .sub-category-menu ul li ul li a:hover
{
color: <?php echo $theme_color_1; ?>;
}
#product-details-tab #comment-area .write-comment-area .submit-btn{
border: 1px solid <?php echo $theme_color_1; ?>;
}
#product-details-tab #replay-area .write-comment-area .submit-btn {
border: 1px solid <?php echo $theme_color_1; ?>;
}
#product-details-tab #replay-area .write-comment-area .submit-btn:hover {
color: <?php echo $theme_color_1; ?>;
}
#product-details-tab #comment-area .write-comment-area .submit-btn:hover {
color: <?php echo $theme_color_1; ?>;
}

@media (max-width: 991px) {
nav .nav-header .toggle-bar {
color: <?php echo $theme_color_1; ?>;
}
}

.product-details-page .xzoom-container .owl-carousel .owl-controls .owl-nav .owl-prev:hover,
.product-details-page .xzoom-container .owl-carousel .owl-controls .owl-nav .owl-next:hover {
color: <?php echo $theme_color_1; ?>;
}

.product-details-page .categori .owl-carousel .owl-controls .owl-nav .owl-prev:hover,
.product-details-page .categori .owl-carousel .owl-controls .owl-nav .owl-next:hover {
background: <?php echo $theme_color_1; ?> !important;

}
.slider-buttom-category .single-category::before,.slider-buttom-category .single-category {
background: white;
}

.slider-buttom-category .single-category .left .title, .slider-buttom-category .single-category .left .count {
    color: <?php echo $text_color_1; ?>;
}

.custom-control-input:checked~.custom-control-label::before {
border-color: <?php echo $theme_color_1; ?>;
background-color: <?php echo $theme_color_1; ?>;
}
#product-details-tab #comment-area .all-comment li .single-comment .left-area img {

border: 2px solid <?php echo $theme_color_1; ?>;
}

#product-details-tab #replay-area .all-replay li .single-review .left-area img {

border: 2px solid <?php echo $theme_color_1; ?>;
}

.wholesell-details-page{
background: <?php echo $theme_color_1; ?>0f;
}
.sub-categori .left-area .filter-result-area .body-area .filter-list li a:hover{
color: <?php echo $theme_color_1; ?>;
}
.contact-us .right-area .contact-info .content a:hover {
color: <?php echo $theme_color_1; ?>;
}
#product-details-tab #comment-area .all-comment li .replay-area button {
background: <?php echo $theme_color_1; ?>;

}

.customize-title{
font-size: 14px;
font-weight: 600;
color: <?php echo $theme_color_1; ?>;
}

.textureOverlay{
width: 82px;
height: 82px;
margin-top: -81px;
margin-left: 4px;
border-radius: 50px;
opacity: 0;
transition: .5s ease;
background-color: <?php echo $theme_color_1; ?>;
}
.textureOverlayModal{
width: 100px;
height: 100px;
margin-top: -99px;
margin-left: 8px;
border-radius: 50px;
opacity: 0;
transition: .5s ease;
background-color: <?php echo $theme_color_1; ?>;
}

.textureCurrentOverlay{
width: 102px;
height: 100px;
margin-left: -165px;
margin-top: -6px;
opacity: 0.5;
border-radius: 50px;
background-color: <?php echo $theme_color_1; ?>;
border: 3px solid #fff;
}

.allOptionsAnchor p{
color: <?php echo $theme_color_1; ?>;
font-size: 14px;
}

.uploadLogoBtn{
border-radius: 30px;
background-color: <?php echo $theme_color_1; ?>;
border: 1px solid <?php echo $theme_color_1; ?>;
}
.uploadLogoBtn:hover{
border-radius: 30px;
color: <?php echo $theme_color_1; ?>;
transition: 0.4s ease;
background-color: #fff;
border: 1px solid <?php echo $theme_color_1; ?>;
}
.uploadLogoBtn:hover p{
color: <?php echo $theme_color_1; ?>;
}
.uploadLogoBtn p {
color: #fff;
font-size: 14px;
margin-bottom: -1px;
font-weight: 600;
}

.logo-header .search-box {
    border: none;
    border-bottom: 1px solid <?php echo $text_color_2; ?>;
}

.logo-header .search-box .search-form input::-webkit-placeholder {
    color: <?php echo $text_color_2; ?>!important;
    opacity: .8;
}
.logo-header .search-box .search-form input::-moz-placeholder {
    color: <?php echo $text_color_2; ?>!important;
    opacity: .8;
}
.logo-header .search-box .search-form input::-ms-placeholder {
    color: <?php echo $text_color_2; ?>!important;
    opacity: .8;
}
.logo-header .search-box .search-form input::-o-placeholder {
    color: <?php echo $text_color_2; ?>!important;
    opacity: .8;
}
.logo-header .search-box .search-form input::placeholder {
    color: <?php echo $text_color_2; ?>!important;
    opacity: .8;
}

.logo-header .search-box .search-form button {
    color: <?php echo $text_color_2; ?>;
}

.mainmenu-area {
    background-color: <?php echo $text_color_1; ?>;
    border-bottom:3px solid <?php echo $theme_color_1; ?> !important;
}

.hero-area .hero-area-slider .owl-controls .owl-dots .owl-dot {
    background: <?php echo $text_color_2; ?>;
    border-radius: 3px;
}

.hero-area .hero-area-slider .owl-controls .owl-dots .owl-dot.active{
    background: <?php echo $theme_color_1; ?>;
}

.info-area .info-box .icon .title {
    color: <?php echo $text_color_1; ?>;
}

.section-title #post-title::after,.section-title #post-title::before  {
    border: 1px solid <?php echo $theme_color_1; ?>;
}

.item .info .price {
    color: <?php echo $theme_color_1; ?>;
}

.flash-deals .flas-deal-slider .deal-counter {
    border: 1px solid <?php echo $theme_color_1; ?>;
}

.flash-deals .flas-deal-slider .deal-counter span {
    color: <?php echo $theme_color_1; ?>;
}

.flash-deals .flas-deal-slider .deal-counter span small {
    color: <?php echo $theme_color_1; ?>;
}

.hot-and-new-item .categori .item-list li .single-box .right-area .price {
    color: <?php echo $theme_color_1; ?>;
}

.blog-area .owl-carousel .owl-controls .owl-nav .owl-prev, .blog-area .owl-carousel .owl-controls .owl-nav .owl-next {
    color: <?php echo $theme_color_1; ?>;
}

.blog-area .aside .coments {
    border-left: 1px solid <?php echo $theme_color_1; ?>;
}

.logo-header .helpful-links ul li.my-dropdown .cart .icon span {
    color: #333;
    border: 1px solid <?php echo $theme_color_1; ?>;
    background: <?php echo $text_color_1; ?> ;
}

.toggle-password{
float: right;
margin-right: 20px;
position: relative;
margin-top: -35px;
color: <?php echo $theme_color_1; ?>;
}


.item .item-img .sell-area .sale {
    color: <?php echo $text_color_1; ?>;
    background: <?php echo $theme_color_1; ?>;
}

.blog-area .blog-box .blog-images .img .date p {
    color: <?php echo $text_color_1; ?>;
}

.blog-area .blog-box .blog-images .img .date {
    border: 1px solid <?php echo $text_color_1; ?>;
}

.info-area .info-big-box {
    background-color: <?php echo $theme_color_1; ?>;
}

.section-top .section-title {
    color:<?php echo $text_color_2; ?>;
    background-color: <?php echo $theme_color_2; ?>;
}

.section-top .section-title.title-oferta {
    color:<?php echo $text_color_1; ?>;
    background-color: <?php echo $theme_color_1; ?>;
}

.item .item-img .extra-list ul li span svg {
    fill: <?php echo $theme_color_1; ?>;
    transition: .1s all;
}

.item .item-img .extra-list ul li:hover span svg {
    transform: scale(1.1);
    transition: .1s all;
}

.blog-area .aside .slider-wrapper .slide-item .top-area .right .content .name,
.blog-area .aside .slider-wrapper .slide-item .top-area .right .content .dagenation,
.blog-area .aside .slider-wrapper .slide-item .review-text::before,
.blog-area .aside .slider-wrapper .slide-item .review-text::after {
    color: <?php echo $theme_color_1; ?>;
}

.footer {
    background-color: <?php echo $theme_color_2; ?>;
}

.footer .footer-info-area .text p, .footer .footer-widget ul li a,
.recent-post-widget .post-list li .post .post-details .post-title,
.recent-post-widget .post-list li .post .post-details .date,
.footer .footer-widget .title,
.footer .footer-widget ul li
{
color:  <?php echo $text_color_2; ?>;
}


.footer .title {
    color: <?php echo $text_color_2; ?>;
}

.footer .fotter-social-links ul li a {
    color: <?php echo $text_color_2; ?>;
    border-color: <?php echo $text_color_2; ?>;
}

#services-carousel .owl-controls .owl-nav .owl-prev, #services-carousel .owl-controls .owl-nav .owl-next {
    color: <?php echo $text_color_1; ?>;
}

.icon-filter {
    background-color: <?php echo $theme_color_1; ?>;
    fill: <?php echo $text_color_1; ?>;
}

.sub-categori .left-area .tags-area .body-area .sub-title {
    color: <?php echo $theme_color_1; ?>;
}
.cookie-alert .bg-custom{
    background-color: <?php echo $theme_color_1; ?>;
}

.cookie-alert h4{
    color: <?php echo $text_color_1; ?>;
}
.cookie-alert p{
    font-size: 14px;
    color: <?php echo $text_color_1; ?>;
}

.cookie-alert .button-fixed{
    bottom: 0;
    position: fixed;
    right: 0;
    border: 1px solid <?php echo $theme_color_1; ?>;
    background-color: <?php echo $theme_color_1; ?>;
}
.cookie-alert .fas{
    cursor: pointer;
    font-size: 24px;
    color: <?php echo $text_color_1; ?>;
}

.cookie-alert .btn{
    background-color: <?php echo $text_color_1; ?>;
    color: <?php echo $theme_color_1; ?>;
    border: none;
}

.cookie-alert .btn:focus{
    background-color: <?php echo $theme_color_1; ?>;
    color: <?php echo $text_color_1; ?>;
    border: 1px solid <?php echo $text_color_1; ?>;
}

.cookie-alert a{
    color: <?php echo $text_color_1; ?>;
    font-weight: 600;
}
.box-center-text.infos-internas {
    background-color: <?php echo $theme_color_2; ?>;
}

.box-center-text.infos-internas .title, .box-center-text.infos-internas .count {
    color:  <?php echo $text_color_2; ?>;
}

.badge-danger.descont-card {
    background-color: <?php echo $theme_color_1; ?>;
}

.badge-primary{
    background-color: <?php echo $theme_color_1; ?>;
    color:  <?php echo $text_color_1; ?>;
}
