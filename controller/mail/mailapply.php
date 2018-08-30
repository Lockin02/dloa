<?php
/**
 * 邮寄申请控制层类
 */
class controller_mail_mailapply extends controller_base_action {

	function __construct() {
		$this->objName = "mailapply";
		$this->objPath = "mail";
		parent::__construct ();
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		//设置数据字典
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**跳转到我的邮寄申请列表
	*author can
	*2011-4-15
	*/
	function c_toMyApplyList(){
		$this->display('mylist');
	}
	/**跳转到我的邮寄申请单审批tab页
	*author can
	*2011-4-15
	*/
	function c_toMailApplyAudit(){
		$this->display('audit-tab');
	}

	/**待审批邮寄申请列表
	*author can
	*2011-4-15
	*/
	function c_toMyAuditList(){
		$this->display('auditno-list');
	}

	/**已审批邮寄申请列表
	*author can
	*2011-4-15
	*/
	function c_toMyAuditYes(){
		$this->display('audityes-list');
	}


	/**
	 * 邮寄任务
	 */
	function c_mailTask(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$deptName=$this->service->getDeptByUserId($_SESSION['USER_ID']);
		if($deptName=="财务部"){
			$service->searchArr['applyType']="invoiceapply";
		}else{
			$service->searchArr['applyTypes']="invoiceapply";
		}
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
//		$service->searchArr=array("outApplyExaStatus"=>AUDITED);

		//$service->asc = false;
		$rows = $service->pageBySqlId ('select_mails');
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**我的邮寄申请列表
	*author can
	*2011-4-15
	*/
	function c_myApplyListJson(){
		$service=$this->service;
		$service->getParam($_POST);
		$service->asc = true;
		$service->searchArr['createId']=$_SESSION['USER_ID'];
		$rows=$service->pageBySqlId("select_mails");
		$arr = array ();
        $arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**待审批列表Json
	*author can
	*2011-4-15
	*/
	function c_myAuditPj() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 0;
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId ('sql_examine');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**已审批列表Json
	*author can
	*2011-4-15
	*/
	function c_pageJsonAuditYes(){
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr['Flag'] = 1;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('sql_audited');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 初始话编辑页面
	 */
	function c_init() {
		$mailApply = $this->service->get_d ( $_GET ['id'] );
//		echo "<pre>";
//		print_r ( $mailApply );
		foreach ( $mailApply as $key => $val ) {
			if ($key == 'mailproducts') {
				$str = $this->showproductslist ( $val );
				$this->show->assign ( 'mailproducts', $str[0] );
				$this->assign( 'rowNum',$str[1] );
			} else {
				$this->show->assign ( $key, $val );
			}
		}
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ), $mailApply ['mailType'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}

	/**
	 * 初始化对象-查看页面
	 */
	function c_readInfo() {
		$mailApply = $this->service->get_d ( $_GET ['id'] );
		$actType=isset($_GET['actType'])?$_GET['actType']:null;
		$this->show->assign("actType",$actType);//操作页面(一般的查看页面、内嵌在审批表单中)
		//print_r ( $mailApply );
		foreach ( $mailApply as $key => $val ) {
			if( $key == 'mailType' ){
				$val = $this->getDataNameByCode( $val );
			}
			$this->assign ( $key, $val );
			if( $key == 'mailproducts' ){
				$str = $this->showproductslistView( $val );
				$this->assign( 'mailproducts',$str );
			}
		}
		$this->showDatadicts ( array ('mailTypeList' => 'YJFS' ), $mailApply [mailType] );
		$this->display ( 'read' );
	}

	/**
	 * 邮寄任务:只显示发货申请单审批通过的邮寄任务或者没关联发货申请单的邮寄任务
	 */
	function c_page() {
		$this->display( 'list' );
	}

	/**
	 * 邮寄记录列表
	 */
	function c_mailRecordsList() {
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		//分页
		$showpage = new includes_class_page ();
		$showpage->show_page ( array ('total' => $service->count ) );

		$this->show->assign ( 'list', $service->showListRecords ( $rows, $showpage ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listrecords' );
	}

	/**
	 * 页面显示动态邮寄申请产品调用方法,返回字符串给页面模板替换，用于修改到货申请
	 */
	function showproductslist($rows) {
		$str = ""; //返回的模板字符串
		if (is_array ( $rows )) {
			$j = 0; //列表记录序号
			foreach ( $rows as $key => $val ) {
				$j++;
				$str .= <<<EOT
						<tr><td align="center">$j</td>
				<td align="center">
					<input type="text" id="productName$j" class="txtlong" name="mailapply[productsdetail][$j][productName]" value="$val[productName]"/>
				</td>
				<td align="center"><input type="text" class="txtmiddle"
					name="mailapply[productsdetail][$j][mailNum]" value="$val[mailNum]"/></td>
				<td align="center"><img
					src='images/closeDiv.gif' onclick="mydel(this,'productslist')"
					title='删除行'></td>
			</tr>
EOT;
			}

		}
		return array( $str,$j );
	}



	/**
	 * 页面显示邮寄申请产品调用方法,返回字符串给页面模板替换，用于查看邮寄申请
	 */
	function showproductslistView($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
						<tr>
				<td align="center">$j</td>
				<td align="center">
					$val[productName]
				</td>
				<td align="center">
					$val[mailNum]
				</td>
			</tr>
EOT;
				$i ++;
			}

		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}


}
?>