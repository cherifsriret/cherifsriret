<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AdminPost;
use App\Models\AdminPostTag;
use App\Models\AdminTip;
use App\Models\Comment;
use App\Models\Genres;
use App\Models\Group;
use App\Models\GroupConversation;
use App\Models\GroupUser;
use App\Models\Highlight;
use App\Models\HighlightGenre;
use App\Models\HighlightRating;
use App\Models\Like;
use App\Models\Penpal;
use App\Models\Post;
use App\Models\Story;
use App\Models\Tag;
use App\Models\UserPayment;
use App\Models\User;
use App\Models\UserConnection;
use App\Models\UserProfileView;
use Carbon\Carbon;
use App\Models\UserMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class ProfileController extends Controller
{
    //
    public function user_profile(Request $request, $user_id = null){
        $auth_user = Auth::user();
        $genres = Genres::query()->orderBy('id','ASC')->get();

        if (!$user_id){

        }else{

            if ($auth_user->uuid == $user_id){


                $is_user = User::query()->with('posts')
                    ->with('highlights')
                    ->with('stories')
                    ->with('likes')
                    ->where('uuid', $user_id)->first();
                if ($is_user){
                    if (sizeof($is_user->stories) > 0){
                        foreach ($is_user->stories as $is => $is_row){
                            if ($is_row->post_id){
                                $post = Post::query()->where('uuid', $is_row->post_id)->first();
                                $is_row['post'] = $post;
                            }
                        }
                    }
                    if (sizeof(@$is_user->posts)> 0){
                        foreach (@$is_user->posts as $i => $i_row){
                            $like_exist =  Like::query()->where('user_id', $auth_user->uuid)
                                ->where('post_id', $i_row->uuid)->first();
                            $like_counts =  Like::query()->where('post_id', $i_row->uuid)->count();

                            $i_row['likes_count'] = $like_counts;
                            if ($like_exist){
                                $i_row['is_like'] = true;
                            }else{
                                $i_row['is_like'] = false;
                            }
                        }
                    }

                    $penpals = Penpal::query()->where('status','Accept')->whereIn('sender_id',[$auth_user->uuid,$user_id])
                        ->whereIn('receiver_id',[$auth_user->uuid,$user_id])->get();
                    if (sizeof($penpals)>0){
                        foreach ($penpals as $p => $p_row){
                            $toPick = $auth_user->uuid;
                            if ($toPick == $p_row->sender_id){
                                $toPick = $p_row->receiver_id;
                            }else{
                                $toPick = $p_row->sender_id;
                            }
                            $penpal_user = User::query()->where('uuid', $toPick)->first();
                            $penpals_user_count = Penpal::query()->where('status','Accept')->where('sender_id',$toPick)
                                ->orWhere('receiver_id',$toPick)->count();
                            $penpal_user['penpal_user_count'] = $penpals_user_count;
                            $p_row['penpal_user'] = $penpal_user;
                        }
                    }
                    $is_user['penpals_count'] = count($penpals);
                    $is_user['penpals'] = $penpals;

                    return view('profile',compact('is_user' ,'genres'));

                }
            }else{
                $profile_view_exist = UserProfileView::query()->where('user_id', $user_id)->first();
                if (!$profile_view_exist){

                    $add_profile_view = UserProfileView::create([
                        'uuid'=>Str::uuid(),
                        'ip_address'=>$request->ip(),
                        'user_id'=>$user_id,
                        'agent'=> $request->header('user-agent'),
                    ]);
                    if ($add_profile_view){
                        $user_to_update = User::query()->where('uuid', $user_id)->first();
                        $update_view = $user_to_update->views + 1;
                        $user_to_update->update([
                            'views'=>$update_view
                        ]);
                    }
                }

                $is_penpal = Penpal::query()
                    ->whereIn('sender_id', [$auth_user->uuid, $user_id])
                    ->whereIn('receiver_id', [$auth_user->uuid, $user_id])
                    ->first();

                if ($is_penpal){
                    if ($is_penpal->status == 'Accept'){

                        $is_user = User::query()->where('uuid', $user_id)
                            ->with('posts')
                            ->with('highlights')
                            ->with('stories')
                            ->with('likes')
                            ->first();
                        if ($is_user){
                            $is_user['penpal_status'] = 'Friends';
                            $penpal_count = Penpal::query()->where('status', 'Accept')->where('sender_id',$user_id)
                                ->orWhere('receiver_id', $user_id)->count();

                            $is_user['penpals_count'] = $penpal_count;
                        }

                    }else{
                        $is_user = User::query()->where('uuid', $user_id)
                            ->first();
                        if ($is_user){
                            $penpal_count = Penpal::query()->where('status', 'Accept')->where('sender_id',$user_id)
                                ->orWhere('receiver_id', $user_id)->count();
                            $is_user['penpals_count'] = $penpal_count;
                        }
                        $status = 'Request Sent';

                        if ($is_penpal->receiver_id == $auth_user->uuid) {
                            $status = 'Confirm or Cancel';
                        }
                        $is_user['penpal_status'] = $status;
//                        $status_button = $status;
//                        $resposne = [
//                            'success'=> true,
//                            'message'=> 'Record Found Success fully',
//                            'data'=> $is_user,
//                            'status'=>$status_button
//                        ];
                    }
                }else{
                    $is_user = User::query()->where('uuid', $user_id)
                        ->first();
                    if ($is_user){
                        $penpal_count = Penpal::query()->where('status', 'Accept')->where('sender_id',$user_id)
                            ->orWhere('receiver_id', $user_id)->count();
                        $is_user['penpals_count'] = $penpal_count;
                        $is_user['penpal_status'] = 'Add Friend';
                    }
//                    $resposne = [
//                        'success'=>true,
//                        'message'=>'Penpal not found',
//                        'data'=>$is_user,
//                        'status'=>'Add Friend'
//                    ];
                }
            }
        }
        return view('profile', compact('genres','is_user'));
    }
    public function user_profile_data(Request $request){

        $user = Auth::user();
        $user_id = $request->input('user_id');
        $validator = Validator::make($request->all(), [
            'user_id'=>'required',

        ]);
        $final_data = [];
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($user->uuid == $user_id){
            $is_user = User::query()->with('posts')
                ->with('highlights')
                ->with('stories')
                ->with('likes')
                ->where('uuid', $user_id)->first();
            if ($is_user){

                $penpals = Penpal::query()->where('status','Accept')->where('sender_id',$user_id)
                    ->orWhere('receiver_id',$user_id)->get();
                if (sizeof($penpals)>0){
                    foreach ($penpals as $p => $p_row){
                        $toPick = $user->uuid;
                        if ($toPick == $p_row->sender_id){
                            $toPick = $p_row->receiver_id;
                        }else{
                            $toPick = $p_row->sender_id;
                        }
                        $penpal_user = User::query()->where('uuid', $toPick)->first();
                        $penpals_user_count = Penpal::query()->where('status','Accept')->where('sender_id',$toPick)
                            ->orWhere('receiver_id',$toPick)->count();
                        $penpal_user['penpal_user_count'] = $penpals_user_count;
                        $p_row['penpal_user'] = $penpal_user;
                    }
                }
                $is_user['penpals_count'] = count($penpals);
                $is_user['penpals'] = $penpals;
                $resposne = [
                    'success'=> true,
                    'message'=> 'Record Found Success fully',
                    'data'=> $is_user,
                    'status'=>'profile'
                ];
            }
        }else{

            $profile_view_exist = UserProfileView::query()->where('user_id', $user_id)->first();
            if (!$profile_view_exist){

                $add_profile_view = UserProfileView::create([
                    'uuid'=>Str::uuid(),
                    'ip_address'=>$request->ip(),
                    'user_id'=>$user_id,
                    'agent'=> $request->header('user-agent'),
                ]);
                if ($add_profile_view){
                    $user_to_update = User::query()->where('uuid', $user_id)->first();
                    $update_view = $user_to_update->views + 1;
                    $user_to_update->update([
                        'views'=>$update_view
                    ]);
                }
            }

            $is_penpal = Penpal::query()
                ->whereIn('sender_id', [$user->uuid, $user_id])
                ->whereIn('receiver_id', [$user->uuid, $user_id])
                ->first();

            if ($is_penpal){
                if ($is_penpal->status == 'Accept'){

                    $is_user = User::query()->where('uuid', $user_id)
                        ->with('posts')
                        ->with('highlights')
                        ->with('stories')
                        ->with('likes')
                        ->first();
                    if ($is_user){
                        $penpal_count = Penpal::query()->where('status', 'Accept')->where('sender_id',$user_id)
                            ->orWhere('receiver_id', $user_id)->count();
                        $is_user['penpals_count'] = $penpal_count;
                    }
                    $resposne = [
                        'success'=> true,
                        'message'=> 'Record Found Success fully',
                        'data'=> $is_user,
                        'status' => 'Friends'
                    ];
                }else{
                    $is_user = User::query()->where('uuid', $user_id)
                        ->first();
                    if ($is_user){
                        $penpal_count = Penpal::query()->where('status', 'Accept')->where('sender_id',$user_id)
                            ->orWhere('receiver_id', $user_id)->count();
                        $is_user['penpals_count'] = $penpal_count;
                    }
                    $status = 'Request Sent';
                    if ($is_penpal->receiver_id == $user->uuid) {
                        $status = 'Accept';
                    }
                    $status_button = $status;
                    $resposne = [
                        'success'=> true,
                        'message'=> 'Record Found Success fully',
                        'data'=> $is_user,
                        'status'=>$status_button
                    ];
                }
            }else{
                $is_user = User::query()->where('uuid', $user_id)
                    ->first();
                if ($is_user){
                    $penpal_count = Penpal::query()->where('status', 'Accept')->where('sender_id',$user_id)
                        ->orWhere('receiver_id', $user_id)->count();
                    $is_user['penpals_count'] = $penpal_count;
                }
                $resposne = [
                    'success'=>true,
                    'message'=>'Penpal not found',
                    'data'=>$is_user,
                    'status'=>'Add Friend'
                ];
            }
        }
        return $resposne;
    }

    public function logout_user(){
       Auth::logout();
        return redirect('/login');
    }
    public function edit_user_profile(){
        $auth_user = Auth::user();
        if ($auth_user){
            $genres = Genres::query()->orderby('id','ASC')->get();
            return view('edit_profile',compact('auth_user', 'genres'));
        }else{
            return redirect('login');
        }
    }
    public function update_user_profile(Request $request){
        $uuid = $request->input('auth_user_uuid');
        $name = $request->input('name');
        $email = $request->input('email');
        $contact_no = $request->input('contact_no');
        $favorite_genres = $request->input('favorite_genres');
        $user_img = $request->file('file');
        $bio = $request->input('bio');

        $user_exist = User::query()->where('uuid', $uuid)->first();
        $user_arr = [
            'name' => $name,
            'email' => $email,
            'contact_no' => $contact_no,
            'bio' => $bio,
            'status' => 'active',
            'verify_user' => 0,
            'favorite_genres'=>$favorite_genres
//                'password' => Hash::make($password),
//                'secret_key' => encrypt($password),
        ];

        if ($user_exist){

            if ($user_img){
                $fileName = 'user_'.time() . '_' . $user_img->getClientOriginalName();
                $filePath = $request->file('file')->storeAs('uploads/users', $fileName, 'public');

                $user_arr['image'] = 'uploads/users/'.$fileName;
            }
            $user_exist->update($user_arr);
        }
        return redirect()->back()->with('success', 'Record updated successfully');
    }

    public function get_explore_user_stories(){
        return view('explore_stories');
    }
    public function get_user_hightlights(){
        $highlights = Highlight::all();
        $most_rated_arr = [];
        $most_viewed_arr = [];
        if (sizeof($highlights)> 0) {
            foreach ($highlights as $h => $row) {
                $user=User::where('uuid',$row->user_id)->first();
                $row['image_user']=$user->image??"";
                $row['name_user']=$user->name??"";
                $rating = HighlightRating::query()->where('highlight_id', $row->uuid)
                    ->groupBy('highlight_id')->average('rating');
                $row['rating'] = round($rating,'1');
                if($row->file){
                    $file = explode('.',$row->file);
                    $row['extension'] = $file[1];
                }
                if($row->file_image){
                    $file_image = explode('.',$row->file_image);
                    $row['image_extension'] = $file_image[1];
                }
                if ($rating){

                    array_push($most_rated_arr, $row);
                }

            }
            $rating = array_column($most_rated_arr, 'rating');
            array_multisort($rating, SORT_DESC, $most_rated_arr);
//           $most_rated_arr =  collect($most_rated_arr)->sortBy('rating')->reverse();
//            usort($most_rated_arr,function ($a,$b){
//                return $a['rating'] > $b['rating'];
//            });
            $most_rated_arr = array_splice($most_rated_arr, 0,6);
        }
        $top_views = Highlight::query()->orderByDesc('views')->take(6)->get();
        if(sizeof($top_views) > 0){
            foreach ($top_views as $t => $t_row){
                $user=User::where('uuid',$t_row->user_id)->first();
                $t_row['image_user']=$user->image??"";
                $t_row['name_user']=$user->name??"";
                if($t_row->file){
                    $file = explode('.',$t_row->file);
                    $t_row['extension'] = $file[1];
                }
                if($t_row->file_image){
                    $file_image = explode('.',$t_row->file_image);
                    $t_row['image_extension'] = $file_image[1];
                }
            }
        }
        $most_viewed_arr = $top_views;
        if ($highlights){
            $response = [
                'success'=> true,
                'message'=> 'Record Found',
                'data'=>[
                    'most_rated'=>$most_rated_arr,
                    'most_viewed'=> $most_viewed_arr
                ]
            ];
        } else{
            $response = [
                'success'=> false,
                'message'=> 'Record Not Found'
            ];
        }
        return $response;
    }
    public function filter_admin_posts(Request $request)
    {
        $tag_id = $request->input('tid');
        $search = $request->input('search');
        $posts_arr = [];


if ($search){
    $post_tags = Tag::query()->where('tag_name','LIKE', $search)->get();
    if (sizeof($post_tags) > 0) {
        foreach ($post_tags as $p => $p_row) {

            $tag_id = $p_row->uuid;

            $post_tags = AdminPostTag::query()->where('tag_id',$p_row->tag_id)->first();
            $post =  AdminPost::query()->where('uuid' , $post_tags->post_id)->withCount(['likes','comments'])->first();
  if ($post){
                    $tag_id = $p_row->uuid;
                    $like = false;
                    $like_exist =  Like::query()->where('user_id', Auth::user()->uuid)
                        ->where('post_id', $post->uuid)->first();

                    if ($like_exist){
                        $like = true;
                    }
                    $post['is_like'] = $like;

                     $format_date = @$post->created_at->diffForHumans();
                    $post['basic_formatted_date'] = $format_date;
                    $comments = @$post->comments;
                    if (sizeof($comments)> 0){
                        foreach ($comments as $c=> $bc_row){

                            $comment_user_data = User::query()->where('uuid', @$bc_row->user_id)->first();
                            if ($comment_user_data) {

                                $bc_row['comment_user_name'] = @$comment_user_data->name;
                                $bc_row['comment_user_image'] = @$comment_user_data->image;
                                $bc_row['comment_created_at_formatted'] = @$bc_row->created_at->diffForHumans();
                            }

                            $post['comments'] = @$comments;
                        }
                    }
                      //get comments count


            array_push($posts_arr,$post);

}

}
}

}else{
         $post_id = $request->post_id;

    if($tag_id){

    $post_tags = AdminPostTag::query()->where('tag_id', $tag_id)->get();
    }else{
           $post_tags = AdminPostTag::all();

    }

    if (sizeof($post_tags) > 0) {
        foreach ($post_tags as $p => $p_row) {


            $post =  AdminPost::query()->where('uuid' , $p_row->post_id)->withCount(['likes','comments'])->first();
           // $post_id= $p_row->post_id;

            //$tag_id = $p_row->uuid;

            if ($post){

                    $like = false;
                    $like_exist =  Like::query()->where('user_id', Auth::user()->uuid)
                    ->where('post_id', $post->uuid)->first();

                    if ($like_exist){
                        $like = true;
                    }
                     $post['is_like'] = $like;

                     $format_date = @$post->created_at->diffForHumans();
                    $post['basic_formatted_date'] = $format_date;
                    $comments = @$post->comments;
                    if (sizeof($comments)> 0){
                        foreach ($comments as $c=> $bc_row){

                            $comment_user_data = User::query()->where('uuid', @$bc_row->user_id)->first();
                            if ($comment_user_data) {

                                $bc_row['comment_user_name'] = @$comment_user_data->name;
                                $bc_row['comment_user_image'] = @$comment_user_data->image;
                                $bc_row['comment_created_at_formatted'] = @$bc_row->created_at->diffForHumans();
                            }

                            $post['comments'] = @$comments;
                        }
                    }

                    //get comments count end

            array_push($posts_arr,$post);

            }

    }

}
}
  usort($posts_arr,function($a, $b) {
                    return $a['created_at'] < $b['created_at'];
                });
return json_encode($posts_arr);

    }
    public function get_highlight_data(Request $request){
        $highlight_id = $request->input('hid');
        if ($highlight_id){
            $is_highlight = Highlight::query()->where('uuid',$highlight_id)->first();

            $is_highlight['praise_comments'] = [];
            $is_highlight['critique_comments'] = [];
            $praise_comments = Comment::query()->where('post_id', $is_highlight->uuid)
                ->where('commentable_type','App\Models\Highlight')
                ->where('comment_type','praise')->get();

            $critique_comments = Comment::query()->where('post_id', $is_highlight->uuid)
                ->where('commentable_type','App\Models\Highlight')
                ->where('comment_type','critique')->get();
            if (sizeof($praise_comments) > 0){
                foreach ($praise_comments as $pc => $pc_row){

                    $user = User::query()->where('id', $pc_row->user_id)->first();
                    $pc_row['comment_created_at_formatted'] = @$pc_row->created_at->diffForHumans();
                    $pc_row['user'] = $user;

                }
                $is_highlight['praise_comments'] = $praise_comments;
            }

            if (sizeof($critique_comments) > 0){
                foreach ($critique_comments as $cc => $cc_row){

                    $user = User::query()->where('id', $cc_row->user_id)->first();
                    $cc_row['comment_created_at_formatted'] = @$cc_row->created_at->diffForHumans();
                    $cc_row['user'] = $user;

                }
                $is_highlight['critique_comments'] = $critique_comments;
            }
            $response = [
                'success'=> true,
                'message'=>'Record found successfully',
                'data'=>$is_highlight
            ];
        }else{
            $response = [
                'success'=> false,
                'message'=>'Record not found',
            ];
        }
        return $response;
    }
    public function get_genre_highlights(Request $request){
        $user = Auth::user();
        $hashtag_id = $request->input('hashtag_id');

        $highlight_arr = [];
        $genre_arr = [];
        if (!empty($hashtag_id)) {

            if ($hashtag_id == "Top"){
                $highlights = Highlight::with('highlight_genres')->get();
                $most_rated_arr = [];
                $most_viewed_arr = [];
                if (sizeof($highlights)> 0) {
                    foreach ($highlights as $h => $row) {
                        $user=User::where('uuid',$row->user_id)->first();
                        $row['image_user']=$user->image??"";
                        $row['name_user']=$user->name??"";
                        $rating = HighlightRating::query()->where('highlight_id', $row->uuid)
                            ->groupBy('highlight_id')->average('rating');
                        $row['rating'] = round($rating,'1');
                        if($row->file){
                            $file = explode('.',$row->file);
                            $row['extension'] = $file[1];
                        }
                        if($row->file_image){
                            $file_image = explode('.',$row->file_image);
                            $row['image_extension'] = $file_image[1];
                        }
                        if ($rating){

                            array_push($most_rated_arr, $row);
                        }

                    }
                    $rating = array_column($most_rated_arr, 'rating');
                    array_multisort($rating, SORT_DESC, $most_rated_arr);
//           $most_rated_arr =  collect($most_rated_arr)->sortBy('rating')->reverse();
//            usort($most_rated_arr,function ($a,$b){
//                return $a['rating'] > $b['rating'];
//            });
//                    $most_rated_arr = array_splice($most_rated_arr, 0,6);
                }
                $top_views = Highlight::query()->orderByDesc('views')->get();
                if(sizeof($top_views) > 0){
                    foreach ($top_views as $t => $t_row){
                        $user=User::where('uuid',$t_row->user_id)->first();
                        $t_row['image_user']=$user->image??"";
                        $t_row['name_user']=$user->name??"";
                        if($t_row->file){
                            $file = explode('.',$t_row->file);
                            $t_row['extension'] = $file[1];
                        }
                        if($t_row->file_image){
                            $file_image = explode('.',$t_row->file_image);
                            $t_row['image_extension'] = $file_image[1];
                        }
                    }
                }
                $most_viewed_arr = $top_views;

                $highlight_arr = array_merge($most_rated_arr,$most_viewed_arr->toArray());
            }else{
                $hashtag = Genres::query()->where('genres', $hashtag_id)->first();

                $highlight_hashtags = HighlightGenre::query()->where('genre_id', $hashtag->genres)->get();
                if (sizeof($highlight_hashtags) > 0) {

                    foreach ($highlight_hashtags as $h => $h_row) {

                        $highlight = Highlight::query()->where('uuid', $h_row->highlight_id)->with('user')->first();

                        if ($highlight){
                            $rating = HighlightRating::query()->where('highlight_id', $highlight->uuid)
                                ->groupBy('highlight_id')->average('rating');
                            $user=User::where('uuid',$highlight->user_id)->first();
                            $highlight['rating'] = round($rating,'1');
                            $highlight['views'] = $highlight->views;
                            $explode_file = explode('.',$highlight->file);
                            $highlight['extension'] = $explode_file[1];
                            $highlight['image_user']=$user->image??"";
                            $highlight['name_user']=$user->name??"";
                            array_push($highlight_arr, $highlight);

                        }
                    }
                }
            }
            $views = array_column($highlight_arr, 'views');
            array_multisort($views, SORT_DESC, $highlight_arr);

        }
        $response = [
            'success'=> true,
            'message'=>'Record found successfully',
            'data'=>$highlight_arr
        ];
        return $response;
    }

    public function post_details($type = null, $uuid = null){
        if ($type && $uuid){
            if ($type != "admin"){
                $posts = Post::query()
                    ->where('uuid', $uuid)
                    ->where('suspend', 0)
                    ->withCount(['likes','comments'])
                    ->first();
            }else{
                $posts = AdminPost::query()
                    ->where('uuid', $uuid)
                    ->withCount(['likes','comments'])
                    ->first();
            }

            $post_comments = Comment::query()->where('post_id', $uuid)
                ->whereNull('parent_id')->get();

            if (sizeof($post_comments) > 0 ){
                foreach ($post_comments as $p => $pc_row){
                    $post_comment_user = User::query()->where('id', $pc_row->user_id)->first();
                    $pc_row['user'] = $post_comment_user;
                    $comment_replies = Comment::query()->where('post_id', $pc_row->post_id)
                        ->where('parent_id',$pc_row->uuid)->orderBy('id', 'desc')->get();
                    if (sizeof($comment_replies) > 0){
                        foreach ($comment_replies as $cr => $cr_row){
                            $comment_reply_user = User::query()->where('id', $cr_row->user_id)->first();
                            $cr_row['comment_reply_user'] = $comment_reply_user;
                        }
                    }
                    $pc_row['replies'] = $comment_replies;
                }


            }
            $posts->post_comments = $post_comments;
            if($type != "admin"){
                $user = User::query()->where('uuid', $posts->user_id)->first();
                if ($user){
                    $penpal_counts = Penpal::query()->where('status','Accept')
                        ->where('sender_id',$user->uuid)->orWhere('receiver_id', $user->uuid)->count();
//                    $user['image'] = Image::query()->where('imageable_id',$user->id)->where('imageable_type','App\Models\User')->pluck('url')->first();
                    $user['penpal_counts'] = $penpal_counts;
                }

            }else{
                $user = Admin::query()->where('uuid', $posts->user_id)->first();
                $posts->post_type = "admin";

            }

            $posts->user = $user;

            $like = false;
            if (Auth::user()){
                $like_exist =  Like::query()->where('user_id', Auth::user()->uuid)->where('post_id', $posts->uuid)->first();

                if ($like_exist){
                    $like = true;
                }
            }

            $posts['is_like'] = $like;


//            dd($posts);
            return view('post_details', compact('posts'));

        }else{
            return redirect()->back();
        }
    }
    public function get_user_chats(){
        $auth_user = Auth::user();
        $user_connections = [];
        $messages = [];
        $penpal_search = [];
        // $friends = Penpal::query()->where('sender_id',$auth_user->uuid)
        if ($auth_user){
            $user_connections =UserConnection::query()->where('sender_id',$auth_user->uuid)
                    ->orWhere('receiver_id',$auth_user->uuid)->get();

            if (sizeof($user_connections) > 0){
                foreach ($user_connections as $u => $u_row){
                    $toPick = $auth_user->uuid;
                    if ($toPick == $u_row->sender_id){
                        $toPick = $u_row->receiver_id;
                    }else{
                        $toPick = $u_row->sender_id;
                    }
                    $user = User::query()->where('uuid', $toPick)->first();
                    $u_row['user'] = $user;


//                    ..........FIRST CONNECTON MESSAGES.......


                }
                $connection_uuid = $user_connections[0]->uuid;
                $messages = UserMessages::query()->where('connection_id', $connection_uuid)->get();
                if (sizeof($messages) > 0){
                    foreach($messages as $message){
                        $user_to_pick = $message->receiver_id;
                        if ($message->receiver_id == $user->uuid){
                            $user_to_pick = $message->sender_id;
                        }
                        $message['user_details'] =  User::query()->where('uuid',$user_to_pick)->first();
                        $message['format_created_at'] = $message->created_at->diffForHumans();
                        $message['format_date'] = Carbon::parse($message->created_at)->format('m/d/Y');
                    }
                }




            }

            $user_penpals = Penpal::query()->where('status','Accept')->where('sender_id', $auth_user->uuid)
                ->orWhere('receiver_id',$auth_user->uuid)
                ->get();
            $to_pick = $auth_user->uuid;
            $penpal_search = [];
            if (sizeof($user_penpals)>0){
                foreach ($user_penpals as $u => $u_row){

                    if ($auth_user->uuid == $u_row->sender_id){
                        $to_pick = $u_row->receiver_id;
                    }else{
                        $to_pick = $u_row->sender_id;

                    }

                    $penpal_data = User::query()->where('uuid', $to_pick)->first();
                    if ($penpal_data){
                        $penpal_data['formatted_created_at'] = $penpal_data->created_at->diffForHumans();
                        $u_row['user'] = $penpal_data;
                        array_push($penpal_search,$u_row );
                    }

                }

            }
        //dd($user_conns);
            $group_arr =[];
            $user_groups = GroupUser::query()->where('user_id', $auth_user->uuid)->get();
            if (sizeof($user_groups)>0){
                foreach ($user_groups as $u => $row){
                    $group =  Group::query()->where('uuid', $row->group_id)->first();
                    if ($group){
                        $last_message = GroupConversation::query()->where('group_id', $group->uuid)->latest()->first();
                        $message_count =GroupConversation::query()->where('group_id', $group->uuid)->count();
                        $group->formatted_created_at = $group->created_at->diffForHumans();
                        $group->message_count = $message_count;
                        if ($last_message){
                            $last_message['formatted_created_at'] = $last_message->created_at->diffForHumans();
                            $last_message['format_date'] = Carbon::parse($last_message->created_at)->format('m/d/Y');

                            $group->last_message = $last_message;
                        }
                        array_push($group_arr, $group);

                    }
                }
                $group_arr = collect($group_arr)->sortBy('created_at');

            }
        return view('chat',compact('user_connections','messages','penpal_search','group_arr'));
    }else{
            return redirect('login');
        }
}
    public function get_user_penpals(){
        $auth_user = Auth::user();
        if ($auth_user){
            $user_penpals = Penpal::where(function ($query) use($auth_user){
               $query->where('sender_id', $auth_user->uuid)
               ->orWhere('receiver_id', $auth_user->uuid);
            })->where('status', 'Accept')->get();

            if (sizeof($user_penpals) > 0){
                foreach ($user_penpals as $u => $u_row){
                    $toPick = $auth_user->uuid;
                    if ($toPick == $u_row->sender_id){
                        $toPick = $u_row->receiver_id;

                    }else{
                        $toPick = $u_row->sender_id;
                    }

                    $user = User::query()->where('uuid', $toPick)->first();
                    $u_row['user'] = $user;
                }
            }


        }else{
            return redirect('login');
        }

        return view('penpals',compact('user_penpals'));
    }

    public function get_change_user_password(){
        return view('change_password');
    }
    public function get_friend_requests(){
        $user = Auth::user();
        if ($user){
            $get_requests = Penpal::query()->where('receiver_id',$user->uuid)
                ->where('status', 'Request Sent')->get();
            if (sizeof($get_requests)> 0){
                foreach ($get_requests as $g => $g_row){
                    $user = User::query()->where('uuid', $g_row->sender_id)->first();
                    if ($user){

                        $g_row['user_detail'] = $user;
                    }
                }
            }

        }else{
            return redirect('login');
        }

        return view('friend_requests', compact('get_requests'));
    }
    public function update_penpal_status(Request $request){
        $user = Auth::user();
        $status = $request->input('status');
        $other_user_id = $request->input('receiver_id');

        $request_exist = Penpal::query()->whereIn('sender_id', [$other_user_id, $user->uuid])
            ->whereIn('receiver_id', [$other_user_id, $user->uuid])->first();
        if ($request_exist){
            if ($status == 'cancel' || $status == 'Cancel'){
                $request_exist->delete();
            }else{
                $request_exist->update([
                    'status'=>$status,
                ]);
            }


        }else{

        }
        return redirect()->back();

    }

    public function stories(){
        $auth_user = Auth::user();
        $genres = Genres::all();
        if ($auth_user){
            $user_stories_arr = [];
            $penpals = Penpal::where(function($query) use ($auth_user){
                $query->where('sender_id', $auth_user->uuid)
                    ->orWhere('receiver_id',$auth_user->uuid);
            })->where('status','Accept')->get();
            //******************* STORIES ****************** //
            $get_auth_user = User::query()->select('id','uuid','email','name','image','favorite_genres')
                ->where('uuid', $auth_user->uuid)->first();
            $user_stories = Story::query()->where('user_id', $auth_user->uuid)
                ->where('created_at',  '>=', Carbon::now()->subDay()->toDateTimeString())->get();
            $admin_stories = AdminTip::query()
                ->where('created_at',  '>=', Carbon::now()->subDay()->toDateTimeString())->get();
            if (sizeof($user_stories)> 0){
                foreach ($user_stories as $us=> $u_row){
                    if ($u_row->post_id){

                        $post = Post::query()->where('uuid',$u_row->post_id)->first();

                        if ($post){
                            $user =  User::query()->where('uuid', $u_row->user_id)->first();


                            $u_row['post'] = $post;
                            $u_row['tip_type'] = 1;
                        }
                    }else{
                        $post =Story::query()
                            ->where('uuid', $u_row->uuid)
                            ->first();
                        if ($post){

                            $user =  User::query()->where('uuid', $u_row->user_id)->first();

                            $u_row['post'] = $post;
                            $u_row['tip_type'] = 2;
                        }


                    }
                    array_push($user_stories_arr, $u_row);
                }
            }
            $get_auth_user['stories'] = $user_stories_arr;

            $stories_arr = [
                'auth_user_stories'=>@$get_auth_user,
//                'admin_stories'=> @$admin_stories,
//                'penpal_stories'=> @$today_stories,
            ];

        }
        else{
            return redirect('login');
        }
        return view('stories', compact('genres'));
    }
    public function change_password(Request $request){
            $user = Auth::user();
            $user_password = $user->password;
            $request->validate([
               'current_password'=> 'required',
               'password'=>'required|same:confirm_password|min:5',
               'confirm_password'=> 'required'
            ]);

            if (!Hash::check($request->current_password, $user_password)){
                return back()->withErrors(['current_password'=>'Password not match']);
            }

            $user->password = Hash::make($request->password);
            $user->secret_key = encrypt($request->password);
            $user->save();
            return redirect()->back()->with('success','Password successfully updated');

    }
    public function save_highlight_comment(Request $request){

        $user = Auth::user();
        $highlight_id = $request->input('highlight_id');
        $comment = $request->input('comment');
        $comment_type = $request->input('comment_type');
        $post_type = 'user';
        $validator = Validator::make($request->all(), [
            'comment'=>'required',

        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $add_comment = Comment::create([
            'uuid' => base64_encode(rand(9,9999)) ,
            'user_id' => $user->id,
            'post_id' => $highlight_id,
            'post_type' => $post_type,
            'comment' => $comment,
            'comment_type' => strtolower($comment_type),
            'commentable_id' => 1,
            'commentable_type' => "App\Models\Highlight",
        ]);

if ($add_comment){
    return response(['success'=> true, 'message'=> 'Comment Saved Successfully']);

}else{
    return response(['success'=> false, 'message'=> 'Comment not posted']);

}



//            if ($created_comment){
//            }else{
//                return response(['success'=> false, 'message'=> 'Failed to save comment ']);
//
//            }
    }

    public function get_packages()
    {
        return view('packages');
    }
    public function get_highlight_comments(Request $request){
        $highlight_id = $request->input('highlight_id');
        if (!empty($highlight_id)){
            $is_highlight  = Highlight::query()->where('uuid', $highlight_id)->first();
            if ($is_highlight){
                $is_highlight['praise_comments'] = [];
                $is_highlight['critique_comments'] = [];
                $praise_comments = Comment::query()->where('post_id', $is_highlight->uuid)
                    ->where('commentable_type','App\Models\Highlight')
                    ->where('comment_type','praise')->get();

                $critique_comments = Comment::query()->where('post_id', $is_highlight->uuid)
                    ->where('commentable_type','App\Models\Highlight')
                    ->where('comment_type','critique')->get();

                if (sizeof($praise_comments) > 0){
                    foreach ($praise_comments as $pc => $pc_row){

                        $user = User::query()->where('id', $pc_row->user_id)->first();
                        $pc_row['comment_created_at_formatted'] = Carbon::parse($pc_row->created_at)->format('d/m/Y');
                        $pc_row['user'] = $user;

                    }
                    $is_highlight['praise_comments'] = $praise_comments;
                }

                if (sizeof($critique_comments) > 0){
                    foreach ($critique_comments as $cc => $cc_row){

                        $user = User::query()->where('id', $cc_row->user_id)->first();
                        $cc_row['comment_created_at_formatted'] = Carbon::parse($cc_row->created_at)->format('d/m/Y');
                        $cc_row['user'] = $user;

                    }
                    $is_highlight['critique_comments'] = $critique_comments;
                }

            }else{
                $response = ['success'=> false,'message'=>'No highlight exists with this id'];
            }
            $response = ['success'=>true,'message'=>'Record found','data'=>$is_highlight];

        }else{
            $response = ['success'=>false,'message'=>'Highlight id can\'t be null'];
        }
        return $response;
    }
    //API SECTION
    public function create_group_new(Request $request){
      $user_data = Auth::user();
      if ($user_data && $request->input('users')){
          $creator_id = $user_data->uuid;

          $group = Group::create([
              'uuid'=>Str::uuid(),
              'name' => $request->input('group_name'),
              'creator_id'=> $creator_id,
          ]);

          $users_arr = $request->input('users');
          $users_arr = json_decode($users_arr);
//         $users = explode(',',$users_arr[0]);
          if (sizeof($users_arr)> 0){
              foreach ($users_arr as $u => $row){

                  $user_exist =  User::query()->where('uuid',$row)->first();

                  if ($user_exist){
                      GroupUser::create([
                          'uuid'=>Str::uuid(),
                          'group_id'=>$group->uuid,
                          'user_id'=>$user_exist->uuid,
                          'user_type'=>'member'
                      ]);

                  }

              }

          }
          $group->users()->attach($creator_id,['uuid'=>Str::uuid(), 'user_type'=>'admin']);

      }



        return redirect()->back();
    }

public function get_data(Request $request)
{

        $connection_id =$request->input('conn_id');
        $user = Auth::user();

        $messages = [];
               $user_conns =  UserConnection::query()->where('uuid', $connection_id)->first();
               if ($user_conns){
                   $messages = UserMessages::query()->where('connection_id', $connection_id)->get();
                   if (sizeof($messages)> 0){
                       foreach ($messages as $m => $m_row){
                           $user_to_pick = $m_row->receiver_id;
                           if ($m_row->receiver_id == $user->uuid){
                               $user_to_pick = $m_row->sender_id;
                           }
                           $m_row['user_details'] =  User::query()->where('uuid',$user_to_pick)->first();
                           $m_row['format_created_at'] = $m_row->created_at->diffForHumans();
                           $m_row['format_date'] = Carbon::parse($m_row->created_at)->format('m/d/Y');
                       }

                   }
                   return json_encode([
                       'success' => true,
                       'data' => $messages,
                   ]);
                   // dd($user_conns);
              }else{
                   return json_encode([
                       'success' => false,
                       'data' => $messages,
                   ]);
               }



}

public function write_new_message(Request $request){
        $user_data = Auth::user();
        if ($user_data){
            $message = $request->input('message');
            $receiver_id = $request->input('receiver_id');
            $sender_id = $user_data->uuid;

            $check_conn = UserConnection::query()
                ->whereIn('sender_id',[$sender_id,$receiver_id])
                ->whereIn('receiver_id',[$sender_id,$receiver_id])
                ->first();
            if ($check_conn){
                $message = UserMessages::create([
                    'uuid'=> Str::uuid(),
                    'connection_id'=>$check_conn->uuid,
                    'sender_id'=>$sender_id,
                    'receiver_id'=>$receiver_id,
                    'message'=>$message,
                ]);

            }else{
//                new connection
                $connection = UserConnection::create([
                    'uuid'=> Str::uuid(),
                    'sender_id'=>$sender_id,
                    'receiver_id'=>$receiver_id,
                ]);
                if ($connection){
                    $message = UserMessages::create([
                        'uuid'=> Str::uuid(),
                        'connection_id'=>$connection->uuid,
                        'sender_id'=>$sender_id,
                        'receiver_id'=>$receiver_id,
                        'message'=>$message,
                    ]);

                }
            }
        }
        return redirect()->back();
}
public function send_message_user(Request $request){
        $user_data = Auth::user();
    $sender_id = $user_data->uuid;
    $receiver_id = $request->input('receiver_id');
    $message = $request->input('message');


    $sender_exist = User::query()->where('uuid', $sender_id)->first();
    $receiver_exist = User::query()->where('uuid',$receiver_id)->first();
    if ($sender_exist && $receiver_exist){


        $conn_exist = UserConnection::query()->whereIn('sender_id', [$sender_id, $receiver_id])
            ->whereIn('receiver_id',[$sender_id, $receiver_id])->first();

        if (!$conn_exist){
            $connection = UserConnection::create([
                'uuid'=> Str::uuid(),
                'sender_id'=>$sender_id,
                'receiver_id'=>$receiver_id,
            ]);
            if ($connection){
                $message = UserMessages::create([
                    'uuid'=> Str::uuid(),
                    'connection_id'=>$connection->uuid,
                    'sender_id'=>$sender_id,
                    'receiver_id'=>$receiver_id,
                    'message'=>$message,
                ]);

                $response = [
                    'success'=> true,
                    'message'=>'Message saved successfully',
                ];
            }
        }else{

            $message = UserMessages::create([
                'uuid'=> Str::uuid(),
                'connection_id'=>$conn_exist->uuid,
                'sender_id'=>$sender_id,
                'receiver_id'=>$receiver_id,
                'message'=>$message,
            ]);
            $response = [
                'success'=> true,
                'message'=>'Message saved successfully',
                'connection_id'=>$conn_exist->uuid,
            ];
        }
    }else{
        $response = [
            'success'=> false,
            'message'=>'Sender or receiver not exists',
        ];
    }
    return $response;
}

///////////// cherif

public function update_profile(Request $request){
    $uuid = $request->input('auth_user_uuid');
    $name = $request->input('name');
    $email = $request->input('email');
    $contact_no = $request->input('contact_no');
    $bio = $request->input('bio');
    //$favorite_genres = $request->input('favorite_genres');
    //$user_img = $request->file('file');

    $user_exist = User::query()->where('uuid', $uuid)->first();
    $user_arr = [
        'name' => $name,
        'email' => $email,
        'contact_no' => $contact_no,
        'bio' => $bio,
        // 'status' => 'active',
        // 'verify_user' => 0,
        // 'favorite_genres'=>$favorite_genres
    ];

    if ($user_exist){

        // if ($user_img){
        //     $fileName = 'user_'.time() . '_' . $user_img->getClientOriginalName();
        //     $filePath = $request->file('file')->storeAs('uploads/users', $fileName, 'public');

        //     $user_arr['image'] = 'uploads/users/'.$fileName;
        // }


        $user_exist->update($user_arr);
        $response = [
            'success'=> false,
            'message'=>'profile updated',
        ];
    }
    else
    {
        $response = [
            'success'=> false,
            'message'=>'profile not updated',
        ];
    }
    return $response;
}



}
