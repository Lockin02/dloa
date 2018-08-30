<?php


/**
 * 领料申请单控制层类
 */
class controller_stock_picking_pickingapply extends controller_base_action {

	function __construct() {
		$this->objName = "pickingapply";
		$this->objPath = "stock_picking";
		parent :: __construct();
	}


/*******************增删改查操作************************/



	/**
	 * 新增领料申请页面
	 */
	function c_toAdd() {
		//设置数据字典
		$this->showDatadicts(array (
//			'invcostTypeList' => 'DKFS'
		));
		parent :: c_toAdd();
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d($_POST[$this->objName]);
		if ($id) {
			msg('添加成功！');
		}else{
			msg('添加失败！');
		}
	}
	/**
	 * 初始领料申请页面
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
	 * 重新编辑页面
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
	 * 保存重新编辑对象
	 */
	function c_reedit() {
		$preId = $_GET['id'];
		$id = $this->service->reedit_d($_POST[$this->objName],$preId);
		if ($id) {
			msg('编辑成功！');
		}else{
			msg('编辑失败！');
		}
	}



	/**
	 * 页面显示动态费用条目调用方法,返回字符串给页面模板替换 -----编辑
	 */
	function showDetaillist($rows) {
		if ($rows) {
			$i = 1; //列表记录序号
			$str = ""; //返回的模板字符串
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
								<td width="5%"><img src="images/closeDiv.gif" onclick="mydel(this,'invbody');" title="删除行">
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
	 * 隐藏数据
	 */
	function showDetailHidden($rows) {
		if ($rows) {
			$i = 1; //列表记录序号
			$str = ""; //返回的模板字符串
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
	 * 页面显示动态费用条目调用方法,返回字符串给页面模板替换 ---- 查看
	 */
	function showDetaillistview($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
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

/**********************增删改查操作结束**********************/

/*
 *  我的领料申请单列表
 */


	/**
	 * 我的领料申请单列表
	 */
	function c_myApply() {
		$this->show->display($this->objPath . '_' . $this->objName . '-myapply');
	}


	/**
	 * 获取分页数据转成Json ---我的领料申请单列表
	 */
	function c_myApplyJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service -> searchArr['createId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId ( "myapply_list" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}



	/***************************工作流部分*************************/
	function c_auditTab(){
		$this->display('audittab');
	}

	/**
	 * 未审批
	 */
	function c_approvalNo(){
		$this->display('approvalno');
	}

	/**
	 * audit
	 */
	function c_pageJsonAuditNo(){
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
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
	 * 未审批
	 */
	function c_approvalYes(){
		$this->display('approvalyes');
	}

	/**
	 * audit
	 */
	function c_pageJsonAuditYes(){
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$rows = $service->pageBySqlId ( 'shipapply_audited' );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/********************提交部分*****************************/
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
			msg('提交成功');
		}else{
			msg('提交失败');
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
	 * 删除对象-数据库操作阶段
	 */
	function c_del() {
		if ($this->service->deletes_d($_GET['id'])) {
			showmsg('删除成功！', 'self.parent.tb_remove();self.parent.location.reload();', 'button');
		} else {
			showmsg('删除失败！', 'self.parent.tb_remove();self.parent.location.reload();', 'button');
		}
	}

}
?>
