<?php

/**    单据编号生成MODEL层
 * Created on 2011-6-23
 * Created by suxc
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_common_codeRule extends model_base
{

	/**
	 * 获取用户编号
	 *
	 * @param $userId 用户ID
	 */
	function getUserCard($userId) {
		$userSql = "select UserCard from hrms where USER_ID='" . $userId . "'";
		$userCard = mysql_fetch_row($this->query($userSql));
		return $userCard[0];
	}

	/**
	 * 获取用户编号
	 *
	 * @param $userCard 用户编码
	 */
	function getUserIdByCard($userCard) {
		$userSql = "select USER_ID from hrms where UserCard='" . $userCard . "'";
		$userCard = mysql_fetch_row($this->query($userSql));
		return $userCard[0];
	}

	/**
	 *采购申请单编号生成方法
	 *
	 * @param $tblname 采购申请单表名
	 * @param $purchType 采购类型
	 */
	function purchApplyCode($tblname, $purchType) {
		$codeStr = "DLC";     //编号前缀
		$date = date("ymd");
		switch ($purchType) {   //根据采购类型返回类型编号
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
	 * 采购任务编号
	 *
	 * @param $tblname 采购任务表名
	 * @param $name   采购员ID
	 */
	function purchTaskCode($tblname, $userID) {
		$date = date("ymd");
		//获取用户编号
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
	 * 采购订单编号
	 *
	 * @param $tblname 采购订单表名
	 * @param $name   采购员ID
	 */
	function purchOrderCode($tblname, $userID) {
		$pingYing = new model_common_getPingYing();
		$codeStr = "DLC";     //编号前缀
		$date = date("ymd");
		//获取用户编号
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
	 * 采购询价单，收料通知单，退料通知单编号生成方法.
	 *
	 * @param $type 单据类型
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
	 * 采购发票编号
	 *
	 * @param $tblname 采购发票表名
	 * @param $name   采购员姓名
	 */
	function purchInvoiceCode($tblname, $userID) {
		$pingYing = new model_common_getPingYing();
		$codeStr = "FP";     //编号前缀
		$date = date("ymd");
		//		if($name=="邝碧瑜"){     //“邝” 转换错误，暂时先这样处理
		//			$nameCode="KBY";
		//		}else{
		//			$nameCode=$pingYing->getFirstPY($name);
		////			$nameCode=$this->getInitials($name);
		//		}

		//获取用户编号
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
	 * 应付其他发票
	 *
	 * @param $tblname
	 * @param $name 业务员姓名
	 */
	function invotherCode($tblname, $userID) {
		$pingYing = new model_common_getPingYing();
		$codeStr = "QTFP";     //编号前缀
		$date = date("ymd");

		//获取用户编号
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
	 * 供应商编号
	 *
	 * @param $type 单据类型
	 * @param $preString 单据编号前缀
	 */
	function supplierCode($type) {
		switch ($type) {
			case "oa_supp_lib":
				$preString = "01";
				break;         //正式
			case "oa_supp_lib_temp":
				$preString = "02";
				break;    //临时
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
	 * 外包供应商编号
	 *
	 * @param $type 单据类型
	 * @param $preString 单据编号前缀
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
	 * 仓存模块单据编号
	 *
	 * @param $type 单据类型
	 * @param $preString 单据编号前缀
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
	 * 配件销售编码
	 *
	 * @param $type 单据类型
	 * @param $preString 单据编号前缀
	 * @param $prov 省份
	 * @param $codeTypeName 业务名称
	 */
	function accessorderCode($type, $preString, $prov, $codeTypeName, $isReflesh = true) {
		//获取省份拼音首字母
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
	 * 财务部分编号
	 *
	 * @param $type 单据类型
	 * @param $preString 单据编号前缀
	 * @param $midString 单据编号中间字符串
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
	//	 * 发货计划编号
	//	 *
	//	 * @param $type 单据类型
	//	 */
	//	function sendPlanCode ($type) {
	//		$year=date("y");
	//		$week=date("W");   //周次
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
	 * 发货单编号
	 *
	 * @param $type 单据类型
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
	 * 客户编号.
	 *
	 * @param $type
	 * @param $customerCode  客户性质代码
	 * @param $countryId    国家ID
	 * @param $cityId    城市ID
	 */
	function customerCode($type, $customerCode, $countryId, $cityId) {
		//获取国家编码
		$rs = $this->_db->get_one("select countryCode from oa_system_country_info where id=" . $countryId);
		$countryCode = $rs ['countryCode'];
		if ($cityId && $cityId != "请选择城市") {
			//获取城市编码
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
	 * 通用的编号
	 * @param $type 单据类型
	 * @param $preString 单据编号前缀
	 * @param $digits 流水号位数
	 * @param $codeTypeName 类型名
	 * @param $codeTypeAss 类型编号
	 * 编码格式 $prefix.$num AAAA001
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
	 *鼎利为卖方（乙方）合同编号
	 *
	 * @param $type        合同类型
	 * @param $customerCode  客户性质代码
	 * @param $countryId    国家ID
	 * @param $cityId    城市ID
	 */
	function contractCode($type, $customerId) {
		$preString = "DLA";
		switch ($type) {
			case "oa_sale_order":
				$contractType = "01";
				break;   //销售合同
			case "oa_sale_service":
				$contractType = "02";
				break; //服务合同
			case "oa_sale_lease":
				$contractType = "03";
				break;      //租赁合同
			case "oa_sale_rdproject":
				$contractType = "04";
				break; //研发合同
			default:
				$contractType = "05";
				break;
		}
		//根据客户ID获取客户编号
		$customerDao = new model_customer_customer_customer();
		$customerCode = $customerDao->getObjectCode($customerId);
		$customerCode = substr($customerCode, 0, -2);
		$date = date("ymd");
		//获取流水号
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
		//更新流水号
		//			$updateSql="update oa_billcode set codeValue='".$newNum."' where codeType='".$type."'";
		$updateSql = "update oa_billcode set codeValue='" . $newNum . "' where codeType='oa_sale_order'";
		$this->query($updateSql);
		//		}
		return $preString . $contractType . $customerCode . $date . $codeNum;
	}

	/**
	 *鼎利为买方（甲方）合同编号
	 *
	 * @param $type        合同类型
	 * @param $deptId    部门ID
	 */
	function contractBuyCode($type, $deptId) {
		$preString = "DLB";
		switch ($type) {
			case "oa_sale_service":
				$contractType = "01";
				break;//采购合同
			case "":
				$contractType = "02";
				break;//服务外包
			case "":
				$contractType = "03";
				break;//委托开发
			case "":
				$contractType = "04";
				break;//收购
			case "":
				$contractType = "05";
				break;//咨询
			case "":
				$contractType = "06";
				break;//技术服务
			case "":
				$contractType = "07";
				break;//其他
		}
		$rs = $this->_db->get_one("select Code from department where DEPT_ID=" . $deptId);
		$deptCode = $rs ['Code'];
		$date = date("ymd");
		//获取流水号
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
		//更新流水号
		$updateSql = "update oa_billcode set codeValue='" . $newNum . "' where codeType='" . $type . "'";
		$this->query($updateSql);
		$billCode = $preString . $contractType . $deptCode . $date . $codeNum;
		return $billCode;
	}

	/**
	 *鼎利对外合作协议
	 *
	 * @param $type      合同类型
	 * @param $deptId    部门ID
	 */
	function contractExternalCode($type, $deptId) {
		$preString = "DLC";
		switch ($type) {
			case "oa_sale_service":
				$contractType = "01";
				break;//保密协议
			case "":
				$contractType = "02";
				break;//
		}
		$rs = $this->_db->get_one("select Code from department where DEPT_ID=" . $deptId);
		$deptCode = $rs ['Code'];
		$date = date("ymd");
		//获取流水号
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
		//更新流水号
		$updateSql = "update oa_billcode set codeValue='" . $newNum . "' where codeType='" . $type . "'";
		$this->query($updateSql);
		$billCode = $preString . $contractType . $deptCode . $date . $codeNum;
		return $billCode;
	}

	/**
	 * 借试用编号 （新）
	 */
	function borrowCode($type, $borrowType) {
		switch ($borrowType) {    //编号前缀
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
	 * 商机编号（新，跟新合同规则一样）
	 */
	function newChanceCode($row) {

		//		$contract['contractProvince']="安徽";
		//		$contract['customerTypeName']="运营商 中国移动";
		$provId = $row['ProvinceId'];//省份
		$typeName = $row['customerTypeName'];//客户性质
		$billCode = "SJ";
		$billCode = $billCode . date("ymd");
		if (empty($provId)) {
			//省份处理
			$provinceCode = "GW";
		} else {
			//省份处理
			$provinceDao = new model_system_procity_province();
			$provinceCode = $provinceDao->getProTypeCodeById($provId);
		}
		$billCode .= $provinceCode;
		$type = 8;
		//客户性质处理
		if (strpos($typeName, "移动") !== false) {
			$type = 1;
		} else if (strpos($typeName, "联通") !== false) {
			$type = 2;
		} else if (strpos($typeName, "电信") !== false) {
			$type = 3;
		} else if (strpos($typeName, "系统商") !== false) {
			$type = 4;
		} else if (strpos($typeName, "第三方") !== false) {
			$type = 5;
		} else if (strpos($typeName, "海外") !== false) {
			$type = 6;
		} else if (strpos($typeName, "子公司") !== false) {
			$type = 7;
		} else if (strpos($typeName, "其他") !== false) {
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
	 * 借试用申请,商机立项,售前项目编号
	 *
	 * @param $type     单据类型（表名）
	 * @param $customerId   客户ID
	 */
	function changeCode($type, $customerId) {
		switch ($type) {    //编号前缀
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
		//根据客户ID获取客户编号
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
	 * 获取业务编码
	 * 业务编号规则：业务性质2位，YYMMDD，部门代码2位，流水号4为（按年起流水号）
	 * 业务性质：销售合同XS，服务合同FW，租赁合同ZN，研发合同YF，外包合同WB，其他合同QT，借试用JY，赠送ZS，维修WX
	 */
	function getObjCode($type, $deptCode) {
		switch ($type) {    //编号前缀
			case "HTLX-XSHT":
				$type = "oa_contract_contract_objCode";
				$preString = "XS";
				break;   //销售合同
			case "HTLX-FWHT":
				$type = "oa_contract_contract_objCode";
				$preString = "FW";
				break; //服务合同
			case "HTLX-ZLHT":
				$type = "oa_contract_contract_objCode";
				$preString = "ZL";
				break;      //租赁合同
			case "HTLX-YFHT":
				$type = "oa_contract_contract_objCode";
				$preString = "YF";
				break; //研发合同
			case "HTLX-PJGH":
				$type = "oa_contract_contract_objCode";
				$preString = "PG";
				break; //研发合同
			//case "":$preString="WB";break; //外包合同
			//case "":$preString="QT";break; //其他合同
			case "oa_borrow_borrow_objCode":
				$preString = "JY";
				break;//借试用
			//case "":$preString="WX";break;//维修
			case "oa_present_present_objCode":
				$preString = "ZS";
				break;//赠送
			case "oa_sale_other_objCode":
				$preString = "QT";
				break;//其他合同
			case "oa_sale_outsourcing_objCode":
				$preString = "WB";
				break;//外包合同
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
	 * 提供给业务批量产生业务编号
	 */
	function getBatchCode($type, $deptCode, $codeValue, $date) {
		switch ($type) {    //编号前缀
			case "oa_sale_order_objCode":
				$preString = "XS";
				break;   //销售合同
			case "oa_sale_service_objCode":
				$preString = "FW";
				break; //服务合同
			case "oa_sale_lease_objCode":
				$preString = "ZL";
				break;      //租赁合同
			case "oa_sale_rdproject_objCode":
				$preString = "YF";
				break; //研发合同
			//case "":$preString="WB";break; //外包合同
			//case "":$preString="QT";break; //其他合同
			case "oa_borrow_borrow_objCode":
				$preString = "JY";
				break;//借试用
			//case "":$preString="WX";break;//维修
			case "oa_present_present_objCode":
				$preString = "ZS";
				break;//赠送
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


	/*************************固定资产部分***************************************/
	/**
	 * 固定资产模块卡片管理的资产编号
	 *
	 * @param $type 单据类型
	 * @param $property 资产属性，用以区分固定资产/低值耐用品
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
			$updateSql = "insert into oa_billcode (codeTypeName,codeTypeAss,codeType,codeValue) values ('固定资产卡片','$property','$type','001')";
		}
		$this->query($updateSql);
		return $codeNum;
	}

	/**
	 * 固定资产模块卡片管理的资产编号
	 *
	 * @param $type 单据类型
	 * @param $preString 单据编号前缀
	 * @param $orgName 资产所属部门
	 * @param $assetabbrev 资产名称缩写
	 * @param $$assetTypeCode2 资产类别编码
	 * @param $buyDate 购置日期
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
			//将资产所属部门,资产名称缩写，资产类别的中文转化成拼音
			$pingYing = new model_common_getPingYing();
			$orgName2 = $pingYing->getFirstPY($orgName);
			$assetabbrev2 = $pingYing->getFirstPY($assetabbrev);
			$assetTypeCode2 = $pingYing->getFirstPY($assetTypeCode);
			//将yy-mm-dd日期格式转成yymm
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
	 * 固定资产模块--公用单据编号方法
	 *
	 * @param $type 单据类型
	 * @param $preString 单据编号前缀
	 * @param $Date 创建日期
	 * @param $companyCode 公司编号
	 * @param $codeTypeName 单据编号类型名称
	 */
	function assetRequireCode($type, $preString, $Date, $companyCode, $codeTypeName, $isReflesh = true) {
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		//将yy-mm-dd日期格式转成yymmdd
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
	 * 新合同编号
	 * 公司缩写 DL
	 * 日期 年+月+日（各两位）
	 * 省份 2位
	 * 客户性质  1 中国移动、2 中国联通、3 中国电信、4 系统商、5 第三方、6海外、7子公司、8其他
	 * 流水号 2位
	 */
	function newContractCode($contract) {
		//		$contract['contractProvince']="安徽";
		//		$contract['customerTypeName']="运营商 中国移动";
		$provId = $contract['contractProvinceId'];//省份
		//$countryId=$contract['contractCountryId'];//省份
		$typeName = $contract['customerTypeName'];//客户性质
		//		$signSubject=$contract['signSubject'];//签约单位
		$signSubject = strtoupper($contract['businessBelong']);//签约单位

		$billCode = empty($signSubject) ? "DL" : $signSubject;
		$billCode = $billCode . date("ymd");
		if (empty($provId)) {
			//			$countryDao=new model_system_procity_country();
			//			$country=$countryDao->get_d($countryId);
			$billCode .= "HW";
		} else {
			//省份处理
			$provinceDao = new model_system_procity_province();
			$provinceCode = $provinceDao->getProTypeCodeById($provId);
			$billCode .= $provinceCode;
		}
		$type = 8;
		//客户性质处理
		if (strpos($typeName, "移动") !== false) {
			$type = 1;
		} else if (strpos($typeName, "联通") !== false) {
			$type = 2;
		} else if (strpos($typeName, "电信") !== false) {
			$type = 3;
		} else if (strpos($typeName, "系统商") !== false) {
			$type = 4;
		} else if (strpos($typeName, "第三方") !== false) {
			$type = 5;
		} else if (strpos($typeName, "海外") !== false) {
			$type = 6;
		} else if (strpos($typeName, "子公司") !== false) {
			$type = 7;
		} else if (strpos($typeName, "其他") !== false) {
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
	 *    试用/PK项目编号规则：（参照合同编号规则）    　
	 *  PK+日期（YYMMDD)+省份+运营商+流水号(2位）
	 */
	function pkCode($pk) {
		$provId = $pk['provinceId'];//省份
		$typeName = $pk['customerTypeName'];//客户性质
		$billCode = empty($billCode) ? "PK" : $billCode;
		$billCode = $billCode . date("ymd");
		if (empty($provId)) {
			//			$countryDao=new model_system_procity_country();
			//			$country=$countryDao->get_d($countryId);
			$billCode .= "HW";
		} else {
			//省份处理
			$provinceDao = new model_system_procity_province();
			$provinceCode = $provinceDao->getProTypeCodeById($provId);
			$billCode .= $provinceCode;
		}
		$type = 8;
		//客户性质处理
		if (strpos($typeName, "移动") !== false) {
			$type = 1;
		} else if (strpos($typeName, "联通") !== false) {
			$type = 2;
		} else if (strpos($typeName, "电信") !== false) {
			$type = 3;
		} else if (strpos($typeName, "系统商") !== false) {
			$type = 4;
		} else if (strpos($typeName, "第三方") !== false) {
			$type = 5;
		} else if (strpos($typeName, "海外") !== false) {
			$type = 6;
		} else if (strpos($typeName, "子公司") !== false) {
			$type = 7;
		} else if (strpos($typeName, "其他") !== false) {
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
	 * 工程超权限申请单
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
			$updateSql = "insert into oa_billcode (codeTypeName,codeType,codeValue) values ('工程超权限申请','$type','0001')";
			$this->query($updateSql);
		}
		return $billCode;
	}


	/**
	 * 发货计划编号
	 *
	 * @param $type 单据类型
	 */
	function sendPlanCode($type) {
		$year = date("y");
		$week = date("W");   //周次
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
	 * 费用报销单
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
	 * 费用报销单
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
	 * 换货通知单编号
	 *
	 * @param $type 单据类型
	 */
	function sendDrawCode($type) {
		$year = date("y");
		$week = date("W");   //周次
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
			$updateSql = "insert into oa_billcode (codeTypeName,codeType,codeValue) values ('收货通知单','" . $type . "','" . $codeValue . "')";
			$this->query($updateSql);
		}
		return $billCode;
	}

	/**
	 * 设备申请单
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
			$updateSql = "insert into oa_billcode (codeTypeName,codeTypeAss,codeValue,codeType) values ('工程项目设备申请单','SBSQ','" . $billCode . "','" . $type . "')";
			$this->query($updateSql);
		}
		return 'SBSQ' . $billCode;
	}

	/**
	 * 固定资产模块采购管理-验收单号
	 *
	 * @param $type 单据类型
	 * @param $preString 单据编号前缀
	 * @param $Date 创建日期
	 */
	function assetReceiveCode($type, $preString, $Date, $companyCode, $isReflesh = true) {
		$sql = "select codeValue from oa_billcode where codeType='" . $type . "' and codeTypeAss='" . $preString . "'";
		$res = $this->query($sql);
		$codeRows = mysql_fetch_row($res);
		//将yy-mm-dd日期格式转成yymmdd
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
			$updateSql = "insert into oa_billcode (codeTypeName,codeTypeAss,codeType,codeValue) values ('资产验收单号','$preString','$type','0001')";
		}
		$this->query($updateSql);
		return $preString . $time . $companyCode . $codeNum;
	}

	/**
	 * 换货通知单编号
	 * @param $headStr 用来区分不同的业务
	 * @param $type 单据类型
	 */
	function sendNoticeCode($headStr,$type) {
		$year = date("y");
		$week = date("W");   //周次
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
			$updateSql = "insert into oa_billcode (codeTypeName,codeType,codeTypeAss,codeValue) values ('入库通知单','$type','$headStr','$billCode')";
			$this->query($updateSql);
		}
		return $billCode;

	}

	/**
	 * 通用编码规则
	 * @p1 规则名称
	 * @p2 规则编码
	 * @p3 头字符串
	 * 编码格式 $TYPE.$DATE.$NUM AAAA201307010001
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
	 * 通用编码规则
	 * @p1 规则名称
	 * @p2 规则编码
	 * @p3 头字符串
	 * 编码格式 $TYPE.$NUM AAAA000001
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
	 * 通用规则
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