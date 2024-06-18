<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class AdminMessagesController extends Controller
{
    public function index()
    {
        return view('admin.messages.index');
    }
}
