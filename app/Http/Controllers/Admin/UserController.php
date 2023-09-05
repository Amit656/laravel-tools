<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserService;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\MemberDeleteRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helper\Helper;
use Response;

class UserController extends Controller
{
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index($role)
    {       
        
        return view('admin.pages.users', compact('role'));

    }

    /**
     * Function will return all created users for index list
     *
     * @param Request $request
     * @return void
     */
    function list(Request $request, $role) {
        Helper::handleDataTableQuery($request, 'users.id');
        $userRequest = $request->all();
        $uersData = $this->userService->getUserAsJsonForDatatable($userRequest, $role);
        return Response::dataTableJson($uersData, $request->draw);
    }

    /**
     * Function will create user
     *
     * @param CreateUserRequest $request
     * @return void
     */
    public function store(CreateUserRequest $request)
    {  
        return $this->userService->saveNewuser($request);
    }

    /**
     * Get data for edit view
     *
     * @return void
     */
    public function getDetail(User $user)
    {
        return $this->userService->getUser($user);
    }

    /**
     * Function will update the requested user
     *
     * @param UpdateUserRequest $request
     *
     * @return void
     */
    public function update(UpdateUserRequest $request)
    {
        return $this->userService->updateUser($request);
    }

    /**
     * Function will detete request user
     *
     *
     * @return void
     */
    public function delete(MemberDeleteRequest $request)
    {   
        return $this->userService->deleteUser($request->validated());
    }

}
