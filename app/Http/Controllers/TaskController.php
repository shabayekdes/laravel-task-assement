<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tasks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasksAjax(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Task::select('count(*) as allcount')
            ->where('title', 'LIKE', "%$searchValue%")
            ->orWhere('description', 'LIKE', "%$searchValue%")
            ->count();
        $totalRecordswithFilter = Task::select('count(*) as allcount')
            ->where('title', 'LIKE', "%$searchValue%")
            ->orWhere('description', 'LIKE', "%$searchValue%")
            ->count();

        // Fetch records
        $records = Task::orderBy($columnName, $columnSortOrder)
            ->where('title', 'LIKE', "%$searchValue%")
            ->orWhere('description', 'LIKE', "%$searchValue%")
            ->skip($start)
            ->take($rowperpage)
            ->get();

        // dd($records->first(), $totalRecords, $searchValue);

        $data_arr = array();

        foreach ($records as $record) {
            $data_arr[] = [
                "id" => $record->id,
                "title" => $record->title,
                "description" => $record->description,
                "user_id" => $record->user_id,
                "created_at" => $record->created_at->format('Y-m-d h:i:s'),
                "actions" => $record->id
            ];
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );
        return response()->json($response);
    }

}
