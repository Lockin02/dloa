<?php

/**
 * �ʼ���Ϣmodel����
 */
class model_mail_mailsign extends model_base {

	function __construct() {
		$this->tbl_name = "oa_mail_sign";
		$this->sql_map = "mail/mailsignSql.php";
		parent::__construct ();
	}
	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------
	 *************************************************************************************************/

	function showlist($rows, $showpage) {
		$str = ""; //���ص�ģ���ַ���
		if ($rows) {
			$i = 0; //�б��¼���
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
								<a href="?model=mail_mailsign&action=init&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�޸��ʼ�ǩ��" class="thickbox">�޸�</a>
								<a href="?model=mail_mailinfo&action=init&perm=view&id=$val[mailInfoId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�鿴�ʼ���Ϣ" class="thickbox">�ʼ���Ϣ</a>
							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}

	/*
	 * �����ʼ���Ϣ��ȡǩ����Ϣ
	 */
	function getMailsignByMailInfo($mailInfoId) {
		$this->searchArr = array ("mailInfoId" => $mailInfoId );
		$arr = $this->list_d ();
		if (count ( $arr ) > 0) {
			return $arr [0];
		}

	}


	/**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
		try{
			$this->start_d();
			if ($isAddInfo) {
				$object = $this->addCreateInfo ( $object );
			}
			$newId = $this->create ( $object );
			//����ӳɹ������ʼ���Ϣ״̬��Ϊ��ǩ��
			if( $newId ){
				$searchArr = array( 'id' => $object['mailInfoId'] );
				$mailinfoDao = new model_mail_mailinfo();
				$mailinfoDao->update( $searchArr,array('status' => 1 ,'signDate' => $object['signDate']));
			}

			//�ʼ�����
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
	 * �ʼ�ǩ�շ����ʼ�
	 */
	function mail_d($object, $actor ,$addIds=null) {
		$content = "<font color=blue>".$object['signMan']."</font> �� ".$object['signDate']." ǩ�����ʼĵ���Ϊ����".$object['mailNo']."�����ʼĵ����ʼ�����Ϊ����Ʊ����Ϊ��<font color=red>".$object['docCode']."</font>���ķ�Ʊ ��<br/>";
		$content .= "��Ʊ���뵥λΪ��" .$object['customerName'] . '<br/>' . "ʵ�ʿ�Ʊ��λΪ��". $object['invoiceUnitName'];
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->mailClear("�ʼ�ǩ���ʼ�", $actor, $content,$addIds);
	}

	/**�����û�ID������������
	*author can
	*2011-4-19
	*/
	function getDeptByUserId($userId){
		//�����û����ڵĲ���ID
		$sql="select DEPT_ID from user where USER_ID='" .$userId. "' ";
		$row=$this->_db->query($sql);
		$deptID=mysql_fetch_row($row);
		//���Ҳ�������
		$sql2="select DEPT_NAME from department where DEPT_ID='" .$deptID[0]. "' ";
		$res=$this->_db->query($sql2);
		$deptName=mysql_fetch_row($res);
//		print_r($deptName[0]);
		return $deptName[0];
	}

	/**
	 * ��ȡ�ʼ�������
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
	 * ��ȡ��Ʊ�Ϳ�Ʊ������Ϣ
	 */
	function getInvoiceInfo($docId){
		$invoiceDao = new model_finance_invoice_invoice();
		return $invoiceDao->getInvoiceAndApply_d($docId);
	}

}
?>