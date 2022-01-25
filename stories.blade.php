
@extends('layouts.main')
@section('title')
   Add Story
@endsection
@section('content')


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







<div class="w-40 mt-5 mx-auto bg-white">
	<div class="px-3 shadow-xss">
		<div class="friend-request">
			<h3 class="modal-title11">Stories</h3>
			{{-- <button type="button" class="close cross-button" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> --}}
		</div>
		<div class="story-options m-1 tags_stories">
            <button type="button" class="btn story-write mr-2 my-2 trigger_genere active">Top</button>

        @if(sizeof($genres) > 0)
                @foreach($genres as $genere)
			<button type="button" class="btn story-write mr-2 my-2 trigger_genere">{{$genere->genres}}</button>
                @endforeach
            @endif
		</div>
        <div class="display_top">
            <h2 class="title-stories">Most Rated</h2>
            <div class="row mb-0 render_rated"></div>
            <h2 class="title-stories">Most Viewed</h2>
            <div class="row mb-0 render_viewed">

            </div>
        </div>
        <div style="display: none" class="display_others">
            <div class="row mb-0 render_highlights"></div>
        </div>
	</div>
</div>

        <!-- Modal -->
        <div class="modal fade exampleModal" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered view-pdf mb-0" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <div class="">
                            <button type="button" class="close closing-popup" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                        </div>
                        <div class="">
                            <div class="uploaded-pdf-view text-center  pt-3">
                                <h2>Rate this document</h2>
                                <div class="rating" style="">
                                    <input id="rating-5" type="radio" name="rating" value="5">
                                    <label for="rating-5"><i class="fas fa-3x fa-star"></i></label>
                                    <input id="rating-4" type="radio" name="rating" value="4" checked="">
                                    <label for="rating-4"><i class="fas fa-3x fa-star"></i></label>
                                    <input id="rating-3" type="radio" name="rating" value="3">
                                    <label for="rating-3"><i class="fas fa-3x fa-star"></i></label>
                                    <input id="rating-2" type="radio" name="rating" value="2">
                                    <label for="rating-2"><i class="fas fa-3x fa-star"></i></label>
                                    <input id="rating-1" type="radio" name="rating" value="1">
                                    <label for="rating-1"><i class="fas fa-3x fa-star"></i></label>
                                </div>
                                <input type="hidden" id="highlight_id" value="20f9d29f-95a3-4829-9115-48396fba37fc">


                                <a id="highlight_image_anc" href="http://writerstalkadmin.com/public/storage/uploads/highlights/highlight_docs_5417.pdf" target="_blank">
                                    <img src="http://writerstalkadmin.com/public/storage/uploads/highlights/highlight_img9011.jpeg">
                                </a>

                                <div class="mt-4 px-5">

                                    <div class="tips-type mt-1">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item w-50 "> <a class="nav-link font-md font-weight-bold text-center active show pros highlight_comment_tab" id="pros-tab" data-toggle="tab" href="#Posts" role="tab" aria-controls="Posts" aria-selected="true">Praise</a> </li>
                                            <li class="nav-item w-50 "> <a class="nav-link font-md font-weight-bold text-center cons highlight_comment_tab" id="cons-tab" data-toggle="tab" href="#My-Tips" role="tab" aria-controls="My-Tips" aria-selected="false">Critique</a> </li>
                                        </ul>
                                        <div class="add-comment-pdf px-3 pt-3 d-flex">
                                            <textarea name="" id="highlight_comment" cols="30" rows="1" value="Add comment here .." class="mb-0 mx-2"> </textarea>
                                            <button id="highlight_comment_submit"><i class="far fa-paper-plane"></i></button>
                                        </div>
                                        <div style="" class="chat-boxes pdf-coments mt-2 scroll-bar" id="thumb-scroll1">
                                            <div class="single-chat pb-1">
                                                <div class="tab-content profile-posts-align" id="myTabContent">
                                                    <div class="tab-pane fade active show display_praise_comments" id="Posts" role="tabpanel" aria-labelledby="posts-tab"></div>
                                                    <div class="tab-pane fade display_critique_comments" id="My-Tips" role="tabpanel" aria-labelledby="tips-tab"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



@endsection
@push('js')

    <script src="{{asset('assets/vendors/highlight.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" ></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" ></script>
<script>
        $(".trigger_genere").click(function () {
            $(".trigger_genere").removeClass('active');
            $(this).addClass('active');
            var genere = $("button.active").text()

            if (genere == "Top"){
                $(".display_top").show();
                $(".display_others").hide();
                get_user_highlights();
            } else{
                $(".display_top").hide();
                $(".display_others").show();
                get_genre_highlights()
            }
        })
        get_user_highlights();
        function get_user_highlights(){
            var _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                type: 'POST',
                url: '/get_user_hightlights',
                data: {
                    _token: _token
                },
                dataType: 'json',
                success: function (data) {
                    if (data.success == true){
                        $(".render_rated").html('');
                        $(".render_viewed").html('');
                        var most_rated = data.data.most_rated;
                        var most_viewed = data.data.most_viewed;
                        if (most_rated.length > 0){

                            $.map(most_rated, function(item, index){
                                var html = "<div class=\"shadow-xss bg-white p-2 mr-2 ml-1 mb-2 story-line trigger_highlight\" hid='"+item.uuid+"'>\n" +
                                    "<a class=\"dropdown-item p-0 change\" href=\"#\"><img src='http://writerstalkadmin.com/public/storage/"+item.file_image+"' class=\"viwed-images\">\n" +
                                    "<h5 class=\"titalname pb-0 mb-0 mt-1 text-center\">"+item.title+"</h5></a>\n" +
                                    "</div>";

                                $(".render_rated ").append(html);
                            })
                        }
                        if (most_viewed.length > 0){

                            $.map(most_viewed, function(item, index){
                                var html = "<div class=\"shadow-xss bg-white p-2 mr-2 ml-1 mb-2 story-line trigger_highlight\" hid='"+item.uuid+"'>\n" +
                                    "<a class=\"dropdown-item p-0 change\" href=\"#\"><img src='http://writerstalkadmin.com/public/storage/"+item.file_image+"' class=\"viwed-images\">\n" +
                                    "<h5 class=\"titalname pb-0 mb-0 mt-1 text-center\">"+item.title+"</h5></a>\n" +
                                    "</div>";

                                $(".render_viewed").append(html);
                            })
                        }
                    }
                }
            })
        }
        function get_genre_highlights(){
            var _token   = $('meta[name="csrf-token"]').attr('content');
            var genere = $("button.active").text()
            $.ajax({
                type: 'POST',
                url: '/get_genre_highlights',
                data: {
                    _token: _token,
                    hashtag_id: genere
                },
                dataType: 'json',
                success: function (data) {
                    if (data.success == true){
                        $(".render_highlights").html('');
                        var highlights_arr = data.data;
                        if (highlights_arr.length > 0){

                            $.map(highlights_arr, function(item, index){
                                var html = "<div class=\"shadow-xss bg-white p-2 mr-2 ml-1 mb-2 story-line trigger_highlight\" hid='"+item.uuid+"'>\n" +
                                    "<a class=\"dropdown-item p-0 change\" href=\"#\"><img src='http://writerstalkadmin.com/public/storage/"+item.file_image+"' class=\"viwed-images\">\n" +
                                    "<h5 class=\"titalname pb-0 mb-0 mt-1 text-center\">"+item.title+"</h5></a>\n" +
                                    "</div>";

                                $(".render_highlights").append(html);
                            })
                        }
                    }
                }
            })
        }
    //    modal js

        // get_highlight_comments();
        $(document).on('click','.trigger_highlight', function (e){
            //alert($(this).attr('hid'))
            $("#highlight_id").val($(this).attr('hid'));
            get_highlight_comments()

        })
        function get_highlight_comments(){

            var hid = $("#highlight_id").val();
            var _token   = $('meta[name="csrf-token"]').attr('content');
            console.log(">>>>");
            console.log(hid);
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
                        $("#highlight_image_anc").attr('href','http://writerstalkadmin.com/public/storage/'+highlight_data.file)
                        $("#highlight_image_anc").find('img').attr('src',"http://writerstalkadmin.com/public/storage/"+highlight_data.file_image)
                        $(".display_praise_comments").html('')
                        $(".display_critique_comments").html('')
                        if (highlight_data.praise_comments.length > 0){
                            $.map(highlight_data.praise_comments, function(item, index){
                                var praise_html = "<div class=\"media\"> <img class=\"mr-3 single-user-name\" src='http://writerstalkadmin.com/public/storage/"+item.user.image+"' alt=\"Generic placeholder image\">\n" +
                                    "                                                    <div class=\"media-body mt-1 text-left\">\n" +
                                    "                                                        <h5 class=\"mt-0 main-name\">"+item.user.name+"</h5>\n" +
                                    "                                                        <p class=\"review\">"+item.comment+"</p>\n" +
                                    "                                                        <div class=\"time last-seen\">"+item.comment_created_at_formatted+"</div>\n" +
                                    "                                                    </div>\n" +
                                    "                                                </div>";


                                $(".display_praise_comments").append(praise_html)
                            })
                        }
                        if (highlight_data.critique_comments.length > 0){
                            $.map(highlight_data.critique_comments, function(item, index){
                                var critique_html = "<div class=\"media\"> <img class=\"mr-3 single-user-name\" src='http://writerstalkadmin.com/public/storage/"+item.user.image+"' alt=\"Generic placeholder image\">\n" +
                                    "                                                    <div class=\"media-body mt-1 text-left\">\n" +
                                    "                                                        <h5 class=\"mt-0 main-name\">"+item.user.name+"</h5>\n" +
                                    "                                                        <p class=\"review\">"+item.comment+"</p>\n" +
                                    "                                                        <div class=\"time last-seen\">"+item.comment_created_at_formatted+"</div>\n" +
                                    "                                                    </div>\n" +
                                    "                                                </div>";


                                $(".display_critique_comments").append(critique_html)
                            })
                        }
                    } else{

                    }
                }
            })
        }
    //    comment Modal
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
    </script>
@endpush
