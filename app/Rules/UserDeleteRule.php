<?php

namespace App\Rules;

use App\Models\ToolRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class UserDeleteRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $message;

    public function __construct()
    {
    
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $userId)
    {   
        if(ToolRequest::where('user_id', $userId)->where('status', '!=', 'approved')->count()){
            $this->message = __('messages.user.toolsDependency');
            return false;
        }
        if(Auth::user()->role == 'admin' && Auth::user()->id == $userId){
            $this->message = __('messages.user.not_deleted');
            return false;
        }

        return true;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
