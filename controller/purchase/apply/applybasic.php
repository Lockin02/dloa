<?php
/**
 * �ɹ����뵥������Ϣ control
 */

 class controller_purchase_apply_applybasic extends controller_base_action {

 	function __construct() {
		$this->objName = "applybasic";
		$this->objPath = "purchase_apply";
		parent :: __construct();
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �½��ɹ����뵥
	 */
	function c_add(){
		$approval = isset( $_POST['approval'] )?$_POST['approval']:0;
		$object = $this->service->add_d( $_POST ["basic"] );
		if ($object) {
			if($approval=="1"){
				msgGo("����ɹ�","controller/purchase/apply/ewf_index.php?actTo=ewfSelect&billId=" . $object . "&examCode=oa_purch_apply_basic&formName=�ɹ����뵥����&go=2");
			}else{
				msgBack2("����ɹ���");
			}
		}else{
			msgBack2("���ʧ�ܣ�");
		}
	}
/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �ҵĲɹ����뵥
	 */
//	function c_myList(){
//		$clickNumb=isset($_GET['clickNumb'])?$_GET['clickNumb']:"1";
//		$arrayPanel = array("numb"=>5,
//								"clickNumb"=> $clickNumb,
//								"name1"=>"ִ����",
//								"title1"=>"ִ���вɹ����뵥",
//								"url1"=>"?model=purchase_apply_applybasic&action=myList&clickNumb=1",
//								"name2"=>"���ύ����",
//								"title2"=>"���ύ�����ɹ����뵥",
//								"url2"=>"?model=purchase_apply_applybasic&action=myList&clickNumb=2",
//								"name3"=>"������",
//								"title3"=>"�����вɹ����뵥",
//								"url3"=>"?model=purchase_apply_applybasic&action=myList&clickNumb=3",
//								"name4"=>"�ѹر�",
//								"title4"=>"�ѹرղɹ����뵥",
//								"url4"=>"?model=purchase_apply_applybasic&action=myList&clickNumb=4",
//								"name5"=>"�ҵı������",
//								"title5"=>"�ҵı������",
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
//				showmsg('����','temp','button');
//				break;
//		}
//	}

	/*
	 * @desription �ҵĲɹ�����--Tabҳ
	 * @param tags
	 * @author qian
	 * @date 2010-12-29 ����03:40:56
	 */
	function c_myList () {
		$this->show->display($this->objPath . '_' . $this->objName . '-tab-myapply');
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �ҵ�ִ���вɹ����뵥
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
		//��ҳ
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
	 * ����ִ�е���ʾ�б�
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
        	<div class="readThisTable"><����չ�����豸������Ϣ></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]','$val[applyNumb]')">
			    <option value="value1">&nbsp;&nbsp;��ѡ�����&nbsp;&nbsp;</option>
			    <option value="execute">ִ�����뵥</option>
			    <option value="read">�鿴</option>
			    <option value="change">�������</option>
			    <option value="readExa">�鿴����</option>
			    <option value="del">�ر�</option>
			  </select>
		</td>
    </tr>
EOT;
				}else{

					if($amountNot <= 0 ){
	        		$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><����չ�����豸������Ϣ></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]','$val[applyNumb]')">
			    <option value="value1">&nbsp;&nbsp;��ѡ�����&nbsp;&nbsp;</option>
			    <option value="pay">��д�������뵥</option>
			    <option value="read">�鿴</option>
			    <!--option value="value5">�������</option-->
			    <option value="ininvoice">¼�뷢Ʊ</option>
			    <option value="readExa">�鿴����</option>
			    <option value="end">���</option>
			    <!--option value="del">�ر�</option-->
			  </select>
		</td>
    </tr>
EOT;
					}else{
	        		$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><����չ�����豸������Ϣ></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]','$val[applyNumb]')">
			    <option value="value1">&nbsp;&nbsp;��ѡ�����&nbsp;&nbsp;</option>
			    <option value="arival">��д�ɹ�������</option>
			    <option value="pay">��д�������뵥</option>
			    <option value="read">�鿴</option>
			    <!--option value="value5">�������</option-->
			    <option value="ininvoice">¼�뷢Ʊ</option>
			    <option value="readExa">�鿴����</option>
			    <option value="end">���</option>
			    <!--option value="del">�ر�</option-->
			  </select>
		</td>
    </tr>
EOT;

					}
				}
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

	/**
	 * ִ�����뵥
	 */
	function c_execute(){
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		if( $this->service->execute_d($id) ){
			msgGo("ִ�гɹ�");
		}else{
			msgGo("ִ��ʧ�ܣ��������Ƿ������������Ժ�����");
		}

	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �ҵĴ��ύ�����ɹ����뵥
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
		//��ҳ
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
	 * @exclude ���ڴ���������ʾ�б�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-11 ����11:21:03
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
        	<div class="readThisTable"><����չ�����豸������Ϣ></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]')">
			    <option value="value1">��ѡ�����</option>
			    <option value="read">�鿴</option>
EOT;
				if($val["ExaStatus"]=="���"){
					$str.="<option value='readExa'>�鿴����</option>";
				}else if($val["ExaStatus"]=="����"){
					$str.="<option value='subExa'>�ύ����</option>";
				}
				$str.=<<<EOT
			    <option value="edit">�޸�</option>
			    <option value="del">ɾ��</option>
			  </select>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �ҵ������еĲɹ����뵥
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
		//��ҳ
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
	 * ���������е���ʾ�б�
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
        	<div class="readThisTable"><����չ�����豸������Ϣ></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]')">
			    <option value="value1">��ѡ�����</option>
			    <option value="readExa">�鿴����</option>
			    <option value="read">�鿴</option>
			  </select>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �ҵĹرյĲɹ����뵥
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
		//��ҳ
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
	 * ���ڹرյ���ʾ�б�
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
        	<div class="readThisTable"><����չ�����豸������Ϣ></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]')">
			    <option value="value1">��ѡ�����</option>
			    <option value="readExa">�鿴����</option>
			    <option value="read">�鿴</option>
			  </select>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************��ʾ�ָ���********************************************/
	/*
	 * @desription �ɹ����뵥--Tabҳ
	 * @param tags
	 * @author qian
	 * @date 2010-12-14 ����07:36:28
	 */
	function c_centerList () {
		$this->show->display($this->objPath . '_' . $this->objName . '-tab-index');
	}

	/**
	 * �ɹ����뵥
	 */
//	function c_centerList(){
//		$clickNumb=isset($_GET['clickNumb'])?$_GET['clickNumb']:"1";
//
//		/* 2010��12��14������ע��*/
//		$clickNumb=isset($_GET['clickNumb'])?$_GET['clickNumb']:"1";
//		$arrayPanel = array("numb"=>4,
//								"clickNumb"=> $clickNumb,
//								"name1"=>"���ύ����",
//								"title1"=>"���ύ�����ɹ����뵥",
//								"url1"=>"?model=purchase_apply_applybasic&action=centerList&clickNumb=1",
//								"name2"=>"������",
//								"title2"=>"�����вɹ����뵥",
//								"url2"=>"?model=purchase_apply_applybasic&action=centerList&clickNumb=2",
//								"name3"=>"ִ����",
//								"title3"=>"ִ���вɹ����뵥",
//								"url3"=>"?model=purchase_apply_applybasic&action=centerList&clickNumb=3",
//								"name4"=>"�ѹر�",
//								"title4"=>"�ѹرղɹ����뵥",
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
//				showmsg('����','temp','button');
//				break;
//		}
//	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * ���ύ�����ɹ����뵥
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
		//��ҳ
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
	 * ����������ʾ�б�
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
        	<div class="readThisTable"><����չ�����豸������Ϣ></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]')">
			    <option value="value1">��ѡ�����</option>
			    <option value="read">�鿴����</option>
			  </select>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �����еĲɹ����뵥
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
		//��ҳ
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
	 * ִ�е���ʾ�б�
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
        	<div class="readThisTable"><����չ�����豸������Ϣ></div>
        </td>
        <td >
			<select onchange="selectBut(this,'$val[id]')">
			    <option value="value1">��ѡ�����</option>
			    <option value="read">�鿴����</option>
			    <option value="readExa">�鿴����</option>
			  </select>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * ִ���вɹ����뵥
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
		//��ҳ
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

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �رյĲɹ����뵥
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
		//��ҳ
		$this->pageShowAssign();
//		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showCenterList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
		unset($this->show);
		unset($service);
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �ҵĲɹ����뵥����
	 */
	function c_myApproval(){
		$clickNumb=isset($_GET['clickNumb'])?$_GET['clickNumb']:"1";
		$arrayPanel = array("numb"=>2,
								"clickNumb"=> $clickNumb,
								"name1"=>"�������ɹ����뵥",
								"title1"=>"�������ɹ����뵥",
								"url1"=>"?model=purchase_apply_applybasic&action=myApproval&clickNumb=1",
								"name2"=>"�������ɹ����뵥",
								"title2"=>"�������ɹ����뵥",
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
				showmsg('����','temp','button');
				break;
		}
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �������б�
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
		//��ҳ
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
	 * ��ʾδ�����б�
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
			<a href="controller/purchase/apply/ewf_index.php?actTo=ewfExam&taskId=$val[task]&spid=$val[flowId]&billId=$val[id]&examCode=$val[code]&go=2">����</a>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �������б�
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
		//��ҳ
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
	 * �����������б�
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
			<a href="?model=purchase_apply_applybasic&action=read&id=$val[id]">�鿴</a> |
			<a href="?model=purchase_apply_applybasic&action=read&seeExa=yes&id=$val[id]">�鿴����</a>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";}
//		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude �޸ı���
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-11 ����08:00:03
	 */
	function c_editSave(){
		$object = $this->service->editSave_d( $_POST ["basic"],$_POST ["oldId"] );
		if ($object) {
			msgBack2("�޸ĳɹ���");
		}else{
			msgBack2("�޸�ʧ�ܣ�");
		}

	}

	/**
	 * @exclude �޸Ĳɹ����뵥(���ύ����������ʱ���޸�)
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-11 ����11:23:42
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
	 * @exclude ��ʾ�б��޸Ĳɹ����뵥
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-11 ����06:38:13
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

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude �������
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����12:12:49
	 */
	function c_changeSave(){
		$object = $this->service->changeSave_d( $_POST ["basic"],$_POST ["oldId"],$_POST ["productIds"] );
		if ($object) {
			msgGo("�����ɹ���","controller/purchase/apply/ewf_change.php?actTo=ewfSelect&billId=$object&examCode=oa_purch_apply_change&go=2");
		}else{
			msgBack2("����ʧ�ܣ�");
		}

	}

	/**
	 * @exclude ��ת���ҳ��
	 * @author ouyang
	 * @param
	 * @version 2010-8-12 ����10:28:27
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
	 * @version 2010-8-12 ����11:02:00
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

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �鿴���뵥ҳ��
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
					<input type="button" value="��  ��" class="txt_btn_a" onclick="history.back();" >&nbsp;
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
	 * �鿴�ɹ����뵥�豸��ʾ�б�
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

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * �����б�
	 */
	function showExa($arrExa){
		if($arrExa){
			$str =<<<EOT
	<tr align="center" >
      <td colspan="6" style="padding-left:0px;padding-right:0px;" >
        <table border="0" cellspacing="0" cellpadding="0" class="table" width="100%"  align="center">
                <tr align="center" class="tablecontrol" >
                    <td width="100%" align="center" colspan="6" style="font-size:14px;" height="35"><B>�������</B></td>
                </tr>
                    <tr align="center" class="TableLine2" style="color:blue;">
                        <td width="20%">������</td>
                        <td width="10%">������</td>
                        <td width="20%">��������</td>
                        <td width="9%">�������</td>
                        <td width="27%">�������</td>
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
							$str .= "<font color='green'>ͬ��</font>";
						}elseif( isset( $childVal['Result'] ) && $childVal['Result']=='no') {
							$str .= "<font color='red'>��ͬ��</font>";
						}else {
							$str .= 'δ����';
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
					$str .= "<td align='center'>&nbsp;δ����</td><td>&nbsp;</td></tr>" ;
				}

			}
			$str .="</table></td></tr>";
		}
		else{
			$str = "";
		}
		return $str;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @desription �رղɹ����뵥
	 * @author ouyang
	 * @date 2010-9-15 ����03:40:16
	 * @version V1.0
	 */
	function c_close(){
		$id = isset( $_GET["id"] )?$_GET["id"]:exit;
		$numb = $this->service->findNumbById_d($id);
		if( $this->service->close_d($numb) ){
			msgGo("�رճɹ�");
		}else{
			msgGo("�ر�ʧ�ܣ��������Ƿ������������Ժ�����");
		}
	}

	/**
	 * @exclude ��ɲɹ����뵥����������
	 * @author ouyang
	 * @param
	 * @version 2010-8-10 ����04:45:25
	 */
	function c_end() {
		$id = isset( $_GET["id"] )?$_GET["id"]:exit;
		$val = $this->service->end_d($id);
		if( $val==1 ){
			msgGo("�����ɹ�");
		}
		else if($val==2){
			msgGo("����Ϊ�ɹ������豸���������");
		}
		else{
			msgGo("����ʧ�ܣ��������Ƿ������������Ժ�����");
		}
	}

	/**
	 * @desription ɾ���ɹ����뵥���������������棩
	 * @author ouyang
	 * @date 2010-9-13 ����09:32:42
	 * @version V1.0
	 */
	function c_del () {
		//http://localhost/dloa/oae/index1.php?model=purchase_apply_applybasic&action=del&id=3
		$id = isset( $_GET["id"] )?$_GET["id"]:exit;
		$val = $this->service->del_d($id);
		if( $val==1 ){
			msgGo("�����ɹ�");
		}
		else{
			msgGo("����ʧ�ܣ��������Ƿ������������Ժ�����");
		}
	}


}



?>
