<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RentBookResource;
use App\Http\ValidatorResponse;
use App\Models\RentBook;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function check(Request $request)
    {
        $rules = array (
            'student_id' => 'required',
            'book_id' => 'required',
        );
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if($validator->fails){
            return response()->json([
                'message' => $validator->response,
                'code' => 400
            ]);
        }
        $rent_book = new RentBook();
        $rent_book->student_id = $request->student_id;
        $rent_book->book_id = $request->book_id;
        $rent_book->save();
        return response()->json([
            'data' => new RentBookResource($rent_book),
            'code' => 200
        ]);
    }
}
