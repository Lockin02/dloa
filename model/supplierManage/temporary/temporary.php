<?php


/**
 *��Ӧ��model����
 */
class model_supplierManage_temporary_temporary extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_lib_temp";
		$this->sql_map = "supplierManage/temporary/temporarySql.php";
		parent :: __construct();
	}

	/**
	 * ***************************************ģ�����滻********************************************
	 */

	/*
	 * #############################��ע��Ĺ�Ӧ��#############################
	 */
	/**
	 * @desription Tab��ǩ����ת��ע����Ϣ
	 * @param tags
	 * @date 2010-11-12 ����03:58:24
	 */
	function isApprovaled($parentId) {
		$str = "";
		$parentId = isset ($parentId) ? $parentId : null;
		if ($parentId) {
			$rows = $this->getByid_d($parentId);
			foreach ($rows as $key => $val) {
				if ($val['ExaStatus'] == "δ�ύ") {
					$str = "<tr><td colspan='5'>��δ�ύ����</td></tr>";
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
	 * @desription ��Ӧ��Ʒ����ʾ�б�
	 * @param tags
	 * @date 2010-11-13 ����11:07:08
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
			$str = "<tr><td colspan='5'>������ز�Ʒ</td></tr>";
		}
		return $str;
	}

	/**
	 * ********************************************��ͨ�ӿڷ���************************************************
	 */
	/*
	 * ע�ṩӦ�̵ı��淽��
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

		//���¸���������ϵ
		$this->updateObjWithFile($newId);
		return $newId;
	}

	/**����ע�ṩӦ��
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
			//���������Ϣ
			foreach($object['Bank'] as $key => $val){
				if($val['accountNum']!=""){
				$val['suppId']=$id;
				$val['busiCode']=$object['busiCode'];
				$val['suppName']=$object['suppName'];
				$bankDao->addBankInfo_d($val);

				}
			}
			//�����ϵ����Ϣ
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
			//��ӹ�Ӧ��Ʒ��Ϣ
			$object['stproduct']['parentCode']=$object['objCode'];
			$object['stproduct']['parentId']=$id;
			$stproductDao->add_d($object['stproduct']);

			//��ӹ�Ӧ������
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
	 * @desription �鿴��Ӧ��Ʒ
	 * @param tags
	 * @date 2010-11-16 ����07:36:22
	 */
	function showgoods_d($parentId) {
		$this->searchArr['parentId'] = $parentId;
		return $this->pageBySqlId('select_prod');
	}

	//��д�����ж�Ψһ�Եķ���
	//20101118����
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
	 * @desription ע�������Ϣ���޸ķ�����
	 * @param tags
	 * @date 2010-11-18 ����05:19:48
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
			//���¸���������ϵ
			$this->updateObjWithFile($objinfo['id']);
			$this->commit_d();
			return $num;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/*******************************************����������******************************************/

	/**
	 *	���������б�
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
							<a href='controller/supplierManage/temporary/ewf_index.php?actTo=ewfExam&taskId=$val[task]&spid=$val[appId]&billId=$val[id]&skey=$val[skey_]'>����</a> |
							<a href='javascript:showOpenWin("?model=supplierManage_temporary_temporary&action=init&perm=view&id=$val[id]&skey=$val[skey_]")'>�鿴</a>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="50">������ؼ�¼</td></tr>';
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
							<a href='javascript:showOpenWin("?model=supplierManage_temporary_temporary&action=init&perm=view&id=$val[id]&skey=$val[skey_]")'>�鿴</a> |
							<a href="controller/common/readview.php?itemtype=oa_supp_lib_temp&pid=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650" class="thickbox" title="�鿴�������">�������</a>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="50">������ؼ�¼</td></tr>';
		}
		return $str;
	}

	/****************************************************************************/
	/**
	 * @desription ��ע��Ĺ�Ӧ�̻�ȡ���ݷ���
	 * @param tags
	 * @date 2010-11-16 ����02:22:41
	 */
	function myLogSupp($createId) {
		$this->searchArr['createId'] = $createId;
		$arr = $this->pageBySqlId('select_default');
		return $arr;
	}


	/**
	 * ¼����Ӫ��
	 */
	function putInFormal($id) {
		$flibraryDao = new model_supplierManage_formal_flibrary();
		try {
			$this->start_d();

			//����Ϊkxy��д
			$temporary = $this->get_d ( $id );
			$temporary['registeredFunds']=$temporary['regiCapital'];
			$temporary['registeredDate']=$temporary['foundedDate'];
			$temporary['taxRegistCode']=$temporary['taxCode'];
            $bankinfo=new model_supplierManage_temporary_tempbankinfo();
			$Objectrecord=$bankinfo->tempBankFind(array("suppId"=>$temporary['id']));
			unset ( $temporary ['id'] );
			unset ( $temporary ['busiCode'] );
			$temporary['status'] = "1";		//Ĭ�ϵĹ�Ӧ��״̬�ǽ���

			//������ʱ��Ӧ��ҵ��״̬Ϊ�ѽ�����Ӫ��

			$sql = "update " . $this->tbl_name . " set status=3 where id=" . $id;
			$this->query ( $sql );
			$codeDao=new model_common_codeRule();
			$temporary['busiCode']=$codeDao->supplierCode("oa_supp_lib");
			//���빩Ӧ����ʽ����Ϣ
			$actId=$flibraryDao->add_d ( $temporary ,true);

			$bankDao=new model_supplierManage_formal_bankinfo();
			foreach($Objectrecord as $key=>$val){
				unset($val['id']);
				$val['suppId']=$actId;
				$bankDao->bankAddToFormal_d($val);
			}
			//����Ϊkxy��д


//			/*start:--��ȡע�ṩӦ����ϵ����Ϣ--*/
			$tempConDao = new model_supplierManage_temporary_stcontact();
			$tempConInfos = $tempConDao->getByid_d( $id );

//			/*end:��ȡע�ṩӦ����ϵ����Ϣ*/

//			/*start:--����ʱ��Ӧ����ϵ�˱���Ϊ��ʽ�⹩Ӧ����ϵ��--*/
			$actConDao=new model_supplierManage_formal_sfcontact();
//			if(is_array($tempConInfos)){
				foreach($tempConInfos as $rnum=>$row){
					unset($row['id']);
					$row['parentId']=$actId;
					$actConDao->add_d($row,true);
				}
//			}
//			/*end:--����ʱ��Ӧ����ϵ�˱���Ϊ��ʽ�⹩Ӧ����ϵ��--*/

			/*begin:-- ��ȡע�ṩӦ�̲�Ʒ��Ϣ --*/
			$tempProdDao = new model_supplierManage_temporary_stproduct();
			$tempProdInfo = $tempProdDao->getProdByid_d( $id );
			/*end:-- ��ȡע�ṩӦ�̲�Ʒ��Ϣ --*/

			/*begin:-- ����ʱ�⹩Ӧ�̲�Ʒ��Ϣ����Ϊ��ʽ�⹩Ӧ�̲�Ʒ --*/
			$actProdDao = new model_supplierManage_formal_sfproduct();
			foreach( $tempProdInfo as $key=>$val ){
				unset($val['id']);
				$val['parentId'] = $actId;
				$actProdDao->addprodFromTempToForm_d($val);
			}
			/*end:-- ����ʱ�⹩Ӧ�̲�Ʒ��Ϣ����Ϊ��ʽ�⹩Ӧ�̲�Ʒ --*/
			$this->commit_d();
			return $actId;
		} catch (exception $e) {
			$this->rollback();
			return null;
		}




	}
}
?>
