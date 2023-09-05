<?php
namespace App\Services\Admin;

use Exception;
use App\Models\User;
use App\Models\ToolReturn;
use App\Models\ToolRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function getAllUsers()
    {
        return User::where('role', 'engineer')->get();
    }

    /*public function getUserAsJsonForDatatable(Request $request)
    {
        $pagination = $this->getPaginationInformationFromRequest($request);
        //$status = $request->datatable['query']['statusFiler'] ?? null;

        $userData = User::select('id', 'name', 'email', 'phone')
            ->where('id', '!=', 1)
            ->orderBy('id', 'ASC')

        ->paginate($pagination['perPage'], ['*'], 'datatable[pagination][page]', $pagination['page'])->toArray();

        return array_merge($userData, ['meta' => $this->makeMetaFromPaginationForDatatable($userData)]);
    }*/
    
    public function getUserAsJsonForDatatable($request, $role='engineer')
    {   
        $name = $request['search']['value']??'';
        $sortBy = $request['sort_by']??'';
        $orderBy = $request['order_by']??'';
        $length = $request['length']??10;

        return User::select('id','name','email','phone','employee_id', 'created_at')
                ->where('role', $role)
                ->where('id', '!=', Auth::user()->id)
                ->when($name, function($query) use ($name){
                    return $query->where('name', 'LIKE',"%{$name}%");
                })
                ->orderBy($sortBy, $orderBy)
                ->paginate($length)->toArray();
    }

    public function saveNewUser($request)
    {
        try {
            $user = new User();

            $user->name = $request->userName;
            $user->email   = $request->userEmail;
            $user->employee_id = $request->userEmpNo; 
            $user->password = Hash::make($request->userPassword);
            $user->phone = $request->userMobile;
            $user->role = $request->role;

            $user->save();
           
            return response()->json([
                'success' => true,
                'message' => __('messages.user.created'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getUser($user)
    {
       try{
            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function updateUser($request)
    {
        try {
            $user = User::findOrFail($request->userId);

            $user->name = $request->userName;
            $user->email   = $request->userEmail;
            $user->phone = $request->userMobile;
            
            if($request->userPassword){
                $user->password = Hash::make($request->userPassword);
            }
            $user->save();
            return response()->json([
                'success' => true,
                'message' => __('messages.user.updated'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function deleteUser($request)
    {
        try {
            $user  = User::where('id',$request['user_id'])->delete();
            
            return response()->json([
                'success' => true,
                'message' => __('messages.user.deleted'),
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

}