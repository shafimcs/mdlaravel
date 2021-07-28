<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function create()
    {
        return view('admin.slider.createSlider');
    }
    public function store(Request $request)
    {
        $file = $request['image'];
        if ($file) {
            $path = $file->storeAs('image/slider', str_slug('slider') . mt_rand() . '.' . $file->extension(), 'uploads');
            $inputs['image']=$path;
            $slider=Slider::create(['title1'=>$request->title1,'title2'=>$request->title2,'image'=>$path]);
        }
        flash('Added Successfully')->important()->success();
        return redirect()->route('slider.show');
    }

    public function show()
    {
        $sliders=Slider::orderBy('id','desc')->get();
        return view('admin.slider.showSlider',compact('sliders'));
    }
    public function edit($id)
    {
      $slider=Slider::where('id',$id)->first();
      return view('admin.slider.editSlider',compact('slider'));

    }
    public function update(Request $request)
    {
        $file = $request['image'];
        if (!empty($file)) {
            $path = $file->storeAs('image/slider', str_slug('slider') . mt_rand() . '.' . $file->extension(), 'uploads');
            $slider=Slider::where('id',$request->id)->update(['image'=>$path]);
        }
        else
        {
            $slider=Slider::where('id',$request->id)->update(['title1'=>$request->title1,'title2'=>$request->title2]);

        }
        flash('Updated Successfully')->important()->success();
        return redirect()->route('slider.show');
    }
    public function delete(Request $request)
    {
        $slider=Slider::where('id',$request->id)->first();
        $slider->delete();
        $msg="Deleted Sucessfully";
        return response()->json($msg);

    }
}
