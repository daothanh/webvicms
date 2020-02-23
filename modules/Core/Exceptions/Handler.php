<?php

namespace Modules\Core\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Entities\ErrorReport;
use Modules\Core\Notifications\ErrorReportNotify;
use Modules\User\Repositories\UserRepository;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
        if (app()->environment() == 'production' && !$this->shouldntReport($exception) && ($exception instanceof FatalThrowableError || $exception instanceof \ErrorException)) {
            $key = md5($exception->getFile() . $exception->getMessage() . $exception->getLine());
            $exists = ErrorReport::query()->where('key', '=', $key)->exists();
            if (!$exists) {
                $request = app('request');
                $errorInfo = [
                    'Message: ' => $exception->getMessage(),
                    'Line: ' => $exception->getLine(),
                    'File: ' => $exception->getFile(),
                    'Request: ' => json_encode($request->all()),
                    'Session: ' => json_encode(session()->all()),
                    'Current Uri: ' => \Route::current()->uri(),
                    'Website: ' => config('app.url'),

                ];
                $report = ErrorReport::query()->create([
                    'key' => $key,
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'data' => json_encode($errorInfo),
                    'trace' => $exception->getTraceAsString()
                ]);
                if ($report) {
                    $adminUser = app(UserRepository::class)
                        ->newQueryBuilder()
                        ->whereHas('roles', function ($q) {
                            $q->where('name', 'admin');
                        })->first();

                    if ($adminUser) {
                        \Notification::send($adminUser, new ErrorReportNotify($exception, $errorInfo));
                    }
                }
            }
        }

    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);

    }

    protected function registerErrorViewPaths()
    {
        $paths = collect(config('view.paths'));

        $theme = \Theme::currentTheme();
        $errorViewPath = base_path('themes/' . ucfirst($theme) . '/views');
        $paths->put($theme, $errorViewPath);

        \View::replaceNamespace('errors', $paths->map(function ($path) {
            return "{$path}/errors";
        })->push(__DIR__.'/views')->all());
    }
}
