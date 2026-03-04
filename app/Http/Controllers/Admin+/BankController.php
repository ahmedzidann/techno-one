<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
        public function getAllBanks(Request $request)
    {

        if ($request->ajax()) {

            $term = trim($request->term);
            $posts = DB::table('banks')->select('id', 'name as text')
                ->where('name', 'LIKE', '%' . $term . '%')
                ->orderBy('name', 'asc')->simplePaginate(3);

            $morePages = true;
            $pagination_obj = json_encode($posts);
            if (empty($posts->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $posts->items(),
                "pagination" => array(
                    "more" => $morePages,
                ),
            );

            return \Response::json($results);

        }

    }
}
