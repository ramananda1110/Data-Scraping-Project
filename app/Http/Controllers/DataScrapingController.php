<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Http;  // Import the Http facade
use Illuminate\Support\Str;
use App\Models\ScrapedData;

class DataScrapingController extends Controller
{
    public function data_scraping()
    {
        for ($i = 401000062; $i < 401000200; $i++) { 
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
}
