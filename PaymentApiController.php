<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PaymentApiController extends Controller
{
     public function buy_package(Request $request){
        //dd($request->all());
        $sender_data = Auth::user();

        $payment =  $request->input('payment');

        $days =  $request->input('days');
        $receiver_data = Admin::query()->where('id',1)->first();

        if ($sender_data){
            $sender_id = $sender_data->uuid;
            $receiver_id = $receiver_data->uuid;

            //........................... paypal ....................................
            $payment_add = $this->addPaymentDetails($sender_id,$payment,$days);
           
                // dd($payment,$payment_add, $receiver_id ,$receiver_data->paypal_email);

            $paypal =$this->paypalPaymentCurl($payment,$payment_add, $receiver_id ,$receiver_data->paypal_email);

            if (@$paypal->error){

                return redirect()->back()->with("Some paypal email error occured");
               
            }
            if (@$paypal->paymentExecStatus =='CREATED'){
                $payment = $this->paymentDetails($payment_add, $paypal->payKey);

                return redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey='.$paypal->payKey);
               
            }
            else{
                return redirect()->back()->with("Error occured");
            }
        }else{
             return redirect('login');
           

        }
    }

    public function accept_payment($id = null){
        if ($id){
            $user_id = UserPayment::query()->where('uuid',$id)->pluck('user_id')->first();
            $update_status = UserPayment::query()->where('uuid',$id)->update([
                'status'=>'accept'
            ]);

            $update_promo_used = User::query()->where('uuid', $user_id)->first();
            $update_promo_used->promo_used = 0;

            if ($update_status && $update_promo_used->update()){
                return redirect('home');
//                return view('payment_response')->with(['success'=>true,'message'=>'Congargulations! Payment made Successfully. Now you can use Writers Talk App']);
            }else{
                return redirect()->back();
            }
        }
    }

    public function cancel_payment($id = null){
        if ($id){
            $update_status = UserPayment::query()->where('uuid',$id)->update([
                'status'=>'cancel'
            ]);
            if ($update_status){
                return redirect()->back();
            }
        }
    }
        private function addPaymentDetails($user_id,$payment,$days){
    $user_payment = UserPayment::query()->where('user_id',$user_id)
            ->where('status', 'pending')->latest()->first();
        if (!$user_payment){
            $payment_add = UserPayment::create([
                'uuid' => Str::uuid(),
                'user_id' => $user_id,
                'payment' => $payment,
                'days' => $days,
                'end_date' => Carbon::now()->addDays($days),
                'status' => 'pending',
            ]);
        }else{
            $payment_add = $user_payment;
        }


        return $payment_add['uuid'];
    }


      private function paypalPaymentCurl($payment, $id, $receiver_id,$receiver_paypal_email){
        $path = base_path();
        $obj_rec = [
            'amount' => $payment,
            'email' =>$receiver_paypal_email
        ];
        $obj_rec = (object)$obj_rec;
        $json  =[
            'actionType' =>'PAY',
            'currencyCode'=>'USD',
            'receiverList'=>[
                'receiver' =>[$obj_rec
                ]
            ],
            'returnUrl'=> 'http://writerstalkadmin.com/accept-payment/'.$id,
            'cancelUrl'=> 'http://writerstalkadmin.com/cancel-payment/'.$id,
            'requestEnvelope'=>[
                'errorLanguage'=>'en_US',
                'detailLevel'=>'ReturnAll'
            ]
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://svcs.paypal.com/AdaptivePayments/Pay",
            // CURLOPT_URL => "https://svcs.paypal.com/AdaptivePayments/Pay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json),
            CURLOPT_HTTPHEADER => array(
                "accept: */*",
                "X-PAYPAL-SECURITY-USERID: writerstalkclub_api1.gmail.com",
                "X-PAYPAL-SECURITY-PASSWORD: 37PLW48U3J6WV23Y",
                "X-PAYPAL-SECURITY-SIGNATURE: Ak5NrzjIyJCjladQvy.M5TgY53jOAPYKaJTuXjLmCiJXA4RyuBWN0HJu",
                "X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
                "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON",
                "X-PAYPAL-APPLICATION-ID: APP-7RE75854PL601005B",
                "Accept: application/json",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }

    }

    private function paymentDetails($id,$payKey){

        $update_data = array('pay_key' => $payKey);
        $update_dayment_data = UserPayment::query()
            ->where('uuid',$id)
            ->update($update_data);

    }
}
