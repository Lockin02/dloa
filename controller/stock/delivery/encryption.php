<?php
/**
 * @author Michael
 * @Date 2014年5月29日 16:42:09
 * @version 1.0
 * @description:加密锁任务控制层
 */
class controller_stock_delivery_encryption extends controller_base_action {

	function __construct() {
		$this->objName = "encryption";
		$this->objPath = "stock_delivery";
		parent::__construct ();
	}

	/**
	 * 跳转到加密锁管理列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到加密锁任务列表
	 */
	function c_pageMission() {
		$this->view('list-mission');
	}

	/**
	 * 跳转到未完成加密锁任务列表
	 */
	function c_notPage() {
		$this->view('list-not');
	}

	/**
	 * 跳转到未完成加密锁任务列表（可编辑）
	 */
	function c_notPageMission() {
		$this->view('list-notMission');
	}

	/**
	 * 跳转到已完成加密锁任务列表
	 */
	function c_yesPage() {
		$this->view('list-yes');
	}

	/**
	 * 跳转到新增加密锁任务页面
	 */
	function c_toAdd() {
		$sourceDocId = isset ( $_GET ['sourceDocId'] ) ? $_GET ['sourceDocId'] : null;
		$equIds = isset ( $_GET ['equIds'] ) ? $_GET ['equIds'] : null;

		$contractDao = new model_contract_contract_contract ();
		$obj = $contractDao->getContractInfo ( $sourceDocId, array ("equ" ) );
		$this->assign("sourceDocType" ,'合同'); //源单类型
		$this->assign("sourceDocTypeCode" ,'CONTRACT'); //源单类型编码
		$this->assign("sourceDocId" ,$obj ['id']); //源单id
		$this->assign("sourceDocCode" ,$obj ['objCode']); //源单编号
		$this->assign("customerName" ,$obj ['customerName']); //客户名称
		$this->assign("customerId" ,$obj ['customerId']); //客户ID
		$this->assign("headMan" ,$obj ['prinvipalName']); //负责人
		$this->assign("headManId" ,$obj ['prinvipalId']); //负责人ID

		//获取所有可执行id
		$objEquIds = '';
		$equDao = new model_contract_contract_equ();
		if (is_array($obj['equ'])) {
			foreach ($obj['equ'] as $key => $val) {
				$equObj = $equDao->get_d( $val['id'] );
				if ($equObj['number'] - $equObj['encryptionNum'] > 0) {
					$objEquIds .= $val['id'] . ',';
				}
			}
		}

		//从选中的id中过滤出可执行的id
		$equIdsStr = '';
		if ($equIds) {
			$equIdsArr = explode(',' ,$equIds);
			if (is_array($equIdsArr)) {
				foreach ($equIdsArr as $key => $val) {
					$equObj = $equDao->get_d( $val );
					if ($equObj['number'] - $equObj['encryptionNum'] > 0) {
						$equIdsStr .= $val . ',';
					}
				}
			}
		}

		$ids = $equIds ? $equIdsStr : $objEquIds;
		if (!$ids) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有可执行记录!');window.close();"
				 ."</script>";
			exit();
		}

		$this->assign("issueName" ,$_SESSION['USERNAME']); //下达人
		$this->assign("issueId" ,$_SESSION['USER_ID']); //下达人ID
		$this->assign("issueDate" ,date("Y-m-d")); //下达日期

		$this->assign("equIds" ,$ids);
		$this->view ( 'add' ,true);
	}

	/**
	 * 重写add
	 */
	function c_add() {
		$this->checkSubmit(); //验证是否重复提交
		$obj = $_POST[$this->objName];
		$obj['state'] = isset ($_GET['issued']) ? 1 : 0;
		$rs = $this->service->add_d( $obj );
		if($rs) {
			if(isset ($_GET['issued'])) {
				msg( '下达成功！' );
			} else {
				msg( '保存成功！' );
			}
		} else {
			msg( '保存失败！' );
		}
	}

	/**
	 * 跳转到编辑加密锁任务页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('edit' ,true);
	}

	/**
	 * 重写edit
	 */
	function c_edit() {
		$this->checkSubmit(); //验证是否重复提交
		$obj = $_POST[$this->objName];
		$obj['state'] = isset ($_GET['issued']) ? 1 : 0;
		$rs = $this->service->edit_d( $obj );
		if($rs) {
			if(isset ($_GET['issued'])) {
				msg( '下达成功！' );
			} else {
				msg( '保存成功！' );
			}
		} else {
			msg( '保存失败！' );
		}
	}

	/**
	 * 跳转到查看加密锁任务页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * ajax右键下达任务
	 */
	function c_assignMission() {
		$rs = $this->service->assignMission_d($_POST['id']);
		if ($rs) {
			$this->service->mailIssued_d( $_POST['id'] ); //发送邮件通知
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ajax右键接收任务
	 */
	function c_receiveMission() {
		$rs = $this->service->updateById(array('id'=>$_POST['id'] ,'state'=>2 ,'receiveDate'=>date("Y-m-d")));
		if ($rs) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 跳转到完成加密锁任务页面
	 */
	function c_toFinish() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('finish' ,true);
	}

	/**
	 * 完成加密锁任务
	 */
	function c_finish() {
		$this->checkSubmit(); //验证是否重复提交
		$obj = $_POST[$this->objName];

		$rs = $this->service->finish_d( $obj );
		if($rs) {
			msg( '保存成功！' );
		} else {
			msg( '保存失败！' );
		}
	}
}
?>