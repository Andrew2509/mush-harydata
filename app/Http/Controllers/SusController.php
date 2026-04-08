<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SusQuestion;
use App\Models\SusResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SusController extends Controller
{
    public function index()
    {
        $questions = SusQuestion::orderBy('order')->get();
        $config = DB::table('setting_webs')->where('id', 1)->first();

        return view('user.sus.form', [
            'questions' => $questions,
            'config' => $config,
            'title' => 'Analisis Skor SUS - ' . ($config->judul_web ?? 'Mustopup'),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'usia' => 'required|integer|min:1|max:120',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
        ];
        for ($i = 1; $i <= 10; $i++) {
            $rules['q' . $i] = 'required|integer|min:1|max:5';
        }

        $validated = $request->validate($rules);

        // SUS Score Calculation
        $oddSum = 0;
        $evenSum = 0;

        for ($i = 1; $i <= 10; $i++) {
            $val = intval($validated['q' . $i]);
            if ($i % 2 != 0) {
                // Odd questions (1, 3, 5, 7, 9): Answer - 1
                $oddSum += ($val - 1);
            } else {
                // Even questions (2, 4, 6, 8, 10): 5 - Answer
                $evenSum += (5 - $val);
            }
        }

        $susScore = ($oddSum + $evenSum) * 2.5;

        SusResponse::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'total_score' => $susScore,
        ]));

        return redirect()->route('home')->with('success', 'Terima kasih atas masukan Anda! Skor SUS Anda telah dicatat.');
    }
}
