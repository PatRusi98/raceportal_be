<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service){ $this->service = $service; }

    public function getAll() { return $this->service->getAll(); }

    public function get($id) { return $this->service->get($id); }

    public function update(Request $request, $id) { return $this->service->store($request, $id); }

    public function addLicense($id, $licenseId) { return $this->service->addLicense($id, $licenseId); }

    public function removeLicense($id, $licenseId) { return $this->service->addLicense($id); }

    public function uploadAvatar(Request $request, $id) { return $this->service->store($request, $id); }
}
