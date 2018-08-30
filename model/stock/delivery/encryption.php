<?php
/**
 * @author Michael
 * @Date 2014年5月29日 16:42:09
 * @version 1.0
 * @description:交付加密锁任务 Model层
 */
class model_stock_delivery_encryption  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_delivery_encryption";
		$this->sql_map = "stock/delivery/encryptionSql.php";
		parent::__construct ();
	}

	/**
	 * 重写add
	 */
	function add_d( $object ) {
		try {
			$this->start_d();

			//获取归属公司名称
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$id = parent :: add_d($object ,true); //新增主表信息

			if(is_array($object['equ'])) { //从表信息
				$equDao = new model_stock_delivery_encryptionequ();
				foreach($object['equ'] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						//处理发货任务的ID
						$val['equId'] = $val['id'];
						unset($val['id']);

						//处理规格型号
						$val['pattern'] = $val['productModel'];
						unset($val['productModel']);

						//处理需求数量
						$val['needNum'] = $val['number'];
						unset($val['number']);

						//处理已执行数量
						$val['finshNum'] = $val['encryptionNum'];
						unset($val['encryptionNum']);

						$val['sourceDocId'] = $object['sourceDocId']; //源单ID
						$val['sourceDocCode'] = $object['sourceDocCode']; //源单编号
						$val['parentId'] = $id;
						$equDao->add_d($val ,true);
					}
				}
			}

			if ($object['state'] == 1) {
				$this->mailIssued_d( $id ); //发邮件通知

				//更新合同发货从表的加密锁任务数量
				foreach($object['equ'] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						$this->upDateEqu_d($val['id'] ,$val['produceNum']);
					}
				}
			}

			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写edit
	 */
	function edit_d( $object ) {
		try {
			$this->start_d();

			$id = parent :: edit_d($object ,true); //新增主表信息

			if(is_array($object['equ'])) { //从表信息
				$equDao = new model_stock_delivery_encryptionequ();
				foreach($object['equ'] as $key => $val){
					if ($val['isDelTag'] == 1) {
						$equDao->deleteByPk($val['id']);
					} else {
						$equDao->edit_d($val ,true);
					}
				}
			}

			if ($object['state'] == 1) {
				$this->mailIssued_d( $object['id'] ); //发邮件通知

				//更新合同发货从表的加密锁任务数量
				foreach($object['equ'] as $key => $val){
					if ($val['isDelTag'] != 1) {
						$this->upDateEqu_d($val['equId'] ,$val['produceNum']);
					}
				}
			}

			$this->commit_d();
			return $object['id'];
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 完成加密锁任务
	 */
	function finish_d( $obj ) {
		try {
			$this->start_d();

			if(is_array($obj['equ'])){  //从表信息
				$equDao = new model_stock_delivery_encryptionequ();
				foreach($obj['equ'] as $key => $val){
					if (isset($val['state'])) {
						$val['state'] = 1;
						if ($val['actualFinshDate'] == '') {
							$val['actualFinshDate'] = date("Y-m-d");
							$val['finshNum'] = $val['produceNum'];
							unset($val['produceNum']);
						}
					} else {
						$val['state'] = 0;
					}
					$equDao->edit_d($val);
				}
			}

			//判断是否已经全部完成
			if ($this->isFinish_d($obj['id'])) {
				$this->updateById(array('id'=>$obj['id'] ,'state'=>3 ,'finshDate'=>date("Y-m-d")));
				$this->mailFinish_d($obj['id']); //发邮件通知
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据id判断从表的任务是否已经全部完成
	 */
	function isFinish_d( $id ) {
		$equDao = new model_stock_delivery_encryptionequ();
		$equObj = $equDao->findAll(array('parentId'=>$id));

		if(is_array($equObj)) {  //从表信息
			$num = 0;
			foreach($equObj as $key => $val) {
				if ($val['state'] == 1) {
					$num++;
				}
			}
		}

		if (count($equObj) == $num) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 下达加密锁任务邮件通知
	 */
	function mailIssued_d( $id ) {
		$obj = $this->get_d( $id );
		$this->mailDeal_d('encryptionIssued' ,$obj['headManId'] ,array('id'=>$id));
	}

	/**
	 * 完成加密锁任务邮件通知
	 */
	function mailFinish_d( $id ) {
		$obj = $this->get_d( $id );
		$this->mailDeal_d('encryptionFinish' ,$obj['headManId'].','.$obj['issueId'] ,array('id'=>$id));
	}

	/**
	 * 根据合同发货清单从表（oa_contract_equ）的id更新加密锁任务数量
	 */
	function upDateEqu_d($equId ,$encryptionNum) {
		try {
			$this->start_d();

			$sql = "UPDATE oa_contract_equ SET encryptionNum = encryptionNum + $encryptionNum WHERE id = $equId";
			$this->query($sql);

			//更新加密锁任务从表以防保存但未下达的数据可以再次下达导致数量超出需求数量
			$sql = "UPDATE oa_delivery_encryptionequ a "
					." LEFT JOIN oa_delivery_encryption b ON a.parentId = b.id "
					." SET a.finshNum = a.finshNum + $encryptionNum , "
					." a.produceNum = a.needNum - a.finshNum - $encryptionNum "
					." WHERE a.equId = $equId AND b.state=0 ";
			$this->query($sql);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ajax右键下达任务
	 */
	function assignMission_d( $id ) {
		try {
			$this->start_d();

			//判断是否都有可以下达的数量
			$equDao = new model_stock_delivery_encryptionequ();
			$equObj = $equDao->findAll(array('parentId'=>$id));
			if (is_array($equObj)) {
				$rs = false;
				foreach ($equObj as $key => $val) {
					if ($val['produceNum'] <= 0) {
						$rs = true;
					}
				}
				//有不满足的单据
				if ($rs) {
					$this->commit_d();
					return false;
				}
			}

			$this->updateById(array('id'=>$id ,'state'=>1)); //更新单据状态

			if (is_array($equObj)) {
				foreach ($equObj as $key => $val) {
					$this->upDateEqu_d($val['equId'] ,$val['produceNum']);
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
}
?>