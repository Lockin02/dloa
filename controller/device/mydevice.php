<?php
class controller_device_mydevice extends model_device_mydevice
{
	public $show; // ģ����ʾ
	/**
	 * ���캯��
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
	 * Ĭ�Ϸ�����ʾ
	 *
	 */
	function c_index()
	{
		$this->showlist();
	}
	//##############################################��ʾ����#################################################
	/**
	 * ��ʾ�б�
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
	 * ���ö�����¼
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
	 * ��ʾȷ��
	 *
	 */
	function c_show_confirm()
	{
		showmsg('��ȷ��Ҫ��ȡ�ö����豸��',"location.href='?model=device_mydevice&action=save_confirm&id=".$_GET['id']."'",'button');
	}
	//##############################################��������#################################################
	/**
	 * ����ȷ�϶���
	 */
	function c_save_confirm()
	{
		if (intval($_GET['id']))
		{
			if ($this->model_save_confirm())
			{
				showmsg('�����ɹ���','self.parent.location.reload();','button');
			}else{
				showmsg('����ʧ�ܣ��������Ա��ϵ��');
			}
		}else{
			showmsg('�Ƿ�������');
		}
	}
	//##############################################����#################################################
	/**
	 * ��������
	 */
	function __destruct()
	{

	}
}
?>