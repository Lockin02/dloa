<?php
class controller_administration_appraisal_performance_list extends model_administration_appraisal_performance_list
{
	public $show;
	public $count_my_fraction = 0;
	function __construct()
	{
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'administration/appraisal/performance/';
	}
	/**
	 * 默认访问
	 */
	function c_index()
	{
		
		$table = array ();
		$tpl_id_arr = array ();
		$tpl_obj = new model_administration_appraisal_performance_item ();
		//$years = $this->this_quarter == 1 ? date('Y')-1 : date('Y');
		$years = date('Y');
		$tpl_data = $tpl_obj->GetTemplate ( $_SESSION ['USER_ID'],$years,$this->last_quarter );
		$quarter_data = $this->GetQuarterList ($_SESSION['USER_ID']);
		if ($quarter_data)
		{
			foreach ( $quarter_data as $rs )
			{
				$tpl_id_arr [] = $rs ['tpl_id'];
			}
		}
		if ($tpl_data)
		{
			foreach ( $tpl_data as $rs )
			{
				if ($tpl_id_arr)
				{
					if (! in_array ( $rs ['id'], $tpl_id_arr ))
					{
						$table [] = $rs;
					}
				} else
				{
					$table [] = $rs;
				}
			}
		}
		
		$config = new model_administration_appraisal_performance_config();
		$this->manager = $config->getProjectManagerList(false);
		$isManage = 'false';
		if(in_array($_SESSION['user_id'], $this->manager)){
			$isManage = 'true';
		}
		$this->show->assign ( 'isManager', $isManage);
		$this->show->assign ( 'table_list', json_encode ( un_iconv ( $table ) ) );
		$this->show->display ( 'mylist' );
	}
	/**
	 * 员工季度考核列表界面
	 */
	function c_list()
	{
		$this->show->assign('quarte',($this->this_quarter < 4 ? $this->last_quarter : $this->this_quarter));
		$this->show->assign('year',$this->last_quarter_year);
		$this->show->display('list');
	}
	/**
	 * 员工季度考核列表数据
	 */
	function c_list_data()
	{
		global $func_limit;
		$data = array();
		if ($func_limit['查看部门'])
		{
			$dept_id_str = $func_limit['查看部门'];
		}
		
		$condition .= $dept_id_str ? " b.dept_id in(".trim($dept_id_str,',').") " : " b.dept_id=".$_SESSION['DEPT_ID'];
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		$years = $_GET ['years'] ? $_GET ['years'] : $_POST ['years'];
		$quarter = $_GET ['quarter'] ? $_GET ['quarter'] : $_POST ['quarter'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$keyword = $keyword ? $keyword : '';
		$condition .= $dept_id ? " and b.dept_id=".$dept_id : '';
		$condition .= $years ? " and a.years=$years " : '';
		$condition .= $quarter ? " and a.quarter=$quarter" : '';
		$condition .= $keyword ? " and (e.name like '%$keyword%' or b.user_name like '%$keyword%')" : '';
		
		if(!$func_limit['全部查阅']){
			$condition .= " AND (a.user_id = '{$_SESSION['USER_ID']}' OR a.assess_userid = '{$_SESSION['USER_ID']}' OR a.audit_userid = '{$_SESSION['USER_ID']}')";
		}
		
		
		$sort = $_GET['sort'] ? $_GET['sort'] : $_POST['sort'];
		$order = $_GET['order'] ? $_GET['order'] : $_POST['order'];
		$order_str = $sort ? 'a.'.$sort.' '.$order : '';
		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows'] ,$order_str );
		
		
		if ($data)
		{
			$user_fraction_arr = array();
			foreach ($data as $rs)
			{
				$key = $rs['years'].'-'.$rs['quarter'];
				$user_fraction_arr[$rs['user_id']][$key][] = $rs['count_audit_fraction'] ? $rs['count_audit_fraction'] : $rs['count_assess_fraction'];
			}
			
			$new_data = array();
			foreach ($data as $row)
			{
				$key = $row['years'].'-'.$row['quarter'];
				$row['final_fraction'] = round(array_sum($user_fraction_arr[$row['user_id']][$key]) / count($user_fraction_arr[$row['user_id']][$key]),3);
				$row['rowspan_num'] = count($user_fraction_arr[$row['user_id']][$key]);
				$new_data[] = $row;
			}
		}
		$json = array (
						'total' => $this->num 
		);
		if ($new_data)
		{
			$json ['rows'] = un_iconv ( $new_data );
		} else
		{
			$json ['rows'] = array ();
		}
		
		echo json_encode ( $json );
	}
		
	/**
	 * 项目经理为评价组员列表
	 */
	function c_no_evaluate_member(){
		$content = '';
		if(isset($_POST['year']) and isset($_POST['quarter'])){
			if(($_POST['year'] == $this->last_quarter_year) and ($_POST['quarter'] == $this->this_quarter)){
			
				$manager = new model_administration_appraisal_evaluate_manager();
				$data = $manager->getMemberByManager($_SESSION['user_id'], $_POST['year'], $_POST['quarter']);
				$members = array();
				if(trim($data['all']['developer']) != "" and stripos($data['all']['developer'], '\,') !== false){
					$members = spliti(',', $data['all']['developer']);
				}else{
					$members[] = $data['all']['developer'];
				}
				$notIn = array();
				foreach ($members as $member){
					if(!in_array($member, $data['in'])){
						$notIn[] = $member;
					}
				}
				
				if(count($notIn) > 0){
					$content .= "<table border ='0' cellpadding='0' cellspacing='0' >";
					$content .= "<tr><td>未提交成员有：</td></tr>";
					foreach($notIn as $notInMember){
						$content .= "<tr>"
								. "<td>".$manager->getManagerRealName($notInMember)."</td>"
								."</tr>";
					}
					
					$content .= "</table>";
				}else{
					$content .= '全成员已提交';
				}
			}
		}
		echo $content;
	}
	/**
	 * 考核列表
	 */
	function c_assess_list()
	{
		$reslase = $this->GetReleaseResult($_SESSION['USER_ID'],$this->last_quarter_year,($this->this_quarter < 4 ? $this->last_quarter: $this->this_quarter),1);
		$this->show->assign('reslase',($reslase ? 'true' : 'false'));
		$this->show->assign('year',$this->last_quarter_year);
		$this->show->assign('quarte',($this->this_quarter < 4 ? $this->last_quarter: $this->this_quarter));
		$this->show->display ( 'assess-list' );
	}
	function c_audit_list()
	{
		$reslase = $this->GetReleaseResult($_SESSION['USER_ID'],$this->last_quarter_year,($this->this_quarter < 4 ? $this->last_quarter: $this->this_quarter),2);//这里平时修改为上季度
		$this->show->assign('reslase',($reslase ? 'true' : 'false'));
		$this->show->assign('year',$this->last_quarter_year);
		$this->show->assign('quarte',($this->this_quarter < 4 ? $this->last_quarter: $this->this_quarter));
		$this->show->display ( 'audit-list' );
	}
	/**
	 * 显示添加模板
	 */
	function c_show_add_tpl()
	{
		$tpl_id = $_GET ['tpl_id'] ? $_GET ['tpl_id'] : $_POST ['tpl_id'];
		if ($tpl_id)
		{
			$tpl_obj = new model_administration_appraisal_performance_item ();
			$rs = $tpl_obj->GetOneData ( 'a.id=' . $tpl_id );
			$content = '<html><title>' . $rs ['name'] . '</title><head>';
			$content .= '<meta http-equiv="Content-Type" content="text/html; charset=GBK">';
			$content .= '<script type="text/javascript" src="js/jqeasyui/jquery.min.js"></script>';
			$content .= '<script type="text/javascript" src="js/preformance.js"></script>';
			$content .= '<style type="text/css">';
			$content .= ' input[type=text] { background-color:#e1e1e1; }';
			$content .= 'td {border-collapse:collapse;border: 1px solid #000000;}';
			$content .= 'table {border-collapse:collapse;border: 1px solid #000000;font-size:12px;}';
			$content .= 'textarea {background-color:#e1e1e1;}';
			$content .= '</style></head>';
			$content .= '<form method="post" action="?model=' . $_GET ['model'] . '&action=add&tpl_id=' . $tpl_id . '" enctype="multipart/form-data" onsubmit="return submit_check(\'add\')">';
			$content .= $rs ['content'];
			$content = str_replace ( '{user_name}', $_SESSION ['USERNAME'], $content );
			$content = str_replace ( '{jobs_name}', $_SESSION ['JOBS_NAME'], $content );
			$content = str_replace ( '{cycle}', $rs ['years'] . '年第' . $rs ['quarter'] . '季度', $content );
			$content = str_replace ( '{assess_user_name}', $rs ['assess_user_name'], $content );
			$content = str_replace ( '{audit_user_name}', $rs ['audit_user_name'], $content );
			$content = str_replace ( '{average_assess_fraction}', '&nbsp;', $content );
			$content = str_replace ( '{average_audit_fraction}', '&nbsp;', $content );
			
			preg_match_all ( '/<span class="percentage"(.+?)>(.+?)<\/span>/is', $content, $arr );
			$count_percentage = 0;
			if ($arr [2])
			{
				foreach ( $arr [2] as $val )
				{
					$count_percentage = $count_percentage + trim ( str_replace ( '%', '', $val ) );
				}
			}
			$content = str_replace ( '{count_percentage}', $count_percentage . '%', $content );
			$content = preg_replace ( '/<input class="evaluate_fraction" name="evaluate_fraction(.+?)>/', '&nbsp;', $content ); //关闭评介人评分框
			$content = preg_replace ( '/<input class="assess_fraction" name="assess_fraction(.+?)>/', '&nbsp;', $content ); //关闭考核人评分框
			if (strpos($content,'{manager_schedule_fraction}')!==false)
			{
				if ($rs['assess_status'] == 1)
				{
					$manager = new model_administration_appraisal_evaluate_manager();
					$ManagerScheduleFraction = $manager->GetManagerScheduleFraction($rs['user_id'],$rs ['years'],$rs ['quarter']);
					$content = str_replace('{manager_schedule_fraction}',$ManagerScheduleFraction,$content);
				}else{
					$content = str_replace('{manager_schedule_fraction}','',$content);
				}
			}
			$content = preg_replace ( '/<input class="audit_fraction" name="audit_fraction(.+?)>/', '&nbsp;', $content ); //关闭审核人评分框
			$content = preg_replace ( '/<textarea(.+?)evaluate(.+?)>/', '&nbsp;', $content ); //关闭评价描述填写框
			$content = preg_replace ( '/<textarea(.+?)opinion(.+?)>/', '&nbsp;', $content ); //关闭意见填写框
			$content .= '<div style="text-align:center;"><input type="submit" value=" 确定提交 " /> &nbsp;&nbsp; <input type="button" onclick="window.close();" value=" 关闭返回 " /></div>';
			$content .= '</form></html>';
			echo $content;
		} else
		{
			showmsg ( '非法访问！' );
		}
	}
	/**
	 * 我的列表数据
	 */
	function c_mylist_data()
	{
		$condition = " a.user_id='".$_SESSION['USER_ID']."' ";
		$years = $_GET ['years'] ? $_GET ['years'] : $_POST ['years'];
		$quarter = $_GET ['quarter'] ? $_GET ['quarter'] : $_POST ['quarter'];
		$condition .= $years ? " and a.years=$years " : '';
		$condition .= $quarter ? " and a.quarter=$quarter" : '';
		$sort = $_GET['sort'] ? $_GET['sort'] : $_POST['sort'];
		$order = $_GET['order'] ? $_GET['order'] : $_POST['order'];
		$order_str = $sort ? 'a.'.$sort.' '.$order : '';
		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows'] ,$order_str );
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
	 * 考核列表
	 */
	function c_assess_list_data()
	{
		$condition = ' e.assess_userid = \'' . $_SESSION ['USER_ID'] . '\' ';
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		$years = $_GET ['years'] ? $_GET ['years'] : $_POST ['years'];
		$quarter = $_GET ['quarter'] ? $_GET ['quarter'] : $_POST ['quarter'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		
		$condition .= $years ? " and a.years=$years " : '';
		$condition .= $quarter ? " and a.quarter=$quarter" : '';
		$condition .= $dept_id ? " and b.dept_id=".$dept_id : '';
		$condition .= $keyword ? " and (b.user_name like '%$keyword%' or e.name like '%$keyword%')" : '';
		
		$sort = $_GET['sort'] ? $_GET['sort'] : $_POST['sort'];
		$order = $_GET['order'] ? $_GET['order'] : $_POST['order'];
		$order_str = $sort ? 'a.'.$sort.' '.$order : '';
		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows'] ,$order_str);
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
	 * 考核列表
	 */
	function c_audit_list_data()
	{
		$condition = 'a.assess_status=1 and  e.audit_userid = \'' . $_SESSION ['USER_ID'] . '\' ';
		
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		$years = $_GET ['years'] ? $_GET ['years'] : $_POST ['years'];
		$quarter = $_GET ['quarter'] ? $_GET ['quarter'] : $_POST ['quarter'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		
		$condition .= $years ? " and a.years=$years " : '';
		$condition .= $quarter ? " and a.quarter=$quarter" : '';
		$condition .= $dept_id ? " and b.dept_id=".$dept_id : '';
		$condition .= $keyword ? " and (b.user_name like '%$keyword%' or e.name like '%$keyword%')" : '';
		
		$sort = $_GET['sort'] ? $_GET['sort'] : $_POST['sort'];
		$order = $_GET['order'] ? $_GET['order'] : $_POST['order'];
		$order_str = $sort ? 'a.'.$sort.' '.$order : '';
		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows'] ,$order_str );
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
		$tpl_id = $_GET ['tpl_id'];
		if ($tpl_id && $_POST)
		{
			if ($this->Add ( $tpl_id, $_POST ))
			{
				showmsg ( '提交成功！', 'opener.location.reload();window.close()', 'button' );
			}
		}
	}
	/**
	 * 显示详细
	 */
	function c_showinfo()
	{
		$rs = $this->GetOneInfo ( 'a.id=' . $_GET ['id'] );
		if ($rs)
		{
			
			$tpl_obj = new model_administration_appraisal_performance_item ();
			$row = $tpl_obj->GetOneData ( 'a.id=' . $rs ['tpl_id'] );
			$content = '<html><title>' . $row ['name'] . '</title><head>';
			$content .= '<meta http-equiv="Content-Type" content="text/html; charset=GBK">';
			$content .= '<script type="text/javascript" src="js/jqeasyui/jquery.min.js"></script>';
			$content .= '<script type="text/javascript" src="js/preformance.js"></script>';
			$content .= '<style type="text/css">';
			$content .= ' input[type=text] { background-color:#e1e1e1; }';
			$content .= 'td {border-collapse:collapse;border: 1px solid #000000;}';
			$content .= 'table {border-collapse:collapse;border: 1px solid #000000;font-size:12px;}';
			$content .= 'textarea {background-color:#e1e1e1;}';
			$content .= '</style></head>';
			//$content .= '<form method="post" action="?model=' . $_GET ['model'] . '&action=edit&id=' . $_GET ['id'] . '" onsubmit="return submit_check(\'add\')">';
			$content .= $row ['content'];
			$content = str_replace ( '{user_name}', $rs['user_name'], $content );
			$content = str_replace ( '{jobs_name}', $rs['jobs_name'], $content );
			$content = str_replace ( '{cycle}', $row ['years'] . '年第' . $rs ['quarter'] . '季度', $content );
			$content = str_replace ( '{assess_user_name}', $row ['assess_user_name'], $content );
			$content = str_replace ( '{audit_user_name}', $row ['audit_user_name'], $content );
			
			//自评分
			$content = preg_replace_callback ( '/<input class="my_fraction" name="my_fraction[[][]]"(.+?)>/is', array (
																														$this, 
																														'set_edit_fraction' 
			), $content );
			//自评分说明
			$content = preg_replace_callback ( '/<textarea class="my_remark"(.+?)my_remark(.+?)>/is', array (
																							$this, 
																							'set_edit_remark' 
			), $content );
			//获取百分比
			preg_match_all ( '/<span class="percentage"(.+?)>(.+?)<\/span>/is', $content, $arr );
			$count_percentage = 0;
			if ($arr [2])
			{
				foreach ( $arr [2] as $val )
				{
					$count_percentage = $count_percentage + trim ( str_replace ( '%', '', $val ) );
				}
			}
			$content = str_replace ( '{count_percentage}', $count_percentage . '%', $content );
			//自评总分
			$content = str_replace ( '<span id="show_count_my_fraction" style="color: red">0</span>', '<span id="show_count_my_fraction" style="color: red">' . $rs ['count_my_fraction'] . '</span>', $content );
			$content = str_replace ( '<input id="count_my_fraction" name="count_my_fraction" type="hidden" />', '<input id="count_my_fraction" name="count_my_fraction" type="hidden" value="' . $rs ['count_my_fraction'] . '"/>', $content );

			//考核分
			if($rs['release_assess_status'] == 1 || $rs['assess_username'] == $_SESSION['USERNAME'])
			{
				$content = str_replace ( '<span id="show_count_assess_fraction" style="color: red">0</span>', '<span id="show_count_assess_fraction" style="color: red">' . $rs ['count_assess_fraction'] . '</span>', $content );
				$content = preg_replace_callback ( '/<input class="assess_fraction" name="assess_fraction[[][]]"(.+?)>/is', array (
																																	$this, 
																																	'set_assess_fraction' 
				), $content );
				$content = preg_replace ( '/<textarea(.+?)assess_opinion(.+?)><\/textarea>/', $rs ['assess_opinion'], $content );
				$content = str_replace ( '{average_assess_fraction}', '<span id="show_average_assess_fraction">' . $rs ['average_assess_fraction'] . '</span>', $content );
			}else{
				$content = str_replace ( '<span id="show_count_assess_fraction" style="color: red">0</span>', '&nbsp;', $content );
				$content = preg_replace( '/<input class="assess_fraction" name="assess_fraction[[][]]"(.+?)>/is', '&nbsp;' , $content );
				$content = preg_replace ( '/<textarea(.+?)assess_opinion(.+?)><\/textarea>/', '&nbsp;', $content );
				$content = str_replace ( '{average_assess_fraction}', '&nbsp;', $content );
			}
			//项目经理进度管理评价得分
			if (strpos($content,'{manager_schedule_fraction}')!==false)
			{
				if ($rs['assess_status'] == 1 && ($rs['release_assess_status'] == 1 || $rs['assess_username'] == $_SESSION['USERNAME']))
				{
					$manager = new model_administration_appraisal_evaluate_manager();
					$ManagerScheduleFraction = $manager->GetManagerScheduleFraction($rs['user_id'],$rs ['years'],$rs ['quarter']);
					$content = str_replace('{manager_schedule_fraction}',$ManagerScheduleFraction,$content);
				}else{
					$content = str_replace('{manager_schedule_fraction}','',$content);
				}
			}
			//审核分
			if($rs['release_audit_status'] == 1 || $rs['audit_username'] == $_SESSION['USERNAME'])
			{
				$content = preg_replace_callback ( '/<input class="audit_fraction" name="audit_fraction[[][]]"(.+?)>/is', array (
																																	$this, 
																																	'set_audit_fraction' 
				), $content );
				$content = str_replace ( '<span id="show_count_audit_fraction" style="color: red">0</span>', '<span id="show_count_audit_fraction" style="color: red">' . $rs ['count_audit_fraction'] . '</span>', $content );
				$content = preg_replace ( '/<textarea(.+?)audit_opinion(.+?)><\/textarea>/', $rs ['audit_opinion'], $content );
				$content = str_replace ( '{average_audit_fraction}', '<span id="show_average_audit_fraction">' . $rs ['average_audit_fraction'] . '</span>', $content );
			}else{
				$content = str_replace ( '<span id="show_count_audit_fraction" style="color: red">0</span>', '&nbsp;', $content );
				$content = preg_replace( '/<input class="audit_fraction" name="audit_fraction[[][]]"(.+?)>/is', '&nbsp',$content);
				$content = preg_replace ( '/<textarea(.+?)audit_opinion(.+?)><\/textarea>/', '&nbsp', $content );
				$content = str_replace ( '{average_audit_fraction}', '&nbsp', $content );
			}
			//评价
			$content = preg_replace ( '/<input class="evaluate_fraction" name="evaluate_fraction(.+?)>/', '&nbsp;', $content ); //关闭评介人评分框
			$content = preg_replace('/<textarea class="evaluate_remark"(.+?)<\/textarea>/is','&nbsp;', $content);
			//附件
			$content = str_replace('<input type="file" name="upfile[]" onchange="file_input(this);" type="file" />',$this->file_list($rs),$content);
			$content = str_replace('<input name="upfile[]" onchange="file_input(this);" type="file" />',$this->file_list($rs),$content);
			//被评价人意见
			$content = preg_replace ( '/<textarea(.+?)my_opinion(.+?)><\/textarea>/', $rs ['my_opinion'], $content );
			//提交及关闭按钮
			$content .= '<div style="text-align:center;"><input type="button" onclick="window.close();" value=" 关闭返回 " /></div>';
			$content .= '</form></html>';
			echo $content;
		} else
		{
			showmsg ( '非法请求！', 'parent.location.reload();window.close()', 'button' );
		}
	}
	/**
	 * 查看评价
	 */
	function c_show_evaluate()
	{
		$rs = $this->GetOneInfo ( 'a.id=' . $_GET ['id'] );
		$list_id = $_GET['list_id'] ? $_GET['list_id'] : $_POST['list_id'];
		$list_obj = new model_administration_appraisal_evaluate_index();
		$list_obj->tbl_name = 'appraisal_evaluate_list';
		$info = $list_obj->GetOneInfo(array('id'=>$list_id));
		
		$evaluators_userid = $_GET['evaluators_userid'] ? $_GET['evaluators_userid'] : $_POST['evaluators_userid'];
		$evaluators = $_GET['evaluators'] ? $_GET['evaluators'] : $_POST['evaluators'];
		$sort = $_GET['sort'] ? $_GET['sort'] : $_POST['sort'];
		if ($info['evaluators_userid'] != $_SESSION['USER_ID'] && $rs['assess_username']!=$_SESSION['USERNAME'] && $rs['audit_username']!=$_SESSION['USERNAME'])
		{
			$evaluators = '评价人'.$sort;
		}
		$ev =  $info['evaluators_userid'] == $_SESSION['USER_ID'] ? true : false;
		if ($rs)
		{
			$tpl_obj = new model_administration_appraisal_performance_item ();
			$row = $tpl_obj->GetOneData ( 'a.id=' . $rs ['tpl_id'] );
			$content = '<html><title>' . $row ['name'] . '</title><head>';
			$content .= '<meta http-equiv="Content-Type" content="text/html; charset=GBK">';
			$content .= '<script type="text/javascript" src="js/jqeasyui/jquery.min.js"></script>';
			$content .= '<script type="text/javascript" src="js/preformance.js"></script>';
			$content .= '<style type="text/css">';
			$content .= ' input[type=text] { background-color:#e1e1e1; }';
			$content .= 'td {border-collapse:collapse;border: 1px solid #000000;}';
			$content .= 'table {border-collapse:collapse;border: 1px solid #000000;font-size:12px;}';
			$content .= 'textarea {background-color:#e1e1e1;}';
			$content .= '</style></head>';
			$content .= '<div style="width:100%;text-align:center;color:red;"><h2>下面的评价由：'.$evaluators.' 提交</h2></div>';
			$content .= $row ['content'];
			$content = str_replace ( '{user_name}', $rs['user_name'], $content );
			$content = str_replace ( '{jobs_name}', $rs['jobs_name'], $content );
			$content = str_replace ( '{cycle}', $row ['years'] . '年第' . $row ['quarter'] . '季度', $content );
			$content = str_replace ( '{assess_user_name}', $row ['assess_user_name'], $content );
			$content = str_replace ( '{audit_user_name}', $row ['audit_user_name'], $content );
			
			/*if ($ev || ($rs['assess_username']==$_SESSION['USERNAME'] && $rs['release_assess_status']!=1))
			{//评价人
				$content = preg_replace ( '/<input class="my_fraction" name="my_fraction[[][]]"(.+?)>/is','&nbsp;',$content);
				$content = preg_replace('/<textarea class="my_remark"(.+?)my_remark(.+?)>/is','&nbsp;',$content);
				$content = str_replace ( '<span id="show_count_my_fraction" style="color: red">0</span>', '&nbsp;', $content );
				$content = str_replace ( '<input id="count_my_fraction" name="count_my_fraction" type="hidden" />', '&nbsp;', $content );
			}else if ($rs['assess_username']==$_SESSION['USERNAME'] && $rs['release_assess_status']==1)
			{//考核人
				
			}else if ($rs['audit_username']==$_SESSION['USERNAME'])
			{//审核人
				
			}else
			{//其他人
				
			}*/
			
			if ($ev || ($rs['assess_username']==$_SESSION['USERNAME'] && $rs['release_assess_status']!=1))
			{
				$content = preg_replace ( '/<input class="my_fraction" name="my_fraction[[][]]"(.+?)>/is','&nbsp;',$content);
				$content = preg_replace('/<textarea class="my_remark"(.+?)my_remark(.+?)>/is','&nbsp;',$content);
				$content = str_replace ( '<span id="show_count_my_fraction" style="color: red">0</span>', '&nbsp;', $content );
				$content = str_replace ( '<input id="count_my_fraction" name="count_my_fraction" type="hidden" />', '&nbsp;', $content );
			}else{
				$content = str_replace ( '<span id="show_count_my_fraction" style="color: red">0</span>', '<span id="show_count_my_fraction" style="color: red">' . ($ev ? '':$rs ['count_my_fraction']) . '</span>', $content );
				$content = str_replace ( '<input id="count_my_fraction" name="count_my_fraction" type="hidden" />', '<input id="count_my_fraction" name="count_my_fraction" type="hidden" value="' . ($ev ? '' : $rs ['count_my_fraction']) . '"/>', $content );
				$content = preg_replace_callback ( '/<input class="my_fraction" name="my_fraction[[][]]"(.+?)>/is', array (
																															$this, 
																															'set_edit_fraction' 
				), $content );
				$content = preg_replace_callback ( '/<textarea class="my_remark"(.+?)my_remark(.+?)>/is', array (
																								$this, 
																								'set_edit_remark' 
				), $content );
			}
			preg_match_all ( '/<span class="percentage"(.+?)>(.+?)<\/span>/is', $content, $arr );
			$count_percentage = 0;
			if ($arr [2])
			{
				foreach ( $arr [2] as $val )
				{
					$count_percentage = $count_percentage + trim ( str_replace ( '%', '', $val ) );
				}
			}
			$content = str_replace ( '{count_percentage}', $count_percentage . '%', $content );
			if ($rs['user_id'] == $_SESSION['USER_ID'])
			{
				$content = str_replace ( '<span id="show_count_evaluate_fraction" style="color: red">0</span>', '<span id="show_count_evaluate_fraction" style="color: red">&nbsp;</span>', $content );
			}else{
				$content = str_replace ( '<span id="show_count_evaluate_fraction" style="color: red">0</span>', '<span id="show_count_evaluate_fraction" style="color: red">' . $info['count_fraction'] . '</span>', $content );
			}
			
			
			
			if ($ev)
			{
				$content = preg_replace('/<input class="assess_fraction" name="assess_fraction[[][]]"(.+?)>/is','&nbsp',$content);
				$content = preg_replace('/<input class="audit_fraction" name="audit_fraction[[][]]"(.+?)>/is','&nbsp;',$content);
				$content = str_replace ( '{average_assess_fraction}', '&nbsp;', $content );
				$content = preg_replace ( '/<textarea(.+?)assess_opinion(.+?)><\/textarea>/', '&nbsp;', $content );
				$content = str_replace ( '<span id="show_count_assess_fraction" style="color: red">0</span>', '&nbsp;', $content );
				$content = str_replace ( '{average_audit_fraction}','&nbsp;',$content);
				$content = str_replace ( '<span id="show_count_audit_fraction" style="color: red">0</span>', '&nbsp;', $content );
				$content = preg_replace ( '/<textarea(.+?)audit_opinion(.+?)><\/textarea>/', '&nbsp;', $content );
			}else{
				//考核分
				if($rs['release_assess_status'] == 1 || $rs['assess_username'] == $_SESSION['USERNAME'])
				{
					$content = preg_replace_callback ( '/<input class="assess_fraction" name="assess_fraction[[][]]"(.+?)>/is', array ($this,'set_assess_fraction'), $content );
					$content = str_replace ( '<span id="show_count_assess_fraction" style="color: red">0</span>', '<span id="show_count_assess_fraction" style="color: red">' . ($ev ? '' : $rs ['count_assess_fraction']) . '</span>', $content );
					$content = str_replace ( '{average_assess_fraction}', '<span id="show_average_assess_fraction">' . ($ev ? '' : $rs ['average_assess_fraction']) . '</span>', $content );
					$content = preg_replace ( '/<textarea(.+?)assess_opinion(.+?)><\/textarea>/', ($ev ? '' : $rs ['assess_opinion']), $content );
				}else{
					$content = preg_replace ( '/<input class="assess_fraction" name="assess_fraction[[][]]"(.+?)>/is','&nbsp',$content);
					$content = str_replace ( '<span id="show_count_assess_fraction" style="color: red">0</span>', '&nbsp;', $content );
					$content = str_replace ( '{average_assess_fraction}', '&nbsp;', $content );
					$content = preg_replace ( '/<textarea(.+?)assess_opinion(.+?)><\/textarea>/', '&nbsp;', $content );
				}
				//审核
				if(($rs['release_audit_status'] == 1 || $rs['audit_username'] == $_SESSION['USERNAME'])&& ($rs['assess_username'] != $_SESSION['USERNAME']))
				{
					$content = preg_replace_callback ( '/<input class="audit_fraction" name="audit_fraction[[][]]"(.+?)>/is', array ($this,'set_audit_fraction'), $content );
					$content = str_replace ( '{average_audit_fraction}', '<span id="show_average_audit_fraction">' .($ev ? '' : $rs ['average_audit_fraction']) . '</span>', $content );
					$content = str_replace ( '<span id="show_count_audit_fraction" style="color: red">0</span>', '<span id="show_count_audit_fraction" style="color: red">' . ($ev ? '' : $rs ['count_audit_fraction']) . '</span>', $content );
					$content = preg_replace ( '/<textarea(.+?)audit_opinion(.+?)><\/textarea>/', ($ev ? '' : $rs ['audit_opinion']), $content );
				}else{
					$content = preg_replace( '/<input class="audit_fraction" name="audit_fraction[[][]]"(.+?)>/is','&nbsp;',$content);
					$content = str_replace ( '{average_audit_fraction}','&nbsp;',$content);
					$content = str_replace ( '<span id="show_count_audit_fraction" style="color: red">0</span>', '&nbsp;', $content );
					$content = preg_replace ( '/<textarea(.+?)audit_opinion(.+?)><\/textarea>/', '&nbsp;', $content );
				}
			}
			if (strpos($content,'{manager_schedule_fraction}')!==false)
			{
				if ($rs['assess_status'] == 1)
				{
					$manager = new model_administration_appraisal_evaluate_manager();
					$ManagerScheduleFraction = $manager->GetManagerScheduleFraction($rs['user_id'],$rs ['years'],$rs ['quarter']);
					$content = str_replace('{manager_schedule_fraction}',$ManagerScheduleFraction,$content);
				}else{
					$content = str_replace('{manager_schedule_fraction}','',$content);
				}
			}
			if ($rs['user_id'] == $_SESSION['USER_ID'] &!$ev)
			{
				$content = preg_replace ( '/<input class="evaluate_fraction" name="evaluate_fraction(.+?)>/', '&nbsp;', $content ); //关闭评介人评分框
			}else{
				$content = preg_replace_callback ( '/<input class="evaluate_fraction" name="evaluate_fraction(.+?)>/', array($this,'set_Evaluate_fraction'), $content );
			}
			$content = preg_replace_callback('/<textarea class="evaluate_remark"(.+?)<\/textarea>/is',array($this,'set_Evaluate_remark'), $content);
			$content = str_replace('<input type="file" name="upfile[]" onchange="file_input(this);" value="" />',($ev ? '' : $this->file_list($rs)),$content);
			$content = str_replace('<input name="upfile[]" onchange="file_input(this);" type="file" />',$this->file_list($rs),$content);
			
			$content = preg_replace ( '/<textarea(.+?)my_opinion(.+?)><\/textarea>/', ($ev ? '' : $rs ['my_opinion']), $content );
			
			$content .= '<div style="text-align:center;"><input type="button" onclick="window.close();" value=" 关闭返回 " /></div>';
			$content .= '</form></html>';
			echo $content;
		}else{
			showmsg('非法访问！');
		}
		
		
	}
	/**
	 * 修改自评
	 */
	function c_edit()
	{
		if ($_POST)
		{
			if ($this->Edit ( $_POST, $_GET ['id'] ))
			{
				showmsg ( '操作成功！', 'opener.location.reload();window.close()', 'button' );
			} else
			{
				showmsg ( '操作失败，请与管理员联系！', 'parent.location.reload();window.close()', 'button' );
			}
		} else if ($_GET ['id'])
		{
			$rs = $this->GetOneInfo ( 'a.id=' . $_GET ['id'] );
			if ($rs)
			{
				$tpl_obj = new model_administration_appraisal_performance_item ();
				$row = $tpl_obj->GetOneData ( 'a.id=' . $rs ['tpl_id'] );
				$content = '<html><title>' . $row ['name'] . '</title><head>';
				$content .= '<meta http-equiv="Content-Type" content="text/html; charset=GBK">';
				$content .= '<script type="text/javascript" src="js/jqeasyui/jquery.min.js"></script>';
				$content .= '<script type="text/javascript" src="js/preformance.js"></script>';
				$content .= '<style type="text/css">';
				$content .= ' input[type=text] { background-color:#e1e1e1; }';
				$content .= 'td {border-collapse:collapse;border: 1px solid #000000;}';
				$content .= 'table {border-collapse:collapse;border: 1px solid #000000;font-size:12px;}';
				$content .= 'textarea {background-color:#e1e1e1;}';
				$content .= '</style></head>';
				$content .= '<form method="post" action="?model=' . $_GET ['model'] . '&action=edit&id=' . $_GET ['id'] . '" enctype="multipart/form-data" onsubmit="return submit_check(\'add\')">';
				$content .= $row ['content'];
				$content = str_replace ( '{user_name}', $rs['user_name'], $content );
				$content = str_replace ( '{jobs_name}', $rs['jobs_name'], $content );
				$content = str_replace ( '{cycle}', $row ['years'] . '年第' . $rs ['quarter'] . '季度', $content );
				$content = str_replace ( '{assess_user_name}', $row ['assess_user_name'], $content );
				$content = str_replace ( '{audit_user_name}', $row ['audit_user_name'], $content );
				$content = str_replace ( '{average_assess_fraction}', '&nbsp;', $content );
				$content = str_replace ( '{average_audit_fraction}', '&nbsp;', $content );
				$content = preg_replace_callback ( '/<input class="my_fraction" name="my_fraction[[][]]"(.+?)>/is', array (
																															$this, 
																															'set_edit_fraction' 
				), $content );
				$content = preg_replace_callback ( '/<textarea(.+?)my_remark(.+?)>/is', array (
																								$this, 
																								'set_edit_remark' 
				), $content );
				preg_match_all ( '/<span class="percentage"(.+?)>(.+?)<\/span>/is', $content, $arr );
				$count_percentage = 0;
				if ($arr [2])
				{
					foreach ( $arr [2] as $val )
					{
						$count_percentage = $count_percentage + trim ( str_replace ( '%', '', $val ) );
					}
				}
				$content = str_replace ( '{count_percentage}', $count_percentage . '%', $content );
				$content = str_replace ( '<span id="show_count_my_fraction" style="color: red">0</span>', '<span id="show_count_my_fraction" style="color: red">' . $rs ['count_my_fraction'] . '</span>', $content );
				$content = str_replace ( '<input id="count_my_fraction" name="count_my_fraction" type="hidden" />', '<input id="count_my_fraction" name="count_my_fraction" type="hidden" value="' . $rs ['count_my_fraction'] . '"/>', $content );
				$content = str_replace ( '<span id="show_count_assess_fraction" style="color: red">0</span>', '<span id="show_count_assess_fraction" style="color: red">' . $rs ['count_assess_fraction'] . '</span>', $content );
				
				$content = preg_replace ( '/<input class="evaluate_fraction" name="evaluate_fraction(.+?)>/', '&nbsp;', $content ); //关闭评介人评分框
				$content = preg_replace('/<textarea class="evaluate_remark"(.+?)<\/textarea>/is','&nbsp;', $content);
				$content = preg_replace ( '/<input class="assess_fraction" name="assess(.+?)>/', '&nbsp;', $content ); //关闭考核人评分框
				if (strpos($content,'{manager_schedule_fraction}')!==false)
				{
					if ($rs['assess_status'] == 1)
					{
						$manager = new model_administration_appraisal_evaluate_manager();
						$ManagerScheduleFraction = $manager->GetManagerScheduleFraction($rs['user_id'],$rs ['years'],$rs ['quarter']);
						$content = str_replace('{manager_schedule_fraction}',$ManagerScheduleFraction,$content);
					}else{
						$content = str_replace('{manager_schedule_fraction}','',$content);
					}
				}
				$content = preg_replace ( '/<input class="audit_fraction" name="audit(.+?)>/', '&nbsp;', $content ); //关闭审核人评分框
				$content = preg_replace ( '/<textarea(.+?)opinion(.+?)>/', '&nbsp;', $content ); //关闭意见填写框
				if ($rs['filename_str'])
				{
					$arr = explode(',',$rs['filename_str']);
					$str = '';
					if ($arr)
					{
						foreach ($arr as $val)
						{
							$str .='<input type="checkbox" name="filename[]" value="'.$val.'" /><a href="'.PERFORMANCE_FILE_DIR.$rs['file_path'].'/'.$val.'" target="_blank">'.$val.'</a>';
						}
					}
					$content = str_replace('<span><input name="upfile[]"','<span id="file_list">已上传附件：'.$str.' <input type="button" onclick="del_file('.$rs['id'].')" value=" 删除选中附件 " /></span><br /><span><input name="upfile[]"',$content);
				}
				
				$content .= '<div style="text-align:center;"><input type="submit" value=" 确定提交 " /> &nbsp;&nbsp; <input type="button" onclick="window.close();" value=" 关闭返回 " /></div>';
				$content .= '</form></html>';
				echo $content;
			} else
			{
				showmsg ( '非法请求！', 'parent.location.reload();window.close()', 'button' );
			}
		}
	}
	/**
	 * 删除
	 */
	function c_del()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($id)
		{
			$info = $this->GetOneInfo('a.id='.$id);
			if ($info)
			{
				if ($this->Del($id))
				{
					echo 1;
				}else{
					echo -1;
				}
			}else{
				echo -1;
			}
		}else{
			echo -1;
		}
	}
	/**
	 * 删除附件
	 */
	function c_del_file()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		$rs = $this->GetOneInfo ( 'a.id='.$id );
		if ($rs)
		{
			$file_name = $_POST['file_name'] ? explode(',',trim(mb_iconv($_POST['file_name']),',')) : array();
			if ($file_name)
			{
				$temp = array_diff(explode(',',$rs['filename_str']),$file_name);
				$this->update_filename(array('filename_str'=>($temp ? trim(implode(',',$temp),','):'')),$id);
				foreach ($file_name as $val)
				{
					@unlink(WEB_TOR . PERFORMANCE_FILE_DIR.$rs['file_path'].'/'.$val);
				}
				if ($temp)
				{
					$str = '已上传附件：';
					foreach ($temp as $val)
					{
						$str .='<input type="checkbox" name="filename[]" value="'.$val.'" /><a href="'.PERFORMANCE_FILE_DIR.$rs['file_path'].'/'.$val.'" target="_blank">'.$val.'</a>';
					}
					$str .='  <input type="button" onclick="del_file('.$rs['id'].')" value=" 删除选中附件 " />';
					echo un_iconv($str);
				}
			}
			
		}
	}
	/**
	 * 提交意见
	 */
	function c_my_opinion()
	{
		if ($_POST)
		{
			if ($_GET ['id'])
			{
				if ($_POST['my_opinion'])
				{
					if ($this->UpdateOpinion($_GET['id'],$_POST['my_opinion']))
					{
						showmsg ( '提交意见成功！', 'opener.location.reload();window.close()', 'button' );
					} else
					{
						showmsg ( '提交失败，请与管理员联系！', 'parent.location.reload();window.close()', 'button' );
					}
				}else{
					showmsg('请填写意见内容！');
				}
			}else{
				showmsg('非法访问！');
			}
		}else if ($_GET ['id']){
		$rs = $this->GetOneInfo ( 'a.id=' . $_GET ['id'] );
		if ($rs)
		{
			$tpl_obj = new model_administration_appraisal_performance_item ();
			$row = $tpl_obj->GetOneData ( 'a.id=' . $rs ['tpl_id'] );
			$content = '<html><title>' . $row ['name'] . '</title><head>';
			$content .= '<meta http-equiv="Content-Type" content="text/html; charset=GBK">';
			$content .= '<script type="text/javascript" src="js/jqeasyui/jquery.min.js"></script>';
			$content .= '<script type="text/javascript" src="js/preformance.js"></script>';
			$content .= '<style type="text/css">';
			$content .= ' input[type=text] { background-color:#e1e1e1; }';
			$content .= 'td {border-collapse:collapse;border: 1px solid #000000;}';
			$content .= 'table {border-collapse:collapse;border: 1px solid #000000;font-size:12px;}';
			$content .= 'textarea {background-color:#e1e1e1;}';
			$content .= '</style></head>';
			$content .= '<form method="post" action="?model=' . $_GET ['model'] . '&action=my_opinion&id=' . $_GET ['id'] . '" onsubmit="return submit_check(\'opinion\')">';
			$content .= $row ['content'];
			$content = str_replace ( '{user_name}', $rs['user_name'], $content );
			$content = str_replace ( '{jobs_name}', $rs['jobs_name'], $content );
			$content = str_replace ( '{cycle}', $row ['years'] . '年第' . $rs ['quarter'] . '季度', $content );
			$content = str_replace ( '{assess_user_name}', $row ['assess_user_name'], $content );
			$content = str_replace ( '{audit_user_name}', $row ['audit_user_name'], $content );
			
			$content = preg_replace_callback ( '/<input class="my_fraction" name="my_fraction[[][]]"(.+?)>/is', array (
																														$this, 
																														'set_edit_fraction' 
			), $content );
			$content = preg_replace_callback ( '/<textarea class="my_remark"(.+?)my_remark(.+?)>/is', array (
																							$this, 
																							'set_edit_remark' 
			), $content );
			preg_match_all ( '/<span class="percentage"(.+?)>(.+?)<\/span>/is', $content, $arr );
			$count_percentage = 0;
			if ($arr [2])
			{
				foreach ( $arr [2] as $val )
				{
					$count_percentage = $count_percentage + trim ( str_replace ( '%', '', $val ) );
				}
			}
			$content = str_replace ( '{count_percentage}', $count_percentage . '%', $content );
			$content = str_replace ( '<span id="show_count_my_fraction" style="color: red">0</span>', '<span id="show_count_my_fraction" style="color: red">' . $rs ['count_my_fraction'] . '</span>', $content );
			$content = str_replace ( '<input id="count_my_fraction" name="count_my_fraction" type="hidden" />', '<input id="count_my_fraction" name="count_my_fraction" type="hidden" value="' . $rs ['count_my_fraction'] . '"/>', $content );
			
			//考核分
			if($rs['release_assess_status'] == 1 || $rs['assess_username'] == $_SESSION['USERNAME'])
			{
				$content = str_replace ( '<span id="show_count_assess_fraction" style="color: red">0</span>', '<span id="show_count_assess_fraction" style="color: red">' . $rs ['count_assess_fraction'] . '</span>', $content );
				$content = preg_replace_callback ( '/<input class="assess_fraction" name="assess_fraction[[][]]"(.+?)>/is', array (
																																	$this, 
																																	'set_assess_fraction' 
				), $content );
				$content = preg_replace ( '/<textarea(.+?)assess_opinion(.+?)><\/textarea>/', $rs ['assess_opinion'], $content );
				$content = str_replace ( '{average_assess_fraction}', '<span id="show_average_assess_fraction">' . $rs ['average_assess_fraction'] . '</span>', $content );
			}else{
				$content = str_replace ( '<span id="show_count_assess_fraction" style="color: red">0</span>', '&nbsp;', $content );
				$content = preg_replace( '/<input class="assess_fraction" name="assess_fraction[[][]]"(.+?)>/is', '&nbsp;' , $content );
				$content = preg_replace ( '/<textarea(.+?)assess_opinion(.+?)><\/textarea>/', '&nbsp;', $content );
				$content = str_replace ( '{average_assess_fraction}', '&nbsp;', $content );
			}
			
			//项目经理进度管理评价得分
			if (strpos($content,'{manager_schedule_fraction}')!==false)
			{
				if ($rs['assess_status'] == 1 && ($rs['release_assess_status'] == 1 || $rs['assess_username'] == $_SESSION['USERNAME']))
				{
					$manager = new model_administration_appraisal_evaluate_manager();
					$ManagerScheduleFraction = $manager->GetManagerScheduleFraction($rs['user_id'],$rs ['years'],$rs ['quarter']);
					$content = str_replace('{manager_schedule_fraction}',$ManagerScheduleFraction,$content);
				}else{
					$content = str_replace('{manager_schedule_fraction}','',$content);
				}
			}
			//审核分
			if(($rs['release_audit_status'] == 1 || $rs['audit_username'] == $_SESSION['USERNAME']) && ($rs['assess_username'] != $_SESSION['USERNAME']))
			{
				$content = preg_replace_callback ( '/<input class="audit_fraction" name="audit_fraction[[][]]"(.+?)>/is', array (
																																	$this, 
																																	'set_audit_fraction' 
				), $content );
				$content = str_replace ( '<span id="show_count_audit_fraction" style="color: red">0</span>', '<span id="show_count_audit_fraction" style="color: red">' . $rs ['count_audit_fraction'] . '</span>', $content );
				$content = preg_replace ( '/<textarea(.+?)audit_opinion(.+?)><\/textarea>/', $rs ['audit_opinion'], $content );
				$content = str_replace ( '{average_audit_fraction}', '<span id="show_average_audit_fraction">' . $rs ['average_audit_fraction'] . '</span>', $content );
			}else{
				$content = str_replace ( '<span id="show_count_audit_fraction" style="color: red">0</span>', '&nbsp;', $content );
				$content = preg_replace( '/<input class="audit_fraction" name="audit_fraction[[][]]"(.+?)>/is', '&nbsp',$content);
				$content = preg_replace ( '/<textarea(.+?)audit_opinion(.+?)><\/textarea>/', '&nbsp', $content );
				$content = str_replace ( '{average_audit_fraction}', '&nbsp', $content );
			}
			
			$content = preg_replace ( '/<input class="evaluate_fraction" name="evaluate_fraction(.+?)>/', '&nbsp;', $content ); //关闭评介人评分框
			$content = preg_replace('/<textarea class="evaluate_remark"(.+?)<\/textarea>/is','&nbsp;', $content);
			$content = str_replace('<input type="file" name="upfile[]" onchange="file_input(this);" value="" />',$this->file_list($rs),$content);
			$content = str_replace('<input name="upfile[]" onchange="file_input(this);" type="file" />',$this->file_list($rs),$content);
			$content .= '<div style="text-align:center;"><input type="submit" value=" 确定提交 " /> &nbsp;&nbsp; <input type="button" onclick="window.close();" value=" 关闭返回 " /></div>';
			$content .= '</form></html>';
			echo $content;
		} else
		{
			showmsg ( '非法请求！', 'parent.location.reload();window.close()', 'button' );
		}
		}
	}
	/**
	 * 考核
	 */
	function c_assess()
	{
		if ($_POST)
		{
			if ($this->assess ( $_GET ['id'], $_POST ))
			{
				showmsg ( '提交考核成功！', 'opener.location.reload();window.close()', 'button' );
			} else
			{
				showmsg ( '提交考核失败，请与管理员联系！', 'parent.location.reload();window.close()', 'button' );
			}
		} else
		{
			$rs = $this->GetOneInfo ( 'a.id=' . $_GET ['id'] );
			if ($rs)
			{
				$tpl_obj = new model_administration_appraisal_performance_item ();
				$row = $tpl_obj->GetOneData ( 'a.id=' . $rs ['tpl_id'] );
				$content = '<html><title>' . $row ['name'] . '</title><head>';
				$content .= '<meta http-equiv="Content-Type" content="text/html; charset=GBK">';
				$content .= '<script type="text/javascript" src="js/jqeasyui/jquery.min.js"></script>';
				$content .= '<script type="text/javascript" src="js/preformance.js"></script>';
				$content .= '<style type="text/css">';
				$content .= ' input[type=text] { background-color:#e1e1e1; }';
				$content .= 'td {border-collapse:collapse;border: 1px solid #000000;}';
				$content .= 'table {border-collapse:collapse;border: 1px solid #000000;font-size:12px;}';
				$content .= 'textarea {background-color:#e1e1e1;}';
				$content .= '</style></head>';
				$content .= '<form method="post" action="?model=' . $_GET ['model'] . '&action=assess&id=' . $_GET ['id'] . '" onsubmit="return submit_check(\'assess\')">';
				$content .= $row ['content'];
				$content = str_replace ( '{user_name}', $rs['user_name'], $content );
				$content = str_replace ( '{jobs_name}', $rs['jobs_name'], $content );
				$content = str_replace ( '{cycle}', $row ['years'] . '年第' . $rs ['quarter'] . '季度', $content );
				$content = str_replace ( '{assess_user_name}', $row ['assess_user_name'], $content );
				$content = str_replace ( '{audit_user_name}', $row ['audit_user_name'], $content );
				$content = str_replace ( '{average_assess_fraction}', '<span id="show_average_assess_fraction">' . $rs ['average_assess_fraction'] . '</span>', $content );
				$content = str_replace ( '<input id="average_assess_fraction_value" name="average_assess_fraction" type="hidden" />', '<input id="average_assess_fraction_value" name="average_assess_fraction" type="hidden" value="' . $rs ['average_assess_fraction'] . '"/>', $content );
				$content = str_replace ( '{average_audit_fraction}', '<span id="show_average_audit_fraction">' . $rs ['average_audit_fraction'] . '</span>', $content );
				
				$content = preg_replace ( '/<input class="my_fraction" name="my_fraction[[][]]"(.+?)>/is','&nbsp;',$content);
				$content = preg_replace ( '/<textarea class="my_remark"(.+?)my_remark(.+?)><\/textarea>/is','&nbsp;',$content);
				/**屏蔽考核人不能查看被考核人的自评分
				$content = preg_replace_callback ( '/<input class="my_fraction" name="my_fraction[[][]]"(.+?)>/is', array (
																															$this, 
																															'set_edit_fraction' 
				), $content );
				$content = preg_replace_callback ( '/<textarea class="my_remark"(.+?)my_remark(.+?)><\/textarea>/is', array (
																											$this, 
																											'set_edit_remark' 
				), $content );
				*/
				preg_match_all ( '/<span class="percentage"(.+?)>(.+?)<\/span>/is', $content, $arr );
				$count_percentage = 0;
				if ($arr [2])
				{
					foreach ( $arr [2] as $val )
					{
						$count_percentage = $count_percentage + trim ( str_replace ( '%', '', $val ) );
					}
				}
				$content = str_replace ( '{count_percentage}', $count_percentage . '%', $content );
				
				$content = str_replace ( '<span id="show_count_my_fraction" style="color: red">0</span>','&nbsp;',$content);
				//$content = str_replace ( '<span id="show_count_my_fraction" style="color: red">0</span>', '<span id="show_count_my_fraction" style="color: red">' . $rs ['count_my_fraction'] . '</span>', $content );
				
				$content = str_replace ( '<span id="show_count_assess_fraction" style="color: red">0</span>', '<span id="show_count_assess_fraction" style="color: red">' . $rs ['count_assess_fraction'] . '</span>', $content );
				$content = str_replace ( '<input id="count_assess_fraction" name="count_assess_fraction" type="hidden" />', '<input id="count_assess_fraction" name="count_assess_fraction" type="hidden" value="' . $rs ['count_assess_fraction'] . '" />', $content );
				if ($rs['assess_status'] == 1 && strpos($content,'{manager_schedule_fraction}')!==false)
				{
					$content = str_replace('{manager_schedule_fraction}','<input class="assess_fraction" name="assess_fraction[]" type="hidden"/>',$content);
				}
				$content = preg_replace_callback ( '/<input class="assess_fraction" name="assess_fraction[[][]]"(.+?)>/is', array (
																																	$this, 
																																	'set_assess_fraction' 
				), $content );
				//如果是修改审核则替换项目经理的进度管理评分
				if ($rs['assess_status'] != 1)
				{
					$content = preg_replace('/<input class="assess_fraction" name="assess_fraction[[](.+?)[]]" value="(.+?)" type="hidden"\/>/','<input class="assess_fraction" name="assess_fraction[$1]" value="$2" type="hidden" />$2',$content);
				}
				if ($rs['assess_status']!=1 && strpos($content,'{manager_schedule_fraction}')!==false)
				{
					$manager = new model_administration_appraisal_evaluate_manager();
					$ManagerScheduleFraction = $manager->GetManagerScheduleFraction($rs['user_id'],$rs ['years'],$rs ['quarter']);
					if ($ManagerScheduleFraction == 0)
					{
						$content = '<div><h2>对不起，项目经理进度管理评分尚未有成员提交,待项目成员提交评分后方可进行考核！</h2></div>';
						
						$data = $manager->getMemberByManager($rs['user_id'],$rs ['years'],$rs ['quarter']);
						$members = array();
						if(trim($data['all']['developer']) != "" and stripos($data['all']['developer'], '\,') !== false){
							$members = spliti(',', $data['all']['developer']);
						}else{
							$members[] = $data['all']['developer'];
						}
						$notIn = array();
						foreach ($members as $member){
							if(!in_array($member, $data['in'])){
								$notIn[] = $member;
							}
						}
						if(count($notIn) > 0){
							$content .= "<table border ='0' cellpadding='0' cellspacing='0' >";
							$content .= "<tr><td>未提交成员有：</td></tr>";
							foreach($notIn as $notInMember){
								$content .= "<tr>"
										. "<td>".$manager->getManagerRealName($notInMember)."</td>"
										."</tr>";
							}
							
							$content .= "</table>";
						}
						
					}else{
						$content = str_replace('{manager_schedule_fraction}','<input class="assess_fraction" name="assess_fraction[]" value="'.$ManagerScheduleFraction.'"  type="hidden" />'.$ManagerScheduleFraction,$content);
					}
				}
				$content = preg_replace ( '/<input class="evaluate_fraction" name="evaluate_fraction(.+?)>/', '&nbsp;', $content ); //关闭评介人评分框
				$content = preg_replace('/<textarea class="evaluate_remark"(.+?)<\/textarea>/is','&nbsp;', $content);
				$content = str_replace('<input name="upfile[]" onchange="file_input(this);" type="file" />',$this->file_list($rs),$content);
				$content = preg_replace ( '/<input class="audit_fraction" name="audit(.+?)>/', '&nbsp;', $content ); //关闭审核人评分框
				$content = preg_replace ( '/<textarea class="assess_opinion"(.+?)><\/textarea>/', '<textarea class="assess_opinion"${1}>' . $rs ['assess_opinion'] . '</textarea>', $content );
				$content = preg_replace ( '/<textarea(.+?)my_opinion(.+?)><\/textarea>/', '&nbsp;', $content ); //关闭意见填写框
				$content = preg_replace ( '/<textarea(.+?)audit_opinion(.+?)><\/textarea>/', '&nbsp;', $content ); //关闭意见填写框
				$content .= '<div style="text-align:center;"><input type="submit" value=" 确定提交 " /> &nbsp;&nbsp; <input type="button" onclick="window.close();" value=" 关闭返回 " /></div>';
				$content .= '</form></html>';
				echo $content;
			} else
			{
				showmsg ( '非法请求！', 'parent.location.reload();window.close()', 'button' );
			}
		}
	}
	/**
	 * 发布结果
	 */
	function c_release_result()
	{
		$user_id = $_SESSION['USER_ID'];
		$typeid = $_GET['typeid'] ? $_GET['typeid'] : $_POST['typeid'];
		$years = $_GET['years'] ? $_GET['years'] : $_POST['years'];
		$quarter = $_GET ['quarter'] ? $_GET ['quarter'] : $_POST ['quarter'];
		$status = $_GET['status'] ? $_GET['status'] : $_POST['status'];
		$years = $years ? $years : $this->last_quarter_year;
		$quarter = $quarter ? $quarter : $this->last_quarter;
		if ($this->release_result($user_id,$years,$quarter,$typeid,$status))
		{
			echo 1;
		}else{
			echo 2;
		}
		
	}
	/**
	 * 审核
	 */
	function c_audit()
	{
		if ($_POST)
		{
			if ($this->audit ( $_GET ['id'], $_POST ))
			{
				showmsg ( '提交审核成功！', 'opener.location.reload();window.close()', 'button' );
			} else
			{
				showmsg ( '提交审核失败，请与管理员联系！', 'parent.location.reload();window.close()', 'button' );
			}
		} else
		{
			$rs = $this->GetOneInfo ( 'a.id=' . $_GET ['id'] );
			if ($rs)
			{
				$tpl_obj = new model_administration_appraisal_performance_item ();
				$row = $tpl_obj->GetOneData ( 'a.id=' . $rs ['tpl_id'] );
				$content = '<html><title>' . $row ['name'] . '</title><head>';
				$content .= '<meta http-equiv="Content-Type" content="text/html; charset=gbk">';
				$content .= '<script type="text/javascript" src="js/jqeasyui/jquery.min.js"></script>';
				$content .= '<script type="text/javascript" src="js/preformance.js"></script>';
				$content .= '<style type="text/css">';
				$content .= ' input[type=text] { background-color:#e1e1e1; }';
				$content .= 'td {border-collapse:collapse;border: 1px solid #000000;}';
				$content .= 'table {border-collapse:collapse;border: 1px solid #000000;font-size:12px;}';
				$content .= 'textarea {background-color:#e1e1e1;}';
				$content .= '</style></head>';
				$content .= '<form method="post" action="?model=' . $_GET ['model'] . '&action=audit&id=' . $_GET ['id'] . '" onsubmit="return submit_check(\'audit\')">';
				$content .= $row ['content'];
				$content = str_replace ( '{user_name}', $rs['user_name'], $content );
				$content = str_replace ( '{jobs_name}', $rs['jobs_name'], $content );
				$content = str_replace ( '{cycle}', $row ['years'] . '年第' . $rs ['quarter'] . '季度', $content );
				$content = str_replace ( '{assess_user_name}', $row ['assess_user_name'], $content );
				$content = str_replace ( '{audit_user_name}', $row ['audit_user_name'], $content );
				$content = str_replace('<input id="count_audit_fraction" name="count_audit_fraction" type="hidden" />','<input id="count_audit_fraction" name="count_audit_fraction" type="hidden" value="'.($rs['count_audit_fraction'] ? $rs['count_audit_fraction'] : $rs['count_assess_fraction']).'" />',$content);
				$content = str_replace ( '{average_assess_fraction}', '<span id="show_average_assess_fraction">' . $rs ['average_assess_fraction'] . '</span>', $content );
				$content = str_replace ( '{average_audit_fraction}', '<span id="show_average_audit_fraction">' . ($rs ['average_audit_fraction'] ? $rs ['average_audit_fraction'] : $rs ['average_assess_fraction']) . '</span>', $content );
				$content = str_replace ( '<input id="average_audit_fraction_value" name="average_audit_fraction" type="hidden" />', '<input id="average_audit_fraction_value" name="average_audit_fraction" type="hidden" value="' . ($rs ['average_audit_fraction'] ? $rs ['average_audit_fraction'] : $rs ['average_assess_fraction']) . '"/>', $content );
				$content = preg_replace_callback ( '/<input class="my_fraction" name="my_fraction[[][]]"(.+?)>/is', array (
																															$this, 
																															'set_edit_fraction' 
				), $content );
				$content = preg_replace_callback ( '/<textarea class="my_remark"(.+?)my_remark(.+?)><\/textarea>/is', array (
																											$this, 
																											'set_edit_remark' 
				), $content );
				preg_match_all ( '/<span class="percentage"(.+?)>(.+?)<\/span>/is', $content, $arr );
				$count_percentage = 0;
				if ($arr [2])
				{
					foreach ( $arr [2] as $val )
					{
						$count_percentage = $count_percentage + trim ( str_replace ( '%', '', $val ) );
					}
				}
				$content = str_replace ( '{count_percentage}', $count_percentage . '%', $content );
				
				$content = str_replace ( '<span id="show_count_my_fraction" style="color: red">0</span>', '<span id="show_count_my_fraction" style="color: red">' . $rs ['count_my_fraction'] . '</span>', $content );
				$content = str_replace ( '<span id="show_count_assess_fraction" style="color: red">0</span>', '<span id="show_count_assess_fraction" style="color: red">' . $rs ['count_assess_fraction'] . '</span>', $content );
				$content = str_replace ( '<span id="show_count_audit_fraction" style="color: red">0</span>', '<span id="show_count_audit_fraction" style="color: red">' . ($rs ['count_audit_fraction'] ? $rs ['count_audit_fraction'] : $rs ['count_assess_fraction']) . '</span>', $content );
				if (strpos($content,'{manager_schedule_fraction}')!==false)
				{
					$content = str_replace('{manager_schedule_fraction}','<input class="assess_fraction" name="assess_fraction[]" value="" />',$content);
				}
				$content = preg_replace_callback ( '/<input class="assess_fraction" name="assess_fraction[[][]]"(.+?)>/is', array (
																																	$this, 
																																	'set_assess_fraction' 
				), $content );
				$content = preg_replace_callback ( '/<input class="audit_fraction" name="audit_fraction[[][]]"(.+?)>/is', array (
																																	$this, 
																																	'set_audit_fraction' 
				), $content );
				$content = preg_replace ( '/<input class="evaluate_fraction" name="evaluate_fraction(.+?)>/', '&nbsp;', $content ); //关闭评介人评分框
				$content = preg_replace('/<textarea class="evaluate_remark"(.+?)<\/textarea>/is','&nbsp;', $content);
				$content = str_replace('<input type="file" name="upfile[]" onchange="file_input(this);" value="" />',$this->file_list($rs),$content);
				$content = str_replace('<input name="upfile[]" onchange="file_input(this);" type="file" />',$this->file_list($rs),$content);
				$content = preg_replace ( '/<textarea(.+?)assess_opinion(.+?)><\/textarea>/', $rs ['assess_opinion'], $content );
				$content = preg_replace ( '/<textarea(.+?)my_opinion(.+?)><\/textarea>/', $rs ['my_opinion'], $content );
				$content = preg_replace('/<textarea class="audit_opinion"(.+?)>/','<textarea class="audit_opinion"${1}>'.$rs['audit_opinion'],$content);
				$content .= '<div style="text-align:center;"><input type="submit" value=" 确定提交 " /> &nbsp;&nbsp; <input type="button" onclick="window.close();" value=" 关闭返回 " /></div>';
				$content .= '</form></html>';
				echo $content;
			} else
			{
				showmsg ( '非法请求！', 'parent.location.reload();window.close()', 'button' );
			}
		}
	}
	/**
	 * 设置等级
	 */
	function c_set_level()
	{
		$id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
		if ($id)
		{
			$level = $_GET['level'] ? $_GET['level'] : $_POST['level'];
			if ($this->SetLevel($level,$id))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			echo -1;
		}
	}
	/**
	 * 替换评分
	 * @param $matches
	 */
	function set_Evaluate_fraction($matches)
	{
		$list_id = $_GET['list_id'] ? $_GET['list_id'] : $_POST['list_id'];
		$list_obj = new model_administration_appraisal_evaluate_index();
		
		static $data = null;
		static $fra = null;
		if ($data == null && $fra == null)
		{
			$data = $list_obj->GetEvaluateInfo($list_id);
		}
		static $i = 0;
		$str = $data[$i]['fraction'];
		$i ++;
		return $str;
	}
	/**
	 * 替换评分说明
	 * @param $matches
	 */
	function set_Evaluate_remark($matches)
	{
		$list_id = $_GET['list_id'] ? $_GET['list_id'] : $_POST['list_id'];
		$list_obj = new model_administration_appraisal_evaluate_index();
		
		static $data = null;
		static $fra = null;
		if ($data == null && $fra == null)
		{
			$data = $list_obj->GetEvaluateInfo($list_id);
		}
		static $i = 0;
		$str = $data[$i]['remark'];
		$i ++;
		return $str;
	}
	/**
	 * 替换标签的评分
	 * @param $str
	 */
	function set_edit_fraction($matches)
	{
		static $data = null;
		static $fra = null;
		if ($data == null && $fra == null)
		{
			$data = $this->GetContent ( $_GET ['id'] );
			$fra = $this->GetTypeContent ( $data, 1 );
		}
		static $i = 0;
		if ($_GET ['action'] == 'edit')
		{
			$str = str_replace ( '<input class="my_fraction" name="my_fraction[]"', '<input class="my_fraction" name="my_fraction[' . $fra [$i] ['id'] . ']" value="' . $fra [$i] ['content'] . '"', $matches [0] );
		} else
		{
			$str = $fra [$i] ['content'];
		}
		$this->count_my_fraction += $fra [$i] ['content'];
		$i ++;
		return $str;
	}
	/**
	 * 替换自评说明
	 * @param $matches
	 */
	function set_edit_remark($matches)
	{
		static $data = null;
		static $fra = null;
		static $i = 0;
		if ($data == null && $fra == null)
		{
			$data = $this->GetContent ( $_GET ['id'] );
			$fra = $this->GetTypeContent ( $data, 2 );
		}
		if ($_GET ['action'] == 'edit')
		{
			$str = str_replace ( 'my_remark[]', 'my_remark[' . $fra [$i] ['id'] . ']', $matches [0] ) . $fra [$i] ['content'];
		} else
		{
			$str = $fra [$i] ['content'];
		}
		$i ++;
		return $str;
	}
	/**
	 * 设置考核分
	 */
	function set_assess_fraction($matches)
	{
		static $data = null;
		static $fra = null;
		if ($data == null && $fra == null)
		{
			$data = $this->GetContent ( $_GET ['id'] );
			$fra = $this->GetTypeContent ( $data, 3 );
		}
		static $i = 0;
		if ($_GET ['action'] == 'assess')
		{
			$str = str_replace ( '<input class="assess_fraction" name="assess_fraction[]"', '<input class="assess_fraction" name="assess_fraction[' . $fra [$i] ['id'] . ']" value="' . round($fra [$i] ['content'],3) . '"', $matches [0] );
		} else
		{
			$str = round($fra [$i] ['content'],3);
		}
		$i ++;
		return $str;
	}
	/**
	 * 设置审核分
	 */
	function set_audit_fraction($matches)
	{
		static $data = null;
		static $fra = null;
		static $assess_fra = null;
		if ($data == null && $fra == null)
		{
			$data = $this->GetContent ( $_GET ['id'] );
			$fra = $this->GetTypeContent ( $data, 4 );
			$assess_fra = $this->GetTypeContent ( $data, 3 );
		}
		static $i = 0;
		if ($_GET ['action'] == 'audit')
		{
			$str = str_replace ( '<input class="audit_fraction" name="audit_fraction[]"', '<input class="audit_fraction" name="audit_fraction[' . ($fra [$i] ['id'] ? $fra [$i] ['id'] : '') . ']" value="' . ($fra [$i] ['content'] ? $fra [$i] ['content'] :round($assess_fra[$i] ['content'],3)) . '"', $matches [0] );
		} else
		{
			$str = $fra [$i] ['content'];
		}
		$i ++;
		return $str;
	}
	/**
	 * 附件列表
	 * @param unknown_type $rs
	 */
	function file_list($rs)
	{
		$str ='';
		if ($rs['filename_str'])
		{
			$filename = explode(',',$rs['filename_str']);
			foreach ($filename as $val)
			{
				$str .=' <a href="'.PERFORMANCE_FILE_DIR.$rs['file_path'].'/'.$val.'" target="_blank">'.$val.'</a> ';
			}
		}
		return $str;
	}
	/**
	 * 批量生成员工考核表
	 */
	function c_bathc_add()
	{
		$years = $_GET['years'] ? $_GET['years'] : $_POST['years'];
		$quarter = $_GET['quarter'] ? $_GET['quarter'] : $_POST['quarter'];
		if ($this->bathc_add_table($years,$quarter))
		{
			echo 1;
		}else{
			echo -1;
		}
	}
	/**
	 * 导出EXCEL数据
	 */
	function c_improt_data()
	{
		global $func_limit;
		$data = array();
		if ($func_limit['查看部门'])
		{
			$dept_id_str = $func_limit['查看部门'];
		}
		
		$condition .= $dept_id_str ? " b.dept_id in(".trim($dept_id_str,',').") " : " b.dept_id=".$_SESSION['DEPT_ID'];
		
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		$years = $_GET ['years'] ? $_GET ['years'] : $_POST ['years'];
		$quarter = $_GET ['quarter'] ? $_GET ['quarter'] : $_POST ['quarter'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		
		$condition .= $dept_id ? " and b.dept_id=".$dept_id : '';
		$condition .= $years ? " and a.years=$years " : '';
		$condition .= $quarter ? " and a.quarter=$quarter" : '';
		$condition .= $keyword ? " and (e.name like '%$keyword%' or b.user_name like '%$keyword%')" : '';
		
		$sort = $_GET['sort'] ? $_GET['sort'] : $_POST['sort'];
		$order = $_GET['order'] ? $_GET['order'] : $_POST['order'];
		$order_str = $sort ? 'a.'.$sort.' '.$order : '';
		$data = $this->GetDataList ( $condition, $_POST ['page'], $_POST ['rows'] ,$order_str );
		$excel_data = array();
		if ($data)
		{
			/* modified */
			$user_fraction_arr = array();
			foreach ($data as $rs)
			{
				$key = $rs['years'].'-'.$rs['quarter'];
				$user_fraction_arr[$rs['user_id']][$key][] = $rs['count_audit_fraction'] ? $rs['count_audit_fraction'] : $rs['count_assess_fraction'];
			}
			
			/* modified */
			
			
//			$user_fraction_arr = array();
//			foreach ($data as $row)
//			{
//				$user_fraction_arr[$row['user_id']][] = $row['count_audit_fraction'] ? $row['count_audit_fraction'] : $row['count_assess_fraction'];
//			}
			$line_arr = array();
			foreach ($data as $key=>$rs)
			{
				$tempName = $rs['years'].'-'.$rs['quarter'];
				$excel_data[$key][] = $rs['dept_name'];
				$excel_data[$key][] = $rs['user_name'];
				$excel_data[$key][] = $rs['jobs_name'];
				$excel_data[$key][] = $rs['years'];
				$excel_data[$key][] = $rs['quarter'];
				$excel_data[$key][] = $rs['assess_username'];
				$excel_data[$key][] = $rs['audit_username'];
				$excel_data[$key][] = $rs['count_my_fraction'];
				$excel_data[$key][] = $rs['count_assess_fraction'];
				$excel_data[$key][] = $rs['count_audit_fraction'];
				
				$excel_data[$key][] = round(array_sum($user_fraction_arr[$rs['user_id']][$tempName]) / count($user_fraction_arr[$rs['user_id']][$tempName]),3);
				//$excel_data[$key][] = round(array_sum($user_fraction_arr[$rs['user_id']]) / count($user_fraction_arr[$rs['user_id']]),3).'';
				
				$excel_data[$key][] = $rs['level'];
				
				//$line_arr[] = count($user_fraction_arr[$rs['user_id']]);
				$line_arr[] = count($user_fraction_arr[$rs['user_id']][$tempName]);
			}
		}
		$Title = array('部门','姓名','职位','年份','季度','考核人','审核人','自评分数','考核分数','审核分数','最终总分','排名等级');
		$filename = ($years ? $years : $this->last_quarter_year).'年'.($quarter ? $quarter : $this->last_quarter).'绩效考核分数.xls';
		$excel = new includes_class_excel($filename);
		$excel->SetTitle(array('绩效考核'),array($Title));
		$excel->SetContent(array($excel_data));
		$j=0;
		for ($i=0;$i<count($line_arr);$i++)
		{
			if ($line_arr[$i] > 1)
			{
				$excel->objActSheet[0]->mergeCells('K'.($i+2).':'.'k'.($i+1+$line_arr[$i]));
				$i=$i+$line_arr[$i]-1;
			}
		}
		$excel->OutPut();
	}
	/**
	 * 部门
	 */
	function c_dept_data()
	{
		global $func_limit;
		$dept_id = $func_limit['管理部门'] ? $func_limit['管理部门']: $_SESSION['DEPT_ID'];
		$dept = new model_system_dept();
		$data = $dept->DeptTree($dept_id);
		$josn[] = array('dept_id'=>'','dept_name'=>'所有部门');
		if ($data)
		{
			foreach ($data as $key=>$rs)
			{
				$josn[] = array('dept_id'=>$rs['DEPT_ID'],'dept_name'=>($rs['level'] ? str_repeat('|--',$rs['level']) : '').$rs['DEPT_NAME']);
			}
		}
		echo json_encode(un_iconv($josn));
	}
	
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function c_deptData(){
		echo $this->model_administration_appraisal_performance_list_deptData();		
}
function c_deptDataAll(){
		echo $this->model_administration_appraisal_performance_list_deptDataAll();		
}
function c_mList()
	{
		global $func_limit;
		//部门
		$this->show->assign('sort',$func_limit['导入权限']?'<a class="mini-button" iconCls="icon-sort" onclick="search(2)">临时排名</a>':'');
		$this->show->assign('import',$func_limit['导入权限']?'<a class="mini-button" iconCls="icon-upload"  onclick="ImportExcel()">导入</a>':'');
		$this->show->assign('updata',$func_limit['导入权限']?'<a class="mini-button" iconCls="icon-upload"  onclick="upDataExcel()">更新</a>':'');
		$this->show->assign('export',$func_limit['导出权限']?'<a class="mini-button" iconCls="icon-download"  onclick="ExportExcel()">导出</a>':'');
		$this->show->display('mlist');
	}
	/**
	 * 员工季度考核列表数据
	 */
	function c_mListData()
	{
		
		echo $this->model_administration_appraisal_performance_list_mlistData();
	}
	function c_perList()
	{
		$this->show->display ( 'perlist' );
	}
	function c_perListData()
	{
		echo $this->model_administration_appraisal_performance_list_perListData();
	}
	function c_perExIn(){
		$keyId=$_GET ['keyId'];
		$tplId=$_GET ['tplId'];
		$isEvals=$_GET ['isEvals'];
		if ($keyId&&$tplId)
		{
			$this->tbl_name = 'appraisal_performance';
     	    $perData=$this->find(array('id'=>$keyId));
     	    if($perData['isOld']==1){
	     	     if($isEvals==1){
	     	        $this->show->assign('strList',$this->model_administration_appraisal_performance_list_getTplContentEvalData());
				}else{
					$this->show->assign('strList',$this->model_administration_appraisal_performance_list_getTplContentData());
				}
				$tplWeek=$perData['years'].'年  '.($perData['quarter']==5?' 上半年':($perData['quarter']==6?' 下半年':($perData['quarter']==7?' 全年':' 第'.$perData['quarter'].'季'))).'度';
				//$tpLName=$perData['name'].' '.$tplWeek.'考核表';
				$tpLName=$tplWeek.'考核表';
				$this->show->assign('inFlag',$perData['inFlag']);
				$this->show->assign('tplName',$tpLName);
				$this->show->assign('tplWeek',$tplWeek);
				$this->show->assign('userName',$perData['userName']);
				$this->show->assign('userDeptName',$perData['deptName']);
				$this->show->assign('userJobName',$perData['jobName']);
				$this->show->display('ExIn');	
     	    }else{
     	    	$_GET ['id']=$keyId;
     	    	$this->c_showinfo();
     	    }
     	    
		}else{
			showmsg ( '非法请求！', 'parent.CloseOpen();', 'button' );
		}
			
	}
	
	function c_perExInSubmit(){
		if($this->model_administration_appraisal_performance_list_perExInSubmit()){ 
		   showmsg ($_POST ['isConfim']==1?'保存成功！': '提交成功！', 'parent.CloseOpen();', 'button' );
		}else{
			showmsg ( '非法请求！', 'parent.CloseOpen();', 'button' );
		}
			
	}
	function c_addEval(){ 
		if ($_POST) {
			 echo $this->model_administration_appraisal_performance_list_addEval();
		} else {
			$this->show->assign ( 'tplId',$_GET['tplId']);
			$this->show->assign ( 'keyId', $_GET['keyId']);
			$this->show->display ( 'addEval');
		}
	}
	function c_editEval(){ 
		if ($_POST) {
			 echo $this->model_administration_appraisal_performance_list_editEval();
		} else {
			$this->show->assign ( 'tplId',$_GET['tplId']);
			$this->show->assign ( 'keyId', $_GET['keyId']);
			$this->show->display ( 'editEval');
		}
	}
	function c_getEvalerData(){
		 echo $this->model_administration_appraisal_performance_list_getEvalerData();
	}
	function c_asseList()
	{
		$this->show->display ( 'asseList' );
	}
	function c_asseListData()
	{
		echo $this->model_administration_appraisal_performance_list_asseList();
	}
function c_auditList()
{
	$this->show->display ( 'auditList' );
}
function c_auditListData()
{
	echo $this->model_administration_appraisal_performance_list_auditList();
}
function c_postAuditList()
{
	$this->show->display ( 'postAuditList' );
}
function c_postAuditListData()
{
	echo $this->model_administration_appraisal_performance_list_postAuditList();
}

function c_finalScore(){
	if ($_POST) {
		echo $this->model_administration_appraisal_performance_list_editFinalScore();
	} else {
		$this->tbl_name = 'appraisal_performance';
        $perData=$this->find(array('id'=>$_GET['keyId']));
		$this->show->assign ( 'keyId', $_GET['keyId']);
		$this->show->assign ( 'countFraction', $perData['countFraction']);
		$this->show->assign ( 'finalScore', $perData['finalScore']);
		$this->show->display ( 'finalScore' );
	}
}
	
    function c_begineSort(){
    	echo  $this->model_administration_appraisal_performance_list_begineSort();
    }
    
     function c_perManager(){
    	$this->show->assign ( 'tplId',$_GET['tplId']);
		$this->show->assign ( 'keyId', $_GET['keyId']);
		$this->show->display ( 'perManager');
    }
    function c_perTabData(){
    	  	$tabData=un_iconv(array(array(
					'title'=>'自评管理',
					'url'=>'index1.php?model=administration_appraisal_performance_list&action=perList'
					),array(
					'title'=>'评价管理',
					'url'=>'?model=administration_appraisal_evaluate_index&action=evalList'
					),array(
					'title'=>'考核管理',
					'url'=>'index1.php?model=administration_appraisal_performance_list&action=asseList'
					),array(
					'title'=>'审核管理',
					'url'=>'index1.php?model=administration_appraisal_performance_list&action=auditList'
					)));
		echo  json_encode ( $tabData );
    	
    }
    function c_delAtt(){
    	$key=$_POST['key'];
    	if($key){
    		echo  $this->query("delete from appraisal_performance_upfiles where id=".$key);
    	}
    	
    }
    
   function c_editManager(){ 
		if ($_POST) {
			 echo $this->model_administration_appraisal_performance_list_editManager();
		} else {
			$this->tbl_name = 'appraisal_performance';
 	        $perData=$this->find(array('id'=>$_GET['keyId']));
			$this->show->assign ( 'assessName', $perData['assessName']);
			$this->show->assign ( 'auditName', $perData['auditName']);
			$this->show->assign ( 'assessId', $perData['assess_userid']);
			$this->show->assign ( 'auditId', $perData['audit_userid']);
			$this->show->assign ( 'isAssReadOnly',($perData['inFlag']<4||($perData['inFlag']==4&&$perData['assess_status']=='0'))?2:1);
			$this->show->assign ( 'isAuditReadOnly', $perData['audit_status']);
			$this->show->assign ( 'inFlag', $perData['inFlag']);
			$this->show->assign ( 'isEval', $perData['isEval']);
			$this->show->assign ( 'tplId',$_GET['tplId']);
			$this->show->assign ( 'keyId', $_GET['keyId']);
			$this->show->display ( 'editManager');
		}
	} 
	function c_detailExIn(){
			$tplId=$_GET ['tplId'];
			if ($tplId)
			{
				$this->tbl_name = 'appraisal_performance_template';
	     	    $perData=$this->find(array('id'=>$tplId));
	     	    if($perData['tplStyleFlag']==2){
	     	    	$this->show->assign ( 'keyId',$tplId);	
				  $this->show->display ( 'detailTplHtmlContent');
	     	    }else{
	     	    	$this->show->assign('strList',$this->model_administration_appraisal_performance_list_getDetailTplData());
					$tplWeek=$perData['years'].'年  '.($perData['quarter']==5?' 上半年':($perData['quarter']==6?' 下半年':($perData['quarter']==7?' 全年':' 第'.$perData['quarter'].'季'))).'度';
					//$tpLName=$perData['name'].' '.$tplWeek.'考核表';
					$tpLName=$tplWeek.' 考核表';
					$this->show->assign('tplName',$tpLName);
					$this->show->assign('tplWeek',$tplWeek);
					$this->show->assign('userName',$perData['userName']);
					$this->show->assign('userDeptName',$perData['deptName']);
					$this->show->assign('userJobName',$perData['jobName']);
					$this->show->display('detailExIn');
	     	    }
				
			}else{
				showmsg ( '木有此模板数据！', 'parent.CloseOpen();', 'button' );
			}
	  		
	  }
	  
	 /* 导出员工数据
	 * Enter description here ...
	 */
	function c_exportExcel ( )
	{
	   $this->model_administration_appraisal_performance_list_exportExcel();
    }
	function c_importExcel(){ 
		if ($_POST) {
			if($this->model_administration_appraisal_performance_list_importExcel()==2){
	       	  showmsg ( '导入成功！', 'parent.CloseOpen();', 'button' );
	        }else{
	       	  showmsg ( '导入失败！', 'parent.CloseOpen();', 'button' );
	        }
		} else {
			$this->show->display ( 'importExcel');
		}
	} 
	function c_optionStatus(){
		echo $this->model_administration_appraisal_performance_list_optionStatus();
	}
	
	  	
	function c_init(){ 
		echo $this->model_administration_appraisal_performance_list_init();
	}
	
	 /* 导出员工数据
	 * Enter description here ...
	 */
	function c_exportExaExcel ( )
	{
	   $this->model_administration_appraisal_performance_list_exportExaExcel();
    }
    
    function c_importExExcel(){ 
		if ($_POST) {
			$msgI=$this->model_administration_appraisal_performance_list_importExExcel();
			if($msgI['msg']==2){
				if($msgI['err']&&is_array($msgI['err'])){
					foreach($msgI['err'] as $key =>$val){
						$str.=$val.'导入失败！<br/>';
					}
				}else{
					$str='导入成功！';
				}
	       	  showmsg ( $str, 'parent.CloseOpen();', 'button' );
	        }else{
	       	  showmsg ( '导入失败！', 'parent.CloseOpen();', 'button' );
	        }
		} else {
			$this->show->display ( 'importExExcel');
		}
	}
	
	function c_importUpExcel(){ 
		if ($_POST) {
			$msgI=$this->model_administration_appraisal_performance_list_importUpExcel();
			if($msgI['msg']==2){
				if($msgI['err']&&is_array($msgI['err'])){
					foreach($msgI['err'] as $key =>$val){
						$str.=$val.'导入失败！<br/>';
					}
				}else{
					$str='导入成功！';
				}
	       	  showmsg ( $str, 'parent.CloseOpen();', 'button' );
	        }else{
	       	  showmsg ( '导入失败！', 'parent.CloseOpen();', 'button' );
	        }
		} else {
			showmsg ( '导入失败！', 'parent.CloseOpen();', 'button' );
		}
	}
	function c_updateInFlag(){
		
		$inFlag=$_POST['inFlag'];
		$kIdI=explode(',',$_POST['kId']);
		if($kIdI&&is_array($kIdI)&&$inFlag){
			$upDate=array('inFlag'=>$inFlag);
			foreach($kIdI as $key =>$val){
				if($val){
					$flag=$this->update(array('id'=>$val),$upDate);
				}
			}
			echo  $flag; 
		}		
	}

	function c_toEmail(){
		if ($this->model_administration_appraisal_toEmail()=='2')
		{
			echo 2;
		}else{
			echo -1;
		}
		
	
	}
}
?>