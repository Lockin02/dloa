<?php


/**
 * �������뵥���Ʋ���
 */
class controller_stock_picking_pickingapply extends controller_base_action {

	function __construct() {
		$this->objName = "pickingapply";
		$this->objPath = "stock_picking";
		parent :: __construct();
	}


/*******************��ɾ�Ĳ����************************/



	/**
	 * ������������ҳ��
	 */
	function c_toAdd() {
		//���������ֵ�
		$this->showDatadicts(array (
//			'invcostTypeList' => 'DKFS'
		));
		parent :: c_toAdd();
	}

	/**
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d($_POST[$this->objName]);
		if ($id) {
			msg('��ӳɹ���');
		}else{
			msg('���ʧ�ܣ�');
		}
	}
	/**
	 * ��ʼ��������ҳ��
	 */
	function c_init() {
		$picking = $this->service->get_d($_GET['id']);
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			foreach ($picking as $key => $val) {
				if ($key == 'pickingapplyDetail') {
					$str = $this->showDetaillistview($val);
					$this->show->assign('pickingapplyDetail', $str[0]);
					$this->show->assign('invnumber', $str[1]);
				} else {
					$this->show->assign($key, $val);
				}
			}
			$this->show->display($this->objPath . '_' . $this->objName . '-view');
		} else {
			foreach ($picking as $key => $val) {
				if ($key == 'pickingapplyDetail') {
					$str = $this->showDetaillist($val);
					$this->show->assign('pickingapplyDetail', $str[0]);
					$this->show->assign('invnumber', $str[1]);
				} else {
					$this->show->assign($key, $val);
				}
			}
			//		$this->showDatadicts ( array ('invcostTypeList' => 'DKFS' ) );
			$this->show->display($this->objPath . '_' . $this->objName . '-edit');
		}
	}




	/**
	 * ���±༭ҳ��
	 */
	function c_toReEdit() {
		$picking = $this->service->get_d($_GET['id']);
		foreach ($picking as $key => $val) {
			if ($key == 'pickingapplyDetail') {
				$str = $this->showDetaillist($val);
				$this->show->assign('pickingapplyDetail', $str[0]);
				$this->show->assign('invnumber', $str[1]);
			} else {
				$this->show->assign($key, $val);
			}
		}
		$this->display('reedit');
	}



	/**
	 * �������±༭����
	 */
	function c_reedit() {
		$preId = $_GET['id'];
		$id = $this->service->reedit_d($_POST[$this->objName],$preId);
		if ($id) {
			msg('�༭�ɹ���');
		}else{
			msg('�༭ʧ�ܣ�');
		}
	}



	/**
	 * ҳ����ʾ��̬������Ŀ���÷���,�����ַ�����ҳ��ģ���滻 -----�༭
	 */
	function showDetaillist($rows) {
		if ($rows) {
			$i = 1; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ($rows as $key => $val) {
				$j = $i;
				$str .=<<<EOT
						<tr class="TableData">
							<td>$j</td>
							<td>
								<input type='text' class="txtmiddle" id='productNo$i' value='$val[productNo]' name='pickingapply[pickingapplyDetail][$i][productNo]' readonly/>
							</td>
							<td>
								<input type='hidden' id='productId$i' value='$val[productId]' name='pickingapply[pickingapplyDetail][$i][productId]'/>
								<input type='text' class="txt" value='$val[productName]' id='productName$i' name='pickingapply[pickingapplyDetail][$i][productName]' readonly/>
							</td>
							<td>
								<input type='text' class="txtmiddle" id='productModel$i' value='$val[productModel]' name='pickingapply[pickingapplyDetail][$i][productModel]' readonly/>
							</td>
							<td>
								<input type='hidden' id='dstockId$i' value='$val[stockId]' name='pickingapply[pickingapplyDetail][$i][stockId]'/>
								<input type='text' class="txtmiddle" id='dstockName$i' value='$val[stockName]' name='pickingapply[pickingapplyDetail][$i][stockName]'/>
							</td>
							<td>
								<input type='text' class="txtshort" id='number$i' value='$val[number]' name='pickingapply[pickingapplyDetail][$i][number]'/>
							</td>
								<td width="5%"><img src="images/closeDiv.gif" onclick="mydel(this,'invbody');" title="ɾ����">
							</td>
						</tr>
EOT;
				$i++;
			}

		}
		return array (
			$str,
			$j
		);
	}

	/**
	 * ��������
	 */
	function showDetailHidden($rows) {
		if ($rows) {
			$i = 1; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ($rows as $key => $val) {
				$j = $i;
				$str .=<<<EOT
					<input type='hidden' value='$val[productId]' name='outstock[pickingapplyDetail][$i][productId]'/>
					<input type='hidden' value='$val[productNo]' name='outstock[pickingapplyDetail][$i][productNo]'/>
					<input type='hidden' value='$val[productName]' id='productName$i' name='outstock[pickingapplyDetail][$i][productName]'/>
					<input type='hidden' value='$val[productModel]' name='outstock[pickingapplyDetail][$i][productModel]'/>
					<input type='hidden' value='$val[stockId]' name='outstock[pickingapplyDetail][$i][stockId]'/>
					<input type='hidden' value='$val[stockName]' name='outstock[pickingapplyDetail][$i][stockName]'/>
					<input type='hidden' value='$val[number]' name='outstock[pickingapplyDetail][$i][outstockNum]'>
EOT;
				$i++;
			}

		}
		return array (
			$str,
			$j
		);
	}

	/**
	 * ҳ����ʾ��̬������Ŀ���÷���,�����ַ�����ҳ��ģ���滻 ---- �鿴
	 */
	function showDetaillistview($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ($rows as $key => $val) {
				$j = $i +1;
				$str .=<<<EOT
						<tr class="TableData" align="center">
							<td>$j</td>
							<td>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[productModel]
							</td>
							<td>
								$val[stockName]
							</td>
							<td>
								$val[number]
							</td>
						</tr>
EOT;
				$i++;
			}

		}
		return array (
			$str,
			$j
		);
	}

/**********************��ɾ�Ĳ��������**********************/

/*
 *  �ҵ��������뵥�б�
 */


	/**
	 * �ҵ��������뵥�б�
	 */
	function c_myApply() {
		$this->show->display($this->objPath . '_' . $this->objName . '-myapply');
	}


	/**
	 * ��ȡ��ҳ����ת��Json ---�ҵ��������뵥�б�
	 */
	function c_myApplyJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service -> searchArr['createId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId ( "myapply_list" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}



	/***************************����������*************************/
	function c_auditTab(){
		$this->display('audittab');
	}

	/**
	 * δ����
	 */
	function c_approvalNo(){
		$this->display('approvalno');
	}

	/**
	 * audit
	 */
	function c_pageJsonAuditNo(){
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId ( 'shipapply_auditing' );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * δ����
	 */
	function c_approvalYes(){
		$this->display('approvalyes');
	}

	/**
	 * audit
	 */
	function c_pageJsonAuditYes(){
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId ( 'shipapply_audited' );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/********************�ύ����*****************************/
	function c_toHandUp(){
		$picking = $this->service->get_d($_GET['id']);
		foreach ($picking as $key => $val) {
			if ($key == 'pickingapplyDetail') {
				$str = $this->showDetailHidden($val);
				$this->show->assign('pickingapplyDetail', $str[0]);
				$this->show->assign('invnumber', $str[1]);
			} else {
				$this->show->assign($key, $val);
			}
		}
		$this->show->assign("outstockNo", substr(("outstock" . md5(uniqid(rand()))), 0,15));
		$this->display('handup');
	}

	function c_handUp(){
		$rs = $this->service->handUp_d($_POST[$this->tbl_name]);
		if($rs){
			msg('�ύ�ɹ�');
		}else{
			msg('�ύʧ��');
		}
	}

	function c_auditing(){
		$picking = $this->service->get_d($_GET['id']);
		foreach ($picking as $key => $val) {
			if ($key == 'pickingapplyDetail') {
				$str = $this->showDetaillistview($val);
				$this->show->assign('pickingapplyDetail', $str[0]);
				$this->show->assign('invnumber', $str[1]);
			} else {
				$this->show->assign($key, $val);
			}
		}
		$this->display('auditing');
	}

	/**
	 * ɾ������-���ݿ�����׶�
	 */
	function c_del() {
		if ($this->service->deletes_d($_GET['id'])) {
			showmsg('ɾ���ɹ���', 'self.parent.tb_remove();self.parent.location.reload();', 'button');
		} else {
			showmsg('ɾ��ʧ�ܣ�', 'self.parent.tb_remove();self.parent.location.reload();', 'button');
		}
	}

}
?>
