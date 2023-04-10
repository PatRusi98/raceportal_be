<?php

namespace App\Services;

use App\Models\ScoringFlScoring;
use App\Models\ScoringQualificationScoring;
use App\Models\ScoringRaceScoring;
use App\Models\Scoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ScoringService
{
    private $getResponse;

    public function get($id = null)
    {
        if(!$id) {
            $data = Scoring::all();
        } else {
            $data = Scoring::query()->where('id', '=', $id)->get();
            if($data->isEmpty())
            {
                throw new NotFoundHttpException("Scoring not found");
            }
        }

        foreach ($data as $entity) {
            $raceScoring = [];
            foreach (ScoringRaceScoring::query()->where('scoring_id', '=', $entity->id)->get() as $scoring) {
                $raceScoring[$scoring->race_scoring_key] = $scoring->race_scoring;
            }

            $qualificationScoring = [];
            foreach (ScoringQualificationScoring::query()->where('scoring_id', '=', $entity->id)->get() as $scoring) {
                $qualificationScoring[$scoring->qualification_scoring_key] = $scoring->qualification_scoring;
            }

            $flScoring = [];
            foreach (ScoringFlScoring::query()->where('scoring_id', '=', $entity->id)->get() as $scoring) {
                $flScoring[$scoring->fl_scoring_key] = $scoring->fl_scoring;
            }

            $this->getResponse[] = [
                'id' => $entity->id,
                'name' => $entity->name,
                'raceScoringCars' => $entity->race_scoring_cars,
                'raceScoring' => $raceScoring,
                'qualificationScoringCars' => $entity->qualification_scoring_cars,
                'qualificationScoring' => $qualificationScoring,
                'flScoringCars' => $entity->fl_scoring_cars,
                'flScoring' => $flScoring,
            ];
        }
        return $this->getResponse;
    }

    public function store(Request $request, $id = null)
    {
        $update = null;
        if ($id) {
            $scoring = Scoring::findOrFail($id);
            $update = true;
            $message = "Scoring has been successfully updated";
        } else {
            $scoring = new Scoring;
            $update = false;
            $message = "Scoring has been successfully created";
        }

        $qualiRequest = new Request($request->input('qualificationScoring'));
        $raceRequest = new Request($request->input('raceScoring'));
        $flRequest = new Request($request->input('flScoring'));

        $rules = $this->rules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $scoring->fl_scoring_cars = $request->input('flScoringCars');
        $scoring->name = $request->input('name');
        $scoring->qualification_scoring_cars = $request->input('qualificationScoringCars');
        $scoring->race_scoring_cars = $request->input('raceScoringCars');
        $scoring->save();

        $this->storeraceScoring($raceRequest, $scoring->id, $request->input('raceScoringCars'));
        $this->storequalificationScoring($qualiRequest, $scoring->id, $request->input('qualificationScoringCars'));
        $this->storeflScoring($flRequest, $scoring->id, $request->input('flScoringCars'));

        return response()->json(["scoring"=>$scoring,
            "message"=>$message], 200);
    }

    public function storeraceScoring(Request $request, $id, $cars) {
//        $scoring = ScoringRaceScoring::query()->where('scoring_id', '=', $id)->get();
//        if ($scoring->isEmpty()) {
//            $scoring = new ScoringRaceScoring();
//        }

        for ($i = 1; $i >= $cars; $i++) {
            $scoring = new ScoringRaceScoring();
            $scoring->scoring_id = $id;
            $scoring->race_scoring = $request->input($i);
            $scoring->race_scoring_key = $i;
            $scoring->save();
        }
    }

    public function storequalificationScoring(Request $request, $id, $cars) {
//        $scoring = ScoringQualificationScoring::query()->where('scoring_id', '=', $id)->get();
//        if ($scoring->isEmpty()) {
//            $scoring = new ScoringQualificationScoring();
//        }

        for ($i = 1; $i >= $cars; $i++) {
            $scoring = new ScoringQualificationScoring();
            $scoring->scoring_id = $id;
            $scoring->race_scoring = $request->input($i);
            $scoring->race_scoring_key = $i;
            $scoring->save();
        }
    }

    public function storeflScoring(Request $request, $id, $cars) {
//        $scoring = ScoringFlScoring::query()->where('scoring_id', '=', $id)->get();
//        if ($scoring->isEmpty()) {
//            $scoring = new ScoringFlScoring();
//        }

        for ($i = 1; $i >= $cars; $i++) {
            $scoring = new ScoringFlScoring();
            $scoring->scoring_id = $id;
            $scoring->race_scoring = $request->input($i);
            $scoring->race_scoring_key = $i;
            $scoring->save();
        }
    }

    public function delete($id)
    {
        $scoringFind = Scoring::query()->where('id', '=', $id)->get();
        if ($scoringFind->isEmpty())
        {
            throw new NotFoundHttpException("Scoring not found");
        }

        $scoring = Scoring::findOrFail($id);
        $scoring->delete();
        return response()->json(["message"=>"Scoring was successfully deleted"], 200);
    }

    public function rules(){
        return [

        ];
    }
}
