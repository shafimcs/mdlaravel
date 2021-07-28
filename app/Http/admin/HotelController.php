<?php

namespace App\Http\Controllers\admin;

use App\Destination;
use App\HotelRating;
use App\HotelRoom;
use App\Hotels;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function  createHotel()
    {
        $destinations=Destination::orderBy('id','desc')->get();
        return view('admin.hotel.addHotel',compact('destinations'));
    }
    public function  storeHotel(Request $request)
    {
        $inputs=$request->all();
        $file = $request['image'];
        if ($file) {
            $path = $file->storeAs('image/hotels', str_slug($request->hotel_name) . mt_rand() . '.' . $file->extension(), 'uploads');
            $inputs['image']=$path;
        }

        $hotel=Hotels::create($inputs);
        $images = $request->hotel_rooms;
        if (isset($images)) {
            foreach ($images as $image) {
                if ($image) {
                    $path = $image->storeAs('image/hotelrooms', str_slug($request->hotel_name.'room') . mt_rand() . '.' . $image->extension(), 'uploads');
                    HotelRoom::create(['hotel_id' => $hotel['id'],
                        'image' => $path]);
                }
            }
        }

        flash('Added Successfully')->important()->success();
        return redirect()->route('hotel.show');

    }

    public function  showHotel()
    {
        $hotels=Hotels::orderBy('id','desc')->get();
        return view('admin.hotel.showHotel',compact('hotels'));

    }
    public function  editHotel($id)
    {
        $hotel=Hotels::where('id',$id)->first();
        $hotel_rooms=HotelRoom::where('hotel_id',$id)->get();
        $destinations=Destination::orderBy('id','desc')->get();
        return view('admin.hotel.editHotel',compact('hotel','destinations','hotel_rooms'));

    }
    public function  updateHotel(Request $request)
    {
        $inputs=$request->all();
       // dd($inputs);
        $file = $request['image'];
        if ($file) {
            $path = $file->storeAs('image/hotels', str_slug($request->hotel_name) . mt_rand() . '.' . $file->extension(), 'uploads');
            $inputs['image']=$path;
        }
        $hotel=Hotels::where('id',$request->id)->firstOrFail();
        $hotel->update($inputs);
        $images = $request->hotel_rooms;
        if (isset($images)) {
            foreach ($images as $image) {
                if ($image) {
                    $path = $image->storeAs('image/hotelrooms', str_slug($request->hotel_name.'room') . mt_rand() . '.' . $image->extension(), 'uploads');
                    HotelRoom::create(['hotel_id' => $inputs['id'],
                        'image' => $path]);
                }
            }
        }
        flash('Updated Successfully')->important()->success();
        return redirect()->route('hotel.show');

    }
    public function deleteHotel(Request $request)
    {
        $delete_hotel=Hotels::where('id',$request->id)->first();
        $delete_hotel->delete();
        $delete_hotelrooms=HotelRoom::where('hotel_id',$request->id)->delete();
        $msg='Deleted Succesfully';
        return response()->json($msg);

    }

    public function deleteHotelroom(Request $request)
    {
        $delete_hotel_rooms=HotelRoom::where('id',$request->id)->first();
        $delete_hotel_rooms->delete();
        return response()->json(true);

    }
    public function reviews($id)
    {
        $reviews=HotelRating::where('hotel_id',$id)->get();
        return view('admin.hotel.reviews',compact('reviews'));

    }
    public function hotelReviewdelete(Request $request)
    {
        $review=HotelRating::where('id',$request->id)->first();
        $review->delete();
        $msg='Deleted Succesfully';
        return response()->json($msg);

    }
    public function pushHoteltoHome(Request $request)
    {
        $review=HotelRating::where('id',$request->id)->first();
        $review->update(['flag'=>'1']);
        $msg='Pushed Succesfully';
        return response()->json($msg);

    }
    public function pushHotelBack(Request $request)
    {
        $review=HotelRating::where('id',$request->id)->first();
        $review->update(['flag'=>'0']);
        $msg='Push Back Succesfully';
        return response()->json($msg);

    }




}
