<?php

namespace App\Parsers\Sim;


use App\Models\CarClass;
use App\Models\Entry;
use App\Models\EntryDrivers;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Result;
use App\Models\ScoringQualificationScoring;
use App\Models\ScoringRaceScoring;
use App\Models\Session;
use App\Models\Users;
use App\Models\Warning;

class rFactor2
{
    public function parse($filename, $eventId) {

        try {
            $xmlString = utf8_encode(file_get_contents(public_path('public\\logs\\').$filename));
            $xmlObject = simplexml_load_string($xmlString);

            $sessionType = null;

            foreach ($xmlObject->RaceResults as $session) {
                if ($session->Race) {
                    $sessionType = "Race";
                } else if ($session->Qualify) {
                    $sessionType = "Qualify";
                } else if ($session->Qualify2) {
                    $sessionType = "Qualify2";
                } else if ($session->Qualify3) {
                    $sessionType = "Qualify3";
                } else {
                    return response("Unrecognized session type!", 400);
                }
            }

            $sessionNew = new Session();
            if ($sessionType === "Race") {
                $sessionNew->type = 0;
            } else {
                $sessionNew->type = 1;
            }
            $sessionNew->event_id = $eventId;
            $sessionNew->save();

            $sessionId = $sessionNew->id;
            $event = Event::query()->where('id', '=', $eventId)->get();
            $classes = CarClass::query()->where('series_id', '=', $event->series_id)->get();
            foreach ($classes as $class) {
                ${$class->id}[] = null;
            }

            $content = [];
            foreach ($xmlObject->RaceResults->$sessionType->Driver as $value) {
                $result = new Result();
                $result->best_lap = round((double) $value->BestLapTime * 10000);
                $result->lap_count = (int) $value->Laps;
                $result->position = (int) $value->Position;
                $result->total_time = round((double) $value->FinishTime * 10000);
                $result->total_time_with_penalties = round((double) $value->FinishTime * 10000);
                $result->session_id = $sessionId;
                $result->last_lap = "";

                $user = Users::query()->where('steam_id', '=', $value->SteamID)->get();
                $entry = Entry::query()->where([['series_id', '=', $event->series_id], ['state', '=', 'APPROVED']])->get();
                $actualEntryDriver = null;
                foreach ($entry as $item) {
                    $entryDriver = EntryDrivers::query()->where([['entry_id', '=', $item->id], ['users_id', '=', $user->id]])->get();
                    if ($entryDriver) {
                        $actualEntryDriver = $entryDriver;
                    }
                }

                $result->position_inclass = 0;
                $result->points = 0;
                $result->entry_id = $actualEntryDriver->entry_id;
                $result->save();

                $actualEntry = Entry::query()->where('id', '=', $actualEntryDriver->entry_id)->get();

                ${$actualEntry->car_class_id} = [
                    "position" => (int) $value->Position,
                    "result" => $result->id
                ];

                if (!$actualEntryDriver) {
                    $warningEntry = new Warning();
                    $warningEntry->warning_text = "Entry was not found";
                    $warningEntry->result_id = $result->id;
                    $warningEntry->save();
                }

                if (!$user) {
                    $warningUser = new Warning();
                    $warningUser->warning_text = "User was not found";
                    $warningUser->result_id = $result->id;
                    $warningUser->save();
                }

                $nameArr = preg_split('/\s+/', $value->Name);

                $participant = new Participant();
                $participant->firstname = $nameArr[0];
                $surname = "";
                for ($i = 1; $i < count($nameArr) - 1; $i++) {
                    if ($i = count($nameArr) - 1) {
                        $surname .= $nameArr[$i];
                    } else {
                        $surname .= $nameArr[$i];
                        $surname .= " ";
                    }
                }
                $participant->lastname = $surname;
                $participant->steam_id = (string) $value->SteamID;
                $participant->result_id = $result->id;
                $participant->user_id = $user->id;
                $participant->save();

                $content[] = [
                    'name' => (string) $value->Name,
                    'steamId' => (string) $value->SteamID,
                    'position' => (int) $value->Position,
                    'bestLap' => round((double) $value->BestLapTime * 10000),
                    'finishTime' => round((double) $value->FinishTime * 10000),
                    'laps' => (int) $value->Laps,
                    'finishStatus' => (string) $value->FinishStatus,
                ];
            }

            foreach ($classes as $class) {
                $sorted = array();
                while (sizeof($sorted) < sizeof(${$class->id})) {
                    $lowest = 999;
                    $lowestResult = null;
                    foreach (${$class->id} as $item) {
                        if (($item->position < $lowest) && (!in_array($item->position, $sorted))) {
                            $lowest = $item->position;
                            $lowestResult = $item->result;
                        }
                    }
                    $sorted[] = $lowest;
                    $resultToEdit = Result::findById($lowestResult);
                    $resultToEdit->position_inclass = sizeof($sorted);
                    if ($sessionNew->type == 0) {
                        $scoring = ScoringRaceScoring::query()->where('scoring_id', '=', $class->scoring_id)->get();
                        $key = "race_scoring_key";
                        $points = "race_scoring";
                    } else {
                        $scoring = ScoringQualificationScoring::query()->where('scoring_id', '=', $class->scoring_id)->get();
                        $key = "qualification_scoring_key";
                        $points = "qualification_scoring";
                    }
                    foreach ($scoring as $score) {
                        if (sizeof($sorted) == $score->{$key}) {
                            $resultToEdit->points = $score->{$points};
                            $resultToEdit->save();
                            break;
                        }
                    }
                }
            }

            return $content;

        } catch (\Exception $e) {
            return $e;
        }
    }
}
