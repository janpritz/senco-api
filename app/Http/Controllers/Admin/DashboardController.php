<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Cache, Log};

class DashboardController extends Controller
{
    // app/Http/Controllers/Admin/DashboardController.php
    public function index()
    {
        // 1. Get Fee from DB (or default to 4300)
        $fee = (float) (DB::table('settings')->where('key', 'contribution_fee')->value('value') ?? 4300);

        // 2. Get Masterlist from Cache
        $masterlist = Cache::get('senco_masterlist', []);

        // 3. Perform Math on the Array
        $totalStudents = count($masterlist);

        // Summing the 'paid' or 'amount' field from your cache objects
        $totalCollected = array_sum(array_column($masterlist, 'paid_amount'));

        // Count fully paid (where balance is 0)
        $paidStudents = count(array_filter($masterlist, function ($student) {
            return ($student['balance'] ?? 1) <= 0;
        }));

        return response()->json([
            'stats' => [
                'totalCollected' => $totalCollected,
                'totalStudents'  => $totalStudents,
                'paidStudents'   => $paidStudents,
                'expectedTotal'  => $totalStudents * $fee,
            ],
            'contribution_fee' => $fee,
            Log::info("Dashboard stats calculated: " . json_encode([
                'totalCollected' => $totalCollected,
                'totalStudents'  => $totalStudents,
                'paidStudents'   => $paidStudents,
                'expectedTotal'  => $totalStudents * $fee,
            ]))
        ]);
    }
}
