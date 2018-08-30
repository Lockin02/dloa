<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @description: 服务合同Controller类
 * @date 2010-12-1 下午04:22:23 LiuB
 */
class controller_engineering_serviceContract_serviceContract extends controller_base_action {
	function __construct(){
		$this->objName = "serviceContract";
		$this->objPath = "engineering_serviceContract";
		parent::__construct();
	}

/***********************************************以下是普通Action方法***********************************************/
    /**
     * 合同信息----服务合同信息
     * by MaiZP
     */
     function c_toContractListAll(){
		$this->display("listAll");
	}


	/*
	 * 跳转到Tab标签主页
	 */
	function c_toAddContractIndex(){
		$this->display("add-index");
	}

	/*
	 * @desription 新建的服务合同列表Tab页
	 * @param tags
	 * @date 2010-12-2 下午02:16:51
	 */
	function c_toContractListIndex () {
		$this->display('list-index');
	}

	/*
	 * @desription 跳转到服务合同新增页面
	 * @param tags
	 * @date 2010-12-2 上午09:59:41
	 */
	function c_toAddContract () {
        $this->assign('orderInput', ORDER_INPUT);
		$this->assign('createName', $_SESSION['USERNAME']);
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('saleman', $_SESSION['USERNAME']);
		$this->assign('salemanId', $_SESSION['USER_ID']);
		$this->assign('orderPrincipal', $_SESSION['USERNAME']);
		$this->assign('orderPrincipalId', $_SESSION['USER_ID']);
		$this->assign('createTime' , day_date );
		//产生系统编号
		$systemCode = generatorSerial();
		$this->assign('systemCode',$systemCode);

		//配置发票类型的数据字典
		$this->showDatadicts(array('invoiceType'=>'FPLX'));
		//默认合同的审核状态为“未审核”
			$this->assign('ExaStatus','未审核');
			$this->assign('status','合同未启动');
			$this->assign('missionStatus','未下达');
		$id = $_GET['id'];
        if ($id) {
        	$this->permCheck($id,projectmanagent_chance_chance);
			$condiction = array (
				"id" => $id
			);
			$cluesDao = new model_projectmanagent_chance_chance();
			$rows = $cluesDao->get_d($id);
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
            $this->customerProCity($rows['customerId']);//根据客户获取 客户的省市信息
			$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
			$this->showDatadicts(array ( 'orderNature' => 'FWHTSX' ), $rows['orderNature']);
            $orderChance = new model_engineering_serviceContract_serviceequ();
			$chanceequ = $orderChance->ChanceOrderEqu($rows['chanceequ']);
			$orderCus = new model_engineering_serviceContract_customizelist();
            $customizelist = $orderCus->changeCusOrder($rows['customizelist']);

			$this->assign('chance', $chanceequ[0]);
			$this->assign('productNumber', $chanceequ[1]);
			$this->assign('customizelist', $customizelist[0]);
			$this->assign('PreNum', $customizelist[1]);
			$this->assign('chanceId', $id);
			$this->display('add-chance');
		} else {
			$this->display('add');
		}

	}
	/**
     * 根据客户ID 获取 客户的省市信息
     */
    function customerProCity($customerId){
      $dao = new model_customer_customer_customer();
      $proId = $dao->find(array("id" => $customerId),null,"ProvId");//省份ID
      $cityId = $dao->find(array("id" => $customerId),null,"CityId");//城市ID
      $this->assign("orderProvinceId",$proId['ProvId']);
      $this->assign("orderCityId",$cityId['CityId']);
    }
/*******************************************************************************************/
    /**
     * 未审批的服务合同
     */
   function c_toMyorderWtj(){
   	   $this->assign("userId" , $_SESSION['USER_ID']);
		$this->display('myorder-wtj');
   }
	/*
	 * @desription 审批中的合同
	 * 	 * @param tags
	 * @date 2010-12-2 下午02:26:19
	 */
	function c_toUnDoContractList () {
		$this->assign("userId" , $_SESSION['USER_ID']);
		$this->display('listUndo');
	}

    /**
     * 正执行的服务合同
     */
     function c_toMyorderZzx(){
     	$this->assign("userId" , $_SESSION['USER_ID']);
     	$this->display('myorder-zzx');
     }

     /**
      * 已关闭的服务合同
      */
      function c_toMyorderYwg(){
      	$this->assign("userId" , $_SESSION['USER_ID']);
      	$this->display('myorder-ywg');
      }
     /**
      * 已完成的服务合同
      */
     function c_toMyorderYwc(){
     	$this->assign("userId" , $_SESSION['USER_ID']);
     	$this->display('myorder-ywc');
     }

    /**跳转到合同变更页面
	*author can
	*2011-6-2
	*/
	function c_toChange(){
	  $changeLogDao = new model_common_changeLog ( 'servicecontract' );
	  if($changeLogDao->isChanging($_GET['id'])){
	  		msgGo ( "该合同已在变更审批中，无法变更." );
	  }
   	  $changer = isset ($_GET['changer'])? $_GET['changer'] :null;
   	  $changeC = isset ($_GET['changeC'])? $_GET['changeC'] :null;

   	  $rows = $this->service->get_d($_GET['id']);
  	  $rows['file'] = $this->service->getFilesByObjId($rows['id'],true);
  	  $rows = $this->service->initChange($rows);
       foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);

		}
		if($rows['sign'] == '是'){
        	$this->assign('signYes' , 'checked');
        }else if($rows['sign'] == '否'){
        	$this->assign('signNo'  , 'checked');
        };

        if($rows['orderstate'] == '已提交'){
        	$this->assign('orderstateYes' , 'checked');
        }else if($rows['orderstate'] == '已拿到'){
        	$this->assign('orderstateNo'  , 'checked');
        };
        if($rows['isShipments'] == '是'){
        	$this->assign('isShipYes' , 'checked');
        }else if($rows['isShipments'] == '否'){
        	$this->assign('isShipNo' , 'checked');
        };
		$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
		$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
			$this->showDatadicts(array ( 'orderNature' => 'FWHTSX' ), $rows['orderNature']);
		$this->assign('changer', $changer);
		$this->assign('changeC', $changeC);
		$this->display('change');

	}

	/**变更服务合同
	*author can
	*2011-6-1
	*/
	function c_change() {
		try {
			$changer=isset($_GET['changer'])?$_GET['changer']:null;
			$changeC=isset($_GET['changeC'])?$_GET['changeC']:null;
             $infoArr =  $_POST ['serviceContract'];
             $isDel="0";
			foreach($infoArr['serviceequ'] as $key =>$val){
				if (empty($val['productName'])){
					unset($infoArr['serviceequ'][$key]);
				}
				if(array_key_exists ("isDel",$val)){
					//判断物料是否有下达发货，下达采购，下达生产
                   $fsql="select count(id) as isExe from oa_service_equ where (issuedPurNum > 0 or issuedProNum >0 or issuedShipNum>0) and id=".$val['oldId']." ";
                   $isExeArr = $this->service->_db->getArray($fsql);
                    $isExe += $isExeArr[0]['isExe'];
					$isDel="1";
					$purchaseArr[] = $this->service->purchaseMail($val['oldId']);
				}
			}
			//如果删除的物料有下达采购的，发邮件给采购部
            if(!empty($purchaseArr[0])){
               //获取默认发送人
		       include (WEB_TOR."model/common/mailConfig.php");
		       $toMailId = $mailUser['contractChangepurchase']['sendUserId'];
		       $emailDao = new model_common_mail();
		       if(empty($infoArr['orderCode'])){
		       	  $orderCode = $infoArr['orderTempCode'];
		       }else{
		       	  $orderCode = $infoArr['orderCode'];
		       }
		       //发送人，发送人邮箱，title,收件人,附加信息
			   $emailInfo = $emailDao->contractChangepurchaseMail($_SESSION['USERNAME'],$_SESSION['EMAIL'],"销售变更物料提醒",$toMailId,$purchaseArr,$orderCode);
            }
			foreach($infoArr['serviceequTemp'] as $key =>$val){
				if (empty($val['productName'])){
					unset($infoArr['serviceequTemp'][$key]);
				}
			}
		   if($isDel=="1" && $isExe != "0"){
	         $formName="服务合同变更（删物料）";
//	         $formName="服务合同审批";
           }else{
           	 $formName="服务合同审批";
           }
			$id = $this->service->change_d ( $infoArr );
			if($changer=="changer"){
				if($changeC == "changeC"){
					echo "<script>this.location='controller/engineering/serviceContract/ewf_mychangeC_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
				}else{
					echo "<script>this.location='controller/engineering/serviceContract/ewf_mychange_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
				}
			}else{
				echo "<script>this.location='controller/engineering/serviceContract/ewf_change_index.php?actTo=ewfSelect&billId=" . $id . "&formName=".$formName."'</script>";
			}
		} catch ( Exception $e ) {
			msgBack2 ( "变更失败！失败原因：" . $e->getMessage () );
		}
	}



/*******************************************************************************************/
/**
 * 重写init
 */
   function c_init() {
//   	  $this->permCheck();//安全校验
   	  $perm = isset ($_GET['perm'])? $_GET['perm'] :null;
   	  $rows = $this->service->get_d($_GET['id']);


		if ($perm == 'view') {

            //权限过滤,如果是合同负责人和区域负责人、合同创建人，则不限制字段权限过滤
            if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['orderPrincipalId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
                $rows = $this->service->filterWithoutField('服务合同金额',$rows,'form',array('orderMoney','orderTempMoney'));
                $rows['serviceequ'] = $this->service->filterWithoutField('服务设备金额',$rows['serviceequ'],'list',array('price','money'));
            }

            $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
            //合同文本权限

			 if($rows['areaPrincipalId'] != $_SESSION['USER_ID']&&$rows['orderPrincipalId'] != $_SESSION['USER_ID']&&$rows['createId'] != $_SESSION['USER_ID']){
	            if(!empty($this->service->this_limit ['服务合同金额'])){
					$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_service2' );
				}else{
					$rows ['file2']="";
				}
            }else{
            	$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_service2' );
            }
            $rows = $this->service->initView($rows);

            foreach ($rows as $key => $val) {
                $this->show->assign($key, $val);
            }

			if ($rows['sign'] == '是') {
					$this->assign('sign', '是');
				} else
					if ($rows['sign'] == '否') {
						$this->assign('sign', '否');
					};

			if ($rows['orderstate'] == '已提交') {
					$this->assign('orderstate', '已提交');
			} else if ($rows['orderstate'] == '已拿到') {
					$this->assign('orderstate', '已拿到');
			};
			if($rows['shipCondition'] == '0' ){
				$this->assign ( 'shipCondition', '(立即发货)' );
			}else if($rows['shipCondition'] == '1' ){
				$this->assign ( 'shipCondition', '(通知发货)' );
			}else{
				$this->assign ( 'shipCondition', '' );
			}
			$orderTempCode = explode ( ',', $rows ['orderTempCode'] );
			$this->assign ( 'orderTempCode', $this->service->TempOrderView ( $orderTempCode, $_GET ['skey'] ) );
			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
			$this->display('view');
		}  else{

            $rows['file'] = $this->service->getFilesByObjId($rows['id'],true);
		    $rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_service2' );
            $rows = $this->service->initEdit($rows);


            foreach ($rows as $key => $val) {
                $this->show->assign($key, $val);
            }
			if ($perm == 'signIn') {
				if ($rows['sign'] == '是') {
					$this->assign('signYes', 'checked');
				} else
					if ($rows['sign'] == '否') {
						$this->assign('signNo', 'checked');
					};

				if ($rows['orderstate'] == '已提交') {
					$this->assign('orderstateYes', 'checked');
				} else
					if ($rows['orderstate'] == '已拿到') {
						$this->assign('orderstateNo', 'checked');
					};
				$this->assign('signName', $_SESSION['USERNAME']);
				$this->assign('signNameId', $_SESSION['USER_ID']);
				$this->assign('signDate', date('Y-m-d'));
                $this->showDatadicts(array ( 'orderNature' => 'FWHTSX' ), $rows['orderNature']);
				$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
				$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
				$this->display('signin');
			}else {
    			if($rows['sign'] == '是'){
                	$this->assign('signYes' , 'checked');
                }else if($rows['sign'] == '否'){
                	$this->assign('signNo'  , 'checked');
                };

                if($rows['orderstate'] == '已提交'){
                	$this->assign('orderstateYes' , 'checked');
                }else if($rows['orderstate'] == '已拿到'){
                	$this->assign('orderstateNo'  , 'checked');
                };
                if($rows['isShipments'] == '是'){
                	$this->assign('isShipYes' , 'checked');
                }else if($rows['isShipments'] == '否'){
                	$this->assign('isShipNo' , 'checked');
                };
    			$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
    			$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
    			$this->showDatadicts(array ( 'orderNature' => 'FWHTSX' ), $rows['orderNature']);
    			$this->display('edit');
    		}
        }
   }

   /**
	 * 审批查看页面
	 */
	function c_toViewForAudit() {
		$perm = isset ($_GET['perm'])? $_GET['perm'] :null;
		$rows = $this->service->get_d($_GET['id']);

        $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
        //合同文本权限


    	$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], false,'oa_sale_service2' );
        $rows = $this->service->initView($rows);

        foreach ($rows as $key => $val) {
            $this->show->assign($key, $val);
        }

		if ($rows['sign'] == '是') {
				$this->assign('sign', '是');
			} else
				if ($rows['sign'] == '否') {
					$this->assign('sign', '否');
				};

		if ($rows['orderstate'] == '已提交') {
				$this->assign('orderstate', '已提交');
		} else if ($rows['orderstate'] == '已拿到') {
				$this->assign('orderstate', '已拿到');
		};
		if($rows['shipCondition'] == '0' ){
			$this->assign ( 'shipCondition', '(立即发货)' );
		}else if($rows['shipCondition'] == '1' ){
			$this->assign ( 'shipCondition', '(通知发货)' );
		}else{
			$this->assign ( 'shipCondition', '' );
		}
		$orderTempCode = explode ( ',', $rows ['orderTempCode'] );
		$this->assign ( 'orderTempCode', $this->service->TempOrderView ( $orderTempCode, $_GET ['skey'] ) );
		$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
		$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
		$this->display('view');
	}

    /**
     * 物料修改
     */
    function c_productEdit() {
		if(!isset($this->service->this_limit['物料修改'])||$this->service->this_limit['物料修改'] != 1){
			echo "没有物料修改的权限，请联系OA管理员开通";
			exit();
		}
   	   $this->permCheck();//安全校验
   	   $perm = isset ($_GET['perm'])? $_GET['perm'] :null;
   	   $rows = $this->service->get_d($_GET['id']);
//   	   foreach ($rows['serviceequ'] as $k => $v){
//        	 if($v['purchasedNum'] > 0 || $v['issuedShipNum'] > 0){
//                   echo "该合同已执行发货或采购操作，请选择变更流程修改物料";
//                   exit();
//        	 }
//        }
       $rows['file'] = $this->service->getFilesByObjId($rows['id'],false);

   	   $rows = $this->service->editProduct($rows);
         foreach ($rows as $key => $val) {
                $this->show->assign($key, $val);
            }
    			if($rows['sign'] == '是'){
                	$this->assign('signYes' , 'checked');
                }else if($rows['sign'] == '否'){
                	$this->assign('signNo'  , 'checked');
                };

                if($rows['orderstate'] == '已提交'){
                	$this->assign('orderstateYes' , 'checked');
                }else if($rows['orderstate'] == '已拿到'){
                	$this->assign('orderstateNo'  , 'checked');
                };
                if($rows['isShipments'] == '是'){
                	$this->assign('isShipYes' , 'checked');
                }else if($rows['isShipments'] == '否'){
                	$this->assign('isShipNo' , 'checked');
                };
    			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			    $this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
    			$this->display('productedit');
        }


	/**
	 * 合同查看页面
	 */
	function c_toViewTab() {
//        $this->permCheck ();//安全校验
		$this->assign('id', $_GET['id']);
		$rows = $this->service->get_d($_GET['id']);
		$this->assign('originalId', $rows['originalId']);
		$this->display('view-tab');
	}
	/**审批变更合同的查看页面
	*author can
	*2011-6-1
	*/
	function c_toReadTab() {
        $this->permCheck();
		$this->assign('id', $_GET['id']);
		$rows = $this->service->get_d($_GET['id']);
		$originalKey=$this->md5Row($rows ['originalId']);
		$this->assign ( 'orderCode', $rows ['orderCode'] );
		$this->assign('originalId', $rows['originalId']);
		$this->assign ( 'originKey', $originalKey);
		$this->display('read-tab');
	}



/**
	 * 合同查看Tab---关闭信息
	 */
	function c_toCloseInfo() {
		$rows = $this->service->get_d($_GET['id']);
		if ($rows['state'] == '3' || $rows['state'] == '9' ) {
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
			$this->display('closeinfo');

		} else {
			echo '<span>暂无相关信息</span>';
		}
	}
/*********************************************************************************************************/

    /**
     * 合同签收
     */
	function c_toSign() {
				$this->permCheck();//安全校验
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$tablename = isset ($_GET['tablename']) ? $_GET['tablename'] : null;
		$rows = $this->service->get_d($_GET['id']);


		//渲染从表

			//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'],true);
		$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_service2' );
	    $rows = $this->service->initChange($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
				if ($rows['sign'] == '是') {
					$this->assign('signYes', 'checked');
				} else
					if ($rows['sign'] == '否') {
						$this->assign('signNo', 'checked');
					};

				if ($rows['orderstate'] == '已提交') {
					$this->assign('orderstateYes', 'checked');
				} else
					if ($rows['orderstate'] == '已拿到') {
						$this->assign('orderstateNo', 'checked');
					};
				$this->assign('signName', $_SESSION['USERNAME']);
				$this->assign('signNameId', $_SESSION['USER_ID']);
				$this->assign('signDate', date('Y-m-d'));

				$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
				$this->showDatadicts(array ( 'invoiceType' => 'FPLX' ), $rows['invoiceType']);
				$this->display('signin');
	}



	/**
	 * 合同签收
	 */
	function c_signInVerify($isEditInfo = false) {
		$object = $_POST[$this->objName];
		if ($this->service->signin_d($object, $isEditInfo)) {
			msgRF('签收完成！');
		}
	}

	/**
	 * by maizp
	 * 转为正式合同
	 */
	 function c_toBecomeContract() {
		$this->permCheck();//安全校验
		$this->assign('orderInput', ORDER_INPUT);
		$rows = $this->service->get_d($_GET['id']);
		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		$rows ['file2'] = $this->service->getFilesByObjId ( $rows ['id'], true,'oa_sale_service2' );
		//渲染从表
		$rows = $this->service->becomeEdit($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		 if($rows['isShipments'] == '是'){
                	$this->assign('isShipYes' , 'checked');
                }else if($rows['isShipments'] == '否'){
                	$this->assign('isShipNo' , 'checked');
                };
		$this->showDatadicts(array (
			'customerType' => 'KHLX'
		), $rows['customerType']);
		$this->showDatadicts(array (
			'invoiceType' => 'FPLX'
		), $rows['invoiceType']);
		$this->showDatadicts(array (
			'orderNature' => 'XSHTSX'
		), $rows['orderNature']);
		$this->assign("pId", $_GET['id']);
		$this->display("becomecontract");
	}
/**
	 * by maizp
	 */
     function c_become($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$orderCodeDao = new model_common_codeRule();
       if($object['orderInput'] == "1"){
			$object['orderCode'] = $orderCodeDao->contractCode ("oa_sale_service",$object['cusNameId']);
			if($this->service->becomeEdit_d($object)){
				msgRF('已转为正式合同');
			}
		}else if($object['orderInput'] == "0"){
			if($this->service->becomeEdit_d($object)){
				msgRF('已转为正式合同');
			}
		}else {
			msgGo('请找管理员确认配置合同输入的"ORDER_INPUT"值是否正确');
		}
	}
/**********************************************************************************************************/
	/**
	 * 关闭合同页面
	 */
	function c_CloseOrder() {
		$row = $this->service->get_d($_GET['id']);
		foreach ($row as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('userName', $_SESSION['USERNAME']);
		$this->assign('dateTime', date('Y-m-d'));
		$this->display('closeorder');
	}
	/**
	 * 关闭合同
	 */
	function c_close($isEditInfo = false) {
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$object = $_POST[$this->objName];
		$id = $this->service->close_d($object, $isEditInfo);
		if ($id && $_GET['actType'] == "app") {
           succ_show('controller/engineering/serviceContract/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
		}else {
			msg('关闭成功！');
		}
	}

	/**
     * 关闭合同审批查看页
     */
    function c_toCloseView() {

    	$rows = $this->service->get_d($_GET['id']);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->assign('view', $this->service->closeOrderView($_GET['id']));
    	$this->display('closeview');
    }


	/**
	 * 关闭审批tab
	 */
	function c_toCloseAuditTab() {
		$this->display('closeaudittab');
	}

	/**
	 * 未审批的关闭
	 */
	function c_toCloseAuditUndo() {
		$this->display('closeauditundo');
	}

	/**
	 * 未审批Json
	 */
	function c_jsonCloseAuditNo() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '服务合同异常关闭审批';
		$rows = $service->pageBySqlId('select_auditing');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 已审批的关闭
	 */
	function c_toCloseAuditDone() {
		$this->display('closeauditdone');
	}

	/**
	 * 已审批Json
	 */
	function c_closeAuditYesJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '服务合同异常关闭审批';
		$rows = $service->pageBySqlId('select_audited');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/*
	 * @desription 跳转到服务合同新增页面--具有关闭按钮
	 * @param tags
	 * @author qian
	 * @date 2010-12-26 下午02:28:34
	 */
	function c_toAddContract2 () {
		//产生系统编号
		$systemCode = generatorSerial();
		$this->assign('systemCode',$systemCode);

		//配置发票类型的数据字典
		$this->showDatadicts(array('invoiceType'=>'FPLX'));

		//默认合同的审核状态为“未审核”
		$this->assign('ExaStatus','未审核');
		$this->assign('status','合同未启动');
		$this->assign('missionStatus','未下达');
		$this->display("add2");
	}

	/*
	 * @desription 商机转服务合同的保存方法
	 */
	function c_addChance () {
		$service = $this->service;

		//$trainArr = isset( $_POST['serviceTrain'] )?$_POST['serviceTrain']:null;
		$arr = isset( $_POST[$this->objName] )?$_POST[$this->objName]:null;
//		if($arr['orderInput'] == "1"){
//		    $orderCodeDao = new model_common_codeRule();
//			if( $arr['sign'] == "否" ){
//			   $arr['orderTempCode'] = $orderCodeDao->contractCode ("oa_sale_service",$arr['cusNameId']);
//			}else if($arr['sign'] == "是"){
//				$arr['orderCode'] = $orderCodeDao->contractCode ("oa_sale_service",$arr['cusNameId']);
//			}
//			$id = $service->addContract_d($arr);
//		}else if($arr['orderInput'] == "0"){
//			$id = $service->addContract_d($arr);
//		}else {
//			msgGo('请找管理员确认配置合同输入的"ORDER_INPUT"值是否正确');
//		}
		$id = $service->addContract_d($arr);
		if($id){
			if($_GET['act']=='app'){
				succ_show('controller/engineering/serviceContract/ewf_index.php?actTo=ewfSelect&billId=' . $id );
			}else{
				//如果添加成功，则跳转到服务合同编辑页面
				msgRF('添加成功！');
			}
		}
	}
	/*
	 * @desription 服务合同的保存方法
	 * @param tags
	 * @date 2010-12-2 上午10:19:27
	 */
	function c_addContract () {
		$service = $this->service;

		//$trainArr = isset( $_POST['serviceTrain'] )?$_POST['serviceTrain']:null;
		$arr = isset( $_POST[$this->objName] )?$_POST[$this->objName]:null;
//	    if($arr['orderInput'] == "1"){
//		    $orderCodeDao = new model_common_codeRule();
//			if( $arr['sign'] == "否" ){
//				$arr['orderTempCode'] = "LS".$arr['orderTempCode'];
//			   $arr['orderTempCode'] = $orderCodeDao->contractCode ("oa_sale_service",$arr['cusNameId']);
//			}else if($arr['sign'] == "是"){
//				$arr['orderCode'] = $orderCodeDao->contractCode ("oa_sale_service",$arr['cusNameId']);
//			}
//			$id = $service->addContract_d($arr);
//		}else if($arr['orderInput'] == "0"){
//			if(!empty($arr['orderTempCode'])){
//				$arr['orderTempCode'] = $arr['orderTempCode'];
//			}
//			$id = $service->addContract_d($arr);
//		}else {
//			msgGo('请找管理员确认配置合同输入的"ORDER_INPUT"值是否正确');
//		}
		$id = $service->addContract_d($arr);
		if($id){
			if($_GET['act']=='app'){
				succ_show('controller/engineering/serviceContract/ewf_index2.php?actTo=ewfSelect&billId=' . $id );
			}else{
				//如果添加成功，则跳转到服务合同编辑页面
				msgGo('保存成功！','index1.php?model=engineering_serviceContract_serviceContract&action=toAddContract');
			}
		}
	}


	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST[$this->objName];
        $orderCodeDao = new model_common_codeRule();
        if($object['sign'] == "是" && $object['orderCode'] == ''){
			$object['orderCode'] = $orderCodeDao->contractCode ("oa_sale_service",$object['cusNameId']);
		}

		if ($this->service->edit_d($object, $isEditInfo)) {
			msg('编辑成功！');
		}
	}
	/**
	 * 物料修改
	 */
	function c_proedit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$id = $this->service->proedit_d ( $object, $isEditInfo );

		if ($id) {
            $this->service->updateOrderShipStatus_d($id);
			msgRF ( '编辑成功' );
		}
	}

/**
 * 我的合同 -- xxx （用于导出权限）
 */
 function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$orderDao = new model_projectmanagent_order_order();
//        $export = isset ( $service->this_limit ['单条合同导出'] ) ? $service->this_limit ['单条合同导出'] : null;

		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
//		foreach($rows as $k => $v){
//             $rows[$k]['exportOrder'] = $export;
//		   }
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_pageJsonUnLimit() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$orderDao = new model_projectmanagent_order_order();
//        $export = isset ( $orderDao->this_limit ['单条合同导出'] ) ? $orderDao->this_limit ['单条合同导出'] : null;
		$service->asc = true;
		$rows = $service->pageBySqlId ();
//		foreach($rows as $k => $v){
//             $rows[$k]['exportOrder'] = $export;
//		   }
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}



	/*
	 * @desription 服务合同编辑页面
	 * @param tags
	 * @date 2010-12-2 下午05:26:30
	 */
	function c_toEditContract () {
		$service = $this->service;
		$contractID = isset( $_GET['contractId'] )?$_GET['contractId']:null;
		$service->searchArr = array( 'id'=>$contractID );
		$arr = $service->pageBySqlId();
		$arr[0]['file']=$this->service->getFilesByObjId($contractID);
		foreach($arr[0] as $key=>$val){
			$this->assign($key,$val);
		}
		$trainDao = new model_engineering_serviceTrain_servicetrain() ;
		$this->assign('list',$trainDao->showTrainEditList($contractID));
		$this->display('edit');
	}

	/*
	 * @desription 查看服务合同的基本信息
	 * @param tags
	 * @date 2010-12-7 上午10:35:58
	 */
	function c_toViewContractInfo () {
		$trainDao = new model_engineering_serviceTrain_servicetrain();
		$contractId = isset( $_GET['id'] )?$_GET['id']:$_GET['contractId'];
		$conSearch = array( 'id' => $contractId );
		$contractRows = $this->service->find($conSearch);
		//附件
		$contractRows['file']=$this->service->getFilesByObjId($contractId,false);
		foreach( $contractRows as $key => $val ){
			$this->assign( $key , $val );
		}
		$invoiceType = $this->getDataNameByCode($contractRows['invoiceType']);
		$this->assign('invoiceType',$invoiceType);

		$this->assign('list',$trainDao->showTrainViewList($contractId));
		$this->display( 'view2' );
	}

	/**
	 * 批量删除对象
	 */
	function c_deletesInfo() {
		$deleteId=isset($_GET['id'])?$_GET['id']:exit;
	        $delete=$this->service->deletesInfo_d ($deleteId);
         if($delete){
         	msg('删除成功');
         }
    }


	/**-----------------------------------------------以下是“未执行的服务合同”部分----------------------------------------------------*/

	/*
	 * @desription 未执行服务合同查看页面
	 * @param tags
	 * @date 2010-12-2 下午08:15:35
	 */
	function c_toViewUnDoContract () {
		$service = $this->service;
		$contractID = isset( $_GET['id'] )?$_GET['id']:null;
		$service->searchArr = array( 'id'=>$contractID );
		$arr = $service->pageBySqlId();
		foreach($arr[0] as $key=>$val){
			$this->assign($key,$val);
		}
		$this->display('viewUndo');
	}


	/*
	 * @desription 启动已审核的服务合同
	 * @param tags
	 * @date 2010-12-6 上午11:37:45
	 */
	function c_putContractStart () {
		$service = $this->service;
		$contractId = isset( $_GET['contractId'] )?$_GET['contractId']:null;
		$condiction = array( 'id' => $contractId );
		//启动操作是修改表里面对应状态字段的值。
		$updateTag = $service->updateField( $condiction,'status','合同执行中' );
		if($updateTag){
			//启动后刷新列表页面--由于“未执行合同”及“我申请的合同”均调用此方法，所以没有写上固定跳转页面。
			echo "<script>alert('启动成功');history.back();</script>";
		}
	}

	/**-----------------------------------------------以上是“未执行的服务合同”部分----------------------------------------------------*/

	/**-----------------------------------------------以下是“执行中的服务合同”部分----------------------------------------------------*/
	/*
	 * @desription 在执行的合同列表
	 * @param tags
	 * @date 2010-12-2 下午02:28:00
	 */
	function c_toDoContractList () {
		$this->show->display($this->objPath . '_' . $this->objName . '-listDo');
	}

	/*
	 * @desription 关闭服务合同
	 * @param tags
	 * @date 2010-12-6 下午02:23:57
	 */
	function c_putContractClose () {
		$service = $this->service;
		$contractId = isset( $_GET['contractId'] )?$_GET['contractId']:null;
		$condiction = array( 'id' => $contractId );
		//关闭操作是修改表里面对应状态字段的值。
		$updateTag = $service->updateField( $condiction,'status','完成合同' );
		if($updateTag){
			//启动成功后跳转回原来列表
			msgGo('关闭成功',"?model=engineering_serviceContract_serviceContract&action=toDoContractList");
		}
	}


	/**-----------------------------------------------以上是“执行中的服务合同”部分----------------------------------------------------*/

	/**-----------------------------------------------以下是“已关闭的服务合同”部分----------------------------------------------------*/

	/*
	 * @desription 已关闭合同列表
	 * @param tags
	 * @date 2010-12-2 下午02:28:25
	 */
	function c_toCloseContractList () {
		$this->display('listClose');
	}

	/**-----------------------------------------------以上是“已关闭的服务合同”部分----------------------------------------------------*/

	/**-----------------------------------------------以下是“个人办公-我的审批-合同审批”部分----------------------------------------------------*/
	/*
	 * @desription 跳转到“个人办公-我的审批-合同审批”主跳转页面
	 * @param tags
	 * @date 2010-12-6 下午08:31:38
	 */
	function c_toApprovalMain () {
		$this->display('approval-main');
	}

	/*
	 * @desription 我的审批，包含左导航栏
	 * @param tags
	 * @date 2010-12-6 下午03:12:03
	 */
	function c_toMyApproval () {
		$this->display('approval-menu');
	}

	/*
	 * @desription “个人办公-我的审批-合同审批”的Tab页
	 * @param tags
	 * @date 2010-12-6 下午07:05:48
	 */
	function c_toMyApprovalTab () {
		$this->display('approval-index');
	}
	/**变更的服务合同审批
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditTab () {
		$this->display('change-audit-tab');
	}

	/**-----------------------------------------------以上是“个人办公-我的审批-合同审批”部分----------------------------------------------------*/

	/**##########################################审批工作流部分##################################################*/

	/*
	 * @desription 待审批
	 * @param tags
	 * @date 2010-12-4 上午10:10:54
	 */
	function c_toApprovalNo () {
		$this->display( 'approvalNo');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonNo() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('select_auditing');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/*
	 * @desription 已审批
	 * @param tags
	 * @date 2010-12-4 上午11:05:03
	 */
	function c_toApprovalYes () {
		$this->display('approvalYes');
	}

	/*
	 * @desription 获取分布数据转成JSON--已审批
	 * @param tags
	 * @author qian
	 * @date 2010-12-22 上午11:14:18
	 */
	function c_pageJsonYes() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$service->groupBy = 'c.id';
		$rows = $service->pageBySqlId('select_audited');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**未审批的变更服务合同
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditNo(){
		$this->display('change-auditno');
	}
	/**未审批的变更服务合同PageJson
	*author can
	*2011-6-2
	*/
	function c_changeAuditNo() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('change_auditing');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**已审批的变更服务合同
	*author can
	*2011-6-2
	*/
	function c_toChangeAuditYes(){
		$this->display('change-audityes');
	}
	/**已审批的变更服务合同PageJson
	*author can
	*2011-6-2
	*/
	function c_changeAuditYes() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$service->groupBy = 'c.updateTime';
		$rows = $service->pageBySqlId('change_audited');
		$rows = $this->sconfig->md5Rows ( $rows,"contractId" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * add by can 2011-06-01
	 * 确认合同变更并且跳转到未审批的合同列表页
	 *
	 */
	function c_confirmChangeToApprovalNo() {
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			$objId = $folowInfo ['objId'];
			if (! empty ( $objId )) {
				$contract = $this->service->get_d ( $objId );
				$contract1 = $this->service->get_d ( $contract['originalId'] );

				$changeLogDao = new model_common_changeLog ( 'servicecontract' );
				$changeLogDao->confirmChange_d ( $contract );
				   if ($contract ['ExaStatus'] == "打回") { //打回情况
				            $sql = "update oa_sale_service set isBecome = 0 where id = ".$contract['originalId']."";
							$this->service->query ( $sql );
							//查找有无借试用转销售物料，有则删除最近一条
							$dao = new model_projectmanagent_borrow_toorder();
			                $dao->getRelOrderequ($contract['originalId'],"service","change",$objId);
					}else{
						$orderInfo = $this->service->get_d ( $contract['originalId'] );
						foreach ( $contract['serviceequ'] as $k => $v){
		                     $contractEqu1[$k] = $v['productId'];
		                     $contractNum1[$k] = $v['number'];
						}
						foreach ( $contract1['serviceequ'] as $k => $v){
		                     $contractEqu2[$k] = $v['productId'];
		                     $contractNum2[$k] = $v['number'];
						}
					       //查找有无借试用转销售物料
					      	  $dao = new model_projectmanagent_borrow_toorder();
			                  $borrowChange = $dao->getRelOrderequ($contract['originalId'],"service","changeE",$objId);
						if (($contractEqu1 != $contractEqu2) || ($contractNum1 != $contractNum2)){
							   $this->service->updateOrderShipStatus_d($contract['originalId']);
						}else if($borrowChange == '1'){
							$sql = "update oa_sale_service set state = 2,DeliveryStatus = 10 where id = ".$contract['originalId']."";
							$this->service->query ( $sql );
						}
						 //查找有无 借出单，有则自动生成归还单
				          $toorderDao = new model_projectmanagent_borrow_toorder();
				          $toorderDao->findLoan($objId,"service");
					}
			}
		}

		//防止重复刷新
//		echo "<script>this.location='?model=engineering_serviceContract_serviceContract&action=allAuditingList'</script>";
		$urlType = isset($_GET['urlType']) ? $_GET['urlType'] : null;
		//防止重复刷新
		if($urlType){
			echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
		}else{
			echo "<script>this.location='?model=projectmanagent_order_order&action=allAuditingList'</script>";
		}
		//$this->display ( 'change-approvalNo' );
	}

 /**
     * 取消变更提醒
     */
    function c_cancelBecome(){
        $orderId = $_GET['id'];
        $sql = "update oa_sale_service set isBecome = 0 where id = $orderId";
        $this->service->query($sql);
        return $orderId;
    }
	/**##########################################审批工作流部分##################################################*/


/**-----------------------------------------------以下是我申请的服务合同------------------------------------------------------------*/

	/*
	 * @desription 我申请的服务合同列表--左导航与右列表
	 * @param tags
	 * @author qian
	 * @date 2010-12-11 下午04:33:07
	 */
	function c_myAppServiceContract () {
		$this->show->display( $this->objPath . '_' . $this->objName . '-myapp-main' );
	}

	/*
	 * @desription menu菜单面页面
	 * @param tags
	 * @author qian
	 * @date 2010-12-11 下午04:38:30
	 */
	function c_toMyApplication () {
		$this->display( 'myapp-menu' );
	}

	/*
	 * @desription 我申请的服务合同列表的Tab页
	 * @param tags
	 * @author qian
	 * @date 2010-12-11 下午04:39:09
	 */
	function c_toMyApplicationTab () {
		$this->display( 'myapp' );
	}


/**-----------------------------------------------以上是我申请的服务合同------------------------------------------------------------*/


/***********************************************以上是普通Action方法***********************************************/
/***********************************************Ajax与JSON方法***********************************************/
	/**
	 * @ ajax判断项
	 * 临时合同号
	 */
	function c_ajaxOrderTempCode() {
		$service = $this->service;
		$projectName = isset ($_GET['orderTempCode']) ? $_GET['orderTempCode'] : false;
        $projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );

		$isRepeat = $service->isRepeatAll($searchArr, "");

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
	function c_ajaxOrderCode() {
		$service = $this->service;
		$projectName = isset ($_GET['orderCode']) ? $_GET['orderCode'] : false;

		 $projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );

		$isRepeat = $service->isRepeatAll($searchArr, "");

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/*
	 * ajax方式批量删除对象（应该把成功标志跟消息返回）
	 */
	function c_ajaxdeletesOrder() {
		//$this->permDelCheck ();
		$orderId = $_POST ['id'];
		$orderType = $_POST ['type'];
		try {
			//查找是否有 借试用转销售的关联物料 有则删除
			$dao = new model_projectmanagent_borrow_toorder();
			$dao->getRelOrderequ($orderId,$orderType);
			$del = $this->service->deletes_d ( $orderId );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}
/****************************************************************************/
   /**
    * 发货需求查看
    */
   function c_toShipView(){
	    $rows = $this->service->get_d($_GET['id']);
		//渲染从表
			//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'],false);
		$rows = $this->service->initView($rows);


		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
            if ($rows['sign'] == '是') {
					$this->assign('sign', '是');
				} else
					if ($rows['sign'] == '否') {
						$this->assign('sign', '否');
					};

			if ($rows['orderstate'] == '已提交') {
					$this->assign('orderstate', '已提交');
				} else
					if ($rows['orderstate'] == '已拿到') {
						$this->assign('orderstate', '已拿到');
					};
			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
			$this->display('view-ship');
		}
		/*******************************************************************************************************/

    /**********************开票收款权限部分*********************************/
    /**
     * 开票tab
     */
    function c_toInvoiceTab(){
        $obj = $_GET['obj'];
    	$this->permCheck($obj['objId']);
        if($this->service->isKeyMan_d($obj['objId'])||isset($this->service->this_limit['服务合同开票tab'])){
            $url = '?model=finance_invoice_invoice&action=getInvoiceRecords&obj[objType]='.$obj['objType'].'&obj[objCode]='.$obj['objCode'].'&obj[objId]='.$obj['objId'].'&skey='.$_GET['skey'];
            succ_show($url);
        }else{
            echo '没有权限,需要开通权限请联系oa管理员';
        }
    }

    /**
	 * 开票申请tab
	 */
	function c_toInvoiceApplyTab() {
		$obj = $_GET ['obj'];
		if ($this->service->isKeyMan_d ( $obj ['objId'] ) || ! empty ( $this->service->this_limit ['服务合同开票tab'] )) {
			$url = '?model=finance_invoiceapply_invoiceapply&action=getInvoiceapplyList&obj[objType]=' . $obj ['objType'] . '&obj[objCode]=' . $obj ['objCode'] . '&obj[objId]=' . $obj ['objId'] . '&skey=' . $_GET ['skey'];
			succ_show ( $url );
		} else {
			echo '没有权限,需要开通权限请联系oa管理员';
		}
	}

    /**
     * 到款tab
     */
    function c_toIncomeTab(){
        $obj = $_GET['obj'];
    	$this->permCheck($obj['objId']);
        if($this->service->isKeyMan_d($obj['objId'])||isset($this->service->this_limit['服务合同到款tab'])){
            $url = '?model=finance_income_incomeAllot&action=orderIncomeAllot&obj[objType]='.$obj['objType'].'&obj[objCode]='.$obj['objCode'].'&obj[objId]='.$obj['objId'].'&skey='.$_GET['skey'];
            succ_show($url);
        }else{
            echo '没有权限,需要开通权限请联系oa管理员';
        }
    }

    /**********************开票收款权限部分*********************************/

	function c_serviceContactInfoJson(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ();
		if($_REQUEST['prinvipalId'] != $_SESSION['USER_ID']){
			$orderDao = new model_projectmanagent_order_order();
			$rows = $orderDao->filterContractMoney_d ($rows,$this->service->tbl_name);
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/********************************************************/

	/**
	 * 获取分页数据转成Json
	 */
	function c_myOrderPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
        $orderDao = new model_projectmanagent_order_order();
        $export = isset ( $service->this_limit ['单条合同导出'] ) ? $service->this_limit ['单条合同导出'] : null;

		//$service->asc = false;
		$rows = $service->pageBySqlId('select_default');
		$orderDao = new model_projectmanagent_order_order();
		$rows = $orderDao->getInvoiceAndIncome_d($rows,0,$this->service->tbl_name);
		$rows = $service->isGoodsReturn ( $rows);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		foreach($rows as $k => $v){
             $rows[$k]['exportOrder'] = $export;
		   }
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 到款分配使用
	 */
	function c_orderForIncomePj(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->page_d ();
		$orderDao = new model_projectmanagent_order_order();
		$rows = $orderDao->getInvoiceAndIncome_d($rows,0,$this->service->tbl_name);
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
/**
 * 从表选择物料处理配置
 */
     function c_ajaxorder(){
       $isEdit = isset($_GET['isEdit'])?$_GET['isEdit'] : null;
        $configInfo = $this->service->c_configuration( $_GET['id'],$_GET['Num'],$_GET['trId'],$isEdit);
         echo $configInfo[0];
     }

    /**
     * 审批通过后发送邮件并跳回列表
     */
     function c_configOrder(){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		$objId = $folowInfo ['objId'];
       $this->service->configOrder_d($objId);
       $contract = $this->service->get_d ( $objId );
        if ($contract ['ExaStatus'] == "完成") {
          $toorderDao = new model_projectmanagent_borrow_toorder();
          $toorderDao->findLoan($objId,"service");
        }
       echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
     }

/**********************************************************************************************/
/**
 * 验证合同号的唯一性
 */
function c_ajaxCode(){
		$service = $this->service;
		$projectName = isset ( $_GET ['ajaxOrderCode'] ) ? $_GET ['ajaxOrderCode'] : false;
		$orderId = isset ( $_GET ['id'] ) ? $_GET ['id'] : false;
		$projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );
		$isRepeat = $service->isRepeat ( $searchArr, $orderId );

		if ($isRepeat) {
			echo "1";
		} else {
			echo "0";
		}
		 }
function c_ajaxTempCode(){

		$service = $this->service;
		$projectName = isset ( $_GET ['ajaxOrderTempCode'] ) ? $_GET ['ajaxOrderTempCode'] : false;
		$orderId = isset ( $_GET ['id'] ) ? $_GET ['id'] : false;
		$projectNameLS = "LS".$projectName;
		$searchArr = array (
            "ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
         );
		$isRepeat = $service->isRepeat ( $searchArr, $orderId );

		if ($isRepeat) {
			echo "1";
		} else {
			echo "0";
		} }
 	/**
  * ajax 获取从表数据
  */
	function c_ajaxList() {
		$rows = $this->service->get_d ( $_POST ['id'] );
		$rows = util_jsonUtil::encode ( $rows );

		echo $rows;

	}

	/*********************************工程部分合同处理****************************/


	/**
	 * 服务合同导出  （忠武要的）
	 */
	function c_exportServiceExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);


		$stateArr = array ('0' => '未提交', '1' => '审批中', '2' => '执行中', '3' => '已关闭', '4' => '已完成' );

		$this->service->searchArr["ExaStatusArr"] = "完成,变更审批中";
		$this->service->searchArr["isTemp"] = 0;
		$this->service->searchArr["dealStatus"] = 0;
		$rows = $this->service->listBySqlId ( 'select_default' );
		foreach ( $rows as $index => $row ) {
			foreach ( $row as $key => $val ) {
				if ($key == 'state') {
					$rows [$index] [$key] = $stateArr [$val];
				}
			}
		}
		$colIdArr = array("orderCode","orderTempCode","cusName","orderName");
		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip ( $colIdArr );
		foreach ( $rows as $key => $row ) {
			foreach ( $colIdArr as $index => $val ) {
				$colIdArr [$index] = $row [$index];
			}
			array_push ( $dataArr, $colIdArr );
		}
		return model_contract_common_contExcelUtil::exportServiceExcelUtil ( $dataArr );
	}

	/**
	 * 工程部门合同查看列表
	 * createBy Show
	 * createOn 2011-11-30
	 */
	function c_orderForEngineering(){
		$this->display('listforesm');
	}

	/**
	 * 服务合同列表 - 用于工程部查看
	 */
	function c_pageJsonForESM(){
		$service = $this->service;
		$rows = array();
		//省份权限设置
		$provinceArr = array();

		//首先获取对应的办事处权限id
		$otherDataDao = new model_common_otherdatas();
		$limitArr = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID']);
		$sysLimit = $limitArr['办事处'];

		if(strstr($sysLimit,';;')){
			$service->getParam ( $_REQUEST );
			$service->sort = 'c.createTime';
			$rows = $service->page_d ('select_process');
		}else{
			//获取办事处对应省份权限
			$provincesNames = $this->service->getProvinceNames_d($limitArr);
			//如果不存在对应权限，直接筛除列表
			if(!empty($provincesNames)){
				$_REQUEST['orderProvinces'] = $provincesNames;

				$service->getParam ( $_REQUEST );
				$service->sort = 'c.createTime';
				$rows = $service->page_d ('select_process');
			}
		}

		$rows = $this->service->filterWithoutField ( '服务合同金额', $rows, 'list', array ('orderMoney', 'orderTempMoney' ));

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}



	/*********************************工程部分合同处理****************************/
}
?>
