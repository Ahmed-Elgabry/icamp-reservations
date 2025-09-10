<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\ICityRepository;
use App\Requests\dashboard\CreateUpdateCityRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class CityController extends Controller
{
    private $cityRepository;

    public function __construct(ICityRepository $cityRepository){

        $this->cityRepository = $cityRepository;
       
    }


    public function index()
    {
        $cities = $this->cityRepository->getAll();
        return view('dashboard.cities.index' , compact('cities'));
    }

    public function create()
    {
        return view('dashboard.cities.create');
    }

    public function store(CreateUpdateCityRequest $request)
    {
        $this->cityRepository->create($request->all());
        return response()->json();
    }


    public function edit($id)
    {
        $city = $this->cityRepository->findOne($id);
        return view('dashboard.cities.edit' , compact('city'));
    }

    public function update(CreateUpdateCityRequest $request , $id)
    {
        $this->cityRepository->update($request->validated() , $id);
        return response()->json();
    }


    public function destroy($id)
    {
        $this->cityRepository->forceDelete($id);
        return response()->json();

    }

     
    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);
    
        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if ($this->cityRepository->deleteForceWhereIn('id', $ids)) {
          return response()->json('success');
        } else {
          return response()->json('failed');
        }
    }

}