<?php
/**
 * 采购申请单基本信息 control
 */

 class controller_purchase_apply_applybasic extends controller_base_action {

 	function __construct() {
		$this->objName = "applybasic";
		$this->objPath = "purchase_apply";
		parent :: __construct();
	}

/*****************************************显示分割线********************************************/

	/**
	 * 新建采购申请单
	 */
	function c_add(){
		$approval = isset( $_POST['approval'] )?$_POST['approval']:0;
		$object = $this->service->add_d( $_POST ["basic"] );
		if ($object) {
			if($approval=="1"){
				msgGo("保存成功","controller/purchase/apply/ewf_index.php?actTo=ewfSelect&billId=" . $object . "&examCode=oa_purch_apply_basic&formName=采购申请单审批&go=2");
			}else{
				msgBack2("保存成功！");
			}
		}else{
			msgBack2("添加失败！");
		}
	}
/*****************************************显示分割线********************************************/

	/**
	 * 我的采购申请单
	 */
//	function c_myList(){
//		$clickNumb=isset($_GET['clickNumb'])?$_GET['clickNumb']:"1";
//		$arrayPanel = array("numb"=>5,
//								"clickNumb"=> $clickNumb,
//								"name1"=>"执行中",
//								"title1"=>"执行中采购申请单",
//								"url1"=>"?model=purchase_apply_applybasic&action=myList&clickNumb=1",
//								"name2"=>"待提交审批",
//								"title2"=>"待提交审批采购申请单",
//								"url2"=>"?model=purchase_apply_applybasic&action=myList&clickNumb=2",
//								"name3"=>"审批中",
//								"title3"=>"审批中采购申请单",
//								"url3"=>"?model=purchase_apply_applybasic&action=myList&clickNumb=3",
//								"name4"=>"已关闭",
//								"title4"=>"已关闭采购申请单",
//								"url4"=>"?model=purchase_apply_applybasic&action=myList&clickNumb=4",
//								"name5"=>"我的变更申请",
//								"title5"=>"我的变更申请",
//								"url5"=>"?model=purchase_apply_applybasic&action=myList&clickNumb=5"
//							);
//		$topPlan = parent::topPlan($arrayPanel);
//		$this->show->assign('topPlan', $topPlan);
//
//		$changeController = new controller_purchase_apply_applychange();
//		$changeController->show->assign('topPlan', $topPlan);
//
//		switch ($clickNumb) {
//			case 1:
//				$this->myExecutionList($clickNumb);
//				break;
//			case 2:
//				$this->myWaitList($clickNumb);
//				break;
//			case 3:
//				$this->myApprovalList($clickNumb);
//				break;
//			case 4:
//				$this->myCloseList($clickNumb);
//				break;
//			case 5:
//				$changeController->myList($clickNumb);
//				break;
//			default:
//				showmsg('错误','temp','button');
//				break;
//		}
//	}

	/*
	 * @desription 我的采购申请--Tab页
	 * @param tags
	 * @author qian
	 * @date 2010-12-29 下午03:40:56
	 */
	function c_myList () {
		$this->show->display($this->objPath . '_' . $this->objName . '-tab-myapply');
	}

/*****************************************显示分割线********************************************/

	/**
	 * 我的执行中采购申请单
	 */
	function c_myExecutionList($clickNumb){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		//"createName" => $_SESSION['USERNAME'],
		$searchArr = array (
			"createId" => $_SESSION['USER_ID'],
			//"state" => $this->service->stateToSta("execute"),
			"stateArr" =>$this->service->stateToSta("execute").",".$this->service->stateToSta("wite"),
//			"isUse" => "1"
		);
		if($applyNumb!=""){
			$searchArr["seachApplyNumb"] = $applyNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		//分页
		$this->pageShowAssign();
//		$showpage->show_page(array (
//			'total' => $service->count,
//			'perpage' => pagenum
//		));
		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showMyExecutionList_s($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-my-execution');
		unset($this->show);
		unset($service);
	}

	/**
	 * 我在执行的显示列表
	 */
	function showMyExecutionList_s($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = (($i%2)==0)?"tr_even":"tr_odd";
				$str .=<<<EOT
	<tr class="TableLine $iClass">
		<td align="center" >
			<p class="childImg">
            <image src="images/expanded.gif" />$i
        	</p>
        </td>
        <td  >
            <p class="checkChildAll"><input type="checkbox"> $val[applyNumb] </p>
        </td>
        <td  >
            $val[dateHope]
        </td>
        <td  >
            $val[suppName]
        </td>
        <td  >
            $val[stateCName]
        </td>
        <td width="45%" class="tdChange" >
			<table width="100%"  class="shrinkTable">
EOT;
				$amountNot = 0;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$amountNot += $chdVal['amountNotIssued'];
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
	        				<input type="checkbox" class="checkChild">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="13%">
				            $chdVal[amountAll]
				        </td>
				        <td width="13%">
				          	$chdVal[amountNotIssued]
				        </td>
				        <td width="13%">
				            $chdVal[applyPrice]
				        </td>
	        		</tr>
EOT;
				}
				if( $val['state'] == $this->service->stateToSta("wite") ){
					$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开本设备具体信息></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]','$val[applyNumb]')">
			    <option value="value1">&nbsp;&nbsp;请选择操作&nbsp;&nbsp;</option>
			    <option value="execute">执行申请单</option>
			    <option value="read">查看</option>
			    <option value="change">变更申请</option>
			    <option value="readExa">查看审批</option>
			    <option value="del">关闭</option>
			  </select>
		</td>
    </tr>
EOT;
				}else{

					if($amountNot <= 0 ){
	        		$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开本设备具体信息></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]','$val[applyNumb]')">
			    <option value="value1">&nbsp;&nbsp;请选择操作&nbsp;&nbsp;</option>
			    <option value="pay">填写付款申请单</option>
			    <option value="read">查看</option>
			    <!--option value="value5">变更申请</option-->
			    <option value="ininvoice">录入发票</option>
			    <option value="readExa">查看审批</option>
			    <option value="end">完成</option>
			    <!--option value="del">关闭</option-->
			  </select>
		</td>
    </tr>
EOT;
					}else{
	        		$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开本设备具体信息></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]','$val[applyNumb]')">
			    <option value="value1">&nbsp;&nbsp;请选择操作&nbsp;&nbsp;</option>
			    <option value="arival">填写采购到货单</option>
			    <option value="pay">填写付款申请单</option>
			    <option value="read">查看</option>
			    <!--option value="value5">变更申请</option-->
			    <option value="ininvoice">录入发票</option>
			    <option value="readExa">查看审批</option>
			    <option value="end">完成</option>
			    <!--option value="del">关闭</option-->
			  </select>
		</td>
    </tr>
EOT;

					}
				}
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

	/**
	 * 执行申请单
	 */
	function c_execute(){
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		if( $this->service->execute_d($id) ){
			msgGo("执行成功");
		}else{
			msgGo("执行失败！！可能是服务器错误，请稍后再试");
		}

	}

/*****************************************显示分割线********************************************/

	/**
	 * 我的待提交审批采购申请单
	 */
	function c_myWaitList($clickNumb){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		$searchArr = array (
			"createId" => $_SESSION['USER_ID'],
			"stateArr" => $this->service->stateToSta("save").",".$this->service->stateToSta("fightback"),
			"isUse" => '1'
		);
		if($applyNumb!=""){
			$searchArr['seachApplyNumb'] = $applyNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		//分页
		$this->pageShowAssign();
//		$showpage->show_page(array (
//			'total' => $service->count,
//			'perpage' => pagenum
//		));
		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showMyWaitList($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-my-wait');
		unset($this->show);
		unset($service);
	}

	/**
	 * @exclude 我在待审批的显示列表
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-11 上午11:21:03
	 */
	function showMyWaitList($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = (($i%2)==0)?"tr_even":"tr_odd";
				$str .=<<<EOT
	<tr class="TableLine $iClass">
		<td align="center" >
			<p class="childImg">
            <image src="images/expanded.gif" />$i
        	</p>
        </td>
        <td  >
            $val[applyNumb]
        </td>
        <td  >
            $val[dateHope]
        </td>
        <td  >
            $val[suppName]
        </td>
        <td  >
            $val[stateCName]
        </td>
        <td width="45%" class="tdChange" >
			<table width="100%"  class="shrinkTable">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="13%">
				            $chdVal[amountAll]
				        </td>
				        <td width="13%">
				          	$chdVal[amountNotIssued]
				        </td>
				        <td width="13%">
				            $chdVal[applyPrice]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开本设备具体信息></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]')">
			    <option value="value1">请选择操作</option>
			    <option value="read">查看</option>
EOT;
				if($val["ExaStatus"]=="打回"){
					$str.="<option value='readExa'>查看审批</option>";
				}else if($val["ExaStatus"]=="保存"){
					$str.="<option value='subExa'>提交审批</option>";
				}
				$str.=<<<EOT
			    <option value="edit">修改</option>
			    <option value="del">删除</option>
			  </select>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************显示分割线********************************************/

	/**
	 * 我的审批中的采购申请单
	 */
	function c_myApprovalList($clickNumb){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		$searchArr = array (
			"seachApplyNumb" => $applyNumb,
			"createId" => $_SESSION['USER_ID'],
			"state" => $this->service->stateToSta("approval"),
//			"isUse" => "1"
		);
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		//分页
		$this->pageShowAssign();
//		$showpage->show_page(array (
//			'total' => $service->count,
//			'perpage' => pagenum
//		));
		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showMyApprovalList($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-my-approval');
		unset($this->show);
		unset($service);
	}

	/**
	 * 我在审批中的显示列表
	 */
	function showMyApprovalList($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = (($i%2)==0)?"tr_even":"tr_odd";
				$str .=<<<EOT
	<tr class="TableLine $iClass">
		<td align="center" >
			<p class="childImg">
            <image src="images/expanded.gif" />$i
        	</p>
        </td>
        <td  >
            $val[applyNumb]
        </td>
        <td  >
            $val[dateHope]
        </td>
        <td  >
            $val[suppName]
        </td>
        <td  >
            $val[stateCName]
        </td>

        <td width="45%" class="tdChange" >
			<table width="100%"  class="shrinkTable">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="13%">
				            $chdVal[amountAll]
				        </td>
				        <td width="13%">
				          	$chdVal[amountNotIssued]
				        </td>
				        <td width="13%">
				            $chdVal[applyPrice]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开本设备具体信息></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]')">
			    <option value="value1">请选择操作</option>
			    <option value="readExa">查看审批</option>
			    <option value="read">查看</option>
			  </select>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************显示分割线********************************************/

	/**
	 * 我的关闭的采购申请单
	 */
	function c_myCloseList($clickNumb){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		$searchArr = array (
			"seachApplyNumb" => $applyNumb,
			"createId" => $_SESSION['USER_ID'],
			"stateArr" => $this->service->stateToSta("close").",".$this->service->stateToSta("end"),
//			"isUse" => "1"
		);
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		//分页
		$this->pageShowAssign();
//		$showpage->show_page(array (
//			'total' => $service->count,
//			'perpage' => pagenum
//		));
		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showMyCloseList($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-my-close');
		unset($this->show);
		unset($service);
	}

	/**
	 * 我在关闭的显示列表
	 */
	function showMyCloseList($rows, $showpage){
				$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = (($i%2)==0)?"tr_even":"tr_odd";
				$str .=<<<EOT
	<tr class="TableLine $iClass">
		<td align="center" >
			<p class="childImg">
            <image src="images/expanded.gif" />$i
        	</p>
        </td>
        <td  >
            $val[applyNumb]
        </td>
        <td  >
            $val[dateHope]
        </td>
        <td  >
            $val[suppName]
        </td>
        <td  >
            $val[stateCName]
        </td>
        <td width="45%" class="tdChange" >
			<table width="100%"  class="shrinkTable">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="13%">
				            $chdVal[amountAll]
				        </td>
				        <td width="13%">
				          	$chdVal[amountNotIssued]
				        </td>
				        <td width="13%">
				            $chdVal[applyPrice]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开本设备具体信息></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]')">
			    <option value="value1">请选择操作</option>
			    <option value="readExa">查看审批</option>
			    <option value="read">查看</option>
			  </select>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************显示分割线********************************************/
	/*
	 * @desription 采购申请单--Tab页
	 * @param tags
	 * @author qian
	 * @date 2010-12-14 下午07:36:28
	 */
	function c_centerList () {
		$this->show->display($this->objPath . '_' . $this->objName . '-tab-index');
	}

	/**
	 * 采购申请单
	 */
//	function c_centerList(){
//		$clickNumb=isset($_GET['clickNumb'])?$_GET['clickNumb']:"1";
//
//		/* 2010年12月14日整段注释*/
//		$clickNumb=isset($_GET['clickNumb'])?$_GET['clickNumb']:"1";
//		$arrayPanel = array("numb"=>4,
//								"clickNumb"=> $clickNumb,
//								"name1"=>"待提交审批",
//								"title1"=>"待提交审批采购申请单",
//								"url1"=>"?model=purchase_apply_applybasic&action=centerList&clickNumb=1",
//								"name2"=>"审批中",
//								"title2"=>"审批中采购申请单",
//								"url2"=>"?model=purchase_apply_applybasic&action=centerList&clickNumb=2",
//								"name3"=>"执行中",
//								"title3"=>"执行中采购申请单",
//								"url3"=>"?model=purchase_apply_applybasic&action=centerList&clickNumb=3",
//								"name4"=>"已关闭",
//								"title4"=>"已关闭采购申请单",
//								"url4"=>"?model=purchase_apply_applybasic&action=centerList&clickNumb=4"
//							);
//		$topPlan = parent::topPlan($arrayPanel);
//		$this->show->assign('topPlan', $topPlan);
//		switch ($clickNumb) {
//			case 1:
//				$this->centerWaitList($clickNumb);
//				break;
//			case 2:
//				$this->centerApprovalList($clickNumb);
//				break;
//			case 3:
//				$this->centerExecutionList($clickNumb);
//				break;
//			case 4:
//				$this->centerCloseList($clickNumb);
//				break;
//			default:
//				showmsg('错误','temp','button');
//				break;
//		}
//	}

/*****************************************显示分割线********************************************/

	/**
	 * 待提交审批采购申请单
	 */
	function c_centerWaitList(){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		$searchArr = array (
			"seachApplyNumb" => $applyNumb,
			"stateArr" => $this->service->stateToSta("save").",".$this->service->stateToSta("fightback"),
//			"isUse" => "1"
		);
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		//分页
		$this->pageShowAssign();
//		$showpage->show_page(array (
//			'total' => $service->count,
//			'perpage' => pagenum
//		));
//		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showWaitList($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
		unset($this->show);
		unset($service);
	}

	/**
	 * 待审批的显示列表
	 */
	function showWaitList($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = (($i%2)==0)?"tr_even":"tr_odd";
				$str .=<<<EOT
	<tr class="TableLine $iClass">
		<td align="center" >
			<p class="childImg">
            <image src="images/expanded.gif" />$i
        	</p>
        </td>
        <td  >
            $val[applyNumb]
        </td>
        <td  >
            $val[dateHope]
        </td>
        <td  >
            $val[suppName]
        </td>
        <td  >
            $val[stateCName]
        </td>
        <td width="45%" class="tdChange td_table" >
			<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="13%">
				            $chdVal[amountAll]
				        </td>
				        <td width="13%">
				          	$chdVal[amountNotIssued]
				        </td>
				        <td width="13%">
				            $chdVal[applyPrice]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开本设备具体信息></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]')">
			    <option value="value1">请选择操作</option>
			    <option value="read">查看详情</option>
			  </select>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************显示分割线********************************************/

	/**
	 * 审批中的采购申请单
	 */
	function c_centerApprovalList(){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		$searchArr = array (
			"seachApplyNumb" => $applyNumb,
			"createId" => $_SESSION['USER_ID'],
			"state" => $this->service->stateToSta("approval"),
//			"isUse" => "1"
		);
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		//分页
		$this->pageShowAssign();
//		$showpage->show_page(array (
//			'total' => $service->count,
//			'perpage' => pagenum
//		));
//		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showCenterList($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
		unset($this->show);
		unset($service);
	}

	/**
	 * 执行的显示列表
	 */
	function showCenterList($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = (($i%2)==0)?"tr_even":"tr_odd";
				$str .=<<<EOT
	<tr class="TableLine $iClass">
		<td align="center" >
			<p class="childImg">
            <image src="images/expanded.gif" />$i
        	</p>
        </td>
        <td  >
            $val[applyNumb]
        </td>
        <td  >
            $val[dateHope]
        </td>
        <td  >
            $val[suppName]
        </td>
        <td  >
            $val[stateCName]
        </td>
        <td width="45%" class="tdChange td_table" >
			<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="13%">
				            $chdVal[amountAll]
				        </td>
				        <td width="13%">
				          	$chdVal[amountNotIssued]
				        </td>
				        <td width="13%">
				            $chdVal[applyPrice]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开本设备具体信息></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]')">
			    <option value="value1">请选择操作</option>
			    <option value="read">查看详情</option>
			    <option value="readExa">查看审批</option>
			  </select>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************显示分割线********************************************/

	/**
	 * 执行中采购申请单
	 */
	function c_centerExecutionList(){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		$searchArr = array (
			"seachApplyNumb" => $applyNumb,
			"createId" => $_SESSION['USER_ID'],
			//"state" => $this->service->stateToSta("execute"),
			"stateArr" =>$this->service->stateToSta("execute").",".$this->service->stateToSta("wite"),
//			"isUse" => "1"
		);
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		//分页
		$this->pageShowAssign();
//		$showpage->show_page(array (
//			'total' => $service->count,
//			'perpage' => pagenum
//		));
//		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showCenterList($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
		unset($this->show);
		unset($service);
	}

/*****************************************显示分割线********************************************/

	/**
	 * 关闭的采购申请单
	 */
	function c_centerCloseList(){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		$searchArr = array (
			"seachApplyNumb" => $applyNumb,
			"createId" => $_SESSION['USER_ID'],
			"stateArr" => $this->service->stateToSta("close").",".$this->service->stateToSta("end"),
//			"isUse" => "1"
		);
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		//分页
		$this->pageShowAssign();
//		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showCenterList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
		unset($this->show);
		unset($service);
	}

/*****************************************显示分割线********************************************/

	/**
	 * 我的采购申请单审批
	 */
	function c_myApproval(){
		$clickNumb=isset($_GET['clickNumb'])?$_GET['clickNumb']:"1";
		$arrayPanel = array("numb"=>2,
								"clickNumb"=> $clickNumb,
								"name1"=>"待审批采购申请单",
								"title1"=>"待审批采购申请单",
								"url1"=>"?model=purchase_apply_applybasic&action=myApproval&clickNumb=1",
								"name2"=>"已审批采购申请单",
								"title2"=>"已审批采购申请单",
								"url2"=>"?model=purchase_apply_applybasic&action=myApproval&clickNumb=2"
							);
		$topPlan = parent::topPlan($arrayPanel);
		$this->show->assign('topPlan', $topPlan);
		switch ($clickNumb) {
			case 1:
				$this->myApprovalWait($clickNumb);
				break;
			case 2:
				$this->myApprovalAlready($clickNumb);
				break;
			default:
				showmsg('错误','temp','button');
				break;
		}
	}

/*****************************************显示分割线********************************************/

	/**
	 * 待审批列表
	 */
	function myApprovalWait($clickNumb){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$service = $this->service;
		$service->getParam($_GET);
		if($applyNumb!=""){
			$searchArr['seachApplyNumb'] = $applyNumb;
			$service->__SET('searchArr', $searchArr);
		}
		$showpage = new includes_class_page();
		$rows = $service->pageApprovalWait_d();
		//分页
		$this->pageShowAssign();
//		$showpage->show_page(array (
//			'total' => $service->count,
//			'perpage' => pagenum
//		));
		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showApprovalWait($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-Approval-Wait');
		unset($this->show);
		unset($service);
	}

	/**
	 * 显示未审批列表
	 */
	function showApprovalWait($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = (($i%2)==0)?"tr_even":"tr_odd";
				$str .=<<<EOT
	<tr class="TableLine $iClass">
		<td align="center" >
			$i
        </td>
        <td  >
            $val[task]
        </td>
        <td  >
            $val[applyNumb]
        </td>
        <td  >
            $val[createName]
        </td>
        <td >
            $val[suppName]
        </td>
        <td  >
            $val[createTime]
        </td>
        <td  >
            $val[dateHope]
        </td>
        <td >
			<a href="controller/purchase/apply/ewf_index.php?actTo=ewfExam&taskId=$val[task]&spid=$val[flowId]&billId=$val[id]&examCode=$val[code]&go=2">审批</a>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************显示分割线********************************************/

	/**
	 * 已审批列表
	 */
	function myApprovalAlready($clickNumb){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$service = $this->service;
		$service->getParam($_GET);
		if($applyNumb!=""){
			$searchArr['seachApplyNumb'] = $applyNumb;
			$service->__SET('searchArr', $searchArr);
		}
		$showpage = new includes_class_page();
		$rows = $service->pageApprovalAlready_d();
		//分页
		$this->pageShowAssign();
//		$showpage->show_page(array (
//			'total' => $service->count,
//			'perpage' => pagenum
//		));
		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showApprovalAlready($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-Approval-Wait');
		unset($this->show);
		unset($service);
	}

	/**
	 * 我审批过的列表
	 */
	function showApprovalAlready($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = (($i%2)==0)?"tr_even":"tr_odd";
				$str .=<<<EOT
	<tr class="TableLine $iClass">
		<td align="center" >
			$i
        </td>
        <td  >
            $val[task]
        </td>
        <td  >
            $val[applyNumb]
        </td>
        <td  >
            $val[createName]
        </td>
        <td >
            $val[suppName]
        </td>
        <td  >
            $val[createTime]
        </td>
        <td  >
            $val[dateHope]
        </td>
        <td >
			<a href="?model=purchase_apply_applybasic&action=read&id=$val[id]">查看</a> |
			<a href="?model=purchase_apply_applybasic&action=read&seeExa=yes&id=$val[id]">查看审批</a>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************显示分割线********************************************/

	/**
	 * @exclude 修改保存
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-11 下午08:00:03
	 */
	function c_editSave(){
		$object = $this->service->editSave_d( $_POST ["basic"],$_POST ["oldId"] );
		if ($object) {
			msgBack2("修改成功！");
		}else{
			msgBack2("修改失败！");
		}

	}

	/**
	 * @exclude 修改采购申请单(待提交审批，保存时可修改)
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-11 上午11:23:42
	 */
	function c_edit(){
		$id = isset($_GET['id'])?$_GET['id']:exit;
		$arr = $this->service->listEditApply_d($id);
		$this->arrToShow($arr);
		$this->show->assign('oldId', $id );
		$this->show->assign('userId', $_SESSION['USER_ID'] );
		$this->show->assign('userName', $_SESSION['USERNAME'] );
		$this->show->assign('applyVersionNumb', $arr['0']['applyVersionNumb'] );
		$this->show->assign('nowTime', date("Y-m-d") );
		$this->show->assign('list', $this->showEdit($arr["0"]["childArr"]) );
		$this->show->display($this->objPath . '_' . $this->objName . '-edit');
	}

	/**
	 * @exclude 显示列表修改采购申请单
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-11 下午06:38:13
	 */
	function showEdit($listEqu){
		$str="";
		$i = $m = 0;
//		echo "<pre>";
//		print_r($listEqu);
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$i++;
				$str.=<<<EOT
		<tr height="28" align="center">
			<td >
				$i
			</td>
			<td >
				$val[productNumb]<br>$val[productName]
			</td>
			<td >
				$val[objectsNumb]
			</td>
			<td >
				$val[purchTypeCName]
			</td>
			<td >
				<input type="text" name="basic[equment][$m][amountAll]" id="amountAll$m" value="$val[amountAll]" size=6 class="taskAmount">

				<input type="hidden" name="amountAll" value="$val[amountAll]" />
				<input type="hidden" name="basic[equment][$m][amountOld]" value="$val[amountAll]" />

				<input type="hidden" name="basic[equment][$m][deviceIsUse]" value="1" />
				<input type="hidden" name="basic[equment][$m][applyEquOnlyId]" value="$val[applyEquOnlyId]" />
				<input type="hidden" name="basic[equment][$m][deviceNumb]" value="$val[deviceNumb]" />
				<input type="hidden" name="basic[equment][$m][basicVersionNumb]" value="1" />
				<input type="hidden" name="basic[equment][$m][objectsNumb]" value="$val[objectsNumb]" />
				<input type="hidden" name="basic[equment][$m][typeTabName]" value="$val[typeTabName]" />
				<input type="hidden" name="basic[equment][$m][typeTabId]" value="$val[typeTabId]" />
				<input type="hidden" name="basic[equment][$m][typeEquTabName]" value="$val[typeEquTabName]" />
				<input type="hidden" name="basic[equment][$m][typeEquTabId]" value="$val[typeEquTabId]" />
				<input type="hidden" name="basic[equment][$m][productName]" value="$val[productName]" />
				<input type="hidden" name="basic[equment][$m][productId]" value="$val[productId]" />
				<input type="hidden" name="basic[equment][$m][productNumb]" value="$val[productNumb]" />
				<input type="hidden" name="basic[equment][$m][amountIssued]" value="0" />
				<input type="hidden" name="basic[equment][$m][dateIssued]" value="$val[dateIssued]" />
				<input type="hidden" name="basic[equment][$m][planId]" value="$val[planId]" />
				<input type="hidden" name="basic[equment][$m][plantNumb]" value="$val[plantNumb]" />
				<input type="hidden" name="basic[equment][$m][planEquId]" value="$val[planEquId]" />
				<input type="hidden" name="basic[equment][$m][planEquNumb]" value="$val[planEquNumb]" />
				<input type="hidden" name="basic[equment][$m][taskId]" value="$val[taskId]" />
				<input type="hidden" name="basic[equment][$m][taskNumb]" value="$val[taskNumb]" />
				<input type="hidden" name="basic[equment][$m][taskEquId]" value="$val[taskEquId]" />
				<input type="hidden" name="basic[equment][$m][taskEquNumb]" value="$val[taskEquNumb]" />
				<input type="hidden" name="basic[equment][$m][status]" value="1" />
			</td>
			<td >
				<input type="text" id="applyPrice$m" name="basic[equment][$m][applyPrice]" size="9" maxlength="12" value="$val[applyPrice]"/>
			</td>
			<td >
				<input type="text" id="hopeTime$m" name="basic[equment][$m][dateHope]" size="9" maxlength="12" class="BigInput" value="$val[dateHope]"  onfocus="WdatePicker()"  readonly />
			</td>
			<td>
				<textarea rows="2" cols="18" name="basic[equment][$m][remark]" id="remark$m">$val[remark]</textarea>
			</td>
		</tr>
EOT;
				++$m;
			}
		}
		return $str;
	}

/*****************************************显示分割线********************************************/

	/**
	 * @exclude 变更保存
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 下午12:12:49
	 */
	function c_changeSave(){
		$object = $this->service->changeSave_d( $_POST ["basic"],$_POST ["oldId"],$_POST ["productIds"] );
		if ($object) {
			msgGo("操作成功！","controller/purchase/apply/ewf_change.php?actTo=ewfSelect&billId=$object&examCode=oa_purch_apply_change&go=2");
		}else{
			msgBack2("操作失败！");
		}

	}

	/**
	 * @exclude 跳转变更页面
	 * @author ouyang
	 * @param
	 * @version 2010-8-12 上午10:28:27
	 */
	function c_toChange () {
		$id = isset($_GET['id'])?$_GET['id']:exit;
		$productIds = isset($_GET['productIds'])?$_GET['productIds']:"";
		$arr = $this->service->listChangeApply_d($id,$productIds);
		$this->arrToShow($arr);
		$this->show->assign('oldId', $id );
		$this->show->assign('productIds', $productIds );
		$this->show->assign('userId', $_SESSION['USER_ID'] );
		$this->show->assign('userName', $_SESSION['USERNAME'] );
		$this->show->assign('applyVersionNumb', $arr['0']['applyVersionNumb'] );
		$this->show->assign('nowTime', date("Y-m-d") );
		$this->show->assign('changeNumb', "papcha-".date("YmdHis").rand(10,99) );
		$this->show->assign('list', $this->showChange($arr["0"]["childArr"]) );
		$this->show->display($this->objPath . '_' . $this->objName . '-change');
	}

	/**
	 * @exclude
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 上午11:02:00
	 */
	function showChange($listEqu) {
		$str="";
		$i = $m = 0;
//		echo "<pre>";
//		print_r($listEqu);
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$i++;
				$str.=<<<EOT
		<tr height="28" align="center">
			<td >
				$i
			</td>
			<td >
				$val[productNumb]<br>$val[productName]
			</td>
			<td >
				$val[objectsNumb]
			</td>
			<td >
				$val[purchTypeCName]
			</td>
			<td >
				<input type="text" name="basic[equment][$m][amountAll]" id="amountAll$m" value="$val[amountAll]" size=6 class="taskAmount">

				<input type="hidden" name="amountAll" value="$val[amountAll]" />
				<input type="hidden" name="basic[equment][$m][amountOld]" value="$val[amountAll]" />

				<input type="hidden" name="basic[equment][$m][deviceIsUse]" value="2" />
				<input type="hidden" name="basic[equment][$m][applyEquOnlyId]" value="$val[applyEquOnlyId]" />
				<input type="hidden" name="basic[equment][$m][deviceNumb]" value="$val[deviceNumb]" />
				<input type="hidden" name="basic[equment][$m][basicVersionNumb]" value="1" />
				<input type="hidden" name="basic[equment][$m][objectsNumb]" value="$val[objectsNumb]" />
				<input type="hidden" name="basic[equment][$m][typeTabName]" value="$val[typeTabName]" />
				<input type="hidden" name="basic[equment][$m][typeTabId]" value="$val[typeTabId]" />
				<input type="hidden" name="basic[equment][$m][typeEquTabName]" value="$val[typeEquTabName]" />
				<input type="hidden" name="basic[equment][$m][typeEquTabId]" value="$val[typeEquTabId]" />
				<input type="hidden" name="basic[equment][$m][productName]" value="$val[productName]" />
				<input type="hidden" name="basic[equment][$m][productId]" value="$val[productId]" />
				<input type="hidden" name="basic[equment][$m][productNumb]" value="$val[productNumb]" />
				<input type="hidden" name="basic[equment][$m][amountIssued]" value="0" />
				<input type="hidden" name="basic[equment][$m][dateIssued]" value="$val[dateIssued]" />
				<input type="hidden" name="basic[equment][$m][planId]" value="$val[planId]" />
				<input type="hidden" name="basic[equment][$m][plantNumb]" value="$val[plantNumb]" />
				<input type="hidden" name="basic[equment][$m][planEquId]" value="$val[planEquId]" />
				<input type="hidden" name="basic[equment][$m][planEquNumb]" value="$val[planEquNumb]" />
				<input type="hidden" name="basic[equment][$m][taskId]" value="$val[taskId]" />
				<input type="hidden" name="basic[equment][$m][taskNumb]" value="$val[taskNumb]" />
				<input type="hidden" name="basic[equment][$m][taskEquId]" value="$val[taskEquId]" />
				<input type="hidden" name="basic[equment][$m][taskEquNumb]" value="$val[taskEquNumb]" />
				<input type="hidden" name="basic[equment][$m][status]" value="1" />
			</td>
			<td >
				<input type="text" id="applyPrice$m" name="basic[equment][$m][applyPrice]" size="9" maxlength="12" value="$val[applyPrice]"/>
			</td>
			<td >
				<input type="text" id="hopeTime$m" name="basic[equment][$m][dateHope]" size="9" maxlength="12" class="BigInput"  onfocus="WdatePicker()"  value="$val[dateHope]" readonly />
			</td>
			<td>
				<textarea rows="2" cols="18" name="basic[equment][$m][remark]" id="remark$m">$val[remark]</textarea>
			</td>
		</tr>
EOT;
				++$m;
			}
		}
		return $str;
	}

/*****************************************显示分割线********************************************/

	/**
	 * 查看申请单页面
	 */
	function c_read(){
		$id = isset($_GET['id'])?$_GET['id']:exit;
		$seeExa = isset($_GET["seeExa"])?$_GET["seeExa"]:"no";
		$goBack = isset($_GET["goBack"])?$_GET["goBack"]:"yes";
		$arr = $this->service->readApply_d($id);
		$arrExa = "" ;
		if($seeExa == "yes"){
			$arrExa = $this->service->arrExa_d($id);
		}

		$strGoBack=" ";
		if($goBack=="yes"){
			$strGoBack=<<<EOT
			<tr align="center" class="TableHeader" height="28">
				<td colspan="6"class="footform">
					<input type="button" value="返  回" class="txt_btn_a" onclick="history.back();" >&nbsp;
				</td>
			</tr>
EOT;
		}

		$this->arrToShow($arr);
		$this->show->assign('goback', $strGoBack );
		$this->show->assign('showExa', $this->showExa($arrExa) );
		$this->show->assign('list', $this->showRead($arr["0"]["childArr"]) );
		$this->show->display($this->objPath . '_' . $this->objName . '-read');
	}

	/**
	 * 查看采购申请单设备显示列表
	 */
	function showRead($listEqu){
		$str="";
		$i = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$i++;
				$str.=<<<EOT
					<tr height="28" align="center">
						<td>
							$i
						</td>
						<td>
							$val[productNumb] <br/> $val[productName]
						</td>

						<td>
							$val[purchTypeCName]
						</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$val[amountIssued]
						</td>
						<td>
							$val[dateHope]
						</td>
						<td>
							$val[applyPrice]
						</td>
						<td>
							<textarea rows="2" cols="23" readOnly>$val[remark]</textarea>
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

/*****************************************显示分割线********************************************/

	/**
	 * 审批列表
	 */
	function showExa($arrExa){
		if($arrExa){
			$str =<<<EOT
	<tr align="center" >
      <td colspan="6" style="padding-left:0px;padding-right:0px;" >
        <table border="0" cellspacing="0" cellpadding="0" class="table" width="100%"  align="center">
                <tr align="center" class="tablecontrol" >
                    <td width="100%" align="center" colspan="6" style="font-size:14px;" height="35"><B>审批情况</B></td>
                </tr>
                    <tr align="center" class="TableLine2" style="color:blue;">
                        <td width="20%">步骤名</td>
                        <td width="10%">审批人</td>
                        <td width="20%">审批日期</td>
                        <td width="9%">审批结果</td>
                        <td width="27%">审批意见</td>
                    </tr>
EOT;
			foreach($arrExa as $key => $val){
				if($val["childArr"]){
					$x = 0;
					foreach($val["childArr"] as $childKey => $childVal ){
						$x++;
						$str .= "<tr class='extr TableLine2' >";
                        if($x==1){
                        	$str .="<td rowspan=".count( $val["childArr"] );
							if( $childVal["Flag"]==0 ){
								$str.=" style='color:red;' ";
							}
							$str.= ">&nbsp;".$val["Item"]."</td>";
                        }
                        //trim(get_username_list( $childVal['User'] ),",")
                        $str .= " <td align='center'>&nbsp ".$childVal['User']."</td>";
						$str .= " <td align='center' style='color:green;'>&nbsp;".$childVal['Endtime']."</td> ";
						$str .= "<td align='center'>&nbsp;" ;

						if(  isset( $childVal['Result'] ) && $childVal['Result']=='ok'){
							$str .= "<font color='green'>同意</font>";
						}elseif( isset( $childVal['Result'] ) && $childVal['Result']=='no') {
							$str .= "<font color='red'>不同意</font>";
						}else {
							$str .= '未审批';
						}
						$str .= "</td><td>&nbsp;";
						if( isset( $childVal['Content'] ) ) $str.=$childVal['Content'];

						$str.="</td></tr>";
					}
				}
				else{
					$str.= "<tr class='extr TableLine2' >";
                    $str.= "<td >&nbsp;".$val['Item']."</td>";
                    $str .= " <td align='center'>&nbsp ".$val['User']."</td>";
                    $str .= " <td align='center'>&nbsp;".$val['Endtime']."</td>";
					$str .= "<td align='center'>&nbsp;未审批</td><td>&nbsp;</td></tr>" ;
				}

			}
			$str .="</table></td></tr>";
		}
		else{
			$str = "";
		}
		return $str;
	}

/*****************************************显示分割线********************************************/

	/**
	 * @desription 关闭采购申请单
	 * @author ouyang
	 * @date 2010-9-15 下午03:40:16
	 * @version V1.0
	 */
	function c_close(){
		$id = isset( $_GET["id"] )?$_GET["id"]:exit;
		$numb = $this->service->findNumbById_d($id);
		if( $this->service->close_d($numb) ){
			msgGo("关闭成功");
		}else{
			msgGo("关闭失败！！可能是服务器错误，请稍后再试");
		}
	}

	/**
	 * @exclude 完成采购申请单控制器方法
	 * @author ouyang
	 * @param
	 * @version 2010-8-10 下午04:45:25
	 */
	function c_end() {
		$id = isset( $_GET["id"] )?$_GET["id"]:exit;
		$val = $this->service->end_d($id);
		if( $val==1 ){
			msgGo("操作成功");
		}
		else if($val==2){
			msgGo("存在为采购到货设备，不可完成");
		}
		else{
			msgGo("操作失败！！可能是服务器错误，请稍后再试");
		}
	}

	/**
	 * @desription 删除采购申请单方法（待审批界面）
	 * @author ouyang
	 * @date 2010-9-13 下午09:32:42
	 * @version V1.0
	 */
	function c_del () {
		//http://localhost/dloa/oae/index1.php?model=purchase_apply_applybasic&action=del&id=3
		$id = isset( $_GET["id"] )?$_GET["id"]:exit;
		$val = $this->service->del_d($id);
		if( $val==1 ){
			msgGo("操作成功");
		}
		else{
			msgGo("操作失败！！可能是服务器错误，请稍后再试");
		}
	}


}



?>
