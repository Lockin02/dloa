<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @author qian
 * @Date 2011��3��3�� 10:49:20
 * @version 1.0
 * @description:�����̻� Model��
 */
class model_projectmanagent_chance_chance extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance";
		$this->sql_map = "projectmanagent/chance/chanceSql.php";
		parent :: __construct();
		//���ó�ʼ�����������
		parent :: setObjAss();

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'transferred',
				'statusCName' => '��ת�̻�',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'follow',
				'statusCName' => '������',
				'key' => '0'
			),
			2 => array (
				'statusEName' => 'confer',
				'statusCName' => 'Ǣ̸��',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'close',
				'statusCName' => '�ر�',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'transchance',
				'statusCName' => '�����ɺ�ͬ',
				'key' => '4'
			),
			5 => array (
				'statusEName' => 'save',
				'statusCName' => '����',
				'key' => '5'
			),
			6 => array (
				'statusEName' => 'pause',
				'statusCName' => '��ͣ',
				'key' => '6'
			)
		);

		//���ó�ʼ�����������
		parent :: setObjAss();
	}


	//����json�ַ���
	function resolve_d($id){
		$obj = $this->find(array( 'id' => $id ),null,'id,deploy');

//		print_r($obj);
		$goodsCacheDao = new model_goods_goods_goodscache();
		$newArr = $goodsCacheDao->changeToProduct_d($obj['deploy']);
		if(is_array($newArr)&&count($newArr)){
			return $newArr;
		}else{
			return 0;
		}
	}

	/**
	 * �޸ĸ����˵�edit����
	 */
	function TrackmanEdit_d($authorizeInfo,$object, $isEditInfo = false) {
		try {
			$this->start_d();
			//�޸�������Ϣ

			parent :: edit_d($object, true);

			$chanceId = $object['id'];

			//��ȡ������ID�����֣������������������һ����Ԫ��
			$tracker = array ();
			$trackerId = explode(",", $object['trackmanId']);
			$trackman = explode(",", $object['trackman']);

			//���������
			foreach ($trackerId as $key => $val) {
				$tracker[$key]['trackmanId'] = $val;
				$tracker[$key]['trackman'] = $trackman[$key];
				$tracker[$key]['chanceId'] = $chanceId;
				$tracker[$key]['chanceCode'] = $object['chanceCode'];
				$tracker[$key]['chanceName'] = $object['chanceName'];
			}

			//����ӱ������
			$chanceequDao = new model_projectmanagent_chancetracker_chancetracker();
			$chanceequDao->delete(array (
				'chanceId' => $chanceId
			));
			$chanceequDao->createBatch($tracker);

			 //�����̻��Ŷӳ�ԱȨ��
            foreach($authorizeInfo as $k => $v){
            	if(!empty($v['limitInfo'])){
                    $authorizeInfo[$k]['limitInfo'] = implode(",",$v['limitInfo']);
            	}else{
            		$authorizeInfo[$k]['limitInfo'] = "";
            	}
            }
			if (!empty ($authorizeInfo)) {
				$authorizeDao = new model_projectmanagent_chance_authorize();
				$authorizeDao->delete(array('chanceId' => $chanceId));
				$authorizeDao->createBatch($authorizeInfo, array (
					'chanceId' => $chanceId
				));
			}
            //����ʱ��
            $this->updateChanceNewDate($chanceId);
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �ƽ��̻�����
	 */
	function transfer_d($object) {
		try {
			$this->start_d();

			$chanceId = $object['id'];
			$sql = "update " . $this->tbl_name . " set prinvipalName = '" . $object['trackman'] . "',prinvipalId = '" . $object['trackmanId'] . "' where id = '$chanceId'";
			$this->_db->query($sql);

            //����ʱ��
           $this->updateChanceNewDate($chanceId);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/**
	 * ��������
	 */
	function handleDate($object){
             if(empty($object['predictContractDate'])){
             	$object['predictContractDate'] = "0000-00-00";
             }
             if(empty($object['predictExeDate'])){
             	$object['predictExeDate'] = "0000-00-00";
             }
             if(empty($object['newUpdateDate'])){
             	$object['newUpdateDate'] = "0000-00-00";
             }
             if(empty($object['closeTime'])){
             	$object['closeTime'] = "0000-00-00";
             }
             if(empty($object['ExaDT'])){
             	$object['ExaDT'] = "0000-00-00";
             }
             if(empty($object['changeEquDate'])){
             	$object['changeEquDate'] = "0000-00-00";
             }
             if(empty($object['warnDate'])){
             	$object['warnDate'] = "0000-00-00";
             }
             if(empty($object['contractTurnDate'])){
             	$object['contractTurnDate'] = "0000-00-00";
             }
            return $object;
	}
	/**
	 * ��дadd_d����
	 */
	function add_d($authorizeInfo,$object) {
		try {
			$this->start_d();
			 //��������
//            $object = $this->handleDate($object);
			//�̻����
			$chanceCodeDao = new model_common_codeRule();
			$object['chanceCode'] = $chanceCodeDao->newChanceCode($object);
			//�����̻����
//			$chanceMoney = "0";
//			if (!empty ($object['goods'])) {
//				foreach ($object['goods'] as $k => $v) {
//					$chanceMoney += $v['money'];
//				}
//			}
//			$object['chanceMoney'] = $chanceMoney;
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict();
			$object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);
			$object['chanceTypeName'] = $datadictDao->getDataNameByCode($object['chanceType']);
			$object['chanceNatureName'] = $datadictDao->getDataNameByCode($object['chanceNature']);
			$object['signSubjectName'] = $datadictDao->getDataNameByCode($object['signSubject']);

			$object['newUpdateDate'] = date("Y-m-d");

			//����������Ϣ
			$newId = parent :: add_d($object, true);

            //�����̻��Ŷӳ�ԱȨ��
            foreach($authorizeInfo as $k => $v){
            	if(!empty($v['limitInfo'])){
                    $authorizeInfo[$k]['limitInfo'] = implode(",",$v['limitInfo']);
            	}else{
            		$authorizeInfo[$k]['limitInfo'] = "";
            	}
            }
			if (!empty ($authorizeInfo)) {
				$authorizeDao = new model_projectmanagent_chance_authorize();
				$authorizeDao->createBatch($authorizeInfo, array (
					'chanceId' => $newId
				));
			}
			//�ͻ���ϵ��
			if (!empty ($object['linkman'])) {
				$linkmanDao = new model_projectmanagent_chance_linkman();
				$linkmanDao->createBatch($object['linkman'], array (
					'chanceId' => $newId
				), 'linkmanName');
			}
			//��Ʒ
			if (!empty ($object['goods'])) {
				$orderequDao = new model_projectmanagent_chance_goods();
				$orderequDao->createBatch($object['goods'], array (
					'chanceId' => $newId
				), 'goodsId');
			}

			//�豸Ӳ��
			if (!empty ($object['hardware'])) {
				$hardwareDao = new model_projectmanagent_chance_hardware();
				$hardwareDao->createBatch($object['hardware'], array (
					'chanceId' => $newId
				), 'hardwareId');
			}

			//����
			$competitor = new model_projectmanagent_chance_competitor();
			$competitor->createBatch($object['competitor'], array (
				'chanceId' => $newId
			), 'competitor');
			//			//��Ʒ
			//			if (!empty ($object['product'])) {
			//				$orderequDao = new model_projectmanagent_chance_product();
			//				$orderequDao->createBatch($object['product'], array (
			//					'chanceId' => $newId
			//				), 'conProductName');
			//			}
			//��ȡ������ID�����֣������������������һ����Ԫ��
			$tracker = array ();
			$trackerId = explode(",", $object['trackmanId']);
			$trackman = explode(",", $object['trackman']);

			//���������
			foreach ($trackerId as $key => $val) {
				$tracker[$key]['trackmanId'] = $val;
				$tracker[$key]['trackman'] = $trackman[$key];
				$tracker[$key]['chanceId'] = $newId;
				$tracker[$key]['chanceCode'] = $object['chanceCode'];
				$tracker[$key]['chanceName'] = $object['chanceName'];
			}

			$trackerDao = new model_projectmanagent_chancetracker_chancetracker();
			$trackerDao->createBatch($tracker);

			//���������ƺ�Id
			$this->updateObjWithFile($newId);

			//��������ʼ�
			$this->chanceToMail($object);

			$this->commit_d();
//						$this->rollBack();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

    /**
     * �̻�������������ʼ�
     */
    function chanceToMail($object){
       //�̻��Ŷӳ�Ա
       if(!empty($object['trackmanId'])){
       	  $trackArr = explode(",",$object['trackmanId']);
       	  foreach($trackArr as $k=>$v){
            $tomail = $v;
			$addmsg = "  ���ã�<br/>���Ϊ��" .$object['chanceCode']. "�����̻��Ѿ�������" .
					"���ѽ��������Ӧ�̻�����Ŀ�Ŷӡ�<br/>" .
					"�̻���� :��<span style='color:blue'>".$object['chanceCode']."</span>   �̻����� ��<span style='color:blue'>".$object['chanceName']."</span>" .
				    "<br/>  �������ڡ�ҵ�����->�����̻�->�ҵ�����->�̻���Ϣ�� �б����ҵ������̻���Ϣ";
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->chanceMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_sale_chance", "����", "ͨ��", $tomail, $addmsg);
       	  }
       }
        //�̻���������
            $tomail = $object['areaPrincipalId'];
			$addmsg = "".$object['areaPrincipal']."  ���ã�<br/>���Ϊ��" .$object['chanceCode']. "�����̻��Ѿ�������" .
					"�̻���������Ϊ �� ".$object['areaName']."��<br/>" .
					"�̻���� :��<span style='color:blue'>".$object['chanceCode']."</span>   �̻����� ��<span style='color:blue'>".$object['chanceName']."</span>" .
				    "<br/>  �������ڡ�ҵ�����->�����̻�->�̻���Ϣ�� �б��ڲ鿴�����̻���Ϣ";
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->chanceMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_sale_chance", "����", "ͨ��", $tomail, $addmsg);

    }


	/**
	 * ͨ���̻�ID �������н����������ID
	 */
	function findBorrow($chanceId) {
		$cond = array (
			'chanceId' => $chanceId
		);
		$borrowDao = new model_projectmanagent_borrow_borrow();
		$borrowId = $borrowDao->find($cond, '', 'id');
		return $borrowId;
	}

	/**
	 * �̻�����
	 */
	function updateChance_d($obj) {
		try {
			$this->start_d();
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict();
			$obj['customerTypeName'] = $datadictDao->getDataNameByCode($obj['customerType']);
			$obj['chanceTypeName'] = $datadictDao->getDataNameByCode($obj['chanceType']);
			$obj['orderNatureName'] = $datadictDao->getDataNameByCode($obj['orderNature']);
			//�����̻����
//			$chanceMoney = "0";
//			if (!empty ($obj['goods'])) {
//				foreach ($obj['goods'] as $k => $v) {
//					if (!isset ($v['isDelTag'])) {
//						$chanceMoney += $v['money'];
//					}
//				}
//			}
//			$obj['chanceMoney'] = $chanceMoney;
			//���OldId
			$obj['oldId'] = $obj['id'];
			$forArr = array (
				"linkman",
				"product",
				"goods",
				"competitor",
				"hardware"
			);
			foreach ($forArr as $key => $val) {
				foreach ($obj[$val] as $k => $v) {
					$obj[$val][$k]['oldId'] = $obj[$val][$k]['id'];
				}
			}
			//���Ԥ�ƺ�ִͬ������û�䣬��unset������ֶΣ���Ȼ�����¼��Ȼ���¼����Ӧ���Ǳ��ͨ�ô�������⣬����ô����
			if($obj['predictExeDate'] == $obj['oldPredictExeDate']){
				unset($obj['predictExeDate']);
			}
			//�����������
			$changeLogDao = new model_common_changeLog('chance', false);
			$changeLogDao->addLog($obj);
			//�޸�������Ϣ
			$obj['newUpdateDate'] = date("Y-m-d");
			parent :: edit_d($obj, true);

			$chanceId = $obj['oldId'];
			//����ӱ���Ϣ
			//�ͻ���ϵ��
			$linkmanDao = new model_projectmanagent_chance_linkman();
			$linkmanDao->delete(array (
				'chanceId' => $chanceId
			));
			foreach ($obj['linkman'] as $key => $val) {
				if (isset ($val['isDelTag'])) {
					unset($obj['linkman'][$key]);
				}
			}
			$linkmanDao->createBatch($obj['linkman'], array (
				'chanceId' => $chanceId
			), 'linkmanName');
			//��Ʒ�嵥
// 			$orderequDao = new model_projectmanagent_chance_product();
// 			foreach ($obj['product'] as $key => $val) {
// 				if (!empty ($val['oldId'])) {
// 					$val['id'] = $val['oldId'];
// 					$orderequDao->edit_d($val);
// 				} else {
// 					$orderequDao->create(array (
// 						$obj['product'][$key]
// 					), array (
// 						'chanceId' => $chanceId
// 					), 'conProductName');
// 				}
// 				if (isset ($val['isDelTag'])) {
// 					$orderequDao->updateField(array (
// 						'id' => $val['oldId']
// 					), "isDel", "1");
// 				}
// 			}
			$Dao = new model_projectmanagent_chance_product();
			// ��Ʒ������
			$newProLineStr = "";
			foreach ($obj['product'] as $key => $val) {
				if (isset ($val['isDelTag'])) {
					$Dao->updateField(array ('id' => $val['id']), "isDel", "1");
				} else {
					if (empty ($val['id'])) {
						$Dao->createBatch(array ($obj['product'][$key]), array ('chanceId' => $chanceId), 'conProductName');
					} else {
						$Dao->edit_d($val);
					}
					$newProLineStr .= $val['newProLineCode'] . ",";
				}
			}
			//���²�Ʒ������
			$this->update(array('id' => $chanceId), array('newProLineStr' => rtrim($newProLineStr,',')));
			//�����Ʒ�µ�Ĭ������
			$findEqu = "select id,chanceId,conProductId,conProductName,deploy from oa_sale_chance_product where chanceId=".$chanceId." and isDel=0";
			$equArr = $this->_db->getArray($findEqu);
			$allDao = new model_common_contract_allsource();
			$equInfo = array();
			$equDao = new model_projectmanagent_chance_chanceequ();
			$equDao->delete(array ( 'chanceId' => $chanceId));
			foreach($equArr as $k => $v){
				$equInfoTemp = $allDao->getProductEqu_d($v['id'],"chanceEqu");
				foreach($equInfoTemp as $key=>$val){
					$equInfo[$key]['chanceId'] = $chanceId;
					$equInfo[$key]['productName'] = $val['productName'];
					$equInfo[$key]['productId'] = $val['productId'];
					$equInfo[$key]['productCode'] = $val['productCode'];
					$equInfo[$key]['productModel'] = $val['productModel'];
					$equInfo[$key]['conProductId'] = $v['conProductId'];
					$equInfo[$key]['conProductName'] = $v['conProductName'];
					$equInfo[$key]['number'] = $val['number'];
					$equInfo[$key]['warrantyPeriod'] = $val['warranty'];
				}
				//����
				$equDao->createBatch($equInfo);
				$equInfo = "";
			}
			//����ʱ��
			$this->updateChanceNewDate($chanceId);
			//��Ʒ
			$goods = new model_projectmanagent_chance_goods();
			$goods->delete(array (
				'chanceId' => $chanceId
			));
			$goods->createBatch($obj['goods'], array (
				'chanceId' => $chanceId
			), 'goodsId');


			//�豸Ӳ��
			$hardwareDao = new model_projectmanagent_chance_hardware();
			$hardwareDao->delete(array (
				'chanceId' => $chanceId
			));
			$hardwareDao->createBatch($obj['hardware'], array (
				'chanceId' => $chanceId
			), 'hardwareId');
			//����
			$competitor = new model_projectmanagent_chance_competitor();
			$competitor->delete(array (
				'chanceId' => $chanceId
			));
			foreach ($obj['competitor'] as $key => $val) {
				if (isset ($val['isDelTag'])) {
					unset($obj['competitor'][$key]);
				}
			}
			$competitor->createBatch($obj['competitor'], array (
				'chanceId' => $chanceId
			), 'competitor');

			//��ȡ������ID�����֣������������������һ����Ԫ��
			$tracker = array ();
			$trackerId = explode(",", $obj['trackmanId']);
			$trackman = explode(",", $obj['trackman']);

			//���������
			foreach ($trackerId as $key => $val) {
				$tracker[$key]['trackmanId'] = $val;
				$tracker[$key]['trackman'] = $trackman[$key];
				$tracker[$key]['chanceId'] = $chanceId;
				$tracker[$key]['chanceCode'] = $obj['chanceCode'];
				$tracker[$key]['chanceName'] = $obj['chanceName'];
			}

			$trackerDao = new model_projectmanagent_chancetracker_chancetracker();
			$trackerDao->delete(array ('chanceId' => $chanceId));
			$trackerDao->createBatch($tracker);
			
			//Ӯ��Ϊ0���ر��̻�
			if($obj['winRate'] == '0'){
				$this->handleCloseChance_d($chanceId);
			}

			$this->commit_d();
//							$this->rollBack();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ������¼�¼
	 * @param unknown $layout
	 */
	function updateRecord_d($layout,$id){
		$sql = "update ".$this->tbl_name. " set updateRecord = '$layout' where id='$id'";
		return $this->query($sql);
	}

	/**
	 * ��ȡ�̻����¼�¼
	 */
	function getUpdateRecord_d($id){
		$sql = "select updateRecord from ".$this->tbl_name. " where id = '$id'";
		return $this->_db->getArray($sql);
	}

	/**
	 * ���¾�������
	 */
	function competitorAdd_d($obj) {
		try {
			$linkmanDao = new model_projectmanagent_chance_competitor();
			$linkmanDao->delete(array (
				'chanceId' => $obj['chanceId']
			));
			$linkmanDao->createBatch($obj['competitor'], array (
				'chanceId' => $obj['chanceId']
			), 'competitor');

			$this->commit_d();
			//				$this->rollBack();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ������ϸ������Ϣ
	 */
	function prodcutInfoAdd_d($obj) {
		try {
			$Dao = new model_projectmanagent_chance_product();
			// ��Ʒ������
			$newProLineStr = "";
			foreach ($obj['product'] as $key => $val) {
				if (isset ($val['isDelTag'])) {
					$Dao->updateField(array ('id' => $val['id']), "isDel", "1");
				} else {
					if (empty ($val['id'])) {
						$Dao->createBatch(array ($obj['product'][$key]), array ('chanceId' => $obj['chanceId']), 'conProductName');
					} else {
						$Dao->edit_d($val);
					}
					$newProLineStr .= $val['newProLineCode'] . ",";
				}
			}
			//���²�Ʒ������
			$this->update(array('id' => $obj['chanceId']), array('newProLineStr' => rtrim($newProLineStr,',')));
            //�����Ʒ�µ�Ĭ������
            $findEqu = "select id,chanceId,conProductId,conProductName,deploy from oa_sale_chance_product where chanceId=".$obj['chanceId']." and isDel=0";
            $equArr = $this->_db->getArray($findEqu);
            $allDao = new model_common_contract_allsource();
            $equInfo = array();
            $equDao = new model_projectmanagent_chance_chanceequ();
            $equDao->delete(array ( 'chanceId' => $obj['chanceId'] ));
           foreach($equArr as $k => $v){
              $equInfoTemp = $allDao->getProductEqu_d($v['id'],"chanceEqu");
              foreach($equInfoTemp as $key=>$val){
              	  $equInfo[$key]['chanceId'] = $obj['chanceId'];
              	  $equInfo[$key]['productName'] = $val['productName'];
              	  $equInfo[$key]['productId'] = $val['productId'];
              	  $equInfo[$key]['productCode'] = $val['productCode'];
              	  $equInfo[$key]['productModel'] = $val['productModel'];
              	  $equInfo[$key]['conProductId'] = $v['conProductId'];
              	  $equInfo[$key]['conProductName'] = $v['conProductName'];
              	  $equInfo[$key]['number'] = $val['number'];
              	  $equInfo[$key]['warrantyPeriod'] = $val['warranty'];
              }
		      //����
			  $equDao->createBatch($equInfo);
			  $equInfo = "";
           }
           //����ʱ��
           $this->updateChanceNewDate($obj['chanceId']);
			$this->commit_d();
//							$this->rollBack();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �����̻��ƽ���Ϣ
	 */
	function boostChance_d($obj) {
		try {
			$boostDao = new model_projectmanagent_chance_boost();
			//Ӯ��
			if ($obj['winRate'] != $obj['oldwinRate']) {
				$arr = array (
					"chanceId" => $obj['chanceId'],
					"boostType" => "winRate",
					"boostValue" => $obj['winRate'],
					"oldValue" => $obj['oldwinRate'],

				);
				$boostDao->add_d($arr, true);
				$updateWinRate = "update oa_sale_chance set winRate='" . $obj['winRate'] . "' where id=" . $obj['chanceId'] . "";
				$this->query($updateWinRate);
			}
			//�̻��׶�
			if ($obj['chanceStage'] != $obj['oldchanceStage']) {
				$arrA = array (
					"chanceId" => $obj['chanceId'],
					"boostType" => "chanceStage",
					"boostValue" => $obj['chanceStage'],
					"oldValue" => $obj['oldchanceStage'],

				);
				$boostDao->add_d($arrA, true);
				$updatechanceStage = "update oa_sale_chance set chanceStage='" . $obj['chanceStage'] . "' where id=" . $obj['chanceId'] . "";
				$this->query($updatechanceStage);
			}
			//����ʱ��
			$this->updateChanceNewDate($obj['chanceId']);
			//�ر��̻�
	      if($obj['winRate'] == '0' || $obj['chanceStage'] == 'SJJD06' || $obj['chanceStage'] == 'SJJD07'){
	      	 $this->handleCloseChance_d($obj['chanceId']);
	      }else{
	      	 $this->handlerecoverChance_d($obj['chanceId']);
	      }

	       //���²�Ʒ��Ϣ
	       //��Ʒ
			if (!empty ($obj['goods'])) {
				$orderequDao = new model_projectmanagent_chance_goods();
				$orderequDao->createBatch($obj['goods'], array (
					'chanceId' => $obj['chanceId']
				), 'goodsId');
			}
	       	//�����̻����
			if (!empty ($obj['goods'])) {
				$chanceMoney = "0";
				foreach ($obj['goods'] as $k => $v) {
//					$chanceMoney += $v['money'];
				}
				$sql = "update oa_sale_chance set chanceMoney = '".$chanceMoney."' where id='".$obj['chanceId']."'";
			    $this->query($sql);
			}

			//�ر���Ϣ
			if (!empty ($obj['closeRegard'])) {
				$info = $obj['closeRegard'];
				$sql = "update oa_sale_chance set closeRegard = '".$info."' where id='".$obj['chanceId']."'";
			    $this->query($sql);
			}

			$this->commit_d();
//							$this->rollBack();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/**************************************************�ӿ�����÷���****************************************************/

	/**
	 * ����ɾ������
	 */
	function deletesInfo_d($ids) {
		try {
			$this->deletes($ids);
			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}

	}

	/**
	 * ����ת�̻�--add����
	 */
	function addChance_d($rows) {
		if (is_array($rows)) {
			try {
				$this->start_d();

				//���������ֵ��ֶ�
				$datadictDao = new model_system_datadict_datadict();
				$rows['orderNatureName'] = $datadictDao->getDataNameByCode($rows['orderNature']);

				//Ĭ��״̬Ϊ����ת�̻���
				$rows['status'] = $this->statusDao->statusEtoK('transferred');
				$rows['ExaStatus'] = "����";

				$chanceId = parent :: add_d($rows, true);

				//�����Ӧ������״̬
				$cluesId = $rows['cluesId'];
				$cluesDao = new model_projectmanagent_clues_clues();
				$condiction = array (
					"id" => $cluesId
				);
				$cluesDao->updateField($condiction, "status", "1");

				//����ӱ���Ϣ

				$linkmanDao = new model_projectmanagent_chance_linkman();
				//��ӿͻ���ϵ��  add by suxc 2011-08-13
				if (is_array($rows['linkman'])) {
					foreach ($rows['linkman'] as $lKey => $lVal) {
						if ($lVal['linkmanId'] != "") {
							$lVal['chanceId'] = $chanceId;
							$lVal['chanceCode'] = $rows['chanceCode'];
							$lVal['chanceName'] = $rows['chanceName'];
							$lVal['roleName'] = $datadictDao->getDataNameByCode($lVal['roleCode']);
							$linkmanDao->add_d($lVal);
						}
					}
				}
				//�豸
				$chanceequDao = new model_projectmanagent_chance_chanceequ();
				if (!empty ($rows['chanceequ'])) {
					$chanceequDao->createBatch($rows['chanceequ'], array (
						'chanceId' => $chanceId
					), 'productName');
					$licenseDao = new model_yxlicense_license_tempKey();
					$licenseDao->updateLicenseBacth_d(array (
						'objId' => $chanceId,
						'objType' => $this->tbl_name,
						'extType' => $chanceequDao->tbl_name
					), 'chanceId', 'license');
				}
				//�Զ����嵵
				$customizelistDao = new model_projectmanagent_chance_customizelist();
				if (!empty ($rows['customizelist'])) {
					$customizelistDao->createBatch($rows['customizelist'], array (
						'chanceId' => $chanceId
					), 'productName');
					$licenseDao = new model_yxlicense_license_tempKey();
					$licenseDao->updateLicenseBacth_d(array (
						'objId' => $chanceId,
						'objType' => $this->tbl_name,
						'extType' => $customizelistDao->tbl_name
					), 'chanceId', 'license');
				}
				//��ȡ������ID�����֣������������������һ����Ԫ��
				$tracker = array ();
				$trackerId = explode(",", $rows['trackmanId']);
				$trackman = explode(",", $rows['trackman']);

				//���������
				foreach ($trackerId as $key => $val) {
					$tracker[$key]['trackmanId'] = $val;
					$tracker[$key]['trackman'] = $trackman[$key];
					$tracker[$key]['chanceId'] = $chanceId;
					$tracker[$key]['chanceCode'] = $rows['chanceCode'];
					$tracker[$key]['chanceName'] = $rows['chanceName'];
				}

				$trackerDao = new model_projectmanagent_chancetracker_chancetracker();
				$trackerDao->createBatch($tracker);

				//�����������Ϣ
				foreach ($rows as $key => $val) {
					$chance[0] = Array (
						'cluseId' => $rows['cluesId'],
						'cluseCode' => $rows['cluesName'],
						'chanceId' => $chanceId,
						'chanceCode' => $rows['chanceCode'],
						'projectId' => "",
						'projectCode' => "",
						'projectType' => "",
						'contractUnique' => "",
						'contractCode' => "",
						'contractNumber' => "",
						'contractId' => "",
						'contractType' => ""
					);
				}
				//���������ƺ�Id
				$this->updateObjWithFile($chanceId);
				$this->objass->addModelObjs('projectInfo', $chance);
				//                 $this->rollBack();
				$this->commit_d();
				return $chanceId;
			} catch (Exception $e) {
				$this->rollBack();
				return false;
			}
		}
	}

	/**
	 * @description ����������״̬
	 * @date 2011-03-09 14:15
	 */
	function updateClues_d($id) {
		if ($id) {
			//���ݺ�ͬ��IDֵ�ҵ�������IDֵ
			$cond = array (
				"id" => $id
			);
			$cluesId = $this->find($cond, null, "cluesId");

			$cluesDao = new model_projectmanagent_clues_clues();
			//��������ID�������������״ֵ̬
			$condiction = array (
				"cluesId" => $cluesId
			);
			$cluesDao->updateField($condiction, "status", "1");
		}
	}

	/**
	 * @description �ر��̻��ı��淽��
	 *
	 */
	function closeChance_d($rows) {
		try {
			$this->start_d();

			$chanceId = $rows['chanceId'];
			$updateSql = "update oa_sale_chance set status='3' where id=$chanceId";
			$this->query($updateSql);
			//����ر���Ϣ
		   $closeDao = new model_projectmanagent_chance_close();
		   $id = $closeDao->add_d($rows,true);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * @description ��ͣ�̻��ı��淽��
	 *
	 */
	function pauseChance_d($rows) {
		if (is_array($rows)) {
			//ִ�йرղ�����ʱ�򣬽�״ֵ̬��Ϊ���رա�
			$rows['chance']['status'] = $this->statusDao->statusEtoK('pause');
			$rows['chance']['closeTime'] = date('Y-m-d');
			$rows['chance']['closeId'] = $_SESSION['USER_ID'];
			$rows['chance']['closeName'] = $_SESSION['USERNAME'];
			$condiction = array (
				'id' => $rows['id']
			);
			$flag = $this->update($condiction, $rows['chance']);
		}
		if ($flag)
			return true;
		return false;
	}

	/**
	 * @description �ָ��̻��ı��淽��
	 *
	 */
	function recoverChance_d($rows) {
		try {
			$this->start_d();

			$chanceId = $rows['chanceId'];
			$updateSql = "update oa_sale_chance set status='5' where id=$chanceId";
			$this->query($updateSql);
			//����ر���Ϣ
		   $closeDao = new model_projectmanagent_chance_close();
		   $id = $closeDao->add_d($rows,true);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/***********************************************************************/
	/**
	 * ����������ѡ������� ҳ����Ҫ����Ϣ
	 */
	function deptTrackInfo($row) {

		$dept = new model_common_otherdatas();
		$deptName = $dept->getUserDatas($_SESSION['USER_ID'], 'DEPT_NAME'); //��ǰ��¼�����ڲ�������
		$ditpId = $_SESSION['DEPT_ID']; //��ǰ��¼�����ڲ���Id
		$row['deptName'] = $deptName;
		$row['deptId'] = $row['deptName'];
		$trackmanInfo = $row['trackmaninfo'];
		$deptMan = array (); //�����¼�����ڲ��Ÿ���������
		$deptManOther = array (); //����������
		foreach ($trackmanInfo as $key => $val) {
			$trackmanInfo[$key]['deptName'] = $dept->getUserDatas($trackmanInfo[$key]['trackmanId'], 'DEPT_NAME'); //���ݵ�¼��ID �鵽�����ڲ�������
			if ($trackmanInfo[$key]['deptName'] == $deptName) {
				$deptMan[$key]['name'] = $trackmanInfo[$key]['trackman'];
				$deptMan[$key]['id'] = $trackmanInfo[$key]['trackmanId'];
			} else {
				$deptManOther[$key]['name'] = $trackmanInfo[$key]['trackman'];
				$deptManOther[$key]['id'] = $trackmanInfo[$key]['trackmanId'];
			}
		}

		foreach ($deptMan as $key => $val) {
			$row['deptman'][$key] = $deptMan[$key]['name'];
			$row['deptmanId'][$key] = $deptMan[$key]['id'];
		}
		foreach ($deptManOther as $key => $val) {
			$row['deptManOther'][$key] = $deptManOther[$key]['name'];
			$row['deptManOtherId'][$key] = $deptManOther[$key]['id'];
		}
		return $row;
	}
	/**
	 *��������ָ�������Ÿ����˷���
	 */
	function deptTrack_d($object) {
		try {
			$this->start_d();
			$chanceId = $object['id'];
			//�����̻�����ĸ�������Ϣ
			$deptTrackmanNew = explode(',', $object['deptTrackmanOther']);
			$trackman = explode(',', $object['trackman']);

			foreach ($trackman as $key => $val) {
				array_push($deptTrackmanNew, $trackman[$key]);
			}

			$deptTrackmanA = $deptTrackmanNew; //��������˴ӱ�����Ҫ������
			$deptTrackmanNew = implode(',', $deptTrackmanNew);

			//�޸��̻�����ĸ�������Ϣ
			$sql = "update " . $this->tbl_name . " set trackman = '" . $deptTrackmanNew . "' where id = '$chanceId'";
			$this->_db->query($sql);

			//�޸ĸ����˴ӱ������Ϣ
			$deptTrackmanNewId = explode(',', $object['deptTrackmanOtherId']);
			$trackmanId = explode(',', $object['trackmanId']);
			foreach ($trackmanId as $key => $val) {
				array_push($deptTrackmanNewId, $trackmanId[$key]);
			}

			$trackmanNews = array (); //Ҫ������ٱ������
			foreach ($deptTrackmanNewId as $key => $val) {
				$trackmanNews[$key]['trackmanId'] = $val;
				$trackmanNews[$key]['trackman'] = $deptTrackmanA[$key];
				$trackmanNews[$key]['chanceId'] = $chanceId;
				$trackmanNews[$key]['chanceName'] = $object['chanceName'];
			}

			$trackmanDao = new model_projectmanagent_chancetracker_chancetracker();
			$trackmanDao->delete(array (
				'chanceId' => $chanceId
			));
			$trackmanDao->createBatch($trackmanNews);
			//�޸ĸ����˴ӱ������Ϣ ����

			//           $this->rollBack();
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/******************************************************************************/
	/*�����������*/
	function c_configuration($proId, $Num, $trId) {
		$configurationDao = new model_stock_productinfo_configuration();
		$sql = "select configId,configNum from " . $configurationDao->tbl_name . " where hardWareId = $proId and configId > 0";
		$configId = $this->_db->getArray($sql);
		if (!empty ($configId)) {
			foreach ($configId as $k => $v) {
				$configIdA[$k] = $v['configId'];
			}
			$configIdA = implode(",", $configIdA);
			$productInfoDao = new model_stock_productinfo_productinfo();
			$sql = "select * from " . $productInfoDao->tbl_name . " where id in($configIdA)";
			$infoArr = $this->_db->getArray($sql);
			foreach ($infoArr as $key => $val) {
				foreach ($configId as $keyo => $valo) {
					if ($infoArr[$key]['id'] == $configId[$keyo]['configId']) {
						$infoArr[$key]['configNum'] = $configId[$keyo]['configNum'];
						$infoArr[$key]['isCon'] = $trId;
					}
				}
			}
			$equDao = new model_projectmanagent_chance_chanceequ();
			$configArr = $equDao->configTable($infoArr, $Num);
		}
		return $configArr;
	}
	/******************************************************************************/
	/**
	 * �̻��ƽ���Ϣ
	 */
	function boostChanceStageInfo_d($chanceId) {
		$boostsql = "select * from oa_sale_chance_boost where chanceId=$chanceId and boostType='chanceStage'";
		$boostInfo = $this->_db->getArray($boostsql);
		//���������ֵ��ֶ�
		$datadictDao = new model_system_datadict_datadict();
		foreach ($boostInfo as $k => $v) {
			$boostVale = $datadictDao->getDataNameByCode($v['boostValue']);
			$oldValue = $datadictDao->getDataNameByCode($v['oldValue']);
			$boostStr .= "-->" . "<span style='color:blue' title = '�ƽ��ˣ� " . $v['createName'] . "
�ƽ�ʱ�� �� " . $v['createTime'] . "
			        					'>" . $boostVale . "<span>";
			$boostList .= "<tr><td style='text-align: left'>��" . $v['createName'] . "���ڡ�" . $v['createTime'] . "�����̻��� �� " . $oldValue . " ���ƽ��� �� " . $boostVale . " ��</td><tr>";
		}
		if ($boostInfo) {
			$str .=<<<EOT
				<tr align="center">
					<td>
						<b>�� $boostStr</b>
					</td>

			</tr>
               $boostList
EOT;
		} else {
			$str .=<<<EOT
				<tr align="center">
					<td>
						<b>���ƽ���Ϣ</b>
					</td>

			</tr>
EOT;
		}
		return $str;
	}
	/**
	 * �̻�Ӯ���ƽ���Ϣ
	 */
	function winRateInfo_d($chanceId) {
		$boostsql = "select * from oa_sale_chance_boost where chanceId=$chanceId and boostType='winRate'";
		$boostInfo = $this->_db->getArray($boostsql);
		//���������ֵ��ֶ�
		$datadictDao = new model_system_datadict_datadict();
		foreach ($boostInfo as $k => $v) {
			$boostVale = $datadictDao->getDataNameByCode($v['boostValue']);
			if($boostVale == ""){
				$boostVale = "0%";
			}
			$oldValue = $datadictDao->getDataNameByCode($v['oldValue']);
			if($oldValue == ""){
				$oldValue = "0%";
			}

			$boostStr .= "-->" . "<span style='color:blue' title = '�����ˣ� " . $v['createName'] . "
����ʱ�� �� " . $v['createTime'] . "
			        					'>" . $boostVale . "<span>";
			$boostList .= "<tr><td style='text-align: left'>��" . $v['createName'] . "���ڡ�" . $v['createTime'] . "�����̻�Ӯ�ʴ� �� " . $oldValue . " ������Ϊ �� " . $boostVale . " ��</td><tr>";
		}
		if ($boostInfo) {
			$str .=<<<EOT
				<tr align="center">
					<td>
						<b>�� $boostStr</b>
					</td>

			</tr>
               $boostList
EOT;
		} else {
			$str .=<<<EOT
				<tr align="center">
					<td>
						<b>���ƽ���Ϣ</b>
					</td>

			</tr>
EOT;
		}
		return $str;
	}

	/**
	 * �����Ʒ
	 */
	function setProduct_d($goodsIds, $chanceId,$rows,$productLen) {
//		//����Ǹ��£��Ȼ�ȡ���еĲ�Ʒ
//		if (!empty ($chanceId)) {
//			$goodsSql = "select * from oa_sale_chance_goods where chanceId=" . $chanceId . "";
//			$goodinfo = $this->_db->getArray($goodsSql);
//		}
		$goodsDao = new model_goods_goods_goodsbaseinfo();
		//��ȡ��ѡ�Ĳ�Ʒ
		foreach ($goodsIds as $k => $v) {
			$goodinfo[] = $goodsDao->get_d($v);
		}
		foreach ($goodinfo as $k => $v) {
			$i = !empty($productLen)? $productLen + 1 :$k +1;
			$goodsName = $v['goodsName'];
			$goodsTypeId = $v['goodsTypeId'];
			$goodsTypeName = $v['goodsTypeName'];
			$goodsId = $v['id'];
			$number = $v['number'];
			$money = $v['money'];
			$list .= "<tr><td>$i</td>" .
			"<td>
			           		    <input type='hidden' name='chance[goods][$rows][goodsId]' value='{$goodsId}'/>
			           		    <input type='hidden' name='chance[goods][$rows][goodsTypeId]' value='{$goodsTypeId}'/>
			           		    <input type='hidden' name='chance[goods][$rows][goodsTypeName]' value='{$goodsTypeName}'/>
			           		    <input type='hidden' name='chance[goods][$rows][goodsName]' value='{$goodsName}'/>$goodsName
			           		</td>" .
			"<td>���� �� <input type='text' class='txtmiddle Num' name='chance[goods][$rows][number]' value='{$number}'onblur='checkNum(this)'/></td>" .
			"<td>�ܽ�� �� <input type='text' id='product$i' class='txtmiddle validate[required] formatMoneyGreaterZero' name='chance[goods][$rows][money]' value='{$money}' onblur='countSum()'/></td>" .

			"<td onclick='delectPro(this)' style='color:red;cursor:pointer;'>ɾ��</td></tr>";

			$rows = $rows + 1;
			$productLen++;
		}
		return $list;
	}

	/**
	 * �鿴ҳ -��Ʒ
	 */
	function productListView($chanceId) {
		$goodsSql = "select * from oa_sale_chance_goods where chanceId=" . $chanceId . "";
		$goodinfo = $this->_db->getArray($goodsSql);
		foreach ($goodinfo as $k => $v) {
			$i = $k +1;
			$goodsName = $v['goodsName'];
			$number = $v['number'];
			$money = $v['money'];
			$list .= "<tr><td>$i</td>" .
			"<td>$goodsName</td>" .
			"<td>���� ��$number</td>" .
			"<td>�ܽ�� �� <span  class='formatMoney' id='goodsMoney$i'>$money</span></td>";
		}

		$listA = "<table border=0 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef style='font-size:14'>";
		$listB = "</table>";
		$listAll = $listA . $list . $listB;
		return $listAll;
	}
	/**
	 * ����ҳ-��Ʒ
	 */
	function productListUpdate($chanceId) {
		$goodsSql = "select * from oa_sale_chance_goods where chanceId=" . $chanceId . "";
		$goodinfo = $this->_db->getArray($goodsSql);
		$i = 0;
		foreach ($goodinfo as $k => $v) {
			$i = $k +1;
			$id = $v['id'];
			$goodsTypeId = $v['goodsTypeId'];
			$goodsTypeName = $v['goodsTypeName'];
			$goodsId = $v['goodsId'];
			$goodsName = $v['goodsName'];
			$number = $v['number'];
			$money = $v['money'];
			$list .= "<tr><td>$i</td>" .
			"<td>
                <input type='hidden' name='chance[goods][$k][id]' value='{$id}'/>
       		    <input type='hidden' name='chance[goods][$k][goodsId]' value='{$goodsId}'/>
       		    <input type='hidden' name='chance[goods][$k][goodsTypeId]' value='{$goodsTypeId}'/>
       		    <input type='hidden' name='chance[goods][$k][goodsTypeName]' value='{$goodsTypeName}'/>
       		    <input type='hidden' name='chance[goods][$k][goodsName]' value='{$goodsName}'/>$goodsName
       		</td>" .
			"<td>���� �� <input type='text' class='txtmiddle' name='chance[goods][$k][number]' value='{$number}'onblur='checkNum(this)'/></td>" .
			"<td>�ܽ�� ��  <span id='goodsMoney$i'></span><input type='text' id='product$i' class='txtmiddle validate[required] formatMoneyGreaterZero' name='chance[goods][$k][money]' value='{$money}' onblur='countSum();checkProduct(this.id)'/></td>" .
			"<td onclick='delectPro(this)' style='color:red;cursor:pointer;'>ɾ��</td></tr>";
		}
		$list .= "<input type='hidden' id='productNum' value='$i'>";
		return $list;
	}

	/**
	 * �����̻�id ��ȡ�Ƿ��в�Ʒ
	 */
	function getChanceGoods_d($chanceId) {
		$sql = "select count(*) as num from oa_sale_chance_goods where chanceId=" . $chanceId . "";
		$goodsArr = $this->_db->getArray($sql);
		return $goodsArr[0]['num'];
	}
	/*
	 * �����̻�id ��ȡ��Ʒ��Ϣ
	 */
	function getChanceGoodsById($chanceId){
		$sql = "select c.id,c.goodsId,c.goodsName,c.money,c.price,c.goodsTypeName,c.goodsTypeId from oa_sale_chance_goods c where chanceId=$chanceId";
		$goodinfo = $this->_db->getArray($sql);
		return $goodinfo;
	}
	/**
	 * �����̻�id ��ȡ��Ʒ��Ϣ(��ʷ�̻�)
	 */
	function getHistoryChanceGoodsById($chanceId,$timingDate){
		$sql = "select c.id,c.oldId,c.goodsId,c.goodsName,c.money,c.price,c.goodsTypeName,c.goodsTypeId,timingDate from oa_sale_chance_timinggoods c where chanceId=$chanceId and timingDate='".$timingDate."'";
		$goodinfo = $this->_db->getArray($sql);
		return $goodinfo;
	}
	/**
	 * �̻��ŶӸ�Ȩ
	 */
	function toSetauthorizeInfo_d($trackmanIds,$chanceId){
	  if(!empty($trackmanIds)){
	  	$trackmanIdsArr = explode(",",$trackmanIds);
	  	foreach($trackmanIdsArr as $k=>$v){
               $trackmanIdsStr .= "'$v'".",";
		 }
			    $trackmanIdsStr = rtrim($trackmanIdsStr, ',');
	  	 //����Ա����Ϣ
		 $findUserSql = "select USER_ID,USER_NAME from user where USER_ID in ($trackmanIdsStr)";
		 $userInfo = $this->_db->getArray($findUserSql);
		 $list = "<tr><td>���</td><td>�Ŷӳ�Ա</td><td colspan='2'>Ȩ������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><a href='javascript:fs_selectAll(1);'>ȫѡ</a>&nbsp;&nbsp;&nbsp;<a
                                                    href='javascript:fs_selectAll(0);'>ȡ��ȫѡ</a> </span>  </td></tr>";
		foreach ($userInfo as $k => $v) {
			$i = $k +1;
			$trackman = $v['USER_NAME'];
			$trackmanId = $v['USER_ID'];
			$moneyChecked = "";
			$winRateChecked = "";
		  if(!empty($chanceId)){
             //���Ȩ�ޱ���������Ϣ
		     $findAssSql = "select limitInfo from oa_sale_chance_authorize where chanceId=".$chanceId." and trackmanId='".$trackmanId."'";
		      $assArr = $this->_db->getArray($findAssSql);
		      $limitArr = explode(",",$assArr[0]['limitInfo']);
		      if(in_array("chanceMoney",$limitArr)){
					$moneyChecked="checked";
				}else{
					$moneyChecked="";
				}
				if(in_array("winRate",$limitArr)){
					$winRateChecked="checked";
				}else{
					$winRateChecked="";
				}
				if(in_array("chanceStage",$limitArr)){
					$chanceStageChecked="checked";
				}else{
					$chanceStageChecked="";
				}

		   }
			$list .= "<tr><td>$i</td>" .
			"<td><input type='hidden'  name='authorize[$k][trackman]' value='{$trackman}'>$trackman" .
			"    <input type='hidden'  name='authorize[$k][trackmanId]' value='{$trackmanId}'></td>" .
			"<td>���&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='chanceMoney' $moneyChecked>" .
			"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ӯ��&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='winRate' $winRateChecked>" .
//             "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�׶�&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='chanceStage' $chanceStageChecked></td>" .
            "</tr>";
		}
		 return $list;
	  }
	}

	/**
	 * �̻��ŶӸ�Ȩ(ָ�������Ŷ�)
	 */
	function toSetauthorizeInfoEdit_d($chanceId){
	  if(!empty($chanceId)){

	  	 //����Ա����Ϣ
		 $findUserSql = "select trackman,trackmanId,limitInfo from oa_sale_chance_authorize where chanceId=".$chanceId."";
		 $userInfo = $this->_db->getArray($findUserSql);
		 $list = "<tr><td>���</td><td>�Ŷӳ�Ա</td><td colspan='2'>Ȩ������&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><a href='javascript:fs_selectAll(1);'>ȫѡ</a>&nbsp;&nbsp;&nbsp;<a
                                                    href='javascript:fs_selectAll(0);'>ȡ��ȫѡ</a> </span>  </td></tr>";
		foreach ($userInfo as $k => $v) {
			$i = $k +1;
			$moneyChecked = "";
			$winRateChecked = "";
			$limitArr = explode(",",$v['limitInfo']);
			$trackman = $v['trackman'];
			$trackmanId = $v['trackmanId'];
			if(in_array("chanceMoney",$limitArr)){
				$moneyChecked="checked";
			}else{
				$moneyChecked="";
			}
			if(in_array("winRate",$limitArr)){
				$winRateChecked="checked";
			}else{
				$winRateChecked="";
			}
			if(in_array("chanceStage",$limitArr)){
				$chanceStageChecked="checked";
			}else{
				$chanceStageChecked="";
			}
			$list .= "<tr><td>$i</td>" .
			"<td><input type='hidden'  name='authorize[$k][trackman]' value='{$trackman}'>$trackman" .
			"    <input type='hidden'  name='authorize[$k][trackmanId]' value='{$trackmanId}'></td>" .
			"<td>���&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='chanceMoney' $moneyChecked>" .
			"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ӯ��&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='winRate' $winRateChecked>" .
// 			"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�׶�&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='chanceStage' $chanceStageChecked></td>" .
            "</tr>";
		}
		 return $list;
	  }
	}


	/**
	 * �����̻�ID �� ��¼�� ��ȥ��ӵ�е�Ȩ�޵�
	 */
	function limitFilter_d($chanceId,$userId){
       $sql = "select limitInfo from oa_sale_chance_authorize where chanceId=".$chanceId." and trackmanId='".$userId."'";
       $arr = $this->_db->getArray($sql);
       return $arr[0];
	}


    /**
     * ��ͬ���� add����
     */
    function importAdd_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		//���������ֵ䴦�� add by chengl 2011-05-15
		$newId = $this->create ( $object );
		return $newId;
	}
   /**
    * �����̻�id���� �̻��ĸ�������
    */
   function updateChanceNewDate($chanceId,$cid){
   	  $date = date("Y-m-d");
   	  $sql = "update oa_sale_chance set newUpdateDate = '".$date."' where id='".$chanceId."'";
   	  $this->query($sql);
   	  if(!empty($cid)){
   	  	  $cDao = new model_contract_contract_contract();
   	  	  $carr = $cDao->get_d($cid);
   	  	  $contractCode =  $carr['contractCode'];
   	  	  $asql = "update oa_sale_chance set contractCode = '".$contractCode."' where id='".$chanceId."'";
   	  	  $this->query($asql);

   	  }
   }
   /**
    * �ر��̻�
    */
   function handleCloseChance_d($chanceId){
   	  $sql = "update oa_sale_chance set status = '3' where id='".$chanceId."'";
   	  $this->query($sql);
   }
   /**
    * �ָ��̻�
    */
   function handlerecoverChance_d($chanceId){
   	  $sql = "update oa_sale_chance set status = '5' where id='".$chanceId."'";
   	  $this->query($sql);
   }



    /**
     * �б�ע��������
     */
    function listremarkAdd_d($object) {
		try {
			$this->start_d();

			$dao = new model_projectmanagent_chance_remark();
			$object['content'] = nl2br($object['content']);
			$newId = $dao->add_d($object);
            //��ȡ��ͬ��Ϣ
            $contractArr = $this->get_d($object['chanceId']);
            //�����ʼ� ,������Ϊ�ύʱ�ŷ���
			if( $object['issend'] == 'y'){
				$emailDao = new model_common_mail();
				$contractCode = $contractArr['chanceCode'];
				$content = $object['content'];
				$msg = "<span style='color:blue'>�̻����</span> ��$contractCode<br/><span style='color:blue'>��Ϣ</span> �� $content" .
						"<br/>�����Ϣ���ڹ�ͨ���ڻظ�<br/>�鿴�̻���·����ҵ�����->���۹���->�ҵ�����->�̻���Ϣ";
				$emailInfo = $emailDao->batchEmail($object['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'contractInfo','�����',null,$object['TO_ID'],$msg);
			}

			$this->commit_d();
			//$this->rollBack();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/**
	 * ��ȡ��ע����
	 */
	function getRemarkInfo_d($contractId){
		$sql = "select * from oa_chance_remark where chanceId=".$contractId."";
        $arr = $this->_db->getArray($sql);
        //��������
       foreach($arr as $k=>$v){
       	   $content = str_replace("<br />"," ",$v['content']);
           $arrHTML .= "<span style='color:blue'><b>".$v['createName']."</b>(".$v['createTime'].")</span> : <br />".$v['content']."<br/>";
       }
        return $arrHTML;
	}

	/**
	 *��ȡ�б�ע��Ϣ�ĺ�ͬ ����
	 */
	function getRemarkIs(){
         $sql = "select chanceId from oa_chance_remark  GROUP BY chanceId;";
         $arr = $this->_db->getArray($sql);
         foreach($arr as $k=>$v){
         	$arr[$k] = $v['chanceId'];
         }
         return $arr;
	}
	/**
	 * �����̻�ID ��ȡ��Ӧ��ͬid
	 */
	function getContractIdBychanceId($chanceId){
		$sql = "select id from oa_contract_contract where chanceId = '".$chanceId."'";
		$arr = $this->_db->getArray($sql);
		if(empty($arr)){
			return "";
		}else{
			return $arr[0]['id'];
		}

	}


    /******************�豸Ӳ��**************************************/

	/**
	 * �����Ʒ
	 */
	function setHardware_d($goodsIds, $chanceId,$rows) {
		$Dao = new model_projectmanagent_hardware_hardware();
		//��ȡ��ѡ�Ĳ�Ʒ
		foreach ($goodsIds as $k => $v) {
			$goodinfo[] = $Dao->get_d($v);
		}

		foreach ($goodinfo as $k => $v) {
			$i = $k +1;
			$goodsName = $v['hardwareName'];
			$goodsId = $v['id'];
			$number = $v['number'];
			$money = $v['money'];
			$list .= "<tr>" .
			"<td>
			           		    <input type='hidden' name='chance[hardware][$rows][hardwareId]' value='{$goodsId}'/>
			           		    <input type='hidden' name='chance[hardware][$rows][hardwareName]' value='{$goodsName}'/>$goodsName
			           		</td>" .
			"<td>���� �� <input type='text' class='txtmiddle Num' name='chance[hardware][$rows][number]' value='{$number}'/></td>" .
			"<td>�ܽ�� �� <input type='text' id='hardwareMoney$i' class='txtmiddle' name='chance[hardware][$rows][money]' value='{$money}'/></td>" .

			"<td onclick='delectHard(this)' style='color:red;cursor:pointer;'>ɾ��</td></tr>";

			$rows = $rows + 1;
		}
		return $list;
	}

	/**
	 * �鿴ҳ -��Ʒ
	 */
	function hardwareListView($chanceId) {
		$goodsSql = "select * from oa_sale_chance_hardware where chanceId=" . $chanceId . "";
		$goodinfo = $this->_db->getArray($goodsSql);
		foreach ($goodinfo as $k => $v) {
			$i = $k +1;
			$goodsName = $v['hardwareName'];
			$number = $v['number'];
			$money = $v['money'];
			$list .= "<tr><td>$i</td>" .
			"<td>$goodsName</td>" .
			"<td>���� ��$number</td>" .
			"<td>�ܽ�� �� <span  class='formatMoney' id='hardwareMoney$i'>$money</span></td>";
		}

		$listA = "<table border=0 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef style='font-size:14'>";
		$listB = "</table>";
		$listAll = $listA . $list . $listB;
		return $listAll;
	}
	/**
	 * ����ҳ-��Ʒ
	 */
	function hardwareListUpdate($chanceId) {
		$goodsSql = "select * from oa_sale_chance_hardware where chanceId=" . $chanceId . "";
		$goodinfo = $this->_db->getArray($goodsSql);
		$i = 0;
		foreach ($goodinfo as $k => $v) {
			$i = $k +1;
			$id = $v['id'];
			$goodsId = $v['hardwareId'];
			$goodsName = $v['hardwareName'];
			$number = $v['number'];
			$money = $v['money'];
			$list .= "<tr>" .
			"<td>
                <input type='hidden' name='chance[hardware][$k][id]' value='{$id}'/>
       		    <input type='hidden' name='chance[hardware][$k][hardwareId]' value='{$goodsId}'/>
       		    <input type='hidden' name='chance[hardware][$k][hardwareName]' value='{$goodsName}'/>$goodsName
       		</td>" .
			"<td>���� �� <input type='text' class='txtmiddle' name='chance[hardware][$k][number]' value='{$number}'/></td>" .
			"<td>�ܽ�� ��  <span id='hardwareMoney$i'></span><input type='text'id='product$i' class='txtmiddle validate[required] ' name='chance[hardware][$k][money]' value='{$money}'/></td>" .
			"<td onclick='delectPro(this)' style='color:red;cursor:pointer;'>ɾ��</td></tr>";
		}
		$list .= "<input type='hidden' id='productNum' value='$i'>";
		return $list;
	}

	/**
	 * ���ϼƼ���
	 */
	function getRowsallMoney_d($rows, $selectSql) {
		//��ѯ��¼�ϼ�
		$objArr = $this->listBySqlId($selectSql . '_sumMoney');
		if (is_array($objArr)) {
			$rsArr = $objArr[0];
			$rsArr['thisAreaName'] = '�ϼ�';
		} else {
			$rsArr = array (
				'id' => 'noId',
				'chanceMoney' => 0
			);
		}
		$rows[] = $rsArr;
		return $rows;
	}

	/**
	 * ��ȡ����ID
	 * @param unknown $ids
	 */
	function getUpdateInfo_d($ids){
		$changeLogDao = new model_common_changeLog();
		return $changeLogDao->getUpdateInfo_d($ids);
	}

	/**
	 * ͨ��ID��ȡ������Ϣ
	 * @param unknown $ids
	 */
	function getUpdataInfo_d($ids){
		$changeLogDao = new model_common_changeLog();
		return $changeLogDao->getUpdataInfo_d($ids);
	}

	/**
	 * ͨ��ID��ȡ���½׶�
	 * @param unknown $ids
	 */
	function getboostInfo_d($ids){
		$dataArr = array();
		foreach($ids as $k=>$v){
			$sql = "select boostType,oldValue as oldboostValue,boostValue,updateTime from oa_sale_chance_boost where chanceId = '$v' order by updateTime desc limit 1";
			$data = $this->_db->getArray($sql);
			array_push($dataArr,$data);
		}
		return $dataArr;
	}

	/**
	 * ��ȡ���е�chance����
	 * @param unknown $prinvipalId
	 */
	function getChanceAll_d($prinvipalId){
		$date = date('Y-m-d');
		$sql = "select t.boostTime,g.goodsNameStr,c.id ,c.contractTurnDate,c.chanceCode ,c.chanceName ,c.chanceLevel ,c.chanceStage ,c.winRate ,c.chanceType ,c.chanceTypeName ,c.chanceNature ,c.chanceNatureName ,c.chanceMoney ,c.predictContractDate ,c.predictExeDate ,c.contractPeriod ,c.newUpdateDate ,c.trackman ,c.trackmanId ,c.customerName ,c.customerId ,c.address ,c.customerProvince ,c.customerType ,c.customerTypeName,c.remark ,c.progress ,c.competitor ,c.won ,c.Country ,c.CountryId ,c.Province ,c.ProvinceId ,c.City ,c.CityId ,c.areaName ,c.areaPrincipal ,c.areaPrincipalId ,c.areaCode ,c.prinvipalName ,c.prinvipalId ,c.prinvipalDept ,c.prinvipalDeptId ,c.status ,c.closeId ,c.closeName ,c.closeTime ,c.closeRegard ,c.ExaStatus ,c.ExaDT ,c.customerNeed ,c.updateTime ,c.updateName ,c.updateId ,date_format(c.createTime,'%Y-%m-%d') as createTime,c.createName ,c.createId ,c.orderNatureName ,c.orderNature ,c.rObjCode ,c.isTemp ,c.changeEquDate ,c.changeEquName ,c.changeEquNameId ,c.originalId ,c.changeTips ,c.isBecome ,c.makeStatus ,c.dealStatus ,c.DeliveryStatus ,c.customTypeId ,c.customTypeName ,c.warnDate ,c.reterStart,c.updateRecord
         		,if(ce.chanceId is null,0,1) as isTurn,ce.ExaStatus as CExaStatus,ce.contractCode
                from oa_sale_chance c left join
				(
				 select GROUP_CONCAT(goodsName) as goodsNameStr,chanceId from oa_sale_chance_goods group by chanceId
				) g on c.id=g.chanceId
				left join
		        (
		          select max(createTime) as boostTime,chanceId from  oa_sale_chance_boost group by chanceId
		        ) t on c.id=t.chanceId
		        left join
		           oa_contract_contract ce on c.id=ce.chanceId

         		where 1=1 and c.isTemp=0 and (ce.isTemp=0 or ce.isTemp is null) and(( c.status ='5')) and(( c.prinvipalId='$prinvipalId')) and (( c.predictContractDate < '$date'))";
		return $this->_db->getArray($sql);
	}

	/**
	 * ��ȡ�̻���Ϣ
	 * @param unknown $ids
	 */
	function getChanceInfo_d($beginDate,$endDate,$ids){
		$sql = "select t.*,c.chanceCode,c.chanceName from oa_sale_track t
				left join oa_sale_chance c
				on t.chanceId = c.id where  t.trackDate >= '$beginDate' and t.trackDate <= '$endDate'";
		return $this->_db->getArray($sql);
	}

	/**
	 * ��ȡ���е�chanceId
	 * @param unknown $prinvipalId
	 */
	function getAllChanceIds_d($status){
		$sql = "select t.boostTime,g.goodsNameStr,c.id ,c.contractTurnDate,c.chanceCode ,c.chanceName ,c.chanceLevel ,c.chanceStage ,c.winRate ,c.chanceType ,c.chanceTypeName ,c.chanceNature ,c.chanceNatureName ,c.chanceMoney ,c.predictContractDate ,c.predictExeDate ,c.contractPeriod ,c.newUpdateDate ,c.trackman ,c.trackmanId ,c.customerName ,c.customerId ,c.address ,c.customerProvince ,c.customerType ,c.customerTypeName,c.remark ,c.progress ,c.competitor ,c.won ,c.Country ,c.CountryId ,c.Province ,c.ProvinceId ,c.City ,c.CityId ,c.areaName ,c.areaPrincipal ,c.areaPrincipalId ,c.areaCode ,c.prinvipalName ,c.prinvipalId ,c.prinvipalDept ,c.prinvipalDeptId ,c.status ,c.closeId ,c.closeName ,c.closeTime ,c.closeRegard ,c.ExaStatus ,c.ExaDT ,c.customerNeed ,c.updateTime ,c.updateName ,c.updateId ,date_format(c.createTime,'%Y-%m-%d') as createTime,c.createName ,c.createId ,c.orderNatureName ,c.orderNature ,c.rObjCode ,c.isTemp ,c.changeEquDate ,c.changeEquName ,c.changeEquNameId ,c.originalId ,c.changeTips ,c.isBecome ,c.makeStatus ,c.dealStatus ,c.DeliveryStatus ,c.customTypeId ,c.customTypeName ,c.warnDate ,c.reterStart,c.updateRecord
         		,if(ce.chanceId is null,0,1) as isTurn,ce.ExaStatus as CExaStatus,ce.contractCode
                from oa_sale_chance c left join
				(
				 select GROUP_CONCAT(goodsName) as goodsNameStr,chanceId from oa_sale_chance_goods group by chanceId
				) g on c.id=g.chanceId
				left join
		        (
		          select max(createTime) as boostTime,chanceId from  oa_sale_chance_boost group by chanceId
		        ) t on c.id=t.chanceId
		        left join
		           oa_contract_contract ce on c.id=ce.chanceId

         		where 1=1 and c.isTemp=0  and(( c.status ='$status')) order by newUpdateDate DESC ";
		return $this->_db->getArray($sql);
	}


  /******************************************************************************************************************/

    /**
     * ���±�Ѷ��ϵ����Ϣ����
     */
    function updateChanceBX(){
    	$this->titleInfo("���ڻ�ȡ��Ҫ������̻�����...");
    	//��ȡ��Ѷ�ͻ�������
    	$rowSql = "select * from oa_sale_chance_bx";
    	$BXrow = $this->_db->getArray($rowSql);
        $this->titleInfo("��ȡ�������,��ʼ׼����������...");
        foreach($BXrow as $k => $v){
        	$this->handleData($v);
        }

       //copy �ӱ�����
        //�Ŷ�Ȩ�ޱ�
       $authorizeUpdate = "update oa_sale_chance_authorize_bx b
				LEFT JOIN oa_sale_chance c on b.chanceId=c.isImport
				LEFT JOIN hrms h on b.trackmanId=h.UserCard
				set b.chanceId = c.id,b.trackmanId = h.USER_ID";
	   $this->query($authorizeUpdate);
       $authorize = "INSERT INTO oa_sale_chance_authorize(chanceId,trackman,trackmanId,limitInfo)" .
       		" SELECT chanceId,trackman,trackmanId,limitInfo FROM oa_sale_chance_authorize_bx where chanceId != ''";
       $this->query($authorize);
       $this->titleInfo("�Ŷ�Ȩ�ޱ�.    ");
       //�ƽ���¼
       $boostUpdate = "update oa_sale_chance_boost_bx b
LEFT JOIN oa_sale_chance c on b.chanceId=c.isImport
LEFT JOIN hrms h on b.createId=h.UserCard
set b.chanceId = c.id,b.createId = h.USER_ID,b.updateId = h.USER_ID";
	   $this->query($boostUpdate);
       $boost = "INSERT INTO oa_sale_chance_boost(chanceId,boostType,boostValue,oldValue,updateTime,updateName,updateId,createTime,createName,createId)
SELECT chanceId,boostType,boostValue,oldValue,updateTime,updateName,updateId,createTime,createName,createId FROM oa_sale_chance_boost_bx where chanceId != ''";
       $this->query($boost);
       $this->titleInfo("�ƽ���¼��.    ");
       //��Ʒ
       $goodsUpdate = "update oa_sale_chance_goods_bx b
LEFT JOIN oa_sale_chance c on b.chanceId=c.isImport
set b.chanceId = c.id";
	   $this->query($goodsUpdate);
       $goods = "INSERT INTO oa_sale_chance_goods(chanceId,goodsId,goodsTypeId,goodsTypeName,goodsName,number,price,money)
SELECT chanceId,goodsId,goodsTypeId,goodsTypeName,goodsName,number,price,money FROM oa_sale_chance_goods_bx where chanceId != ''";
       $this->query($goods);
       $this->titleInfo("��Ʒ��.    ");
       //���¼�¼
       $changelogUpdate = "update oa_chance_changlog_bx b
LEFT JOIN oa_sale_chance c on b.objId=c.isImport
LEFT JOIN hrms h on b.changeManId=h.UserCard
set b.objId = c.id,b.changeManId = h.USER_ID";
	   $this->query($changelogUpdate);
       $changelog = "INSERT INTO oa_chance_changlog(tempId,objId,objType,changeManId,changeManName,changeTime)
SELECT tempId,objId,objType,changeManId,changeManName,changeTime FROM oa_chance_changlog_bx where objId != ''";
       $this->query($changelog);
       $this->titleInfo("�������.    ");

       $changelogDetailUpdateA = "update oa_chance_changedetail_bx b
LEFT JOIN oa_sale_chance c on b.objId=c.isImport
set b.objId = c.id";
	   $this->query($changelogDetailUpdateA);
	   $changelogDetailUpdateB = "update oa_chance_changedetail_bx b
LEFT JOIN oa_chance_changlog l on b.parentId=l.remark
set b.parentId=l.id";
	   $this->query($changelogDetailUpdateB);
       $changeDetaillog = "INSERT INTO oa_chance_changedetail(parentId,parentType,objId,objField,detailTypeCn,detailType,tempId,detailId,changeFieldCn,changeField,oldValue,newValue)
SELECT parentId,parentType,objId,objField,detailTypeCn,detailType,tempId,detailId,changeFieldCn,changeField,oldValue,newValue
FROM oa_chance_changedetail_bx where objId != '' ";
       $this->query($changeDetaillog);
       $this->titleInfo("����ӱ�.    ");


        $this->titleInfo("<input type='button' onclick='history.back()' value='����'>");
    }


 //��������
   function handleData($row){
   	   //�����̻�����ж��̻��Ƿ����
   	   $cSql = "select id from oa_sale_chance where chanceCode = '".$row['chanceCode']."'";
   	   $cArr = $this->_db->getArray($cSql);
   	   if(!empty($cArr)){
   	   	   $this->titleInfo("<span style='color:blue'>  ��</span>��Ѷ�̻���".$row['chanceCode']."�� ��ϵͳ���Ѵ���.    ");
   	   }else{
   	   	  //�ж������ֵ���
          $typesql = "select * from oa_system_datadict where dataCode = '".$row['chanceNature']."'";
          $tb = $this->_db->getArray($typesql);
   	   	  	  //���������id
   	   	  	  if(!empty($row['trackmanId'])){
   	   	  	  	  $trackmanIdArr = explode(",",$row['trackmanId']);
   	   	  	  	  foreach($trackmanIdArr as $k => $v){
   	   	  	  	  	 $trackmanIdArr[$k] = $this->turnUserCard($v);
   	   	  	  	  }
   	   	  	  }
   	   	  	  $row['trackmanId'] = implode(",",$trackmanIdArr);
//   	   	  	  //�ж������Ƿ����
   	   	  	  $areaCode = $this->isArea($row['areaName']);
//   	   	  	  if(empty($areaCode)){
//   	   	  	  	$this->titleInfo("<span style='color:red'>�� </span>��Ѷ�̻���".$row['chanceCode']."�� δ�ҵ���Ӧ����.    ");
//   	   	  	  }else{
//   	   	  	  	 $row['areaCode'] = $areaCode;
//   	   	  	  	 $row['areaPrincipalId'] = $this->turnUserCard($row['areaPrincipalId']);
//   	   	  	  	 $row['prinvipalId'] = $this->turnUserCard($row['prinvipalId']);
//   	   	  	  	 $row['updateId'] = $this->turnUserCard($row['updateId']);
//   	   	  	  	 $row['createId'] = $this->turnUserCard($row['createId']);
//   	   	  	  }
   	   	  	  $row['areaCode'] = $areaCode;
   	  	  	  $row['areaPrincipalId'] = $this->turnUserCard($row['areaPrincipalId']);
   	  	  	  $row['prinvipalId'] = $this->turnUserCard($row['prinvipalId']);
   	  	  	  $row['updateId'] = $this->turnUserCard($row['updateId']);
   	  	  	  $row['createId'] = $this->turnUserCard($row['createId']);

              $bxid = $row['id'];
	   	   	  unset($row['id']);
	   	   	  $row['isImport'] = $bxid;
	   	   	  $row['formBelong'] = "bx";
	   	   	  $row['formBelongName'] = "���ݱ�Ѷ";
	   	   	  $row['businessBelong'] = "bx";
	   	   	  $row['businessBelongName'] = "���ݱ�Ѷ";

	   	   	  $id = parent :: add_d($row, false);
	   	   	  if($id){
            	$this->titleInfo("<span style='color:black'> ��</span>��Ѷ�̻���".$row['chanceCode']."�� ����ɹ�.    ");
              }else{
            	$this->titleInfo("<span style='color:red'> ��</span>��Ѷ�̻���".$row['chanceCode']."�� ����ʧ��.    ");
              }
   	   }
   }


    //��ʾ��Ϣ
	 function titleInfo($ff){
	 	echo str_pad($ff,4096).'<hr />';
		flush();
		ob_flush();
		sleep(0.1);
	 }
	 //����Ա����  ת��  userId
	 function turnUserCard($userCard){
	 	 $sql = "select USER_ID from hrms where UserCard = '".$userCard."'";
	 	 $idArr = $this->_db->getArray($sql);
	 	 if(!empty($idArr)){
	 	 	return $idArr[0]['USER_ID'];
	 	 }else{
	 	 	return $userCard;
	 	 }
	 }
	 //�������������ж������Ƿ����
	 function isArea($areaName){
	 	$sql = "select id from oa_system_region where areaName = '".$areaName."'";
	 	$ff = $this->_db->getArray($sql);
	 	if(!empty($ff)){
	 		return $ff[0]['id'];
	 	}else{
	 		return "";
	 	}
	 }


  /******************************************************************************************************************/

}
?>