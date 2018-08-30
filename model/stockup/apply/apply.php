<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:21:40
 * @version 1.0
 * @description:备货申请表 Model层
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
	 * 重写编辑方法
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
			//修改主表信息
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
	 * 更新状态
	 */
	function updateObjStatus($id, $statusType,$statusValue) {
		if ($id && $statusType&&$statusValue) {
			$sql = "UPDATE  oa_stockup_apply SET $statusType='$statusValue' WHERE id='$id'; ";
			$flag = $this->query($sql);
		}
		return $flag;
	}

	/**
	 * 审批DATA
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
				WHERE b.ExaStatus='完成' AND a.isClose=1 AND b.isClose=1  AND a.statusId=1  $sqlStr
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
							<td><a  title="查看详细" href="javascript:showLink('$links','stockup_apply_apply',$val[pid])">$val[listNo]</a></td>
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
						$str .="<tr><td></td><td><span style='color:blue;font-size:14px'>汇总</span></td><td></td><td></td><td></td><td></td><td><span style='color:blue'>$productNum</span></td><td></td><td></td><td></td><td></td></tr>";
					}
				}
		}else {
			$str = "<tr><td colspan='8'>暂无产品备货申请信息</td></tr>";
		}

		return $str;
	}
	/**
	 * 审批数据更新；
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
							WHERE b.ExaStatus='完成' AND a.isClose=1 AND b.isClose=1  AND a.statusId=1 AND a.id in ($isTrueStr)
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
									<td><a  title="查看详细" href="javascript:showLink('$links','stockup_apply_apply',$val[pid])">$val[listNo]</a></td>
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
								$str .="<tr><td></td><td><span style='color:blue;font-size:14px'>汇总</span></td><td></td><td></td><td></td><td></td><td><span style='color:blue'>$productNum</span></td><td></td><td></td></tr>";
								$applyMatter.='{"productName":"'.$dataInfo[$key][0]['productName'].'","productId":"'.$key.'","productCode":"'.$dataInfo[$key][0]['productCode'].'","stockupNum":"'.$productNum.'"},';
							}
							}else {
						$str = "<tr><td colspan='8'>暂无产品备货申请信息</td></tr>";
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
	 * 审批DATA
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
				WHERE b.ExaStatus='完成' AND a.isClose=2 AND b.isClose=1  AND a.statusId=1  $sqlStr
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
							<td><a  title="查看详细" href="javascript:showLink('$links','stockup_apply_apply',$val[pid])">$val[listNo]</a></td>
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
						$str .="<tr><td></td><td><span style='color:blue;font-size:14px'>汇总</span></td><td></td><td></td><td></td><td></td><td><span style='color:blue'>$productNum</span></td><td></td><td></td><td></td></tr>";
					}
				}
		}else {
			$str = "<tr><td colspan='8'>暂无关闭产品备货申请信息</td></tr>";
		}

		return $str;
	}
	/**
	 *重启关闭备货
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
		 		$title = 'OA-通知 : 备货申请'; //标题
				//主表信息带入
				$mainInfo = $rs[0];
				$str .= '单据id : '.$mainInfo['id'].' , 申请编号: '.$mainInfo['listNo'].' , 项目名称 : '.$mainInfo['projectName'].' , 申请人 : '.$mainInfo['appUserName'].' , 说明 ：'.$mainInfo['description'];
				//从表信息带入
				$str .= '<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef style="font-size:14"><tr><td>序号</td><td>物料名称（产品）</td><td>数量</td><td>产品配置</td><td>期望交付日期</td><td>备注</td></tr>';
				foreach($rs as $key => $val){
					$i = $key + 1;
					$str .=<<<EOT
						<tr><td>$i</td><td>$val[productName]</td><td>$val[productNum]</td><td>$val[productConfig]</td><td>$val[exDeliveryDate]</td><td>$val[remark]</td></tr>
EOT;
				}
				$str .= '</table>';


		 	}
		 	if($str){
		 		//获取默认发送人
				include (WEB_TOR . "model/common/mailConfig.php");
				$mailUser= $mailUser['oa_stockup_apply']['TO_ID'];
		 		$emailDao = new model_common_mail();
				$emailDao->mailClear($title,$mailUser,$str);
		 	}

		}
	}

}
?>