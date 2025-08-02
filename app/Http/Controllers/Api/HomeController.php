<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BannerResource;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\InitialPageResource;
use App\Http\Resources\Api\PackageResource;
use App\Http\Resources\Api\UserResource;
use App\Models\Contact;
use App\Models\InitialPage;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\IBannerRepository;
use App\Repositories\ICategoryRepository;
use App\Repositories\IPackageRepository;
use App\Repositories\IUserRepository;
use App\Requests\Api\ContactRequest;
use App\Requests\Api\Orders\searchProviderRequest;
use App\Traits\ApiResponseTrait;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {

    use ApiResponseTrait,  UploadTrait;

    public function __construct(IBannerRepository $Ibanner , 
                                ICategoryRepository $Icategory,
                                IUserRepository $Iuser)
    {
        $this->Ibanner   = $Ibanner;
        $this->Icategory = $Icategory;
        $this->Iuser     = $Iuser;
        $this->data = [];
    }


    public function index()
    {
        $this->data['banners']    = BannerResource::collection($this->Ibanner->getAllActive());
        if(auth()->user() && auth()->user()->categories)
        {
            $this->data['categories'] =  CategoryResource::collection($this->Icategory->getWhereIn('id' ,auth()->user()->categories->pluck('category_id')->toArray()));
        }else{

            $this->data['categories'] =  CategoryResource::collection($this->Icategory->getAllActive());
        }

        return $this->ApiResponse($this->data , __('apis.data_fetched'), 200);
    }

    public function terms()
    {
       
        $this->data['terms'] = Setting::where(['key' => 'terms_'.App::getLocale()])->first()->value;
        return $this->ApiResponse($this->data , __('apis.data_fetched'), 200);

    }

    public function about()
    {
        $this->data['about'] = Setting::where(['key' => 'about_'.App::getLocale()])->first()->value;
        return $this->ApiResponse($this->data , __('apis.data_fetched'), 200);
    }

    public function policy()
    {
        $this->data['policy'] = Setting::where(['key' => 'policy_'.App::getLocale()])->first()->value;
        return $this->ApiResponse($this->data , __('apis.data_fetched'), 200);
    }

    public function sendContact(ContactRequest $request)
    {
        Contact::create($request->validated() + ['user_id' => auth()->user()->id ?? null]);

        return $this->ApiResponse($this->data , __('apis.contact_message_sent'), 200);

    }

    public function categories()
    {
        $this->data['categories'] =  CategoryResource::collection($this->Icategory->getAllActive());

        return $this->ApiResponse($this->data , __('apis.data_fetched'), 200);
    }


    public function initialPages()
    {
        $this->data['pages'] = InitialPageResource::collection(InitialPage::orderBy('order' , 'DESC')->get());
        return $this->ApiResponse($this->data , __('apis.data_fetched'), 200);
    }


    public function providersByCategory($categoryId)
    {
        $providers = $this->Iuser->whereHasWith(['categories','certificates'], 'categories' ,
                                               ['category_id' => $categoryId] , ['user_type' => 3 , 'is_active' => 1]);

        return $this->ApiResponse(UserResource::collection($providers), __('apis.data_fetched'), 200);

    }

    public function search(searchProviderRequest $request)
    {
        $providers = User::query();
        if($request->category_id){

            $providers  = $providers->providers()->whereHas('categories' , function($q) use ($request)
            {
                $q->where('category_id' , $request->category_id);
            });
        }
        if($request->word)
        {
            $providers  = $providers->providers()->where('first_name' ,  'like' , '%' . $request->word . '%');
        }
        if($request->rate)
        {
            switch($request->rate){
                case 'highest':
                    $providers  =   $providers->providers()->whereHas('rates' ,function($q)
                    {
                        $q->orderBy('rate' , 'ASC');
                    });
                break;
                case 'lowest':
                    $providers  =   $providers->providers()->whereHas('rates' ,function($q)
                    {
                        $q->orderBy('rate' , 'DESC');
                    });
                break;
            };
        }

        $this->data = UserResource::collection($providers->get());
        return $this->ApiResponse($this->data , __('apis.data_fetched'), 200);



    }
    
    public function providerById($providerId)
    {
        $provider = $this->Iuser->findWhereWith(['id' => $providerId , 'user_type' => 3 , 'is_active' => 1] ,['categories','certificates']);

        if($provider)
        return $this->ApiResponse(new UserResource($provider), __('apis.data_fetched'), 200);
        
        return $this->ApiResponse($this->data, __('apis.data_fetched'), 404);
    }





}