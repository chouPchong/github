<?php

namespace app\admin\controller;

use app\admin\model\Comment as CommentModel;
use think\Request;

class Comment extends Base
{

	/*
	 * 检测管理员权限
	 */
	public function _init(){
		if(session('admin.level') < 2){
			session('admin',null);
			return $this->error('管理员权限不足,重新登录',url('admin/admin/login'));
		}
	}

    /**
     * 显示评论列表
     */
    public function index(Request $request)
    {
		$keywords = $request->get('keywords');
		$model = new CommentModel();
		if ($keywords) {
			$records = $model->where('content','like',"%{$keywords}%")->paginate(2);
		} else {
			$records = $model->paginate(5);
		}

		$this->assign(['records' => $records, 'keywords' => $keywords]);
		return $this->fetch();
    }

    /**
     * 显示评论详情
     */
    public function read($id)
    {
        $record = CommentModel::get($id);
		$this->assign('record',$record);
		return $this->fetch();
    }

    /**
     * 删除指定评论
     */
    public function delete($id)
    {
        $model = new CommentModel;
		$result = $model->where('id','=',$id)->delete();
		if($result){
			return $this->success('评论删除成功',url('admin/comment/index'));
		}else{
			return $this->error('评论删除失败,请重试',url('admin/comment/index'));
		}
    }
}
