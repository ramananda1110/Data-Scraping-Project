<?php

namespace App\Http\Controllers;

use App\Models\MeritList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

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

   public function index(Request $request)
    {
        $query = MeritList::where('id', '<=', 1441);

        // optional search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('roll', 'like', "%{$search}%")
                ->orWhere('applicant_name', 'like', "%{$search}%")
                ->orWhere('recommend_institute', 'like', "%{$search}%")
                ->orWhere('marks', 'like', "%{$search}%")
                ->orWhere('institute_district', 'like', "%{$search}%");
            });
        }
        if ($request->filled('marks')) {
            $search = $request->marks;
            $query->where(function ($q) use ($search) {
                $q->where('marks', 'like', "%{$search}%");
            });
        }

        if ($request->filled('institute_type')) {
            if ($request->institute_type === 'general') {
                $query->where(function ($q) {
                    $q->where('institute_type', 'LIKE', '%general%');
                });
            } else if ($request->institute_type === 'bm') {
                $query->where(function ($q) {
                    $q->where('institute_type', 'LIKE', '%bm%');
                });
            } 
        }

        // paginate instead of get()
        $listOfData = $query->orderBy('id', 'asc')->paginate(20);
       
        $total_recommended = MeritList::where('batch', 18)
        ->where('recommend_institute', '!=', 'N/A')
        ->count();

        // $total_recommended = MeritList::where('id', '<=', 1441)
        // ->where('recommend_institute', '!=', 'N/A')
        // ->count();
        $not_recommended = $query->count() - $total_recommended;

        return view('subjects.recommended_bangla_lect', compact('listOfData', 'total_recommended', 'not_recommended'));
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


     public function exportingPdfSelectCandidate(Request $request)
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

    
        $query = MeritList::query();


       $search = trim($request->input('search'));

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('roll', 'like', "%{$search}%")
                ->orWhere('applicant_name', 'like', "%{$search}%")
                ->orWhere('recommend_institute', 'like', "%{$search}%")
                ->orWhere('institute_district', 'like', "%{$search}%");
            });
        }


         if ($request->filled('marks')) {
            $search = $request->marks;
            $query->where(function ($q) use ($search) {
                $q->where('marks', 'like', "%{$search}%");
            });
        }

        if ($request->filled('institute_type')) {
            if ($request->institute_type === 'general') {
                $query->where(function ($q) {
                    $q->where('institute_type', 'LIKE', '%general%');
                });
            } else if ($request->institute_type === 'bm') {
                $query->where(function ($q) {
                    $q->where('institute_type', 'LIKE', '%bm%');
                });
            } 
        }

         // 👇 Fetch actual results
        $results = $query->get();


        $html = '
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: sans-serif; margin: 20px; }
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid black; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <h1>Candidate List With Selected Insitute</h1>
            <table>
                <thead>
                    <tr>
                        <th>Serial/Rank</th>
                        <th>Batch</th>
                        <th>Marks</th>
                        <th>Applicant Name</th>
                        <th>Type</th>
                        <th>Institute</th>	
                        <th>District</th>	
                        <th>Thana</th>	
                    </tr>
                </thead>
                <tbody>';

        foreach ($results as $data) {
            $html .= '<tr>';
            $html .= '<td>' . $data->id . '</td>';
            $html .= '<td>' . $data->batch . '</td>';
            $html .= '<td>' . $data->marks . '</td>';
            $html .= '<td>' . $data->applicant_name . '</td>';
            $html .= '<td>' . $data->institute_type . '</td>';
            $html .= '<td>' . $data->recommend_institute . '</td>';
            $html .= '<td>' . $data->institute_district . '</td>';
            $html .= '<td>' . $data->institute_thana . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 96,
                'defaultFont' => 'sans-serif',
            ]);

        return $pdf->download('merit-pdf-export.pdf');
    }
}
