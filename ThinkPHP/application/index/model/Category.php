<?php

namespace app\index\model;

use think\Model;

class Category extends Model
{
	/**
	 * 通过pid获取分类信息
	 */
    public function getCategoryByPid($pid)
	{
		return $this->where('pid','=',$pid)->select();
	}

	/**
	 * 通过新闻类别获取新闻类别的id
	 */
	public function getCateidByCatename($catename)
	{
		$result = $this->where('catename','=',$catename)->find();
		return $result->id;
	}

	/**
	 * 根据导航一级分类id获取所有次级分类id
	 */
	public function getIdByPid($id)
	{
		$array =  $this->field('id')->where('pid','=',$id)->select();
		$idStr = '';
		foreach ($array as $item) {
			$idStr .= $item->id . ',';
		}

		return trim($idStr,',');

	}
}
