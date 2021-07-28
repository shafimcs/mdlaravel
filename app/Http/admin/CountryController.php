<?php

namespace App\Http\Controllers\admin;

use App\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class CountryController extends Controller
{
	
    public function create()
    {
        return view('admin.country.addCountry');
    }
    public  function store(Request $request)
    {
        $request->validate([
            'country_name' => 'required|max:255']);

        $values=$request->all();
        Country::create($values);
        flash('Added Successfully')->important()->success();
        return redirect()->route('country.show');

    }
    public  function  show()
    {
        $country = Country::orderBy('id','desc')->get();
        return view('admin.country.viewCountries',compact('country'));
    }
    public  function  edit($id)
    {
        $editcountry=Country::where('id',$id)->first();
        return view('admin.country.editCountry',compact('editcountry'));
    }
    public  function  update(Request $request)
    {

        Country::where('id',$request->id)->update(['country_name'=>$request->country_name]);
        flash('Updated Successfully')->important()->success();
        return redirect()->route('country.show');
    }
    public function destroy($id)
    {
        $country=Country::where('id',$id)->first();
        $country->delete();
        flash('Deleted Successfully')->important()->success();
        return Redirect::back();


    }

}
