<?php

/**
 * 邮寄申请model层类
 */
class model_mail_mailapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_mail_apply";
		$this->sql_map = "mail/mailapplySql.php";
		parent::__construct ();

		$this->mailStatus = array ("未处理", "已处理" ); //邮寄任务状态
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
								<a href="?model=mail_mailapply&action=init&perm=view&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="修改邮寄信息" class="thickbox">修改</a>
								<a href="?model=mail_mailinfo&action=page&mailApplyId=$val[id]" title="邮寄信息">邮寄信息</a>
							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}

	function showListRecords($rows, $showpage) {
		$str = ""; //返回的模板字符串
		if ($rows) {
			$i = 0; //列表记录序号
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
								<a href="?model=mail_mailapply&action=readInfo&perm=view&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="查看邮寄信息" class="thickbox">查看</a>
							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}

	/***************************************************************************************************
	 * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
	 *************************************************************************************************/
	/**
	 * 根据主键获取对象
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
			$apply['ExaStatus']="待提交";
			$inid = parent::add_d ( $apply, true );
			//邮寄产品明细
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
	 * 编辑邮寄申请对象
	 */
	function edit_d($apply) {
		try {
			$this->start_d ();
			$productsDetailDao = new model_mail_productsdetail ();
			//删除邮寄产品信息
			$productsDetailDao->deleteProductsByApplyId ( $apply ['id'] );
			//邮寄产品明细
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
	 * 获取邮寄申请及邮寄产品
	 */
	function get_d($id) {
		$mailproductDao = new model_mail_productsdetail ();
		$mailproducts = $mailproductDao->getProductsDetail ( $id );
		$mailapply = parent::get_d ( $id );
		$mailapply ['mailproducts'] = $mailproducts;
		return $mailapply;
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

	/*
	 * 根据申请单获取邮寄信息
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