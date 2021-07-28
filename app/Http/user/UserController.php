<?php

namespace App\Http\Controllers\user;

use App\Booking;
use App\Category;
use App\Contactus;
use App\Days;
use App\Destination;
use App\DestinationRating;
use App\Enquiry;
use App\HotelBooking;
use App\HotelRating;
use App\HotelRoom;
use App\Hotels;
use App\Http\Requests\EnquiryRequest;
use App\Location;
use App\PackageHotels;
use App\PackageImages;
use App\PackageRating;
use App\Packages;
use App\Review;
use App\Slider;
use App\Testimonial;
use App\TripRequest;
use Gmopx\LaravelOWM\LaravelOWM;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Validator;
use PhpParser\Node\Stmt\Foreach_;

class UserController extends Controller
{
   public function contactus()
   {
       $testimonials=Testimonial::orderBy('id','desc')->get();
       return view('user.contactus',compact('testimonials'));
   }
   public function about_us()
   {
       return view('user.aboutus');
   }
    public function viewMainCategory()
    {

        $location=Location::orderby('id','desc')->where('country','=','India')->paginate(12);
        $package_reviews=PackageRating::where('flag','1')->orderby('id','desc')->get()->take(2);
        $hotel_reviews=HotelRating::where('flag','1')->orderby('id','desc')->get()->take(2);
        $destination_reviews=DestinationRating::with('get_destination')->where('flag','1')->orderby('id','desc')->get()->take(2);
        $outbound_location=Location::with(['get_all_packages'=>function($q){
            $q->min('economy');
        }])->orderby('id','desc')->where('country','!=','India')->paginate(15);
        $testimonial=Testimonial::orderby('id')->get();
        $sliders=Slider::orderby('id','desc')->get();

        $destinations=Destination::with(['get_packages'=>function($q){
            $q->min('economy');
        }])->orderBy('id','desc')->get();
$maincategory='';
//        dd($destinations);
        $package_types=Category::with(['all_packages'=>function($q){
            $q->min('economy');
        }])->orderBy('updated_at','id')->get();
        return view('welcome',compact('maincategory','outbound_location','testimonial','location','package_reviews','hotel_reviews','destination_reviews','sliders','destinations','package_types'));
    }

    public function showsubCategories($slug)
    {

        $category=Category::where('slug',$slug)->first();

        $parent=$category->category_name;
        $metatags=$category->description;
        if(!empty($category))
        {
             $subcategory=Category::orderby('id','asc')->where('parent_id',$category->id)->get();

            if(count($subcategory))
            {

                return view('user.showsubCategories',compact('subcategory','parent','metatags'));
            }
            else
            {

                return redirect()->route('user.packages.show',$slug);
                /*  return view('user.showPackages',compact('packages','name'));*/
            }
        }
        else
          {

           return view('404');

          }

      // $category=Category::where('parent_id',$subcategory->id)->with('subcategory')->find($subcategory->id);

    }



     public function showPackages($slug)
     {
        $category=Category::where('slug',$slug)->first();
        $parent=$category->category_name;
        $metatags=$category->description;
        if(!empty($category))
        {
            $packages = Packages::where('category_id', $category->id)->get();
            if (count($packages))
            {

                return view('user.showPackages', compact('packages','parent','metatags'));
            }
            else
                {

                return view('noPackageAvailable',compact('parent','metatags'));
            }
        }
        else
        {
            return view('404');
        }

    }
    public function showfullPackage($slug)
    {
        $package=Packages::with('daysDetail')->where('slug',$slug)->first();
        $package_rating=PackageRating::where('package_id',$package->id)->get();
        $ratings= $package_rating->avg('rating');
        $package=Packages::with('daysDetail')->where('slug',$slug)->first();
        if(!empty($package))
        {

            $package_id=Packages::where('slug',$slug)->first();
            $hotels=PackageHotels::with('hotels')->where('package_id', $package_id->id)->get();
            $reviews=PackageRating::where('package_id',$package_id->id)->orderBy('id','desc')->paginate(5);
            $avg_rating=PackageRating::where('package_id',$package_id->id)->avg('rating');
            $packages=Packages::orderBy('updated_at','id')->where('location',$package_id->location)->get()->take(5);
            $package_images=PackageImages::where('package_id',$package_id->id)->get();

            //dd($hotels);
            if(!empty($hotels))
            {
                //$whatIWant = substr($data, strpos($data, "*")+1);
                return view('user.showfullPackage',compact('package','hotels','reviews','packages','ratings','package_images','avg_rating'));

            }
            else
            {
                return view('user.showfullPackage',compact('package','reviews','packages','package_images','avg_rating'));

            }

        }
        else
        {
            return view('404');
        }

    }


           /*BOOKING*/

    public function booking(Request $request,$id)
    {

         $package=Packages::where('id',$id)->first();
         $package_id=$package->gotripz_id;
       $booking= Booking::create(['package_id'=>$id,
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'leave_from'=>$request->leave_from,
            'departure_date'=>$request->departure_date,
            'package_type'=>$request->package_type,
            'number_of_adult'=>$request->number_of_adult,
            'number_of_children'=>$request->number_of_children,
            'notes'=>$request->notes,
            ]);
        Mail::to('info@gotripaz.com')->send(new \App\Mail\Booking($id,$request->name, $request->email, $request->phone_number,$request->leave_from ,$request->departure_date,$request->package_type,$request->number_of_adult ,$request->number_of_children,$request->notes));
        flash('Booked Successfully')->important()->success();

        return redirect()->back();

    }

    public function hotelBooking(Request $request)
    {

       //dd($request->all());
       $booking= HotelBooking::create(['hotel_id'=>$request->hotel_id,
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'check_in'=>$request->check_in,
            
            'check_out'=>$request->check_out,
           'no_of_passenger'=>$request->num_of_passenger,
'no_of_room'=>$request->num_of_room,         
   ]);
//dd($request->all());
        $hotel=Hotels::where('id',$request->hotel_id)->first();


        Mail::to('info@gotripaz.com')->send(new \App\Mail\HotelBooking($hotel->hotel_name,$request->name, $request->email, $request->phone_number, $request->check_in, $request->check_out,$request->num_of_passenger,$request->num_of_room,$hotel));
 //     dd("ok");
 flash('Booked Successfully')->important()->success();

        return redirect()->back();

    }

    /**
     * @param EnquiryRequest $request
     * @return mixed
     */
    public function enquiry(Request $request)
    {
/*        return response()->json(true);*/

        // dd($request->all());
        if(!empty($request->destination))
        {
            $enquiry= Enquiry::create(['destination'=>$request->destination,
                'email'=>$request->email,
                'number'=>$request->number,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
            ]);
        }
        else{
            $enquiry= Enquiry::create(['destination'=>'',
                'email'=>$request->email,
                'number'=>$request->number,
                'start_date'=>$request->start_date,
                'end_date'=>$request->end_date,
            ]);
        }
        //return response()->json(true);

             /*info@gotripaz.com*/
/*        Mail::to('igenuzdevelopment@creopedia.com')->send(new \App\Mail\Enquiry($request->destination, $request->email, $request->number, $request->start_date ,$request->end_date));*/
/*       flash('Submitted Successfully')->important()->success();*/
        return Redirect::back();

    }

    public function autocompleteDestination(Request $request)
    {
    $destinations=Destination::where('destination','like','%'.$request->destination.'%')->get();
        $destinations= $destinations->map(function ($item){
           return ['id'=>$item->destination,'text'=>$item->destination];
        });
    return response()->json(['results'=>$destinations]);

    }



    public function contact(Request $request)
    {

           $contact= Contactus::create(['name'=>$request->name,
                'email'=>$request->email,
                'number'=>$request->number,
                'subject'=>$request->subjct,
                'messege'=>$request->messege,

            ]);
            Mail::to('info@gotripaz.com')->send(new \App\Mail\Contactus($request->name, $request->email, $request->number, $request->subjct ,$request->messege));
            flash('Submitted Successfully')->important()->success();

            return redirect()->back();
            /*Mail::send([‘text’=>’admin.contact’], $contact);
            return $this->from($request->email)
           ->view('admin.contact',compact('contact'));*/


       //return response()->json(true);



    }

    public function showDestinations()
    {



      /*  if (strpos($request->search, 'GTZ') !== false) {
            $pos = 7;
            $id = substr($request->search, 3, $pos);
            $package = Packages::with('daysDetail')->where('id', $id)->first();
        }*/

        $destination=Destination::with('get_locations')->orderby('id','desc')->distinct()->get();
        //$location=Location::with('get_destinations')->orderby('id','desc')->get();
       $location=Location::orderby('id','desc')->whereHas('get_destinations',function ($data){
        })->get();
        $packages=Packages::orderBy('id','desc')->paginate(6);
        $hotels=Hotels::orderBy('updated_at')->paginate(3);
        $hotels_lists=Hotels::orderBy('updated_at')->get();
        //dd($packages);
        $hotel_lists=Hotels::orderBy('updated_at','desc')->get()->take(4);

        $loc=Location::orderby('id','asc')->paginate(20);
        return view('user.destinations',compact('destination','location','loc','packages','hotels','hotels_lists','hotel_lists'));

    }
    public function searchbyLocation(Request $request)
    {
        $loc=Location::orderby('id','asc')->paginate(20);
        $hotels_lists=Hotels::orderBy('updated_at','desc')->get()->take(4);
        $hotels=Hotels::orderBy('created_at','desc')->get()->take(4);

        if($request->search)
       {
           if (strpos($request->search, 'GTZ') !== false)
           {

               $package=Packages::with('daysDetail')->where('gotripz_id',$request->search)->first();
               $packages=Packages::with('daysDetail')->orderBy('updated_at','id')->get()->take(5);

               if(!empty($package))
               {
                   $package_id=Packages::where('id',$package->id)->first();
                   $reviews=PackageRating::where('package_id',$package_id->id)->orderBy('id','desc')->paginate(5);

                   $hotels=PackageHotels::with('hotels')->where('package_id', $package_id->id)->get();
                   $package_images=PackageImages::where('package_id',$package_id->id)->get();
                   $avg_rating=PackageRating::where('package_id',$package_id->id)->avg('rating');
                   return view('user.showfullPackage',compact('packages','reviews','avg_rating','hotels_lists','package','hotels','package_images'));
               }
               else
               {
                   return view('noPackageAvailable');
               }
           }

           elseif(!empty( $lctn=Location::where('location',$request->search)->first()))
           {
               $location = Location::orderby('id', 'desc')->whereHas('get_destinations', function ($data) use ($request) {
                   $data->where('location', $request->search);
              })->get();

               $loc = Location::orderby('id', 'asc')->paginate(20);
               $packages = Packages::orderBy('id', 'desc')->paginate(6);
               $hotels = Hotels::orderBy('updated_at', 'desc')->paginate(4);
               $destination = Destination::where('location', $request->search)->get();
               $hotel_lists=Hotels::orderBy('updated_at','desc')->get()->take(4);

               if ($destination) {

                   return view('user.destinations', compact('destination', 'hotels_lists','location', 'loc', 'hotels', 'packages'));
               } else {

                   return view('user.destinations', compact('destination', 'hotels_lists','location', 'loc', 'hotels', 'packages'));
               }
           }

           elseif(!empty($dest=Destination::with('get_locations')->where('destination',$request->search)->first()))
           {
               $destination=Destination::where('destination','like','%'.$request->search.'%')->first();
               $reviews=DestinationRating::where('destination_id',$destination->id)->orderBy('id','desc')->paginate(4);
               $location=Location::orderby('id','desc')->get();
               $hotels=Hotels::where('destination',$destination->destination)->paginate(4);
               $lowm = new LaravelOWM();
               $current_weather = $lowm->getCurrentWeather($destination->destination);
               $packages=Packages::where('destination',$destination->id)->orderBy('updated_at','desc')->get();
               $other_destinations=Destination::where('location',$location)->get();
               return view('user.about',compact('destination','hotels','hotels_lists','reviews','current_weather','packages','other_destinations'));
           }

           elseif(!empty($hotel=Hotels::where('hotel_name',$request->search)->first()))
           {
               $location=Destination::where('destination','like','%'. $hotel->destination .'%')->first();
               $hotels=Hotels::where('destination',$hotel->destination)->where('id','!=',$hotel->id)->get();
               $hotel_rooms=HotelRoom::where('hotel_id',$hotel->id)->get();
               $reviews=HotelRating::where('hotel_id',$hotel->id)->orderBy('id','desc')->paginate(4);
               $destinations=Destination::orderBy('created_at','desc')->get();
               $avg_rating=HotelRating::where('hotel_id',$hotel->id)->avg('rating');

               if(!empty($location))
               {
                   $packages=Packages::where('location',$location->location)->get()->take(5);
                   if(count($packages))
                   {
                       return view('user.hotel',compact('hotel','packages','hotels','hotels_lists',
                           'hotel_lists','reviews','hotel_rooms','destinations','avg_rating'));
                   }
                   else
                   {
                       return view('user.hotel',compact('hotel','hotels','hotels_lists',
                           'hotel_lists','reviews','hotel_rooms','destinations','avg_rating'));
                   }
               }
               else
               {
                   $packages=Packages::orderBy('created_at','desc')->get()->take(5);
                   return view('user.hotel',compact('hotel','hotels','hotels_lists',
                       'hotel_lists','reviews','hotel_rooms','packages','destinations','avg_rating'));
               }

           }
           elseif(!empty($package=Packages::where('package_name',$request->search)->first()))
            {
                $package_rating=PackageRating::where('package_id',$package->id)->get();
                $ratings= $package_rating->avg('rating');
                $hotels=PackageHotels::with('hotels')->where('package_id', $package->id)->get();
                $reviews=PackageRating::where('package_id',$package->id)->orderBy('id','desc')->paginate(5);
                $avg_rating=PackageRating::where('package_id',$package->id)->avg('rating');
                $packages=Packages::orderBy('updated_at','id')->where('location',$package->location)->get()->take(5);
                $package_images=PackageImages::where('package_id',$package->id)->get();
                if(!empty($hotels))
                {
                    return view('user.showfullPackage',compact('package','hotels','hotels_lists','avg_rating','reviews','packages','ratings','package_images'));

                }
                else
                {
                    return view('user.showfullPackage',compact('package','reviews','hotels_lists','avg_rating','packages','package_images'));

                }


            }
            else
            {
                return view('nodestinationAvailable');

            }


     }
        $locations=Location::orderBy('created_at','desc')->get();

        $destination=Destination::with('get_locations')->orderby('id','desc')->distinct()->get();
        return view('user.destinations',compact('destination','location','loc','hotels_lists','hotels'));


    }


    public function destinationDetail($slug)
    {
        $destination=Destination::where('slug',$slug)->first();
        if(!empty($destination))
        {
            $lowm = new LaravelOWM();
            $current_weather = $lowm->getCurrentWeather($destination->destination);
            $hotels=Hotels::where('destination',$destination->destination)->get();
            $packages=Packages::where('destination',$destination->id)->orderBy('updated_at','desc')->get();
            $reviews=DestinationRating::where('destination_id',$destination->id)->orderBy('id','desc')->paginate(4);
            return view('user.destinationDetail',compact('destination','hotels','reviews','current_weather','packages'));
        }
        else
        {
            return view('nodestinationAvailable');

        }

    }

    public function showDestinationPackages($slug)
    {

        $destination=Destination::where('slug',$slug)->first();
        if(!empty($destination))
        {
            $packages=Packages::where('destination',$destination->id)->get();
            $hotels=Hotels::where('destination',$destination->destination)->get();
            if(!empty($packages))
            {
                return view('user.destinationPackages',compact('packages','destination','hotels'));

            }
            else
            {
                return view('noPackageAvailable');

            }

        }
        else
        {
            return view('noPackageAvailable');

        }
      //dd($hotels);


    }

  public function showlocationDestinations($location)
    {
        $destination=Destination::where('location',$location)->get();
        if(count($destination))
        {
            return view('user.locationDestinations',compact('destination','location'));

        }
        else
        {
            return view('nodestinationAvailable');

        }
    }
    public function aboutDestination($location)
    {

        $destination=Destination::where('destination',$location)->first();
        if(empty($destination))
        {
            return view('nodestinationAvailable');
        }
        else
        {
            $other_destinations=Destination::where('location',$location)->get();
            $packages=Packages::where('destination',$destination->id)->get();
            $hotels=Hotels::where('destination',$destination->destination)->orderBy('id','desc')->get();
            $reviews=DestinationRating::where('destination_id',$destination->id)->orderBy('id','desc')->get();
            $lowm = new LaravelOWM();
            $current_weather = $lowm->getCurrentWeather($destination->destination);
            if(!empty($destination))
            {
                return view('user.about',compact('destination','packages','hotels','reviews','other_destinations','current_weather'));

            }
            else
            {
                return view('nodestinationAvailable');

            }
        }

    }

    public function  showHotel($slug)
    {
        $hotel=Hotels::where('slug',$slug)->first();
        $hotels=Hotels::where('destination',$hotel->destination)->where('id','!=',$hotel->id)->get();
        $destinations=Destination::orderBy('updated_at','desc')->get();
        $location=Destination::where('destination',$hotel->destination)->first();
        $hotel_rooms=HotelRoom::where('hotel_id',$hotel->id)->get();
        $packages=Packages::where('location',$location->location)->get();
        $reviews=HotelRating::where('hotel_id',$hotel->id)->orderBy('id','desc')->paginate(4);
        $hotels_lists=Hotels::get();
        $avg_rating=HotelRating::where('hotel_id',$hotel->id)->avg('rating');
        return view('user.hotel',compact('hotel','packages','hotels','avg_rating','reviews','destinations','hotel_rooms','hotels_lists'));
    }
    public function  destinationHotels($slug)
    {
        $destination=Destination::where('slug',$slug)->first();
        $hotels=Hotels::where('destination',$destination->destination)->with('hotel_ratings')->get();
        $packages=Packages::orderBy('id','desc')->get()->take(3);
        return view('user.destinationHotels',compact('packages','hotels','destination'));
    }






    public function tripRequest()
    {
        return view('user.triprequest');
    }
    public function storetripRequest(Request $request)
    {

        $trip_request=TripRequest::create(['name'=>$request->name,
            'number'=>$request->number,
            'email'=>$request->email,
            'leave_from'=>$request->leave_from,
            'going_to'=>$request->going_to,
            'departure_date'=>$request->departure_date,
            'nights'=>$request->nights,
            'adults'=>$request->adults,
            'minors'=>$request->minors,
            'trip_type'=>$request->trip_type,
            'budget'=>$request->budget,
            'services'=>implode(",",$request->services),
            'additional_details'=>$request->additional_details,

            ]);
        Mail::to('iamshafimc@gmail.com')->send(new \App\Mail\TripRequest($request->name,$request->number, $request->email, $request->leave_from, $request->going_to ,$request->departure_date ,$request->nights ,$request->adults,$request->minors,$request->trip_type,$request->budget,$request->services,$request->additional_details));
        flash('Your Trip Request Submitted Successfully')->important()->success();
         return Redirect::back();
    }



    public function howitWorks()
    {
        return view('user.howitWorks');
    }
    public function allPackages()
    {
        $packages=Packages::orderBy('id','desc')->get();
        $hotels=Hotels::orderBy('id','desc')->get();
        $hotel_lists=Hotels::orderBy('updated_at','desc')->get()->take(4);
        $package_types=Category::orderBy('id','asc')->get();
        $outbound_locations=Location::orderby('id','desc')->where('country','!=','India')->paginate(15);

        return view('user.packages',compact('packages','hotels','package_types','outbound_locations','hotel_lists'));
    }
    public function categoryPackages($slug)
    {
        $category=Category::where('slug',$slug)->first();
        $packages=Packages::where('category_id',$category->id)->with('package_ratings')->orderBy('id','desc')->get();


        $hotels=Hotels::orderBy('id','desc')->get();
        $package_types=Category::orderBy('id','asc')->get();
        if(!count($packages))
        {
            return view('nodestinationAvailable');

        }
        else
        {
            return view('user.categoryPackages',compact('packages','hotels','package_types','category'));

        }
    }
    public function destinationPackages($slug)
    {

        $destination=Destination::where('slug',$slug)->first();
        $packages=Packages::where('destination',$destination->id)->orderBy('id','desc')->get();
        $hotels=Hotels::orderBy('id','desc')->get();
        $package_types=Category::orderBy('id','asc')->get();
        if(!count($packages))
        {
            return view('nodestinationAvailable');

        }
        else
        {
            return view('user.categoryPackages',compact('packages','hotels','package_types','category'));

        }
    }



    public function filterPackage(Request $request)
    {

        $packages = Packages::where('category_id',$request->category_id)->where(function ($query) use ($request){
            $query->whereBetween('economy',[$request->min_amount, $request->max_amount])
                ->orWhereBetween('deluxe', [$request->min_amount, $request->max_amount])
                ->orWhereBetween('premium', [$request->min_amount, $request->max_amount])
                ->orWhereBetween('luxury', [$request->min_amount, $request->max_amount])
                ->orWhereBetween('premium_luxury', [$request->min_amount, $request->max_amount]);
        }) ->get();
        return response()->json($packages);
    }
    public function filterPackageDay(Request $request)
    {
        //$arr=array();

       if(in_array("8",$request->days))
       {
           $packages=Packages::where('category_id',$request->category_id)->where('days','>=',7);

       }
      if($request->days)
       {
           $packages=Packages::where('category_id',$request->category_id)->whereIn('days',$request->days);
       }

       $packages=$packages->get();
       return response()->json($packages);

    }
public function filterPackagebyDestination(Request $request)
    {

        $packages = Packages::where('destination',$request->destination_id)->where(function ($query) use ($request){
                    $query->whereBetween('economy',[$request->min_amount, $request->max_amount])
                ->orWhereBetween('deluxe', [$request->min_amount, $request->max_amount])
                ->orWhereBetween('premium', [$request->min_amount, $request->max_amount])
                ->orWhereBetween('luxury', [$request->min_amount, $request->max_amount])
                ->orWhereBetween('premium_luxury', [$request->min_amount, $request->max_amount]);
        }) ->get();
        return response()->json($packages);
    }
    public function filterPackagebyDestinationByday(Request $request)
    {

       if(in_array("8",$request->days))
       {
           $packages=Packages::where('destination',$request->destination_id)->where('days','>=',7);

       }
      if($request->days)
       {
           $packages=Packages::where('destination',$request->destination_id)->whereIn('days',$request->days);
       }

       $packages=$packages->get();
       return response()->json($packages);

    }

    public function filterHotels(Request $request)
    {

        $hotels = Hotels::where('destination',$request->destination)
            ->where(function ($query) use ($request){
                $query->whereBetween('economy',[$request->min_amount, $request->max_amount])
                    ->orWhereBetween('deluxe', [$request->min_amount, $request->max_amount])
                    ->orWhereBetween('premium', [$request->min_amount, $request->max_amount])
                    ->orWhereBetween('luxury', [$request->min_amount, $request->max_amount])
                    ->orWhereBetween('premium_luxury', [$request->min_amount, $request->max_amount]);
            }) ->get();
        return response()->json($hotels);
    }


    public function allHotels()
    {
        $packages=Packages::orderBy('id','desc')->get();
        $package_lists=Packages::orderBy('updated_at','desc')->get()->take(3);
        $hotels=Hotels::orderBy('id','desc')->get();
        $destinations=Destination::orderBy('id','desc')->whereHas('get_hotels',function ($data){
        })->get();
        $destination_lists=Destination::orderBy('updated_at','desc')->get();
        return view('user.hotels',compact('packages','hotels','destinations','destination_lists','package_lists'));
    }

    public function searchbyAll(Request $request)
    {
        if (strpos($request->search, 'GTZ') == 0) {
            /*$pos = 7;
            $id = substr($request->search, 3, $pos);*/
            $package_lists = Packages::with('daysDetail')->where('gotripz_id','like','%'.$request->search.'%')->take(2)->get();
        }
        $locations=Location::orderBy('id','desc')->where('location','like','%'.$request->search.'%')->take(2)->get();
        $destinations=Destination::orderBy('id','desc')->where('destination','like','%'.$request->search.'%')->take(2)->get();
        $packages=Packages::orderBy('id','desc')->where('package_name','like','%'.$request->search.'%')->take(2)->get();
        $hotels=Hotels::orderBy('id','desc')->where('hotel_name','like','%'.$request->search.'%')->take(2)->get();
        return view('user.search',compact('locations','destinations','packages','hotels','package_lists'));
    }

    public function searchinDestination(Request $request)
    {
        if (strpos($request->search, 'GTZ') == 0) {
            $package_lists = Packages::with('daysDetail')->where('gotripz_id','like','%'.$request->search.'%')->take(2)->get();
        }
        $locations=Location::orderBy('id','desc')->where('location','like','%'.$request->search.'%')->take(2)->get();
        $destinations=Destination::orderBy('id','desc')->where('destination','like','%'.$request->search.'%')->take(2)->get();
        $packages=Packages::orderBy('id','desc')->where('package_name','like','%'.$request->search.'%')->take(2)->get();
        $hotels=Hotels::orderBy('id','desc')->where('hotel_name','like','%'.$request->search.'%')->take(2)->get();
        return view('user.searchin_destinations',compact('locations','destinations','packages','hotels','package_lists'));
    }




    public function show_different_packages($location)
    {
        $packages=Packages::orderBy('id','desc')->paginate(6);
        $hotels=Hotels::orderBy('id','desc')->paginate(6);
        //dd($packages);

        $loc=Location::orderby('id','asc')->paginate(20);
        return view('user.all_packages',compact('destination','location','loc','packages','hotels'));

    }
    public function privacy()
    {
        return view('user.privacy');
    }
    public function faq()
    {
        return view('user.faq');
    }
    public function terms()
    {
        return view('user.terms_of_service');
    }
    public function how_it_work()
    {
        return view('user.how_it_work');
    }




}
