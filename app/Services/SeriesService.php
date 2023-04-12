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
use phpDocumentor\Reflection\Types\ClassString;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function Composer\Autoload\includeFile;

class SeriesService
{
    private $getResponse;

    public function get($id)
    {
        $data = Series::query()->where('id', '=', $id)->get();

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Series not found");
        }

        foreach ($data as $entity) {
            $carClassesService = new CarClassService();
            $classes = $carClassesService->get($entity['id']);

            $this->getResponse = ([
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
                'classes' => $classes
            ]);
        }
        return $this->getResponse;
    }

    public function getAll($active = null) {
        if (!$active) {
            $data = Series::query()->orderBy('id', 'desc')->get();
        } else {
            $data = Series::query()->where('state', '=', 'ACTIVE')->orderBy('id', 'desc')->get();
        }

        if($data->isEmpty())
        {
            throw new NotFoundHttpException("Series not found");
        }

        $this->getResponse = [];

        foreach ($data as $entity) {
            $carClassesService = new CarClassService();
            $classes = $carClassesService->get($entity['id']);

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
                'classes' => $classes
            ];
        }
        return $this->getResponse;
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

    public function getAllShort($id = null) {
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
            $this->getResponse = ([
                'id' => $entity->id,
                'name' => $entity->name,
                'code' => $entity->code,
                'color' => $entity->color,
                'simulator' => $entity->simulator
            ]);
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
        $entity->multiclass = false;
        $entity->name = "";
        $entity->registrations = false;
        $entity->rules = "";
        $entity->state = SeriesStateEnum::PREPARING->value;
        $entity->teams_enable = false;


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

        if ($request->input('registrations') == "true") {
            $entity->registrations = true;
        } else {
            $entity->registrations = false;
        }

        if ($request->input('teamsEnable') == "true") {
            $entity->teams_enable = true;
        } else {
            $entity->teams_enable = false;
        }


        if ($request->input('multiclass') == "true") {
            $entity->multiclass = true;
        } else {
            $entity->multiclass = false;
        }
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

        foreach ($data as $entity) {
            $carClassesService = new CarClassService();
            $carService = new CarService();
            $userService = new UserService();

            $carClass = $carClassesService->getShort($entity->car_class_id);
            $car = $carService->getShort($entity->car_id);
            $drivers = $userService->getDriverByEntry($entity->id);

            $this->getResponse = ([
                'id' => $entity->id,
                'team' => $entity->team,
                'image' => $entity->image,
                'number' => $entity->number,
                'points' => $entity->points,
                'carClass' => $carClass,
                'car' => $car,
                'drivers' => $drivers,
                'state' => $entity->state
            ]);
        }

        return $this->getResponse;
    }

    public function getAllEntries($seriesId = null, $id = null) {
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

        foreach ($data as $entity) {
            $carClassesService = new CarClassService();
            $carService = new CarService();
            $userService = new UserService();

            $carClass = $carClassesService->getAllShort($entity->car_class_id);
            $car = $carService->getShort($entity->car_id);
            $drivers = $userService->getDriverByEntry($entity->id);

            $this->getResponse[] = [
                'id' => $entity->id,
                'team' => $entity->team,
                'image' => $entity->image,
                'number' => $entity->number,
                'points' => $entity->points,
                'carClass' => $carClass,
                'car' => $car,
                'drivers' => $drivers,
                'state' => $entity->state
            ];
        }
        return $this->getResponse;
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

    public function registerEntry(Request $request, $seriesId) {
        $entry = new Entry();

        $entry->car_class_id = $request->input('carClass');
        $entry->car_id = $request->input('car');
        $entry->team = $request->input('team');
        $entry->number = $request->input('number');
        $entry->image = "obrazok";
        $entry->state = EntryStateEnum::WAITING;
        $entry->points = 0;
        $entry->series_id = $seriesId;
        //drivers nieco
        $entry->save();

        return response()->json($entry, 200);
    }

    public function updateEntry(Request $request, $seriesId, $id) {
        $data = Entry::findOrFail($id);

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
        if ($request->input('state')) {
            $data->state = $request->input('state');
        }
        if ($request->input('image')) {
            $data->state = $request->input('image');
        }
        $data->series_id = $seriesId;
        $data->save();
        //drivers nieco

        return response()->json($data, 200);
    }

    public function rules(){
        return [
            //'name' => 'required|string|max:255',
        ];
    }
}
