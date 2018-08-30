<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:22:42
 * @version 1.0
 * @description:产品备货申请 Model层 
 */
 class model_stockup_application_application  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stockup_application";
		$this->sql_map = "stockup/application/applicationSql.php";
		parent::__construct ();
	}
	function add_d($object,$isSetExaStatus = false) {
		try {
			$this->start_d();
			$object['listNo'] =strtoupper($_SESSION['COM_BRN_PT']).substr(date('Y'),-2).date('md').'C'.substr(time(),-5).substr(microtime(),2,2).rand(0,9);
			$appId=parent::add_d ( $object,true );
			$subClass= new model_stockup_application_applicationProducts();
			$applyClass= new model_stockup_apply_apply();
			$apClass= new model_stockup_apply_applyProducts();
			
			if($object['list']&&is_array($object['list'])){
				foreach($object['list'] as $key => $val){
					if(is_array($val)&&$val['productId']){
						$apClass->updateObjStatus($key,'statusId','2');
						$statusId=$applyClass->getObjStatus($val['applyId']);
						$applyClass->updateObjStatus($val['applyId'],'status',$statusId);
						
						$val['listId']=$appId;
						$subClass->add_d($val);
					}
				}
			}
			if($object['matter']&&is_array($object['matter'])){
				$matterClass= new model_stockup_application_applicationMatter();
				foreach($object['matter'] as $key => $val){
					if(is_array($val)&&$val['productId']){						
						$val['appId']=$appId;
						$matterClass->add_d($val);
					}
				}
			}
			$this->commit_d();
			if($isSetExaStatus){
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
			if($object['matter']&&is_array($object['matter'])){
				$matterClass= new model_stockup_application_applicationMatter();
				$matterClass->delete(array('appId'=>$object['id']));
				foreach($object['matter'] as $key => $val){
					if(is_array($val)&&$val['productId']){						
						$val['appId']=$object['id'];
						$matterClass->add_d($val,true);
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
	/**
	 * 更新状态
	 */
	function updateObjStatus($id, $statusType,$statusValue) {
		if ($id && $statusType&&$statusValue) {
			$sql = "UPDATE  oa_stockup_application SET $statusType='$statusValue' WHERE id='$id'; ";
			$flag = $this->query($sql);
		}
		return $flag;
	}
	
	function details($id){
		if($id){
			$sql="  SELECT *
							FROM oa_stockup_application_products a
							WHERE  a.listId in ($id)
							";
					$rs = $this->_db->getArray($sql);
					$str = "";
					$i = 0;
					$k = 0;
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
										$links='?model=stockup_apply_apply&action=toView&id='.$val[applyId];
								$str .= <<<EOT
									<td><a  title="查看详细" href="javascript:showLink('$links','stockup_apply_apply',$val[apId])">$val[listNo]</a></td>
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
					
							}
							}else {
						$str = "<tr><td colspan='8'>暂无产品备货申请详情</td></tr>";
					}
	return $str;
	}     
	}
	/**
	 * 
	 * 
	 */
	
	function deletes_d($object){
		try{
			$this->start_d();
			if($object['id']){
				$subClass= new model_stockup_application_applicationProducts();
				$applyClass= new model_stockup_apply_apply();
				$apClass= new model_stockup_apply_applyProducts();
				$obj=$subClass->getProductInfo ( $object ['id'] );
				if($obj&&is_array($obj)){
					foreach($obj as $key => $val){
						$apClass->updateObjStatus($val['apId'],'statusId','1');
						$statusId=$applyClass->getObjStatus($val['applyId'],2);
						$applyClass->updateObjStatus($val['applyId'],'status',$statusId);	
					}
				}
				$subClass->delete(" listId='".$object['id']."'");
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
	     
 }
?>