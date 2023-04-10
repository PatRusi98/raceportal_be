<?php

namespace App\Services;

use App\Models\EntryDrivers;
use App\Models\Users;
use App\Models\UserLicenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService
{
    private $getResponse;

    public function get($id = null)
    {
        if(!$id) {
            $data = Users::all();
        }
        else {
            $data = Users::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("User not found");
        }

        foreach ($data as $entity) {
            $licenseService = new LicenseService();
            $license = UserLicenses::query()->where('users_id', '=', $entity->id)->value('licenses_id');

            $this->getResponse[] = [
                'id' => $entity->id,
                'name' => $entity->name,
                'username' => $entity->username,
                'accFirstName' => $entity->acc_first_name,
                'accLastName' => $entity->acc_last_name,
                'accShortName' => $entity->acc_short_name,
                'acShortName' => $entity->ac_short_name,
                'acFirstName' => $entity->ac_first_name,
                'acLastName' => $entity->ac_last_name,
                'rreId' => $entity->rre_id,
                'steamId' => $entity->steam_id,
                'iban' => $entity->iban,
                'phone' => $entity->phone,
                'address' => $entity->address,
                'country' => $entity->country,
                'shirt' => $entity->shirt,
                'licenseSams' => $entity->licence_sams,
                'birth' => $entity->birth,
                'avatar' => $entity->avatar,
                'licenses' => $licenseService->get($license),
                'role' => $entity->role,
            ];
        }
        return $this->getResponse;
    }

    public function getShort($id = null, $columnId = true) {
        if(!$id) {
            $data = Users::all();
        } else {
            $data = Users::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("User not found");
        }

        foreach ($data as $entity) {
            if ($columnId) {
                $this->getResponse = [
                    'id' => $entity->id,
                    'name' => $entity->name,
                    'country' => $entity->country
                ];
            } else {
                $this->getResponse = [
                    'name' => $entity->name,
                    'country' => $entity->country
                ];
            }
        }
        return $this->getResponse;
    }

    public function getDriverByEntry($entryId, $columnId = true) {
        $data = EntryDrivers::query()->where('entry_id', '=', $entryId)->get();

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Driver not found");
        }

        foreach ($data as $entity) {
            $this->getResponse = [
                $this->getShort($entity->drivers_id, $columnId)
            ];
            return $this->getResponse;
        }
    }

    public function store(Request $request, $id = null)
    {
        if ($id) {
            $entity = Users::findOrFail($id);
            $message = "Event has been successfully updated";
        } else {
            $entity = new Users();
            $message = "Event has been successfully created";
        }

        $rules = $this->rules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $entity->name = $request->input('name');
        $entity->code = $request->input('code');
        $entity->description = $request->input('description');
        $entity->briefing = $request->input('briefing');
        $entity->race_start = $request->input('raceStart');
        $entity->qualify_start = $request->input('qualifyStart');
        $entity->practice_start = $request->input('practiceStart');
        $entity->series_id = $request->input('seriesId');
        $entity->state = $request->input('state');
        $entity->save();

        return response()->json(["track"=>$entity,
            "message"=>$message], 200);
    }

    public function delete($id)
    {
        $find = Users::query()->where('id', '=', $id)->get();
        if ($find->isEmpty())
        {
            throw new NotFoundHttpException("Event not found");
        }

        $entity = Users::findOrFail($id);
        $entity->delete();
        return response()->json(["message"=>"Event was successfully deleted"], 200);
    }

    public function rules(){
        return [
            'name' => 'required|string|max:255',
            'description' => 'text',
            'image' => 'string',
            'state' => 'string',
            'series_id' => 'integer',
            'code' => 'string',
            'briefing' => 'text',
        ];
    }
}
