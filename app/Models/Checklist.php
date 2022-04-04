<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Checklist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'checklist';
    protected $guarded = [];

    public function full_name($user_id = null)
    {   
        $full_name = '';
        if(!empty($user_id)) {
            $user_info = \App\Models\User::where('id', $user_id)->select('full_name')->first();
            $full_name = $user_info['full_name'];
        } 
        return $full_name;
    }
}