<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transaction::orderBy('time', 'desc')->get();
        $response = [
            'message' => 'List data transaction order by time',
            'data' => $transaction
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $transaction = Transaction::create($request->all());
            $response = [
                'message' => 'Transaction created',
                'data' =>  $transaction
            ];

            return response()->json($response, Response::HTTP_CREATED);

        }catch(QueryException $e){ //menggunakan queryexception karena proses ini dilakukan saat query

            // QueryException adalah sebuah class yang akan meng-handle
            // jika terjadi prosess error maka tidak hanya akan menghentikan proses
            // tetapi juga akan memberi info dimana error berada dan kejadian kapan error terjadi
            return response()->json([
                'message' => "Failed ". $e->errorinfo
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::FindOrFail($id);
        // akan mencari data berdasarkan 'id'
        // jika ada akan melanjutkan ke kode selanjutnya jika tidak
        // akan mengirim response 404, dan exception handling ini sudah
        // secara otomatis di handling/atur/sediakan oleh FindOrFail - laravel

        $response = [
            'message' => 'Detail of transaction resource',
            'data' =>  $transaction
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $transaction = Transaction::FindOrFail($id);
        // akan mencari data berdasarkan 'id'
        // jika ada akan melanjutkan ke kode selanjutnya jika tidak
        // akan mengirim response 404, dan exception handling ini sudah
        // secara otomatis di handling/atur/sediakan oleh FindOrFail - laravel

        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $transaction->update($request->all());
            $response = [
                'message' => 'Transaction update',
                'data' =>  $transaction
            ];

            return response()->json($response, Response::HTTP_OK);

        }catch(QueryException $e){ //menggunakan queryexception karena proses ini dilakukan saat query

            // QueryException adalah sebuah class yang akan meng-handle
            // jika terjadi prosess error maka tidak hanya akan menghentikan proses
            // tetapi juga akan memberi info dimana error berada dan kejadian kapan error terjadi
            return response()->json([
                'message' => "Failed ". $e->errorinfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::FindOrFail($id);
        // akan mencari data berdasarkan 'id'
        // jika ada akan melanjutkan ke kode selanjutnya jika tidak
        // akan mengirim response 404, dan exception handling ini sudah
        // secara otomatis di handling/atur/sediakan oleh FindOrFail - laravel

        try{
            $transaction->delete();
            $response = [
                'message' => 'Transaction deleted',
                'data' =>  $transaction
            ];

            return response()->json($response, Response::HTTP_OK);

        }catch(QueryException $e){ //menggunakan queryexception karena proses ini dilakukan saat query

            // QueryException adalah sebuah class yang akan meng-handle
            // jika terjadi prosess error maka tidak hanya akan menghentikan proses
            // tetapi juga akan memberi info dimana error berada dan kejadian kapan error terjadi
            return response()->json([
                'message' => "Failed ". $e->errorinfo
            ]);
        }
    }
}
