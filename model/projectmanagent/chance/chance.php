<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @author qian
 * @Date 2011年3月3日 10:49:20
 * @version 1.0
 * @description:销售商机 Model层
 */
class model_projectmanagent_chance_chance extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance";
		$this->sql_map = "projectmanagent/chance/chanceSql.php";
		parent :: __construct();
		//调用初始化对象关联类
		parent :: setObjAss();

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'transferred',
				'statusCName' => '已转商机',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'follow',
				'statusCName' => '跟踪中',
				'key' => '0'
			),
			2 => array (
				'statusEName' => 'confer',
				'statusCName' => '洽谈中',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'close',
				'statusCName' => '关闭',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'transchance',
				'statusCName' => '已生成合同',
				'key' => '4'
			),
			5 => array (
				'statusEName' => 'save',
				'statusCName' => '保存',
				'key' => '5'
			),
			6 => array (
				'statusEName' => 'pause',
				'statusCName' => '暂停',
				'key' => '6'
			)
		);

		//调用初始化对象关联类
		parent :: setObjAss();
	}


	//解析json字符串
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
	 * 修改跟踪人的edit方法
	 */
	function TrackmanEdit_d($authorizeInfo,$object, $isEditInfo = false) {
		try {
			$this->start_d();
			//修改主表信息

			parent :: edit_d($object, true);

			$chanceId = $object['id'];

			//获取跟踪人ID及名字，并弹出各自数组最后一个空元素
			$tracker = array ();
			$trackerId = explode(",", $object['trackmanId']);
			$trackman = explode(",", $object['trackman']);

			//保存跟踪人
			foreach ($trackerId as $key => $val) {
				$tracker[$key]['trackmanId'] = $val;
				$tracker[$key]['trackman'] = $trackman[$key];
				$tracker[$key]['chanceId'] = $chanceId;
				$tracker[$key]['chanceCode'] = $object['chanceCode'];
				$tracker[$key]['chanceName'] = $object['chanceName'];
			}

			//插入从表跟踪人
			$chanceequDao = new model_projectmanagent_chancetracker_chancetracker();
			$chanceequDao->delete(array (
				'chanceId' => $chanceId
			));
			$chanceequDao->createBatch($tracker);

			 //插入商机团队成员权限
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
            //更新时间
            $this->updateChanceNewDate($chanceId);
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 移交商机方法
	 */
	function transfer_d($object) {
		try {
			$this->start_d();

			$chanceId = $object['id'];
			$sql = "update " . $this->tbl_name . " set prinvipalName = '" . $object['trackman'] . "',prinvipalId = '" . $object['trackmanId'] . "' where id = '$chanceId'";
			$this->_db->query($sql);

            //更新时间
           $this->updateChanceNewDate($chanceId);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/**
	 * 处理日期
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
	 * 重写add_d方法
	 */
	function add_d($authorizeInfo,$object) {
		try {
			$this->start_d();
			 //处理日期
//            $object = $this->handleDate($object);
			//商机编号
			$chanceCodeDao = new model_common_codeRule();
			$object['chanceCode'] = $chanceCodeDao->newChanceCode($object);
			//处理商机金额
//			$chanceMoney = "0";
//			if (!empty ($object['goods'])) {
//				foreach ($object['goods'] as $k => $v) {
//					$chanceMoney += $v['money'];
//				}
//			}
//			$object['chanceMoney'] = $chanceMoney;
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);
			$object['chanceTypeName'] = $datadictDao->getDataNameByCode($object['chanceType']);
			$object['chanceNatureName'] = $datadictDao->getDataNameByCode($object['chanceNature']);
			$object['signSubjectName'] = $datadictDao->getDataNameByCode($object['signSubject']);

			$object['newUpdateDate'] = date("Y-m-d");

			//插入主表信息
			$newId = parent :: add_d($object, true);

            //插入商机团队成员权限
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
			//客户联系人
			if (!empty ($object['linkman'])) {
				$linkmanDao = new model_projectmanagent_chance_linkman();
				$linkmanDao->createBatch($object['linkman'], array (
					'chanceId' => $newId
				), 'linkmanName');
			}
			//产品
			if (!empty ($object['goods'])) {
				$orderequDao = new model_projectmanagent_chance_goods();
				$orderequDao->createBatch($object['goods'], array (
					'chanceId' => $newId
				), 'goodsId');
			}

			//设备硬件
			if (!empty ($object['hardware'])) {
				$hardwareDao = new model_projectmanagent_chance_hardware();
				$hardwareDao->createBatch($object['hardware'], array (
					'chanceId' => $newId
				), 'hardwareId');
			}

			//对手
			$competitor = new model_projectmanagent_chance_competitor();
			$competitor->createBatch($object['competitor'], array (
				'chanceId' => $newId
			), 'competitor');
			//			//产品
			//			if (!empty ($object['product'])) {
			//				$orderequDao = new model_projectmanagent_chance_product();
			//				$orderequDao->createBatch($object['product'], array (
			//					'chanceId' => $newId
			//				), 'conProductName');
			//			}
			//获取跟踪人ID及名字，并弹出各自数组最后一个空元素
			$tracker = array ();
			$trackerId = explode(",", $object['trackmanId']);
			$trackman = explode(",", $object['trackman']);

			//保存跟踪人
			foreach ($trackerId as $key => $val) {
				$tracker[$key]['trackmanId'] = $val;
				$tracker[$key]['trackman'] = $trackman[$key];
				$tracker[$key]['chanceId'] = $newId;
				$tracker[$key]['chanceCode'] = $object['chanceCode'];
				$tracker[$key]['chanceName'] = $object['chanceName'];
			}

			$trackerDao = new model_projectmanagent_chancetracker_chancetracker();
			$trackerDao->createBatch($tracker);

			//处理附件名称和Id
			$this->updateObjWithFile($newId);

			//发送相关邮件
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
     * 商机建立后发送相关邮件
     */
    function chanceToMail($object){
       //商机团队成员
       if(!empty($object['trackmanId'])){
       	  $trackArr = explode(",",$object['trackmanId']);
       	  foreach($trackArr as $k=>$v){
            $tomail = $v;
			$addmsg = "  您好：<br/>编号为《" .$object['chanceCode']. "》的商机已经建立。" .
					"并已将您加入对应商机的项目团队。<br/>" .
					"商机编号 :　<span style='color:blue'>".$object['chanceCode']."</span>   商机名称 ：<span style='color:blue'>".$object['chanceName']."</span>" .
				    "<br/>  您可以在“业务管理->销售商机->我的销售->商机信息” 列表内找到该条商机信息";
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->chanceMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_sale_chance", "审批", "通过", $tomail, $addmsg);
       	  }
       }
        //商机区域负责人
            $tomail = $object['areaPrincipalId'];
			$addmsg = "".$object['areaPrincipal']."  您好：<br/>编号为《" .$object['chanceCode']. "》的商机已经建立。" .
					"商机所属区域为 ： ".$object['areaName']."。<br/>" .
					"商机编号 :　<span style='color:blue'>".$object['chanceCode']."</span>   商机名称 ：<span style='color:blue'>".$object['chanceName']."</span>" .
				    "<br/>  您可以在“业务管理->销售商机->商机信息” 列表内查看该条商机信息";
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->chanceMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_sale_chance", "审批", "通过", $tomail, $addmsg);

    }


	/**
	 * 通过商机ID 查找所有借试用申请的ID
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
	 * 商机更新
	 */
	function updateChance_d($obj) {
		try {
			$this->start_d();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$obj['customerTypeName'] = $datadictDao->getDataNameByCode($obj['customerType']);
			$obj['chanceTypeName'] = $datadictDao->getDataNameByCode($obj['chanceType']);
			$obj['orderNatureName'] = $datadictDao->getDataNameByCode($obj['orderNature']);
			//处理商机金额
//			$chanceMoney = "0";
//			if (!empty ($obj['goods'])) {
//				foreach ($obj['goods'] as $k => $v) {
//					if (!isset ($v['isDelTag'])) {
//						$chanceMoney += $v['money'];
//					}
//				}
//			}
//			$obj['chanceMoney'] = $chanceMoney;
			//添加OldId
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
			//如果预计合同执行日期没变，则unset掉这个字段，不然变更记录依然会记录到，应该是变更通用处理的问题，先这么处理
			if($obj['predictExeDate'] == $obj['oldPredictExeDate']){
				unset($obj['predictExeDate']);
			}
			//变更附件处理
			$changeLogDao = new model_common_changeLog('chance', false);
			$changeLogDao->addLog($obj);
			//修改主表信息
			$obj['newUpdateDate'] = date("Y-m-d");
			parent :: edit_d($obj, true);

			$chanceId = $obj['oldId'];
			//插入从表信息
			//客户联系人
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
			//产品清单
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
			// 产品线冗余
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
			//更新产品线冗余
			$this->update(array('id' => $chanceId), array('newProLineStr' => rtrim($newProLineStr,',')));
			//处理产品下的默认物料
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
				//物料
				$equDao->createBatch($equInfo);
				$equInfo = "";
			}
			//更新时间
			$this->updateChanceNewDate($chanceId);
			//产品
			$goods = new model_projectmanagent_chance_goods();
			$goods->delete(array (
				'chanceId' => $chanceId
			));
			$goods->createBatch($obj['goods'], array (
				'chanceId' => $chanceId
			), 'goodsId');


			//设备硬件
			$hardwareDao = new model_projectmanagent_chance_hardware();
			$hardwareDao->delete(array (
				'chanceId' => $chanceId
			));
			$hardwareDao->createBatch($obj['hardware'], array (
				'chanceId' => $chanceId
			), 'hardwareId');
			//对手
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

			//获取跟踪人ID及名字，并弹出各自数组最后一个空元素
			$tracker = array ();
			$trackerId = explode(",", $obj['trackmanId']);
			$trackman = explode(",", $obj['trackman']);

			//保存跟踪人
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
			
			//赢率为0，关闭商机
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
	 * 保存更新记录
	 * @param unknown $layout
	 */
	function updateRecord_d($layout,$id){
		$sql = "update ".$this->tbl_name. " set updateRecord = '$layout' where id='$id'";
		return $this->query($sql);
	}

	/**
	 * 获取商机更新记录
	 */
	function getUpdateRecord_d($id){
		$sql = "select updateRecord from ".$this->tbl_name. " where id = '$id'";
		return $this->_db->getArray($sql);
	}

	/**
	 * 更新竞争对手
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
	 * 更新详细物料信息
	 */
	function prodcutInfoAdd_d($obj) {
		try {
			$Dao = new model_projectmanagent_chance_product();
			// 产品线冗余
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
			//更新产品线冗余
			$this->update(array('id' => $obj['chanceId']), array('newProLineStr' => rtrim($newProLineStr,',')));
            //处理产品下的默认物料
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
		      //物料
			  $equDao->createBatch($equInfo);
			  $equInfo = "";
           }
           //更新时间
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
	 * 更新商机推进信息
	 */
	function boostChance_d($obj) {
		try {
			$boostDao = new model_projectmanagent_chance_boost();
			//赢率
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
			//商机阶段
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
			//更新时间
			$this->updateChanceNewDate($obj['chanceId']);
			//关闭商机
	      if($obj['winRate'] == '0' || $obj['chanceStage'] == 'SJJD06' || $obj['chanceStage'] == 'SJJD07'){
	      	 $this->handleCloseChance_d($obj['chanceId']);
	      }else{
	      	 $this->handlerecoverChance_d($obj['chanceId']);
	      }

	       //更新产品信息
	       //产品
			if (!empty ($obj['goods'])) {
				$orderequDao = new model_projectmanagent_chance_goods();
				$orderequDao->createBatch($obj['goods'], array (
					'chanceId' => $obj['chanceId']
				), 'goodsId');
			}
	       	//处理商机金额
			if (!empty ($obj['goods'])) {
				$chanceMoney = "0";
				foreach ($obj['goods'] as $k => $v) {
//					$chanceMoney += $v['money'];
				}
				$sql = "update oa_sale_chance set chanceMoney = '".$chanceMoney."' where id='".$obj['chanceId']."'";
			    $this->query($sql);
			}

			//关闭信息
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
	/**************************************************接口类调用方法****************************************************/

	/**
	 * 批量删除对象
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
	 * 线索转商机--add方法
	 */
	function addChance_d($rows) {
		if (is_array($rows)) {
			try {
				$this->start_d();

				//处理数据字典字段
				$datadictDao = new model_system_datadict_datadict();
				$rows['orderNatureName'] = $datadictDao->getDataNameByCode($rows['orderNature']);

				//默认状态为“已转商机”
				$rows['status'] = $this->statusDao->statusEtoK('transferred');
				$rows['ExaStatus'] = "保存";

				$chanceId = parent :: add_d($rows, true);

				//变更对应线索的状态
				$cluesId = $rows['cluesId'];
				$cluesDao = new model_projectmanagent_clues_clues();
				$condiction = array (
					"id" => $cluesId
				);
				$cluesDao->updateField($condiction, "status", "1");

				//插入从表信息

				$linkmanDao = new model_projectmanagent_chance_linkman();
				//添加客户联系人  add by suxc 2011-08-13
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
				//设备
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
				//自定义清档
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
				//获取跟踪人ID及名字，并弹出各自数组最后一个空元素
				$tracker = array ();
				$trackerId = explode(",", $rows['trackmanId']);
				$trackman = explode(",", $rows['trackman']);

				//保存跟踪人
				foreach ($trackerId as $key => $val) {
					$tracker[$key]['trackmanId'] = $val;
					$tracker[$key]['trackman'] = $trackman[$key];
					$tracker[$key]['chanceId'] = $chanceId;
					$tracker[$key]['chanceCode'] = $rows['chanceCode'];
					$tracker[$key]['chanceName'] = $rows['chanceName'];
				}

				$trackerDao = new model_projectmanagent_chancetracker_chancetracker();
				$trackerDao->createBatch($tracker);

				//插入关联表信息
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
				//处理附件名称和Id
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
	 * @description 更新线索的状态
	 * @date 2011-03-09 14:15
	 */
	function updateClues_d($id) {
		if ($id) {
			//根据合同的ID值找到线索的ID值
			$cond = array (
				"id" => $id
			);
			$cluesId = $this->find($cond, null, "cluesId");

			$cluesDao = new model_projectmanagent_clues_clues();
			//根据线索ID更新线索表里的状态值
			$condiction = array (
				"cluesId" => $cluesId
			);
			$cluesDao->updateField($condiction, "status", "1");
		}
	}

	/**
	 * @description 关闭商机的保存方法
	 *
	 */
	function closeChance_d($rows) {
		try {
			$this->start_d();

			$chanceId = $rows['chanceId'];
			$updateSql = "update oa_sale_chance set status='3' where id=$chanceId";
			$this->query($updateSql);
			//出错关闭信息
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
	 * @description 暂停商机的保存方法
	 *
	 */
	function pauseChance_d($rows) {
		if (is_array($rows)) {
			//执行关闭操作的时候，将状态值改为“关闭”
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
	 * @description 恢复商机的保存方法
	 *
	 */
	function recoverChance_d($rows) {
		try {
			$this->start_d();

			$chanceId = $rows['chanceId'];
			$updateSql = "update oa_sale_chance set status='5' where id=$chanceId";
			$this->query($updateSql);
			//出错关闭信息
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
	 * 处理部门主管选择跟踪人 页面需要的信息
	 */
	function deptTrackInfo($row) {

		$dept = new model_common_otherdatas();
		$deptName = $dept->getUserDatas($_SESSION['USER_ID'], 'DEPT_NAME'); //当前登录人所在部门名称
		$ditpId = $_SESSION['DEPT_ID']; //当前登录人所在部门Id
		$row['deptName'] = $deptName;
		$row['deptId'] = $row['deptName'];
		$trackmanInfo = $row['trackmaninfo'];
		$deptMan = array (); //定义登录人所在部门跟踪人数组
		$deptManOther = array (); //其他跟踪人
		foreach ($trackmanInfo as $key => $val) {
			$trackmanInfo[$key]['deptName'] = $dept->getUserDatas($trackmanInfo[$key]['trackmanId'], 'DEPT_NAME'); //根据登录人ID 查到其所在部门名称
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
	 *部门主管指定本部门跟踪人方法
	 */
	function deptTrack_d($object) {
		try {
			$this->start_d();
			$chanceId = $object['id'];
			//处理商机表里的跟踪人信息
			$deptTrackmanNew = explode(',', $object['deptTrackmanOther']);
			$trackman = explode(',', $object['trackman']);

			foreach ($trackman as $key => $val) {
				array_push($deptTrackmanNew, $trackman[$key]);
			}

			$deptTrackmanA = $deptTrackmanNew; //处理跟踪人从表是需要的数组
			$deptTrackmanNew = implode(',', $deptTrackmanNew);

			//修改商机表里的跟踪人信息
			$sql = "update " . $this->tbl_name . " set trackman = '" . $deptTrackmanNew . "' where id = '$chanceId'";
			$this->_db->query($sql);

			//修改跟踪人从表里的信息
			$deptTrackmanNewId = explode(',', $object['deptTrackmanOtherId']);
			$trackmanId = explode(',', $object['trackmanId']);
			foreach ($trackmanId as $key => $val) {
				array_push($deptTrackmanNewId, $trackmanId[$key]);
			}

			$trackmanNews = array (); //要存入跟踪表的数组
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
			//修改跟踪人从表里的信息 结束

			//           $this->rollBack();
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
	/******************************************************************************/
	/*物料配件处理*/
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
	 * 商机推进信息
	 */
	function boostChanceStageInfo_d($chanceId) {
		$boostsql = "select * from oa_sale_chance_boost where chanceId=$chanceId and boostType='chanceStage'";
		$boostInfo = $this->_db->getArray($boostsql);
		//处理数据字典字段
		$datadictDao = new model_system_datadict_datadict();
		foreach ($boostInfo as $k => $v) {
			$boostVale = $datadictDao->getDataNameByCode($v['boostValue']);
			$oldValue = $datadictDao->getDataNameByCode($v['oldValue']);
			$boostStr .= "-->" . "<span style='color:blue' title = '推进人： " . $v['createName'] . "
推进时间 ： " . $v['createTime'] . "
			        					'>" . $boostVale . "<span>";
			$boostList .= "<tr><td style='text-align: left'>【" . $v['createName'] . "】于【" . $v['createTime'] . "】将商机从 【 " . $oldValue . " 】推进至 【 " . $boostVale . " 】</td><tr>";
		}
		if ($boostInfo) {
			$str .=<<<EOT
				<tr align="center">
					<td>
						<b>● $boostStr</b>
					</td>

			</tr>
               $boostList
EOT;
		} else {
			$str .=<<<EOT
				<tr align="center">
					<td>
						<b>无推进信息</b>
					</td>

			</tr>
EOT;
		}
		return $str;
	}
	/**
	 * 商机赢率推进信息
	 */
	function winRateInfo_d($chanceId) {
		$boostsql = "select * from oa_sale_chance_boost where chanceId=$chanceId and boostType='winRate'";
		$boostInfo = $this->_db->getArray($boostsql);
		//处理数据字典字段
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

			$boostStr .= "-->" . "<span style='color:blue' title = '更新人： " . $v['createName'] . "
更新时间 ： " . $v['createTime'] . "
			        					'>" . $boostVale . "<span>";
			$boostList .= "<tr><td style='text-align: left'>【" . $v['createName'] . "】于【" . $v['createTime'] . "】将商机赢率从 【 " . $oldValue . " 】更新为 【 " . $boostVale . " 】</td><tr>";
		}
		if ($boostInfo) {
			$str .=<<<EOT
				<tr align="center">
					<td>
						<b>● $boostStr</b>
					</td>

			</tr>
               $boostList
EOT;
		} else {
			$str .=<<<EOT
				<tr align="center">
					<td>
						<b>无推进信息</b>
					</td>

			</tr>
EOT;
		}
		return $str;
	}

	/**
	 * 处理产品
	 */
	function setProduct_d($goodsIds, $chanceId,$rows,$productLen) {
//		//如果是更新，先获取已有的产品
//		if (!empty ($chanceId)) {
//			$goodsSql = "select * from oa_sale_chance_goods where chanceId=" . $chanceId . "";
//			$goodinfo = $this->_db->getArray($goodsSql);
//		}
		$goodsDao = new model_goods_goods_goodsbaseinfo();
		//获取所选的产品
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
			"<td>数量 ： <input type='text' class='txtmiddle Num' name='chance[goods][$rows][number]' value='{$number}'onblur='checkNum(this)'/></td>" .
			"<td>总金额 ： <input type='text' id='product$i' class='txtmiddle validate[required] formatMoneyGreaterZero' name='chance[goods][$rows][money]' value='{$money}' onblur='countSum()'/></td>" .

			"<td onclick='delectPro(this)' style='color:red;cursor:pointer;'>删除</td></tr>";

			$rows = $rows + 1;
			$productLen++;
		}
		return $list;
	}

	/**
	 * 查看页 -产品
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
			"<td>数量 ：$number</td>" .
			"<td>总金额 ： <span  class='formatMoney' id='goodsMoney$i'>$money</span></td>";
		}

		$listA = "<table border=0 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef style='font-size:14'>";
		$listB = "</table>";
		$listAll = $listA . $list . $listB;
		return $listAll;
	}
	/**
	 * 更新页-产品
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
			"<td>数量 ： <input type='text' class='txtmiddle' name='chance[goods][$k][number]' value='{$number}'onblur='checkNum(this)'/></td>" .
			"<td>总金额 ：  <span id='goodsMoney$i'></span><input type='text' id='product$i' class='txtmiddle validate[required] formatMoneyGreaterZero' name='chance[goods][$k][money]' value='{$money}' onblur='countSum();checkProduct(this.id)'/></td>" .
			"<td onclick='delectPro(this)' style='color:red;cursor:pointer;'>删除</td></tr>";
		}
		$list .= "<input type='hidden' id='productNum' value='$i'>";
		return $list;
	}

	/**
	 * 根据商机id 获取是否有产品
	 */
	function getChanceGoods_d($chanceId) {
		$sql = "select count(*) as num from oa_sale_chance_goods where chanceId=" . $chanceId . "";
		$goodsArr = $this->_db->getArray($sql);
		return $goodsArr[0]['num'];
	}
	/*
	 * 根据商机id 获取产品信息
	 */
	function getChanceGoodsById($chanceId){
		$sql = "select c.id,c.goodsId,c.goodsName,c.money,c.price,c.goodsTypeName,c.goodsTypeId from oa_sale_chance_goods c where chanceId=$chanceId";
		$goodinfo = $this->_db->getArray($sql);
		return $goodinfo;
	}
	/**
	 * 根据商机id 获取产品信息(历史商机)
	 */
	function getHistoryChanceGoodsById($chanceId,$timingDate){
		$sql = "select c.id,c.oldId,c.goodsId,c.goodsName,c.money,c.price,c.goodsTypeName,c.goodsTypeId,timingDate from oa_sale_chance_timinggoods c where chanceId=$chanceId and timingDate='".$timingDate."'";
		$goodinfo = $this->_db->getArray($sql);
		return $goodinfo;
	}
	/**
	 * 商机团队赋权
	 */
	function toSetauthorizeInfo_d($trackmanIds,$chanceId){
	  if(!empty($trackmanIds)){
	  	$trackmanIdsArr = explode(",",$trackmanIds);
	  	foreach($trackmanIdsArr as $k=>$v){
               $trackmanIdsStr .= "'$v'".",";
		 }
			    $trackmanIdsStr = rtrim($trackmanIdsStr, ',');
	  	 //查找员工信息
		 $findUserSql = "select USER_ID,USER_NAME from user where USER_ID in ($trackmanIdsStr)";
		 $userInfo = $this->_db->getArray($findUserSql);
		 $list = "<tr><td>序号</td><td>团队成员</td><td colspan='2'>权限类型&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><a href='javascript:fs_selectAll(1);'>全选</a>&nbsp;&nbsp;&nbsp;<a
                                                    href='javascript:fs_selectAll(0);'>取消全选</a> </span>  </td></tr>";
		foreach ($userInfo as $k => $v) {
			$i = $k +1;
			$trackman = $v['USER_NAME'];
			$trackmanId = $v['USER_ID'];
			$moneyChecked = "";
			$winRateChecked = "";
		  if(!empty($chanceId)){
             //如果权限表内已有信息
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
			"<td>金额&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='chanceMoney' $moneyChecked>" .
			"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;赢率&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='winRate' $winRateChecked>" .
//             "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;阶段&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='chanceStage' $chanceStageChecked></td>" .
            "</tr>";
		}
		 return $list;
	  }
	}

	/**
	 * 商机团队赋权(指定销售团队)
	 */
	function toSetauthorizeInfoEdit_d($chanceId){
	  if(!empty($chanceId)){

	  	 //查找员工信息
		 $findUserSql = "select trackman,trackmanId,limitInfo from oa_sale_chance_authorize where chanceId=".$chanceId."";
		 $userInfo = $this->_db->getArray($findUserSql);
		 $list = "<tr><td>序号</td><td>团队成员</td><td colspan='2'>权限类型&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><a href='javascript:fs_selectAll(1);'>全选</a>&nbsp;&nbsp;&nbsp;<a
                                                    href='javascript:fs_selectAll(0);'>取消全选</a> </span>  </td></tr>";
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
			"<td>金额&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='chanceMoney' $moneyChecked>" .
			"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;赢率&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='winRate' $winRateChecked>" .
// 			"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;阶段&nbsp;&nbsp;<input type='checkbox'  name='authorize[$k][limitInfo][]' value='chanceStage' $chanceStageChecked></td>" .
            "</tr>";
		}
		 return $list;
	  }
	}


	/**
	 * 根据商机ID 和 登录人 后去所拥有的权限点
	 */
	function limitFilter_d($chanceId,$userId){
       $sql = "select limitInfo from oa_sale_chance_authorize where chanceId=".$chanceId." and trackmanId='".$userId."'";
       $arr = $this->_db->getArray($sql);
       return $arr[0];
	}


    /**
     * 合同导入 add方法
     */
    function importAdd_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		//加入数据字典处理 add by chengl 2011-05-15
		$newId = $this->create ( $object );
		return $newId;
	}
   /**
    * 根据商机id更新 商机的更新日期
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
    * 关闭商机
    */
   function handleCloseChance_d($chanceId){
   	  $sql = "update oa_sale_chance set status = '3' where id='".$chanceId."'";
   	  $this->query($sql);
   }
   /**
    * 恢复商机
    */
   function handlerecoverChance_d($chanceId){
   	  $sql = "update oa_sale_chance set status = '5' where id='".$chanceId."'";
   	  $this->query($sql);
   }



    /**
     * 列表备注新增方法
     */
    function listremarkAdd_d($object) {
		try {
			$this->start_d();

			$dao = new model_projectmanagent_chance_remark();
			$object['content'] = nl2br($object['content']);
			$newId = $dao->add_d($object);
            //获取合同信息
            $contractArr = $this->get_d($object['chanceId']);
            //发送邮件 ,当操作为提交时才发送
			if( $object['issend'] == 'y'){
				$emailDao = new model_common_mail();
				$contractCode = $contractArr['chanceCode'];
				$content = $object['content'];
				$msg = "<span style='color:blue'>商机编号</span> ：$contractCode<br/><span style='color:blue'>信息</span> ： $content" .
						"<br/>相关信息请在沟通板内回复<br/>查看商机的路径：业务管理->销售管理->我的销售->商机信息";
				$emailInfo = $emailDao->batchEmail($object['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'contractInfo','添加了',null,$object['TO_ID'],$msg);
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
	 * 获取备注数据
	 */
	function getRemarkInfo_d($contractId){
		$sql = "select * from oa_chance_remark where chanceId=".$contractId."";
        $arr = $this->_db->getArray($sql);
        //处理数组
       foreach($arr as $k=>$v){
       	   $content = str_replace("<br />"," ",$v['content']);
           $arrHTML .= "<span style='color:blue'><b>".$v['createName']."</b>(".$v['createTime'].")</span> : <br />".$v['content']."<br/>";
       }
        return $arrHTML;
	}

	/**
	 *获取有备注信息的合同 数组
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
	 * 根据商机ID 获取对应合同id
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


    /******************设备硬件**************************************/

	/**
	 * 处理产品
	 */
	function setHardware_d($goodsIds, $chanceId,$rows) {
		$Dao = new model_projectmanagent_hardware_hardware();
		//获取所选的产品
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
			"<td>数量 ： <input type='text' class='txtmiddle Num' name='chance[hardware][$rows][number]' value='{$number}'/></td>" .
			"<td>总金额 ： <input type='text' id='hardwareMoney$i' class='txtmiddle' name='chance[hardware][$rows][money]' value='{$money}'/></td>" .

			"<td onclick='delectHard(this)' style='color:red;cursor:pointer;'>删除</td></tr>";

			$rows = $rows + 1;
		}
		return $list;
	}

	/**
	 * 查看页 -产品
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
			"<td>数量 ：$number</td>" .
			"<td>总金额 ： <span  class='formatMoney' id='hardwareMoney$i'>$money</span></td>";
		}

		$listA = "<table border=0 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef style='font-size:14'>";
		$listB = "</table>";
		$listAll = $listA . $list . $listB;
		return $listAll;
	}
	/**
	 * 更新页-产品
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
			"<td>数量 ： <input type='text' class='txtmiddle' name='chance[hardware][$k][number]' value='{$number}'/></td>" .
			"<td>总金额 ：  <span id='hardwareMoney$i'></span><input type='text'id='product$i' class='txtmiddle validate[required] ' name='chance[hardware][$k][money]' value='{$money}'/></td>" .
			"<td onclick='delectPro(this)' style='color:red;cursor:pointer;'>删除</td></tr>";
		}
		$list .= "<input type='hidden' id='productNum' value='$i'>";
		return $list;
	}

	/**
	 * 金额合计计算
	 */
	function getRowsallMoney_d($rows, $selectSql) {
		//查询记录合计
		$objArr = $this->listBySqlId($selectSql . '_sumMoney');
		if (is_array($objArr)) {
			$rsArr = $objArr[0];
			$rsArr['thisAreaName'] = '合计';
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
	 * 获取更新ID
	 * @param unknown $ids
	 */
	function getUpdateInfo_d($ids){
		$changeLogDao = new model_common_changeLog();
		return $changeLogDao->getUpdateInfo_d($ids);
	}

	/**
	 * 通过ID获取更新信息
	 * @param unknown $ids
	 */
	function getUpdataInfo_d($ids){
		$changeLogDao = new model_common_changeLog();
		return $changeLogDao->getUpdataInfo_d($ids);
	}

	/**
	 * 通过ID获取更新阶段
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
	 * 获取所有的chance数据
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
	 * 获取商机信息
	 * @param unknown $ids
	 */
	function getChanceInfo_d($beginDate,$endDate,$ids){
		$sql = "select t.*,c.chanceCode,c.chanceName from oa_sale_track t
				left join oa_sale_chance c
				on t.chanceId = c.id where  t.trackDate >= '$beginDate' and t.trackDate <= '$endDate'";
		return $this->_db->getArray($sql);
	}

	/**
	 * 获取所有的chanceId
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
     * 更新贝讯联系人信息数据
     */
    function updateChanceBX(){
    	$this->titleInfo("正在获取需要插入的商机数据...");
    	//获取贝讯客户表数据
    	$rowSql = "select * from oa_sale_chance_bx";
    	$BXrow = $this->_db->getArray($rowSql);
        $this->titleInfo("获取数据完成,开始准备插入数据...");
        foreach($BXrow as $k => $v){
        	$this->handleData($v);
        }

       //copy 从表数据
        //团队权限表
       $authorizeUpdate = "update oa_sale_chance_authorize_bx b
				LEFT JOIN oa_sale_chance c on b.chanceId=c.isImport
				LEFT JOIN hrms h on b.trackmanId=h.UserCard
				set b.chanceId = c.id,b.trackmanId = h.USER_ID";
	   $this->query($authorizeUpdate);
       $authorize = "INSERT INTO oa_sale_chance_authorize(chanceId,trackman,trackmanId,limitInfo)" .
       		" SELECT chanceId,trackman,trackmanId,limitInfo FROM oa_sale_chance_authorize_bx where chanceId != ''";
       $this->query($authorize);
       $this->titleInfo("团队权限表.    ");
       //推进记录
       $boostUpdate = "update oa_sale_chance_boost_bx b
LEFT JOIN oa_sale_chance c on b.chanceId=c.isImport
LEFT JOIN hrms h on b.createId=h.UserCard
set b.chanceId = c.id,b.createId = h.USER_ID,b.updateId = h.USER_ID";
	   $this->query($boostUpdate);
       $boost = "INSERT INTO oa_sale_chance_boost(chanceId,boostType,boostValue,oldValue,updateTime,updateName,updateId,createTime,createName,createId)
SELECT chanceId,boostType,boostValue,oldValue,updateTime,updateName,updateId,createTime,createName,createId FROM oa_sale_chance_boost_bx where chanceId != ''";
       $this->query($boost);
       $this->titleInfo("推进记录表.    ");
       //产品
       $goodsUpdate = "update oa_sale_chance_goods_bx b
LEFT JOIN oa_sale_chance c on b.chanceId=c.isImport
set b.chanceId = c.id";
	   $this->query($goodsUpdate);
       $goods = "INSERT INTO oa_sale_chance_goods(chanceId,goodsId,goodsTypeId,goodsTypeName,goodsName,number,price,money)
SELECT chanceId,goodsId,goodsTypeId,goodsTypeName,goodsName,number,price,money FROM oa_sale_chance_goods_bx where chanceId != ''";
       $this->query($goods);
       $this->titleInfo("产品表.    ");
       //更新记录
       $changelogUpdate = "update oa_chance_changlog_bx b
LEFT JOIN oa_sale_chance c on b.objId=c.isImport
LEFT JOIN hrms h on b.changeManId=h.UserCard
set b.objId = c.id,b.changeManId = h.USER_ID";
	   $this->query($changelogUpdate);
       $changelog = "INSERT INTO oa_chance_changlog(tempId,objId,objType,changeManId,changeManName,changeTime)
SELECT tempId,objId,objType,changeManId,changeManName,changeTime FROM oa_chance_changlog_bx where objId != ''";
       $this->query($changelog);
       $this->titleInfo("变更主表.    ");

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
       $this->titleInfo("变更从表.    ");


        $this->titleInfo("<input type='button' onclick='history.back()' value='返回'>");
    }


 //整理数据
   function handleData($row){
   	   //根据商机编号判断商机是否存在
   	   $cSql = "select id from oa_sale_chance where chanceCode = '".$row['chanceCode']."'";
   	   $cArr = $this->_db->getArray($cSql);
   	   if(!empty($cArr)){
   	   	   $this->titleInfo("<span style='color:blue'>  ○</span>贝讯商机【".$row['chanceCode']."】 在系统内已存在.    ");
   	   }else{
   	   	  //判断数据字典项
          $typesql = "select * from oa_system_datadict where dataCode = '".$row['chanceNature']."'";
          $tb = $this->_db->getArray($typesql);
   	   	  	  //处理跟踪人id
   	   	  	  if(!empty($row['trackmanId'])){
   	   	  	  	  $trackmanIdArr = explode(",",$row['trackmanId']);
   	   	  	  	  foreach($trackmanIdArr as $k => $v){
   	   	  	  	  	 $trackmanIdArr[$k] = $this->turnUserCard($v);
   	   	  	  	  }
   	   	  	  }
   	   	  	  $row['trackmanId'] = implode(",",$trackmanIdArr);
//   	   	  	  //判断区域是否存在
   	   	  	  $areaCode = $this->isArea($row['areaName']);
//   	   	  	  if(empty($areaCode)){
//   	   	  	  	$this->titleInfo("<span style='color:red'>× </span>贝讯商机【".$row['chanceCode']."】 未找到对应区域.    ");
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
	   	   	  $row['formBelongName'] = "广州贝讯";
	   	   	  $row['businessBelong'] = "bx";
	   	   	  $row['businessBelongName'] = "广州贝讯";

	   	   	  $id = parent :: add_d($row, false);
	   	   	  if($id){
            	$this->titleInfo("<span style='color:black'> √</span>贝讯商机【".$row['chanceCode']."】 插入成功.    ");
              }else{
            	$this->titleInfo("<span style='color:red'> ×</span>贝讯商机【".$row['chanceCode']."】 插入失败.    ");
              }
   	   }
   }


    //提示信息
	 function titleInfo($ff){
	 	echo str_pad($ff,4096).'<hr />';
		flush();
		ob_flush();
		sleep(0.1);
	 }
	 //根据员工号  转换  userId
	 function turnUserCard($userCard){
	 	 $sql = "select USER_ID from hrms where UserCard = '".$userCard."'";
	 	 $idArr = $this->_db->getArray($sql);
	 	 if(!empty($idArr)){
	 	 	return $idArr[0]['USER_ID'];
	 	 }else{
	 	 	return $userCard;
	 	 }
	 }
	 //根据区域名称判断区域是否粗在
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