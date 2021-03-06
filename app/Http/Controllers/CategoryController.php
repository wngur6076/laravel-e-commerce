<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    public function AllCat()
    {
        /* $categories = DB::table('categories')
            ->join('users', 'categories.user_id', 'users.id')
            ->select('categories.*', 'users.name')
            ->latest()->paginate(5); */

        $categories = Category::latest()->paginate(5);
        // $categories = DB::table('categories')->latest()->paginate(5);
        return view('admin.category.index', compact('categories'));
    }

    public function AddCat(Request $request)
    {
        $validateData = $request->validate([
            'category_name' => 'required|unique:categories|max:255',
        ],
        [
            'category_name.required' => 'Please Input Category Name',
            'category_name.max' => 'Category Less Then 255Chars',
        ]);

        Category::create([
            'category_name' => $request->category_name,
            'user_id' => Auth::user()->id,
        ]);

        // $data = array();
        // $data['category_name'] = $request->category_name;
        // $data['user_id'] = Auth::user()->id;
        // DB::table('categories')->insert($data);

        return redirect()->back()->with('success', 'Category Inserted Successfull');
    }

    public function Edit($id)
    {
        // $category = Category::find($id);
        $category = DB::table('categories')->where('id', $id)->first();
        return view('admin.category.edit', compact('category'));
    }

    public function Update(Request $request, $id)
    {
        // $update = Category::find($id)->update([
        //     'category_name' => $request->category_name,
        //     'user_id' => Auth::user()->id,
        // ]);

        $data = array();
        $data['category_name'] = $request->category_name;
        $data['user_id'] = Auth::user()->id;

        DB::table('categories')->where('id', $id)->update($data);

        return redirect()->route('all.category')->with('success', 'Category Updated Successfull');

    }
}
