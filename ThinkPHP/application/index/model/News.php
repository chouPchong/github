<?php

namespace app\index\model;

use think\Model;

class News extends Model
{
	/**
	 * 通过新闻类别id获取新闻内容
	 */
	public function getNewsByCateid($cateid, $limit=10)
	{
		return $this->where('category_id','=',$cateid)->where('is_checked','=',1)->where('is_deleted','=',1)->limit(0, $limit)->select();

	}

	/**
	 * 通过新闻类别id获取新闻内容并分页
	 */
	public function getPageNewsByCateid($cateid,$page=5)
	{
		return $this->where('is_checked','=',1)->where('is_deleted','=',1)->where('category_id','in',$cateid)->order('addtime', 'desc')->paginate($page);

	}

	/**
	 * 获取24小时排行榜
	 */
	public function get24hoursNews()
	{
		return $this->where('is_checked','=',1)->where('is_deleted','=',1)->order('addtime','desc')->limit(0,10)->select();
	}

	/**
	 * 新闻点击量的增加
	 */
	public function addReadtimes($id)
	{
		return $this->where('id', '=', $id)->setInc('readtimes');
	}

	/**
	 * 获取首页推荐新闻
	 */
	public function  getRecommendNews()
	{
		return $this->where('recommend','=','1')->select();
	}

	/**
	 * 根据[阅读量]字段获取新闻排行
	 */
	public function getNewsByField($field='readtimes')
	{
		return $this->order($field,'desc')->limit(0,10)->select();
	}

	/**
	 * 搜索关键字获取新闻
	 */
	public function getNewsBySearch($keywords)
	{
		$config['query'] = ['keywords' =>$keywords];
		return $this->where('subject','like','%'.$keywords.'%')->whereor('content','like','%'.$keywords.'%')->paginate(3,false, $config);
	}

	/**
	 * 根据新闻id获取评论
	 */
	public function getCommentByNew()
	{
		return $this->hasMany(\app\index\model\Comment::class, 'news_id', 'id');
	}
}
