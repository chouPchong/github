<?php

namespace app\admin\model;

use think\Model;

class Partner extends Model
{
    public function news() {
    	return $this->hasMany(\app\admin\model\News::class,'partner_id','id');
    }
}
