<?php

namespace App\Services;

use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TrackService
{
    private $getResponse;

    public function get($id = null)
    {
        if(!$id) {
            $data = Track::all();
        } else {
            $data = Track::query()->where('id', '=', $id)->get();
            if($data->isEmpty())
            {
                throw new NotFoundHttpException("Track not found");
            }
        }

        foreach ($data as $entity) {
            $this->getResponse = ([
                'id' => $entity->id,
                'name' => $entity->name,
            ]);
        }
        return $this->getResponse;
    }

    public function getAll($id = null)
    {
        if(!$id) {
            $data = Track::all();
        } else {
            $data = Track::query()->where('id', '=', $id)->get();
            if($data->isEmpty())
            {
                throw new NotFoundHttpException("Track not found");
            }
        }

        foreach ($data as $entity) {
            $this->getResponse[] = [
                'id' => $entity->id,
                'name' => $entity->name,
            ];
        }
        return $this->getResponse;
    }

    public function store(Request $request, $id = null)
    {
        if ($id) {
            $entity = Track::findOrFail($id);
            $message = "Track has been successfully updated";
        } else {
            $entity = new Track();
            $message = "Track has been successfully created";
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

        return response()->json(["track"=>$entity,
            "message"=>$message], 200);
    }

    public function delete($id)
    {
        $find = Track::query()->where('id', '=', $id)->get();
        if ($find->isEmpty())
        {
            throw new NotFoundHttpException("Track not found");
        }

        $entity = Track::findOrFail($id);
        $entity->delete();
        return response()->json(["message"=>"Track was successfully deleted"], 200);
    }

    public function rules(){
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
