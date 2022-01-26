@extends('layouts.main')

@section('title')
Profile
@endsection
@push('style')
    <style>
        .show-read-more .more-text{
            display: none;
        }
 .anyClass {
  height:180px;
  overflow-y: scroll;
   }

   </style>
@endpush
@section('content')
<div class="d-flex">
        <div class="w-40 mx-auto mt-5">

        <div class="row comments pt-2">

                    <i class="fas fa-sliders-h pt-2 ml-auto mr-1" id="toggle-click"></i>



               </div>
               <div class="media d-block text-center mt-2">
             <img class="mr-3 profile-image rounded-circle" src="{{asset('storage/'.@$is_user->image)}}" alt="Generic placeholder image">
            <div class="media-body mt-0 pt-0">

            <h5 class="mt-0 user-name ">{{@$is_user->name}}<i class="fas fa-star pl-2 text-warning"></i></h5>
            <h5 class="mt-0  user-country">{{@$is_user->favorite_genres}}</h5>
            <h5 class="mt-0  pb-1 penpal">{{@$is_user->penpals_count}} PenPal</h5>




        <!-- Button trigger modal -->

        @if($is_user->uuid != \Illuminate\Support\Facades\Auth::user()->uuid)
                    @if($is_user->penpal_status == 'Friends')
                        <button type="submit" class="btn btn-primary mt-1 ">
                            {{@$is_user->penpal_status}}
                        </button>
                    @endif
                    @if($is_user->penpal_status == 'Request Sent')

                                <button type="submit" class="btn btn-primary mt-1 ">
                                    {{@$is_user->penpal_status}}
                                </button>
                    @endif

                    @if($is_user->penpal_status == 'Confirm or Cancel')
                            <form action="{{url('update-penpal-status')}}" method="post">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{$is_user->uuid}}">

                                <input type="hidden" name="status" value="Accept">
                                <button type="submit" class="btn btn-primary mt-1 ">
                                    Accept
                                </button>
                            </form>
                            <form action="{{url('update-penpal-status')}}" method="post">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{$is_user->uuid}}">

                                <input type="hidden" name="status" value="Cancel">
                                <button type="submit" class="btn btn-primary mt-1 ">
                                    Cancel
                                </button>
                            </form>
                    @endif
                    @if($is_user->penpal_status == 'Add Friend')
                            <form action="{{url('add-penpal')}}" method="post" id="add-penpal-form">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{$is_user->uuid}}">

                                <input type="hidden" name="request_for" value="Request Sent">
                                <button type="submit" class="btn btn-primary mt-1 ">
                                    {{$is_user->penpal_status}}
                                </button>
                            </form>
                    @endif


        @endif
        @if($is_user->uuid == \Illuminate\Support\Facades\Auth::user()->uuid)
            <button type="button" class="btn btn-primary mt-1 " data-toggle="modal" data-target="#exampleModal">
                Upload Story
            </button>
        @endif


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form action="{{route('submitUserHighlight')}}" method="POST" enctype="multipart/form-data" id="submitHighlightForm">
              @csrf
        <div class="avatar-upload">
            <div class="avatar-edit">
                <input type='file' name="highlight_thumb_image" id="imageUpload" accept=".png, .jpg, .jpeg" />
                <label for="imageUpload"></label>
            </div>
            <div class="avatar-preview">
                <div id="imagePreview" style="background-image: url(https://i.ibb.co/xfqPmPP/upload.png);">
                </div>
            </div>
        </div>

        {{-- <div class="uploading-file">
        <button type="button" class="btn btn-upload"><i class="fas fa-upload"></i><span class="d-block upload"> UPLOAD</span></button></div> --}}
        <div class="file-name">

            <input type="text" name="highlight_name" class="form-control enter-name" id="formGroupExampleInput" placeholder="Enter file name"></div>
            {{-- <div class="pick-items d-flex">
                  <select  id="genres_multi_select"  multiple="multiple">
                      <option disabled selected>Select Genre for Highlight</option>
                      @if(sizeof($genres) > 0)
                          @foreach($genres as $g=>$g_row)
                              <option class="option" value="{{$g_row->genres}}">{{$g_row->genres}}</option>
                          @endforeach
                      @endif
                  </select>
            </div> --}}
            <div>
            <select  class="overflow-auto"  name="highlight_genres[]" id="choices-multiple-remove-button" placeholder="Select upto 5 tags" multiple>

              @if(sizeof($genres) > 0)
                          @foreach($genres as $g=>$g_row)

                              <option class="overflow-auto"  value="{{$g_row->genres}}">{{$g_row->genres}}</option>

                          @endforeach
                      @endif
            </select>


        <div class="p-rel upload-document-btn">
            <button type="button" class="btn btn-upload-document">Upload Story</button>
            {{-- <input type="file" name="" id="""> --}}
            <input type="file" name="highlight_document" class="upload-d p-ab" accept=".pdf" id="myPdf" /><br>
            <canvas id="pdfViewer"></canvas>
        </div>
          </form>

      </div>

        <div class="modal-footer">

        <button type="button" class="btn btn-primary Submit-Document" id="submitHighlightBtn">Submit Document</button>
      </div>
    </div>
  </div>
</div>





  </div>
</div>
            @if(sizeof($is_user->highlights) >  0)

                    <div class="row pb-1">
                        <div class="owl-carousel" id="story-line" style="display:flex!important">
                            @foreach($is_user->highlights as $h => $h_row)
                                <div class="story-profile trigger_highlight" hid="{{$h_row->uuid}}">
                                    <img class="" src="{{asset('storage/'.$h_row->file_image)}}" alt="image">
                                    <p>{{@$h_row->title}}</p>

                                </div>
                            @endforeach
                        </div>
                    </div>
            @endif
            <!-- Button trigger modal -->


  <!-- Modal -->
  <div class="modal fade" id="highlight_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered view-pdf mb-0" role="document">
		<div class="modal-content"> {{--
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
			</div> --}}
			<div class="modal-body text-center">
				<div class="">
					<button type="button" class="close closing-popup" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
				</div>
				<div class="">
					<div class="uploaded-pdf-view text-center  pt-3"> {{--
						<p>Rating : 105</p>
						<p>View : 0</p> --}}
						<h2>Rate this document</h2>
						<div class="rating" style="">
							<input id="rating-5" type="radio" name="rating" value="5" />
							<label for="rating-5"><i class="fas fa-3x fa-star"></i></label>
							<input id="rating-4" type="radio" name="rating" value="4" checked />
							<label for="rating-4"><i class="fas fa-3x fa-star"></i></label>
							<input id="rating-3" type="radio" name="rating" value="3" />
							<label for="rating-3"><i class="fas fa-3x fa-star"></i></label>
							<input id="rating-2" type="radio" name="rating" value="2" />
							<label for="rating-2"><i class="fas fa-3x fa-star"></i></label>
							<input id="rating-1" type="radio" name="rating" value="1" />
							<label for="rating-1"><i class="fas fa-3x fa-star"></i></label>
						</div>
                        <input type="hidden" id="highlight_id" value="">
						{{--<iframe src="{{asset('/assets/imgs/dummy.pdf')}}" class="my-2" width="100%" height="500px"></iframe>--}}
						{{--<iframe src="{{asset('/assets/imgs/QR-CODES_4.pdf')}}" class="my-2" width="100%" height="500px"></iframe>--}}
                        <a id="highlight_image_anc" href="{{asset('/assets/imgs/img-bg.png')}}" target="_blank">
                            <img src="{{asset('/assets/imgs/img-bg.png')}}" />
                        </a>
						<div class="mt-4 px-5">

                            <div class="tips-type mt-1">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item w-50 " > <a class="nav-link font-md font-weight-bold text-center active show pros highlight_comment_tab" id="pros-tab" data-toggle="tab" href="#Posts" role="tab" aria-controls="Posts" aria-selected="true">Praise</a> </li>
                                    <li class="nav-item w-50 "> <a class="nav-link font-md font-weight-bold text-center cons highlight_comment_tab" id="cons-tab" data-toggle="tab" href="#My-Tips" role="tab" aria-controls="My-Tips" aria-selected="false">Critique</a> </li>
                                </ul>
                                <div class="add-comment-pdf px-3 pt-3 d-flex">
                                    <textarea name="" id="highlight_comment" cols="30" rows="1" value="Add comment here .." class="mb-0 mx-2"> </textarea>
                                    <button id="highlight_comment_submit"><i class="far fa-paper-plane"></i></button>
                                </div>
                                 <div style="" class="chat-boxes pdf-coments mt-2 scroll-bar" id="thumb-scroll1">
                                    <div class="single-chat pb-1">
                                        <div class="tab-content profile-posts-align" id="myTabContent">
                                            <div class="tab-pane fade active show display_praise_comments" id="Posts" role="tabpanel" aria-labelledby="posts-tab">
                                                <div class="media"> <img class="mr-3 single-user-name" src="{{asset('assets/imgs/2.jpg')}}" alt="Generic placeholder image">
                                                    <div class="media-body mt-1 text-left">
                                                        <h5 class="mt-0 main-name">Username</h5>
                                                        <p class="review">I liked this document alot!</p>
                                                        <div class="time last-seen">2 min ago </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane fade display_critique_comments" id="My-Tips" role="tabpanel" aria-labelledby="tips-tab">
                                                <div class="media"> <img class="mr-3 single-user-name" src="{{asset('assets/imgs/2.jpg')}}" alt="Generic placeholder image">
                                                    <div class="media-body mt-1 text-left">
                                                        <h5 class="mt-0 main-name">Username</h5>
                                                        <p class="review">I liked this document alot!</p>
                                                        <div class="time last-seen">2 min ago </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</div>
							</div>
						</div> {{--
						<button class="font-weight-bold">Go Back</button> --}} </div>
				</div>
			</div>
		</div>
	</div>
</div>

         <div class="tips mt-1">
                <ul class="nav nav-tabs" id="myTab12" role="tablist">
                  <li class="nav-item w-50">
                    <!-- <a class="nav-link active font-md font-weight-bold text-center" id="posts-tab12" data-toggle="tab" href="#Posts" role="tab" aria-controls="Posts" aria-selected="true">Posts</a> -->
                    <a class="nav-link active font-md font-weight-bold text-center" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Post</a>

                </li>
                  <li class="nav-item w-50">
                    <!-- <a class="nav-link font-md font-weight-bold text-center" id="tips-tab12" data-toggle="tab" href="#My-Tips" role="tab" aria-controls="My-Tips" aria-selected="false">My Tips</a> -->
                    <a class="nav-link font-md font-weight-bold text-center" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">My Tips</a>

                </li>
                </ul>
                <div class="tab-content profile-posts-align" id="myTabContent12">
                  <!-- <div class="tab-pane fade show active" id="Posts" role="tabpanel" aria-labelledby="posts-tab12"> -->
                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                      @if(sizeof($is_user->posts) > 0)
                            <div class="p-3 row">
                                @foreach($is_user->posts as $ip=> $ip_row)
                                @if($ip_row->file_type == 'image')
                                <div class="col-md-4">
                                    <img class="posts-image" @if($ip_row->file ) src="{{asset('storage/'.$ip_row->file)}}" @else src="{{asset('storage/assets/imgs/no_image.png')}}" @endif alt="Generic placeholder image">
                                </div>
                                 @elseif($ip_row->file_type == 'video')
                                 <div class="col-md-4">
                                    <video class="posts-image" controls style="vertical-align: middle;">
                                        <source src="{{asset('storage/'.$ip_row->file)}}">
                                    </video>
                                 </div>
                                @else
                                <div class="col-md-4">
                                    <img class=" posts-image" src="{{asset('storage/assets/imgs/no_image.png')}}" alt="Generic placeholder image">
                                </div>
                                @endif
                                @endforeach
                            </div>
                          @endif
                    </div>
                  <!-- <div class="tab-pane fade" id="My-Tips" role="tabpanel" aria-labelledby="tips-tab12"> -->
                  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                  <div class="p-3 row">
                      @if($is_user->stories)
                        @foreach($is_user->stories as $is => $is_row)

                              @if($is_row->post_id != null || $is_row->post_id != Null)

                                  @if($is_row->post->file_type == 'image')
                                  <div class="col-md-4">
                                      <img class=" posts-image" src="{{asset('storage/'.$is_row->post->file)}}" alt="Generic placeholder image">
                                  </div>
                                  @elseif($is_row->post->file_type == 'video')
                                  <div class="col-md-4">
                                      <video class="posts-image" controls style="vertical-align: middle;">
                                          <source src="{{asset('storage/'.$is_row->post->file)}}">
                                      </video>
                                  </div>
                                  @else
                                  <div class="col-md-4">
                                      <img class=" posts-image" src="{{asset('storage/assets/imgs/no_image.png')}}" alt="Generic placeholder image">
                                 </div>
                                  @endif
                              @else
                                  @if($is_row->file)
                                      @if($is_row->file_type == 'image')
                                      <div class="col-md-4">
                                          <img class="posts-image" @if($is_row->file ) src="{{asset('storage/'.$is_row->file)}}" @else src="{{asset('storage/assets/imgs/no_image.png')}}" @endif alt="Generic placeholder image">
                                      </div>
                                      @elseif($ip_row->file_type == 'video')
                                      <div class="col-md-4">
                                          <video class="posts-image" controls style="vertical-align: middle;">
                                              <source src="{{asset('storage/'.$is_row->file)}}">
                                          </video>
                                      </div>
                                      @else
                                      <div class="col-md-4">
                                          <img class=" posts-image" src="{{asset('storage/assets/imgs/no_image.png')}}" alt="Generic placeholder image">
                                      </div>
                                      @endif
                                  @endif

                              @endif
                            @endforeach
                        @endif

                        </div>
                </div>
        </div>

        </div>
<div class="position-toggle">
    <div class="options shadow-xss rounded-xxl" id="toggle-section">
          <a class="dropdown-item update-change" href="#">{{@$is_user->name}}</a>

           <!-- view profile -->

                            <a class="dropdown-item changes" href="#" data-toggle="modal" data-target="#exampleModal6">View Profile</a>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                            <div class="modal-dialog  modal-dialog-centered" role="document">
                                <div class="modal-content">
                                <form action="{{route('updateUserProfile')}}" method="POST" enctype="multipart/form-data" id="submitUpdateProfileForm">
                                    @csrf
                                    <div class="modal-body w-100">
                                        <div class="">
                                            <button type="button" class="close closing-popup" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <h5 class="modal-title6">Profile</h5>
                                        <div class="d-block">
                                        <input type="hidden" name="auth_user_uuid" value="{{$is_user->uuid}}">
                                        <div class="avatar-upload">
                                            @if ( $is_user->uuid === auth()->user()->uuid )

                                        <div class="avatar-edit">
                                        <input type='file' id="imageUpload-1" name="file" accept=".png, .jpg, .jpeg" />
                                        <label for="imageUpload-1"></label>
                                        </div>
                                        @endif

                                        <div class="avatar-preview">
                                        <div id="imagePreview-1" style="background-image: url('{{asset('storage/'.$is_user->image)}}');">
                                        </div>
                                        </div>
                                        </div>
                                        <div class="enter-name">
                                        <label for="exampleFormControlInput1" class="form-label person-name">Name</label>
                                        <input type="text" class="form-control input-name m-0" name="name"  {{$is_user->uuid === auth()->user()->uuid ? "" :"disabled"}} id="profile_name" value="{{$is_user->name}}" placeholder="Name">
                                        </div>
                                        <div class="enter-name">
                                        <label for="exampleFormControlInput2" class="form-label person-name">Bio</label>
                                        <input type="text" class="form-control input-name m-0" name="bio"  {{$is_user->uuid === auth()->user()->uuid ? "" :"disabled"}} id="profile_bio" value="{{$is_user->bio}}" >
                                        </div>
                                        <div class="enter-name">
                                        <label for="exampleFormControlInput3" class="form-label person-name">Contact</label>
                                        <input type="text" class="form-control input-name m-0" name="contact_no"  {{$is_user->uuid === auth()->user()->uuid ? "" :"disabled"}} id="profile_contact_no" value="{{$is_user->contact_no}}"  placeholder="000-000-0000">
                                        </div>
                                        <div class="enter-name">
                                        <label for="exampleFormControlInput4" class="form-label person-name">Email</label>
                                        <input type="email" class="form-control input-name m-0" {{$is_user->uuid === auth()->user()->uuid ? "" :"disabled"}} name="email" id="profile_email" value="{{$is_user->email}}"  placeholder="example@example.com">
                                        </div>
                                        @if($is_user->uuid === auth()->user()->uuid)
                                        <button type="submit" class="btn btn-primary Submit-Document mt-2 mb-0" id="btn-update-profile1">Profile Update</button>
                                        @endif
                                    </div>

                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>

           <!-- about -->
           <a class="dropdown-item changes" href="#" data-toggle="modal" data-target="#exampleModal8">About</a>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog  modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body w-100">
                                            <div class="">
                                                <button type="button" class="close closing-popup" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <h5 class="modal-title6">About</h5>
                                            <div class="about-form d-block">
                                            <a class="dropdown-item changes" href="#">Data Policy</a>
                                            <a class="dropdown-item changes" href="#">Term of Use</a>
                                            <a class="dropdown-item changes" href="#">Open Source Libraries</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
          <a class="dropdown-item changes" href="{{url('get-user-penpals')}}">Pen Pals</a>
            <!-- change password -->

            <a class="dropdown-item changes" href="#" data-toggle="modal" data-target="#exampleModal7">Change password</a>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal7" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                <div class="modal-dialog  modal-dialog-centered" role="document">
                                    <div class="modal-content">

                                        <div class="modal-body w-100">

                                            <div class="">
                                                <button type="button" class="close closing-popup" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <h5 class="modal-title6">Change Paswword</h5>
                                            <div class="password-form d-block">
                                           <div class="">
                                            <input type="email" class="form-control intput-email" id="exampleFormControlInput1" placeholder="Old Password">
                                             </div>
                                             <div class="">
                                            <input type="email" class="form-control intput-email" id="exampleFormControlInput1" placeholder="New Password">
                                             </div>
                                             <div class="">
                                            <input type="email" class="form-control intput-email" id="exampleFormControlInput1" placeholder="Confirm Password">
                                             </div>
                                            <center><button type="button" class="btn btn-primary Submit-Done mt-2 mb-0">Done</button></center>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
{{-->>>>>>> c29e1e5d0d839a723d2eff8233cfe1e8c7fcaef2--}}
          {{--<a class="dropdown-item changes" href="#">My favorites</a>--}}
           <!-- about -->
           <a class="dropdown-item changes" href="#" data-toggle="modal" data-target=".bd-example-modal-sm">Refer Pen Pal</a>

                            <!-- Modal -->
                            <div class="modal fade bd-example-modal-sm" id="exampleModal9" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog  modal-dialog-centered modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body w-100">
                                            <div class="">
                                                <button type="button" class="close closing-popup" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <h5 class="modal-title6 text-center">Refer your Penpal</h5>
                                            <p class="modal-para text-center">Use this Reference code to invite friend to this application</p>
                                            <button type="button" class="btn btn-primary Submit-Document mt-4 mb-0">Refer Pen Pal</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
          <a class="dropdown-item changes" href="{{route('friendRequests')}}">PenPal Requests </a>
          <a class="dropdown-item logout-btn" href="{{ route('logout') }}"
             onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
          <button type="button" class="btn btn-primary signout-btn"><i class="fas fa-sign-out-alt"></i>Logout</button>
          </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
    </div>
    </div>
    <!-- ***** User profile Starts ***** -->
    <div class="w-56 d-none">
        <div class="card w-100 border-0 bg shadow-xss rounded-xxl">
            <div class="card-body rounded-xxl"><img src="{{asset('assets/imgs/flower1.jpg')}}" class="bg-img" alt="image"></div>
            <div class="card-body position-relative pt-0">
                <figure class="avatar position-absolute profile w100"><img src="{{asset('storage/'.$is_user->image)}}" alt="image" class=" p-1 bg-white rounded-circle w-100"></figure>
                <div class="row">
                    <div class="col-8">
                        <h4 class=" fw-700 font-sm pl-6">{{@$is_user->name}}<span class="fw-500 font-xssss text-grey-500 mb-3 d-block">{{@$is_user->email}}</span></h4>
                    </div>
                    <div class="col-4">
                        <div class="d-flex align-items-center mb-3 writers_container">
                            @if($is_user->uuid != \Illuminate\Support\Facades\Auth::user()->uuid)

                                @if($is_user->penpal_status == 'Friends')
                                    <a href="#" class="bg-danger pt-0 mt-0 p-3 rounded-3 text-white font-xsssss fw-700 ls-3">
                                        {{@$is_user->penpal_status}}
                                    </a>
                                @endif
                                @if($is_user->penpal_status == 'Request Sent')
                                    <a href="#" class="bg-secondary pt-0 mt-0 p-3 rounded-3 text-white font-xsssss fw-700 ls-3 ">
                                        {{@$is_user->penpal_status}}
                                    </a>
                                @endif
                                @if($is_user->penpal_status == 'Confirm or Cancel')
                                        <a href="javascript:void(0)" class="bg-success pt-0 mt-0 p-3 rounded-3 text-white font-xsssss fw-700 ls-3 mr-1 update-request-btn "  receiver_user_id="{{@$is_user->uuid}}" request_for="Accept">Accept</a>
                                        <a href="javascript:void(0)" class="bg-secondary pt-0 mt-0 p-3 rounded-3 text-white font-xsssss fw-700 ls-3 update-request-btn"   receiver_user_id="{{@$is_user->uuid}}" request_for="Cancel">Cancel</a>
                                @endif
                                @if($is_user->penpal_status == 'Add Friend')
                                    <a href="javascript:void(0)" class="bg-success pt-0 mt-0 p-3 rounded-3 text-white font-xsssss fw-700 ls-3 add_writer_btn" receiver_user_id="{{@$is_user->uuid}}" request_for="Request Sent">
                                        {{@$is_user->penpal_status}}
                                    </a>
                                @endif
                            <a href="#" class="bg-greylight btn-round-lg ms-2 rounded-3 text-grey-700"><i class="far fa-envelope font-md"></i></a>
                            <a href="#" class="bg-greylight btn-round-lg ms-2 rounded-3 text-grey-700"><i class="fas fa-ellipsis-h font-md tetx-dark"></i></a>
                        @endif
                        </div>

                    </div>
                </div>
            </div>
            {{--            <div class="card-body d-block w-100 shadow-none mb-0 p-0 border-top-xs">--}}
            {{--                <ul class="nav nav-tabs h55 d-flex border-bottom-0 ps-4">--}}
            {{--                    <li class="active list-inline-item me-5"><a class="fw-700 font-xssss text-grey-500 pt-3 pb-3 ls-1 d-inline-block active">About</a></li>--}}
            {{--                    <li class="list-inline-item me-5"><a class="fw-700 font-xssss text-grey-500 pt-3 pb-3 ls-1 d-inline-block">Membership</a></li>--}}
            {{--                </ul>--}}
            {{--            </div>--}}
            </div>
        <!-- ***** user profile ends ***** -->

        <!-- ***** About us Starts ***** -->
        <div class="row mt-3">
            <div class="col-4 pr-0">
                <div class="card shadow-xss rounded-xxl border-0 mb-3">
                    <div class="card-body d-block p-3">
                        <h4 class="fw-700 mb-3 font-xsss text-grey-900">About</h4>
                        <p class="fw-500 text-grey-500 lh-24 font-xssss mb-0">
                            @if($is_user->bio)
                                {{@$is_user->bio}}
                            @else
                                No Bio
                            @endif
                        </p>
                    </div>
                    <div class="card-body border-top-xs d-flex font-size">
                        <i class="fas fa-lock text-grey-500 me-3"></i>
                        <h4 class="fw-700 text-grey-900 font-xssss mt-0">Favourite Genres
                            <span class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">
                                @if(@$is_user->favorite_genres)
                                {{@$is_user->favorite_genres}}
                                @else
                                No Genres Selected
                                @endif
                            </span>
                        </h4>
                    </div>

        {{--                    <div class="card-body d-flex pt-0">--}}
        {{--                        <i class="far fa-eye text-grey-500 me-3 font-size"></i>--}}
        {{--                        <h4 class="fw-700 text-grey-900 font-xssss mt-0">Visble <span class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">Anyone can find you</span></h4>--}}
        {{--                    </div>--}}
        {{--                    <div class="card-body d-flex pt-0">--}}
        {{--                        <i class="fas fa-map-marker-alt text-grey-500 me-3 font-size"></i>--}}
        {{--                        <h4 class="fw-700 text-grey-900 font-xssss mt-1">Flodia, Austia </h4>--}}
        {{--                    </div>--}}
        {{--                    <div class="card-body d-flex pt-0">--}}
        {{--                        <i class="fas fa-users text-grey-500 me-3 font-size"></i>--}}
        {{--                        <h4 class="fw-700 text-grey-900 font-xssss mt-1">Genarel Group</h4>--}}
        {{--                    </div>--}}
                        </div>
                    </div>
                    <!-- ***** About us ends ***** -->

                    <div class="col-8 w-100 ml-0">
                        @if($is_user->uuid == \Illuminate\Support\Facades\Auth::user()->uuid)
                        <div class="card w-100 shadow-xss rounded-xxl border-0 ps-4 pt-3 pe-4 pb-3">
                            <div class="card-body p-0 mb-2 mt-0 position-relative">
                                <figure class="avatar position-absolute ml-2 mt-2 top-5"><img src="{{asset('storage/'.$is_user->image)}}" alt="image" class="shadow-sm rounded-circle w30"></figure>
                                <textarea name="message" class="h100 bor-0 mt-1 w-100 rounded-xxl p-2 pl-5 font-xssss text-grey-500 fw-500 border-light-md theme-dark-bg" cols="30" rows="10" placeholder="What's on your mind?"></textarea>
                            </div>
                            <div class="card-body d-flex p-0 mt-0 mb-4">
                                <a href="#" class="p-rel d-flex align-items-center font-xssss fw-600 ls-1 text-grey-700 text-dark pe-4" ><i class="fas fa-video feather-video font-md text-danger me-2"></i><span class="d-none-xs">Live Video</span></a><input class="upload-video" type="file">
                                <a href="#" class="p-rel d-flex align-items-center font-xssss fw-600 ls-1 text-grey-700 text-dark pe-4"><i class="far fa-file-image font-md text-success feather-image me-2"></i><span class="d-none-xs">Photo/Video</span></a><input class="upload-video" type="file">
                            </div>
                        </div>
                        @endif
                        @if(sizeof(@$is_user->posts) > 0 && $is_user->penpal_status == 'Friends' ||  $is_user->uuid == \Illuminate\Support\Facades\Auth::user()->uuid)
                        <input type="hidden" name="user_posts" id="userPostsArr" value="{{json_encode($is_user->posts)}}">
                        <input type="hidden" name="user_id" id="userId" value="{{\Illuminate\Support\Facades\Auth::user()->uuid}}">
                            @foreach(@$is_user->posts as $p => $p_row)
                            <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-0 mt-2 display_posts">
                                <div class="card-body p-0 d-flex">
                                    <figure class="avatar me-3 m-0"><img src="{{asset('storage/'.@$is_user->image)}}" alt="image" class="shadow-sm rounded-circle w45"></figure>
                                    <h4 class="fw-700 text-grey-900 font-xssss mt-1 ml-2">{{@$is_user->name}} <span class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">2 hour ago</span></h4>
            {{--                        <a href="#" class="ms-auto"><i class="fas fa-ellipsis-h text-grey-900 btn-round-md bg-greylight font-xss"></i></a>--}}
                                </div>
                                <div class="card-body p-0 me-lg-5">
                                    <p class="fw-500 text-grey-500 lh-26 font-xssss w-100 show-read-more">
                                        @if(@$p_row->description)
                                        {{@$p_row->description}}
                                        @endif
                                        @if(@$p_row->file_type == 'image')

                                                <img src="{{asset('storage/'.$p_row->file)}}" class="float-right w-100" alt="">
                                        @endif
                                        @if(@$p_row->file_type == 'video')
                                                <video autoplay="" loop="" class="float-right w-100" controls>
                                                    <source src="{{asset('storage/'.$p_row->file)}}" type="video/mp4">
                                                </video>
                                        @endif
            {{--                            <a href="#" class="fw-600 text-primary ms-2 d-inline-block ml-1">See more</a>--}}
                                    </p>
                                </div>
                                <div class="card-body d-flex p-0">
                                    <a href="javascript:void(0)" class="d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-2 like_box" id="postLikeCount">
        {{--                                <i class="far fa-thumbs-up text-white bg-primary-gradiant me-1 btn-round-xs font-xss"></i>--}}
                                        @if(@$p_row->is_like)
                                        <i class="far fa-heart text-white bg-red-gradiant me-2 btn-round-xs font-xss post_like" postIndex="{{$p}}" ></i>
                                        @else
                                        <i class="far fa-heart text-white bg-primary-gradiant me-2 btn-round-xs font-xss post_like" postIndex="{{$p}}"></i>
                                        @endif
                                        <span class="like_count_val">{{$p_row->likes_count}} </span>   Like
                                    </a>
                                    <a href="#" class="d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss">
                                        <i class="far fa-comment text-dark text-grey-900 btn-round-sm"></i>
                                        <span class="d-none-xss">
                                            22 Comment
                                        </span>
                                    </a>
                                    <a href="#" class="ms-auto d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss"><i class="fas fa-share-alt text-grey-900 text-dark btn-round-sm"></i><span class="d-none-xs">Share</span></a>
                                </div>
                            </div>
                            @endforeach
                            @else
                                <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-0 mt-2 display_posts">
                                    <div class="card-body p-0 d-flex">
                                    No Posts Available
                                    </div>
                                </div>
                        @endif
        {{--                <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mt-2">--}}
        {{--                    <div class="card-body p-0 d-flex">--}}
        {{--                        <figure class="avatar me-3 m-0"><img src="assets/imgs/2.jpg" alt="image" class="shadow-sm rounded-circle w45"></figure>--}}
        {{--                        <h4 class="fw-700 text-grey-900 font-xssss mt-1 ml-2">Anthony Daugloi  <span class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">2 hour ago</span></h4>--}}
        {{--                        <a href="#" class="ms-auto"><i class="fas fa-ellipsis-h text-grey-900 btn-round-md bg-greylight font-xss"></i></a>--}}
        {{--                    </div>--}}
        {{--                    <div class="card-body p-0 mb-3 mt-2 rounded-3 overflow-hidden">--}}
        {{--                        <video autoplay="" loop="" class="float-right w-100" controls>--}}
        {{--                            <source src="assets/vedio/1.mp4" type="video/mp4">--}}
        {{--                        </video>--}}
        {{--                    </div>--}}
        {{--                    <div class="card-body p-0 me-lg-5">--}}
        {{--                        <p class="fw-500 text-grey-500 lh-26 font-xssss w-100">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nulla dolor, ornare at commodo non, feugiat non nisi. Phasellus faucibus mollis pharetra. Proin blandit ac massa sed rhoncus <a href="#" class="fw-600 text-primary ms-2 d-inline-block ml-1">See more</a></p>--}}
        {{--                    </div>--}}
        {{--                    <div class="card-body d-flex p-0">--}}
        {{--                        <a href="#" class="d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-2"><i class="far fa-thumbs-up text-white bg-primary-gradiant me-1 btn-round-xs font-xss"></i><i class="far fa-heart text-white bg-red-gradiant me-2 btn-round-xs font-xss"></i>2.8K Like</a>--}}
        {{--                        <a href="#" class="d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss"><i class="far fa-comment text-dark text-grey-900 btn-round-sm"></i><span class="d-none-xss">22 Comment</span></a>--}}
        {{--                        <a href="#" class="ms-auto d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss"><i class="fas fa-share-alt text-grey-900 text-dark btn-round-sm"></i><span class="d-none-xs">Share</span></a>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--                <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-0 mt-2">--}}
        {{--                    <div class="card-body p-0 d-flex">--}}
        {{--                        <figure class="avatar me-3 m-0"><img src="assets/imgs/2.jpg" alt="image" class="shadow-sm rounded-circle w45"></figure>--}}
        {{--                        <h4 class="fw-700 text-grey-900 font-xssss mt-1 ml-2">Anthony Daugloi <span class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">2 hour ago</span></h4>--}}
        {{--                        <a href="#" class="ms-auto"><i class="fas fa-ellipsis-h text-grey-900 btn-round-md bg-greylight font-xss"></i></a>--}}
        {{--                    </div>--}}
        {{--                    <div class="card-body p-0 me-lg-5">--}}
        {{--                        <p class="fw-500 text-grey-500 lh-26 font-xssss w-100">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi nulla dolor, ornare at commodo non, feugiat non nisi. Phasellus faucibus mollis pharetra. Proin blandit ac massa sed rhoncus <a href="#" class="fw-600 text-primary ms-2 d-inline-block ml-1">See more</a></p>--}}
        {{--                    </div>--}}
        {{--                    <div class="card-body d-block p-0 mb-3">--}}
        {{--                        <div class="row ps-2 pe-2 mb-5">--}}
        {{--                            <div class="col-xs-6 col-sm-6 p-1"><a href="assets/imgs/building1.jpg" data-lightbox="roadtri"><img src="assets/imgs/building1.jpg" class="img2 rounded-3 w-100" alt="image"></a></div>--}}
        {{--                            <div class="col-xs-6 col-sm-6 p-1"><a href= "assets/imgs/building1.jpg"><img src="assets/imgs/building2.jpg" class="img3 rounded-3 w-100" alt="image"></a></div>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                    <div class="card-body d-flex p-0">--}}
        {{--                        <a href="#" class="d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-2"><i class="far fa-thumbs-up text-white bg-primary-gradiant me-1 btn-round-xs font-xss"></i><i class="far fa-heart text-white bg-red-gradiant me-2 btn-round-xs font-xss"></i>2.8K Like</a>--}}
        {{--                        <a href="#" class="d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss"><i class="far fa-comment text-dark text-grey-900 btn-round-sm"></i><span class="d-none-xss">22 Comment</span></a>--}}
        {{--                        <a href="#" class="ms-auto d-flex align-items-center fw-600 text-grey-900 text-dark font-xssss"><i class="fas fa-share-alt text-grey-900 text-dark btn-round-sm"></i><span class="d-none-xs">Share</span></a>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
            </div>
        </div>

    </div>

@endsection

@push('js')
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>



<script>
    get_highlight_comments();
    function get_highlight_comments(){
        var hid = $("#highlight_id").val();
        var _token   = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: '/get_highlight_data',
            data: {
                _token: _token,
                hid: hid
            },
            dataType: 'json',
            success: function (data) {
                if (data.success == true){

                    $('#highlight_modal').modal('show')
                    var highlight_data = data.data;
                    $("#highlight_id").val(highlight_data.uuid)
                    $("#highlight_image_anc").attr('href',highlight_data.file ? '{{asset("storage")}}'+'/' + highlight_data.file : "javascript:void(0)" )
                    $("#highlight_image_anc").find('img').attr('src', highlight_data.file_image ? '{{asset("storage")}}'+'/' + highlight_data.file_image : '{{asset("assets/imgs/img-bg.png")}}')
                    $(".display_praise_comments").html('')
                    $(".display_critique_comments").html('')
                    if (highlight_data.praise_comments.length > 0){
                        $.map(highlight_data.praise_comments, function(item, index){
                            $(".display_praise_comments").append(`
                                        <div class="media"> <img class="mr-3 single-user-name" src="${item.user.image ? '{{asset("storage")}}'+'/' +item.user.image: '{{asset("assets/imgs/user_avatar.png")}}'}" alt="Generic placeholder image">
                                            <div class="media-body mt-1 text-left">
                                                <h5 class="mt-0 main-name">${item.user.name}</h5>
                                                <p class="review">${item.comment}</p>
                                                <div class="time last-seen">${item.comment_created_at_formatted}</div>
                                            </div>
                                        </div>`);

                        })
                    }
                    if (highlight_data.critique_comments.length > 0){
                        $.map(highlight_data.critique_comments, function(item, index){

                            $(".display_critique_comments").append(`
                                        <div class="media"> <img class="mr-3 single-user-name" src="${item.user.image ? '{{asset("storage")}}'+'/' +item.user.image: '{{asset("assets/imgs/user_avatar.png")}}'}" alt="Generic placeholder image">
                                            <div class="media-body mt-1 text-left">
                                                <h5 class="mt-0 main-name">${item.user.name}</h5>
                                                <p class="review">${item.comment}</p>
                                                <div class="time last-seen">${item.comment_created_at_formatted}</div>
                                            </div>
                                        </div>`);
                        })
                    }
                } else{

                }
            }
        })
    }
    $(document).on('click','.highlight_comment_tab', function (e){
        $(".highlight_comment_tab").removeClass('active')
        $(this).addClass('active')
    })
    $(document).on('click','#highlight_comment_submit', function (e){
        var comment = $("#highlight_comment").val();
   var comment_type = $(".highlight_comment_tab.active").text();
   var highlight_id = $("#highlight_id").val();
        var _token   = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: 'POST',
            url: '/save-highlight-comment',
            data: {
                _token: _token,
                highlight_id: highlight_id,
                comment: comment,
                comment_type: comment_type
            },
            dataType: 'json',
            success: function (data) {
                if (data.success == true){
                    get_highlight_comments()

                } else{
alert(data.message)
                }
                $("#highlight_comment").val('')
            }
        })

    })
    $(document).on('click','.trigger_highlight', function (e){
$("#highlight_id").val($(this).attr('hid'));
      get_highlight_comments()

    })
    // Loaded via <script> tag, create shortcut to access PDF.js exports.
    var pdfjsLib = window['pdfjs-dist/build/pdf'];
    // The workerSrc property shall be specified.
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

    $("#myPdf").on("change", function(e){
	var file = e.target.files[0]
	if(file.type == "application/pdf"){
		var fileReader = new FileReader();
		fileReader.onload = function() {
			var pdfData = new Uint8Array(this.result);
			// Using DocumentInitParameters object to load binary data.
			var loadingTask = pdfjsLib.getDocument({data: pdfData});
			loadingTask.promise.then(function(pdf) {
			  console.log('PDF loaded');

			  // Fetch the first page
			  var pageNumber = 1;
			  pdf.getPage(pageNumber).then(function(page) {
				console.log('Page loaded');

				var scale = 1.5;
				var viewport = page.getViewport({scale: scale});

				// Prepare canvas using PDF page dimensions
				var canvas = $("#pdfViewer")[0];
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;

				// Render PDF page into canvas context
				var renderContext = {
				  canvasContext: context,
				  viewport: viewport
				};
				var renderTask = page.render(renderContext);
				renderTask.promise.then(function () {
				  console.log('Page rendered');
				});
			  });
			}, function (reason) {
			  // PDF loading error
			  console.error(reason);
			});
		};
		fileReader.readAsArrayBuffer(file);
	}
    });
</script>
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
<script>
    $(document).ready(function(){

var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
removeItemButton: true,
maxItemCount:5,
searchResultLimit:5,
renderChoiceLimit:5
});


});
    </script>



<script>
    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
    }
    function readURLProfile(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview-1').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview-1').hide();
            $('#imagePreview-1').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
    }
    $("#imageUpload").change(function() {
        readURL(this);
    });
    $("#imageUpload-1").change(function() {
        readURLProfile(this);
    });
</script>
<script>
        $(document).ready(function(){
            $('#toggle-click').click(function(){

                $('#toggle-section').toggle()

            });
        });

    </script>

    <script type="text/javascript">
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
        });
    </script>
    <script>
        $(document).on('click','.add_writer_btn', function (e){
            e.preventDefault();
            var request_for = $(this).attr('request_for');
            var receiver_id = $(this).attr('receiver_user_id');
            var  writers_container = $('.writers_container').find('.add_writer_btn');

            // var current_pointer = $(writers_container).find('.add_writer_btn').replaceWith(html_body);
            console.log(writers_container);

            var formData = {
                receiver_id : receiver_id,
                request_for: request_for,
                _token:$('meta[name="csrf-token"]').attr('content')
            };
            $.ajax({
                type: "POST",
                url: '/add-penpal',
                data: formData,
                success: function (data) {
                    var res = JSON.parse(data);
                    if (res.success == true){
                        $( writers_container).remove();
                        console.log(res);
                        var html_body = '<a href="javascript:void(0)" class="bg-secondary pt-0 mt-0 p-3 rounded-3 text-white font-xsssss fw-700 ls-3 add_writer_btn" receiver_user_id="'+receiver_id+'" request_for="Request Sent">'+res.data+'</a>';
                        $('.writers_container').prepend(html_body);
                    }



                }
            });

        });
    </script>
    <script>
        $(document).on('click','.update-request-btn',function(e){
           e.preventDefault();
           var receiver_id =  $(this).attr('receiver_user_id')
           var request_for = $(this).attr('request_for')
            var formData = {
                receiver_id : receiver_id,
                request_for: request_for,
                _token:$('meta[name="csrf-token"]').attr('content')
            };
            $.ajax({
                type: "POST",
                url: '/update-penpal-status',
                data: formData,
                success: function (data) {
                    var res = JSON.parse(data);
                    if (res.success == true){
                        // $( writers_container).remove();
                        console.log(res);
                        // var html_body = '<a href="javascript:void(0)" class="bg-secondary pt-0 mt-0 p-3 rounded-3 text-white font-xsssss fw-700 ls-3 add_writer_btn" receiver_user_id="'+receiver_id+'" request_for="Request Sent">'+res.data+'</a>';
                        // $('.writers_container').prepend(html_body);
                    }



                }
            });
        });

    </script>
<script type="text/javascript">
    $(document).on('click','#submitHighlightBtn', function (e){
        e.preventDefault();
        $('#submitHighlightForm').submit();
    })
    $("#add-penpal-form").on('submit',function(e){
        e.preventDefault();
            let formData = $(this).serialize();
            let url = $(this).attr('action');
            let elem = $(this);
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function (data) {
                    if (data.success == true){
                       document.location.reload();
                    }
                }
            });
    })
</script>
<script src="{{asset('assets/js/post.js')}}"></script>
@endpush
