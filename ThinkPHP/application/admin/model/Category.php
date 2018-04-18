<?php

namespace app\admin\model;

use think\Model;

class Category extends Model
{
    public function category(){
    		return $this->hasMany(\app\admin\model\Category::class,'pid','id');	
    }
	public function news(){
		return $this->hasMany(\app\admin\model\News::class,'category_id','id');
	}

	/**
	 * 获取类型树
	 */
	public function getCategoryTree($pid=0)
	{
		static $target = [];
		static $n = 1;
		$cates = $this->where('state=1')->where('pid','=',$pid)->select();
		foreach ($cates as $item) {
			$item->level = $n;
			$target[] = $item->toArray();
			$n++;
			$this->getCategoryTree($item->id);
			$n--;
		}

		return $target;
	}
}
