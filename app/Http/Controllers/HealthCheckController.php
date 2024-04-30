<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\Enums\Status;
use Spatie\Health\ResultStores\ResultStore;

class HealthCheckController extends Controller
{
    public function __invoke(Request $request, ResultStore $resultStore): Response
    {
        Artisan::call(RunHealthChecksCommand::class);

        $response = [
            "db" => (new DatabaseCheck())->run()->status->value === Status::ok()->value,
            "cache" => (new RedisCheck())->run()->status->value === Status::ok()->value
        ];
        $status = $response["db"] && $response["cache"] ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;

        return response(json_encode($response, JSON_THROW_ON_ERROR), $status);
    }
}
