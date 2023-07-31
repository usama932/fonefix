<?php

namespace App\Http\Controllers;

use App\Models\BasicSetting;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\JobSetting;
use App\Models\Provinces;
use App\Models\Setting;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Session;
use PDF;
use File;
use Auth;
//use Mail;

use App\Mail\DynamicSMTPMail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Swift_SmtpTransport;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    public function pdf($id,$shop_id)
    {
        $user = Job::find($id);
        $img = $user->shop->image;
        $logo = public_path("/uploads/$img");
//        $logo = "$url/public/uploads/".$img;
//        'debugPng' => true,

        $settings = JobSetting::where('user_id', $user->user_id)->first();
        $basic = BasicSetting::where('user_id', $user->user_id)->first();
        if ($basic){
            $logo = url("/uploads/$basic->image");
        }
        $path = url("uploads/");
        $pdf = app('dompdf.wrapper');

        //############ Permitir ver imagenes si falla ################################
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true,'defaultFont' => 'sans-serif']);
        $pdf->getDomPDF()->setHttpContext($contxt);
        //#################################################################################
          $pdf =  $pdf->loadView('admin.jobs.pdf', compact('user','logo','settings','path'));
         //return $pdf->download("$user->job_sheet_number.pdf");
        return view("admin.jobs.pdf",compact('user','logo','settings','path'));

    }
    public function download()
    {
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/job.pdf";

        $headers = array(
            'Content-Type: application/pdf',
        );

        return Response::download($file, 'job-sample.pdf    ', $headers);
    }
    public function index(Request $request)
    {
        $title = 'Home - FoneFix';
        return view('frontend.index',compact("title"));
    }
    public function shop($slug)
    {
        $shop = \App\Models\User::where('slug',$slug)->with('cmsSetting')->first();
        if($shop){
            $sliders = Slider::where('user_id',$shop->id)->take(5)->latest()->get();
            $title = "Home - $shop->name";
            return view('frontend.index',compact("title","shop",'sliders'));
        }
        return redirect()->route('/');


    }
    public function shopContact($slug)
    {
        $shop = \App\Models\User::where('slug',$slug)->first();
        $title = "Contact - $shop->name";
        return view('frontend.contact',compact("title","shop"));
    }

    public function contact(Request $request)
    {
        $title = 'Contact - FoneFix';
        return view('frontend.contact',compact("title"));
    }

    public function  contactUsForm(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|',
            'message' => 'required|',
        ]);
        $settings = Setting::pluck('value', 'name')->all();
        $data = array(
            'name' => $request->name,
            'user_email' => $request->email,
            'from_email' => 'noreply@e-fong-canada.com',
            'subject' => $request->subject,
            'service' => $request->service,

            'phone' => $request->phone,
            'msg' => $request->message,
            'email' => isset($settings['email'])? $settings['email']:'contact@huntpro.ca',
            'logo' => isset($settings['logo']) ? $settings['logo']: '',
            'site_title' => isset($settings['site_title']) ? $settings['site_title']: 'Libby Kitchen',
        );
        Mail::send('emails.contact', $data, function ($message) use ($data) {
            $message->to($data['email'])
                ->from($data['from_email'],$data['site_title'])
                ->subject($data['subject']);
        });
        Session::flash('success_message', 'Great! Email has been sent successfully!');
        return redirect()->back();

    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect()->back();
    }
    public function assignCustomer()
    {
        $invoices = Invoice::whereNull("customer_id")->get();
        foreach ($invoices as $invoice) {
            if ($invoice->job_id){
                $job = Job::findOrFail($invoice->job_id);
                $invoice->customer_id = $job->customer_id;
                $invoice->save();
            }
        }
        dd("done");
    }
    public function assignSlug()
    {
        $shops = \App\Models\User::where([["is_admin",1],["role",2]])->get();
        foreach ($shops as $shop) {
            if (!$shop->slug){
                $shop->slug = $this->createSlug($shop->name,$shop->id);
                $shop->save();
            }
        }
        dd("done");
    }

    public function createSlug($title, $id)
    {
        // Normalize the title
        $slug = Str::slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($slug, $id )
    {
        return \App\Models\User::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
    public function updateCountries(Request $request)
    {
        $data_file = public_path('all-countries/countries.json');
        $content = File::get($data_file);
        $countries = json_decode($content,TRUE);
        foreach ($countries as $country) {
            $new_country = Country::where("name",$country['name'])->first();
            if (!$new_country){
                $new_country = new Country();
            }
            $new_country->name = $country['name'];
            $new_country->save();
            if ( array_key_exists("filename", $country)){
                $name = $country['filename'].".json";
                $data_file = public_path("all-countries/countries/$name");
                $content = File::get($data_file);
                $provinces = json_decode($content,TRUE);
                foreach ($provinces as $province) {
                    $new_province = Provinces::where([['name',$province['name'],['country_id',$new_country->id]]])->first();
                    if (!$new_province) {
                        $new_province = new Provinces();
                    }
                    $new_province->name = $province['name'];
                    $new_province->country_id = $new_country->id;
                    $new_province->save();
                }
            }

        }
        dd("done");
    }
    public function search(Request $request)
    {

        $user = Job::where("serial_number",$request->keyword)
            ->orWhere("id",$request->keyword)
            ->orWhereHas('customer', function ($query) use ($request) {
                $query->where('phone', $request->keyword);
            })->first();

        return view('frontend.search',compact("user"));
    }
    public function testVonage(Request $request)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://web.cloudwhatsapp.com/wapp/api/send?apikey=85264c51f6bb405a8638d17082f3027a&mobile=9884893017&msg=test messages from web',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        dd($response);
    }

   public function testMailchimp(){
       $mailchimp = new \MailchimpTransactional\ApiClient();
       $mailchimp->setApiKey('m0mXnUADtLIbRibkblpeNw');


       $response = $mailchimp->messages->send(
           [
               "message" => [
                   "from_email" => "aizeekhan007@gmail.com",
                   "from_name" => "Aizaz",
                   "subject" => "Email Testing",
                   "text" => "Email Testing ok done",
                   "html" => "<h1>Email Testing ok done</h1>",

               ],
               "to" => [
                   "email" => "gulapkhan007@gmail.com",
                   "name" => "Gulap",
                   "type" => "to",
               ]
           ]
       );
       dd($response);
   }
    public function testSMTP() {
        try {
            $user = (object) [
                'name' => 'Aizaz',
                'email' => 'aizeekhan007@gmail.com',
            ];
            $configuration = [
                'smtp_host'    => 'mail.webexert.us',
                'smtp_port'    => '465',
                'smtp_username'  => 'noreply@webexert.us',
                'smtp_password'  => 'LiB3ds9^euRq',
                'smtp_encryption'  => 'ssl',
                'from_email'    => 'noreply@webexert.us',
                'from_name'    => 'FoneFix',
                'replyTo_email'    => 'noreply@webexert.us',
                'replyTo_name'    => 'FoneFix',
            ];


            $this->approach2($configuration, $user);

            return true;
        } catch (\Throwable $th) {
            throw $th;
            return false;
        }
    }
    public function approach1($configuration, $user) {
        $mailer = app()->makeWith('custom.smtp.mailer', $configuration);
        $mailer->to( $user->email )->send( new DynamicSMTPMail($user->name, ['email' => $configuration['from_email'], 'name' => $configuration['from_name']]) );
    }
    public function approach2($configuration, $user) {

        $backup = Mail::getSwiftMailer();
        $transport = (new Swift_SmtpTransport(
            $configuration['smtp_host'],
            $configuration['smtp_port'],
            $configuration['smtp_encryption']))
            ->setUsername($configuration['smtp_username'])
            ->setPassword($configuration['smtp_password']);
        $maildoll = new Swift_Mailer($transport);
        Mail::setSwiftMailer($maildoll);
        $settings = Setting::pluck('value', 'name')->all();
        $data = array(
            'name' => $user->name,
            'user_email' => $user->email,
            'from_email' => $configuration['from_email'],
            'from_name' => $configuration['from_name'],
            'subject' => "New Order is Placed",

            'msg' => "Your Order is Successfully placed",
            'email' => $configuration['from_email'],
            'logo' => isset($settings['logo']) ? $settings['logo']: '',
            'site_title' => isset($settings['site_title']) ? $settings['site_title']: 'Libby Kitchen',
        );
        Mail::send('emails.order', $data, function ($message) use ($data) {
            $message->to($data['user_email'])
                ->from($data['user_email'],$data['from_name'])
                ->subject($data['subject']);
        });
//        Mail::to(  $user->email )->send( new DynamicSMTPMail( $user->name, ['email' => $configuration['from_email'], 'name' => $configuration['from_name']] ) );
        Mail::setSwiftMailer($backup);
    }

    public function approach3($configuration, $user) {
        $backup = Config::get('mail.mailers.smtp');
        Config::set('mail.mailers.smtp.host', $configuration['smtp_host']);
        Config::set('mail.mailers.smtp.port', $configuration['smtp_port']);
        Config::set('mail.mailers.smtp.username', $configuration['smtp_username']);
        Config::set('mail.mailers.smtp.password', $configuration['smtp_password']);
        Config::set('mail.mailers.smtp.encryption', $configuration['smtp_encryption']);
        Config::set('mail.mailers.smtp.transport', 'smtp');
        $settings = Setting::pluck('value', 'name')->all();
        $data = array(
            'name' => $user->name,
            'user_email' => $user->email,
            'from_email' => $configuration['from_email'],
            'from_name' => $configuration['from_name'],
            'subject' => "New Order is Placed",

            'msg' => "Your Order is Successfully placed",
            'email' => $configuration['from_email'],
            'logo' => isset($settings['logo']) ? $settings['logo']: '',
            'site_title' => isset($settings['site_title']) ? $settings['site_title']: 'Libby Kitchen',
        );
        Mail::send('emails.order', $data, function ($message) use ($data) {
            $message->to($data['user_email'])
                ->from($data['user_email'],$data['from_name'])
                ->subject($data['subject']);
        });

//        Mail::to(  $user->email )->send(new DynamicSMTPMail( $user->name, ['email' => $configuration['from_email'], 'name' => $configuration['from_name']] ));
        Config::set('mail.mailers.smtp', $backup);
    }
    public function sendMessage()
    {



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://sms.bulksmsind.in/v2/sendSMS?username=thefonefix21&message=Dear,Web%20Your%20Mobile%20Repair%20Info:test,RepairID:test,Estimate%20Delivery:test,Brand:test,Model:test,Click%20testTHE%20FONE%20FIX&sendername=FONFIX&smstype=TRANS&numbers=9094281234&apikey=8721ed80-7591-41c4-a96c-76a9c1768fec&peid=1701166624754147545&templateid=1707166641953024774',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        dd($response);
    }
}
