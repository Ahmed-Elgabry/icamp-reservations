<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Requests\Api\AssignOrderRequest;
use App\Traits\ApiResponseTrait;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;


class DashboardController extends Controller {

    use ApiResponseTrait,  UploadTrait;

  
}