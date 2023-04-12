<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        $users = User::withCount('tasks')->where('type', UserType::TYPE_USER->value)->orderByDesc('tasks_count')->paginate(10);
        return view('dashboard', compact('users'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersListAjax(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');

        // Total records
        $totalRecords = User::select('count(*) as allcount')
            ->count();

        $totalRecordsWithFilter = User::select('count(*) as allcount')
            ->where('type', UserType::TYPE_USER->value)
            ->count();

        // Fetch records
        $records = User::withCount('tasks')
            ->where('type', UserType::TYPE_USER->value)
            ->orderByDesc('tasks_count')
            ->take(10)
            ->get();


        $data_arr = [];
        foreach ($records as $record) {
            $data_arr[] = [
                "id" => $record->id,
                "user_name" => $record->name,
                "user_email" => $record->email,
                "tasks_count" => $record->tasks_count
            ];
        }
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData" => $data_arr
        ];
        return response()->json($response);
    }

}
