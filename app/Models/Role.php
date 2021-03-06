<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    /**
     * Generated
     */

    protected $table = 'roles';
    protected $fillable = ['id', 'role'];


    public function user()
    {
        return $this->belongsToMany('App\User', 'role_user');
    }

}
