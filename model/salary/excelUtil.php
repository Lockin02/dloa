<?php

class model_salary_excelUtil extends model_base {
	
	function  __construct(){
		parent::__construct();
		$this->db = new mysql();
	}
	/**
	 * ͳһ������
	 * @param unknown $ckt
	 * @param unknown $flag
	 * @param string $outFlag
	 * @param string $info ��ȡǰ������
	 * @throws Exception
	 * @return Ambigous <string, multitype:Ambigous <multitype:, multitype:multitype:NULL  > >
	 */
	function xls_check($ckt, $flag,$outFlag='str',$info=null ) {
		set_time_limit(600);
		$ckData=array();
		$str = '';
		try {
			if(!empty($info['list'])){
				$str.='<tr class="tableheader"><td align="center">���</td>';
				foreach ( $info['list'] as $val ){
					$str .= '<td align="center">'.$val.'</td>';
				}
				$str .='<td align="center">��֤��Ϣ</td></tr>';
			}
			$excelfilename = 'attachment/xls_model/join/' . $ckt . ".xls";
			if (empty($_FILES["ctr_file"]["tmp_name"])&&$outFlag=='str') {
				$str .= '<tr><td colspan="25">�뵼����Ϣ��</td></tr>';
			} elseif (!move_uploaded_file($_FILES["ctr_file"]["tmp_name"],$excelfilename)&&$outFlag=='str') {
				$str .= '<tr><td colspan="25">�ϴ�ʧ�ܣ�</td></tr>';
			} else {
				//��ȡexcel��Ϣ
				$excelClass= new includes_classes_excelphp();
				$hi = 1 ;//��ͷ
				$excelClass->readDataFormat(WEB_TOR . $excelfilename,$hi);
				//print_r($excelClass->formatData);
				if ($flag == 'hr_join') {//��ְ
					if (!in_array('����', $excelClass->formatHead)
							|| !in_array('Ա����', $excelClass->formatHead)) {
						throw new Exception('�������ݱ�ͷ����ȷ����˶��Ƿ��ģ��һ�£�');
					}
					
					$sql = "select
                        h.usercard , s.username , h.userlevel , s.rand_key  as skey , p.id as pid
                    from salary s
						left join salary_pay p
                                                on ( p.pyear='" . $info['pyear'] . "' and p.pmon='" . $info['pmon'] . "' and p.userid=s.userid )
                        left join hrms h on ( s.userid=h.user_id )
                    where
                        s.userid=h.user_id
                        and ( s.usersta='0' or
                                ( year(s.probationdt)=" . $info['pyear']. " and  month(s.probationdt)=" . $info['pmon']. " )
                            )
                    order by s.id
                    ";
					$query = $this->db->query_exc($sql);
					while ($row = $this->db->fetch_array($query)) {
						$ckData[$row['usercard']]= array(
								'userlevel'=> $row['userlevel']
								,'name'=>$row['username']
								,'skey'=>$row['skey']
								,'pid'=>$row['pid']
								);
					}
					//print_r($ckData);
					if (count($excelClass->formatData)) {
						$i = 1;
						foreach ($excelClass->formatData as $key => $val) {
							$cl = 'green';
							$rk = '';
							$ckp = 1 ; //Ĭ��ͨ����֤
							if( empty( $ckData[$val['Ա����']] ) ){
								$cl = '#ff9900';
								$rk .= 'Ա���Ŵ��������ְ';
								$ckp= 0 ;
							}
							if( $val['����'] !=$ckData[$val['Ա����']]['name']  ){
								$cl = '#ff9900';
								$rk .= '���Ʋ�һ�£���ȷ���Ƿ�������֣����룺"' . $val['����'].'"��ϵͳ��"'.$ckData[$val['Ա����']]['name'].'" ';
							}
// 							if ($ckData[$val['Ա����']]['userlevel'] != 4) {
// 								$rk .= '��Ա���ǹ����λ��������Ϣ�����¹��ʽ��';
// 							}
							$excelClass->formatData[$key]['��֤'] = $ckp ;
							$excelClass->formatData[$key]['Ա������'] = $ckData[$val['Ա����']]['userlevel'] ;
							$excelClass->formatData[$key]['skey'] = $ckData[$val['Ա����']]['skey'] ;
							$excelClass->formatData[$key]['pid'] = $ckData[$val['Ա����']]['pid'] ;
							
							$str .= '<tr style="color:' . $cl. '">
                                    <td>' . $i. '</td>
                                    <td>' . $val['Ա����']. '</td>
                                    <td>' . $val['����']. '</td>
                                    <td>' . $val['����']. '</td>
                                    <td>' . $val['�������ʽ��']. '</td>
                                    <td>' . $val['��λ���ʽ��']. '</td>
                                    <td>' . $val['��Ч���ʽ��']. '</td>
                                    <td>' . $val['��Ŀ��Ч����']. '</td>
                                    <td>' . $val['��Ŀ������']. '</td>
                                    <td>' . $val['��Ŀס�޲���']. '</td>
                                    <td>' . $val['��Ŀͨ�Ų���']. '</td>
                                    <td>' . $val['��Ŀ���Բ���']. '</td>
                                    <td>' . $val['�������Ų���']. '</td>
                                    <td>' . $val['���֤']. '</td>
                                    <td>' . $val['�˺�']. '</td>
									<td>' . $val['������']. '</td>
                                    <td>' . $rk. '</td>
                                </tr>';
							
							$i++;
						}
					}
				} elseif ($flag == 'hr_pass') {
					if (!in_array('����', $excelClass->formatHead)
							|| !in_array('Ա����', $excelClass->formatHead)) {
						throw new Exception('�������ݱ�ͷ����ȷ����˶��Ƿ��ģ��һ�£�');
					}
					$sql = "select
                        h.usercard , s.username , h.userlevel , s.usersta ,  s.passoldam , s.amount , s.rand_key  as skey , p.id as pid
						, s.gwam, s.jxam
                    from salary s
							left join salary_pay p
                                                on ( p.pyear='" . $info['pyear'] . "' and p.pmon='" . $info['pmon'] . "' and p.userid=s.userid )
                        	left join hrms h on ( s.userid=h.user_id )
                    where
                        s.userid=h.user_id
                        and ( s.usersta='1' or
                                ( year(s.passdt)='" . $info['pyear']. "' and  month(s.passdt)='" . $info['pmon']. "' ) or
                                ( year(s.passuserdt)='" . $info['pyear']. "' and  month(s.passuserdt)='" . $info['pmon']. "' )
                            )
                    order by s.id
                    ";
					$query = $this->db->query_exc($sql);
					while ($row = $this->db->fetch_array($query)) {
						$ckData[$row['usercard']]= array(
								'userlevel'=> $row['userlevel']
								,'name'=>$row['username']
								,'skey'=>$row['skey']
								,'pid'=>$row['pid']
								,'usersta'=>$row['usersta']
								,'passoldam'=>$row['passoldam']
								,'amount'=>$row['amount']
								,'gwam'=>$row['gwam']
								,'jxam'=>$row['jxam']
								);
					}
					if (count($excelClass->formatData)) {
						$i = 1;
						foreach ($excelClass->formatData as $key => $val) {
							$cl = 'green';
							$rk = '��֤ͨ��';
							$ckp = 1 ; //Ĭ��ͨ����֤
							if( empty( $ckData[$val['Ա����']] ) ){
								$cl = '#ff9900';
								$rk .= 'Ա���Ŵ��������ְ';
								$ckp= 0 ;
							}
							//if ($ckData[$val['Ա����']]['userlevel'] != 4) {
							//	$rk .= '��Ա���ǹ����λ��������Ϣ�����¹��ʽ��';
							//}
							$excelClass->formatData[$key]['��֤'] = $ckp ;
							$excelClass->formatData[$key]['Ա������'] = $ckData[$val['Ա����']]['userlevel'] ;
							$excelClass->formatData[$key]['skey'] = $ckData[$val['Ա����']]['skey'] ;
							$excelClass->formatData[$key]['pid'] = $ckData[$val['Ա����']]['pid'] ;
							$excelClass->formatData[$key]['usersta'] = $ckData[$val['Ա����']]['usersta'] ;
							$excelClass->formatData[$key]['passoldam'] = $ckData[$val['Ա����']]['passoldam'] ;
							$excelClass->formatData[$key]['amount'] = $ckData[$val['Ա����']]['amount'] ;
							$excelClass->formatData[$key]['gwam'] = $ckData[$val['Ա����']]['gwam'] ;
							$excelClass->formatData[$key]['jxam'] = $ckData[$val['Ա����']]['jxam'] ;
							
							$str .= '<tr style="color:' . $cl. '">
                                    <td>' . $i. '</td>
                                    <td>' . $val['Ա����']. '</td>
                                    <td>' . $val['����']. '</td>
                                    <td>' . $val['ת������']. '</td>
                                    <td>' . $val['ת����������']. '</td>
                                    <td>' . $val['ת����λ����']. '</td>
                                    <td>' . $val['ת����Ч����']. '</td>
                                    <td>' . $val['��Ŀ��Ч����']. '</td>
                                    <td>' . $val['��Ŀ������']. '</td>
                                    <td>' . $val['��Ŀס�޲���']. '</td>
                                    <td>' . $val['��Ŀͨ�Ų���']. '</td>
                                    <td>' . $val['��Ŀ���Բ���']. '</td>
                                    <td>' . $val['�������Ų���']. '</td>
                                    <td>' . $rk. '</td>
                                </tr>';
							
							$i++;
						}
					}
				} elseif ($flag == 'hr_spe') {//����
					if (!in_array('����', $excelClass->formatHead)
							|| !in_array('Ա����', $excelClass->formatHead)
							|| !in_array('����', $excelClass->formatHead)
							|| !in_array('�Ƿ��˰', $excelClass->formatHead)
							|| !in_array('���', $excelClass->formatHead)
							|| !in_array('��ע', $excelClass->formatHead)) {
						throw new Exception('�������ݱ�ͷ����ȷ����˶��Ƿ��ģ��һ�£�');
					}
					$sql = "select
                        h.usercard , s.username , h.userlevel , s.rand_key  as skey  , s.userid
                    from salary s
                        left join hrms h on ( s.userid=h.user_id )
                    where
                        s.userid=h.user_id
                    order by s.id
                    ";
					$query = $this->db->query_exc($sql);
					while ($row = $this->db->fetch_array($query)) {
						$ckData[$row['usercard']]= array(
								'userlevel'=> $row['userlevel']
								,'name'=>$row['username']
								,'skey'=>$row['skey']
								,'userid'=>$row['userid']
								);
					}
					if (count($excelClass->formatData)) {
						$i = 1;
						foreach ($excelClass->formatData as $key => $val) {
							$cl = 'green';
							$rk = '��֤ͨ��';
							$ckp = 1 ; //Ĭ��ͨ����֤
							if( empty( $ckData[$val['Ա����']] ) ){
								$cl = '#ff9900';
								$rk .= 'Ա���Ŵ���';
								$ckp= 0 ;
							}
							$excelClass->formatData[$key]['��֤'] = $ckp ;
							$excelClass->formatData[$key]['Ա������'] = $ckData[$val['Ա����']]['userlevel'] ;
							$excelClass->formatData[$key]['skey'] = $ckData[$val['Ա����']]['skey'] ;
							$excelClass->formatData[$key]['userid'] = $ckData[$val['Ա����']]['userid'] ;
							
							$str .= '<tr style="color:' . $cl
									. '">
                                    <td>' . $i . '</td>
                                    <td>' . $val['Ա����'] . '</td>
                                    <td>' . $val['����'] . '</td>
                                    <td>' . $val['����'] . '</td>
                                    <td>' .$val['�Ƿ��˰'] . '</td>
                                    <td>' . sprintf("%.2f",$val['���']). '</td>
                                    <td>' .$val['��ע'] . '</td>
                                    <td>' . $rk . '</td>
                                </tr>';
							$i++;
						}
					}
				} elseif ($flag == 'hr_jb') {//����
					if (!in_array('����', $excelClass->formatHead)
							|| !in_array('Ա����', $excelClass->formatHead)) {
						throw new Exception('�������ݱ�ͷ����ȷ����˶��Ƿ��ģ��һ�£�');
					}
					$sql = "select
                        h.usercard , s.username , h.userlevel , s.rand_key  as skey  , s.userid , p.id as pid 
                    from salary s
							left join salary_pay p
                                                on ( p.pyear='" . $info['pyear'] . "' and p.pmon='" . $info['pmon'] . "' and p.userid=s.userid )
                           left join hrms h on ( s.userid=h.user_id )
                    where
                        s.userid=h.user_id
                    order by s.id
                    ";
					$query = $this->db->query_exc($sql);
					while ($row = $this->db->fetch_array($query)) {
						$ckData[$row['usercard']]= array(
								'userlevel'=> $row['userlevel']
								,'name'=>$row['username']
								,'skey'=>$row['skey']
								,'userid'=>$row['userid']
								,'pid'=>$row['pid']
						);
					}
					if (count($excelClass->formatData)) {
						$i = 1;
						foreach ($excelClass->formatData as $key => $val) {
							$cl = 'green';
							$rk = '��֤ͨ��';
							$ckp = 1 ; //Ĭ��ͨ����֤
							if( empty( $ckData[$val['Ա����']] ) ){
								$cl = '#ff9900';
								$rk .= 'Ա���Ŵ���';
								$ckp= 0 ;
							}
							$excelClass->formatData[$key]['��֤'] = $ckp ;
							$excelClass->formatData[$key]['Ա������'] = $ckData[$val['Ա����']]['userlevel'] ;
							$excelClass->formatData[$key]['skey'] = $ckData[$val['Ա����']]['skey'] ;
							$excelClass->formatData[$key]['userid'] = $ckData[$val['Ա����']]['userid'] ;
							$excelClass->formatData[$key]['pid'] = $ckData[$val['Ա����']]['pid'] ;
								
							$str .= '<tr style="color:' . $cl
							. '">
                                    <td>' . $i   . '</td>
                                    <td>' . $val['Ա����']   . '</td>
                                    <td>' . $val['����']  . '</td>
                                    <td>' . $val['��������']  . '</td>
                                    <td>' .$val['��λ����']  . '</td>
                                    <td>' .$val['��Ч����']   . '</td>
                                    <td>' . $rk  . '</td>
                                </tr>';
							$i++;
						}
					}
				}elseif ($flag == 'hr_gw'||$flag == 'hr_tx') {//��λ
					if (!in_array('����', $excelClass->formatHead)
							|| !in_array('Ա����', $excelClass->formatHead)) {
						throw new Exception('�������ݱ�ͷ����ȷ����˶��Ƿ��ģ��һ�£�');
					}
					$sql = "select
                        h.usercard , s.username , h.userlevel , s.rand_key  as skey  , s.userid , p.id as pid 
                    from salary s
							left join salary_pay p
                                                on ( p.pyear='" . $info['pyear'] . "' and p.pmon='" . $info['pmon'] . "' and p.userid=s.userid )
                           left join hrms h on ( s.userid=h.user_id )
                    where
                        s.userid=h.user_id
                    order by s.id
                    ";
					$query = $this->db->query_exc($sql);
					while ($row = $this->db->fetch_array($query)) {
						$ckData[$row['usercard']]= array(
								'userlevel'=> $row['userlevel']
								,'name'=>$row['username']
								,'skey'=>$row['skey']
								,'userid'=>$row['userid']
								,'pid'=>$row['pid']
						);
					}
					if (count($excelClass->formatData)) {
						$i = 1;
						foreach ($excelClass->formatData as $key => $val) {
							$cl = 'green';
							$rk = '��֤ͨ��';
							$ckp = 1 ; //Ĭ��ͨ����֤
							if( empty( $ckData[$val['Ա����']] ) ){
								$cl = '#ff9900';
								$rk .= 'Ա���Ŵ���';
								$ckp= 0 ;
							}
							if( empty( $ckData[$val['Ա����']]['pid'] ) ){
								$cl = '#ff9900';
								$rk .= 'Ա��������Ϣ������';
								$ckp= 0 ;
							}
							$excelClass->formatData[$key]['��֤'] = $ckp ;
							$excelClass->formatData[$key]['Ա������'] = $ckData[$val['Ա����']]['userlevel'] ;
							$excelClass->formatData[$key]['skey'] = $ckData[$val['Ա����']]['skey'] ;
							$excelClass->formatData[$key]['userid'] = $ckData[$val['Ա����']]['userid'] ;
							$excelClass->formatData[$key]['pid'] = $ckData[$val['Ա����']]['pid'] ;
							
							$str .= '<tr style="color:' . $cl . '">
                                    <td>' . $i   . '</td>';
							foreach ( $info['list'] as $ival ){
								$str .= '<td>'.$val[$ival].'</td>';
							}
                            $str .='<td>' . $rk  . '</td>
                                </tr>';
							$i++;
						}
					}
				}else if ($flag == 'hr_leave') {//��ְ����
					if (!in_array('Ա��', $excelClass->formatHead)
							|| !in_array('Ա����', $excelClass->formatHead)) {
						throw new Exception('�������ݱ�ͷ����ȷ����˶��Ƿ��ģ��һ�£�');
					}
					$userCardArr = array();
					if (count($excelClass->formatData)) {
						foreach ($excelClass->formatData as $key => $val) {
							array_push($userCardArr, "'".$val["Ա����"]."'");
						}
					}
					$userCardsStr=implode(",",$userCardArr);
					if(empty($userCardsStr) == true) {
						$userCardsStr = "'x761hfet'";
					}
					$sql = "select
		                s.rand_key , u1.user_name as username , d.dept_name as olddept , s.leavedt
		                , s.leavecreatedt , u.user_name , s.usersta
		                , h.expflag , s.freezedt , u1.user_name as freezeuser , s.freezecdt , s.freezeflag
		                , u1.company , u1.salarycom ,b.NameCN , h.usercard , s.rand_key 
		            from salary s
		                left join user u on (s.leavecreator=u.user_id)
		                left join user u1 on (u1.user_id=s.userid)
		                left join department d on (u1.dept_id=d.dept_id)
		                left join hrms h on (s.userid=h.user_id)
		                left join branch_info b on (b.NamePT = s.usercom )
		            where h.usercard in ($userCardsStr)";
					$query = $this->db->query_exc($sql);
					$ckData = array();
					while ($row = $this->db->fetch_array($query)) {
						$dt = $row ['leavedt'];
						$us = $row ['user_name'];
						$cdt = $row ['leavecreatedt'];
						$st = $this->userSta [$row ['usersta']];
						$ckData[$row['usercard']]['rand_key'] = $row['rand_key'];
						if ($row ['usersta'] == "3" && ((date ( 'Y', strtotime ( $dt ) ) == $info["pyear"] && date ( 'n', strtotime ( $dt ) ) == $info["pmon"]) || (date ( 'Y', strtotime ( $cdt ) ) == $info["pyear"] && date ( 'n', strtotime ( $cdt ) ) == $info["pmon"]))) {
							$ckData[$row['usercard']]['��֤'] = "yes";
						} else if ($row ['usersta'] == "3") {
							$ckData[$row['usercard']]['��֤'] = "no";
						} else {
							$ckData[$row['usercard']]['��֤'] = "yes";
						}
					}
					if (count($excelClass->formatData)) {
						$i = 1;
						foreach ($excelClass->formatData as $key => $val) {
							$cl = 'green';
							$rk = '��֤ͨ��';
							$ckp = 1 ; //Ĭ��ͨ����֤
							if( empty( $ckData[$val['Ա����']] ) ){
								$cl = '#ff9900';
								$rk = 'Ա���Ŵ���';
								$ckp= 0 ;
							}
							
							if($ckData[$val['Ա����']]['��֤'] == "yes") {
								$rk .= '�ɸ���';
							}else {
								$cl = '#ff9900';
								$rk = '���ɸ���';
								$ckp= 0 ;
							}
							$excelClass->formatData[$key]['��֤'] = $ckp ;
							$excelClass->formatData[$key]['rand_key'] = $ckData[$val['Ա����']]['rand_key'] ;
							
							$str .= '<tr style="color:' . $cl . '">
                                    <td>' . $i   . '</td>';
							foreach ( $info['list'] as $ival ){
								$str .= '<td>'.$val[$ival].'</td>';
							}
                            $str .='<td>' . $rk  . '</td>
                                </tr>';
							$i++;
						}
					}
				}

			}
		} catch (Exception $e) {
			$str = '<tr><td colspan="10">�������ݴ���' . $e->getMessage()
					. '</td></tr>';
		}
		return  ( $outFlag =='str'? $str : $excelClass->formatData ) ;
	}

}

?>