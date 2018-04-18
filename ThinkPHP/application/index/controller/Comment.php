<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\News;
use app\index\model\Category;

class Comment extends Controller
{
	/**
	 * 初始化验证用户登录
	 */
	public function _initialize()
	{
		$CateModel = new Category();
		$naviCate = $CateModel->getCategoryByPid('0');
		$this->assign('naviCate', $naviCate);
	}

    /**
     * 保存评论
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request->post();
		$data['addtime'] = time();
		$data['user_id'] = session('user.userid');
		$model = new \app\index\model\Comment;
		$model->save($data);
		$new_model = new News;
		$new_model->where('id','=',$data['news_id'])->setInc('commentnums');
		
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
		//查询单条新闻
        $model = new News;
		$record = $model->where('id','=',$id)->find();
		//查询单一类别新闻按降序排列
		$hourNews = $model->get24hoursNews();
		$this->assign([
			'record' => $record,
			'hourNews' => $hourNews
			]);

		return $this->fetch();
    }


}
