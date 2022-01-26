<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AdminPost;
use App\Models\AdminPostTag;
use App\Models\AdminTip;
use App\Models\Comment;
use App\Models\Genres;
use App\Models\Highlight;
use App\Models\HighlightGenre;
use App\Models\Like;
use App\Models\Penpal;
use App\Models\Post;
use App\Models\Story;
use App\Models\Tag;
use App\Models\Quick;
use App\Models\User;
use App\Models\QuickText;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    //


    public function submit_post(Request $request){

        $user = Auth::user();
        $file = $request->file('post_file');
        $description = $request->input('description');
        $file_type = $request->input('file_type');
        if (!$description){
            $description = '';
        }
        if ($file || $description) {

            $post_arr = [
                'uuid' => Str::uuid(),
                'user_id' => $user->uuid,
                'description' => $description,
                'suspend' => 0,
                'post_type'=>'user',
                'file_type'=>$file_type
            ];

            if ($file){


                if ($file_type == 'video'){
                    $fileName = 'post_'.rand(999,9999).time() .'.'. strtolower($file->getClientOriginalExtension());
//                        $filePath = $file->move(public_path('uploads/videos'), $fileName);
                    $filePath = $file->storeAs('uploads/videos', $fileName, 'public');

                }
                if ($file_type == 'image'){
                    $fileName = 'post_'.rand(999,9999).time() .'.'. strtolower($file->getClientOriginalExtension());
//                        $filePath = $file->move(public_path('uploads/images'), $fileName);
                    $filePath = $file->storeAs('uploads/images', $fileName, 'public');
                }


                $post_arr['file'] = $filePath;

            }

            $add_post  = Post::create($post_arr);

            if ($add_post){
                return redirect()->back()->with('success','Post Added Successfully');
            }else{
                return redirect()->back()->with('error','Failed to Add Post');

            }

        }else{
            return redirect()->back()->with('error','File or Description is requried to add post ');

        }


    }

    public function post_details($id = null){
        $post_id = $id;
        $auth_user = Auth::user();
        if ($auth_user){
               $post =  Post::query()->where('uuid', $post_id)->first();
            if ($post){

                    $like = false;
                    $like_exist =  Like::query()->where('user_id', Auth::user()->uuid)
                        ->where('post_id', $post->uuid)->first();

                    if ($like_exist){
                        $like = true;
                    }
                    $row['is_like'] = $like;



                    $comments = @$post->comments;

                    if (sizeof($comments)> 0) {
                        foreach ($comments as $c => $c_row) {

                            $comment_user_data = User::query()->where('uuid', @$c_row->user_id)->first();
                            if ($comment_user_data) {

                                $c_row['comment_user_name'] = @$comment_user_data->name;
                                $c_row['comment_user_image'] = @$comment_user_data->image;
                                $c_row['comment_created_at_formatted'] = @$c_row->created_at->diffForHumans();
                            }

                            $row['comments'] = @$comments;
                        }
                    }
                    $user = User::query()->select('id','uuid', 'name','email','contact_no','image', 'status','verify_user')
                        ->where('uuid', $row->user_id)->first();
                    if ($user){
                        $penpal_counts = Penpal::query()->where('status','Accept')
                            ->where('sender_id',$user->uuid)->orWhere('receiver_id', $user->uuid)->count();
//                    $user['image'] = Image::query()->where('imageable_id',$user->id)->where('imageable_type','App\Models\User')->pluck('url')->first();
                        $user['penpal_counts'] = $penpal_counts;
                        $row['user'] = $user;
                    }

                return view('post_details');
            }

        }else{
            return redirect('login');
        }
    }

    public function save_user_comment(Request $request){

        $user = Auth::user();
        $post_id = $request->input('post_id');
        $comment = $request->input('comment');
        $post_type = $request->input('post_type');

        $validator = Validator::make($request->all(), [
            'post_id'=>'required',
            'comment'=>'required',
            'post_type'=>'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $comment = new Comment;
        $comment->uuid = Str::uuid();
        $comment->comment = $request->comment;
        $comment->post_type = $post_type;

        $comment->user()->associate($request->user());
        if ($post_type == 'user'){

            $post = Post::where('uuid',$post_id)->first();
        }
        if ($post_type == 'admin'){
            $post = AdminPost::where('uuid',$post_id)->first();
        }
        $comment->post_id = $post->uuid;
        $post->comments()->save($comment);


         $response = [
             'success'=> true,
             'message'=> 'Comment Saved Successfully'
         ];

        return json_encode($response);
    }

    public function get_post_latest_comment($post_id){
        $latest_comment = Comment::query()->where('post_id',$post_id)
            ->whereNull('parent_id')
            ->latest()->take(1)->first();
        if ($latest_comment){

            $comment_replies = Comment::query()->where('post_id', $latest_comment->post_id)
                ->where('parent_id',$latest_comment->uuid)->latest()->limit(2)->get();
            if (sizeof($comment_replies) > 0){
                foreach ($comment_replies as $cr => $cr_row){
                    $comment_reply_user = User::query()->where('id', $cr_row->user_id)->first();
                    $formatted_created_at = $cr_row->created_at->diffForHumans();
                    $cr_row['formatted_created_at'] = $formatted_created_at;
                    $cr_row['comment_reply_user'] = $comment_reply_user;
                }
            }
            $latest_comment['comment_replies'] = $comment_replies;


            $formatted_created_at = $latest_comment->created_at->diffForHumans();
            $latest_comment['formatted_created_at'] = $formatted_created_at;
            $latest_comment_user = User::query()->where('id', $latest_comment->user_id)->first();
            $latest_comment['user'] = $latest_comment_user;
        }
        $response = [
            'success'=>true,
            'message'=>'Comment found Successfully',
            'data'=>$latest_comment
        ];
        return json_encode($response);
    }

    public function get_post_comments($post_id){
        $post_comments = Comment::query()->where('post_id',$post_id)
            ->whereNull('parent_id')->get();


        if (sizeof($post_comments) > 0 ){
            foreach ($post_comments as $p => $pc_row){
                $post_comment_user = User::query()->where('id', $pc_row->user_id)->first();
                $pc_row['user'] = $post_comment_user;
                $pc_row['formatted_created_at'] = $pc_row->created_at->diffForHumans();
                $comment_replies = Comment::query()->where('post_id', $pc_row->post_id)
                    ->where('parent_id',$pc_row->uuid)->orderBy('id', 'desc')->get();
                if (sizeof($comment_replies) > 0){
                    foreach ($comment_replies as $cr => $cr_row){
                        $comment_reply_user = User::query()->where('id', $cr_row->user_id)->first();
                        $cr_row['user'] = $comment_reply_user;
                        $cr_row['formatted_created_at'] = $cr_row->created_at->diffForHumans();

                    }
                }
                $pc_row['replies'] = $comment_replies;

//                            array_push($post_comments_arr, $pc_row);
            }


        }



        $response = [
            'success'=>true,
            'message'=>'Comment found Successfully',
            'data'=>$post_comments
        ];
        return json_encode($response);
    }
    public function get_comment_user($id){
        if (!empty($id)){

           $user = User::query()->where('id', $id)->first();
           if ($user){
               $response = [
                 'success'=>true,
                 'message'=>'Record found successfully',
                 'data'=>$user
               ];
           }else{
               $response = [
                   'success'=>false,
                   'message'=>'User not found',
               ];
           }
        }else{
            $response  = [
                'success'=>false,
                'message'=>'ID is required',
            ];
        }
        return json_encode($response);
    }

    public function save_user_comment_reply(Request $request){
        $user = Auth::user();
        $post_id = $request->input('post_id');
        $comment = $request->input('comment');
        $comment_id = $request->input('comment_id');
        $post_type = $request->input('post_type');

        $validator = Validator::make($request->all(), [
            'post_id'=>'required',
            'comment'=>'required',
            'post_type'=>'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $comment = new Comment;
        $comment->uuid = Str::uuid();
        $comment->comment = $request->comment;
        $comment->parent_id = $request->comment_id;
        $comment->post_type = $post_type;

        $comment->user()->associate($request->user());
        if ($post_type == 'user'){

            $post = Post::where('uuid',$post_id)->first();
        }
        if ($post_type == 'admin'){
            $post = AdminPost::where('uuid',$post_id)->first();
        }
        $comment->post_id = $post->uuid;
        $post->comments()->save($comment);


        $response = [
            'success'=> true,
            'message'=> 'Comment Saved Successfully'
        ];

        return json_encode($response);
    }

    public function save_post_like(Request $request){
        $uuid = $request->input('uuid');
        $post_type = $request->input('post_type');
        $auth_user = Auth::user();
        if($auth_user){
            if($post_type != "admin"){
                $is_post =  Post::query()->where('uuid', $uuid)->first();

            }else{
                $is_post =  AdminPost::query()->where('uuid', $uuid)->first();

            }
           if ($is_post){
              $is_like = Like::query()->where('post_id',$is_post->uuid)
                            ->where('user_id', $auth_user->uuid)
                            ->where('post_type', $post_type)->first();
              if ($is_like){
                  $is_like->delete();
                  $like_count =Like::query()->where('post_id',$is_post->uuid)->count();
                  $response = [
                      'success'=> true,
                      'target'=>'unlike',
                      'likes_count'=>$like_count,
                      'is_like'=> false,
                  ];
              }else{
                  Like::create([
                      'uuid'=> Str::uuid(),
                      'user_id'=>$auth_user->uuid,
                      'post_id'=>$is_post->uuid,
                      'post_type'=> $post_type
                  ]);
                  $like_count =Like::query()->where('post_id',$is_post->uuid)->count();
                  $response = [
                      'success'=> true,
                      'target'=>'like',
                      'likes_count'=>$like_count,
                      'is_like'=> true,

                  ];
              }
           }
            return json_encode($response);
        }else{
            return redirect('login');
        }
        return json_encode($response);

    }
    public function save_admin_pro_post_like(Request $request){
        $uuid = $request->input('uuid');
        $post_type = $request->input('post_type');
        $auth_user = Auth::user();
        if($auth_user){
           $is_post =  AdminPost::query()->where('uuid', $uuid)->first();
           if ($is_post){
              $is_like = Like::query()->where('post_id',$is_post->uuid)
                            ->where('user_id', $auth_user->uuid)
                            ->where('post_type', $post_type)->first();
              if ($is_like){
                  $is_like->delete();
                  $like_count =Like::query()->where('post_id',$is_post->uuid)->count();
                  $response = [
                      'success'=> true,
                      'target'=>'unlike',
                      'likes_count'=>$like_count,
                      'is_like'=> false,
                  ];
              }else{
                  Like::create([
                      'uuid'=> Str::uuid(),
                      'user_id'=>$auth_user->uuid,
                      'post_id'=>$is_post->uuid,
                      'post_type'=> $post_type
                  ]);
                  $like_count =Like::query()->where('post_id',$is_post->uuid)->count();
                  $response = [
                      'success'=> true,
                      'target'=>'like',
                      'likes_count'=>$like_count,
                      'is_like'=> true,

                  ];
              }
           }
            return json_encode($response);
        }else{
            return redirect('login');
        }
        return json_encode($response);

    }

    public function share_post_story(Request $request){

        $post_id = $request->input('post_id');
        $post_type = $request->input('post_type');
        $file_type = $request->input('file_type');
        $file = $request->file('file');

        $auth_user = Auth::user();
        if($auth_user){

            $story_arr = [
                'uuid'=>Str::uuid(),
                'user_id'=>$auth_user->uuid,
                'post_type'=>$post_type,
                'file_type'=>$file_type,
            ];

            if ($post_id){

                $is_post =  Post::query()->where('uuid', $post_id)->first();
                $story_arr['post_id'] = $is_post->uuid;
            }
            if ($file){
                $fileName = 'post_'.rand(999,9999).time() .'.'. strtolower($file->getClientOriginalExtension());
                $filePath = $file->storeAs('uploads/stories', $fileName, 'public');
                $story_arr['file'] = $filePath;

            }
                $add_story = Story::create($story_arr);
                if ($add_story){

                    $post_data = Post::query()->where('uuid', $add_story->post_id)->first();
                    $response = [
                        'success'=> true,
                        'message'=>'Record found successfully',
                        'data' => [
                            'post_data'=>$post_data,
                            'user_data'=>$auth_user
                        ]

                    ];
                }

            return json_encode($response);
        }else{
            return redirect('login');
        }
        return json_encode($response);

    }


    public function admin_post($tag = null){
        //qiuck start
        $quickData=[];
        $quick = Quick::all();
        if(sizeof($quick) > 0)
        {
        foreach($quick as $qck)
        {
            $quickData = User::query()->where('uuid', $qck->user_id)->first();
            $qck->user_details=$quickData;
        }
        }
       

        
        $quick_text = QuickText::first();
        
        $user = Auth::user();
        $basic_post_arr = [];
        $pro_post_arr = [];

        if ($user){
            if (!empty($tag_id)){
                $post_tags = AdminPostTag::query()->where('tag_id', $tag_id)->get();
                if (sizeof($post_tags) > 0){
                    foreach ($post_tags as $p => $p_row){

                        $admin_basic_post = AdminPost::query()->withCount(['likes','comments'])
                            ->where('uuid',$p_row->post_id)
                            ->where('tip_type','basic')
                            ->orderBy('id','desc')
                            ->first();
                        if ($admin_basic_post){
                            array_push($basic_post_arr, $admin_basic_post);
                        }
                        $admin_pro_post = AdminPost::query()->withCount(['likes','comments'])
                            ->where('tip_type','pro')
                            ->where('uuid',$p_row->post_id)
                            ->orderBy('id','desc')
                            ->first();
                        if ($admin_pro_post){
                            array_push($pro_post_arr, $admin_pro_post);
                        }
                    }
                }
                $basic_posts = $basic_post_arr;
                $total_basic_data_count  = count($basic_posts);
                $total_basic = (int)($total_basic_data_count / 10);
                usort($basic_posts,function($a, $b) {
                    return $a['created_at'] < $b['created_at'];
                });
//                $basic_posts = array_slice($basic_posts, $start_limit,$end_limit);
                $pro_posts = $pro_post_arr;
                $total_pro_data_count  = count($pro_posts);
                $total_pro = (int)($total_pro_data_count / 10);
                usort($pro_posts,function($a, $b) {
                    return $a['created_at'] < $b['created_at'];
                });
//                $pro_posts = array_slice($pro_posts, $start_limit,$end_limit);


            }
            else{

                $basic_posts = AdminPost::query()->withCount(['likes','comments'])
                    ->where('tip_type','basic')
                    ->orderBy('id','desc')->get();
                $total_basic_data_count = AdminPost::query()->withCount(['likes','comments'])
                    ->where('tip_type','basic')
                    ->orderBy('id','desc')->count();
                $total_basic = (int)($total_basic_data_count / 10);
                $pro_posts = AdminPost::query()->withCount(['likes','comments'])
                    ->where('tip_type','pro')
                    ->orderBy('id','desc')->get();
                $total_pro_data_count = AdminPost::query()->withCount(['likes','comments'])
                    ->where('tip_type','pro')
                    ->orderBy('id','desc')->count();
                $total_pro = (int)($total_pro_data_count / 10);

            }

            if (sizeof($basic_posts)> 0){
                foreach ($basic_posts as $b =>$b_row){

                    $like_exist =  Like::query()->where('user_id', $user->uuid)
                        ->where('post_id', $b_row->uuid)->first();

                    if ($like_exist){
                        $b_row['is_like'] = true;
                    }else{
                        $b_row['is_like'] = false;
                    }
                    if ($b_row->file){

                        $explode_file = explode('.',$b_row->file);
                        $extension = $explode_file[1];
                        $b_row['extension']= $extension;
                    }

                    $format_date = @$b_row->created_at->diffForHumans();
                    $b_row['basic_formatted_date'] = $format_date;
                    $comments = @$b_row->comments;
                    if (sizeof($comments)> 0){
                        foreach ($comments as $c=> $bc_row){

                            $comment_user_data = User::query()->where('uuid', @$bc_row->user_id)->first();
                            if ($comment_user_data) {

                                $bc_row['comment_user_name'] = @$comment_user_data->name;
                                $bc_row['comment_user_image'] = @$comment_user_data->image;
                                $bc_row['comment_created_at_formatted'] = @$bc_row->created_at->diffForHumans();
                            }

                            $b_row['comments'] = @$comments;
                        }
                    }
                }
            }
            if (sizeof($pro_posts)> 0){
                foreach ($pro_posts as $b =>$p_row){
                    $like_exist =  Like::query()->where('user_id', $user->uuid)
                        ->where('post_id', $p_row->uuid)->first();

                    if ($like_exist){
                        $p_row['is_like'] = true;
                    }else{
                        $p_row['is_like'] = false;

                    }


                    $format_date = @$p_row->created_at->diffForHumans();
                    $p_row['basic_formatted_date'] = $format_date;
                    $comments = @$p_row->comments;
                    if (sizeof($comments)> 0){
                        foreach ($comments as $c=> $pc_row){

                            $comment_user_data = User::query()->where('uuid', @$pc_row->user_id)->first();
                            if ($comment_user_data) {

                                $pc_row['comment_user_name'] = @$comment_user_data->name;
                                $pc_row['comment_user_image'] = @$comment_user_data->image;
                                $pc_row['comment_created_at_formatted'] = @$pc_row->created_at->diffForHumans();
                            }

                            $p_row['comments'] = @$comments;
                        }
                    }

                }
            }
            $total_rounded_basic = round($total_basic);
            if (!$total_rounded_basic > 0){
                $total_rounded_basic = 1;
            }
            $total_rounded_pro = round($total_pro);
            if (!$total_rounded_pro > 0){
                $total_rounded_pro = 1;
            }
            $response = [
                'success'=> true,
                'message'=>'Record Found',
                'total_basic_pages'=>$total_rounded_basic,
                'total_pro_pages'=>$total_rounded_pro,
                'data'=> [
                    'basic_posts'=>$basic_posts,
                    'pro_posts'=>$pro_posts
                ]
            ];


        }else{
           return redirect('login');
        }
        //dd(($pro_post->is_like));
        $hashtags = Tag::query()->orderBy('id','asc')->get();
        return view('admin_posts', compact('basic_posts','pro_posts','hashtags','quick_text','quick'));
    }

    public function get_user_favourites(Request $request){
        $public_path = public_path();
        $user = Auth::user();
        $search = $request->input('search');
        $postArr = [];
        if (Auth::user()){
            $user_fav = Like::query()->where('user_id', $user->uuid)->get();
            if (sizeof($user_fav)>0){
                foreach ($user_fav as $u => $row){
                    if ($row->post_type == 'user'){
                        $post_data =  Post::query()->where('uuid', $row->post_id)
                            ->where('description','LIKE', '%'.$search.'%')->first();

                        if ($post_data){
                            $date = $post_data->created_at;
                            $date = Carbon::parse($date);
                            $post_user = User::query()->where('uuid', $post_data->user_id)->first();
                            $post_data['user_name'] = $post_user->name;
                            $post_data['user_image'] = $post_user->image;

                            if ($post_data->file){
                                $explode_file = explode('.',$post_data->file);
                                $extension = $explode_file[1];
                                $post_data['extension']= $extension;
                            }

                            if ($date){

                                $post_data['formatted_created_at'] =$date->diffForHumans(Carbon::now());
                            }
                            array_push($postArr, $post_data);
                        }
                    }
                    if($row->post_type == 'admin'){
                        $post_data =  AdminPost::query()->where('uuid', $row->post_id)->first();
                        if ($post_data){

                            $date = $post_data->created_at;
                            $date = Carbon::parse($date);
                            $post_data['user_name'] = 'Admin';
                            $post_data['user_image'] =  $public_path.'/assets/admin_avatar.png';
                            if ($post_data->file){
                                $explode_file = explode('.',$post_data->file);
                                $extension = $explode_file[1];
                                $post_data['extension']= $extension;
                            }
                            if ($date){

                                $post_data['formatted_created_at'] =$date->diffForHumans(Carbon::now());
                            }
                            array_push($postArr, $post_data);
                        }

                    }
                }
            }
//            $response = [
//                'success'=> true,
//                'message'=> 'Record found successfully',
//                'data'=>$postArr,
//            ];
//        }else{
//            $response = [
//                'success'=> false,
//                'message'=>'You are not logged in'
//            ];
        }
        return view('favourites');
    }

    public function get_user_stories(){

        $auth_user = Auth::user();

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
//            if (sizeof($admin_stories)> 0){
//                foreach ($admin_stories as $a=> $a_row){
//                    if ($a_row->post_id){
//                        $post = AdminPost::query()->where('uuid',$a_row->post_id)->first();
//                        if ($post){
//                            $user =  Admin::query()->where('uuid', $post->user_id)->first();
//                            $post['user'] = $user;
//                            $a_row['post'] = $post;
//                            $a_row['tip_type'] = 1;
//
//                        }
//                    }else{
//                        $post =  $a_row;
//                        if ($post){
//                            $user =  Admin::query()->where('uuid', $post->user_id)->first();
//                            $post['user'] = $user;
//                            $a_row['post'] = $post;
//                            $a_row['tip_type'] = 2;
//
//                        }
//
//                    }
//
//                }
//            }
//            $today_stories = [];
//            if (sizeof($penpals)>0){
//                foreach ($penpals as $p=> $p_row){
//                    if ($auth_user->uuid == $p_row->sender_id){
//                        $to_pick = $p_row->receiver_id;
//                    }else{
//                        $to_pick = $p_row->sender_id;
//                    }
//                    $story = Story::query()->where('user_id', $to_pick)
//                        ->where('created_at',  '>=', Carbon::now()->subDay()->toDateTimeString())->first();
//
//                    if ($story ){
//                        array_push($today_stories, $story);
//                    }
//
//
//                }
//            }

//            if (sizeof($today_stories)>0){
//                foreach ($today_stories as $t => $t_row){
//                    if ($t_row->file){
//                        $file_url = $t_row->file;
//                        $explode_extension = explode('.',$file_url);
//                        $t_row['extension'] = $explode_extension[1];
//                        $user = User::query()->where('uuid', $t_row->user_id)->first();
//                        $t_row['user'] = $user;
//                    }
//                }
//            }

            $stories_arr = [
                'auth_user_stories'=>@$get_auth_user,
//                'admin_stories'=> @$admin_stories,
//                'penpal_stories'=> @$today_stories,
            ];

            $response = [
                'success'=> true,
                'message'=>'Record Found Successfully',
                'data'=>$stories_arr
            ];


            return json_encode($response);
        }
        else{
            $response = [
                'success'=> true,
                'message'=>'Login to countinue',
            ];
        }
        return json_encode($response);
    }

    public function  submit_user_highlight(Request $request){
        $user = Auth::user();

        $validator =  Validator::make($request->all(),[
            'highlight_document' => 'required|mimes:doc,pdf,docx|max:2048',
            'highlight_thumb_image' => 'required',
            'highlight_name' => 'required',
            'highlight_genres' => 'required',
//            'hashtags'=> 'array|max:2'
        ]);
        if($validator->fails())
        {
            return redirect()->back();
        }
       
        $file = $request->file('highlight_document');
        $file_image = $request->file('highlight_thumb_image');
        $title = $request->input('highlight_name');
        $genres = $request->input('highlight_genres');
      //  dd($genres);
       // $hashtags = $request->input('hashtags');



        $fileName = 'highlight_docs_'.rand(100,9999) . '.' . $file->getClientOriginalExtension();
        $filePath = $request->file('highlight_document')->storeAs('uploads/highlights', $fileName, 'public');
        $fileImageName = 'highlight_img'.rand(100,9999) . '.' . $file_image->getClientOriginalExtension();
        $fileImagePath = $request->file('highlight_thumb_image')->storeAs('uploads/highlights', $fileImageName, 'public');

        $create_highlight = Highlight::create([
            'uuid'=>Str::uuid(),
            'user_id'=>$user->uuid,
            'file'=> $filePath,
            'file_image'=>$fileImagePath,
            'title'=>$title
        ]);
        if($create_highlight){
            $add_hashtags = HighlightGenre::create([
                'uuid'=> Str::uuid(),
                'highlight_id'=>$create_highlight->uuid,
                'genre_id'=>"8852889-88d0-44bb-89cf-00ab1820133",

            ]);
           if (sizeof($genres) > 0){
               foreach ($genres as $g => $g_row){
                   $genre = Genres::query()->where('genres', $g_row)->first();
                   if ($genre){
                       $add_hashtags = HighlightGenre::create([
                           'uuid'=> Str::uuid(),
                           'highlight_id'=>$create_highlight->uuid,
                           'genre_id'=>$genre->genres,

                       ]);
                   }
               }
           }


        }
        return redirect()->back()->with('success','Highlight Added Successfully');
    }
        public function get_archive_prompts()
        {

        $quickData=[];
        $quick = Quick::all();
        if(sizeof($quick) > 0)
        {
        foreach($quick as $qck)
        {
            $quickData = User::query()->where('uuid', $qck->user_id)->first();
            $qck->user_details=$quickData;
        }
        }
         return view('archivePrompts',compact('quick'));
        }
            
        

    public function save_prompt_data(Request $request)
    {
        $media = $request->file('Upload_media');
        $uuid= Auth::user()->uuid;
        
        if($media)
        {
           $random_int = rand(100, 100000);
           $extension = $media->guessExtension();
           $file_name = "manager_" . $random_int . "." . $extension;
           $media->move('uploads/video/', $file_name);
           $Media_manager = 'uploads/video/' . $file_name;
           $data_arr = [
               'uuid' => base64_encode(rand(9,9999)) ,
               'user_id' => $uuid,
               'file' => $Media_manager,
           ];
            Quick::updateOrCreate($data_arr);
       }

        return redirect()->back();

      }


      public function get_prompt_data(Request $request)
      {
        $save_arr = [];
        $result =[];
        $final_rzlt = [];
        $search_name =  $request->input('search');
        // dd($search_name);
        $date_search = $request->input('queryDate');
        //$date_search = Carbon\Carbon::parse($date)->format('d/m/Y');
       // dd($date_search);
        //if($request->ajax())
        //{
      if(!empty($search_name) && empty($date_search))
      {
      $data = Quick::query()
         ->get();
         if (sizeof($data) > 0) {
             foreach($data as $val){
          $result = User::query()->where('uuid','=',$val->user_id)->first();
          if ($result) {
            $val->user_details = $result;
              $a = $result->name;
            $val->formated_date = Carbon::parse($val->created_at)->diffForHumans();
if (strpos($a, $search_name) !== false) {
    array_push($final_rzlt,$val);
}
          }
             }
         }
       
      }
   
       if(!empty($date_search) && empty($search_name))
       {
         $data = Quick::query()
         ->whereDate('created_at',Carbon::parse($date_search)->format('Y-m-d'))
         ->get();
         if (sizeof($data) > 0) {
             foreach($data as $val){
          $result = User::query()->where('uuid','=',$val->user_id)->first();
          if ($result) {
            $val->user_details = $result;
              $val->formated_date = Carbon::parse($val->created_at)->diffForHumans();
    array_push($final_rzlt,$val);
          }
             }
         }
       }
       if($date_search != '' && $search_name !='')
       {
          $data = Quick::query()
         ->whereDate('created_at',Carbon::parse($date_search)->format('Y-m-d'))
         ->get();
         if (sizeof($data) > 0) {
             foreach($data as $val){
          $result = User::query()->where('uuid','=',$val->user_id)->first();
          if ($result) {
            $val->user_details = $result;
              $a = $result->name;
              $val->formated_date = Carbon::parse($val->created_at)->diffForHumans();
if (strpos($a, $search_name) !== false) {
    array_push($final_rzlt,$val);
}
          }
             }
         }

          }
          if($date_search == '' && $search_name =='')
            {
                $final_rzlt = Quick::all();
                if(sizeof($final_rzlt) > 0){
                    foreach ($final_rzlt as $quick){
                        $quick->formated_date = Carbon::parse($quick->created_at)->diffForHumans();
                        $result = User::query()->where('uuid','=',$quick->user_id)->first();

                        if ($result) {
                            $quick->user_details = $result;

                        }
                    }
                }
            }
           
               return  json_encode($final_rzlt);
                
         }



    public function Search_tag(Request $request){
                    $data = Tag::where('tag_name','LIKE','%'.$request->search.'%')->get();
                    
                return json_encode($data);
            }

    public function upload_quick(Request $request){
        $validator = Validator::make($request->all(), [
            'video'=>'required',
        ]);

        if($validator->fails()){
            //return redirect()->back();
        }
        // dd($request->all());
        $user = Auth::user();
         //dd($request->all());
        $file = $request->file('video');
        // dd($file);
        if ($file){

            $post_arr = [
                'uuid'=> Str::uuid(),
                'user_id'=> $user->uuid,
            ];

            if ($file){

                $fileName = 'quick_'.rand(999,9999).time() .'.'. strtolower($file->getClientOriginalExtension());

                    $filePath = $file->storeAs('uploads/videos', $fileName, 'public');
                    $post_arr['file'] = $filePath;
            }
            $add_post  = Quick::create($post_arr);

            if ($add_post){
                return redirect()->back();
            }else{
                $response = [
                    'success'=> false,
                    'message'=>'Failed to Save Post'
                ];
            }

        }else{
            $response = [
                'success'=> false,
                'message'=>'File or Description is required to upload post'
            ];
        }

            return  json_encode($response);
    }

   
        
       

    

    }






