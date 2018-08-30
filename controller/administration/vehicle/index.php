<?php
class controller_administration_vehicle_index extends model_administration_vehicle_index
{
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'administration/vehicle/';
	}
	/**
	 * ��ҳ
	 */
	function c_index()
	{
		global $func_limit;
		$this->show->assign('admin_user',($func_limit['����Ա'] ? '1' : '0'));
		$this->show->assign('audit_user',($func_limit['��������'] ? '1' : '0'));
		$this->show->assign('USER_ID',$_SESSION['USER_ID']);
		$this->show->assign('datetime',date('Y-m-d H:i:s'));
		$this->show->display('index');
	}
	/**
	 * �����¼
	 */
	function c_apply_list()
	{
		$this->tbl_name = 'vehicle_usage';
		$this->pk = 'VU_ID';
		$data = $this->GetDataList('a.VU_STATUS>=1',$_POST['page'],$_POST['rows']);
		$json = array('total'=>$this->num);
		$json['rows'] = un_iconv($data);
		echo json_encode($json);
	}
	/**
	 * �ҵ�����
	 */
	function c_myapply()
	{
		$this->tbl_name = 'vehicle_usage';
		$this->pk = 'VU_ID';
		$data = $this->GetDataList("VU_PROPOSER='".$_SESSION['USER_ID']."'",$_POST['page'],$_POST['rows']);
		$json = array('total'=>$this->num);
		$json['rows'] = un_iconv($data);
		echo json_encode($json);
	}
	/**
	 * ���������б�
	 */
	function c_audit_list()
	{
		global $func_limit;
		if ($func_limit['��������'])
		{
			$this->tbl_name = 'vehicle_usage';
			$this->pk = 'VU_ID';
			$data = $this->GetDataList("VU_DEPT in (".$func_limit['��������'].")",$_POST['page'],$_POST['rows']);
			$json = array('total'=>$this->num);
			$json['rows'] = un_iconv($data);
			echo json_encode($json);
		}
		
	}
	/**
	 * �޸�����
	 */
	function c_edit_apply()
	{
		if ($_GET ['id'])
		{
			$this->tbl_name = 'vehicle_usage';
			$this->pk = 'VU_ID';
			$info = $this->GetOneInfo ( array (
												'VU_ID' => $_GET ['id'] 
			) );
			if ($info)
			{
				if ($info ['VU_STATUS'] == 1)
				{
					echo 2;
				} else if ($this->Edit ( mb_iconv ( $_POST ), $_GET ['id'] ))
				{
					echo 1;
				} else
				{
					echo - 1;
				}
			} else
			{
				echo - 1;
			}
		} else
		{
			echo - 1;
		}
	}
	/**
	 * �����б�
	 */
	function c_accepted_list()
	{
		$this->tbl_name = 'vehicle_usage';
		$this->pk = 'VU_ID';
		$data = $this->GetDataList('a.VU_STATUS>=1',$_POST['page'],$_POST['rows']);
		$json = array('total'=>$this->num);
		$json['rows'] = un_iconv($data);
		echo json_encode($json);
	}
	/**
	 * ������ϢJSON
	 */
	function c_info_list()
	{
		$data = $this->GetDataList(null,$_POST['page'],$_POST['rows']);
		$json = array('total'=>$this->num);
		$json['rows'] = un_iconv($data);
		echo json_encode($json);
	}
	/**
	 * �������
	 */
	function c_add()
	{
		if ($_POST)
		{
			if ($this->Add(mb_iconv($_POST)))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	/**
	 * �޸�
	 */
	function c_edit()
	{
		if ($this->Edit($_POST,$_POST['V_ID']))
		{
			echo 1;
		}else{
			echo -1;
		}
	}
	/**
	 * ɾ��
	 */
	function c_del()
	{
		if ($this->Del($_POST['V_ID']))
		{
			echo 1;
		}else{
			echo -1;
		}
	}
	/**
	 * �ύ����
	 */
	function c_apply()
	{
		$this->tbl_name = 'vehicle_usage';
		$this->pk = 'VU_ID';
		$_POST['VU_DEPT'] = $_SESSION['DEPT_ID'];
		if ($this->Add(mb_iconv($_POST)))
		{
			$this->send_email_audit();
			echo 1;
		}else{
			echo -1;
		}
	}
	/**
	 * ��ȡ�����û�
	 */
	function c_get_all_user_json()
	{
		$obj = new model_user();
		$user_data = $obj->GetUser();
		$data = array();
		if ($user_data)
		{
			foreach ($user_data as $rs)
			{
				$data[] = array('user_id'=>$rs['user_id'],'user_name'=>un_iconv($rs['user_name']));
			}
		}
		echo  json_encode($data);
	}
	/**
	 * ����
	 */
	function c_audit()
	{
		if ($_POST)
		{
			if ($this->audit($_GET['VU_ID'],$_POST))
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	/**
	 * ��������
	 */
	function c_export_data()
	{
		$this->tbl_name = 'vehicle_usage';
		$this->pk = 'VU_ID';
		$V_TYPE = $_GET['V_TYPE'] ? $_GET['V_TYPE'] : $_POST['V_TYPE'];
		$VU_START = $_GET['VU_START'] ? $_GET['VU_START'] : $_POST['VU_START'];
		$VU_END = $_GET['VU_END'] ? $_GET['VU_END'] : $_POST['VU_END'];
		$VU_STATUS = $_GET['VU_STATUS'] ? $_GET['VU_STATUS'] : $_POST['VU_STATUS'];
		$conditions = ' 1 ';
		if ($V_TYPE && $V_TYPE!='undefined')
		{
			$conditions .=" and a.V_TYPE='$V_TYPE' ";
		}
		if ($VU_START && $VU_START!='undefined')
		{
			$conditions .=" and a.VU_START >='$VU_START' ";
		}
		if ($VU_END && $VU_END!='undefined')
		{
			$conditions .=" and a.VU_END <='$VU_END 23:59:59' ";
		}
		if ($VU_STATUS && $VU_STATUS!='undefined')
		{
			$conditions .=" and a.VU_STATUS='$VU_STATUS' ";
		}
		$data = $this->GetDataList($conditions);
		$status = array('-2'=>'��ȡ��','-1'=>'�����','0'=>'�����','1'=>'�����','2'=>'ʹ����','3'=>'�ѽ���','4'=>'�޷�����');
		$type = array('0'=>'����','1'=>'С��');
		$excel_data = array();
		$total = 0;
		foreach ($data as $rs)
		{
			$total++;
			$excel_data[] = array($rs['VU_ID'],$type[$rs['V_TYPE']],$rs['apply_user'],$rs['use_user'],$rs['VU_DEPARTURE'],$rs['VU_DERSTINATION'],$rs['VU_NUMBER'],$rs['VU_START'],$rs['VU_END'],$rs['VU_MILEAGE'],$rs['VU_REASON'],$status[$rs['VU_STATUS']]);
		}
		$sheet = '����ʹ�ü�¼';
		$title = array('ID','��������','������','�ó���','�����ص�','Ŀ��ص�','�泵����','����ʱ��','����ʱ��','���','����','״̬');
		$excel = new includes_class_excel();
		$excel->SetTitle(array($sheet),array($title));
		$excel->SetContent(array($excel_data));
		$excel->objActSheet[0]->setCellValue('A'.($total+4),un_iconv('�ϼƣ�'.$total));
		$excel->OutPut();
	}
}
?>