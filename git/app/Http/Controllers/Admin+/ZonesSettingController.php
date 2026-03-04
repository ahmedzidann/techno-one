<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ZonesSetting;
use Illuminate\Http\Request;

class ZonesSettingController extends Controller
{
   public function getChildCities(Request $request)
{
    $parentId = $request->input('zone_id');

    // Fetch the data where `parent_id` matches
    $data = ZonesSetting::where('parent_id', $parentId)->get(['id', 'title']);

    return response()->json([
        'success' => true,
        'data' => $data
    ]);
}
}
