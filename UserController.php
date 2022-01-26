<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupConversation;
use App\Models\GroupUser;
use App\Models\Notification;
use App\Models\Penpal;
use App\Models\User;
use App\Models\UserConnection;
use App\Models\UserMessages;
use App\Notifications\OffersNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //

    public function find_writers(){
        $auth_user = Auth::user();
        $status = 'Add Friend';
        if ($auth_user){
            $users = User::all()->except(Auth::user()->id);

            if (sizeof($users) > 0){
                foreach ($users as $u => $u_row){
                    $is_exist = Penpal::query()->whereIn('sender_id', [$auth_user->uuid, $u_row->uuid])
                    ->whereIn('receiver_id',[$auth_user->uuid, $u_row->uuid])->first();

                    if ($is_exist){
//                        $toPick = $auth_user->uuid;
//                        if ($toPick == $is_exist->sender_id){
//                            $toPick = $is_exist->receiver_id;
//                        }else{
//                            $toPick = $is_exist->sender_id;
//                        }
//                        $user = User::query()->where('uuid',$toPick)->first();
//                        if ($user){

                            if ($is_exist->status == 'Accept'){
                                $status = 'Friends';
                                $u_row['status'] = $status;
                            }
                            if ($is_exist->status == 'Request Sent' && $is_exist->sender_id == $auth_user->uuid){
                                $status = 'Request Sent';
                                $u_row['status'] = $status;

                            }
                            if ($is_exist->status == 'Request Sent' && $is_exist->receiver_id == $auth_user->uuid){
                                $status = 'Confirm or Cancel';
                                $u_row['status'] = $status;

                            }
                            if ($is_exist->status == 'Pending' && $is_exist->sender_id == $auth_user->uuid){
                                $status = 'Request Sent';
                                $u_row['status'] = $status;

                            }
                            if ($is_exist->status == 'Pending' && $is_exist->receiver_id == $auth_user->uuid){
                                $status = 'Confirm or Cancel';
                                $u_row['status'] = $status;

                            }

//                        }
                    }else{
                        $status = 'Add Friend';
                        $u_row['status'] = $status;

                    }

                }
            }

        }else{
            return redirect('login');
        }
        return view('find_penpals',compact('users'));
    }

    public function user_add_penpal(Request $request){

        $user_data = Auth::user();
        if($user_data){
            $sender_id = Auth::user()->uuid;
            $receiver_id = $request->input('receiver_id');
            $status = $request->input('request_for');
            $is_penpal = Penpal::query()->whereIn('sender_id', [$sender_id,$receiver_id])
                ->WhereIn('receiver_id', [$sender_id,$receiver_id])->first();

            $is_sender_id = User::query()->where('uuid', $sender_id)->first();
            $is_receiver_id = User::query()->where('uuid', $receiver_id)->first();

            if ($is_sender_id && $is_receiver_id){

                if (!$is_penpal){
                    $penpal = Penpal::create([
                        'uuid'=> Str::uuid(),
                        'sender_id'=> $sender_id,
                        'receiver_id'=>$receiver_id,
                        'status'=> $status
                    ]);
                    if ($penpal){
                        $offerData = [
                            'name'=> 'Friend Request',
                            'body'=>'You received a new friend request from'.$is_sender_id->name,
                            'thanks'=>'Need Response',
                            'offerText'=> 'Check out the request',
                            'offerUrl'=> url('/'),
                            'order_id'=>007

                        ];
//                    \Illuminate\Support\Facades\Notification::send($is_receiver_id, new OffersNotifications($offerData));
                        $response = [
                            'success'=> true,
                            'message'=> 'Request Sent',
                            'data'=>'Request Sent'
                        ];

                    }else{
                        $response = [
                            'success'=> false,
                            'message'=> 'Failed to save record',
                        ];
                    }
                }else{
                    $response = [
                        'success'=> false,
                        'message'=> 'Record already exist',
                    ];
                }

                return $response;
            }
            return redirect()->back();
        }else{
            return redirect('login');
        }







    }
    public function create_user_chat(Request $request){
        $sender_id = $request->input('sender_id');
        $receiver_id = $request->input('receiver_id');
        $message = $request->input('message');

        $validator = Validator::make($request->all(), [
            'sender_id'=>'required',
            'receiver_id'=>'required',
            'message'=>'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
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

    public function create_group(Request $request){

        $validator =  Validator::make($request->all(),[
            'name' => 'required|unique:groups',
            'users'=>'required',
            'creator_id'=>'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $creator_id = $request->input('creator_id');
        $group = Group::create([
            'uuid'=>Str::uuid(),
            'name' => $request->input('name'),
            'creator_id'=> $creator_id,
        ]);

        $users_arr = $request->input('users');
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

        $response = [
            'success'=> true,
            'message'=>'Group Added Successfully',
        ];
        return $response;
    }
    public function store_message(Request $request){
        $group_id = $request->input('group_id');
        $user_uuid = Auth::user()->uuid;
        $conversation = GroupConversation::create([
            'uuid'=>Str::uuid(),
            'message' => $request->input('message'),
            'group_id' => $group_id,
            'user_id' => $user_uuid,
        ]);

        $conversation->load('user');
        $response = [
            'success'=>true,
            'message'=>'Record Saved Successfully',
            'data'=> $conversation->load('user')
        ];

        $group_users = GroupUser::query()->where('group_id',$group_id )
            ->where('user_id','!=', $user_uuid)->get();
        $user = User::query()->where('uuid',$user_uuid)->first();
//        if (sizeof($group_users)> 0){
//            foreach ($group_users as $g => $row){
//
//                $firebaseToken = User::where('uuid', $row->user_id)->pluck('device_token')->first();
//                array_push($firebaseToken_arr, $firebaseToken);
//            }
//        }
////        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
//
//        $SERVER_API_KEY = 'AAAAxCBcttQ:APA91bE6kRMXhJxKXmITswtq-D4MWObEZz2jKe4bSsBjYZJm-oFiyPXtqRRrdGoalNPS-zZ65CFy7fGvQhktEEw9mospukbbx9Ov38r1ZAzPSXIelu8muzqzjWZLxVMPqB9xssseqV-Z';
//
//        $data = [
//            "registration_ids" => $firebaseToken_arr,
//            "notification" => [
//                "title" => @$user->name+' send message',
//                "body" => $request->message,
//            ]
//        ];
//        $dataString = json_encode($data);
//
//        $headers = [
//            'Authorization: key=' . $SERVER_API_KEY,
//            'Content-Type: application/json',
//        ];
//
//        $ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
//
////        $response = curl_exec($ch);
//             curl_exec($ch);


        return $response;
    }
    public function get_group_messages(Request $request){
        $group_user_arr = [];
        $group_id = $request->input('group_id');
        $group_exist = Group::query()->where('uuid', $group_id)->first();
        if ($group_exist){
            $group_messages = GroupConversation::query()->where('group_id', $group_id)->get();
            $group_users = GroupUser::query()->where('group_id', $group_id)->get();
            if (sizeof($group_messages)>0){
                foreach ($group_messages as $g=> $row){

                    $user_details =  User::query()->where('uuid', $row->user_id)->first();
                    $group_user = GroupUser::query()->where('group_id',$group_id)
                        ->where('user_id', $user_details->uuid)->first();
                    $user_details->user_type = $group_user->user_type;
                    $row['user_details'] = $user_details;
                }
            }


            if (sizeof($group_users)>0){
                foreach ($group_users as $u=> $u_row){
                    $user_details =  User::query()->where('uuid', $u_row->user_id)->first();
                    $u_row['user'] = $user_details;
                }
            }

            $response = [
                'success'=> true,
                'message'=>'Record Found',
                'group_details'=>$group_exist,
                'group_users'=>$group_users,
                'group_message'=>$group_messages
            ];

        }else{
            $response = [
                'success'=> false,
                'message'=> 'Group not exist'
            ];
        }
        return $response;
    }

    public function get_user_chats(Request $request){
        $auth_user = Auth::user();
        $group_arr = [];
        if ($auth_user){
            $user_conns = UserConnection::query()->where('sender_id', $auth_user->uuid)
                ->orWhere('receiver_id', $auth_user->uuid)->orderBy('id', 'desc')->get();
            if (sizeof($user_conns) > 0){
                foreach ($user_conns  as $u => $row){

                    $last_msg = UserMessages::query()->where('connection_id', $row->uuid)->latest()->first();
                    $msg_count = UserMessages::query()->where('connection_id', $row->uuid)->count();
                    $user_to_pick = $last_msg->receiver_id;
                    if ($last_msg->receiver_id == $auth_user->uuid){
                        $user_to_pick = $last_msg->sender_id;
                    }

                    $last_msg['format_created_at'] = $last_msg->created_at->diffForHumans();
                    $last_msg['format_date'] = Carbon::parse($last_msg->created_at)->format('m/d/Y');
                    $last_msg['user_details'] =  User::query()->where('uuid',$user_to_pick)->first();
                    $row['latest_message'] = $last_msg;
                    $row['message_count'] = $msg_count;
                }

                $response = [
                    'success'=> true,
                    'message'=> $user_conns
                ];
            }else{
                $response = [
                    'success'=>false,
                    'message'=>'No connection exist',
                ];
            }

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

                    }else{
                        $response = [
                            'success'=> false,
                            'message'=>'No group found'
                        ];
                    }
                }
                $group_arr = collect($group_arr)->sortByDesc('id');
                $response = [
                    'success'=> true,
                    'message'=>'Record found successfully',
                    'data'=> $group_arr
                ];
                return view('chat');
            }else{
                $response = [
                    'success'=> false,
                    'message'=>'No group found'
                ];
            }
        }else{
            $response = [
                'success'=> false,
                'message'=>'User not login'
            ];
        }
        return $response;
    }

    public function get_user_chat_messages(Request $request){
        $receiver = $request->input('receiver_id');
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'receiver_id'=>'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user_conn =  UserConnection::query()->whereIn('sender_id', [$user->uuid, $receiver])
            ->whereIn('receiver_id',[$user->uuid, $receiver])->first();
        if ($user_conn){
            $messages = UserMessages::query()->where('connection_id', $user_conn->uuid)
                ->orderBy('id', 'ASC')->get();
            if (sizeof($messages)> 0){
                foreach ($messages as $m => $m_row){
                    $user = User::query()->where('uuid', $m_row->sender_id)->first();
                    $m_row['user'] =$user;
                }
                $response = [
                    'success'=>true,
                    'message'=>'Conversation found',
                    'data'=> $messages
                ];
            }else{
                $response = [
                    'success'=> false,
                    'message'=>'No Message Found'
                ];
            }

        }else{
            $response = [
                'success'=>false,
                'message'=>'No conversation exists'
            ];
        }

        return $response;
    }

    public function update_penpal_status(Request $request){
        $user = Auth::user();
        $status = $request->input('request_for');
        $uuid = $request->input('penpal_uuid');
        $request_exist = Penpal::query()->where('uuid',$uuid)->first();
        if ($request_exist){
            if ($status == 'cancel' || $status == 'Cancel'){
                $request_exist->delete();
                $response = [
                    'success'=>true,
                    'message'=>'Request status updated',
                    'data'=>'Cancel'
                ];
            }else{
                $request_exist->update([
                    'status'=>$status,
                ]);
                $response = [
                    'success'=>true,
                    'message'=>'Request status updated',
                    'data'=>'Friends'
                ];
            }

        }else{
            $response = [
                'success'=>false,
                'message'=>'No record found'
            ];
        }
        return json_encode($response);

    }

    public function get_auth_user_penpals(Request $request){
        $auth_user =  Auth::user();
        if ($auth_user){
            $penpals = Penpal::where(function ($query) use($auth_user){
               $query->where('sender_id', $auth_user->uuid)
               ->orWhere('receiver_id', $auth_user->uuid);
            })->where('status', 'Accept')->latest()->take(3)->get();
            if (sizeof($penpals) > 0){
                foreach ($penpals as $p=> $p_row){
                    $toPick = $auth_user->uuid;
                    if ($toPick == $p_row->sender_id){
                        $toPick = $p_row->receiver_id;
                    }else{
                        $toPick = $p_row->sender_id;
                    }
                    $user = User::query()->where('uuid', $toPick)->first();
                    $p_row['user'] = $user;
                }
            }
            $response = [
                'success'=> true,
                'message'=>'Record found successfully',
                'data'=>$penpals
            ];
        }else{
            $response = ['success'=>false,'message'=>'Login to countinue'];
        }
        return json_encode($response);
    }
}
