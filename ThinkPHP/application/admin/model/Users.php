<?php

namespace app\admin\model;

use think\Model;

class Users extends Model
{
    public function comment() {
    	return $this->hasMany(\app\admin\model\Comment::class,'user_id','id');
    }
}
