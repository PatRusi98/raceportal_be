<?php

namespace App\Services;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LicenseService
{
    private $getResponse;

    public function get($id = null)
    {
        if(!$id) {
            $data = License::all();
        } else {
            $data = License::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("License not found");
        }

        foreach ($data as $entity) {
            $this->getResponse = ([
                'id' => $entity->id,
                'name' => $entity->name,
                'description' => $entity->description,
                'color' => $entity->color,
            ]);
        }
        return $this->getResponse;
    }

    public function getAll($id = null)
    {
        if(!$id) {
            $data = License::all();
        } else {
            $data = License::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("License not found");
        }

        $this->getResponse = [];

        foreach ($data as $entity) {
            $this->getResponse[] = [
                'id' => $entity->id,
                'name' => $entity->name,
                'description' => $entity->description,
                'color' => $entity->color,
            ];
        }
        return $this->getResponse;
    }

    public function store(Request $request, $id = null)
    {
        if ($id) {
            $entity = License::findOrFail($id);
            $message = "License has been successfully updated";
        } else {
            $entity = new License;
            $message = "License has been successfully created";
        }

        $rules = $this->rules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $entity->name = $request->input('name');
        $entity->description = $request->input('description');
        $entity->color = $request->input('color');
        $entity->save();

        return response()->json(["license"=>$entity,
            "message"=>$message], 200);
    }

    public function delete($id)
    {
        $find = License::query()->where('id', '=', $id)->get();
        if ($find->isEmpty())
        {
            throw new NotFoundHttpException("License not found");
        }

        $entity = License::findOrFail($id);
        $entity->delete();
        return response()->json(["message"=>"License was successfully deleted"], 200);
    }

    public function rules(){
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:255',
        ];
    }
}
