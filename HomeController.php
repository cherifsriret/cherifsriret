<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AdminPost;
use App\Models\AdminTip;
use App\Models\Comment;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Like;
use App\Models\Penpal;
use App\Models\Post;
use App\Models\Story;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['main_screen']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $auth_user = Auth::user();
        $data = '';
        $base_url = base_path();
        if ($auth_user){



            $posts = [];
            $search_writers = User::query()->orderBy('views','desc')->get();
            $search_tags = Tag::query()->get();
            $search_posts = Post::query()->where('file_type', 'image')->get();

            $auth_user_penpals = Penpal::where(function($query) use ($auth_user){
               $query->where('sender_id', $auth_user->uuid)
                   ->orWhere('receiver_id',$auth_user->uuid);
            })->where('status','Accept')->take(3)->get();
            if (sizeof($auth_user_penpals) > 0){
                foreach($auth_user_penpals as $auth_user_penpal){
                    $toPick = $auth_user->uuid;
                    if ($toPick == $auth_user_penpal->sender_id){
                        $toPick = $auth_user_penpal->receiver_id;
                    }else{
                        $toPick = $auth_user_penpal->sender_id;
                    }
                    $other_user = User::query()->where('uuid', $toPick)->first();
                    $auth_user_penpal->user = $other_user;
                }
            }
//            $auth_user_penpals = Penpal::query()->where('sender_id', $auth_user->uuid)
//                ->orWhere('receiver_id', $auth_user->uuid)->where('status', 'Accept')->take(3)->get();

            $auth_user_request = Penpal::query()->whereIn('status', ['Request Sent','Add Friend'])
                ->where('receiver_id', $auth_user->uuid)->take(4)->get();
            if (sizeof($auth_user_request) > 0){
                foreach($auth_user_request as $auth_u_request){
                    $toPick = $auth_user->uuid;
                    if ($toPick == $auth_u_request->sender_id){
                        $toPick = $auth_u_request->receiver_id;
                    }else{
                        $toPick = $auth_u_request->sender_id;
                    }
                    $other_user = User::query()->where('uuid', $toPick)->first();
                    $auth_u_request->user = $other_user;
                }
            }

            $auth_user_groups = GroupUser::query()->where('user_id',$auth_user->uuid)->get();
            if (sizeof($auth_user_groups) > 0){
                foreach ($auth_user_groups as $ag => $ag_row){
                    $group = Group::query()->where('uuid', $ag_row->group_id)->first();
                    $ag_row['group'] = $group;
                }
            }

            //******************* STORIES ****************** //

            $today_stories = [];
            $user_stories_arr = [];
            $admin_story_data = [];
            $penpal_stories_arr = [];


            $user_stories = null;



            $get_auth_user = Story::query()->where('user_id', $auth_user->uuid)
                ->where('created_at',  '>=', Carbon::now()->subDay()->toDateTimeString())->latest()->get();
            if (sizeof($get_auth_user)> 0){
                $user_stories = User::query()->select('id','uuid','email','name','image','favorite_genres')
                    ->where('uuid', $auth_user->uuid)->first();
                foreach ($get_auth_user as $us=> $u_row){
                    if ($u_row->post_id){
                        $post = Post::query()->where('uuid',$u_row->post_id)->first();
                        if ($post){
                            $user =  User::query()->where('uuid', $post->user_id)->first();
                            $post['user'] = $user;
                            $u_row['post'] = $post;
                            $u_row['tip_type'] = 1;
                        }
                    }else{
                        $post = Story::query()->where('uuid',$u_row->uuid)->first();
                        if ($post){

                            $user =  User::query()->where('uuid', $u_row->user_id)->first();
                            $post['user'] = $user;
                            $u_row['post'] = $post;
                            $u_row['tip_type'] = 2;
                        }


                    }
                    array_push($user_stories_arr, $u_row);
                }
                $user_stories['stories'] = $user_stories_arr;
            }

            $admin_stories = null;
            $admin_user = AdminTip::query()
                ->where('created_at',  '>=', Carbon::now()->subDay()->toDateTimeString())->get();
            if (sizeof($admin_user)> 0){
                $admin_stories = Admin::query()->first();
                foreach ($admin_user as $a=> $a_row){
                    if ($a_row->post_id){
                        $post = AdminPost::query()->where('uuid',$a_row->post_id)->first();
                        if ($post){
                            $user =  Admin::query()->where('uuid', $post->user_id)->first();
                            $post['user'] = $user;
                            $a_row['post'] = $post;
                            $a_row['tip_type'] = 1;

                        }
                    }else{
                        $post = Story::query()->where('uuid',$a_row->uuid)->first();
                        if ($post){
                            $user =  Admin::query()->where('uuid', $post->user_id)->first();
                            $post['user'] = $user;
                            $a_row['post'] = $post;
                            $a_row['tip_type'] = 2;

                        }

                    }
                    array_push($admin_story_data, $a_row);

                }

                $admin_user['stories'] = $admin_story_data;


            }


            $penpals = Penpal::query()->where('status', 'accept')->where('sender_id', $auth_user->uuid)
                ->orWhere('receiver_id', $auth_user->uuid)->get();
            if (sizeof($penpals)>0){
                foreach ($penpals as $p=> $p_row){
                    if ($auth_user->uuid == $p_row->sender_id){
                        $to_pick = $p_row->receiver_id;
                    }else{
                        $to_pick = $p_row->sender_id;
                    }



                    $get_penpal_stories = Story::query()->where('user_id', $to_pick)
                        ->where('created_at',  '>=', Carbon::now()->subDay()->toDateTimeString())->get();
                    if(sizeof($get_penpal_stories) > 0){
                        $get_user = User::query()->select('id','uuid','email','name','image','favorite_genres')
                            ->where('uuid', $to_pick)->first();
                        foreach ($get_penpal_stories as $pe => $p_row){
                            if ($p_row->post_id){
                                $post = Post::query()->where('uuid',$p_row->post_id)->first();
                                if ($post){
                                    $user =  User::query()->where('uuid', $post->user_id)->first();
                                    $post['user'] = $user;
                                    $p_row['post'] = $post;
                                    $p_row['tip_type'] = 1;
                                }
                            }else{
                                $post = Story::query()->where('uuid',$p_row->uuid)->first();
                                if ($post){

                                    $user =  User::query()->where('uuid', $p_row->user_id)->first();
                                    $post['user'] = $user;
                                    $p_row['post'] = $post;
                                    $p_row['tip_type'] = 2;
                                }


                            }
                            array_push($penpal_stories_arr, $p_row);
                        }
                        $get_user['stories'] = $penpal_stories_arr;
                        array_push($today_stories, $get_user);
                    }

                }
            }

            if (sizeof($today_stories)>0){
                foreach ($today_stories as $t => $t_row){
                    if ($t_row->file){
                        $file_url = $t_row->file;
                        $explode_extension = explode('.',$file_url);
                        $t_row['extension'] = $explode_extension[1];
                        $user = User::query()->where('uuid', $t_row->user_id)->first();
                        $t_row['user'] = $user;
                    }
                }
            }

            //******************* POSTS ****************** //
            if (sizeof($penpals) > 0){
                foreach ($penpals as $p => $p_row){
                    $toPick = $auth_user->uuid;
                    if ($toPick == $p_row->sender_id){
                        $toPick = $p_row->receiver_id;
                    }else{
                        $toPick = $p_row->sender_id;
                    }
                    $penpal_posts = Post::query()
                        ->where('user_id', $toPick)
                        ->where('suspend', 0)
                        ->withCount(['likes','comments'])
                        ->orderBy('id','desc')
                        ->get();

                    if(sizeof($penpal_posts) > 0){
                        foreach ($penpal_posts as $pp => $pp_row){
                            array_push($posts,$pp_row);
                        }
                    }


                }
            }
            $auth_user = Auth::user();
            $user_posts = Post::query()->where('user_id',   $auth_user->uuid)
                ->where('suspend', 0)
                ->withCount(['likes','comments'])
//                ->where('description', 'LIKE', '%'.$search.'%')
                ->orderBy('created_at','desc')
                ->get();


            if(sizeof($user_posts) > 0){
                foreach ($user_posts as $up => $up_row){
                    array_push($posts,$up_row);
                }
            }

            if (sizeof($posts)> 0){

                foreach ($posts as $u => $row){

                    $like = false;
                    $like_exist =  Like::query()->where('user_id', Auth::user()->uuid)->where('post_id', $row->uuid)->first();

                    if ($like_exist){
                        $like = true;
                    }
                    $row['is_like'] = $like;
                    if ($row->file){

                        $file_explode = explode('.',$row->file);
                        $row['extension'] = @$file_explode[1];
                    }

                    $row['formatted_created_at'] = @$row->created_at->diffForHumans();
                    $comment_count = Comment::query()->where('post_id', $row->uuid)->count();
                    $row['comment_count'] = $comment_count;
                    $latest_comment = Comment::query()->where('post_id', $row->uuid)
                        ->whereNull('parent_id')->latest()->first();

                    if ($latest_comment){
                            $latest_comment_user = User::query()->where('id', $latest_comment->user_id)->first();
                            $latest_comment['user'] = $latest_comment_user;

                            $comment_replies = Comment::query()->where('post_id', $latest_comment->post_id)
                            ->where('parent_id',$latest_comment->uuid)->latest()->limit(2)->get();
                            if (sizeof($comment_replies) > 0){
                                foreach ($comment_replies as $cr => $cr_row){
                                    $comment_reply_user = User::query()->where('id', $cr_row->user_id)->first();
                                    $cr_row['comment_reply_user'] = $comment_reply_user;
                                }
                            }
                            $latest_comment['comment_replies'] = $comment_replies;
                    }
                    $row['latest_comment'] = $latest_comment;


                    $post_comments = Comment::query()->where('post_id', $row->uuid)
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

//                            array_push($post_comments_arr, $pc_row);
                        }


                    }
                    $row['post_comments'] = $post_comments;

//                    if (sizeof($latest_comment)> 0) {
//                        foreach ($latest_comment as $c => $c_row) {
//
//                            $comment_user_data = User::query()->where('uuid', @$c_row->user_id)->first();
//                            if ($comment_user_data) {
//
//                                $c_row['comment_user_name'] = @$comment_user_data->name;
//                                $c_row['comment_user_image'] = @$comment_user_data->image;
//                                $c_row['comment_created_at_formatted'] = @$c_row->created_at->diffForHumans();
//                            }
//
//                            $row['comments'] = @$comments;
//                        }
//                    }
                    $user = User::query()->select('id','uuid', 'name','email','contact_no','image', 'status','verify_user')
                        ->where('uuid', $row->user_id)->first();
                    if ($user){
                        $penpal_counts = Penpal::query()->where('status','Accept')
                            ->where('sender_id',$user->uuid)->orWhere('receiver_id', $user->uuid)->count();
//                    $user['image'] = Image::query()->where('imageable_id',$user->id)->where('imageable_type','App\Models\User')->pluck('url')->first();
                        $user['penpal_counts'] = $penpal_counts;
                        $row['user'] = $user;
                    }
                }

            }
            $top_100_writers = User::query()->orderBy('views','desc')->take(100)->get();
//            return view('home',compact('user_stories','admin_stories','today_stories','posts','auth_user_penpals','auth_user_request','auth_user_groups','latest_comment','comment_count'));
            return view('home',compact('user_stories','get_auth_user','admin_stories','today_stories','posts','auth_user_penpals','auth_user_request','auth_user_groups','search_writers','search_posts','search_tags','top_100_writers'));

        }else{
            return redirect('login')->with('error','Login to use this app');
        }

    }

    public function main_screen(){
        return view('main_screen');
    }
    public function search_home_input(Request $request){
        $search = $request->input('search');
        $search_users = User::query()->where('name','LIKE','%'.$search.'%')->orderBy('id', "desc")->get();
        $search_posts = Post::query()->where('description','LIKE','%'.$search.'%')->orderBy('id', "desc")->get();
        $search_tags = Tag::query()->where('tag_name','LIKE','%'.$search.'%')->orderBy('id', "desc")->get();

        $arr = [
            'writers' => $search_users,
            'posts' => $search_posts,
            'tags' => $search_tags,
        ];
        return json_encode($arr);
    }
//    public function paginate($items, $perPage = 6, $page = null, $options = [])
//    {
//        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
//        $items = $items instanceof Collection ? $items : Collection::make($items);
//        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
//    }
}
