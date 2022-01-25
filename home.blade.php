
@extends('layouts.main')
@section('title')
    Home
@endsection
@push('style')
    <style>
        .show-read-more .more-text{
            display: none;
        }

    </style>
@endpush
@section('content')

    <div class="w-2 d-none">
    </div>
    <div class="w-40 mx-auto pt-5">

        <!-- body -->

        <div class="home-demo pt-2 pb-1 mt-1">
            <div class="row mb-2">
                <div class="large-12 columns">
                    <div class="d-flex float right">
                        <div class="searchbar-posts mr-2">
                            {{-- <form class="form-inline my-2 my-lg-0"> <i class="fas fa-search search-icon"></i> --}}
                                <input type="text" placeholder="Search" class="searchbar-input pl-3 search-box pt-2 pb-2 " id="homeSearchInput" onkeyup="search_home(event)">
                             {{-- </form> --}}
                            <div class="All-posts mt-1 mt-3 searchbar-tabs">
                                <div class="AvhYw nLL4f"></div>
                                <div class="close-x p-ab">X</div>
                                <div class="tips shadow-xss">
                                    <ul class="nav nav-tabs shadow-xss" id="myTab" role="tablist">
                                        <li class="nav-item width"> <a class="nav-link active fontsize font-weight-bold text-center" id="writer-tab" data-toggle="tab" href="#writer" role="tab" aria-controls="Writers" aria-selected="true">Writers</a> </li>
                                        <li class="nav-item width"> <a class="nav-link fontsize font-weight-bold text-center" id="posts-tab" data-toggle="tab" href="#Posts" role="tab" aria-controls="Posts" aria-selected="false">Posts</a> </li>
                                        <li class="nav-item width"> <a class="nav-link fontsize font-weight-bold text-center" id="tips-tab" data-toggle="tab" href="#My-Tips" role="tab" aria-controls="Tags" aria-selected="false">Tags</a> </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade scrollbar style-1 show active" id="writer" role="tabpanel" aria-labelledby="writer-tab">
                                           @if(sizeof($search_writers) > 0)
                                               @foreach($search_writers as $sw => $sw_row)
                                            <a href="{{url('profile/'.$sw_row->uuid)}}" class="media py-1 one-user align-items-center"> <img class="mr-2 chat-user-id ml-2" src="{{asset('storage/'.$sw_row->image)}}" alt="Generic placeholder image">
                                                <div class="media-body d-flex">
                                                    <div>
                                                        <h5 class="mt-0 main-name">{{ucwords($sw_row->name)}}</h5>
                                                        <div class="time last-seen line-height-1">{{@$sw_row->favorite_genres}}</div>
                                                    </div>
                                                    {{-- <button type="button" class="btn btn-primary ml-auto mr-3 add-friend">Add friend</button> --}}
                                                </div>
                                            </a>
                                                @endforeach
                                           @endif

                                        </div>
                                        <div class="tab-pane fade scrollbar style-1" id="Posts" role="tabpanel" aria-labelledby="posts-tab">
                                            <div class="row no-gutters px-2 m-0" id="render_posts">
                                                @if(sizeof(@$search_posts) > 0)
                                                    @foreach(@$search_posts  as $sp => $sp_row)
                                                <div class="col-md-4 p-1">
                                                    <a href="{{url('post-details/user/'.$sp_row->uuid)}}">
                                                        <img class="posts-pics pt-1 pb-2 shadow-xss rounded-xxl" src="{{asset('storage/'.@$sp_row->file)}}" alt="">
                                                    </a>
                                                </div>
                                                    @endforeach
                                                @endif

                                            </div>
                                        </div>
                                        <div class="tab-pane fade scrollbar style-1" id="My-Tips" role="tabpanel" aria-labelledby="tips-tab">

                                            @if(sizeof(@$search_tags) > 0)
                                                @foreach(@$search_tags as $st => $st_row)
                                            <a href="{{url('admin-posts/'.$st_row->tag_name)}}" class="media py-1 one-user align-items-center">
                                                <h1 class="tag-hash">#</h1>
                                                <div class="media-body d-flex">
                                                    <div>
                                                        <h5 class="mt-0 main-name">{{$st_row->tag_name}}</h5>
{{--                                                        <div class="time last-seen line-height-1"></div>--}}
                                                    </div>
                                                </div>
                                            </a>
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- search box -->
                        {{-- <div class="search-box mr-2">
                            <button type="button" class="btn btn-light search-icon mb-0" data-toggle="modal" data-target="#exampleModal">
                                <i class="fas fa-search"></i>
                            </button>
                        </div> --}}

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                            <div class="modal-dialog  modal-dialog-centered" role="document">
                                <div class="modal-content m-5">

                                    <div class="modal-body w-100">
                                        <div class="">
                                            <button type="button" class="close closing-popup" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="text-center select-btns-search">
                                            <h2 class="font-weight-bold mb-3 p-0 text-dark" style="font-style: normal;">Search Type</h2>

                                            <button class="choose-search mb-0">Search 1</button>
                                            <button class="choose-search mb-0">Search 1</button>
                                            <button class="choose-search mb-0">Search 1</button>
                                        </div>
                                        <div class="select-input-seach">
                                            <h2 class="font-weight-bold mb-2 p-0 text-dark" style="font-style: normal;">HASHTAGS</h2>
                                            <form class="form-inline my-2 w-50">
                                                <i class="fas fa-search search-icon"></i>
                                                <input type="text" placeholder="Start typing to search.." class="w-100 search-box pt-2 pb-2">
                                            </form>

                                            <p class="p-2">#Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag
                                                #tag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag </p>
                                            <p class="p-2">#Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag
                                                #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag
                                                #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag
                                                #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag
                                                #tag #Hashtag #Hashtag #Hashtag #Hashtag #Hashtag </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- create post -->
                        <div class="craete-new-post mr-2">
                            <button type="button" class="btn btn-primary btn-new-post mb-0 p-0 pl-1" data-toggle="modal" data-target="#exampleModal1">
                                <i class="fas fa-edit"></i>
                            </button>


                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">

                                        <div class="modal-body p-0">

                                            <div class="card w-100 py-4 shadow-xss rounded-xxl border-0 ps-4 pt-3 pe-4 pb-3">
                                                <div class="">
                                                    <button type="button" class="close closing-popup" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                <form method="POST" action="{{route('submitUserPost')}}" id="addPostForm" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="file_type" id="FileExtension" value="">

                                                    <div class="card-body p-0 mt-0 position-relative">
                                                        <figure class="avatar position-absolute ml-2 mt-2 pt-1 pb-0 mb-0 top-5"><img src="{{asset('storage/'.Auth::user()->image)}}" alt="image" class="shadow-sm rounded-circle w30"></figure>
                                                        {{-- <figure class="avatar position-absolute ml-2 mt-2 pt-1 top-5"><img src="{{asset('storage/'.\Illuminate\Support\Facades\Auth::user()->image)}}" alt="image" class="shadow-sm rounded-circle w30"></figure> --}}
                                                        <textarea name="description" id="postDescription" class="h100 bor-0 mb-0 mt-1 w-100 rounded-xxl p-2 pl-5 font-xssss text-grey-500 fw-500 border-light-md theme-dark-bg" cols="30" rows="10" placeholder="What's on your mind?"></textarea>
                                                    </div>
                                                    <div class="validate-msg-div" style="display: none">
                                                        <p class="text-danger pl-1 validate-msg" style="font-size: 12px; font-weight: bold">Select/Write some stuff to add post</p>
                                                    </div>
                                                    <div class="upload-vid-img"></div>
                                                    <div class="card-body d-flex p-0 mt-2">
                                                        <div class="pl-2 pt-2 pointer" >
                                                            <a href="#" class="p-rel d-flex align-items-center font-xssss fw-600 ls-1 text-grey-700 text-dark pe-4" >
                                                                <i class="far fa-file-image font-md text-success feather-image me-2"></i>
                                                                <span class="d-none-xs">Photo/Video</span>
                                                            </a>
                                                            <input type="file" name="post_file" class="mb-0 input-vid-img post_file" accept=".jpg,.jpeg.,.gif,.png,.mov,.mp4,.x-m4v" onchange="readFile(this)" />
                                                        </div>
                                                        <div class="ms-auto">
                                                            <input type="button" class="d-flex btn btn-primary btn-sm float-right" id="addPostBtn" value="Add Post"> </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <i class="fas fa-sliders-h pt-1 ml-2" id="toggle-click"></i>
<div class="position-toggle-set bg-white">
	<div class="options shadow-xss" id="writers-section">
		<h5 class="modal-title8" id="exampleModalLabel">Top 100 Writers</h5>
        <button type="button" class="close closing-button" id="closing-option" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
		<div class="row m-0">
			<div class="col-md-4 p-1 pb-2 one-story shadow-xss bg-white">
				<a class="dropdown-item p-0 change" href="#"><img src="{{asset('storage/assets/imgs/1.jpg')}}" class="viwed-images">
					<h5 class="titalname pb-0 mb-0 text-center">Zeeshan Shabir</h5></a>
			</div>
			<div class="col-md-4 p-1 pb-2  one-story shadow-xss bg-white">
				<a class="dropdown-item p-0 change" href="#"><img src="{{asset('storage/assets/imgs/5.jpg')}}" class="viwed-images">
					<h5 class="titalname pb-0 mb-0 text-center ">Zeeshan Shabir</h5></a>
			</div>
			<div class="col-md-4 p-1 pb-2  one-story shadow-xss bg-white">
				<a class="dropdown-item p-0 change" href="#"><img src="{{asset('storage/assets/imgs/9.jpg')}}" class="viwed-images">
					<h5 class="titalname pb-0 mb-0 text-center">Zeeshan Shabir</h5></a>
			</div>
		</div>
	</div>
</div>

<div class="position-toggle-set bg-white">
	<div class="options shadow-xss" id="toggle-section">

		<h5 class="modal-title9 mb-1 text-center my-2">Top 100 Writers</h5>
		<div style="height: auto;max-height: 561px;" class="row m-0 scrollbar style-1">
            @if(sizeof($top_100_writers) > 0)
                @foreach($top_100_writers as $top_writer)
			<div class="col-md-4 p-1 pb-2 one-story shadow-xss bg-white">
				<a class="dropdown-item p-0 change" href="{{url('profile/'.$top_writer->uuid)}}">
                    <img src="{{asset('storage/'.$top_writer->image)}}" class="viwed-images">
					<h5 class="titalname pb-0 mb-0 text-center">{{$top_writer->name}}</h5>
                </a>
			</div>
			@endforeach
                @endif
		</div>
        <div class="mt-1">

            <a href="{{url('stories')}}" style="color: #fff;" class="btn btn-primary w-100">View other Stories</a>
        </div>

    </div>
</div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row bg-light pt-2 pb-1" style="z-index: 0">
                <div class="large-12 columns ">
{{--                    <input type="hidden" name="user_stories" id="userStoriesArr" value="{{json_encode([$user_stories])}}">--}}

                    <div class="owl-carousel owl-carousel-tips"  style="z-index: 0">
                        {{--<<<<<<< HEAD--}}

                        {{--                        <div class="item">--}}
                        {{--                              <div class=" h200 d-block border-0 shadow-none rounded-xxxl bg-dark">--}}
                        {{--                                    <div class="story-profile">--}}
                        {{--                                        <a href="#">--}}
                        {{--                                            <span class="btn-round1 bg-white"><i class="fas fa-plus font-lg"></i></span>--}}
                        {{--                                            <div class="clearfix"></div>--}}
                        {{--                                        </a>--}}
                        {{--                                        <p class="add" style="font-size: 13px; padding-top: 5px;">Add Story</p>--}}
                        {{--                                        <input class="upload-story" name="story_file" id="userAddStory" type="file" onchange="add_story(this)">--}}
                        {{--                                    </div>--}}
                        {{--                              </div>--}}
                        {{--                        </div>--}}

                        {{--=======--}}
                        <div class="item">
                            <div class="py-3 d-block border-grey shadow-none rounded-xxxl p-rel">
                                <div class="text-center">
                                    <a href="#">
                                        <span class="text-dark"><i class="fas fa-plus font-sm" style="padding: 4px;"></i></span>
                                        {{-- <div class="clearfix"></div> --}}
                                    </a>
                                    <input class="upload-story mb-0" name="story_file" id="userAddStory" type="file" onchange="add_story(this)">
                                    {{-- <input class="upload-story" type="file"> --}}
                                </div>
                            </div>
                            <div class="story-profile">
                                <p class="add fw-700 text-center" >New Tips</p>
                            </div>

                        </div>
                                                @if($user_stories)

                        <div class="item">
                            <div class=""  data-toggle="modal" data-target="#userStoryModal">
                                <img style="object-fit: cover" class="single-story" src="{{asset('storage/'.@$user_stories->image)}}">
                            </div>
                            <div class="story-profile">
                                <p class="text-truncate text-center">{{ucfirst(@$user_stories->name)}}</p>
                            </div>
                        </div>
                                                @endif
                                                @if($admin_stories)

                        <div class="item">
                            <div class=""  data-toggle="modal" data-target="#adminStoryModal">
                                <img style="object-fit: cover" class="single-story" src="{{asset('storage/'.@$admin_stories->image)}}">
                            </div>
                            <div class="story-profile">
                                <p class="text-truncate text-center">{{ucfirst(@$admin_stories->name)}}</p>
                            </div>
                        </div>
                                                @endif
                                                @if(sizeof($today_stories) > 0)
                                                    @foreach($today_stories as $key => $today_story)

                        <div class="item">
                            <div class=""  data-toggle="modal" data-target="#todayStoryModal_{{$key}}">
                                <img style="object-fit: cover" class="single-story" src="{{asset('storage/'.@$today_story->image)}}">
                            </div>
                            <div class="story-profile">
                                <p class="text-truncate text-center">{{ucfirst(@$today_story->name)}}</p>
                            </div>
                        </div>
                            @endforeach
                                                    @endif
                    </div>

                </div>
            </div>
        </div>
        @if(sizeof($posts) > 0)
            <input type="hidden" name="user_posts" id="userPostsArr" value="{{json_encode($posts)}}">
            <input type="hidden" name="user_id" id="userId" value="{{\Illuminate\Support\Facades\Auth::user()->uuid}}">
            <div class="row">
                <div class="col-md-12 mt-2 text-center">
                    <a href="{{route('userFindPenpals')}}" class="btn btn-info">Find Penpals</a>
                    <button class="btn btn-info mb-0 "  data-toggle="modal" data-target="#exampleModal1">Post</button>
                </div>
            </div>
            @foreach($posts as $p => $p_row)
                <div class="card w-100 px-3 pt-3 mb-0 mt-2 display_posts border-clr">
                    <div class="media mb-2">
                        <img class="mr-3 tip-profile rounded-circle" @if($p_row->user->image) src="{{asset('storage/'.$p_row->user->image)}}" @else src="{{asset('assets/imgs/2.jpg')}}" @endif alt="img">
                        <div class="media-body line-height-6 pt-1">
                            <h5 class="mt-0 font-weight-bold">{{$p_row->user->name}}</h5>
                            <span class="post-time">{{$p_row->created_at->diffForHumans()}}</span>

                        </div>
                        <a href="javascript:void(0)" class="ms-auto " id="shareStoryPostBtn" post_id="{{@$p_row->uuid}}" post_type="{{@$p_row->post_type}}" file_type="{{@$p_row->file_type}}">
                            <i class="fas fa-share-alt text-grey-900 text-dark btn-round-sm"></i>
                            <span class="d-none-xs">Share as Story</span>
                        </a>
                    </div>

                    @if($p_row->description)
                        <div class="card-body p-0 me-lg-5">
                            <p class="fw-500 text-grey-500 lh-26 font-xssss w-100 show-read-more">{{@$p_row->description}}

                            </p>
                        </div>
                    @endif

                    @if($p_row->file)
                        @if($p_row->file_type == 'image')
                            <div class="card-body p-0 mb-2 mt-0 rounded-3 overflow-hidden">
                                <img src="{{asset('storage/'.$p_row->file)}}" class="float-right w-100" alt="">
                            </div>
                        @endif
                        @if($p_row->file_type == 'video')
                            <div class="card-body p-0 mb-2 mt-0 rounded-3 overflow-hidden">
                                <video autoplay="" loop="" class="float-right w-100" controls muted>
                                    <source src="{{asset('storage/'.$p_row->file)}}" type="video/mp4">
                                </video>
                            </div>
                        @endif

                    @endif

                    <div class="card-body d-flex p-0">
                        <a href="javascript:void(0)" class=" px-1 d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss like_box" id="postLikeCount">
                            @if(@$posts->is_like)
                                <i class="fas fa-heart text-danger text-danger-900 f-21 mr-2 post_like" postIndex="{{$p}}"></i>
                            @else
                                <i class="far fa-heart text-dark text-grey-900 f-21 mr-2 post_like" postIndex="{{$p}}"></i>

                            @endif
                            <span class="d-none-xss">{{@$p_row->likes_count}} </span>
                        </a>

                        <a href="{{url('post-details/user/'.$p_row->uuid)}}" class="d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss">
                            <i class="far fa-comment text-dark text-grey-900 btn-round-sm " ></i>
                            <span class="d-none-xss">{{@$p_row->comment_count}} </span>
                        </a>
                    </div>
                    <hr class=" mt-1 mb-2">
                    <div class="Comment-box l">

                        @if(sizeof($p_row->post_comments) > 0 )
                        <div>
                            <a href="{{url('post-details/user/'.$p_row->uuid)}}" class="btn btn-light d-block comment-btn" >
                                View all comments
                            </a>
                        </div>
                        @endif
                        <!-- Modal -->

                        <div class="modal fade bd-example-modal-lg{{$p}}" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body m-0 p-0 w-100" >
                                        <div class="w-100">
                                            <button type="button" class="close closing-popup" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>

                                            <div class="row">
                                                <div class="col-6 m-0 p-0 post_detail_asset">
                                                    @if($p_row->file)
                                                        @if($p_row->file_type == 'image')
                                                            <img class="sky-view"  src="{{asset('storage/'.$p_row->file)}}" >
                                                        @endif
                                                        @if($p_row->file_type == 'video')
                                                            <video class="sky-view" controls>
                                                                <source src="{{asset('storage/'.$p_row->file)}}">
                                                            </video>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="col-6 ">
                                                    <div class="chat-name">
                                                        <div class="media">
                                                            <img class="mr-2 comment-user-name" src="{{asset('storage/'.$p_row->user->image)}}" alt="Generic placeholder image">
                                                            <div class="media-body">
                                                                <h5 class="mt-0 main-name p-2">{{$p_row->user->name}}</h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="scroll-bar3 " id="thumb-scroll">

                                                        @if(sizeof($p_row->post_comments) > 0 )
                                                            <input type="hidden" name="comment_arr" class="comment_arr" id="postCommentArr" value="{{json_encode($p_row->post_comments)}}">
                                                            @foreach($p_row->post_comments as $pc => $pc_row)

                                                        <div class="Comment-user pt-1 modal_user_comment_container ">
                                                            <figure class="avatar">
                                                                <img src="{{asset('storage/'.$pc_row->user->image)}}" alt="image">
                                                            </figure>
                                                            <div>
                                                                <div class="w-50">
                                                                    <div class="Comment-wrap">
                                                                        <p class="heading">{{ucwords($pc_row->user->name)}}</p>
                                                                        <p class="comment">{{$pc_row->comment}}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="reply-back d-flex mt-1 sub-comments">
                                                                    <div class="time">{{$pc_row->created_at->diffForHumans()}}</div>
                                                                    <div class="Reply modal_main_comment_reply_btn" user_id="{{$pc_row->user->uuid}}" post_id="{{@$pc_row->post_id}}" comment_id="{{@$pc_row->uuid}}" post_type="{{@$pc_row->post_type}}" commentIndex="{{$pc}}">Reply </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                           @if(sizeof($pc_row->replies) > 0)

                                                            <div class="View-more-reply">

                                                                @foreach($pc_row->replies as $pr=> $pr_row)
                                                                    <div class="Comment-user pt-1 modal_user_reply_container">
                                                                <figure class="avatar">
                                                                    <img src="{{asset('storage/'.$pr_row->user->image)}}" class="more-reply-image" alt="image">
                                                                </figure>
                                                                <div>
                                                                    <div class="w-50">
                                                                        <div class="Comment-wrap" id="view-more-reply">
                                                                            <p class="heading">{{ucwords($pr_row->user->name)}}</p>
                                                                            <p class="comment">{{$pr_row->comment}}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="reply-back d-flex mt-1 sub-comments">
                                                                        <div class="time" id="time-ago">{{$pr_row->created_at->diffForHumans()}}</div>
{{--                                                                        <div class="Like">Like </div>--}}
                                                                        <div class="Reply reply_btn" post_id="{{@$p_row->uuid}}" post_type="{{@$p_row->post_type}}" postIndex="{{$p}}">Reply </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                                    {{--Reply Input --}}

                                                                        <div class="chat-bottom py-2 ml-5 shadow-none w-95 modal_reply_input_div" style="display: none">
                                                                        <form class="chat-form">
                                                                            <div class="Comment-user">


                                                                            </div>
                                                                            <div class="form-group">
                                                                                <input type="text" name="reply_text" class="mb-0 modal_reply_text_input" placeholder="Write a reply..." post_id="{{@$p_row->uuid}}"  post_type="{{@$p_row->post_type}}" postIndex="{{$p}}">
                                                                            </div>
                                                                            <div class="bg-arrow">
                                                                                <i class="fas fa-arrow-right right-icon modal_reply_comment_btn" >
                                                                                </i>
                                                                            </div>
                                                                        </form>
                                                                    </div>

                                                                    @endforeach
                                                            </div>
                                                                @endif
                                                            @endforeach
                                                            @endif



                                                    </div>
                                                    <div class="card-body d-flex pt-3 ml-0 p-0 reaction">
                                                        <a href="#" class=" px-1 d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss"><i class="far fa-heart text-dark text-grey-900 f-21 mr-2"></i></a>
                                                        <a href="#" class="d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss"><i class="far fa-comment text-dark text-grey-900 btn-round-sm"></i></a>
                                                        <a href="#" class="ms-auto mr-2 d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss"><i class="far fa-bookmark text-grey-900  font"></i></a>

                                                    </div>
                                                    <div class="d-block pt-0 ">
                                                        <h5 class="total-likes">{{$p_row->likes_count}} likes </h5>
                                                        <h5 class="hours-ago pt-1">{{$p_row->created_at->diffForHumans()}}  </h5>
                                                    </div>
                                                    <div class="chat-bottom py-2 pt-0 shadow-none w-100 comment_input_div" >
                                                        <form class="chat-form">
                                                            <div class="form-group">
                                                                <input type="text" name="comment_text" class="mb-0 comment_text_input"  placeholder="Write a comment..." post_id="{{@$p_row->uuid}}"  post_type="{{@$p_row->post_type}}" postIndex="{{$p}}">
                                                            </div>
                                                            <div class="bg-arrow">
                                                                <i class="fas fa-arrow-right right-icon add_modal_comment_btn"></i>
                                                            </div>
                                                        </form>
                                                    </div>










                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="scroll-bar1 user_comment_container" id="thumb-scroll" style="max-height: 140px!important;">
                           <div>
                            @if(@$p_row->latest_comment)
                            <div class="Comment-user pt-1">
                                <figure class="avatar">
                                    <img src="{{asset('storage/'.$p_row->latest_comment->user->image)}}" alt="image">
                                </figure>
                                <div>
                                    <div class="w-50">
                                        <div class="Comment-wrap">
                                            <p class="heading">{{$p_row->latest_comment->user->name}}</p>
                                            <p class="comment">{{$p_row->latest_comment->comment}}</p>
                                        </div>
                                    </div>
                                    <div class="reply-back d-flex mt-1 sub-comments">
                                        <div class="time">{{$p_row->latest_comment->created_at->diffForHumans()}}</div>
{{--                                        <div class="Like">Like </div>--}}
                                        <div class="Reply main_comment_reply_btn"  user_id="{{$p_row->latest_comment->user->id}}" post_id="{{@$p_row->uuid}}" comment_id="{{@$p_row->latest_comment->uuid}}" post_type="{{@$p_row->post_type}}" postIndex="{{$p}}">Reply </div>


                                    </div>
                                </div>
                            </div>




                            @if(sizeof(@$p_row->latest_comment->comment_replies) > 0)
                                @foreach(@$p_row->latest_comment->comment_replies as $cr => $cr_row)

                                 <div class="View-more-reply">

                                    <div class="Comment-user pt-1 user_reply_container">
                                        <figure class="avatar">
                                            <img src="{{asset('storage/'.@$cr_row->comment_reply_user->image)}}" class="more-reply-image" alt="image">
                                        </figure>
                                        <div>
                                            <div class="w-50">
                                                <div class="Comment-wrap" id="view-more-reply">
                                                    <p class="heading">{{@$cr_row->comment_reply_user->name}}</p>
                                                    <p class="comment">{{@$cr_row->comment}}</p>
                                                </div>
                                            </div>
                                            <div class="reply-back d-flex mt-1 sub-comments">
                                                <div class="time" id="time-ago">{{@$cr_row->created_at->diffForHumans()}}</div>
{{--                                                <div class="Like">Like </div>--}}
                                                <div class="Reply main_comment_reply_btn" user_id="{{$cr_row->user_id}}" post_id="{{@$cr_row->post_id}}" comment_id="{{@$cr_row->uuid}}" post_type="{{@$cr_row->post_type}}" postIndex="{{$cr}}">Reply </div>


                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @endforeach
                            @endif
                            @else
                                   {{-- <hr> --}}
                                <div class="font-weight-bold ml-2">No Comments</div>
                          @endif
                           </div>
                        </div>

                            {{--Reply Input --}}

                        <div class="chat-bottom py-2 ml-5 shadow-none w-95 reply_input_div" style="display: none">
                            <form class="chat-form">
                                <div class="Comment-user">


                                </div>
                                <div class="form-group">
                                    <input type="text" name="reply_text" class="mb-0 reply_text_input" placeholder="Write a reply..." post_id="{{@$p_row->uuid}}"  post_type="{{@$p_row->post_type}}" postIndex="{{$p}}">
                                </div>
                                <div class="bg-arrow">
                                    <i class="fas fa-arrow-right right-icon reply_comment_btn" >
                                    </i>
                                </div>
                            </form>
                        </div>

                        {{--Comment Input --}}

                        <div class="chat-bottom py-2 shadow-none w-100 comment_input_div " >
                            <form class="chat-form">
                                <div class="Comment-user">


                                </div>
                                <div class="form-group">
                                    <input type="text" name="comment_text" class="mb-0 comment_text_input"  placeholder="Write a comment..." post_id="{{@$p_row->uuid}}"  post_type="{{@$p_row->post_type}}" postIndex="{{$p}}">
                                </div>
                                <div class="bg-arrow">
                                    <i class="fas fa-arrow-right right-icon add_comment_btn"></i>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>



            @endforeach

        @else
            <div class="card w-100 shadow-xss rounded-xxl border-0 px-4 pt-3 pb-4 mb-0 mt-2 display_posts  mb-5 align-items-center">
            <div class="card-body p-0 d-flex">

                <h5 class="font-weight-bold">Welcome to Writers Talk</h5>
            </div>
            <div class="card-body p-0 d-flex">
                <p>Your posts and your Penpals’ posts will appear here</p>
            </div>
                <div class="row">
                    <div class="col-md-12 mt-2 text-center">
                        <a href="{{route('userFindPenpals')}}" class="btn btn-info">Find Penpals</a>

                    {{-- </div>
                    <div class="col-md-5"> --}}
                        <button class="btn btn-info mb-0 "  data-toggle="modal" data-target="#exampleModal1">Post</button>

                    </div>
                </div>
    </div>
    @endif

    </div>


    <div class="w-4 d-none">
        <nav class="navigation">
            <div class="nav-content ml-2">
                <div class="Friend-request">
                    <div class="card w-100 shadow-xss rounded-xxl border-0 mb-3 mt-2 friend-request-container">
                        <div class="card-body d-flex align-items-center p-3">
                            <h4 class="fw-700 mb-0 font-xssss text-grey-900">Friend Request</h4>
                            <a href="#" class="fw-600 ms-auto font-xssss text-primary">See all</a>
                        </div>
                        @if(sizeof($auth_user_request) > 0)
                            <input type="hidden" name="penpals_request_arr" id="penpalRequestArr" value="{{json_encode($auth_user_request)}}">
                            @foreach($auth_user_request as $ar => $ar_row)
                                <div class="penpal-remove-div">
                                    <div class="card-body d-flex pt-4 ps-4 pe-4 pb-0 border-top-xs bor-0  ">
                                        <a href="{{route('userProfile',['id'=> $ar_row->user->uuid])}}">
                                            <figure class="avatar me-3"><img src="{{asset('storage/'.@$ar_row->user->image)}}" alt="image" class="shadow-sm rounded-circle w45"></figure>
                                            <h4 class="fw-700 text-grey-900 font-xssss mt-1">{{@$ar_row->user->name}}<span class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">
            {{--                                    12 mutual friends--}}
                                            </span>
                                            </h4>
                                        </a>
                                    </div>
                                    <div class="card-body d-flex align-items-center pt-0 ps-4 pe-4 pb-4 ">
                                        <a href="javascript:void(0)" class="p-2 pl-3 pr-3 mr-2 lh-20 w100 bg-primary-gradiant me-2 text-white text-center font-xssss fw-600 ls-1 rounded-xl penpal-request-action-btn" penpal_uuid="{{@$ar_row->uuid}}"  request_for="Accept" penpal_index="{{$ar}}">
                                            Confirm
                                        </a>
                                        <a href="javascript:void(0)" class="p-2 pl-3 pr-3 lh-20 w100 bg-grey text-grey-800 text-center font-xssss fw-600 ls-1 rounded-xl penpal-request-action-btn" penpal_uuid="{{@$ar_row->uuid}}"  request_for="Cancel" penpal_index="{{$ar}}">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="card-body d-flex pt-4 ps-4 pe-4 pb-0 border-top-xs bor-0">
                                {{--                                <figure class="avatar me-3"><img src="{{asset('storage/'.@$ar_row->user->image)}}" alt="image" class="shadow-sm rounded-circle w45"></figure>--}}
                                <h4 class="fw-700 text-grey-900 font-xssss mt-1">No Requests
                                    <span class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">
{{--                                    12 mutual friends--}}
                                </span></h4>
                            </div>
                        @endif

                    </div>

                </div>
                <div class="confirm-Friend">
                    <div class="card w-100 shadow-xss rounded-xxl border-0 p-0 friend-card-container ">
                        <div class="card-body d-flex align-items-center p-4 mb-0">
                            <h4 class="fw-700 mb-0 font-xssss text-grey-900">Friends</h4>
                            <a href="{{route('getUserPenpals')}}" class="fw-600 ms-auto font-xssss text-primary">See all</a>
                        </div>
                        @if(sizeof($auth_user_penpals)> 0)
                            @foreach($auth_user_penpals as $p=> $p_row)

                                <div class="card-body bg-transparent-card d-flex p-2 bg-greylight m-1 rounded-3">
                                    <figure class="avatar mb-0">
                                        <img src="{{asset('storage/'.$p_row->user->image)}}" alt="image" class="shadow-sm rounded-circle w45">
                                    </figure>
                                    <h4 class="fw-700 text-grey-900 font-xssss mt-2">{{$p_row->user->name}} <span class="d-block font-xssss fw-500 mt-1  text-grey-500">
{{--                                    12 mutual friends--}}
                                </span></h4>
                                    {{--                            <a href="#" class="btn-round-sm bg-white text-grey-900 font-xss  mt-2"><i class="fas fa-chevron-right"></i></a>--}}
                                </div>
                            @endforeach
                        @else
                            <div class="card-body bg-transparent-card d-flex p-2 bg-greylight m-1 rounded-3">
                                <h4 class="fw-700 text-grey-900 font-xssss mt-2">No Friends <span class="d-block font-xssss fw-500 mt-1  text-grey-500"></span></h4>
                                {{--                                <a href="#" class="btn-round-sm bg-white text-grey-900 font-xss  mt-2"><i class="fas fa-chevron-right"></i></a>--}}
                            </div>
                        @endif
                        {{--                        <div class="card-body bg-transparent-card d-flex p-2 bg-greylight m-1 rounded-3">--}}
                        {{--                            <figure class="avatar mb-0"><img src="assets/imgs/2.jpg" alt="image" class="shadow-sm rounded-circle w45"></figure>--}}
                        {{--                            <h4 class="fw-700 text-grey-900 font-xssss mt-2"> David Agfree  <span class="d-block font-xssss fw-500 mt-1 text-grey-500">12 mutual friends</span></h4>--}}
                        {{--                            <a href="#" class="btn-round-sm bg-white text-grey-900 font-xss mt-2"><i class="fas fa-plus"></i></a>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="card-body bg-transparent-card d-flex p-2 bg-greylight m-1 rounded-3">--}}
                        {{--                            <figure class="avatar mb-0"><img src="assets/imgs/2.jpg" alt="image" class="shadow-sm rounded-circle w45"></figure>--}}
                        {{--                            <h4 class="fw-700 text-grey-900 font-xssss mt-2">Hugury Daugloi <span class="d-block font-xssss fw-500 mt-1 text-grey-500">12 mutual friends</span></h4>--}}
                        {{--                            <a href="#" class="btn-round-sm bg-white text-grey-900 font-xss mt-2"><i class="fas fa-plus"></i></a>--}}
                        {{--                        </div>--}}

                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userStoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  px-5" role="document">
            <div class="modal-content px-3" style="background: transparent; border:0;">

                <div class="modal-body p-0">
                    <div class="" >
                        <button type="button" style="z-index: 9" class="close closing-popup" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <section id="demos">
                        <div class="row">
                            <div class="large-12 columns p-0">
                                <div class="owl-carousel owl-theme dot-style3 m-0" id="ownCarouselDiv">
                                    @if($user_stories)
                                        @foreach(@$user_stories->stories as $story)
                                    <div class="item">
                                        @if($story->file_type == 'image')
                                            <img class="single-story" src="{{asset('storage/'.$story->post->file)}}">
                                        @endif
                                        @if($story->file_type == 'video')
                                            <video class="single-story" muted style="height: 68px">
                                                <source  src="{{asset('storage/'.$story->post->file)}}">
                                            </video>
                                        @endif
                                    </div>
                                        @endforeach
                                    @endif
                                </div>

                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="adminStoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  px-5" role="document">
            <div class="modal-content px-3" style="background: transparent; border:0;">

                <div class="modal-body p-0">
                    <div class="" >
                        <button type="button" style="z-index: 9" class="close closing-popup" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <section id="demos">
                        <div class="row">
                            <div class="large-12 columns p-0">
                                <div class="owl-carousel owl-theme dot-style3 m-0" id="ownCarouselDiv">
                                    @if($admin_stories)
                                        @foreach(@$admin_stories->stories as $story)
                                    <div class="item">
                                        @if($story->file_type == 'image')
                                              <img class="single-story" src="{{asset('storage/'.$story->post->file)}}">
                                         @endif
                                         @if($story->file_type == 'video')
                                        <video class="single-story" muted style="height: 68px">
                                          <source  src="{{asset('storage/'.$story->post->file)}}">
                                        </video>
                                            @endif
                                    </div>
                                        @endforeach
                                    @endif
                                </div>

                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    @if(sizeof($today_stories) > 0)
        @foreach($today_stories as $key1 => $today_story)

            <div class="modal fade" id="todayStoryModal_{{$key1}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered  px-5" role="document">
                    <div class="modal-content px-3" style="background: transparent; border:0;">

                        <div class="modal-body p-0">
                            <div class="" >
                                <button type="button" style="z-index: 9" class="close closing-popup" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <section id="demos">
                                <div class="row">
                                    <div class="large-12 columns p-0">
                                        <div class="owl-carousel owl-theme dot-style3 m-0" id="ownCarouselDiv">
                                            @if(sizeof($today_story->stories) > 0)
                                                @foreach(@$today_story->stories as $story)
                                                    <div class="item">
                                                        @if($story->file_type == 'image')
                                                            <img class="single-story" src="{{asset('storage/'.$story->post->file)}}">
                                                        @endif
                                                        @if($story->file_type == 'video')
                                                            <video class="single-story" muted style="height: 68px">
                                                                <source  src="{{asset('storage/'.$story->post->file)}}">
                                                            </video>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    @endif



    <div class="w-5 d-none" >
    </div>
    @include('partials.navigation_right')
@endsection
@push('js')
    <script>
        var owl = $('.owl-carousel.owl-carousel-tips');
        owl.owlCarousel({
            margin: 10,

            loop: false,
            responsive: {
                0: {
                    items: 1
                },
                200: {
                    items: 2
                },
                400: {
                    items: 3
                },
                800: {
                    items: 4
                },
                1000: {
                    items: 7
                }
            }
        })
    </script>


    <!-- vendors -->
    <script src="{{asset('assets/vendors/highlight.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>

    <script>
        $(document).ready(function(){
            $('#toggle-click').click(function(){
                $('#toggle-section').toggle()
                $('#writers-section').hide()

            });
            $('#writers-click').click(function(){
                $('#writers-section').show()
                $('#toggle-section').hide()
            });
            $('#closing-option').click(function(){
                $('#writers-section').hide()
                $('#toggle-section').show()
            });
        });
    </script>

    <script>

        $(document).on('click', '#addPostBtn', function (e){

            e.preventDefault();
            var description =   $('#postDescription').val()
            var file = $('.post_file')[0].files[0];
            console.log(file);
            if (description || file){
                $('#addPostForm').submit();
            }else{
                $('.validate-msg-div').show();
                $('.validate-msg').html('Select/Write some stuff to add post')

            }
        });
    </script>
    <script>
        $(document).ready(function(){
            var maxLength = 300;
            $(".show-read-more").each(function(){
                var myStr = $(this).text();
                if($.trim(myStr).length > maxLength){
                    var newStr = myStr.substring(0, maxLength);
                    var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                    $(this).empty().html(newStr);
                    $(this).append(' <a href="javascript:void(0);" class="read-more">...read more</a>');
                    $(this).append('<span class="more-text">' + removedStr + '</span>');
                }
            });
            $(".read-more").click(function(){
                $(this).siblings(".more-text").contents().unwrap();
                $(this).remove();
            });
            // $('.example').on('blur', function() {
            //     $('div.tip').fadeOut('medium');
            // });
            $('.searchbar-input').on('focus', function() {
                $(this).siblings('.searchbar-tabs').show();
            });
            $('.searchbar-tabs').hide();

            $('.close-x').click(function() {
                // $(this).siblings('.searchbar-tabs').show();
                $('.searchbar-tabs').hide();
            });
            // if($(".searchbar-tabs:visible")){
            //     $('body').click(function (event)
            //     {
            //     if(!$(event.target).closest('.searchbar-tabs').length && !$(event.target).is('.searchbar-tabs')) {
            //         $(".searchbar-tabs").hide();
            //     }
            // });
            // }

        });
    </script>

    <script>

        {{--$(document).on('click','.penpal-request-action-btn', function (e){--}}
            {{--e.preventDefault();--}}
            {{--var base_url = window.location.origin;--}}
            {{--var toRemove =  $(this).closest(".penpal-remove-div");--}}
            {{--var request_arr = $('#penpalRequestArr').val();--}}
            {{--if (request_arr){--}}
                {{--JSON.parse(request_arr);--}}
            {{--}--}}
            {{--console.log(request_arr);--}}

            {{--var request_index = $(this).attr('penpal_index')--}}
            {{--console.log(request_index);--}}
            {{--var request_for = $(this).attr('request_for')--}}
            {{--console.log(request_for);--}}

            {{--var penpal_uuid = $(this).attr('penpal_uuid')--}}
            {{--var formData = {--}}
                {{--request_for : request_for,--}}
                {{--penpal_uuid: penpal_uuid,--}}
                {{--_token:$('meta[name="csrf-token"]').attr('content')--}}
            {{--};--}}
            {{--$.ajax({--}}
                {{--type: "POST",--}}
                {{--url:'{{route("updatePenpalStatus")}}',--}}
                {{--data: formData,--}}
                {{--success:function(data){--}}
                    {{--console.log(data);--}}

                    {{--var res = JSON.parse(data);--}}
                    {{--console.log(res);--}}

                    {{--if (res.success == true){--}}
                        {{--if (res.data){--}}
                            {{--$(toRemove).remove();--}}
                            {{--var friendsFormData = {--}}
                                {{--// request_for : request_for,--}}
                                {{--// penpal_uuid: penpal_uuid,--}}
                                {{--_token:$('meta[name="csrf-token"]').attr('content')--}}
                            {{--};--}}
                            {{--$.ajax({--}}
                                {{--type: "POST",--}}
                                {{--url:'{{route("userPenpals")}}',--}}
                                {{--data: friendsFormData,--}}
                                {{--success: function (data){--}}
                                    {{--console.log(data);--}}
                                    {{--var res = JSON.parse(data)--}}
                                    {{--console.log(res);--}}
                                    {{--var html_body = '<div class="card-body d-flex align-items-center p-4 mb-0"> ' +--}}
                                        {{--'<h4 class="fw-700 mb-0 font-xssss text-grey-900">Friends</h4> ' +--}}
                                        {{--'<a href="" class="fw-600 ms-auto font-xssss text-primary">See all</a> ' +--}}
                                        {{--'</div>';--}}

                                    {{--$('.friend-card-container').html('');--}}
                                    {{--if (res.data.length > 0){--}}
                                        {{--$.map(res.data, function (val,index){--}}
                                            {{--html_body += '<div class="card-body bg-transparent-card d-flex p-2 bg-greylight m-1 rounded-3"> ' +--}}
                                                {{--'<figure class="avatar mb-0">' +--}}
                                                {{--'<img src="'+base_url+'/storage/'+val.user.image+'" alt="image" class="shadow-sm rounded-circle w45">' +--}}
                                                {{--'</figure>'+--}}
                                                {{--'<h4 class="fw-700 text-grey-900 font-xssss mt-2">'+val.user.name+--}}
                                                {{--'<span class="d-block font-xssss fw-500 mt-1  text-grey-500"></span></h4></div>'--}}
                                        {{--});--}}
                                    {{--}else{--}}
                                        {{--html_body += '<div class="card-body bg-transparent-card d-flex p-2 bg-greylight m-1 rounded-3">' +--}}
                                            {{--'<h4 class="fw-700 text-grey-900 font-xssss mt-2">No Friends <span class="d-block font-xssss fw-500 mt-1  text-grey-500"></span></h4> ' +--}}
                                            {{--'</div>';--}}
                                    {{--}--}}
                                    {{--$('.friend-card-container').append(html_body)--}}
                                {{--}--}}
                            {{--});--}}


                        {{--}--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}
    </script>

    <script src="{{asset('assets/js/post.js')}}"></script>
    <script>
        function readFile(input) {
            var reader;
            var isImageExt = true;
            var isVideoExt = true;
            var  validImageExtensions = ["jpg","pdf","jpeg","gif","png"];
            var  validVideoExtensions = ["mp4","mov", "x-m4v"];
            $('#FileExtension').val('');
            console.log(input.files)
            console.log(input.files[0]);
            if (input.files && input.files[0]) {
                var file_ext = input.files[0].name.split('.').pop().toLowerCase();
                console.log(file_ext);
                if (validImageExtensions.indexOf(file_ext) == -1){
                    isImageExt = false;
                }
                if (validVideoExtensions.indexOf(file_ext) == -1){
                    isVideoExt = false;
                }

                console.log('---------------Is Video --------------')
                console.log(isVideoExt)
                console.log('---------------Is Image --------------')
                console.log(isImageExt)

                if (isImageExt == true){
                    reader = new FileReader();
                    reader.onload = function(e) {
                        $('#FileExtension').val('image');
                        // $('.myVideo').hide();
                        // $('.myImage').show();
                        // $('.myImage').attr('src', e.target.result);
                        // $('.myImage').css('opacity', 1);
                        // $('.file-label').text(input.files[0].name)
                    };
                    reader.readAsDataURL(input.files[0]);

                }

                if (isVideoExt == true){
                    reader = new FileReader();
                    reader.onload = function(e) {
                        $('#FileExtension').val('video');
                        // $('.myImage').hide();
                        // $('.myVideo').show();
                        // $('.myVideo').attr('src', e.target.result);
                        // $('.file-label').text(input.files[0].name)
                    };
                    reader.readAsDataURL(input.files[0]);

                }


            }
        }

        $(document).on('click', '#addPostBtn', function (e){

            e.preventDefault();
            var description =   $('#postDescription').val()
            console.log($('.post_file'));
            var file = $('.post_file')[0].files[0];
            console.log(file);
            if (description || file){
                $('#addPostForm').submit();
            }else{
                $('.validate-msg-div').show();
                $('.validate-msg').html('Select/Write some stuff to add post')

            }
        });


        document.getElementsByClassName('input-vid-img')[0].addEventListener('change', function(event) {
            var file = event.target.files[0];
            var fileReader = new FileReader();
            if (file.type.match('image')) {
                fileReader.onload = function() {
                    var img = document.createElement('img');
                    img.src = fileReader.result;
                    document.getElementsByClassName('upload-vid-img')[0].appendChild(img);
                };
                fileReader.readAsDataURL(file);
            } else {
                fileReader.onload = function() {
                    var blob = new Blob([fileReader.result], {type: file.type});
                    var url = URL.createObjectURL(blob);
                    var video = document.createElement('video');
                    var timeupdate = function() {
                        if (snapImage()) {
                            video.removeEventListener('timeupdate', timeupdate);
                            video.pause();
                        }
                    };
                    video.addEventListener('loadeddata', function() {
                        if (snapImage()) {
                            video.removeEventListener('timeupdate', timeupdate);
                        }
                    });
                    var snapImage = function() {
                        var canvas = document.createElement('canvas');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                        var image = canvas.toDataURL();
                        var success = image.length > 100000;
                        if (success) {
                            var img = document.createElement('img');
                            img.src = image;
                            document.getElementsByClassName('upload-vid-img')[0].appendChild(img);
                            URL.revokeObjectURL(url);
                        }
                        return success;
                    };
                    video.addEventListener('timeupdate', timeupdate);
                    video.preload = 'metadata';
                    video.src = url;
                    // Load video in Safari / IE11
                    video.muted = true;
                    video.playsInline = true;
                    video.play();
                };
                fileReader.readAsArrayBuffer(file);
            }
        });
    </script>
    <script type="text/javascript">
        initiateOwl();
        $(document).on('click','.authUserStoryModalTrigger',function(e){
            e.preventDefault();
            $("#ownCarouselDiv").html('');
            var html_body = "";
             $('.auth_user_stories').each(function(){
             var data_story = JSON.parse($(this).attr('post'));
             console.log(data_story)
              html_body =  "  <div class=\"item\">\n" +
                 "                                        <img src='http://admin.writerstalkadmin.com/public/storage/"+data_story.file+"' alt=\"\">\n" +
                 "                                    </div>";


                 // owl-carousel owl-theme dot-style3 m-0 owl-loaded owl-drag
                 // owl-carousel owl-theme dot-style3 m-0 owl-loaded owl-drag
                 // owl-carousel owl-theme dot-style3 m-0 owl-loaded owl-drag
             $("#ownCarouselDiv").append(html_body);
            })
            initiateOwl();


            // var tip_type = $('.auth_user_stories').val()
            // console.log('posts');
            // console.log(stories);
            // console.log('tip type');
            // console.log(tip_type);
            // var data = JSON.parse(stories);
            // console.log(data)
            // var stories_arr = data.stories
            // console.log(stories_arr);
            // if(stories_arr.stories && stories_arr.length > 0){
            //     $('#ownCarouselDiv').html('');
            //     $.map(stories_arr , function (val,index){
            //         var html_body = '<div class="item">\n' +
            //                             '<img src="http://admin.writerstalkadmin.com/public/storage/uploads/users/user_1636013369_11zon_cropped.jpg" alt="">\n' +
            //                         '</div>'
            //     });
            //     $('#owlCarouselDiv').append(html_body);
            // }

        })
    </script>
    <script type="text/javascript">
        function search_home(event){
          var search = $('#homeSearchInput').val();
          console.log(search)
            var formData = {
                search : search,
                _token:$('meta[name="csrf-token"]').attr('content')
            };
            $.ajax({
                type: "POST",
                url: '/search-home-input',
                data: formData,
                dataType: "json",
                success:function(data){
                    var writers = data['writers'];
                    var posts = data['posts'];
                    var tags = data['tags'];

                    $("#writer").html('')
                    $("#render_posts").html('')
                    $("#My-Tips").html('')

                    if (writers.length > 0){
                        $.map(writers, function(item, index){
                            var html_writer = "<a href='http://writerstalkadmin.com/profile/"+item.uuid+"' class=\"media py-1 one-user align-items-center\"> " +
                                "<img class=\"mr-2 chat-user-id ml-2\" src='http://admin.writerstalkadmin.com/public/storage/"+item.image+"' alt=\"Generic placeholder image\">\n" +
                                "                                                <div class=\"media-body d-flex\">\n" +
                                "                                                    <div>\n" +
                                "                                                        <h5 class=\"mt-0 main-name\">"+item.name+"</h5>\n" +
                                "                                                        <div class=\"time last-seen line-height-1\">"+item.favorite_genres+"</div>\n" +
                                "                                                    </div>\n" +
                                "                                                    \n" +
                                "                                                </div>\n" +
                                "                                            </a>";
                            $("#writer").append(html_writer)
                        })
                    }
                    if (posts.length > 0){
                        $.map(posts, function(item, index){
                            var html_posts = "<div class=\"col-md-4 p-1\">\n" +
                                "                                                    <a href=\"\">\n";
                            if(item.file && item.file_type != "video"){
                                html_posts += "<img class=\"posts-pics pt-1 pb-2 shadow-xss rounded-xxl\" src='http://admin.writerstalkadmin.com/public/storage/"+item.file+"' alt=\"\">\n";
                            }else{
                                html_posts += "<img class=\"posts-pics pt-1 pb-2 shadow-xss rounded-xxl\" src=\"http://admin.writerstalkadmin.com/public/storage/uploads/images/post_17581637579449.jpeg\" alt=\"\">\n";
                            }
                            html_posts += "                                                    </a>\n" +
                                "                                                </div>";
                            $("#render_posts").append(html_posts)

                        })
                    }
                    if (tags.length > 0){
                        $.map(tags, function(item, index){
                            var html_tags = "<a href=\"\" class=\"media py-1 one-user align-items-center\">\n" +
                                "                                                <h1 class=\"tag-hash\">#</h1>\n" +
                                "                                                <div class=\"media-body d-flex\">\n" +
                                "                                                    <div>\n" +
                                "                                                        <h5 class=\"mt-0 main-name\">"+item.tag_name+"</h5>\n" +
                                "                                                    </div>\n" +
                                "                                                </div>\n" +
                                "                                            </a>";
                            $("#My-Tips").append(html_tags)

                        })
                    }

                }
            })
        }
    </script>

@endpush



