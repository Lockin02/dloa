<?php
class controller_salary extends model_salary_class {
	public $show; // 模板显示
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->show = new show ();
	}
	
	/**
	 * 默认访问显示
	 */
	function index() {
	}
	// 人事模块
	// 工资办理
	function c_hr_exa() {
		global $func_limit;
		$this->show->assign ( 'display', $func_limit ['人事进阶操作限制'] == 1 ? 'none' : '' );
		
		$this->show->assign ( 'join_url', '?model=salary&action=hr_join' );
		$this->show->assign ( 'pass_url', '?model=salary&action=hr_pass' );
		$this->show->assign ( 'bou_url', '?model=salary&action=hr_spe' );
		$this->show->assign ( 'hols_url', '?model=exet_attendance&action=monthsta' );
		$this->show->assign ( 'pay_url', '?model=salary&action=hr_pay' );
		$this->show->assign ( 'user_url', '?model=salary&action=hr_info' );
		$this->show->assign ( 'leave_url', '?model=salary&action=hr_leave' );
		$this->show->assign ( 'leave_manager_url', '?model=salary&action=hr_leave_manager' );
		$this->show->assign ( 'exp_url', '?model=salary&action=hr_exp' );
		$this->show->assign ( 'sdy_url', '?model=salary&action=hr_sdy' );
		$this->show->assign ( 'wsh_url', '?model=module_report&action=list&reportkey=dc1cfc1213274f9e7bfdc7409fed0e24' );
		$this->show->assign ( 'year_url', '?model=salary&action=hr_yeb' );
		$this->show->assign ( 'mdf_url', '?model=salary&action=dp_ymd' );
		// 导入
		$this->show->assign ( 'hf_url', '?model=module_report&action=upExcel&repkey=gzhf' );
		$this->show->assign ( 'cb_url', '?model=module_report&action=upExcel&repkey=gzfuxcb' );
		$this->show->assign ( 'jt_url', '?model=module_report&action=upExcel&repkey=gzxmjt' );
		// 基本工资导入
		$this->show->assign ( 'jb_url', '?model=salary&action=hr_jb' );
		$this->show->assign ( 'gw_url', '?model=salary&action=hr_gw' );
		// 工程工资导入
		$this->show->assign ( 'gc_url', '?model=salary&action=hr_gc' );
		// 通信工资导入
		$this->show->assign ( 'tx_url', '?model=salary&action=hr_tx' );
		// 项目明细导入
		$this->show->assign ( 'prod_url', '?model=salary&action=hr_prod' );
		// 考核工资总表
		$this->show->assign ( 'pro_url', '?model=salary&action=hr_pro' );
		// 薪资结构
		$this->show->assign( 'user_salary_url', '?model=salary&action=hr_user_salary');
		// 用户调薪记录
		$this->show->assign( 'user_adjust_url', '?model=salary&action=hr_user_adjust');
		// 月度调薪记录
		$this->show->assign( 'mon_adjust_url', '?model=salary&action=hr_mon_adjust');
		// 生成预提
		$this->show->assign( 'yuti_url', '?model=salary&action=hr_yuti');
		
		$this->show->display ( 'salary_hr-exa' );
	}
	
	/**
	 * 月度调薪记录
	 */
	function c_hr_mon_adjust() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = $this->get_com_purview ();
		$navinfo .= "年份：<select id='seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份：<select id='seapm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " 部门：<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= " <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$navinfo .= " <input type='button' id='explore' value='导出' onclick='exploreExcel()' />";
		
		$isHidden = "true";
		$isHiddenSubName = "";
		$isHiddenSubAlign = "";
		$isHiddenSubWidth = "";
		if($this->isWyLeader()) {
			$isHidden = "false";
			$isHiddenSubName = ",'补贴发放部分'";
			$isHiddenSubAlign = ",'right'";
			$isHiddenSubWidth = ",130";
		}
		$this->show->assign ( 'data_list', '?model=salary&action=mon_adjust_list' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->assign ( 'isHidden', $isHidden );
		$this->show->assign ( 'isHiddenSubName', $isHiddenSubName );
		$this->show->assign ( 'isHiddenSubAlign', $isHiddenSubAlign );
		$this->show->assign ( 'isHiddenSubWidth', $isHiddenSubWidth );
		$this->show->display ( 'salary_hr-mon-adjust' );
	}
	
	/**
	 * 人事年终奖
	 */
	function c_hr_yeb() {
		$sealist .= ' 年份：<select name="seay" id="seay">';
		for($i = 2010; $i <= date ( "Y" ); $i ++) {
			if ($this->yebyear == $i) {
				$sealist .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$sealist .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$sealist .= '</select>';
		$sealist .= ' 姓名：<input type="text" name="seaname" id="seaname" value="" style="width:100px;"/>';
		$sealist .= ' 部门：<input type="text" name="seadept" id="seadept" value="" style="width:100px;"/>';
		$sealist .= ' <input name="btn" type="button" id="btn" value="查询" onclick="gridNavSeaFun()"/>';
		$sealist .= ' <input type="button" value="导入年终奖" onclick="showNewExcel()" />';
		$sealist .= ' <input type="button" value="导出年终奖" onclick="hrExcelOutFun()" />';
		$sealist .= '<input name="btn" type="button" id="btn" value="导出外派" onclick="expExcelOutFun()" />';
		$this->show->assign ( 'data_list', '?model=salary&action=hr_yeb_list' );
		$this->show->assign ( 'excel_list', '?model=salary&action=hr_yeb_xls&TB_iframe=true&height=650' );
		$this->show->assign ( 'user_capt', '年终奖列表 ' . $sealist );
		$this->show->display ( 'salary_hr-yeb' );
	}
	/**
	 * 人事年终奖信息
	 */
	function c_hr_yeb_list() {
		// $sqlflag=" and h.userlevel='4' ";
		echo json_encode ( $this->model_dp_yeb_list ( $sqlflag ) );
	}
	/**
	 * 人事年终导入
	 */
	function c_hr_yeb_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_dp_yeb_xls ( $ckt, 'hr' ) );
		$this->show->display ( 'salary_c-hr-yeb-xls' );
	}
	/**
	 * 年终奖导入处理
	 */
	function c_hr_yeb_xls_in() {
		echo json_encode ( $this->model_dp_yeb_xls_in ( 'hr' ) );
	}
	// 人事管理
	function c_hr() {
		$this->show->assign ( 'grid_url', '?model=salary&action=hr_tree' );
		$this->show->assign ( 'first_tab', '工资办理' );
		$this->show->assign ( 'first_ifr', '?model=salary&action=hr_exa' );
		$this->show->assign ( 'exitSalary', '?model=salary&action=exitSalary&mod=hr' );
		$this->show->display ( 'salary_index' );
	}
	function c_exitSalary() {
		@session_start ();
		unset($_SESSION["prikey"]);
		if($_REQUEST ["mod"] == "hr") {
			echo "<script>location.href='?model=salary&action=hr';</script>";
		}else if ($_REQUEST ["mod"] == "fin") {
			echo "<script>location.href='?model=salary&action=fin_index';</script>";
		}else if ($_REQUEST ["mod"] == "dp") {
			echo "<script>location.href='?model=salary&action=dp';</script>";
		}
	}
	/**
	 * 人事树形
	 */
	function c_hr_tree() {
		$responce->rows [0] ['id'] = '1';
		$responce->rows [0] ['cell'] = un_iconv ( array (
				'1',
				'工资办理',
				'?model=salary&action=hr_exa',
				'1',
				'',
				'',
				'true',
				'true' 
		) );
		$responce->rows [1] ['id'] = '2';
		$responce->rows [1] ['cell'] = un_iconv ( array (
				'2',
				'工资信息',
				'?model=salary&action=hr_user',
				'1',
				'',
				'',
				'true',
				'true' 
		) );
		global $func_limit;
		if ($func_limit ['集团人力'] == 1) {
			// 专区
// 			$responce->rows [2] ['id'] = '3';
// 			$responce->rows [2] ['cell'] = un_iconv ( array (
// 					'3',
// 					'专区信息',
// 					'?model=salary&action=hr_user_div',
// 					'1',
// 					'',
// 					'',
// 					'true',
// 					'true' 
// 			) );
			// 额外人员
			$responce->rows [2] ['id'] = '4';
			$responce->rows [2] ['cell'] = un_iconv ( array (
					'3',
					'额外信息',
					'?model=salary&action=hr_user_ext',
					'1',
					'',
					'',
					'true',
					'true' 
			) );
			$responce->rows [3] ['id'] = '5';
			$responce->rows [3] ['cell'] = un_iconv ( array (
					'4',
					'工资统计',
					'?model=salary&action=hr_user_stat',
					'1',
					'',
					'',
					'true',
					'true' 
			) );
		}
		echo json_encode ( $responce );
	}
	/**
	 * 人事入职
	 */
	function c_hr_join() {
		$navinfo = " 部门：<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$this->xls_out ();
		$navinfo .= " <input type='button' value='入职导入' onclick='showNewExcel()' />";
		$this->show->assign ( 'data_list', '?model=salary&action=hr_join_list' );
		$this->show->assign ( 'user_capt', '员工入职列表' . $navinfo );
		$this->show->display ( 'salary_hr-join' );
	}
	/**
	 * 入职员工列表
	 */
	function c_hr_join_list() {
		echo json_encode ( $this->model_hr_join_list ());
	}
	/**
	 * 入职处理
	 */
	function c_hr_join_in() {
		echo json_encode ( $this->model_hr_join_in () );
	}
	
	/**
	 * 入职处理导入
	 */
	function c_hr_join_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm 
		);
		$this->show->assign ( 'data_list', $excelUtil->xls_check ( $ckt, 'hr_join', 'str', $info ) );
		$this->show->display ( 'salary_hr-join-xls' );
	}
	/**
	 * 入职处理导入处理
	 */
	function c_hr_join_xls_in() {
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm 
		);
		echo json_encode ( $this->model_xls_in ( 'hr_join', $info ) );
	}

	/**
	 * 离职名单导入
	 */
	function c_hr_leave_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm,
				'list' => array (
						'员工号',
						'员工',
						'离职日期' 
				) 
		);
		$this->show->assign ( 'data_list', $excelUtil->xls_check ( $ckt, 'hr_leave', 'str', $info ) );
		$this->show->display ( 'salary_hr-leave-xls' );
	}
	
	/**
	 * 离职名单提交
	 */
	function c_hr_leave_xls_in() {
		$ckt = $_POST['ckt'];
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm,
				'list' => array (
						'员工号',
						'员工',
						'离职日期'
				)
		);
		$responce = "";
		$data = $excelUtil->xls_check ( $ckt, 'hr_leave', 'data', $info );
		foreach ($data as $key => $val) {
			if($val['验证'] == "1") {
				$responce = $this->model_hr_leave_in_ext($val["rand_key"],$val["离职日期"],"lz","");
			}
		}
		echo json_encode ($responce);
	}
		
	/**
	 * 基本工资导入
	 */
	function c_hr_jb() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		
		$seapy = empty ( $_POST ['seapy'] ) ? $this->nowy : $_POST ['seapy'];
		$seapm = empty ( $_POST ['seapm'] ) ? $this->nowm : $_POST ['seapm'];
		$info = array (
				'pyear' => $seapy,
				'pmon' => $seapm,
				'list' => array (
						'员工号',
						'姓名',
						'基本工资',
						'岗位工资',
						'绩效工资' 
				) 
		);
		$navinfo .= "<br>对应的生效年份 <select id='seapy' name='seapy'>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份 <select id='seapm' name='seapm'>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$this->show->assign ( 'notice_info', $navinfo );
		// print_r($info);
		$this->show->assign ( 'up_url', '?model=salary&action=hr_jb_in' );
		$this->show->assign ( 'ck_url', '?model=salary&action=hr_jb' );
		$this->show->assign ( 'data_list', $excelUtil->xls_check ( $ckt, 'hr_jb', 'str', $info ) );
		$this->show->display ( 'salary_xls' );
	}
	function c_hr_jb_in() {
		$seapy = empty ( $_POST ['seapy'] ) ? $this->nowy : $_POST ['seapy'];
		$seapm = empty ( $_POST ['seapm'] ) ? $this->nowm : $_POST ['seapm'];
		$info = array (
				'pyear' => $seapy,
				'pmon' => $seapm 
		);
		echo json_encode ( $this->model_xls_in ( 'hr_jb', $info ) );
	}
	
	/**
	 * 岗位工资导入
	 */
	function c_hr_gw() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm,
				'list' => array (
						'员工号',
						'姓名',
						'岗位工资' 
				) 
		);
		$this->show->assign ( 'notice_info', '对应的生效月份：' . $this->nowy . '年' . $this->nowm . '月' );
		$this->show->assign ( 'up_url', '?model=salary&action=hr_gw_in' );
		$this->show->assign ( 'ck_url', '?model=salary&action=hr_gw' );
		$this->show->assign ( 'data_list', $excelUtil->xls_check ( $ckt, 'hr_gw', 'str', $info ) );
		$this->show->display ( 'salary_xls' );
	}
	function c_hr_gw_in() {
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm 
		);
		echo json_encode ( $this->model_xls_in ( 'hr_gw', $info ) );
	}
	
	/**
	 * 工程工资导入
	 */
	function c_hr_gc() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm,
				'list' => array (
						'员工号',
						'姓名',
						'月度出差系数',
						'月度考核系数',
						'绩效奖金',
						'管理津贴',
						'临时住宿补贴',
						'其他津贴',
						'通信津贴' 
				) 
		);
		$this->show->assign ( 'notice_info', '对应的生效月份：' . $this->nowy . '年' . $this->nowm . '月' );
		$this->show->assign ( 'up_url', '?model=salary&action=hr_gc_in' );
		$this->show->assign ( 'ck_url', '?model=salary&action=hr_gc' );
		$this->show->assign ( 'data_list', $excelUtil->xls_check ( $ckt, 'hr_gc', 'str', $info ) );
		$this->show->display ( 'salary_xls' );
	}
	function c_hr_gc_in() {
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm 
		);
		echo json_encode ( $this->model_xls_in ( 'hr_gc', $info ) );
	}
	
	/**
	 * 通信工资导入
	 */
	function c_hr_tx() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm,
				'list' => array (
						'员工号',
						'姓名',
						'通信津贴' 
				) 
		);
		$this->show->assign ( 'notice_info', '对应的生效月份：' . $this->nowy . '年' . $this->nowm . '月' );
		$this->show->assign ( 'up_url', '?model=salary&action=hr_tx_in' );
		$this->show->assign ( 'ck_url', '?model=salary&action=hr_tx' );
		$this->show->assign ( 'data_list', $excelUtil->xls_check ( $ckt, 'hr_tx', 'str', $info ) );
		$this->show->display ( 'salary_xls' );
	}
	function c_hr_tx_in() {
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm 
		);
		echo json_encode ( $this->model_xls_in ( 'hr_tx', $info ) );
	}
	
	/**
	 * 转正导入
	 */
	function c_hr_pass_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm 
		);
		$this->show->assign ( 'data_list', $excelUtil->xls_check ( $ckt, 'hr_pass', 'str', $info ) );
		$this->show->display ( 'salary_hr-pass-xls' );
	}
	
	/**
	 * 入职处理导入处理
	 */
	function c_hr_pass_xls_in() {
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm 
		);
		echo json_encode ( $this->model_xls_in ( 'hr_pass', $info ) );
	}
	
	/**
	 * 人事转正
	 */
	function c_hr_pass() {
		$navinfo = " 部门：<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$this->xls_out ();
		$navinfo .= " <input type='button' value='转正导入' onclick='showNewExcel()' />";
		$this->show->assign ( 'data_list', '?model=salary&action=hr_pass_list' );
		$this->show->assign ( 'user_capt', '员工转正列表' . $navinfo );
		$this->show->display ( 'salary_hr-pass' );
	}
	/**
	 * 转正员工列表
	 */
	function c_hr_pass_list() {
		echo json_encode ( $this->model_hr_pass_list () );
	}
	/**
	 * 转正处理
	 */
	function c_hr_pass_in() {
		echo json_encode ( $this->model_hr_pass_in () );
	}
	/**
	 * 员工离职
	 */
	function c_hr_leave() {
		$navinfo = " 部门：<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$navinfo .= " <input type='button' value='离职导入' onclick='showNewExcel()' />";
		$this->xls_out ();
		$this->show->assign ( 'data_list', '?model=salary&action=hr_leave_list' );
		$this->show->assign ( 'user_capt', '员工离职列表' . $navinfo );
		$this->show->assign ( 'miniDate', "{minDate:'" . date ( 'Y-m' ) . "-01'}" );
		$this->show->display ( 'salary_hr-leave' );
	}
	/**
	 * 员工离职列表
	 */
	function c_hr_leave_list() {
		echo json_encode ( $this->model_hr_leave_list () );
	}
	/**
	 * 部门员工离职
	 */
	function c_dp_leave_manager() {
		$this->c_hr_leave_manager ( '?model=salary&action=dp_leave_manager_list', 'salary_dp-leave-manager' );
	}
	/**
	 * 员工离职管理
	 * 
	 * @param type $url
	 *        	读取数据路径
	 * @param type $v
	 *        	视图显示路径
	 */
	function c_hr_leave_manager($url = '?model=salary&action=hr_leave_manager_list', $v = 'salary_hr-leave-manager') {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : '-';
		$seaplf = $_GET ['seaplf'] ? $_GET ['seaplf'] : '-';
		$navinfo = $this->get_com_sel ();
		$navinfo .= "离职年份：<select id='sealy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份：<select id='sealm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= "结算状态：<select id='seaplf'> <option value='-'>不限</option>";
		$navinfo .= "<option value='1' >已结算</option>";
		$navinfo .= "<option value='0' >未结算</option>";
		$navinfo .= "</select>";
		$navinfo .= " 部门：<input type='text' id='seadept' value='' size='10'/>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='10'/>";
		$navinfo .= "  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$navinfo .= "  <input type='button' id='sub' value='高级查询/导出' onclick='showjsearch()' />";
		$navinfo .= "  <input type='button' id='printAll' value='打印选中数据' onclick='print_All()' />";
		// 高级
		$jsearch .= '<tr><td>';
		$jsearch .= $this->get_com_sel ( true, 'all', 'js_seacom' );
		$jsearch .= '</td></tr>';
		$jsearch .= '<tr><td>';
		$jsearch .= "离职年份：<select id='js_sealy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$jsearch .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$jsearch .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$jsearch .= "</select> 月份：<select id='js_sealm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$jsearch .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$jsearch .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$jsearch .= "</select>";
		$jsearch .= '</td></tr>';
		
		$jsearch .= '<tr><td>';
		$jsearch .= "支付年份：<select id='js_seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			$jsearch .= "<option value='" . $i . "'>" . $i . "</option>";
		}
		$jsearch .= "</select> 月份：<select id='js_seapm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			$jsearch .= "<option value='" . $i . "'>" . $i . "</option>";
		}
		$jsearch .= "</select> 日：<select id='js_seapj'> <option value='-'>不限</option>";
		for($i = 1; $i <= 31; $i ++) {
			$jsearch .= "<option value='" . $i . "'>" . $i . "</option>";
		}
		$jsearch .= "</select>";
		$jsearch .= '</td></tr>';
		$jsearch .= '<tr><td>';
		$jsearch .= "结算状态：<select id='js_seaplf'> <option value='-'>不限</option>";
		$jsearch .= "<option value='1' >已结算</option>";
		$jsearch .= "<option value='0' >未结算</option>";
		$jsearch .= "</select>";
		$jsearch .= '</td></tr>';
		$jsearch .= '<tr><td>';
		$jsearch .= " 部门：<input type='text' id='js_seadept' value=''/>";
		$jsearch .= '</td></tr>';
		$jsearch .= '<tr><td>';
		$jsearch .= " 员工：<input type='text' id='js_seaname' value='' />";
		$jsearch .= '</td></tr>';
		$this->show->assign ( 'jsearch', $jsearch );
		$this->show->assign ( 'data_list', $url );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( $v );
	}
	/**
	 * 员工离职管理别表
	 */
	function c_hr_leave_manager_list() {
		$sqlflag = $this->getSalaryScope(false,true,'','d.dept_id');
		echo json_encode ( $this->model_hr_leave_manager_list ('list', '', true, $sqlflag) );
	}
	/**
	 * 部门离职管理别表
	 */
	function c_dp_leave_manager_list() {
		echo json_encode ( $this->model_dp_leave_manager_list () );
	}
	
	function c_getAssessInfo_d() {
		$logDao = new model_engineering_worklog_esmworklog ();
		$kh = $logDao->getAssessInfo_d ( 2017, 9 );
		print_r($kh);
	}
	
	/**
	 * 计算离职工资
	 */
	function c_cal_leavepay() {
		echo json_encode ( $this->model_cal_leavepay () );
	}
	function c_cal_leave_in() {
		echo json_encode ( $this->model_cal_leave_in () );
	}
	/*
	 * 打印
	 */
	function c_hr_leave_print() {
		$userid = $_REQUEST ['userid'];
		$leavedt = $_REQUEST ['leavedt'];
		$proDetail = $this->model_hr_getProDetail(array($userid),array($leavedt));
		$bna = floatval($_REQUEST ['bna']);
		$sra = floatval($_REQUEST ['sra']);
		$ara = floatval($_REQUEST ['ara']);
		
		$isShow = "none";
		$html = "";
		if(isset($proDetail[$userid])) {
			$isShow = "table";
			$workDays = floatval(substr($leavedt,8,2));
			$deptDays = $workDays;
			foreach (($proDetail[$userid]) as $key => $val) {
				$p = floatval($val) / $workDays;
				$persents = round(($p * 100),2).'%';
				$deptDays = $deptDays - floatval($val);
				$html .= "<tr>
						<td
							style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>$key</td>
						<td class='form_text_right'
							style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($bna*$p),2)."</td>
						<td
							style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($sra*$p),2)."</td>
						<td class='form_text_right'
							style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($ara*$p),2)."</td>
					</tr>";
			}
			if($deptDays > 0) {
				$p = $deptDays / $workDays;
				$html .= "<tr>
				<td
				style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".$_REQUEST ['dept']."</td>
				<td class='form_text_right'
				style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($bna*$p),2)."</td>
				<td
				style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($sra*$p),2)."</td>
				<td class='form_text_right'
				style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($ara*$p),2)."</td>
				</tr>";
			}
		}

		$this->show->assign ( 'isShow', $isShow );
		$this->show->assign ( 'html', $html );
		$this->show->assign ( 'uname', $_REQUEST ['uname'] );
		$this->show->assign ( 'com', $_REQUEST ['com'] );
		$this->show->assign ( 'exp', $_REQUEST ['exp'] );
		$this->show->assign ( 'dept', $_REQUEST ['dept'] );
		$this->show->assign ( 'job', $_REQUEST ['job'] );
		$this->show->assign ( 'comedt', $_REQUEST ['comedt'] );
		$this->show->assign ( 'leavedt', $_REQUEST ['leavedt'] );
		$this->show->assign ( 'baseam', $_REQUEST ['baseam'] );
		$this->show->assign ( 'hda', $_REQUEST ['hda'] );
		$this->show->assign ( 'bna', $_REQUEST ['bna'] );
		$this->show->assign ( 'sra', $_REQUEST ['sra'] );
		$this->show->assign ( 'shb', $_REQUEST ['shb'] );
		$this->show->assign ( 'gjj', $_REQUEST ['gjj'] );
		$this->show->assign ( 'sda', $_REQUEST ['sda'] );
		$this->show->assign ( 'pc', $_REQUEST ['pc'] );
		$this->show->assign ( 'ara', $_REQUEST ['ara'] );
		$this->show->assign ( 'ptol', $_REQUEST ['ptol'] );
		$this->show->assign ( 'acc', $_REQUEST ['acc'] );
		$this->show->assign ( 'accbank', $_REQUEST ['accbank'] );
		
		$this->show->assign ( 'hdar', $_REQUEST ['hdar'] );
		$this->show->assign ( 'bnar', $_REQUEST ['bnar'] );
		$this->show->assign ( 'srar', $_REQUEST ['srar'] );
		$this->show->assign ( 'shbr', $_REQUEST ['shbr'] );
		$this->show->assign ( 'gjjr', $_REQUEST ['gjjr'] );
		$this->show->assign ( 'sdar', $_REQUEST ['sdar'] );
		$this->show->assign ( 'pcr', $_REQUEST ['pcr'] );
		$this->show->assign ( 'arar', $_REQUEST ['arar'] );
		
		$this->show->assign ( 'ph', $_REQUEST ['ph'] );
		$this->show->assign ( 'sh', $_REQUEST ['sh'] );
		$this->show->assign ( 'wdt', $_REQUEST ['wdt'] );
		$this->show->assign ( 'wdtr', $_REQUEST ['wdtr'] );
		$this->show->assign ( 'arac', $_REQUEST ['arac'] );
		$this->show->assign ( 'oara', $_REQUEST ['oara'] );
		$this->show->assign ( 'oarar', $_REQUEST ['oarar'] );
		// print_r($_REQUEST);
		$this->show->display ( 'salary_hr-leave-print' );
	}
	
	/**
	 * 离职结算批量打印
	 */
	function c_hr_leave_printAll() {
		$keys = $_REQUEST ["keys"];
		$keys = "('".str_replace ( ',', "','", $keys )."')";
		$query = $this->model_hr_leave_manager_list("print",$keys);
		$html = "";
		$i = 1;
		$ids = "";

		$userids = array();
		$leavedt = array();
		foreach ( $query as $key=>$row ) {
			$userids[] = $row ['userid'];
			$leavedt[] = $row ['leavedt'];
		}
		$proDetail = $this->model_hr_getProDetail($userids,$leavedt);
		
		foreach ( $query as $key=>$row ) {
            //$divStr = '<div id="payablesapply'.$i.'" style="width: 740px;"><table class="form_main_table" style="border: 1px solid #000000;width:730px;margin-top:40px;margin-left:18px;"><tr ><td colspan="4" style="border: 1px solid #000000;height: 65px;"> <span style="font-size:18px;font-weight:bold;letter-spacing:5px;"> 离职工资结算清单 </span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">姓名</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {uname}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">合同归属</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {com}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">用工类型</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {exp}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">部门</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > {dept}</td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">职务</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {job}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">入职时间</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {comedt}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">离职时间</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {leavedt}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">基本工资（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{baseam}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">岗位工资（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{gwam}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">绩效工资（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{jxam}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">事假</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="">{ph}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">病假</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="">{sh}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">离职当月出勤天数</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="">{wdt}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{wdtr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">事病假扣除（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="formatMoney">{hda}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{hdar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">离职当月实际工作天数工资小计</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="formatMoney">{bna}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{bnar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">各项补贴（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{sra}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{srar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">社会保险费（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{shb}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{shbr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">住房公积金（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="formatMoney">{gjj}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{gjjr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">税前扣除（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{spedelam}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{bsdar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">个人所得税（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{pc}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{pcr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">税后扣除（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{accdelam}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{sdar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">离职福利（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{ara}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{arar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">离职福利税金（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{arac}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">其余税后需发（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{oara}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{oarar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">实发离职工资</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{ptol}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">账号：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {acc}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">开户行：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {accbank}</td></tr><tr><td style="text-align: left; font-size: 14px; font-weight: bolder ;border: 1px solid #000000;padding-top: 10px;padding-bottom: 10px;" colspan="4"> 根据《劳动合同法》规定，在我公司工作期间已签订劳动合同并购买社会保险。<br/> 自本人离开我公司之日起所有经济、法律等纠纷与我公司无关。</td></tr><tr><td style="text-align: left; font-size: 14px; font-weight: bolder;border: 1px solid #000000;" > 人力资源部复核：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td><td style="text-align: left; font-size: 14px; font-weight: bolder;border: 1px solid #000000;" > 人力资源部审批：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td></tr><tr><td style="text-align: left; font-size: 14px; font-weight: bolder; width: 30%;border: 1px solid #000000;"> 财务部审批：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder ;width: 25%;border: 1px solid #000000;"></td><td style="text-align: left; font-size: 14px; font-weight: bolder ; width: 20%;border: 1px solid #000000;" > 离职员工确认：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td></tr></table>';
            $divStr = '<div id="payablesapply'.$i.'" style="width: 740px;"><table class="form_main_table" style="border: 1px solid #000000;width:730px;margin-top:40px;margin-left:18px;"><tr ><td colspan="4" style="border: 1px solid #000000;height: 65px;"> <span style="font-size:18px;font-weight:bold;letter-spacing:5px;"> 离职工资结算清单 </span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">姓名</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {uname}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">合同归属</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {com}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">用工类型</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {exp}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">部门</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > {dept}</td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">职务</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {job}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">入职时间</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {comedt}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">离职时间</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {leavedt}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">基本工资（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{baseam}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">岗位工资（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{gwam}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">绩效工资（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{jxam}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">事假</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="">{ph}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">病假</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="">{sh}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">离职当月出勤天数</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="">{wdt}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{wdtr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">事病假扣除（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="formatMoney">{hda}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{hdar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">税前扣除（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{spedelam}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{bsdar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">离职当月实际工作天数工资小计</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="formatMoney">{bna}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{bnar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">各项补贴（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{sra}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{srar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">社会保险费（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{shb}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{shbr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">住房公积金（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="formatMoney">{gjj}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{gjjr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">个人所得税（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{pc}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{pcr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">税后扣除（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{accdelam}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{sdar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">离职福利（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{ara}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{arar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">离职福利税金（-）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{arac}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">其余税后需发（+）</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{oara}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{oarar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">实发离职工资</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{ptol}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">账号：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {acc}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">开户行：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {accbank}</td></tr><tr><td style="text-align: left; font-size: 14px; font-weight: bolder ;border: 1px solid #000000;padding-top: 10px;padding-bottom: 10px;" colspan="4"> 根据《劳动合同法》规定，在我公司工作期间已签订劳动合同并购买社会保险。<br/> 自本人离开我公司之日起所有经济、法律等纠纷与我公司无关。</td></tr><tr><td style="text-align: left; font-size: 14px; font-weight: bolder;border: 1px solid #000000;" > 人力资源部复核：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td><td style="text-align: left; font-size: 14px; font-weight: bolder;border: 1px solid #000000;" > 人力资源部审批：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td></tr><tr><td style="text-align: left; font-size: 14px; font-weight: bolder; width: 30%;border: 1px solid #000000;"> 财务部审批：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder ;width: 25%;border: 1px solid #000000;"></td><td style="text-align: left; font-size: 14px; font-weight: bolder ; width: 20%;border: 1px solid #000000;" > 离职员工确认：</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td></tr></table>';
			if(empty($ids) == false) {
				$ids .= ",";
			}
			$ids .= "payablesapply".$i;
			$i++;
			$divStr = str_replace ( '{uname}', $row ['username'], $divStr);
			$divStr = str_replace ( '{com}', $row ['com'], $divStr);
			$divStr = str_replace ( '{exp}', $row ['expflag'], $divStr);
			$divStr = str_replace ( '{dept}', $row ['SalaryDept'], $divStr);
			$divStr = str_replace ( '{job}', $row ['jname'], $divStr);
			$divStr = str_replace ( '{comedt}', $row ['comedt'], $divStr);
			$divStr = str_replace ( '{leavedt}', $row ['leavedt'], $divStr);
			$divStr = str_replace ( '{baseam}', $row ['baseam'], $divStr);
			$divStr = str_replace ( '{gwam}', $row ['gwam'], $divStr);
			$divStr = str_replace ( '{jxam}', $row ['jxam'], $divStr);
			$divStr = str_replace ( '{hda}', $row ['hda'], $divStr);
			$divStr = str_replace ( '{bna}', $row ['bna'], $divStr);
			$divStr = str_replace ( '{sra}', $row ['sra'], $divStr);
			$divStr = str_replace ( '{shb}', $row ['shb'], $divStr);
			//20180806修改
			$divStr = str_replace ( '{spedelam}', $row ['spedelam'], $divStr); //其余扣除 拆分为税前扣除和税后扣除
            $divStr = str_replace ( '{gjj}', $row ['gjj'], $divStr);
//            $divStr = str_replace ( '{sda}', $row ['sda'], $divStr); //其余扣除 拆分为税前扣除和税后扣除
            $divStr = str_replace ( '{accdelam}', $row ['accdelam'], $divStr); //其余扣除 拆分为税前扣除和税后扣除
			$divStr = str_replace ( '{pc}', $row ['pc'], $divStr);
			$divStr = str_replace ( '{ara}', $row ['ara'], $divStr);
			$divStr = str_replace ( '{ptol}', $row ['ptol'], $divStr);
			$divStr = str_replace ( '{acc}', $row ['acc'], $divStr);
			$divStr = str_replace ( '{accbank}', $row ['accbank'], $divStr);
			
			$divStr = str_replace ( '{hdar}', $row ['hdar'], $divStr);
			$divStr = str_replace ( '{bnar}', $row ['bnar'], $divStr);
			$divStr = str_replace ( '{srar}', $row ['srar'], $divStr);
			$divStr = str_replace ( '{shbr}', $row ['shbr'], $divStr);
			$divStr = str_replace ( '{gjjr}', $row ['gjjr'], $divStr);
			$divStr = str_replace ( '{sdar}', $row ['sdar'], $divStr);
			$divStr = str_replace ( '{bsdar}', $row ['bsdar'], $divStr);
			$divStr = str_replace ( '{pcr}', $row ['pcr'], $divStr);
			$divStr = str_replace ( '{arar}', $row ['arar'], $divStr);
			
			$divStr = str_replace ( '{ph}', $row ['ph'], $divStr);
			$divStr = str_replace ( '{sh}', $row ['sh'], $divStr);
			$divStr = str_replace ( '{wdt}', $row ['wdt'], $divStr);
			$divStr = str_replace ( '{wdtr}', $row ['wdtr'], $divStr);
			$divStr = str_replace ( '{arac}', $row ['arac'], $divStr);
			$divStr = str_replace ( '{oara}', $row ['oara'], $divStr);
			$divStr = str_replace ( '{oarar}', $row ['oarar'], $divStr);
			$html.= $divStr."</div><br>";
		
			$html2 = "";
			$userid = $row ['userid'];
			if(isset($proDetail[$userid])) {
				$leavedt = $row ['leavedt'];
				$username = $row ['username'];
				$workDays = floatval(substr($leavedt,8,2));
				$deptDays = $workDays;
				$bna = floatval($row ['bna']);
				$sra = floatval($row ['sra']);
				$ara = floatval($row ['ara']);
				$html2 .= "<div id='payablesapply".$i."' style='width: 740px;'><table class='form_main_table' style='border: 1px solid #000000; width:730px;margin-left:18px;margin-top:40px; '>
				<tbody>
					<tr>
						<td colspan='4' style='border: 1px solid #000000; height: 65px;'>
							<span
							style='font-size: 18px; font-weight: bold; letter-spacing: 5px;'>
								$username 离职工资费用归属结算清单 </span>
						</td>
					</tr>
					<tr>
						<td
							style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>部门\项目</td>
						<td class='form_text_right'
							style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>离职当月实际工作天数工资小计</td>
						<td
							style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>各项补贴</td>
						<td class='form_text_right'
							style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>离职福利</td>
					</tr>";
								
				if(empty($ids) == false) {
					$ids .= ",";
				}
				$ids .= "payablesapply".$i;
				$i++;
				$pbna = 0;
				$psra = 0;
				$para = 0;
				foreach (($proDetail[$userid]) as $key => $val) {
					$p = floatval($val) / $workDays;
					$persents = round(($p * 100),2).'%';
					$deptDays = $deptDays - floatval($val);
					$pbna += round(($bna*$p),2);
					$psra += round(($sra*$p),2);
					$para += round(($ara*$p),2);
					$html2 .= "<tr>
							<td
								style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>$key</td>
							<td class='form_text_right'
								style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($bna*$p),2)."</td>
							<td
								style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($sra*$p),2)."</td>
							<td class='form_text_right'
								style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($ara*$p),2)."</td>
						</tr>";
				}
				if($deptDays > 0 || $pbna != $bna || $psra != $sra || $para != $ara) {
					$html2 .= "<tr>
					<td
					style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".$row ['SalaryDept']."</td>
					<td class='form_text_right'
					style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($bna-$pbna),2)."</td>
					<td
					style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($sra-$psra),2)."</td>
					<td class='form_text_right'
					style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>".round(($ara-$para),2)."</td>
					</tr>";
				}
				$html2 .= "<tr>
								<td
									style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>小计</td>
								<td class='form_text_right'
									style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>$bna</td>
								<td
									style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>$sra</td>
								<td class='form_text_right'
									style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>$ara</td>
							</tr>
						</tbody>
					</table></div><br>";
			}
			
			$html .= $html2;
		}
		//payablesapply,payablesapply1
		$this->show->assign ( 'ids', $ids );
		$this->show->assign ( 'divs', $html );
		$this->show->display ( 'salary_hr-leave-printAll' );
	}
	
	/**
	 * 外派员工初始
	 */
	function c_hr_exp() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_hr_exp_ini ( $ckt ) );
		$this->show->display ( 'salary_hr-exp' );
	}
	/**
	 * 初始化子公司
	 */
	function c_hr_sub_ini() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_hr_sub_ini ( $ckt ) );
		$this->show->display ( 'salary_hr-sub-ini' );
	}
	/**
	 * 初始化子公司
	 */
	function c_hr_sub_ini_in() {
		echo json_encode ( $this->model_hr_sub_ini_in () );
	}
	/**
	 * 外派员工
	 */
	function c_hr_exp_in() {
		echo json_encode ( $this->model_hr_exp_in () );
	}
	/**
	 * 员工离职处理
	 */
	function c_hr_leave_in() {
		echo json_encode ( $this->model_hr_leave_in () );
	}
	/**
	 * 补发/扣除
	 */
	function c_hr_spe() {
		$xlsStr = '<select id="xls_year"><option value="-">不限</option>';
		for($i = 2009; $i <= $this->nowy; $i ++) {
			if ($i == $this->nowy) {
				$xlsStr .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$xlsStr .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$xlsStr .= '    </select> 年';
		$xlsStr .= '<select id="xls_mon"><option value="-">不限</option>';
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $this->nowm) {
				$xlsStr .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$xlsStr .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$xlsStr .= '    </select> 月';
		$navinfo .= "  <input type='button' value='新建' onclick='newClickFun()'/>";
		$this->show->assign ( 'xls_list', $xlsStr );
		$this->show->assign ( 'data_list', '?model=salary&action=hr_spe_list' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_hr-spe' );
	}
	
	function c_del_pro() {
		$this->model_del_pro();
	}
	
	function c_hr_spe_xls() {
		$this->model_hr_spe_xls ();
	}
	/**
	 * 特殊员工列表
	 */
	function c_hr_spe_list() {
		echo json_encode ( $this->model_hr_spe_list () );
	}
	/**
	 * 补发/扣除处理
	 */
	function c_hr_spe_in() {
		echo json_encode ( $this->model_hr_spe_in () );
	}
	
	/**
	 * 导入
	 */
	function c_hr_spe_xls_in() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm 
		);
		$this->show->assign ( 'data_list', $excelUtil->xls_check ( $ckt, 'hr_spe', 'str', $info ) );
		$this->show->display ( 'salary_hr-spe-xls' );
	}
	
	/**
	 */
	function c_hr_spe_xls_in_sub() {
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm 
		);
		echo json_encode ( $this->model_xls_in ( 'hr_spe', $info ) );
	}
	
	/**
	 * 缴费
	 */
	function c_hr_pay() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = $this->get_com_sel ( 1, 'all', 'seausercom', '员工' );
		$navinfo .= $this->get_com_sel ( 1, 'all', 'seajfcom', '缴付' );
		$navinfo .= "年：<select id='seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月：<select id='seapm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 类型：<select id='seaexp'> <option value='-'>不限</option>";
		$navinfo .= "<option value='0' >公司员工</option>";
		$navinfo .= "<option value='1' >外派员工</option></select>";
		$navinfo .= " 部门：<input type='text' id='seadept' value='' size='6'/>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='6'/>";
		$navinfo .= "  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$navinfo .= "  <input type='button' id='subx' value='导出' onclick='xlsClickFun()' />";
		$navinfo .= "  <input type='button' id='subu' value='更新' onclick='upClickFun()' />";
		$this->show->assign ( 'data_list', '?model=salary&action=hr_pay_list' );
		$this->show->assign ( 'edit_list', '?model=salary&action=hr_pay_in' );
		$this->show->assign ( 'ctr_list', '?model=salary&action=hr_pay_ctr&TB_iframe=true&height=600' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_hr-pay' );
	}
	/**
	 * 缴费信息列表
	 */
	function c_hr_pay_list() {
		echo json_encode ( $this->model_hr_pay_list () );
	}
	/**
	 * 缴费处理
	 */
	function c_hr_pay_in() {
		echo json_encode ( $this->model_hr_pay_in () );
	}
	/**
	 * 缴费导出
	 */
	function c_hr_pay_xls() {
		$this->model_hr_pay_xls ();
	}
	/**
	 * 缴费对比
	 */
	function c_hr_pay_ctr() {
		$seapy = $_POST ['ctr_py'] ? $_POST ['ctr_py'] : $this->nowy;
		$seapm = $_POST ['ctr_pm'] ? $_POST ['ctr_pm'] : $this->nowm;
		$seac = $_POST ['ctr_com'] ? $_POST ['ctr_com'] : '';
		$ckt = time ();
		
		$navinfo .= "缴付年份：<select id='ctr_py' name='ctr_py'>";
		for($i = $this->nowy; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份：<select id='ctr_pm' name='ctr_pm'>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'ctr_com', $this->get_com_sel ( 0, '', '', '', $seac ) );
		$this->show->assign ( 'pm_list', $navinfo );
		$this->show->assign ( 'cky', $seapy );
		$this->show->assign ( 'ckm', $seapm );
		$this->show->assign ( 'ckc', $seac );
		$this->show->assign ( 'data_list', $this->model_pay_ctr ( $ckt ) );
		$this->show->display ( 'salary_hr-ctr' );
		// $setid=true,$settp='all',$setidn='seacom',$title='',$selected=''
	}
	/**
	 * 缴费对比更新
	 */
	function c_hr_pay_ctr_in() {
		echo json_encode ( $this->model_hr_pay_ctr_in () );
	}
	/**
	 * 初始化公司缴费
	 */
	function c_hr_jf_ini() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_hr_jf_ini ( $ckt ) );
		$this->show->display ( 'salary_hr-jf-ini' );
	}
	/**
	 * 缴费对比更新
	 */
	function c_hr_jf_ini_in() {
		echo json_encode ( $this->model_hr_jf_ini_in () );
	}
	/**
	 * 员工信息
	 */
	function c_hr_info() {
		$navinfo = "  <input type='button' id='sub' value='信息核对' onclick='gridNavSeaFun()' />";
		$this->show->assign ( 'data_list', '?model=salary&action=hr_info_list' );
		$this->show->assign ( 'user_capt', '员工信息列表' . $navinfo );
		$this->show->display ( 'salary_hr-info' );
	}
	function c_hr_info_ck() {
		$nowtime = strtotime ( $this->nowy . '-' . $this->nowm . '-01' );
		$bdt = $_POST ['bdt'] ? $_POST ['bdt'] : date ( 'Y-m-d', $nowtime );
		$edt = $_POST ['edt'] ? $_POST ['edt'] : date ( 'Y-m-d', strtotime ( $this->nowy . '-' . $this->nowm . '-' . date ( 't', $nowtime ) ) );
		$this->show->assign ( 'bdt', $bdt );
		$this->show->assign ( 'edt', $edt );
		$this->show->assign ( 'data_info', $this->model_hr_info_ck ( $bdt, $edt ) );
		$this->show->display ( 'salary_hr-info-ck' );
	}
	/**
	 * 员工信息列表
	 */
	function c_hr_info_list() {
		echo json_encode ( $this->model_hr_info_list () );
	}
	/**
	 * 员工信息修改
	 */
	function c_hr_info_in() {
		echo json_encode ( $this->model_hr_info_in () );
	}
	/**
	 * 人事补贴
	 */
	function c_hr_sdy() {
		$this->xls_out ();
		$this->show->assign ( 'data_list', '?model=salary&action=hr_sdy_list' );
		$this->show->assign ( 'user_capt', '员工补贴列表' );
		$this->show->display ( 'salary_hr-sdy' );
	}
	function c_hr_sdy_list() {
		echo json_encode ( $this->model_hr_sdy_list () );
	}
	function c_hr_sdy_new() {
		$this->show->assign ( 'user_win', 'module/user_select' );
		$this->show->display ( 'salary_hr-sdy-new' );
	}
	function c_hr_sdy_new_in() {
		echo json_encode ( $this->model_hr_sdy_new_in () );
	}
	function c_hr_sdy_xls() {
		$this->show->assign ( 'data_list', $this->model_dp_sdy_xls ( '1' ) );
		$this->show->display ( 'salary_hr-sdy-xls' );
	}
	function c_hr_sdy_xls_in() {
		echo json_encode ( $this->model_hr_sdy_new_in () );
	}
	// 工程项目工资条导入
	function c_hr_prod() {
		$this->xls_out ();
		$this->show->assign ( 'data_list', '?model=salary&action=hr_prod_list' );
		$this->show->assign ( 'user_capt', '工程项目明细' );
		$this->show->display ( 'salary_hr-prod' );
	}
	// 考核工资总表
	function c_hr_pro() {
		global $func_limit;
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo .= "年份：<select id='seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份：<select id='seapm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		if($func_limit['网优数据删除'] == 1) {
			$navinfo .= "  <input type='button' id='del' value='删除' onclick='deleteData()' />";
		}
		$this->show->assign ( 'data_list', '?model=salary&action=hr_pro_list' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_hr-pro' );
	}
	function c_hr_prod_list() {
		echo json_encode ( $this->model_hr_prod_list () );
	}
	function c_hr_pro_list() {
		echo json_encode ( $this->model_hr_pro_list () );
	}
	function c_hr_pro_sub_list() {
		echo json_encode ( $this->model_hr_pro_sub_list () );
	}
	function c_hr_adjust_list() {
		echo json_encode ( $this->model_adjust_list () );
	}
	function c_hr_prod_xls() {
		$this->show->assign ( 'data_list', $this->model_dp_prod_xls () );
		$this->show->display ( 'salary_hr-prod-xls' );
	}
	function c_hr_prod_xls_in() {
		echo json_encode ( $this->model_hr_prod_new_in () );
	}
	// old
	/**
	 * 获取公司信息
	 * 
	 * @param type $setid        	
	 * @return string
	 */
	function get_com_sel($setid = true, $settp = 'all', $setidn = 'seacom', $title = '', $selected = '') {
		$title = empty ( $title ) ? '公司' : $title;
		if ($setid == true) {
			if ($settp == 'all') {
				$res = $title . "：<select id='" . $setidn . "'> <option value='' tabindex='所有公司'>所有公司</option>";
			} else {
				$res = $title . "：<select id='" . $setidn . "'>";
			}
		} else {
			$res = '';
		}
		$comData = $this->globalUtil->getBranchInfo ();
		foreach ( $comData as $key => $val ) {
			if ($selected == $val ['NamePT']) {
				$res .= "<option value='" . $val ['NamePT'] . "' tabindex='" . $val ['NameCN'] . "' selected >" . $val ['NameCN'] . "</option>";
			} else {
				$res .= "<option value='" . $val ['NamePT'] . "' tabindex='" . $val ['NameCN'] . "'>" . $val ['NameCN'] . "</option>";
			}
		}
		if ($setid) {
			$res .= "</select>"; 
		} else {
		}
		
		return $res;
	}
	/**
	 * 根据权限列表['子公司']['浏览部门']获取公司信息
	 * 
	 * @param type $setid        	
	 * @return string
	 */
	function get_com_purview($setid = true, $setidn = 'seacom', $title = '', $selected = '') {
		global $func_limit;
		$scom = $func_limit ['子公司'];
		$deptCom = "";
		if(!empty($func_limit ['浏览部门'])) {
			$sdept = $func_limit ['浏览部门'];
			if($sdept == ";;") {
				$sdept = '';
			}else {
				$sdept = " where p.deptid in(".$func_limit ['浏览部门'].")";
			}
			$sql = "select GROUP_CONCAT(DISTINCT(p.usercom) SEPARATOR ',') as 'comcode' from salary_pay p $sdept";
			$rs = $this->db->get_one($sql);
			$deptCom = " or namept in( '" . str_replace ( ',', "','", $rs['comcode'] ) . "' )";
		}
		$res = '';
		$title = empty ( $title ) ? '公司' : $title;
		if ($setid == true) {
			$res= $res."<select id='".$setidn."'> <option value='' tabindex='所有公司'>所有公司</option>";
		}
		$sql = "select * from branch_info where type='1' "." and (namept in( '" . str_replace ( ',', "','", $scom ) . "' )"."$deptCom) order by parentid , comcard ";
		$query = $this->db->query($sql);
		$comData = array();
		while (($row = $this->db->fetch_array($query)) != false) {
			$comData[$row['ID']] = $row;
		}
		foreach ( $comData as $key => $val ) {
			if ($selected == $val ['NamePT']) {
				$res .= "<option value='" . $val ['NamePT'] . "' tabindex='" . $val ['NameCN'] . "' selected >" . $val ['NameCN'] . "</option>";
			} else {
				$res .= "<option value='" . $val ['NamePT'] . "' tabindex='" . $val ['NameCN'] . "'>" . $val ['NameCN'] . "</option>";
			}
		}
		if ($setid) {
			$res .= "</select>"; 
		}
		
		return $res;
	}
	/**
	 * 额外
	 */
	function c_hr_user_ext() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		
		$navinfo .= "年份：<select id='seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份：<select id='seapm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 状态：<select id='seausersta'> <option value=''>全部</option>";
		$navinfo .= "<option value='生效'>生效</option>";
		$navinfo .= "<option value='关闭'>关闭</option>";
		$navinfo .= "</select>";
		$navinfo .= " 部门：<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$navinfo .= "  <input type='button' id='newspeSub' onclick='newClickFun()' value='新增'/>";
		// $navinfo.=" <input type='button' id='newspeSub' onclick='daoClickFun()' value='导入'/>";
		$navinfo .= "  <input type='button' id='newspeSub' onclick='xlsClickFun()' value='导出'/>";
		
		$this->show->assign ( 'usercom', $this->globalUtil->get_com_sel ( 1, '', 'usercom', '' ) );
		$this->show->assign ( 'jfcom', $this->globalUtil->get_com_sel ( 1, '', 'jfcom', '' ) );
		$this->show->assign ( 'userdept', $this->globalUtil->get_dept_sel ( 1, '', 'userdept', '' ) );
		$this->show->assign ( 'user_list', '?model=salary&action=hr_user_ext_list' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->xls_out ();
		$this->show->display ( 'salary_hr-user-ext' );
	}
	/**
	 * 额外人员
	 */
	function c_hr_user_ext_list() {
		echo json_encode ( $this->model_hr_user_ext () );
	}
	/**
	 * 额外人员录入
	 */
	function c_hr_usre_ext_in() {
		echo json_encode ( $this->model_hr_user_ext_in () );
	}
	
	/**
	 * 专区
	 */
	function c_hr_user_div() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo .= "年份：<select id='seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份：<select id='seapm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " 部门：<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$navinfo .= "  <input type='button' id='sub' value='操作' onclick='outExcel()' />";
		// $navinfo.=" <input type='button' id='xls' value='导出外派' onclick='xlsClickFun()' />";
		// $navinfo.=" <input type='button' id='xls' value='导出工资' onclick='hrXlsClickFun()' />";
		// $navinfo.=" <input type='button' id='xls' value='外派变动' onclick='expClickFun()' />";
		// $navinfo.=" <input type='button' id='xls' value='统计' onclick='xlsTolClickFun()' />";
		// $navinfo.=" <input type='checkbox' id='errs' value='y' onclick='gridNavSeaFun()'/> 补";
		// $navinfo.=" <input type='button' id='xls' value='外派算年终' onclick='expYebClickFun()' />";
		// $navinfo.="<input type='button' value='年度员工' class='BigButton' onclick='btnClick()' />";
		$this->show->assign ( 'user_list', '?model=salary&action=hr_user_div_list' );
		// $this->show->assign('edit_list', '?model=salary&action=hr_user_edit');
		$this->show->assign ( 'user_capt', $navinfo );
		$this->xls_out ();
		$this->show->display ( 'salary_hr-user-div' );
	}
	/**
	 */
	function c_hr_user_div_list() {
		echo json_encode ( $this->model_hr_user_div () );
	}
	/**
	 * 更新导入数据
	 */
	function c_hr_div_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_dao_xls ( 'hr_div', $ckt ) );
		$this->show->display ( 'salary_hr-div-xls' );
	}
	/**
	 * 更新导入数据
	 */
	function c_hr_div_xls_in() {
		$ckt = $_POST ['ckt'];
		echo json_encode ( $this->model_dao_xls ( 'hr_div', $ckt, 'in' ) );
	}
	/**
	 * 更新导入数据
	 */
	function c_hr_ext_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_dao_xls ( 'hr_ext', $ckt ) );
		$this->show->display ( 'salary_hr-ext-xls' );
	}
	/**
	 * 更新导入数据
	 */
	function c_hr_ext_xls_in() {
		$ckt = $_POST ['ckt'];
		echo json_encode ( $this->model_dao_xls ( 'hr_ext', $ckt, 'in' ) );
	}
	/**
	 * 员工信息栏目
	 */
	function c_hr_user() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = $this->get_com_purview ();
		$navinfo .= "年份：<select id='seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份：<select id='seapm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " 部门：<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		// $navinfo.=" <input type='button' id='xls' value='导出外派' onclick='xlsClickFun()' />";
		global $func_limit;
		if ($func_limit ['人事进阶操作限制'] != 1) {
			$navinfo .= " <input type='button' id='sub' value='高级查询' onclick='$(\\\".advModelDiv\\\").show()' />";
			$navinfo .= " <input type='button' id='xls' value='导出工资' onclick='hrXlsClickFun()' />";
		}
		// $navinfo.=" <input type='button' id='xls' value='外派变动' onclick='expClickFun()' />";
		$navinfo .= " <input type='button' id='xls' value='部门统计' onclick='xlsTolClickFun(\\\"?model=salary&action=xls_out&flag=dp_tol&type=hr\\\")' />";
		$navinfo .= " <input type='button' id='xls' value='部门分析' onclick='xlsTolClickFun(\\\"?model=salary&action=xls_out&flag=gs_tol\\\")' />";
		
		$navinfo .= " <input type='checkbox' id='errs' value='y' onclick='gridNavSeaFun()'/> 补";
		// $navinfo.=" <input type='button' id='xls' value='外派算年终' onclick='expYebClickFun()' />";
		// $navinfo.="<input type='button' value='年度员工' class='BigButton' onclick='btnClick()' />";
		$this->show->assign ( 'user_list', '?model=salary&action=hr_user_list&seacom='.$seacom ); 
		$this->show->assign ( 'edit_list', '?model=salary&action=hr_user_edit&seacom='.$seacom );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->assign ( 'salaryYear', $this->nowy );
		$this->show->assign ( 'salaryMon', $this->nowm );
		$this->show->display ( 'salary_hr-user' );
	}
	
	/**
	 * 员工薪资结构信息栏目
	 */
	function c_hr_user_salary() {
        $navinfo = $this->get_com_sel ();
        $navinfo .= "</select>";
        $seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
        $seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
        $deptModel = $_GET ['deptModel'] ? $_GET ['deptModel'] : 0;
        $navinfo .= "年份：<select id='seapy'>";
        for($i = 2010; $i <= $this->nowy; $i ++) {
            if ($i == $seapy) {
                $navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
            } else {
                $navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
            }
        }
        $navinfo .= "</select> 月份：<select id='seapm'>";
        for($i = 1; $i <= 12; $i ++) {
            if ($i == $seapm) {
                $navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
            } else {
                $navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
            }
        }
        $navinfo .= "</select>";
        if($deptModel == 0) {
        	$navinfo .= " 部门：<input type='text' id='seadept' value='' size='15'/>";
        }else if($deptModel == 1) {
        	$navinfo .= "<input type='text' id='seadept' size='15' style='display:none;'/>";
		}
        $navinfo .= " 员工：<input type='text' id='seaname' value='' size='15'/>";
        
		$navinfo .= "&nbsp;&nbsp;&nbsp;只显示网优人员<input type='checkbox' checked id='iswy' onclick='gridNavSeaFun()'/>";
        $navinfo .= "&nbsp;&nbsp;&nbsp;只显示有数据项<input type='checkbox' checked id='hasData' onclick='gridNavSeaFun()' />"
                 ."  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />  <input type='button' id='export' value='导出数据' onclick='exportSalaryData()' />";
        $this->show->assign ( 'user_salary_list', '?model=salary&action=hr_user_salary_list' );
        $this->show->assign ( 'user_capt', $navinfo );
        $this->show->display ( 'salary_hr-user-salary' );
	}
	
	/**
	 * 预提数据栏目
	 */
	function c_hr_yuti() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo .= "年份：<select id='seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份：<select id='seapm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
        $navinfo .= " <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' /> <input type='button' id='export' value='导出' onclick='exportExcel()' /> <input type='button' id='init' value='生成预提数据' onclick='inityuti()' /> <input type='button' id='push' value='输出预提数据' onclick='pushyuti()' /> <input type='button' id='cancel' value='撤销预提数据' onclick='cancelyuti()' />";
		$this->show->assign ( 'hr_yuti_list', '?model=salary&action=hr_yuti_list' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_hr-yuti' );
	}
	
	/**
	 * 查询预提数据
	 */
	function c_hr_yuti_list() {
		echo json_encode ( $this->model_hr_yuti_list () );
	}
	
	/**
	 * 生成预提数据
	 */
	function c_yuti_init() {
		echo un_iconv($this->model_yuti_init() );
	}
	
	/**
	 * 员工调薪记录栏目
	 */
	function c_hr_user_adjust() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = $this->get_com_sel ();
		$navinfo .= "</select>";
		$navinfo .= " 部门：<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "&nbsp;&nbsp;&nbsp;只显示网优人员<input type='checkbox' id='iswy' onclick='gridNavSeaFun()' />  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$this->show->assign ( 'data_list', '?model=salary&action=hr_user_adjust_list' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_hr-user-adjust' );
	}
	
	
	/**
	 * 初始化考核总表
	 */
	function c_init_salarypro() {
		$this->model_initSalaryPro ();
	}
	
	/**
	 * 提交绩效总表数据审批
	 */ 
	function c_sendSalaryToFlow() {
		$this->model_sendSalaryToFlow ();
	}
	
	/**
	 * 员工统计栏目
	 */
	function c_hr_user_stat() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = "年份：<select id='seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " 部门：<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " 员工：<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$navinfo .= " <input type='button' id='xls' value='导出' onclick='xlsClickFun()' />";
		$navinfo .= " <input type='button' id='xls' value='工资统计对比导出' onclick='xlsStaClickFun()' />";
		// $navinfo.=" 2011年实发总工资：".$this->model_hr_yeb_stat(2011);
		$this->show->assign ( 'user_list', '?model=salary&action=hr_user_stat_list' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_hr-user-stat' );
	}
	/*
	 * 统计数据
	 */
	function c_hr_user_stat_list() {
		echo json_encode ( $this->model_hr_user_stat () );
	}
	/**
	 * 统计导出
	 */
	function c_hr_user_stat_xls() {
		$this->model_hr_user_stat_xls ();
	}
	/**
	 * 用户列表
	 */
	function c_hr_user_list() {
		$sqlflag = $this->getSalaryScope();
		echo json_encode ( $this->model_hr_user (false,$sqlflag) );
	}
	/**
	 * 用户列表高级查询
	 */
	function c_hr_user_advlist() {
		$sqlflag = $this->getSalaryScope();
		echo json_encode ( $this->model_hr_user (false,$sqlflag,false,false,'list','',true) );
	}
	/**
	 * 用户薪资结构列表
	 */
	function c_hr_user_salary_list() {
		echo json_encode ( $this->model_hr_salary_user () );
	}
	/**
	 * 用户调薪记录列表
	 */
	function c_hr_user_adjust_list() {
		echo json_encode ( $this->model_hr_adjust_user () );
	}
	
	/**
	 * 外派员工年终奖计算
	 */
	function c_hr_exp_count() {
		$ckt = time ();
		$manflag = $_GET ['manflag'];
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_hr_exp_count ( $ckt ) );
		$this->show->display ( 'salary_hr-yeb-xls' );
	}
	function c_hr_exp_count_out() {
		$this->model_hr_exp_count_out ( $_GET ['ckt'] );
	}
	/**
	 * 外派导出
	 */
	function c_hr_user_exp_xls() {
		if ($_GET ['flag'] == 'float') {
			$this->model_hr_user_exp_xls_f ();
		} else {
			$this->model_hr_user_exp_xls ();
		}
	}
	function c_hr_user_wy_xls() {
		$this->model_hr_user_wy_xls ();
	}
	/**
	 * 请休假上交
	 */
	function c_hr_hols_hd() {
		$this->model_hr_hols_hd ();
	}
	/**
	 * 邮件发送
	 */
	function c_hr_email() {
		$this->show->display ( 'salary_hr-email' );
	}
	function c_hr_email_send() {
		$this->model_hr_email_send ();
	}
	function c_hr_email_exp() {
		$this->model_hr_email_exp ();
	}
	/**
	 * 人事导入调薪
	 */
	function c_hr_mdf() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_mdf_list' );
		$this->show->assign ( 'user_capt', '调薪列表' );
		$this->show->display ( 'salary_hr-mdf' );
	}
	/**
	 * 财务人员
	 */
	function c_fin() {
		$this->show->assign ( 'first_tab', '财务管理' );
		$this->show->assign ( 'first_ifr', '?model=salary&action=fin_index' );
		$this->show->display ( 'salary_fin-index' );
	}
	/**
	 * 部门管理
	 */
	function c_dp() {
		$this->show->assign ( 'grid_url', '?model=salary&action=dp_tree' );
		$this->show->assign ( 'first_tab', '审批事项' );
		$this->show->assign ( 'first_ifr', '?model=salary&action=dp_exa' );
		$this->show->assign ( 'exitSalary', '?model=salary&action=exitSalary&mod=dp' );
		$this->show->display ( 'salary_index' );
	}
	/**
	 * 部门树形
	 */
	function c_dp_tree() {
		$responce->rows [0] ['id'] = '1';
		$responce->rows [0] ['cell'] = un_iconv ( array (
				'1',
				'审批事项',
				'?model=salary&action=dp_exa',
				'1',
				'',
				'',
				'true',
				'true' 
		) );
		$responce->rows [1] ['id'] = '2';
		$responce->rows [1] ['cell'] = un_iconv ( array (
				'2',
				'工资查询',
				'?model=salary&action=dp_user',
				'1',
				'',
				'',
				'true',
				'true' 
		) );
		$responce->rows [2] ['id'] = '3';
		$responce->rows [2] ['cell'] = un_iconv ( array (
				'3',
				'离职查询',
				'?model=salary&action=dp_leave_manager',
				'1',
				'',
				'',
				'true',
				'true' 
		) );
		$responce->rows [3] ['id'] = '4';
		$responce->rows [3] ['cell'] = un_iconv ( array (
				'4',
				'年终奖管理',
				'?model=salary&action=dp_yeb_fn',
				'1',
				'',
				'',
				'true',
				'true' 
		) );
		echo json_encode ( $responce );
	}
	/**
	 * 工资办理
	 */
	function c_dp_exa() {
		$this->xls_out ();
		$this->show->assign ( 'exa_list', $this->model_dp_exa () );
		$this->show->display ( 'salary_dp-exa' );
	}
	/**
	 * 部门入职
	 */
	function c_dp_join() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_join_list' );
		$this->show->assign ( 'user_capt', '员工入职列表' );
		$this->show->display ( 'salary_dp-join' );
	}
	/**
	 * 入职员工列表
	 */
	function c_dp_join_list() {
		echo json_encode ( $this->model_dp_join_list () );
	}
	/**
	 * 入职处理
	 */
	function c_dp_join_in() {
		echo json_encode ( $this->model_hr_join_in ( false ) );
	}
	/**
	 * 部门转正
	 */
	function c_dp_pass() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_pass_list' );
		$this->show->assign ( 'user_capt', '员工转正列表' );
		$this->show->display ( 'salary_dp-pass' );
	}
	/**
	 * 转正员工列表
	 */
	function c_dp_pass_list() {
		echo json_encode ( $this->model_dp_pass_list () );
	}
	/**
	 * 转正处理
	 */
	function c_dp_pass_in() {
		echo json_encode ( $this->model_hr_pass_in ( false ) );
	}
	/**
	 * 部门请休假
	 */
	function c_dp_hols_hd() {
		$this->model_dp_hols_hd ();
	}
	/**
	 * 用户列表
	 */
	function c_dp_user_list() {
		echo json_encode ( $this->model_dp_user () );
	}
	/**
	 * 部门审批
	 */
	function c_dp_sal_exa() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_list' );
		$this->show->assign ( 'user_capt', "工资审批列表<input type='button' name='all_sel' id='all_sel' value='批量审批' />" );
		$this->show->display ( 'salary_dp-sal-exa' );
	}
	/**
	 * 部门审批
	 */
	function c_dp_sal_exa_list() {
		$sf = $_GET ['sf'];
		$exa_sta = $_REQUEST ['exa_sta'];
		if ($sf) {
			if ($sf == 'spe') {
				$sfq = " and f.flowname in ('" . $this->flowName ['spe'] . "','" . $this->flowName ['spe_3'] . "','" . $this->flowName ['spe_5'] . "','" . $this->flowName ['spe_1'] . "','" . $this->flowName ['spe_0'] . "'
											,'" . $this->flowName ['spe_xs_3'] . "','" . $this->flowName ['spe_xs_5'] . "','" . $this->flowName ['spe_xs_1'] . "','" . $this->flowName ['spe_xs_0'] . "','" . $this->flowName ['spe_xs_12'] . "') ";
			} elseif ($sf == 'nym') {
				$sfq = " and f.flowname in ('" . $this->flowName ['nym_0'] . "','" . $this->flowName ['nym_1'] . "'
                    ,'" . $this->flowName ['nym_2'] . "','" . $this->flowName ['nym_3'] . "','" . $this->flowName ['nym_4'] . "'
                    ,'" . $this->flowName ['nym_xs_0'] . "','" . $this->flowName ['nym_xs_1'] . "'
                    ,'" . $this->flowName ['nym_xs_2'] . "','" . $this->flowName ['nym_xs_3'] . "','" . $this->flowName ['nym_xs_4'] . "','" . $this->flowName ['nym_xs_12'] . "'
                    ,'" . $this->flowName ['ymd'] . "','" . $this->flowName ['nym_wy1'] . "','" . $this->flowName ['nym_wy2'] . "','".$this->flowName ['nym_yqyb3']."') ";
			} elseif ($sf == 'pro') {
				$sfq = " and f.flowname in ('" . $this->flowName ['pro'] . "','" . $this->flowName ['pro_3'] . "','" . $this->flowName ['pro_5'] . "','" . $this->flowName ['pro_1'] . "','" . $this->flowName ['pro_0'] . "'
						,'" . $this->flowName ['pro_xs_3'] . "','" . $this->flowName ['pro_xs_5'] . "','" . $this->flowName ['pro_xs_1'] . "','" . $this->flowName ['pro_xs_0'] . "','" . $this->flowName ['pro_xs_12'] . "') ";
			} elseif ($sf == 'bos') {
				$sfq = " and f.flowname in ('" . $this->flowName ['bos'] . "') ";
			} else {
				$sfq = " and f.flowname in ('" . $this->flowName [$sf] . "') ";
			}
		}
		if (empty ( $exa_sta )) {
			$sfq .= " and fs.sta='0' ";
		} else if($exa_sta == 3 )  {
            $sfq .= " and fs.sta in (0,1) ";
        } else {
            $sfq .= " and fs.sta='" . $exa_sta . "' ";
        }
		echo json_encode ( $this->model_dp_sal_exa_list ( false, $sfq, $sf ) );
	}
	
	/**
	 * 月度调薪记录
	 */
	function c_mon_adjust_list() {
		echo json_encode ( $this->model_get_mon_adjust () );
	}
	
	/**
	 * 调薪明细
	 */
	function c_dp_sal_adjust_detail() {
		$fid = $_GET ['fid'];
		echo json_encode ( $this->model_get_salary_detail () );
	}
	
	function c_salary_flow_pass_temp() {
		$flowkey = $_GET ['flowkey'];
		$this->model_flow_pass_temp($flowkey);
	}
	
	function c_salaryauto() {
		$this->model_salaryauto();
	}
	
	/**
	 * 特殊奖励审批
	 */
	function c_dp_sal_exa_spe() {
		$user_capt = "审批状态：<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>未审批</option><option value='1'>已审批</option><option value='3'>全部</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='批量审批' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_list&sf=spe' );
		$this->show->display ( 'salary_dp-sal-exa-spe' );
	}
	/**
	 * 调薪审批
	 */
	function c_dp_sal_exa_nym() {
		$user_capt = "审批状态：<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>未审批</option><option value='1'>已审批</option><option value='3'>全部</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='批量审批' />";
		$isHidden = "true";
		$isHiddenSubName = "";
		$isHiddenSubAlign = "";
		$isHiddenSubWidth = "";
		if($this->isWyLeader()){
			$isHidden = "false";
			$isHiddenSubName = ",'补贴发放部分'";
			$isHiddenSubAlign = ",'right'";
			$isHiddenSubWidth = ",130";
		}
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_list&sf=nym' );
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'isHidden', $isHidden );
		$this->show->assign ( 'isHiddenSubName', $isHiddenSubName );
		$this->show->assign ( 'isHiddenSubAlign', $isHiddenSubAlign );
		$this->show->assign ( 'isHiddenSubWidth', $isHiddenSubWidth );
		$this->show->display ( 'salary_dp-sal-exa-mdy' );
	}
	/**
	 * 项目奖审批
	 */
	function c_dp_sal_exa_pro() {
		$user_capt = "审批状态：<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>未审批</option><option value='1'>已审批</option><option value='3'>全部</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='批量审批' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_list&sf=pro' );
		$this->show->display ( 'salary_dp-sal-exa' );
	}
	/**
	 * 项目奖审批
	 */
	function c_dp_sal_exa_bos() {
		$user_capt = "审批状态：<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>未审批</option><option value='1'>已审批</option><option value='3'>全部</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='批量审批' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_list&sf=bos' );
		$this->show->display ( 'salary_dp-sal-exa' );
	}
	/**
	 * 人事补贴
	 */
	function c_dp_sal_exa_sdy() {
		$user_capt = "审批状态：<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>未审批</option><option value='1'>已审批</option><option value='3'>全部</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='批量审批' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_sdy_list' );
		$this->show->display ( 'salary_dp-sal-exa-sdy' );
	}
	/**
	 * 工程项目明细
	 */
	function c_dp_sal_exa_prod() {
		$user_capt = "审批状态：<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>未审批</option><option value='1'>已审批</option><option value='3'>全部</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='批量审批' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_prod_list' );
		$this->show->display ( 'salary_dp-sal-exa-prod' );
	}
	/**
	 * 考核总表
	 */
	function c_dp_sal_exa_salarypro() {
		$user_capt = "审批状态：<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>未审批</option><option value='1'>已审批</option><option value='3'>全部</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='批量审批' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_pro_list' );
		$this->show->display ( 'salary_dp-sal-exa-pro' );
	}
	
	/**
	 * 季度奖
	 */
	function c_dp_sal_exa_fla() {
		$user_capt = "审批状态：<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>未审批</option><option value='1'>已审批</option><option value='3'>全部</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='批量审批' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_list&sf=fla' );
		$this->show->display ( 'salary_dp-sal-exa-fla' );
	}
	function c_dp_sal_exa_sdy_list() {
		echo json_encode ( $this->model_dp_sal_exa_sdy_list () );
	}
	function c_dp_sal_exa_prod_list() {
		echo json_encode ( $this->model_dp_sal_exa_prod_list () );
	}
	function c_dp_sal_exa_pro_list() {
		echo json_encode ( $this->model_dp_sal_exa_salarypro_list () );
	}
	function c_dp_sal_exa_pro_sub_list() {
		echo json_encode ( $this->model_dp_sal_exa_salarypro_sub_list () );
	}
	function c_dp_sal_exa_salarypro_list() {
		echo json_encode ( $this->model_dp_sal_exa_salarypro_list () );
	}
	/**
	 * 转正处理
	 */
	function c_dp_sal_exa_in() {
		echo json_encode ( $this->model_dp_sal_exa_in () );
	}
	function c_dp_sal_exa_info() {
		echo un_iconv ( $this->model_dp_sal_exa_info () );
	}
	function c_dp_sal_exa_del() {
		$id = $_POST ['id'];
		echo json_encode ( un_iconv ( $this->model_dp_sal_exa_del ( $id ) ) );
	}
	/**
	 * 审批检查
	 */
	function c_dp_sal_exa_ck() {
		echo un_iconv ( $this->model_dp_sal_exa_ck () );
	}
	/**
	 * 员工信息栏目-后
	 */
	function c_dp_user() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = "年份：<select id='seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份：<select id='seapm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$isHidden = "true";
		if($this->isWyLeader()){
			$isHidden = "false";
		}
		$navinfo .= "</select>";
		$navinfo .= " 部门：<input type='text' id='seadept' name='seadept' value='' size='15'/><input type='checkbox' id='isEq' />完全匹配 ";
		$navinfo .= " 员工：<input type='text' id='seaname' name='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$navinfo .= "  <input type='button' id='sub' value='基本工资导出' onclick='xlsClickFun()' />";
		$navinfo .= "  <input type='button' id='sub' value='工资统计导出' onclick='xlsClickFun(1)' />";
		$this->show->assign ( 'user_list', '?model=salary&action=dp_user_list' );
		$this->show->assign ( 'edit_list', '?model=salary&action=dp_user_edit' );
		$this->show->assign ( 'isHidden', $isHidden );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_dp-user' );
	}
	/**
	 * 年度工资更新
	 */
	function c_dp_ymd() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_ymd_list' );
		$this->show->assign ( 'user_capt', '年度调薪列表' );
		$this->show->display ( 'salary_dp-ymd' );
	}
	/**
	 * 年度调薪列表
	 */
	function c_dp_ymd_list() {
		echo json_encode ( $this->model_dp_mdf_list ( array (
				'ymd' 
		), 'nym' ) );
	}
	/**
	 * 员工工资更新
	 */
	function c_dp_mdf() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_mdf_list' );
		$this->show->assign ( 'user_capt', '调薪列表' );
		$isHidden = "true";
		$isHiddenSubName = "";
		$isHiddenSubAlign = "";
		$isHiddenSubWidth = "";
		if($this->isWyLeader()) {
			$isHidden = "false";
			$isHiddenSubName = ",'补贴发放部分'";
			$isHiddenSubAlign = ",'right'";
			$isHiddenSubWidth = ",130";
		}
		if($this->isWyLeader() == true) {
			$isHidden = "false";
		}
		$this->show->assign ( 'isHidden', $isHidden );
		$this->show->assign ( 'isHiddenSubName', $isHiddenSubName );
		$this->show->assign ( 'isHiddenSubAlign', $isHiddenSubAlign );
		$this->show->assign ( 'isHiddenSubWidth', $isHiddenSubWidth );
		$this->show->display ( 'salary_dp-modify' );
	}
	/**
	 * 员工列表
	 */
	function c_dp_mdf_list() {
		echo json_encode ( $this->model_dp_mdf_list ( array (
				'nym_4',
				'nym_3',
				'nym_2',
				'nym_1',
				'nym_0',
				'nym_wy1',
				'nym_wy2',
				'nym_xs_3',
				'nym_xs_5',
				'nym_xs_1',
		        'nym_yqyb3'
		), 'nym' ) );
	}
	/**
	 * 工资周期
	 */
	function c_close_stat() {
		$this->show->assign ( 'data_list', $this->model_close_stat () );
		$this->show->display ( 'salary_close-stat' );
	}
	function c_close_stat_in() {
		echo $this->model_close_stat_in ();
	}
	/**
	 * 员工处理
	 */
	function c_dp_mdf_in() {
		echo json_encode ( $this->model_dp_mdf_in () );
	}
	/**
	 * 非年度调薪
	 */
	function c_dp_nym() {
		$pow = $this->model_dp_pow ();
		global $func_limit;
		if (! empty ( $pow ['2'] )) {
			$todept = trim ( implode ( ',', $pow ['1'] ) . ',' . implode ( ',', $pow ['2'] ) . ',' . $func_limit ['浏览部门'], ',' );
			// $this->show->assign('user_win','module/user_select_single?todept='.trim(implode(',', $pow['1']).','.implode(',', $pow['2']),',') );
		} elseif (! empty ( $pow ['1'] )) {
			$todept = trim ( implode ( ',', $pow ['1'] ) . ',' . $func_limit ['浏览部门'], ',' );
			// $this->show->assign('user_win','module/user_select_single?todept='.trim( implode(',', $pow['1'])) );
		} else {
			$todept = empty ( $func_limit ['浏览部门'] ) ? '-' : $func_limit ['浏览部门'];
			// $this->show->assign('user_win','module/user_select_single?todept=-' );
		}
		$this->show->assign ( 'user_win', 'module/user_select_single?todept=' . $todept );
		$this->show->display ( 'salary_dp-nym' );
	}
	function c_dp_nym_in() {
		echo json_encode ( $this->model_dp_nym_in () );
	}
	function c_dp_nym_xls() {
		$xlsUrl = 'attachment/xls_model/非年度调薪申请表.xls';
		$expenCeilingTd = '';
		$isWy = $this->isWyLeader();
		if($isWy) {
			$xlsUrl = 'attachment/xls_model/非年度网优调薪申请表.xls';
			$expenCeilingTd = '<td align="center">调整后补贴发放部分</td>';
		}
		
		$this->show->assign ( 'xls_url', $xlsUrl );
		$this->show->assign ( 'flag_val', $_REQUEST ['flag'] );
		$this->show->assign ( 'expenCeilingTd', $expenCeilingTd  );
		$this->show->assign ( 'data_list', $this->model_dp_nym_xls () );
		$this->show->display ( 'salary_dp-nym-xls' );
	}
	function c_dp_nym_xls_in() {
		echo json_encode ( $this->model_dp_nym_xls_in () );
	}
	/**
	 * 季度奖
	 */
	function c_dp_fla() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_fla_list' );
		$this->show->assign ( 'edit_list', '?model=salary&action=dp_fla_in' );
		$this->show->assign ( 'user_capt', '季度奖列表' );
		$this->show->display ( 'salary_dp-fla' );
	}
	function c_dp_fla_list() {
		echo json_encode ( $this->model_dp_fla_list () );
	}
	function c_dp_fla_in() {
		echo json_encode ( $this->model_dp_fla_in () );
	}
	function c_dp_fla_new() {
		$pow = $this->model_dp_pow ();
		$this->show->assign ( 'user_win', 'module/user_select?todept=' . implode ( ',', $pow ['1'] ) );
		$this->show->display ( 'salary_dp-fla-new' );
	}
	function c_dp_fla_new_in() {
		echo json_encode ( $this->model_dp_fla_new_in () );
	}
	function c_dp_fla_xls() {
		$this->show->assign ( 'data_list', $this->model_dp_sdy_xls () );
		$this->show->display ( 'salary_dp-fla-xls' );
	}
	function c_dp_fla_xls_in() {
		echo json_encode ( $this->model_dp_fla_new_in () );
	}
	/**
	 * 项目奖
	 */
	function c_dp_pro() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_pro_list' );
		$this->show->assign ( 'user_capt', '项目奖列表' );
		$this->show->display ( 'salary_dp-pro' );
	}
	
	/**
	 * 项目奖列表
	 */
	function c_dp_pro_list() {
		echo json_encode ( $this->model_dp_mdf_list ( array (
				'pro',
				'pro_3',
				'pro_5',
				'pro_1',
				'pro_0' 
		) ) );
	}
	/**
	 * 项目奖处理
	 */
	function c_dp_pro_in() {
		echo json_encode ( $this->model_dp_pro_in () );
	}
	function c_dp_pro_new() {
		$pow = $this->model_dp_pow ();
		$this->show->assign ( 'user_win', 'module/user_select?todept=' . implode ( ',', $pow ['1'] ) );
		$this->show->display ( 'salary_dp-pro-new' );
	}
	/**
	 * 项目奖导入
	 */
	function c_dp_pro_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_dp_pro_xls ( $ckt ) );
		$this->show->display ( 'salary_dp-pro-xls' );
	}
	/**
	 * 导入处理
	 */
	function c_dp_pro_xls_in() {
		echo json_encode ( $this->model_dp_pro_xls_in () );
	}
	/**
	 * 项目奖
	 */
	function c_dp_bos() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_bos_list' );
		$this->show->assign ( 'user_capt', '奖金列表' );
		$this->show->display ( 'salary_dp-bos' );
	}
	/**
	 * 项目奖列表
	 */
	function c_dp_bos_list() {
		echo json_encode ( $this->model_dp_mdf_list ( array (
				'bos' 
		) ) );
	}
	/**
	 * 项目奖处理
	 */
	function c_dp_bos_in() {
		echo json_encode ( $this->model_dp_bos_in () );
	}
	function c_dp_bos_new() {
		$pow = $this->model_dp_pow ();
		$this->show->assign ( 'user_win', 'module/user_select?todept=' . implode ( ',', $pow ['1'] ) );
		$this->show->display ( 'salary_dp-bos-new' );
	}
	/**
	 * 项目奖导入
	 */
	function c_dp_bos_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_dp_bos_xls ( $ckt ) );
		$this->show->display ( 'salary_dp-bos-xls' );
	}
	/**
	 * 导入处理
	 */
	function c_dp_bos_xls_in() {
		echo json_encode ( $this->model_dp_bos_xls_in () );
	}
	/**
	 * 年终奖
	 */
	function c_dp_yeb() {
		$manflag = $_GET ['manflag'];
		$sealist .= ' 年份：<select name="seay" id="seay">';
		for($i = 2010; $i <= date ( "Y" ); $i ++) {
			if ($this->yebyear == $i) {
				$sealist .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$sealist .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$sealist .= '</select>';
		$sealist .= ' 姓名：<input type="text" name="seaname" id="seaname" value="" style="width:100px;"/>';
		$sealist .= ' 部门：<input type="text" name="seadept" id="seadept" value="" style="width:100px;"/>';
		$sealist .= ' <input name="btn" type="button" id="btn" value="查询" onclick="gridNavSeaFun()"/>';
		$sealist .= ' <input type="button" value="导入年终奖" onclick="showNewExcel()" />';
		$sealist .= ' <input type="button" value="导出年终奖" onclick="ExcelOutFun()" />';
		if ($manflag == 'man') {
			$this->show->assign ( 'data_list', '?model=salary&action=dp_yeb_list&manflag=' . $manflag );
			$this->show->assign ( 'excel_list', '?model=salary&action=dp_yeb_xls&manflag=' . $manflag . '&TB_iframe=true&height=650' );
			$this->show->assign ( 'user_capt', '年终奖列表 ' . $sealist );
			$this->show->display ( 'salary_dp-yeb-man' );
		} else {
			$this->show->assign ( 'data_list', '?model=salary&action=dp_yeb_list' );
			$this->show->assign ( 'excel_list', '?model=salary&action=dp_yeb_xls&TB_iframe=true&height=650' );
			$this->show->assign ( 'user_capt', '年终奖列表 ' . $sealist );
			$this->show->display ( 'salary_dp-yeb' );
		}
	}
	function c_dp_yeb_fn() {
		global $func_limit;
		$sealist .= '<select name="comflag" id="comflag">';
		$sealist .= '<option value="">所有员工</option>';
		$sealist .= '<option value="com">公司员工</option>';
		$sealist .= '<option value="exp">外派员工</option>';
		$sealist .= '</select>';
		$sealist .= ' 年份：<select name="seay" id="seay">';
		for($i = 2010; $i <= date ( "Y" ); $i ++) {
			if ($this->yebyear == $i) {
				$sealist .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$sealist .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$sealist .= '</select>';
		$sealist .= ' 姓名：<input type="text" name="seaname" id="seaname" value="" style="width:100px;"/>';
		$sealist .= ' 部门：<input type="text" name="seadept" id="seadept" value="" style="width:100px;"/>';
		$sealist .= ' <input name="btn" type="button" id="btn" value="查询" onclick="gridNavSeaFun()"/>';
		if ($func_limit ['年终奖报表'] == '1') {
			$sealist .= '<input type="button" id="sub" value="年终奖报表" onclick="yebClickFun()"';
		}
		$this->show->assign ( 'data_list', '?model=salary&action=dp_yeb_fn_list' );
		$this->show->assign ( 'user_capt', $sealist );
		$this->show->display ( 'salary_dp-yeb-fn' );
	}
	/**
	 * 年终奖列表
	 */
	function c_dp_yeb_list() {
		echo json_encode ( $this->model_dp_yeb_list () );
	}
	function c_dp_yeb_fn_list() {
		global $func_limit;
		$dppow = $this->model_dp_pow ();
		if (strpos ( "perm" . $func_limit ['浏览部门'], ";" ) > 0) {
			$sqlflag = "  ";
		} else if (! empty ( $func_limit ['浏览部门'] )) {
			$sqlflag = " and
                ( s.deptid in ('" . implode ( "','", $dppow ['1'] ) . "','" . implode ( "','", $dppow ['2'] ) . "')
                    or s.userid='" . $_SESSION ['USER_ID'] . "'
                    or s.deptid in ( " . trim ( $func_limit ['浏览部门'], ',' ) . " )
                )
                and s.usersta!=3 ";
		} else {
			$sqlflag = " and
                ( s.deptid in ('" . implode ( "','", $dppow ['1'] ) . "','" . implode ( "','", $dppow ['2'] ) . "')
                    or s.userid='" . $_SESSION ['USER_ID'] . "' )
                and s.usersta!=3 ";
		}
		echo json_encode ( $this->model_dp_yeb_list ( $sqlflag ) );
	}
	/**
	 * 年终奖导入
	 */
	function c_dp_yeb_xls() {
		$ckt = time ();
		$manflag = $_GET ['manflag'];
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_dp_yeb_xls ( $ckt ) );
		if ($manflag == 'hr') {
			$this->show->display ( 'salary_dp-yeb-hr-xls' );
		} else {
			$this->show->display ( 'salary_dp-yeb-xls' );
		}
	}
	/**
	 * 年终奖导入处理
	 */
	function c_dp_yeb_xls_in() {
		echo json_encode ( $this->model_dp_yeb_xls_in () );
	}
	function c_dp_yeb_rep() {
		$seay = isset ( $_GET ['seay'] ) ? $_GET ['seay'] : $this->yebyear;
		$sealist = '';
		$sealist .= ' 年份：<select name="seay" id="seay" onchange="changeDt()">';
		for($i = 2010; $i <= date ( "Y" ); $i ++) {
			if ($seay == $i) {
				$sealist .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$sealist .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$sealist .= '</select>';
		$this->show->assign ( 'yeb_sea', $sealist );
		$this->show->assign ( 'yeb_rep', $this->model_dp_yeb_rep () );
		$this->show->display ( 'salary_dp-yeb-rep' );
	}
	
	/**
	 * 补贴
	 */
	function c_dp_sdy() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sdy_list' );
		$this->show->assign ( 'user_capt', '补贴列表' );
		$this->show->display ( 'salary_dp-sdy' );
	}
	function c_dp_sdy_list() {
		echo json_encode ( $this->model_dp_sdy_list () );
	}
	function c_dp_sdy_new() {
		$pow = $this->model_dp_pow ();
		$this->show->assign ( 'user_win', 'module/user_select?todept=' . implode ( ',', $pow ['1'] ) );
		$this->show->display ( 'salary_dp-sdy-new' );
	}
	function c_dp_sdy_new_in() {
		echo json_encode ( $this->model_dp_sdy_new_in () );
	}
	function c_dp_sdy_xls() {
		$this->show->assign ( 'data_list', $this->model_dp_sdy_xls () );
		$this->show->display ( 'salary_dp-sdy-xls' );
	}
	function c_dp_sdy_xls_in() {
		echo json_encode ( $this->model_dp_sdy_new_in () );
	}
	/**
	 * 员工属性
	 */
	function c_dp_user_type() {
		$this->show->assign ( 'user_win', 'module/user_select?x=1' );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_user_typel' );
		$this->show->assign ( 'user_capt', '员工信息-项目属性' );
		$this->show->display ( 'salary_dp-user-type' );
	}
	function c_dp_user_typel() {
		echo json_encode ( $this->model_dp_user_typel () );
	}
	function c_dp_user_typen() {
		echo json_encode ( $this->model_dp_user_typen () );
	}
	/**
	 * 财务对账-鼎利
	 */
	function c_fn_stat() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		global $func_limit;
		$navinfo = $this->get_com_sel ( true, 'part', 'seacom', '', $func_limit ['财务发放公司'] );
		$navinfo .= "<select id='seacompt'><option value='0'>公司员工</option><option value='1'>外派员工</option><option value='-'>不限</option> ";
		$navinfo .= "</select>年份<select id='seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份<select id='seapm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " 部门<input type='text' id='seadept' value='' size='8'/>";
		$navinfo .= " 员工<input type='text' id='seaname' value='' size='8'/>";
		$navinfo .= " <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$navinfo .= "<input type='button' value='财务' class='BigButton' onclick='btnClick(1," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='个税' class='BigButton' onclick='btnClick(2," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='银行' class='BigButton' onclick='btnClick(3," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='部门' class='BigButton' onclick='btnClick(4," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='邮件发送' class='BigButton' onclick='btnClick(5," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='部门全年' class='BigButton' onclick='btnClick(6," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='年度员工' class='BigButton' onclick='btnClick(7," . $this->nowy . "," . $this->nowm . ")' />";
		$this->show->assign ( 'user_list', '?model=salary&action=fn_user_list' );
		$this->show->assign ( 'edit_list', '' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_fn-stat' );
	}
	/**
	 * 财务管理-子公司
	 */
	function c_fin_index() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo .= "<select id='seacompt'><option value='0'>公司员工</option><option value='1'>外派员工</option><option value='-'>不限</option> ";
		$navinfo .= "</select>年份<select id='seapy'> <option value='-'>不限</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> 月份<select id='seapm'> <option value='-'>不限</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " 部门<input type='text' id='seadept' value='' size='8'/>";
		$navinfo .= " 员工<input type='text' id='seaname' value='' size='8'/>";
		$navinfo .= " <input type='button' id='sub' value='查询' onclick='gridNavSeaFun()' />";
		$navinfo .= "<input type='button' value='财务' class='BigButton' onclick='btnClick(1," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='邮件发送' class='BigButton' onclick='btnClick(5," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<a href='?model=salary&action=exitSalary&mod=fin' style='margin-top: 2px;margin-left: 20px;font-weight:normal;color:blue;'>退出薪资系统</a>";
		// $navinfo.="<input type='button' value='年终奖' class='btn' onclick='yTab()' />";
		$this->show->assign ( 'user_list', '?model=salary&action=fin_user_list' );
		$this->show->assign ( 'edit_list', '' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_fin-stat' );
	}
	function c_hr_user_fn_xls() {
		$_REQUEST ['ty'] = 'hr';
		$this->model_fn_xls ();
	}
	function c_fin_user_list() {
		echo json_encode ( $this->model_fin_user () );
	}
	function c_fn_user_list() {
		echo json_encode ( $this->model_fn_user () );
	}
	function c_fn_xls() {
		$this->model_fn_xls ();
	}
	function c_fn_xls_ck() {
		$syear = $_REQUEST ["sy"];
		$smon = $_REQUEST ["sm"];
		$nowymt = strtotime ( $this->nowy . '-' . $this->nowm . '-01' );
		$dtendy = date ( 'Y-m', $nowymt );
		$dtendt = date ( 't', $nowymt );
		$dtend = $dtendy . '-' . $dtendt;
		$dtendw = date ( 'w', strtotime ( $dtend ) );
		if ($dtendw == '0') {
			$dtendwork = $dtendy . '-' . ($dtendt - 2);
		} elseif ($dtendw == '6') {
			$dtendwork = $dtendy . '-' . ($dtendt - 1);
		} else {
			$dtendwork = $dtend;
		}
		if ($dtendwork == '2011-01-31') { //
			$dtendwork = '2011-01-25';
		}
		if ($syear < $this->nowy || ($syear = $this->nowy && $smon < $this->nowm) || (time ( date ( 'Y-m-d' ) ) >= strtotime ( $dtendwork ) && $smon = $this->nowm && $syear = $this->nowy)) {
			if (time ( date ( 'Y-m-d' ) ) >= strtotime ( $dtendwork ) && $smon = $this->nowm && $syear = $this->nowy) {
				$this->model_salary_ini ( true );
			}
			$responce->res = 'pass';
			$responce->url = '?model=salary&action=fn_xls';
		} else {
			$responce->res = 'unpass';
		}
		echo json_encode ( $responce );
	}
	function c_fn_user_info() {
		$this->show->assign ( 'data_list', '?model=salary&action=hr_info_list' );
		$this->show->assign ( 'user_capt', '员工信息管理列表' );
		$this->show->display ( 'salary_fn-info' );
	}
	/**
	 * 身份证检查
	 */
	function c_ck_idcard() {
		echo un_iconv ( $this->model_ck_idcard () );
	}
	/*
	 * 休息修改
	 */
	function c_fn_info_in() {
		echo json_encode ( $this->model_fn_info_in () );
	}
	// 年终奖
	function c_fn_yeb() {
		$sealist .= '<select name="comflag" id="comflag">';
		$sealist .= '<option value="">所有员工</option>';
		$sealist .= '<option value="com">公司员工</option>';
		$sealist .= '<option value="exp">外派员工</option>';
		$sealist .= '</select>';
		$sealist .= ' 年份：<select name="seay" id="seay">';
		for($i = 2010; $i <= date ( "Y" ); $i ++) {
			if ($this->yebyear == $i) {
				$sealist .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$sealist .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$sealist .= '</select>';
		$sealist .= ' 姓名：<input type="text" name="seaname" id="seaname" value="" style="width:80px;"/>';
		$sealist .= ' 部门：<input type="text" name="seadept" id="seadept" value="" style="width:80px;"/>';
		$sealist .= '<input name="btn" type="button" id="btn" value="查询" onclick="gridNavSeaFun()"/>';
		$sealist .= '<input name="btn" type="button" id="btn" value="财务报表" onclick="excelOutFun(1)" />';
		$sealist .= '<input name="btn" type="button" id="btn" value="个税报表" onclick="excelOutFun(2)" />';
		$sealist .= '<input name="btn" type="button" id="btn" value="银行报表" onclick="excelOutFun(3)" />';
		$sealist .= '<input name="btn" type="button" id="btn" value="部门统计" onclick="excelOutFun(4)" />';
		// $sealist.='<input name="btn" type="button" id="btn" value="邮件发送" onclick="emailFun()" />';
		$sealist .= '<input name="btn" type="button" id="btn" value="导出-外派" onclick="expExcelOutFun()" />';
		$this->show->assign ( 'data_list', '?model=salary&action=fn_yeb_list' );
		$this->show->assign ( 'edit_url', '?model=salary&action=fn_yeb_edit' );
		$this->show->assign ( 'excel_out', '?model=salary&action=fn_yeb_xls' );
		$this->show->assign ( 'exp_excel_out', '?model=salary&action=fn_yeb_xls_exp' );
		$this->show->assign ( 'email_url', '?model=salary&action=fn_yeb_email' );
		$this->show->assign ( 'user_capt', $sealist );
		$this->show->assign ( 'yearck', $this->yebyear );
		$this->show->display ( 'salary_fn-yeb' );
	}
	function c_fn_yeb_fin() {
		$sealist .= '<select name="comflag" id="comflag">';
		$sealist .= '<option value="">所有员工</option>';
		$sealist .= '<option value="com">公司员工</option>';
		$sealist .= '<option value="exp">外派员工</option>';
		$sealist .= '</select>';
		$sealist .= ' 年份：<select name="seay" id="seay">';
		for($i = 2010; $i <= date ( "Y" ); $i ++) {
			if ($this->yebyear == $i) {
				$sealist .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$sealist .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$sealist .= '</select>';
		$sealist .= ' 姓名：<input type="text" name="seaname" id="seaname" value="" style="width:80px;"/>';
		$sealist .= ' 部门：<input type="text" name="seadept" id="seadept" value="" style="width:80px;"/>';
		$sealist .= '<input name="btn" type="button" id="btn" value="查询" onclick="gridNavSeaFun()"/>';
		$sealist .= '<input name="btn" type="button" id="btn" value="年终奖导出" onclick="excelOutFun(1)" />';
		// $sealist.='<input name="btn" type="button" id="btn" value="邮件发送" onclick="emailFun()" />';
		$this->show->assign ( 'data_list', '?model=salary&action=fn_yeb_list' );
		$this->show->assign ( 'excel_out', '?model=salary&action=fn_yeb_xls' );
		$this->show->assign ( 'email_url', '?model=salary&action=fn_yeb_email' );
		$this->show->assign ( 'user_capt', $sealist );
		$this->show->assign ( 'yearck', $this->yebyear );
		$this->show->display ( 'salary_fn-yeb-fin' );
	}
	function c_fn_yeb_list() {
		echo json_encode ( $this->model_fn_yeb_list () );
	}
	function c_fn_yeb_edit() {
		echo $this->model_fn_yeb_edit ();
	}
	function c_fn_yeb_xls() {
		echo $this->model_fn_yeb_xls ( 'com' );
	}
	function c_fn_yeb_email() {
		echo $this->model_fn_yeb_xls ( 'email' );
	}
	function c_fn_yeb_xls_exp() {
		echo $this->model_fn_yeb_xls ( 'exp' );
	}
	function c_fn_yeb_xls_hr() {
		echo $this->model_fn_yeb_xls ( 'hr' );
	}
	function c_fn_yeb_xls_dao() {
		echo $this->model_fn_yeb_xls ( 'dao' );
	}
	function c_salary_info() {
		echo $this->model_salary_info ();
	}
	function c_hr_xls_out() {
		$this->model_hr_xls_out ();
	}
	function c_bi_deptFee() {
		$this->model_bi_deptFee();
	}
	function c_bi_showDeptFee() {
 		print_r($this->model_deptFeeOfMonth());
	}
	function c_temp_deptFeeExport() {
		$this->model_tempDeptExport();
	}
	/**
	 *	salaryTemp表数据同步到salary表（更新薪资表收入项上限值）
	 * @throws Exception
	 */
	function c_salary_salaryTempToSalary() {
		$this->model_salaryTempToSalary ();
	}
	function xls_out() {
		$xlsStr = '<select id="xls_year">';
		for($i = 2009; $i <= $this->nowy; $i ++) {
			if ($i == $this->nowy) {
				$xlsStr .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$xlsStr .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$xlsStr .= '    </select> 年';
		$xlsStr .= '<select id="xls_mon"><option value="-">不限</option>';
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $this->nowm) {
				$xlsStr .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$xlsStr .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$xlsStr .= '    </select> 月';
		$this->show->assign ( 'xls_list', $xlsStr );
	}
	function c_xls_out() {
		$flag = $_REQUEST ['flag'];
		$xls_arr = array (
				'dp_tol' => '工资统计表',
				'lin' => '离职工资统计表',
				'lin2' => '离职工资统计表',
				'dp_lin' => '离职工资统计表',
				'fn_pro' => '工资项目统计表',
				'hr_sdy' => '工资补贴导出数据表',
				'hr_div' => '工资专区表',
				'dp_detail' => '工资明细表',
				'hr_detail' => '工资明细表-人事',
				'hr_jf' => '工资社保公积金表',
				'ext' => '工资额外人员明细表',
				'gs_tol' => $_REQUEST ['sy'] . '工资部门分析表',
				'gs_cp' => '工资统计对比',
				'wy_salary' => '网优薪酬表',
				'salary_base' => '薪酬结构表',
				'hr_yuti' => '补贴预提表',
				'salary_adjust' => '月度调薪记录表'
		);
		$this->model_xls_out ( $flag, $xls_arr [$flag] );
	}
	/**
	 */
	function c_sal_tools() {
		$this->show->display ( 'salary_sal-tools' );
	}
	function c_sal_tools_in() {
		echo json_encode ( $this->model_sal_tools_in () );
	}
	/**
	 * 工资统计
	 */
	function c_dp_stat_float() {
		$this->model_dp_stat_float ();
	}
	// ##############################################结束#################################################
	/**
	 * 析构函数
	 */
	function __destruct() {
	}
	function c_updateSalaryPay() {
		$this->updateSalaryPay ();
	}
	function c_getSalary_info() {
		$gl = new includes_class_global ();
		$salary = $gl->get_salaryDept_info( '2017', '3' );
		print_r ( $salary );
		die ();
	}
	function c_encryptDeal() {
		$this->model_encryptDeal ();
	}
	function c_decryptDeal() {
		$this->model_decryptDeal ();
	}
	//密钥加解密方法
	function c_getK() {
		$this->getK();
	}
	
	function c_temp_proToClaimExpenses() {
		$salaryPayId = "dawei.liu,yanhui.zhang";
        $salaryPayIds = explode ( ',', $salaryPayId );
        for($index = 0; $index < count ( $salaryPayIds ); $index ++) {
			$this->model_proToClaimExpenses($salaryPayIds [$index],'2017','5');
        }
	}
	
	function c_getSalaryProInfo() {
		$gl = new includes_class_global();
		$salary = $gl->get_salarypro_info(2016, 10);
		 
		print_r($salary);
	}
	
	function c_feeCancel() {
		$this->feeCancel();
	}
	
	function c_getKh() {
		$logDao = new model_engineering_worklog_esmworklog ();
		$kh = $logDao->getAssessInfo_d ( 2017, 3 );
		print_r($kh);
		die();
	}
}
?>