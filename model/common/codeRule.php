<?php

/**    ���ݱ������MODEL��
 * Created on 2011-6-23
 * Created by suxc
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_common_codeRule extends model_base
{

	/**
	 * ��ȡ�û����
	 *
	 * @param $userId �û�ID
	 */
	function getUserCard($userId) {
		$userSql = "select UserCard from hrms where USER_ID='" . $userId . "'";
		$userCard = mysql_fetch_row($this->query($userSql));
		return $userCard[0];
	}

	/**
	 * ��ȡ�û����
	 *
	 * @param $userCard �û�����
	 */
	function getUserIdByCard($userCard) {
		$userSql = "select USER_ID from hrms where UserCard='" . $userCard . "'";
		$userCard = mysql_fetch_row($this->query($userSql));
		return $userCard[0];
	}

	/**
	 *�ɹ����뵥������ɷ���
	 *
	 * @param $tblname �ɹ����뵥����
	 * @param $purchType �ɹ�����
	 */
	function purchApplyCode($tblname, $purchType) {
		$codeStr = "DLC";     //���ǰ׺
		$date = date("ymd");
		switch ($purchType) {   //���ݲɹ����ͷ������ͱ��
			case 'oa_sale_order':
				$type = "03";
				break;
			case 'oa_sale_service':
				$type = "03";
				break;
			case 'oa_sale_lease':
				$type = "03";
				break;
			case 'oa_sale_rdproject':
				$type = "03";
				break;
			case 'stock':
				$type = "01";
				break;
			case 'rdproject':
				$type = "06";
				break;
			case 'assets':
				$type = "05";
				break;
			case 'produce':
				$type = "02";
				break;
			case 'oa_borrow_borrow':
				$type = "07";
				break;
			case 'oa_present_present':
				$type = "07";
				break;
		}
		$sql = "select codeValue from oa_billcode where codeType='" . $tblname . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 6);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 6, 4);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "000" . $newNum;
						break;
					case 2:
						$codeNum = "00" . $newNum;
						break;
					case 3:
						$codeNum = "0" . $newNum;
						break;
					case 4:
						$codeNum = $newNum;
						break;
				}
				$billCode = $codeStr . $oldDate . $type . $codeNum;
				$codeValue = $oldDate . $codeNum;

				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
				$this->query($updateSql);
			} else {
				$billCode = $codeStr . $date . $type . "0001";
				$codeValue = $date . "0001";
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $codeStr . $date . $type . "0001";
			$codeValue = $date . "0001";
			$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
			$this->query($updateSql);
		}
		return $billCode;

	}

	/**
	 * �ɹ�������
	 *
	 * @param $tblname �ɹ��������
	 * @param $name   �ɹ�ԱID
	 */
	function purchTaskCode($tblname, $userID) {
		$date = date("ymd");
		//��ȡ�û����
		$nameCode = $this->getUserCard($userID);

		$sql = "select codeValue from oa_billcode where codeType='" . $tblname . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 6);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 6, 4);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "000" . $newNum;
						break;
					case 2:
						$codeNum = "00" . $newNum;
						break;
					case 3:
						$codeNum = "0" . $newNum;
						break;
					case 4:
						$codeNum = $newNum;
						break;
				}
				$billCode = $oldDate . $nameCode . $codeNum;
				$codeValue = $oldDate . $codeNum;

				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
				$this->query($updateSql);
			} else {
				$billCode = $date . $nameCode . "0001";
				$codeValue = $date . "0001";
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $date . $nameCode . "0001";
			$codeValue = $date . "0001";
			$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
			$this->query($updateSql);
		}
		return $billCode;

	}

	/**
	 * �ɹ��������
	 *
	 * @param $tblname �ɹ���������
	 * @param $name   �ɹ�ԱID
	 */
	function purchOrderCode($tblname, $userID) {
		$pingYing = new model_common_getPingYing();
		$codeStr = "DLC";     //���ǰ׺
		$date = date("ymd");
		//��ȡ�û����
		$nameCode = $this->getUserCard($userID);

		$sql = "select codeValue from oa_billcode where codeType='" . $tblname . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 6);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 6, 4);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "000" . $newNum;
						break;
					case 2:
						$codeNum = "00" . $newNum;
						break;
					case 3:
						$codeNum = "0" . $newNum;
						break;
					case 4:
						$codeNum = $newNum;
						break;
				}
				$billCode = $codeStr . $oldDate . $nameCode . $codeNum;
				$codeValue = $oldDate . $codeNum;

				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
				$this->query($updateSql);
			} else {
				$billCode = $codeStr . $date . $nameCode . "0001";
				$codeValue = $date . "0001";
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $codeStr . $date . $nameCode . "0001";
			$codeValue = $date . "0001";
			$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
			$this->query($updateSql);
		}
		return $billCode;

	}

	/**
	 * �ɹ�ѯ�۵�������֪ͨ��������֪ͨ��������ɷ���.
	 *
	 * @param $type ��������
	 */
	function purchaseCode($type) {
		$date = date("ymd");
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 6);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 6, 4);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "000" . $newNum;
						break;
					case 2:
						$codeNum = "00" . $newNum;
						break;
					case 3:
						$codeNum = "0" . $newNum;
						break;
					case 4:
						$codeNum = $newNum;
						break;
				}
				$billCode = $oldDate . $codeNum;
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			} else {
				$billCode = $date . "0001";
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $date . "0001";
			$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * �ɹ���Ʊ���
	 *
	 * @param $tblname �ɹ���Ʊ����
	 * @param $name   �ɹ�Ա����
	 */
	function purchInvoiceCode($tblname, $userID) {
		$pingYing = new model_common_getPingYing();
		$codeStr = "FP";     //���ǰ׺
		$date = date("ymd");
		//		if($name=="�����"){     //������ ת��������ʱ����������
		//			$nameCode="KBY";
		//		}else{
		//			$nameCode=$pingYing->getFirstPY($name);
		////			$nameCode=$this->getInitials($name);
		//		}

		//��ȡ�û����
		$nameCode = $this->getUserCard($userID);

		$sql = "select codeValue from oa_billcode where codeType='" . $tblname . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 6);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 6, 3);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "00" . $newNum;
						break;
					case 2:
						$codeNum = "0" . $newNum;
						break;
					case 3:
						$codeNum = $newNum;
						break;
				}
				$billCode = $codeStr . $oldDate . $nameCode . $codeNum;
				$codeValue = $oldDate . $codeNum;

				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
				$this->query($updateSql);
			} else {
				$billCode = $codeStr . $date . $nameCode . "001";
				$codeValue = $date . "001";
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $codeStr . $date . $nameCode . "001";
			$codeValue = $date . "001";
			$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * Ӧ��������Ʊ
	 *
	 * @param $tblname
	 * @param $name ҵ��Ա����
	 */
	function invotherCode($tblname, $userID) {
		$pingYing = new model_common_getPingYing();
		$codeStr = "QTFP";     //���ǰ׺
		$date = date("ymd");

		//��ȡ�û����
		$nameCode = $this->getUserCard($userID);

		$sql = "select codeValue from oa_billcode where codeType='" . $tblname . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 6);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 6, 3);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "00" . $newNum;
						break;
					case 2:
						$codeNum = "0" . $newNum;
						break;
					case 3:
						$codeNum = $newNum;
						break;
				}
				$billCode = $codeStr . $oldDate . $nameCode . $codeNum;
				$codeValue = $oldDate . $codeNum;

				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
				$this->query($updateSql);
			} else {
				$billCode = $codeStr . $date . $nameCode . "001";
				$codeValue = $date . "001";
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $codeStr . $date . $nameCode . "001";
			$codeValue = $date . "001";
			$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $tblname . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * ��Ӧ�̱��
	 *
	 * @param $type ��������
	 * @param $preString ���ݱ��ǰ׺
	 */
	function supplierCode($type) {
		switch ($type) {
			case "oa_supp_lib":
				$preString = "01";
				break;         //��ʽ
			case "oa_supp_lib_temp":
				$preString = "02";
				break;    //��ʱ
		}

		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		if (!empty($codeRows[0])) {
			$newNum = $codeRows[0] + 1;
			switch (strlen($newNum)) {
				case 1:
					$codeNum = "000" . $newNum;
					break;
				case 2:
					$codeNum = "00" . $newNum;
					break;
				case 3:
					$codeNum = "0" . $newNum;
					break;
				case 4:
					$codeNum = $newNum;
					break;
			}
			$billCode = $preString . $codeNum;
			$updateSql = "update oa_billcode set codeValue='" . $codeNum . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		} else {
			$billCode = $preString . "0001";
			$updateSql = "update oa_billcode set codeValue='0001' where codeType='" . $type . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * �����Ӧ�̱��
	 *
	 * @param $type ��������
	 * @param $preString ���ݱ��ǰ׺
	 */
	function outsourcSupplierCode($type, $prefix = '') {
		if ($prefix != '') {
			$preString = $prefix;
		} else {
			$preString = "WB";
		}
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		if (!empty($codeRows[0])) {
			$newNum = $codeRows[0] + 1;
			switch (strlen($newNum)) {
				case 1:
					$codeNum = "000" . $newNum;
					break;
				case 2:
					$codeNum = "00" . $newNum;
					break;
				case 3:
					$codeNum = "0" . $newNum;
					break;
				case 4:
					$codeNum = $newNum;
					break;
			}
			$billCode = $preString . $codeNum;
			$updateSql = "update oa_billcode set codeValue='" . $codeNum . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		} else {
			$billCode = $preString . "0001";
			$updateSql = "update oa_billcode set codeValue='0001' where codeType='" . $type . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * �ִ�ģ�鵥�ݱ��
	 *
	 * @param $type ��������
	 * @param $preString ���ݱ��ǰ׺
	 */
	function stockCode($type, $preString) {
		$sql = "select codeValue,codeTypeAss from oa_billcode where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		if (!empty($codeRows[0])) {
			$newNum = $codeRows[0] + 1;
			switch (strlen($newNum)) {
				case 1:
					$codeNum = "00000" . $newNum;
					break;
				case 2:
					$codeNum = "0000" . $newNum;
					break;
				case 3:
					$codeNum = "000" . $newNum;
					break;
				case 4:
					$codeNum = "00" . $newNum;
					break;
				case 5:
					$codeNum = "0" . $newNum;
					break;
				case 6:
					$codeNum = $newNum;
					break;
			}
			$billCode = $preString . $codeNum;
			$updateSql = "update oa_billcode set codeValue='" . $codeNum . "' where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
			$this->query($updateSql);
		} else {
			$billCode = $preString . "000001";
			$updateSql = "update oa_billcode set codeValue='000001' where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * ������۱���
	 *
	 * @param $type ��������
	 * @param $preString ���ݱ��ǰ׺
	 * @param $prov ʡ��
	 * @param $codeTypeName ҵ������
	 */
	function accessorderCode($type, $preString, $prov, $codeTypeName, $isReflesh = true) {
		//��ȡʡ��ƴ������ĸ
		$pinyingModel = new model_common_getPingYing();
		$firstPY = $pinyingModel->getFirstPY($prov);
		$date = date('ymd');
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		if (!empty($codeRows[0])) {
			if ($isReflesh) {
				$codeNum = "001";
			} else {
				$newNum = $codeRows[0] + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "00" . $newNum;
						break;
					case 2:
						$codeNum = "0" . $newNum;
						break;
					case 3:
						$codeNum = $newNum;
						break;
				}
			}
			$billCode = $preString . $date . $firstPY . $codeNum;
			$updateSql = "update oa_billcode set codeValue='" . $codeNum . "' where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
		} else {
			$billCode = $preString . $date . $firstPY . "001";
			$updateSql = "insert into oa_billcode (codeTypeName,codeTypeAss,codeType,codeValue) values ('$codeTypeName','$preString','$type','001')";
		}
		$this->query($updateSql);
		return $billCode;
	}

	/**
	 * ���񲿷ֱ��
	 *
	 * @param $type ��������
	 * @param $preString ���ݱ��ǰ׺
	 * @param $midString ���ݱ���м��ַ���
	 */
	function financeCode($type, $preString, $midString = null) {
		$date = date("ymd");
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 6);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 6, 6);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "00000" . $newNum;
						break;
					case 2:
						$codeNum = "0000" . $newNum;
						break;
					case 3:
						$codeNum = "000" . $newNum;
						break;
					case 4:
						$codeNum = "00" . $newNum;
						break;
					case 5:
						$codeNum = "0" . $newNum;
						break;
					case 6:
						$codeNum = $newNum;
						break;
				}
				if ($midString) {
					$billCode = $preString . $oldDate . $midString . $codeNum;
				} else {
					$billCode = $preString . $oldDate . $codeNum;
				}
				$codeValue = $oldDate . $codeNum;
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			} else {
				if ($midString) {
					$billCode = $preString . $date . $midString . "000001";
				} else {
					$billCode = $preString . $date . "000001";
				}
				$codeValue = $date . "000001";
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			}
		} else {
			if ($midString) {
				$billCode = $preString . $date . $midString . "000001";
			} else {
				$billCode = $preString . $date . "000001";
			}
			$codeValue = $date . "000001";
			$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}
	//
	//	/**
	//	 * �����ƻ����
	//	 *
	//	 * @param $type ��������
	//	 */
	//	function sendPlanCode ($type) {
	//		$year=date("y");
	//		$week=date("W");   //�ܴ�
	//		$sql="select codeValue from oa_billcode where codeType='".$type."'";
	//		$res=$this->query($sql);
	//		$codeValue=mysql_fetch_row($res);
	//		if(!empty($codeValue[0])){
	//			$oldWeek=substr($codeValue[0],0,2);
	//			if($week==$oldWeek){
	//				$num=substr($codeValue[0],2,6);
	//				$newNum=$num+1;
	//				switch(strlen($newNum)){
	//					case 1:$codeNum="00000".$newNum;break;
	//					case 2:$codeNum="0000".$newNum;break;
	//					case 3:$codeNum="000".$newNum;break;
	//					case 4:$codeNum="00".$newNum;break;
	//					case 5:$codeNum="0".$newNum;break;
	//					case 6:$codeNum=$newNum;break;
	//				}
	//				$billCode="FHJH".$oldWeek.$codeNum;
	//				$codeValue=$oldWeek.$codeNum;;
	//				$updateSql="update oa_billcode set codeValue='".$codeValue."' where codeType='".$type."'";
	//				$this->query($updateSql);
	//			}else{
	//				$billCode="FHJH".$week."000001";
	//				$codeValue=$week."000001";
	//				$updateSql="update oa_billcode set codeValue='".$codeValue."' where codeType='".$type."'";
	//				$this->query($updateSql);
	//			}
	//		}else {
	//				$billCode="FHJH".$week."000001";
	//				$codeValue=$week."000001";
	//				$updateSql="update oa_billcode set codeValue='".$codeValue."' where codeType='".$type."'";
	//				$this->query($updateSql);
	//		}
	//		return $billCode;
	//
	//	}

	/**
	 * ���������
	 *
	 * @param $type ��������
	 */
	function sendCode($type) {
		$date = date("ymd");
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 6);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 6, 6);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "00000" . $newNum;
						break;
					case 2:
						$codeNum = "0000" . $newNum;
						break;
					case 3:
						$codeNum = "000" . $newNum;
						break;
					case 4:
						$codeNum = "00" . $newNum;
						break;
					case 5:
						$codeNum = "0" . $newNum;
						break;
					case 6:
						$codeNum = $newNum;
						break;
				}
				$billCode = "FHQD" . $oldDate . $codeNum;
				$codeValue = $oldDate . $codeNum;;
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			} else {
				$billCode = "FHQD" . $date . "000001";
				$codeValue = $date . "000001";
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = "FHQD" . $date . "000001";
			$codeValue = $date . "000001";
			$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * �ͻ����.
	 *
	 * @param $type
	 * @param $customerCode  �ͻ����ʴ���
	 * @param $countryId    ����ID
	 * @param $cityId    ����ID
	 */
	function customerCode($type, $customerCode, $countryId, $cityId) {
		//��ȡ���ұ���
		$rs = $this->_db->get_one("select countryCode from oa_system_country_info where id=" . $countryId);
		$countryCode = $rs ['countryCode'];
		if ($cityId && $cityId != "��ѡ�����") {
			//��ȡ���б���
			$cityRs = $this->_db->get_one("select cityCode from oa_system_city_info where id=" . $cityId);
			$areaCode = $cityRs['cityCode'];
		} else {
			$areaCode = $countryCode;
		}
		$size = strlen($areaCode) + 3;
		$sql = "select max(RIGHT(c.objectCode,2)) as maxCode,SUBSTRING(c.objectCode,1,$size) as objCode from customer c group by objCode having  objCode='" . $customerCode . $areaCode . "'";
		$res = $this->query($sql);
		$maxCode = mysql_fetch_row($res);
		if (is_array($maxCode)) {
			$newNum = $maxCode[0] + 1;
			switch (strlen($newNum)) {
				case 1:
					$codeNum = "0" . $newNum;
					break;
				case 2:
					$codeNum = $newNum;
					break;
			}
			$billCode = $customerCode . $areaCode . $codeNum;
		} else {
			$billCode = $customerCode . $areaCode . "01";
		}
		return $billCode;
	}

	/**
	 * ͨ�õı��
	 * @param $type ��������
	 * @param $preString ���ݱ��ǰ׺
	 * @param $digits ��ˮ��λ��
	 * @param $codeTypeName ������
	 * @param $codeTypeAss ���ͱ��
	 * �����ʽ $prefix.$num AAAA001
	 */
	function setCommonCode($type, $prefix = '', $digits = 3, $codeTypeName, $codeTypeAss) {
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		if (!empty($codeRows[0])) {
			$newNum = $codeRows[0] + 1;
			if (strlen($newNum) == $digits) {
				$codeNum = $newNum;
			} else if (strlen($newNum) < $digits) {
				$zero = '';
				for ($i = 0; $i < $digits - strlen($newNum); $i++) {
					$zero .= '0';
				}
				$codeNum = $zero . $newNum;
			} else {
				$zero = '';
				for ($i = 1; $i < $digits; $i++) {
					$zero .= '0';
				}
				$codeNum = $zero . '1';
			}

			$billCode = $prefix . $codeNum;
			$updateSql = "update oa_billCode set codeValue='" . $codeNum . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		} else {
			$zero = '';
			for ($i = 1; $i < $digits; $i++) {
				$zero .= '0';
			}
			$codeNum = $zero . '1';
			$billCode = $prefix . $codeNum;
			$insertSql = "insert into oa_billCode (codeTypeName ,codeTypeAss ,codeType ,codeValue)
							values ('$codeTypeName','$codeTypeAss','$type','$codeNum')";
			$this->query($insertSql);
		}
		return $billCode;
	}

	/**
	 *����Ϊ�������ҷ�����ͬ���
	 *
	 * @param $type        ��ͬ����
	 * @param $customerCode  �ͻ����ʴ���
	 * @param $countryId    ����ID
	 * @param $cityId    ����ID
	 */
	function contractCode($type, $customerId) {
		$preString = "DLA";
		switch ($type) {
			case "oa_sale_order":
				$contractType = "01";
				break;   //���ۺ�ͬ
			case "oa_sale_service":
				$contractType = "02";
				break; //�����ͬ
			case "oa_sale_lease":
				$contractType = "03";
				break;      //���޺�ͬ
			case "oa_sale_rdproject":
				$contractType = "04";
				break; //�з���ͬ
			default:
				$contractType = "05";
				break;
		}
		//���ݿͻ�ID��ȡ�ͻ����
		$customerDao = new model_customer_customer_customer();
		$customerCode = $customerDao->getObjectCode($customerId);
		$customerCode = substr($customerCode, 0, -2);
		$date = date("ymd");
		//��ȡ��ˮ��
		//		$sql="select codeValue from oa_billcode where codeType='".$type."'";
		$sql = "select codeValue from oa_billcode where codeType='oa_sale_order'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		//		if(!empty($codeValue[0])){
		$newNum = $codeValue[0] + 1;
		switch (strlen($newNum)) {
			case 1:
				$codeNum = "000" . $newNum;
				break;
			case 2:
				$codeNum = "00" . $newNum;
				break;
			case 3:
				$codeNum = "0" . $newNum;
				break;
			case 4:
				$codeNum = $newNum;
				break;
		}
		//������ˮ��
		//			$updateSql="update oa_billcode set codeValue='".$newNum."' where codeType='".$type."'";
		$updateSql = "update oa_billcode set codeValue='" . $newNum . "' where codeType='oa_sale_order'";
		$this->query($updateSql);
		//		}
		return $preString . $contractType . $customerCode . $date . $codeNum;
	}

	/**
	 *����Ϊ�򷽣��׷�����ͬ���
	 *
	 * @param $type        ��ͬ����
	 * @param $deptId    ����ID
	 */
	function contractBuyCode($type, $deptId) {
		$preString = "DLB";
		switch ($type) {
			case "oa_sale_service":
				$contractType = "01";
				break;//�ɹ���ͬ
			case "":
				$contractType = "02";
				break;//�������
			case "":
				$contractType = "03";
				break;//ί�п���
			case "":
				$contractType = "04";
				break;//�չ�
			case "":
				$contractType = "05";
				break;//��ѯ
			case "":
				$contractType = "06";
				break;//��������
			case "":
				$contractType = "07";
				break;//����
		}
		$rs = $this->_db->get_one("select Code from department where DEPT_ID=" . $deptId);
		$deptCode = $rs ['Code'];
		$date = date("ymd");
		//��ȡ��ˮ��
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		$newNum = $codeValue[0] + 1;
		switch (strlen($newNum)) {
			case 1:
				$codeNum = "000" . $newNum;
				break;
			case 2:
				$codeNum = "00" . $newNum;
				break;
			case 3:
				$codeNum = "0" . $newNum;
				break;
			case 4:
				$codeNum = $newNum;
				break;
		}
		//������ˮ��
		$updateSql = "update oa_billcode set codeValue='" . $newNum . "' where codeType='" . $type . "'";
		$this->query($updateSql);
		$billCode = $preString . $contractType . $deptCode . $date . $codeNum;
		return $billCode;
	}

	/**
	 *�����������Э��
	 *
	 * @param $type      ��ͬ����
	 * @param $deptId    ����ID
	 */
	function contractExternalCode($type, $deptId) {
		$preString = "DLC";
		switch ($type) {
			case "oa_sale_service":
				$contractType = "01";
				break;//����Э��
			case "":
				$contractType = "02";
				break;//
		}
		$rs = $this->_db->get_one("select Code from department where DEPT_ID=" . $deptId);
		$deptCode = $rs ['Code'];
		$date = date("ymd");
		//��ȡ��ˮ��
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		$newNum = $codeValue[0] + 1;
		switch (strlen($newNum)) {
			case 1:
				$codeNum = "000" . $newNum;
				break;
			case 2:
				$codeNum = "00" . $newNum;
				break;
			case 3:
				$codeNum = "0" . $newNum;
				break;
			case 4:
				$codeNum = $newNum;
				break;
		}
		//������ˮ��
		$updateSql = "update oa_billcode set codeValue='" . $newNum . "' where codeType='" . $type . "'";
		$this->query($updateSql);
		$billCode = $preString . $contractType . $deptCode . $date . $codeNum;
		return $billCode;
	}

	/**
	 * �����ñ�� ���£�
	 */
	function borrowCode($type, $borrowType) {
		switch ($borrowType) {    //���ǰ׺
			case "cus":
				$preString = "KJY";
				break;
			case "pro":
				$preString = "JY";
				break;
		}
		$date = date("ymd");
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 6);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 6, 4);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "000" . $newNum;
						break;
					case 2:
						$codeNum = "00" . $newNum;
						break;
					case 3:
						$codeNum = "0" . $newNum;
						break;
					case 4:
						$codeNum = $newNum;
						break;
				}
				$billCode = $preString . $oldDate . $codeNum;
				$codeValue = $oldDate . $codeNum;
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			} else {
				$billCode = $preString . $date . "0001";
				$codeValue = $date . "0001";
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $preString . $date . "0001";
			$codeValue = $date . "0001";
			$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * �̻���ţ��£����º�ͬ����һ����
	 */
	function newChanceCode($row) {

		//		$contract['contractProvince']="����";
		//		$contract['customerTypeName']="��Ӫ�� �й��ƶ�";
		$provId = $row['ProvinceId'];//ʡ��
		$typeName = $row['customerTypeName'];//�ͻ�����
		$billCode = "SJ";
		$billCode = $billCode . date("ymd");
		if (empty($provId)) {
			//ʡ�ݴ���
			$provinceCode = "GW";
		} else {
			//ʡ�ݴ���
			$provinceDao = new model_system_procity_province();
			$provinceCode = $provinceDao->getProTypeCodeById($provId);
		}
		$billCode .= $provinceCode;
		$type = 8;
		//�ͻ����ʴ���
		if (strpos($typeName, "�ƶ�") !== false) {
			$type = 1;
		} else if (strpos($typeName, "��ͨ") !== false) {
			$type = 2;
		} else if (strpos($typeName, "����") !== false) {
			$type = 3;
		} else if (strpos($typeName, "ϵͳ��") !== false) {
			$type = 4;
		} else if (strpos($typeName, "������") !== false) {
			$type = 5;
		} else if (strpos($typeName, "����") !== false) {
			$type = 6;
		} else if (strpos($typeName, "�ӹ�˾") !== false) {
			$type = 7;
		} else if (strpos($typeName, "����") !== false) {
			$type = 8;
		}
		$billCode .= $type;
		$size = strlen($billCode);
		$sql = "select max(RIGHT(c.chanceCode,2)) as maxCode,SUBSTRING(c.chanceCode,1,$size) as _chanceCode" .
			" from oa_sale_chance c group by _chanceCode having _chanceCode='" . $billCode . "'";
		$resArr = $this->findSql($sql);
		$res = $resArr[0];
		if (is_array($res)) {
			$maxCode = $res['maxCode'];
			$newNum = $maxCode + 1;
			switch (strlen($newNum)) {
				case 1:
					$codeNum = "0" . $newNum;
					break;
				case 2:
					$codeNum = $newNum;
					break;
			}
			$billCode .= $codeNum;
		} else {
			$billCode .= "01";
		}

		return $billCode;

	}

	/**
	 * ����������,�̻�����,��ǰ��Ŀ���
	 *
	 * @param $type     �������ͣ�������
	 * @param $customerId   �ͻ�ID
	 */
	function changeCode($type, $customerId) {
		switch ($type) {    //���ǰ׺
			case "oa_borrow_borrow":
				$preString = "JY";
				break;
			case "oa_sale_chance":
				$preString = "SJ";
				break;
			case "oa_ts_task":
				$preString = "SQ";
				break;
		}
		//���ݿͻ�ID��ȡ�ͻ����
		$customerDao = new model_customer_customer_customer();
		$customerCode = $customerDao->getObjectCode($customerId);
		$date = date("ymd");
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 6);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 6, 4);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "000" . $newNum;
						break;
					case 2:
						$codeNum = "00" . $newNum;
						break;
					case 3:
						$codeNum = "0" . $newNum;
						break;
					case 4:
						$codeNum = $newNum;
						break;
				}
				$billCode = $preString . $customerCode . $oldDate . $codeNum;
				$codeValue = $oldDate . $codeNum;
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			} else {
				$billCode = $preString . $customerCode . $date . "0001";
				$codeValue = $date . "0001";
				$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $preString . $customerCode . $date . "0001";
			$codeValue = $date . "0001";
			$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}


	/**
	 * ��ȡҵ�����
	 * ҵ���Ź���ҵ������2λ��YYMMDD�����Ŵ���2λ����ˮ��4Ϊ����������ˮ�ţ�
	 * ҵ�����ʣ����ۺ�ͬXS�������ͬFW�����޺�ͬZN���з���ͬYF�������ͬWB��������ͬQT��������JY������ZS��ά��WX
	 */
	function getObjCode($type, $deptCode) {
		switch ($type) {    //���ǰ׺
			case "HTLX-XSHT":
				$type = "oa_contract_contract_objCode";
				$preString = "XS";
				break;   //���ۺ�ͬ
			case "HTLX-FWHT":
				$type = "oa_contract_contract_objCode";
				$preString = "FW";
				break; //�����ͬ
			case "HTLX-ZLHT":
				$type = "oa_contract_contract_objCode";
				$preString = "ZL";
				break;      //���޺�ͬ
			case "HTLX-YFHT":
				$type = "oa_contract_contract_objCode";
				$preString = "YF";
				break; //�з���ͬ
			case "HTLX-PJGH":
				$type = "oa_contract_contract_objCode";
				$preString = "PG";
				break; //�з���ͬ
			//case "":$preString="WB";break; //�����ͬ
			//case "":$preString="QT";break; //������ͬ
			case "oa_borrow_borrow_objCode":
				$preString = "JY";
				break;//������
			//case "":$preString="WX";break;//ά��
			case "oa_present_present_objCode":
				$preString = "ZS";
				break;//����
			case "oa_sale_other_objCode":
				$preString = "QT";
				break;//������ͬ
			case "oa_sale_outsourcing_objCode":
				$preString = "WB";
				break;//�����ͬ
		}
		//		$rs = $this->_db->get_one ( "select Code from department where DEPT_ID=".$deptId );
		//		$deptCode= $rs ['Code'];
		$date = date("Ymd");

		//		$sql="select codeValue from oa_billcode where codeType='".$type."' and codeTypeAss='".$preString."'";
		//		$codeValue=$this->_db->get_one($sql);
		//		$codeValue=$codeValue['codeValue'];
		$codeValue = $preString . $date;
		$table = substr($type, 0, -8);
		$sql = "select right(objCode,4) as code from " . $table . " where isTemp=0 and  left(objCode,6)='" . $preString . date("Y") . "' order by id desc limit 1";
		$rs = $this->_db->get_one($sql);
		$codeNum = $rs['code'];
		//echo $codeValue;
		if (!empty($codeNum)) {
			$newCode = intval($codeNum) + 1;
			switch (strlen($newCode)) {
				case 1:
					$newCode = "000" . $newCode;
					break;
				case 2:
					$newCode = "00" . $newCode;
					break;
				case 3:
					$newCode = "0" . $newCode;
					break;
				case 4:
					break;
			}
			$objCode = $codeValue . $deptCode . $newCode;
		} else {
			$objCode = $codeValue . $deptCode . "0001";
		}

		//		$updateSql="update oa_billcode set codeValue='".$objCode."' where codeType='".$type."' and codeTypeAss='".$preString."'";
		//		$this->query($updateSql);
		return $objCode;
	}

	/**
	 * �ṩ��ҵ����������ҵ����
	 */
	function getBatchCode($type, $deptCode, $codeValue, $date) {
		switch ($type) {    //���ǰ׺
			case "oa_sale_order_objCode":
				$preString = "XS";
				break;   //���ۺ�ͬ
			case "oa_sale_service_objCode":
				$preString = "FW";
				break; //�����ͬ
			case "oa_sale_lease_objCode":
				$preString = "ZL";
				break;      //���޺�ͬ
			case "oa_sale_rdproject_objCode":
				$preString = "YF";
				break; //�з���ͬ
			//case "":$preString="WB";break; //�����ͬ
			//case "":$preString="QT";break; //������ͬ
			case "oa_borrow_borrow_objCode":
				$preString = "JY";
				break;//������
			//case "":$preString="WX";break;//ά��
			case "oa_present_present_objCode":
				$preString = "ZS";
				break;//����
		}
		//		$rs = $this->_db->get_one ( "select Code from department where DEPT_ID=".$deptId );
		//		$deptCode= $rs ['Code'];
		//$date=date("Ymd");

		if (empty($date)) {
			$date = date("Y-m-d H:i:s");
		}
		//$createTime=substr($createTime,0,10);
		$date = date('Ymd', strtotime($date));//echo $createTime;

		//echo $codeValue;
		if (!empty($codeValue)) {
			$nowYear = substr($date, 0, 4);
			$oldYear = substr($codeValue, 2, 4);
			if ($nowYear != $oldYear) {
				$newCode = $date . $deptCode . "0001";
			} else {
				$num = substr($codeValue, -4);
				$newCode = $num + 1;
				switch (strlen($newCode)) {
					case 1:
						$newCode = "000" . $newCode;
						break;
					case 2:
						$newCode = "00" . $newCode;
						break;
					case 3:
						$newCode = "0" . $newCode;
						break;
					case 4:
						$newCode = $newCode;
						break;
				}
				$newCode = $date . $deptCode . $newCode;
			}
			$objCode = $preString . $newCode;
		} else {
			$objCode = $preString . $date . $deptCode . "0001";
		}
		return $objCode;
	}


	/*************************�̶��ʲ�����***************************************/
	/**
	 * �̶��ʲ�ģ�鿨Ƭ������ʲ����
	 *
	 * @param $type ��������
	 * @param $property �ʲ����ԣ��������̶ֹ��ʲ�/��ֵ����Ʒ
	 */
	function assetcardCode($type, $property, $isReflesh = false) {
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "' and codeTypeAss='" . $property . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		if (!empty($codeRows[0])) {
			if ($isReflesh) {
				$codeNum = "001";
			} else {
				$newNum = $codeRows[0] + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "00" . $newNum;
						break;
					case 2:
						$codeNum = "0" . $newNum;
						break;
					case 3:
						$codeNum = $newNum;
						break;
				}
			}
			$updateSql = "update oa_billcode set codeValue='" . $codeNum . "' where codeType='" . $type . "' and codeTypeAss='" . $property . "'";
		} else {
			$codeNum = "001";
			$updateSql = "insert into oa_billcode (codeTypeName,codeTypeAss,codeType,codeValue) values ('�̶��ʲ���Ƭ','$property','$type','001')";
		}
		$this->query($updateSql);
		return $codeNum;
	}

	/**
	 * �̶��ʲ�ģ�鿨Ƭ������ʲ����
	 *
	 * @param $type ��������
	 * @param $preString ���ݱ��ǰ׺
	 * @param $orgName �ʲ���������
	 * @param $assetabbrev �ʲ�������д
	 * @param $$assetTypeCode2 �ʲ�������
	 * @param $buyDate ��������
	 */
	function assetcardCode2($type, $preString, $orgName, $assetabbrev, $assetTypeCode, $buyDate) {
		$sql = "select codeValue,codeTypeAss from oa_billcode where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		if (!empty($codeRows[0])) {
			$newNum = $codeRows[0] + 1;
			switch (strlen($newNum)) {
				case 1:
					$codeNum = "00000" . $newNum;
					break;
				case 2:
					$codeNum = "0000" . $newNum;
					break;
				case 3:
					$codeNum = "000" . $newNum;
					break;
				case 4:
					$codeNum = "00" . $newNum;
					break;
				case 5:
					$codeNum = "0" . $newNum;
					break;
				case 6:
					$codeNum = $newNum;
					break;
			}
			//$billCode=$preString.$codeNum;
			//���ʲ���������,�ʲ�������д���ʲ���������ת����ƴ��
			$pingYing = new model_common_getPingYing();
			$orgName2 = $pingYing->getFirstPY($orgName);
			$assetabbrev2 = $pingYing->getFirstPY($assetabbrev);
			$assetTypeCode2 = $pingYing->getFirstPY($assetTypeCode);
			//��yy-mm-dd���ڸ�ʽת��yymm
			$time = date('Ym', strtotime($buyDate));
			$billCode = $preString . $orgName2 . $assetabbrev2 . $assetTypeCode2 . $time . $codeNum;
			$updateSql = "update oa_billcode set codeValue='" . $codeNum . "' where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
			$this->query($updateSql);
		} else {
			$billCode = $preString . "000001";
			$updateSql = "update oa_billcode set codeValue='000001' where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * �̶��ʲ�ģ��--���õ��ݱ�ŷ���
	 *
	 * @param $type ��������
	 * @param $preString ���ݱ��ǰ׺
	 * @param $Date ��������
	 * @param $companyCode ��˾���
	 * @param $codeTypeName ���ݱ����������
	 */
	function assetRequireCode($type, $preString, $Date, $companyCode, $codeTypeName, $isReflesh = true) {
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		//��yy-mm-dd���ڸ�ʽת��yymmdd
		$time = date('Ymd', strtotime($Date));
		if (!empty($codeRows[0])) {
			if ($isReflesh) {
				$codeNum = "0001";
			} else {
				$newNum = $codeRows[0] + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "000" . $newNum;
						break;
					case 2:
						$codeNum = "00" . $newNum;
						break;
					case 3:
						$codeNum = "0" . $newNum;
						break;
					case 4:
						$codeNum = "" . $newNum;
						break;
				}
			}
			$billCode = $preString . $time . $companyCode . $codeNum;
			$updateSql = "update oa_billcode set codeValue='" . $codeNum . "' where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
			$this->query($updateSql);
		} else {
			$billCode = $preString . $time . $companyCode . "0001";
			$updateSql = "insert into oa_billcode (codeTypeName,codeTypeAss,codeType,codeValue) values ('$codeTypeName','$preString','$type','0001')";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * �º�ͬ���
	 * ��˾��д DL
	 * ���� ��+��+�գ�����λ��
	 * ʡ�� 2λ
	 * �ͻ�����  1 �й��ƶ���2 �й���ͨ��3 �й����š�4 ϵͳ�̡�5 ��������6���⡢7�ӹ�˾��8����
	 * ��ˮ�� 2λ
	 */
	function newContractCode($contract) {
		//		$contract['contractProvince']="����";
		//		$contract['customerTypeName']="��Ӫ�� �й��ƶ�";
		$provId = $contract['contractProvinceId'];//ʡ��
		//$countryId=$contract['contractCountryId'];//ʡ��
		$typeName = $contract['customerTypeName'];//�ͻ�����
		//		$signSubject=$contract['signSubject'];//ǩԼ��λ
		$signSubject = strtoupper($contract['businessBelong']);//ǩԼ��λ

		$billCode = empty($signSubject) ? "DL" : $signSubject;
		$billCode = $billCode . date("ymd");
		if (empty($provId)) {
			//			$countryDao=new model_system_procity_country();
			//			$country=$countryDao->get_d($countryId);
			$billCode .= "HW";
		} else {
			//ʡ�ݴ���
			$provinceDao = new model_system_procity_province();
			$provinceCode = $provinceDao->getProTypeCodeById($provId);
			$billCode .= $provinceCode;
		}
		$type = 8;
		//�ͻ����ʴ���
		if (strpos($typeName, "�ƶ�") !== false) {
			$type = 1;
		} else if (strpos($typeName, "��ͨ") !== false) {
			$type = 2;
		} else if (strpos($typeName, "����") !== false) {
			$type = 3;
		} else if (strpos($typeName, "ϵͳ��") !== false) {
			$type = 4;
		} else if (strpos($typeName, "������") !== false) {
			$type = 5;
		} else if (strpos($typeName, "����") !== false) {
			$type = 6;
		} else if (strpos($typeName, "�ӹ�˾") !== false) {
			$type = 7;
		} else if (strpos($typeName, "����") !== false) {
			$type = 8;
		}
		$billCode .= $type;
		$size = strlen($billCode);
		$sql = "select max(RIGHT(c.contractCode,2)) as maxCode,SUBSTRING(c.contractCode,1,$size) as _contractCode" .
			" from oa_contract_contract c group by _contractCode having _contractCode='" . $billCode . "'";
		$resArr = $this->findSql($sql);
		$res = $resArr[0];
		if (is_array($res)) {
			$maxCode = $res['maxCode'];
			$newNum = $maxCode + 1;
			switch (strlen($newNum)) {
				case 1:
					$codeNum = "0" . $newNum;
					break;
				case 2:
					$codeNum = $newNum;
					break;
			}
			$billCode .= $codeNum;
		} else {
			$billCode .= "01";
		}

		return $billCode;
	}


	/**
	 *    ����/PK��Ŀ��Ź��򣺣����պ�ͬ��Ź���    ��
	 *  PK+���ڣ�YYMMDD)+ʡ��+��Ӫ��+��ˮ��(2λ��
	 */
	function pkCode($pk) {
		$provId = $pk['provinceId'];//ʡ��
		$typeName = $pk['customerTypeName'];//�ͻ�����
		$billCode = empty($billCode) ? "PK" : $billCode;
		$billCode = $billCode . date("ymd");
		if (empty($provId)) {
			//			$countryDao=new model_system_procity_country();
			//			$country=$countryDao->get_d($countryId);
			$billCode .= "HW";
		} else {
			//ʡ�ݴ���
			$provinceDao = new model_system_procity_province();
			$provinceCode = $provinceDao->getProTypeCodeById($provId);
			$billCode .= $provinceCode;
		}
		$type = 8;
		//�ͻ����ʴ���
		if (strpos($typeName, "�ƶ�") !== false) {
			$type = 1;
		} else if (strpos($typeName, "��ͨ") !== false) {
			$type = 2;
		} else if (strpos($typeName, "����") !== false) {
			$type = 3;
		} else if (strpos($typeName, "ϵͳ��") !== false) {
			$type = 4;
		} else if (strpos($typeName, "������") !== false) {
			$type = 5;
		} else if (strpos($typeName, "����") !== false) {
			$type = 6;
		} else if (strpos($typeName, "�ӹ�˾") !== false) {
			$type = 7;
		} else if (strpos($typeName, "����") !== false) {
			$type = 8;
		}
		$size = strlen($billCode);
		$billCode .= $type;
		$size = strlen($billCode);
		$sql = "select max(RIGHT(c.projectCode,2)) as maxCode,SUBSTRING(c.projectCode,1,$size) as _projectCode" .
			" from oa_trialproject_trialproject c group by _projectCode having _projectCode='" . $billCode . "'";
		$resArr = $this->findSql($sql);
		$res = $resArr[0];
		if (is_array($res)) {
			$maxCode = $res['maxCode'];
			$newNum = $maxCode + 1;
			switch (strlen($newNum)) {
				case 1:
					$codeNum = "0" . $newNum;
					break;
				case 2:
					$codeNum = $newNum;
					break;
			}
			$billCode .= $codeNum;
		} else {
			$billCode .= "01";
		}

		return $billCode;
	}

	/**
	 * ���̳�Ȩ�����뵥
	 * @param $type
	 */
	function exceptionApplyCode($type) {
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		if (!empty($codeRows[0])) {
			$newNum = $codeRows[0] + 1;
			switch (strlen($newNum)) {
				case 1:
					$codeNum = "000" . $newNum;
					break;
				case 2:
					$codeNum = "00" . $newNum;
					break;
				case 3:
					$codeNum = "0" . $newNum;
					break;
				case 4:
					$codeNum = "" . $newNum;
					break;
			}
			$billCode = $codeNum;
			$updateSql = "update oa_billcode set codeValue='" . $codeNum . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		} else {
			$billCode = "0001";
			$updateSql = "insert into oa_billcode (codeTypeName,codeType,codeValue) values ('���̳�Ȩ������','$type','0001')";
			$this->query($updateSql);
		}
		return $billCode;
	}


	/**
	 * �����ƻ����
	 *
	 * @param $type ��������
	 */
	function sendPlanCode($type) {
		$year = date("y");
		$week = date("W");   //�ܴ�
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldYear = substr($codeValue[0], 0, 2);
			$oldWeek = substr($codeValue[0], 2, 2);
			if ($year == $oldYear) {
				if ($week == $oldWeek) {
					$num = substr($codeValue[0], 4, 6);
					$newNum = $num + 1;
					switch (strlen($newNum)) {
						case 1:
							$codeNum = "00000" . $newNum;
							break;
						case 2:
							$codeNum = "0000" . $newNum;
							break;
						case 3:
							$codeNum = "000" . $newNum;
							break;
						case 4:
							$codeNum = "00" . $newNum;
							break;
						case 5:
							$codeNum = "0" . $newNum;
							break;
						case 6:
							$codeNum = $newNum;
							break;
					}
					$billCode = "FHJH" . $oldYear . $oldWeek . $codeNum;
					$codeValue = $oldYear . $oldWeek . $codeNum;;
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
					$this->query($updateSql);
				} else {
					$billCode = "FHJH" . $oldYear . $week . "000001";
					$codeValue = $oldYear . $week . "000001";
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
					$this->query($updateSql);
				}
			} else {
				if ($week == $oldWeek) {
					$num = substr($codeValue[0], 4, 6);
					$newNum = $num + 1;
					switch (strlen($newNum)) {
						case 1:
							$codeNum = "00000" . $newNum;
							break;
						case 2:
							$codeNum = "0000" . $newNum;
							break;
						case 3:
							$codeNum = "000" . $newNum;
							break;
						case 4:
							$codeNum = "00" . $newNum;
							break;
						case 5:
							$codeNum = "0" . $newNum;
							break;
						case 6:
							$codeNum = $newNum;
							break;
					}
					$billCode = "FHJH" . $year . $oldWeek . $codeNum;
					$codeValue = $year . $oldWeek . $codeNum;;
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
					$this->query($updateSql);
				} else {
					$billCode = "FHJH" . $year . $week . "000001";
					$codeValue = $year . $week . "000001";
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
					$this->query($updateSql);
				}
			}
		} else {
			$billCode = "FHJH" . $year . $week . "000001";
			$codeValue = $year . $week . "000001";
			$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		}
		return $billCode;
	}


	/**
	 * ���ñ�����
	 * @param $type
	 */
	function expenseCode($type, $deptId) {
		$date = date("Ymd");
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 8);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 11, 3);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "00" . $newNum;
						break;
					case 2:
						$codeNum = "0" . $newNum;
						break;
					case 3:
						$codeNum = $newNum;
						break;
				}
				$billCode = $oldDate . str_pad($deptId, 3, 0, STR_PAD_LEFT) . $codeNum;
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			} else {
				$billCode = $date . str_pad($deptId, 3, 0, STR_PAD_LEFT) . "001";
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $date . str_pad($deptId, 3, 0, STR_PAD_LEFT) . "001";
			$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		}
		return 'FYBX' . $billCode;
	}

	/**
	 * ���ñ�����
	 * @param $type
	 */
	function exbillCode($type) {
		$date = date("Ymd");
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 8);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 8, 4);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "000" . $newNum;
						break;
					case 2:
						$codeNum = "00" . $newNum;
						break;
					case 3:
						$codeNum = "0" . $newNum;
						break;
					case 4:
						$codeNum = $newNum;
						break;
				}
				$billCode = $oldDate . $codeNum;
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			} else {
				$billCode = $date . "0001";
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $date . "0001";
			$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		}
		return 'MX' . $billCode;
	}


	/**
	 * ����֪ͨ�����
	 *
	 * @param $type ��������
	 */
	function sendDrawCode($type) {
		$year = date("y");
		$week = date("W");   //�ܴ�
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldYear = substr($codeValue[0], 0, 2);
			$oldWeek = substr($codeValue[0], 2, 2);
			if ($year == $oldYear) {
				if ($week == $oldWeek) {
					$num = substr($codeValue[0], 4, 6);
					$newNum = $num + 1;
					switch (strlen($newNum)) {
						case 1:
							$codeNum = "00000" . $newNum;
							break;
						case 2:
							$codeNum = "0000" . $newNum;
							break;
						case 3:
							$codeNum = "000" . $newNum;
							break;
						case 4:
							$codeNum = "00" . $newNum;
							break;
						case 5:
							$codeNum = "0" . $newNum;
							break;
						case 6:
							$codeNum = $newNum;
							break;
					}
					$billCode = "HHTZ" . $oldYear . $oldWeek . $codeNum;
					$codeValue = $oldYear . $oldWeek . $codeNum;;
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
					$this->query($updateSql);
				} else {
					$billCode = "HHTZ" . $oldYear . $week . "000001";
					$codeValue = $oldYear . $week . "000001";
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
					$this->query($updateSql);
				}
			} else {
				if ($week == $oldWeek) {
					$num = substr($codeValue[0], 4, 6);
					$newNum = $num + 1;
					switch (strlen($newNum)) {
						case 1:
							$codeNum = "00000" . $newNum;
							break;
						case 2:
							$codeNum = "0000" . $newNum;
							break;
						case 3:
							$codeNum = "000" . $newNum;
							break;
						case 4:
							$codeNum = "00" . $newNum;
							break;
						case 5:
							$codeNum = "0" . $newNum;
							break;
						case 6:
							$codeNum = $newNum;
							break;
					}
					$billCode = "HHTZ" . $year . $oldWeek . $codeNum;
					$codeValue = $year . $oldWeek . $codeNum;;
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
					$this->query($updateSql);
				} else {
					$billCode = "HHTZ" . $year . $week . "000001";
					$codeValue = $year . $week . "000001";
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "'";
					$this->query($updateSql);
				}
			}
		} else {
			$billCode = "HHTZ" . $year . $week . "000001";
			$codeValue = $year . $week . "000001";
			$updateSql = "insert into oa_billcode (codeTypeName,codeType,codeValue) values ('�ջ�֪ͨ��','" . $type . "','" . $codeValue . "')";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * �豸���뵥
	 * @param $type
	 */
	function resourceapplyCode($type) {
		$date = date("Ymd");
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 8);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 8, 4);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "000" . $newNum;
						break;
					case 2:
						$codeNum = "00" . $newNum;
						break;
					case 3:
						$codeNum = "0" . $newNum;
						break;
					case 4:
						$codeNum = $newNum;
						break;
				}
				$billCode = $oldDate . $codeNum;
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			} else {
				$billCode = $date . "0001";
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $date . "0001";
			$updateSql = "insert into oa_billcode (codeTypeName,codeTypeAss,codeValue,codeType) values ('������Ŀ�豸���뵥','SBSQ','" . $billCode . "','" . $type . "')";
			$this->query($updateSql);
		}
		return 'SBSQ' . $billCode;
	}

	/**
	 * �̶��ʲ�ģ��ɹ�����-���յ���
	 *
	 * @param $type ��������
	 * @param $preString ���ݱ��ǰ׺
	 * @param $Date ��������
	 */
	function assetReceiveCode($type, $preString, $Date, $companyCode, $isReflesh = true) {
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		//��yy-mm-dd���ڸ�ʽת��yymmdd
		$time = date('Ymd', strtotime($Date));
		if (!empty($codeRows[0])) {
			if ($isReflesh) {
				$codeNum = "0001";
			} else {
				$newNum = $codeRows[0] + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "000" . $newNum;
						break;
					case 2:
						$codeNum = "00" . $newNum;
						break;
					case 3:
						$codeNum = "0" . $newNum;
						break;
					case 4:
						$codeNum = "" . $newNum;
						break;
				}
			}
			$updateSql = "update oa_billcode set codeValue='" . $codeNum . "' where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
		} else {
			$codeNum = "0001";
			$updateSql = "insert into oa_billcode (codeTypeName,codeTypeAss,codeType,codeValue) values ('�ʲ����յ���','$preString','$type','0001')";
		}
		$this->query($updateSql);
		return $preString . $time . $companyCode . $codeNum;
	}

	/**
	 * ����֪ͨ�����
	 * @param $headStr �������ֲ�ͬ��ҵ��
	 * @param $type ��������
	 */
	function sendNoticeCode($headStr,$type) {
		$year = date("y");
		$week = date("W");   //�ܴ�
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "' and codeTypeAss='" . $headStr ."'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldYear = substr($codeValue[0], 0, 2);
			$oldWeek = substr($codeValue[0], 2, 2);
			if ($year == $oldYear) {
				if ($week == $oldWeek) {
					$num = substr($codeValue[0], 4, 6);
					$newNum = $num + 1;
					switch (strlen($newNum)) {
						case 1:
							$codeNum = "00000" . $newNum;
							break;
						case 2:
							$codeNum = "0000" . $newNum;
							break;
						case 3:
							$codeNum = "000" . $newNum;
							break;
						case 4:
							$codeNum = "00" . $newNum;
							break;
						case 5:
							$codeNum = "0" . $newNum;
							break;
						case 6:
							$codeNum = $newNum;
							break;
					}
					$billCode = "RKTZ" . $oldYear . $oldWeek . $codeNum;
					$codeValue = $oldYear . $oldWeek . $codeNum;;
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "' and codeTypeAss='" . $headStr ."'";;
					$this->query($updateSql);
				} else {
					$billCode = "RKTZ" . $oldYear . $week . "000001";
					$codeValue = $oldYear . $week . "000001";
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "' and codeTypeAss='" . $headStr ."'";;
					$this->query($updateSql);
				}
			} else {
				if ($week == $oldWeek) {
					$num = substr($codeValue[0], 4, 6);
					$newNum = $num + 1;
					switch (strlen($newNum)) {
						case 1:
							$codeNum = "00000" . $newNum;
							break;
						case 2:
							$codeNum = "0000" . $newNum;
							break;
						case 3:
							$codeNum = "000" . $newNum;
							break;
						case 4:
							$codeNum = "00" . $newNum;
							break;
						case 5:
							$codeNum = "0" . $newNum;
							break;
						case 6:
							$codeNum = $newNum;
							break;
					}
					$billCode = "RKTZ" . $year . $oldWeek . $codeNum;
					$codeValue = $year . $oldWeek . $codeNum;;
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "' and codeTypeAss='" . $headStr ."'";;
					$this->query($updateSql);
				} else {
					$billCode = "RKTZ" . $year . $week . "000001";
					$codeValue = $year . $week . "000001";
					$updateSql = "update oa_billcode set codeValue='" . $codeValue . "' where codeType='" . $type . "' and codeTypeAss='" . $headStr ."'";;
					$this->query($updateSql);
				}
			}
		} else {
			$billCode = "RKTZ" . $year . $week . "000001";
			$codeValue = $year . $week . "000001";
			$updateSql = "insert into oa_billcode (codeTypeName,codeType,codeTypeAss,codeValue) values ('���֪ͨ��','$type','$headStr','$billCode')";
			$this->query($updateSql);
		}
		return $billCode;

	}

	/**
	 * ͨ�ñ������
	 * @p1 ��������
	 * @p2 �������
	 * @p3 ͷ�ַ���
	 * �����ʽ $TYPE.$DATE.$NUM AAAA201307010001
	 */
	function commonCode($name, $type, $headStr) {
		$date = date("Ymd");
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if ($codeValue) {
			$oldDate = substr($codeValue[0], 0, 8);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 8, 3);
				$num++;
				$billCode = $oldDate . str_pad($num, 3, 0, STR_PAD_LEFT);
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			} else {
				$billCode = $date . "001";
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $date . "001";
			$updateSql = "insert into oa_billcode (codeTypeName,codeType,codeTypeAss,codeValue) values ('$name','$type','$headStr','$billCode')";
			$this->query($updateSql);
		}
		return $headStr . $billCode;
	}

	/**
	 * ͨ�ñ������
	 * @p1 ��������
	 * @p2 �������
	 * @p3 ͷ�ַ���
	 * �����ʽ $TYPE.$NUM AAAA000001
	 */
	function commonCodeEasy($name, $type, $headStr) {
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if ($codeValue) {
			$num = $codeValue[0];
			$num++;
			$billCode = str_pad($num, 6, 0, STR_PAD_LEFT);
			$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
			$this->query($updateSql);
		} else {
			$billCode = "000001";
			$updateSql = "insert into oa_billcode (codeTypeName,codeType,codeTypeAss,codeValue) values ('$name','$type','$headStr','$billCode')";
			$this->query($updateSql);
		}
		return $headStr . $billCode;
	}


	/**
	 * ͨ�ù���
	 * @param $name
	 * @param $type
	 * @param $headStr
	 * @param $deptId
	 * @return string
	 */
	function commonCode2($name, $type, $headStr, $deptId) {
		$date = date("Ymd");
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "'";
		$res = $this->query($sql);
		$codeValue = mysql_fetch_row($res);
		if (!empty($codeValue[0])) {
			$oldDate = substr($codeValue[0], 0, 8);
			if ($date == $oldDate) {
				$num = substr($codeValue[0], 11, 3);
				$newNum = $num + 1;
				switch (strlen($newNum)) {
					case 1:
						$codeNum = "00" . $newNum;
						break;
					case 2:
						$codeNum = "0" . $newNum;
						break;
					case 3:
						$codeNum = $newNum;
						break;
				}
				$billCode = $oldDate . str_pad($deptId, 3, 0, STR_PAD_LEFT) . $codeNum;
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			} else {
				$billCode = $date . str_pad($deptId, 3, 0, STR_PAD_LEFT) . "001";
				$updateSql = "update oa_billcode set codeValue='" . $billCode . "' where codeType='" . $type . "'";
				$this->query($updateSql);
			}
		} else {
			$billCode = $date . str_pad($deptId, 3, 0, STR_PAD_LEFT) . "001";
			$updateSql = "insert into oa_billcode (codeTypeName,codeType,codeTypeAss,codeValue) values ('$name','$type','$headStr','$billCode')";
			$this->query($updateSql);
		}
		return $headStr . $billCode;
	}
}