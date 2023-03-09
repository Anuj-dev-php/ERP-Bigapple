<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use InfyOm\Generator\Utils\ResponseUtil;
use App\Http\Controllers\Controller as LaravelController;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 *
 * @OA\Info(title="Monsoon App", version="0.1")
 *
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($result, $message, $code = 200, $extra = [])
    {
        return Response::json(array_merge(ResponseUtil::makeResponse($message, $result), $extra, ['status' => $code]), $code);
    }

    public function sendResponseArray($result, $message, $extra = [])
    {
        $result = ($result ? ($result == "[]" ? [] : $result) : json_decode("{}"));
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 201)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

    public function getLangMessages($path, $name)
    {
        if ($path && $name) {
            return \Lang::get($path, ['name' => $name]);
        } else {
            return "Success.";
        }
    }
}
