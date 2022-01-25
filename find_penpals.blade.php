@extends('layouts.main')
@section('title')
    Penpals
@endsection
@section('content')
    <div class="w-2">
    </div>
    <div class="w-23">
        <div class="row">
            <div class="col-xl-12">
                    <p class="text-danger" style="display: none"> </p>
                <div class="card shadow-xss border-0 p-3 mb-3">
                    <div class="card-body d-flex align-items-center p-0">
                        <h2 class="fw-700 mb-0 mt-0 font-md text-grey-900">Find Writers</h2>
                        <div class="search-form-2 ms-auto">
                            <i class="fas fa-search font-xss"></i>
                            <input type="text" class="form-control text-grey-500 mb-0 bg-greylight border-0" id="search_writer_input" placeholder="Search here.">
                        </div>
                        <a href="#" class="btn-round-md ms-2 bg-greylight rounded-3" id="search_writer_btn"><i class="fas fa-filter font-xss text-grey-500"></i></a>
                    </div>
                </div>
                <div class="ps-2 pe-2">
                    @if(sizeof($users) > 0)
                        <input type="hidden" name="writers_data_arr" id="writersDataArr" value="{{json_encode($users)}}">
                        <div id="writer_filtred" class="row">
                        @foreach($users as $u => $u_row)
                    <div class="col-md-3 col-sm-4 pe-2 ps-2">
                        <div class="card border-0 shadow-xss rounded-3 mb-3">
                            <div class="card-body ps-3 pe-3 pb-4 text-center writers-container" >
                                <a href="{{route('userProfile',['id'=>$u_row->uuid])}}">
                                <figure class="avatar ms-auto me-auto mb-0 "><img @if(@$u_row->image)src="{{asset('storage/'.@$u_row->image)}}"@else src="{{asset('assets/imgs/user_avatar.png')}}" @endif alt="image" class="h20 p-0 ml rounded-circle w65 shadow-xss"></figure>
                                <div class="clearfix"></div>
                                <h4 class="fw-700 font-xsss mt-3 mb-1">{{ucfirst(@$u_row->name)}} </h4>
                                </a>

                                    <p class="fw-500 font-xsssss text-grey-500 mt-0 mb-3">{{@$u_row->email}}</p>
                         <input type="hidden" name="receiver_user_id" id="receiverUserId" value="{{@$u_row->uuid}}">
                                    @if($u_row->status == 'Friends')
                                    <a href="javascript:void(0)" class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 rounded-xl bg-danger font-xsssss fw-700  text-white"  receiver_user_id="{{@$u_row->uuid}}" >{{@$u_row->status}}</a>
                                    @endif
                                    @if($u_row->status == 'Request Sent')
                                        <a href="javascript:void(0)" class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 rounded-xl bg-success font-xsssss fw-700  text-white"  receiver_user_id="{{@$u_row->uuid}}">{{@$u_row->status}}</a>
                                    @endif
                                    @if($u_row->status == 'Confirm or Cancel')
                                        <a href="javascript:void(0)" class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 rounded-xl bg-success font-xsssss fw-700  text-white"  receiver_user_id="{{@$u_row->uuid}}">Accept</a>
                                        <a href="javascript:void(0)" class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 rounded-xl bg-success font-xsssss fw-700  text-white"  receiver_user_id="{{@$u_row->uuid}}">Cancel</a>
                                    @endif
                                    @if($u_row->status == 'Add Friend')
                                        <a href="javascript:void(0)" class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 rounded-xl bg-success font-xsssss fw-700  text-white writers-action-btn " receiver_user_id="{{@$u_row->uuid}}" request_for="Request Sent">
                                            {{@$u_row->status}}
                                        </a>
                                    @endif
                            </div>
                        </div>
                    </div>
                        @endforeach
                    </div>

                    @endif

                </div>
            </div>
        </div>
    </div>
    <div class="w-5">
    </div>
@endsection
@push('js')
    <script type="text/javascript">
    $(document).on('click', '.writers-action-btn', function (e){
            let writers_arr = $('#writersDataArr').val();
            if (writers_arr){
                writers_arr = JSON.parse(writers_arr);
            }
            let request_for = $(this).attr('request_for');
            let receiver_id = $(this).attr('receiver_user_id');

            let formData = {
                receiver_id : receiver_id,
                request_for: request_for,
                _token:$('meta[name="csrf-token"]').attr('content')
            };
            let elem = $(this);
            $.ajax({
                type: "POST",
                url: '/add-penpal',
                data: formData,
                success: function (data) {
                    if (data.success == true){
                        var foundIndex = writers_arr.findIndex(x => x.uuid == receiver_id);
                        writers_arr[foundIndex].status = data.data;
                        $('#writersDataArr').val(JSON.stringify(writers_arr));
                        elem.text(data.data);
                    }
                }
            });
        })
        $(document).on('click', '#search_writer_btn', function (e){
            let writers_arr = $('#writersDataArr').val();
            if (writers_arr){
                writers_arr = JSON.parse(writers_arr);
            }
            let search = $("#search_writer_input").val().toLowerCase();
            let search_regex = new RegExp(".*"+search+".*", 'g');
            writers_arr = writers_arr.filter( writer=> writer.name.match(search_regex ) ||  writer.email.match(search_regex ))
            $('#writer_filtred').html("")
            $.map(writers_arr, function(item, index){
                let writer_content = ``;
                writer_content +=`
                <div class="col-md-3 col-sm-4 pe-2 ps-2">
                        <div class="card border-0 shadow-xss rounded-3 mb-3">
                            <div class="card-body ps-3 pe-3 pb-4 text-center writers-container" >
                                <a href="{{route('userProfile',['id'=>$u_row->uuid])}}">
                                <figure class="avatar ms-auto me-auto mb-0 ">
                                    <img src="${item.image ? '{{asset("storage")}}'+'/'+item.image: '{{asset("assets/imgs/user_avatar.png")}}'}" alt="image" class="h20 p-0 ml rounded-circle w65 shadow-xss"></figure>
                                <div class="clearfix"></div>
                                <h4 class="fw-700 font-xsss mt-3 mb-1">${item.name.charAt(0).toUpperCase() + item.name.slice(1)} </h4>
                                </a>
                                    <p class="fw-500 font-xsssss text-grey-500 mt-0 mb-3">${item.email}</p>
                                    <input type="hidden" name="receiver_user_id" id="receiverUserId" value="${item.uuid}">`
                                    if(item.status =='Friends')
                                    {
                                        writer_content +=` <a href="javascript:void(0)" class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 rounded-xl bg-danger font-xsssss fw-700  text-white"  receiver_user_id="${item.uuid}" >${item.status}</a>`;
                                    }
                                    if(item.status =='Request Sent')
                                    {
                                        writer_content +=` <a href="javascript:void(0)" class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 rounded-xl bg-success font-xsssss fw-700  text-white"  receiver_user_id="${item.uuid}">${item.status}</a>`;
                                    }
                                    if(item.status =='Confirm or Cancel')
                                    {
                                        writer_content +=`
                                        <a href="javascript:void(0)" class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 rounded-xl bg-success font-xsssss fw-700  text-white"  receiver_user_id="${item.uuid}">Accept</a>
                                        <a href="javascript:void(0)" class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 rounded-xl bg-success font-xsssss fw-700  text-white"  receiver_user_id="${item.uuid}">Cancel</a>`;
                                    }
                                    if(item.status =='Add Friend')
                                    {
                                        writer_content +=`<a href="javascript:void(0)" class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 rounded-xl bg-success font-xsssss fw-700  text-white writers-action-btn " receiver_user_id="${item.uuid}" request_for="Request Sent">${item.status}</a>`;

                                    }
                                    writer_content +=`
                            </div>
                        </div>
                    </div>
                `;
                $('#writer_filtred').append(writer_content);
                //bind onclick
                $( ".writers-action-btn" ).unbind( "click");
                $( ".writers-action-btn" ).bind( "click");
            })
        })
    </script>
@endpush
