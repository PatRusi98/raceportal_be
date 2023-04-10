<?php

namespace App\Services;

class LoginService
{
    public function getMessage(string $name): string
    {
        return "Hello, $name!";
    }
}
