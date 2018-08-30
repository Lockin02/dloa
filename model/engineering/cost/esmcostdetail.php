<?php

/**
 * @author Show
 * @Date 2012��7��29�� 16:32:12
 * @version 1.0
 * @description:������ϸ Model��
 */
class model_engineering_cost_esmcostdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_costdetail";
		$this->sql_map = "engineering/cost/esmcostdetailSql.php";
		parent :: __construct();
	}

	/**
	 * ����ȷ��״̬
	 */
	function rtConfirmStatus_d($thisVal){
		switch($thisVal){
			case '0' : return 'δȷ��';break;
			case '1' : return '���ͨ��';break;
			case '2' : return '���';break;
			case '3' : return '������';break;
			case '4' : return '�ѱ���';break;
			default : return $thisVal;
		}
	}

	/**
	 * �Ż����ڼ�
	 */
	function rtWeekDay_d($thisVal){
		switch($thisVal){
			case '0' : return '��';break;
			case '1' : return 'һ';break;
			case '2' : return '��';break;
			case '3' : return '��';break;
			case '4' : return '��';break;
			case '5' : return '��';break;
			case '6' : return '��';break;
			default : return $thisVal;
		}
	}

    /*********************** �ⲿ��Ϣ��ȡ *************************/
    /**
     * ��ȡ��־��Ϣ
     */
    function getWorklog_d($worklogId){
        $worklogDao = new model_engineering_worklog_esmworklog();
        return $worklogDao->find(array('id' => $worklogId));
    }
    /*********************** ��ɾ�Ĳ� ****************************/

    //��������
    function addBatch_d($object){
//        echo "<pre>";
//        print_r($object);
//        die();

        $worklogArr = $object['worklog'];
        unset($object['worklog']);

        //ʵ������Ʊ��ϸ
        $invoiceDetailDao = new model_engineering_cost_esminvoicedetail();
        //����¼����
        $countMoney = 0;
        try{
            $this->start_d();

            foreach ( $object as $key => $val ) {
            	if(empty($val['costMoney'])){
					continue;
            	}
                //��ȡ��Ʊ��ϸ
                $invoiceDetail = $val['invoiceDetail'];
                unset($val['invoiceDetail']);

            	//�ж��Ƿ����ɾ����־λ
				$isDelTag=isset($val ['isDelTag'])?$val ['isDelTag']:NULL;
				if ($isDelTag != 1) {
	                //�ϲ�ҵ������
	                $val = array_merge($worklogArr,$val);
	                //���������ϸ
	                $id = parent::add_d ( $val,true );

	                $invoiceDetailDao->batchAdd_d($invoiceDetail,$id);
	                //���㱾��¼���ܽ��
	                $countMoney = bcadd($countMoney,$val['costMoney'],2);
				}
            }

			//��־id����
			if($worklogArr['worklogId']){
				$worklogArr['id'] = $worklogArr['worklogId'];
			}
            $this->updateWorklog_d($worklogArr,$countMoney);

			//������Ա����Ŀ����
            if($worklogArr['projectId']){
				//��ȡ��ǰ��Ŀ�ķ���
				$projectCountArr = $this->getCostFormMember_d($worklogArr['projectId']);

				//������Ա������Ϣ
				$esmmemberDao = new model_engineering_member_esmmember();
				$esmmemberDao->update(
					array('projectId' => $worklogArr['projectId'] ,'memberId' => $_SESSION['USER_ID']),
					$projectCountArr
				);
            }

//            $this->rollBack();
            $this->commit_d();
            return $countMoney;
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

    //�����༭
    function editBatch_d($object,$invoiceStatus = null){
//        echo "<pre>";
//        print_r($object);
//        die();

        $worklogArr = $object['worklog'];
        unset($object['worklog']);

        $idsArr = array('addIds' => array(),'editIds' => array(),'delIds' => array());

        //ʵ������Ʊ��ϸ
        $invoiceDetailDao = new model_engineering_cost_esminvoicedetail();
        try{
            $this->start_d();

            $obj = array ();
            foreach ( $object as $key => $val ) {
            	if(empty($val['costMoney'])){
					continue;
            	}
                //��ȡ��Ʊ��ϸ
                $invoiceDetail = $val['invoiceDetail'];
                unset($val['invoiceDetail']);

            	//�ж��Ƿ����ɾ����־λ
				$isDelTag=isset($val ['isDelTag'])?$val ['isDelTag']:NULL;

				if (empty ($val ['id'] ) && $isDelTag== 1) {

				}elseif(empty( $val ['id'])){
	                //�ϲ�ҵ������
	                $val = array_merge($worklogArr,$val);
	                if($invoiceStatus){
						$val['status'] = $invoiceStatus;
	                }
                    //���������ϸ
                    $id = parent::add_d ( $val,true );
	                //���㱾��¼���ܽ��
	                $countMoney = bcadd($countMoney,$val['costMoney'],2);

	                //���÷�������
	                array_push($idsArr['addIds'],$id);
					//����Ʊ��Ϣ
               		$invoiceDetailDao->batchAdd_d($invoiceDetail,$id,$invoiceStatus);
				}elseif($isDelTag == 1){
					//ɾ��
					$this->delete(array('id' => $val['id']));

	                //���÷�������
	                array_push($idsArr['delIds'],$val['id']);
					//����Ʊ��Ϣ
               		$invoiceDetailDao->batchAdd_d($invoiceDetail,$id,$invoiceStatus);
				}else{
                    //�༭������ϸ
                    parent::edit_d ( $val,true );
                    $id = $val['id'];
	                //���㱾��¼���ܽ��
	                $countMoney = bcadd($countMoney,$val['costMoney'],2);
	                //���÷�������
	                array_push($idsArr['editIds'],$id);
					//����Ʊ��Ϣ
               		$invoiceDetailDao->batchAdd_d($invoiceDetail,$id,$invoiceStatus);
				}
            }

			//��־id����
			if($worklogArr['worklogId']){
				$worklogArr['id'] = $worklogArr['worklogId'];
			}
			//��excel���������־���ܷ��ô��뷽ʽ��ͬ
			if($worklogArr['isExcel'] == 1){
				//������־��Ϣ
				$this->updateWorklog_d($worklogArr,$worklogArr['costMoney']);
				unset($worklogArr['isExcel']);
				unset($worklogArr['costMoney']);
			}else{
				//������־��Ϣ
				$this->updateWorklog_d($worklogArr,$countMoney);
			}


			//������Ա����Ŀ����
            if($worklogArr['projectId']){
				//��ȡ��ǰ��Ŀ�ķ���
				$projectCountArr = $this->getCostFormMember_d($worklogArr['projectId']);

				//������Ա������Ϣ
				$esmmemberDao = new model_engineering_member_esmmember();
				$esmmemberDao->update(
					array('projectId' => $worklogArr['projectId'] ,'memberId' => $_SESSION['USER_ID']),
					$projectCountArr
				);
            }

            $this->commit_d();
            return $idsArr;
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

    //�����༭ -- ��Դ�ص�����
    function reeditBatch_d($object){
//        echo "<pre>";
//        print_r($object);
//        die();

        $worklogArr = $object['worklog'];
        unset($object['worklog']);

        //ʵ������Ʊ��ϸ
        $invoiceDetailDao = new model_engineering_cost_esminvoicedetail();
        try{
            $this->start_d();

            $obj = array ();
            foreach ( $object as $key => $val ) {
            	$val['status'] = 0;
                //��ȡ��Ʊ��ϸ
                $invoiceDetail = $val['invoiceDetail'];
                unset($val['invoiceDetail']);
                if($val['id']){
                    //���������ϸ
                    parent::edit_d ( $val,true );
                    $id = $val['id'];
                }else{
                    //���������ϸ
                    $id = parent::add_d ( $val,true );
                }

                $invoiceDetailDao->batchAdd_d($invoiceDetail,$id);
            }

            //����¼����
            $moneyArr = $this->getWorklogMoney_d($worklogArr['id']);

            //������־��Ϣ
            $this->updateWorklog_d($worklogArr,array('costMoney' => $moneyArr['costMoney'],'confirmMoney' => $moneyArr['confirmMoney'],'backMoney' => $moneyArr['backMoney']));

			//������Ա����Ŀ����
            if($worklogArr['projectId']){
				//��ȡ��ǰ��Ŀ�ķ���
				$projectCountArr = $this->getCostFormMember_d($worklogArr['projectId']);

				//������Ա������Ϣ
				$esmmemberDao = new model_engineering_member_esmmember();
				$esmmemberDao->update(
					array('projectId' => $worklogArr['projectId'] ,'memberId' => $_SESSION['USER_ID']),
					$projectCountArr
				);
            }

            $this->commit_d();
//            $this->rollBack();
            return $moneyArr['confirmMoney'];
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

    /**
     * ������־״̬
     */
    function updateStatus_d($worklogIds,$status = '0'){
		try{

			$sql = "update " .$this->tbl_name . " set status = '$status' where worklogId in ($worklogIds)";
			$this->_db->query($sql);

			return true;
		}catch(exception $e){
			throw $e;
			return false;
		}
    }

    /**
     * ����״̬
     */
    function updateCost_d($ids,$status){
		$sql = "update ".$this->tbl_name." set status = '$status' where id in ($ids)";
		$this->query($sql);
    }

    /************************ ҵ���߼����� ****************************/
    //������־�ķ�����Ϣ
    function updateWorklog_d($worklogArr,$costMoney){
    	//ʵ������־
        $worklogDao = new model_engineering_worklog_esmworklog();
        try{
        	$this->start_d();

            //���½��
            $worklogDao->updateCostMoney_d($worklogArr['id'],$costMoney);

            //������־��Ϣ
            $worklogDao->editOrg_d($worklogArr);

            //��������������Ϣ
            if($worklogArr['activityId']){
		        //ʵ��������
		        $activityDao = new model_engineering_activity_esmactivity();
				$activityArr = $worklogDao->getWorklogCountInfo_d($worklogArr['activityId']);

				//�����������Ԥ��
				$activityDao->update(array('id' => $worklogArr['activityId']),array('feeAll' => $activityArr['costMoney']));
            }

            $this->commit_d();
            return true;
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

    //��֤����
    function checkConfig_d(){
        //����������Ϣ
        include (WEB_TOR."model/common/commonConfig.php");
        //����ģ��
        if(!isset($COSTMODEL)){
            return 'δ���ù��̱�������ģ��,����ϵ����Ա��������';
        }
        return 1;
    }

    /**
     * ��ȡ����δ��˷��õ��ܴ�
     */
    function getUnconfirmWeek_d($projectId){
    	$this->searchArr = array('projectId' => $projectId,'statusArr' => '0');
		$rows = $this->list_d();
		if($rows[0]['worklogId']){
			//���õ���־id
			$worklogId = $rows[0]['worklogId'];
			//������־id��ѯ��־��Ϣ
			$esmworklogDao = new model_engineering_worklog_esmworklog();
			$esmworklogObj = $esmworklogDao->get_d($worklogId);
			//������־ִ�����ڼ����ܴ�
			$weekTimes = model_engineering_util_esmtoolutil::getEsmWeekNo( $esmworklogObj['executionDate'] );

			return $weekTimes;
		}else{
			return false;
		}
    }

    /*********************** ҳ����ʾ���� ******************************/


	/**************************************** ������־����ʹ�� **********************************/
	/**
	 * ʵ����ģ��
	 */
	function initTempAdd_d($contentIds){
        //��ѯģ��С����
        $sql = "select
					c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.ParentCostType,c.invoiceType,
					c.invoiceTypeName,c.isReplace,c.isEqu,c.isSubsidy
				from
					cost_type c
				where c.CostTypeID in(".$contentIds.") and c.isNew = '1' order by c.ParentCostTypeID,c.orderNum,c.CostTypeID";
        $costTypeArr = $this->_db->getArray($sql);

        //��ȡ��Ʊ����
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        foreach( $costTypeArr as $k => $v){
            $countI = $v['CostTypeID'];
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';
            $thisI = $countI . "_0";

            $str .=<<<EOT
                <tr class="$trClass" id="tr$v[CostTypeID]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="ɾ������" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[ParentCostType]
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[ParentCostType]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[ParentCostTypeID]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[CostTypeName]
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[CostTypeName]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[CostTypeID]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$v[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$v[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$v[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$v[isEqu]"/>
                        <input type="hidden" id="isSubsidy$countI" value="$v[isSubsidy]"/>
                        <input type="hidden" id="showDays$countI" value="$v[showDays]"/>
                    </td>
	                <td valign="top" class="form_text_right">
EOT;
			//�����Ҫ��ʾ����������ʾ
			if($v['showDays']){
				$str .=<<<EOT
						<span>
							<input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" class="txtshort formatMoney" style="width:60px" onblur="detailSet($countI);countAll();"/>
							X
							����
							<input type="text" name="esmworklog[esmcostdetail][$countI][days]" class="readOnlyTxtMin" id="days$countI" value="1" readonly="readonly"/>
						</span>
EOT;
			}else{
				$str .=<<<EOT
	                    <input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet($countI);countAll();"/>
						<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="1"/>
EOT;
			}

			// �Ƿ���Ҫ��Ʊ
			if($v['isSubsidy'] == 1){
				$billArr = $this->getBillArr_d($billTypeArr,$v['invoiceType']);
				$str .=<<<EOT
		                </td>
	                    <td valign="top" colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
	                            <tr id="tr_$thisI">
	                                <td width="30%">
	                                    <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
	                                    <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" value="$billArr[id]"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" class="readOnlyTxtShort" readonly="readonly"/>
	                                </td>
	                                <td width="20%">
	                                    <img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="alert('�����Ͳ���Ҫ¼�뷢Ʊ�����ܽ�����������');"/>
	                                </td>
	                            </tr>
	                        </table>
	                    </td>
	                    <td valign="top">
	                    	<textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" class="txtlong"></textarea>
	                    </td>
	                </tr>
EOT;
			}else{
       			$billTypeStr = $this->initBillType_d($billTypeArr,null,$v['invoiceType'],$v['isReplace']);//ģ��ʵ�����ַ���
				$str .=<<<EOT
		                </td>
	                    <td valign="top" colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
	                            <tr id="tr_$thisI">
	                                <td width="30%">
	                                    <select id="select_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" style="width:90px"><option value="">��ѡ��Ʊ</option>$billTypeStr</select>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="invMoneySet('$thisI');countInvoiceMoney()" class="txtshort formatMoney"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort"/>
	                                </td>
	                                <td width="20%">
	                                    <img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice($countI)"/>
	                                </td>
	                            </tr>
	                        </table>
	                    </td>
	                    <td valign="top">
	                    	<textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" class="txtlong"></textarea>
	                    </td>
	                </tr>
EOT;
			}
        }
		return $str;
	}

    //�༭��ʼ��ģ��
    //TODO initTemplateEdit
    function initTempEdit_d($worklogId){
        //��ȡ��Ʊ����
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);
        $initBillStr = $this->initBillType_d($billTypeArr);

        //��ѯģ��С����
        $sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isReplace,isEqu,isSubsidy from cost_type where isNew = '1'";
        $sysCostTypeArr = $this->_db->getArray($sql);

        //ʵ������Ʊ����
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

    	//��������±༭
		$costTypeArr = $this->findAll(array('worklogId' => $worklogId));

        //ģ��ʵ�����ַ���
        $str = null;
        //�����ܽ��
        $countMoney = 0;
        foreach( $costTypeArr as $k => $v){
            //���÷�������Id
            $countI = $v['costTypeId'];
            //��ѯ����־�ڵĸ�����ý��
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';
            $countMoney = bcadd($countMoney,$v['costMoney']);

            //��ȡƥ���������
            $thisCostType = $this->initExpenseEdit_d($sysCostTypeArr,$v['costTypeId']);

            $str .=<<<EOT
                <tr class="$trClass" id="tr$v[costTypeId]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="ɾ������" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[parentCostType]
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[parentCostType]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[costType]
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][id]" value="$v[id]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][status]" value="0"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
                    </td>
                    <td valign="top" class="form_text_right">
EOT;
			//�����Ҫ��ʾ����������ʾ
			if($thisCostType['showDays']){
				$str .=<<<EOT
						<span>
							<input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" class="txtshort formatMoney" value="$v[costMoney]" style="width:60px" onblur="detailSet($countI);countAll();"/>
							X
							����
							<input type="text" name="esmworklog[esmcostdetail][$countI][days]" class="readOnlyTxtMin" id="days$countI" value="$v[days]" readonly="readonly"/>
						</span>
EOT;
			}else{
				$str .=<<<EOT
	                    <input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" style="width:146px" class="txtmiddle formatMoney" value="$v[costMoney]" onblur="detailSet($countI);countAll();"/>
						<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="$v[days]"/>
EOT;
			}

            $str .=<<<EOT
					</td>
                    <td colspan="4" class="innerTd">
                        <table class="form_in_table" id="table_$countI">
EOT;
            //��ȡ��Ʊ��Ϣ
            $esminvoiceDetailArr = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
            foreach($esminvoiceDetailArr as $thisK => $thisV){
				// �Ƿ���Ҫ��Ʊ
				if($thisCostType['isSubsidy'] == 1){
					$billArr = $this->getBillArr_d($billTypeArr,$thisV['invoiceTypeId']);
					$str .=<<<EOT
	                    <tr id="tr_$thisI">
		                    <td width="30%">
                                <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
                                <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" value="$billArr[id]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
	                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" readonly="readonly"/>
	                        </td>
	                        <td width="20%">
                                <img style="cursor:pointer;" src="images/add_item.png" title="�����Ͳ���Ҫ¼�뷢Ʊ�����ܽ�����������" onclick="alert('�����Ͳ���Ҫ¼�뷢Ʊ�����ܽ�����������');"/>
	                        </td>
	                    </tr>
EOT;
				}else{
	                $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId'],$thisCostType['invoiceType'],$thisCostType['isReplace']);
	                $thisI = $countI . "_" .$thisK;
	                //ͼƬ��ʾ�ж�
	                $imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
	                //�����ж�
	                $funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
	                $invTitle = $thisK == 0 ? "�����" : "ɾ�����з�Ʊ";
	                //��Ʊ����
	                $str .=<<<EOT
	                    <tr id="tr_$thisI">
	                        <td width="30%">
	                            <select id="select_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceTypeId]" style="width:90px"><option value="">��ѡ��Ʊ</option>$billTypeStr</select>
	                        </td>
	                        <td width="25%">
	                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
	                            <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" onblur="invMoneySet('$thisI');countInvoiceMoney()" class="txtshort formatMoney"/>
	                        </td>
	                        <td width="25%">
	                            <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" onblur="countInvoiceNumber(this)" value="$thisV[invoiceNumber]" class="txtshort"/>
	                        </td>
	                        <td width="20%">
	                            <img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
	                        </td>
	                    </tr>
EOT;
				}
            }

			//���ñ�ע���߶�
            $remarkHeight = (count($esminvoiceDetailArr) - 1)*33 + 20 ."px";

            $str .=<<<EOT
                        </table>
                    </td>
	                <td valign="top">
                    	<textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" style="height:$remarkHeight" class="txtlong">$v[remark]</textarea>
                    </td>
                </tr>
EOT;
        }

        return $str;
    }

    //�༭��ʼ��ģ��
    //TODO initTemplateView_d
    function initTempView_d($worklogId){
        //��������
        $rtArr = array();

        //��ȡ��Ʊ����
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        //ʵ������Ʊ����
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

        $costTypeArr = $this->findAll(array('worklogId' => $worklogId));

		//��־λ
		$markArr = array();

		//��ͬ�м���
		$countArr = array();
		//��ͬ���ü���
		foreach($costTypeArr as $key => $val){
            //��ȡ��Ʊ��Ϣ
            $costTypeArr[$key]['invDetail'] = $esminvoiceDetailDao->findAll(array('costDetailId' => $val['id']));

			//��Ʊ��Ϣ����
            $costTypeArr[$key]['invLength'] = count($costTypeArr[$key]['invDetail']);

			if(isset($countArr[$val['parentCostTypeId']])){
				$countArr[$val['parentCostTypeId']] += $costTypeArr[$key]['invLength'];
			}else{
				$countArr[$val['parentCostTypeId']] = $costTypeArr[$key]['invLength'];
			}
		}

//        echo "<pre>";
//        print_r($costTypeArr);

        //ģ��ʵ�����ַ���
        $str = null;
        //�����ܽ��
        $countMoney = 0;
        $invoiceMoney = 0;
        $invoiceNumber = 0;
        foreach( $costTypeArr as $k => $v){
            if($v['costMoney'] == 0){
                continue;
            }
//	    	echo "<pre>";
//			print_r($v);
            //���г���
    		$mailSize = $countArr[$v['parentCostTypeId']];

            //��ѯ����־�ڵĸ�����ý��
            $detailMoney = bcmul($v['costMoney'],$v['days'],2);
            $countMoney = bcadd($countMoney,$detailMoney,2);

            //���÷�������Id
            $countI = $v['costTypeId'];
            //��ѯ����־�ڵĸ�����ý��
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';

            //�����������ʾ
            if($v['days'] > 1){
            	$costMoneyHtm = "<span class='formatMoney green' title='����:".$v['costMoney']." X ����:".$v['days']."'>$detailMoney</span>";
            }else{
            	$costMoneyHtm = "<span class='formatMoney'>$detailMoney</span>";
            }

            //��Ʊ��������
    		$invSize = $v['invLength'];

    		//ȷ�Ͻ����ʾ
    		$confirmStatus = $this->rtConfirmStatus_d($v['status']);

            foreach($v['invDetail'] as $thisK => $thisV){
            	//��Ʊ�ϼ�
	            $invoiceMoney = bcadd($invoiceMoney,$thisV['invoiceMoney'],2);
	            $invoiceNumber = bcadd($invoiceNumber,$thisV['invoiceNumber']);
            	if($thisK == 0){
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);

            		if(!in_array($v['parentCostTypeId'],$markArr)){
           				$trClass = count($markArr)%2 == 0? 'tr_odd' : 'tr_even';

			            $str .=<<<EOT
			            	<tr class="$trClass tr$v[id]">
			                    <td valign="top" class="form_text_right" rowspan="$mailSize">
			                        $v[parentCostType]
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
			                    <td valign="top" rowspan="$invSize">$confirmStatus</td>
				            </tr>
EOT;
					array_push($markArr,$v['parentCostTypeId']);
            		}else{
			            $str .=<<<EOT
			            	<tr class="$trClass tr$v[id]">
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
			                    <td valign="top" rowspan="$invSize">$confirmStatus</td>
				            </tr>
EOT;
            		}
            	}else{
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
		            $str .=<<<EOT
		            	<tr class="$trClass tr$v[id]">
			                <td>
								$billType
			                </td>
			                <td>
								<span class="formatMoney">$thisV[invoiceMoney]</span>
			                </td>
			                <td>$thisV[invoiceNumber]</td>
			            </tr>
EOT;
            	}
            }
        }
        return $str;
    }

    /**
     * ȷ�Ϸ���ҳ��
     */
    function initTempConfirm_d($worklogId){
        //��������
        $rtArr = array();

        //��ȡ��Ʊ����
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        //ʵ������Ʊ����
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

        $costTypeArr = $this->findAll(array('worklogId' => $worklogId));

		//��־λ
		$markArr = array();

		//��ͬ�м���
		$countArr = array();
		//��ͬ���ü���
		foreach($costTypeArr as $key => $val){
            //��ȡ��Ʊ��Ϣ
            $costTypeArr[$key]['invDetail'] = $esminvoiceDetailDao->findAll(array('costDetailId' => $val['id']));

			//��Ʊ��Ϣ����
            $costTypeArr[$key]['invLength'] = count($costTypeArr[$key]['invDetail']);

			if(isset($countArr[$val['parentCostTypeId']])){
				$countArr[$val['parentCostTypeId']] += $costTypeArr[$key]['invLength'];
			}else{
				$countArr[$val['parentCostTypeId']] = $costTypeArr[$key]['invLength'];
			}
		}

//        echo "<pre>";
//        print_r($costTypeArr);

        //ģ��ʵ�����ַ���
        $str = null;
        //�����ܽ��
        $countMoney = 0;
        $invoiceMoney = 0;
        $invoiceNumber = 0;
        foreach( $costTypeArr as $k => $v){
            if($v['costMoney'] == 0){
                continue;
            }
//	    	echo "<pre>";
//			print_r($v);
            //���г���
    		$mailSize = $countArr[$v['parentCostTypeId']];

            //��ѯ����־�ڵĸ�����ý��
            $detailMoney = bcmul($v['costMoney'],$v['days'],2);
            $countMoney = bcadd($countMoney,$detailMoney,2);

            //���÷�������Id
            $countI = $v['costTypeId'];
            //��ѯ����־�ڵĸ�����ý��
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';

            //�����������ʾ
            if($v['days'] > 1){
            	$costMoneyHtm = "<span class='formatMoney green' title='����:".$v['costMoney']." X ����:".$v['days']."'>$detailMoney</span>";
            }else{
            	$costMoneyHtm = "<span class='formatMoney'>$detailMoney</span>";
            }

            //��Ʊ��������
    		$invSize = $v['invLength'];
			//�������״̬
    		$thisRs = $this->rtConfirmStatus_d($v['status']);

            foreach($v['invDetail'] as $thisK => $thisV){
            	//��Ʊ�ϼ�
	            $invoiceMoney = bcadd($invoiceMoney,$thisV['invoiceMoney'],2);
	            $invoiceNumber = bcadd($invoiceNumber,$thisV['invoiceNumber']);
            	if($thisK == 0){
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
            		if(!in_array($v['parentCostTypeId'],$markArr)){
           				$trClass = count($markArr)%2 == 0? 'tr_odd' : 'tr_even';

			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$mailSize">
			                        $v[parentCostType]
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
EOT;
						if($v['status'] == "0"){
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
	                                	<input type="hidden" name="esmcostdetail[detail][$k][id]" value="$v[id]"/>
										<select name="esmcostdetail[detail][$k][status]" class="txtshort"><option value="1">���ͨ��</option><option value="2">���</option></select>
				                    </td>
					            </tr>
EOT;
						}else{
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
										$thisRs
				                    </td>
					            </tr>
EOT;
						}
					array_push($markArr,$v['parentCostTypeId']);
            		}else{
			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
EOT;
						if($v['status'] == "0"){
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
	                                	<input type="hidden" name="esmcostdetail[detail][$k][id]" value="$v[id]"/>
										<select name="esmcostdetail[detail][$k][status]" class="txtshort"><option value="1">���ͨ��</option><option value="2">���</option></select>
				                    </td>
					            </tr>
EOT;
						}else{
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
										$thisRs
				                    </td>
					            </tr>
EOT;
						}
            		}
            	}else{
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
		            $str .=<<<EOT
		            	<tr class="$trClass">
			                <td>
								$billType
			                </td>
			                <td>
								<span class="formatMoney">$thisV[invoiceMoney]</span>
			                </td>
			                <td>$thisV[invoiceNumber]</td>
			            </tr>
EOT;
            	}
            }
        }
        return $str;
    }

    //�༭��ʼ��ģ��
    function initTempReedit_d($worklogId){
        //��ȡ��Ʊ����
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);
        $initBillStr = $this->initBillType_d($billTypeArr);

        //��ѯģ��С����
        $sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isReplace,isEqu,isSubsidy from cost_type where isNew = '1'";
        $sysCostTypeArr = $this->_db->getArray($sql);

        //ʵ������Ʊ����
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

    	//��������±༭
		$costTypeArr = $this->findAll(array('worklogId' => $worklogId),'id asc');

        //ģ��ʵ�����ַ���
        $str = null;
        //�����ܽ��
        $countMoney = 0;
        foreach( $costTypeArr as $k => $v){
            //���÷�������Id
            $countI = $v['costTypeId'];
            //��ѯ����־�ڵĸ�����ý��
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';
            $countMoney = bcadd($countMoney,$v['costMoney']);

            //��ȡƥ���������
            $thisCostType = $this->initExpenseEdit_d($sysCostTypeArr,$v['costTypeId']);

			//���޸Ĳ���
            if($v['status'] == 0 || $v['status'] == 2){
	            $str .=<<<EOT
	                <tr class="$trClass" id="tr$v[costTypeId]">
	                    <td valign="top">
	                        <img style="cursor:pointer;" src="images/removeline.png" title="ɾ������" onclick="deleteCostType($countI)"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[parentCostType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[parentCostType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[costType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][id]" value="$v[id]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][status]" value="0"/>
	                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
	                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
	                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
	                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
	                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
	                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
EOT;
				//�����Ҫ��ʾ����������ʾ
				if($thisCostType['showDays']){
					$str .=<<<EOT
							<span>
								<input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" class="txtshort formatMoney" value="$v[costMoney]" style="width:60px" onblur="detailSet($countI);countAll();"/>
								X
								����
								<input type="text" name="esmworklog[esmcostdetail][$countI][days]" class="readOnlyTxtMin" id="days$countI" value="$v[days]" readonly="readonly"/>
							</span>
EOT;
				}else{
					$str .=<<<EOT
		                    <input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" style="width:146px" class="txtmiddle formatMoney" value="$v[costMoney]" onblur="detailSet($countI);countAll();"/>
							<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="$v[days]"/>
EOT;
				}

	            $str .=<<<EOT
						</td>
	                    <td colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
EOT;
	            //��ȡ��Ʊ��Ϣ
	            $esminvoiceDetailArr = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
	            foreach($esminvoiceDetailArr as $thisK => $thisV){
					// �Ƿ���Ҫ��Ʊ
					if($thisCostType['isSubsidy'] == 1){
						$billArr = $this->getBillArr_d($billTypeArr,$thisV['invoiceTypeId']);
						$str .=<<<EOT
		                    <tr id="tr_$thisI">
			                    <td width="30%">
	                                <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
	                                <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" value="$billArr[id]"/>
		                        </td>
		                        <td width="25%">
	                                <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
		                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
		                        </td>
		                        <td width="25%">
	                                <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" readonly="readonly"/>
		                        </td>
		                        <td width="20%">
	                                <img style="cursor:pointer;" src="images/add_item.png" title="�����Ͳ���Ҫ¼�뷢Ʊ�����ܽ�����������" onclick="alert('�����Ͳ���Ҫ¼�뷢Ʊ�����ܽ�����������');"/>
		                        </td>
		                    </tr>
EOT;
					}else{
		                $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId'],$thisCostType['invoiceType'],$thisCostType['isReplace']);
		                $thisI = $countI . "_" .$thisK;
		                //ͼƬ��ʾ�ж�
		                $imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
		                //�����ж�
		                $funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
		                $invTitle = $thisK == 0 ? "�����" : "ɾ�����з�Ʊ";
		                //��Ʊ����
		                $str .=<<<EOT
		                    <tr id="tr_$thisI">
		                        <td width="30%">
		                            <select id="select_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceTypeId]" style="width:90px"><option value="">��ѡ��Ʊ</option>$billTypeStr</select>
		                        </td>
		                        <td width="25%">
		                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
		                            <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" onblur="invMoneySet('$thisI');countInvoiceMoney()" class="txtshort formatMoney"/>
		                        </td>
		                        <td width="25%">
		                            <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" onblur="countInvoiceNumber(this)" value="$thisV[invoiceNumber]" class="txtshort"/>
		                        </td>
		                        <td width="20%">
		                            <img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
		                        </td>
		                    </tr>
EOT;
					}
	            }

				//���ñ�ע���߶�
	            $remarkHeight = (count($esminvoiceDetailArr) - 1)*33 + 20 ."px";

	            $str .=<<<EOT
	                        </table>
	                    </td>
		                <td valign="top">
	                    	<textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" style="height:$remarkHeight" class="txtlong">$v[remark]</textarea>
	                    </td>
	                </tr>
EOT;
            }else{
	            $str .=<<<EOT
	                <tr class="$trClass" id="tr$v[costTypeId]" title="ͨ����˵ķ���">
	                    <td valign="top"></td>
	                    <td valign="top" class="form_text_right">
	                        $v[parentCostType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[parentCostType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[costType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][id]" value="$v[id]"/>
		                    <input type="hidden" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]"/>
							<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="$v[days]"/>

	                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
	                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
	                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
	                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
	                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
	                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>

	                        <input type="hidden" id="notSelect$countI" value="1"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
							$v[costMoney]
						</td>
	                    <td colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
EOT;
	            //��ȡ��Ʊ��Ϣ
	            $esminvoiceDetailArr = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
	            foreach($esminvoiceDetailArr as $thisK => $thisV){
	                $thisI = $countI . "_" .$thisK;
	                $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId'],$thisCostType['invoiceType'],$thisCostType['isReplace']);
					$billArr = $this->getBillArr_d($billTypeArr,$thisV['invoiceTypeId']);
					$str .=<<<EOT
	                    <tr id="tr_$thisI">
		                    <td align="left" width="29.6%">
                                $billArr[name]
	                            <select id="select_$thisI" style="width:90px;display:none;">$billTypeStr</select>
                                <input type="hidden" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]"/>
	                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
                                <input type="hidden" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceNumber]"/>
	                        </td>
	                        <td align="left" width="27%" class="formatMoney">$thisV[invoiceMoney]</td>
	                        <td align="left" width="27%">$thisV[invoiceNumber]</td>
	                        <td></td>
	                    </tr>
EOT;
	            }
	            $str .=<<<EOT
	                        </table>
	                    </td>
		                <td valign="top" align="left">$v[remark]
	                    </td>
	                </tr>
EOT;
            }
        }

        return $str;
    }

    //���ض�Ӧ�ķ�Ʊ����
    function getBillArr_d($object,$defaultVal = null){
		if($defaultVal){
			$rtArr = array();
	        foreach($object as $key => $val){
				if($val['id'] == $defaultVal){
					$rtArr = $val;
					break;
				}
	        }
	        return $rtArr;
		}else{
			return array(
				'name' => '',
				'id' => ''
			);
		}
    }

    //��ʼ��ģ����Ϣ
    //TODO - initTemplateAdd����
    function initTemplateAdd_d($worklogObj,$modelType){
        //����������Ϣ
        include (WEB_TOR."model/common/commonConfig.php");
        //���Կ��Ѷ�Ӧ�ķ�������
        $CARDCOSTTYPE = isset($CARDCOSTTYPE) ? $CARDCOSTTYPE['id'] : null;
        //��Ա���޶�Ӧ�ķ�������
        $TEMPPERSONCOSTTYPE = isset($TEMPPERSONCOSTTYPE) ? $TEMPPERSONCOSTTYPE['id'] : null;
        //�⳵�Ѷ�Ӧ�ķ�������
        $CARTRAVELFEECOSTTYPE = isset($CARTRAVELFEECOSTTYPE) ? $CARTRAVELFEECOSTTYPE['id'] : null;
        //�ͷѶ�Ӧ�ķ�������
        $CARFUELFEECOSTTYPE = isset($CARFUELFEECOSTTYPE) ? $CARFUELFEECOSTTYPE['id'] : null;
        //·�Ŷ�Ӧ�ķ�������
        $CARROADFEECOSTTYPE = isset($CARROADFEECOSTTYPE) ? $CARROADFEECOSTTYPE['id'] : null;
        //ͣ���ѵķ�������
        $CARPARKINGFEECOSTTYPE = isset($CARPARKINGFEECOSTTYPE) ? $CARPARKINGFEECOSTTYPE['id'] : null;
        //��������
        $rtArr = array();
        //ģ��Id
        $modelId = null;
        //����ģ������
        $COSTMODEL = isset($COSTMODEL) ? $COSTMODEL : null;
        if(empty($COSTMODEL)){
            return null;
        }
        $rtArr['templateName'] = $COSTMODEL['name'];
        $rtArr['templateId'] = $COSTMODEL['id'];
        $modelId = $COSTMODEL['id'];

        //��ȡģ����Ϣ
        $sql = "select id,templateName,contentId from cost_customtemplate where id = $modelId";
        $rs = $this->_db->getArray($sql);
        $modelArr = $rs[0];

        //��ѯģ��С����
        $sql = "select
					c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.ParentCostType,c.invoiceType,
					c.invoiceTypeName,c.isReplace,c.isEqu
				from
					cost_type c
				where c.CostTypeID in(".$modelArr['contentId'].") and c.isNew = '1' order by c.ParentCostTypeID ";
        $costTypeArr = $this->_db->getArray($sql);

        //��ȡ��Ʊ����
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        //ģ��ʵ�����ַ���
        $str = null;
        foreach( $costTypeArr as $k => $v){
            $countI = $v['CostTypeID'];
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';

			//����������Ⱦ
       		$billTypeStr = $this->initBillType_d($billTypeArr,null,$v['invoiceType'],$v['isReplace']);

            $str .=<<<EOT
                <tr class="$trClass">
                    <td valign="top" class="form_text_right">
                        $v[ParentCostType]
                        <input type="hidden" name="esmcostdetail[$countI][parentCostType]" value="$v[ParentCostType]"/>
                        <input type="hidden" name="esmcostdetail[$countI][parentCostTypeId]" value="$v[ParentCostTypeID]"/>
						<input type="hidden" name="esmcostdetail[$countI][days]" id="days$countI" value="1"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[CostTypeName]
                        <input type="hidden" name="esmcostdetail[$countI][costType]" id="costType$countI" value="$v[CostTypeName]"/>
                        <input type="hidden" name="esmcostdetail[$countI][costTypeId]" id="costTypeId$countI" value="$v[CostTypeID]"/>
                        <input type="hidden" id="projectCode" name="esmcostdetail[$countI][projectCode]" value="$worklogObj[projectCode]"/>
                        <input type="hidden" id="projectId" name="esmcostdetail[$countI][projectId]" value="$worklogObj[projectId]"/>
                        <input type="hidden" id="projectName" name="esmcostdetail[$countI][projectName]" value="$worklogObj[projectName]"/>
                        <input type="hidden" id="activityName" name="esmcostdetail[$countI][activityName]" value="$worklogObj[activityName]"/>
                        <input type="hidden" id="activityId" name="esmcostdetail[$countI][activityId]" value="$worklogObj[activityId]"/>
                        <input type="hidden" id="worklogId" name="esmcostdetail[$countI][worklogId]" value="$worklogObj[id]"/>
                        <input type="hidden" name="esmcostdetail[$countI][executionDate]" value="$worklogObj[executionDate]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$v[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$v[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$v[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$v[isEqu]"/>
                        <input type="hidden" id="showDays$countI" value="$v[showDays]"/>
                    </td>
                    <td valign="top" class="form_text_right">
EOT;

            switch($v['CostTypeID']){
                case $CARDCOSTTYPE ://���Կ�
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCardrecords($countI)" readonly="readonly"/>
EOT;
                    break;
                case $TEMPPERSONCOSTTYPE ://��Ƹ��Ա
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initTempPerson($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARTRAVELFEECOSTTYPE ://�⳵��
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARFUELFEECOSTTYPE ://�ͷ�
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARROADFEECOSTTYPE ://·�ŷ�
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARPARKINGFEECOSTTYPE ://ͣ����
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                default :
                    $str .=<<<EOT
                        <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$rs[costMoney]" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet($countI);countAll();"/>
EOT;
                    break;
            }

            $thisI = $countI . "_0";
            //��Ʊ����
            $str .=<<<EOT
                    </td>
                    <td valign="top" colspan="4" class="innerTd">
                        <table class="form_in_table" id="table_$countI">
                            <tr id="tr_$thisI">
                                <td width="30%">
                                    <select id="select_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceTypeId]" style="width:90px"><option value="">��ѡ��Ʊ</option>$billTypeStr</select>
                                </td>
                                <td width="25%">
                                    <input type="text" id="invoiceMoney_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceMoney()" class="txtshort formatMoney"/>
                                </td>
                                <td width="25%">
                                    <input type="text" id="invoiceNumber_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort"/>
                                </td>
                                <td width="20%">
                                    <img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice($countI)"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top">
                    	<textarea name="esmcostdetail[$countI][remark]" id="remark$countI" class="txtlong"></textarea>
                    </td>
                </tr>
EOT;
        }
        $rtArr['str'] = $str;
        return $rtArr;
    }


    //�༭��ʼ��ģ��
    //TODO initTemplateEdit
    function initTemplateEdit_d($worklogObj,$isReedit = false){
        //����������Ϣ
        include (WEB_TOR."model/common/commonConfig.php");
        //���Կ��Ѷ�Ӧ�ķ�������
        $CARDCOSTTYPE = isset($CARDCOSTTYPE) ? $CARDCOSTTYPE['id'] : null;
        //��Ա���޶�Ӧ�ķ�������
        $TEMPPERSONCOSTTYPE = isset($TEMPPERSONCOSTTYPE) ? $TEMPPERSONCOSTTYPE['id'] : null;
        //�⳵�Ѷ�Ӧ�ķ�������
        $CARTRAVELFEECOSTTYPE = isset($CARTRAVELFEECOSTTYPE) ? $CARTRAVELFEECOSTTYPE['id'] : null;
        //�ͷѶ�Ӧ�ķ�������
        $CARFUELFEECOSTTYPE = isset($CARFUELFEECOSTTYPE) ? $CARFUELFEECOSTTYPE['id'] : null;
        //·�Ŷ�Ӧ�ķ�������
        $CARROADFEECOSTTYPE = isset($CARROADFEECOSTTYPE) ? $CARROADFEECOSTTYPE['id'] : null;
        //ͣ���ѵķ�������
        $CARPARKINGFEECOSTTYPE = isset($CARPARKINGFEECOSTTYPE) ? $CARPARKINGFEECOSTTYPE['id'] : null;
        //��������
        $rtArr = array();
        //����ģ������
        $COSTMODEL = isset($COSTMODEL) ? $COSTMODEL : null;
        if(empty($COSTMODEL)){
            return null;
        }

        //��ȡ��Ʊ����
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);
        $initBillStr = $this->initBillType_d($billTypeArr);

        //��ѯģ��С����
        $sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName from cost_type where isNew = '1'";
        $sysCostTypeArr = $this->_db->getArray($sql);

        //ʵ������Ʊ����
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

		//��������±༭
		if($isReedit == true){
			$this->searchArr = array(
				'worklogId' => $worklogObj['id'],
				'statusArr' => '2'
			);
        	$costTypeArr = $this->list_d();
		}else{
        	$costTypeArr = $this->findAll(array('worklogId' => $worklogObj['id']));
		}
//        echo "<pre>";
//        print_r($costTypeArr);

        //ģ��ʵ�����ַ���
        $str = null;
        //�����ܽ��
        $countMoney = 0;
        foreach( $costTypeArr as $k => $v){
            //���÷�������Id
            $countI = $v['costTypeId'];
            //��ѯ����־�ڵĸ�����ý��
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';
            $countMoney = bcadd($countMoney,$v['costMoney']);

            //��ȡƥ���������
            $thisCostType = $this->initExpenseEdit_d($sysCostTypeArr,$v['costTypeId']);
//       	echo "<pre>";
//			print_r($costTypeArr);

            $str .=<<<EOT
                <tr class="$trClass">
                    <td valign="top" class="form_text_right">
                        $v[parentCostType]
                        <input type="hidden" name="esmcostdetail[$countI][parentCostType]" value="$v[parentCostType]"/>
                        <input type="hidden" name="esmcostdetail[$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
						<input type="hidden" name="esmcostdetail[$countI][days]" id="days$countI" value="1"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[costType]
                        <input type="hidden" name="esmcostdetail[$countI][costType]" id="costType$countI" value="$v[costType]"/>
                        <input type="hidden" name="esmcostdetail[$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
                        <input type="hidden" id="projectCode" name="esmcostdetail[$countI][projectCode]" value="$worklogObj[projectCode]"/>
                        <input type="hidden" id="projectId" name="esmcostdetail[$countI][projectId]" value="$worklogObj[projectId]"/>
                        <input type="hidden" id="projectName" name="esmcostdetail[$countI][projectName]" value="$worklogObj[projectName]"/>
                        <input type="hidden" id="activityName" name="esmcostdetail[$countI][activityName]" value="$worklogObj[activityName]"/>
                        <input type="hidden" id="activityId" name="esmcostdetail[$countI][activityId]" value="$worklogObj[activityId]"/>
                        <input type="hidden" id="worklogId" name="esmcostdetail[$countI][worklogId]" value="$worklogObj[id]"/>
                        <input type="hidden" name="esmcostdetail[$countI][executionDate]" value="$worklogObj[executionDate]"/>
                        <input type="hidden" name="esmcostdetail[$countI][id]" value="$v[id]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
                    </td>
                    <td valign="top" class="form_text_right">
EOT;
            //��Ⱦ��ͬ�ķ��������
            switch($v['costTypeId']){
                case $CARDCOSTTYPE ://���Կ�
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCardrecords($countI)" readonly="readonly"/>
EOT;
                    break;
                case $TEMPPERSONCOSTTYPE ://��Ƹ��Ա
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initTempPerson($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARTRAVELFEECOSTTYPE ://�⳵��
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARFUELFEECOSTTYPE ://�ͷ�
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARROADFEECOSTTYPE ://·�ŷ�
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                case $CARPARKINGFEECOSTTYPE ://ͣ����
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" ondblclick="initCarRental($countI)" readonly="readonly"/>
EOT;
                    break;
                default :
                    $str .=<<<EOT
                            <input type="text" name="esmcostdetail[$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet($countI);countAll();"/>
EOT;
                    break;
            }

            $str .=<<<EOT
					</td>
                    <td colspan="4" class="innerTd">
                        <table class="form_in_table" id="table_$countI">
EOT;
            $esmInvoiceRow = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
            if($esmInvoiceRow){
                foreach($esmInvoiceRow as $thisK => $thisV){
                    $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId']);
                    $thisI = $countI . "_" .$thisK;
                    //ͼƬ��ʾ�ж�
                    $imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
                    //�����ж�
                    $funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
                    //��Ʊ����
                    $str .=<<<EOT
                        <tr id="tr_$thisI">
                            <td width="30%">
                                <select id="select_$thisI" name="esmcostdetail[$countI][invoiceDetail][$thisK][invoiceTypeId]"><option value="">��ѡ��Ʊ</option>$billTypeStr</select>
                            </td>
                            <td width="25%">
                                <input type="hidden" name="esmcostdetail[$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
                                <input type="text" id="invoiceMoney_$thisI" name="esmcostdetail[$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[invoiceMoney]" onblur="countInvoiceMoney()" class="txtshort formatMoney"/>
                            </td>
                            <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceNumber]" value="$thisV[invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort"/>
                            </td>
                            <td width="20">
                                <img style="cursor:pointer;" src="$imgUrl" title="�����" onclick="$funClick"/>
                            </td>
                        </tr>
EOT;
                }
            }else{
                $thisI = $countI . "_0";
                //��Ʊ����
                $str .=<<<EOT
                        <tr id="tr_$thisI">
                            <td width="30%">
                                <select id="select_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceTypeId]"><option value="">��ѡ��Ʊ</option>$initBillStr</select>
                            </td>
                            <td width="25%">
                                <input type="text" id="invoiceMoney_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceMoney()" class="txtshort formatMoney"/>
                            </td>
                            <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="esmcostdetail[$countI][invoiceDetail][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort"/>
                            </td>
                            <td width="20%">
                                <img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice($countI)"/>
                            </td>
                        </tr>
EOT;
            }

			//���ñ�ע���߶�
            $remarkHeight = (count($esmInvoiceRow) - 1)*33 + 20 ."px";

            $str .=<<<EOT
                        </table>
                    </td>
                    <td valign="top">
                    	<textarea name="esmcostdetail[$countI][remark]" id="remark$countI" class="txtlong" style="height:$remarkHeight">$v[remark]</textarea>
                    </td>
                </tr>
EOT;
        }
        $rtArr['str'] = $str;
        $rtArr['countMoney'] = $countMoney;

        return $rtArr;
    }

    //�༭��ʼ��ģ��
    //TODO initTemplateView_d
    function initTemplateView_d($worklogObj){
        //��������
        $rtArr = array();

        //��ȡ��Ʊ����
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        //ʵ������Ʊ����
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

        $costTypeArr = $this->findAll(array('worklogId' => $worklogObj['id']));

		//��־λ
		$markArr = array();

		//��ͬ�м���
		$countArr = array();
		//��ͬ���ü���
		foreach($costTypeArr as $key => $val){
            //��ȡ��Ʊ��Ϣ
            $costTypeArr[$key]['invDetail'] = $esminvoiceDetailDao->findAll(array('costDetailId' => $val['id']));

			//��Ʊ��Ϣ����
            $costTypeArr[$key]['invLength'] = count($costTypeArr[$key]['invDetail']);

			if(isset($countArr[$val['parentCostTypeId']])){
				$countArr[$val['parentCostTypeId']] += $costTypeArr[$key]['invLength'];
			}else{
				$countArr[$val['parentCostTypeId']] = $costTypeArr[$key]['invLength'];
			}
		}

//        echo "<pre>";
//        print_r($costTypeArr);

        //ģ��ʵ�����ַ���
        $str = null;
        //�����ܽ��
        $countMoney = 0;
        $invoiceMoney = 0;
        $invoiceNumber = 0;
        foreach( $costTypeArr as $k => $v){
            if($v['costMoney'] == 0){
                continue;
            }
//	    	echo "<pre>";
//			print_r($v);
            //���г���
    		$mailSize = $countArr[$v['parentCostTypeId']];

            //��ѯ����־�ڵĸ�����ý��
            $detailMoney = bcmul($v['costMoney'],$v['days'],2);
            $countMoney = bcadd($countMoney,$detailMoney,2);

            //���÷�������Id
            $countI = $v['costTypeId'];
            //��ѯ����־�ڵĸ�����ý��
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';

            //�����������ʾ
            if($v['days'] > 1){
            	$costMoneyHtm = "<span class='formatMoney green' title='����:".$v['costMoney']." X ����:".$v['days']."'>$detailMoney</span>";
            }else{
            	$costMoneyHtm = "<span class='formatMoney'>$detailMoney</span>";
            }

            //��Ʊ��������
    		$invSize = $v['invLength'];

    		//ȷ�Ͻ����ʾ
    		$confirmStatus = $this->rtConfirmStatus_d($v['status']);

            foreach($v['invDetail'] as $thisK => $thisV){
            	//��Ʊ�ϼ�
	            $invoiceMoney = bcadd($invoiceMoney,$thisV['invoiceMoney'],2);
	            $invoiceNumber = bcadd($invoiceNumber,$thisV['invoiceNumber']);
            	if($thisK == 0){
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);

            		if(!in_array($v['parentCostTypeId'],$markArr)){
           				$trClass = count($markArr)%2 == 0? 'tr_odd' : 'tr_even';

			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$mailSize">
			                        $v[parentCostType]
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
			                    <td valign="top" rowspan="$invSize">$confirmStatus</td>
				            </tr>
EOT;
					array_push($markArr,$v['parentCostTypeId']);
            		}else{
			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
			                    <td valign="top" rowspan="$invSize">$confirmStatus</td>
				            </tr>
EOT;
            		}
            	}else{
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
		            $str .=<<<EOT
		            	<tr class="$trClass">
			                <td>
								$billType
			                </td>
			                <td>
								<span class="formatMoney">$thisV[invoiceMoney]</span>
			                </td>
			                <td>$thisV[invoiceNumber]</td>
			            </tr>
EOT;
            	}
            }
        }
        $rtArr['str'] = $str;
        $rtArr['countMoney'] = $countMoney;
        $rtArr['invoiceMoney'] = $invoiceMoney;
        $rtArr['invoiceNumber'] = $invoiceNumber;

        return $rtArr;
    }

    /**
     * ȷ�Ϸ���ҳ��
     */
    function initTemplateConfirm_d($worklogObj){
        //��������
        $rtArr = array();

        //��ȡ��Ʊ����
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        //ʵ������Ʊ����
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

        $costTypeArr = $this->findAll(array('worklogId' => $worklogObj['id']));

		//��־λ
		$markArr = array();

		//��ͬ�м���
		$countArr = array();
		//��ͬ���ü���
		foreach($costTypeArr as $key => $val){
            //��ȡ��Ʊ��Ϣ
            $costTypeArr[$key]['invDetail'] = $esminvoiceDetailDao->findAll(array('costDetailId' => $val['id']));

			//��Ʊ��Ϣ����
            $costTypeArr[$key]['invLength'] = count($costTypeArr[$key]['invDetail']);

			if(isset($countArr[$val['parentCostTypeId']])){
				$countArr[$val['parentCostTypeId']] += $costTypeArr[$key]['invLength'];
			}else{
				$countArr[$val['parentCostTypeId']] = $costTypeArr[$key]['invLength'];
			}
		}

//        echo "<pre>";
//        print_r($costTypeArr);

        //ģ��ʵ�����ַ���
        $str = null;
        //�����ܽ��
        $countMoney = 0;
        $invoiceMoney = 0;
        $invoiceNumber = 0;
        foreach( $costTypeArr as $k => $v){
            if($v['costMoney'] == 0){
                continue;
            }
//	    	echo "<pre>";
//			print_r($v);
            //���г���
    		$mailSize = $countArr[$v['parentCostTypeId']];

            //��ѯ����־�ڵĸ�����ý��
            $detailMoney = bcmul($v['costMoney'],$v['days'],2);
            $countMoney = bcadd($countMoney,$detailMoney,2);

            //���÷�������Id
            $countI = $v['costTypeId'];
            //��ѯ����־�ڵĸ�����ý��
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';

            //�����������ʾ
            if($v['days'] > 1){
            	$costMoneyHtm = "<span class='formatMoney green' title='����:".$v['costMoney']." X ����:".$v['days']."'>$detailMoney</span>";
            }else{
            	$costMoneyHtm = "<span class='formatMoney'>$detailMoney</span>";
            }

            //��Ʊ��������
    		$invSize = $v['invLength'];
			//�������״̬
    		$thisRs = $this->rtConfirmStatus_d($v['status']);

            foreach($v['invDetail'] as $thisK => $thisV){
            	//��Ʊ�ϼ�
	            $invoiceMoney = bcadd($invoiceMoney,$thisV['invoiceMoney'],2);
	            $invoiceNumber = bcadd($invoiceNumber,$thisV['invoiceNumber']);
            	if($thisK == 0){
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
            		if(!in_array($v['parentCostTypeId'],$markArr)){
           				$trClass = count($markArr)%2 == 0? 'tr_odd' : 'tr_even';

			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$mailSize">
			                        $v[parentCostType]
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
EOT;
						if($v['status'] == "0"){
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
	                                	<input type="hidden" name="esmcostdetail[detail][$k][id]" value="$v[id]"/>
										<select name="esmcostdetail[detail][$k][status]" class="txtshort"><option value="1">���ͨ��</option><option value="2">���</option></select>
				                    </td>
					            </tr>
EOT;
						}else{
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
										$thisRs
				                    </td>
					            </tr>
EOT;
						}
					array_push($markArr,$v['parentCostTypeId']);
            		}else{
			            $str .=<<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $v[costType]
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td>
									<span class="formatMoney">$thisV[invoiceMoney]</span>
				                </td>
				                <td>$thisV[invoiceNumber]</td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[remark]
			                    </td>
EOT;
						if($v['status'] == "0"){
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
	                                	<input type="hidden" name="esmcostdetail[detail][$k][id]" value="$v[id]"/>
										<select name="esmcostdetail[detail][$k][status]" class="txtshort"><option value="1">���ͨ��</option><option value="2">���</option></select>
				                    </td>
					            </tr>
EOT;
						}else{
				            $str .=<<<EOT
				                    <td valign="top" class="form_text_right" rowspan="$invSize">
										$thisRs
				                    </td>
					            </tr>
EOT;
						}
            		}
            	}else{
            		$billType = $this->initBillView_d($billTypeArr,$thisV['invoiceTypeId']);
		            $str .=<<<EOT
		            	<tr class="$trClass">
			                <td>
								$billType
			                </td>
			                <td>
								<span class="formatMoney">$thisV[invoiceMoney]</span>
			                </td>
			                <td>$thisV[invoiceNumber]</td>
			            </tr>
EOT;
            	}
            }
        }
        $rtArr['str'] = $str;
        $rtArr['countMoney'] = $countMoney;
        $rtArr['invoiceMoney'] = $invoiceMoney;
        $rtArr['invoiceNumber'] = $invoiceNumber;

        return $rtArr;
    }

    //�������ʼ����optionѡ��
    function initBillType_d($object,$thisVal = null,$defaultVal = null,$isReplace = 1){
        $str = null;
        $title = $isReplace ? '�˷���������Ʊ' : '�˷��ò�������Ʊ';
        foreach($object as $key => $val){
            if($thisVal == $val['id']){
            	$str.='<option value="'.$val['id'].'" selected="selected" title="'.$title.'">'.$val['name'] .'</option>';
            }elseif($defaultVal == $val['id']){
            	if($thisVal){
           			$str.='<option value="'.$val['id'].'" title="'.$title.'">'.$val['name'] .'</option>';
            	}else{
            		$str.='<option value="'.$val['id'].'" selected="selected" title="'.$title.'">'.$val['name'] .'</option>';
            	}
            }else{
            	if($isReplace){
               		$str.='<option value="'.$val['id'].'" title="'.$title.'">'.$val['name'] .'</option>';
            	}
            }
        }
        return $str;
    }

    //�鿴��Ʊֵ
    function initBillView_d($object,$thisVal = null){
        $str = null;
        foreach($object as $key => $val){
        	if($thisVal == $val['id']){
				return $val['name'];
        	}
        }
        return null;
    }

    //���ط�����������
    function initExpenseEdit_d($object,$thisVal = null){
        $str = null;
        foreach($object as $key => $val){
        	if($thisVal == $val['id']){
				return array(
					'name' => $val['name'],
					'showDays' => $val['showDays'],
					'isReplace' => $val['isReplace'],
					'isEqu' => $val['isEqu'],
					'invoiceType' => $val['invoiceType'],
					'invoiceTypeName' => $val['invoiceTypeName'],
					'isSubsidy' => $val['isSubsidy']
				);
        	}
        }
        return null;
    }

    /******************** ��Ŀ���������� *********************/
    /**
     * ��Ŀ����������
     * TODO ��Ŀ����������
     */
    function projectManage_d($projectId,$showType,$beginDate,$endDate){
    	//��ȡ��Ŀ�еĳ�Ա
		$esmmemberDao = new model_engineering_member_esmmember();
		$memberArr = $esmmemberDao->getMemberInProject_d($projectId);

		//��������
		$days = (strtotime($endDate) - strtotime($beginDate))/86400 + 1;

		//��ǰ���ϼ�
		$countArr = array();

		//����������Ⱦ
		$dateArr = array();

		//ͷ����Ϣ��Ⱦ
		$thisDate = $beginDate;
    	for($i = 0;$i< $days;$i++){
    		if($i != 0){
				$thisDate = date('Y-m-d',strtotime($thisDate) + 86400);
    		}
    		$wday = $this->rtWeekDay_d(date('w',strtotime($thisDate)));
    		$rtArr['tr'] .=<<<EOT
				<th>$thisDate($wday)</th>
EOT;
			array_push($dateArr,$thisDate);
    	}

    	//��Ⱦ��Ŀ��Ա��
    	$memberIdArr = array();
		foreach($memberArr as $val){
			array_push($memberIdArr,$val['memberId']);
		}

    	//��־��Ϣ��ȡ
    	$esmworklogDao = new model_engineering_worklog_esmworklog();
    	$worklogArr = $esmworklogDao->getWorklogInPeriod_d(implode($memberIdArr,','),$projectId,$beginDate,$endDate);
    	$newLogArr = $esmworklogDao->logArrDeal_d($worklogArr);

    	//��Ŀ���ò�ѯ
    	$projectCostArr = $this->getCostForProject_d($projectId);

//    	echo "<pre>";
//		print_r($newLogArr);

		//��ǰ���úϼ�
		$showCostMoneyCount = 0;

		//�б���Ϣ
		$str = null;
		$i = 0;
		foreach($memberArr as $key => $val){
			$i++;
			$trClass = $i%2 == 0? 'tr_odd' : 'tr_even';

			//�ܱ��Ƿ��Ѿ��ύ
			$logHandup = $newLogArr[$val['memberId']]['logStatus'] == 'YTJ' || $newLogArr[$val['memberId']]['logStatus'] == 'YQR' ? '<img src="images/icon/icon088.gif"/>' : '';

			//��ʾ��¼�Ľ��ϼ�
			$showCostMoney = isset($newLogArr[$val['memberId']]) ? $newLogArr[$val['memberId']]['costMoney'] : 0;
			$showCostMoneyCount = bcadd($showCostMoneyCount,$showCostMoney,2);

			//ȫ������
			$thisAllCostMoney = isset($projectCostArr[$val['memberId']]) ? $projectCostArr[$val['memberId']]['allCostMoney'] : 0;

			//δ��˷���
			$thisUnauditMoney = isset($projectCostArr[$val['memberId']]) ? $projectCostArr[$val['memberId']]['unauditMoney'] : 0;

			$str .=<<<EOT
				<tr class="$trClass">
					<td>$i</td>
					<td>$val[memberName]</td>
					<td>$logHandup</td>
					<td class="formatMoney">$thisAllCostMoney</td>
					<td class="formatMoney">$thisUnauditMoney</td>
					<td class="formatMoney">$showCostMoney</td>
EOT;
			foreach($dateArr as $k => $v){
//				if($val['memberId'] == 'peng.lei'){
//					echo "<pre>";
//					print_r($newLogArr[$val['memberId']]['dateInfo'][$v]);
//				}
				//�û���ǰ¼�����
				$thisCostMoney = isset($newLogArr[$val['memberId']]['dateInfo'][$v]['costMoney']) ? $newLogArr[$val['memberId']]['dateInfo'][$v]['costMoney'] : '';

				//��־id
				$worklogId = isset($newLogArr[$val['memberId']]['dateInfo'][$v]['worklogId']) ? $newLogArr[$val['memberId']]['dateInfo'][$v]['worklogId'] : '';

				//������δȷ��
				if($thisCostMoney == 0){
					if($thisCostMoney*1 === 0.00){
						$str .=<<<EOT
							<!--td title="����־���޷���"><span class="formatMoney">$thisCostMoney</span></td-->
							<td title="����־���޷���"><a href="javascript:void(0)" class="formatMoney" onclick="viewCost($worklogId);">$thisCostMoney</a></td>
EOT;
					}else{
						$str .=<<<EOT
							<td></td>
EOT;
					}
				}elseif($newLogArr[$val['memberId']]['dateInfo'][$v]['confirmStatus'] == '1' && $newLogArr[$val['memberId']]['dateInfo'][$v]['unconfirmMoney'] == 0){
					if($newLogArr[$val['memberId']]['dateInfo'][$v]['backMoney'] == 0){
						$thisCostMoney = $newLogArr[$val['memberId']]['dateInfo'][$v]['confirmMoney'];
						$str .=<<<EOT
							<td title="��ȷ�ϵķ���"><a href="javascript:void(0)" class="formatMoney" onclick="viewCost($worklogId);">$thisCostMoney</a></td>
EOT;
					}else{
						$thisCostMoney = $newLogArr[$val['memberId']]['dateInfo'][$v]['confirmMoney'];
						$str .=<<<EOT
							<td title="��ȷ�ϵ����д�ط���"><a href="javascript:void(0)" class="formatMoney" style="color:red" onclick="viewCost($worklogId);">$thisCostMoney</a></td>
EOT;
					}
				}else{//��ȷ��
					$str .=<<<EOT
						<td title="δȷ�ϵķ���"><a href="javascript:void(0)" class="formatMoney" style="color:green" onclick="confirmCost($worklogId);">$thisCostMoney</a></td>
EOT;
				}

				//�������¼��Ľ��ʱ�����Ⱥϼ����鿪���ж�
				if($thisCostMoney !== ''){
					//����ϼ���
					$countArr[$v] = isset($countArr[$v]) ? bcadd($countArr[$v],$thisCostMoney,2) : $thisCostMoney;
				}else if(!isset($countArr[$v])){
					$countArr[$v] = "";
				}
			}

			$str .=<<<EOT
				</tr>
EOT;
		}

		//�ϼƲ���
		$str .=<<<EOT
			<tr class="tr_count">
				<td></td>
				<td>�ϼ�</td>
				<td></td>
				<td class="formatMoney">$projectCostArr[allCostMoney]</td>
				<td class="formatMoney">$projectCostArr[unauditMoney]</td>
				<td class="formatMoney">$showCostMoneyCount</td>
EOT;
		foreach($dateArr as $key => $val){
			if(isset($countArr[$val])){
				$str .=<<<EOT
					<td class="formatMoney">$countArr[$val]</td>
EOT;
			}
		}
		//�ϼƲ���
		$str .=<<<EOT
			</tr>
EOT;

		$rtArr['list'] = $str;
		return $rtArr;
    }

    /**
     * ����ȷ�Ϲ���
     */
    function confirm_d($object){
//    	echo "<pre>";
//		print_r($object);
		//��ȡ��������
		$feeArr = $object['detail'];

		//��Ʊ����
		$esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

		try{
			$this->start_d();

			//����ȷ�ϵ�״̬
			foreach($feeArr as $key => $val){
				$this->update(array('id' => $val['id']),array('status' => $val['status']));

				//���·�Ʊ״̬
				$esminvoiceDetailDao->updateCostInvoice_d($val['id'],$val['status']);
			}

			//������ɺ��ȡ��ȷ�Ͻ��
			$moneyArr = $this->getWorklogMoney_d($object['id']);

			//������־��Ϣ
			$worklogDao = new model_engineering_worklog_esmworklog();
			$worklogDao->update(
				array('id' => $object['id']),
				array('confirmDate' => day_date,'confirmMoney' => $moneyArr['confirmMoney'],'backMoney' => $moneyArr['backMoney'],
					'confirmName' => $_SESSION['USERNAME'],'confirmId' => $_SESSION['USER_ID'],
					'confirmStatus' => 1
				)
			);

			//������Ա����Ŀ����
            if($object['projectId']){
				//��ȡ��ǰ��Ŀ�ķ���
				$projectCountArr = $this->getCostFormMember_d($object['projectId'],$object['memberId']);

				//������Ա������Ϣ
				$esmmemberDao = new model_engineering_member_esmmember();
				$esmmemberDao->update(
					array('projectId' => $object['projectId'] ,'memberId' => $object['memberId']),
					$projectCountArr
				);
            }

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
    }

    /**
     * ����ȷ�Ϲ���
     */
    function auditFee_d($worklogId,$isBack = 0){
		$rs = $this->findAll(array('worklogId' => $worklogId),null,'id');

		//����������ʱֱ�ӷ���true
		if(!$rs){
			return true;
		}

		try{
			$this->start_d();

			//��Ʊ����
			$esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

			//�жϸ���״̬
			if($isBack == 0){
				$status = '1';
			}else{
				$status = '2';
			}
			//�Լ��ķ��ø���
			$this->update(array('worklogId' => $worklogId),array('status' => $status));

			//����ȷ�ϵ�״̬
			foreach($rs as $key => $val){
				//���·�Ʊ״̬
				$esminvoiceDetailDao->updateCostInvoice_d($val['id'],$status);
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
    }

    /**
     * ����ȷ�Ϲ��� - ����
     */
    function unauditFee_d($worklogId){
		$rs = $this->findAll(array('worklogId' => $worklogId),null,'id');

		//����������ʱֱ�ӷ���true
		if(!$rs){
			return true;
		}

		try{
			$this->start_d();

			//��Ʊ����
			$esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();
			//�Լ��ķ��ø���
			$this->update(array('worklogId' => $worklogId),array('status' => 0));

			//����ȷ�ϵ�״̬
			foreach($rs as $key => $val){
				//���·�Ʊ״̬
				$esminvoiceDetailDao->updateCostInvoice_d($val['id'],0);
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
    }

    /**
     * ��ȡ��־���ͨ���ķ���
     */
    function getWorklogMoney_d($worklogId){
		$this->searchArr = array(
			'worklogId' => $worklogId
		);
		$this->groupBy = 'c.worklogId';
		$rs = $this->list_d('count_membercount');
		if($rs){
			return $rs[0];
		}else{
			return 0;
		}
    }

    /**
     * ��ȡ��Ŀ�еķ��� - ������������
     */
    function getCostForProject_d($projectId){
		$this->searchArr = array(
			'projectId' => $projectId
		);
		$this->groupBy = 'c.createId';
		$rs = $this->list_d('count_project');
		if($rs){
			$allCostCount = 0;//ȫ�����úϼ�
			$unauditCount = 0;//δ��˷��úϼ�

			foreach($rs as $key => $val){
				$projectCostArr[$val['createId']]['allCostMoney'] = $val['allCostMoney'];
				$projectCostArr[$val['createId']]['unauditMoney'] = $val['unauditMoney'];

				$allCostCount = bcadd($allCostCount,$val['allCostMoney'],2);
				$unauditCount = bcadd($unauditCount,$val['unauditMoney'],2);
			}
			$projectCostArr['allCostMoney'] = $allCostCount;
			$projectCostArr['unauditMoney'] = $unauditCount;

			return $projectCostArr;
		}else{
			return false;
		}
    }

    /**
     * ��ȡĳ����ĳ��Ŀ�еķ���
     */
    function getCostFormMember_d($projectId,$memberId = null){
    	//���û�����ˣ����Զ���ȡ$_SESSION
    	if(empty($memberId)){
			$memberId = $_SESSION['USER_ID'];
    	}
		$this->searchArr = array(
			'projectId' => $projectId,
			'createId' => $memberId
		);
		$this->groupBy = 'c.createId';
		$rs = $this->list_d('count_membercount');
		if($rs){
			return $rs[0];
		}else{
			return false;
		}
    }

    /**
     * ��ȡĳ��ĳ��Ŀĳ���ڵĿɱ�������
     */
    function getCostForExpense_d($projectId,$days,$memberId){
		$this->searchArr = array(
			'projectId' => $projectId,
			'executionDates' => $days,
			'createId' => $memberId,
			'status' => 1
		);
		$this->groupBy = 'c.costTypeId';
		$this->sort = 'c.parentCostTypeId desc,c.costTypeId';
		$this->asc = false;
		$rs = $this->list_d('count_costType');
		if($rs){
			return $rs;
		}else{
			return false;
		}
    }

    /**
     * ��Ŀ����������
     * TODO ��Ŀ����������
     */
    function projectView_d($projectId,$showType,$beginDate,$endDate){
    	//��ȡ��Ŀ�еĳ�Ա
		$esmmemberDao = new model_engineering_member_esmmember();
		$memberArr = $esmmemberDao->getMemberInProject_d($projectId);

		//��������
		$days = (strtotime($endDate) - strtotime($beginDate))/86400 + 1;

		//��ǰ���ϼ�
		$countArr = array();

		//����������Ⱦ
		$dateArr = array();

		//ͷ����Ϣ��Ⱦ
		$thisDate = $beginDate;
    	for($i = 0;$i< $days;$i++){
    		if($i != 0){
				$thisDate = date('Y-m-d',strtotime($thisDate) + 86400);
    		}
    		$wday = $this->rtWeekDay_d(date('w',strtotime($thisDate)));
    		$rtArr['tr'] .=<<<EOT
				<th>$thisDate($wday)</th>
EOT;
			array_push($dateArr,$thisDate);
    	}

    	//��Ⱦ��Ŀ��Ա��
    	$memberIdArr = array();
		foreach($memberArr as $val){
			array_push($memberIdArr,$val['memberId']);
		}

    	//��־��Ϣ��ȡ
    	$esmworklogDao = new model_engineering_worklog_esmworklog();
    	$worklogArr = $esmworklogDao->getWorklogInPeriod_d(implode($memberIdArr,','),$projectId,$beginDate,$endDate);
    	$newLogArr = $esmworklogDao->logArrDeal_d($worklogArr);

    	//��Ŀ���ò�ѯ
    	$projectCostArr = $this->getCostForProject_d($projectId);

//    	echo "<pre>";
//		print_r($newLogArr);

		//��ǰ���úϼ�
		$showCostMoneyCount = 0;

		//�б���Ϣ
		$str = null;
		$i = 0;
		foreach($memberArr as $key => $val){
			$i++;
			$trClass = $i%2 == 0? 'tr_odd' : 'tr_even';

			//�ܱ��Ƿ��Ѿ��ύ
			$logHandup = $newLogArr[$val['memberId']]['logStatus'] == 'YTJ' || $newLogArr[$val['memberId']]['logStatus'] == 'YQR' ? '<img src="images/icon/icon088.gif"/>' : '';

			//��ʾ��¼�Ľ��ϼ�
			$showCostMoney = isset($newLogArr[$val['memberId']]) ? $newLogArr[$val['memberId']]['costMoney'] : 0;
			$showCostMoneyCount = bcadd($showCostMoneyCount,$showCostMoney,2);

			//ȫ������
			$thisAllCostMoney = isset($projectCostArr[$val['memberId']]) ? $projectCostArr[$val['memberId']]['allCostMoney'] : 0;

			//δ��˷���
			$thisUnauditMoney = isset($projectCostArr[$val['memberId']]) ? $projectCostArr[$val['memberId']]['unauditMoney'] : 0;

			$str .=<<<EOT
				<tr class="$trClass">
					<td>$i</td>
					<td>$val[memberName]</td>
					<td>$logHandup</td>
					<td class="formatMoney">$thisAllCostMoney</td>
					<td class="formatMoney">$thisUnauditMoney</td>
					<td class="formatMoney">$showCostMoney</td>
EOT;
			foreach($dateArr as $k => $v){
				//�û���ǰ¼�����
				$thisCostMoney = isset($newLogArr[$val['memberId']]['dateInfo'][$v]['costMoney']) ? $newLogArr[$val['memberId']]['dateInfo'][$v]['costMoney'] : '';

				//��־id
				$worklogId = isset($newLogArr[$val['memberId']]['dateInfo'][$v]['worklogId']) ? $newLogArr[$val['memberId']]['dateInfo'][$v]['worklogId'] : '';

				//������δȷ��
				if($thisCostMoney == 0){
					if($thisCostMoney*1 === 0.00){
						$str .=<<<EOT
							<td title="����־���޷���"><span class="formatMoney">$thisCostMoney</span></td>
EOT;
					}else{
						$str .=<<<EOT
							<td></td>
EOT;
					}
				}elseif($newLogArr[$val['memberId']]['dateInfo'][$v]['confirmStatus'] == '1'){
					$thisCostMoney = $newLogArr[$val['memberId']]['dateInfo'][$v]['confirmMoney'];
					$str .=<<<EOT
						<td title="��ȷ�ϵķ���"><a href="javascript:void(0)" class="formatMoney" onclick="viewCost($worklogId);">$thisCostMoney</a></td>
EOT;
				}else{//��ȷ��
					$str .=<<<EOT
						<td title="δȷ�ϵķ���"><a href="javascript:void(0)" class="formatMoney" style="color:green" onclick="viewCost($worklogId);">$thisCostMoney</a></td>
EOT;
				}

				//�������¼��Ľ��ʱ�����Ⱥϼ����鿪���ж�
				if($thisCostMoney !== ''){
					//����ϼ���
					$countArr[$v] = isset($countArr[$v]) ? bcadd($countArr[$v],$thisCostMoney,2) : $thisCostMoney;
				}else if(!isset($countArr[$v])){
					$countArr[$v] = "";
				}
			}

			$str .=<<<EOT
				</tr>
EOT;
		}

		//�ϼƲ���
		$str .=<<<EOT
			<tr class="tr_count">
				<td></td>
				<td>�ϼ�</td>
				<td></td>
				<td class="formatMoney">$projectCostArr[allCostMoney]</td>
				<td class="formatMoney">$projectCostArr[unauditMoney]</td>
				<td class="formatMoney">$showCostMoneyCount</td>
EOT;
		foreach($dateArr as $key => $val){
			if(isset($countArr[$val])){
				$str .=<<<EOT
					<td class="formatMoney">$countArr[$val]</td>
EOT;
			}
		}
		//�ϼƲ���
		$str .=<<<EOT
			</tr>
EOT;

		$rtArr['list'] = $str;
		return $rtArr;
    }

    /*********************** Ʊ�������� ************************/
    /**
     * ��ȡ��������Ϣ
     */
    function getExpenseInfo_d($id){
		$expenseDao = new model_finance_expense_expense();
		$expenseObj = $expenseDao->find(array('id' => $id),null);
		return $expenseObj;
    }

    /**
     *  ��ȡ��Ӧ�ķ�����ϸ
     */
    function getCostdetail_d($ids){
    	$this->searchArr = array('ids' => $ids);
    	$this->sort = "c.worklogId";
		$rs = $this->list_d();
		return $rs;
    }

    /**
     * ����id��ȡ��������
     */
    function getCostByIds_d($ids){
    	$this->searchArr = array('ids' => $ids);
    	$this->groupBy = 'costTypeId';
		$rs = $this->list_d('select_fee');
		return $rs;
    }

    /**
     * ��Ⱦ������ϸ�����б�
     */
    function initCostdetail_d($costdetail){
		$rs = array('tr' => '','list' => '');

		//��չ��ͷ -- ��Ҫ�Ƿ�������
		$titleArr = array();
		//��������
		$contentArr = array();

		foreach($costdetail as $key => $val){
			//��չ���⹹��
			if(!isset($titleArr[$val['costTypeId']])){
				$titleArr[$val['costTypeId']] = $val['costType'];
			}

			//�ع�����
			$contentArr[$val['worklogId']][$val['costTypeId']] = $val;
		}
//		echo "<pre>";
//		print_r($contentArr);

		$str = "";
		$countMoneyArr = array();
		$i = 0;
		foreach($contentArr as $key => $val){
			$head = "";
			$body = "";
			$countMoney = 0;
			$costdetailIdArr = array();
			$i++;
			foreach($titleArr as $tk => $tv){
				if(isset($val[$tk])){
					//������id���浽������
					array_push($costdetailIdArr,$val[$tk]['id']);

					$costMoney = $val[$tk]['costMoney'];
					$countMoney = bcadd($countMoney,$costMoney,2);//�кϼƽ��
					//ͳ���в���
					$countMoneyArr[$tk] = isset($countMoneyArr[$tk]) ? bcadd($costMoney ,$countMoneyArr[$tk],2) : $costMoney;

					$body .=<<<EOT
						<td class="formatMoney" style="text-align:right;padding:0 5px 0 0;">$costMoney</td>
EOT;
					if(empty($head)){
						$worklogDate = $costMoney = $val[$tk]['executionDate'];
						$head .=<<<EOT
							<td><a href="javascript:void(0)" onclick="showWorklog($key)" title="����鿴��־">$worklogDate</a></td>
							<td></td>
EOT;
					}
				}else{
					$body .=<<<EOT
						<td style="text-align:right;padding:0 5px 0 0;">0.00</td>
EOT;
				}
			}
			//ȫ���ϼ�
			$countMoneyArr['allMoney'] = isset($countMoneyArr['allMoney']) ?  bcadd($countMoney ,$countMoneyArr['allMoney'],2) : $countMoney;

			$costdetailId = implode(',',$costdetailIdArr);
			$bottom =<<<EOT
				<td class="formatMoney" style="text-align:right;padding:0 5px 0 0;">$countMoney</td>
				<td>
					<a href="javascript:void(0)" onclick="editWorklog($key)">�޸���ϸ</a>
					<input type="hidden" id="costdetailId$key" value="$costdetailId"/>
				</td>
EOT;
			$trClass = $i%2 == 1? 'tr_even' : 'tr_odd';
			$str .= "<tr class='$trClass'><td>$i</td>".$head . $body. $bottom."</tr>";

		}
		$rs['tr'] = $this->initCostTitle_d($titleArr);

		//���ɺϼ���
		$countStr = $this->initCostCount_d($titleArr,$countMoneyArr);

		$rs['list'] = $str.$countStr;

		return $rs;
    }

    /**
     * ʵ��������
     */
    function initCostTitle_d($titleArr){
    	$str = "";
		foreach($titleArr as $key => $val){
			$str .=<<<EOT
				<td width="80px">$val</td>
EOT;
		}
		$str .="<td width='80px'>�ϼ�</td><td width='80px'>����</td>";
		return $str;
    }

    /**
     * ʵ�����ϼ���
     */
    function initCostCount_d($titleArr,$countMoneyArr){
		$countStr = "<tr class='tr_count'><td></td><td>�ϼ�</td><td></td>";
		foreach($titleArr as $ctk => $ctv){
			$countStr .=<<<EOT
				<td class="formatMoney" style="text-align:right;padding:0 5px 0 0;">$countMoneyArr[$ctk]</td>
EOT;
		}
		$countStr.= "<td class='formatMoney' style='text-align:right;padding:0 5px 0 0;'>$countMoneyArr[allMoney]</td><td></td></tr>";
		return $countStr;
    }

    //�༭��ʼ��ģ��
    function initCheckEdit_d($worklogId,$costdetailId){
    	$costdetailIdArr = explode(',',$costdetailId);

        //��ȡ��Ʊ����
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);
        $initBillStr = $this->initBillType_d($billTypeArr);

        //��ѯģ��С����
        $sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isReplace,isEqu,isSubsidy from cost_type where isNew = '1'";
        $sysCostTypeArr = $this->_db->getArray($sql);

        //ʵ������Ʊ����
        $esminvoiceDetailDao = new model_engineering_cost_esminvoicedetail();

    	//��������±༭
		$costTypeArr = $this->findAll(array('worklogId' => $worklogId),'id asc');

        //ģ��ʵ�����ַ���
        $str = null;
        //�����ܽ��
        $countMoney = 0;
        foreach( $costTypeArr as $k => $v){
            //���÷�������Id
            $countI = $v['costTypeId'];
            //��ѯ����־�ڵĸ�����ý��
            $trClass = $k%2 == 0? 'tr_odd' : 'tr_even';
            $countMoney = bcadd($countMoney,$v['costMoney']);

            //��ȡƥ���������
            $thisCostType = $this->initExpenseEdit_d($sysCostTypeArr,$v['costTypeId']);

			//���޸Ĳ���
            if(in_array($v['id'],$costdetailIdArr)){
	            $str .=<<<EOT
	                <tr class="$trClass" id="tr$v[costTypeId]">
	                    <td valign="top">
	                        <img style="cursor:pointer;" src="images/removeline.png" title="ɾ������" onclick="deleteCostType($countI)"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[parentCostType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[parentCostType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[costType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][id]" value="$v[id]"/>
	                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
	                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
	                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
	                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
	                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
	                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
EOT;
				//�����Ҫ��ʾ����������ʾ
				if($thisCostType['showDays']){
					$str .=<<<EOT
							<span>
								<input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" class="txtshort formatMoney" value="$v[costMoney]" style="width:60px" onblur="detailSet($countI);countAll();"/>
								X
								����
								<input type="text" name="esmworklog[esmcostdetail][$countI][days]" class="readOnlyTxtMin" id="days$countI" value="$v[days]" readonly="readonly"/>
							</span>
EOT;
				}else{
					$str .=<<<EOT
		                    <input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" style="width:146px" class="txtmiddle formatMoney" value="$v[costMoney]" onblur="detailSet($countI);countAll();"/>
							<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="$v[days]"/>
EOT;
				}

	            $str .=<<<EOT
						</td>
	                    <td colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
EOT;
	            //��ȡ��Ʊ��Ϣ
	            $esminvoiceDetailArr = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
	            foreach($esminvoiceDetailArr as $thisK => $thisV){
					// �Ƿ���Ҫ��Ʊ
					if($thisCostType['isSubsidy'] == 1){
						$billArr = $this->getBillArr_d($billTypeArr,$thisV['invoiceTypeId']);
						$str .=<<<EOT
		                    <tr id="tr_$thisI">
			                    <td width="30%">
	                                <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
	                                <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" value="$billArr[id]"/>
		                        </td>
		                        <td width="25%">
	                                <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
		                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
		                        </td>
		                        <td width="25%">
	                                <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" readonly="readonly"/>
		                        </td>
		                        <td width="20%">
	                                <img style="cursor:pointer;" src="images/add_item.png" title="�����Ͳ���Ҫ¼�뷢Ʊ�����ܽ�����������" onclick="alert('�����Ͳ���Ҫ¼�뷢Ʊ�����ܽ�����������');"/>
		                        </td>
		                    </tr>
EOT;
					}else{
		                $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId'],$thisCostType['invoiceType'],$thisCostType['isReplace']);
		                $thisI = $countI . "_" .$thisK;
		                //ͼƬ��ʾ�ж�
		                $imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
		                //�����ж�
		                $funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
		                $invTitle = $thisK == 0 ? "�����" : "ɾ�����з�Ʊ";
		                //��Ʊ����
		                $str .=<<<EOT
		                    <tr id="tr_$thisI">
		                        <td width="30%">
		                            <select id="select_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceTypeId]" style="width:90px"><option value="">��ѡ��Ʊ</option>$billTypeStr</select>
		                        </td>
		                        <td width="25%">
		                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
		                            <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]" onblur="invMoneySet('$thisI');countInvoiceMoney()" class="txtshort formatMoney"/>
		                        </td>
		                        <td width="25%">
		                            <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" onblur="countInvoiceNumber(this)" value="$thisV[invoiceNumber]" class="txtshort"/>
		                        </td>
		                        <td width="20%">
		                            <img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
		                        </td>
		                    </tr>
EOT;
					}
	            }

				//���ñ�ע���߶�
	            $remarkHeight = (count($esminvoiceDetailArr) - 1)*33 + 20 ."px";

	            $str .=<<<EOT
	                        </table>
	                    </td>
		                <td valign="top">
	                    	<textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" style="height:$remarkHeight" class="txtlong">$v[remark]</textarea>
	                    </td>
	                </tr>
EOT;
            }else{
	            $str .=<<<EOT
	                <tr class="$trClass" id="tr$v[costTypeId]" title="ͨ����˵ķ���">
	                    <td valign="top"></td>
	                    <td valign="top" class="form_text_right">
	                        $v[parentCostType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[parentCostType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[parentCostTypeId]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[costType]
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[costTypeId]"/>
	                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][id]" value="$v[id]"/>
		                    <input type="hidden" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" value="$v[costMoney]"/>
							<input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="$v[days]"/>

	                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
	                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
	                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
	                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
	                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
	                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>

	                        <input type="hidden" id="notSelect$countI" value="1"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
							$v[costMoney]
						</td>
	                    <td colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
EOT;
	            //��ȡ��Ʊ��Ϣ
	            $esminvoiceDetailArr = $esminvoiceDetailDao->findAll(array('costDetailId' => $v['id']));
	            foreach($esminvoiceDetailArr as $thisK => $thisV){
	                $thisI = $countI . "_" .$thisK;
	                $billTypeStr = $this->initBillType_d($billTypeArr,$thisV['invoiceTypeId'],$thisCostType['invoiceType'],$thisCostType['isReplace']);
					$billArr = $this->getBillArr_d($billTypeArr,$thisV['invoiceTypeId']);
					$str .=<<<EOT
	                    <tr id="tr_$thisI">
		                    <td align="left" width="29.6%">
                                $billArr[name]
	                            <select id="select_$thisI" style="width:90px;display:none;">$billTypeStr</select>
                                <input type="hidden" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceMoney]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceMoney]"/>
	                            <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][id]" value="$thisV[id]"/>
                                <input type="hidden" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][$thisK][invoiceNumber]" costTypeId="$v[costTypeId]" rowCount="$thisI" value="$thisV[invoiceNumber]"/>
	                        </td>
	                        <td align="left" width="27%" class="formatMoney">$thisV[invoiceMoney]</td>
	                        <td align="left" width="27%">$thisV[invoiceNumber]</td>
	                        <td></td>
	                    </tr>
EOT;
	            }
	            $str .=<<<EOT
	                        </table>
	                    </td>
		                <td valign="top" align="left">$v[remark]
	                    </td>
	                </tr>
EOT;
            }
        }

        return $str;
    }
    /**
     * ��ϸ���ñ���
     */
    function listHtml_d($projectId,$status){
    	$sql = "SELECT
    				executionDate,
					parentCostType,
					costType,
					SUM(costMoney) AS costMoney
				FROM ".$this->tbl_name."
				WHERE
					projectId = '".$projectId."'
				AND createId = '".$_SESSION['USER_ID']."'
				AND status='".$status."'
				GROUP BY executionDate,costType";

    	$this->asc = false;
    	$this->sort = "executionDate";
    	$trStr="";
    	$arr = $this->listBySql($sql);
    	//�ع�����
    	$new = array();
    	foreach ($arr as $key => $val) {
    		$new[$val['executionDate']]['executionDate'] = $val['executionDate'];
    		$new[$val['executionDate']][$val['parentCostType']][$val['costType']] = $val['costMoney'];
    		$new[$val['executionDate']]['countMoney'] = bcadd($new[$val['executionDate']]['countMoney'], $val['costMoney'],2);
    	}
    	$new = array_values($new);
    	//��ȡ���з������͹����ͷ
    	$headArr = array();
    	foreach ($arr as $key => $val){
    		if(!in_array($val[costType],$headArr[$val['parentCostType']])){
    			$headArr[$val['parentCostType']][] = $val[costType];
    		}
    	}
    	//δ����״̬������ʾcheckbox
    	if($status == '1'){
    		$subCheck.=<<<EOT
				<td style="text-align: center;"><input type="checkbox" class="subCheck"/></td>
EOT;
    		$mainCheck =<<<EOT
				<th rowspan="2" width="30"><input type="checkbox" id="mainCheck" onclick="selectAllCheck();"/></th>
EOT;
    	}
    	foreach ($new as $key => $val){
    	    $td = '';
    		foreach ($headArr as $thisKey => $thisVal){
				foreach ($thisVal as $thisK =>$thisV){
					if (isset($val[$thisKey])){
						$td.=<<<EOT
    						<td style="text-align: center;" class="formatMoney">{$val[$thisKey][$thisV]}</td>
EOT;
					}else {
						$td.=<<<EOT
    						<td style="text-align: center;"></td>
EOT;
					}
				}
    		}
			$trStr.=<<<EOT
				<tr class="tr_even">
					$subCheck
					<td style="text-align: center;" class="executionDate">{$val['executionDate']}</td>
					<td style="text-align: center;" class="formatMoney">{$val['countMoney']}</td>
					$td
				</tr>
EOT;
		}
		if(empty($trStr)){
			$trStr = <<<EOT
				<tr class="tr_even" rownum="0"><td style="text-align: center;" colspan="3">------û����ؼ�¼------</td></tr>
EOT;
			$mainCheck ='';
		}else{
			//��ѯ����ؼ�¼����Ϊδ����״̬������ʾ���ɱ�������ť
			if($status == '1'){
				$button = <<<EOT
					<input type="button" class="txt_btn_a" value="���ɱ�����" onclick="toEsmExpenseAdd();"/>
EOT;
			}
		}		
		$thead1 =<<<EOT
				$mainCheck
				<th rowspan="2" style="width: 100px;"><div style="min-width: 70px;" class="divChangeLine">ִ������</div></th>
				<th rowspan="2" style="width: 100px;"><div style="min-width: 80px;" class="divChangeLine">С��</div></th>
EOT;
		$thead2 =<<<EOT
		
EOT;
		foreach($headArr as $k => $v){
			$thLength = count($v);
			$thead1 .=<<<EOT
				<th colspan="$thLength">$k</td>			
EOT;
			foreach($v as $ik){
				$thead2 .=<<<EOT
				<th>$ik</td>
EOT;
			}
		}
		$str = <<<EOT
			<thead>
				<tr class="main_tr_header">
					<td colspan="10" style="text-align: left;">
						<b>��ϸ���ñ���</b>
						&nbsp;&nbsp;<b>����״̬</b>
						<select id="status" onchange="changeStatus();">
						<option value="1">δ����</option>
						<option value="3">�ڱ���</option>
						<option value="4">�ѱ���</option>
						</select>
						$button
					</td>
				</tr>
				<tr class="main_tr_header">
					$thead1
				</tr>
				<tr class="main_tr_header">
					$thead2
				</tr>
			</thead>
			<tbody>
				$trStr
			</tbody>
EOT;
		return $str;
    }
}