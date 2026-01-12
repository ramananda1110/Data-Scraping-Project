<?php

namespace App\Http\Controllers;

use App\Models\InstitutePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class InstitutePostController extends Controller
{
   

   public function importBulk(Request $request)
    {
        foreach ($request->data as $item) {

            $index_id = $item['requisitionId'];  // same role as $item[0]

           // $existing = InstitutePost::where('index_id', $index_id)->first();

            //if (!$existing) {

                InstitutePost::create([
                    'index_id'       => $index_id,
                    'eiin'           => $item['eiin'],
                    'institute_name' => $item['instName'],
                    'authority'      => $item['authority'],
                    'subject'        => $item['subName'],
                    'post_name'      => $item['postName'],
                    'thana'          => $item['thanaName'],
                    'district'       => $item['distName'],
                    'level'          => $item['levelName'],
                ]);
            //}
        }

        return response()->json(['status'=>true,'message'=>'Imported successfully']);
    }



    public function index(Request $request)
    {
        $query = $this->buildFilterQuery($request);

        // Clone base query for counts
        $baseQuery = clone $query;

        $filtered_total = $baseQuery->count();

       // Technical
        $filtered_technical = (clone $query)->where(function ($q) {
            $q->where('post_name','Instructor (Non Tech)')
            ->orWhere('institute_name','LIKE','%TECHNICAL%')
            ->orWhere('institute_name','LIKE','%BUSINESS%');
        })->count();

        // Madrasah (never technical)
        $filtered_madrasah = (clone $query)->where(function ($q) {
            $q->where('post_name','!=','Instructor (Non Tech)')
            ->where(function ($x) {
                $x->where('institute_name','LIKE','%MADRASHA%')
                    ->orWhere('institute_name','LIKE','%MADRASA%')
                    ->orWhere('institute_name','LIKE','%MADRASAH%')
                    ->orWhere('institute_name','LIKE','%MADRSHA%')
                     ->orWhere('institute_name','LIKE','%MADRAHAD%')
                      ->orWhere('institute_name','LIKE','%MADRAHAD%')
                    ->orWhere('institute_name','LIKE','%MADRA%')

                    ->orWhere('institute_name','LIKE','%MADRSASHA%');
            });
        })->count();

        $filtered_total = $query->count();


        // General = total - madrasa - technical
        $filtered_general = $filtered_total - $filtered_madrasah - $filtered_technical;

        $requisitions = $query->paginate(20)->appends($request->query());

        return view('requisitions.bangla', compact(
            'requisitions',
            'filtered_total',
            'filtered_general',
            'filtered_technical',
            'filtered_madrasah'
        ));
    }


   private function buildFilterQuery(Request $request)
    {
        $query = InstitutePost::query();

        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        if ($request->filled('authority')) {
            $query->where('authority', $request->authority);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('institute_type')) {

            if ($request->institute_type === 'madrasha') {
                $query->where(function ($q) {
                    $q->where('institute_name','LIKE','%MADRASHA%')
                    ->orWhere('institute_name','LIKE','%MADRASA%')
                    ->orWhere('institute_name','LIKE','%MADRASAH%')
                    ->orWhere('institute_name','LIKE','%MADRSHA%')
                    ->orWhere('institute_name','LIKE','%MADRAHAD%')
                    ->orWhere('institute_name','LIKE','%MADRA%')
                    ->orWhere('institute_name','LIKE','%MADRSASHA%');
                });
            }

            elseif ($request->institute_type === 'technical') {
                $query->where(function ($q) {
                     $q->where('post_name','Instructor (Non Tech)')
                    ->orWhere('institute_name','LIKE','%TECHNICAL%')
                    ->orWhere('institute_name','LIKE','%BUSINESS%');
                });

                
            }

            else { // general
                $query->where(function ($q) {
                    $q->where('institute_name','NOT LIKE','%MADRASHA%')
                    ->where('institute_name','NOT LIKE','%MADRASA%')
                    ->where('institute_name','NOT LIKE','%MADRASAH%')
                    ->where('institute_name','NOT LIKE','%MADRSHA%')
                    ->where('institute_name','NOT LIKE','%MADRSASHA%')
                     ->where('institute_name','LIKE','%MADRAHAD%')
                    ->where('institute_name','NOT LIKE','%TECHNICAL%')
                    ->where('institute_name','NOT LIKE','%BUSINESS%')
                    ->where('post_name','NOT LIKE','%Instructor (Non Tech)%');
                });
            }
        }

        return $query;
    }


    public function vacanciesByDistrict(Request $request)
    {
        $requisitions = InstitutePost::where('district', $request->district)
            ->whereIn('post_name', ['Lecturer','Instructor (Non Tech)'])
            ->orderBy('institute_name')
            ->get();

        return view('requisitions.vacant_table', compact('requisitions'));
    }


}



