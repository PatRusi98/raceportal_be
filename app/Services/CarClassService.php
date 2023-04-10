<?php

namespace App\Services;

use App\Models\CarClass;
use App\Models\CarClassAvailableCars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarClassService
{
    private $getResponse;

    public function get($id = null)
    {
        if(!$id) {
            $data = CarClass::all();
        } else {
            $data = CarClass::query()->where('series_id', '=', $id)->get();
        }

//        if($data->isEmpty())
//        {
//            throw new NotFoundHttpException("Class not found");
//        }

        foreach ($data as $entity) {
            $scoringService = new ScoringService();
            $carService = new CarService();

            $availableCars = [];
            $counter = 0;
            foreach (CarClassAvailableCars::query()->where('car_class_id', '=', $entity->id)->get() as $car) {
                $counter++;
                $availableCars[$counter] = $car->available_cars_id;
            }

            $toResponse = [];
            foreach ($availableCars as $carDetail) {
                $toResponse += $carService->get($carDetail);
            }

            $this->getResponse[] = [
                'id' => $entity->id,
                'name' => $entity->name,
                'color' => $entity->color,
                'maxEntries' => $entity->max_entries,
                'accCategoryId' => $entity->acc_category_id,
                'driversPerEntry' => $entity->drivers_per_entry,
                'needSamsLicense' => $entity->need_sams_license,
                'scoring' => $scoringService->get($entity->scoring_id),
                'availableCars' => $toResponse
            ];
        }
        return $this->getResponse;
    }

    public function getShort($id = null){
        if(!$id) {
            $data = CarClass::all();
        } else {
            $data = CarClass::query()->where('id', '=', $id)->get();
        }

//        if($data->isEmpty())
//        {
//            throw new NotFoundHttpException("Class not found");
//        }

        foreach ($data as $entity) {

            $this->getResponse[] = [
                'id' => $entity->id,
                'name' => $entity->name,
                'color' => $entity->color
            ];
        }
        return $this->getResponse;
    }

    public function store(Request $request, $id = null)
    {
        if ($id) {
            $entity = CarClass::findOrFail($id);
            $message = "Class has been successfully updated";
        } else {
            $entity = new CarClass();
            $message = "Class has been successfully created";
        }

        $rules = $this->rules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $entity->name = $request->input('name');
        $entity->save();

        return response()->json(["class"=>$entity,
            "message"=>$message], 200);
    }

    public function delete($id)
    {
        $find = CarClass::query()->where('id', '=', $id)->get();
        if ($find->isEmpty())
        {
            throw new NotFoundHttpException("Class not found");
        }

        $entity = CarClass::findOrFail($id);
        $entity->delete();
        return response()->json(["message"=>"Class was successfully deleted"], 200);
    }

    public function rules(){
        return [
            'color' => 'nullable|string|max:255',
            'driversPerEntry' => 'required|integer|min:0',
            'maxEntries' => 'required|integer|min:0',
            'name' => 'required|string|max:255',
            'needSamsLicense' => 'boolean',
            'scoringId' => 'required|integer',
            'seriesId' => 'required|integer',
            'accCategoryId' => 'nullable|integer',
        ];
    }
}
