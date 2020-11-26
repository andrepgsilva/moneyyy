<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\API\Bills\Bill;
use App\Http\Controllers\Controller;
use App\Repositories\BillRepository;

class BillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Bill::with(['categories:id,name,slug', 'places:id,name'])->paginate(3);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formValidated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'value' => 'required|integer|gt:0'
        ]);

        Bill::create($formValidated);

        return response()->json(['message' => 'Bill successfully created'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, BillRepository $billRepository)
    {
        try {
            $bill = $billRepository->show($id);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Bill not found'], 404);    
        }

        return response()->json($bill);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request, BillRepository $billRepository)
    {
        $formValidated = $request->validate([
            'name' => 'required||max:255|sometimes',
            'description' => 'required|max:255|sometimes',
            'value' => 'required|integer|gt:0|sometimes'
        ]);

        try {
            $bill = $billRepository->show($id);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Bill not found'], 404);
        }

        /**
        * @var Bill $bill 
        */
        $billRepository->update($bill, $formValidated);

        return response()->json(['message' => 'Bill updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, BillRepository $billRepository)
    {
        try {
            $bill = $billRepository->show($id);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Bill not found'], 404);
        }

        try {
            /**
            * @var Bill $bill 
            */
            $billRepository->destroy($bill);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }

        return response()->json(['message' => 'Bill deleted successfully'], 201);
    }
}
