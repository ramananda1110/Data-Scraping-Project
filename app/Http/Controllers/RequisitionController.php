<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Requisition;

class RequisitionController extends Controller
{
   
     private $codes = [
        701, 702, 703, 704, 705, 706, 707, 708,
        109, 110, 111, 112, 113, 114, 115, 116,
        305, 306, 307, 309, 308, 310, 311, 312, 313, 314, 315, 316, 317,
        201, 202, 203, 204, 205, 206, 207, 208, 209, 210,
        501, 502, 503, 504, 505, 506,
        601, 602, 603, 604,
        801, 802, 803, 804,
        405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415
     ];

    // Keywords to filter the data
    // private $filter_keywords = ['Bengali', 'Assistant Teacher'];


    private $filter_keywords = ['Lecturer', 'Bengali'];

   
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
    
            // Log full response
            \Log::info("Response body:", ['body' => $response->body()]);
    
            // Decode response
            $data = $response->json();
            \Log::info("Decoded JSON:", ['data' => $data]);
    
            if (!isset($data['data']) || empty($data['data'])) {
                \Log::warning("No data received for code: $code");
                continue;
            }
    
            // Loop through the response data
                foreach ($data['data'] as $item) {
                    if (count($item) < 10) {
                        continue; // Skip invalid data
                    }

                    // Extract values from the response array
                    $index_id = $item[0];  // First value in the array
                    $etin_id = $item[1];
                    $name_of_institute = $item[2];
                    $district = $item[3];
                    $thana = $item[4];
                    $post_name = trim($item[5]);
                    $subject = trim($item[6]);
                    $vacancy = (int) $item[7];
                    $type = $item[8];
                    $apply_for = strip_tags($item[9]); // Remove HTML tags like &amp;

                    // Filter based on keywords (BOTH must match)
                    $post_name_matches = false;
                    $subject_matches = false;

                    foreach ($this->filter_keywords as $keyword) {
                        if (strpos($post_name, $keyword) !== false) {
                            $post_name_matches = true;
                        }
                        if (strpos($subject, $keyword) !== false) {
                            $subject_matches = true;
                        }
                    }

                    // Store only if both post_name and subject contain the keywords
                    if (!$post_name_matches || !$subject_matches) {
                        continue; // Skip if neither post_name nor subject match
                    }

                    // Check if the record already exists
                    $existing = Requisition::where('index_id', $index_id)->first();

                    if (!$existing) {
                        // Insert into the database
                        Requisition::create([
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
    
        return response()->json(['message' => 'Data fetched and stored successfully'], 200);
    }
    

    public function getAllInfo()
    {
        // Total count of all records
        $total = Requisition::count();

        // Total MADRASHA count (MADRASHA, MADRASA, and MADRASAH)
        $madrasahTotal = Requisition::where('name_of_institute', 'LIKE', '%MADRASHA%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRASA%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRASAH%')
            ->count();

        // Total general count (all - MADRASHA count)
        $generalTotal = $total - $madrasahTotal;

        // Total Female only count
        $femaleOnlyTotal = Requisition::where('apply_for', 'LIKE', '%Female only%')->count();

        // District-wise count for both MADRASHA and General
        $districtCounts = Requisition::groupBy('district')
            ->selectRaw('district, count(*) as total_count, 
                        sum(case when name_of_institute LIKE "%MADRASHA%" or 
                                  name_of_institute LIKE "%MADRASA%" or 
                                  name_of_institute LIKE "%MADRASAH%" then 1 else 0 end) as madrasa_count,
                        sum(case when apply_for LIKE "%Female only%" then 1 else 0 end) as female_seat')
            ->get();

        // Calculate General count for each district (total - madrasa_count)
        foreach ($districtCounts as $districtCount) {
            $districtCount->general_count = $districtCount->total_count - $districtCount->madrasa_count;
        }

        // Return the counts in the response
        return response()->json([
            'total' => $total,
            'madrasah_total' => $madrasahTotal,
            'general_total' => $generalTotal,
            'female_only_total' => $femaleOnlyTotal,
            'district_counts' => $districtCounts,
        ]);
    }


}