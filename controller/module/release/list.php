<?php
class controller_module_release_list extends model_module_release_list
{
	public $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'module/release/';
	}
	/**
	 * 默认访问
	 */
	function c_index()
	{
		$this->c_list();
	}
	/**
	 * 列表总汇
	 */
	function c_list()
	{
		$platform_obj = new model_module_release_platform();
		$platform_data = $platform_obj->GetDataList();
		$platform_arr = array();
		if($platform_data)
		{
			foreach ($platform_data as $rs)
			{
				$platform_arr[] = array('id'=>$rs['id'],'name'=>$rs['platform_name']);
			}
		}
		//CPU
		$cpu_obj = new model_module_release_cpu();
		$cpu_data = $cpu_obj->GetDataList();
		$cpu_arr = array();
		if ($cpu_data)
		{
			foreach ($cpu_data as $rs)
			{
				$cpu_arr[] = array('id'=>$rs['id'],'name'=>$rs['cpu_name']);
			}
		}
		$this->show->assign('user_id', $_SESSION['USER_ID']);
		$this->show->assign('platform',json_encode(un_iconv($platform_arr)));
		$this->show->assign('cpu',json_encode(un_iconv($cpu_arr)));
		$this->show->display('list');
	}
	/**
	 * 列表数据
	 */
	function c_list_data()
	{
		$condition = "a.status=1";
		$platform = $_GET['platform'] ? $_GET['platform'] : $_POST['platform'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$condition .= $keyword ? " and a.module_name like '%$keyword%' " : '';
		$condition .= $platform ? " and a.platform='$platform'" : '';
		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows'] ,true );
		$json = array (
						'total' => $this->num 
		);
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		
		echo json_encode ( $json );
	}
	/**
	 * 历史版本
	 */
	function c_get_history_list()
	{
		$type = $_GET['type'] ? $_GET['type'] : $_POST['type'];
		$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
		$platform = $_GET['platform'] ? $_GET['platform'] : $_POST['platform'];
		$identification = isset($_GET['identification']) ? $_GET['identification'] : $_POST['identification'];
		$status = isset($_GET['status']) ? $_GET['status'] : $_POST['status'];
		$condition = " a.id!=".$id;
		$condition .= $platform ? " and a.platform='$platform'" : '';
		$condition .=" and a.identification='".$identification."'";
		$condition .= $status || $type=='list' ? " and a.status=1" : '';
		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows'] ,false );
		$str ='';
		if ($data)
		{
			foreach ($data as $rs)
			{
				$str .='<div class="list_info">';
				$str .='<li style="width:202px;"><a href="#" title="点击查看模块详细说明" onclick="get_info('.$rs['id'].')">'.$rs['module_name'].'</a></li>';
				$str .='<li style="width:51px;">'.$rs['version'].'</li>';
				if ($type != 'list')
				{
					$str .='<li style="width:81px;">'.($rs['svn_version'] ? $rs['svn_version'] : '&nbsp;').'</li>';
				}
				$str .='<li style="width:71px;">'.$rs['platform'].' </li>';
				$str .='<li style="width:51px;">'.($rs['cpu'] ? $rs['cpu'] : '&nbsp;').' </li>';
				if ($type == 'list')
				{
					$str .='<li style="width:431px;">'.($rs['description'] ? $rs['description'] : '&nbsp;').'</li>';
				}else{
					$str .='<li style="width:201px;">'.($rs['description'] ? $rs['description'] : '&nbsp;').'</li>';
				}
				$str .='<li style="width:51px;">'.($rs['owner_name'] ? $rs['owner_name'] : '&nbsp;').'</li>';
				if ($type != 'list')
				{
					$str .='<li style="width:61px;">'.($rs['status']==1 ? '审批通过' : ($rs['status']==-1 ? '被打回' : '待审批')).'</li>';
					$str .='<li style="width:61px;">'.($rs['audit_name'] ? $rs['audit_name'] : '&nbsp;').'</li>';
					$str .='<li style="width:61px;">'.$rs['user_name'].'</li>';
				}
				$str .='<li style="width:83px;">'.date('Y-m-d',strtotime($rs['date'])).'</li>';
				$str .='<li style="width:41px;"><a href="?model='.$_GET['model'].'&action=download&id='.$rs['id'].'" target="_blink">下载</a></li>';
				if ($type != 'list')
				{
					$str .='<li style="width:159px;text-align:center;">';
					$str .='<a href="#" title="查看详细模块信息"  onclick="get_info('.$rs['id'].')">查看详细</a>';
					$str .=' <a href="#" onclick="show_feedback('.$rs['id'].')">反馈信息</a>';
				}else{
					$str .='<li style="width:59px;text-align:center;">';
					$str .='<a href="#" title="查看详细模块信息" onclick="get_info('.$rs['id'].')"><img src="js/jqeasyui/themes/icons/view.gif" border="0"/></a>';
					if ($rs['reviewer'] == $_SESSION['USER_ID'])
					{
						$str .=' <a href="#" title="删除模块" onclick="deleterow('.$rs['id'].')"><img src="js/jqeasyui/themes/icons/del.gif" border="0"/></a>';
					}
				}
				$str .='</li>';
				$str .='</div>';
			}
		}
		echo un_iconv($str);
	}
	/**
	 * 获取单条信息
	 */
	function c_get_info()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$data = $this->GetDataList('a.id='.$id);
		if ($data)
		{
			echo json_encode(un_iconv($data[0]));
		}
	}
	/**
	 * 我的列表
	 */
	function c_mylist()
	{
		//系统
		$platform_obj = new model_module_release_platform();
		$platform_data = $platform_obj->GetDataList();
		$platform_arr = array();
		if($platform_data)
		{
			foreach ($platform_data as $rs)
			{
				$platform_arr[] = array('id'=>$rs['id'],'name'=>$rs['platform_name']);
			}
		}
		//CPU
		$cpu_obj = new model_module_release_cpu();
		$cpu_data = $cpu_obj->GetDataList();
		$cpu_arr = array();
		if ($cpu_data)
		{
			foreach ($cpu_data as $rs)
			{
				$cpu_arr[] = array('id'=>$rs['id'],'name'=>$rs['cpu_name']);
			}
		}
		$this->show->assign('platform',json_encode(un_iconv($platform_arr)));
		$this->show->assign('cpu',json_encode(un_iconv($cpu_arr)));
		$this->show->display('mylist');
	}
	/**
	 * 我的列表数据
	 */
	function c_mylist_data()
	{
		$condition = "a.user_id='".$_SESSION['USER_ID']."'";

		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows'] ,true );
		$json = array (
						'total' => $this->num 
		);
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		
		echo json_encode ( $json );
	}
	/**
	 * 审核列表
	 */
	function c_audit_list()
	{
		$this->show->display('audit-list');
	}
	/**
	 * 审核列表数据
	 */
	function c_audit_list_data()
	{
		$condition = " a.status=0 or a.status=-1";

		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows'] ,true );
		$json = array (
						'total' => $this->num 
		);
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		
		echo json_encode ( $json );
	}
	/**
	 * 添加
	 */
	function c_add()
	{
		if ($_POST)
		{
			if ($_FILES['upfile'])
			{
				$filename = $_FILES['upfile']['name'];
				$tmpfile = $_FILES['upfile']['tmp_name'];
				$ftp = new includes_class_ftp($this->ftp_host,$this->ftp_port,$this->ftp_user,$this->ftp_pwd);
				if($ftp->up_file($tmpfile,$_POST['module_name'].'/'.$_POST['platform'].'/'.$filename))
				{
					$_POST['module_path'] = trim($_POST['module_name']).'/'.trim($_POST['platform']).'/'.$filename;
				}else{
					$ftp->close();
					showmsg('上传附件失败,请与OA管理员联系！');
				}
				$ftp->close();
			}
			$_POST['user_id'] = $_SESSION['USER_ID'];
			$_POST['date'] = date('Y-m-d H:i:s');
			$_POST['identification'] = $_POST['identification'] ? $_POST['identification'] : md5(time().rand(0,9999));
			if ($this->Add($_POST))
			{
				$email = new includes_class_sendmail();
				$email->send($_SESSION['USERNAME'].'申请发布 '.$_POST['module_name'].' ver'.$_POST['version'].' 模块，有劳您登录OA审批！','详情请登录OA查看！'.oaurlinfo,array('green.wang@dinglicom.com','bill.tsao@dinglicom.com'));
				showmsg('操作成功','?model='.$_GET['model'].'&action=mylist');
			}else{
				showmsg('操作失败','?model='.$_GET['model'].'&action=mylist');
			}
		}
	}
	/**
	 *修改
	 */
	function c_edit()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($id)
		{
			$info = $this->GetOneInfo("id=$id");
			if($info)
			{
				if ($_FILES['upfile']['tmp_name'])
				{
					$filename = $_FILES['upfile']['name'];
					$tmpfile = $_FILES['upfile']['tmp_name'];
					$ftp = new includes_class_ftp($this->ftp_host,$this->ftp_port,$this->ftp_user,$this->ftp_pwd);
					if($ftp->up_file($tmpfile,$_POST['module_name'].'/'.$_POST['platform'].'/'.$filename))
					{
						$_POST['module_path'] = trim($_POST['module_name']).'/'.trim($_POST['platform']).'/'.$filename;
						@$ftp->del_file($info['module_path']);
					}else{
						showmsg('上传附件失败，请与OA管理员联系！');
					}
					$ftp->close();
				}
				if ($_SESSION['USER_ID'] != $info['reviewer'])
				{
					$_POST['status'] = 0;
				}
				if ($this->Edit($_POST,$id))
				{
					if ($info['status']==-1)
					{
						$email = new includes_class_sendmail();
						$email->send($_SESSION['USERNAME'].'重新申请发布 '.$_POST['module_name'].' ver'.$_POST['version'].' 模块，有劳您登录OA审批！','详情请登录OA查看！'.oaurlinfo,array('green.wang@dinglicom.com','bill.tsao@dinglicom.com'));
					}
					if ($_SESSION['USER_ID'] != $info['reviewer'])
					{
						showmsg('操作成功','?model='.$_GET['model'].'&action=mylist');
					}else{
						showmsg('操作成功','?model='.$_GET['model'].'&action=list');
					}
				}else{
					showmsg('操作失败','?model='.$_GET['model'].'&action=mylist');
				}
			}else{
				showmsg('非法请求','?model='.$_GET['model'].'&action=mylist');
			}
		}
	}
	/**
	 * 删除记录
	 */
	function c_del()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($id)
		{
			$info = $this->GetOneInfo("id=$id");
			if (($info['reviewer']== $_SESSION['USER_ID']) || ($info['user_id'] == $_SESSION['USER_ID'] && ($info['status']==0 || $info['status']==-1)))
			{
				if($info['module_path'])
				{
					$ftp = new includes_class_ftp($this->ftp_host,$this->ftp_port,$this->ftp_user,$this->ftp_pwd);
					@$ftp->del_file($info['module_path']);
					$ftp->close();
				}
				
				if ($this->Del($id))
				{
					echo 1;
				}else{
					echo -1;
				}
			}else{
				echo -2;
			}
		}
	}
	/**
	 * 检查模块名称是否存在
	 */
	function c_check_module_name()
	{
		$module_name = $_GET['module_name'] ? $_GET['module_name'] : $_POST['module_name'];
		if ($module_name)
		{
			$info = $this->GetOneInfo("module_name='$module_name'");
			if ($info)
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			echo -2;
		}
	}
	
	/**
	 * 检查唯一标识
	 */
	function c_check_identification()
	{
		$identification = $_GET['identification'] ? $_GET['identification'] : $_POST['identification'];
		if ($identification)
		{
			$info = $this->GetOneInfo("identification='$identification'");
			if ($info)
			{
				echo 1;
			}else{
				echo -1;
			}
		}
	}
	/**
	 * 下载
	 */
	function c_download()
	{
		$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
		if ($id)
		{
			$info = $this->GetOneInfo("id=".$id);
			if ($info['module_path'])
			{
				$this->update_download_info($id,$_SESSION['USER_ID']); //记录下载次数
				$filename = 'http://172.16.1.102:8080/'.rawurlencode(trim($info['module_path']));
				header("Content-Type: application/force-download");
				header("Content-Disposition: attachment; filename=".basename(trim($info['module_path']))); 
				readfile($filename);
			}
		}
	}
	/**
	 * 审核
	 */
	function c_audit()
	{
		if ($_POST && $_GET['id'])
		{
			$_POST = mb_iconv($_POST);
			$_POST['reviewer'] = $_SESSION['USER_ID'];
			if ($this->audit($_GET['id'],$_POST['status'],$_SESSION['USER_ID'],$_POST['audit_remark']))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			echo -1;
		}
	}
}