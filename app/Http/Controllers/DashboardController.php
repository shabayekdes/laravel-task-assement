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
    public function __invoke(Request $request)
    {
        $users = User::withCount('tasks')->where('type', UserType::TYPE_USER->value)->orderByDesc('tasks_count')->paginate(10);
        return view('dashboard', compact('users'));
    }
}
