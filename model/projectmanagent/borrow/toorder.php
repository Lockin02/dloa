<?php
/**
 * @author Administrator
 * @Date 2011年11月30日 11:05:26
 * @version 1.0
 * @description:借试用转销售物料处理表 Model层
 */
 class model_projectmanagent_borrow_toorder  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_order_equ";
		$this->sql_map = "projectmanagent/toorderSql.php";
		parent::__construct ();
	}

	 /**
	  * 查找已转销售 的借试用物料的数量
	  */
     function getBorrowOrderequNum($borrowId,$productId){
         $sql = "select sum(number) as num from oa_borrow_order_equ where businessId = $borrowId and productId = $productId";
         $NumArr = $this->_db->getArray($sql);
         if(empty($NumArr)){
         	return 0;
         }else{
         	return $NumArr['0']['num'];
         }

     }

     /**
      * 根据id 查找是否有关联的物料信息 有则删除
      */
     function getRelOrderequ($orderId,$type,$isChange,$changeId){
     	if($isChange == 'change'){
             $sql = "select id from oa_borrow_order_equ where contractId = $orderId and contractType = '$type'";
	         $relEqu = $this->_db->getArray($sql);
	         if(!empty($relEqu)){
             $delSql = "delete from oa_borrow_order_equ where contractId = $orderId and contractType = '$type' and contractChangeId = $changeId";
             $this->_db->query($delSql);
             }
     	}else if($isChange == 'changeE'){
             $sql = "select id from oa_borrow_order_equ where contractId = $orderId and contractType = '$type' and contractChangeId = $changeId";
	         $relEqu = $this->_db->getArray($sql);
	         if(!empty($relEqu)){
                return 1;
	         }else{
	         	return 0;
	         }
     	}else{
     		 $sql = "select id from oa_borrow_order_equ where contractId = $orderId and contractType = '$type'";
	         $relEqu = $this->_db->getArray($sql);
	         if(!empty($relEqu)){
             $delSql = "delete from oa_borrow_order_equ where contractId = $orderId and contractType = '$type'";
             $this->_db->query($delSql);
             }
     	}
     }

     /**
      * 根据合同ID 类型 获取从表渲染
      */
     function getBorrowOrderequ($orderId,$orderType){
     	    if($orderType == 'borrow'){
     	    	$sql = "select * from oa_borrow_order_equ where businessId = $orderId ";
	            $relRowsArr = $this->_db->getArray($sql);
     	    }else{
               $sql = "select * from oa_borrow_order_equ where contractId = $orderId and contractType = '$orderType'";
	            $relRowsArr = $this->_db->getArray($sql);
     	    }
	        $relRow = $this->borrowOrderequ($relRowsArr);
	        return $relRow;
     }
     function borrowOrderequ($relRowsArr){
		$str = "";
		$equipDatadictDao = new model_system_datadict_datadict ();
		$i = 0;
		foreach($relRowsArr as $key => $val ){
			$i ++ ;
               if(empty($val['license'] )){
               		$license = "";
               }else{
               		$license = "<input type='button' class='txt_btn_a' value='配置' onclick='" .
               				"showThickboxWin(\"?model=yxlicense_license_tempKey&action=toViewRecord&id=".$val['license']."" .
               						"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900\")'/>";
               }
               if (! empty ( $val ['isSell'] )) {
					$checked = '是';
				} else {
					$checked = '否';
				}
				$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td>$val[productNo]</td>
						<td>$val[productName]</td>
						<td>$val[productModel]</td>
						<td>$val[number]</td>
						<td>$val[unitName]</td>
						<td class="formatMoney">$val[price]</td>
						<td class="formatMoney">$val[money]</td>
						<td>$val[warrantyPeriod]</td>
						<td>$license</td>
                        <td>$checked</td>
					</tr>
EOT;
		}

		return $str;
     }


   /**
    * 根据合同id  获取源单获取借出单,并自动发送邮件通知仓管 填写归还单
    */
   function findLoan($orderId,$orderType,$isChange){
   	   if($isChange == "add"){
   	   	  $sql = "select businessId from oa_borrow_order_equ where contractId = ".$orderId." and contractType = '$orderType' ";
          $borrowId = $this->_db->getArray($sql);
   	   }else if($isChange == "change"){
   	   	  $sql = "select businessId from oa_borrow_order_equ where contractChangeId = ".$orderId." and contractType = '$orderType' ";
          $borrowId = $this->_db->getArray($sql);
   	   }
       if(!empty($borrowId)){
       	        $allocationDao = new model_stock_allocation_allocation();
       	        $borrowDao = new model_projectmanagent_borrow_borrow();
       foreach($borrowId as $k => $v){
           $tempArr[$k] =  $v['businessId'];
       }
       	$tempArr1 = array_flip($tempArr);
       	$tempArr = array_flip($tempArr1);
       	foreach($tempArr as $k => $v){
             $loanArr[$k] = $allocationDao->findLendDoc("DBDYDLXFH",$v);
             $borrowCode[$k] = $borrowDao->find(array("id"=>$v),null,"Code");
       	}
       //$serOrderId 根据源单ID 查找物料信息
        if($isChange == "add"){
   	   	  $sql = "select productName,productNo,productModel,number,serialName,serialId,businessEquId from oa_borrow_order_equ where contractId = ".$orderId." and contractType = '$orderType' ";
          $serialArr = $this->_db->getArray($sql);
   	   }else if($isChange == "change"){
   	   	  $sql = "select productName,productNo,productModel,number,serialName,serialId,businessEquId from oa_borrow_order_equ where contractChangeId = ".$orderId." and contractType = '$orderType' ";
          $serialArr = $this->_db->getArray($sql);
   	   }

        foreach($loanArr as $k => $v){
        	foreach($v as $key => $val){
        		   	//借出调拨单 信息
        		   	$outLoanInfoArr[$key]['docCode'] = $val['docCode'];//借出调拨单编号
        		   	$outLoanInfoArr[$key]['relDocCode'] = $val['relDocCode'];//借试用单编号（源单）
        		   	$outLoanInfoArr[$key]['pickName'] = $val['pickName'];//借出人
//        		$allocationDao->addAllocationAuto($val['id'],$productArr);
        	}
        }
        //归还物料信息
       if(!empty($serialArr)){
				$i=0;
				$serial.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>物料编号</b></td><td><b>物料型号</b></td><td><b>归还数量</b></td><td><b>序列号</b></td></tr>";
				foreach($serialArr as $key => $val ){
					$i++;
					$productNmae = $val['productName'];
					$productNo = $val['productNo'];
					$productModel = $val['productModel'];
					$number = $val['number'];
					$serialName = $val['serialName'];
					$serial .=<<<EOT
					  <tr align="center" >
							<td>$i</td>
							<td>$productNmae</td>
							<td>$productNo</td>
							<td>$productModel</td>
							<td>$number</td>
							<td>$serialName</td>
						</tr>
EOT;
					}
					$serial.="</table>";
			}
       //借出调拨单信息
       if(!empty($outLoanInfoArr)){
				$j=0;
				$outLoanInfo.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>借出调拨单编号</b></td><td><b>借试用单编号</b></td><td><b>借出人</b></td></tr>";
				foreach($outLoanInfoArr as $key => $val ){
					$j++;
					$docCode = $val['docCode'];
					$relDocCode = $val['relDocCode'];
					$pickName = $val['pickName'];
					$outLoanInfo .=<<<EOT
					  <tr align="center" >
							<td>$j</td>
							<td>$docCode</td>
							<td>$relDocCode</td>
							<td>$pickName</td>
						</tr>
EOT;
					}
					$outLoanInfo.="</table>";
			}
		//借用源单编号
		if(!empty($borrowCode)){
				$j=0;
				$borrowCodeInfo.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>借试用单编号</b></td></tr>";
				foreach($borrowCode as $key => $val ){
					$j++;
					$Code = $val['Code'];
					$borrowCodeInfo .=<<<EOT
					  <tr align="center" >
							<td>$j</td>
							<td>$Code</td>
						</tr>
EOT;
					}
					$borrowCodeInfo.="</table>";
			}
        //获取默认发送人
	   include (WEB_TOR."model/common/mailConfig.php");
      //发送邮件
          $emailDao = new model_common_mail();
		  $emailInfo = $emailDao->borrowToOrderEmail(1,$_SESSION['USERNAME'],$_SESSION['EMAIL'],"borrowToOrder",$serial,$outLoanInfo,$borrowCodeInfo,$mailUser['borrowToOrder']['borrowToOrderNameId'],null);
       }
   }
 }
?>