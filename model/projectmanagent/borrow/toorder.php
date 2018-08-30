<?php
/**
 * @author Administrator
 * @Date 2011��11��30�� 11:05:26
 * @version 1.0
 * @description:������ת�������ϴ���� Model��
 */
 class model_projectmanagent_borrow_toorder  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_order_equ";
		$this->sql_map = "projectmanagent/toorderSql.php";
		parent::__construct ();
	}

	 /**
	  * ������ת���� �Ľ��������ϵ�����
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
      * ����id �����Ƿ��й�����������Ϣ ����ɾ��
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
      * ���ݺ�ͬID ���� ��ȡ�ӱ���Ⱦ
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
               		$license = "<input type='button' class='txt_btn_a' value='����' onclick='" .
               				"showThickboxWin(\"?model=yxlicense_license_tempKey&action=toViewRecord&id=".$val['license']."" .
               						"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900\")'/>";
               }
               if (! empty ( $val ['isSell'] )) {
					$checked = '��';
				} else {
					$checked = '��';
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
    * ���ݺ�ͬid  ��ȡԴ����ȡ�����,���Զ������ʼ�֪ͨ�ֹ� ��д�黹��
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
       //$serOrderId ����Դ��ID ����������Ϣ
        if($isChange == "add"){
   	   	  $sql = "select productName,productNo,productModel,number,serialName,serialId,businessEquId from oa_borrow_order_equ where contractId = ".$orderId." and contractType = '$orderType' ";
          $serialArr = $this->_db->getArray($sql);
   	   }else if($isChange == "change"){
   	   	  $sql = "select productName,productNo,productModel,number,serialName,serialId,businessEquId from oa_borrow_order_equ where contractChangeId = ".$orderId." and contractType = '$orderType' ";
          $serialArr = $this->_db->getArray($sql);
   	   }

        foreach($loanArr as $k => $v){
        	foreach($v as $key => $val){
        		   	//��������� ��Ϣ
        		   	$outLoanInfoArr[$key]['docCode'] = $val['docCode'];//������������
        		   	$outLoanInfoArr[$key]['relDocCode'] = $val['relDocCode'];//�����õ���ţ�Դ����
        		   	$outLoanInfoArr[$key]['pickName'] = $val['pickName'];//�����
//        		$allocationDao->addAllocationAuto($val['id'],$productArr);
        	}
        }
        //�黹������Ϣ
       if(!empty($serialArr)){
				$i=0;
				$serial.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>��������</b></td><td><b>���ϱ��</b></td><td><b>�����ͺ�</b></td><td><b>�黹����</b></td><td><b>���к�</b></td></tr>";
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
       //�����������Ϣ
       if(!empty($outLoanInfoArr)){
				$j=0;
				$outLoanInfo.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>������������</b></td><td><b>�����õ����</b></td><td><b>�����</b></td></tr>";
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
		//����Դ�����
		if(!empty($borrowCode)){
				$j=0;
				$borrowCodeInfo.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>�����õ����</b></td></tr>";
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
        //��ȡĬ�Ϸ�����
	   include (WEB_TOR."model/common/mailConfig.php");
      //�����ʼ�
          $emailDao = new model_common_mail();
		  $emailInfo = $emailDao->borrowToOrderEmail(1,$_SESSION['USERNAME'],$_SESSION['EMAIL'],"borrowToOrder",$serial,$outLoanInfo,$borrowCodeInfo,$mailUser['borrowToOrder']['borrowToOrderNameId'],null);
       }
   }
 }
?>