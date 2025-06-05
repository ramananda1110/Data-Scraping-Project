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
        $students = Bangla::select('roll_no')->get();

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

            // Determine result
            $finalResult = Str::contains($data, 'CONGRATULATIONS') ? 'passed' : 'failed';

            // Update the final_result in the bangla table
            Bangla::where('roll_no', $roll)->update([
                'final_result' => $finalResult
            ]);
        }

        return response()->json(['message' => 'Final result update completed.']);
    }
    
}
