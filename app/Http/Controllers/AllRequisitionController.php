<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use App\Models\AllRequisition;

class AllRequisitionController extends Controller
{

    private $codes = [
        //701, 702, 703, 704, 705, 706, 707, 708,
        // 109, 110, 111, 112, 113, 114, 115, 116,
        // 305, 306, 307, 309, 308, 310,311, 312, 313, 314, 315, 316, 317
        // 201, 202, 203, 204, 205, 206, 207, 208, 209, 210
        //501, 502, 503, 504, 505, 506
        
        //601, 602,  603, 604
          
       
         //801, 802, 803, 804
         //405, 406, 407, 408, 409,  410, 411, 412, 413, 414, 415
     ];

    public function fetchAndStoreData()
    {
        foreach ($this->codes as $code) {
            $url = "http://103.230.104.210:8088/ntrca/c7/app/get_requisition_report_ngi3.php?type=district&code={$code}&demo=";

            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Referer' => 'http://103.230.104.210:8088/ntrca/c7/app/requisition-list.php',
                'Cookie' => 'PHPSESSID=tce6b70c5f0pbnu2fvn713337b',
            ])->asForm()->post($url, [
                'type' => 'district',
                'code' => $code,
                'start' => 0,
                'draw' => 1,
                'length' => 10000,
            ]);

            \Log::info("Response body:", ['body' => $response->body()]);

            $data = $response->json();
            \Log::info("Decoded JSON:", ['data' => $data]);

            if (!isset($data['data']) || empty($data['data'])) {
                \Log::warning("No data received for code: $code");
                continue;
            }

            foreach ($data['data'] as $item) {
                if (count($item) < 10) {
                    continue;
                }

                $index_id = $item[0];
                $etin_id = $item[1];
                $name_of_institute = $item[2];
                $district = $item[3];
                $thana = $item[4];
                $post_name = trim($item[5]);
                $subject = trim($item[6]);
                $vacancy = (int) $item[7];
                $type = $item[8];
                $apply_for = strip_tags($item[9]);

                // Check if the record already exists
                $existing = AllRequisition::where('index_id', $index_id)->first();

                if (!$existing) {
                    // Insert into the new database table
                    AllRequisition::create([
                        'index_id' => $index_id,
                        'etin_id' => $etin_id,
                        'name_of_institute' => $name_of_institute,
                        'district' => $district,
                        'thana' => $thana,
                        'post_name' => $post_name,
                        'subject' => $subject,
                        'vacancy' => $vacancy,
                        'type' => $type,
                        'apply_for' => $apply_for,
                    ]);
                }
            }
        }

        return response()->json(['message' => 'All records fetched and stored successfully'], 200);
    
    }


    public function getAllRecords()
    {
        $records = AllRequisition::all();
        return response()->json($records);
    }


    public function getAllInfo()
    {
        // Total count of all records
        $total = AllRequisition::count();
    
        // Total MADRASAH count (MADRASHA, MADRASA, and MADRASAH, MADRSHA, MADRSASHA)
        $madrasahTotal = AllRequisition::where('name_of_institute', 'LIKE', '%MADRASHA%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRASA%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRASAH%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRASH%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRSHA%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRSASHA%')
            ->count();
    
        // Total general count (all - MADRASHA count)
        $generalTotal = $total - $madrasahTotal;
    
        // Total Female only count
        $femaleOnlyTotal = AllRequisition::where('apply_for', 'LIKE', '%Female only%')->count();
    
        // District-wise count for both MADRASAH and General
        $districtCounts = AllRequisition::groupBy('district')
            ->selectRaw('district, count(*) as total_count, 
                        sum(case when name_of_institute LIKE "%MADRASHA%" or 
                                  name_of_institute LIKE "%MADRASA%" or name_of_institute LIKE "%MADRASH%" or 
                                  name_of_institute LIKE "%MADRSHA%" or name_of_institute LIKE "%MADRSASHA%" or
                                  name_of_institute LIKE "%MADRASAH%" then 1 else 0 end) as madrasa_count,
                        sum(case when apply_for LIKE "%Female only%" then 1 else 0 end) as female_seat')
            ->get();
    
        // Calculate General count for each district (total - madrasa_count)
        foreach ($districtCounts as $districtCount) {
            $districtCount->general_count = $districtCount->total_count - $districtCount->madrasa_count;
        }
    
        // Return the data to the Blade view
        return view('requisitions.all_info', [
            'total' => $total,
            'madrasahTotal' => $madrasahTotal,
            'generalTotal' => $generalTotal,
            'femaleOnlyTotal' => $femaleOnlyTotal,
            'districtCounts' => $districtCounts,
        ]);
    }
    








    public function index(Request $request)
    {
        // Build the filtered query.
        $query = AllRequisition::query();

        if ($request->filled('subject')) {
            $query->where('subject', 'LIKE', '%' . $request->subject . '%');
        }
        if ($request->filled('post_name')) {
            $query->where('post_name', 'LIKE', '%' . $request->post_name . '%');
        }
        if ($request->filled('district')) {
            $query->where('district', 'LIKE', '%' . $request->district . '%');
        }
        if ($request->filled('apply_for')) {
            $query->where('apply_for',  $request->apply_for);
        }

        if ($request->filled('institute_type')) {

            if ($request->institute_type === 'madrasha') {
                $query->where(function ($q) {
                    $q->where('name_of_institute', 'LIKE', '%MADRASHA%')
                      ->orWhere('name_of_institute', 'LIKE', '%MADRASA%')
                      ->orWhere('name_of_institute', 'LIKE', '%MADRASH%')
                      ->orWhere('name_of_institute', 'LIKE', '%MADRSHA%')
                      ->orWhere('name_of_institute', 'LIKE', '%MADRSASHA%')
                      ->orWhere('name_of_institute', 'LIKE', '%MADRASAH%');
                });
            } else {
                $query->where(function ($q) use ($request) {
                    $q->where('name_of_institute', 'NOT LIKE', '%MADRASHA%')
                      ->where('name_of_institute', 'NOT LIKE', '%MADRASA%')
                      ->where('name_of_institute', 'NOT LIKE', '%MADRASH%')
                      ->where('name_of_institute', 'NOT LIKE', '%MADRSASHA%')
                      ->where('name_of_institute', 'NOT LIKE', '%MADRSHA%')
                      ->where('name_of_institute', 'NOT LIKE', '%MADRASAH%');
                });
            }
            
           
        }

        // dd($request->institute_type);


        // Clone the query for totals before pagination.
        $filtered_total   = (clone $query)->count();
        $filtered_madrasah = (clone $query)->where(function($q) {
            $q->where('name_of_institute', 'LIKE', '%MADRASHA%')
              ->orWhere('name_of_institute', 'LIKE', '%MADRASA%')
              ->orWhere('name_of_institute', 'LIKE', '%MADRASH%')
              ->orWhere('name_of_institute', 'LIKE', '%MADRSHA%')
              ->orWhere('name_of_institute', 'LIKE', '%MADRSASHA%')
              ->orWhere('name_of_institute', 'LIKE', '%MADRASAH%');
        })->count();
        $filtered_female  = (clone $query)->where('apply_for', 'Female only')->count();
        $filtered_general = $filtered_total - $filtered_madrasah;

        $lecturer  = (clone $query)->where('post_name', 'Lecturer')->count();
        $demonstrator  = (clone $query)->where('post_name', 'Demonstrator')->count();

        // Paginate the filtered results.
        $requisitions = $query->paginate(15)->appends($request->query());

        // Pass all filtered totals to the view.
        return view('requisitions.index', compact(
            'requisitions', 'filtered_total', 'filtered_madrasah', 'filtered_general', 'filtered_female', 'lecturer', 'demonstrator',
        ));
    }
    
}
