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
    public function index(Request $request)
    {
        $search = $request->get('search');

        $tasks = Task::when($request->has('search'), function ($query) use ($search) {
            $query->where('title', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%")
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                })
                ->orWhereHas('admin', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
        })
            ->latest()
            ->paginate();

        return view('tasks.index', compact('tasks'));
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
            'assigned_to_id' => 'required|exists:users,id',
            'assigned_by_id' => 'required|exists:users,id'
        ]);
        Task::create($validated);

        return redirect(route('tasks.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasksListAjax(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $search_arr = $request->get('search');


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
        $records = Task::with('user:id,name')
            ->where(function ($query) use ($searchValue) {
                $query->where('title', 'LIKE', "%$searchValue%")
                    ->orWhere('description', 'LIKE', "%$searchValue%");
            })
            ->skip($start)
            ->take($rowperpage)
            ->latest()
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
            })
            ->where('type', $request->get('type'))
            ->orderby('name', 'asc')
            ->select('id', 'name')
            ->limit(10)
            ->get();

        $response = array();
        foreach ($users as $user) {
            $response[] = array(
                "id" => $user->id,
                "text" => $user->name
            );
        }
        return response()->json($response);
    }

}
