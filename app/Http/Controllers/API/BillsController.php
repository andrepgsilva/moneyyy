<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\API\Bills\Bill;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BillRepositoryInterface;

class BillsController extends Controller
{
    protected $billRepository;

    public function __construct(BillRepositoryInterface $billRepository)
    {
        $this->billRepository = $billRepository; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->billRepository->index()->paginate(20);
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
            'description' => 'max:255',
            'value' => 'required|integer|gt:0',
            'issue_date' => 'required|date',
        ]);

        $this->billRepository->store($formValidated);

        return response()->json(['message' => 'Bill successfully created'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = $this->billRepository->show($id);

        $this->authorize('view', $bill);

        return response()->json($bill);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $bill = $this->billRepository->show($id);

        $formValidated = $request->validate([
            'name' => 'required||max:255|sometimes',
            'description' => 'required|max:255|sometimes',
            'value' => 'required|integer|gt:0|sometimes',
            'issue_date' => 'required|date|sometimes'
        ]);

        $this->authorize('view', $bill);

        /**
        * @var Bill $bill 
        */
        $this->billRepository->update($bill, $formValidated);

        return response()->json(['message' => 'Bill updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bill = $this->billRepository->show($id);

        $this->authorize('view', $bill);

        try {
            /**
            * @var Bill $bill 
            */
            $this->billRepository->destroy($bill);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }

        return response()->json(['message' => 'Bill deleted successfully'], 201);
    }
}
