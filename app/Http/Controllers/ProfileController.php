<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileImageRequest;

class ProfileController extends Controller
{
    public $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index()
    {
        if ( \Auth::user()->role == 'engineer' ) {// do your magic here
            return view('engineer.pages.profile');
        }
        return view('admin.pages.profile');
    }

    /**
     * Get data for edit view
     *
     * @return void
     */
    public function getDetail()
    {
        $userId = \Auth::user()->id;
        return $this->profileService->getUserDetail($userId);
        
    }

    /**
     * Function will update the requested user
     *
     * @param UpdateProfileRequest $request
     *
     * @return void
     */
    public function update(UpdateProfileRequest $request)
    {
        return $this->profileService->updateProfile($request);
    }

    /**
     * Function will update the requested password
     *
     * @param UpdatePasswordRequest $request
     *
     * @return void
     */
    public function changePassword(UpdatePasswordRequest $request)
    {
        return $this->profileService->updatePassword($request);
    }

    /**
     * Function will update the requested image
     *
     * @param UpdateProfileImageRequest $request
     *
     * @return void
     */
    public function changeProfileImage(UpdateProfileImageRequest $request)
    {
        return $this->profileService->updateProfileImage($request);
    }
    
}
