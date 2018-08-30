<?php
class controller_contract_sales_purchase extends controller_base_action {

	//���캯��
	function __construct() {
		$this->objName = "purchase";
		$this->objPath = "contract_sales";
		parent :: __construct();
	}

	//Ĭ�Ϸ���
	function c_index() {
		$this->showPurchList();
	}

	//��ʾ�豸-��ͬ�豸����
	function showPurchList(){
		$productName = isset( $_GET['productName'] )?$_GET['productName']:"";
		$idList = isset( $_GET['idList'] )?$_GET['idList']:"";
		$searchArr = array (
			"productName" => $productName
		);
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "productNumber,storageId");
		$showpage = new includes_class_page();
		$rows = $service->page_d($showpage);
		$this->show->assign('purdIdsArry', $idList);
		$this->show->assign('topName', "�豸-��ͬ");
		$this->show->assign('productName', $productName);
		$this->pageShowAssign();
		$this->show->assign('list', $service->showlist($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
		unset($this->show);
	}

	//��ʾ�½��ɹ��ƻ�����
//	function c_newPlan(){
//		$purchId = isset($_GET['purchId'])? substr( $_GET['purchId'],1 ):exit;
//		$this->service->getParam($_GET);
//		$listEqu = $this->service->listEqu_d($purchId);
//		if($listEqu){
//			$this->show->assign('userName', $_SESSION['USERNAME']);
//			$this->show->assign('userId', $_SESSION['USER_ID']);
//			$this->show->assign('nowTime', date("Y-m-d H:i:s"));
//			$this->show->assign('list', $this->service->newPlan($listEqu));
//
//			$planNumb = "pp-".date("YmdHis").rand(10,99);
//			$this->show->assign('planNumb', $planNumb);
//			$this->show->display($this->objPath . '_' . $this->objName . '-new');
//		}else {
//			showmsg('����','temp','button');
//		}
//		unset($this->show);
//	}

	/**
	 * �½����ۺ�ͬ�ɹ��ƻ�
	 */
//	function c_addPurchPlan(){
////		print_r('<pre>');
////		print_r($_POST["basic"]);
//		$object = $this->service->addPurchPlan_d( $_POST["basic"] );
//		if ($object) {
//			msgBack2("��ӳɹ���");
//		}else{
//			msgBack2("���ʧ�ܣ�");
//		}
//
//	}

	//��ʾ�½������ƻ�����
//	function c_newProductionPlan(){
//		$purchId = isset($_GET['purchId'])? substr( $_GET['purchId'],1 ):exit;
//		$this->service->getParam($_GET);
//		$listEqu = $this->service->listEqu_d($purchId);
//		if($listEqu){
//			$this->show->assign('userName', $_SESSION['USERNAME']);
//			$this->show->assign('userId', $_SESSION['USER_ID']);
//			$this->show->assign('nowTime', date("Y-m-d H:i:s"));
//			$this->show->assign('list', $this->newProductionPlan($listEqu));
//
//			$planNumb = "prp-".date("YmdHis").rand(10,99);
//			$this->show->assign('planNumb', $planNumb);
//			$this->show->display($this->objPath . '_production-new');
//		}else {
//			showmsg('����','temp','button');
//		}
//		unset($this->show);
//	}

	/**
	 * �����ƻ�List��ʾ�б�
	 */
//	function newProductionPlan($listEqu){
//		$str="";
//		$i = $m = 0;
//		if($listEqu){
//			foreach ($listEqu as $key => $val) {
//				$i++;
//				$allAmount = 0;
//				foreach ($val['childArr'] as $chdKey1 => $chdVal1){
//					$allAmount += $chdVal1["alreadyFlwgfollowing"];
//				}
//				$str.=<<<EOT
//		<tr height="28">
//			<td  align="center" width="5%">
//				<p class="childImg">
//					<image src="images/expanded.gif" />$i
//				</p>
//			</td>
//			<td  align="center" width="13%">
//				$val[productName]
//			</td>
//			<td  align="center" width="13%">
//				$val[productNumber]
//			</td>
//			<td  align="center" width="13%">
//				$val[productModel]
//			</td>
//			<td  align="center" width="6%">
//				<p class="allAmount">$allAmount</p>
//			</td>
//			<td align="center">
//				<table class="shrinkTable" width="100%" border="0" cellspacing="1" cellpadding="0">
//EOT;
//				foreach ($val['childArr'] as $chdKey => $chdVal){
//					$nowTime=date("Y-m-d H:i:s");
//					$str.=<<<EOT
//				<tr align="center">
//						<td width="30%">
//							$chdVal[contNumber]<br>
//							$chdVal[contName]
//						</td>
//						<td  width="12%">
//							<input type="text" name="basic[equment][$m][outputAmount]" id="planAmount$m" value="$chdVal[alreadyFlwgfollowing]" size=6 class="planAmount">
//
//							<input type="hidden" name="planAmount" value="$chdVal[alreadyFlwgfollowing]"/>
//							<input type="hidden" name="basic[equment][$m][outputAmountIssued]" value="0"/>
//							<input type="hidden" name="basic[equment][$m][proListId]" value="$chdVal[productListId]" />
//							<input type="hidden" name="basic[equment][$m][productName]" value="$chdVal[productName]" />
//							<input type="hidden" name="basic[equment][$m][productId]" value="$chdVal[productId]" />
//							<input type="hidden" name="basic[equment][$m][productNumb]" value="$chdVal[productNumber]" />
//							<input type="hidden" name="basic[equment][$m][storageTypeId]" value="$chdVal[storageTypeId]" />
//							<input type="hidden" name="basic[equment][$m][storageTypeName]" value="$chdVal[storageTypeName]" />
//							<input type="hidden" name="basic[equment][$m][storageId]" value="$chdVal[storageId]" />
//							<input type="hidden" name="basic[equment][$m][storageName]" value="$chdVal[storageName]" />
//							<input type="hidden" name="basic[equment][$m][contNumb]" value="$chdVal[contNumber]" />
//							<input type="hidden" name="basic[equment][$m][contId]" value="$chdVal[contId]" />
//							<input type="hidden" name="basic[equment][$m][contOnlyId]" value="$chdVal[contOnlyId]" />
//							<input type="hidden" name="basic[equment][$m][contName]" value="$chdVal[contName]" />
//							<input type="hidden" name="basic[equment][$m][contEquId]" value="$chdVal[id]" />
//							<input type="hidden" name="basic[equment][$m][outputBeginDate]" value="$nowTime" />
//						</td>
//						<td width="20%">
//							&nbsp;<input type="text" id="outputEndDate$m" name="basic[equment][$m][outputEndDate]" size="9" maxlength="12" class="BigInput" value="" onfocus="WdatePicker()" readonly />
//						</td>
//						<td>
//							<textarea rows="2" cols="25" name="basic[equment][$m][remark]" id="remark$m"></textarea>
//						</td>
//					</tr>
//EOT;
//					++$m;
//				}
//				$str.=<<<EOT
//        	</table>
//        	<div class="readThisTable"><����չ�����豸������Ϣ></div>
//        </td>
//    </tr>
//EOT;
//			}
//		}
//		return $str;
//	}

	/**
	 * �½������ƻ�
	 */
//	function c_addProductionPlan(){
//		$object = $this->service->addProductionPlan_d( $_POST ['basic'] );
//		if ($object) {
//			msgBack2("��ӳɹ���");
//		}else{
//			msgBack2("���ʧ�ܣ�");
//		}
//	}

//1.
//��Ҫһ���ӿں��� �ҵ����������������������ֿ�����飬��ʽ��
//array(
//	"0"=>array(
//		"stockName"=> "�ֿ�����"��
//		"stockId"=��"�ֿ�ID"
//	),
//	xxxxxxx
//)
//
//2
//�Ҹ��㴫����2��ֵ
//stockId="�ֿ�Id",
//arrEquIds = "1,2,3"//����ǲ�ƷId���ַ�������
//
//���ظ�������  ��ʽ
//array(
//	"0"=>array(
//	"equId" => "1",
//	"equ" =�� "����ִ������"
//	),
//	XXXX
//)


}
?>