<?php
class controller_salary extends model_salary_class {
	public $show; // ģ����ʾ
	
	/**
	 * ���캯��
	 */
	function __construct() {
		parent::__construct ();
		$this->show = new show ();
	}
	
	/**
	 * Ĭ�Ϸ�����ʾ
	 */
	function index() {
	}
	// ����ģ��
	// ���ʰ���
	function c_hr_exa() {
		global $func_limit;
		$this->show->assign ( 'display', $func_limit ['���½��ײ�������'] == 1 ? 'none' : '' );
		
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
		// ����
		$this->show->assign ( 'hf_url', '?model=module_report&action=upExcel&repkey=gzhf' );
		$this->show->assign ( 'cb_url', '?model=module_report&action=upExcel&repkey=gzfuxcb' );
		$this->show->assign ( 'jt_url', '?model=module_report&action=upExcel&repkey=gzxmjt' );
		// �������ʵ���
		$this->show->assign ( 'jb_url', '?model=salary&action=hr_jb' );
		$this->show->assign ( 'gw_url', '?model=salary&action=hr_gw' );
		// ���̹��ʵ���
		$this->show->assign ( 'gc_url', '?model=salary&action=hr_gc' );
		// ͨ�Ź��ʵ���
		$this->show->assign ( 'tx_url', '?model=salary&action=hr_tx' );
		// ��Ŀ��ϸ����
		$this->show->assign ( 'prod_url', '?model=salary&action=hr_prod' );
		// ���˹����ܱ�
		$this->show->assign ( 'pro_url', '?model=salary&action=hr_pro' );
		// н�ʽṹ
		$this->show->assign( 'user_salary_url', '?model=salary&action=hr_user_salary');
		// �û���н��¼
		$this->show->assign( 'user_adjust_url', '?model=salary&action=hr_user_adjust');
		// �¶ȵ�н��¼
		$this->show->assign( 'mon_adjust_url', '?model=salary&action=hr_mon_adjust');
		// ����Ԥ��
		$this->show->assign( 'yuti_url', '?model=salary&action=hr_yuti');
		
		$this->show->display ( 'salary_hr-exa' );
	}
	
	/**
	 * �¶ȵ�н��¼
	 */
	function c_hr_mon_adjust() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = $this->get_com_purview ();
		$navinfo .= "��ݣ�<select id='seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·ݣ�<select id='seapm'> <option value='-'>����</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " ���ţ�<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= " <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$navinfo .= " <input type='button' id='explore' value='����' onclick='exploreExcel()' />";
		
		$isHidden = "true";
		$isHiddenSubName = "";
		$isHiddenSubAlign = "";
		$isHiddenSubWidth = "";
		if($this->isWyLeader()) {
			$isHidden = "false";
			$isHiddenSubName = ",'�������Ų���'";
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
	 * �������ս�
	 */
	function c_hr_yeb() {
		$sealist .= ' ��ݣ�<select name="seay" id="seay">';
		for($i = 2010; $i <= date ( "Y" ); $i ++) {
			if ($this->yebyear == $i) {
				$sealist .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$sealist .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$sealist .= '</select>';
		$sealist .= ' ������<input type="text" name="seaname" id="seaname" value="" style="width:100px;"/>';
		$sealist .= ' ���ţ�<input type="text" name="seadept" id="seadept" value="" style="width:100px;"/>';
		$sealist .= ' <input name="btn" type="button" id="btn" value="��ѯ" onclick="gridNavSeaFun()"/>';
		$sealist .= ' <input type="button" value="�������ս�" onclick="showNewExcel()" />';
		$sealist .= ' <input type="button" value="�������ս�" onclick="hrExcelOutFun()" />';
		$sealist .= '<input name="btn" type="button" id="btn" value="��������" onclick="expExcelOutFun()" />';
		$this->show->assign ( 'data_list', '?model=salary&action=hr_yeb_list' );
		$this->show->assign ( 'excel_list', '?model=salary&action=hr_yeb_xls&TB_iframe=true&height=650' );
		$this->show->assign ( 'user_capt', '���ս��б� ' . $sealist );
		$this->show->display ( 'salary_hr-yeb' );
	}
	/**
	 * �������ս���Ϣ
	 */
	function c_hr_yeb_list() {
		// $sqlflag=" and h.userlevel='4' ";
		echo json_encode ( $this->model_dp_yeb_list ( $sqlflag ) );
	}
	/**
	 * �������յ���
	 */
	function c_hr_yeb_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_dp_yeb_xls ( $ckt, 'hr' ) );
		$this->show->display ( 'salary_c-hr-yeb-xls' );
	}
	/**
	 * ���ս����봦��
	 */
	function c_hr_yeb_xls_in() {
		echo json_encode ( $this->model_dp_yeb_xls_in ( 'hr' ) );
	}
	// ���¹���
	function c_hr() {
		$this->show->assign ( 'grid_url', '?model=salary&action=hr_tree' );
		$this->show->assign ( 'first_tab', '���ʰ���' );
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
	 * ��������
	 */
	function c_hr_tree() {
		$responce->rows [0] ['id'] = '1';
		$responce->rows [0] ['cell'] = un_iconv ( array (
				'1',
				'���ʰ���',
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
				'������Ϣ',
				'?model=salary&action=hr_user',
				'1',
				'',
				'',
				'true',
				'true' 
		) );
		global $func_limit;
		if ($func_limit ['��������'] == 1) {
			// ר��
// 			$responce->rows [2] ['id'] = '3';
// 			$responce->rows [2] ['cell'] = un_iconv ( array (
// 					'3',
// 					'ר����Ϣ',
// 					'?model=salary&action=hr_user_div',
// 					'1',
// 					'',
// 					'',
// 					'true',
// 					'true' 
// 			) );
			// ������Ա
			$responce->rows [2] ['id'] = '4';
			$responce->rows [2] ['cell'] = un_iconv ( array (
					'3',
					'������Ϣ',
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
					'����ͳ��',
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
	 * ������ְ
	 */
	function c_hr_join() {
		$navinfo = " ���ţ�<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$this->xls_out ();
		$navinfo .= " <input type='button' value='��ְ����' onclick='showNewExcel()' />";
		$this->show->assign ( 'data_list', '?model=salary&action=hr_join_list' );
		$this->show->assign ( 'user_capt', 'Ա����ְ�б�' . $navinfo );
		$this->show->display ( 'salary_hr-join' );
	}
	/**
	 * ��ְԱ���б�
	 */
	function c_hr_join_list() {
		echo json_encode ( $this->model_hr_join_list ());
	}
	/**
	 * ��ְ����
	 */
	function c_hr_join_in() {
		echo json_encode ( $this->model_hr_join_in () );
	}
	
	/**
	 * ��ְ��������
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
	 * ��ְ�������봦��
	 */
	function c_hr_join_xls_in() {
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm 
		);
		echo json_encode ( $this->model_xls_in ( 'hr_join', $info ) );
	}

	/**
	 * ��ְ��������
	 */
	function c_hr_leave_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm,
				'list' => array (
						'Ա����',
						'Ա��',
						'��ְ����' 
				) 
		);
		$this->show->assign ( 'data_list', $excelUtil->xls_check ( $ckt, 'hr_leave', 'str', $info ) );
		$this->show->display ( 'salary_hr-leave-xls' );
	}
	
	/**
	 * ��ְ�����ύ
	 */
	function c_hr_leave_xls_in() {
		$ckt = $_POST['ckt'];
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm,
				'list' => array (
						'Ա����',
						'Ա��',
						'��ְ����'
				)
		);
		$responce = "";
		$data = $excelUtil->xls_check ( $ckt, 'hr_leave', 'data', $info );
		foreach ($data as $key => $val) {
			if($val['��֤'] == "1") {
				$responce = $this->model_hr_leave_in_ext($val["rand_key"],$val["��ְ����"],"lz","");
			}
		}
		echo json_encode ($responce);
	}
		
	/**
	 * �������ʵ���
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
						'Ա����',
						'����',
						'��������',
						'��λ����',
						'��Ч����' 
				) 
		);
		$navinfo .= "<br>��Ӧ����Ч��� <select id='seapy' name='seapy'>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·� <select id='seapm' name='seapm'>";
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
	 * ��λ���ʵ���
	 */
	function c_hr_gw() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm,
				'list' => array (
						'Ա����',
						'����',
						'��λ����' 
				) 
		);
		$this->show->assign ( 'notice_info', '��Ӧ����Ч�·ݣ�' . $this->nowy . '��' . $this->nowm . '��' );
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
	 * ���̹��ʵ���
	 */
	function c_hr_gc() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm,
				'list' => array (
						'Ա����',
						'����',
						'�¶ȳ���ϵ��',
						'�¶ȿ���ϵ��',
						'��Ч����',
						'��������',
						'��ʱס�޲���',
						'��������',
						'ͨ�Ž���' 
				) 
		);
		$this->show->assign ( 'notice_info', '��Ӧ����Ч�·ݣ�' . $this->nowy . '��' . $this->nowm . '��' );
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
	 * ͨ�Ź��ʵ���
	 */
	function c_hr_tx() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$excelUtil = new model_salary_excelUtil ();
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm,
				'list' => array (
						'Ա����',
						'����',
						'ͨ�Ž���' 
				) 
		);
		$this->show->assign ( 'notice_info', '��Ӧ����Ч�·ݣ�' . $this->nowy . '��' . $this->nowm . '��' );
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
	 * ת������
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
	 * ��ְ�������봦��
	 */
	function c_hr_pass_xls_in() {
		$info = array (
				'pyear' => $this->nowy,
				'pmon' => $this->nowm 
		);
		echo json_encode ( $this->model_xls_in ( 'hr_pass', $info ) );
	}
	
	/**
	 * ����ת��
	 */
	function c_hr_pass() {
		$navinfo = " ���ţ�<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$this->xls_out ();
		$navinfo .= " <input type='button' value='ת������' onclick='showNewExcel()' />";
		$this->show->assign ( 'data_list', '?model=salary&action=hr_pass_list' );
		$this->show->assign ( 'user_capt', 'Ա��ת���б�' . $navinfo );
		$this->show->display ( 'salary_hr-pass' );
	}
	/**
	 * ת��Ա���б�
	 */
	function c_hr_pass_list() {
		echo json_encode ( $this->model_hr_pass_list () );
	}
	/**
	 * ת������
	 */
	function c_hr_pass_in() {
		echo json_encode ( $this->model_hr_pass_in () );
	}
	/**
	 * Ա����ְ
	 */
	function c_hr_leave() {
		$navinfo = " ���ţ�<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$navinfo .= " <input type='button' value='��ְ����' onclick='showNewExcel()' />";
		$this->xls_out ();
		$this->show->assign ( 'data_list', '?model=salary&action=hr_leave_list' );
		$this->show->assign ( 'user_capt', 'Ա����ְ�б�' . $navinfo );
		$this->show->assign ( 'miniDate', "{minDate:'" . date ( 'Y-m' ) . "-01'}" );
		$this->show->display ( 'salary_hr-leave' );
	}
	/**
	 * Ա����ְ�б�
	 */
	function c_hr_leave_list() {
		echo json_encode ( $this->model_hr_leave_list () );
	}
	/**
	 * ����Ա����ְ
	 */
	function c_dp_leave_manager() {
		$this->c_hr_leave_manager ( '?model=salary&action=dp_leave_manager_list', 'salary_dp-leave-manager' );
	}
	/**
	 * Ա����ְ����
	 * 
	 * @param type $url
	 *        	��ȡ����·��
	 * @param type $v
	 *        	��ͼ��ʾ·��
	 */
	function c_hr_leave_manager($url = '?model=salary&action=hr_leave_manager_list', $v = 'salary_hr-leave-manager') {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : '-';
		$seaplf = $_GET ['seaplf'] ? $_GET ['seaplf'] : '-';
		$navinfo = $this->get_com_sel ();
		$navinfo .= "��ְ��ݣ�<select id='sealy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·ݣ�<select id='sealm'> <option value='-'>����</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= "����״̬��<select id='seaplf'> <option value='-'>����</option>";
		$navinfo .= "<option value='1' >�ѽ���</option>";
		$navinfo .= "<option value='0' >δ����</option>";
		$navinfo .= "</select>";
		$navinfo .= " ���ţ�<input type='text' id='seadept' value='' size='10'/>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='10'/>";
		$navinfo .= "  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$navinfo .= "  <input type='button' id='sub' value='�߼���ѯ/����' onclick='showjsearch()' />";
		$navinfo .= "  <input type='button' id='printAll' value='��ӡѡ������' onclick='print_All()' />";
		// �߼�
		$jsearch .= '<tr><td>';
		$jsearch .= $this->get_com_sel ( true, 'all', 'js_seacom' );
		$jsearch .= '</td></tr>';
		$jsearch .= '<tr><td>';
		$jsearch .= "��ְ��ݣ�<select id='js_sealy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$jsearch .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$jsearch .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$jsearch .= "</select> �·ݣ�<select id='js_sealm'> <option value='-'>����</option>";
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
		$jsearch .= "֧����ݣ�<select id='js_seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			$jsearch .= "<option value='" . $i . "'>" . $i . "</option>";
		}
		$jsearch .= "</select> �·ݣ�<select id='js_seapm'> <option value='-'>����</option>";
		for($i = 1; $i <= 12; $i ++) {
			$jsearch .= "<option value='" . $i . "'>" . $i . "</option>";
		}
		$jsearch .= "</select> �գ�<select id='js_seapj'> <option value='-'>����</option>";
		for($i = 1; $i <= 31; $i ++) {
			$jsearch .= "<option value='" . $i . "'>" . $i . "</option>";
		}
		$jsearch .= "</select>";
		$jsearch .= '</td></tr>';
		$jsearch .= '<tr><td>';
		$jsearch .= "����״̬��<select id='js_seaplf'> <option value='-'>����</option>";
		$jsearch .= "<option value='1' >�ѽ���</option>";
		$jsearch .= "<option value='0' >δ����</option>";
		$jsearch .= "</select>";
		$jsearch .= '</td></tr>';
		$jsearch .= '<tr><td>';
		$jsearch .= " ���ţ�<input type='text' id='js_seadept' value=''/>";
		$jsearch .= '</td></tr>';
		$jsearch .= '<tr><td>';
		$jsearch .= " Ա����<input type='text' id='js_seaname' value='' />";
		$jsearch .= '</td></tr>';
		$this->show->assign ( 'jsearch', $jsearch );
		$this->show->assign ( 'data_list', $url );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( $v );
	}
	/**
	 * Ա����ְ�������
	 */
	function c_hr_leave_manager_list() {
		$sqlflag = $this->getSalaryScope(false,true,'','d.dept_id');
		echo json_encode ( $this->model_hr_leave_manager_list ('list', '', true, $sqlflag) );
	}
	/**
	 * ������ְ�������
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
	 * ������ְ����
	 */
	function c_cal_leavepay() {
		echo json_encode ( $this->model_cal_leavepay () );
	}
	function c_cal_leave_in() {
		echo json_encode ( $this->model_cal_leave_in () );
	}
	/*
	 * ��ӡ
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
	 * ��ְ����������ӡ
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
			$divStr = '<div id="payablesapply'.$i.'" style="width: 740px;"><table class="form_main_table" style="border: 1px solid #000000;width:730px;margin-top:40px;margin-left:18px;"><tr ><td colspan="4" style="border: 1px solid #000000;height: 65px;"> <span style="font-size:18px;font-weight:bold;letter-spacing:5px;"> ��ְ���ʽ����嵥 </span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">����</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {uname}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">��ͬ����</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {com}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">�ù�����</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {exp}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">����</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > {dept}</td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">ְ��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {job}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">��ְʱ��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {comedt}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">��ְʱ��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {leavedt}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">�������ʣ�+��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{baseam}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">��λ���ʣ�+��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{gwam}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">��Ч���ʣ�+��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{jxam}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">�¼�</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="">{ph}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">����</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="">{sh}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">��ְ���³�������</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="">{wdt}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{wdtr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">�²��ٿ۳���-��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="formatMoney">{hda}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{hdar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">��ְ����ʵ�ʹ�����������С��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="formatMoney">{bna}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{bnar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">�������+��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{sra}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{srar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">��ᱣ�շѣ�-��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{shb}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{shbr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">ס��������-��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" > <span class="formatMoney">{gjj}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{gjjr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">˰ǰ�۳���-��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{spedelam}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{bsdar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">��������˰��-��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{pc}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{pcr}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">˰��۳���-��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{accdelam}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{sdar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">��ְ������+��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{ara}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{arar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">��ְ����˰��-��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{arac}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">����˰���跢��+��</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"> <span class="formatMoney">{oara}</span></td><td colspan="2" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;text-align: left;">{oarar}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">ʵ����ְ����</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> <span class="formatMoney">{ptol}</span></td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">�˺ţ�</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {acc}</td></tr><tr><td  style="text-align: center; font-size: 14px;font-weight: bolder;border: 1px solid #000000;">�����У�</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;" colspan="3"> {accbank}</td></tr><tr><td style="text-align: left; font-size: 14px; font-weight: bolder ;border: 1px solid #000000;padding-top: 10px;padding-bottom: 10px;" colspan="4"> ���ݡ��Ͷ���ͬ�����涨�����ҹ�˾�����ڼ���ǩ���Ͷ���ͬ��������ᱣ�ա�<br/> �Ա����뿪�ҹ�˾֮�������о��á����ɵȾ������ҹ�˾�޹ء�</td></tr><tr><td style="text-align: left; font-size: 14px; font-weight: bolder;border: 1px solid #000000;" > ������Դ�����ˣ�</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td><td style="text-align: left; font-size: 14px; font-weight: bolder;border: 1px solid #000000;" > ������Դ��������</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td></tr><tr><td style="text-align: left; font-size: 14px; font-weight: bolder; width: 30%;border: 1px solid #000000;"> ����������</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder ;width: 25%;border: 1px solid #000000;"></td><td style="text-align: left; font-size: 14px; font-weight: bolder ; width: 20%;border: 1px solid #000000;" > ��ְԱ��ȷ�ϣ�</td><td class="form_text_right" style="font-size: 14px;font-weight: bolder;border: 1px solid #000000;"></td></tr></table>';
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
			//20180806�޸�
			$divStr = str_replace ( '{spedelam}', $row ['spedelam'], $divStr); //����۳� ���Ϊ˰ǰ�۳���˰��۳�
            $divStr = str_replace ( '{gjj}', $row ['gjj'], $divStr);
//            $divStr = str_replace ( '{sda}', $row ['sda'], $divStr); //����۳� ���Ϊ˰ǰ�۳���˰��۳�
            $divStr = str_replace ( '{accdelam}', $row ['accdelam'], $divStr); //����۳� ���Ϊ˰ǰ�۳���˰��۳�
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
								$username ��ְ���ʷ��ù��������嵥 </span>
						</td>
					</tr>
					<tr>
						<td
							style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>����\��Ŀ</td>
						<td class='form_text_right'
							style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>��ְ����ʵ�ʹ�����������С��</td>
						<td
							style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>�����</td>
						<td class='form_text_right'
							style='font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>��ְ����</td>
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
									style='text-align: left; font-size: 14px; font-weight: bolder; border: 1px solid #000000;'>С��</td>
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
	 * ����Ա����ʼ
	 */
	function c_hr_exp() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_hr_exp_ini ( $ckt ) );
		$this->show->display ( 'salary_hr-exp' );
	}
	/**
	 * ��ʼ���ӹ�˾
	 */
	function c_hr_sub_ini() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_hr_sub_ini ( $ckt ) );
		$this->show->display ( 'salary_hr-sub-ini' );
	}
	/**
	 * ��ʼ���ӹ�˾
	 */
	function c_hr_sub_ini_in() {
		echo json_encode ( $this->model_hr_sub_ini_in () );
	}
	/**
	 * ����Ա��
	 */
	function c_hr_exp_in() {
		echo json_encode ( $this->model_hr_exp_in () );
	}
	/**
	 * Ա����ְ����
	 */
	function c_hr_leave_in() {
		echo json_encode ( $this->model_hr_leave_in () );
	}
	/**
	 * ����/�۳�
	 */
	function c_hr_spe() {
		$xlsStr = '<select id="xls_year"><option value="-">����</option>';
		for($i = 2009; $i <= $this->nowy; $i ++) {
			if ($i == $this->nowy) {
				$xlsStr .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$xlsStr .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$xlsStr .= '    </select> ��';
		$xlsStr .= '<select id="xls_mon"><option value="-">����</option>';
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $this->nowm) {
				$xlsStr .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$xlsStr .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$xlsStr .= '    </select> ��';
		$navinfo .= "  <input type='button' value='�½�' onclick='newClickFun()'/>";
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
	 * ����Ա���б�
	 */
	function c_hr_spe_list() {
		echo json_encode ( $this->model_hr_spe_list () );
	}
	/**
	 * ����/�۳�����
	 */
	function c_hr_spe_in() {
		echo json_encode ( $this->model_hr_spe_in () );
	}
	
	/**
	 * ����
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
	 * �ɷ�
	 */
	function c_hr_pay() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = $this->get_com_sel ( 1, 'all', 'seausercom', 'Ա��' );
		$navinfo .= $this->get_com_sel ( 1, 'all', 'seajfcom', '�ɸ�' );
		$navinfo .= "�꣺<select id='seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �£�<select id='seapm'> <option value='-'>����</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> ���ͣ�<select id='seaexp'> <option value='-'>����</option>";
		$navinfo .= "<option value='0' >��˾Ա��</option>";
		$navinfo .= "<option value='1' >����Ա��</option></select>";
		$navinfo .= " ���ţ�<input type='text' id='seadept' value='' size='6'/>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='6'/>";
		$navinfo .= "  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$navinfo .= "  <input type='button' id='subx' value='����' onclick='xlsClickFun()' />";
		$navinfo .= "  <input type='button' id='subu' value='����' onclick='upClickFun()' />";
		$this->show->assign ( 'data_list', '?model=salary&action=hr_pay_list' );
		$this->show->assign ( 'edit_list', '?model=salary&action=hr_pay_in' );
		$this->show->assign ( 'ctr_list', '?model=salary&action=hr_pay_ctr&TB_iframe=true&height=600' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_hr-pay' );
	}
	/**
	 * �ɷ���Ϣ�б�
	 */
	function c_hr_pay_list() {
		echo json_encode ( $this->model_hr_pay_list () );
	}
	/**
	 * �ɷѴ���
	 */
	function c_hr_pay_in() {
		echo json_encode ( $this->model_hr_pay_in () );
	}
	/**
	 * �ɷѵ���
	 */
	function c_hr_pay_xls() {
		$this->model_hr_pay_xls ();
	}
	/**
	 * �ɷѶԱ�
	 */
	function c_hr_pay_ctr() {
		$seapy = $_POST ['ctr_py'] ? $_POST ['ctr_py'] : $this->nowy;
		$seapm = $_POST ['ctr_pm'] ? $_POST ['ctr_pm'] : $this->nowm;
		$seac = $_POST ['ctr_com'] ? $_POST ['ctr_com'] : '';
		$ckt = time ();
		
		$navinfo .= "�ɸ���ݣ�<select id='ctr_py' name='ctr_py'>";
		for($i = $this->nowy; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·ݣ�<select id='ctr_pm' name='ctr_pm'>";
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
	 * �ɷѶԱȸ���
	 */
	function c_hr_pay_ctr_in() {
		echo json_encode ( $this->model_hr_pay_ctr_in () );
	}
	/**
	 * ��ʼ����˾�ɷ�
	 */
	function c_hr_jf_ini() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_hr_jf_ini ( $ckt ) );
		$this->show->display ( 'salary_hr-jf-ini' );
	}
	/**
	 * �ɷѶԱȸ���
	 */
	function c_hr_jf_ini_in() {
		echo json_encode ( $this->model_hr_jf_ini_in () );
	}
	/**
	 * Ա����Ϣ
	 */
	function c_hr_info() {
		$navinfo = "  <input type='button' id='sub' value='��Ϣ�˶�' onclick='gridNavSeaFun()' />";
		$this->show->assign ( 'data_list', '?model=salary&action=hr_info_list' );
		$this->show->assign ( 'user_capt', 'Ա����Ϣ�б�' . $navinfo );
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
	 * Ա����Ϣ�б�
	 */
	function c_hr_info_list() {
		echo json_encode ( $this->model_hr_info_list () );
	}
	/**
	 * Ա����Ϣ�޸�
	 */
	function c_hr_info_in() {
		echo json_encode ( $this->model_hr_info_in () );
	}
	/**
	 * ���²���
	 */
	function c_hr_sdy() {
		$this->xls_out ();
		$this->show->assign ( 'data_list', '?model=salary&action=hr_sdy_list' );
		$this->show->assign ( 'user_capt', 'Ա�������б�' );
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
	// ������Ŀ����������
	function c_hr_prod() {
		$this->xls_out ();
		$this->show->assign ( 'data_list', '?model=salary&action=hr_prod_list' );
		$this->show->assign ( 'user_capt', '������Ŀ��ϸ' );
		$this->show->display ( 'salary_hr-prod' );
	}
	// ���˹����ܱ�
	function c_hr_pro() {
		global $func_limit;
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo .= "��ݣ�<select id='seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·ݣ�<select id='seapm'> <option value='-'>����</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		if($func_limit['��������ɾ��'] == 1) {
			$navinfo .= "  <input type='button' id='del' value='ɾ��' onclick='deleteData()' />";
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
	 * ��ȡ��˾��Ϣ
	 * 
	 * @param type $setid        	
	 * @return string
	 */
	function get_com_sel($setid = true, $settp = 'all', $setidn = 'seacom', $title = '', $selected = '') {
		$title = empty ( $title ) ? '��˾' : $title;
		if ($setid == true) {
			if ($settp == 'all') {
				$res = $title . "��<select id='" . $setidn . "'> <option value='' tabindex='���й�˾'>���й�˾</option>";
			} else {
				$res = $title . "��<select id='" . $setidn . "'>";
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
	 * ����Ȩ���б�['�ӹ�˾']['�������']��ȡ��˾��Ϣ
	 * 
	 * @param type $setid        	
	 * @return string
	 */
	function get_com_purview($setid = true, $setidn = 'seacom', $title = '', $selected = '') {
		global $func_limit;
		$scom = $func_limit ['�ӹ�˾'];
		$deptCom = "";
		if(!empty($func_limit ['�������'])) {
			$sdept = $func_limit ['�������'];
			if($sdept == ";;") {
				$sdept = '';
			}else {
				$sdept = " where p.deptid in(".$func_limit ['�������'].")";
			}
			$sql = "select GROUP_CONCAT(DISTINCT(p.usercom) SEPARATOR ',') as 'comcode' from salary_pay p $sdept";
			$rs = $this->db->get_one($sql);
			$deptCom = " or namept in( '" . str_replace ( ',', "','", $rs['comcode'] ) . "' )";
		}
		$res = '';
		$title = empty ( $title ) ? '��˾' : $title;
		if ($setid == true) {
			$res= $res."<select id='".$setidn."'> <option value='' tabindex='���й�˾'>���й�˾</option>";
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
	 * ����
	 */
	function c_hr_user_ext() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		
		$navinfo .= "��ݣ�<select id='seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·ݣ�<select id='seapm'> <option value='-'>����</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> ״̬��<select id='seausersta'> <option value=''>ȫ��</option>";
		$navinfo .= "<option value='��Ч'>��Ч</option>";
		$navinfo .= "<option value='�ر�'>�ر�</option>";
		$navinfo .= "</select>";
		$navinfo .= " ���ţ�<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$navinfo .= "  <input type='button' id='newspeSub' onclick='newClickFun()' value='����'/>";
		// $navinfo.=" <input type='button' id='newspeSub' onclick='daoClickFun()' value='����'/>";
		$navinfo .= "  <input type='button' id='newspeSub' onclick='xlsClickFun()' value='����'/>";
		
		$this->show->assign ( 'usercom', $this->globalUtil->get_com_sel ( 1, '', 'usercom', '' ) );
		$this->show->assign ( 'jfcom', $this->globalUtil->get_com_sel ( 1, '', 'jfcom', '' ) );
		$this->show->assign ( 'userdept', $this->globalUtil->get_dept_sel ( 1, '', 'userdept', '' ) );
		$this->show->assign ( 'user_list', '?model=salary&action=hr_user_ext_list' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->xls_out ();
		$this->show->display ( 'salary_hr-user-ext' );
	}
	/**
	 * ������Ա
	 */
	function c_hr_user_ext_list() {
		echo json_encode ( $this->model_hr_user_ext () );
	}
	/**
	 * ������Ա¼��
	 */
	function c_hr_usre_ext_in() {
		echo json_encode ( $this->model_hr_user_ext_in () );
	}
	
	/**
	 * ר��
	 */
	function c_hr_user_div() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo .= "��ݣ�<select id='seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·ݣ�<select id='seapm'> <option value='-'>����</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " ���ţ�<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$navinfo .= "  <input type='button' id='sub' value='����' onclick='outExcel()' />";
		// $navinfo.=" <input type='button' id='xls' value='��������' onclick='xlsClickFun()' />";
		// $navinfo.=" <input type='button' id='xls' value='��������' onclick='hrXlsClickFun()' />";
		// $navinfo.=" <input type='button' id='xls' value='���ɱ䶯' onclick='expClickFun()' />";
		// $navinfo.=" <input type='button' id='xls' value='ͳ��' onclick='xlsTolClickFun()' />";
		// $navinfo.=" <input type='checkbox' id='errs' value='y' onclick='gridNavSeaFun()'/> ��";
		// $navinfo.=" <input type='button' id='xls' value='����������' onclick='expYebClickFun()' />";
		// $navinfo.="<input type='button' value='���Ա��' class='BigButton' onclick='btnClick()' />";
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
	 * ���µ�������
	 */
	function c_hr_div_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_dao_xls ( 'hr_div', $ckt ) );
		$this->show->display ( 'salary_hr-div-xls' );
	}
	/**
	 * ���µ�������
	 */
	function c_hr_div_xls_in() {
		$ckt = $_POST ['ckt'];
		echo json_encode ( $this->model_dao_xls ( 'hr_div', $ckt, 'in' ) );
	}
	/**
	 * ���µ�������
	 */
	function c_hr_ext_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_dao_xls ( 'hr_ext', $ckt ) );
		$this->show->display ( 'salary_hr-ext-xls' );
	}
	/**
	 * ���µ�������
	 */
	function c_hr_ext_xls_in() {
		$ckt = $_POST ['ckt'];
		echo json_encode ( $this->model_dao_xls ( 'hr_ext', $ckt, 'in' ) );
	}
	/**
	 * Ա����Ϣ��Ŀ
	 */
	function c_hr_user() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = $this->get_com_purview ();
		$navinfo .= "��ݣ�<select id='seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·ݣ�<select id='seapm'> <option value='-'>����</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " ���ţ�<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		// $navinfo.=" <input type='button' id='xls' value='��������' onclick='xlsClickFun()' />";
		global $func_limit;
		if ($func_limit ['���½��ײ�������'] != 1) {
			$navinfo .= " <input type='button' id='sub' value='�߼���ѯ' onclick='$(\\\".advModelDiv\\\").show()' />";
			$navinfo .= " <input type='button' id='xls' value='��������' onclick='hrXlsClickFun()' />";
		}
		// $navinfo.=" <input type='button' id='xls' value='���ɱ䶯' onclick='expClickFun()' />";
		$navinfo .= " <input type='button' id='xls' value='����ͳ��' onclick='xlsTolClickFun(\\\"?model=salary&action=xls_out&flag=dp_tol&type=hr\\\")' />";
		$navinfo .= " <input type='button' id='xls' value='���ŷ���' onclick='xlsTolClickFun(\\\"?model=salary&action=xls_out&flag=gs_tol\\\")' />";
		
		$navinfo .= " <input type='checkbox' id='errs' value='y' onclick='gridNavSeaFun()'/> ��";
		// $navinfo.=" <input type='button' id='xls' value='����������' onclick='expYebClickFun()' />";
		// $navinfo.="<input type='button' value='���Ա��' class='BigButton' onclick='btnClick()' />";
		$this->show->assign ( 'user_list', '?model=salary&action=hr_user_list&seacom='.$seacom ); 
		$this->show->assign ( 'edit_list', '?model=salary&action=hr_user_edit&seacom='.$seacom );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->assign ( 'salaryYear', $this->nowy );
		$this->show->assign ( 'salaryMon', $this->nowm );
		$this->show->display ( 'salary_hr-user' );
	}
	
	/**
	 * Ա��н�ʽṹ��Ϣ��Ŀ
	 */
	function c_hr_user_salary() {
        $navinfo = $this->get_com_sel ();
        $navinfo .= "</select>";
        $seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
        $seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
        $deptModel = $_GET ['deptModel'] ? $_GET ['deptModel'] : 0;
        $navinfo .= "��ݣ�<select id='seapy'>";
        for($i = 2010; $i <= $this->nowy; $i ++) {
            if ($i == $seapy) {
                $navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
            } else {
                $navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
            }
        }
        $navinfo .= "</select> �·ݣ�<select id='seapm'>";
        for($i = 1; $i <= 12; $i ++) {
            if ($i == $seapm) {
                $navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
            } else {
                $navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
            }
        }
        $navinfo .= "</select>";
        if($deptModel == 0) {
        	$navinfo .= " ���ţ�<input type='text' id='seadept' value='' size='15'/>";
        }else if($deptModel == 1) {
        	$navinfo .= "<input type='text' id='seadept' size='15' style='display:none;'/>";
		}
        $navinfo .= " Ա����<input type='text' id='seaname' value='' size='15'/>";
        
		$navinfo .= "&nbsp;&nbsp;&nbsp;ֻ��ʾ������Ա<input type='checkbox' checked id='iswy' onclick='gridNavSeaFun()'/>";
        $navinfo .= "&nbsp;&nbsp;&nbsp;ֻ��ʾ��������<input type='checkbox' checked id='hasData' onclick='gridNavSeaFun()' />"
                 ."  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />  <input type='button' id='export' value='��������' onclick='exportSalaryData()' />";
        $this->show->assign ( 'user_salary_list', '?model=salary&action=hr_user_salary_list' );
        $this->show->assign ( 'user_capt', $navinfo );
        $this->show->display ( 'salary_hr-user-salary' );
	}
	
	/**
	 * Ԥ��������Ŀ
	 */
	function c_hr_yuti() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo .= "��ݣ�<select id='seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·ݣ�<select id='seapm'> <option value='-'>����</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
        $navinfo .= " <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' /> <input type='button' id='export' value='����' onclick='exportExcel()' /> <input type='button' id='init' value='����Ԥ������' onclick='inityuti()' /> <input type='button' id='push' value='���Ԥ������' onclick='pushyuti()' /> <input type='button' id='cancel' value='����Ԥ������' onclick='cancelyuti()' />";
		$this->show->assign ( 'hr_yuti_list', '?model=salary&action=hr_yuti_list' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_hr-yuti' );
	}
	
	/**
	 * ��ѯԤ������
	 */
	function c_hr_yuti_list() {
		echo json_encode ( $this->model_hr_yuti_list () );
	}
	
	/**
	 * ����Ԥ������
	 */
	function c_yuti_init() {
		echo un_iconv($this->model_yuti_init() );
	}
	
	/**
	 * Ա����н��¼��Ŀ
	 */
	function c_hr_user_adjust() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = $this->get_com_sel ();
		$navinfo .= "</select>";
		$navinfo .= " ���ţ�<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "&nbsp;&nbsp;&nbsp;ֻ��ʾ������Ա<input type='checkbox' id='iswy' onclick='gridNavSeaFun()' />  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$this->show->assign ( 'data_list', '?model=salary&action=hr_user_adjust_list' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_hr-user-adjust' );
	}
	
	
	/**
	 * ��ʼ�������ܱ�
	 */
	function c_init_salarypro() {
		$this->model_initSalaryPro ();
	}
	
	/**
	 * �ύ��Ч�ܱ���������
	 */ 
	function c_sendSalaryToFlow() {
		$this->model_sendSalaryToFlow ();
	}
	
	/**
	 * Ա��ͳ����Ŀ
	 */
	function c_hr_user_stat() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = "��ݣ�<select id='seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " ���ţ�<input type='text' id='seadept' value='' size='15'/>";
		$navinfo .= " Ա����<input type='text' id='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$navinfo .= " <input type='button' id='xls' value='����' onclick='xlsClickFun()' />";
		$navinfo .= " <input type='button' id='xls' value='����ͳ�ƶԱȵ���' onclick='xlsStaClickFun()' />";
		// $navinfo.=" 2011��ʵ���ܹ��ʣ�".$this->model_hr_yeb_stat(2011);
		$this->show->assign ( 'user_list', '?model=salary&action=hr_user_stat_list' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_hr-user-stat' );
	}
	/*
	 * ͳ������
	 */
	function c_hr_user_stat_list() {
		echo json_encode ( $this->model_hr_user_stat () );
	}
	/**
	 * ͳ�Ƶ���
	 */
	function c_hr_user_stat_xls() {
		$this->model_hr_user_stat_xls ();
	}
	/**
	 * �û��б�
	 */
	function c_hr_user_list() {
		$sqlflag = $this->getSalaryScope();
		echo json_encode ( $this->model_hr_user (false,$sqlflag) );
	}
	/**
	 * �û��б��߼���ѯ
	 */
	function c_hr_user_advlist() {
		$sqlflag = $this->getSalaryScope();
		echo json_encode ( $this->model_hr_user (false,$sqlflag,false,false,'list','',true) );
	}
	/**
	 * �û�н�ʽṹ�б�
	 */
	function c_hr_user_salary_list() {
		echo json_encode ( $this->model_hr_salary_user () );
	}
	/**
	 * �û���н��¼�б�
	 */
	function c_hr_user_adjust_list() {
		echo json_encode ( $this->model_hr_adjust_user () );
	}
	
	/**
	 * ����Ա�����ս�����
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
	 * ���ɵ���
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
	 * ���ݼ��Ͻ�
	 */
	function c_hr_hols_hd() {
		$this->model_hr_hols_hd ();
	}
	/**
	 * �ʼ�����
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
	 * ���µ����н
	 */
	function c_hr_mdf() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_mdf_list' );
		$this->show->assign ( 'user_capt', '��н�б�' );
		$this->show->display ( 'salary_hr-mdf' );
	}
	/**
	 * ������Ա
	 */
	function c_fin() {
		$this->show->assign ( 'first_tab', '�������' );
		$this->show->assign ( 'first_ifr', '?model=salary&action=fin_index' );
		$this->show->display ( 'salary_fin-index' );
	}
	/**
	 * ���Ź���
	 */
	function c_dp() {
		$this->show->assign ( 'grid_url', '?model=salary&action=dp_tree' );
		$this->show->assign ( 'first_tab', '��������' );
		$this->show->assign ( 'first_ifr', '?model=salary&action=dp_exa' );
		$this->show->assign ( 'exitSalary', '?model=salary&action=exitSalary&mod=dp' );
		$this->show->display ( 'salary_index' );
	}
	/**
	 * ��������
	 */
	function c_dp_tree() {
		$responce->rows [0] ['id'] = '1';
		$responce->rows [0] ['cell'] = un_iconv ( array (
				'1',
				'��������',
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
				'���ʲ�ѯ',
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
				'��ְ��ѯ',
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
				'���ս�����',
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
	 * ���ʰ���
	 */
	function c_dp_exa() {
		$this->xls_out ();
		$this->show->assign ( 'exa_list', $this->model_dp_exa () );
		$this->show->display ( 'salary_dp-exa' );
	}
	/**
	 * ������ְ
	 */
	function c_dp_join() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_join_list' );
		$this->show->assign ( 'user_capt', 'Ա����ְ�б�' );
		$this->show->display ( 'salary_dp-join' );
	}
	/**
	 * ��ְԱ���б�
	 */
	function c_dp_join_list() {
		echo json_encode ( $this->model_dp_join_list () );
	}
	/**
	 * ��ְ����
	 */
	function c_dp_join_in() {
		echo json_encode ( $this->model_hr_join_in ( false ) );
	}
	/**
	 * ����ת��
	 */
	function c_dp_pass() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_pass_list' );
		$this->show->assign ( 'user_capt', 'Ա��ת���б�' );
		$this->show->display ( 'salary_dp-pass' );
	}
	/**
	 * ת��Ա���б�
	 */
	function c_dp_pass_list() {
		echo json_encode ( $this->model_dp_pass_list () );
	}
	/**
	 * ת������
	 */
	function c_dp_pass_in() {
		echo json_encode ( $this->model_hr_pass_in ( false ) );
	}
	/**
	 * �������ݼ�
	 */
	function c_dp_hols_hd() {
		$this->model_dp_hols_hd ();
	}
	/**
	 * �û��б�
	 */
	function c_dp_user_list() {
		echo json_encode ( $this->model_dp_user () );
	}
	/**
	 * ��������
	 */
	function c_dp_sal_exa() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_list' );
		$this->show->assign ( 'user_capt', "���������б�<input type='button' name='all_sel' id='all_sel' value='��������' />" );
		$this->show->display ( 'salary_dp-sal-exa' );
	}
	/**
	 * ��������
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
	 * �¶ȵ�н��¼
	 */
	function c_mon_adjust_list() {
		echo json_encode ( $this->model_get_mon_adjust () );
	}
	
	/**
	 * ��н��ϸ
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
	 * ���⽱������
	 */
	function c_dp_sal_exa_spe() {
		$user_capt = "����״̬��<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>δ����</option><option value='1'>������</option><option value='3'>ȫ��</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='��������' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_list&sf=spe' );
		$this->show->display ( 'salary_dp-sal-exa-spe' );
	}
	/**
	 * ��н����
	 */
	function c_dp_sal_exa_nym() {
		$user_capt = "����״̬��<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>δ����</option><option value='1'>������</option><option value='3'>ȫ��</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='��������' />";
		$isHidden = "true";
		$isHiddenSubName = "";
		$isHiddenSubAlign = "";
		$isHiddenSubWidth = "";
		if($this->isWyLeader()){
			$isHidden = "false";
			$isHiddenSubName = ",'�������Ų���'";
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
	 * ��Ŀ������
	 */
	function c_dp_sal_exa_pro() {
		$user_capt = "����״̬��<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>δ����</option><option value='1'>������</option><option value='3'>ȫ��</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='��������' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_list&sf=pro' );
		$this->show->display ( 'salary_dp-sal-exa' );
	}
	/**
	 * ��Ŀ������
	 */
	function c_dp_sal_exa_bos() {
		$user_capt = "����״̬��<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>δ����</option><option value='1'>������</option><option value='3'>ȫ��</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='��������' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_list&sf=bos' );
		$this->show->display ( 'salary_dp-sal-exa' );
	}
	/**
	 * ���²���
	 */
	function c_dp_sal_exa_sdy() {
		$user_capt = "����״̬��<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>δ����</option><option value='1'>������</option><option value='3'>ȫ��</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='��������' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_sdy_list' );
		$this->show->display ( 'salary_dp-sal-exa-sdy' );
	}
	/**
	 * ������Ŀ��ϸ
	 */
	function c_dp_sal_exa_prod() {
		$user_capt = "����״̬��<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>δ����</option><option value='1'>������</option><option value='3'>ȫ��</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='��������' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_prod_list' );
		$this->show->display ( 'salary_dp-sal-exa-prod' );
	}
	/**
	 * �����ܱ�
	 */
	function c_dp_sal_exa_salarypro() {
		$user_capt = "����״̬��<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>δ����</option><option value='1'>������</option><option value='3'>ȫ��</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='��������' />";
		$this->show->assign ( 'user_capt', $user_capt );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sal_exa_pro_list' );
		$this->show->display ( 'salary_dp-sal-exa-pro' );
	}
	
	/**
	 * ���Ƚ�
	 */
	function c_dp_sal_exa_fla() {
		$user_capt = "����״̬��<select id='exa_sta' name ='exa_sta' onchange='gridNavSeaFun()'>" . "<option value='0'>δ����</option><option value='1'>������</option><option value='3'>ȫ��</option></select> " . "<input type='button' name='all_sel' id='all_sel' value='��������' />";
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
	 * ת������
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
	 * �������
	 */
	function c_dp_sal_exa_ck() {
		echo un_iconv ( $this->model_dp_sal_exa_ck () );
	}
	/**
	 * Ա����Ϣ��Ŀ-��
	 */
	function c_dp_user() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo = "��ݣ�<select id='seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·ݣ�<select id='seapm'> <option value='-'>����</option>";
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
		$navinfo .= " ���ţ�<input type='text' id='seadept' name='seadept' value='' size='15'/><input type='checkbox' id='isEq' />��ȫƥ�� ";
		$navinfo .= " Ա����<input type='text' id='seaname' name='seaname' value='' size='15'/>";
		$navinfo .= "  <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$navinfo .= "  <input type='button' id='sub' value='�������ʵ���' onclick='xlsClickFun()' />";
		$navinfo .= "  <input type='button' id='sub' value='����ͳ�Ƶ���' onclick='xlsClickFun(1)' />";
		$this->show->assign ( 'user_list', '?model=salary&action=dp_user_list' );
		$this->show->assign ( 'edit_list', '?model=salary&action=dp_user_edit' );
		$this->show->assign ( 'isHidden', $isHidden );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_dp-user' );
	}
	/**
	 * ��ȹ��ʸ���
	 */
	function c_dp_ymd() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_ymd_list' );
		$this->show->assign ( 'user_capt', '��ȵ�н�б�' );
		$this->show->display ( 'salary_dp-ymd' );
	}
	/**
	 * ��ȵ�н�б�
	 */
	function c_dp_ymd_list() {
		echo json_encode ( $this->model_dp_mdf_list ( array (
				'ymd' 
		), 'nym' ) );
	}
	/**
	 * Ա�����ʸ���
	 */
	function c_dp_mdf() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_mdf_list' );
		$this->show->assign ( 'user_capt', '��н�б�' );
		$isHidden = "true";
		$isHiddenSubName = "";
		$isHiddenSubAlign = "";
		$isHiddenSubWidth = "";
		if($this->isWyLeader()) {
			$isHidden = "false";
			$isHiddenSubName = ",'�������Ų���'";
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
	 * Ա���б�
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
	 * ��������
	 */
	function c_close_stat() {
		$this->show->assign ( 'data_list', $this->model_close_stat () );
		$this->show->display ( 'salary_close-stat' );
	}
	function c_close_stat_in() {
		echo $this->model_close_stat_in ();
	}
	/**
	 * Ա������
	 */
	function c_dp_mdf_in() {
		echo json_encode ( $this->model_dp_mdf_in () );
	}
	/**
	 * ����ȵ�н
	 */
	function c_dp_nym() {
		$pow = $this->model_dp_pow ();
		global $func_limit;
		if (! empty ( $pow ['2'] )) {
			$todept = trim ( implode ( ',', $pow ['1'] ) . ',' . implode ( ',', $pow ['2'] ) . ',' . $func_limit ['�������'], ',' );
			// $this->show->assign('user_win','module/user_select_single?todept='.trim(implode(',', $pow['1']).','.implode(',', $pow['2']),',') );
		} elseif (! empty ( $pow ['1'] )) {
			$todept = trim ( implode ( ',', $pow ['1'] ) . ',' . $func_limit ['�������'], ',' );
			// $this->show->assign('user_win','module/user_select_single?todept='.trim( implode(',', $pow['1'])) );
		} else {
			$todept = empty ( $func_limit ['�������'] ) ? '-' : $func_limit ['�������'];
			// $this->show->assign('user_win','module/user_select_single?todept=-' );
		}
		$this->show->assign ( 'user_win', 'module/user_select_single?todept=' . $todept );
		$this->show->display ( 'salary_dp-nym' );
	}
	function c_dp_nym_in() {
		echo json_encode ( $this->model_dp_nym_in () );
	}
	function c_dp_nym_xls() {
		$xlsUrl = 'attachment/xls_model/����ȵ�н�����.xls';
		$expenCeilingTd = '';
		$isWy = $this->isWyLeader();
		if($isWy) {
			$xlsUrl = 'attachment/xls_model/��������ŵ�н�����.xls';
			$expenCeilingTd = '<td align="center">�����������Ų���</td>';
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
	 * ���Ƚ�
	 */
	function c_dp_fla() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_fla_list' );
		$this->show->assign ( 'edit_list', '?model=salary&action=dp_fla_in' );
		$this->show->assign ( 'user_capt', '���Ƚ��б�' );
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
	 * ��Ŀ��
	 */
	function c_dp_pro() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_pro_list' );
		$this->show->assign ( 'user_capt', '��Ŀ���б�' );
		$this->show->display ( 'salary_dp-pro' );
	}
	
	/**
	 * ��Ŀ���б�
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
	 * ��Ŀ������
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
	 * ��Ŀ������
	 */
	function c_dp_pro_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_dp_pro_xls ( $ckt ) );
		$this->show->display ( 'salary_dp-pro-xls' );
	}
	/**
	 * ���봦��
	 */
	function c_dp_pro_xls_in() {
		echo json_encode ( $this->model_dp_pro_xls_in () );
	}
	/**
	 * ��Ŀ��
	 */
	function c_dp_bos() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_bos_list' );
		$this->show->assign ( 'user_capt', '�����б�' );
		$this->show->display ( 'salary_dp-bos' );
	}
	/**
	 * ��Ŀ���б�
	 */
	function c_dp_bos_list() {
		echo json_encode ( $this->model_dp_mdf_list ( array (
				'bos' 
		) ) );
	}
	/**
	 * ��Ŀ������
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
	 * ��Ŀ������
	 */
	function c_dp_bos_xls() {
		$ckt = time ();
		$this->show->assign ( 'ckt', $ckt );
		$this->show->assign ( 'data_list', $this->model_dp_bos_xls ( $ckt ) );
		$this->show->display ( 'salary_dp-bos-xls' );
	}
	/**
	 * ���봦��
	 */
	function c_dp_bos_xls_in() {
		echo json_encode ( $this->model_dp_bos_xls_in () );
	}
	/**
	 * ���ս�
	 */
	function c_dp_yeb() {
		$manflag = $_GET ['manflag'];
		$sealist .= ' ��ݣ�<select name="seay" id="seay">';
		for($i = 2010; $i <= date ( "Y" ); $i ++) {
			if ($this->yebyear == $i) {
				$sealist .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$sealist .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$sealist .= '</select>';
		$sealist .= ' ������<input type="text" name="seaname" id="seaname" value="" style="width:100px;"/>';
		$sealist .= ' ���ţ�<input type="text" name="seadept" id="seadept" value="" style="width:100px;"/>';
		$sealist .= ' <input name="btn" type="button" id="btn" value="��ѯ" onclick="gridNavSeaFun()"/>';
		$sealist .= ' <input type="button" value="�������ս�" onclick="showNewExcel()" />';
		$sealist .= ' <input type="button" value="�������ս�" onclick="ExcelOutFun()" />';
		if ($manflag == 'man') {
			$this->show->assign ( 'data_list', '?model=salary&action=dp_yeb_list&manflag=' . $manflag );
			$this->show->assign ( 'excel_list', '?model=salary&action=dp_yeb_xls&manflag=' . $manflag . '&TB_iframe=true&height=650' );
			$this->show->assign ( 'user_capt', '���ս��б� ' . $sealist );
			$this->show->display ( 'salary_dp-yeb-man' );
		} else {
			$this->show->assign ( 'data_list', '?model=salary&action=dp_yeb_list' );
			$this->show->assign ( 'excel_list', '?model=salary&action=dp_yeb_xls&TB_iframe=true&height=650' );
			$this->show->assign ( 'user_capt', '���ս��б� ' . $sealist );
			$this->show->display ( 'salary_dp-yeb' );
		}
	}
	function c_dp_yeb_fn() {
		global $func_limit;
		$sealist .= '<select name="comflag" id="comflag">';
		$sealist .= '<option value="">����Ա��</option>';
		$sealist .= '<option value="com">��˾Ա��</option>';
		$sealist .= '<option value="exp">����Ա��</option>';
		$sealist .= '</select>';
		$sealist .= ' ��ݣ�<select name="seay" id="seay">';
		for($i = 2010; $i <= date ( "Y" ); $i ++) {
			if ($this->yebyear == $i) {
				$sealist .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$sealist .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$sealist .= '</select>';
		$sealist .= ' ������<input type="text" name="seaname" id="seaname" value="" style="width:100px;"/>';
		$sealist .= ' ���ţ�<input type="text" name="seadept" id="seadept" value="" style="width:100px;"/>';
		$sealist .= ' <input name="btn" type="button" id="btn" value="��ѯ" onclick="gridNavSeaFun()"/>';
		if ($func_limit ['���ս�����'] == '1') {
			$sealist .= '<input type="button" id="sub" value="���ս�����" onclick="yebClickFun()"';
		}
		$this->show->assign ( 'data_list', '?model=salary&action=dp_yeb_fn_list' );
		$this->show->assign ( 'user_capt', $sealist );
		$this->show->display ( 'salary_dp-yeb-fn' );
	}
	/**
	 * ���ս��б�
	 */
	function c_dp_yeb_list() {
		echo json_encode ( $this->model_dp_yeb_list () );
	}
	function c_dp_yeb_fn_list() {
		global $func_limit;
		$dppow = $this->model_dp_pow ();
		if (strpos ( "perm" . $func_limit ['�������'], ";" ) > 0) {
			$sqlflag = "  ";
		} else if (! empty ( $func_limit ['�������'] )) {
			$sqlflag = " and
                ( s.deptid in ('" . implode ( "','", $dppow ['1'] ) . "','" . implode ( "','", $dppow ['2'] ) . "')
                    or s.userid='" . $_SESSION ['USER_ID'] . "'
                    or s.deptid in ( " . trim ( $func_limit ['�������'], ',' ) . " )
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
	 * ���ս�����
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
	 * ���ս����봦��
	 */
	function c_dp_yeb_xls_in() {
		echo json_encode ( $this->model_dp_yeb_xls_in () );
	}
	function c_dp_yeb_rep() {
		$seay = isset ( $_GET ['seay'] ) ? $_GET ['seay'] : $this->yebyear;
		$sealist = '';
		$sealist .= ' ��ݣ�<select name="seay" id="seay" onchange="changeDt()">';
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
	 * ����
	 */
	function c_dp_sdy() {
		$this->show->assign ( 'data_list', '?model=salary&action=dp_sdy_list' );
		$this->show->assign ( 'user_capt', '�����б�' );
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
	 * Ա������
	 */
	function c_dp_user_type() {
		$this->show->assign ( 'user_win', 'module/user_select?x=1' );
		$this->show->assign ( 'data_list', '?model=salary&action=dp_user_typel' );
		$this->show->assign ( 'user_capt', 'Ա����Ϣ-��Ŀ����' );
		$this->show->display ( 'salary_dp-user-type' );
	}
	function c_dp_user_typel() {
		echo json_encode ( $this->model_dp_user_typel () );
	}
	function c_dp_user_typen() {
		echo json_encode ( $this->model_dp_user_typen () );
	}
	/**
	 * �������-����
	 */
	function c_fn_stat() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		global $func_limit;
		$navinfo = $this->get_com_sel ( true, 'part', 'seacom', '', $func_limit ['���񷢷Ź�˾'] );
		$navinfo .= "<select id='seacompt'><option value='0'>��˾Ա��</option><option value='1'>����Ա��</option><option value='-'>����</option> ";
		$navinfo .= "</select>���<select id='seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·�<select id='seapm'> <option value='-'>����</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " ����<input type='text' id='seadept' value='' size='8'/>";
		$navinfo .= " Ա��<input type='text' id='seaname' value='' size='8'/>";
		$navinfo .= " <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$navinfo .= "<input type='button' value='����' class='BigButton' onclick='btnClick(1," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='��˰' class='BigButton' onclick='btnClick(2," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='����' class='BigButton' onclick='btnClick(3," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='����' class='BigButton' onclick='btnClick(4," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='�ʼ�����' class='BigButton' onclick='btnClick(5," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='����ȫ��' class='BigButton' onclick='btnClick(6," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='���Ա��' class='BigButton' onclick='btnClick(7," . $this->nowy . "," . $this->nowm . ")' />";
		$this->show->assign ( 'user_list', '?model=salary&action=fn_user_list' );
		$this->show->assign ( 'edit_list', '' );
		$this->show->assign ( 'user_capt', $navinfo );
		$this->show->display ( 'salary_fn-stat' );
	}
	/**
	 * �������-�ӹ�˾
	 */
	function c_fin_index() {
		$seapy = $_GET ['seapy'] ? $_GET ['seapy'] : $this->nowy;
		$seapm = $_GET ['seapm'] ? $_GET ['seapm'] : $this->nowm;
		$navinfo .= "<select id='seacompt'><option value='0'>��˾Ա��</option><option value='1'>����Ա��</option><option value='-'>����</option> ";
		$navinfo .= "</select>���<select id='seapy'> <option value='-'>����</option>";
		for($i = 2010; $i <= $this->nowy; $i ++) {
			if ($i == $seapy) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select> �·�<select id='seapm'> <option value='-'>����</option>";
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $seapm) {
				$navinfo .= "<option value='" . $i . "' selected>" . $i . "</option>";
			} else {
				$navinfo .= "<option value='" . $i . "'>" . $i . "</option>";
			}
		}
		$navinfo .= "</select>";
		$navinfo .= " ����<input type='text' id='seadept' value='' size='8'/>";
		$navinfo .= " Ա��<input type='text' id='seaname' value='' size='8'/>";
		$navinfo .= " <input type='button' id='sub' value='��ѯ' onclick='gridNavSeaFun()' />";
		$navinfo .= "<input type='button' value='����' class='BigButton' onclick='btnClick(1," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<input type='button' value='�ʼ�����' class='BigButton' onclick='btnClick(5," . $this->nowy . "," . $this->nowm . ")' />";
		$navinfo .= "<a href='?model=salary&action=exitSalary&mod=fin' style='margin-top: 2px;margin-left: 20px;font-weight:normal;color:blue;'>�˳�н��ϵͳ</a>";
		// $navinfo.="<input type='button' value='���ս�' class='btn' onclick='yTab()' />";
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
		$this->show->assign ( 'user_capt', 'Ա����Ϣ�����б�' );
		$this->show->display ( 'salary_fn-info' );
	}
	/**
	 * ����֤���
	 */
	function c_ck_idcard() {
		echo un_iconv ( $this->model_ck_idcard () );
	}
	/*
	 * ��Ϣ�޸�
	 */
	function c_fn_info_in() {
		echo json_encode ( $this->model_fn_info_in () );
	}
	// ���ս�
	function c_fn_yeb() {
		$sealist .= '<select name="comflag" id="comflag">';
		$sealist .= '<option value="">����Ա��</option>';
		$sealist .= '<option value="com">��˾Ա��</option>';
		$sealist .= '<option value="exp">����Ա��</option>';
		$sealist .= '</select>';
		$sealist .= ' ��ݣ�<select name="seay" id="seay">';
		for($i = 2010; $i <= date ( "Y" ); $i ++) {
			if ($this->yebyear == $i) {
				$sealist .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$sealist .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$sealist .= '</select>';
		$sealist .= ' ������<input type="text" name="seaname" id="seaname" value="" style="width:80px;"/>';
		$sealist .= ' ���ţ�<input type="text" name="seadept" id="seadept" value="" style="width:80px;"/>';
		$sealist .= '<input name="btn" type="button" id="btn" value="��ѯ" onclick="gridNavSeaFun()"/>';
		$sealist .= '<input name="btn" type="button" id="btn" value="���񱨱�" onclick="excelOutFun(1)" />';
		$sealist .= '<input name="btn" type="button" id="btn" value="��˰����" onclick="excelOutFun(2)" />';
		$sealist .= '<input name="btn" type="button" id="btn" value="���б���" onclick="excelOutFun(3)" />';
		$sealist .= '<input name="btn" type="button" id="btn" value="����ͳ��" onclick="excelOutFun(4)" />';
		// $sealist.='<input name="btn" type="button" id="btn" value="�ʼ�����" onclick="emailFun()" />';
		$sealist .= '<input name="btn" type="button" id="btn" value="����-����" onclick="expExcelOutFun()" />';
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
		$sealist .= '<option value="">����Ա��</option>';
		$sealist .= '<option value="com">��˾Ա��</option>';
		$sealist .= '<option value="exp">����Ա��</option>';
		$sealist .= '</select>';
		$sealist .= ' ��ݣ�<select name="seay" id="seay">';
		for($i = 2010; $i <= date ( "Y" ); $i ++) {
			if ($this->yebyear == $i) {
				$sealist .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$sealist .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$sealist .= '</select>';
		$sealist .= ' ������<input type="text" name="seaname" id="seaname" value="" style="width:80px;"/>';
		$sealist .= ' ���ţ�<input type="text" name="seadept" id="seadept" value="" style="width:80px;"/>';
		$sealist .= '<input name="btn" type="button" id="btn" value="��ѯ" onclick="gridNavSeaFun()"/>';
		$sealist .= '<input name="btn" type="button" id="btn" value="���ս�����" onclick="excelOutFun(1)" />';
		// $sealist.='<input name="btn" type="button" id="btn" value="�ʼ�����" onclick="emailFun()" />';
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
	 *	salaryTemp������ͬ����salary��������н�ʱ�����������ֵ��
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
		$xlsStr .= '    </select> ��';
		$xlsStr .= '<select id="xls_mon"><option value="-">����</option>';
		for($i = 1; $i <= 12; $i ++) {
			if ($i == $this->nowm) {
				$xlsStr .= '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				$xlsStr .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		$xlsStr .= '    </select> ��';
		$this->show->assign ( 'xls_list', $xlsStr );
	}
	function c_xls_out() {
		$flag = $_REQUEST ['flag'];
		$xls_arr = array (
				'dp_tol' => '����ͳ�Ʊ�',
				'lin' => '��ְ����ͳ�Ʊ�',
				'lin2' => '��ְ����ͳ�Ʊ�',
				'dp_lin' => '��ְ����ͳ�Ʊ�',
				'fn_pro' => '������Ŀͳ�Ʊ�',
				'hr_sdy' => '���ʲ����������ݱ�',
				'hr_div' => '����ר����',
				'dp_detail' => '������ϸ��',
				'hr_detail' => '������ϸ��-����',
				'hr_jf' => '�����籣�������',
				'ext' => '���ʶ�����Ա��ϸ��',
				'gs_tol' => $_REQUEST ['sy'] . '���ʲ��ŷ�����',
				'gs_cp' => '����ͳ�ƶԱ�',
				'wy_salary' => '����н���',
				'salary_base' => 'н��ṹ��',
				'hr_yuti' => '����Ԥ���',
				'salary_adjust' => '�¶ȵ�н��¼��'
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
	 * ����ͳ��
	 */
	function c_dp_stat_float() {
		$this->model_dp_stat_float ();
	}
	// ##############################################����#################################################
	/**
	 * ��������
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