<?php
/**
 * @author Michael
 * @Date 2014��5��29�� 16:42:09
 * @version 1.0
 * @description:�������������� Model��
 */
class model_stock_delivery_encryption  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_delivery_encryption";
		$this->sql_map = "stock/delivery/encryptionSql.php";
		parent::__construct ();
	}

	/**
	 * ��дadd
	 */
	function add_d( $object ) {
		try {
			$this->start_d();

			//��ȡ������˾����
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$id = parent :: add_d($object ,true); //����������Ϣ

			if(is_array($object['equ'])) { //�ӱ���Ϣ
				$equDao = new model_stock_delivery_encryptionequ();
				foreach($object['equ'] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						//�����������ID
						$val['equId'] = $val['id'];
						unset($val['id']);

						//�������ͺ�
						$val['pattern'] = $val['productModel'];
						unset($val['productModel']);

						//������������
						$val['needNum'] = $val['number'];
						unset($val['number']);

						//������ִ������
						$val['finshNum'] = $val['encryptionNum'];
						unset($val['encryptionNum']);

						$val['sourceDocId'] = $object['sourceDocId']; //Դ��ID
						$val['sourceDocCode'] = $object['sourceDocCode']; //Դ�����
						$val['parentId'] = $id;
						$equDao->add_d($val ,true);
					}
				}
			}

			if ($object['state'] == 1) {
				$this->mailIssued_d( $id ); //���ʼ�֪ͨ

				//���º�ͬ�����ӱ�ļ�������������
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
	 * ��дedit
	 */
	function edit_d( $object ) {
		try {
			$this->start_d();

			$id = parent :: edit_d($object ,true); //����������Ϣ

			if(is_array($object['equ'])) { //�ӱ���Ϣ
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
				$this->mailIssued_d( $object['id'] ); //���ʼ�֪ͨ

				//���º�ͬ�����ӱ�ļ�������������
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
	 * ��ɼ���������
	 */
	function finish_d( $obj ) {
		try {
			$this->start_d();

			if(is_array($obj['equ'])){  //�ӱ���Ϣ
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

			//�ж��Ƿ��Ѿ�ȫ�����
			if ($this->isFinish_d($obj['id'])) {
				$this->updateById(array('id'=>$obj['id'] ,'state'=>3 ,'finshDate'=>date("Y-m-d")));
				$this->mailFinish_d($obj['id']); //���ʼ�֪ͨ
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ����id�жϴӱ�������Ƿ��Ѿ�ȫ�����
	 */
	function isFinish_d( $id ) {
		$equDao = new model_stock_delivery_encryptionequ();
		$equObj = $equDao->findAll(array('parentId'=>$id));

		if(is_array($equObj)) {  //�ӱ���Ϣ
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
	 * �´�����������ʼ�֪ͨ
	 */
	function mailIssued_d( $id ) {
		$obj = $this->get_d( $id );
		$this->mailDeal_d('encryptionIssued' ,$obj['headManId'] ,array('id'=>$id));
	}

	/**
	 * ��ɼ����������ʼ�֪ͨ
	 */
	function mailFinish_d( $id ) {
		$obj = $this->get_d( $id );
		$this->mailDeal_d('encryptionFinish' ,$obj['headManId'].','.$obj['issueId'] ,array('id'=>$id));
	}

	/**
	 * ���ݺ�ͬ�����嵥�ӱ�oa_contract_equ����id���¼�������������
	 */
	function upDateEqu_d($equId ,$encryptionNum) {
		try {
			$this->start_d();

			$sql = "UPDATE oa_contract_equ SET encryptionNum = encryptionNum + $encryptionNum WHERE id = $equId";
			$this->query($sql);

			//���¼���������ӱ��Է����浫δ�´�����ݿ����ٴ��´ﵼ������������������
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
	 * ajax�Ҽ��´�����
	 */
	function assignMission_d( $id ) {
		try {
			$this->start_d();

			//�ж��Ƿ��п����´������
			$equDao = new model_stock_delivery_encryptionequ();
			$equObj = $equDao->findAll(array('parentId'=>$id));
			if (is_array($equObj)) {
				$rs = false;
				foreach ($equObj as $key => $val) {
					if ($val['produceNum'] <= 0) {
						$rs = true;
					}
				}
				//�в�����ĵ���
				if ($rs) {
					$this->commit_d();
					return false;
				}
			}

			$this->updateById(array('id'=>$id ,'state'=>1)); //���µ���״̬

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