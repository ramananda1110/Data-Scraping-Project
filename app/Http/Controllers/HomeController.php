<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }


    public function data_scraping()
    {
        
        for ($i=462002985; $i < 462004000; $i++) { 
            $roll = $i;
            // $response = Http::asForm()->post('http://ntrca.teletalk.com.bd/result/index.php', [
                $response = Http::timeout(60)->asForm()->post('http://ntrca.teletalk.com.bd/result/index.php', [
    
                'rollno'  => $roll,
                'exam'    => '18:18th:2023:1',
                'yes'     => 'YES',
                'button2' => 'Submit',
            ]);
            
            // Get the response body
            $data = $response->body();


            if(Str::contains($data, 'CONGRATULATIONS')){
                $test_data = new StoreDataModel;
                $test_data->roll_no = $roll;
                $test_data->core_data = $data;
                $test_data->save();
            }
        }

        return 'ok';
        
    }
}
