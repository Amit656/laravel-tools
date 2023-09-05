<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;

class ProfileService
{   

    public function getUserDetail($userId)
    {
       try{
           $data = User::where('id', '=', $userId)->first();
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function updateProfile($request)
    {
        try {
            $user = User::findOrFail($request->userId);

            $user->name = $request->userName;
            $user->phone = $request->mobile;
            $user->email   = $request->userEmail;
            $user->save();
            return response()->json([
                'success' => true,
                'message' => __('messages.profile.updated'),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function updatePassword($request)
    {
        try {
            $user = \Auth::user();

            if (\Hash::check($request->oldPassword, $user->password)) {
                $user->update([
                    'password' => \Hash::make($request->newPassword),
                ]);
                return response()->json([
                    'success' => true,
                    'message' => __('messages.profile.updatePassword'),
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => __('messages.profile.notMatch'),
                ]);
            }
            
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function updateProfileImage($request)
    {
        try {
            $user = \Auth::user(); 
            if(!empty($user->image)){    
                $path = \Storage::disk('public')->exists($user->image); 
                if ($path) {
                    \Storage::disk('public')->delete($user->image);
                }
            }
            $storage = \Storage::disk('public')->put('/users', $request->profileImg);
            $user->image = $storage;
            $user->save();
            return response()->json([
                'success' => true,
                'message' => __('messages.profile.updated'),
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

}