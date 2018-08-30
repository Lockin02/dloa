<?php
/**
 * �ɹ����뵥������Ϣ control
 */

 class controller_purchase_apply_applychange extends controller_base_action {

 	function __construct() {
		$this->objName = "applychange";
		$this->objPath = "purchase_apply";
		parent :: __construct();
	}

/*****************************************��ʾ�ָ���********************************************/

	/** ��ʾ�ҵı������
	 * @exclude
	 * @author ouyang
	 * @param
	 * @version 2010-8-12 ����02:07:22
	 */
	function c_myList ($clickNumb) {
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$changeNumb = isset( $_GET['changeNumb'] )?$_GET['changeNumb']:"";
		$searchArr = array (
			"createId" => $_SESSION['USER_ID'],
			"stateArr" =>$this->service->stateToSta("save").",".
						$this->service->stateToSta("approval").",".
						$this->service->stateToSta("fightback").",".
						$this->service->stateToSta("end").",".
						$this->service->stateToSta("close")
		);
		if($changeNumb!=""){
			$searchArr["seachChangeNumb"] = $changeNumb;
		}
		if($applyNumb!=""){
			$searchArr["seachBasicNumb"] = $applyNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$rows = $service->pageBySqlId("list_page");
		$showpage = new includes_class_page();
		//��ҳ
		$showpage->show_page(array (
			'total' => $service->count,
			'perpage' => pagenum
		));
		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('changeNumb', $changeNumb);
		$this->show->assign('list', $this->showList($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-my-change');
		unset($this->show);
		unset($service);
	}

	/** ��ʾ�ҵı�������б�
	 * @exclude
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����02:31:57
	 */
	function showList($rows, $showpage) {
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$iClass = $i%2+1;
				$selectStr = "";
				$state = $this->service->stateToVal( $val['state'] );
				if($val['state'] == $this->service->stateToSta( 'save' ) ){
					$selectStr =<<<EOT
						<option value="approval">�ύ����</option>
						<option value="read">�鿴</option>
						<!--option value="del">ɾ��</option-->
						<option value="close">�ر�</option>
EOT;
				}
				else if( $val['state'] == $this->service->stateToSta( 'approval' ) ){
					$selectStr =<<<EOT
						<option value="readExa">�鿴</option>
EOT;
				}
				else if( $val['state'] == $this->service->stateToSta( 'fightback' ) ){
					$selectStr =<<<EOT
						<option value="readExa">�鿴</option>
						<!--option value="edit">�޸�</option-->
						<option value="close">�ر�</option>

EOT;
				}
				else if( $val['state'] == $this->service->stateToSta( 'end' ) ){
					$selectStr =<<<EOT
						<option value="execute">����ִ��</option>
						<option value="readExa">�鿴</option>
EOT;
				}
				else if( $val['state'] == $this->service->stateToSta( 'close' ) ){
					$selectStr =<<<EOT
						<option value="readExa">�鿴</option>
EOT;
				}
				$str .=<<<EOT
	<tr class="TableLine$iClass">
		<td align="center" >
			$i
        </td>
        <td  >
             $val[basicNumb]
        </td>
        <td  >
            $val[changeNumb]
        </td>
        <td  >
            $val[name]
        </td>
        <td  >
            $val[createName]
        </td>
		<td  >
            $state
        </td>
        <td  >
			<select onchange="selectBut(this,'$val[id]','$val[changeNumb]')">
		    	<option value="value1">&nbsp;&nbsp;��ѡ�����&nbsp;&nbsp;</option>
				$selectStr
			</select>
        </td>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude �鿴��ϸ���庯��
	 * @author ouyang
	 * @param
	 * @version 2010-8-12 ����06:58:49
	 */
	function c_readmain () {
		$id = isset($_GET['id'])?$_GET['id']:exit;
		$seeExa = isset($_GET["seeExa"])?$_GET["seeExa"]:"no";
		$goBack = isset($_GET["goBack"])?$_GET["goBack"]:"yes";
		$arr = $this->service->read_d($id);
//		echo "<pre>";
//		print_r($arr);
		$arrExa = "" ;
		if($seeExa == "yes"){
			$arrExa = $this->service->arrExa_d($id);
		}

		$strGoBack=" ";
		if($goBack=="yes"){
			$strGoBack=<<<EOT
			<tr align="center" class="TableHeader" height="28">
				<td colspan="6">
					<input type="button" value="��  ��" class="BigButton" onclick="history.back();" >&nbsp;
				</td>
			</tr>
EOT;
		}
		$this->arrToShow($arr);
		$this->show->assign('goback', $strGoBack );
		$this->show->assign('showExa', $this->showExa($arrExa) );
		$this->show->display($this->objPath . '_' . $this->objName . '-read');
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude �ҵĲɹ����뵥�������
	 * @author ouyang
	 * @param
	 * @version 2010-8-12 ����07:53:01
	 */
	function c_myApproval () {
		$clickNumb=isset($_GET['clickNumb'])?$_GET['clickNumb']:"1";
		$arrayPanel = array("numb"=>2,
								"clickNumb"=> $clickNumb,
								"name1"=>"���������",
								"title1"=>"����������ɹ����뵥",
								"url1"=>"?model=purchase_apply_applychange&action=myApproval&clickNumb=1",
								"name2"=>"���������",
								"title2"=>"����������ɹ����뵥",
								"url2"=>"?model=purchase_apply_applychange&action=myApproval&clickNumb=2"
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
	 * @exclude �������б�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:22:59
	 */
	function myApprovalWait($clickNumb){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$changeNumb = isset( $_GET['changeNumb'] )?$_GET['changeNumb']:"";
		$service = $this->service;
		$service->getParam($_GET);
		$searchArr = "";
		if($changeNumb!=""){
			$searchArr['seachChangeNumb'] = $changeNumb;
		}
		if($applyNumb!=""){
			$searchArr['seachBasicNumb'] = $applyNumb;
		}
		$service->__SET('searchArr', $searchArr);
		$showpage = new includes_class_page();
		$rows = $service->pageApprovalWait_d();
		//��ҳ
		$showpage->show_page(array (
			'total' => $service->count,
			'perpage' => pagenum
		));
		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('changeNumb', $changeNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showApprovalWait($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-Approval');
		unset($this->show);
		unset($service);
	}

	/**
	 * @exclude ��ʾδ�����б�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:23:11
	 */
	function showApprovalWait($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = $i%2+1;
				$str .=<<<EOT
	<tr class="TableLine$iClass">
		<td align="center" >
			$i
        </td>
        <td  >
            $val[task]
        </td>
        <td  >
            $val[changeNumb]
        </td>
        <td  >
            $val[basicNumb]
        </td>
        <td  >
            $val[createName]
        </td>
        <td  >
            $val[createTime]
        </td>
        <td >
			<a href="controller/purchase/apply/ewf_change.php?actTo=ewfExam&taskId=$val[task]&spid=$val[flowId]&billId=$val[id]&examCode=$val[code]&go=2">����</a>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * @exclude �������б�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:23:22
	 */
	function myApprovalAlready($clickNumb){
		$applyNumb = isset( $_GET['applyNumb'] )?$_GET['applyNumb']:"";
		$changeNumb = isset( $_GET['changeNumb'] )?$_GET['changeNumb']:"";
		$service = $this->service;
		$service->getParam($_GET);
		if($changeNumb!=""){
			$searchArr['seachChangeNumb'] = $changeNumb;
		}
		if($applyNumb!=""){
			$searchArr['seachBasicNumb'] = $applyNumb;
		}
		$showpage = new includes_class_page();
		$rows = $service->pageApprovalAlready_d();
		//��ҳ
		$showpage->show_page(array (
			'total' => $service->count,
			'perpage' => pagenum
		));
		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('changeNumb', $changeNumb);
		$this->show->assign('applyNumb', $applyNumb);
		$this->show->assign('list', $this->showApprovalAlready($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-Approval');
		unset($this->show);
		unset($service);
	}

	/**
	 * @exclude �����������б�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:23:32
	 */
	function showApprovalAlready($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = $i%2+1;
				$str .=<<<EOT
	<tr class="TableLine$iClass">
		<td align="center" >
			$i
        </td>
        <td  >
            $val[task]
        </td>
        <td  >
            $val[changeNumb]
        </td>
        <td  >
            $val[basicNumb]
        </td>
        <td  >
            $val[createName]
        </td>
        <td  >
            $val[createTime]
        </td>
        <td >
			<a href="?model=purchase_apply_applychange&action=readmain&id=$val[id]">�鿴</a> |
			<a href="?model=purchase_apply_applychange&action=readmain&seeExa=yes&id=$val[id]">�鿴����</a>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		$str .= '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

/*****************************************��ʾ�ָ���********************************************/


	/**
	 * @exclude ����ִ��
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:22:15
	 */
	function c_execute () {
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$object = $this->service->execute_d($id);
		if ($object) {
			msgGo("������ɣ�");
		}else{
			msgGo("����ʧ�ܣ����Ժ�������");
		}
	}

	/**
	 * @exclude �ر�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����08:51:54
	 */
	function c_close () {
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$object = $this->service->close_d($id);
		if ($object) {
			msgGo("�رճɹ���");
		}else{
			msgGo("�ر�ʧ�ܣ����Ժ�������");
		}
	}
}
?>