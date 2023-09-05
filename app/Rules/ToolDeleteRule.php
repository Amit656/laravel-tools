<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ToolRequest;

class ToolDeleteRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $toolId)
    {   
        if(ToolRequest::where('tool_id', $toolId)->whereNotIn('status', ['returned', 'rejected'])->exists()){
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
        return __('messages.tool.notDeleted');
    }
}
