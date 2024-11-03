<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Illuminate\Session\Middleware\StartSession;

class ZoneSession extends StartSession
{
    public function getSession($request)
    {
        return tap($this->manager->driver(), function ($session) {
            $session->setId($this->generateSessionId());
            $session->setName('zone_session_' . request()->segment(2)); // Uses the zone path segment
        });
    }

    protected function generateSessionId()
    {
        return 'zone_' . request()->segment(2) . '_' . Str::random(40);
    }
} 