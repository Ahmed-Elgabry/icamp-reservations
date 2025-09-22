<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\ICategoryRepository;
use App\Repositories\ICouponRepository;
use App\Requests\dashboard\CreateUpdateCouponRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class CouponController extends Controller
{
    private $couponRepository;

    public function __construct(ICouponRepository $couponRepository , ICategoryRepository $Icat){

        $this->couponRepository = $couponRepository;
        $this->Icat = $Icat;
       
    }


    public function index()
    {
        $coupons = $this->couponRepository->getAll();
        return view('dashboard.coupons.index' , compact('coupons'));
    }

    public function create()
    {
        $categories = $this->Icat->getAllActive();
        return view('dashboard.coupons.create' , compact('categories'));
    }

    public function store(CreateUpdateCouponRequest $request)
    {
        $attributes = $request->validated();
        if(!$request->is_percentage)
        {
            $attributes['percentage']  = 0;
        }else
        {
            $attributes['discount']  = 0;
        }
        $coupon =  $this->couponRepository->create($attributes);
        
        $coupon->categories()->attach($request->category_ids);
        return response()->json();
    }


    public function edit($id)
    {
        $coupon = $this->couponRepository->findOne($id);
        $categories = $this->Icat->getAllActive();

        return view('dashboard.coupons.edit' , compact('coupon' , 'categories'));
    }

    public function update(CreateUpdateCouponRequest $request , $id)
    {
        $attributes = $request->validated();

        if(!$request->is_percentage)
        {
            $attributes['percentage']  = 0;
        }else
        {
            $attributes['discount']  = 0;
 
        }
        $this->couponRepository->update($attributes , $id);
        $coupon = $this->couponRepository->findOne($id);

        $coupon->categories()->sync($request->category_ids);

        return response()->json();
    }


    public function destroy($id)
    {
        $this->couponRepository->forceDelete($id);
        return response()->json();

    }

    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);
    
        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if ($this->couponRepository->deleteForceWhereIn('id', $ids)) {
          return response()->json('success');
        } else {
          return response()->json('failed');
        }
    }

}