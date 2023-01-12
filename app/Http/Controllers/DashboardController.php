<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Contact;
use App\Nghiphep;
// use LoLong\Invoice\Models\Invoice;

class DashboardController extends Controller
{
    /**
    * Check authorization and display admin dashboard, otherwise display
    * the user's checked-out assets.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return View
    */
    public function getIndex()
    {
        // Contact::find(1)->video([
        //     'title' => "uQ0XteuY-08",
        // ], Auth::user());
        // Show the page
        // Invoice::find(1);
        return redirect('/dashboard');
        if (Auth::user()->can('sale')) {
            return redirect('/don-hang');
        } else {
            return redirect('/profile');
        }
    }

    public function checkPass() {
        $status = Auth::user()->status;
        if($status == 0) {
            return view('reset', ['id' => Auth::user()->id]);
        }
        if($status == 1) {
            return redirect('/dashboard');
            if (Auth::user()->can('sale')) {
                return redirect('/don-hang');
            } else {
                return redirect('/profile');
            }
        }
    }

    public function reset(Request $request)
    {
        $this->validate($request,[
            'password' => 'required|min:6'
        ],
        [
            'password.required'=>'Bạn chưa nhập pass',
            'password.min'=>'mật khẩu phải có ít nhất 6 ký tự',
        ]);
        $user = User::find($request['id']);
        $user->password = Hash::make($request['password']);
        $user->status = 1;
        $user->save();
        return redirect('/dashboard');
    }
}
