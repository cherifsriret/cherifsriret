
@extends('layouts.main')
@section('title')
   Friend Requests
@endsection
@section('content')

<div class="w-40 mt-6 mx-auto pb-3 bg-white">
	<div class="">
		<div class="friend-request">
			<h5 class="modal-title11">Friend Request<i class="fas fa-user-plus"></i></h5>
			{{-- <button type="button" class="mr-3 px-2 close cross-button" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> --}}
		</div>
        <hr class="mt-0">
        @if(sizeof($get_requests) > 0)
            @foreach($get_requests as $request)
		<div class="request-list mt-2">
			<div class="media py-2"> <img class="mr-3 request-user" src="{{asset('storage/'.$request->user_detail->image)}}" alt="Generic placeholder image">
				<div class="media-body">
					<h5 class="mt-0 main-name">{{$request->user_detail->name}}</h5>
					<div class="time last-seen">{{$request->user_detail->favorite_genres}}</div>
					<div class="confirm-btn mb-1 mt-2">
						<button type="button" class="btn m-0 btn-primary  px-4" onclick="event.preventDefault();
                                                     document.getElementById('accept-request').submit();">Accept Request</button>
                        <form id="accept-request" action="{{ url('update-penpal-status') }}" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="status" value="accept">
                            <input type="hidden" name="other_user_id" value="{{$request->user_detail->uuid}}">
                        </form>
						<button type="button" class="btn m-0 btn-danger px-4 ml-2"
                                onclick="event.preventDefault();
                                                     document.getElementById('cancel-request').submit();">Cancel</button>
                        <form id="cancel-request" action="{{ url('update-penpal-status') }}" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="status" value="cancel">
                            <input type="hidden" name="other_user_id" value="{{$request->user_detail->uuid}}">
                        </form>
					</div>
				</div>
			</div>
		</div>
            @endforeach
@else
            <div class="col-md-12 text-center">
                <p>No requests yet</p>
            </div>
        @endif

	</div>
</div>


@endsection
@push('js')

@endpush
