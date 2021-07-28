<?php

namespace App\Http\Controllers\admin;

use App\Category;
use App\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    public function create()
    {
        $category = Category::orderby('id')->get();
        $country = Country::orderby('id')->get();


        return view('admin.category.addCategory',compact('category','country'));
    }

    public function store(Request $request)
    {

        $values=$request->all();
        $file=$request->file('image');

        if(!empty($file))
        {
            $path=$file->storeAs('image/category',str_slug('slide').mt_rand(). '.' . $file->extension(),'uploads');

            $values['image']=$path;
        }

        Category::create(['category_name'=>$request->category_name,
            'image'=>$path,
            /*'parent_id'=>$request->category_id,
            'country_name'=>$request->country_name,
            'title'=>$request->title,
            'description'=>$request->description,*/

        ]) ;

        flash('Added Successfully')->important()->success();
        return redirect()->route('category.show');


    }



    public  function  show()
    {
        // dd("hi");
        /*  $category = Category::paginate(5);*/


        $category = Category::orderBy('id','desc')->get();
        /*dd($category);*/
        /*dd($category);*/
        return view('admin.category.viewCategories',compact('category'));
    }
    public  function  edit($id)
    {
        // dd("hi");
        $editcategory=Category::where('id',$id)->first();

        $categories=Category::orderby('id')->get();
        $country = Country::orderby('id')->get();

        return view('admin.category.editCategory',compact('editcategory','categories','country'));
    }

    public  function  update(Request $request)
    {
        $values=$request->all();

        $file=$request->file('image');

        if(!empty($file))
        {
            $path=$file->storeAs('image/category',str_slug('slide').mt_rand(). '.' . $file->extension(),'uploads');

            Category::where('id',$request->id)->update(['category_name'=>$request->category_name,
                'image'=>$path,
                /*'parent_id'=>$request->category_id,
                'country_name'=>$request->country_name,'title'=>$request->title,
                'description'=>$request->description,*/
                ]);
        }
		else
			
		{
			Category::where('id',$request->id)->update(['category_name'=>$request->category_name,
                /*'parent_id'=>$request->category_id,
                'country_name'=>$request->country_name,'title'=>$request->title,
                'description'=>$request->description,*/
                ]);
		}


        flash('Updated Successfully')->important()->success();
        return \redirect()->route('category.show');
    }

    public function destroy($id) {
        $category = Category::findOrFail($id);
        $category->delete();
        flash('Deleted Successfully')->important()->success();
        return Redirect::back();
    }



}
