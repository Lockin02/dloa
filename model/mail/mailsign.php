<?php

/**
 * 邮寄信息model层类
 */
class model_mail_mailsign extends model_base {

	function __construct() {
		$this->tbl_name = "oa_mail_sign";
		$this->sql_map = "mail/mailsignSql.php";
		parent::__construct ();
	}
	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------
	 *************************************************************************************************/

	function showlist($rows, $showpage) {
		$str = ""; //返回的模板字符串
		if ($rows) {
			$i = 0; //列表记录序号
			$datadictDao = new model_system_datadict_datadict ();

			foreach ( $rows as $key => $val ) {
				$mailType = $datadictDao->getDataNameByCode ( $val ['mailType'] );
				$mailStatus = $this->mailStatus [$val ['mailStatus'] - 1];
				$i ++;
				$str .= <<<EOT
						<tr id="tr_$val[id]">
							<td><input type="checkbox" name="datacb"  value="$val[id]" onClick="checkOne();"></td>
							<td align="center">$i</td>
							<td align="center">$val[mailNo]</td>
							<td align="center">$val[signMan]</td>
							<td align="center">$val[signDate]</td>
							<td align="center" id="m_$val[id] ">
								<a href="?model=mail_mailsign&action=init&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="修改邮寄签收" class="thickbox">修改</a>
								<a href="?model=mail_mailinfo&action=init&perm=view&id=$val[mailInfoId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="查看邮寄信息" class="thickbox">邮寄信息</a>
							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}

	/*
	 * 根据邮寄信息获取签收信息
	 */
	function getMailsignByMailInfo($mailInfoId) {
		$this->searchArr = array ("mailInfoId" => $mailInfoId );
		$arr = $this->list_d ();
		if (count ( $arr ) > 0) {
			return $arr [0];
		}

	}


	/**
	 * 添加对象
	 */
	function add_d($object, $isAddInfo = false) {
		try{
			$this->start_d();
			if ($isAddInfo) {
				$object = $this->addCreateInfo ( $object );
			}
			$newId = $this->create ( $object );
			//若添加成功，则将邮寄信息状态改为已签收
			if( $newId ){
				$searchArr = array( 'id' => $object['mailInfoId'] );
				$mailinfoDao = new model_mail_mailinfo();
				$mailinfoDao->update( $searchArr,array('status' => 1 ,'signDate' => $object['signDate']));
			}

			//邮件处理
			if ($object['email']['issend'] == 'y') {
				$this->mail_d($object,$object['email']['TO_ID'],$object['email']['ADDIDS']);
			}
			$this->commit_d();
			return $newId;
		}catch( Exception $e ){
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 邮寄签收发送邮件
	 */
	function mail_d($object, $actor ,$addIds=null) {
		$content = "<font color=blue>".$object['signMan']."</font> 于 ".$object['signDate']." 签收了邮寄单号为：《".$object['mailNo']."》的邮寄单，邮寄内容为：发票号码为《<font color=red>".$object['docCode']."</font>》的发票 。<br/>";
		$content .= "开票申请单位为：" .$object['customerName'] . '<br/>' . "实际开票单位为：". $object['invoiceUnitName'];
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->mailClear("邮寄签收邮件", $actor, $content,$addIds);
	}

	/**根据用户ID查找所属部门
	*author can
	*2011-4-19
	*/
	function getDeptByUserId($userId){
		//查找用户所在的部门ID
		$sql="select DEPT_ID from user where USER_ID='" .$userId. "' ";
		$row=$this->_db->query($sql);
		$deptID=mysql_fetch_row($row);
		//查找部门名称
		$sql2="select DEPT_NAME from department where DEPT_ID='" .$deptID[0]. "' ";
		$res=$this->_db->query($sql2);
		$deptName=mysql_fetch_row($res);
//		print_r($deptName[0]);
		return $deptName[0];
	}

	/**
	 * 获取邮件接收人
	 */
	function getMailman($object){
		$ids = array();
		$names = array();
		if($object['salesmanId']){
			array_push($ids,$object['salesmanId']);
			array_push($names,$object['salesman']);
		}
		if($object['createId']){
			array_push($ids,$object['createId']);
			array_push($names,$object['createName']);
		}
		return array( implode($ids,','),implode($names,','));
	}

	/**
	 * 获取开票和开票申请信息
	 */
	function getInvoiceInfo($docId){
		$invoiceDao = new model_finance_invoice_invoice();
		return $invoiceDao->getInvoiceAndApply_d($docId);
	}

}
?>