<?php
/**
 * @author liub
 * @Date 2014��2��25�� 14:22:58
 * @version 1.0
 * @description:��ͬ�ɱ�������Ϣ Model��
 */
 class model_contract_contract_cost  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_cost";
		$this->sql_map = "contract/contract/costSql.php";
		parent::__construct ();
	}


    /**
     * ����ɱ�ȷ�ϴ��
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
		  	 	//���»��� ����ȷ��״̬
			  	  $updateA = "update oa_contract_contract set dealStatus='0' where id='".$row['contractId']."'";
			  	  $this->_db->query($updateA);
			  	  $updateB = "update oa_contract_equ_link set ExaStatus='���' where contractId='".$row['contractId']."'";
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
                    "stepName"=> "ִ�в�����˴��",
                    "isChange"=> 0,
                    "stepInfo"=> $row['productLine'],
                ));
            }else{
                $handleDao->handleAdd_d(array(
                    "cid"=> $costType,
                    "stepName"=> "ִ�в�����˴��",
                    "isChange"=> 2,
                    "stepInfo"=> $row['productLine'],
                ));
            }
//			$Code = $this->find(array (
//				"id" => $id
//			), null, "Code");
//			//��ȡĬ�Ϸ�����
//			include (WEB_TOR . "model/common/mailConfig.php");
//			$emailDao = new model_common_mail();
//			$emailInfo = $emailDao->subBorrowEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "subProBorrowMail", $Code['Code'], "ͨ��", $mailUser['subProBorrow']['subNameId']);
			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
	}

	/**
	 * ���ݺ�ͬID �Ͳ�Ʒ��Code ��ȡ����ȷ�Ϲ��Ľ��,���쵼���״̬
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
        else{// ���û�ж�Ӧ�ĸ����¼,�Ͳ�ԭ��ͬ�ķ������,Ҳû�оͷ���0 PMS2508
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
	 * ���ݺ�ͬid�Ͳ�Ʒ���ж��Ƿ����
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
	 * ���ݺ�ͬID��ȡ�ɱ�������ϸ
	 * @result  ���ؽ��Ϊ����array('saleCost'=>�������ܳɱ���'saleCostTax'=>�������ܳɱ�����˰����'serCost'=>�������ܳɱ���'allCost'=>�����ܳɱ���'allCostTax'=>�����ܳɱ�����˰��)
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
	  * ���ݺ�ͬID ����ͬ������Ĳ�Ʒ��  �ж��Ƿ�ȫ��ȷ��
	  */
	 function confirmCostFlag($productLineStr,$cid,$costId){
	 	$productLineArr = explode(",",$productLineStr);
	    $productLineStrFlep = array_flip($productLineArr);
	    $productLineArrTemp = array_flip($productLineStrFlep);
         //����ڲ��ύ������������ҵ���������⣨��ǰ������� �������ж��У��ύ�������ڸ���״ֵ̬��
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
        if(count($productLineArrTemp)-1 == '0' && $productLineNum != '0'){//˵������ͬ ֻ�е�һ���ߵ�һ���Ͷ����Ʒ��ֱ��ȡ0
            $productLineNum = '0';
        }
        if($productLineNum > count($productLineArrTemp)-1){
            if($productLineNum > $arr[0]['allNum']){// ��� һ����ͬ�������ߣ�����һ�������ж�����Ʒ�����޷��ύ����
                $productLineNum = count($productLineArrTemp)-1;
            }
        }
        if($productLineNum=='0'){//��� $productLineNum Ϊ0 ˵��ֻ�е�һ���ߣ����ͳһ���߲�ͬ��Ʒ����
            return "0";
        }else if($arr[0]['allNum'] == $productLineNum && $arr[0]['useNum'] == $productLineNum){
	 		return "0";
	 	}else{
	 		return "1";
	 	}
	 }

	 /**
	  * ���ݺ�ͬID ���ò�Ʒ��ȷ�Ͻ��״̬
	  *
	  */
	 function resetStateByCid($obj){
	     $conDao = new model_contract_contract_contract();
	     $cid = $obj['id'];
         $conArr = $conDao->get_d($cid);
		 $findSql = "select productLine from oa_contract_cost where contractId = '".$cid."'";
		 $proLineArr = $this->_db->getArray($findSql);
		 foreach($proLineArr as $k=>$v){
			 //����productLine ����֪ͨ�ʼ�
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
	  * ���ݲ�Ʒ�߻�ȡ ��Ʒ�߳ɱ�ȷ����
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
	  * ���ݺ�ͬID ��ԭ��Ʒ��ȷ�Ͻ��״̬
	  * ��Ա������ɾ��ԭ������ϸ���ڰѱ����¼��ϸ���³�ԭ��ͬid
	  */
	 function returnStateByCid($cid,$nid){
        if(!empty($cid)){
            $oldRows = $this->_db->getArray("select * from oa_contract_cost where contractId = '".$cid."'");
            $dsql = "delete from oa_contract_cost where contractId = '".$cid."'";
            $this->query($dsql);
            $sql = "update oa_contract_cost set contractId='".$cid."' where contractId = '".$nid."'";
            $this->query($sql);

            // ��ԭ����ͬ�ĸ�����ϸ������ͬ�ĸ�����ϸ����,����ɾ��ԭ����ͬ�ĸ�����ϸ 2017-01-05 ����PMS2233
            foreach($oldRows as $k => $v){
                $v['contractId'] = $nid;
                $this->add_d($v);
            }
        }
	 }


	 /**
	  * ��ϸ��ϸչʾ
	  */
	 function productlineCost($contractId){
     	$sql = "select * from oa_contract_cost where contractId = '".$contractId."'";
	 	 $rows = $this->_db->getArray($sql);
		if ($rows) {
			$i = 0; //�б��¼���
			$sNum = $i + 1;
			$str = ""; //���ص�ģ���ַ���
            $moneyAll = "";
			foreach ( $rows as $key => $val ) {
				$moneyAll += $val['confirmMoneyTax'];
				$str .= <<<EOT
			     <tr>
				   <td width='20%'>$val[productLineName]</td>
				   <td width='10%' class="formatMoney">$val[confirmMoneyTax]</td>
				   <td width='30%' style='text-align : left'>ȷ���ˣ�$val[confirmName]<br/>ȷ��ʱ�䣺$val[confirmDate]</td>
				   <td width='40%' style='text-align : left'>$val[costRemark]</td>
				</tr>
EOT;
				$i ++;
		  }
		  $str .= <<<EOT
                    <tr>
						<td><b>�ϼ�</b></td>
						<td class="formatMoney">$moneyAll</td>
						<td></td>
						<td></td>
					</tr>
EOT;
		return $str;
	  }

	}

      /**
     *  �����Ʒ�߳ɱ�ȷ�ϱ�ע
     */
    function handleCostRemark($remark,$cid){
        $remarkNow = nl2br($remark);
        $this->update(array("id"=>$cid),array("costRemark"=>$remarkNow));
    }

    /**
     * ���ݺ�ͬid ��Ʒ�� ��ѯ���ɵ�ȷ����Ϣ
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