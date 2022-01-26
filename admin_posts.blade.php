@extends('layouts.main')
@section('title')
    Admin Posts
@endsection

@section('content')

{{--    Write you code here--}}



<div class="w-40 mt-6 pt-1 mx-auto">
    <div class="tips">
        <ul class="nav nav-tabs" id="myTab11" role="tablist">
          <li class="nav-item w-50">
          <a class="nav-link active text-center font-md font-weight-bold" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Pro Tips</a>

            <!-- <a class="nav-link text-center active font-md font-weight-bold" id="home-tab11" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Pro Tips</a> -->
          </li>
          <li class="nav-item w-50">
          <a class="nav-link text-center font-md font-weight-bold" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Prompts</a>

            <!-- <a class="nav-link text-center font-md font-weight-bold" id="profile-tab11" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Basics</a> -->
          </li>
        </ul>
        <div class="tab-content " id="myTabContent11">

          <!-- <div class="tab-pane fade show active display_pro_posts" id="home11" role="tabpanel" aria-labelledby="home-tab"> -->
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
             <div class="post-btns d-flex">
                @if(sizeof($hashtags) > 0)
                    @foreach($hashtags as $hashtag)
                        <button type="button" class=" pointer btn btn-secondary trigger_filter" tid="{{$hashtag->uuid}}" id="knowledge-buttons">{{$hashtag->tag_name}}</button>
                    @endforeach
                @endif
                <button type="button" class="pointer btn btn-secondary" id="searching-button">Search</button>
                <div id="searching-option" class="admin-post-search w-100" style="display:none">
                    <form class="form-inline my-2 my-lg-0 ml-0 justify-content-center w-100 form-position">
                        {{-- <i class="fas fa-search search-icon"></i> --}}

                        <input type="text" placeholder="Start typing to search.." class="searchbar-input search-box pt-2 pb-2 trigger_search trigger_search_tag" id="homeSearchInput">
                        <div id="search_list"></div>
                        <div class="All-posts mt-1 mt-3 searchbar-tabs" id="tag-post-section" style="top:29px; right:30px; display:none;" >
                                <div class="AvhYw nLL4f"></div>
                                <div class="close-x p-ab" id="disclose-option">X</div>
                                       <div class="tips shadow-xss display_searched_tags">
                                        @if(sizeof($hashtags) > 0)
                                        @foreach($hashtags as $hashtag)
                                        <div class="media py-1 one-user align-items-center">
                                          <h1 class="tag-hash">#</h1>
                                            <div class="media-body d-flex">
                                                <div>
                                                    <h5 class="mt-0 main-name trigger_filter_searchable" tid="{{$hashtag->uuid}}">{{$hashtag->tag_name}}</h5>
                                                </div>
                                            </div>
                                        </div>

                                          @endforeach
                                          @endif
                                </div>
                            </div>
                        <button type="button" class="pointer closing-menu-option" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="display_pro_posts">
                 @if(sizeof($pro_posts) > 0)

                  <input type="hidden" name="pro_posts" id="pro_posts" value="{{json_encode($pro_posts)}}">
               <input type="hidden" name="user_id" id="userId" value="{{\Illuminate\Support\Facades\Auth::user()->uuid}}">
                  @foreach($pro_posts as $p => $pro_post)
               <div class="p-3 border-tips">
                  <div class="media">
                          <img class="mr-3 tip-profile rounded-circle" src="{{asset('storage/assets/logo.jpeg')}}" alt="Generic placeholder image">
                      <div class="media-body">
                        <h5 class="mt-0 font-weight-bold">{{@$pro_post->title}}</h5>
                        <span class="post-time">{{$pro_post->basic_formatted_date}}</span>
                      </div>
                  </div>
                   @if($pro_post->file_type == "video")
                       <video  class="tip-post py-5 w-100">
                           <source src="{{asset('storage/'.$pro_post->file)}}">
                       </video>
                   @else
                       <img class="tip-post py-5 w-100" src="{{asset('storage/'.$pro_post->file)}}" alt="Generic placeholder image">
                   @endif
                   {!! @$pro_post->description !!}
                     <div class="card-body d-flex p-0">
                        <a href="javascript:void(0)" class=" px-1 d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss like_box" id="postLikeCount">
                          @if($pro_post->is_like)
                              <i class="fas fa-heart text-danger text-danger-900 f-21 mr-2 post_like" postIndex="{{$p}}"></i>
                          @else
                              <i class="far fa-heart text-dark text-grey-900 f-21 mr-2 post_like" postIndex="{{$p}}"></i>
                          @endif
                           <span class="d-none-xss">{{$pro_post->likes_count}} </span>
                        </a>
                       <a  class="d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss" data-toggle="modal" data-target=".bd-example-modal-lg{{$p}}"><i class="far fa-comment text-dark text-grey-900 btn-round-sm"></i><span class="d-none-xss">{{$pro_post->comments_count}} </span>

                       </a>
                     </div>
                     <hr class=" mt-1 mb-2">
                        <!-- Button trigger modal -->
                      {{--  <!-- @if(sizeof($p_row->post_comments) > 0 ) --> --}}

                            <div class="w-50">
                                <div class="Comment-wrap">

                                </div>
                            </div>
                         <div class="reply-back d-flex mt-1 sub-comments">
                         </div>
                      {{-- <!--   @endif --> --}}
                  </div>

                  @endforeach
                  @endif
            </div>

            <input type="hidden" name="user_id" id="userId" value="{{\Illuminate\Support\Facades\Auth::user()->uuid}}">
          </div>
            <!-- <div class="tab-pane fade display_basic_posts" id="profile" role="tabpanel" aria-labelledby="profile-tab"> -->
            <div class="tab-pane fade  display_basic_posts" id="profile" role="tabpanel" aria-labelledby="profile-tab">


                  <div class="row mb-2 mt-2">
                <div class="large-12 columns">
                    <div class="d-flex float right">
                        <div class="searchbar-posts mr-2">


                                <input type="text" placeholder="Lets Begin" class="searchbar-input pl-3 search-box pt-2 pb-2 text-center " id="homeSearchInput" value="{{$quick_text->text}}" >

                        </div>
                        <form  method="POST" action="/upload-quick" id="formId" enctype="multipart/form-data">
                            @csrf
                        <div class="craete-new-post mr-2">
                            <button type="button" class="btn btn-primary btn-new-post mb-0 p-0" >
                            <i class="fas fa-plus"></i>
                            </button>
                            <input type="file" name="video" id="videoUpload" class="mb-0 vid-tag-input post_file" accept=".mov,.mp4,.x-m4v">
                            </div>
                        </form>
                            <div class="craete-new-post mr-1">
                            <a href="{{ url('archive-prompts') }}">
                            <button type="button" class="btn btn-primary btn-new-post mb-0 p-0" >
                            <i class="fas fa-archive"></i>
                            </button>
                            </a>
                            </div>

                    </div>
                </div>
            </div>
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                     @if(sizeof($quick) > 0)
                    @foreach($quick as $key => $quickData)

                   <div class="carousel-item @if($key == 0) active @endif">


                     <video width="100%" height="400px" autoplay="" muted="" loop="" class="" style="background-color:black;">
			           <source src="{{asset('storage/'.@$quickData->file)}}"  type="video/mp4" alt="First slide" >
                     </video>
                     <a href="{{ url('profile/'.$quickData->user_details->uuid) }}">
                     <div class="media m-1">
                          <img class=" tip-profile rounded-circle mr-3" src="{{asset('storage/'.@$quickData->user_details->image)}}" alt="Generic placeholder image">
                      <div class="media-body ml-0 align-self-center">
                        <h5 class="mt-0 font-weight-bold">{{$quickData->user_details->name}}</h5>
                        <span class="post-time">{{ \Carbon\Carbon::parse($quickData->created_at)->diffForHumans()}}</span>
                      </div>
                  </div>
              </a>
                   </div>




                 @endforeach
                 @endif
                 </div>
                   <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                   <span class="sr-only">Previous</span>
                 </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                   <span class="carousel-control-next-icon" aria-hidden="true"></span>
                   <span class="sr-only">Next</span>
                 </a>
                </div>

            </div>

        </div>
    </div>

    <div class="modal fade myLargeModalLabel bd-example-modal-lg" id="" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <!-- role="dialog" aria-labelledby="myLargeModalLabel" -->
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

                            {{--<div class="modal-dialog modal-lg modal-dialog-centered" role="document">--}}

                                {{--<div class="modal-content">--}}

                                    {{--<div class="modal-body m-0 p-0 w-100">--}}
                                        {{--<div class="w-100">--}}
                                            {{--<button type="button" class="close closing-popup" data-dismiss="modal" aria-label="Close">--}}
                                                {{--<span aria-hidden="true">×</span>--}}
                                            {{--</button>--}}

                                            {{--<div class="row">--}}
                                                {{--<div class="col-6 m-0 p-0 post_detail_asset">--}}
                                                                                                                                                                        {{--<img class="sky-view" src="http://127.0.0.1:8000/storage/uploads/images/post_84371637146872.jpg">--}}
                                                                                                                                                                                                                    {{--</div>--}}
                                                {{--<div class="col-6 ">--}}
                                                    {{--<div class="chat-name">--}}
                                                        {{--<div class="media">--}}
                                                            {{--<img class="mr-2 comment-user-name" src="http://127.0.0.1:8000/storage/uploads/users/user_1636013369_11zon_cropped.jpg" alt="Generic placeholder image">--}}
                                                            {{--<div class="media-body">--}}
                                                                {{--<h5 class="mt-0 main-name p-2">Zeeshan Shabir</h5>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}


                                                      <input type="hidden" name="comment_arr" class="comment_arr" id="postCommentArr" value="[{&quot;id&quot;:30,&quot;uuid&quot;:&quot;144e61de-b530-4a28-b9aa-ae663da753cc&quot;,&quot;user_id&quot;:&quot;13&quot;,&quot;post_id&quot;:&quot;d84957f1-3b31-4f50-8ee5-78f04f201f48&quot;,&quot;parent_id&quot;:null,&quot;post_type&quot;:&quot;user&quot;,&quot;comment&quot;:&quot;Nice&quot;,&quot;comment_type&quot;:null,&quot;commentable_id&quot;:&quot;6&quot;,&quot;commentable_type&quot;:&quot;App\\Models\\Post&quot;,&quot;created_at&quot;:&quot;2021-11-17T03:25:08.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-17T03:25:08.000000Z&quot;,&quot;user&quot;:{&quot;id&quot;:13,&quot;uuid&quot;:&quot;72042d2e-8381-4839-84b2-b9c898b50ba4&quot;,&quot;name&quot;:&quot;Zeeshan Shabir&quot;,&quot;email&quot;:&quot;ziishan.mughal@gmail.com&quot;,&quot;favorite_genres&quot;:&quot;Fantasy&quot;,&quot;email_verified_at&quot;:null,&quot;country_code&quot;:&quot;92&quot;,&quot;contact_no&quot;:&quot;30078601&quot;,&quot;status&quot;:&quot;active&quot;,&quot;bio&quot;:&quot;Chemistry&quot;,&quot;image&quot;:&quot;uploads\/users\/user_1636013369_11zon_cropped.jpg&quot;,&quot;views&quot;:0,&quot;show_top_hundered&quot;:1,&quot;promo_code&quot;:null,&quot;promo_used&quot;:0,&quot;referral_used&quot;:0,&quot;verify_user&quot;:0,&quot;invitation_key&quot;:null,&quot;api_token&quot;:null,&quot;device_token&quot;:null,&quot;created_at&quot;:&quot;2021-10-25T02:39:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-05T03:04:27.000000Z&quot;},&quot;replies&quot;:[]},{&quot;id&quot;:31,&quot;uuid&quot;:&quot;0ecb83fd-8430-4e79-a3da-9a788b86cc74&quot;,&quot;user_id&quot;:&quot;13&quot;,&quot;post_id&quot;:&quot;d84957f1-3b31-4f50-8ee5-78f04f201f48&quot;,&quot;parent_id&quot;:null,&quot;post_type&quot;:&quot;user&quot;,&quot;comment&quot;:&quot;Good 1&quot;,&quot;comment_type&quot;:null,&quot;commentable_id&quot;:&quot;6&quot;,&quot;commentable_type&quot;:&quot;App\\Models\\Post&quot;,&quot;created_at&quot;:&quot;2021-11-17T03:25:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-17T03:25:12.000000Z&quot;,&quot;user&quot;:{&quot;id&quot;:13,&quot;uuid&quot;:&quot;72042d2e-8381-4839-84b2-b9c898b50ba4&quot;,&quot;name&quot;:&quot;Zeeshan Shabir&quot;,&quot;email&quot;:&quot;ziishan.mughal@gmail.com&quot;,&quot;favorite_genres&quot;:&quot;Fantasy&quot;,&quot;email_verified_at&quot;:null,&quot;country_code&quot;:&quot;92&quot;,&quot;contact_no&quot;:&quot;30078601&quot;,&quot;status&quot;:&quot;active&quot;,&quot;bio&quot;:&quot;Chemistry&quot;,&quot;image&quot;:&quot;uploads\/users\/user_1636013369_11zon_cropped.jpg&quot;,&quot;views&quot;:0,&quot;show_top_hundered&quot;:1,&quot;promo_code&quot;:null,&quot;promo_used&quot;:0,&quot;referral_used&quot;:0,&quot;verify_user&quot;:0,&quot;invitation_key&quot;:null,&quot;api_token&quot;:null,&quot;device_token&quot;:null,&quot;created_at&quot;:&quot;2021-10-25T02:39:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-05T03:04:27.000000Z&quot;},&quot;replies&quot;:[{&quot;id&quot;:32,&quot;uuid&quot;:&quot;db9d24f0-2228-41b7-8fa0-1d079959b0f0&quot;,&quot;user_id&quot;:&quot;13&quot;,&quot;post_id&quot;:&quot;d84957f1-3b31-4f50-8ee5-78f04f201f48&quot;,&quot;parent_id&quot;:&quot;0ecb83fd-8430-4e79-a3da-9a788b86cc74&quot;,&quot;post_type&quot;:&quot;user&quot;,&quot;comment&quot;:&quot;@Zeeshan Shabir yes&quot;,&quot;comment_type&quot;:null,&quot;commentable_id&quot;:&quot;6&quot;,&quot;commentable_type&quot;:&quot;App\\Models\\Post&quot;,&quot;created_at&quot;:&quot;2021-11-17T04:28:11.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-17T04:28:11.000000Z&quot;,&quot;comment_reply_user&quot;:{&quot;id&quot;:13,&quot;uuid&quot;:&quot;72042d2e-8381-4839-84b2-b9c898b50ba4&quot;,&quot;name&quot;:&quot;Zeeshan Shabir&quot;,&quot;email&quot;:&quot;ziishan.mughal@gmail.com&quot;,&quot;favorite_genres&quot;:&quot;Fantasy&quot;,&quot;email_verified_at&quot;:null,&quot;country_code&quot;:&quot;92&quot;,&quot;contact_no&quot;:&quot;30078601&quot;,&quot;status&quot;:&quot;active&quot;,&quot;bio&quot;:&quot;Chemistry&quot;,&quot;image&quot;:&quot;uploads\/users\/user_1636013369_11zon_cropped.jpg&quot;,&quot;views&quot;:0,&quot;show_top_hundered&quot;:1,&quot;promo_code&quot;:null,&quot;promo_used&quot;:0,&quot;referral_used&quot;:0,&quot;verify_user&quot;:0,&quot;invitation_key&quot;:null,&quot;api_token&quot;:null,&quot;device_token&quot;:null,&quot;created_at&quot;:&quot;2021-10-25T02:39:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-05T03:04:27.000000Z&quot;}}]},{&quot;id&quot;:35,&quot;uuid&quot;:&quot;2c539bba-e16b-40e5-9388-5a345c18cf29&quot;,&quot;user_id&quot;:&quot;13&quot;,&quot;post_id&quot;:&quot;d84957f1-3b31-4f50-8ee5-78f04f201f48&quot;,&quot;parent_id&quot;:null,&quot;post_type&quot;:&quot;user&quot;,&quot;comment&quot;:&quot;hiii&quot;,&quot;comment_type&quot;:null,&quot;commentable_id&quot;:&quot;6&quot;,&quot;commentable_type&quot;:&quot;App\\Models\\Post&quot;,&quot;created_at&quot;:&quot;2021-12-10T17:35:44.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-12-10T17:35:44.000000Z&quot;,&quot;user&quot;:{&quot;id&quot;:13,&quot;uuid&quot;:&quot;72042d2e-8381-4839-84b2-b9c898b50ba4&quot;,&quot;name&quot;:&quot;Zeeshan Shabir&quot;,&quot;email&quot;:&quot;ziishan.mughal@gmail.com&quot;,&quot;favorite_genres&quot;:&quot;Fantasy&quot;,&quot;email_verified_at&quot;:null,&quot;country_code&quot;:&quot;92&quot;,&quot;contact_no&quot;:&quot;30078601&quot;,&quot;status&quot;:&quot;active&quot;,&quot;bio&quot;:&quot;Chemistry&quot;,&quot;image&quot;:&quot;uploads\/users\/user_1636013369_11zon_cropped.jpg&quot;,&quot;views&quot;:0,&quot;show_top_hundered&quot;:1,&quot;promo_code&quot;:null,&quot;promo_used&quot;:0,&quot;referral_used&quot;:0,&quot;verify_user&quot;:0,&quot;invitation_key&quot;:null,&quot;api_token&quot;:null,&quot;device_token&quot;:null,&quot;created_at&quot;:&quot;2021-10-25T02:39:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-05T03:04:27.000000Z&quot;},&quot;replies&quot;:[]},{&quot;id&quot;:36,&quot;uuid&quot;:&quot;4960a1b5-357f-4206-8e1b-b2ada0a746d9&quot;,&quot;user_id&quot;:&quot;13&quot;,&quot;post_id&quot;:&quot;d84957f1-3b31-4f50-8ee5-78f04f201f48&quot;,&quot;parent_id&quot;:null,&quot;post_type&quot;:&quot;user&quot;,&quot;comment&quot;:&quot;hiii&quot;,&quot;comment_type&quot;:null,&quot;commentable_id&quot;:&quot;6&quot;,&quot;commentable_type&quot;:&quot;App\\Models\\Post&quot;,&quot;created_at&quot;:&quot;2021-12-10T17:35:44.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-12-10T17:35:44.000000Z&quot;,&quot;user&quot;:{&quot;id&quot;:13,&quot;uuid&quot;:&quot;72042d2e-8381-4839-84b2-b9c898b50ba4&quot;,&quot;name&quot;:&quot;Zeeshan Shabir&quot;,&quot;email&quot;:&quot;ziishan.mughal@gmail.com&quot;,&quot;favorite_genres&quot;:&quot;Fantasy&quot;,&quot;email_verified_at&quot;:null,&quot;country_code&quot;:&quot;92&quot;,&quot;contact_no&quot;:&quot;30078601&quot;,&quot;status&quot;:&quot;active&quot;,&quot;bio&quot;:&quot;Chemistry&quot;,&quot;image&quot;:&quot;uploads\/users\/user_1636013369_11zon_cropped.jpg&quot;,&quot;views&quot;:0,&quot;show_top_hundered&quot;:1,&quot;promo_code&quot;:null,&quot;promo_used&quot;:0,&quot;referral_used&quot;:0,&quot;verify_user&quot;:0,&quot;invitation_key&quot;:null,&quot;api_token&quot;:null,&quot;device_token&quot;:null,&quot;created_at&quot;:&quot;2021-10-25T02:39:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-05T03:04:27.000000Z&quot;},&quot;replies&quot;:[]},{&quot;id&quot;:37,&quot;uuid&quot;:&quot;c29ed405-f4ea-4578-bb1d-c977fe39e626&quot;,&quot;user_id&quot;:&quot;13&quot;,&quot;post_id&quot;:&quot;d84957f1-3b31-4f50-8ee5-78f04f201f48&quot;,&quot;parent_id&quot;:null,&quot;post_type&quot;:&quot;user&quot;,&quot;comment&quot;:&quot;hiii&quot;,&quot;comment_type&quot;:null,&quot;commentable_id&quot;:&quot;6&quot;,&quot;commentable_type&quot;:&quot;App\\Models\\Post&quot;,&quot;created_at&quot;:&quot;2021-12-10T17:35:45.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-12-10T17:35:45.000000Z&quot;,&quot;user&quot;:{&quot;id&quot;:13,&quot;uuid&quot;:&quot;72042d2e-8381-4839-84b2-b9c898b50ba4&quot;,&quot;name&quot;:&quot;Zeeshan Shabir&quot;,&quot;email&quot;:&quot;ziishan.mughal@gmail.com&quot;,&quot;favorite_genres&quot;:&quot;Fantasy&quot;,&quot;email_verified_at&quot;:null,&quot;country_code&quot;:&quot;92&quot;,&quot;contact_no&quot;:&quot;30078601&quot;,&quot;status&quot;:&quot;active&quot;,&quot;bio&quot;:&quot;Chemistry&quot;,&quot;image&quot;:&quot;uploads\/users\/user_1636013369_11zon_cropped.jpg&quot;,&quot;views&quot;:0,&quot;show_top_hundered&quot;:1,&quot;promo_code&quot;:null,&quot;promo_used&quot;:0,&quot;referral_used&quot;:0,&quot;verify_user&quot;:0,&quot;invitation_key&quot;:null,&quot;api_token&quot;:null,&quot;device_token&quot;:null,&quot;created_at&quot;:&quot;2021-10-25T02:39:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-05T03:04:27.000000Z&quot;},&quot;replies&quot;:[]},{&quot;id&quot;:38,&quot;uuid&quot;:&quot;71fd551b-d15c-4195-af5e-aeb50452aa37&quot;,&quot;user_id&quot;:&quot;13&quot;,&quot;post_id&quot;:&quot;d84957f1-3b31-4f50-8ee5-78f04f201f48&quot;,&quot;parent_id&quot;:null,&quot;post_type&quot;:&quot;user&quot;,&quot;comment&quot;:&quot;hiii&quot;,&quot;comment_type&quot;:null,&quot;commentable_id&quot;:&quot;6&quot;,&quot;commentable_type&quot;:&quot;App\\Models\\Post&quot;,&quot;created_at&quot;:&quot;2021-12-10T17:35:46.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-12-10T17:35:46.000000Z&quot;,&quot;user&quot;:{&quot;id&quot;:13,&quot;uuid&quot;:&quot;72042d2e-8381-4839-84b2-b9c898b50ba4&quot;,&quot;name&quot;:&quot;Zeeshan Shabir&quot;,&quot;email&quot;:&quot;ziishan.mughal@gmail.com&quot;,&quot;favorite_genres&quot;:&quot;Fantasy&quot;,&quot;email_verified_at&quot;:null,&quot;country_code&quot;:&quot;92&quot;,&quot;contact_no&quot;:&quot;30078601&quot;,&quot;status&quot;:&quot;active&quot;,&quot;bio&quot;:&quot;Chemistry&quot;,&quot;image&quot;:&quot;uploads\/users\/user_1636013369_11zon_cropped.jpg&quot;,&quot;views&quot;:0,&quot;show_top_hundered&quot;:1,&quot;promo_code&quot;:null,&quot;promo_used&quot;:0,&quot;referral_used&quot;:0,&quot;verify_user&quot;:0,&quot;invitation_key&quot;:null,&quot;api_token&quot;:null,&quot;device_token&quot;:null,&quot;created_at&quot;:&quot;2021-10-25T02:39:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-05T03:04:27.000000Z&quot;},&quot;replies&quot;:[]}]">

                                                        <div class="Comment-user pt-1 modal_user_comment_container ">
                                                            <figure class="avatar">
                                                                <img src="http://127.0.0.1:8000/storage/uploads/users/user_1636013369_11zon_cropped.jpg" alt="image" class="comment-chat-user1">
                                                            </figure>
                                                            <div>
                                                                <div class="w-50">
                                                                    <div class="Comment-wrap">
                                                                        <p class="heading">Zeeshan Shabir</p>
                                                                        <p class="comment">Nice</p>
                                                                    </div>
                                                                </div>
                                                                <div class="reply-back d-flex mt-1 sub-comments">
                                                                    <div class="time">3 weeks ago</div>
                                                                    <div class="Reply modal_main_comment_reply_btn" user_id="72042d2e-8381-4839-84b2-b9c898b50ba4" post_id="d84957f1-3b31-4f50-8ee5-78f04f201f48" comment_id="144e61de-b530-4a28-b9aa-ae663da753cc" post_type="user" commentindex="0">Reply </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                                                                                    {{--<input type="hidden" name="comment_arr" class="comment_arr" id="postCommentArr" value="[{&quot;id&quot;:30,&quot;uuid&quot;:&quot;144e61de-b530-4a28-b9aa-ae663da753cc&quot;,&quot;user_id&quot;:&quot;13&quot;,&quot;post_id&quot;:&quot;d84957f1-3b31-4f50-8ee5-78f04f201f48&quot;,&quot;parent_id&quot;:null,&quot;post_type&quot;:&quot;user&quot;,&quot;comment&quot;:&quot;Nice&quot;,&quot;comment_type&quot;:null,&quot;commentable_id&quot;:&quot;6&quot;,&quot;commentable_type&quot;:&quot;App\\Models\\Post&quot;,&quot;created_at&quot;:&quot;2021-11-17T03:25:08.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-17T03:25:08.000000Z&quot;,&quot;user&quot;:{&quot;id&quot;:13,&quot;uuid&quot;:&quot;72042d2e-8381-4839-84b2-b9c898b50ba4&quot;,&quot;name&quot;:&quot;Zeeshan Shabir&quot;,&quot;email&quot;:&quot;ziishan.mughal@gmail.com&quot;,&quot;favorite_genres&quot;:&quot;Fantasy&quot;,&quot;email_verified_at&quot;:null,&quot;country_code&quot;:&quot;92&quot;,&quot;contact_no&quot;:&quot;30078601&quot;,&quot;status&quot;:&quot;active&quot;,&quot;bio&quot;:&quot;Chemistry&quot;,&quot;image&quot;:&quot;uploads\/users\/user_1636013369_11zon_cropped.jpg&quot;,&quot;views&quot;:0,&quot;show_top_hundered&quot;:1,&quot;promo_code&quot;:null,&quot;promo_used&quot;:0,&quot;referral_used&quot;:0,&quot;verify_user&quot;:0,&quot;invitation_key&quot;:null,&quot;api_token&quot;:null,&quot;device_token&quot;:null,&quot;created_at&quot;:&quot;2021-10-25T02:39:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-05T03:04:27.000000Z&quot;},&quot;replies&quot;:[]},{&quot;id&quot;:31,&quot;uuid&quot;:&quot;0ecb83fd-8430-4e79-a3da-9a788b86cc74&quot;,&quot;user_id&quot;:&quot;13&quot;,&quot;post_id&quot;:&quot;d84957f1-3b31-4f50-8ee5-78f04f201f48&quot;,&quot;parent_id&quot;:null,&quot;post_type&quot;:&quot;user&quot;,&quot;comment&quot;:&quot;Good 1&quot;,&quot;comment_type&quot;:null,&quot;commentable_id&quot;:&quot;6&quot;,&quot;commentable_type&quot;:&quot;App\\Models\\Post&quot;,&quot;created_at&quot;:&quot;2021-11-17T03:25:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-17T03:25:12.000000Z&quot;,&quot;user&quot;:{&quot;id&quot;:13,&quot;uuid&quot;:&quot;72042d2e-8381-4839-84b2-b9c898b50ba4&quot;,&quot;name&quot;:&quot;Zeeshan Shabir&quot;,&quot;email&quot;:&quot;ziishan.mughal@gmail.com&quot;,&quot;favorite_genres&quot;:&quot;Fantasy&quot;,&quot;email_verified_at&quot;:null,&quot;country_code&quot;:&quot;92&quot;,&quot;contact_no&quot;:&quot;30078601&quot;,&quot;status&quot;:&quot;active&quot;,&quot;bio&quot;:&quot;Chemistry&quot;,&quot;image&quot;:&quot;uploads\/users\/user_1636013369_11zon_cropped.jpg&quot;,&quot;views&quot;:0,&quot;show_top_hundered&quot;:1,&quot;promo_code&quot;:null,&quot;promo_used&quot;:0,&quot;referral_used&quot;:0,&quot;verify_user&quot;:0,&quot;invitation_key&quot;:null,&quot;api_token&quot;:null,&quot;device_token&quot;:null,&quot;created_at&quot;:&quot;2021-10-25T02:39:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-05T03:04:27.000000Z&quot;},&quot;replies&quot;:[{&quot;id&quot;:32,&quot;uuid&quot;:&quot;db9d24f0-2228-41b7-8fa0-1d079959b0f0&quot;,&quot;user_id&quot;:&quot;13&quot;,&quot;post_id&quot;:&quot;d84957f1-3b31-4f50-8ee5-78f04f201f48&quot;,&quot;parent_id&quot;:&quot;0ecb83fd-8430-4e79-a3da-9a788b86cc74&quot;,&quot;post_type&quot;:&quot;user&quot;,&quot;comment&quot;:&quot;@Zeeshan Shabir yes&quot;,&quot;comment_type&quot;:null,&quot;commentable_id&quot;:&quot;6&quot;,&quot;commentable_type&quot;:&quot;App\\Models\\Post&quot;,&quot;created_at&quot;:&quot;2021-11-17T04:28:11.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-17T04:28:11.000000Z&quot;,&quot;comment_reply_user&quot;:{&quot;id&quot;:13,&quot;uuid&quot;:&quot;72042d2e-8381-4839-84b2-b9c898b50ba4&quot;,&quot;name&quot;:&quot;Zeeshan Shabir&quot;,&quot;email&quot;:&quot;ziishan.mughal@gmail.com&quot;,&quot;favorite_genres&quot;:&quot;Fantasy&quot;,&quot;email_verified_at&quot;:null,&quot;country_code&quot;:&quot;92&quot;,&quot;contact_no&quot;:&quot;30078601&quot;,&quot;status&quot;:&quot;active&quot;,&quot;bio&quot;:&quot;Chemistry&quot;,&quot;image&quot;:&quot;uploads\/users\/user_1636013369_11zon_cropped.jpg&quot;,&quot;views&quot;:0,&quot;show_top_hundered&quot;:1,&quot;promo_code&quot;:null,&quot;promo_used&quot;:0,&quot;referral_used&quot;:0,&quot;verify_user&quot;:0,&quot;invitation_key&quot;:null,&quot;api_token&quot;:null,&quot;device_token&quot;:null,&quot;created_at&quot;:&quot;2021-10-25T02:39:12.000000Z&quot;,&quot;updated_at&quot;:&quot;2021-11-05T03:04:27.000000Z&quot;}}]}]">--}}

                                                        {{--<div class="Comment-user pt-1 modal_user_comment_container ">--}}
                                                            {{--<figure class="avatar">--}}
                                                                {{--<img src="http://127.0.0.1:8000/storage/uploads/users/user_1636013369_11zon_cropped.jpg" alt="image">--}}
                                                            {{--</figure>--}}
                                                            {{--<div>--}}
                                                                {{--<div class="w-50">--}}
                                                                    {{--<div class="Comment-wrap">--}}
                                                                        {{--<p class="heading">Zeeshan Shabir</p>--}}
                                                                        {{--<p class="comment">Nice</p>--}}
                                                                    {{--</div>--}}
                                                                {{--</div>--}}
                                                                {{--<div class="reply-back d-flex mt-1 sub-comments">--}}
                                                                    {{--<div class="time">{{$pro_post->basic_formatted_date}}</div>--}}
                                                                    {{--<div class="Reply modal_main_comment_reply_btn" user_id="72042d2e-8381-4839-84b2-b9c898b50ba4" post_id="d84957f1-3b31-4f50-8ee5-78f04f201f48" comment_id="144e61de-b530-4a28-b9aa-ae663da753cc" post_type="user" commentindex="0">Reply </div>--}}
                                                                {{--</div>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}


                                                        {{--<div class="Comment-user pt-1 modal_user_comment_container ">--}}
                                                            {{--<figure class="avatar">--}}
                                                                {{--<img src="http://127.0.0.1:8000/storage/uploads/users/user_1636013369_11zon_cropped.jpg" alt="image">--}}
                                                            {{--</figure>--}}
                                                            {{--<div>--}}
                                                                {{--<div class="w-50">--}}
                                                                    {{--<div class="Comment-wrap">--}}
                                                                        {{--<p class="heading">Zeeshan Shabir</p>--}}
                                                                        {{--<p class="comment">Good 1</p>--}}
                                                                    {{--</div>--}}
                                                                {{--</div>--}}
                                                                {{--<div class="reply-back d-flex mt-1 sub-comments">--}}
                                                                    {{--<div class="time">3 weeks ago</div>--}}
                                                                    {{--<div class="Reply modal_main_comment_reply_btn" user_id="72042d2e-8381-4839-84b2-b9c898b50ba4" post_id="d84957f1-3b31-4f50-8ee5-78f04f201f48" comment_id="0ecb83fd-8430-4e79-a3da-9a788b86cc74" post_type="user" commentindex="1">Reply </div>--}}
                                                                {{--</div>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}


                                                                                                                                </div>

                                                        <div class="Comment-user pt-1 modal_user_comment_container ">
                                                            <figure class="avatar">
                                                                <img src="http://127.0.0.1:8000/storage/uploads/users/user_1636013369_11zon_cropped.jpg" alt="image">
                                                            </figure>
                                                            <div>
                                                                <div class="w-50">
                                                                    <div class="Comment-wrap">
                                                                        <p class="heading">Zeeshan Shabir</p>
                                                                        <p class="comment">hiii</p>
                                                                    </div>
                                                                </div>
                                                                <div class="reply-back d-flex mt-1 sub-comments">
                                                                    <div class="time">19 hours ago</div>
                                                                    <div class="Reply modal_main_comment_reply_btn" user_id="72042d2e-8381-4839-84b2-b9c898b50ba4" post_id="d84957f1-3b31-4f50-8ee5-78f04f201f48" comment_id="2c539bba-e16b-40e5-9388-5a345c18cf29" post_type="user" commentindex="2">Reply </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="Comment-user pt-1 modal_user_comment_container ">
                                                            <figure class="avatar">
                                                                <img src="http://127.0.0.1:8000/storage/uploads/users/user_1636013369_11zon_cropped.jpg" alt="image">
                                                            </figure>
                                                            <div>
                                                                <div class="w-50">
                                                                    <div class="Comment-wrap">
                                                                        <p class="heading">Zeeshan Shabir</p>
                                                                        <p class="comment">hiii</p>
                                                                    </div>
                                                                </div>
                                                                <div class="reply-back d-flex mt-1 sub-comments">
                                                                    <div class="time">19 hours ago</div>
                                                                    <div class="Reply modal_main_comment_reply_btn" user_id="72042d2e-8381-4839-84b2-b9c898b50ba4" post_id="d84957f1-3b31-4f50-8ee5-78f04f201f48" comment_id="4960a1b5-357f-4206-8e1b-b2ada0a746d9" post_type="user" commentindex="3">Reply </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="Comment-user pt-1 modal_user_comment_container ">
                                                            <figure class="avatar">
                                                                <img src="http://127.0.0.1:8000/storage/uploads/users/user_1636013369_11zon_cropped.jpg" alt="image">
                                                            </figure>
                                                            <div>
                                                                <div class="w-50">
                                                                    <div class="Comment-wrap">
                                                                        <p class="heading">Zeeshan Shabir</p>
                                                                        <p class="comment">hiii</p>
                                                                    </div>
                                                                </div>
                                                                <div class="reply-back d-flex mt-1 sub-comments">
                                                                    <div class="time">19 hours ago</div>
                                                                    <div class="Reply modal_main_comment_reply_btn" user_id="72042d2e-8381-4839-84b2-b9c898b50ba4" post_id="d84957f1-3b31-4f50-8ee5-78f04f201f48" comment_id="c29ed405-f4ea-4578-bb1d-c977fe39e626" post_type="user" commentindex="4">Reply </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="Comment-user pt-1 modal_user_comment_container ">
                                                            <figure class="avatar">
                                                                <img src="http://127.0.0.1:8000/storage/uploads/users/user_1636013369_11zon_cropped.jpg" alt="image">
                                                            </figure>
                                                            <div>
                                                                <div class="w-50">
                                                                    <div class="Comment-wrap">
                                                                        <p class="heading">Zeeshan Shabir</p>
                                                                        <p class="comment">hiii</p>
                                                                    </div>
                                                                </div>
                                                                <div class="reply-back d-flex mt-1 sub-comments">
                                                                    <div class="time">19 hours ago</div>
                                                                    <div class="Reply modal_main_comment_reply_btn" user_id="72042d2e-8381-4839-84b2-b9c898b50ba4" post_id="d84957f1-3b31-4f50-8ee5-78f04f201f48" comment_id="71fd551b-d15c-4195-af5e-aeb50452aa37" post_type="user" commentindex="5">Reply </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                                            {{--</div>--}}
                                                                            {{--<div class="form-group">--}}
                                                                                {{--<input type="text" name="reply_text" class="mb-0 modal_reply_text_input" placeholder="Write a reply..." post_id="d84957f1-3b31-4f50-8ee5-78f04f201f48" post_type="user" postindex="1">--}}
                                                                            {{--</div>--}}
                                                                            {{--<div class="bg-arrow">--}}
                                                                                {{--<i class="fas fa-arrow-right right-icon modal_reply_comment_btn">--}}
                                                                                {{--</i>--}}
                                                                            {{--</div>--}}
                                                                        {{--</form>--}}
                                                                    {{--</div>--}}

                                                                                                                                {{--</div>--}}
                                                                                                                                                                                        {{----}}


                                                    {{--</div>--}}
                                                    {{--<div class="card-body d-flex pt-3 ml-0 p-0 reaction">--}}
                                                        {{--<a href="#" class=" px-1 d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss"><i class="far fa-heart text-dark text-grey-900 f-21 mr-2"></i></a>--}}
                                                        {{--<a href="#" class="d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss"><i class="far fa-comment text-dark text-grey-900 btn-round-sm"></i></a>--}}
                                                        {{--<a href="#" class="ms-auto mr-2 d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss"><i class="far fa-bookmark text-grey-900  font"></i></a>--}}

                                                    {{--</div>--}}
                                                    {{--<div class="d-block pt-0 ">--}}
                                                        {{--<h5 class="total-likes">0 likes </h5>--}}
                                                        {{--<h5 class="hours-ago pt-1">3 weeks ago  </h5>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="chat-bottom py-2 pt-0 shadow-none w-100 comment_input_div">--}}
                                                        {{--<form class="chat-form">--}}
                                                            {{--<div class="form-group">--}}
                                                                {{--<input type="text" name="comment_text" class="mb-0 comment_text_input" placeholder="Write a comment..." post_id="d84957f1-3b31-4f50-8ee5-78f04f201f48" post_type="user" postindex="1">--}}
                                                            {{--</div>--}}
                                                            {{--<div class="bg-arrow">--}}
                                                                {{--<i class="fas fa-arrow-right right-icon add_modal_comment_btn"></i>--}}
                                                            {{--</div>--}}
                                                        {{--</form>--}}
                                                    {{--</div>--}}

                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
</div>
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
                                                  @if($pro_post->file)
                                                      @if($pro_post->file_type == 'image')
                                                          <img class="sky-view"  src="{{asset('storage/'.$pro_post->file)}}" >
                                                      @endif
                                                      @if($pro_post->file_type == 'video')
                                                          <video class="sky-view" controls>
                                                              <source src="{{asset('storage/'.$pro_post->file)}}">
                                                          </video>
                                                      @endif
                                                  @endif
                                              </div>
                                              <div class="col-6 ">
                                                  <div class="chat-name">
                                                      <div class="media">
                                                          <img class="mr-2 comment-user-name" src="{{asset('http://127.0.0.1:8000/storage/assets/logo.jpeg')}}" alt="Generic placeholder image">
                                                          <div class="media-body">
                                                              <h5 class="mt-0 main-name p-2">{{--{{$pro_post->user->name}}--}} Admin </h5>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="scroll-bar3 " id="thumb-scroll">
                                                      @if(sizeof($pro_post->comments) > 0 )
                                                          <input type="hidden" name="comment_arr" class="comment_arr" id="postCommentArr" value="{{json_encode($pro_post->comments)}}">
                                                          @foreach($pro_post->comments as $pc => $pc_row)
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
                                                                                      <div class="Reply reply_btn" post_id="{{@$pro_post->uuid}}" post_type="{{@$pro_post->post_type}}" postIndex="{{$p}}">Reply </div>
                                                                                  </div>
                                                                              </div>
                                                                          </div>
                                                                          {{--Reply Input --}}
                                                                          <div class="chat-bottom py-2 ml-5 shadow-none w-95 modal_reply_input_div" style="display: none">
                                                                              <form class="chat-form">
                                                                                  <div class="Comment-user">
                                                                                  </div>
                                                                                  <div class="form-group">
                                                                                      <input type="text" name="reply_text" class="mb-0 modal_reply_text_input" placeholder="Write a reply..." post_id="{{@$pro_post->uuid}}"  post_type="{{@$pro_post->post_type}}" postIndex="{{$p}}">
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
                                                      <h5 class="total-likes">{{$pro_post->likes_count}} likes </h5>
                                                      <h5 class="hours-ago pt-1">{{$pro_post->created_at->diffForHumans()}}  </h5>
                                                  </div>
                                                  <div class="chat-bottom py-2 pt-0 shadow-none w-100 comment_input_div" >
                                                      <form class="chat-form">
                                                          <div class="form-group">
                                                              <input type="text" name="comment_text" class="mb-0 comment_text_input"  placeholder="Write a comment..." post_id="{{@$pro_post->uuid}}"  post_type="{{@$pro_post->post_type}}" postIndex="{{$p}}">
                                                          </div>
                                                          <div class="bg-arrow">
                                                              <i class="fas fa-arrow-right right-icon add_modal_comment_btn "></i>
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





@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>


    $(document).on('click','.like_box',function(e) {
        e.preventDefault();
        let element = $(this);
        var post_index = $(this).find('.post_like').attr('postIndex');
        var post_arr = $('#pro_posts').val();
        console.log(post_arr)
        post_arr = JSON.parse(post_arr);
        var index_object = post_arr[post_index];
        var formData = {
            uuid: index_object.uuid,
            post_type: 'admin',
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        $.ajax({
            type: "POST",
            url: '/save-admin-pro-post-like',
            data: formData,
            success: function (data) {
                var res = JSON.parse(data);
                if (res.success == true) {
                    var new_obj = post_arr[post_index].like_counts = res.like_count
                    var current_obj_pointer = $(".display_pro_posts")[post_index]
                    $(current_obj_pointer).find('.like_box').html('')
                    if (res.is_like) {
                        element.html(`<i class="fas fa-heart text-danger text-danger-900 f-21 mr-2 post_like" postIndex="${post_index}"></i><span class="like_count_val">${res.likes_count}</span>`);
                    } else {
                        element.html(`<i class="far fa-heart text-dark text-grey-900 f-21 mr-2 post_like" postIndex="${post_index}"></i><span class="like_count_val">${res.likes_count}</span>`);
                    }
                }


            }
        })
    })
    $(document).on('click','.basic_post_like',function(e) {
        e.preventDefault();
        var post_index = $(this).attr('postIndex');
        var post_arr = $('#basic_posts').val();
        console.log(post_arr)
        post_arr = JSON.parse(post_arr);
        var index_object = post_arr[post_index];
        var formData = {
            uuid: index_object.uuid,
            post_type: 'admin',
            _token: $('meta[name="csrf-token"]').attr('content')
        };


        $.ajax({
            type: "POST",
            url: '/save-admin-pro-post-like',
            data: formData,
            success: function (data) {
                var res = JSON.parse(data);
                if (res.success == true) {
                    var new_obj = post_arr[post_index].like_counts = res.like_count
                    var current_obj_pointer = $(".display_basic_posts")[post_index]
                    $(current_obj_pointer).find('.like_box').html('')
                    var html_body = "";
                    if (res.is_like) {
                        html_body += '<i class="fas fa-heart text-danger text-danger-900 f-21 mr-2 basic_post_like" postIndex="' + post_index + '"></i>';
                    } else {
                        html_body += '<i class="far fa-heart text-dark text-grey-900 f-21 mr-2 basic_post_like" postIndex="' + post_index + '"></i>';

                    }
                    html_body += '<span class="like_count_val">' + res.likes_count + '</span> '
                    console.log($(current_obj_pointer).find('.like_box'));
                    $(current_obj_pointer).find('.like_box').html(html_body)
                    console.log('------');
                    console.log('res.is_like');
                    console.log(res.is_like);
                    console.log(res.likes_count);
                    console.log(post_index);
                    console.log(post_arr);
                }


            }
        })
    })
</script>







@push('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>

    <script>
        $(document).ready(function(){
            $('#searching-button').click(function(){
                $('#searching-option').show()
                $('.btn-secondary').hide()
            });
            $('#homeSearchInput').click(function(){
                $('#tag-post-section').show()
            });
            $('#disclose-option').click(function(){
                $('#tag-post-section').hide()
            });
            $('.closing-menu-option').click(function(){
                $('.trigger_search').val('')
                $('#searching-option').hide()
                $('.btn-secondary').show()
                getAdminPosts();
            });
            $('.trigger_search').keyup(function(){
                $(".trigger_filter").removeClass('active_button')
            })
            $('.trigger_filter').click(function(){
                $(".trigger_filter").removeClass('active_button')
                $(this).addClass('active_button')
                $('.trigger_search').val('')
                getAdminPosts();
            });

            function getAdminPosts(){
let tid = $('.trigger_filter.active_button').attr('tid');
                var _token   = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: 'POST',
                    url: '/filter-admin-posts',
                    data: {
                        _token: _token,
                        tid:tid
                    },
                    datatype:"json",
                    success: function (data) {
                       let posts_arr = JSON.parse(data);

                        if (posts_arr.length > 0){
                            $.map(posts_arr, function (item,index){

                                if(item.tip_type=="pro")
                                {
                                      // let pro_html = "";
                                      let pro_html = "<div class=\"p-3 border-tips\">\n" +
    "                  <div class=\"media\">\n" +
    "                          <img class=\"mr-3 tip-profile rounded-circle\" src='http://admin.writerstalkadmin.com/public/storage/'"+item.file+"' alt=\"Generic placeholder image\">\n" +
    "                      <div class=\"media-body\">\n" +
    "                        <h5 class=\"mt-0 font-weight-bold\">"+item.title+"</h5>\n" +
    "                        <span class=\"post-time\">"+item.basic_formatted_date+"</span>\n" +
    "                      </div>\n" +
    "                  </div>\n";
    if (item.file_type == "video")
    {
pro_html +=  "<video  class=\"tip-post py-5 w-100\">\n" +
    " <source src='http://admin.writerstalkadmin.com/public/storage/."+item.file+"'>\n" +
    "                       </video>\n" ;
    }
    else
    {

pro_html += "<img class=\"tip-post py-5 w-100\" src='http://admin.writerstalkadmin.com/public/storage/"+item.file+"' alt=\"Generic placeholder image\">\n" ;
    }


    pro_html += item.description +
        "<div class=\"card-body d-flex p-0\">\n" +
    "                        <a href=\"javascript:void(0)\" class=\" px-1 d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss like_box\" id=\"postLikeCount\">\n" ;
    if(item.is_like)
    {
        pro_html += " <i class=\"fas fa-heart text-danger text-danger-900 f-21 mr-2 post_like_two\" postIndex=\""+index+"\" postId='"+item.uuid+"'></i>\n" ;
    }
    else
    {
         pro_html += "<i class=\"far fa-heart text-dark text-grey-900 f-21 mr-2 post_like_two\" postIndex=\""+index+"\" postId='"+item.uuid+"'></i>\n" ;
    }
                                                        //alert(item.likes_count);

   pro_html +=  "                           <span class=\"d-none-xss\">"+item.likes_count+"</span>\n" +
    "                        </a>\n" +
    "                       <a  class=\"d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg"+index+"\"><i class=\"far fa-comment text-dark text-grey-900 btn-round-sm\"></i><span class=\"d-none-xss\">"+item.comments_count+"</span>\n" +
    "\n" +
    "                       </a>\n" +
    "                     </div>\n" +
    "                     <hr class=\" mt-1 mb-2\">\n" +
    "                        <!-- Button trigger modal -->\n" +
    "                {{--       <!-- if(sizeof(item.post_comments) > 0 ) --> --}}\n" +
    "\n" +
    "                            <div class=\"w-50\">\n" +
    "                                <div class=\"Comment-wrap\">\n" +
    "\n" +
    "                                </div>\n" +
    "                            </div>\n" +
    "                         <div class=\"reply-back d-flex mt-1 sub-comments\">\n" +
    "                         </div>\n" +
    "                      {{-- <!--   endif --> --}}\n" +
    "                  </div>";

                                $(".display_pro_posts").append(pro_html)
                                }

                            })
                        }


                    }
                });
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.trigger_search_tag').on('keyup',function(){
                var query = $(this).val();
                 var _token   = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url:'/search-tag',
                    type: 'post',

                    data:{search:query,
                         _token:_token},
                    success:function(data){
                        $('.display_searched_tags').html('');
                        let result = JSON.parse(data)
                    if (result.length > 0) {
                         $.map(result, function (item,index){
                            let html = '<a href="javascript:void(0)" class="media py-1 one-user align-items-center">'+
                                          '<h1 class="tag-hash">#</h1>'+
                                            '<div class="media-body d-flex">'+
                                                '<div>'+
                                                    '<h5 class="mt-0 main-name trigger_filter_searchable" tid="'+item.uuid+'">'+item.tag_name+'</h5>'+
                                                '</div>'+
                                            '</div>'+
                                        '</a>';
                        $('.display_searched_tags').append(html);

                         })
                    }
                    }

                });
            });
        });

    </script>
    <script type="text/javascript">
         $(document).on('click','.trigger_filter_searchable',function(e){
            e.preventDefault();
            let tid = $(this).attr('tid');
            var _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                    type: 'POST',
                    url: '/filter-admin-posts',
                    data: {
                        _token: _token,
                        tid:tid
                    },
                    datatype:"json",
                    success: function (data) {
                       let posts_arr = JSON.parse(data);
$(".display_pro_posts").html('')
                        if (posts_arr.length > 0){
                            $.map(posts_arr, function (item,index){
                                if(item.tip_type=="pro")
                                {
                                      // let pro_html = "";
                                      let pro_html = "<div class=\"p-3 border-tips\">\n" +
    "                  <div class=\"media\">\n" +
    "                          <img class=\"mr-3 tip-profile rounded-circle\" src='http://admin.writerstalkadmin.com/public/storage/'"+item.file+"' alt=\"Generic placeholder image\">\n" +
    "                      <div class=\"media-body\">\n" +
    "                        <h5 class=\"mt-0 font-weight-bold\">"+item.title+"</h5>\n" +
    "                        <span class=\"post-time\">"+item.basic_formatted_date+"</span>\n" +
    "                      </div>\n" +
    "                  </div>\n";
    if (item.file_type == "video")
    {
pro_html +=  "<video  class=\"tip-post py-5 w-100\">\n" +
    " <source src='http://admin.writerstalkadmin.com/public/storage/."+item.file+"'>\n" +
    "                       </video>\n" ;
    }
    else
    {

pro_html += "<img class=\"tip-post py-5 w-100\" src='http://writerstalkadmin.com/public/storage/"+item.file+"' alt=\"Generic placeholder image\">\n" ;
    }


    pro_html += "                     <div class=\"card-body d-flex p-0\">\n" +
    "                        <a href=\"javascript:void(0)\" class=\" px-1 d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss like_box\" id=\"postLikeCount\">\n" ;
    if(item.is_like)
    {
        pro_html += " <i class=\"fas fa-heart text-danger text-danger-900 f-21 mr-2 post_like_two\" postIndex=\""+index+"\" postId='"+item.uuid+"'></i>\n" ;
    }
    else
    {
         pro_html += "<i class=\"far fa-heart text-dark text-grey-900 f-21 mr-2 post_like_two\" postIndex=\""+index+"\" postId='"+item.uuid+"'></i>\n" ;
    }
                                                        //alert(item.likes_count);

   pro_html +=  "                           <span class=\"d-none-xss\">"+item.likes_count+"</span>\n" +
    "                        </a>\n" +
    "                       <a  class=\"d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss\" data-toggle=\"modal\" data-target=\".bd-example-modal-lg"+index+"\"><i class=\"far fa-comment text-dark text-grey-900 btn-round-sm\"></i><span class=\"d-none-xss\">"+item.comments_count+"</span>\n" +
    "\n" +
    "                       </a>\n" +
    "                     </div>\n" +
    "                     <hr class=\" mt-1 mb-2\">\n" +
    "                        <!-- Button trigger modal -->\n" +
    "                {{--       <!-- if(sizeof(item.post_comments) > 0 ) --> --}}\n" +
    "\n" +
    "                            <div class=\"w-50\">\n" +
    "                                <div class=\"Comment-wrap\">\n" +
    "\n" +
    "                                </div>\n" +
    "                            </div>\n" +
    "                         <div class=\"reply-back d-flex mt-1 sub-comments\">\n" +
    "                         </div>\n" +
    "                      {{-- <!--   endif --> --}}\n" +
    "                  </div>";

                                $(".display_pro_posts").append(pro_html)
                                }

                            })
                        }

   $('#searching-option').hide()
                $('.btn-secondary').show()
  $('#tag-post-section').hide()
                    }
                });
            // end code
         });

     </script>

 <script type="text/javascript">
  $('#videoUpload').on('change',function(){
        //alert('hello');
      $("#formId").submit();
    })

    </script>
@endpush
