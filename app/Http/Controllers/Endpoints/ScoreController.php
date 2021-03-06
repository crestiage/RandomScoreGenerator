<?php

namespace App\Http\Controllers\Endpoints;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Score;

use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{

    public function test(Request $request){   
        return response()->json($request->all(), 201);
    }

    public function add(Request $request){
        $rangeFrom = $request->get("from_score_range");
        $rangeTo = $request->get("to_score_range");
        
        if ($rangeFrom > $rangeTo){
            return response()->json("Invalid range from and range to", 400);
        }
        
        $generatedScore = $this->createRandomScore($rangeFrom, $rangeTo);
        
        $score = new Score ([
            "from_score_range" => $rangeFrom,
            "to_score_range" => $rangeTo,
            "score_generated" => $generatedScore
        ]);

        $score->save();

        // $score = Score::create($request->all()); 
        return response()->json($score, 201);
    }

    private function createRandomScore($rangeFrom, $rangeTo){
        return rand($rangeFrom, $rangeTo);
    }

    public function getAllScores(){
        $data = array("data" => Score::all());
        return response()->json($data, 200);
    }

    public function getScoreSubmissionsByDate(){
        $dataset = DB::table("score")
                    ->select(DB::raw("date_format(created_at, '%Y-%m-%d') as date_generated, count(id) as score_count"))
                    ->groupBy("date_generated")
                    ->get();
        $data = array("data" => $dataset);
        return response()->json($data, 200);
    }

}
