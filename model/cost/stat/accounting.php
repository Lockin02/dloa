<?php
class model_cost_stat_accounting extends model_base {

	public $page;
	public $num;
	public $start;
	public $db;
	public $gl;

	//*******************************构造函数***********************************
	function __construct() {
		parent :: __construct();
		$this->db = new mysql();
		$this->gl = new includes_class_global();
		$this->page = intval($_GET['page']) ? intval($_GET[page]) : 1;
		$this->start = ($this->page == 1) ? 0 : ($this->page - 1) * pagenum;
		$this->num = intval($_GET['num']) ? intval($_GET['num']) : false;
	}

	//*********************************数据处理********************************
	function model_pro() {

	}

	function model_pro_days() {
		$nowy = date('Y');
		$nowm = date('n');
		$checky = isset ($_REQUEST['seay']) ? $_REQUEST['seay'] : $nowy;
		$checkmb = isset ($_REQUEST['seamb']) ? $_REQUEST['seamb'] : $nowm;
		$checkme = isset ($_REQUEST['seame']) ? $_REQUEST['seame'] : $nowm;
		$sql = "select l.projectno as prono , x.name as proname , sum(days) as sds
						            from cost_detail_project p
						                left join cost_summary_list l on (p.billno=l.billno)
						                left join xm x on (l.projectno=x.projectno)
						            where
						                p.costtypeid='123'
						                and year(l.paydt)='" . $checky . "'
						                and month(l.paydt)>='" . $checkmb . "'
						                and month(l.paydt)<='" . $checkme . "'
						            group by l.projectno
						            order by l.projectno
						            ";
		$query = $this->db->query($sql);
		$i = 0;
		$res = '';
		while ($row = $this->db->fetch_array($query)) {
			$i++;
			$res .= '<tr>
									                    <td>' . $i . '</td>
									                    <td>' . $row['prono'] . '</td>
									                    <td>' . $row['proname'] . '</td>
									                    <td>' . $row['sds'] . '</td>
									                </tr>';
		}
		return $res;
	}
	function model_pro_days_excel() {
		header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
		header("Cache-Control: public");
		header("Content-Type: application/vnd.ms-excel;");
		header("Content-Disposition: inline; filename=\"" . time() . ".xls\"");
		$res = '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px;" width="100%" cellpadding="0" cellspacing="0" border="1" >
						                <tr >
						                    <td>序号</td>
						                    <td>项目编号</td>
						                    <td>项目名称</td>
						                    <td>出差天数</td>
						                </tr>
						                ' . $this->model_pro_days() . '
						            </table>';
		echo un_iconv($res);
	}

	function model_teller_excel() {
	  $ckcom=$_SESSION['USER_COM']?$_SESSION['USER_COM']:'dl';
		$seabdt = $_GET['seabdt'];
		$seaedt = $_GET['seaedt'];
		$payTypes = $_GET['payTypes'];
		$sqlStr="";
		if(trim($payTypes)){
			if($payTypes=="A"){
				$sqlStr=" and  p.PayType='现金支付' ";
			}elseif($payTypes=="B"){
				$sqlStr=" and  p.PayType='银行支付' ";
			}elseif($payTypes=="C"){
				$sqlStr=" and  p.PayType='' ";
			}
		}
		
		include (WEB_TOR . 'includes/classes/excel_out_class.php');
		$xls = new ExcelXML('gb2312', false, 'My Test Sheet');
		$xls->setStyle(array (
			3,
			4,
			5,
			6
		));
		$data = array (
			1 => array (
				'序号',
				'报销人',
				'报销单号',
				'报销金额',
				'报销前借款',
				'报销后借款',
				'支付金额',
				'支付方式',
				'申请人账户',
				'申请人开户行',
				'付款日期',
				'付款备注'
			)
		);
		$sql = "select p.id , p.billnos , p.loanids , p.payremark , p.paydt , p.paycom , p.paytype
					   ,h.account , h.bank , u.user_name , p.paymoney , p.loanampre , p.loanamnow
						        from cost_pay p
						            left join user u on (p.userid=u.user_id)
						            left join user u1 on (p.teller=u1.user_id)
									left join hrms h on (u.user_id=h.user_id)
						        where u1.company='".$ckcom."'
						            and TO_DAYS(p.paydt)>=TO_DAYS('$seabdt')
						            and TO_DAYS(p.paydt)<=TO_DAYS('$seaedt')
									$sqlStr
						        order by id DESC ";
		$query = $this->db->query($sql);
		$i = 0;
		while ($row = $this->db->fetch_array($query)) {
			$i++;
			$sql = "select sum(amount) as sam from cost_summary_list where billno in (" . $row['billnos'] . ") ";
			$billarr = $this->_db->get_one($sql);
			$tpt = $row['paytype'];
			if ($row['paytype'] == '现金支付') {
				$tpt = '现金(' . $row['paycom'] . ')';
			} else {
				//$tpt='冲销';
			}
			$data[] = array (
				$i,
				$row['user_name'],
				str_replace("'", ' ', $row['billnos']),
				 ($billarr['sam']),
				 ($row['loanampre']),
				 ($row['loanamnow']),
				 ($row['paymoney']),
				$tpt,
				$row['account'],
				$row['bank'],
				$row['paydt'],
				$row['payremark']
			);
		}
		$xls->addArray($data);
		$xls->generateXML(time());
	}
	/**
	 *
	 */
	function model_teller_pay_list() {
		$ckcom=$_SESSION['USER_COM']?$_SESSION['USER_COM']:'dl';
		$seabdt = $_GET['seabdt'];
		$seaedt = $_GET['seaedt'];
		$sql = "select  u.user_name , p.paymoney , h.account , h.bank,GROUP_CONCAT(l.ProjectNo) as ProjectNos
						        from cost_pay p
									LEFT JOIN cost_summary_list l ON FIND_IN_SET(CONCAT('\'',l.BillNo,'\''),p.BillNos) AND l.isProject=1
						            left join user u on (p.userid=u.user_id)
									left join user u1 on (p.teller=u1.user_id)
						            left join hrms h on (u.user_id=h.user_id)
						        where 
								FIND_IN_SET('$ckcom',IF(u1.HandCom='' OR u1.HandCom IS NULL,u1.Company,u1.HandCom))
						          and  TO_DAYS(p.paydt)>=TO_DAYS('$seabdt')
						            and TO_DAYS(p.paydt)<=TO_DAYS('$seaedt')
						            and p.paytype='银行支付'
						        GROUP BY p.id order by p.id ";						
		$query = $this->db->query($sql);
		$i = 0;
		$sam = 0;
		while ($row = $this->db->fetch_array($query)) {
			$sam = round($row['paymoney'] + $sam, 2);
			$data->rows[] = array (
				'name' => un_iconv('付' . $row['user_name'] . '  （费用）'),
				'am' => num_to_maney_format($row['paymoney']),
				'acc' => $row['account'],
				'pno' => $row['ProjectNos'],
				'bank' => un_iconv($row['bank'])
			);
		}
		$data->footer[] = array (
			'am' => num_to_maney_format($sam)
		);
		echo json_encode($data);
	}
	/**
	 *
	 */
	function model_teller_pay_excel() {
		$seabdt = $_GET['seabdt'];
		$seaedt = $_GET['seaedt'];
		$ckcom=$_SESSION['USER_COM']?$_SESSION['USER_COM']:'dl';
		$xls = new includes_class_excelout('gb2312', true, 'My Test Sheet');
		$xls->setStyle(array (
			1
		));
		$xls->setID(array (
			1 => 's95'
		));
		$data = array (
			1 => array (
				'员工',
				'项目编号',
				'金额',
				'账号',
				'开户行'
			)
		);
		$sql = "select  u.user_name , p.paymoney , h.account , h.bank,GROUP_CONCAT(l.ProjectNo) as ProjectNos
						        from cost_pay p
									LEFT JOIN cost_summary_list l ON FIND_IN_SET(CONCAT('\'',l.BillNo,'\''),p.BillNos) AND l.isProject=1
						            left join user u on (p.userid=u.user_id)
									left join user u1 on (p.teller=u1.user_id)
						            left join hrms h on (u.user_id=h.user_id)
						        where
								FIND_IN_SET('$ckcom',IF(u1.HandCom='' OR u1.HandCom IS NULL,u1.Company,u1.HandCom))
						          and  TO_DAYS(p.paydt)>=TO_DAYS('$seabdt')
						            and TO_DAYS(p.paydt)<=TO_DAYS('$seaedt')
						            and p.paytype='银行支付'
						        GROUP BY p.id order by p.id";
		$query = $this->db->query($sql);
		$i = 0;
		$sam = 0;
		while ($row = $this->db->fetch_array($query)) {
			$sam = round($row['paymoney'] + $sam, 2);
			$data[] = array (
				$row['user_name'],
				$row['ProjectNos'],
				$row['paymoney'],
				$row['account'],
				$row['bank']
			);
		}
		$data[] = array (
			'总计',
			$sam
		);
		$xls->addArray($data);
		$xls->generateXML($seabdt . '__' . $seaedt);
	}
	/**
	 *类型统计
	 */
	function model_type($seadtb, $seadte, $ctids, $dept_id,$ProjectNO, $Purpose, $Place, $note) {
		$sqlStr = '';
		if ($seadtb) {
			$sqlStr .= " and to_days(l.paydt) >= to_days('" . $seadtb . "') ";
		}
		if ($seadte) {
			$sqlStr .= " and to_days(l.paydt) <= to_days('" . $seadte . "') ";
		}
		$ProjectNO = $this->model_getProjectNo($ProjectNO);
		if ($ProjectNO) {
			$sqlStr .= " and cl.ProjectNO = '$ProjectNO'  ";
		}
		if($dept_id&&$dept_id!=0){
			$dept = new model_system_dept();
			$son_id=$dept->GetSonIDAll($dept_id);
			if($son_id&&is_array($son_id)){
				$dept_id=$dept_id."','".implode("','",(array)$son_id);
				}
			$sqlStr .= " and dp.dept_id in ('$dept_id')  ";
		}
		if ($Purpose) {
			$sqlStr .= " and cl.Purpose like '%$Purpose%'  ";
		}
		if ($Place) {
			$sqlStr .= " and a.Place  like '%$Place%' ";
		}
		if ($note) {
			$sqlStr .= " and a.note like '%$note%' ";
		}
		if ($ctids&&is_array($ctids)) {
			$sqlStr .= " and d.costtypeid in ('" . implode("','", $ctids) . "') ";
		}
        if(empty($sqlStr)){
        	$sqlStr=' and  1<>1 ';
        }
		$data = array ();
		$costtype = array ();
		$sql = " select
	                l.costman , u.user_name  , sum(d.costmoney*d.days) as sm , d.costtypeid , t.costtypename, group_concat(l.costdates) as costdates
	                , dp.dept_name , ar.name as area , count(l.billno) as bct,a.Place, a.note as note, group_concat(cl.Purpose) as Purpose ,cl.ProjectNO
			            from  cost_detail d
			            left join cost_type t on (d.costtypeid=t.costtypeid)
			            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
			            left join cost_summary_list  l on (l.billno=d.billno)
			            left join department dp on (l.costbelongtodeptids=dp.dept_name)
			            left join user u on (l.costman=u.user_id)
			            left join department dpu on (dpu.dept_id=u.dept_id)
						left join area ar on (ar.id=u.area)
						left join cost_detail_list cl on (cl.HeadID=a.HeadID)
			            where
			                l.status in ('完成') and u.company = '".$_SESSION['USER_COM']."'  and a.billno=d.billno
			                " . $sqlStr . "
			            group by l.costman , d.costtypeid
						            order by u.dept_id  , u.user_id ";
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query)) {
			$data[$row['costman']]['name'] = $row['user_name'];
			$data[$row['costman']]['dept'] = $row['dept_name'];
			$data[$row['costman']]['area'] = $row['area'];
			$data[$row['costman']]['ProjectNO'] = $row['ProjectNO'];
			$data[$row['costman']]['Place'] = $row['Place'];
			$data[$row['costman']]['note'] = $row['note'];
			$data[$row['costman']]['Purpose'] = $row['Purpose'];
			$data[$row['costman']]['bct'] = $row['bct'];
			$data[$row['costman']]['costdates'] = $row['costdates'];
			$data[$row['costman']]['cost'][$row['costtypeid']] = $row['sm'];
			$costtype[$row['costtypeid']] = $row['costtypename'];
		}
		$sql = " select
	                l.costman , u.user_name  , sum(d.costmoney*d.days) as sm , d.costtypeid , t.costtypename, group_concat(l.costdates) as costdates
	                , dp.dept_name , ar.name as area , count(l.billno) as bct,a.Place,a.note,group_concat(cl.Purpose) as Purpose, group_concat(cl.ProjectNO) as ProjectNO
			            from  cost_detail_project d
			            left join cost_type t on (d.costtypeid=t.costtypeid)
			            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
			            left join cost_summary_list  l on (l.billno=d.billno)
			            left join department dp on (l.costbelongtodeptids=dp.dept_name)
			            left join user u on (l.costman=u.user_id)
			            left join department dpu on (dpu.dept_id=u.dept_id)
						left join area ar on (ar.id=u.area)
						left join cost_detail_list cl on (cl.HeadID=a.HeadID)
			            where
			                l.status in ('完成') and u.company = '".$_SESSION['USER_COM']."'  and a.billno=d.billno
			                " . $sqlStr . "
			            group by l.costman , d.costtypeid
						            order by u.dept_id  , u.user_id ";
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query)) {
			$data[$row['costman'].'-xm']['name'] = $row['user_name'];
			$data[$row['costman'].'-xm']['dept'] = '工程项目';
			$data[$row['costman'].'-xm']['area'] = $row['area'];
			$data[$row['costman'].'-xm']['ProjectNO'] = $row['ProjectNO'];
			$data[$row['costman'].'-xm']['Place'] = $row['Place'];
			$data[$row['costman'].'-xm']['note'] = $row['note'];
			$data[$row['costman'].'-xm']['Purpose'] = $row['Purpose'];
			$data[$row['costman'].'-xm']['bct'] = $row['bct'];
			$data[$row['costman'].'-xm']['costdates'] = $row['costdates'];
			$data[$row['costman'].'-xm']['cost'][$row['costtypeid']] = $row['sm'];
			$costtype[$row['costtypeid']] = $row['costtypename'];
		}
		if (!empty ($costtype)) {
			$res .= '<tr><td>部门</td><td>员工</td><td>归属</td>';

				$res .= '<td>项目</td>';
				$res .= '<td>事由</td>';
				$res .= '<td>地点</td>';
				$res .= '<td>时间</td>';
				$res .= '<td>摘要</td>';

			$res .= '<td>单数</td><td>小计</td>';
			foreach ($costtype as $key => $val) {
				$res .= '<td>' . $val . '</td>';
			}
			$res .= '</tr>';
			foreach ($data as $key => $val) {
				$res .= '<tr><td>' . $val['dept'] . '</td><td>' . $val['name'] . '</td><td>' . $val['area'] . '</td>';

					$res .= '<td>' . $val['ProjectNO'] . '</td>';


					$res .= '<td>' . $val['Purpose'] . '</td>';


					$res .= '<td>' . $val['Place'] . '</td>';
				    $res .= '<td>' . $val['costdates'] . '</td>';

					$res .= '<td>' . $val['note'] . '</td>';

				$res .= '<td>' . $val['bct'] . '</td>
						<td align="right">' . num_to_maney_format(array_sum($val['cost'])) . '</td>';
				foreach ($costtype as $keyc => $valc) {
					$res .= '<td align="right">' . num_to_maney_format($val['cost'][$keyc]) . '</td>';
				}
				$res .= '</tr>';
			}
		}
		return $res;
	}
	function model_type_excel($seadtb, $seadte, $ctids,$dept_id, $ProjectNO, $purpose, $place, $note) {
		header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
		header("Cache-Control: public");
		header("Content-Type: application/vnd.ms-excel;");
		header("Content-Disposition: inline; filename=\"" . time() . ".xls\"");
		$res = '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px;" width="100%" cellpadding="0" cellspacing="0" border="1" >
				' . $this->model_type($seadtb, $seadte, $ctids, $dept_id,$ProjectNO, $purpose, $place, $note) . '
				</table>';
		echo un_iconv($res);
	}

	function model_getProjectNo($proInfo) {
		if ($proInfo) {
			$QR_SerArr = explode("--", $proInfo);
			$sqlStr = "";
			if (isset ($QR_SerArr[0]))
				$sqlStr .= "  Name='" .
				$QR_SerArr[0] . "'  ";
			if (isset ($QR_SerArr[1]))
				$sqlStr .= " and ProjectNo ='" .
				$QR_SerArr[1] . "'";
			$sql = "select DISTINCT ProjectNo from xm_lx where ProjectNo='$proInfo' or ( $sqlStr ) ";
			$query = $this->db->get_one($sql);
			return $query['ProjectNo'];
		}
	}

	/**
	 *
	 */
	function model_loan_pay_list() {
		$seabdt = $_GET['seabdt'];
		$seaedt = $_GET['seaedt'];
		$ckcom=$_SESSION['USER_COM']?$_SESSION['USER_COM']:'dl';
		$sql = "select  u.user_name , p.amount as paymoney , h.account , h.bank , p.reason , x.name as proname
						        from loan_list p
						            left join user u on (p.debtor=u.user_id)
						            left join hrms h on (u.user_id=h.user_id)
						            left join xm_lx x on (p.projectno=x.projectno)
						        where
						            TO_DAYS(p.paydt)>=TO_DAYS('$seabdt')
						            and TO_DAYS(p.paydt)<=TO_DAYS('$seaedt')
						            and p.paytype='银行支付'
						            and p.belongcomcode='".$_SESSION['USER_COM']."'
						        group by p.id
						        order by p.id ";
		$query = $this->db->query($sql);
		$i = 0;
		$sam = 0;
		while ($row = $this->db->fetch_array($query)) {
			$rmk = $row['reason'];
			if (!empty ($row['proname'])) {
				$rmk = $row['proname'];
			}
			$rmk = $this->gl->cut_str($rmk, 5, 0, 'GBK', false);
			$sam = round($row['paymoney'] + $sam, 2);
			$data->rows[] = array (
				'name' => un_iconv('付' . $row['user_name'] . '  （' . $rmk . '借款）'),
				'am' => num_to_maney_format($row['paymoney']),
				'acc' => $row['account'],
				'bank' => un_iconv($row['bank'])
			);
		}
		$data->footer[] = array (
			'am' => num_to_maney_format($sam)
		);
		echo json_encode($data);
	}
	/**
	 *
	 */
	function model_loan_pay_excel() {
		$seabdt = $_GET['seabdt'];
		$seaedt = $_GET['seaedt'];
		$xls = new includes_class_excelout('gb2312', true, 'My Test Sheet');
		$xls->setStyle(array (
			1
		));
		$xls->setID(array (
			1 => 's95'
		));
		$data = array (
			1 => array (
				'员工',
				'金额',
				'账号',
				'开户行'
			)
		);
		$sql = "select  u.user_name , p.amount as paymoney , h.account , h.bank , p.reason , x.name as proname
						        from loan_list p
						            left join user u on (p.debtor=u.user_id)
						            left join hrms h on (u.user_id=h.user_id)
						            left join xm_lx x on (p.projectno=x.projectno)
						        where
						            TO_DAYS(p.paydt)>=TO_DAYS('$seabdt')
						            and TO_DAYS(p.paydt)<=TO_DAYS('$seaedt')
						            and p.paytype='银行支付'
						            and p.belongcomcode='".$_SESSION['USER_COM']."'
						        group by p.id
						        order by p.id ";
		$query = $this->db->query($sql);
		$i = 0;
		$sam = 0;
		while ($row = $this->db->fetch_array($query)) {
			$rmk = $row['reason'];
			if (!empty ($row['proname'])) {
				$rmk = $row['proname'];
			}
			$rmk = $this->gl->cut_str($rmk, 5, 0, 'GBK', false);
			$sam = round($row['paymoney'] + $sam, 2);
			$data[] = array (
				'付' . $row['user_name'] . '  （' . $rmk . '借款）',
				$row['paymoney'],
				$row['account'],
				$row['bank']
			);
		}
		$data[] = array (
			'总计',
			$sam
		);
		$xls->addArray($data);
		$xls->generateXML($seabdt . '__' . $seaedt);
	}
	function model_purposeData() {
		$select = un_iconv(trim($_POST['select'] ? $_POST['select'] : $_GET['select']));
		$sql = " SELECT Purpose
						                 FROM cost_detail_list
						                 where 1  AND `Status`='提交'
						                  group by Purpose";
		$query = $this->db->query($sql);
		$i = 0;
		$Array[$i] = (array (
					'id' => '',
					'text' =>'',

				));
		while (($row = $this->db->fetch_array($query)) != false) {
			$i++;
			if ($select == un_iconv($row["Purpose"])) {
				$Array[$i] = (array (
					'id' => un_iconv($row["Purpose"]),
					'text' => un_iconv($row["Purpose"]),
					'selected' => true
				));
			} else {
				$Array[$i] = (array (
					'id' => un_iconv($row["Purpose"]),
					'text' => un_iconv($row["Purpose"]),

				));
			}

		}
		return $Array ? json_encode($Array) : '';
	}
	function model_placeData() {
		$select = un_iconv(trim($_POST['select'] ? $_POST['select'] : $_GET['select']));
		$sql = " SELECT Place
						                 FROM cost_detail_assistant
						                 where 1   AND `Status`='上交'
						                  group by Place";
		$query = $this->db->query($sql);
		$i = 0;
		$Array[$i] = (array (
					'id' => '',
					'text' =>'',

				));

		while (($row = $this->db->fetch_array($query)) != false) {
          $i++;
			if ($select == un_iconv($row["Place"])) {
				$Array[$i] = (array (
					'id' => un_iconv($row["Place"]),
					'text' => un_iconv($row["Place"]),
					'selected' => true
				));
			} else {
				$Array[$i] = (array (
					'id' => un_iconv($row["Place"]),
					'text' => un_iconv($row["Place"]),

				));
			}
		}
		return $Array ? json_encode($Array) : '';
	}
	function model_noteData() {
		$select = un_iconv(trim($_POST['select'] ? $_POST['select'] : $_GET['select']));
		$sql = " SELECT note
						                 FROM cost_detail_assistant
						                 where 1   AND `Status`='上交'
						                 group by note";
		$query = $this->db->query($sql);
		$i = 0;
		$Array[$i] = (array (
					'id' => '',
					'text' =>'',

				));
		while (($row = $this->db->fetch_array($query)) != false) {
			$i++;
			if ($select == un_iconv($row["note"])) {
				$Array[$i] = (array (
					'id' => un_iconv($row["note"]),
					'text' => un_iconv($row["note"]),
					'selected' => true
				));
			} else {
				$Array[$i] = (array (
					'id' => un_iconv($row["note"]),
					'text' => un_iconv($row["note"]),

				));
			}
		}
		return $Array ? json_encode($Array) : '';
	}
	function model_deptData() {
		$select = un_iconv(trim($_POST['select'] ? $_POST['select'] : $_GET['select']));
		$sql = " SELECT dept_id,dept_name
		                 FROM department
		                 where 1  and  delflag=0
		                 order by dept_id";
		$query = $this->db->query($sql);
		$i = 0;
		$Array[$i] = (array (
					'id' => '',
					'text' =>'',

				));
		while (($row = $this->db->fetch_array($query)) != false) {
			$i++;
			if ($select == un_iconv($row["dept_id"])) {
				$Array[$i] = (array (
					'id' => un_iconv($row["dept_id"]),
					'text' => un_iconv($row["dept_name"]),
					'selected' => true
				));
			} else {
				$Array[$i] = (array (
					'id' => un_iconv($row["dept_id"]),
					'text' => un_iconv($row["dept_name"]),

				));
			}


		}
		return $Array ? json_encode($Array) : '';

	}

	//*********************************析构函数************************************
	function __destruct() {
	}

}
?>