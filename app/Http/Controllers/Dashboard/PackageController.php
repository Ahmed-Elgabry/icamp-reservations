<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\IPackageRepository;
use App\Requests\dashboard\CreateUpdatePackageRequest;
use Illuminate\Http\Request;
use App\Repositories\ICarRepository;
use App\Repositories\ICategoryRepository;

use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    private $packageRepository;
 
    public function __construct(IPackageRepository $packageRepository , 
                               ICategoryRepository $catRepository ,
                               ICarRepository $carRepository){

        $this->packageRepository = $packageRepository;
        $this->catRepository = $catRepository;
        $this->carRepository = $carRepository;


    }


    public function index()
    {
        $packages = $this->packageRepository->getAll();
        return view('dashboard.packages.index' , compact('packages'));
    }

    public function create()
    {
        $categories = $this->catRepository->getAll();
        $cars = $this->carRepository->getAll();
        return view('dashboard.packages.create' , compact('cars', 'categories'));
    }

    public function store(CreateUpdatePackageRequest $request)
    {
        $this->packageRepository->create($request->all());
        return response()->json();
    }


    public function edit($id)
    {
        $package = $this->packageRepository->findOne($id);
        $categories = $this->catRepository->getAll();
        $cars = $this->carRepository->getAll();
        return view('dashboard.packages.edit' , compact('package','cars', 'categories'));
    }

    public function update(CreateUpdatePackageRequest $request , $id)
    {
        $this->packageRepository->update($request->validated() , $id);
        return response()->json();
    }


    public function destroy($id)
    {
        $this->packageRepository->forceDelete($id);
        return response()->json();

    }


    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);
    
        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if ($this->packageRepository->deleteForceWhereIn('id', $ids)) {
          return response()->json('success');
        } else {
          return response()->json('failed');
        }
    }

}