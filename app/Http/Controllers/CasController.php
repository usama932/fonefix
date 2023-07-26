<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class CasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function casLogin()
    {
        if (cas()->isAuthenticated()){
//            dd(cas()->user());
            $user = User::where("cas_id",cas()->user())->first();
            if ($user){
                Auth::login($user);
                if (Auth::check()){
                    return redirect()->route("admin.dashboard");
                }
            }else{
                return redirect()->route("login");
            }

//            if (Auth::attempt(['cas_id' => $email)) {
//                // The user is active, not suspended, and exists.
//            }
        }else{
//            dd("not loged in");
            return redirect()->route("login");
        }
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function adminHome()
    {
        return view('admin-home');
    }
}
