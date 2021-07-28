<?php

namespace App\Http\Controllers\admin;


use App\Booking;
use App\Category;
use App\Country;
use App\Days;
use App\Enquiry;
use App\HotelBooking;
use App\Hotels;
use App\Packages;
use App\TripRequest;
use App\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
 
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		
        $uid=Auth::User()->id;

        //$booking=Booking::orderby('id','asc')->get();
        $booking=Booking::orderBy('id','desc')->with('package_details')->get();
        $enquiry=Enquiry::orderby('id','desc')->paginate(5);
        return view('home',compact('booking','enquiry'));
    }
    public function changepassword()
    {
        $uid=Auth::User()->id;
        $user=User::where('id',$uid)->first();
        $user_email=$user->email;

     return view('admin.changepassword',compact('user_email'));

    }


    public function passwordchange(Request $request)
    {

        $request->validate([

            'old_password' => 'required',
            'new_password' => 'required',
            ]);


        User::where('email',$request->email)->update(['password'=>bcrypt($request->new_password)

        ]) ;
        flash('Updated Successfully')->important()->success();
        return back();


    }
    public function viewBookings()
    {
        $bookings=Booking::orderBy('id','desc')->paginate(8);
        return view('admin.viewBookings',compact ('bookings'));
    }
    public function viewhotelBookings()
    {

        $bookings=HotelBooking::orderBy('id','desc')->paginate(8);
        return view('admin.viewhotelBookings',compact ('bookings'));
    }
    public function viewEnquiries()
    {
        $enquiries=Enquiry::orderby('id','desc')->paginate(8);
        return view('admin.viewEnquiries',compact ('enquiries'));
    }
    public function viewTriprequests()
    {
        $triprequests=TripRequest::orderby('id','desc')->paginate(8);
        return view('admin.triprequests',compact ('triprequests'));
    }





}
