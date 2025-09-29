<?php

namespace App\Http\Controllers;

use App\Models\MeritList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MeritListController extends Controller
{
    public function fetchAndStore()
    {
        // Call the API

        $response = Http::timeout(120) // 120 seconds
        ->asForm()
        ->post('http://ngi.teletalk.com.bd/ntrca/merit/load-merit-list.php?subject=401', [
            'length' => 40000,
        ]);


        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }

        $data = $response->json();

        if (!isset($data['data'])) {
            return response()->json(['error' => 'Invalid response structure'], 500);
        }

        $inserted = 0;

        foreach ($data['data'] as $item) {
            // ✅ Only store if batch == 18
            if (isset($item['batch']) && $item['batch'] == "18") {
                MeritList::updateOrCreate(
                    ['roll' => $item['roll']], // avoid duplicates by roll
                    [
                        'batch'              => $item['batch'] ?? null,
                        'marks'              => $item['marks'] ?? null,
                        'applicant_name'     => $item['applicant_name'] ?? null,
                        'subject'            => $item['subject'] ?? null,
                        'institute_type'     => $item['level'] ?? null,
                        'recommend_institute'=> 'N/A',
                    ]
                );
                $inserted++;
            }
        }

        return response()->json([
            'message' => "Data fetched & stored successfully",
            'records_inserted' => $inserted,
        ]);
    }

    public function index()
    {
        // Fetch only batch 18 data
        return MeritList::where('batch', 18)->get();
    }


    public function updateRecommendedInstitutes()
    {
        $rolls = DB::table('merit_lists_bangla')->pluck('roll');

        $updated = 0;

        foreach ($rolls as $roll) {
            // Call the API for each roll
            $response = Http::asForm()->post('http://103.230.104.210:8088/ntrca/c7/app/getres.php', [
                'batch' => 18,
                'roll'  => $roll,
            ]);

            if ($response->failed()) {
                continue; // skip if API error
            }

            $html = $response->body();

            // Check if NOT SELECTED
            if (str_contains($html, 'NOT SELECTED')) {
                continue; // skip
            }

            // Extract Institute Name
            if (preg_match('/<th>Institute Name<\/th><td>:\s*(.*?)<\/td>/', $html, $matches)) {
                $instituteName = trim($matches[1]);

                DB::table('merit_lists_bangla')
                    ->where('roll', $roll)
                    ->update(['recommend_institute' => $instituteName]);

                $updated++;
            }
        }

        return response()->json([
            'message' => 'Recommended institutes updated successfully',
            'records_updated' => $updated,
        ]);
    }

}
