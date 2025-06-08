<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB Facade

class SubjectController extends Controller
{
    public function getBanglaInfo(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('bangla_lecturers_bm');

        if ($search) {
            $query->where('roll_no', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%");
        }

        $banglaData = $query->orderBy('roll_no')->paginate(30);

        $femaleCount = DB::table('bangla_lecturers_bm')->where('gender', 'Female')->count();
        $maleCount = DB::table('bangla_lecturers_bm')->where('gender', 'Male')->count();

        $passedCount = DB::table('bangla_lecturers_bm')->where('final_result', 'passed')->count();


        $malePassed = DB::table('bangla_lecturers_bm')->where('gender', 'Male')->where('final_result', 'passed')->count();
        $femalePassed = DB::table('bangla_lecturers_bm')->where('gender', 'Female')->where('final_result', 'passed')->count();

        $total = DB::table('bangla_lecturers_bm')->count();

        return view('subjects.bangla_info', compact('banglaData', 'total', 'femaleCount', 'maleCount', 'passedCount', 'malePassed', 'femalePassed'));
    }


    public function getFemaleBanglaCandidates(Request $request)
    {
        // List of common female-related name parts
        $femaleKeywords = [
            'RANI', 'BEGUM', 'KHATUN', 'BIBI', 'FATEMA', 'JANNAT', 'NUSRAT',
            'SHAHANA', 'SANJIDA', 'TANZINA', 'AFRIN', 'KARIMA', 'MITU', 'SHOBNOM', 'MST', 'MOST.', 'AKTER'
        ];

        // Query to filter female candidates based on name
        $banglaData = DB::table('bangla')
            ->where(function ($query) use ($femaleKeywords) {
                foreach ($femaleKeywords as $keyword) {
                    $query->orWhere('name', 'LIKE', "%{$keyword}%");
                }
            })
            ->paginate(15); // Paginate results

        // Count total female candidates
        $total = $banglaData->total();

        return view('subjects.bangla_female', compact('banglaData', 'total'));
    }


}
