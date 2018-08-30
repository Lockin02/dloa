<?php
class controller_device_mydevice extends model_device_mydevice
{
	public $show; // 模板显示
	/**
	 * 构造函数
	 *
	 */
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = '{table}';
		$this->pk = 'id';
		$this->show = new show();
	}
	/**
	 * 默认访问显示
	 *
	 */
	function c_index()
	{
		$this->showlist();
	}
	//##############################################显示函数#################################################
	/**
	 * 显示列表
	 *
	 */
	function c_showlist()
	{
		$this->show->assign('selected_1',($_GET['status']==1 ? 'selected' : ''));
		$this->show->assign('selected_2',($_GET['status']==2 ? 'selected' : ''));
		$this->show->assign('list',$this->model_device_list());
		$this->show->display('device_mydevice');
	}
	/**
	 * 借用定单记录
	 *
	 */
	function c_order_list()
	{
		$this->show->assign('list',$this->model_mydevice_order_list());
		$this->show->display('device_mydevice-order-list');
	}
	function c_show_order_list()
	{
		$this->show->assign('list',$this->model_show_order_list());
		$this->show->display('device_mydevice-order-info');
	}
	/**
	 * 显示确认
	 *
	 */
	function c_show_confirm()
	{
		showmsg('您确定要领取该订单设备吗？',"location.href='?model=device_mydevice&action=save_confirm&id=".$_GET['id']."'",'button');
	}
	//##############################################操作函数#################################################
	/**
	 * 保存确认订单
	 */
	function c_save_confirm()
	{
		if (intval($_GET['id']))
		{
			if ($this->model_save_confirm())
			{
				showmsg('操作成功！','self.parent.location.reload();','button');
			}else{
				showmsg('操作失败，请与管理员联系！');
			}
		}else{
			showmsg('非法参数！');
		}
	}
	//##############################################结束#################################################
	/**
	 * 析构函数
	 */
	function __destruct()
	{

	}
}
?>