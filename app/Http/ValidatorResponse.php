<?php

namespace App\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Error",
 *     title="ResponseError",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Not found"),
 *     @OA\Property(property="data", type="array", @OA\Items(type="string"), example={}),
 *     @OA\Property(property="code", type="integer", example=404),
 * )
 */

class ValidatorResponse
{
    public $fails = false;

    public $response;

    public function check(Request $request, $rules)
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $result = "";
            $messages = json_decode(json_encode($validator->messages()), true);
            foreach ($messages as $value) {
                foreach ($value as $item) {
                    $result = $item;
                }
            }
            $this->fails = true;
            $this->response = $result;
        } else {
            $this->fails = false;
        }
    }
}
