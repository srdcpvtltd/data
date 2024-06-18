<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AdminDashboardController extends Controller
{
    public function index()
    {

        $data['isUpToDate'] = isUpToDate(\request()->has('update_check'));

        $data['title'] = 'Dashboard';
        $orders = Order::all();
        $data['total_completed'] = $orders->where('status', 'completed')->count();
        $data['total_processing'] = $orders->where('status', 'processing')->count();
        $data['total_pending'] = $orders->where('status', 'pending')->count();
        $data['total_cancelled'] = $orders->where('status', 'cancelled')->count();

        $data['last_30_days'] = $this->getDatesFromRange(date('Y-m-d', strtotime('-30 days')), date('Y-m-d'));
        $sales = [];
        foreach ($data['last_30_days'] as $day) {
            $sales[] = ["y" => $day, "sales" => Order::whereIn('status', ['processing', 'completed'])->whereDate('created_at', $day)->count()];
        }

        $data['sales'] =json_encode($sales);

        return view('admin.dashboard', $data);
    }

    private function getDatesFromRange($date_time_from, $date_time_to)
    {
        $start = Carbon::createFromFormat('Y-m-d', substr($date_time_from, 0, 10));
        $end = Carbon::createFromFormat('Y-m-d', substr($date_time_to, 0, 10));
        $dates = [];
        while ($start->lte($end)) {
            $dates[] = $start->copy()->format('Y-m-d');
            $start->addDay();
        }
        return $dates;
    }

    public function notifications(Request $request)
    {
        if ($request->ajax()) {

            if($request->has('mark_all_as_read')) {
                auth()->user()->unreadNotifications->markAsRead();
            }

            if ($request->has('delete_notifications')) {
                auth()->user()->notifications()->delete();
            }

            $view = (string)View::make('admin.layouts.notifications_popup');

            return response()->json(['success' => true, 'html' => $view]);
        }
    }
}
