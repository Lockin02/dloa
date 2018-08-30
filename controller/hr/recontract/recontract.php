<?php


/**
 * @author Administrator
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:合同信息控制层
 */
class controller_hr_recontract_recontract extends controller_base_action {

	function __construct() {
		$this->objName = "recontract";
		$this->objPath = "hr_recontract";
		parent :: __construct();
		$this->service->buildBaseDate();
	}

	/*
	 * 跳转到合同信息列表
	 */
	function c_page() {
		$this->view('list');
	}
	function c_getYears() {
		for($i=(date('Y')-2012);$i>=0;$i--){
		   $data[]=array('text'=>(2012+$i).'年','value'=>(2012+$i));   	
		}
		echo json_encode(un_iconv($data));
	}
    /*	
     * 获取分页数据转成Json
	*/
	function c_pageJsons() {
		$service = $this->service;
		$month=$_GET['month'] ? $_GET['month'] : $_POST['month'];
		$year=$_GET['year'] ? $_GET['year'] : $_POST['year'];
		$service->getParam($_REQUEST);
		if($month&&$year){
			$this->service->searchArr = array (
			"yearMonth" => ($year.'-'.$month)
		  );
		}
		
		$this->service->sort = 'c.statusId,c.createTime';
		$service->asc = false;
		$rows = $service->page_d();
		
		$remarkI=$this->service->getRemarks();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		if($rows&&is_array($rows)){
		  foreach($rows as $key=>$val){
			if($val['id']){
				$rows[$key]['different']=$this->service->getDifferent($val['id']);
			}
			if($rows[$key]['aisFlag']=='2'){
				$rows[$key]['aconStateName']='';
				$rows[$key]['aconNumName']='';
			}
			if($rows[$key]['pisFlag']=='2'){
				$rows[$key]['pconNumName']='';
				$rows[$key]['pconStateName']='';
			}
			if($rows[$key]['isFlag']=='2'){
				$rows[$key]['beginDate']='';
				$rows[$key]['pconStateName']='';
				$rows[$key]['closeDate']='';
				$rows[$key]['conNumName']='';
				$rows[$key]['conStateName']='';
				$rows[$key]['signCompanyName']='';
			}
			
			$rows[$key]['remark']=$remarkI[$key];
			
		}	
		}
		
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 * 跳转到新增仲裁信息页面
	 */
	function c_viewArbitra() {
		$this->permCheck(); //安全校验
		
		$pid=(int)$_GET['id'] ? $_GET['id'] : $_POST['id'];
		if(!$pid){
			$pid='';
		}
		$objs=$this->service->getApprovealInfo($pid);
		$obj=$objs[0];
		
		if(!$obj&&!is_array($obj)){
			$obj = $this->service->get_d($pid);
			$obj['beginDate']=date('Y-m-d',(strtotime ( "+1 days ", strtotime ( $obj['ocloseDate'] ) )));
			$obj['closeDate']=date('Y-m-d',(strtotime ( "-1 days ", strtotime ( $obj['closeDate'] ) )));
			$obj['conNum']=$obj['oconNum'];
			$obj['conNumName']=$obj['oconNumName'];
			$obj['conState']=$obj['oconState'];
			$obj['conStateName']=$obj['oconStateName'];
			$this->assign('appComit', '<input type="hidden" name="recontractApproval[statusId]"  value="2"><input  type="submit" class="txt_btn_a" value=" 提交审批" onclick="toApp();"/>');
		}else{
			if($obj['statusId']<3){
				$statusId='2';
			}else if($obj['statusId']=='4'){
				$statusId='4';
			}else if($obj['statusId']=='6'){
				$statusId='6';
			}else{
				$statusId=$obj['statusId']+1;
			}
			$this->assign('appComit', '<input type="hidden" name="recontractApproval[statusId]"  value="'.$statusId.'">');
		}
		$obj['id']=$pid;
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('isShow',$obj['statusId']<3?'none':'');
		$this->view('addArbitra');
	}
/**
	 * 跳转到新增仲裁信息页面
	 */
	function c_editArbitra() {
		$pid=(int)$_GET['id'] ? $_GET['id'] : $_POST['id'];
		if(!$pid){
			$pid='';
		}
		$objs=$this->service->getApprovealInfoTop($pid);
		$obj=$objs[0];
		
		if($obj&&is_array($obj)){
			$this->assign('appComit', '<input type="hidden" name="recontractApproval[statusId]"  value="2"><input  type="submit" class="txt_btn_a" value=" 提交审批" onclick="toApp();"/>');
		}
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('isShow',$obj['statusId']<3?'none':'');
		$this->view('editArbitra');
	}



	/**
	* 跳转到审批页面
	*/
	function c_getUserInfo() {
		$this->permCheck(); //安全校验
		$this->service->searchArr = array (
			"user_id" => ($_GET['userAccount'] ? $_GET['userAccount'] : $_POST['userAccount'])
		);
		$this->service->sort = 'a.USER_ID';
		$obj = $this->service->listBySqlId("select_userinfo");
		foreach ($obj as $key => $val) {
			$arr[$key] = $val;
		}
		echo util_jsonUtil :: encode($arr);
	}
	

	/**
	 * 跳转到新增仲裁信息页面
	 */
	function c_toApproval() {
		$this->permCheck(); //安全校验
		$pid=(int)$_GET['id'] ? $_GET['id'] : $_POST['id'];
		$fspid=$_GET['fspid'] ? $_GET['fspid'] : $_POST['fspid'];
		if(!$pid){
			$pid='';
		}
		$this->service->searchArr = array (
			"sltArbitra_id" => $pid
		);
		$this->service->sort = 'a.id';
		$this->service->asc = false;
		$obj = $this->service->listBySqlId("select_Arbitra");
		$i=0;
		foreach ($obj as $key => $val) {
			if($val['isFlag']=='2'){
				$val[conStateName]='';
				$val[conNumName]='';
				$val[beginDate]='';
				$val[closeDate]='';
			}
			if($val['mark']=='HR'){
				$val['stepName']='人事组';
			}elseif($val['mark']=='SP'&&$val['Item']){
				$val['stepName']=$val['Item'];
			}elseif($val['mark']=='ST'){
				$val['stepName']='员工确认';
			}
			$i++;
			$str .=<<<EOT
					 	<tr>
						<td >$val[stepName]</td>
						<td  >$val[createName]</td>
						<td  >$val[isFlagName]</td>
						<td  >$val[conNumName]</td>
						<td >$val[conStateName]</td>
						<td  >$val[beginDate]</td>
						<td  >$val[closeDate]</td>
						<td  >$val[createTime]</td>
						<td  >$val[conContent]</td>
				</tr>
EOT;
		}
		
       $objs=$this->service->getApprovealInfo($pid);
		foreach ((array) $objs[0] as $key => $val) {
			
			$this->assign($key, $val);
		}
        $this->service->searchArr = array (
			"sltAppInfo_id" => $pid,
			"sltFsp_id" => $fspid
		);
		$this->service->sort = 'a.id';
		$this->service->asc = false;
		$appInfo = $this->service->listBySqlId("select_AppInfo");
		foreach ((array) $appInfo[0] as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('pid', $pid);
		$this->assign('applist', $str);
		$this->view('addApproval');
	}


	/**
	 * 跳转到新增仲裁信息页面
	 */
	function c_viewApproval() {
		$this->permCheck(); //安全校验
		$pid=(int)$_GET['id'] ? $_GET['id'] : $_POST['id'];
		$fspid=$_GET['fspid'] ? $_GET['fspid'] : $_POST['fspid'];
		if(!$pid){
			$pid='';
		}
		$this->service->searchArr = array (
			"sltArbitra_id" => ($pid)
		);
		$this->service->sort = 'a.id';
		$this->service->asc = false;
		$obj = $this->service->listBySqlId("select_Arbitra");
		$i=0;
		if($obj&&is_array($obj)){
		foreach ($obj as $key => $val) {
			if($val['isFlag']=='2'){
				$val[conStateName]='';
				$val[conNumName]='';
				$val[beginDate]='';
				$val[closeDate]='';
			}
			if($val['mark']=='HR'){
				$val['stepName']='人事组';
			}elseif($val['mark']=='SP'&&$val['Item']){
				$val['stepName']=$val['Item'];
			}elseif($val['mark']=='ST'){
				$val['stepName']='员工确认';
			}
			$i++;
			$str .=<<<EOT
					 	<tr>
						<td >$val[stepName]</td>
						<td  >$val[createName]</td>
						<td  >$val[isFlagName]</td>
						<td  >$val[conNumName]</td>
						<td >$val[conStateName]</td>
						<td  >$val[beginDate]</td>
						<td  >$val[closeDate]</td>
						<td  >$val[createTime]</td>
						<td  >$val[conContent]</td>
				</tr>
EOT;
		}
			
		}
		
       $objs=$this->service->getApprovealInfo($pid);
		foreach ((array) $objs[0] as $key => $val) {
			$this->assign($key, $val);
		}
        $this->service->searchArr = array (
			"sltAppInfo_id" => $pid,
			"sltFsp_id" => $fspid
		);
		$this->service->sort = 'a.id';
		$this->service->asc = false;
		$appInfo = $this->service->listBySqlId("select_AppInfo");
		foreach ((array) $appInfo[0] as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('pid', $pid);
		$this->assign('applist', $str);
		$this->view('appDetails');
	}
	
		
	/**
	* 获取分页数据转成Json
	*/
	function c_toParentWindowsClose() {
	   echo "<script language=javascript>self.parent.location.reload();</script>";
	}
	
	/**
	 * 跳转到新增仲裁信息页面
	 */
	function c_InformStaff() {
		$this->permCheck(); //安全校验
		$pid=(int)$_GET['id'] ? $_GET['id'] : $_POST['id'];
		if(!$pid){
			$pid='';
		}
       $objs=$this->service->getApprovealHR2STInfo($pid);
		foreach ((array) $objs[0] as $key => $val) {
			$this->assign($key, $val);
		}
		/*
		if($objs[0]['conState']=='EMETH-01'){
			if($objs[0]['companyId']=='dl'){
				$signCompany='HR-DL';
			}elseif($objs[0]['companyId']=='sy'){
				$signCompany='HR-SY';
			}elseif($objs[0]['companyId']=='br'){
				$signCompany='HR-BR';
			}elseif($objs[0]['companyId']=='bx'){
				$signCompany='HR-BX';
			}
		}elseif($objs[0]['conState']=='EMETH-02'){
			$signCompany='HR-ZY';
		}elseif($objs[0]['conState']=='EMETH-03'){
			if($objs[0]['companyId']=='dl'){
				$signCompany='HR-DL';
			}elseif($objs[0]['companyId']=='sy'){
				$signCompany='HR-SY';
			}elseif($objs[0]['companyId']=='br'){
				$signCompany='HR-BR';
			}elseif($objs[0]['companyId']=='bx'){
				$signCompany='HR-BX';
			}
		}
		*/
		$datadictDao = new model_system_datadict_datadict();
			if($objs[0]['sysCompanyId']){
			  $signCompanyName = $datadictDao->getDataNameByCode($objs[0]['sysCompanyId']);
			}
		$this->assign('signCompanyName', $signCompanyName);
		$this->assign('signCompany', $objs[0]['sysCompanyId']);
		$this->assign('pid', $pid);
		$this->view('addInformStaff');
	}
	/*
	 * 跳转到新增仲裁信息页面
	 */
	function c_detialInformStaff() {
		$this->permCheck(); //安全校验
		$pid=(int)$_GET['id'] ? $_GET['id'] : $_POST['id'];
		if($pid){
           $objs=$this->service->getApprovealSTInfo($pid);
			foreach ((array) $objs[0] as $key => $val) {
				$this->assign($key, $val);
			}
			if($objs[0]['conState']=='EMETH-01'){
			if($objs[0]['companyId']=='dl'){
				$signCompany='HR-DL';
			}elseif($objs[0]['companyId']=='sy'){
				$signCompany='HR-SY';
			}elseif($objs[0]['companyId']=='br'){
				$signCompany='HR-BR';
			}elseif($objs[0]['companyId']=='bx'){
				$signCompany='HR-BX';
			}
		}elseif($objs[0]['conState']=='EMETH-02'){
			$signCompany='HR-ZY';
		}elseif($objs[0]['conState']=='EMETH-03'){
			if($objs[0]['companyId']=='dl'){
				$signCompany='HR-DL';
			}elseif($objs[0]['companyId']=='sy'){
				$signCompany='HR-SY';
			}elseif($objs[0]['companyId']=='br'){
				$signCompany='HR-BR';
			}elseif($objs[0]['companyId']=='bx'){
				$signCompany='HR-BX';
			}
		}
			$this->assign('checked1', '');
			$this->assign('checked2', '');
			if($objs[0]['isFlag']=='1')
			{
				$this->assign('checked1', 'checked');
				$this->assign('disply', '');
			}else{
				$this->assign('checked2', 'checked');
				$this->assign('disply', 'none');
			}
			$this->assign('signCompany', $objs[0]['sysCompanyId']);
			$this->assign('pid', $pid);
			$this->view('detialInformStaff');
		}else{
			showmsg('操作失败', 'self.parent.location.reload();', 'button');
		}
       
	}
	
	
	/**
	* 获取分页数据转成Json
	*/
	function c_pageJsonsd() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = false;
		$rows = $service->page_d();
		$rows = $service->getPayInfo_d($rows);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 基础信息Tab页列表
	 */
	function c_tabList() {
		$this->assign("userId", $_GET['userAccount']);
		$this->view('tablist');
	}

	/**
	 * 跳转到新增合同信息页面
	 */
	function c_toAdd() {
		$this->assign("recorderName", $_SESSION['USERNAME']);
		$this->assign("recorderId", $_SESSION['USER_ID']);
		$this->assign("recordDate", date("Y-m-d"));
		$this->view('add');
	}

	/**
	 * 跳转到查看合同信息页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("file", $this->service->getFilesByObjId($_GET['id'], false));
		$this->view('view');
	}

	/**
	 * 跳转到新增合同信息页面(入职通知入口)
	 */
	function c_toAddByExternal() {
		$this->assign("entryId", $_GET['entryId']);
		$this->assign("jobName", $_GET['jobName']);
		$this->assign("jobId", $_GET['hrJobId']);
		$this->assign("recorderName", $_SESSION['USERNAME']);
		$this->assign("recorderId", $_SESSION['USER_ID']);
		$this->assign("recordDate", date("Y-m-d"));
		$this->view('external-add');
	}

	/**
	 * 跳转到编辑合同信息页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array (
			'conType' => 'HRHTLX'
		), $obj['conType']);
		$this->showDatadicts(array (
			'conState' => 'HRHTZT'
		), $obj['conState']);
		$this->showDatadicts(array (
			'conNum' => 'HRHTCS'
		), $obj['conNum']);
		$this->view('edit');
	}

	  /* 合同信息列表 备注
      */
    function c_listRemark(){
    	$this->assign("cId" , $_GET['id']);
        $this->view("listremark");
    }
    
	 function c_listremarkAdd() {
		$rows = $_POST['objInfo'];
        $rows['createName'] = $_SESSION ['USERNAME'];
        $rows['createId'] = $_SESSION ['USER_ID'];
        $rows['createTime'] = date ( "Y-m-d H:i:s" );
	    $id = $this->service->listremarkAdd_d($rows);
	  if($id){
	  	 msg ( '添加成功！' );
	  }else{
	  	
	  	msg ( '添加失败！' );
	  }
	}

	//获取数据
	function c_getRemarkInfo(){
        $contractId = $_POST['contractId'];
        $info = $this->service->getRemarkInfo_d($contractId);
//        echo $info;
        echo util_jsonUtil :: iconvGB2UTF($info);

	}
	/**
	 *跳转到excel上传页面
	 */
	function c_toExcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->display('importexcel');
	}
	/**
	 * 单合同导出
	 * @author zengzx
	 */
	function c_exportExcel(){
		$service = $this->service;
		$month=$_GET['month'] ? $_GET['month'] : $_POST['month'];
		$year=$_GET['year'] ? $_GET['year'] : $_POST['year'];
		$service->getParam($_REQUEST);
		if($month&&$year){
			$this->service->searchArr = array (
			"yearMonth" => ($year.'-'.$month)
		  );
		}
		$this->service->sort = 'c.statusId,c.createTime';
		$service->asc = false;
	    $rows = $service->list_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$title = '合同续签数据';
		$Title = array (array ($title));
		$ExcelData[] = array ("员工基本信息","","","","","","","","状态","纸质合同","上次合同签订概况","","","","本次合同签订公司确认结果","","","本次合同签订员工确认结果"," "," ","本次合同签订最终续签结果","","","","","","");
		$ExcelData[] = array ("员工号","姓名","公司","直属部门","二级部门","三级部门","职位","入职日期","","","开始时间","结束时间","用工年限","用工方式","是否同意续签",
							  "用工方式","用工年限","是否同意续签","用工年限","用工方式","是否同意续签","开始时间","结束时间","用工年限","用工方式","签订公司","接收地址");
		foreach((array)$rows as  $key =>$val){
			
			if($val['statusId']==1)
				{
				  $statusName= '未处理';
				}else if($val['statusId']==2)
				{
					$statusName= '待提交';
				}else if($val['statusId']==3)
				{
					$statusName= '审批中';
				}else if($val['statusId']==4)
				{
					$statusName= '待通知员工';
				}else if($val['statusId']==5)
				{
					$statusName= '待员工确认';
				}else if($val['statusId']==6)
				{
					$statusName= '待HR确认';
				}else if($val['statusId']==7)
				{
					$statusName= '待签订纸质合同';
				}else if($val['statusId']==8)
				{
					$statusName= '合同完成';
				}else if($val['statusId']==9)
				{
					$statusName= '合同关闭';
				}
			if($val['aisFlag']=='2'){
				$val['aconStateName']='';
				$val['aconNumName']='';
			}
			if($val['pisFlag']=='2'){
				$val['pconNumName']='';
				$val['pconStateName']='';
			}
			if($val['isFlag']=='2'){
				$val['pconNumName']='';
				$val['pconStateName']='';
			}
			if($val['isPaperContract']==2){
				$paperContract='已签';
			}else{
				$paperContract='未签';
			}	
		   $ExcelData[] = array ($val['userNo'],$val['userName'],$val['companyName'],$val['deptNameB'],$val['deptNameS'],$val['deptNameT'],$val['jobName'],$val['comeinDate'],$statusName,$paperContract,
		                         $val['obeginDate'],$val['ocloseDate'],$val['oconNumName'],$val['oconStateName'],$val['aisFlagName'],$val['aconStateName'],$val['aconNumName'],
							     $val['pisFlagName'],$val['pconNumName'],$val['pconStateName'],$val['isFlagName'],$val['beginDate'],$val['closeDate'],$val['conNumName'],$val['conStateName'],$val['signCompanyName'],$val['repaAddress']);
		
		}
		$xls = new includes_class_excel ( $title . '.xls' );
		$xls -> SetTitle ( array ( $title) , $Title );
		$xls -> SetContent (array ($ExcelData));
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'A1:AA1' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'A2:H2' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'I2:I3' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'J2:J3' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'K2:N2' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'O2:Q2' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'R2:T2' );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'U2:AA2' );
		$styleArray = array ( 
								'borders' => array ( 
													'allborders' => array ( 
																			'style' => PHPExcel_Style_Border :: BORDER_THIN , 
																			'color' => array ( 
																							'argb' => '00000000' 
																			) 
													) 
								) 
			);
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A2:AA3' ) -> getFont ( ) -> setBold ( true );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:AA500' ) -> getAlignment ( ) -> setHorizontal ( PHPExcel_Style_Alignment :: HORIZONTAL_CENTER );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:AA500') -> applyFromArray ( $styleArray );
		$columnData=array('A','B','C','D','E','F','G','H','I','J','K','M','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA');	
		foreach($columnData as $key =>$val){
		  $xls -> objActSheet[ 0 ] -> getColumnDimension ( $val ) -> setWidth ( 15 );
		}
		$xls -> OutPut ( );
		
	}
	function c_importTplExContent(){
       if($this->model_importTplExContent()==2){
       	  showmsg ( '导入成功！', 'parent.CloseOpen();', 'button' );
       }else{
       	  showmsg ( '导入成功失败！', 'parent.CloseOpen();', 'button' );
       }
	}
}
?>