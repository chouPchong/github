<?php

namespace app\admin\controller;

use think\Request;
use app\admin\model\Admin as AdminModel;

class Admin extends Base
{
	/*
	 * 检测管理员权限
	 */
	public function _init(){
		if(session('admin.level') < 3){
			session('admin',null);
			return $this->error('管理员权限不足,重新登录',url('admin/admin/login'));
		}
	}

    /**
     * 显示管理员列表
     */
    public function index()
    {
    	$model = new AdminModel();
		$records = $model->select();
		$this->assign('records',$records);
		return $this->fetch();
	}
	
	/*
	 * 管理员添加
	 */
	public function add()
	{
		return $this->fetch();
	}
	
	/*
	 * 执行管理员添加
	 */
	public function save(Request $request)
	{
//		$model = new AdminModel($request->post());
//		$success = $model->save();
		$success = AdminModel::create($request->post());
		if($success){
			return $this->success('管理员添加成功',url('admin/admin/index'));
		}else{
			return $this->error('管理员添加失败',url('admin/admin/add'));
		}
	}
	
	/*
	 * 管理员编辑
	 */
	public function edit($id)
	{
		$record = AdminModel::get($id);
		$this->assign('record',$record);
		return $this->fetch();
	}
	
	/*
	 * 执行管理员编辑
	 */
	public function update(Request $request,$id)
	{
		$sucess = AdminModel::update($request->post(),['id'=>$id]);
		if($sucess){
			return $this->success('管理员更新成功',url('admin/admin/index'));
		}else{
			return $this->error('管理员更新失败,请重试',url('admin/admin/edit',['id' => $id]));
		}
		
	}
	
	/*
	 * 管理员删除
	 */
	public function delete($id)
	{
		$model = new AdminModel();
       	$sucess = $model->where('id','=',$id)->delete();
		if($sucess){
			return $this->success('管理员删除成功',url('admin/admin/index'));
		}else{
			return $this->error('管理员删除失败,请重试',url('admin/admin/edit',['id' => $id]));
		}
		
	}
}
