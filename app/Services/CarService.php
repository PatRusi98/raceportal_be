<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarService
{
    private $getResponse;

    public function get($id = null)
    {
        if(!$id) {
            $data = Car::all();
        } else {
            $data = Car::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Car not found");
        }

        foreach ($data as $entity) {
            $this->getResponse = ([
                'id' => $entity->id,
                'name' => $entity->name,
                'model' => $entity->model,
                'simulator' => $entity->simulator,
            ]);
        }
        return $this->getResponse;
    }

    public function getAll($id = null)
    {
        if(!$id) {
            $data = Car::all();
        } else {
            $data = Car::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Car not found");
        }

        foreach ($data as $entity) {
            $this->getResponse[] = [
                'id' => $entity->id,
                'name' => $entity->name,
                'model' => $entity->model,
                'simulator' => $entity->simulator,
            ];
        }
        return $this->getResponse;
    }

    public function getShort($id = null)
    {
        if(!$id) {
            $data = Car::all();
        } else {
            $data = Car::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Car not found");
        }

        foreach ($data as $entity) {
            $this->getResponse = ([
                'id' => $entity->id,
                'name' => $entity->name
            ]);
        }
        return $this->getResponse;
    }

    public function getAllShort($id = null)
    {
        if(!$id) {
            $data = Car::all();
        } else {
            $data = Car::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Car not found");
        }

        foreach ($data as $entity) {
            $this->getResponse[] = [
                'id' => $entity->id,
                'name' => $entity->name
            ];
        }
        return $this->getResponse;
    }

    public function store(Request $request, $id = null)
    {
        if ($id) {
            $car = Car::findOrFail($id);
            $message = "Car has been successfully updated";
        } else {
            $car = new Car;
            $message = "Car has been successfully created";
        }

        $rules = $this->rules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $car->name = $request->input('name');
        $car->model = $request->input('model');
        $car->simulator = $request->input('simulator');
        $car->save();

        return response()->json(["car"=>$car,
            "message"=>$message], 200);
    }

    public function delete($id)
    {
        $carFind = Car::query()->where('id', '=', $id)->get();
        if ($carFind->isEmpty())
        {
            throw new NotFoundHttpException("Car not found");
        }

        $car = Car::findOrFail($id);
        $car->delete();
        return response()->json(["message"=>"Car was successfully deleted"], 200);
    }

    public function rules(){
        return [
            'name' => 'required|string|max:255',
            'model' => 'nullable|string',
            'simulator' => 'required|string|max:255',
        ];
    }
}
