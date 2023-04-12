<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SeriesService;
use Illuminate\Http\File;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    protected $service;

    public function __construct(SeriesService $service){ $this->service = $service; }

    public function getAll() { return $this->service->getAll(); }

    public function get($id) { return $this->service->get($id); }

    public function getAllActive() { return $this->service->getAll(true); }

    public function getAllEvents($id) { return $this->service->getEvents($id); }

    public function store(Request $request) { return $this->service->store($request); }

    public function update(Request $request, $id) { return $this->service->update($request, $id); }

    public function delete($id) { return $this->service->delete($id); }

    public function uploadImage(Request $request, $id) { return $this->service->uploadImage($request ,$id); }

    public function getAllEntries($id) { return $this->service->getAllEntries(seriesId: $id); }

    public function getAllEntriesInCsv($id) { return $this->service->get($id); }

    public function getEntryList($id) { return $this->service->get($id); }

    public function getEntry($seriesId, $id) { return $this->service->getEntry(seriesId: $seriesId, id: $id); }

    public function registerEntry(Request $request ,$seriesId) { return $this->service->registerEntry($request, $seriesId); }

    public function editEntry(Request $request, $seriesId, $id) { return $this->service->updateEntry($request, $seriesId, $id); }

    public function approveEntry($seriesId, $id) { return $this->service->approveEntry($seriesId, $id); }

    public function uploadImageForEntry(Request $request, $id) { return $this->service->uploadEntryImage($request ,$id); }

    public function getStandings($id) { return $this->service->getStandings($id); }
}
