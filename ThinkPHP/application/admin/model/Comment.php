<?php

namespace app\admin\model;

use think\Model;

class Comment extends Model
{
    public function users(){
    	return $this->hasOne(\app\admin\model\Users::class,'id','user_id');
    }
	
	public function news(){
		return $this->hasOne(\app\admin\model\News::class,'id','news_id');
	}
}
