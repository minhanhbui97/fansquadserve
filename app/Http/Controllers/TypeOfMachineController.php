<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTypeOfMachineRequest;
use App\Http\Requests\UpdateTypeOfMachineRequest;
use App\Models\TypeOfMachine;

class TypeOfMachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type_of_machines = TypeOfMachine::all();
        return $type_of_machines;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeOfMachineRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeOfMachine $typeOfMachine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeOfMachine $typeOfMachine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeOfMachineRequest $request, TypeOfMachine $typeOfMachine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeOfMachine $typeOfMachine)
    {
        //
    }
}
