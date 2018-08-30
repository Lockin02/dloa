<?php
/**
 * @author Administrator
 * @Date 2011��9��13�� 14:34:44
 * @version 1.0
 * @description:�������� Model��
 */
 class model_projectmanagent_present_present  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_present_present";
		$this->sql_map = "projectmanagent/present/presentSql.php";
		parent::__construct ();
	}


	/**
	 * �鿴��ͬ��Ϣ
	 */
	function OrderView($orderId,$orderType,$orderCode) {

            $orderIdArr = array("id" => $orderId);
            switch($orderType){
               case "oa_sale_order" :
               		$this->sconfig = new model_common_securityUtil ( "order" );
                   $skey = $this->sconfig->md5Row ( $orderIdArr);
	               $orderView = '<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=projectmanagent_order_order&action=toViewTab&id='.$orderId.'&perm=view&skey='.$skey.'\')">';
	               break;
               case "oa_sale_service" :
                   $this->sconfig = new model_common_securityUtil ( "serviceContract" );
                   $skey = $this->sconfig->md5Row ( $orderIdArr);
                   $orderView = '<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=engineering_serviceContract_serviceContract&action=toViewTab&id='.$orderId.'&perm=view&skey='.$skey.'\')">';
	               break;
               case "oa_sale_lease" :
                   $this->sconfig = new model_common_securityUtil ( "rentalcontract" );
                   $skey = $this->sconfig->md5Row ( $orderIdArr);
                   $orderView = '<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=contract_rental_rentalcontract&action=toViewTab&id='.$orderId.'&perm=view&skey='.$skey.'\')">';
	               break;
               case "oa_sale_rdproject" :
                   $this->sconfig = new model_common_securityUtil ( "rdproject" );
                   $skey = $this->sconfig->md5Row ( $orderIdArr);
                  $orderView = '<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=rdproject_yxrdproject_rdproject&action=toViewTab&id='.$orderId.'&perm=view&skey='.$skey.'\')">';
	               break;
            }
		return $orderCode.$orderView;
	}

	 /**
	  * ��дget_d
	  */
	  function get_d($id,$selection = null){
	  	 //��ȡ������Ϣ
	  	 $rows = parent::get_d($id);
	  	 if(empty($selection)){
	  	 	$equDao = new model_projectmanagent_present_presentequ();
	  	 	$rows['presentequ'] = $equDao -> getDetail_d($id);
	  	 }else if(is_array($selection)){
	  	 	if(in_array('borrowequ',$selection)){
				$equDao = new model_projectmanagent_present_presentequ();
				$rows['presentequ'] = $equDao->getDetail_d($id);
			}
	  	 }
	  	 return $rows;
	  }

	/**
	 *���ͱ���Զ�����
	 */
     function presentCode(){
        $billCode = "ZS".date("Ymd");
//        $billCode = "JL201208";
		$sql="select max(RIGHT(c.Code,4)) as maxCode,left(c.Code,10) as _maxbillCode " .
				"from oa_present_present c group by _maxbillCode having _maxbillCode='".$billCode."'";

		$resArr=$this->findSql($sql);
		$res=$resArr[0];
		if(is_array($res)){
			$maxCode=$res['maxCode'];
			$maxBillCode=$res['maxbillCode'];
			$newNum=$maxCode+1;
			switch(strlen($newNum)){
				case 1:$codeNum="000".$newNum;break;
				case 2:$codeNum="00".$newNum;break;
				case 3:$codeNum="0".$newNum;break;
				case 4:$codeNum=$newNum;break;
			}
			$billCode.=$codeNum;
		}else{
			$billCode.="0001";
		}

		return $billCode;
	}

	/**
	 * ��дadd_d����

	 */
	function add_d($object){
		try{
			$this->start_d();
			//����ҵ�����
			$codeDao = new model_common_codeRule ();
			$salesNameId=$object['salesNameId'];
			$deptDao=new model_deptuser_dept_dept();
			$dept=$deptDao->getDeptByUserId($salesNameId);
			$object['objCode']=$codeDao->getObjCode($this->tbl_name."_objCode",$dept['Code']);

            $object['Code'] = $this->presentCode();
			//����������Ϣ
			$newId = parent::add_d($object,true);
			//����ӱ���Ϣ
			//��Ʒ
			if (!empty ($object['product'])) {
				$orderequDao = new model_projectmanagent_present_product();
				$orderequDao->createBatch($object['product'], array (
					'presentId' => $newId
				), 'conProductName');
			}
			//�豸
			 if(!empty($object['presentequ'])){
			 	$presentequDao = new model_projectmanagent_present_presentequ();
			    $presentequDao->createBatch($object['presentequ'],array('presentId' => $newId ,'presentCode'=>$object['Code']),'productName');

			    $licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $newId, 'objType' => $this->tbl_name , 'extType' => $presentequDao->tbl_name ),
					'presentId',
					'license'
				);
			 }
			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

  /**
	 * ��д�༭����
	 */
	function edit_d($object){

		try{
			$this->start_d();
			//�޸�������Ϣ
			parent::edit_d($object,true);

			$presentId = $object['id'];
			//����ӱ���Ϣ
            //��Ʒ
			$productDao = new model_projectmanagent_present_product();
			$productDao->delete(array (
				'presentId' => $presentId
			));
			foreach ($object['product'] as $k => $v) {
				if ($v['isDelTag'] == '1') {
					unset ($object['product'][$k]);
				}
			}
			$productDao->createBatch($object['product'], array (
				'presentId' => $presentId
			), 'conProductName');
			//�豸
			$equDao = new model_projectmanagent_present_presentequ();
            $equDao->delete(array('presentId' => $presentId));
			$equDao->createBatch($object['presentequ'],array('presentId' => $presentId ),'productName');

			if($object['presentequ']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $object, 'objType' => $this->tbl_name , 'extType' => $equDao->tbl_name ),
					'presentId',
					'license'
				);
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
            $this->rollBack();
			return false;
		}
	}

	/**
	 * ��Ⱦ���� - �鿴
	 */
	function initView($object){

        if(!empty($object['presentequ'])){

        	$equDao = new model_projectmanagent_present_presentequ();
        	$object['presentequ'] = $equDao -> initTableView($object['presentequ'],$object['id']);
        }else{
        	$object['presentequ'] = '<tr><td colspan="10">���������Ϣ</td></tr>';
        }
		return $object;
	}


	/**
	 * ��Ⱦ���� - �༭
	 */
	function initEdit($object){

		//�豸
		$equDao = new model_projectmanagent_present_presentequ();
		$rows = $equDao->initTableEdit($object['presentequ']);
		$object['productNumber'] = $rows[0];
		$object['presentequ'] = $rows[1];
		return $object;
	}

  /**
   * ���ݵ���id ��ȡ��ʢ���ͻ���Ϣ
   */
   function getCusinfoBypresent($id){
   	    $cusArr =   $this->get_d($id);
   	    $arr = array();
   	    $arr['customerName'] = $cusArr['customerName'];
   	    $arr['customerId'] = $cusArr['customerId'];
   	    return $arr;
   }

	/**
	 * ����ID ��ȡȫ����Ϣ
	 * $$presentId : ����ID
	 * $getInfoArr ��Ҫ�Ĵӱ���Ϣ ��:$getInfoArr = array('equ','product') Ĭ��Ϊ�� ȡȫ��
	 *      prodcut-��Ʒ  equ-����
	 */
	function getPresentInfo($presentId, $getInfoArr = null) {
		if (empty ($getInfoArr)) {
			$getInfoArr = array (
				'product',
				'equ',
			);
		}
		$daoArr = array (
			"product" => "model_projectmanagent_present_product",
			"equ" => "model_projectmanagent_present_presentequ",
		);
		$presentInfo = parent::get_d($presentId);
		foreach ($getInfoArr as $key => $val) {
			$daoName = $daoArr[$val];
			$dao = new $daoName ();
			$presentInfo[$val] = $dao->getDetail_d($presentId);
		}
		return $presentInfo;
	}

	/**
	 * ����ID ��ȡȫ����Ϣ
	 * $$presentId : ����ID
	 * $getInfoArr ��Ҫ�Ĵӱ���Ϣ ��:$getInfoArr = array('equ','product') Ĭ��Ϊ�� ȡȫ��
	 *      prodcut-��Ʒ  equ-����
	 */
	function getPresentInfoWithTemp($presentId, $getInfoArr = null) {
		if (empty ($getInfoArr)) {
			$getInfoArr = array (
				'product',
				'equ',
			);
		}
		$daoArr = array (
			"product" => "model_projectmanagent_present_product",
			"equ" => "model_projectmanagent_present_presentequ",
		);
		$presentInfo = parent::get_d($presentId);
		foreach ($getInfoArr as $key => $val) {
			$daoName = $daoArr[$val];
			$dao = new $daoName ();
			if( $val=='product' ){
				$presentInfo[$val] = $dao->getDetailWithTemp_d($presentId);
			}else{
				$presentInfo[$val] = $dao->getDetail_d($presentId);
			}
		}
		return $presentInfo;
	}

	/**
	 * ����ID ��ȡȫ����Ϣ(����ɾ���ļ�¼)
	 * $$presentId : ����ID
	 * $getInfoArr ��Ҫ�Ĵӱ���Ϣ ��:$getInfoArr = array('equ','product') Ĭ��Ϊ�� ȡȫ��
	 *      prodcut-��Ʒ  equ-����
	 */
	function getPresentInfoAll($presentId, $getInfoArr = null) {
		if (empty ($getInfoArr)) {
			$getInfoArr = array (
				'product',
				'presentequ',
			);
		}
		$daoArr = array (
			"product" => "model_projectmanagent_present_product",
			"presentequ" => "model_projectmanagent_present_presentequ",
		);
		$presentInfo = parent::get_d($presentId);
		foreach ($getInfoArr as $key => $val) {
			$daoName = $daoArr[$val];
			$dao = new $daoName ();
			$dao->searchArr ['presentId'] = $presentId;
			$presentInfo[$val] = $dao->list_d();
		}
		return $presentInfo;
	}

/***********************************����*****************************************************/

     /**
      * ����������豸��Ϣ
      */
     function showDetaiInfo($rows) {
     	$orderequDao = new model_projectmanagent_present_presentequ();
     	$rows['orderequ'] =
     	$orderequDao->showDetailByOrder( $orderequDao->showEquListInByOrder($rows['id'],'oa_present_present'));

     	return $rows;
     }
/*******************************���   ��ʼ***************************************************/
    /**
	 * ��Ⱦ���� -���
	 */
	function initChange($object){
		//�豸
		$tentalcontractequDao = new model_projectmanagent_present_presentequ();
		$rows = $tentalcontractequDao->changeTable($object['presentequ']);
		$object['productNumber'] = $rows[0];
		$object['presentequ'] = $rows[1];
		return $object;
	}

   	/**
   	 * �������
	*/
	function change_d($obj) {
		try{
			$this->start_d ();

			//�����¼,�õ��������ʱ������id
			$changeLogDao = new model_common_changeLog ( 'present' );
			$forArr = array (
				"product",
				"presentequ"
			);
		    if(!empty($obj['presentequ'])){
		  	    foreach($obj['presentequ'] as $key=>$val){
		  	  	   if( empty($val['productId'])|| empty($val['productName'])){
		  	  	 	  unset($obj['presentequ'][$key]);
		  	  	   }
		  	    }
		    }
		    if(isset($obj['tempId']) && $obj['presentId'] != $obj['oldId']){//���ڼ�������ʱ�����¼����
		    	//�ϲ���ʱ�����¼ɾ����������
		    	$tempObj = $this->getPresentInfoAll($obj['tempId']);
		    	foreach ($forArr as $key => $val) {
		    		foreach ($tempObj[$val] as $v) {
		    			if($v['isDel'] == '1'){
		    				if(!isset($obj[$val])){
		    					$obj[$val] = array();
		    				}
		    				array_push($obj[$val], $v);
		    			}
		    		}
		    	}
		    	foreach ($forArr as $key => $val) {
		    		foreach ($obj[$val] as $k => $v) {
		    			$obj[$val][$k]['oldId'] = empty($obj[$val][$k]['originalId']) ? '0' : $obj[$val][$k]['originalId'];//�ӱ��originalId��ӦԴ����id
		    		}
		    	}
		    }else{
		    	foreach ($forArr as $key => $val) {
		    		foreach ($obj[$val] as $k => $v) {
		    			$obj[$val][$k]['oldId'] = $obj[$val][$k]['id'];
		    		}
		    	}
		    }
			$tempObjId = $changeLogDao->addLog ( $obj );
			//ɾ�����μ��ص���ʱ�����¼(����)
			if(!empty($obj['tempId'])){
				$sql = "select id,ExaStatus from oa_present_changlog where objType = 'present' and tempId=".$obj['tempId'];
				$rs = $this->_db->getArray($sql);
				if(!empty($rs)){
					//ȡ�����ر����¼�����������صı����¼��ɾ��
					if($rs[0]['ExaStatus'] != '���' || ($rs[0]['ExaStatus'] == '���' && $obj['oldId'] != $obj['presentId'])){
						$delSql = "delete from oa_present_changedetail where parentId=".$rs[0]['id'];
						$this->_db->query($delSql);
						$delSql = "delete from oa_present_changlog where objType = 'present' and tempId=".$obj['tempId'];
						$this->_db->query($delSql);
					}
				}
			}
			if(!empty($tempObjId)){
                $this->updateIstempEquConProduct($tempObjId);
				if($obj['isSub'] == '0'){//����ʱ������ʱ�����¼������״̬��Ϊ����
					$updateSql = "update oa_present_changlog set ExaStatus = '����' where objType = 'present' and tempId=".$tempObjId;
				}else{//�ύʱ���������¼������״̬��Ϊ������
					$updateSql = "update oa_present_changlog set ExaStatus = '������' where objType = 'present' and tempId=".$tempObjId;
				}
				$this->_db->query($updateSql);
			}

			$this->commit_d ();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return null;
		}

	}
	/******************************/
	/**
	 * �ж��Ƿ�Ϊ����ĺ�ͬ
	 */
	function isTemp($conId) {
		$cond = array (
			"id" => $conId
		);
		$isTemp = $this->find($cond, '', 'isTemp');
		$isTemp = implode(',', $isTemp);
		return $isTemp;
	}

     /**
      * ������ʱ��¼���ϵĲ�ƷID
      *
      * @param $presentId //��ʱ��¼��ID
      */
	function updateIstempEquConProduct($presentId){
	    $equDao = new model_projectmanagent_present_presentequ();
        $equSql = "select id,originalId,parentEquId,conProductId from oa_present_equ where presentId = {$presentId} and (parentEquId <> 0 or conProductId <> 0);";
        $productSql = "select id,originalId from oa_present_product where presentId = {$presentId};";
        $equArr = $this->_db->getArray($equSql);
        $productArr = $this->_db->getArray($productSql);

        // �������Ϲ����Ĳ�ƷID
        echo"<pre>";
        $equOriginalArr = array();
        foreach ($equArr as $equK => $equV){
            $equOriginalArr[$equV['originalId']] = $equV['id'];
            foreach ($productArr as $productK => $productV){
                if($equV['conProductId'] == $productV['originalId']){
                    $equArr[$equK]['conProductId'] = $productV['id'];
                    break;
                }
            }
            $arr = array("id"=>$equV['id'],"conProductId"=>$equArr[$equK]['conProductId']);
            $equDao->updateById($arr);
        }

        // ���������������������ID
        foreach ($equArr as $equK => $equV){
            if($equV['parentEquId'] != 0){
                $arr = array("id"=>$equV['id'],"parentEquId" => $equOriginalArr[$equV['parentEquId']]);
                $equDao->updateById($arr);
            }
        }
    }
   /*******************************���   end***************************************************/


/********************************************************�������******************************************************************************/


	/**
	 * ���ݺ�ͬid�޸ĺ�ͬ�������ƻ�״̬
	 */
	 function updateOutStatus_d( $id ){
	 	$orderRemainSql = "select count(0) as countNum,(select sum(o.executedNum) from oa_present_equ o where o.presentId=".$id." and o.isTemp=0 and o.isDel=0) as executeNum
						 from (select e.presentId,(e.number-e.executedNum) as remainNum from oa_present_equ e
						where e.presentId=".$id." and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
	 	$remainNum = $this->_db->getArray( $orderRemainSql );
	 	if( $remainNum[0]['countNum'] <= 0 ){//�ѷ���
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => 'YFH'
		 	);
	 	}elseif( $remainNum[0]['countNum']>0 && $remainNum[0]['executeNum']==0 ){//δ����
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => 'WFH'
		 	);

		} else {//���ַ���
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => 'BFFH'
		 	);
	 	}
	 	$this->updateById( $statusInfo );
	 	return 0;
	 }
    /**
     * �ı䷢��״̬ --- �ر�
     */
    function updateDeliveryStatus ($id) {
    	$condiction = array ("id" => $id);
        if( $this->updateField($condiction,"DeliveryStatus","TZFH") ){
        	echo 1;
        }else
        	echo 0;
    }


	/**
	 * ���ݷ�������޸ĺ�ͬ�������ƻ�״̬
	 */
	 function updateShipStatus_d( $id ){
	 	$orderRemainSql = "select count(0) as countNum,(select sum(o.issuedShipNum) from oa_present_equ o where o.presentId=".$id." and o.isTemp=0 and o.isDel=0) as issuedShipNum
						 from (select e.presentId,(e.number-e.issuedShipNum) as remainNum from oa_present_equ e
						where e.presentId=".$id." and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
	 	$remainNum = $this->_db->getArray( $orderRemainSql );
	 	if( $remainNum[0]['countNum'] <= 0 ){//�ѷ���
		 	$statusInfo = array(
		 		'id' => $id,
		 		'makeStatus' => 'YXD'
		 	);
	 	}elseif( $remainNum[0]['countNum']>0 && $remainNum[0]['issuedShipNum']==0 ){//δ����
		 	$statusInfo = array(
		 		'id' => $id,
		 		'makeStatus' => 'WXD'
		 	);
		} else {//���ַ���
		 	$statusInfo = array(
		 		'id' => $id,
		 		'makeStatus' => 'BFXD'
		 	);
	 	}
	 	$this->updateById( $statusInfo );
	 	return 0;
	 }

     /**
      * ���ݷ�������޸ĺ�ͬ�������ƻ�״̬
      * (����PMS2381, �ָ���ť���ʵ����������δ����ĺ���,Ϊ�˲�Ӱ�������ط������Ǹ�����,��ʱ����,�������¶�����һ��)
      */
	 function updateOrderShipStatus_d( $id ){
         $orderRemainSql = "select count(0) as countNum,(select sum(o.issuedShipNum) from oa_present_equ o where o.presentId=".$id." and o.isTemp=0 and o.isDel=0) as issuedShipNum
						 from (select e.presentId,(e.number-e.issuedShipNum) as remainNum from oa_present_equ e
						where e.presentId=".$id." and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
         $remainNum = $this->_db->getArray( $orderRemainSql );
         if( $remainNum[0]['countNum'] <= 0 ){//�ѷ���
             $statusInfo = array(
                 'id' => $id,
                 'DeliveryStatus' => 'YFH',
                 'makeStatus' => 'YXD'
             );
         }elseif( $remainNum[0]['countNum']>0 && $remainNum[0]['issuedShipNum']==0 ){//δ����
             $statusInfo = array(
                 'id' => $id,
                 'DeliveryStatus' => 'WFH',
                 'makeStatus' => 'WXD'
             );
         } else {//���ַ���
             $statusInfo = array(
                 'id' => $id,
                 'DeliveryStatus' => 'BFFH',
                 'makeStatus' => 'BFXD'
             );
         }
         $this->updateById( $statusInfo );
         return 0;
     }
/**************************************************��ͬ�豸ͳ�Ʋ��� start****************************************************/
	/**
	 * �ɹ��豸-�ƻ�����
	 */
	function pageEqu_d(){
		$stockDao = new model_stock_stockinfo_systeminfo();
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$datadictDao = new model_system_datadict_datadict();
		$searchArr = $this->__GET("searchArr");
		$this->__SET('searchArr', $searchArr);
		$this->groupBy = 'productId';
		$rows = $this->getPagePlan("select_equ");
		$equIdArr = array();
		if( is_array($rows)&&count($rows)>0 ){
			foreach ( $rows as $key => $val ){
				$equIdArr[] = $val['productId'];
			}
			$equIdStr = implode(',',$equIdArr);
			$stockArr = $stockDao->get_d(1);
			$equInvInfo = $inventoryDao->getInventoryInfos($stockArr['salesStockId'],$equIdStr);
			foreach ( $rows as $key=>$val ){
				$rows[$key]['inventoryNum'] = 0;
				foreach( $equInvInfo as $k => $v ){
					if( $val['productId']==$v['productId'] ){
						$rows[$key]['inventoryNum'] = $v['exeNum'];
					}
				}
			}
			$i = 0;
			foreach($rows as $key => $val){
				$searchArr = $this->__GET("searchArr");
				$searchArr['productId'] = $val['productId'];
				$this->groupBy="id";
				$this->sort="id";
				$this->searchArr=$searchArr;
				$chiRows = $this->listBySqlId("select_cont");
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
//			echo "<pre>";
//			print_R($rows);
			return $rows;
		}
		else {
			return false;
		}
	}


	/**�ɹ�����-�ɹ��ƻ�-�豸�嵥��ʾģ��
	*author can
	*2011-3-23
	*/
	function showEqulist_s($rows){
		$str = "";
		$i = 0;
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				$i++;
				$addAllAmount = 0;
				$strTab="";
				foreach ($val['childArr'] as $chdKey => $chdVal){
					switch($chdVal['tablename']){
						case 'oa_sale_order': $chdVal['tablename'] = '���۷���';break;
						case 'oa_sale_lease': $chdVal['tablename'] = '���޷���';break;
						case 'oa_sale_rdproject': $chdVal['tablename'] = '�з�����';break;
						case 'oa_sale_service': $chdVal['tablename'] = '���񷢻�';break;
					}
//					$i++;
					$iClass = (($i%2)==0)?"tr_even":"tr_odd";
//					if( isset( $chdVal['amountIssued']) && $chdVal['amountIssued']!="" ){
//						$amountOk = $chdVal['amountAll'] - $chdVal['amountIssued'];
//					}else{
//						$amountOk = $chdVal['amountAll'];
//					}
					$addAllAmount += $chdVal['number']-$chdVal['executedNum'];
					$inventoryNum = $rows[$key]['inventoryNum'];

//					if( $amountOk==0 || $amountOk=="" ){
//						$checkBoxStr =<<<EOT
//				        	$chdVal[basicNumb]
//EOT;
//					}else{
//						<input type="checkbox" class="checkChild" >
//						$checkBoxStr =<<<EOT
//				    	$chdVal[orderTempCode]<input type="hidden" class="hidden" value="$chdVal[orgid]"/>
//EOT;
//					}

					$strTab.=<<<EOT
						<tr align="center" height="28" class="$iClass">
			        		<td width="20%">
						    	$chdVal[code]
					        </td>
					        <td  width="8%">
					            $chdVal[number]
					        </td>
					        <td  width="8%">
					            $chdVal[onWayNum]
					        </td>
					        <td  width="8%">
					            $chdVal[executedNum]
					        </td>
		            	</tr>
EOT;
				}

				$str .=<<<EOT
					<tr class="$iClass">
				        <td    height="30" width="4%">
				        	<p class="childImg">
				            <image src="images/expanded.gif" />$i
				        	</p>
				        </td>
				        <td >
				            $val[productNo]<br>$val[productName]
				        </td>
				        <td   width="8%">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><!--input type="checkbox"-->$addAllAmount</p>
				            </p>
				        </td>
				        <td width="8%">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><!--input type="checkbox"-->$inventoryNum</p>
				            </p>
				        </td>
				        <td width="65%" class="tdChange td_table" >
							<table width="100%"  class="shrinkTable main_table_nested">
								$strTab
				        	</table>
				        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
				        </td>
				    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;
	}

	/**
	 * ��ͬ�豸�ܻ� ��ҳ
	 * 2011��10��19�� 16:24:57
	 */
	 function getPagePlan($sql){
		$sql=$this->sql_arr [$sql];
		$countsql = "select count(0) as num " . substr ( $sql, strpos ( $sql, "FROM" ) );
		$countsql = $this->createQuery ( $countsql, $this->searchArr );
		$this->count = $this->queryCount ( $countsql );
		//ƴװ��������
		$sql = $this->createQuery ( $sql, $this->searchArr );
		//print($sql);
		//����������Ϣ
		$asc = $this->asc ? "DESC" : "ASC";
		//echo $this->asc;
		$sql .= " group by productId order by " . $this->sort . " " . $asc;
		//������ȡ��¼��
		$sql .= " limit " . $this->start . "," . $this->pageSize;
//		echo $sql;
		return $this->_db->getArray ( $sql );
	 }
/**************************************************��ͬ�豸ͳ�Ʋ��� end***************************************/

 /**
  * ���¾�����
  */
  function updatePresent_d(){
	try {
		$this->start_d();
	  	$objArr = $this->list_d();
		$linkdao = new model_projectmanagent_present_presentequlink();
		if( is_array($objArr)&&count($objArr)>0 ){
			$mainSql = "update oa_present_present set dealStatus='1'";
			$this->_db->query($mainSql);
		  	foreach( $objArr as $key=>$val ){
		  		$presentId = $val['id'];
				//����ȷ��������
				$link = array (
					"presentId" => $presentId,
					"rObjCode" => $val['objCode'],
					"presentCode" => $val['Code'],
					"presentName" => '',
					"presentType" => "oa_present_present",
					"ExaStatus" => '���',
					"ExaDTOne" => $val['ExaDT'],
					"ExaDT" => $val['ExaDT'],
					"changeTips" => 0,
					"updateTime" => $val['updateTime'],
					"updateId" => $val['updateId'],
					"updateName" => $val['updateName'],
					"createTime" => $val['createTime'],
					"createName" => $val['createName'],
					"createId" => $val['createId']
				);
				$linkArr[$presentId] = $linkdao->create($link); //����linkId
		  	}
		  	if( is_array($linkArr)&&count($linkArr)>0 ){
			  	foreach( $linkArr as $key=>$val ){
			  		$itemSql = "update oa_present_equ set linkId=".$val." where presentId=".$key;
					$this->_db->query($itemSql);
			  	}
		  	}
		}
		$this->commit_d();
		return true;
	} catch (exception $e) {
		$this->rollBack();
		return $e;
	}
  }


     /**
      * �ر�����ȷ��
      */
      function setEmailAfterCloseConfirm($id){
        try{
			$this->start_d();
			$linkDao = new model_projectmanagent_present_presentequlink();
			$linkDao->update( array('presentId'=>$id),array('ExaStatus'=>'���','ExaDT'=>day_date) );
			$addMsg = '��������������������ȷ�ϡ�';
		 	$mainObj = $this->get_d($id);
		 	$updateKey = array(
		 		'dealStatus' => '3'
		 	);
		 	$this->update(array('id'=>$id),$updateKey);
		 	$outmailArr=array(
		 		$mainObj['salesNameId']
		 	);
		 	$outmailStr = implode(',',$outmailArr);
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL']
			, 'oa_present_equ', '�ر�', $mainObj['Code'], $outmailStr, $addMsg, '1');

			$this->commit_d();
			return 1;
		}catch(exception $e){
			$this->rollBack();
			return 0;
		}
      }
    /**
     * workflow callback
     */
     function workflowCallBack($spid){
     	$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		if (! empty ( $objId )) {
			$contract = $this->get_d ( $objId );

			$changeLogDao = new model_common_changeLog ( 'present' );
            $changeLogDao->confirmChange_d ( $contract );
			if ($contract ['ExaStatus'] == "���") {
                //�޸�ȷ��״̬
                $dealStatusSql = "update oa_present_present set dealStatus=1,standardDate=".$contract['standardDate'].",costEstimates='".$contract['costEstimates']."',costEstimatesTax='".$contract['costEstimatesTax']."' where id=" . $contract['originalId'] . "";
                $this->query($dealStatusSql);

                //�������Ϲ�����ƷID
                $getEqusSql = "select * from oa_present_equ where presentId = ".$contract['originalId'];
                $equs = $this->_db->getArray($getEqusSql);
                if($equs){
                    foreach ($equs as $equ){
                        $getProductsSql = "select originalId from oa_present_product where id = ".$equ['conProductId'];
                        $products = $this->_db->getArray($getProductsSql);
                        if($products && isset($products[0]['originalId']) && $products[0]['originalId'] != '0'){
                            $dealEquConProductIdSql = "update oa_present_equ set conProductId=".$products[0]['originalId']." where id=" . $equ['id'] . "";
                            $this->query($dealEquConProductIdSql);
                        }
                    }
                }

                $this->updateOutStatus_d($contract['originalId']);
                $this->updateShipStatus_d($contract['originalId']);
            }
		}
     }

     function workflowCallBack_equConfirm($spid){
         $otherdatas = new model_common_otherdatas();
         $folowInfo = $otherdatas->getWorkflowInfo($spid);
         $presentequDao = new model_projectmanagent_present_presentequ();
         $linkDao = new model_projectmanagent_present_presentequlink();
         $objId = $folowInfo['objId'];
         $linkObj = $linkDao->findBy('presentId', $objId);

         if (!empty ($objId)) {
             $presentObj = $this->get_d($objId);
             if($presentObj['ExaStatus'] == '���'){
                 // ����ԭ����������
                 $dateObj['dealStatus']=1;
                 $linkObj['ExaStatus']='���';
                 $linkObj['ExaDT']=day_date;
                 $linkObj['ExaDTOne']=day_date;
                 $linkDao->edit_d($linkObj);
                 $this->updateById($dateObj);

                 // ��������
                 $object['id'] = $objId;
                 $this->updateShipStatus_d($object['id']);
                 $this->updateOutStatus_d($object['id']);
                 $presentequDao->sendMailAtAudit($object,'�ύ');
             }
         }
     }

     /**
      * �������ͺ�ͬid��ȡ���һ�α����¼id
      */
     function findChangeId($id)
     {
         $sql = "select max(id) as Mid from oa_present_present where originalId = $id";
         $idArr = $this->_db->getArray($sql);
         return $idArr[0]['Mid'];
     }
 }
?>