<?php

namespace App\Http\Controllers\admin;

use App\Destination;
use App\DestinationRating;
use App\Http\Controllers\Controller;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DestinationController extends Controller
{
   public function createDestination()
   {
       $location=Location::get();
       return view('admin.destination.addDestination',compact('location'));
   }
   public function storeDestination(Request $request)
   {
       $input=$request->all();
       $file = $request['image'];
       if ($file) {
           $path = $file->storeAs('image/destinations', str_slug($request->destination) . mt_rand() . '.' . $file->extension(), 'uploads');
           $destination=Destination::create(['location'=>$request->location,
               'destination'=>$request->destination,
               'image'=>$path,
               'best_month_from'=>$request->best_month_from,
               'best_month_to'=>$request->best_month_to,
               'best_day_from'=>$request->best_day_from,
               'best_day_to'=>$request->best_day_to,
               'nearest_airport'=>$request->nearest_airport,
               'nearest_railway_station'=>$request->nearest_railway_station,
               'description'=>$request->description,
               'about'=>$request->about,
               'things_to_do'=>$request->things_to_do,
               'hotels'=>$request->hotels,
               'resorts'=>$request->resorts,
               'tourist_places'=>$request->tourist_places,
               'how_to_reach'=>$request->how_to_reach,
               'shop_at'=>$request->shop_at,
               'eat_at'=>$request->eat_at,
               'best_time'=>$request->best_time,
               'top_things_to_do'=>$request->top_things_to_do
           ]);
       }
       else
       {
           $destination=Destination::create(['location'=>$request->location,
               'destination'=>$request->destination,
               'best_month_from'=>$request->best_month_from,
               'best_month_to'=>$request->best_month_to,
               'best_day_from'=>$request->best_day_from,
               'best_day_to'=>$request->best_day_to,
               'nearest_airport'=>$request->nearest_airport,
               'nearest_railway_station'=>$request->nearest_railway_station,
               'description'=>$request->description,
               'about'=>$request->about,
               'things_to_do'=>$request->things_to_do,
               'hotels'=>$request->hotels,
               'resorts'=>$request->resorts,
               'tourist_places'=>$request->tourist_places,
               'how_to_reach'=>$request->how_to_reach,
               'shop_at'=>$request->shop_at,
               'eat_at'=>$request->eat_at,
               'best_time'=>$request->best_time,
               'top_things_to_do'=>$request->top_things_to_do
           ]);
       }

       flash('Created Successfully')->important()->success();
       return redirect()->route('destination.show');
   }

   public function  showDestinationImage(Request $request)
   {
       $image=Location::where('location',$request->location)->pluck('image');
       return response()->json($image);
   }
   public function  showDestinations()
   {
       $destinations=Destination::orderBy('updated_at','desc')->get();
       return view('admin.destination.show',compact('destinations'));
   }
   public function  editDestination($id)
   {
       $location=Location::get();
       $destination=Destination::where('id',$id)->first();
       return view('admin.destination.edit',compact('destination','location'));
   }
   public function  updateDestination(Request $request)
   {
       $inputs=$request->all();
       $file = $request['image'];
       if ($file) {
           $path = $file->storeAs('image/destinations', str_slug($request->destination) . mt_rand() . '.' . $file->extension(), 'uploads');
           $update_destination=Destination::where('id',$request->id)->update(['location'=>$request->location,
               'destination'=>$request->destination,
               'image'=>$path,
               'best_month_from'=>$request->best_month_from,
               'best_month_to'=>$request->best_month_to,
               'best_day_from'=>$request->best_day_from,
               'best_day_to'=>$request->best_day_to,
               'nearest_airport'=>$request->nearest_airport,
               'nearest_railway_station'=>$request->nearest_railway_station,
               'description'=>$request->description,
               'about'=>$request->about,
               'things_to_do'=>$request->things_to_do,
               'hotels'=>$request->hotels,
               'resorts'=>$request->resorts,
               'tourist_places'=>$request->tourist_places,
               'how_to_reach'=>$request->how_to_reach,
               'shop_at'=>$request->shop_at,
               'eat_at'=>$request->eat_at,
               'best_time'=>$request->best_time,
               'top_things_to_do'=>$request->top_things_to_do

               ]);

       }
	   else
	   {
		    $update_destination=Destination::where('id',$request->id)->update(['location'=>$request->location,
           'destination'=>$request->destination,
           'best_month_from'=>$request->best_month_from,
           'best_month_to'=>$request->best_month_to,
           'best_day_from'=>$request->best_day_from,
           'best_day_to'=>$request->best_day_to,
           'nearest_airport'=>$request->nearest_airport,
           'nearest_railway_station'=>$request->nearest_railway_station,
           'description'=>$request->description,
           'about'=>$request->about,
           'things_to_do'=>$request->things_to_do,
           'hotels'=>$request->hotels,
           'resorts'=>$request->resorts,
           'tourist_places'=>$request->tourist_places,
           'how_to_reach'=>$request->how_to_reach,
           'shop_at'=>$request->shop_at,
           'eat_at'=>$request->eat_at,
           'best_time'=>$request->best_time,
           'top_things_to_do'=>$request->top_things_to_do
   ]);
	   }
      
       flash('Updated Successfully')->important()->success();
       return redirect()->route('destination.show');

   }
    public function  deleteDestination(Request $request)
    {
        $delete_location=Destination::where('id',$request->id)->first();
        $delete_location->delete();
        $msg='Destination deleted';
        return response()->json($msg);
    }
    public function destinationReviews($id)
    {
        $reviews=DestinationRating::where('destination_id',$id)->get();
        return view('admin.destination.reviews',compact('reviews'));

    }
    public function pushPackagetoHome(Request $request)
    {
        $review=DestinationRating::where('id',$request->id)->first();

            $review->update(['flag'=>'1']);
            $msg='Pushed Successfully';
              return response()->json($msg);



    }
    public function pushPackageBack(Request $request)
    {
        $review=DestinationRating::where('id',$request->id)->first();

            $review->update(['flag'=>'0']);
            $msg='Push Back Successfully';
              return response()->json($msg);



    }
    public function destinationReviewdelete(Request $request)
    {
        $review=DestinationRating::where('id',$request->id)->first();
        $review->delete();
        $msg='Deleted Succesfully';
        return response()->json($msg);

    }

}
