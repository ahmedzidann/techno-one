<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    //
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Category::query();
            return DataTables::of( $rows)
                ->addColumn('action', function ($row) {

                    $edit='';
                    $delete='';


                    return '
                            <button '.$edit.'   class="editBtn btn rounded-pill btn-primary waves-effect waves-light"
                                    data-id="' . $row->id . '"
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-edit"></i>
                                </span>
                            </span>
                            </button>
                            <button '.$delete.'  class="btn rounded-pill btn-danger waves-effect waves-light delete"
                                    data-id="' . $row->id . '">
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-trash"></i>
                                </span>
                            </span>
                            </button>
                       ';



                })


                ->editColumn('from_id', function ($row) {
                    return $row->parent->title ??'' ;
                })

                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);


        }

        return view('Admin.CRUDS.categories.index');
    }


    public function create()
    {
        $categories=Category::get();
        return view('Admin.CRUDS.categories.parts.create',compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|unique:categories,title' ,
            'from_id'=>'nullable|exists:categories,id',
            'path_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'

        ]);

         if ($request->hasFile('image')) {
          $path = $request->file('image')->store('categories', 'public');
          $data['path_image'] = $path;   // يخزن فقط المسار داخل قاعدة البيانات
        }

        $data['publisher']=auth('admin')->user()->id;

        Category::create($data);



        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]);
    }


    public function edit(  $id)
    {



        $row=Category::find($id);
        $categories=Category::get();

        return view('Admin.CRUDS.categories.parts.edit', compact('row','categories'));

    }

    public function update(Request $request, $id )
    {
        $data = $request->validate([
            'title' => 'required|unique:categories,title,'.$id,
            'from_id'=>'nullable|exists:categories,id',
            'path_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $row=Category::find($id);
          if ($request->hasFile('image')) {
          $path = $request->file('image')->store('categories', 'public');
          $data['path_image'] = $path;   // يخزن فقط المسار داخل قاعدة البيانات
        }
        $row->update($data);



        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }


    public function destroy( $id)
    {

        $row=Category::find($id);

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]);
    }//end fun

}
