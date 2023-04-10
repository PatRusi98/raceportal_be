<?php

namespace App\Services;

use App\Enums\EntryStateEnum;
use App\Enums\EventStateEnum;
use App\Enums\SeriesStateEnum;
use App\Models\Car;
use App\Models\CarClass;
use App\Models\Entry;
use App\Models\Event;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function Composer\Autoload\includeFile;

class SeriesService
{
    private $getResponse;
    private $response;

    public function get($id = null)
    {
        if(!$id) {
            $data = Series::all();
        } elseif ($id == -1) {
            $data = Series::query()->where('state', '=', SeriesStateEnum::ACTIVE->value)->get();
        } else {
            $data = Series::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Series not found");
        }

        $this->getResponse = [];

        foreach ($data as $entity) {
            $this->getResponse[] = [
                'id' => $entity->id,
                'name' => $entity->name,
                'code' => $entity->code,
                'description' => $entity->description,
                'rules' => $entity->rules,
                'color' => $entity->color,
                'image' => $entity->image,
                'state' => $entity->state,
                'simulator' => $entity->simulator,
                'registrations' => $entity->registrations,
                'teamsEnable' => $entity->teams_enable,
                'multiclass' => $entity->multiclass,
            ];
        }

        $this->response = [];

        $counter = 0;
        foreach ($this->getResponse as $entry) {
            $entryObj = [
                'id' => $entry['id'],
                'name' => $entry['name'],
                'code' => $entry['code'],
                'description' => $entry['description'],
                'rules' => $entry['rules'],
                'color' => $entry['color'],
                'image' => $entry['image'],
                'state' => $entry['state'],
                'simulator' => $entry['simulator'],
                'registrations' => $entry['registrations'],
                'teamsEnable' => $entry['teamsEnable'],
                'multiclass' => $entry['multiclass'],
            ];

            $carClassesService = new CarClassService();

            $entryObj['classes'] = $carClassesService->get($entry['id']);
            $this->response[] = $entryObj;
            $counter++;
        }
        return $this->response;
    }

    public function getShort($id = null) {
        if(!$id) {
            $data = Series::all();
        } else {
            $data = Series::query()->where('id', '=', $id)->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Series not found");
        }

        foreach ($data as $entity) {
            $this->getResponse = [
                'id' => $entity->id,
                'name' => $entity->name,
                'code' => $entity->code,
                'color' => $entity->color,
                'simulator' => $entity->simulator
            ];
        }
        return $this->getResponse;
    }

    public function store(Request $request, $id = null)
    {
        if ($id) {
            $entity = Series::findOrFail($id);
            $message = "Series has been successfully updated";
        } else {
            $entity = new Series();
            $message = "Series has been successfully created";
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
        $entity->simulator = $request->input('simulator');
        $entity->color = "";
        $entity->description = "";
        $entity->image = "";
        $entity->multiclass = 0;
        $entity->name = "";
        $entity->registrations = 0;
        $entity->rules = "";
        $entity->state = SeriesStateEnum::PREPARING->value;
        $entity->teams_enable = 0;


        $entity->save();

        return response()->json(["track"=>$entity,
            "message"=>$message], 200);
    }

    public function update(Request $request, $id) {
        $entity = Series::findOrFail($id);
        $message = "Series has been successfully updated";

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
        $entity->rules = $request->input('rules');
        $entity->color = $request->input('color');
        $entity->image = $request->input('image');
        $entity->state = $request->input('state');
        $entity->simulator = $request->input('simulator');
        $entity->registrations = $request->input('registrations');
        $entity->teams_enable = $request->input('teamsEnable');
        $entity->multiclass = $request->input('multiclass');
        $entity->save();

        return response()->json([$entity,
            "message"=>$message], 200);
    }

    public function delete($id)
    {
        $find = Series::query()->where('id', '=', $id)->get();
        if ($find->isEmpty())
        {
            throw new NotFoundHttpException("Series not found");
        }

        $entity = Series::findOrFail($id);
        $entity->delete();
        return response()->json(["message"=>"Series was successfully deleted"], 200);
    }

    public function getEntry($seriesId = null, $id = null) {
        if(!$id && $seriesId) {
            $data = Entry::query()->where('series_id', '=', $seriesId)->get();
        } elseif (!$seriesId && $id) {
            $data = Entry::query()->where('id', '=', $id)->get();
        } else {
            $data = Entry::query()->where([['series_id', '=', $seriesId], ['id', '=', $id]])->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Entry not found");
        }

        $carClassesService = new CarClassService();
        $carService = new CarService();
        $userService = new UserService();

        $this->getResponse = [];

        foreach ($data as $entity) {

            $this->getResponse[] = [
                'id' => $entity->id,
                'team' => $entity->team,
                'image' => $entity->image,
                'number' => $entity->number,
                'points' => $entity->points,
                'carClass' => $carClassesService->getShort($entity->car_class_id),
                'car' => $carService->getShort($entity->car_id),
                'drivers' => $userService->getDriverByEntry($entity->id),
                'state' => $entity->state
            ];
        }
        $response = [];

        if (count($this->getResponse) == 1) {
            return $this->getResponse[0];
        }

        $counter = 0;
        foreach ($this->getResponse as $entry) {
            $entryObj = [
                'id' => $entry['id'],
                'team' => $entry['team'],
                'image' => $entry['image'],
                'number' => $entry['number'],
                'points' => $entry['points'],
            ];

            $entryObj['carClass'] = $entry['carClass'][$counter];
            $entryObj['car'] = $entry['car'][$counter];
            $entryObj['drivers'] = $entry['drivers'];
            $entryObj['state'] = $entry['state'];
            $response[] = $entryObj;
            $counter++;
        }
        return $response;
    }

    public function getStandings($id) {
        $standings = [
            'standings' => $this->getStandingsBody($id)
        ];
        return $standings;
    }

    public function getStandingsBody($id) {
        $data = CarClass::query()->where('series_id', '=', $id)->get();

        foreach ($data as $entity) {
            $standingsBody[] = [
                'name' => $entity->name,
                'carClassId' => $entity->id,
                'color' => $entity->color,
                'events' => $this->getEvents($id, short: true),
                'rows' => $this->getRow($id, $entity->id),
            ];
        }

        return $standingsBody;
    }

    public function getRow($seriesId, $classId = null) {
        if (!$classId) {
            $data = Entry::query()->where('series_id', '=', $seriesId)->orderBy('points', 'desc')->get();
        } else {
            $data = Entry::query()->where([['series_id', '=', $seriesId], ['car_class_id', '=', $classId]])->orderBy('points', 'desc')->get();
        }

        if ($data->isEmpty()) {
            throw new NotFoundHttpException("Entry not found");
        }

        foreach ($data as $entity) {
            $carClassesService = new CarClassService();
            $carService = new CarService();
            $userService = new UserService();
            $eventService = new EventService();

            $row[] = [
                'team' => $entity->team,
                'drivers' => $userService->getDriverByEntry($entity->id, columnId: false),
                'carClass' => $carClassesService->getShort($entity->car_class_id),
                'car' => $carService->getShort($entity->car_id),
                'eventPoints' => $eventService->getEventPoints(24, $entity->id, $seriesId),
                //'eventPoints' => $this->getPoints($seriesId, $entity->id),
                'points' => $entity->points
            ];
        }
        return $row;
    }

    public function getPoints($seriesId, $entryId) {
        $events = Event::query()->where('series_id', '=', $seriesId)->get();

        $response = [];
        foreach ($events as $entity) {
            $eventService = new EventService();
            $response = [
                $eventService->getEventPoints($entity->id, $entryId, $seriesId)
            ];
        }
        return $response;
    }

    public function getEvents($seriesId, $id = null, $short = null) {
        if(!$id) {
            $data = Event::query()->where('series_id', '=', $seriesId)->orderBy('id')->get();
        } else {
            $data = Event::query()->where([['series_id', '=', $seriesId], ['id', '=', $id]])->orderBy('id')->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Entry not found");
        }

        foreach ($data as $entity) {
            $eventService = new EventService();

            if ($short) {
                $getResponse[] = $eventService->getShort($entity->id);
            } else {
                $getResponse[] = $eventService->get($entity->id);
            }
        }
        return $getResponse;
    }

    public function approveEntry($seriesId, $id) {
        $data = Entry::query()->where([['series_id', '=', $seriesId], ['id', '=', $id]])->get();

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Entry not found");
        }

        foreach ($data as $entity) {
            $entity->state = EntryStateEnum::APPROVED;
            $entity->save();
        }

        return response()->json(["track"=>$data,
            "message"=>"Entry has been approved"], 200);
    }

    public function updateEntry(Request $request, $seriesId, $id) {
        $data = Entry::findOrFail($id);

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Entry not found");
        }

        $rules = $this->rules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $data->car_class_id = $request->input('carClass');
        $data->car_id = $request->input('car');
        $data->team = $request->input('team');
        $data->number = $request->input('number');
        $data->state = $request->input('state');
        //drivers nieco

        return response()->json(["scoring"=>$request,
            "message"=>"Entry has been successfully updated"], 200);
    }

    public function rules(){
        return [
            //'name' => 'required|string|max:255',
        ];
    }
}
