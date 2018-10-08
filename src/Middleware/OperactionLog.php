<?php

namespace Hnndy\Operactionlog\Middleware;

use Auth;
use Hnndy\Operactionlog\Models\OperactionLog as OperactionLogModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OperactionLog
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($this->shouldLogOperation($request)) {
            $log = [
                'user_id' => Auth::user()->id,
                'path'    => substr($request->path(), 0, 255),
                'method'  => $request->method(),
                'ip'      => $request->getClientIp(),
                'input'   => json_encode($request->except('_token')),
            ];
            try {
                OperactionLogModel::create($log);
            } catch (\Exception $exception) {
                // pass
            }
        }
        return $next($request);
    }
    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function shouldLogOperation(Request $request)
    {
        return config('actionlog.operaction.enable')
            && !$this->inExceptArray($request)
            && $this->inAllowedMethods($request->method())
            && Auth::user();
    }
    /**
     * Whether requests using this method are allowed to be logged.
     *
     * @param string $method
     *
     * @return bool
     */
    protected function inAllowedMethods($method)
    {
        $allowedMethods = collect(config('actionlog.operaction.allowed_methods'))->filter();
        if ($allowedMethods->isEmpty()) {
            return true;
        }
        return $allowedMethods->map(function ($method) {
            return strtoupper($method);
        })->contains($method);
    }
    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach (config('actionlog.operaction.except') as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            $methods = [];
            if (Str::contains($except, ':')) {
                list($methods, $except) = explode(':', $except);
                $methods = explode(',', $methods);
            }
            $methods = array_map('strtoupper', $methods);
            if ($request->is($except) &&
                (empty($methods) || in_array($request->method(), $methods))) {
                return true;
            }
        }
        return false;
    }
}