<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB Facade

class SubjectController extends Controller
{
    public function getBanglaInfo(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('bangla');

        if ($search) {
            $query->where('roll_no', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%");
        }

        $banglaData = $query->orderBy('roll_no')->paginate(15);

        $femaleCount = DB::table('bangla')->where('gender', 'Female')->count();
        $maleCount = DB::table('bangla')->where('gender', 'Male')->count();

        $total = DB::table('bangla')->count();

        return view('subjects.bangla_info', compact('banglaData', 'total', 'femaleCount', 'maleCount'));
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
