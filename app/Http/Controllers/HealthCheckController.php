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
        $request->validate([

        ]);
        $uuid = $request->header('X-Owner');
        if (!$uuid || !is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
            return response("Missing X-Owner header", Response::HTTP_BAD_REQUEST);
        }

        Artisan::call(RunHealthChecksCommand::class);

        $response = [
            "db" => (new DatabaseCheck())->run()->status->value === Status::ok()->value,
            "cache" => (new RedisCheck())->run()->status->value === Status::ok()->value
        ];
        $status = $response["db"] && $response["cache"] ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR;

        return response(json_encode($response, JSON_THROW_ON_ERROR), $status);
    }
}
