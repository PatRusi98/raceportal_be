<?php

namespace App\Services;

use App\Enums\EventStateEnum;
use App\Enums\PenaltyTypeEnum;
use App\Enums\SessionTypeEnum;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Penalty;
use App\Models\Result;
use App\Models\Session;
use App\Models\Warning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventService
{
    private $getResponse;

    public function get($id = null)
    {
        if(!$id) {
            $data = Event::query()->where('state', '=', 'UPCOMING')->get();;
        }
        else {
            $data = Event::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Event not found");
        }

        foreach ($data as $entity) {
            $series = new SeriesService();

            $this->getResponse = ([
                'id' => $entity->id,
                'name' => $entity->name,
                'code' => $entity->code,
                'description' => $entity->description,
                'briefing' => $entity->briefing,
                'image' => $entity->image,
                'raceStart' => $entity->race_start,
                'qualifyStart' => $entity->qualify_start,
                'practiceStart' => $entity->practice_start,
                'seriesId' => $entity->series_id,
                'state' => $entity->state,
                'series' => $series->getAllShort($entity->series_id)
            ]);
        }
        return $this->getResponse;
    }

    public function getAll($id = null)
    {
        $data = Event::query()->where('state', '=', 'UPCOMING')->get();

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Event not found");
        }

        foreach ($data as $entity) {
            $series = new SeriesService();

            $this->getResponse[] = [
                'id' => $entity->id,
                'name' => $entity->name,
                'code' => $entity->code,
                'description' => $entity->description,
                'briefing' => $entity->briefing,
                'image' => $entity->image,
                'raceStart' => $entity->race_start,
                'qualifyStart' => $entity->qualify_start,
                'practiceStart' => $entity->practice_start,
                'seriesId' => $entity->series_id,
                'state' => $entity->state,
                'series' => $series->getAllShort($entity->series_id)
            ];
        }
        return $this->getResponse;
    }

    public function getShort($id = null) {
        if(!$id) {
            $data = Event::all();
        } else {
            $data = Event::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Event not found");
        }

        foreach ($data as $entity) {
            $this->getResponse = [
                'id' => $entity->id,
                'name' => $entity->name,
                'code' => $entity->code,
                'state' => $entity->state
            ];
        }
        return $this->getResponse;
    }

    public function store(Request $request, $id = null)
    {
        if ($id) {
            $entity = Event::findOrFail($id);
            $message = "Event has been successfully updated";
        } else {
            $entity = new Event();
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
        $entity->description = $request->input('description');
        $entity->race_start = $request->input('raceStart');
        $entity->qualify_start = $request->input('qualifyStart');
        $entity->practice_start = $request->input('practiceStart');
        $entity->series_id = $request->input('seriesId');
        $entity->state = EventStateEnum::UPCOMING;
        $entity->save();

        return response()->json(["track"=>$entity,
            "message"=>$message], 200);
    }

    public function update(Request $request, $id = null)
    {
        if ($id) {
            $entity = Event::findOrFail($id);
        } else {
            $entity = new Event();
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
        $entity->image = $request->input('image');
        $entity->description = $request->input('description');
        $entity->briefing = $request->input('briefing');
        $entity->race_start = $request->input('raceStart');
        $entity->qualify_start = $request->input('qualifyStart');
        $entity->practice_start = $request->input('practiceStart');
        $entity->series_id = $request->input('seriesId');
        $entity->state = $request->input('state');
        $entity->save();

        return response()->json($entity, 200);
    }

    public function getResult($id){
        $data = Session::query()->where('event_id', '=', $id)->get();

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Series not found");
        }

        $result = [];

        foreach ($data as $entity) {

            $result[] = [
                'id' => $entity->id,
                'type' => SessionTypeEnum::from($entity->type)->name,
                'results' => $this->getSessionResult($entity->id)
            ];
        }
        return $result;
    }

    public function getSessionResult($id) {
        $data = Result::query()->where('session_id', '=', $id)->get();

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Series not found");
        }

        $sessionResult = [];

        foreach ($data as $entity) {
            $seriesService = new SeriesService();

            $sessionResult[] = [
                'id' => $entity->id,
                'lapCount' => $entity->lap_count,
                'bestLap' => $entity->best_lap,
                'lastLap' => $entity->last_lap,
                'totalTime' => $entity->total_time,
                'totalTimeWithPenalties' => $entity->total_time_with_penalties,
                'points' => $entity->points,
                'position' => $entity->position,
                'positionInClass' => $entity->position_inclass,
                'entry' => $seriesService->getEntry(id: $entity->entry_id),
                'warnings' => $this->getWarning($entity->id),
                'participants' => $this->getParticipant($entity->id),
                'penalties' => $this->getPenalty($entity->id)
            ];
        }
         return $sessionResult;
    }

    public function getEventPoints($eventId, $entryId, $seriesId) {
        //$data = Session::query()->where('event_id', '=', $eventId)->get();
        $event = Event::query()->where('series_id', '=', $seriesId)->get();

        foreach ($event as $item) {
            $data[] = [
                //$item->id
                Session::query()->where('event_id', '=', $item->id)->value('id')
            ];
        }

        return $data;

        //if($data->isEmpty())
        //{
        //    throw new NotFoundHttpException("Session not found");
        //}

        //foreach ($data as $entity) {
        //    $session[] = [
        //        $entity->id
        //    ];
        //}

        $points = 0;
        foreach ($data as $id) {
            $result = Result::query()->where([['session_id', '=', $id], ['entry_id', '=', $entryId]])->value('points');
            $points += $result;
        }

        $response[] = [
            'points' => $points
        ];

        return $response;
    }

    public function getWarning($id) {
        $data = Warning::query()->where('result_id', '=', $id)->get();

        $warning = [];

        foreach ($data as $entity) {
            $warning[] = [
                'id' => $entity->id,
                'warningText' => $entity->warning_text,
            ];
        }
        return $warning;
    }

    public function getParticipant($id) {
        $data = Participant::query()->where('result_id', '=', $id)->get();

        $participant = [];

        foreach ($data as $entity) {
            $userService = new UserService();

            $participant[] = [
                'id' => $entity->id,
                'firstname' => $entity->firstname,
                'lastname' => $entity->lastname,
                'steamId' => $entity->steam_id,
                'user' => $userService->getShort($entity->user_id)
            ];
        }
        return $participant;
    }

    public function getPenalty($id) {
        $data = Penalty::query()->where('result_id', '=', $id)->get();

        $penalty = [];

        foreach ($data as $entity) {
            $penalty[] = [
                'id' => $entity->id,
                'reason' => $entity->reason,
                'penalty' => $entity->penalty,
                'value' => $entity->value,
                'violationLap' => $entity->violation_lap,
                'clearedLap' => $entity->cleared_lap,
                'type' => PenaltyTypeEnum::from($entity->type)->value
            ];
        }
        return $penalty;
    }

    public function delete($id)
    {
        $entity = Event::findOrFail($id);
        $entity->delete();
        return response()->json(["message"=>"Event was successfully deleted"], 200);
    }

    public function deleteSession($id)
    {
        $entity = Session::findOrFail($id);
        $entity->delete();
        return response()->json(["message"=>"Session was successfully deleted"], 200);
    }

    public function rules(){
        return [
            //'name' => 'required|string|max:255',
            //'description' => 'text',
            //'image' => 'string',
            //'state' => 'string',
            //'series_id' => 'integer',
            //'code' => 'string',
            //'briefing' => 'text',
        ];
    }
}
