<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Users as UsersModel;
use app\index\model\Category;

class Users extends Controller
{
	/**
	 * 初始化验证用户登录
	 */
	public function _initialize()
	{
		if (!session('user')) {
			return $this->error('用户尚未登录',url('index/users/login'));
		}

		$CateModel = new Category();
		$naviCate = $CateModel->getCategoryByPid('0');
		$this->assign('naviCate', $naviCate);

		$this->model = new UsersModel();

	}

	/**
	 * 显示用户中心
	 */
	public function index(){
		//用户信息
		$id = session('user.userid');
		$user_record = UsersModel::get($id);
		$this->assign('user_record',$user_record);
		//显示该用户所有的评论
		$comment_model = new \app\index\model\Comment;
		$user_comments = $comment_model->where('user_id','=',$id)->paginate(10);
		$this->assign('user_comments',$user_comments);

		return $this->fetch();
	}


	/**
	 *  用户修改信息
	 */
	public function edit($id) {
		$user_record = $this->model->where('id','=',$id)->find();
		$this->assign('user_record',$user_record);

		return $this->fetch();
	}
	
	/**
	 *  执行用户修改信息
	 */
	public function update(Request $request,$id) {
		$data = $request->post();
		$image = $request->file('face')->move(ROOT_PATH.'/public/static/user_image');
		$model = \think\Image::open($image->getPathName());
		$model->thumb('180','180')->save($image->getPathName());
		$data['face'] = $image->getSaveName();
		
		$sucess = $this->model->where('id','=',$id)->update($data);
		if($sucess){
			return $this->success('用户信息保存成功',url('index/users/index'));
		}else{
			return $this->error('用户信息保存失败,请重试',url('index/users/edit/'.$id));
		}
	}
		 
    	
}
