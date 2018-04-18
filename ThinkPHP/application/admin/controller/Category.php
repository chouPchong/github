<?php

namespace app\admin\controller;

use think\Request;
use app\admin\model\Category as CateModel;

class Category extends Base
{
	/*
	 * 构造函数,检测管理员权限
	 */ 
	public function _init(){
		if(session('admin.level') < 2){
			session('admin',null);
			return $this->error('管理员权限不足,重新登录',url('admin/admin/login'));
		}
	}
	
    /**
     * 显示分类列表
     */
    public function index()
    {
        $model =  new CateModel;
		$records = $model->getCategoryTree();
		$this->assign('records',$records);
        return $this->fetch();
    }

    /**
     * 显示创建分类表单页.
     */
    public function create()
    {
    	$model =  new CateModel;
		$records = $model->getCategoryTree();
		$this->assign('records',$records);
        return $this->fetch();
    }

    /**
     * 保存新建的资源
     */
    public function save(Request $request)
    {
        $data = $request->post();
		$model = new CateModel;
		$record = $model->save($data);
		if($record){
    		return $this->success('新闻类别添加成功',url('admin/category/index'));
    	}
    }

    /**
     * 查看分类详情
     */
    public function read($id)
    {
       $cate_record = CateModel::get($id);
	   $model = new CateModel;
	   $top_record = $model->where('id','=',$cate_record->pid)->find();
	   $this->assign('cate_record',$cate_record);
	   $this->assign('top_record',$top_record);
	   return $this->fetch(); 
    }

    /**
     * 显示编辑分类表单页.
     */
    public function edit($id)
    {
        $cate_record = CateModel::get($id);
 		$this->assign('cate_record',$cate_record);
		$model =  new CateModel;
		$records = $model->getCategoryTree();
		$this->assign('records',$records);
	   	return $this->fetch(); 
    }

    /**
     * 保存更新的资源
     */
    public function update(Request $request, $id)
    {
        $data = $request->only('catename,pid');
		$model = new CateModel;
		$sucess = $model->update($data,['id'=>$id]);
		if($sucess){
			return $this->success('新闻类别更新成功',url('admin/category/index'));
		}else{
			return $this->error('新闻类别更新失败,请重试',url('admin/category/edit', ['id' => $id]));
		}
    }

    /**
     * 删除指定分类
     */
    public function delete($id)
    {
		$model = CateModel::get($id);
		$model->state = 0;
       	$sucess = $model->save();
		if($sucess){
			return $this->success('新闻类别删除成功',url('admin/category/index'));
		}else{
			return $this->error('新闻类别删除失败,请重试',url('admin/category/index'));
		}	 
    }
}
