<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Illuminate\Session\Middleware\StartSession;

class ZoneSession extends StartSession
{
    public function handle($request, \Closure $next)
    {
        $this->manager->setDefaultDriver($this->driver);

        $session = $this->getSession($request);

        $request->setLaravelSession($session);

        $this->collectGarbage($session);

        $response = $next($request);

        $this->storeCurrentUrl($request, $session);

        $this->addCookieToResponse($response, $session);

        return $response;
    }

    protected function getSession($request)
    {
        $session = $this->manager->driver();
        $sessionName = 'zone_session_' . request()->segment(2);
        
        $session->setName($sessionName);
        
        if (!$request->hasSession()) {
            $session->setId($this->generateSessionId());
        }
        
        $session->start();
        
        return $session;
    }

    protected function generateSessionId()
    {
        $segment = request()->segment(2) ?? 'default';
        return 'zone_' . $segment . '_' . Str::random(40);
    }

    protected function storeCurrentUrl($request, $session)
    {
        if ($request->method() === 'GET' && ! $request->ajax() && ! $request->prefetch()) {
            $session->setPreviousUrl($request->fullUrl());
        }
    }
} 