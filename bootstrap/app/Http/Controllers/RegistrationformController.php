<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreRegistrationformRequest;
use App\Models\{ Service , Registrationform };
use Illuminate\Support\Facades\DB;

class RegistrationformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::select('id','name','hour_from','hour_to')->where('registeration_forms',1)->orderBy('name')->get();
        return view('registrationforms.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRegistrationformRequest  $request
     *
     */
    public function store(StoreRegistrationformRequest $request)
    {
        try {
            DB::beginTransaction();
            $q = Registrationform::create($request->validated());
            DB::commit();
            return redirect()->route('registrationforms.create')->with('request_code' , $q->request_code);
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error($th->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => $th->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Registrationform  $registrationform
     * @return \Illuminate\Http\Response
     */
    public function show(Registrationform $registrationform)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Registrationform  $registrationform
     * @return \Illuminate\Http\Response
     */
    public function edit(Registrationform $registrationform)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Registrationform  $registrationform
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Registrationform $registrationform)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Registrationform  $registrationform
     * @return \Illuminate\Http\Response
     */
    public function destroy(Registrationform $registrationform)
    {
        //
    }
}
