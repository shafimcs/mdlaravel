<?php

namespace App\Http\Controllers\admin;

use App\Category;
use App\Country;
use App\Days;
use App\Destination;
use App\DestinationRating;
use App\Hotels;
use App\Location;
use App\PackageHotels;
use App\PackageImages;
use App\PackageRating;
use App\Packages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class PackageController extends Controller
{
    public  function  create()
    {
        $category = Category::orderby('id')->get();
        $country = Country::orderby('id')->get();
        $location=Location::orderby('id')->get();
        $hotels=Hotels::orderBy('id')->get();
        $categories=Category::orderBy('id')->get();
        $destinations=Destination::orderBy('id')->get();
        return view('admin.package.addPackages',compact('category','country','location','hotels','categories','destinations'));
    }
    public  function  store(Request $request)
    {

       $inputs=$request->all();

      /* $packages= $request->validate([
           // 'category_id' => 'required',
            'package_name' => 'required|max:255',
            'package_details' => 'required',
          //  'image' => 'required',
            'country_name' => 'required',
            'location' => 'required',
            'places' => 'required',
            'number_of_days' => 'numeric|required',
            'number_of_nights' => 'numeric|required',
            'number_of_people' => 'numeric|required',
            'standard' => 'numeric|required',
            'deluxe' => 'numeric|required',
            'luxury' => 'numeric|required',
            'start_date' => 'required|date|after:yesterday',
            'end_date' => 'required|date',
            'inclusion' => 'required',
            'exclusion' => 'required',
             'title'=>'required',
            'description'=>'required',

        ]);*/

        $file=$request->file('image');

        if(!empty($file))
        {
            $path=$file->storeAs('image/category',str_slug($request->package_name).mt_rand(). '.' . $file->extension(),'uploads');

            $values['image']=$path;
        }
        $package =  Packages::create([
            'gotripz_id'=>$request->gotripz_id,
            'package_name'=>$request->package_name,
            'package_details'=>$request->package_details,
            'image'=>$path,
            'country_name'=>$request->country_name,
            'location'=>$request->location,
            'category_id'=>$request->category_id,
            'destination'=>$request->destination,
            'package_category'=>$request->package_category,
            'places'=>$request->places,
            'days'=>$request->number_of_days,
            'nights'=>$request->number_of_nights,
            'number_of_people'=>$request->number_of_people,
            'economy'=>$request->economy,
            'deluxe'=>$request->deluxe,
            'premium'=>$request->premium,
            'luxury'=>$request->luxury,
            'premium_luxury'=>$request->premium_luxury,
            'budget'=>$request->budget,
            'economy_hotel_id'=>$request->economy_hotel_id,
            'deluxe_hotel_id'=>$request->deluxe_hotel_id,
            'premium_hotel_id'=>$request->premium_hotel_id,
            'luxury_hotel_id'=>$request->luxury_hotel_id,
            'premium_luxury_hotel_id'=>$request->premium_luxury_hotel_id,
            'budget_hotel_id'=>$request->budget_hotel_id,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'inclusion'=>$request->inclusion,
            'exclusion'=>$request->exclusion,
            'other_details'=>$request->other_details,
            'title'=>$request->title,
            'description'=>$request->description,
            ]);
        //Packages::where('id',$package->id)->update(['gotripz_id'=>'GTZ00'.$package->id]);


        $i=1;
        $arr=$request->descriptions;
        if(!empty($arr)) {
            foreach ($arr as $days )
            {
                Days::create(['package_id'=>$package->id,
                    'day_number'=>$i,
                    'description'=>$days]);
                $i++;
            }

        }

    /*   if($request->economy)
       {
           if($request->economy_hotel_id)
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'economy','type_amount'=>$request->economy,'hotel_id'=>$request->economy_hotel_id]);
           }
           else
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'economy','type_amount'=>$request->economy]);
           }
       }
       if($request->deluxe)
       {
           if($request->deluxe_hotel_id)
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'deluxe','type_amount'=>$request->deluxe,'hotel_id'=>$request->deluxe_hotel_id]);
           }
           else
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'deluxe','type_amount'=>$request->deluxe]);
           }
       }

       if($request->premium)
       {
           if($request->premium_hotel_id)
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'premium','type_amount'=>$request->premium,'hotel_id'=>$request->premium_hotel_id]);
           }
           else
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'premium','type_amount'=>$request->premium]);
           }
       }

       if($request->luxury)
       {
           if($request->luxury_hotel_id)
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'luxury','type_amount'=>$request->luxury,'hotel_id'=>$request->luxury_hotel_id]);
           }
           else
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'luxury','type_amount'=>$request->luxury]);
           }
       }

       if($request->premium_luxury)
       {
           if($request->premium_luxury_hotel_id)
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'premium_luxury','type_amount'=>$request->premium_luxury,'hotel_id'=>$request->premium_luxury_hotel_id]);
           }
           else
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'premium_luxury','type_amount'=>$request->premium_luxury]);
           }
       }

       if($request->budget)
       {
           if($request->budget_hotel_id)
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'budget','type_amount'=>$request->budget,'hotel_id'=>$request->budget_hotel_id]);
           }
           else
           {
               PackageHotels::create(['package_id'=>$package->id,'type'=>'budget','type_amount'=>$request->budget]);
           }
       }

*/









       /* if(!empty($hotels))
        {
            for($j=0;$j<count($hotels);$j++)
            {
                PackageHotels::create(['package_id'=>$package->id,
                    'hotel_id'=>$hotels[$j]]);

            }
        }*/

        $images = $inputs['package_image'];
        if (isset($images)) {
            foreach ($images as $image) {
                if ($image) {
                    $path = $image->storeAs('image/packageimages', str_slug($request->package_name.'room') . mt_rand() . '.' . $image->extension(), 'uploads');
                    PackageImages::create(['package_id' => $package->id,
                        'image' => $path]);
                }
            }
        }



        flash('Added Successfully')->important()->success();
        return redirect()->route('package.show');
    }


    public  function  show()
    {
        // dd("hi");
        /*  $category = Category::paginate(5);*/
        $packages = Packages::with('get_hotels','get_hotels.hotels')->orderBy('id','desc')->get();
      //  dd($packages[0]->get_hotels[0]->hotels());
        return view('admin.package.viewPackages',compact('packages'));
    }
    public  function  edit($id)
    {
        $editPackages=Packages::with('daysDetail','get_hotels')->where('id',$id)->get();
        $package_images=PackageImages::where('package_id',$id)->get();
        $categories=Category::orderby('id')->get();
        $hotels=Hotels::orderBy('id')->get();
        $days=Days::orderBy('day_number')->where('package_id',$id)->get();
        $country = Country::orderby('id')->get();
        $location=Location::orderby('id')->get();
        $category = Category::orderby('id')->get();
        $destinations=Destination::orderBy('id')->get();
        return view('admin.package.editPackage',compact('editPackages','categories','country',
            'location','hotels','destinations','package_images','days'));
    }



    public  function  update(Request $request)
    {

        $total_days=Days::where('package_id',$request->id)->get();
        for($i=count($total_days);$i<count($request->descriptions);$i++)
        {
            $var=$i + 1;
            Days::create([
                'package_id'=>$request->id,
                'day_number'=>$var,
                'description'=>$request->descriptions[$i]]);
            Packages::where('id',$request->id)->update(['days'=>count($request->descriptions)]);
        }

        for($i=1;$i<=count($request->descriptions);$i++)
        {
            $var=$i-1;
            Days::where('package_id',$request->id)->where('day_number',$i)->update([
                'description'=>$request->descriptions[$var]]);

        }

        $inputs=$request->all();
        $file=$request->file('image');
        $values=$request->except(['_token']);
        if(!empty($file)) {
            $path = $file->storeAs('image/category', str_slug('slide') . mt_rand() . '.' . $file->extension(), 'uploads');
            $values['image'] = $path;
            $package =  Packages::where('id',$request->id)->update([
                'gotripz_id'=>$request->gotripz_id,
                'package_name'=>$request->package_name,
                'package_details'=>$request->package_details,
                'image'=>$path,
                'country_name'=>$request->country_name,
                'location'=>$request->location,
                'places'=>$request->places,
                'category_id'=>$request->category_id,
                'destination'=>$request->destination,
                'package_category'=>$request->package_category,
                'days'=>count($request->descriptions),
                'nights'=>$request->number_of_nights,
                'number_of_people'=>$request->number_of_people,
                'economy'=>$request->economy,
                'deluxe'=>$request->deluxe,
                'premium'=>$request->premium,
                'luxury'=>$request->luxury,
                'premium_luxury'=>$request->premium_luxury,
                'budget'=>$request->budget,
                'economy_hotel_id'=>$request->economy_hotel_id,
                'deluxe_hotel_id'=>$request->deluxe_hotel_id,
                'premium_hotel_id'=>$request->premium_hotel_id,
                'luxury_hotel_id'=>$request->luxury_hotel_id,
                'premium_luxury_hotel_id'=>$request->premium_luxury_hotel_id,
                'budget_hotel_id'=>$request->budget_hotel_id,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
                'inclusion'=>$request->inclusion,
                'exclusion'=>$request->exclusion,
                'other_details'=>$request->other_details,
                'title'=>$request->title,
                'description'=>$request->description,
            ]);
        }
      /*  $package=Packages::where('id',$request->id)->update($values);*/
        else
        {
            $package =  Packages::where('id',$request->id)->update([
                'gotripz_id'=>$request->gotripz_id,
                'package_name'=>$request->package_name,
                'package_details'=>$request->package_details,
                'country_name'=>$request->country_name,
                'location'=>$request->location,
                'places'=>$request->places,
                'category_id'=>$request->category_id,
                'destination'=>$request->destination,
                'package_category'=>$request->package_category,
                'days'=>count($request->descriptions),
                'nights'=>$request->number_of_nights,
                'number_of_people'=>$request->number_of_people,
                'economy'=>$request->economy,
                'deluxe'=>$request->deluxe,
                'premium'=>$request->premium,
                'luxury'=>$request->luxury,
                'premium_luxury'=>$request->premium_luxury,
                'budget'=>$request->budget,
                'economy_hotel_id'=>$request->economy_hotel_id,
                'deluxe_hotel_id'=>$request->deluxe_hotel_id,
                'premium_hotel_id'=>$request->premium_hotel_id,
                'luxury_hotel_id'=>$request->luxury_hotel_id,
                'premium_luxury_hotel_id'=>$request->premium_luxury_hotel_id,
                'budget_hotel_id'=>$request->budget_hotel_id,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
                'inclusion'=>$request->inclusion,
                'exclusion'=>$request->exclusion,
                'other_details'=>$request->other_details,
                'title'=>$request->title,
                'description'=>$request->description,
            ]);


        }

        /*$j=1;
        $hotels=$request->hotels;
        if(!empty($hotels))
        {
            for($j=0;$j<count($hotels);$j++)
            {
                PackageHotels::where('package_id',$request->id)->update(['hotel_id'=>$hotels[$j]]);


            }
        }*/




        $images = $request['package_image'];
        if (isset($images)) {
            foreach ($images as $image) {
                if ($image) {
                    $path = $image->storeAs('image/packageimages', str_slug($request->package_name.'package') . mt_rand() . '.' . $image->extension(), 'uploads');
                    PackageImages::create(['package_id' => $request->id,
                        'image' => $path]);
                }
            }
        }

      flash('Updated Successfully')->important()->success();
        return redirect()->route('package.show');

    }



    public function destroy($id) {
        //dd($id);
        $package = Packages::findOrFail($id);
        $day=Days::where('package_id',$id)->get();
        $package->delete();
        //$day->delete();
        flash('Deleted Successfully')->important()->success();
        return redirect()->route('package.show');
    }

    public function locationList(Request $request)
    {
        $country=$request->country;
        $locations=Location::where('country',$country)->get();
        return response()->json($locations);

    }

    public function packageReviews($id,$package_name)
    {
       $reviews=PackageRating::where('package_id',$id)->get();
       return view('admin.package.reviews',compact('reviews','package_name'));

    }
    public function packageReviewdelete(Request $request)
    {
        $review=PackageRating::where('id',$request->id)->first();
        $review->delete();
        $msg='Deleted Succesfully';
        return response()->json($msg);

    }

    public function pushPackagetoHome(Request $request)
    {
        $review=PackageRating::where('id',$request->id)->first();
        $review->update(['flag'=>'1']);
        $msg='Pushed Succesfully';
        return response()->json($msg);

    }
   public function deletePackageimage(Request $request)
    {
        $delete_package_image=PackageImages::where('id',$request->id)->first();
        $delete_package_image->delete();
        return response()->json(true);
    }

    public function pushPackageBack(Request $request)
    {
        $review=PackageRating::where('id',$request->id)->first();
        $review->update(['flag'=>'0']);
        $msg='Push Back Succesfully';
        return response()->json($msg);

    }





}
