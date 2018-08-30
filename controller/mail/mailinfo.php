<?php
/**
 * 邮寄信息控制层类
 */
class controller_mail_mailinfo extends controller_base_action {

	function __construct() {
		$this->objName = "mailinfo";
		$this->objPath = "mail";
		parent::__construct ();
	}
	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->show->assign ( 'mailApplyId', $_GET ['mailApplyId'] );
		$mailapplyDao = new model_mail_mailapply ();
		$mailapply = $mailapplyDao->get_d ( $_GET ['mailApplyId'] );
		$user = $_SESSION['USERNAME'];
		$this->assign( 'mailMan',$user );
//		echo "<pre>";
//		print_r( $mailapply );
		if ($mailapply ['id']) {
			foreach ( $mailapply as $key => $val ) {
				if ($key == 'mailproducts') {
					$str = $this->showproductslist ( $val );
					$this->show->assign ( 'mailproducts', $str[0] );
					$this->assign( 'rowNum',$str[1] );
				} else {
					$this->show->assign ( $key, $val );
				}
			}
		} else {
			$this->show->assign ( 'mailproducts', "" );
			$this->show->assign ( 'linkman', '' );
			$this->show->assign ( 'tel', '' );
		}

		//设置数据字典
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ),$mailapply['mailType'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * 显示对象分页列表
	 */
	function c_page() {
		$this->display ( 'list' );
	}

	/**
	 * 显示对象分页列表
	 */
	function c_pageByApplyId() {
		$mailApplyId = $_GET['mailApplyId'];
		$this->assign( 'mailApplyId',$mailApplyId );
		$this->display ( 'listByApplyId' );
	}

	/**我的邮寄申请-查看邮寄信息
	*author can
	*2011-4-20
	*/
	function c_toMailInfo() {
		$mailApplyId = $_GET['mailApplyId'];
		$this->assign( 'mailApplyId',$mailApplyId );
		$this->display ( 'apply-list' );
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$mail = $this->service->get_d ( $_GET ['id'] );
		if(!empty($_GET['perm'])){
			foreach ( $mail as $key => $val ) {
				if ($key == 'mailproducts') {
					$str = $this->service->showMailInfo ( $val );
					$this->show->assign ( 'mailproducts', $str[0] );
					$this->assign( 'rowNum',$str[1] );
				} else {
					$this->show->assign ( $key, $val );
				}
			}
			//邮寄方式
			$mailType=$this->getDataNameByCode($mail ['mailType']);
			$this->assign('mailType',$mailType);
			//邮寄状态
			if($mail['mailStatus']==0){
				$mail['mailStatus']="未签收";
			}if($mail['mailStatus']==1){
				$mail['mailStatus']="已签收";
			}
			$this->assign('mailStatus',$mail['mailStatus']);
			$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
		}else{
			foreach ( $mail as $key => $val ) {
				if ($key == 'mailproducts') {
					$str = $this->showproductslist ( $val );
					$this->show->assign ( 'mailproducts', $str[0] );
					$this->assign( 'rowNum',$str[1] );
				} else {
					$this->show->assign ( $key, $val );
				}
			}
			$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ), $mail ['mailType'] );
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		}
	}

	/**
	 * 页面显示动态邮寄申请产品调用方法,返回字符串给页面模板替换，用于修改到货申请
	 */
	function showproductslist($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
				<tr>
					<td>$j</td>
					<td align="center">
						<input type="hidden" id="productId$j" name="mailinfo[productsdetail][$j][productId]" value="$val[productId]"/>
						<input type="hidden" id="productNo$j" name="mailinfo[productsdetail][$j][productNo]" value="$val[productNo]"/>
						<input type="text" id="productName$j" class="txtlong" name="mailinfo[productsdetail][$j][productName]" value="$val[productName]"/>
					</td>
					<td align="center">
						<input type="text" class="txtmiddle" name="mailinfo[productsdetail][$j][mailNum]" value="$val[mailNum]"/>
					</td>
					<td align="center">
						<img src='images/closeDiv.gif' onclick='mydel(this,"productslist")' title='删除行'>
					</td>
				</tr>
EOT;
				$i ++;
			}

		}
		return array( $str,$j );
	}
	/**
	 * 获取分页数据转成Json
	 */
	function c_mylogpageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = true;
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 邮寄确认
	 */
	function c_confirm(){
		if($this->service->confirm_d($_POST['id'])){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}


	/**
	 * 根据邮寄类型和id查看邮寄记录
	 * url中需要传入docId和docType
	 */
	function c_viewByDoc(){
		$obj = $this->service->find ( array( 'docId'=> $_GET['docId'],'docType'=> $_GET['docType']) );
		$thisObj = $this->service->getObjCode($_GET['docType']);
		$this->assignFunc($obj);
		$this->assign('mailTypeCN',$this->getDataNameByCode($obj['mailType']));
		$this->display ( $thisObj.'-view' );
	}


	/***************************发货邮寄部分*****************************/

	/**
	 * 发货邮寄
	 */
	function c_toAddByShip() {
		$service = $this->service;
		$shipInfo = $service->getShipMessage_d( $_GET['shipId'] );
		if($shipInfo['docType'] != 'independent'){
			$mailTo = $service->getMailman($shipInfo);
		}else{
			$mailTo=array();
		}
		foreach ( $shipInfo as $key => $val ){
			$mail = array(
				'docId' => $_GET['shipId'],
				'docCode' => $_GET['shipCode'],
				'docType' => $shipInfo['docType'],
				'linkman' => $shipInfo['linkman'],
				'tel' => $shipInfo['mobil'],
				'mailMan' => $shipInfo['shipman'],
				'mailManId' => $shipInfo['shipmanId'],
				'customerId' => $shipInfo['customerId'],
				'customerName' => $shipInfo['customerName'],
				'companyId' => $shipInfo['companyId'],
				'companyName' => $shipInfo['companyName'],
				'address' => $shipInfo['address'],
				'mailTime' => $shipInfo['shipDate'],
				'mailproducts' => $shipInfo['details'],
				'TO_NAME'=>$mailTo['TO_NAME'],
				'TO_ID'=>$mailTo['TO_ID']
			 );
		}
		foreach( $mail as $key => $val ){
			if ($key == 'mailproducts') {
				$str = $this->service->showproductsEdit ( $val );
				$this->show->assign ( 'mailproducts', $str[0] );
				$this->assign( 'rowNum',$str[1] );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		//设置数据字典
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ) );
		$this->display ( 'addbyship' );
	}

//
//	/**
//	 * 编辑邮寄记录
//	 */
//	function c_shipInit(){
//		$obj = $this->service->get_d ( $_GET ['id'] );
//		$this->assignFunc($obj);
//		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
//			$this->assign('mailTypeCN',$this->getDataNameByCode($obj['mailType']));
//			$this->display ( 'ship-view' );
//		} else {
//			$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ),$obj['mailType'] );
//			$this->display ( 'ship-edit' );
//		}
//	}

	/**
	 * 发货邮寄信息列表
	 */
	 function c_shipList(){
	 	$docType = "YJSQDLX-FHYJ";
	 	$this->assign( 'docType',$docType );
	 	$this->display( 'ship-list' );
	 }

	/**
	 * 发货单邮寄记录列表
	 */
	 function c_listByShip(){
	 	$docType = "YJSQDLX-FHYJ";
	 	$docId = $_GET['docId'];
	 	$this->assign( 'docId',$docId );
	 	$this->assign( 'docType',$docType );
	 	$this->display( 'listbyship' );
	 }

	 /**
	  * 合同邮寄列表页面
	  */
	 function c_listByOrderId(){
	 	$docType = $_GET['type'];
	 	$docId = $_GET['id'];
	 	$this->assign( 'docId',$docId );
	 	$this->assign( 'docType',$docType );
	 	$this->display( 'listbyorder' );
	 }

	/**
	 * 获取分页数据转成Json--合同邮寄列表
	 */
	function c_orderJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$docIdArr = $service->getDocIdByOrder_d($service->searchArr['docId'],$service->searchArr['docType']);
		if($docIdArr['invoiceIds']){
			$this->service->searchArr['invoiceIds']=$docIdArr['invoiceIds'];
		}
		if($docIdArr['outplanIds']){
			$this->service->searchArr['outplanIds']=$docIdArr['outplanIds'];
		}
		$service->asc = true;
		$rows = $service->pageBySqlId("select_order");
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_shipJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = true;
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['sql'] = $service->listSql;
        $_SESSION['shipJsonSql'] = substr_replace($service->listSql, '', strpos($service->listSql,'where'))."where 1=1 ";
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/*****************财务发票邮寄部分*******************/
	/**
	 * 发票邮寄信息列表
	 * by show
	 */
	function c_invoiceList(){
		$thisObj = $this->service->getObjCode($_GET['docType']);
		$this->assign( 'docType' ,$_GET['docType'] );
	 	$this->display( $thisObj.'-list' );
	}

	/**
	 * 邮寄记录新增页面
	 */
	function c_invoiceAdd(){
		$thisObj = $this->service->getObjCode($_GET['docType']);

		$this->assign( 'mailManId',$_SESSION['USER_ID']);
		$this->assign( 'mailMan',$_SESSION['USERNAME']);
		$this->assign( 'docType' ,$_GET['docType'] );
		$this->assign( 'mailTime',day_date );
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ) );
	 	$this->display( $thisObj.'-add' );
	}

	/**
	 * 新增发票邮寄页面
	 */
	function c_addInvoice(){
		$rs = $this->service->addInvoice_d($_POST[$this->objName]);
		if($rs){
			msg('添加成功');
		}else{
			msg('添加失败');
		}
	}

	/**
	 * 新增发货邮寄页面
	 */
	function c_addShip(){
		$rs = $this->service->addShip_d($_POST[$this->objName]);
		if($rs){
			msg('添加成功');
		}else{
			msg('添加失败');
		}
	}


	/**
	 * 初始化对象
	 */
	function c_shipInit() {
		$service=$this->service;
		$mail = $this->service->get_d ( $_GET ['id'] );
		$mailNo = $service->getMailNo($mail);
		$mailEqu = $service->getEqu($mail);
		$mail['mailproducts']=$mailEqu;
//		echo "<pre>";
//		print_R( $mail );
		if(!empty($_GET['perm'])){
			foreach ( $mail as $key => $val ) {
				if ($key == 'mailproducts') {
					$str = $this->service->showMailInfo ( $val );
					$this->show->assign ( 'mailproducts', $str[0] );
					$this->assign( 'rowNum',$str[1] );
				}elseif($key == 'mailStatus'){
					$mailStatus = $mail['mailStatus']==1?'已确定':'未确定';
					$this->assign('mailStatus',$mailStatus);
				} else {
					$this->show->assign ( $key, $val );
				}
			}
			//邮寄方式
			$this->assign( 'mailNoStr',$mailNo );
			$this->assign('mailType',$this->getDataNameByCode($mail['mailType']));
			$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
		}else{
			foreach ( $mail as $key => $val ) {
				if ($key == 'mailproducts') {
					$str = $this->service->showShipEdit ( $val );
					$this->show->assign ( 'mailproducts', $str[0] );
					$this->assign( 'rowNum',$str[1] );
				} else {
					$this->show->assign ( $key, $val );
				}
			}
			$this->assign( 'mailNoStr',$mailNo );
			$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ), $mail ['mailType'] );
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		}
	}

	/**
	 * 发货邮寄修改
	 */
	function c_shipEdit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->shipEdit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

	/**
	 * 编辑邮寄记录
	 */
	function c_invoiceInit(){
		$obj = $this->service->get_d ( $_GET ['id'] );
		$thisObj = $this->service->getObjCode($_GET['docType']);
		$this->assignFunc($obj);
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->assign('mailTypeCN',$this->getDataNameByCode($obj['mailType']));
			$this->display ( $thisObj.'-view' );
		} else {
			$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ),$obj['mailType'] );
			$this->display ( $thisObj.'-edit' );
		}
	}

	/**
	 * 邮寄新增 - 由其他业务下推生成
	 */
	function c_addByPush(){
		$this->permCheck($_GET['docId'],'finance_invoice_invoice');
		$thisObj = $this->service->getObjCode($_GET['docType']);

		$docId = isset($_GET['docId']) ? $_GET['docId'] : null;
		$this->assign( 'docId' , $docId);

		$docCode = isset($_GET['docCode']) ? $_GET['docCode'] : null;
		$this->assign( 'docCode' , $docCode);

        $object = $this->service->getObjInfo_d($docId,$_GET['docType']);
        $object['thisAddress'] = empty($object['linkAddress']) ? $object['unitAddress'] : $object['linkAddress'];
        $this->assignFunc($object);

		$this->assign( 'mailManId',$_SESSION['USER_ID']);
		$this->assign( 'mailMan',$_SESSION['USERNAME']);
		$this->assign( 'docType' ,$_GET['docType'] );
		$this->assign( 'mailTime',day_date );
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ) );
	 	$this->display( $thisObj.'-addbypush' );
	}

	/**
	 * 邮寄费用信息批量导入
	 * 2011年7月30日 13:53:20
	 * zengzx
	 */
	 function c_toFareImport(){
	 	$this->display('excelimport');
	 }
	/*
	 * 上传EXCEL
	 */
	function c_upFareExcel() {
		$objKeyArr = array (
			0 => 'mailNo',
			1 => 'number',
			2 => 'weight',
			3 => 'serviceType',
			4 => 'fare',
			5 => 'anotherfare',
			6 => 'mailMoney',
		); //字段数组
		$resultArr = $this->service->addExecelDatabypro_d ( $objKeyArr );
		$title = '邮寄费用信息导入结果列表';
		$thead = array( '邮寄单号','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

    /**
     * 导出excel界面
     */
    function c_toExportExcel(){
        $colId = isset($_REQUEST['colId'])? $_REQUEST['colId'] : '';
        $colName = isset($_REQUEST['colName'])? $_REQUEST['colName'] : '';
        $searchConditionKey = isset($_REQUEST['searchConditionKey'])? $_REQUEST['searchConditionKey'] : '';
        $searchConditionVal = isset($_REQUEST['searchConditionVal'])? $_REQUEST['searchConditionVal'] : '';
        $docType = isset($_REQUEST['docType'])? $_REQUEST['docType'] : '';
        $this->assign('colId',$colId);
        $this->assign('colName',$colName);
        $this->assign('searchConditionKey',$searchConditionKey);
        $this->assign('searchConditionVal',$searchConditionVal);
        $this->assign('docType',$docType);

        $year = date("Y");
        $yearStr = "";
        while ($year >= 2010) {
            $yearStr .="<option value='$year'>" . $year . "年</option>";
            $year --;
        }
        $this->assign('year',$yearStr);

        $month = date("m");
        $monthStr = '';
        $beginMonth = 12;
        while ($beginMonth > 0) {
            $selected = $beginMonth == $month ? 'selected="selected"' : '';
            $beginMonthVal = ($beginMonth < 10)? '0'.$beginMonth : $beginMonth;
            $monthStr .="<option value='$beginMonthVal' " . $selected . ">" . $beginMonth . "月</option>";
            $beginMonth --;
        }
        $this->assign('month',$monthStr);
        $this->view("toExportExcel");
    }

    /**
     * 导出EXCEL
     */
	function c_exportExcel(){
	    $mailStatusArr = array(
	        "0" => "未确认",
            "1" => "已确认"
        );
        $statusArr = array(
            "0" => "未签收",
            "1" => "已签收"
        );
        $service = $this->service;
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(600);
        $rows = array ();
        $colIdStr = $_GET['colId'];
        $colNameStr = $_GET['colName'];
        $searchConditionKey = isset($_GET['searchConditionKey'])? $_GET['searchConditionKey'] : ''; //普通搜索的Key
        $searchConditionVal = isset($_GET['searchConditionVal'])? $_GET['searchConditionVal'] : ''; //普通搜索的Val

        if( isset($_SESSION['advSql']) ){
            $_REQUEST['advSql'] = $_SESSION['advSql'];
        }

        // 根据与前台一致的语句查询相应的数据
        $service->getParam($_REQUEST);
        $service->asc = true;
        if($searchConditionKey != '') {
            $service->searchArr[$searchConditionKey] = $searchConditionVal;
        }

        // 如果存在缓存语句,则用缓存语句查询,否则有传入参数查询
        if(isset($_SESSION['shipJsonSql']) && $_SESSION['shipJsonSql'] != ''){
            $rows = $service->listBySql($_SESSION['shipJsonSql']);
        }else{
            $rows = $service->list_d ();
        }

        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);

        //匹配导出列
        $dataArr = array ();
        $colIdArr = array_flip($colIdArr);

        foreach ($rows as $key => $row) {
            foreach ($colIdArr as $index => $val) {
                if($index == 'mailStatus'){
                    $colIdArr[$index] = $mailStatusArr[$row[$index]];
                }else if($index == 'status'){
                    $colIdArr[$index] = $statusArr[$row[$index]];
                }else{
                    $colIdArr[$index] = $row[$index];
                }
            }
            array_push($dataArr, $colIdArr);
        }
//        echo "<pre>";print_r($dataArr);exit();
        $mailExcelDao = new model_mail_mailExcelUtil();
        return $mailExcelDao->export2ExcelUtil($colArr, $dataArr);
    }
}
?>