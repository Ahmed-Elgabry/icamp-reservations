<?php

namespace App\Traits;

trait ApiResponseTrait {
    public $paginateNumber = 10;

    public function ApiResponse($data = null, $message = null, $code = 200) {
        $array =
            [
            'data'    => $data,
            'message' => $message,
            'status'  => in_array($code, $this->successCode()) ? true : false,
        ];

        return response()->json($array, $code);
    }

    public function successCode() {
        return [
            200, 201, 202,
        ];
    }

    public function unauthenticatedReturn() {

        return $this->ApiResponse(null, __('auth.unauthenticated'), 400);
    }

    public function unauthorizedReturn($otherData) {
        return $this->ApiResponse($otherData, __('auth.not_authorized'), 400);
    }

    public function blockedReturn($user) {
        $user->logout();
        return $this->ApiResponse(null, __('auth.blocked'), 400);
    }

    public function phoneActivationReturn($user) {

        $data = $user->sendVerificationCode();

        return $this->ApiResponse(null, __('auth.not_active'), 400);
    }

    public function NotApprovedReturn($user) {

        return $this->ApiResponse(null, __('auth.not_approved'), 400);
    }

    public function failMessage($msg) {

        return $this->ApiResponse(null, $msg, 400);
    }

}
