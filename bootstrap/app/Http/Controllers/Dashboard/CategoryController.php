<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\ICategoryRepository;
use App\Requests\dashboard\CreateUpdateCategoryRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    private $catRepository;

    public function __construct(ICategoryRepository $catRepository){

        $this->catRepository = $catRepository;
       
    }


    public function index()
    {
        $categories = $this->catRepository->getAll();
        return view('dashboard.categories.index' , compact('categories'));
    }

    public function create()
    {
        return view('dashboard.categories.create');
    }

    public function store(CreateUpdateCategoryRequest $request)
    {
        $this->catRepository->create($request->all());
        return response()->json();
    }


    public function edit($id)
    {
        $category = $this->catRepository->findOne($id);
        return view('dashboard.categories.edit' , compact('category'));
    }

    public function update(CreateUpdateCategoryRequest $request , $id)
    {
        $this->catRepository->update($request->validated() , $id);
        return response()->json();
    }


    public function destroy($id)
    {
        $this->catRepository->forceDelete($id);
        return response()->json();

    }

    
    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);
    
        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if ($this->catRepository->deleteForceWhereIn('id', $ids)) {
          return response()->json('success');
        } else {
          return response()->json('failed');
        }
    }

}