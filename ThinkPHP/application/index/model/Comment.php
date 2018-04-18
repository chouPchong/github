<?php

namespace app\index\model;

use think\Model;
use app\index\model\News;

class Comment extends Model
{
	/*
	 * 根据用户id,查看评论的新闻内容
	 */
	public function getNewsByComment()
	{
		return $this->hasOne('News','id','news_id');
	}

	/**
	 * 评论的user_id获取用户信息
	 */
	public function users(){
		return $this->hasOne(\app\index\model\Users::class,'id','user_id');
	}
}