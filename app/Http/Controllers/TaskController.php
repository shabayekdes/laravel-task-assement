<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
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
        $task = new Task();
        $users = User::where('type', 1)->inRandomOrder()->limit(10)->get();

        return view('tasks.create', compact('task', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'assigned_to_id' => 'required|exists:users,id'
        ]);
        $validated['assigned_by_id'] = $request->user()->id;
        Task::create($validated);

        return redirect(route('tasks.index'));
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
            ->count();

        $totalRecordsWithFilter = Task::select('count(*) as allcount')
            ->where(function ($query) use ($searchValue) {
                $query->where('title', 'LIKE', "%$searchValue%")
                    ->orWhere('description', 'LIKE', "%$searchValue%");
            })
            ->count();

        // Fetch records
        $records = Task::orderBy($columnName, $columnSortOrder)
            ->with('user:id,name')
            ->where(function ($query) use ($searchValue) {
                $query->where('title', 'LIKE', "%$searchValue%")
                    ->orWhere('description', 'LIKE', "%$searchValue%");
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = [];

        foreach ($records as $record) {
            $data_arr[] = [
                "id" => $record->id,
                "title" => $record->title,
                "description" => $record->description,
                "user_name" => $record->user->name,
                "created_at" => $record->created_at->format('Y-m-d h:i:s'),
                "actions" => $record->id
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

    // Fetch records
    public function getUsersAjax(Request $request)
    {
        $search = $request->search;

        $users = User::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%");
        })->orderby('name', 'asc')->select('id', 'name')->limit(10)->get();

        $response = array();
        foreach ($users as $employee) {
            $response[] = array(
                "id" => $employee->id,
                "text" => $employee->name
            );
        }
        return response()->json($response);
    }

}
