<?php

namespace App\Exceptions;

use Illuminate\Encryption\MissingAppKeyException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\File;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof MissingAppKeyException) {
            try {
                $oldFilePath = base_path('.env.example');
                $newFilePath = base_path('.env');
                if (File::exists($oldFilePath)) {
                    File::copy($oldFilePath, $newFilePath);
                    $APP_KEY = 'base64:' . base64_encode(random_bytes(32));
                    $envFilePath = base_path('.env');
                    $contents = file_get_contents($envFilePath);
                    $contents = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY=' . $APP_KEY, $contents);
                    file_put_contents($envFilePath, $contents);

                    return redirect()->refresh();
                }
            } catch (\Exception $exception) {
                return parent::render($request, $exception);
            }
        }

        return parent::render($request, $e);
    }
}
