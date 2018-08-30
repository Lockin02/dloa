<?php
/**
 * @author liub
 * @Date 2014年2月25日 14:22:58
 * @version 1.0
 * @description:合同成本概算信息 Model层
 */
 class model_contract_contract_cost  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_cost";
		$this->sql_map = "contract/contract/costSql.php";
		parent::__construct ();
	}


    /**
     * 服务成本确认打回
     */
    function ajaxBack_d($id,$costType,$costAppRemark){
		try {
			$row = $this->get_d($id);
			$userId = $_SESSION ['USER_ID'];
			$userName = $_SESSION ['USERNAME'];
			$costTime = date ( "Y-m-d H:i:s" );
            if($costType == 'add'){
                $sql = "update oa_contract_cost set ExaState = '2',state='2',costAppName='".$userName."',costAppId='".$userId."',costAppDate='".$costTime."',costAppRemark='".$costAppRemark."' where id = $id ";
            }else{
                $findSql = "select originalId from oa_contract_contract where id = '".$row['contractId']."'";
                $ff = $this->_db->getArray($findSql);
                $OldId = $ff[0]['originalId'];
                $sql = "update oa_contract_cost set ExaState = '2',state='2',costAppName='".$userName."',costAppId='".$userId."',costAppDate='".$costTime."',costAppRemark='".$costAppRemark."' where id = $id ";
                $sqlOld = "update oa_contract_cost set ExaState = '2',state='2',costAppName='".$userName."',costAppId='".$userId."',costAppDate='".$costTime."',costAppRemark='".$costAppRemark."' where contractId='".$OldId."'";
                $this->_db->query($sqlOld);
            }

			$this->_db->query($sql);

		  if($row['issale'] == '1'){
		  	 if($costType == 'add'){
		  	 	//更新回退 物料确认状态
			  	  $updateA = "update oa_contract_contract set dealStatus='0' where id='".$row['contractId']."'";
			  	  $this->_db->query($updateA);
			  	  $updateB = "update oa_contract_equ_link set ExaStatus='打回' where contractId='".$row['contractId']."'";
			  	  $this->_db->query($updateB);
		  	 }else{
		  	 	  $updateA = "update oa_contract_contract set dealStatus='2' where id='".$costType."'";
			  	  $this->_db->query($updateA);
			  	  $updateB = "update oa_contract_contract set dealStatus='2' where id='".$row['contractId']."'";
			  	  $this->_db->query($updateB);
		  	 }

		  }else{
              if($costType == 'add'){
                  $updateA = "update oa_contract_contract set engConfirm='0' where id='".$row['contractId']."'";
                  $this->_db->query($updateA);
              }else{
                  $updateA = "update oa_contract_contract set engConfirm='0' where id='".$costType."'";
                  $this->_db->query($updateA);
              }
          }
            $handleDao = new model_contract_contract_handle();
            if($costType == 'add'){
                $handleDao->handleAdd_d(array(
                    "cid"=> $row['contractId'],
                    "stepName"=> "执行部门审核打回",
                    "isChange"=> 0,
                    "stepInfo"=> $row['productLine'],
                ));
            }else{
                $handleDao->handleAdd_d(array(
                    "cid"=> $costType,
                    "stepName"=> "执行部门审核打回",
                    "isChange"=> 2,
                    "stepInfo"=> $row['productLine'],
                ));
            }
//			$Code = $this->find(array (
//				"id" => $id
//			), null, "Code");
//			//获取默认发送人
//			include (WEB_TOR . "model/common/mailConfig.php");
//			$emailDao = new model_common_mail();
//			$emailInfo = $emailDao->subBorrowEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "subProBorrowMail", $Code['Code'], "通过", $mailUser['subProBorrow']['subNameId']);
			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
	}

	/**
	 * 根据合同ID 和产品线Code 获取曾经确认过的金额,及领导审核状态
	 */
	function findMoneyByLine($cid,$line,$issale=null){
		if(!empty($issale) || $issale =='0'){
		    $saleCondition = " and issale=$issale";
		}else{
			$saleCondition = "";
		}
		$sql = "select confirmMoney,ExaState,costAppRemark from oa_contract_cost where contractId = '".$cid."' and productLine='".$line."' $saleCondition";
		$ff = $this->_db->getArray($sql);
		if(!empty($ff)){
			return $ff[0];
		}
//		else{
//			$cSql = "select costEstimatesTax as confirmMoney from oa_contract_contract where id='$cid'";
//			$cc = $this->_db->getArray($cSql);
//			return $cc[0];
//		}
        else{// 如果没有对应的概算记录,就查原合同的服务概算,也没有就返回0 PMS2508
            $result['confirmMoney'] = 0;
            $cSql = "select originalId from oa_contract_contract where id='$cid'";
            $cc = $this->_db->getArray($cSql);
            if(!empty($cc)){
                $sql = "select confirmMoney,ExaState,costAppRemark from oa_contract_cost where contractId = '".$cc[0]['originalId']."' and productLine='".$line."' $saleCondition";
                $ff = $this->_db->getArray($sql);
                if(!empty($ff)){
                    $result = $ff[0];
                }
            }
            return $result;
        }
	}

	/**
	 * 根据合同id和产品线判断是否存在
	 */
	function findisEel($cid,$line,$issale=null){
		if(!empty($issale) || $issale =='0'){
		    $saleCondition = " and issale=$issale";
		}else{
			$saleCondition = "";
		}
		$sql = "select id from oa_contract_cost where contractId = '".$cid."' and productLine='".$line."' $saleCondition";
		$ff = $this->_db->getArray($sql);
		if(!empty($ff)){
			return $ff[0]['id'];
		}else{
			return "0";
		}
	}

	/**
	 * 根据合同ID获取成本概算明细
	 * @result  返回结果为数组array('saleCost'=>销售类总成本，'saleCostTax'=>销售类总成本（含税），'serCost'=>服务类总成本，'allCost'=>所有总成本，'allCostTax'=>所有总成本（含税）)
	 */
	 function countCost($contractId){
         $saleCost = $saleCostTax = $serCost = $allCost = $allCostTax = 0;
	 	 $sql = "select * from oa_contract_cost where contractId = '".$contractId."'";
	 	 $arr = $this->_db->getArray($sql);
	 	 if(!empty($arr)){
	 	 	 foreach($arr as $k =>$v){
//	 	 	 	if($v['ExaState'] == '1'){
	 	 	 		if($v['issale'] == '1'){
	 	 	 	 	    $saleCost += $v['confirmMoney'];
	 	 	 	 	    $saleCostTax += $v['confirmMoneyTax'];
		 	 	 	 }else{
		 	 	 	 	$serCost += $v['confirmMoney'];
		 	 	 	 }
		 	 	 	 $allCost += $v['confirmMoney'];
		 	 	 	 $allCostTax += $v['confirmMoneyTax'];
//	 	 	 	}
	 	 	 }
	 	 	 $resultArr = array(
                 "saleCost"  => $saleCost,
	 	 	 	 "saleCostTax"  => $saleCostTax,
                 "serCost"  => $serCost,
                 "allCost"  => $allCost,
	 	 	 	 "allCostTax"  => $allCostTax
 	 	 	 );
 	 	 	 return $resultArr;
	 	 }
	 	     return "";
	 }


	 /**
	  * 根据合同ID 及合同内冗余的产品线  判断是否全部确认
	  */
	 function confirmCostFlag($productLineStr,$cid,$costId){
	 	$productLineArr = explode(",",$productLineStr);
	    $productLineStrFlep = array_flip($productLineArr);
	    $productLineArrTemp = array_flip($productLineStrFlep);
         //解决在不提交审批，但更新业务数据问题（当前产线审核 不算入判断中，提交审批后在更新状态值）
        if(!empty($costId)){
            $productLineNum = count($productLineArr)-1;
        }else{
            $productLineNum = count($productLineArr);
        }
        $lineArr = $this->get_d($costId);
        $lineStr = $lineArr['productLine'];
	 	$sql = "select count(id) as allNum,if(sum(if(ExaState='1',1,0)) is null,0,sum(if(ExaState='1',1,0))) as useNum  from
	 	    (select * from oa_contract_cost  GROUP BY contractId,productLine)c
	 	   where contractId = '".$cid."' and id != '".$costId."' and productLine !='".$lineStr."' ";
	 	$arr = $this->_db->getArray($sql);
//        if($arr[0]['allNum'] > $productLineNum){
//        	$productLineNum = $arr[0]['allNum'];
//        }
        if(count($productLineArrTemp)-1 == '0' && $productLineNum != '0'){//说明，合同 只有单一产线单一类型多个产品，直接取0
            $productLineNum = '0';
        }
        if($productLineNum > count($productLineArrTemp)-1){
            if($productLineNum > $arr[0]['allNum']){// 解决 一个合同两个产线，其中一个产线有多条产品导致无法提交问题
                $productLineNum = count($productLineArrTemp)-1;
            }
        }
        if($productLineNum=='0'){//如果 $productLineNum 为0 说明只有单一产线，解决统一产线不同产品问题
            return "0";
        }else if($arr[0]['allNum'] == $productLineNum && $arr[0]['useNum'] == $productLineNum){
	 		return "0";
	 	}else{
	 		return "1";
	 	}
	 }

	 /**
	  * 根据合同ID 重置产品线确认金额状态
	  *
	  */
	 function resetStateByCid($obj){
	     $conDao = new model_contract_contract_contract();
	     $cid = $obj['id'];
         $conArr = $conDao->get_d($cid);
		 $findSql = "select productLine from oa_contract_cost where contractId = '".$cid."'";
		 $proLineArr = $this->_db->getArray($findSql);
		 foreach($proLineArr as $k=>$v){
			 //根据productLine 发送通知邮件
			 $tomail = $this->costConUserIdBycid($v['productLine']);
			 $content = array(
				 "contractCode" => $conArr['contractCode'],
				 "contractName" => $obj['contractName'],
				 "customerName" => $obj['customerName']
			 );
			 $this->mailDeal_d("contractCost_Confirm", $tomail, $content);
		 }
	 	$sql = "update oa_contract_cost set state='0',ExaState='0' where contractId = '".$cid."'";
	 	$this->query($sql);
	 }

	 /**
	  * 根据产品线获取 产品线成本确认人
	  */
	 function  costConUserIdBycid($productLineStr)
	 {
		 $productLineArr = explode(",", $productLineStr);
		 if (is_array($productLineArr)) {
			 $allArr = array();
			 foreach ($productLineArr as $k => $v) {
				 $sql = "select userid from purview_info where typeid = '298' and FIND_IN_SET('" . $v . "',content)";
				 $arr = $this->_db->getArray($sql);
				 if (!empty($arr))
					 $allArr = array_merge($allArr, $arr);
			 }
		 }
		 if (!empty($allArr)) {
			 $tomailStr = "";
			 foreach ($allArr as $k => $v) {
				 $tomailStr .= $v['userid'] . ",";
			 }
		 }
		 return $tomailStr;
	 }
	 /**
	  * 根据合同ID 还原产品线确认金额状态
	  * 针对变更，先删掉原来的明细，在把变更记录明细更新成原合同id
	  */
	 function returnStateByCid($cid,$nid){
        if(!empty($cid)){
            $oldRows = $this->_db->getArray("select * from oa_contract_cost where contractId = '".$cid."'");
            $dsql = "delete from oa_contract_cost where contractId = '".$cid."'";
            $this->query($dsql);
            $sql = "update oa_contract_cost set contractId='".$cid."' where contractId = '".$nid."'";
            $this->query($sql);

            // 把原来合同的概算明细与变更合同的概算明细互换,避免删除原来合同的概算明细 2017-01-05 关联PMS2233
            foreach($oldRows as $k => $v){
                $v['contractId'] = $nid;
                $this->add_d($v);
            }
        }
	 }


	 /**
	  * 详细明细展示
	  */
	 function productlineCost($contractId){
     	$sql = "select * from oa_contract_cost where contractId = '".$contractId."'";
	 	 $rows = $this->_db->getArray($sql);
		if ($rows) {
			$i = 0; //列表记录序号
			$sNum = $i + 1;
			$str = ""; //返回的模板字符串
            $moneyAll = "";
			foreach ( $rows as $key => $val ) {
				$moneyAll += $val['confirmMoneyTax'];
				$str .= <<<EOT
			     <tr>
				   <td width='20%'>$val[productLineName]</td>
				   <td width='10%' class="formatMoney">$val[confirmMoneyTax]</td>
				   <td width='30%' style='text-align : left'>确认人：$val[confirmName]<br/>确认时间：$val[confirmDate]</td>
				   <td width='40%' style='text-align : left'>$val[costRemark]</td>
				</tr>
EOT;
				$i ++;
		  }
		  $str .= <<<EOT
                    <tr>
						<td><b>合计</b></td>
						<td class="formatMoney">$moneyAll</td>
						<td></td>
						<td></td>
					</tr>
EOT;
		return $str;
	  }

	}

      /**
     *  处理产品线成本确认备注
     */
    function handleCostRemark($remark,$cid){
        $remarkNow = nl2br($remark);
        $this->update(array("id"=>$cid),array("costRemark"=>$remarkNow));
    }

    /**
     * 根据合同id 产品线 查询生成的确认信息
     */
    function getCostByidline($cid,$line,$isslae=null){
    	if($isslae){
    		$condition = " and issale=1";
    	}else{
    		$condition = " " ;
    	}
    	$sql = "select * from oa_contract_cost where contractId = '".$cid."' and productLine = '".$line."' $condition order by issale";
    	$arr = $this->_db->getArray($sql);
    	return $arr[0];
    }


 }
?>