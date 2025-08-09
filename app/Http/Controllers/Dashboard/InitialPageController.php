<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\IInitialPageRepository;
use App\Requests\dashboard\CreateUpdateInitialPageRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class InitialPageController extends Controller
{
    private $InitialPageRepository;

    public function __construct(IInitialPageRepository $pageRepository){

        $this->pageRepository = $pageRepository;
       
    }

    public function index()
    {
        $pages = $this->pageRepository->getAll();
        return view('dashboard.initial-pages.index' , compact('pages'));
    }

    public function create()
    {
        return view('dashboard.initial-pages.create');
    }

    public function store(CreateUpdateInitialPageRequest $request)
    {
        $this->pageRepository->create($request->all());
        return response()->json();
    }


    public function edit($id)
    {
        $page = $this->pageRepository->findOne($id);
        return view('dashboard.initial-pages.edit' , compact('page'));
    }

    public function update(CreateUpdateInitialPageRequest $request , $id)
    {
        $this->pageRepository->update($request->validated() , $id);
        return response()->json();
    }


    public function destroy($id)
    {
        $this->pageRepository->forceDelete($id);
        return response()->json();

    }


    
    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);
    
        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if ($this->pageRepository->deleteForceWhereIn('id', $ids)) {
          return response()->json('success');
        } else {
          return response()->json('failed');
        }
    }

}