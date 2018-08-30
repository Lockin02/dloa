<?php
class model_sell_clue extends model_base {
	public $other_user = array(
								'������'=>'eagle.zhu',
								'������'=>'xiaoming.geng',
								'����'=>'zhen.wang',
								'��ӱ'=>'ying.zhang',
								'���'=>'jingjing.yang');
	function __construct() {
		parent::__construct ();
		$this->tbl_name = 'sell_clue_info';
	}
	/**
	 * �����б�
	 */
	function model_list() {
		$userid = $_SESSION['USER_ID'];
		if (! in_array ( $userid, $this->other_user )) {
			$where = " (a.userid='$userid' or a.sales='$userid' or e.userid='$userid') ";
		}
		if ($_GET['areaid'])
			$where .= $where ? " and a.areaid='" . $_GET['areaid'] . "'" : "a.areaid='" . $_GET['areaid'] . "'";
		if ($_GET['userid'])
			$where .= $where ? " and a.userid='" . $_GET['userid'] . "'" : "a.userid='" . $_GET['userid'] . "'";
		if ($_GET['status'] || $_GET['status'] == '0')
			$where .= $where ? " and a.status='" . $_GET['status'] . "'" : "a.status='" . $_GET['status'] . "'";
		if ($_GET['contract'] || $_GET['contract'] == '0')
			$where .= $where ? " and a.contract='" . $_GET['contract'] . "'" : "a.contract='" . $_GET['contract'] . "'";
		if ($where)
			$where = ' where ' . $where;
		if (! $this->num) {
			$rs = $this->get_one ( "
									select 
										count(0) as num 
									from 
										sell_clue_info as a 
										left join sell_staff as d on find_in_set(a.areaid,d.area)
										left join sell_staff as e on e.id=d.tid 
									$where
									" );
			$this->num = $rs['num'];
		}
		if ($this->num > 0) {
			$query = $this->query ( "
									select
										a.*,b.user_name,c.name as area_name
									from
										sell_clue_info as a
										left join user as b on b.user_id=a.userid
										left join sell_area as c on c.id=a.areaid
										left join sell_staff as d on find_in_set(a.areaid,d.area)
										left join sell_staff as e on e.id=d.tid
									$where
									order by a.id desc
									limit $this->start," . pagenum . "
								" );
			while ( ($rs = $this->fetch_array ( $query )) != false ) {
				$edit_link = '<a href="?model=sell_clue&action=edit_clue&id=' . $rs['id'] . '&key=' . $rs['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=720" class="thickbox" title="�޸�����">�޸�</a>';
				$info_link = '<a href="?model=sell_clue&action=show_clue&id=' . $rs['id'] . '&key=' . $rs['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=720" class="thickbox" title="�鿴����">�鿴</a>';
				$str .= '
						<tr>
							<td>' . $rs['id'] . '</td>
							<td>' . date ( 'Y-m-d', $rs['date'] ) . '</td>
							<td>' . $rs['user_name'] . '</td>
							<td>' . $rs['unit'] . '</td>
							<td>' . $rs['area_name'] . '</td>
							<td>' . $rs['name'] . '</td>
							<td>' . $rs['phone'] . '</td>
							<td nowrap>' . $rs['content'] . '</td>
							<td>' . ($rs['status'] == 1 ? '��ͨ�����' : ($rs['status'] == - 1 ? '�����' : '�����')) . '</td>
							<td>' . ($rs['contract'] == 1 ? '��' : ($rs['contract'] == - 1 ? '��' : 'δ����')) . '</td>
							<td>' . ($rs['userid'] == $_SESSION['USER_ID'] && $rs['status'] < 1 ? $edit_link : $info_link) . '</td>
						</tr>
				';
			}
			$showpage = new includes_class_page ();
			$showpage->show_page ( array(
											'total'=>$this->num,
											'perpage'=>pagenum) );
			$showpage->_set_url ( 'num=' . $this->num . '&areaid=' . $_GET['areaid'] . '&status=' . $_GET['status'] . '&contract=' . $_GET['contract'] . '&userid=' . $_GET['userid'] . '&username=' . $_GET['username'] );
			return $str . '<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
		}
	}
	/**
	 * ��ȡ��������
	 * @param $id
	 * @param $key
	 */
	function get_clue($id, $key) {
		return $this->get_one ( "
								select
									a.*,b.user_name,c.name as area_name,d.user_name as sales_name
								from
									sell_clue_info as a
									left join user as b on b.user_id=a.userid
									left join sell_area as c on c.id=a.areaid
									left join user as d on d.user_id=a.sales
								where
									a.id=$id and a.rand_key='" . $key . "'
		" );
	}
	/**
	 * ��ȡ�������û�ID������
	 * @param unknown_type $areaid
	 */
	function get_explorer($areaid) {
		$rs = $this->get_one ( "
							select 
								a.tid,b.userid,c.user_name
							from
								sell_staff as a
								left join sell_staff as b on b.id=a.tid
								left join user as c on c.user_id=b.userid
							where
								find_in_set($areaid,a.area);
		" );
		return $rs;
	}
	/**
	 * ��ȡ�¼�������Ա
	 * @param $tid
	 */
	function get_lower($tid) {
		$query = $this->query ( "
								select
									a.*,b.user_name
								from
									sell_staff as a
									left join user as b on b.user_id=a.userid
								where
									a.tid='$tid'
							" );
		$data = array();
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$data[] = $rs;
		}
		return $data;
	
	}
	/**
	 * ��������
	 * @param unknown_type $areaid
	 */
	function model_area_select($areaid = '') {
		$area = new model_sell_staff ();
		$data = $area->get_area ();
		if ($data) {
			foreach ( $data as $row ) {
				if ($areaid == $row['id']) {
					$srea_select .= '<option selected value="' . $row['id'] . '">' . $row['name'] . '</option>';
				} else {
					$srea_select .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
				}
			}
		}
		return $srea_select;
	}
	/**
	 * ��������
	 * @param $data
	 */
	function model_save_clue($data, $type = 'add', $id = null, $key = null) {
		if ($type == 'add') {
			$rs = $this->get_one("select userid from sell_staff where find_in_set('".$data['areaid']."',area)");
			$data['sales']=$rs['userid'];
			$data['status'] = 1;
			$id = $this->create ( $data );
			if ($id)
			{
				$rs = $this->find('id='.$id);
				return $this->send_email($id, $rs['rand_key'], 'add');
			}else{
				return false;
			}
		} elseif ($type == 'update') {
			return $this->update ( array(
											'id'=>$id,
											'rand_key'=>$key), $data );
		}
	}
	/**
	 * ɾ������
	 * @param $id
	 * @param $key
	 */
	function model_delete_clue($id, $key) {
	
	}
	/**
	 * �������
	 * @param $id
	 * @param $key
	 * @param $status
	 */
	function model_audit_clue($id, $key, $status, $sales = '', $notse = '') {
		$this->update ( array(
									'id'=>$id,
									'rand_key'=>$key), array(
																	'status'=>$status,
																	'sales'=>$sales,
																	'notse'=>$notse) );
		return $this->send_email($id,$key,'audit');
	}
	/**
	 * ���ú�ͬ
	 * @param $id
	 * @param $key
	 * @param $contract
	 * @param $reason
	 */
	function model_set_contract($id, $key, $contract,$reason) {
		$this->update ( array(
									'id'=>$id,
									'rand_key'=>$key), array(
																	'contract'=>$contract,
																	'reason'=>$reason
															)
							);
		return $this->send_email($id,$key,'contract');
	}
	/**
	 * �����ʼ�
	 * @param $id
	 * @param $key
	 * @param $type
	 */
	function send_email($id,$key,$type)
	{
		$rs = $this->get_one("
					select
						a.*,b.name as area_name
					from
						sell_clue_info as a
						left join sell_area as b on b.id=a.areaid
					where
						a.id='$id' and a.rand_key='$key'
		");
		$userid_arr = array( //�̶�Ҫ���͵���Ա
							$rs['userid'],
							'eagle.zhu',
							'jingjing.yang',
							'xiaoming.geng',
							'ying.zhang',
							'zhen.wang'
		);
		$exploer = $this->get_explorer($rs['areaid']);
		$userid_arr[] = $exploer['userid'];
		if ($rs['sales']) $userid_arr[] = $rs['sales'];
		$gl = new includes_class_global ();
		$username = $gl->GetUserinfo($rs['userid'],'user_name');
		$Address = $gl->get_email ( $userid_arr );
		$Address = array_unique($Address);
		$Email = new includes_class_sendmail ();
		if ($type=='audit')
		{
			return $Email->send($_SESSION['USERNAME'].($rs['status']==1 ? ' ͨ������� ' : ' ����� ').$username.' �ύ����������','��ϸ�������¼OA�鿴��'.oaurlinfo,$Address);
		}elseif ($type=='contract'){
			$title = $username.'�ύ����������ת��Ϊ��ͬ'.($rs['contract']==1 ? '�ɹ���':'ʧ��' );
			return $Email->send($title,($rs['contract']!=1 ? $rs['reason'].'<br />':'').'��ϸ�������¼OA�鿴��'.oaurlinfo,$Address);
		}elseif($type=="add"){
			
			$body .='�ύ���ڣ�'.date('Y-m-d H:i:s',$rs['date']).'<br/>';
			$body .='�ύ�ˣ�'.$_SESSION['USERNAME'].'<br />';
			$body .='�ͻ���λ���ƣ�'.$rs['unit'].'<br />';
			$body .='�ͻ���λ��������:'.$rs['area_name'].'<br />';
			$body .='��ϵ��������'.$rs['name'].'<br />';
			$body .='��ϵ�˵绰��'.$rs['phone'].'<br>';
			$body .='<hr>';
			$body .='����������ϸ�������£�<br />';
			$body .=$rs['content'];
			$body .=oaurlinfo;
			return $Email->send($_SESSION['USERNAME'].'�ύ���µ�����������', $body, $Address);
		}
	}
	
	/**
	 * ���������б�EXCEL����
	 */
	function model_list_export() {
		$userid = $_SESSION['USER_ID'];
		if (! in_array ( $userid, $this->other_user )) {
			$where = " (a.userid='$userid' or a.sales='$userid' or e.userid='$userid') ";
		}
		if ($_GET['areaid'])
			$where .= $where ? " and a.areaid='" . $_GET['areaid'] . "'" : "a.areaid='" . $_GET['areaid'] . "'";
		if ($_GET['userid'])
			$where .= $where ? " and a.userid='" . $_GET['userid'] . "'" : "a.userid='" . $_GET['userid'] . "'";
		if ($_GET['status'] || $_GET['status'] == '0')
			$where .= $where ? " and a.status='" . $_GET['status'] . "'" : "a.status='" . $_GET['status'] . "'";
		if ($_GET['contract'] || $_GET['contract'] == '0')
			$where .= $where ? " and a.contract='" . $_GET['contract'] . "'" : "a.contract='" . $_GET['contract'] . "'";
		if ($where)
			$where = ' where ' . $where;
		if (! $this->num) {
			$rs = $this->get_one ( "
									select 
										count(0) as num 
									from 
										sell_clue_info as a 
										left join sell_staff as d on find_in_set(a.areaid,d.area)
										left join sell_staff as e on e.id=d.tid 
									$where
									" );
			$this->num = $rs['num'];
		}
		if ($this->num > 0) {
			$query = $this->query ( "
									select
										a.*,b.user_name,c.name as area_name,f.user_name as sales_name
									from
										sell_clue_info as a
										left join user as b on b.user_id=a.userid
										left join sell_area as c on c.id=a.areaid
										left join sell_staff as d on find_in_set(a.areaid,d.area)
										left join sell_staff as e on e.id=d.tid
										left join user as f on f.user_id=a.sales
									$where
									order by a.id desc
									limit $this->start," . pagenum . "
								" );
			require_once WEB_TOR . 'includes/classes/PHPExcel.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
			$PHPExcel = new PHPExcel ();
			$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
			$objWriter = new PHPExcel_Writer_Excel5 ( $PHPExcel );
			
			$PHPExcel->setActiveSheetIndex ( 0 );
			$objActSheet = $PHPExcel->getActiveSheet ();
			//=================��ͷ��������======================
			$objActSheet->setTitle ( un_iconv ( '���������б�' ) );
			$objActSheet->setCellValue ( 'A1', un_iconv ( '���' ) );
			$objActSheet->setCellValue ( 'B1', un_iconv ( '�ύ����' ) );
			$objActSheet->setCellValue ( 'C1', un_iconv ( '�ύ��' ) );
			$objActSheet->setCellValue ( 'D1', un_iconv ( '�ͻ���λ����' ) );
			$objActSheet->setCellValue ( 'E1', un_iconv ( '�ͻ���λ��������' ) );
			$objActSheet->setCellValue ( 'F1', un_iconv ( '��ϵ������' ) );
			$objActSheet->setCellValue ( 'G1', un_iconv ( '��ϵ�˵绰' ) );
			$objActSheet->setCellValue ( 'H1', un_iconv ( '������������ϸ����' ) );
			$objActSheet->setCellValue ( 'I1', un_iconv ( '���״̬' ) );
			$objActSheet->setCellValue ( 'J1', un_iconv ( "�������" ) );
			$objActSheet->setCellValue ( 'K1', un_iconv ( '����������Ա' ) );
			$objActSheet->setCellValue ( 'L1', un_iconv ( 'ת��Ϊ��ͬ' ) );
			$objActSheet->setCellValue ( 'M1', un_iconv ( 'ʧ��ԭ��' ) );
			//================��ͷ��ʽ����==============
			$objActSheet->getStyle ( 'A1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
			$objActSheet->getStyle ( 'A1' )->getFill ()->getStartColor ()->setRGB ( 'c0c0c0' );
			$objActSheet->getStyle ( 'A1' )->getFont ()->setBold ( true );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setWrapText ( true );
			$A1Style = $objActSheet->getStyle ( 'A1' );
			$objBorderA1 = $A1Style->getBorders ();
			$objBorderA1->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
			$objBorderA1->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objActSheet->duplicateStyle ( $A1Style, 'A1:M1' );
			$objActSheet->getRowDimension ( '1' )->setRowHeight ( 30 );
			//============================���õ�Ԫ���============================
			$Width_Array = array(
												'A'=>8,
												'B'=>18,
												'C'=>15,
												'D'=>25,
												'E'=>10,
												'F'=>15,
												'G'=>15,
												'H'=>35,
												'I'=>15,
												'J'=>25,
												'K'=>20,
												'L'=>25,
												'M'=>25);
			foreach ( $Width_Array as $key => $val ) {
				$objActSheet->getColumnDimension ( $key )->setWidth ( $val );
			}
			$i = 1;
			while ( ($rs = $this->fetch_array ( $query )) != false ) {
				$i ++;
				$status = ($rs['status'] == 1 ? '��ͨ�����' : ($rs['status'] == - 1 ? '�����' : '�����'));
				$contract = ($rs['contract'] == 1 ? '�ɹ�' : ($rs['contract'] == - 1 ? 'ʧ��' : 'δ����'));
				$rs = un_iconv ( $rs );
				$objActSheet->setCellValue ( 'A' . $i, $rs['id'] );
				$objActSheet->setCellValue ( 'B' . $i, date ( 'Y-m-d', $rs['date'] ) );
				$objActSheet->setCellValue ( 'C' . $i, $rs['user_name'] );
				$objActSheet->setCellValue ( 'D' . $i, $rs['unit'] );
				$objActSheet->setCellValue ( 'E' . $i, $rs['area_name'] );
				$objActSheet->setCellValue ( 'F' . $i, $rs['name'] );
				$objActSheet->setCellValue ( 'G' . $i, $rs['phone'] );
				$objActSheet->setCellValue ( 'H' . $i, $rs['content'] );
				$objActSheet->setCellValue ( 'I' . $i, un_iconv ( $status ) );
				$objActSheet->setCellValue ( 'J' . $i, $rs['notse'] );
				$objActSheet->setCellValue ( 'K' . $i, $rs['sales_name'] );
				$objActSheet->setCellValue ( 'L' . $i, un_iconv ( $contract ) );
				$objActSheet->setCellValue ( 'M' . $i, $rs['reason'] );
			}
			if ($i > 1) {
				$A2Style = $objActSheet->getStyle ( 'A2' );
				$objBorderA2 = $A2Style->getBorders ();
				$objBorderA2->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
				$objBorderA2->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setWrapText ( true );
				
				$objActSheet->duplicateStyle ( $A2Style, 'A2:M' . $i );
			}
			//=======================================
			header ( "Pragma: public" );
			header ( "Expires: 0" );
			header ( "Cache-Control:must-revalidate, post-check=0, pre-check=0" );
			header ( "Content-Type:application/force-download" );
			header ( "Content-Type: application/vnd.ms-excel;" );
			header ( "Content-Type:application/octet-stream" );
			header ( "Content-Type:application/download" );
			header ( "Content-Disposition:attachment;filename=" . time () . '.xls' );
			header ( "Content-Transfer-Encoding:binary" );
			$objWriter->save ( 'php://output' );
		}
	}
	/**
	 * ͳ�������б�
	 * @param $export ��������
	 */
	function model_count_list($export = false) {
		$userid = $_SESSION['USER_ID'];
		if (! in_array ( $userid, $this->other_user )) {
			$where = " (a.userid='$userid' or a.sales='$userid' or e.userid='$userid') ";
		}
		if ($_GET['areaid'])
			$where .= $where ? " and a.areaid='" . $_GET['areaid'] . "'" : "a.areaid='" . $_GET['areaid'] . "'";
		if ($_GET['userid'])
			$where .= $where ? " and a.userid='" . $_GET['userid'] . "'" : "a.userid='" . $_GET['userid'] . "'";
		if ($_GET['status'] || $_GET['status'] == '0')
			$where .= $where ? " and a.status='" . $_GET['status'] . "'" : "a.status='" . $_GET['status'] . "'";
		if ($_GET['contract'] || $_GET['contract'] == '0')
			$where .= $where ? " and a.contract='" . $_GET['contract'] . "'" : "a.contract='" . $_GET['contract'] . "'";
		if ($where)
			$where = ' where ' . $where;
		if (! $this->num) {
			$rs = $this->get_one ( "
									select 
										count(distinct(a.userid)) as num
									from 
										sell_clue_info as a 
										left join sell_staff as d on find_in_set(a.areaid,d.area)
										left join sell_staff as e on e.id=d.tid 
									$where
									" );
			$this->num = $rs['num'];
		}
		if ($this->num > 0) {
			$query = $this->query ( "
									select 
										distinct(a.userid),user_name
									from 
										sell_clue_info as a
										left join user as b on b.user_id=a.userid
										left join sell_staff as d on find_in_set(a.areaid,d.area)
										left join sell_staff as e on e.id=d.tid 
									$where
									" . (! $export ? "limit $this->start," . pagenum : '') . " 
			" );
			$data = array();
			while ( ($rs = $this->fetch_array ( $query )) != false ) {
				$status_sql = $this->query ( "
											select 
												count(0) as num,a.userid,a.status 
											from 
												sell_clue_info as a 
												left join sell_staff as d on find_in_set(a.areaid,d.area)
												left join sell_staff as e on e.id=d.tid 
											" . ($where ? $where . 'and ' : 'where') . " a.userid='" . $rs['userid'] . "' 
											group by a.userid,a.status
										" );
				$status = array();
				while ( ($row = $this->fetch_array ( $status_sql )) != false ) {
					$status[$row['status']] = $row['num'] ? $row['num'] : 0;
				}
				$contract = array();
				$contract_sql = $this->query ( "
												select 
													count(0) as num,a.userid,a.contract 
												from 
													sell_clue_info as a 
													left join sell_staff as d on find_in_set(a.areaid,d.area)
													left join sell_staff as e on e.id=d.tid 
												" . ($where ? $where . 'and ' : 'where') . " a.userid='" . $rs['userid'] . "' 
												group by a.userid,a.contract
											" );
				while ( ($arr = $this->fetch_array ( $contract_sql )) != false ) {
					$contract[$arr['contract']] = $arr['num'] ? $arr['num'] : 0;
				}
				$count = array_sum ( $status );
				$str .= '
						<tr>
							<td>' . $rs['user_name'] . '</td>
							<td>' . $count . '</td>
							<td width="7%">' . ($status[1] ? $status[1] : 0) . '</td>
							<td>' . round ( $status[1] * 100 / $count, 2 ) . '%</td>
							<td width="7%">' . ($status[- 1] ? $status[- 1] : 0) . '</td>
							<td>' . round ( $status[- 1] * 100 / $count, 2 ) . '%</td>
							<td width="7%">' . ($status[0] ? $status[0] : 0) . '</td>
							<td>' . round ( $status[0] * 100 / $count, 2 ) . '%</td>
							<td width="7%">' . ($contract[1] ? $contract[1] : 0) . '</td>
							<td>' . round ( $contract[1] * 100 / $count, 2 ) . '%</td>
							<td width="7%">' . ($contract[- 1] ? $contract[- 1] : 0) . '</td>
							<td>' . round ( $contract[- 1] * 100 / $count, 2 ) . '%</td>
							<td width="7%">' . ($contract[0] ? $contract[0] : 0) . '</td>
							<td>' . round ( $contract[0] * 100 / $count, 2 ) . '%</td>
						</tr>
				';
				if ($export) {
					$data['list'][] = $rs;
					$data['status'][] = $status;
					$data['contract'][] = $contract;
				}
			}
			if ($export) {
				return $data;
			} else {
				$showpage = new includes_class_page ();
				$showpage->show_page ( array(
												'total'=>$this->num,
												'perpage'=>pagenum) );
				$showpage->_set_url ( 'num=' . $this->num . '&areaid=' . $_GET['areaid'] . '&status=' . $_GET['status'] . '&contract=' . $_GET['contract'] . '&userid=' . $_GET['userid'] . '&username=' . $_GET['username'] );
				return $str . '<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
			}
		}
	}
	/**
	 * 
	 */
	function model_count_export() {
		$data = $this->model_count_list ( true );
		require_once WEB_TOR . 'includes/classes/PHPExcel.php';
		require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
		$PHPExcel = new PHPExcel ();
		$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
		$objWriter = new PHPExcel_Writer_Excel5 ( $PHPExcel );
		$PHPExcel->setActiveSheetIndex ( 0 );
		$objActSheet = $PHPExcel->getActiveSheet ();
		//=====================���ñ�ͷ==========================
		$objActSheet->setTitle ( un_iconv ( '��Ʒ����ͳ�Ʊ�' ) );
		
		$objActSheet->mergeCells ( 'A1:A2' );
		$objActSheet->setCellValue ( 'A1', un_iconv ( '�ύ��' ) );
		$objActSheet->getColumnDimension ( 'A' )->setWidth ( 15 );
		$objActSheet->mergeCells ( 'B1:B2' );
		$objActSheet->setCellValue ( 'B1', un_iconv ( '���ύ����' ) );
		$objActSheet->getColumnDimension ( 'B' )->setWidth ( 15 );
		$objActSheet->mergeCells ( 'C1:H1' );
		$objActSheet->setCellValue ( 'C1', un_iconv ( '���״̬' ) );
		$objActSheet->mergeCells ( 'I1:N1' );
		$objActSheet->setCellValue ( 'I1', un_iconv ( 'ת����ͬ' ) );
		
		$objActSheet->mergeCells ( 'C2:D2' );
		$objActSheet->setCellValue ( 'C2', un_iconv ( 'ͨ�����' ) );
		$objActSheet->mergeCells ( 'E2:F2' );
		$objActSheet->setCellValue ( 'E2', un_iconv ( '�����' ) );
		$objActSheet->mergeCells ( 'G2:H2' );
		$objActSheet->setCellValue ( 'G2', un_iconv ( '�����' ) );
		
		$objActSheet->mergeCells ( 'I2:J2' );
		$objActSheet->setCellValue ( 'I2', un_iconv ( '�ɹ�' ) );
		$objActSheet->mergeCells ( 'K2:L2' );
		$objActSheet->setCellValue ( 'K2', un_iconv ( 'ʧ��' ) );
		$objActSheet->mergeCells ( 'M2:N2' );
		$objActSheet->setCellValue ( 'M2', un_iconv ( 'δ����' ) );
		
		//================��ͷ��ʽ����==============
		$objActSheet->getStyle ( 'A1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
		$objActSheet->getStyle ( 'A1' )->getFill ()->getStartColor ()->setRGB ( 'c0c0c0' );
		$objActSheet->getStyle ( 'A1' )->getFont ()->setBold ( true );
		$objActSheet->getStyle ( 'A1' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		$objActSheet->getStyle ( 'A1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objActSheet->getStyle ( 'A1' )->getAlignment ()->setWrapText ( true );
		$A1Style = $objActSheet->getStyle ( 'A1' );
		$objBorderA1 = $A1Style->getBorders ();
		$objBorderA1->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		$objBorderA1->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
		$objBorderA1->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		$objBorderA1->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		$objBorderA1->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
		$objActSheet->duplicateStyle ( $A1Style, 'A1:N2' );
		$objActSheet->getRowDimension ( '1' )->setRowHeight ( 25 );
		//===========================================================
		if ($data) {
			$i = 2;
			foreach ( $data['list'] as $key => $row ) {
				$i ++;
				$row = un_iconv ( $row );
				$count = array_sum ( $data['status'][$key] );
				$objActSheet->setCellValue ( 'A' . $i, $row['user_name'] );
				$objActSheet->setCellValue ( 'B' . $i, $count );
				//===���״̬
				$objActSheet->setCellValue ( 'C' . $i, ($data['status'][$key][1] ? $data['status'][$key][1] : 0) );
				$objActSheet->setCellValue ( 'D' . $i, round ( $data['status'][$key][1] * 100 / $count, 2 ) . '%' );
				$objActSheet->setCellValue ( 'E' . $i, ($data['status'][$key][- 1] ? $data['status'][$key][- 1] : 0) );
				$objActSheet->setCellValue ( 'F' . $i, round ( $data['status'][$key][- 1] * 100 / $count, 2 ) . '%' );
				$objActSheet->setCellValue ( 'G' . $i, ($data['status'][$key][0] ? $data['status'][$key][0] : 0) );
				$objActSheet->setCellValue ( 'H' . $i, round ( $data['status'][$key][0] * 100 / $count, 2 ) . '%' );
				//ת����ͬ
				$objActSheet->setCellValue ( 'I' . $i, ($data['contract'][$key][1] ? $data['contract'][$key][1] : 0) );
				$objActSheet->setCellValue ( 'J' . $i, round ( $data['contract'][$key][1] * 100 / $count, 2 ) . '%' );
				$objActSheet->setCellValue ( 'K' . $i, ($data['contract'][$key][- 1] ? $data['contract'][$key][- 1] : 0) );
				$objActSheet->setCellValue ( 'L' . $i, round ( $data['contract'][$key][- 1] * 100 / $count, 2 ) . '%' );
				$objActSheet->setCellValue ( 'M' . $i, ($data['contract'][$key][0] ? $data['contract'][$key][0] : 0) );
				$objActSheet->setCellValue ( 'N' . $i, round ( $data['contract'][$key][0] * 100 / $count, 2 ) . '%' );
			}
			//========================================
			if ($i > 1) {
				$A2Style = $objActSheet->getStyle ( 'A3' );
				$objBorderA2 = $A2Style->getBorders ();
				$objBorderA2->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
				$objBorderA2->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objActSheet->getStyle ( 'A3' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
				$objActSheet->getStyle ( 'A3' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objActSheet->getStyle ( 'A3' )->getAlignment ()->setWrapText ( true );
				
				$objActSheet->duplicateStyle ( $A2Style, 'A3:N' . $i );
			}
		}
		
		//=======================================
		header ( "Pragma: public" );
		header ( "Expires: 0" );
		header ( "Cache-Control:must-revalidate, post-check=0, pre-check=0" );
		header ( "Content-Type:application/force-download" );
		header ( "Content-Type: application/vnd.ms-excel;" );
		header ( "Content-Type:application/octet-stream" );
		header ( "Content-Type:application/download" );
		header ( "Content-Disposition:attachment;filename=" . time () . '.xls' );
		header ( "Content-Transfer-Encoding:binary" );
		$objWriter->save ( 'php://output' );
	
	}

}

?>