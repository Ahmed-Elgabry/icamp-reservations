<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\ICopyRepository;
use App\Requests\dashboard\CreateUpdateCopyRequest;
use Illuminate\Http\Request;

class CopyController extends Controller
{
    private $copyRepository;

    public function __construct(ICopyRepository $copyRepository){

        $this->copyRepository = $copyRepository;
       
    }


    public function index()
    {
        $copys = $this->copyRepository->getAll();
        return view('dashboard.copys.index' , compact('copys'));
    }

    public function create()
    {
        return view('dashboard.copys.create');
    }

    public function store(CreateUpdateCopyRequest $request)
    {
        $this->copyRepository->create($request->all());
        return response()->json();
    }


    public function edit($id)
    {
        $copy = $this->copyRepository->findOne($id);
        return view('dashboard.copys.edit' , compact('copy'));
    }

    public function update(CreateUpdateCopyRequest $request , $id)
    {
        $this->copyRepository->update($request->validated() , $id);
        return response()->json();
    }


    public function destroy($id)
    {
        $this->copyRepository->forceDelete($id);
        return response()->json();

    }

    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);
    
        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if ($this->copyRepository->deleteForceWhereIn('id', $ids)) {
          return response()->json('success');
        } else {
          return response()->json('failed');
        }
    }

}