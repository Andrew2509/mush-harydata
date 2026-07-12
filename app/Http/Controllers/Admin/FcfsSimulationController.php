<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FcfsSimulationController extends Controller
{
    public function index()
    {
        // Default values from classic GeeksforGeeks FCFS disk scheduling problem
        $defaultSequence = '98, 183, 37, 122, 14, 124, 65, 67';
        $defaultHead = 53;

        return view('admin.sus.fcfs_simulation', [
            'sequence' => $defaultSequence,
            'head' => $defaultHead,
            'result' => null
        ]);
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'sequence' => 'required|string',
            'head' => 'required|integer|min:0'
        ]);

        $sequenceStr = $request->input('sequence');
        $head = intval($request->input('head'));

        // Parse comma separated sequence to array of integers
        $sequence = array_map('intval', array_filter(array_map('trim', explode(',', $sequenceStr)), function($value) {
            return $value !== '';
        }));

        if (empty($sequence)) {
            return back()->with('error', 'Daftar request sequence tidak boleh kosong.');
        }

        $totalHeadMovement = 0;
        $currentHead = $head;
        $steps = [];
        $seekSequence = [$head];

        foreach ($sequence as $index => $req) {
            $diff = abs($req - $currentHead);
            $steps[] = [
                'step' => $index + 1,
                'from' => $currentHead,
                'to' => $req,
                'diff' => $diff
            ];
            $totalHeadMovement += $diff;
            $currentHead = $req;
            $seekSequence[] = $req;
        }

        $avgSeekTime = count($sequence) > 0 ? $totalHeadMovement / count($sequence) : 0;

        $categories = ['Mulai'];
        for ($i = 1; $i <= count($steps); $i++) {
            $categories[] = 'Step ' . $i;
        }

        $result = [
            'steps' => $steps,
            'total_head_movement' => $totalHeadMovement,
            'avg_seek_time' => round($avgSeekTime, 2),
            'seek_sequence' => $seekSequence,
            'raw_sequence' => $sequence,
            'categories' => $categories
        ];

        return view('admin.sus.fcfs_simulation', [
            'sequence' => $sequenceStr,
            'head' => $head,
            'result' => $result
        ]);
    }
}
