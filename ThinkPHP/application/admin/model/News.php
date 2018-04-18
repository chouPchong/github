<?php

namespace app\admin\model;

use think\Model;

class News extends Model
{
    public function category(){
    	return $this->hasOne(\app\admin\model\Category::class,'id','category_id');
    }
	
	public function partner() {
    	return $this->hasOne(\app\admin\model\Partner::class,'id','partner_id');
    }
	
	public function comment() {
		return $this->hasMany(\app\admin\model\Comment::class,'news_id','id');
	}
}
