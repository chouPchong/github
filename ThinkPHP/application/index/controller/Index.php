<?php

namespace app\index\controller;

use app\index\model\Category;
use app\index\model\News;

class index extends Base
{
	//前台搜索默认关键字
	public $keywords = '叙利亚库尔德武装';

	/**
	 * 共用数据
	 */
	public function _init()
	{
		$this->cateModel = new Category();
		$this->newsModel = new News();

		//获取24小时新闻排行
		$hourNews = $this->newsModel->get24hoursNews();
		$keywords = $this->keywords;
		$this->assign([
			'hourNews' => $hourNews,
			'keywords' => $keywords
			]);
	}

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
		//获取展示分类的新闻
		$cateid = $this->cateModel->getCateidByCatename('内地新闻');
    	$inlandNews = $this->newsModel->getNewsByCateid($cateid);
		$cateid = $this->cateModel->getCateidByCatename('港澳台新闻');
		$gatNews = $this->newsModel->getNewsByCateid($cateid);
		$cateid = $this->cateModel->getCateidByCatename('最新消息');
		$newest = $this->newsModel->getNewsByCateid($cateid);
		$cateid = $this->cateModel->getCateidByCatename('环球视野');
		$globalNews = $this->newsModel->getNewsByCateid($cateid);
		$cateid = $this->cateModel->getCateidByCatename('海外观察');
		$outNews = $this->newsModel->getNewsByCateid($cateid,3);
		//获取推荐新闻
		$recommendNews = $this->newsModel->getRecommendNews();
		//点击量排行
		$readNews = $this->newsModel->getNewsByField('readtimes');
		//评论数排行
		$commentNews = $this->newsModel->getNewsByField('commentnums');

		$this->assign([
			'inlandNews' => $inlandNews,
			'gatNews' => $gatNews,
			'newest' => $newest,
			'globalNews' => $globalNews,
			'outNews' => $outNews,
			'recommendNews' => $recommendNews,
			'readNews' => $readNews,
			'commentNews' => $commentNews
		]);

        return $this->fetch();
    }

    /**
     * 显示类别新闻
	 *
     * @param  int  $id
	 * @return \think\Response
     */
    public function catenews($id)
    {
		$idStr = $this->cateModel->getIdByPid($id);
		$records = $this->newsModel->getPageNewsByCateid($idStr,5);

		$this->assign('records', $records);

		return $this->fetch();
    }

    /**
     * 显示单条新闻
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
		//增加阅读次数
		$this->newsModel->addReadtimes($id);

		//获取单条新闻详情
        $record = $this->newsModel->find($id);
		$this->assign('record', $record);

		return $this->fetch();
    }

	/**
	 * 前台页面搜索
	 */
	public function search()
	{
		$keywords = request()->param('keywords');
		$searchNews = $this->newsModel->getNewsBySearch($keywords);
		$this->keywords = $keywords;
		$this->assign('searchNews', $searchNews);
		$this->assign('keywords', $keywords);
		return $this->fetch();
	}
}
