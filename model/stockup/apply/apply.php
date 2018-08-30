<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:21:40
 * @version 1.0
 * @description:��������� Model��
 */
 class model_stockup_apply_apply  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stockup_apply";
		$this->sql_map = "stockup/apply/applySql.php";
		parent::__construct ();
	}

	function add_d($object,$isTrueEx=false) {
		try {
			$this->start_d();
			$object['listNo'] =strtoupper($_SESSION['COM_BRN_PT']).substr(date('Y'),-2).date('md').substr(time(),-5).substr(microtime(),2,2).rand(0,9);
			$appId=parent::add_d ( $object,true );
			$subClass= new model_stockup_apply_applyProducts();
			if($object['list']&&is_array($object['list'])){
				foreach($object['list'] as $key => $val){
					if(is_array($val)&&$val['productId']){
						$val['appId']=$appId;
						$subClass->add_d($val);
					}
				}
			}
			$this->commit_d();
			if($isTrueEx){
				return $appId;
			}else{
			 return true;
			}

		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object){
		try{
			$this->start_d();
			parent::edit_d($object,true);
			if($object['list']&&is_array($object['list'])){
				$subClass= new model_stockup_apply_applyProducts();
				$subClass->delete(array('appId'=>$object['id']));
				foreach($object['list'] as $key => $val){
					if(is_array($val)&&$val['productId']){
						$val['appId']=$object['id'];
						$subClass->add_d($val);
					}
				}
			}
			//�޸�������Ϣ
			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(exception $e){

			return false;
		}
	}

	function deletes_d($object){
		try{
			$this->start_d();
			if($object['id']){
				$subClass= new model_outsourcing_points_details();
				$subClass->delete(" appId='".$object['id']."'");
				 $this->delete(array('id'=>$object['id']));
				 $this->commit_d();
				 return true;
			}else{
				return false;
			}
		}catch(exception $e){

			return false;
		}


	}

	/**
	 * ����״̬
	 */
	function updateObjStatus($id, $statusType,$statusValue) {
		if ($id && $statusType&&$statusValue) {
			$sql = "UPDATE  oa_stockup_apply SET $statusType='$statusValue' WHERE id='$id'; ";
			$flag = $this->query($sql);
		}
		return $flag;
	}

	/**
	 * ����DATA
	 *
	 */
	function showAppList() {
		$keyType=$_POST['keyType']?$_POST['keyType']:$_GET['keyType'];
		$keyWords=$_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords'];
		$keyWords=trim($keyWords);
		$keyTypeI=explode(',',$keyType);
		$sqlStr='';
		$sqlKeyStr='';
		if($keyTypeI&&is_array($keyTypeI)&&$keyWords){
			foreach($keyTypeI as $key => $val){
			 if($val=='listNo'||$val=='appUserName'){
			 	$sqlKeyStr.="or b.$val like '%$keyWords%' ";
			 }else if($val=='productCode'||$val=='productName'){
			 	$sqlKeyStr.="or a.$val like '%$keyWords%' ";
			 }
			}
			if($sqlKeyStr){
				$sqlKeyStr=trim($sqlKeyStr,'or');
			}
			$sqlStr.=" AND ($sqlKeyStr)";
		}
		$sql="SELECT a.id,b.id as pid,a.productId,a.productName,b.listNo,b.appDeptId,b.appDeptName,b.appUserId,b.appUserName,b.appDate,a.productNum,a.productConfig,a.exDeliveryDate
				FROM oa_stockup_apply_products a
				LEFT JOIN oa_stockup_apply b  ON a.appId=b.id
				WHERE b.ExaStatus='���' AND a.isClose=1 AND b.isClose=1  AND a.statusId=1  $sqlStr
				";
		$rs = $this->_db->getArray($sql);
		$str = "";
		$i = 0;
		//$rs = $this->sconfig->md5Rows ( $rs );
		if ($rs&&is_array($rs)) {
			$dataInfo=array();
			foreach ( $rs as $key => $val ) {
			   if($val){
			   		$dataInfo[$val['productId']][]=$val;
			   }
			}
				foreach ( $dataInfo as $key => $_dataInfo ) {
					if($_dataInfo&&is_array($_dataInfo)){
						$i ++;
						$n=0;
						$productNum=0;
						$rowspan=count($_dataInfo);

						foreach ( $_dataInfo as $_key => $val ) {
							$productNum+=$val[productNum];
							$k ++;
							$iClass = (($k % 2) == 0) ? "tr_even" : "tr_odd";
							$str .="<tr class='$iClass'>";
							$n ++;
							if($val){
								$str.="<td >$k</td>";
								if($n==1){
									$str.="<td rowspan='$rowspan'>$val[productName]</td>";
								}
								$links='?model=stockup_apply_apply&action=toView&id='.$val[pid];
						$str .= <<<EOT
							<td><a  title="�鿴��ϸ" href="javascript:showLink('$links','stockup_apply_apply',$val[pid])">$val[listNo]</a></td>
							<td>$val[appDeptName]</td>
							<td>$val[appUserName]</td>
							<td>$val[appDate]</td>
							<td>$val[productNum]</td>
							<td align="left">$val[productConfig]</td>
							<td>$val[exDeliveryDate]</td>
							<td class="isTrue"><input type="checkbox" class="txtshort"  onclick="selCheck('isTrue',$val[id])" id="isTrue$val[id]" value="$val[id]"  name="apply[isTrue][$val[id]]" style="width:20px;" /></td>
							<td class="isClose">
								<input type="checkbox" class="txtshort"  id="isClose$val[id]" onclick="selCheck('isClose',$val[id])" style="width:40px;" value="$val[id]" name="apply[isClose][$val[id]]"/>
							</td>
EOT;
							}
							$str .="</tr>";

						}
						$str .="<tr><td></td><td><span style='color:blue;font-size:14px'>����</span></td><td></td><td></td><td></td><td></td><td><span style='color:blue'>$productNum</span></td><td></td><td></td><td></td><td></td></tr>";
					}
				}
		}else {
			$str = "<tr><td colspan='8'>���޲�Ʒ����������Ϣ</td></tr>";
		}

		return $str;
	}
	/**
	 * �������ݸ��£�
	 *
	 */
	function inPostAppList($object) {
		if($object&&is_array($object)){
			$flag=0;
			if($object['isClose']&&is_array($object['isClose'])){
				$isCloseStr=implode(',',$object['isClose']);
				if($isCloseStr){
					$closeSQLstr="UPDATE oa_stockup_apply_products SET isClose='2' WHERE id in ($isCloseStr)";
					$flags=$this->query($closeSQLstr);
					if($flags==true){
						$flag=1;
					}
				}
			}
			if($object['isTrue']&&is_array($object['isTrue'])){
				$isTrueStr=implode(',',$object['isTrue']);
				if($isTrueStr){
					$sql="  SELECT a.id as apId,b.id AS applyId,b.id as pid,a.productId,a.productName,b.listNo,b.appDeptId,b.appDeptName,b.appUserId,b.appUserName,b.appDate,a.productNum,a.productConfig,a.exDeliveryDate
							FROM oa_stockup_apply_products a
							LEFT JOIN oa_stockup_apply b  ON a.appId=b.id
							WHERE b.ExaStatus='���' AND a.isClose=1 AND b.isClose=1  AND a.statusId=1 AND a.id in ($isTrueStr)
							";
					$rs = $this->_db->getArray($sql);
					$str = "";
					$i = 0;
					$k = 0;
					$flag=2;
					if ($rs&&is_array($rs)) {
						$dataInfo=array();
						foreach ( $rs as $key => $val ) {
						   if($val){
						   		$dataInfo[$val['productId']][]=$val;
						   }
						}

						foreach ( $dataInfo as $key => $_dataInfo ) {
							if($_dataInfo&&is_array($_dataInfo)){
								$i ++;
								$n=0;
								$productNum=0;
								$rowspan=count($_dataInfo);
								foreach ( $_dataInfo as $_key => $val ) {
									$productNum+=$val[productNum];
									$k ++;
									$iClass = (($k % 2) == 0) ? "tr_even" : "tr_odd";
									$str .="<tr class='$iClass'>";
									$n ++;
									if($val){
										foreach($val as $_keys=>$_val){
											$str.='<input type="hidden" name="application[list]['.$val['apId'].']['.$_keys.']" value="'.$_val.'"/>';
										}
										$str.="<td >$k</td>";
										if($n==1){
											$str.="<td rowspan='$rowspan'>$val[productName]</td>";
										}
										$links='?model=stockup_apply_apply&action=toView&id='.$val[pid];
								$str .= <<<EOT
									<td><a  title="�鿴��ϸ" href="javascript:showLink('$links','stockup_apply_apply',$val[pid])">$val[listNo]</a></td>
									<td>$val[appDeptName]</td>
									<td>$val[appUserName]</td>
									<td>$val[appDate]</td>
									<td>$val[productNum]</td>
									<td align="left">$val[productConfig]</td>
									<td>$val[exDeliveryDate]</td>
EOT;
										}
										$str .="</tr>";
									}
								}
								$str .="<tr><td></td><td><span style='color:blue;font-size:14px'>����</span></td><td></td><td></td><td></td><td></td><td><span style='color:blue'>$productNum</span></td><td></td><td></td></tr>";
								$applyMatter.='{"productName":"'.$dataInfo[$key][0]['productName'].'","productId":"'.$key.'","productCode":"'.$dataInfo[$key][0]['productCode'].'","stockupNum":"'.$productNum.'"},';
							}
							}else {
						$str = "<tr><td colspan='8'>���޲�Ʒ����������Ϣ</td></tr>";
					}

			}
		}
		$msgI['Flag']=$flag;
		$msgI['str']=$str;
		$msgI['matterJosn']=trim($applyMatter,',');
	 }
	return $msgI;
	}

  function getObjStatus($objId,$status=1){
  	if($objId){
  		$sqlStr="SELECT COUNT(DISTINCT a.id) AS num FROM  oa_stockup_apply_products a
				 WHERE a.isClose=1 AND a.statusId=$status AND a.appId='$objId'";
		$rs = $this->_db->getArray($sqlStr);
		if($rs[0]['num']=='0'){
			if($status=='2'){
				$flag=3;
			}else{
				$flag=5;
			}
		}else{
			$flag=4;
		}
  	}
  	return $flag;
  }
  	/**
	 * ����DATA
	 *
	 */
	function showAppCloseList() {
		$keyType=$_POST['keyType']?$_POST['keyType']:$_GET['keyType'];
		$keyWords=$_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords'];
		$keyWords=trim($keyWords);
		$keyTypeI=explode(',',$keyType);
		$sqlStr='';
		$sqlKeyStr='';
		if($keyTypeI&&is_array($keyTypeI)&&$keyWords){
			foreach($keyTypeI as $key => $val){
			 if($val=='listNo'||$val=='appUserName'){
			 	$sqlKeyStr.="or b.$val like '%$keyWords%' ";
			 }else if($val=='productCode'||$val=='productName'){
			 	$sqlKeyStr.="or a.$val like '%$keyWords%' ";
			 }
			}
			if($sqlKeyStr){
				$sqlKeyStr=trim($sqlKeyStr,'or');
			}
			$sqlStr.=" AND ($sqlKeyStr)";
		}
		$sql="SELECT a.id,b.id as pid,a.productId,a.productName,b.listNo,b.appDeptId,b.appDeptName,b.appUserId,b.appUserName,b.appDate,a.productNum,a.productConfig,a.exDeliveryDate
				FROM oa_stockup_apply_products a
				LEFT JOIN oa_stockup_apply b  ON a.appId=b.id
				WHERE b.ExaStatus='���' AND a.isClose=2 AND b.isClose=1  AND a.statusId=1  $sqlStr
				";
		$rs = $this->_db->getArray($sql);
		$str = "";
		$i = 0;
		//$rs = $this->sconfig->md5Rows ( $rs );
		if ($rs&&is_array($rs)) {
			$dataInfo=array();
			foreach ( $rs as $key => $val ) {
			   if($val){
			   		$dataInfo[$val['productId']][]=$val;
			   }
			}
				foreach ( $dataInfo as $key => $_dataInfo ) {
					if($_dataInfo&&is_array($_dataInfo)){
						$i ++;
						$n=0;
						$productNum=0;
						$rowspan=count($_dataInfo);

						foreach ( $_dataInfo as $_key => $val ) {
							$productNum+=$val[productNum];
							$k ++;
							$iClass = (($k % 2) == 0) ? "tr_even" : "tr_odd";
							$str .="<tr class='$iClass'>";
							$n ++;
							if($val){
								$str.="<td >$k</td>";
								if($n==1){
									$str.="<td rowspan='$rowspan'>$val[productName]</td>";
								}
								$links='?model=stockup_apply_apply&action=toView&id='.$val[pid];
						$str .= <<<EOT
							<td><a  title="�鿴��ϸ" href="javascript:showLink('$links','stockup_apply_apply',$val[pid])">$val[listNo]</a></td>
							<td>$val[appDeptName]</td>
							<td>$val[appUserName]</td>
							<td>$val[appDate]</td>
							<td>$val[productNum]</td>
							<td align="left">$val[productConfig]</td>
							<td>$val[exDeliveryDate]</td>
							<td class="isClose">
							 	<input type="hidden" name="apply[pId][$val[id]]" value="$val[pid]"/>
								<input type="checkbox" class="txtshort"  id="isClose$k" onclick="selAllback('isClose')" style="width:40px;" value="$val[id]" name="apply[isClose][$val[id]]"/>
							</td>
EOT;
							}
							$str .="</tr>";

						}
						$str .="<tr><td></td><td><span style='color:blue;font-size:14px'>����</span></td><td></td><td></td><td></td><td></td><td><span style='color:blue'>$productNum</span></td><td></td><td></td><td></td></tr>";
					}
				}
		}else {
			$str = "<tr><td colspan='8'>���޹رղ�Ʒ����������Ϣ</td></tr>";
		}

		return $str;
	}
	/**
	 *�����رձ���
	 */

	function inAppCloseList($object){
  	if($object&&is_array($object)){
			if($object['isClose']&&is_array($object['isClose'])){
				$isCloseStr=implode(',',$object['isClose']);
				if($isCloseStr){
					$closeSQLstr="UPDATE oa_stockup_apply_products SET isClose='1' WHERE id in ($isCloseStr)";
					$flags=$this->query($closeSQLstr);
					if($flags==true){
						$flag=1;
						foreach($object['isClose'] as $key =>$val){
							$statusId=$this->getObjStatus($object['pId'][$val],2);
							$this->updateObjStatus($object['pId'][$val],'status',$statusId);
						}

					}
				}
			}
  	}
  	return $flag;
  }

	function setMail($objId,$status){
		if($objId&&$status=='ok'){
			$sql="select c.id ,c.listNo,c.projectName,c.appUserName,c.description,d.productName ,d.productNum ,d.productConfig ,d.exDeliveryDate
		 		  from oa_stockup_apply_products d left join oa_stockup_apply c on c.id = d.appId where 1=1 and c.id='$objId'" ;
		 	$rs = $this->_db->getArray($sql);
		 	if($rs){
		 		$str = '';
		 		$title = 'OA-֪ͨ : ��������'; //����
				//������Ϣ����
				$mainInfo = $rs[0];
				$str .= '����id : '.$mainInfo['id'].' , ������: '.$mainInfo['listNo'].' , ��Ŀ���� : '.$mainInfo['projectName'].' , ������ : '.$mainInfo['appUserName'].' , ˵�� ��'.$mainInfo['description'];
				//�ӱ���Ϣ����
				$str .= '<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef style="font-size:14"><tr><td>���</td><td>�������ƣ���Ʒ��</td><td>����</td><td>��Ʒ����</td><td>������������</td><td>��ע</td></tr>';
				foreach($rs as $key => $val){
					$i = $key + 1;
					$str .=<<<EOT
						<tr><td>$i</td><td>$val[productName]</td><td>$val[productNum]</td><td>$val[productConfig]</td><td>$val[exDeliveryDate]</td><td>$val[remark]</td></tr>
EOT;
				}
				$str .= '</table>';


		 	}
		 	if($str){
		 		//��ȡĬ�Ϸ�����
				include (WEB_TOR . "model/common/mailConfig.php");
				$mailUser= $mailUser['oa_stockup_apply']['TO_ID'];
		 		$emailDao = new model_common_mail();
				$emailDao->mailClear($title,$mailUser,$str);
		 	}

		}
	}

}
?>