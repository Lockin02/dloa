<?php

/**
 * �ʼ�����model����
 */
class model_mail_mailapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_mail_apply";
		$this->sql_map = "mail/mailapplySql.php";
		parent::__construct ();

		$this->mailStatus = array ("δ����", "�Ѵ���" ); //�ʼ�����״̬
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
				$mailStatus = $this->mailStatus [$val ['status'] - 1];
				$i ++;
				$str .= <<<EOT
						<tr id="tr_$val[id]">
							<td><input type="checkbox" name="datacb"  value="$val[id]" onClick="checkOne();"></td>
							<td align="center">$i</td>
							<td align="center">
				<a href="?model=stock_shipapply_shipapply&action=init&perm=view&id=$val[applyId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700">
				$val[applyNo]</a></td>
							<td align="center">$val[mailDate]</td>
							<td align="center">$val[customerName]</td>
							<td align="center">$val[linkman]</td>
							<td align="center">$val[tel]</td>
							<td align="center">$val[expectDate]</td>
							<td align="center">$mailType</td>
							<td align="center">$mailStatus</td>
							<td align="center" id="m_$val[id] ">
								<a href="?model=mail_mailapply&action=init&perm=view&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�޸��ʼ���Ϣ" class="thickbox">�޸�</a>
								<a href="?model=mail_mailinfo&action=page&mailApplyId=$val[id]" title="�ʼ���Ϣ">�ʼ���Ϣ</a>
							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}

	function showListRecords($rows, $showpage) {
		$str = ""; //���ص�ģ���ַ���
		if ($rows) {
			$i = 0; //�б��¼���
			$datadictDao = new model_system_datadict_datadict ();

			foreach ( $rows as $key => $val ) {
				$mailType = $datadictDao->getDataNameByCode ( $val ['mailType'] );
				$mailStatus = $this->mailStatus [$val [status] - 1];
				$i ++;
				$str .= <<<EOT
						<tr id="tr_$val[id]">
							<td><input type="checkbox" name="datacb"  value="$val[id]" onClick="checkOne();"></td>
							<td align="center">$i</td>
							<td align="center">
				<a href="?model=stock_shipapply_shipapply&action=init&perm=view&id=$val[applyId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700">
				$val[applyNo]</a></td>
							<td align="center">$val[mailDate]</td>
							<td align="center">$val[customerName]</td>
							<td align="center">$val[linkman]</td>
							<td align="center">$val[tel]</td>
							<td align="center">$val[expectDate]</td>
							<td align="center">$mailType</td>
							<td align="center">$mailStatus</td>
							<td align="center" id="m_$val[id] ">
								<a href="?model=mail_mailapply&action=readInfo&perm=view&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�鿴�ʼ���Ϣ" class="thickbox">�鿴</a>
							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
	 *************************************************************************************************/
	/**
	 * ����������ȡ����
	 */
	//	function get_d($id) {
	//		$this->searchArr  = array ("id" => $id );
	//		$arr = $this->listBySqlId ();
	//		return $arr [0];
	//	}


	function add_d($apply) {
		$apply = $this->addCreateInfo ( $apply );
		try {
			$this->start_d ();
			$apply ['mailDate'] = date ( "Y-m-d H:i:s" );
			$apply['ExaStatus']="���ύ";
			$inid = parent::add_d ( $apply, true );
			//�ʼĲ�Ʒ��ϸ
			if (is_array ( $apply [productsdetail] )) {
				$productsDetailDao = new model_mail_productsdetail ();
				foreach ( $apply [productsdetail] as $key => $shipproduct ) {
					if (! empty ( $shipproduct ['productName'] )) {
						$shipproduct ['mailApplyId'] = $inid;
						$productsDetailDao->add_d ( $shipproduct );
					}
				}
			}
			$this->commit_d ();
			return $inid;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * �༭�ʼ��������
	 */
	function edit_d($apply) {
		try {
			$this->start_d ();
			$productsDetailDao = new model_mail_productsdetail ();
			//ɾ���ʼĲ�Ʒ��Ϣ
			$productsDetailDao->deleteProductsByApplyId ( $apply ['id'] );
			//�ʼĲ�Ʒ��ϸ
			if (is_array ( $apply [productsdetail] )) {
				foreach ( $apply [productsdetail] as $key => $shipproduct ) {
					if (! empty ( $shipproduct ['productName'] )) {
						$shipproduct ['mailApplyId'] = $apply [id];
						$productsDetailDao->add_d ( $shipproduct );
					}
				}

			}
			$apply = parent::edit_d ( $apply, true );

			$this->commit_d ();
			return $apply;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/*
	 * ��ȡ�ʼ����뼰�ʼĲ�Ʒ
	 */
	function get_d($id) {
		$mailproductDao = new model_mail_productsdetail ();
		$mailproducts = $mailproductDao->getProductsDetail ( $id );
		$mailapply = parent::get_d ( $id );
		$mailapply ['mailproducts'] = $mailproducts;
		return $mailapply;
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

	/*
	 * �������뵥��ȡ�ʼ���Ϣ
	 */
	function getMailByApplyId($applyId) {
		$this->searchArr = array ("applyId" => $applyId );
		$rows = $this->list_d ();
		if (count ( $rows ) > 0) {
			return $rows [0];
		} else {
			return "";
		}
	}
}
?>