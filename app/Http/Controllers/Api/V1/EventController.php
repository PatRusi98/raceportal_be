<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $service;

    public function __construct(EventService $service){ $this->service = $service; }

    public function get($id) { return $this->service->get($id); }

    public function create(Request $request) { return $this->service->store($request); }

    public function update(Request $request, $id) { return $this->service->store($request, $id); }

    public function delete($id) { return $this->service->delete($id); }

    public function getUpcoming() { return $this->service->get(); }

    public function uploadImage($id) { return $this->service->get($id); }

    public function uploadResult($id) { return $this->service->get($id); }

    public function getResult($id) { return $this->service->getResult($id); }

    public function deleteSession($id, $idSession) { return $this->service->get($id); }

    public function addPenalty($id, $idSession, $resultId) { return $this->service->get($id); }
}