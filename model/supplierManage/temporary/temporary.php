<?php


/**
 *供应商model层类
 */
class model_supplierManage_temporary_temporary extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_lib_temp";
		$this->sql_map = "supplierManage/temporary/temporarySql.php";
		parent :: __construct();
	}

	/**
	 * ***************************************模板类替换********************************************
	 */

	/*
	 * #############################我注册的供应商#############################
	 */
	/**
	 * @desription Tab标签的跳转。注册信息
	 * @param tags
	 * @date 2010-11-12 下午03:58:24
	 */
	function isApprovaled($parentId) {
		$str = "";
		$parentId = isset ($parentId) ? $parentId : null;
		if ($parentId) {
			$rows = $this->getByid_d($parentId);
			foreach ($rows as $key => $val) {
				if ($val['ExaStatus'] == "未提交") {
					$str = "<tr><td colspan='5'>暂未提交审批</td></tr>";
				} else {
					$str .=<<<EOT
					<tr >
						<td>$val[ExaStatus]</td>
						<td></td>
						<td>$val[ExaDT]</td>
						<td>$val[status]</td>
						<td></td>
					</tr>
EOT;
				}
			}
		}
		return $str;
	}

	/**
	 * @desription 供应产品的显示列表
	 * @param tags
	 * @date 2010-11-13 上午11:07:08
	 */
	function showGoods($parentId) {
		$str = "";
		$i = 0;
		$parentId = isset ($parentId) ? $parentId : null;
		$rows = $this->showgoods_d($parentId);

		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$str .=<<<EOT
				<tr>
					<td>$i</td>
					<td>$val[productName]</td>
				</tr>
EOT;
			}
		} else {
			$str = "<tr><td colspan='5'>暂无相关产品</td></tr>";
		}
		return $str;
	}

	/**
	 * ********************************************普通接口方法************************************************
	 */
	/*
	 * 注册供应商的保存方法
	 */
	function addsupp_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo($object);
		}

		$bankDao = new model_supplierManage_temporary_tempbankinfo();

		$newId = $this->create($object);
		foreach($object['Bank'] as $key => $val){
			$val['suppId']=$newId;
			$val['busiCode']=$object['busiCode'];
			$val['suppName']=$object['suppName'];
			$bankDao->addBankInfo_d($val);
		}

		//$object['Bank'][1]['suppId'] = $newId;
		//$bankDao->addBankInfo_d($object['Bank']);
//		$this->Bankadd($object['suppName'],$newId,$object['busiCode'],$object['Bank']);

		//更新附件关联关系
		$this->updateObjWithFile($newId);
		return $newId;
	}

	/**保存注册供应商
	*author can
	*2011-4-7
	*/
	function add_d($object){
//		echo "<pre>";
//		print_r($object);
		try{
			$this->start_d();
			$bankDao = new model_supplierManage_temporary_tempbankinfo();
			$stasseDao=new model_supplierManage_temporary_stasse();
			$stproductDao=new model_supplierManage_temporary_stproduct();
			$linkmanDao=new model_supplierManage_temporary_stcontact();
			$codeDao=new model_common_codeRule();
			$object['busiCode']=$codeDao->supplierCode($this->tbl_name);

			$id=parent::add_d($object,true);
			//添加银行信息
			foreach($object['Bank'] as $key => $val){
				if($val['accountNum']!=""){
				$val['suppId']=$id;
				$val['busiCode']=$object['busiCode'];
				$val['suppName']=$object['suppName'];
				$bankDao->addBankInfo_d($val);

				}
			}
			//添加联系人信息
			if(is_array($object['supplinkman'])){
				foreach($object['supplinkman'] as $linkKey=>$linkVal){
					if($linkVal['name']!=""){
						$linkVal['objCode']=generatorSerial();
						$linkVal['systemCode']=generatorSerial();
						$linkVal['parentCode']=$object['objCode'];
						$linkVal['parentId']=$id;
						$linkmanDao->add_d($linkVal,true);
					}
				}
			}
			//添加供应产品信息
			$object['stproduct']['parentCode']=$object['objCode'];
			$object['stproduct']['parentId']=$id;
			$stproductDao->add_d($object['stproduct']);

			//添加供应商评价
			foreach($object['typeCode'] as $assKey=>$assVal){
				$arr = array(
					"objCode"=>generatorSerial(),
					"systemCode" => generatorSerial(),
					"parentCode" => $object['objCode'],
					"parentId"=> $id,
					"typeCode"=> $assVal,
					"typeName"=> $assKey,
					"opinion"=>$object['stasse']['opinion']
				);
				 $stasseDao->add_d($arr,true);
			}

			$this->commit_d();
			return $id;
		}catch (Exception $e){
			return null;
		}
	}

//	function Bankadd($suppname,$suppID,$busicode,$object){
//		if($object)
//		{
//			$strdate="";
//			$str="insert into oa_supp_bankinfo_temp (suppName,busiCode,depositbank,accountNum,suppId,remark) values";
//			foreach($object as $key=>$val){
//				$strdate=$str." ('$suppname','$busicode','$val[KH]','$val[KHnum]','$suppID','$val[KHbz]')";
//				$this->query($strdate);
//			}
//		}
//
//	}

	/**
	 * @desription 查看供应产品
	 * @param tags
	 * @date 2010-11-16 下午07:36:22
	 */
	function showgoods_d($parentId) {
		$this->searchArr['parentId'] = $parentId;
		return $this->pageBySqlId('select_prod');
	}

	//重写用于判断唯一性的方法
	//20101118弃用
	function isSuppRepeat_d($searchArr, $suppName) {
		$countsql = "select count(id) as num  from " . $this->tbl_name . " c ";
		$countsql = $this->createQuery($countsql, $searchArr);
		if ($suppName != '') {
			$countsql .= " and c.suppName!=" . $suppName;
			$num = $this->queryCount($countsql);
			return ($num == 0 ? false : true);
		}
	}

	function getByid_d($parentId) {
		$parentId = isset ($parentId) ? $parentId : '';
		$sql = "select c.id,c.status,c.ExaStatus,c.ExaDT,c.createName,c.createTime,c.updateName from  oa_supp_lib_temp c where c.id=" . "'" . $parentId . "'";
		$rows = $this->pageBySql($sql);
		return $rows;

	}

	/**
	 * @desription 注册库里信息的修改方法。
	 * @param tags
	 * @date 2010-11-18 下午05:19:48
	 */
	function editinfo_d($objinfo) {
		try {
			$this->start_d();
			$num = parent :: edit_d($objinfo, true);
			$bankinfo=new model_supplierManage_temporary_tempbankinfo();
			foreach($objinfo[Bank] as $key=>$val){
				$val['busiCode']=$objinfo['busiCode'];
				$val['suppName']=$objinfo['suppName'];

				$bankinfo->addBankUpdate_d($val);
				if(empty($val['id'])){
					$val['suppId']=$objinfo['id'];
				    $bankinfo->addBankInfo_d($val);
				}
			}
			//更新附件关联关系
			$this->updateObjWithFile($objinfo['id']);
			$this->commit_d();
			return $num;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/*******************************************工作流部分******************************************/

	/**
	 *	待审批的列表
	 */
	function rpApprovalNo_s($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			foreach ($rows as $key => $val) {
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$i++;
				$str .=<<<EOT
					<tr class="$classCss" pjId="$val[suppId]">
						<td>
							$i
						</td>
						<td>
							$val[suppName]
						</td>
						<td>
							$val[busiCode]
						</td>
						<td>
							$val[products]
						</td>
						<td>
							<textarea class="textarea_read">$val[address]</textarea>
						</td>
						<td>
							$val[ExaStatus]
						</td>
						<td>
							$val[createTime]
						</td>
						<td>
							<a href='controller/supplierManage/temporary/ewf_index.php?actTo=ewfExam&taskId=$val[task]&spid=$val[appId]&billId=$val[id]&skey=$val[skey_]'>审批</a> |
							<a href='javascript:showOpenWin("?model=supplierManage_temporary_temporary&action=init&perm=view&id=$val[id]&skey=$val[skey_]")'>查看</a>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="50">暂无相关记录</td></tr>';
		}
		return $str;
	}

	function rpApprovalYes_s($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			foreach ($rows as $key => $val) {
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$i++;
				$str .=<<<EOT
					<tr class="$classCss" pjId="$val[suppId]">
						<td>
							$i
						</td>
						<td>
							$val[suppName]
						</td>
						<td>
							$val[busiCode]
						</td>
						<td>
							$val[products]
						</td>
						<td>
							<textarea class="textarea_read">$val[address]</textarea>
						</td>
						<td>
							$val[ExaStatus]
						</td>
						<td>
							$val[createTime]
						</td>
						<td>
							<a href='javascript:showOpenWin("?model=supplierManage_temporary_temporary&action=init&perm=view&id=$val[id]&skey=$val[skey_]")'>查看</a> |
							<a href="controller/common/readview.php?itemtype=oa_supp_lib_temp&pid=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650" class="thickbox" title="查看审批情况">审批情况</a>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="50">暂无相关记录</td></tr>';
		}
		return $str;
	}

	/****************************************************************************/
	/**
	 * @desription 我注册的供应商获取数据方法
	 * @param tags
	 * @date 2010-11-16 下午02:22:41
	 */
	function myLogSupp($createId) {
		$this->searchArr['createId'] = $createId;
		$arr = $this->pageBySqlId('select_default');
		return $arr;
	}


	/**
	 * 录入运营库
	 */
	function putInFormal($id) {
		$flibraryDao = new model_supplierManage_formal_flibrary();
		try {
			$this->start_d();

			//以下为kxy编写
			$temporary = $this->get_d ( $id );
			$temporary['registeredFunds']=$temporary['regiCapital'];
			$temporary['registeredDate']=$temporary['foundedDate'];
			$temporary['taxRegistCode']=$temporary['taxCode'];
            $bankinfo=new model_supplierManage_temporary_tempbankinfo();
			$Objectrecord=$bankinfo->tempBankFind(array("suppId"=>$temporary['id']));
			unset ( $temporary ['id'] );
			unset ( $temporary ['busiCode'] );
			$temporary['status'] = "1";		//默认的供应商状态是禁用

			//更新临时供应商业务状态为已进入运营库

			$sql = "update " . $this->tbl_name . " set status=3 where id=" . $id;
			$this->query ( $sql );
			$codeDao=new model_common_codeRule();
			$temporary['busiCode']=$codeDao->supplierCode("oa_supp_lib");
			//插入供应商正式库信息
			$actId=$flibraryDao->add_d ( $temporary ,true);

			$bankDao=new model_supplierManage_formal_bankinfo();
			foreach($Objectrecord as $key=>$val){
				unset($val['id']);
				$val['suppId']=$actId;
				$bankDao->bankAddToFormal_d($val);
			}
			//以上为kxy编写


//			/*start:--获取注册供应商联系人信息--*/
			$tempConDao = new model_supplierManage_temporary_stcontact();
			$tempConInfos = $tempConDao->getByid_d( $id );

//			/*end:获取注册供应商联系人信息*/

//			/*start:--把临时供应商联系人保存为正式库供应商联系人--*/
			$actConDao=new model_supplierManage_formal_sfcontact();
//			if(is_array($tempConInfos)){
				foreach($tempConInfos as $rnum=>$row){
					unset($row['id']);
					$row['parentId']=$actId;
					$actConDao->add_d($row,true);
				}
//			}
//			/*end:--把临时供应商联系人保存为正式库供应商联系人--*/

			/*begin:-- 获取注册供应商产品信息 --*/
			$tempProdDao = new model_supplierManage_temporary_stproduct();
			$tempProdInfo = $tempProdDao->getProdByid_d( $id );
			/*end:-- 获取注册供应商产品信息 --*/

			/*begin:-- 把临时库供应商产品信息保存为正式库供应商产品 --*/
			$actProdDao = new model_supplierManage_formal_sfproduct();
			foreach( $tempProdInfo as $key=>$val ){
				unset($val['id']);
				$val['parentId'] = $actId;
				$actProdDao->addprodFromTempToForm_d($val);
			}
			/*end:-- 把临时库供应商产品信息保存为正式库供应商产品 --*/
			$this->commit_d();
			return $actId;
		} catch (exception $e) {
			$this->rollback();
			return null;
		}




	}
}
?>
