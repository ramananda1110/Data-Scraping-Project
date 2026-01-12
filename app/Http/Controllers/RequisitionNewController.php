<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use App\Models\RequisitionNew;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Models\InstitutePost;
use Illuminate\Support\Facades\DB;
class RequisitionNewController extends Controller
{
     private $codes = [
        //701, 702, 703, 704, 705, 706, 707, 708,
        //109, 110, 111, 112, 113, 114, 115, 116,
        // 305, 306, 307, 309, 308, 310,311, 312, 313, 314, 315, 316, 317
        // 201, 202, 203, 204, 205, 206, 207, 208, 209, 210
        //501, 502, 503, 504 
        //505, 506
        
        //601, 602,  603, 604
          
       
         //801, 802, 803, 804
         405, 406, 407, 408, 409,  410, 411, 412, 413, 414, 415
     ];

    public function fetchAndStoreData7th()
    {
        foreach ($this->codes as $code) {
            $url = "http://103.230.104.210:8088/ntrca/c8/app/get_requisition_report_ngi3.php?type=district&code={$code}&demo=";

            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Referer' => 'http://103.230.104.210:8088/ntrca/c6/app/requisition-list.php',
                'Cookie' => 'PHPSESSID=tce6b70c5f0pbnu2fvn713337b',
            ])->asForm()->post($url, [
                'type' => 'district',
                'code' => $code,
                'start' => 0,
                'draw' => 1,
                'length' => 10000,
            ]);

            Log::info("Response body:", ['body' => $response->body()]);

            $data = $response->json();
            Log::info("Decoded JSON:", ['data' => $data]);

            if (!isset($data['data']) || empty($data['data'])) {
                Log::warning("No data received for code: $code");
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
                $existing = RequisitionNew::where('index_id', $index_id)->first();

                if (!$existing) {
                    // Insert into the new database table
                    RequisitionNew::create([
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


    public function getAllRecords7th()
    {
        $records = RequisitionNew::all();
        return response()->json($records);
    }


    public function getAllInfo()
    {
        // Total count of all records
        // $total = AllRequisition::count();
        $total = RequisitionNew::where('post_name', 'Lecturer')
    ->where('subject', 'Bengali')
    ->count();

    
        // Total MADRASAH count (MADRASHA, MADRASA, and MADRASAH, MADRSHA, MADRSASHA)
        // $madrasahTotal = AllRequisition::where('name_of_institute', 'LIKE', '%MADRASHA%')
        //     ->orWhere('name_of_institute', 'LIKE', '%MADRASA%')
        //     ->orWhere('name_of_institute', 'LIKE', '%MADRASAH%')
        //     ->orWhere('name_of_institute', 'LIKE', '%MADRASH%')
        //     ->orWhere('name_of_institute', 'LIKE', '%MADRSHA%')
        //     ->orWhere('name_of_institute', 'LIKE', '%MADRSASHA%')
        //     ->count();

        $madrasahTotal = RequisitionNew::where('post_name', 'Lecturer')
    ->where('subject', 'Bengali')
    ->where(function ($query) {
        $query->where('name_of_institute', 'LIKE', '%MADRASHA%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRASA%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRASAH%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRASH%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRSHA%')
            ->orWhere('name_of_institute', 'LIKE', '%MADRSASHA%');
    })
    ->count();

    
        // Total general count (all - MADRASHA count)
        $generalTotal = $total - $madrasahTotal;
    
        // Total Female only count
        $femaleOnlyTotal = RequisitionNew::where('apply_for', 'LIKE', '%Female only%')->count();
    
        // District-wise count for both MADRASAH and General
        // $districtCounts = AllRequisition::groupBy('district')
        //     ->selectRaw('district, count(*) as total_count, 
        //                 sum(case when name_of_institute LIKE "%MADRASHA%" or 
        //                           name_of_institute LIKE "%MADRASA%" or name_of_institute LIKE "%MADRASH%" or 
        //                           name_of_institute LIKE "%MADRSHA%" or name_of_institute LIKE "%MADRSASHA%" or
        //                           name_of_institute LIKE "%MADRASAH%" then 1 else 0 end) as madrasa_count,
        //                 sum(case when apply_for LIKE "%Female only%" then 1 else 0 end) as female_seat')
        //     ->get();

        $districtCounts = RequisitionNew::where('post_name', 'Lecturer')
        ->where('subject', 'Bengali')
        ->groupBy('district')
        ->selectRaw('
            district,
            count(*) as total_count,
            sum(
                case
                    when name_of_institute LIKE "%MADRASHA%" OR
                        name_of_institute LIKE "%MADRASA%" OR
                        name_of_institute LIKE "%MADRASH%" OR
                        name_of_institute LIKE "%MADRSHA%" OR
                        name_of_institute LIKE "%MADRSASHA%" OR
                        name_of_institute LIKE "%MADRASAH%"
                    then 1
                    else 0
                end
            ) as madrasa_count,
            sum(
                case
                    when apply_for LIKE "%Female only%" then 1
                    else 0
                end
            ) as female_seat
        ')
        ->get();

    
        // Calculate General count for each district (total - madrasa_count)
        foreach ($districtCounts as $districtCount) {
            $districtCount->general_count = $districtCount->total_count - $districtCount->madrasa_count;
        }
    
        // Return the data to the Blade view
        return view('requisitions.all_info', [
            'total' => $total,
            // 'madrasahTotal' => $madrasahTotal,
            'madrasahTotal' => 53501,
            'generalTotal' => 46211,
            'femaleOnlyTotal' => $femaleOnlyTotal,
            'districtCounts' => $districtCounts,
        ]);
    }
    





    public function index(Request $request)
    {
        // Build the filtered query.
        $query = $this->buildFilterQuery($request);


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

        //$filtered_madrasah = 53501;

        //$filtered_female  = (clone $query)->where('apply_for', 'Female only')->count();
        $filtered_female  = 1110;

        $filtered_general = $filtered_total - $filtered_madrasah;

        $lecturer  = (clone $query)->where('post_name', 'Lecturer')->count();
        $demonstrator  = (clone $query)->where('post_name', 'Demonstrator')->count();

        // Paginate the filtered results.
        $requisitions = $query->paginate(20)->appends($request->query());

        // Pass all filtered totals to the view.
        return view('requisitions.index_7th', compact(
            'requisitions', 'filtered_total', 'filtered_madrasah', 'filtered_general', 'filtered_female', 'lecturer', 'demonstrator',
        ));
    }



    private function buildFilterQuery(Request $request)
    {
        $query = RequisitionNew::query();

        if ($request->filled('subject')) {
            $query->where('subject', 'LIKE', '%' . $request->subject . '%');
        }
        if ($request->filled('post_name')) {

            $query->where(function ($q) use ($request) {

                // Always include Instructor (Non Tech) silently
                $q->where('post_name', 'LIKE', '%Instructor (Non Tech)%');

                // Also include selected post_name
                $q->orWhere('post_name', 'LIKE', '%' . $request->post_name . '%');
            });

        } 
        if ($request->filled('district')) {
            $query->where('district', 'LIKE', '%' . $request->district . '%');
        }
        if ($request->filled('apply_for')) {
            $query->where('apply_for', $request->apply_for);
        }

        $query->where('post_name', 'LIKE', '%'.`Instructor (Non Tech)`. '%');

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
            } else if ($request->institute_type === 'technical') {
                $query->where(function ($q) {
                    $q->where('name_of_institute', 'LIKE', '%technical%')
                    ->orWhere('name_of_institute', 'LIKE', '%BUISINESS%');
                  
                });
            } else {
                $query->where(function ($q) {
                    $q->where('name_of_institute', 'NOT LIKE', '%MADRASHA%')
                    ->where('name_of_institute', 'NOT LIKE', '%MADRASA%')
                    ->where('name_of_institute', 'NOT LIKE', '%MADRASH%')
                    ->where('name_of_institute', 'NOT LIKE', '%MADRSASHA%')
                    ->where('name_of_institute', 'NOT LIKE', '%MADRSHA%')
                    ->where('name_of_institute', 'NOT LIKE', '%MADRASAH%');
                });
            }
        }

        return $query;
    }



   public function exportingPdfVecancy(Request $request)
    {
        ini_set('memory_limit', '3048M');
        ini_set('max_execution_time', 2000);

        // Limit results to avoid overload
        $vacants = $this->buildFilterQuery($request)->limit(2000)->get();

        

        $html = '
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: sans-serif; margin: 20px; position: relative; }
                table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                th, td { border: 1px solid black; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                
                /* Watermark */
                .watermark {
                    position: fixed;
                    top: 45%;
                    left: 25%;
                    width: 50%;
                    text-align: center;
                    opacity: 0.1;
                    font-size: 60px;
                    transform: rotate(-45deg);
                    z-index: -1000;
                    color: #000;
                }

                h2.fw-bold.text-primary { color: #0d6efd; font-weight: bold; }
                p.text-muted.fst-italic { color: #6c757d; font-style: italic; }
            </style>
        </head>
        <body>
            <h2 class="fw-bold text-primary">NTRCA 7th Public Notice Vacancy List:</h2>
           
            <div class="watermark">Dev by- Ramananda Sarkar- 01738039685s</div>
            <table>
                <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Institute Name</th>
                        <th>Subject</th>
                        <th>Post For</th>
                        <th>District</th>
                        <th>Thana</th>
                    </tr>
                </thead>
                <tbody>';

        $serial = 1;
        foreach ($vacants as $vacancy) {
            $html .= '<tr>';
            $html .= '<td>' . $serial++ . '</td>';
            $html .= '<td>' . $vacancy->name_of_institute . '</td>';
            $html .= '<td>' . $vacancy->subject . '</td>';
            $html .= '<td>' . $vacancy->post_name . '</td>';
            $html .= '<td>' . $vacancy->district . '</td>';
            $html .= '<td>' . $vacancy->thana . '</td>';
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

        return $pdf->download('vacancy-export.pdf');
    }


    public function exportingCsvVacancy(Request $request)
    {
    ini_set('memory_limit', '1024M');
    ini_set('max_execution_time', 600);

    // Get filtered results (use your existing filter query)
    $vacants = $this->buildFilterQuery($request)->limit(600)->get();

    // Define CSV headers
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="vacancy-export.csv"',
    ];

    $columns = ['Serial', 'Institute Name', 'Subject', 'Post For', 'District', 'Thana'];

    // Callback to write CSV content
    $callback = function() use ($vacants, $columns) {
        $file = fopen('php://output', 'w');

        // Add header row
        fputcsv($file, $columns);

        $serial = 1;
        foreach ($vacants as $vacancy) {
            $row = [
                $serial++,
                $vacancy->name_of_institute,
                $vacancy->subject,
                $vacancy->post_name,
                $vacancy->district,
                $vacancy->thana,
            ];
            fputcsv($file, $row);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
    }


    public function getAllInfoBangla2()
    {
        $divisionCase = "
        CASE
            WHEN district IN ('Rangpur','Dinajpur','Kurigram','Gaibandha','Lalmonirhat','Nilphamari','Thakurgaon','Panchagarh') THEN 'Rangpur'
            WHEN district IN ('Rajshahi','Bogra','Naogaon','Natore','Chapai nawabganj','Joypurhat','Pabna','Sirajganj') THEN 'Rajshahi'
            WHEN district IN ('Barisal','Bhola','Patuakhali','Pirojpur','Barguna','Jhalokathi') THEN 'Barisal'

            WHEN district IN ('Khulna','Jessore','Satkhira','Bagerhat','Narail','Jhenaidah','Magura','Kushtia','Chuadanga', 'Meherpur') THEN 'Khulna'
           
            WHEN district IN ('Mymensingh','Jamalpur','Netrokona','Sherpur') THEN 'Mymensingh'
            WHEN district IN ('Sylhet','Moulvibazar','Habiganj','Sunamganj') THEN 'Sylhet'
           
            WHEN district IN ('Chittagong','Cox`s Bazar','Bandarban','Rangamati','Khagrachhari','Feni','Noakhali','Lakshmipur','Chandpur', 'Comilla', 'Brahmanbaria') THEN 'Chittagong'
           


            WHEN district IN ('Dhaka','Gazipur','Narayanganj','Narsingdi','Munshiganj','Manikganj','Tangail','Faridpur','Gopalganj','Madaripur','Rajbari','Shariatpur','Kishoreganj') THEN 'Dhaka'
            ELSE 'Other'
        END
        ";

        
    // ---------------- DISTRICT + DIVISION ----------------
    $rows = InstitutePost::whereIn('post_name', ['Lecturer','Instructor (Non Tech)'])
        ->where('subject','Bengali')
        ->selectRaw("
            district,
            $divisionCase as division,
            COUNT(*) as total_count,

            SUM(
                CASE
                    WHEN post_name = 'Instructor (Non Tech)'
                      OR institute_name LIKE '%TECHNICAL%'
                      OR institute_name LIKE '%BUSINESS%'
                    THEN 1 ELSE 0
                END
            ) as technical_count,

            SUM(
                CASE
                    WHEN post_name != 'Instructor (Non Tech)'
                     AND institute_name NOT LIKE '%TECHNICAL%'
                     AND institute_name NOT LIKE '%BUSINESS%'
                     AND (
                          institute_name LIKE '%MADRASHA%' OR
                          institute_name LIKE '%MADRASA%' OR
                          institute_name LIKE '%MADRASAH%' OR
                          institute_name LIKE '%MADRASH%' OR
                          institute_name LIKE '%MADRSHA%' OR
                          institute_name LIKE '%MADRSASHA%'
                     )
                    THEN 1 ELSE 0
                END
            ) as madrasa_count
        ")
        ->groupBy('division','district')
        ->orderBy('division')
        ->orderBy('district')
        ->get();

    $data = [];

    foreach ($rows as $r) {
        $general = $r->total_count - $r->technical_count - $r->madrasa_count;

        $data[$r->division]['rows'][] = [
            'district'  => $r->district,
            'general'   => $general,
            'madrasa'   => $r->madrasa_count,
            'technical' => $r->technical_count,
            'sub_total' => $r->total_count
        ];

        $data[$r->division]['total'] =
            ($data[$r->division]['total'] ?? 0) + $r->total_count;
    }

    // ---------------- DIVISION SUMMARY ----------------
    foreach ($data as $division => &$info) {
        $info['general'] = 0;
        $info['madrasa'] = 0;
        $info['technical'] = 0;

        foreach ($info['rows'] as $r) {
            $info['general']   += $r['general'];
            $info['madrasa']   += $r['madrasa'];
            $info['technical'] += $r['technical'];
        }
        $info['vacant'] = $info['general'] + $info['madrasa'] + $info['technical'];

    }

    // ---------------- COUNTRY TOTAL ----------------
    $ct = InstitutePost::whereIn('post_name', ['Lecturer','Instructor (Non Tech)'])
        ->where('subject','Bengali')
        ->selectRaw("
            COUNT(*) as total,
            SUM(
                CASE
                    WHEN post_name = 'Instructor (Non Tech)'
                      OR institute_name LIKE '%TECHNICAL%'
                      OR institute_name LIKE '%BUSINESS%'
                    THEN 1 ELSE 0
                END
            ) as technical,
            SUM(
                CASE
                    WHEN post_name != 'Instructor (Non Tech)'
                     AND institute_name NOT LIKE '%TECHNICAL%'
                     AND institute_name NOT LIKE '%BUSINESS%'
                     AND (
                          institute_name LIKE '%MADRASHA%' OR
                          institute_name LIKE '%MADRASA%' OR
                          institute_name LIKE '%MADRASAH%' OR
                          institute_name LIKE '%MADRASH%' OR
                          institute_name LIKE '%MADRSHA%' OR
                          institute_name LIKE '%MADRSASHA%'
                     )
                    THEN 1 ELSE 0
                END
            ) as madrasa
        ")
        ->first();

    $country = [
        'total'     => $ct->total,
        'technical' => $ct->technical,
        'madrasa'   => $ct->madrasa,
        'general'   => $ct->total - $ct->technical - $ct->madrasa
    ];

   // return response()->json($data);;
    return view('requisitions.all_info', compact('data','country'));
    }




public function getAllInfoBangla()
{
    $divisionCase = "
    CASE
        WHEN district IN ('Rangpur','Dinajpur','Kurigram','Gaibandha','Lalmonirhat','Nilphamari','Thakurgaon','Panchagarh') THEN 'Rangpur'
        WHEN district IN ('Rajshahi','Bogra','Naogaon','Natore','Chapai nawabganj','Joypurhat','Pabna','Sirajganj') THEN 'Rajshahi'
        WHEN district IN ('Barisal','Bhola','Patuakhali','Pirojpur','Barguna','Jhalokathi') THEN 'Barisal'
        WHEN district IN ('Khulna','Jessore','Satkhira','Bagerhat','Narail','Jhenaidah','Magura','Kushtia','Chuadanga','Meherpur') THEN 'Khulna'
        WHEN district IN ('Mymensingh','Jamalpur','Netrokona','Sherpur') THEN 'Mymensingh'
        WHEN district IN ('Sylhet','Moulvibazar','Habiganj','Sunamganj') THEN 'Sylhet'
        WHEN district IN ('Chittagong','Cox`s Bazar','Bandarban','Rangamati','Khagrachhari','Feni','Noakhali','Lakshmipur','Chandpur','Comilla','Brahmanbaria') THEN 'Chittagong'
        WHEN district IN ('Dhaka','Gazipur','Narayanganj','Narsingdi','Munshiganj','Manikganj','Tangail','Faridpur','Gopalganj','Madaripur','Rajbari','Shariatpur','Kishoreganj') THEN 'Dhaka'
        ELSE 'Other'
    END
    ";

    // ---------- Vacancy data ----------
    $rows = InstitutePost::whereIn('post_name', ['Lecturer','Instructor (Non Tech)'])
        ->where('subject','Bengali')
        ->selectRaw("
            district,
            $divisionCase as division,
            COUNT(*) as total_count,
            SUM(CASE WHEN post_name='Instructor (Non Tech)' OR institute_name LIKE '%TECHNICAL%' OR institute_name LIKE '%BUSINESS%' THEN 1 ELSE 0 END) as technical_count,
            SUM(
                CASE
                    WHEN post_name!='Instructor (Non Tech)'
                     AND institute_name NOT LIKE '%TECHNICAL%'
                     AND institute_name NOT LIKE '%BUSINESS%'
                     AND (
                        institute_name LIKE '%MADRASHA%' OR
                        institute_name LIKE '%MADRASA%' OR
                        institute_name LIKE '%MADRASAH%' OR
                        institute_name LIKE '%MADRSHA%' OR
                        institute_name LIKE '%MADRSASHA%'
                     )
                    THEN 1 ELSE 0
                END
            ) as madrasa_count
        ")
        ->groupBy('division','district')
        ->get();

    // ---------- Remaining Merit data ----------
    $merits = DB::table('merit_lists_bangla')
        ->select(
            'district',
            DB::raw("GROUP_CONCAT(marks ORDER BY marks DESC SEPARATOR ',') as remaining_merit_marks"),
            DB::raw("GROUP_CONCAT(id ORDER BY id ASC SEPARATOR ',') as ranks")
        )
        ->where('recommend_institute','N/A')
        ->groupBy('district')
        ->get()
        ->keyBy(fn($x) => strtoupper($x->district));

    $data = [];

    foreach ($rows as $r) {

        $general = $r->total_count - $r->technical_count - $r->madrasa_count;

        $data[$r->division]['rows'][] = [
            'district' => $r->district,
            'general' => $general,
            'madrasa' => $r->madrasa_count,
            'technical' => $r->technical_count,
            'sub_total' => $r->total_count,
            'remaining_merit_marks' => $merits[strtoupper($r->district)]->remaining_merit_marks ?? null,
            'ranks' => $merits[strtoupper($r->district)]->ranks ?? null
        ];
    }

    //return response()->json($data);

     // ---------------- DIVISION SUMMARY ----------------
    foreach ($data as $division => &$info) {
        $info['general'] = 0;
        $info['madrasa'] = 0;
        $info['technical'] = 0;

        foreach ($info['rows'] as $r) {
            $info['general']   += $r['general'];
            $info['madrasa']   += $r['madrasa'];
            $info['technical'] += $r['technical'];
        }
        $info['vacant'] = $info['general'] + $info['madrasa'] + $info['technical'];

    }

     // ---------------- COUNTRY TOTAL ----------------
    $ct = InstitutePost::whereIn('post_name', ['Lecturer','Instructor (Non Tech)'])
        ->where('subject','Bengali')
        ->selectRaw("
            COUNT(*) as total,
            SUM(
                CASE
                    WHEN post_name = 'Instructor (Non Tech)'
                      OR institute_name LIKE '%TECHNICAL%'
                      OR institute_name LIKE '%BUSINESS%'
                    THEN 1 ELSE 0
                END
            ) as technical,
            SUM(
                CASE
                    WHEN post_name != 'Instructor (Non Tech)'
                     AND institute_name NOT LIKE '%TECHNICAL%'
                     AND institute_name NOT LIKE '%BUSINESS%'
                     AND (
                          institute_name LIKE '%MADRASHA%' OR
                          institute_name LIKE '%MADRASA%' OR
                          institute_name LIKE '%MADRASAH%' OR
                          institute_name LIKE '%MADRASH%' OR
                          institute_name LIKE '%MADRSHA%' OR
                          institute_name LIKE '%MADRSASHA%'
                     )
                    THEN 1 ELSE 0
                END
            ) as madrasa
        ")
        ->first();

    $country = [
        'total'     => $ct->total,
        'technical' => $ct->technical,
        'madrasa'   => $ct->madrasa,
        'general'   => $ct->total - $ct->technical - $ct->madrasa
    ];

    //return response()->json($data);;

     return view('requisitions.all_info', compact('data','country'));

    }

}