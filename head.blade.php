<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- Additional CSS Files -->
<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">


<title> Wrtiers Talk - @yield('title')</title>
<!-- add icon link -->
<link rel = "icon" href ="https://media.geeksforgeeks.org/wp-content/cdn-uploads/gfg_200X200.png" type = "image/x-icon">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
{{-- <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css"> --}}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js">
</script>

<!-- Stylesheets -->
<link rel="stylesheet" href="{{asset('assets/css/docs.theme.min.css')}}">
<!-- Owl Stylesheets -->
<link rel="stylesheet" href="{{asset('assets/owlcarousel/assets/owl.carousel.min.css')}}">
<!-- <link rel="stylesheet" href="assets/owlcarousel/assets/owl.carousel.min.css"> -->
<link rel="stylesheet" href="{{asset('assets/owlcarousel/assets/owl.theme.default.min.css')}}">
<!-- javascript -->
<script src="{{asset('assets/vendors/jquery.min.js')}}"></script>
<script src="{{asset('assets/vendors/custom.js')}}"></script>
<script src="{{asset('assets/owlcarousel/owl.carousel.js')}}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
<style type="text/css">
/*
    .owl-carousel .owl-item .story-profile {
        position: absolute;
        bottom: 28px;
        height: 59px;
        width: 67%;
        text-align: center;
        border-radius: 100%;
        object-fit: cover;
        left: 19%;
        right: 50%;
    }
    .owl-carousel .owl-item .story-profile img {
        width: 52px;
        border-radius: 100%;
        object-fit: cover;
        margin-left: 6px;
    }
    .single-story{
        position: relative;
    }
    img.single-story {
        height: 194px;
        object-fit: cover;
        border-radius: 10px;
    }
    .owl-carousel .owl-item .story-profile p {
        font-size: 15px;
        line-height: 1;
        padding-top: 4px;
        color: white;
        font-weight: 700;
    }*/

</style>
@stack('style')
