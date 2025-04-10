<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display the page
     *
     * @return Factory|View|Application|object
     */


    public function index() {
        $groups = Group::all();  // Get all users
        return view('pages.groups.index');
    }
}
