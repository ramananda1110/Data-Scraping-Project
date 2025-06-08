<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Http;  // Import the Http facade
use Illuminate\Support\Str;
use App\Models\ScrapedData;

class DataScrapingController extends Controller
{
    public function data_scraping()
    {
        for ($i = 401000001; $i < 401000100; $i++) { 
            $roll = $i;

            // Send POST request
            $response = Http::timeout(60)->asForm()->post('http://ntrca.teletalk.com.bd/result/index.php', [
                'rollno'  => $roll,
                'exam'    => '18:18th:2023:2',
                'yes'     => 'YES',
                'button2' => 'Submit',
            ]);

            // Get the response body
            $data = $response->body();

            // Store only if it contains 'CONGRATULATIONS'
            if (Str::contains($data, 'CONGRATULATIONS')) {
                ScrapedData::updateOrCreate(
                    ['roll_no' => $roll], // Prevent duplicate entries
                    ['core_data' => $data]
                );
            }
        }

        return 'Data scraping completed!';
    }

    public function showScrapedData()
    {
        // Paginate the data instead of getting all at once
        $scrapedData = DB::table('scraped_data')
            ->selectRaw("id, roll_no, TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(core_data, 'Name: ', -1), '<br />', 1)) as name")
            ->paginate(15); // 10 items per page, adjust as needed
    
        return view('scraped_data', compact('scrapedData'));
    }

    public function finalDataScraping()
    {
        // Fetch all roll_no values from the 'bangla' table
      
        $students = DB::table('bangla')->select('roll_no')->get();

        foreach ($students as $student) {
            $roll = $student->roll_no;

            // Send POST request to external site
            $response = Http::timeout(60)->asForm()->post('http://ntrca.teletalk.com.bd/result/index.php', [
                'rollno'  => $roll,
                'exam'    => '18:18th:2023:3', // Change if needed
                'yes'     => 'YES',
                'button2' => 'Submit',
            ]);

            $data = $response->body();

            $finalResult = Str::contains($data, 'CONGRATULATIONS') ? 'passed' : 'failed';

            DB::table('bangla')
                ->where('roll_no', $roll)
                ->update(['final_result' => $finalResult]);
            }

        return response()->json(['message' => 'Final result update completed.']);
    }
    

    public function finalDataScrapingPhysics()
    {
        // Fetch all roll_no values from the 'bangla' table
      
        $students = DB::table('physics_data')->select('roll_no')->get();

        foreach ($students as $student) {
            $roll = $student->roll_no;

            // Send POST request to external site
            $response = Http::timeout(60)->asForm()->post('http://ntrca.teletalk.com.bd/result/index.php', [
                'rollno'  => $roll,
                'exam'    => '18:18th:2023:3', 
                'yes'     => 'YES',
                'button2' => 'Submit',
            ]);

            $data = $response->body();

            $finalResult = Str::contains($data, 'CONGRATULATIONS') ? 'passed' : 'failed';

            DB::table('physics_data')
                ->where('roll_no', $roll)
                ->update(['final_result' => $finalResult]);
            }

        return response()->json(['message' => 'Final result update completed.']);
    }

    public function finalResultPoliticalScience()
    {
       

        $rollNumbers = [

            
            404050041, 404050064, 404050089, 404050138, 404050166, 404050171, 404050182, 404050209, 404050235,
            404050243, 404050244, 404050438, 404050661, 404050698, 404050889, 404051227, 404051706, 404051707,
            404051722, 404051800, 404051805, 404051842, 404051854, 404051863, 404051873, 404051876, 404051894,
            404051903, 404051910, 404051915, 404051940, 404051942, 404052077, 404052078, 404052089, 404052100,
            404052105, 404052106, 404052120, 404052125, 404052132, 404052137, 404052151, 404052153, 404052159,
            404052162, 404052169, 404052172, 404052182, 404052193, 404052196, 404052244, 404052253, 404052269,
            404052274, 404052278, 404052291, 404052297, 404052321, 404052379, 404052410, 404052424, 404052434,
            404052445, 404052478, 404052493, 404052505, 404052531, 404052583, 404052612, 404052633, 404052634,
            404052641, 404052645, 404052648, 404052659, 404052802, 404052849, 404052950, 404052963, 404052965,
            404052966, 404052975, 404052978, 404052983, 404052996, 404053005, 404053041, 404053067, 404053081,
            404053094
        ];
        

        foreach ($rollNumbers as $roll) {
            try {
                $response = Http::timeout(60)->asForm()->post('http://ntrca.teletalk.com.bd/result/index.php', [
                    'rollno'  => $roll,
                    'exam'    => '18:18th:2023:3',
                    'yes'     => 'YES',
                    'button2' => 'Submit',
                ]);

                $data = $response->body();

                // Check for "CONGRATULATIONS"
                if (Str::contains($data, 'CONGRATULATIONS')) {
                    // Extract name using regex
                    preg_match('/Name:\s*(.+?)<br/i', $data, $matches);
                    $name = $matches[1] ?? null;
    
                    \Log::info("Saving data for Roll: $roll, Name: $name");

                    // Save only if name is found
                    if ($name) {
                        DB::table('political_sciences')->updateOrInsert(
                            ['roll_no' => $roll],
                            [
                                'final_result' => 'passed',
                                'name' => trim($name)
                            ]
                        );
                    }
                }

            } catch (\Exception $e) {
                // Optional: log or handle errors for failed requests
                \Log::error("Failed for roll: $roll, Error: " . $e->getMessage());
            }
        }

        return 'Data scraping completed!';
    }




    public function data_scraping17()
    {

        $rollNumbers  = [
            401010330, 401011105, 401020753, 401021173, 401023565, 401024805, 401023448, 401023487, 401024701, 401011167,
            401021055, 401023486, 401023490, 401023533, 401024860, 401021156, 401000310, 401011093, 401019892, 401020871,
            401021149, 401023494, 401000503, 401000980, 401009738, 401020859, 401021175, 401023468, 401023532, 401023612,
            401006475, 401011250, 401019295, 401020733, 401020781, 401021014, 401023492, 401024699, 401024714, 401000478,
            401000523, 401003266, 401005978, 401007131, 401013186, 401020776, 401020889, 401020996, 401021073, 401021327,
            401024733, 401000675, 401001122, 401006942, 401020788, 401020988, 401021157, 401022443, 401023450, 401023495,
            401023562, 401023589, 401024662, 401024675, 401024721, 401024732, 401000606, 401000952, 401000965, 401001004,
            401005971, 401014367, 401018999, 401019225, 401020759, 401020830, 401020838, 401020856, 401020896, 401021286,
            401022251, 401023541, 401023542, 401024668, 401024672, 401024793, 401025800, 401028580, 401000417, 401000595,
            401000616, 401006442, 401008566, 401009853, 401011094, 401011260, 401020886, 401021052, 401021111, 401021385,
            401022260, 401023535, 401023539, 401023545, 401024667, 401024828, 401024854, 401028513, 401000226, 401000475,
            401000977, 401002792, 401006041, 401006810, 401008601, 401008687, 401009098, 401011283, 401013070, 401016729,
            401016770, 401018189, 401018402, 401020900, 401020905, 401020911, 401021015, 401021082, 401021190, 401021372,
            401022470, 401024713, 401024727, 401026865, 401000535, 401002953, 401003300, 401003617, 401004002, 401004044,
            401006022, 401007185, 401007337, 401008802, 401011218, 401013257, 401013573, 401014027, 401014211, 401014237,
            401014504, 401015068, 401015499, 401016523, 401017282, 401017727, 401018807, 401020778, 401021158, 401021167,
            401021209, 401021246, 401022270, 401022284, 401022377, 401023461, 401024298, 401024734, 401024820, 401025172,
            401025817, 401026336, 401028462, 401000613, 401000632, 401000669, 401001181, 401003099, 401003463, 401004413,
            401004527, 401005796, 401006545, 401006828, 401007162, 401008623, 401008776, 401011760, 401013918, 401014152,
            401015902, 401016472, 401020764, 401020868, 401020920, 401020966, 401020972, 401021308, 401022259, 401023459,
            401023536, 401023584, 401024729, 401026408, 401000043, 401000468, 401000547, 401000551, 401000668, 401001139,
            401002137, 401003189, 401003464, 401004173, 401004363, 401004414, 401006936, 401007231, 401007605, 401008563,
            401008576, 401008598, 401009726, 401010557, 401010700, 401011206, 401011255, 401011294, 401011699, 401011803,
            401013796, 401014018, 401014041, 401014139, 401014358, 401014410, 401014674, 401015120, 401015223, 401015447,
            401017434, 401017661, 401017910, 401018730, 401018735, 401018826, 401020715, 401021116, 401021185, 401021329,
            401021738, 401022414, 401023207, 401024422, 401026849
        ];
        

        foreach ($rollNumbers as $roll) {
            
            // Send POST request
            $response = Http::timeout(60)->asForm()->post('http://ntrca.teletalk.com.bd/result/index.php', [
                'rollno'  => $roll,
                'exam'    => '17:17th:2020:3',
                'yes'     => 'YES',
                'button2' => 'Submit',
            ]);

            // Get the response body
            $data = $response->body();

            // Store only if it contains 'CONGRATULATIONS'
            if (Str::contains($data, 'CONGRATULATIONS')) {
                ScrapedData::updateOrCreate(
                    ['roll_no' => $roll], // Prevent duplicate entries
                    ['core_data' => $data]
                );
            }
        }

        return 'Data scraping completed!';
    }

    
}
