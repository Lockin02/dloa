<?php
/**
 * 项目成员考核项目经理进度管理评分
 * @author 1
 *
 */
class controller_administration_appraisal_evaluate_manager extends model_administration_appraisal_evaluate_manager
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'administration/appraisal/evaluate/manager/';
	}
	/**
	 * 默认访问
	 */
	function c_index()
	{
		
	}
	
	
	function c_list()
	{
		
	}
	/**
	 * 列表数据
	 */
	function c_list_data()
	{
		global $func_limit;
		$dept_id_str = $func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID'];
		$condition = " b.dept_id in($dept_id_str)";
		
		$dept_id = $_GET ['dept_id'] ? $_GET ['dept_id'] : $_POST ['dept_id'];
		$years = $_GET ['years'] ? $_GET ['years'] : $_POST ['years'];
		$quarter = $_GET ['quarter'] ? $_GET ['quarter'] : $_POST ['quarter'];
		//$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		
		$condition .=$dept_id ? " and b.dept_id=".$dept_id : '';
		$condition .= $years ? " and a.years=$years " : '';
		$condition .= $quarter ? " and a.quarter=$quarter" : '';
		//$condition .= $keyword ? " and (b.user_name like '%$keyword%' or c.user_name like '%$keyword%')" : '';
		
		$data = $this->GetDataList ( $condition, $_POST['page'], $_POST['rows']);
		$json = array ('total' => $this->num );
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
	 * 我的评价列表
	 */
	function c_mylist()
	{
		$data = $this->GetMyManager($_SESSION['USER_ID']);
		//第四季度考核提前进行而进行的修改
		$list_data = $this->GetDataList(" a.years=".$this->last_quarter_year." and a.quarter=".$this->this_quarter." and a.assess_userid='".$_SESSION['USER_ID']."'");
		
		/*
		if ($this->this_quarter < 4)
		{
			$list_data = $this->GetDataList(" a.years=".$this->last_quarter_year." and a.quarter=".$this->last_quarter." and a.assess_userid='".$_SESSION['USER_ID']."'");
		}else{
			$list_data = $this->GetDataList(" a.years=".$this->last_quarter_year." and a.quarter=".$this->this_quarter." and a.assess_userid='".$_SESSION['USER_ID']."'");
		}
		*/
		$manager = array();
		if ($list_data)
		{
			foreach ($list_data as $rs)
			{
				$manager[] = $rs['manager_userid'];
			}
		}
		
		//modified mehod at 2012-10-15
		$manager_list = array();
		$esixtManager = array();
		if ($data)
		{
			foreach ($data as $rs)
			{
				$flag = false;
				
				if(strpos($rs['manager'], ',') !== false){
					$flag = true;
					$managerList = spliti(',', $rs['manager']);
				}
				if($flag){
					foreach ($managerList as $key => $value){
						if(!in_array($value, $esixtManager)){
							if (in_array("'".$value."'", $this->manager) && !in_array($value,$manager))
							{
								$realName = $this->getManagerRealName($value);
								$manager_list[] = array('user_id'=>$value,'manager_name'=>$realName);
								$esixtManager[] = $value;
							}
						}
					}
				}else{
					if(!in_array($rs['manager'], $esixtManager)){
						if (in_array("'".$rs['manager']."'",$this->manager) && !in_array($rs['manager'],$manager))
						{
							$manager_list[] = array('user_id'=>$rs['manager'],'manager_name'=>$rs['manager_name']);
							$esixtManager[] = $rs['manager'];
						}
					}
				}
			}
		}
		$this->show->assign('year',$this->last_quarter_year);
		//$this->show->assign('quarte',($this->this_quarter < 4 ? $this->last_quarter : $this->this_quarter));
		$this->show->assign('quarte',$this->this_quarter);
		$this->show->assign('manager_list',json_encode ( un_iconv ( $manager_list ) ));
		$this->show->display('mylist');
	}
	/**
	 * 列表数据
	 */
	function c_mylist_data()
	{
		
		$years = $_GET ['years'] ? $_GET ['years'] : $_POST ['years'];
		$quarter = $_GET ['quarter'] ? $_GET ['quarter'] : $_POST ['quarter'];
		//$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		
		$condition = "a.assess_userid='".$_SESSION['USER_ID']."'";
		$condition .= $years ? " and a.years=$years " : '';
		$condition .= $quarter ? " and a.quarter=$quarter" : '';
		//$condition .= $keyword ? " and e.name like '%$keyword%'" : '';
		$data = $this->GetDataList ( $condition, $_POST['page'], $_POST['rows']);
		$json = array ('total' => $this->num );
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
			$_POST = mb_iconv($_POST);
			$_POST['assess_userid'] = $_SESSION['USER_ID'];
			$_POST['date'] = date('Y-m-d H:i:s');
			if ($this->Add($_POST))
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
	 * 修改
	 */
	function c_edit()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
			if ($this->Edit($_POST,$_POST['id']))
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