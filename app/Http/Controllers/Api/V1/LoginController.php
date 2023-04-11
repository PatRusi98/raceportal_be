<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $service;

    public function __construct(LoginService $service){ $this->service = $service; }

    public function login(Request $request) { return $this->service->login($request); }

    public function refreshToken() { return $this->service->refreshToken(); }

    public function signUp(Request $request) { return $this->service->signUp($request); }

    public function logout() { return $this->service->logout(); }
}
