<?php

class model_salary_excelUtil extends model_base {
	
	function  __construct(){
		parent::__construct();
		$this->db = new mysql();
	}
	/**
	 * 统一导入借口
	 * @param unknown $ckt
	 * @param unknown $flag
	 * @param string $outFlag
	 * @param string $info 获取前端数据
	 * @throws Exception
	 * @return Ambigous <string, multitype:Ambigous <multitype:, multitype:multitype:NULL  > >
	 */
	function xls_check($ckt, $flag,$outFlag='str',$info=null ) {
		set_time_limit(600);
		$ckData=array();
		$str = '';
		try {
			if(!empty($info['list'])){
				$str.='<tr class="tableheader"><td align="center">序号</td>';
				foreach ( $info['list'] as $val ){
					$str .= '<td align="center">'.$val.'</td>';
				}
				$str .='<td align="center">验证信息</td></tr>';
			}
			$excelfilename = 'attachment/xls_model/join/' . $ckt . ".xls";
			if (empty($_FILES["ctr_file"]["tmp_name"])&&$outFlag=='str') {
				$str .= '<tr><td colspan="25">请导入信息！</td></tr>';
			} elseif (!move_uploaded_file($_FILES["ctr_file"]["tmp_name"],$excelfilename)&&$outFlag=='str') {
				$str .= '<tr><td colspan="25">上传失败！</td></tr>';
			} else {
				//读取excel信息
				$excelClass= new includes_classes_excelphp();
				$hi = 1 ;//表头
				$excelClass->readDataFormat(WEB_TOR . $excelfilename,$hi);
				//print_r($excelClass->formatData);
				if ($flag == 'hr_join') {//入职
					if (!in_array('姓名', $excelClass->formatHead)
							|| !in_array('员工号', $excelClass->formatHead)) {
						throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
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
							$ckp = 1 ; //默认通过验证
							if( empty( $ckData[$val['员工号']] ) ){
								$cl = '#ff9900';
								$rk .= '员工号错误或已入职';
								$ckp= 0 ;
							}
							if( $val['姓名'] !=$ckData[$val['员工号']]['name']  ){
								$cl = '#ff9900';
								$rk .= '名称不一致，请确认是否更新名字：导入："' . $val['姓名'].'"，系统："'.$ckData[$val['员工号']]['name'].'" ';
							}
// 							if ($ckData[$val['员工号']]['userlevel'] != 4) {
// 								$rk .= '该员工是管理岗位，导入信息不更新工资金额';
// 							}
							$excelClass->formatData[$key]['验证'] = $ckp ;
							$excelClass->formatData[$key]['员工类型'] = $ckData[$val['员工号']]['userlevel'] ;
							$excelClass->formatData[$key]['skey'] = $ckData[$val['员工号']]['skey'] ;
							$excelClass->formatData[$key]['pid'] = $ckData[$val['员工号']]['pid'] ;
							
							$str .= '<tr style="color:' . $cl. '">
                                    <td>' . $i. '</td>
                                    <td>' . $val['员工号']. '</td>
                                    <td>' . $val['姓名']. '</td>
                                    <td>' . $val['日期']. '</td>
                                    <td>' . $val['基本工资金额']. '</td>
                                    <td>' . $val['岗位工资金额']. '</td>
                                    <td>' . $val['绩效工资金额']. '</td>
                                    <td>' . $val['项目绩效奖金']. '</td>
                                    <td>' . $val['项目管理补贴']. '</td>
                                    <td>' . $val['项目住宿补贴']. '</td>
                                    <td>' . $val['项目通信补贴']. '</td>
                                    <td>' . $val['项目电脑补贴']. '</td>
                                    <td>' . $val['补贴发放部分']. '</td>
                                    <td>' . $val['身份证']. '</td>
                                    <td>' . $val['账号']. '</td>
									<td>' . $val['开户行']. '</td>
                                    <td>' . $rk. '</td>
                                </tr>';
							
							$i++;
						}
					}
				} elseif ($flag == 'hr_pass') {
					if (!in_array('姓名', $excelClass->formatHead)
							|| !in_array('员工号', $excelClass->formatHead)) {
						throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
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
							$rk = '验证通过';
							$ckp = 1 ; //默认通过验证
							if( empty( $ckData[$val['员工号']] ) ){
								$cl = '#ff9900';
								$rk .= '员工号错误或已入职';
								$ckp= 0 ;
							}
							//if ($ckData[$val['员工号']]['userlevel'] != 4) {
							//	$rk .= '该员工是管理岗位，导入信息不更新工资金额';
							//}
							$excelClass->formatData[$key]['验证'] = $ckp ;
							$excelClass->formatData[$key]['员工类型'] = $ckData[$val['员工号']]['userlevel'] ;
							$excelClass->formatData[$key]['skey'] = $ckData[$val['员工号']]['skey'] ;
							$excelClass->formatData[$key]['pid'] = $ckData[$val['员工号']]['pid'] ;
							$excelClass->formatData[$key]['usersta'] = $ckData[$val['员工号']]['usersta'] ;
							$excelClass->formatData[$key]['passoldam'] = $ckData[$val['员工号']]['passoldam'] ;
							$excelClass->formatData[$key]['amount'] = $ckData[$val['员工号']]['amount'] ;
							$excelClass->formatData[$key]['gwam'] = $ckData[$val['员工号']]['gwam'] ;
							$excelClass->formatData[$key]['jxam'] = $ckData[$val['员工号']]['jxam'] ;
							
							$str .= '<tr style="color:' . $cl. '">
                                    <td>' . $i. '</td>
                                    <td>' . $val['员工号']. '</td>
                                    <td>' . $val['姓名']. '</td>
                                    <td>' . $val['转正日期']. '</td>
                                    <td>' . $val['转正基本工资']. '</td>
                                    <td>' . $val['转正岗位工资']. '</td>
                                    <td>' . $val['转正绩效工资']. '</td>
                                    <td>' . $val['项目绩效奖金']. '</td>
                                    <td>' . $val['项目管理补贴']. '</td>
                                    <td>' . $val['项目住宿补贴']. '</td>
                                    <td>' . $val['项目通信补贴']. '</td>
                                    <td>' . $val['项目电脑补贴']. '</td>
                                    <td>' . $val['补贴发放部分']. '</td>
                                    <td>' . $rk. '</td>
                                </tr>';
							
							$i++;
						}
					}
				} elseif ($flag == 'hr_spe') {//补发
					if (!in_array('姓名', $excelClass->formatHead)
							|| !in_array('员工号', $excelClass->formatHead)
							|| !in_array('类型', $excelClass->formatHead)
							|| !in_array('是否计税', $excelClass->formatHead)
							|| !in_array('金额', $excelClass->formatHead)
							|| !in_array('备注', $excelClass->formatHead)) {
						throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
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
							$rk = '验证通过';
							$ckp = 1 ; //默认通过验证
							if( empty( $ckData[$val['员工号']] ) ){
								$cl = '#ff9900';
								$rk .= '员工号错误';
								$ckp= 0 ;
							}
							$excelClass->formatData[$key]['验证'] = $ckp ;
							$excelClass->formatData[$key]['员工类型'] = $ckData[$val['员工号']]['userlevel'] ;
							$excelClass->formatData[$key]['skey'] = $ckData[$val['员工号']]['skey'] ;
							$excelClass->formatData[$key]['userid'] = $ckData[$val['员工号']]['userid'] ;
							
							$str .= '<tr style="color:' . $cl
									. '">
                                    <td>' . $i . '</td>
                                    <td>' . $val['员工号'] . '</td>
                                    <td>' . $val['姓名'] . '</td>
                                    <td>' . $val['类型'] . '</td>
                                    <td>' .$val['是否计税'] . '</td>
                                    <td>' . sprintf("%.2f",$val['金额']). '</td>
                                    <td>' .$val['备注'] . '</td>
                                    <td>' . $rk . '</td>
                                </tr>';
							$i++;
						}
					}
				} elseif ($flag == 'hr_jb') {//基本
					if (!in_array('姓名', $excelClass->formatHead)
							|| !in_array('员工号', $excelClass->formatHead)) {
						throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
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
							$rk = '验证通过';
							$ckp = 1 ; //默认通过验证
							if( empty( $ckData[$val['员工号']] ) ){
								$cl = '#ff9900';
								$rk .= '员工号错误';
								$ckp= 0 ;
							}
							$excelClass->formatData[$key]['验证'] = $ckp ;
							$excelClass->formatData[$key]['员工类型'] = $ckData[$val['员工号']]['userlevel'] ;
							$excelClass->formatData[$key]['skey'] = $ckData[$val['员工号']]['skey'] ;
							$excelClass->formatData[$key]['userid'] = $ckData[$val['员工号']]['userid'] ;
							$excelClass->formatData[$key]['pid'] = $ckData[$val['员工号']]['pid'] ;
								
							$str .= '<tr style="color:' . $cl
							. '">
                                    <td>' . $i   . '</td>
                                    <td>' . $val['员工号']   . '</td>
                                    <td>' . $val['姓名']  . '</td>
                                    <td>' . $val['基本工资']  . '</td>
                                    <td>' .$val['岗位工资']  . '</td>
                                    <td>' .$val['绩效工资']   . '</td>
                                    <td>' . $rk  . '</td>
                                </tr>';
							$i++;
						}
					}
				}elseif ($flag == 'hr_gw'||$flag == 'hr_tx') {//岗位
					if (!in_array('姓名', $excelClass->formatHead)
							|| !in_array('员工号', $excelClass->formatHead)) {
						throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
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
							$rk = '验证通过';
							$ckp = 1 ; //默认通过验证
							if( empty( $ckData[$val['员工号']] ) ){
								$cl = '#ff9900';
								$rk .= '员工号错误';
								$ckp= 0 ;
							}
							if( empty( $ckData[$val['员工号']]['pid'] ) ){
								$cl = '#ff9900';
								$rk .= '员工当月信息不存在';
								$ckp= 0 ;
							}
							$excelClass->formatData[$key]['验证'] = $ckp ;
							$excelClass->formatData[$key]['员工类型'] = $ckData[$val['员工号']]['userlevel'] ;
							$excelClass->formatData[$key]['skey'] = $ckData[$val['员工号']]['skey'] ;
							$excelClass->formatData[$key]['userid'] = $ckData[$val['员工号']]['userid'] ;
							$excelClass->formatData[$key]['pid'] = $ckData[$val['员工号']]['pid'] ;
							
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
				}else if ($flag == 'hr_leave') {//离职导入
					if (!in_array('员工', $excelClass->formatHead)
							|| !in_array('员工号', $excelClass->formatHead)) {
						throw new Exception('导入数据表单头不正确，请核对是否跟模板一致！');
					}
					$userCardArr = array();
					if (count($excelClass->formatData)) {
						foreach ($excelClass->formatData as $key => $val) {
							array_push($userCardArr, "'".$val["员工号"]."'");
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
							$ckData[$row['usercard']]['验证'] = "yes";
						} else if ($row ['usersta'] == "3") {
							$ckData[$row['usercard']]['验证'] = "no";
						} else {
							$ckData[$row['usercard']]['验证'] = "yes";
						}
					}
					if (count($excelClass->formatData)) {
						$i = 1;
						foreach ($excelClass->formatData as $key => $val) {
							$cl = 'green';
							$rk = '验证通过';
							$ckp = 1 ; //默认通过验证
							if( empty( $ckData[$val['员工号']] ) ){
								$cl = '#ff9900';
								$rk = '员工号错误';
								$ckp= 0 ;
							}
							
							if($ckData[$val['员工号']]['验证'] == "yes") {
								$rk .= '可更新';
							}else {
								$cl = '#ff9900';
								$rk = '不可更新';
								$ckp= 0 ;
							}
							$excelClass->formatData[$key]['验证'] = $ckp ;
							$excelClass->formatData[$key]['rand_key'] = $ckData[$val['员工号']]['rand_key'] ;
							
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
			$str = '<tr><td colspan="10">导入数据错误！' . $e->getMessage()
					. '</td></tr>';
		}
		return  ( $outFlag =='str'? $str : $excelClass->formatData ) ;
	}

}

?>