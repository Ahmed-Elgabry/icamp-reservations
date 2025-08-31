<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\IBannerRepository;
use App\Requests\dashboard\CreateUpdateBannerRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    private $bannerRepository;

    public function __construct(IBannerRepository $bannerRepository){

        $this->bannerRepository = $bannerRepository;
       
    }

    public function index()
    {
        $banners = $this->bannerRepository->getAll();
        return view('dashboard.banners.index' , compact('banners'));
    }

    public function create()
    {
        return view('dashboard.banners.create');
    }

    public function store(CreateUpdateBannerRequest $request)
    {
        $this->bannerRepository->create($request->all());
        return response()->json();
    }


    public function edit($id)
    {
        $banner = $this->bannerRepository->findOne($id);
        return view('dashboard.banners.edit' , compact('banner'));
    }

    public function update(CreateUpdateBannerRequest $request , $id)
    {
        $this->bannerRepository->update($request->validated() , $id);
        return response()->json();
    }


    public function destroy($id)
    {
        $this->bannerRepository->forceDelete($id);
        return response()->json();

    }

    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);
    
        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if ($this->bannerRepository->deleteForceWhereIn('id', $ids)) {
          return response()->json('success');
        } else {
          return response()->json('failed');
        }
    }

}