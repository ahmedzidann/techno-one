<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Shape;
use Yajra\DataTables\Facades\DataTables;

class ShapeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = Shape::query();
            return DataTables::of($rows)
                ->addColumn('action', function ($row) {

                    $edit = '';
                    $delete = '';


                    return '
                            <button ' . $edit . '   class="editBtn btn rounded-pill btn-primary waves-effect waves-light"
                                    data-id="' . $row->id . '"
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-edit"></i>
                                </span>
                            </span>
                            </button>
                            <button ' . $delete . '  class="btn rounded-pill btn-danger waves-effect waves-light delete"
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
                    return $row->parent->title ?? '';
                })

                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('Admin.CRUDS.shapes.index');
    }


    public function create()
    {
        return view('Admin.CRUDS.shapes.parts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|unique:shapes,title',
        ]);
        Shape::create($data);
        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]
        );
    }


    public function edit($id)
    {
        $row = Shape::find($id);

        return view('Admin.CRUDS.shapes.parts.edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|unique:shapes,title,' . $id,
        ]);

        $row = Shape::find($id);
        $row->update($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    }


    public function destroy($id)
    {
        $row = Shape::find($id);
        $row->delete();
        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]
        );
    } //end fun

    public function getShapes(Request $request)
    {
        if ($request->ajax()) {

            $companies = DB::table('shapes')->select('id', 'title as text')->simplePaginate(3);

            $morePages = true;
            $pagination_obj = json_encode($companies);
            if (empty($companies->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $companies->items(),
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return \Response::json($results);
        }
    }
}
