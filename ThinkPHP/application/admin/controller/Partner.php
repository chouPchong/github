<?php

namespace app\admin\controller;

use think\Request;
use app\admin\model\Partner as PartnerModel;

class Partner extends Base
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
     * 显示合作伙伴列表
     */
    public function index(Request $request)
    {
		$keywords = $request->get('keywords');
		$model = new PartnerModel();
		if ($keywords) {
			$records = $model->where('name','like',"%{$keywords}%")->paginate(2);
		} else {
			$records = $model->paginate(5);
		}

		$this->assign(['records' => $records, 'keywords' => $keywords]);
		return $this->fetch();
    }

    /**
     * 显示添加页面
     */
    public function create()
    {
        return $this->fetch();
    }

    /**
     * 执行添加
     */
    public function save(Request $request)
    {
    	$data = $request->post();	
        $model = new PartnerModel();
		$result = $model->where('name','=',$data['name'])->whereor('website','=',$data['website'])->find();
		if($result){
			return $this->error('伙伴名称或网址已存在',url('admin/partner/create'));
		}else{
			$success = $model->save($data);
			if($success){
				return $this->success('数据插入成功',url('admin/partner/index'));
			}else{
				return $this->error('数据插入失败,请重试',url('admin/partner/create'));
			}
			
		}
    }

    /**
     * 显示详情
     */
    public function read($id)
    {
        $part_record = PartnerModel::get($id);
		$this->assign('part_record',$part_record);
		return $this->fetch();
    }

    /**
     * 显示编辑表单页.
     */
    public function edit($id)
    {
        $record = PartnerModel::get($id);
		$this->assign('record',$record);
		return $this->fetch();
    }

    /**
     * 保存更新的资源
     */
    public function update(Request $request, $id)
    {
        $data = $request->only('name,website');	
		$result = PartnerModel::update($data,['id'=>$id]);
		if($result){
			return $this->success('合作伙伴编辑成功',url('admin/partner/index'));
		}else{
			return $this->error('修改失败,请重试',url('admin/partner/edit', ['id' => $id]));
		}
    }

    /**
     * 删除指定资源
     */
    public function delete($id)
    {
        $model = new PartnerModel();
		$result = $model->where('id','=',$id)->delete();
		if($result){
			return $this->success('删除成功',url('admin/partner/index'));
		}else{
			return $this->error('删除失败,请重试',url('admin/partner/index'));
		}
    }
}
