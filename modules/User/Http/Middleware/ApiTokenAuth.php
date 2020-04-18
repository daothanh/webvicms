<?php

namespace Modules\User\Http\Middleware;

use Modules\User\Repositories\UserTokenRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiTokenAuth
{
    protected $inputKey;

    public function __construct()
    {
        $this->inputKey = 'access_token';
    }

    public function handle(Request $request, \Closure $next)
    {
        if ($this->isValidToken($this->getTokenForRequest($request)) === false) {
            return new Response('Forbidden', 403);
        }

        return $next($request);
    }

    private function isValidToken($token)
    {
        return \Auth::guard('api')->check();
    }

    public function getTokenForRequest($request)
    {
        $token = $request->query($this->inputKey);
        if (empty($token)) {
            $token = $request->input($this->inputKey);
        }
        if (empty($token)) {
            $token = $request->bearerToken();
        }
        return $token;
    }
}
