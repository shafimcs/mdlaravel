<?php

namespace App\Http\Controllers\admin;

use App\Country;
use App\DestinationCategory;
use App\Http\Controllers\Controller;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LocationController extends Controller
{
   public function locationCreate()
   {
       $country=Country::orderBy('id','desc')->get();
       return view('admin.location.createLocation',compact('country'));
   }
   public function storeLocation(Request $request)
   {

          $file = $request['image'];
           if ($file) {
               $path = $file->storeAs('image/location', str_slug($request->location) . mt_rand() . '.' . $file->extension(), 'uploads');

           }


       $store_category=Location::create(['country'=>$request->country,'location'=>$request->location,'image'=>$path]);
       flash('Location Added Successfully')->important()->success();
       return redirect()->route('location.index');

   }

   public function indexLocation()
   {
       $location=Location::orderBy('id','desc')->get();
       return view('admin.location.indexLocation',compact('location'));
   }
   public function editLocation($id)
   {
       $location=Location::where('id',$id)->first();
       $country=Country::orderBy('id','desc')->get();
       return view('admin.location.editLocation',compact('location','country'));
   }
    public function updateLocation(Request $request)
    {
        $location=Location::find($request->id);
        $inputs=request()->except(['_token']);;
        $file = $request['image'];
        if ($file) {
            $path = $file->storeAs('image/location', str_slug($request->location) . mt_rand() . '.' . $file->extension(), 'uploads');
            $inputs['image']=$path;

        }
        else
        {
            $inputs['image'] = $location->image;
        }

        $update_category=Location::where('id',$request->id)->update($inputs);
        flash('Location Updated Successfully')->important()->success();
        return redirect()->route('location.index');

    }
    public function deleteLocation(Request $request)
    {
        $delete_location=Location::where('id',$request->id)->first();
        $delete_location->delete();
        $msg='Location deleted';
        return response()->json($msg);

    }
}
