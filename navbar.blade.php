<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-white shadow-xs">
{{--<<<<<<< HEAD--}}
{{--    <div class="container mid-content">--}}
{{--        <a class="navbar-brand" href="#">--}}
{{--            <i class="fas fa-bolt logo-icon me-2"></i>--}}
{{--            <span class="d-inline-block logo-text mb-0">Writers Talk </span></a>--}}
{{--        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">--}}
{{--            <span class="navbar-toggler-icon"></span>--}}
{{--        </button>--}}
{{--        <div>--}}
{{--         <form class="form-inline my-2 my-lg-0">--}}
{{--            <i class="fas fa-search search-icon"></i>--}}
{{--            <input type="text" placeholder="Search" class="search-box pt-2 pb-2">--}}
{{--        </form>--}}
{{--</div>--}}
{{--        <div class="collapse navbar-collapse" id="navbarSupportedContent">--}}
{{--            <ul class="navbar-nav mr-auto">--}}
{{--            <li class="nav-item">--}}
{{--                    <a class="nav-link" href="{{route('home')}}"><i class="fas fa-star font-lg"></i></a>--}}
{{--                </li>--}}
{{--                <li class="nav-item" id="comment-click">--}}
{{--                <a class="nav-link" href="{{route('getUserChats')}}"><i class="fas fa-comment-alt font-lg"></i></a>--}}
{{--               </li>--}}
{{--                <li class="nav-item active">--}}
{{--                    <a class="nav-link" href="{{route('home')}}"><i class="fas fa-home font-lg"></i></a>--}}
{{--                </li>--}}
{{--                <li class="nav-item ">--}}
{{--                    <a class="nav-link" href="{{route('home')}}"><i class="fas fa-heart font-lg"></i></a>--}}
{{--                </li>--}}
{{--                <li class="nav-item ">--}}
{{--                    <a class="nav-link" href="{{route('home')}}"><i class="far fa-user font-lg"></i></a>--}}
{{--                </li>--}}
{{--    --}}{{--            <li class="nav-item">--}}
{{--    --}}{{--                <a class="nav-link" href="#"><i class="fas fa-bolt font-lg"></i></a>--}}
{{--    --}}{{--            </li>--}}
{{--    --}}{{--            <li class="nav-item">--}}
{{--    --}}{{--                <a class="nav-link" href="#"><i class="fas fa-video font-lg"></i></a>--}}
{{--    --}}{{--            </li>--}}


{{--    --}}{{--            <li class="nav-item">--}}
{{--    --}}{{--                <a class="nav-link" href="#"><i class="fas fa-cart-plus font-lg"></i></a>--}}
{{--    --}}{{--            </li>--}}
{{--            </ul>--}}
{{--            <ul class="navbar-nav ml-auto">--}}
{{--    --}}{{--            <li class="nav-item ">--}}
{{--    --}}{{--                <a class="nav-link" href="#"><i class="far fa-bell text-primary font"></i></a>--}}
{{--    --}}{{--            </li>--}}
{{--    --}}{{--            <li class="nav-item" id="comment-click">--}}
{{--    --}}{{--                <a class="nav-link" href="#"><i class="far fa-comment-alt text-primary font"></i></a>--}}
{{--    --}}{{--            </li>--}}
{{--    --}}{{--            <li class="nav-item ">--}}
{{--    --}}{{--                <a class="nav-link" href="{{route('userSetting')}}"><i class="fas fa-cog text-primary font"></i></a>--}}
{{--    --}}{{--            </li>--}}
{{--           <li class="nav-item">--}}
{{--                   <a class="nav-link" href="{{route('userProfile',['id'=>Auth::user()->uuid])}}"><img src="{{asset('storage/'.\Illuminate\Support\Facades\Auth::user()->image)}}"  class="img1 font-lgg" style="object-fit: cover"></a>--}}
{{--               </li>--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--=======--}}
	<div class="container mid-content">
		<a class="navbar-brand" href="#">
			<img style="width: 75px;" src="http://writerstalkadmin.com/public/assets/imgs/logo.png" alt="">

		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item @if(\Illuminate\Support\Facades\Route::currentRouteName() == "getAdminPosts") active @endif"> <a class="nav-link" href="{{url('admin-posts')}}"><i class="fas fa-star font-lg"></i></a> </li>
				<li class="nav-item @if(\Illuminate\Support\Facades\Route::currentRouteName() == "getUserChats") active @endif" id="comment-click"> <a class="nav-link" href="{{url('get-user-chats')}}"><i class="fas fa-comment-alt font-lg"></i></a> </li>
				<li class="nav-item @if(\Illuminate\Support\Facades\Route::currentRouteName() == "home") active @endif"> <a class="nav-link" href="{{url('home')}}"><i class="fas fa-home font-lg"></i></a> </li>
				<li class="nav-item @if(\Illuminate\Support\Facades\Route::currentRouteName() == "stories") active @endif "> <a class="nav-link" href="{{url('stories')}}"><i class="fas fa-book font-lg"></i></a> </li>
				<li class="nav-item @if(\Illuminate\Support\Facades\Route::currentRouteName() == "friendRequests") active @endif"> <a class="nav-link" href="{{route('userProfile',['id'=>Auth::user()->uuid])}}" ><i class="far fa-user font-lg"></i></a> </li>
            </ul>
                <!-- <ul class="navbar-nav ml-auto">
				<li class="nav-item"> <a class="nav-link" href="{{route('userProfile',['id'=>Auth::user()->uuid])}}"><img src="{{asset('storage/'.\Illuminate\Support\Facades\Auth::user()->image)}}"  class="img1 font-lgg" style="object-fit: cover"></a> </li>
			</ul> -->
		</div>
	</div>

</nav>
