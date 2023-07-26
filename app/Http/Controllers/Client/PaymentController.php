<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPayment;
use App\Models\StripePlan;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Stripe;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function stripe($id){
        $title = "Payment";
        $plan = StripePlan::find($id);
        return view('client.payments.create',compact('title','plan'));
    }
    public function stripepost(Request $request){


        $plan = StripePlan::where('id',$request->plan_id)->first();
        if($plan->amount != 0 )
        {
            Stripe\Stripe::setApiKey('sk_test_1IUO2lMwmjt2FwXFOdsPridh');
            //dd($request->all());
            Stripe\Charge::create ([
                "amount" => 100 * $request->amount,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment from cheapest.com."
            ]);
        }

        $payment = UserPayment::create([
        'user_id'   => auth()->user()->id,
        'plan_id'   => $request->plan_id
        ]);
        if($plan->interval == 'year'){
            $expiry_date = 365;
        }
        elseif($plan->interval == 'month'){
            $expiry_date = 30;
        }
        elseif($plan->interval == 'day'){
            $expiry_date = 1;
        }
        else{
            $expiry_date = 0;
        }

        $user = User::where('id',auth()->user()->id)->update([
            'expiry_date' => Carbon::now()->addDays($expiry_date),
        ]);
        Session::flash('success', 'Payment successful!');

        return redirect()->route('client.dashboard');
    }
}
