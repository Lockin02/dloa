<?php

/**
 * @author Show
 * @Date 2011��5��21�� ������ 14:47:06
 * @version 1.0
 * esm ��������Ŀ���ñ���
 * trp ��������Ŀ���ñ���
 */
class model_finance_expense_expense extends model_base
{

	function __construct() {
		$this->tbl_name = "cost_summary_list";
		$this->sql_map = "finance/expense/expenseSql.php";
		parent::__construct();
	}

	// ��˾Ȩ�޴��� TODO
	protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

	// ���ñ�������
	private $detailTypeArr = array(
		'1' => '���ŷ���',
		'2' => '��ͬ��Ŀ����',
		'3' => '�з�����',
		'4' => '��ǰ����',
		'5' => '�ۺ����'
	);

	// ���ط�������
	function rtDetailType($thisVal) {
		if (isset($this->detailTypeArr[$thisVal])) {
			return $this->detailTypeArr[$thisVal];
		} else {
			return $thisVal;
		}
	}

	// �����ֵ�����
	public $datadictFieldArr = array('module');

	// ���һ��������֤
	function checkform_d($object) {
        // -- �����ܽ�����̯�ܽ����֤ -- //
        $expensedetail = $expensecostshare = array();
        $expenseCost = $costshareCost = 0;
        $newExpensecostshareObj = array();
        foreach ($object['expensedetail'] as $k => $v){
            if(!isset($v['isDelTag']) || $v['isDelTag'] != '1'){
                foreach ($v['expenseinv'] as $ev){
                    if($ev['isDelTag'] != '1'){
                        $expensedetail[] = $ev;
                        $expenseCost = bcadd($expenseCost, $ev['Amount'], 2);
//                    echo $ev['Amount']."<br>";
                    }
                }
            }
        }
        foreach ($object['expensecostshare'] as $k => $v){
            if(!isset($v['ID']) && !isset($v['isDelTag']) || $v['isDelTag'] != '1'){
                $newExpensecostshareObj[$k] = $v;
            }else if(isset($v['ID'])){
                $newExpensecostshareObj[$k] = $v;
            }
            if(!isset($v['isDelTag']) || $v['isDelTag'] != '1'){
                $expenseinv = array();
                foreach ($v['expenseinv'] as $ev){
                    if(!isset($ev['ID']) && !isset($ev['isDelTag']) || $ev['isDelTag'] != '1'){
                        $expenseinv[] = $ev;
                    }else if(isset($ev['ID'])){
                        $expenseinv[] = $ev;
                    }
                    if($ev['isDelTag'] != '1'){
                        $expensecostshare[] = $ev;
                        $costshareCost = bcadd($costshareCost, $ev['CostMoney'], 2);
//                        echo $ev['CostMoney']."<br>";
                    }
                }
                $newExpensecostshareObj[$k]['expenseinv'] = $expenseinv;
            }
        }

        if($expenseCost != $costshareCost){
//             echo "expenseCost: ".$expenseCost." costshareCost: ".$costshareCost;exit();
            return '����ʧ�ܣ�������Ϣ�ܽ����ڷ�̯��Ϣ�ܽ�';
        }else{
            $object['expensecostshare'] = $newExpensecostshareObj;
        }
        // -- �����ܽ�����̯�ܽ����֤ -- //

		//��������
		if (trim($object['DetailType']) == "") {
			return '����ʧ�ܣ�����û��ѡ��������ͣ�';
		}

		//�����ڼ����֤
		if ($object['CostDateBegin'] == "" || $object['CostDateEnd'] == "" || $object['days'] == "") {
			return '����ʧ�ܣ������ڼ���Ϣ������';
		}

		//����
		if (trim($object['Purpose']) == "") {
			return '����ʧ�ܣ�û����д��������';
		}

		//������Ա
		if (trim($object['CostManName']) == "") {
			return '����ʧ�ܣ�û��ѡ������Ա';
		}

		if (trim($object['CostManCom']) == '' && trim($object['CostBelongCom']) == "") {
			return '����ʧ�ܣ�û����д���ù�����˾';
		} else if (trim($object['CostBelongCom']) == "") {
            $object['CostBelongCom'] = $object['CostManCom'];
            $object['CostBelongComId'] = $object['CostManComId'];
        }

		if (trim($object['CostBelongDeptName']) == "") {
			return '����ʧ�ܣ�û����д���ù�������';
		}

		//�����Ӧ��֤
		switch ($object['DetailType']) {
			case '1' :
				//������Ҫ��յ�����
				$object['contractId'] = '';
				$object['contractCode'] = '';
				$object['contractName'] = '';
				$object['chanceId'] = '';
				$object['chanceCode'] = '';
				$object['chanceName'] = '';
				$object['customerName'] = '';
				$object['customerId'] = '';
				$object['CustomerType'] = '';
				if ($object['projectName'] == '') {
					$object['projectId'] = '';
					$object['ProjectNo'] = '';
					$object['projectName'] = '';
					$object['projectType'] = '';
					$object['proManagerId'] = '';
					$object['proManagerName'] = '';
				}
				$object['city'] = '';
				if (!$object['deptIsNeedProvince']) {
					$object['province'] = '';
				}
				break;
			case '2' :
				//������Ŀ
				if (!$object['projectId']) {
					return '����ʧ�ܣ�û����ȷѡ����Ŀ����������д';
				}
				if (trim($object['projectName']) == "") {
					return '����ʧ�ܣ�û��ѡ��ñʷ������ڹ�����Ŀ';
				}
				if (trim($object['CostBelongDeptName']) == "") {
					return '����ʧ�ܣ�û����д���ù�������';
				}
				//������Ҫ��յ�����
				$object['contractId'] = '';
				$object['contractCode'] = '';
				$object['contractName'] = '';
				$object['chanceId'] = '';
				$object['chanceCode'] = '';
				$object['chanceName'] = '';
				$object['customerName'] = '';
				$object['customerId'] = '';
				$object['CustomerType'] = '';
				if (!$object['deptIsNeedProvince']) {
					$object['province'] = '';
					$object['city'] = '';
				}
				break;
			case '3' :
				// ��Ŀid
				if (!$object['projectId']) {
					return '����ʧ�ܣ�û����ȷѡ����Ŀ����������д';
				}
				//�з���Ŀ
				if (trim($object['projectName']) == "") {
					return '����ʧ�ܣ�û��ѡ��ñʷ��������з���Ŀ';
				}
				if (trim($object['CostBelongDeptName']) == "") {
					return '����ʧ�ܣ�û����д���ù�������';
				}
				//������Ҫ��յ�����
				$object['contractId'] = '';
				$object['contractCode'] = '';
				$object['contractName'] = '';
				$object['chanceId'] = '';
				$object['chanceCode'] = '';
				$object['chanceName'] = '';
				$object['customerName'] = '';
				$object['customerId'] = '';
				$object['CustomerType'] = '';
				if (!$object['deptIsNeedProvince']) {
					$object['province'] = '';
					$object['city'] = '';
				}
				break;
			case '4' :
				//ʡ��
				if (trim($object['province']) == "") {
					return '����ʧ�ܣ�û��ѡ��ͻ�����ʡ��';
				}
				//����
				if (is_array($object['city'])) {
					$object['city'] = implode(',', $object['city']);
				}
				if (trim($object['city']) == "") {
					return '����ʧ�ܣ�û��ѡ��ͻ����ڳ���';
				}
				//��ѡ��ͻ�����
				if (trim($object['CustomerType']) == "") {
					return '����ʧ�ܣ�û��ѡ��ͻ�����';
				}
				//��ѡ��ͻ�����
				if (trim($object['CostBelonger']) == "") {
					return '����ʧ�ܣ�û��¼�����۸����ˣ����۸����˿����̻����ͻ������Զ�����������ͨ���ͻ�ʡ�ݡ����С�������ϵͳ�Զ�ƥ��';
				}
				//��������
				if ($object['CostBelongDeptId'] == "" || $object['CostBelongDeptName'] == "") {
					return '����ʧ�ܣ�û��ѡ����ù�������';
				}
				//������Ҫ��յ�����
				$object['contractId'] = '';
				$object['contractCode'] = '';
				$object['contractName'] = '';
				break;
			case '5' :
				if ($object['contractCode'] == "") {
					return '����ʧ�ܣ�û��ѡ��ñʷ��ù�����ͬ';
				}
				//��ѡ��ͻ�����
				if (trim($object['CostBelonger']) == "") {
					return '����ʧ�ܣ�û��¼�����۸����ˣ����۸�����Ϊ���ù�����';
				}
				//��������
				if ($object['CostBelongDeptId'] == "" || $object['CostBelongDeptName'] == "") {
					return '����ʧ�ܣ�û��ѡ����ù�������';
				}
				//������Ҫ��յ�����
				$object['chanceId'] = '';
				$object['chanceCode'] = '';
				$object['chanceName'] = '';
				$object['projectId'] = '';
				$object['ProjectNo'] = '';
				$object['projectName'] = '';
				$object['projectType'] = '';
				$object['proManagerId'] = '';
				$object['proManagerName'] = '';
				break;
		}
		return $object;
	}

	/********************* �ڲ���ɾ��� *******************/
	/**
	 * ��дadd_d
	 * @param $object
	 * @return array|bool|string
	 */
	function add_d($object,$deptId = '') {
		$object = $this->checkform_d($object);
		if (!is_array($object)) {//���ݴ���ʱ���ش�����Ϣ
			return $object;
		}

		//���δ���״̬
		$thisAuditType = $object['thisAuditType'];
		unset($object['thisAuditType']);

		//��ȡ������ϸ��Ϣ
		$expensedetail = $object['expensedetail'];
		unset($object['expensedetail']);

		//��ȡ��̯��ϸ��Ϣ
		$expensecostshare = $object['expensecostshare'];
		unset($object['expensecostshare']);

		//״̬����
		$object['Status'] = $thisAuditType == 'check' ? '���ż��' : '�༭';
		$object['ExaStatus'] = '�༭';

		//�ж��Ƿ���Ŀ����
		$object['CostDates'] = $object['CostDateBegin'] . '~' . $object['CostDateEnd'];
		$object['CostClientType'] = $object['DetailType'] == 4 || $object['DetailType'] == 5 ? $object['CustomerType'] : $object['Purpose'];
		$object['CostBelongtoDeptIds'] = $object['CostBelongDeptName'];

		//������������
		$object['xm_sid'] = 1;
		$object['isNew'] = 1;
		$object['InputDate'] = $object['UpdateDT'] = date('Y-m-d H:i:s');
		$object['CheckAmount'] = $object['Amount'];
		$object['Area'] = $_SESSION['AREA'];
		//������ύ���,�����ύ���ʱ��
		if ($thisAuditType == 'check') {
			$object['subCheckDT'] = $object['InputDate'];
		}

		//�ж��Ƿ��ӳٱ���
		$object['isLate'] = abs((strtotime(date('Y-m-d', strtotime($object['InputDate']))) -
                strtotime($object['CostDateEnd'])) / 86400) > ISLATE ? 1 : 0;

		//������ϸ��ʵ����
		$expensedetailDao = new model_finance_expense_expensedetail();
		//��̯��ϸ��ʵ����
		$expensecostshareDao = new model_finance_expense_expensecostshare();
		//����cost_detail_list
		$expenselistDao = new model_finance_expense_expenselist();
		//����cost_detail_list
		$expenseassDao = new model_finance_expense_expenseass();

        // ����������Ϊ���򲹳���Ӧ�İ������ created by huanghaojin 16-10-25 2153 (ԭ����ģ������ǰ�����������ֶ����ݱ������ݲ���ID��̬���µ�)
        $deptDao = new model_deptuser_dept_dept();
        $deptId = $object['CostBelongDeptId'];
        $deptRow = $deptDao->find(array('DEPT_ID' => $deptId));
        $object['module'] = $deptRow['module'];


		//��ʼ�������ݴ���
		try {
			$this->start_d();

			//�Զ�����ϵͳ��źͱ�״̬
			$codeRuleDao = new model_common_codeRule();
            $deptId = ($deptId == '')? $_SESSION['DEPT_ID'] : $deptId;
			$object['BillNo'] = $codeRuleDao->expenseCode('expense', $deptId);

			//�����������
			$object = $this->processDatadict($object);
			$newId = parent::add_d($object);

			//���뱨����ϸ����
			$headObj = $object;
			$headObj['ProjectNO'] = $object['ProjectNo'];
			$headId = $expenselistDao->add_d($headObj);

			//�������������
			$assObj = $object;
			$assObj['HeadID'] = $headId;
			$assObj['RNo'] = 1;
			$assId = $expenseassDao->add_d($assObj);

			//������ϸ���ô���
			//�������ݸ���
			$addArr = array(
				'BillNo' => $object['BillNo'],
				'RNo' => 1,
				'HeadID' => $headId,
				'AssID' => $assId
			);
			$expensedetail = util_arrayUtil::setArrayFn($addArr, $expensedetail);
			$expensecostshare = util_arrayUtil::setArrayFn($addArr, $expensecostshare);
			//���������ϸ
			$expensedetailDao->saveDelBatch($expensedetail);
			//�����̯��ϸ
			$expensecostshareDao->saveDelBatch($expensecostshare);

			//��Ʊ��
			$exbillDao = new model_finance_expense_exbill();
			$exbillDao->addSummary_d($object['BillNo'], $codeRuleDao);

			//��������Ƶ��ݣ��������Ŀ��Ϣ����
			if ($object['isPush'] == 1) {
				if ($object['esmCostdetailId']) {
					$esmcostdetailDao = new model_engineering_cost_esmcostdetail();
					$esmcostdetailDao->updateCost_d($object['esmCostdetailId'], '3');

					$esmcostdetailInvDao = new model_engineering_cost_esminvoicedetail();
					$esmcostdetailInvDao->updateCostInvoice_d($object['esmCostdetailId'], '3');

					//������Ա����Ŀ����
					if ($object['projectId']) {
						//��ȡ��ǰ��Ŀ�ķ���
						$projectCountArr = $esmcostdetailDao->getCostFormMember_d($object['projectId'], $object['CostMan']);

						//������Ա������Ϣ
						$esmmemberDao = new model_engineering_member_esmmember();
						$esmmemberDao->update(
							array('projectId' => $object['projectId'], 'memberId' => $object['CostMan']),
							$projectCountArr
						);
					}
				}
			}

			//���¸���������ϵ
			$this->updateObjWithFile($newId, $object['BillNo']);

			//��ȡ�ر�����������ر������
			$specialApplyArr = $expensedetailDao->getSpecialApplyNos_d($object['BillNo']);
			if ($specialApplyArr) {//��������ر������,��
				//��ȡ���뵥ʹ�����
				$specialApplyArr = $expensedetailDao->getSpecialApplyTimes_d($specialApplyArr);
				//�����ر����뵥
				$specialApplyDao = new model_general_special_specialapply();
				$specialApplyDao->calUsedTimes_d($specialApplyArr);
			}

			$object['id'] = $newId;
			$this->commit_d();
		} catch (Exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}

		//�ʼ�֪ͨ����
		$this->expenseMail_d($object, $thisAuditType);

		return $object;
	}

	/**
	 * �����ʼ�����
	 * @param $object
	 * @param string $thisAuditType
	 */
	function expenseMail_d($object, $thisAuditType = 'check') {
		$content = null;
		$title = null;
		$tomail = null;

		//�����ύ���
		if ($thisAuditType == 'check') {
			//����
			$content = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;" . $_SESSION["USERNAME"] . "�ѽ��������ύ���ż��,���½OA����ȷ�ϣ�<br />&nbsp;&nbsp;&nbsp;&nbsp;�������ţ�" . $object['BillNo'] . "<br />&nbsp;&nbsp;&nbsp;&nbsp;";
			//����
			$title = "OA-�������ύ���֪ͨ:" . $object['BillNo'];
			//�ռ���
			include(WEB_TOR . "model/common/mailConfig.php");
			$tomail = isset($mailUser[$this->tbl_name]) ? $mailUser[$this->tbl_name]['TO_ID'] : '';
		} else if ($thisAuditType == 'needConfirm') {
			//����
			$content = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;" . $_SESSION["USERNAME"] . "�����˱������Ľ��,���½OA����ȷ�ϣ�<br />&nbsp;&nbsp;&nbsp;&nbsp;�������ţ�" . $object['BillNo'] . "<br />&nbsp;&nbsp;&nbsp;&nbsp;";
			//����
			$title = "OA-�������ȴ�ȷ��֪ͨ:" . $object['BillNo'];
			//�ռ���
			$tomail = $object['InputMan'];
		}

		$mailDao = new model_common_mail();
		$mailDao->mailClear($title, $tomail, $content);
	}

	/**
	 * ��дedit
	 * ����Ǳ༭״̬������true,������ύ����������$summaryId
	 * @param $object
	 * @return array|bool|string
	 */
	function edit_d($object) {
		$object = $this->checkform_d($object);
		if (!is_array($object)) {
			return $object;
		}

		//�����в��ż�鹦�� - ������ȴ���
		$object['CheckAmount'] = $object['Amount'];

		//���δ���״̬
		$thisAuditType = $object['thisAuditType'];
		unset($object['thisAuditType']);
		if ($thisAuditType == 'check') {
			$object['Status'] = '���ż��';
		} else if ($thisAuditType == 'needConfirm') {
			$object['Status'] = '�ȴ�ȷ��';
			unset($object['Amount']);
		}

		//��ȡ������ϸ��Ϣ
		$expensedetail = $object['expensedetail'];
		unset($object['expensedetail']);

		//��ȡ��̯��ϸ��Ϣ
		$expensecostshare = $object['expensecostshare'];
		unset($object['expensecostshare']);

		//�ж��Ƿ���Ŀ����
		$object['CostClientType'] = $object['DetailType'] == 4 || $object['DetailType'] == 5 ? $object['CustomerType'] : $object['Purpose'];
		$object['CostBelongtoDeptIds'] = $object['CostBelongDeptName'];

		//������������
		$object['UpdateDT'] = date('Y-m-d H:i:s');
		$object['CostDates'] = $object['CostDateBegin'] . '~' . $object['CostDateEnd'];
		//������ύ���,�����ύ���ʱ��
		if ($thisAuditType == 'check') {
			$object['subCheckDT'] = $object['InputDate'];
		}

		//�ж��Ƿ��ӳٱ���
		$object['isLate'] = abs((strtotime(date('Y-m-d', strtotime($object['InputDate']))) - strtotime($object['CostDateEnd'])) / 86400) > ISLATE ? 1 : 0;

		//������ϸ��ʵ����
		$expensedetailDao = new model_finance_expense_expensedetail();
		//��̯��ϸ��ʵ����
		$expensecostshareDao = new model_finance_expense_expensecostshare();
		//����cost_detail_list
		$expenselistDao = new model_finance_expense_expenselist();
		//����cost_detail_list
		$expenseassDao = new model_finance_expense_expenseass();

        // ����������Ϊ���򲹳���Ӧ�İ������ created by huanghaojin 16-10-25 2153 (ԭ����ģ������ǰ�����������ֶ����ݱ������ݲ���ID��̬���µ�)
        $deptDao = new model_deptuser_dept_dept();
        $deptId = $object['CostBelongDeptId'];
        $deptRow = $deptDao->find(array('DEPT_ID' => $deptId));
        $object['module'] = $deptRow['module'];

		//��ʼ�������ݴ���
		try {
			$this->start_d();

			//��ȡ�ر�����������ر������
			$oldSpecialApplyArr = empty($object['specialApplyNos']) ? array() : explode(',', $object['specialApplyNos']);
			unset($object['specialApplyNos']);

			//�����������
			$object = $this->processDatadict($object);
			parent::edit_d($object);

			//���뱨����ϸ����
			$headObj = $object;
			$headObj['ProjectNO'] = $object['ProjectNo'];
			$expenselistDao->edit_d($headObj);

			//�������������
			$assObj = $object;
			$assObj['id'] = $object['AssID'];
			$expenseassDao->edit_d($assObj);

			//������ϸ���ô���
			$addArr = array(
				'RNo' => 1,
				'HeadID' => $object['HeadID'],
				'AssID' => $object['AssID'],
				'BillNo' => $object['BillNo']
			);
			$expensedetail = util_arrayUtil::setArrayFn($addArr, $expensedetail);
			$expensecostshare = util_arrayUtil::setArrayFn($addArr, $expensecostshare);
			//���������ϸ
			$expensedetailDao->saveDelBatch($expensedetail);
			//�����̯��ϸ
			$expensecostshareDao->saveDelBatch($expensecostshare);

			//��ȡ�ر�����������ر������
			$specialApplyArr = $expensedetailDao->getSpecialApplyNos_d($object['BillNo']);
			$specialApplyArr = array_unique(array_merge($specialApplyArr, $oldSpecialApplyArr));
			if ($specialApplyArr) {
				//��ȡ���뵥ʹ�����
				$specialApplyArr = $expensedetailDao->getSpecialApplyTimes_d($specialApplyArr);
				//�����ر����뵥
				$specialApplyDao = new model_general_special_specialapply();
				$specialApplyDao->calUsedTimes_d($specialApplyArr);
			}

			$this->commit_d();
		} catch (Exception $e) {
			$this->rollBack();
			echo $e->getMessage();
			return false;
		}

		//�ʼ�֪ͨ����
		$this->expenseMail_d($object, $thisAuditType);

		return $object;
	}

	/**
	 * ��ȡҵ����Ϣ
	 * @param $id
	 * @return bool|mixed
	 */
	function getInfo_d($id) {
		//��ȡ����λ��Ϣ
		$obj = parent::get_d($id);

		//������ϸ��ʵ����
		$expensedetailDao = new model_finance_expense_expensedetail();
		$obj['expensedetail'] = $expensedetailDao->findAll(array('BillNo' => $obj['BillNo']), 'MainTypeId');

		//����cost_detail_list
		$expenselistDao = new model_finance_expense_expenselist();
		$expenselistArr = $expenselistDao->find(array('BillNo' => $obj['BillNo']), null, 'HeadID');
		$obj['HeadId'] = $expenselistArr['HeadID'];
		$obj['specialApplyNos'] = implode(',', $expensedetailDao->getSpecialApplyNos_d($obj['BillNo']));

		//����cost_detail_list
		$expenseassDao = new model_finance_expense_expenseass();
		$expenseassArr = $expenseassDao->find(array('BillNo' => $obj['BillNo']), null, 'ID');
		$obj['AssID'] = $expenseassArr['ID'];

		//��̯��ϸ��ʵ����
		$expensecostshareDao = new model_finance_expense_expensecostshare();
		$obj['expensecostshare'] = $expensecostshareDao->getBillDetail_d($obj['BillNo']);

		return $obj;
	}

	/**
	 * ���ߵ��ݱ�Ż�ȡ������Ϣ
	 * @param $BillNo
	 * @return bool|mixed
	 */
	function getByBillNo_d($BillNo) {
		$condition = array('BillNo' => $BillNo);
		return $this->find($condition);
	}

	/**
	 * ����ɾ������
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function deletes_d($id) {
		$object = $this->get_d($id);
		//����ǲ��Ϸ�ɾ�����򷵻�ʧ��
		if (empty($object) || $object['ExaStatus'] == AUDITING || $object['ExaStatus'] == AUDITED) {
			throw new Exception('Illegally deleted documents!');
		}

		//ʵ����������ϸ
		$expensedetailDao = new model_finance_expense_expensedetail();
		$specialApplyArr = $expensedetailDao->getSpecialApplyNos_d($object['BillNo']);

		try {
			$this->start_d();

			//ɾ��������
			$this->deletes($id);

			//ɾ��������ϸ
			$expensedetailDao->delete(array('BillNo' => $object['BillNo']));
			if (!empty($specialApplyArr)) {
				$specialApplyArr = $expensedetailDao->getSpecialApplyTimes_d($specialApplyArr);
				//�����ر����뵥
				$specialApplyDao = new model_general_special_specialapply();
				$specialApplyDao->calUsedTimes_d($specialApplyArr);
			}

			//ɾ����Ʊ��ϸ
			$expenseinvDao = new model_finance_expense_expenseinv();
			$expenseinvDao->delete(array('BillNo' => $object['BillNo']));

			//ɾ�����ܵ�
			$exbillDao = new model_finance_expense_exbill();
			$exbillDao->delete(array('ConBillNo' => $object['BillNo']));

			//��ϵ��
			$expenselistDao = new model_finance_expense_expenselist();
			$expenselistDao->delete(array('BillNo' => $object['BillNo']));

			//��ϵ��
			$expenseassDao = new model_finance_expense_expenseass();
			$expenseassDao->delete(array('BillNo' => $object['BillNo']));

			//���ҵ������
			//��������Ƶ��ݣ��������Ŀ��Ϣ����
			if ($object['isPush'] == 1) {
				if ($object['esmCostdetailId']) {
					$esmcostdetailDao = new model_engineering_cost_esmcostdetail();
					$esmcostdetailDao->updateCost_d($object['esmCostdetailId'], '1');

					$esmcostdetailInvDao = new model_engineering_cost_esminvoicedetail();
					$esmcostdetailInvDao->updateCostInvoice_d($object['esmCostdetailId'], '1');

					//������Ա����Ŀ����
					if ($object['projectId']) {
						//��ȡ��ǰ��Ŀ�ķ���
						$projectCountArr = $esmcostdetailDao->getCostFormMember_d($object['projectId'], $object['CostMan']);

						//������Ա������Ϣ
						$esmmemberDao = new model_engineering_member_esmmember();
						$esmmemberDao->update(
							array('projectId' => $object['projectId'], 'memberId' => $object['CostMan']),
							$projectCountArr
						);
					}
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ɾ�����ܱ�ʱ��ձ���Ϣ
	 * @param $BillNo
	 * @return bool
	 * @throws Exception
	 */
	function clearBillNoInfo_d($BillNo) {
		try {
			// ����
			$this->update(array('BillNo' => $BillNo), array('BillNo' => '', 'Status' => '�༭'));

			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * ���ż�� - �ύ����
	 * @param $id
	 * @return bool
	 */
	function ajaxHand_d($id) {
		$object = array(
			'id' => $id,
			'Status' => '���ż��',
			'subCheckDT' => date('Y-m-d H:i:s')
		);

		parent::edit_d($object);

		$obj = $this->find(array('id' => $id));
		//�ʼ�֪ͨ����
		$this->expenseMail_d($obj);

		return true;
	}

	/**
	 * ���ż�� - ��ص���
	 * @param $id
	 * @return bool
	 */
	function ajaxBack_d($id) {
		$object = array(
			'id' => $id,
			'Status' => '�༭',
			'ExaStatus' => '�༭'
		);
		return parent::edit_d($object);
	}

	/**
	 * ���ż���б� - �����յ�
	 * @param $id
	 * @return bool
	 */
	function ajaxDeptRec_d($id) {
		$object = array(
			'id' => $id,
			'isNotReced' => '0',
			'RecInvoiceDT' => date('Y-m-d H:i:s')
		);
		return parent::edit_d($object);
	}

	/**
	 * ���ż���б� - �ύ����
	 * @param $id
	 * @return bool
	 */
	function ajaxHandFinance_d($id) {
		$object = array(
			'id' => $id,
			'isHandUp' => '1',
			'HandUpDT' => date('Y-m-d H:i:s')
		);
		return parent::edit_d($object);
	}

	/**
	 * ��ȡ����˵������
	 */
	function getFile_d() {
		$managentDao = new model_file_uploadfile_management();
		$fileRs = $managentDao->find(array('serviceId' => 1, 'serviceType' => 'expenseselect'), null, 'id');
		if ($fileRs) {
			return "?model=file_uploadfile_management&action=toDownFileById&fileId=" . $fileRs['id'];
		} else {
			return '#';
		}
	}

	/**
	 * �յ����÷���
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function receiveForm($id) {
		try {
			//����
			return $this->update(array('id' => $id), array('IsFinRec' => '1', 'FinRecDT' => date('Y-m-d H:i:s')));
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * �˵����÷���
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function backForm($id) {
		try {
			//����
			return $this->update(array('id' => $id), array('IsFinRec' => '0'));
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * �ύ����ȷ��
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function handConfirm_d($id) {
		$obj = $this->find(array('id' => $id), null, 'CheckAmount,BillNo,InputMan');
		$rs = true;
		try {
			//����
			$this->update(array('id' => $id), array('Status' => '�ȴ�ȷ��'));
		} catch (Exception $e) {
			throw $e;
		}

		//������³ɹ��������ʼ�
		if ($rs) {
			//����
			$content = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;" . $_SESSION["USERNAME"] . "�����˱������Ľ��,���½OA����ȷ�ϣ�<br />&nbsp;&nbsp;&nbsp;&nbsp;�������ţ�" . $obj['BillNo'] . "<br />&nbsp;&nbsp;&nbsp;&nbsp;";
			//����
			$title = "OA-�������ȴ�ȷ��֪ͨ:" . $obj['BillNo'];
			//�ռ���
			$tomail = $obj['InputMan'];

			$mailDao = new model_common_mail();
			$mailDao->mailClear($title, $tomail, $content);
		}
		return true;
	}

	/**
	 * ȷ�ϵ���
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function confirmCheck_d($id) {
		$obj = $this->find(array('id' => $id), null, 'CheckAmount,BillNo,InputMan');

		$rs = true;
		try {
			//����
			$this->update(array('id' => $id), array('Status' => '���ż��', 'Amount' => $obj['CheckAmount']));
		} catch (Exception $e) {
			throw $e;
		}

		//������³ɹ��������ʼ�
		if ($rs) {
			//����
			$content = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;�������ѱ�" . $_SESSION["USERNAME"] . "ȷ��,���½OA����ȷ�ϣ�<br />&nbsp;&nbsp;&nbsp;&nbsp;�������ţ�" . $obj['BillNo'] . "<br />&nbsp;&nbsp;&nbsp;&nbsp;";
			//����
			$title = "OA-������ȷ��֪ͨ:" . $obj['BillNo'];

			//�ռ���
			include(WEB_TOR . "model/common/mailConfig.php");
			$tomail = isset($mailUser[$this->tbl_name]) ? $mailUser[$this->tbl_name]['TO_ID'] : '';
			$mailDao = new model_common_mail();
			$mailDao->mailClear($title, $tomail, $content);
		}
		return true;
	}

	/**
	 * ���ϵ���
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
	function unconfirmCheck_d($id) {
		$obj = $this->find(array('id' => $id), null, 'CheckAmount,BillNo,InputMan');

		$rs = true;
		try {
			//����
			$this->update(array('id' => $id), array('Status' => '���ż��'));
		} catch (Exception $e) {
			throw $e;
		}

		//������³ɹ��������ʼ�
		if ($rs) {
			//����
			$content = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;�������ѱ�" . $_SESSION["USERNAME"] . "<font color='red'>����</font>,���½OA����ȷ�ϣ�<br />&nbsp;&nbsp;&nbsp;&nbsp;�������ţ�" . $obj['BillNo'] . "<br />&nbsp;&nbsp;&nbsp;&nbsp;";
			//����
			$title = "OA-������ȷ��֪ͨ:" . $obj['BillNo'];

			//�ռ���
			include(WEB_TOR . "model/common/mailConfig.php");
			$tomail = isset($mailUser[$this->tbl_name]) ? $mailUser[$this->tbl_name]['TO_ID'] : '';
			$mailDao = new model_common_mail();
			$mailDao->mailClear($title, $tomail, $content);
		}
		return true;
	}

	/**
	 * ��æ�����һ�Ҫ��ȡʡ��
	 * @param $province
	 * @return string
	 */
	function getProvince_d($province) {
		$provinceDao = new model_system_procity_province();
		$provinceRows = $provinceDao->findAll();

		if ($province) {
			$str = '<option value=""></option>';
		} else {
			$str = '<option value="" select="selected"></option>';
		}
		foreach ($provinceRows as $key => $val) {
			if ($province == $val['provinceName']) {
				$str .= '<option value="' . $val['provinceName'] . '" selected="selected">' . $val['provinceName'] . '</option>';
			} else {
				$str .= '<option value="' . $val['provinceName'] . '">' . $val['provinceName'] . '</option>';
			}
		}
		return $str;
	}

	/**
	 * ��æ�����һ�Ҫ��ȡ����
	 * @param $city
	 * @return string
	 */
	function getCity_d($city) {
		if ($city) {
			$cityDao = new model_system_procity_city();
			$cityRows = $cityDao->findAll();

			$str = '<option value=""></option>';
			foreach ($cityRows as $key => $val) {
				if ($city == $val['cityName']) {
					$str .= '<option value="' . $val['cityName'] . '" selected="selected">' . $val['cityName'] . '</option>';
				} else {
					$str .= '<option value="' . $val['cityName'] . '">' . $val['cityName'] . '</option>';
				}
			}
			return $str;
		} else {
			return '<option value="" select="selected"></option><option value="����ѡ��ʡ��">����ѡ��ʡ��</option>';
		}
	}

	/**
	 * Ҫ��ȡ������Ϣ�ˡ�����
	 * @param null $userId
	 * @param null $branchDao
	 * @return int
	 */
	function needExpenseCheck_d($userId = null, $branchDao = null) {
		//��Ա�ж�
		$userId = $userId ? $userId : $_SESSION['USER_ID'];

		$userDao = new model_deptuser_user_user();
		$userInfo = $userDao->getUserById($userId);

		//�ж�
		if (empty($branchDao)) {
			$branchDao = new model_deptuser_branch_branch();
		}
		$branchInfo = $branchDao->find(array('NamePT' => $_SESSION['Company']), null, 'needExpenseCheck');

		if ($userInfo['needExpenseCheck'] == 1 && $branchInfo['needExpenseCheck']) {
			return $userInfo['needExpenseCheck'];
		} else {
			return 0;
		}
	}

	/**
	 * ���¼��㱨��������
	 * @param $id
	 * @param null $expensedetailDao
	 * @param null $expenseinvDao
	 * @return bool
	 * @throws Exception
	 */
	function recountExpense_d($id, $expensedetailDao = null, $expenseinvDao = null) {
		try {
			$this->start_d();

			//��ѯ��������
			$obj = $this->find(array('ID' => $id), null, 'BillNo');

			//�ж��Ƿ�����ʵ����������ϸ
			if (!$expensedetailDao) {
				$expensedetailDao = new model_finance_expense_expensedetail();
			}
			$expensedetailArr = $expensedetailDao->findAll(array('BillNo' => $obj['BillNo']), null, 'id,esmCostdetailId,CostMoney,CostTypeID,days');

			//��Ʊ���ִ���
			if (!$expenseinvDao) {
				$expenseinvDao = new model_finance_expense_expenseinv();
			}

			//��ѯ��ǰ���ڵĲ�������
			$costTypeDao = new model_finance_expense_costtype();
			$subsidyArr = $costTypeDao->getIsSubsidy_d();

			//���������ϸid
			$esmIdsArr = array();
			//���ݽ��
			$formMoney = $invoiceMoney = $invoiceNumber = $feeRegular = $feeSubsidy = 0;

			//ѭ���ع�esmCostdetailId
			foreach ($expensedetailArr as $key => $val) {
				if ($val['esmCostdetailId']) {
					//����id
					$esmIdsArr = array_merge($esmIdsArr, explode(',', $val['esmCostdetailId']));
				}
				//���
				$formMoney = bcadd($formMoney, bcmul($val['CostMoney'], $val['days'], 2), 2);
				//��������Լ���������
				if (in_array($val['CostTypeID'], $subsidyArr)) {
					$feeSubsidy = bcadd($feeSubsidy, bcmul($val['CostMoney'], $val['days'], 2), 2);
				} else {
					$feeRegular = bcadd($feeRegular, bcmul($val['CostMoney'], $val['days'], 2), 2);
				}

				//ɾ���Ѿ������ڵķ�Ʊ����
				if ($val['esmCostdetailId']) {
					$expenseinvDao->clearInvoice_d($obj['BillNo'], $val['esmCostdetailId'], $val['id']);
				}
			}

			$expenseinvArr = $expenseinvDao->findAll(array('BillNo' => $obj['BillNo']), null, 'Amount,days,invoiceNumber,isSubsidy');
			foreach ($expenseinvArr as $key => $val) {
				if ($val['isSubsidy'] == "0") {
					//��Ʊ���
					$invoiceMoney = bcadd($invoiceMoney, $val['Amount'], 2);
					//��Ʊ����
					$invoiceNumber = bcadd($invoiceNumber, $val['invoiceNumber'], 2);
				}
			}

			//���µ�������
			$this->update(array('ID' => $id), array(
				'esmCostdetailId' => implode(',', $esmIdsArr),
				'Amount' => $formMoney,
				'CheckAmount' => $formMoney,
				'feeSubsidy' => $feeSubsidy,
				'feeRegular' => $feeRegular,
				'invoiceMoney' => $invoiceMoney,
				'invoiceNumber' => $invoiceNumber
			));

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/******************* ������Ŀ���� *******************/
	/**
	 * �������ݻ�ȡ
	 * @param $projectId
	 * @return bool|mixed
	 */
	function getEsmInfo_d($projectId) {
		$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectObj = $esmprojectDao->get_d($projectId);
		$esmprojectObj['projectId'] = $esmprojectObj['id'];
		unset($esmprojectObj['id']);

		//����������Ŀ�����ݻ�ȡ
		if ($esmprojectObj['contractType'] == 'GCXMYD-04') {
			//������Ŀ
			$trialprojectDao = new model_projectmanagent_trialproject_trialproject();
			$trialprojectObj = $trialprojectDao->find(array('id' => $esmprojectObj['contractId']));

			//���������Ŀ�����̻����
			if ($trialprojectObj['chanceCode']) {
				$chanceDao = new model_projectmanagent_chance_chance();
				$chanceObj = $chanceDao->find(array('id' => $trialprojectObj['chanceId']));

				$esmprojectObj['salePerson'] = $chanceObj['prinvipalName'];
				$esmprojectObj['salePersonId'] = $chanceObj['prinvipalId'];
				$esmprojectObj['salePersonDept'] = $chanceObj['prinvipalDept'];
				$esmprojectObj['salePersonDeptId'] = $chanceObj['prinvipalDeptId'];
				$esmprojectObj['chanceId'] = $chanceObj['id'];
				$esmprojectObj['chanceCode'] = $chanceObj['chanceCode'];
				$esmprojectObj['chanceName'] = $chanceObj['chanceName'];
			} else {
				$esmprojectObj['salePerson'] = $trialprojectObj['applyName'];
				$esmprojectObj['salePersonId'] = $trialprojectObj['applyNameId'];

				$userDao = new model_deptuser_user_user();
				$userObj = $userDao->getUserById($esmprojectObj['salePersonId']);
				$esmprojectObj['salePersonDept'] = $userObj['DEPT_NAME'];
				$esmprojectObj['salePersonDeptId'] = $userObj['DEPT_ID'];

				$esmprojectObj['chanceId'] = '';
				$esmprojectObj['chanceCode'] = '';
				$esmprojectObj['chanceName'] = '';
			}

			//			echo "<pre>";
			//			print_r($chanceObj);
		}

		return $esmprojectObj;
	}

	/**
	 * ��ȡ��Ӧ�������ŵ���Ŀ������Ϣid
	 * @param $BillNo
	 * @return string
	 */
	function getEsmCostDetail_d($BillNo) {
		$rs = $this->findAll(array('BillNo' => $BillNo), null, 'esmCostdetailId');
		if ($rs) {
			//����id����
			$esmcostdetailArr = array();
			foreach ($rs as $key => $val) {
				array_push($esmcostdetailArr, $val['esmCostdetailId']);
			}
			return implode($esmcostdetailArr, ',');
		} else {
			return '';
		}
	}

	/**
	 * ����ģ����ȾTODO
	 * @param $expenseCostTypeArr
	 * @param $relDocType
	 * @return array
	 */
	function initEsmAdd_d($expenseCostTypeArr, $relDocType) {
		//��������
		$rtArr = array();

		if ($expenseCostTypeArr) {
			//��ȡ��Ӧ��Ʊ���
			$esmcostInvoiceDao = new model_engineering_cost_esminvoicedetail();

			//��ȡ��Ʊ����
			$sql = "select id,name from bill_type where TypeFlag=1 and closeflag=0";
			$billTypeArr = $this->_db->getArray($sql);

			//��ѯģ��С����
			$sql = "select CostTypeID as id, CostTypeName as name, showDays, isReplace, isEqu, invoiceType,
                invoiceTypeName, isSubsidy, budgetType, ParentCostType, ParentCostTypeID from cost_type where isNew = 1";
			$costTypeArr = $this->_db->getArray($sql);

			//ģ��ʵ�����ַ���
			$str = null;
			//�����ܽ�� ������
			$countMoney = $invoiceMoney = $invoiceNumber = $feeRegular = $feeSubsidy = 0;

			//��Ŀ���ü�¼id
			$costIdsArr = array();

			foreach ($expenseCostTypeArr as $k => $v) {
				//��ѯ����־�ڵĸ�����ý��
				$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';

				// ��������ת�� -- ����������Դ��������ת����
				//                $v = $this->fitCostType_d($costTypeArr, $v);

				foreach ($costTypeArr as $key => $val) {
					if ($v['costTypeId'] == $val['budgetType']) {
						$v['costTypeId'] = $val['id'];
						$v['costType'] = $val['name'];
						$v['parentCostType'] = $val['ParentCostType'];
						$v['parentCostTypeId'] = $val['ParentCostTypeID'];
						$v['times'] = 1;
						$v['invoiceType'] = $val['invoiceType'];
						$v['isReplace'] = $val['isReplace'];
						break;
						//                        return $v;
					}
				}
				//                return $v;

				//���÷�������Id
				$countI = $v['costTypeId'];

				if ($v['costIds'])
					array_push($costIdsArr, $v['costIds']);

				//��ȡƥ���������
				$thisCostType = $this->initExpenseEdit_d($costTypeArr, $v['costTypeId']);

				//�ж��Ƿ������ã��������Ӧ������Ϣ
				if ($thisCostType['isSubsidy']) {
					$feeSubsidy = bcadd($feeSubsidy, bcmul($v['costMoney'], $v['times'], 2), 2);
					$countMoney = bcadd($countMoney, bcmul($v['costMoney'], $v['times'], 2), 2);
				} else {
					$feeRegular = bcadd($feeRegular, $v['costMoney'], 2);
					$countMoney = bcadd($countMoney, $v['costMoney'], 2);
				}

				$str .= <<<EOT
	                <tr class="$trClass" id="tr$v[costTypeId]">
	                    <td valign="top" class="form_text_right">
	                        $v[parentCostType]
	                        <input type="hidden" name="expense[expensedetail][$countI][MainType]" value="$v[parentCostType]"/>
	                        <input type="hidden" name="expense[expensedetail][$countI][MainTypeId]" value="$v[parentCostTypeId]"/>
	                        <input type="hidden" name="expense[expensedetail][$countI][esmCostdetailId]" value="$v[costIds]"/>

	                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
	                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
	                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
	                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
	                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
	                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
	                    </td>
	                    <td valign="top" class="form_text_right">
	                        $v[costType]
	                        <input type="hidden" name="expense[expensedetail][$countI][costType]" id="costType$countI" value="$v[costType]"/>
	                        <input type="hidden" name="expense[expensedetail][$countI][CostTypeID]" id="costTypeId$countI" value="$v[costTypeId]"/>
	                    </td>
		                <td valign="top" class="form_text_right">
EOT;
				//�����Ҫ��ʾ����������ʾ
				if ($thisCostType['showDays']) {
					$str .= <<<EOT
						<span>
							<input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI"
                                   value="$v[costMoney]" class="readOnlyTxtShort formatMoney" style="width:60px"
                                   readonly="readonly"/>
							X
							����
							<input type="text" name="expense[expensedetail][$countI][days]" class="readOnlyTxtMin"
                                   id="days$countI" value="$v[times]" readonly="readonly"/>
						</span>
EOT;
				} else {
					$str .= <<<EOT
	                    <input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" value="$v[costMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						<input type="hidden" name="expense[expensedetail][$countI][days]" id="days$countI" value="1"/>
EOT;
				}
				$str .= <<<EOT
						</td>
	                    <td colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
EOT;

				if ($relDocType) {
					$billTypeStr = $this->initBillType_d($billTypeArr, null, $v['invoiceType'], $v['isReplace']);//ģ��ʵ�����ַ���
					$thisI = $countI . "_" . 0;
					$invoiceNumber = bcadd($invoiceNumber, 1);
					$invoiceMoney = bcadd($invoiceMoney, $v['costMoney'], 2);
					$str .= <<<EOT
                        <tr id="tr_$thisI">
                            <td width="30%">
                                <select id="select_$thisI" name="expense[expensedetail][$countI][expenseinv][0][BillTypeID]" style="width:90px"><option value="">��ѡ��Ʊ</option>$billTypeStr</select>
                            </td>
                            <td width="25%">
                                <input type="text" id="invoiceMoney_$thisI"
                                       name="expense[expensedetail][$countI][expenseinv][0][Amount]"
                                       costTypeId="$v[CostTypeID]" rowCount="$thisI"
                                       onblur="invMoneySet('$thisI');countInvoiceMoney();"
                                       class="txtshort formatMoney" value="$v[costMoney]"/>
                            </td>
                            <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort" value="1"/>
                                <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][0][isSubsidy]" value="0"/>
                            </td>
                            <td width="20%">
                                <img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice($countI)"/>
                            </td>
                        </tr>
EOT;
				} else {
					//��Ʊ����ѭ������
					$esmInvoiceArr = $esmcostInvoiceDao->getInvoice_d($v['costIds']);
					foreach ($esmInvoiceArr as $thisK => $thisV) {
						$billTypeStr = $this->initBillView_d($billTypeArr, $thisV['invoiceTypeId']);
						$invoiceNumber = bcadd($invoiceNumber, $thisV['invoiceNumber']);
						$invoiceMoney = bcadd($invoiceMoney, $thisV['invoiceMoney'], 2);

						//��Ʊ����
						$str .= <<<EOT
	                    <tr>
	                        <td width="40%">
	                            <input type="text" value="$billTypeStr" style="width:90px" class="readOnlyTxtShort" readonly="readonly"/>
	                            <input type="hidden" name="expense[expensedetail][$countI][expenseinv][$thisK][BillTypeID]" value="$thisV[invoiceTypeId]"/>
	                        </td>
	                        <td width="30%">
	                            <input type="text" name="expense[expensedetail][$countI][expenseinv][$thisK][Amount]" value="$thisV[invoiceMoney]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
	                        </td>
	                        <td width="30%">
	                            <input type="text" name="expense[expensedetail][$countI][expenseinv][$thisK][invoiceNumber]" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" readonly="readonly"/>
	                        </td>
						</tr>
EOT;
					}
				}

				//���ñ�ע���߶�
				$remarkHeight = isset($esmInvoiceArr) ? (count($esmInvoiceArr) - 1) * 33 + 20 . "px" : "20px";

				$str .= <<<EOT
	                        </table>
	                    </td>
	                    <td valign="top">
	                    	<input class="txtshort" id="specialApplyNo$countI" name="expense[expensedetail][$countI][specialApplyNo]" title="�����ر�����" onclick="showSpecialApply($countI)" readonly="readonly">
	                    </td>
		                <td valign="top">
	                    	<textarea name="expense[expensedetail][$countI][Remark]" style="height:$remarkHeight" id="remark$countI" class="txt">$v[remark]</textarea>
	                    </td>
	                </tr>
EOT;
			}
			$rtArr['expensedetail'] = $str;
			$rtArr['countMoney'] = $countMoney;
			$rtArr['feeRegular'] = $feeRegular;
			$rtArr['feeSubsidy'] = $feeSubsidy;
			$rtArr['invoiceMoney'] = $invoiceMoney;
			$rtArr['invoiceNumber'] = $invoiceNumber;
			$rtArr['esmCostdetailId'] = implode($costIdsArr, ',');
		}

		return $rtArr;
	}

	/**
	 * ����ģ����ȾTODO
	 * @param $expenseArr
	 * @return mixed
	 */
	function initEsmEdit_d($expenseArr) {

		//��ȡ��Ʊ����
		$sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
		$billTypeArr = $this->_db->getArray($sql);

		//��ѯģ��С����
		$sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type";
		$costTypeArr = $this->_db->getArray($sql);

		//ʵ�������÷�Ʊ��ϸ
		$expenseinvDao = new model_finance_expense_expenseinv();

		//ģ��ʵ�����ַ���
		$str = null;
		//�����ܽ��
		$countMoney = 0;
		foreach ($expenseArr['expensedetail'] as $k => $v) {
			$specialApplyNo = $v['specialApplyNo'];
			//���÷�������Id
			$countI = $v['CostTypeID'];
			//��ѯ����־�ڵĸ�����ý��
			$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
			$countMoney = bcadd($countMoney, bcmul($v['CostMoney'], $v['days'], 2), 2);

			//��ȡƥ���������
			$thisCostType = $this->initExpenseEdit_d($costTypeArr, $v['CostTypeID']);

			$str .= <<<EOT
                <tr class="$trClass" id="tr$v[CostTypeID]">
                    <td valign="top" class="form_text_right">
                        $v[MainType]
                        <input type="hidden" name="expense[expensedetail][$countI][MainType]" value="$v[MainType]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][MainTypeId]" value="$v[MainTypeId]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $thisCostType[name]
                        <input type="hidden" name="expense[expensedetail][$countI][costType]" id="costType$countI" value="$thisCostType[name]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][CostTypeID]" id="costTypeId$countI" value="$v[CostTypeID]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][ID]" value="$v[ID]"/>
                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
                    </td>
	                <td valign="top" class="form_text_right">
EOT;
			//�����Ҫ��ʾ����������ʾ
			if ($thisCostType['showDays']) {
				$str .= <<<EOT
						<span>
							<input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" value="$v[CostMoney]" class="readOnlyTxtShort formatMoney" style="width:60px" readonly="readonly"/>
							X
							����
							<input type="text" name="expense[expensedetail][$countI][days]" class="readOnlyTxtMin" id="days$countI" value="$v[days]" readonly="readonly"/>
						</span>
EOT;
			} else {
				$str .= <<<EOT
	                    <input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" value="$v[CostMoney]" style="width:146px" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						<input type="hidden" name="expense[expensedetail][$countI][days]" id="days$countI" value="$v[days]"/>
EOT;
			}
			$str .= <<<EOT
					</td>
                    <td colspan="4" class="innerTd">
                        <table class="form_in_table" id="table_$countI">
EOT;

			//��ȡ��Ʊ��Ϣ
			$expenseinvArr = $expenseinvDao->findAll(array('BillDetailID' => $v['ID'], 'BillNo' => $v['BillNo']));
			foreach ($expenseinvArr as $thisK => $thisV) {
				// �Ƿ���Ҫ��Ʊ
				if ($thisCostType['isSubsidy'] == 1 && $thisK == 0) {
					$billArr = $this->getBillArr_d($billTypeArr, $thisV['BillTypeID']);
					$thisI = $countI . "_" . $thisK;
					$str .= <<<EOT
	                    <tr id="tr_$thisI">
		                    <td width="30%">
                                <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
                                <input type="hidden" name="expense[expensedetail][$countI][expenseinv][0][BillTypeID]" value="$billArr[id]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[Amount]" class="txtshort formatMoney" style="color:gray" onblur="invMoneySet('$thisI');countAll();" title="�����෢Ʊ�����뵽���ݷ�Ʊ�����,ֻ���ڴ���ʾ"/>
	                            <input type="hidden" name="expense[expensedetail][$countI][expenseinv][$thisK][ID]" value="$thisV[ID]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" style="color:gray" readonly="readonly"/>
                                <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][0][isSubsidy]" value="1"/>
	                        </td>
	                        <td width="20%">
                                <img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice($countI)"/>
	                        </td>
	                    </tr>
EOT;
				} else {
					$billTypeStr = $this->initBillType_d($billTypeArr, $thisV['BillTypeID'], $thisCostType['invoiceType'], $thisCostType['isReplace']);
					$thisI = $countI . "_" . $thisK;
					//ͼƬ��ʾ�ж�
					$imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
					//�����ж�
					$funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
					$invTitle = $thisK == 0 ? "�����" : "ɾ�����з�Ʊ";
					//��Ʊ����
					$str .= <<<EOT
	                    <tr id="tr_$thisI">
	                        <td width="30%">
	                            <select id="select_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][BillTypeID]" style="width:90px"><option value="">��ѡ��Ʊ</option>$billTypeStr</select>
	                        </td>
	                        <td width="25%">
	                            <input type="hidden" name="expense[expensedetail][$countI][expenseinv][$thisK][ID]" value="$thisV[ID]"/>
	                            <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[Amount]" onblur="invMoneySet('$thisI');countInvoiceMoney();countAll();" class="txtshort formatMoney"/>
	                        </td>
	                        <td width="25%">
	                            <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" value="$thisV[invoiceNumber]" class="txtshort"/>
                                <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][isSubsidy]" value="0"/>
	                        </td>
	                        <td width="20%">
	                            <img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
	                        </td>
	                    </tr>
EOT;
				}
			}

			//���ñ�ע���߶�
			$remarkHeight = (count($expenseinvArr) - 1) * 33 + 20 . "px";

			$str .= <<<EOT
                        </table>
                    </td>
                    <td valign="top">
	                	<input class="txtshort" id="specialApplyNo$countI" name="expense[expensedetail][$countI][specialApplyNo]" value="$specialApplyNo" title="�����ر�����" onclick="showSpecialApply($countI)" readonly="readonly">
	                </td>
	                <td valign="top">
                    	<textarea name="expense[expensedetail][$countI][Remark]" id="remark$countI" style="height:$remarkHeight" class="txt">$v[Remark]</textarea>
                    </td>
                </tr>
EOT;
		}
		$rtArr['expensedetail'] = $str;
		$rtArr['countMoney'] = $countMoney;

		return $rtArr;
	}

	/********************* ģ�岿�ִ��� *******************/
	/**
	 * ��ȡ����ģ��
	 */
	function getModelType_d() {
		$customtemplateDao = new model_finance_expense_customtemplate();
		return $customtemplateDao->getModelType_d();
	}

	/**
	 * ģ�崦�� - ��Ҫ�Ƿ�����Ŀת��
	 * @param $object
	 * @return mixed
	 */
	function rowDeal_d($object) {
		//ʵ����
		$otherDateDao = new model_common_otherdatas();
		//������ת��
		foreach ($object as $key => $val) {
			$object[$key]['fields'] = $otherDateDao->initCostType($val['fields']);
		}
		return $object;
	}

	/**
	 * ������Ϣģ�崦�� - ������Ⱦģ��
	 * @param $modelType
	 * @return string
	 */
	function initTempAdd_d($modelType) {
		$str = "";

		//��ȡģ����Ϣ
		$sql = "select id,templateName,contentId from cost_customtemplate where id = $modelType";
		$rs = $this->_db->getArray($sql);
		$modelArr = $rs[0];
		//add chenrf ���ӹر�״̬����
		//��ѯģ��С����
		$sql = "select
					c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.ParentCostType,c.invoiceType,
					c.invoiceTypeName,c.isReplace,c.isEqu,c.isSubsidy
				from
					cost_type c
				where c.CostTypeID in(" . $modelArr['contentId'] . ") and c.isNew = '1' and isClose = 0 order by c.ParentCostTypeID,c.orderNum,c.CostTypeID";
		$costTypeArr = $this->_db->getArray($sql);

		//��ȡ��Ʊ����
		$sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
		$billTypeArr = $this->_db->getArray($sql);

		foreach ($costTypeArr as $k => $v) {
			$countI = $v['CostTypeID'];
			$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
			$thisI = $countI . "_0";

			$str .= <<<EOT
                <tr class="$trClass" id="tr$v[CostTypeID]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="ɾ������" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[ParentCostType]
                        <input type="hidden" name="expense[expensedetail][$countI][MainType]" value="$v[ParentCostType]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][MainTypeId]" value="$v[ParentCostTypeID]"/>
                        <input type="hidden" id="showDays$countI" value="$v[showDays]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[CostTypeName]
                        <input type="hidden" name="expense[expensedetail][$countI][costType]" id="costType$countI" value="$v[CostTypeName]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][CostTypeID]" id="costTypeId$countI" value="$v[CostTypeID]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$v[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$v[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$v[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$v[isEqu]"/>
                        <input type="hidden" id="isSubsidy$countI" value="$v[isSubsidy]"/>
                    </td>
	                <td valign="top" class="form_text_right">
EOT;
			//�����Ҫ��ʾ����������ʾ
			if ($v['showDays']) {
				$str .= <<<EOT
                    <span>
                        <input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" class="txtshort formatMoney" style="width:60px" onblur="detailSet($countI);countAll();setCostshareMoney($countI);"/>
                        X
                        ����
                        <input type="text" name="expense[expensedetail][$countI][days]" class="txtmin" id="days$countI" value="1" onblur="daysCheck(this);detailSet($countI);countAll();setCostshareMoney($countI);" onchange="setCostshareMoney($countI);"/>
                    </span>
EOT;
			} else {
				$str .= <<<EOT
                    <input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet($countI);countAll();setCostshareMoney($countI);"/>
                    <input type="hidden" name="expense[expensedetail][$countI][days]" id="days$countI" value="1"/>
EOT;
			}

			// �Ƿ���Ҫ��Ʊ
			if ($v['isSubsidy'] == 1) {
				$billArr = $this->getBillArr_d($billTypeArr, $v['invoiceType']);
				$str .= <<<EOT
                    </td>
	                    <td valign="top" colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
	                            <tr id="tr_$thisI">
	                                <td width="30%">
	                                    <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
	                                    <input type="hidden" name="expense[expensedetail][$countI][expenseinv][0][BillTypeID]" value="$billArr[id]"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][0][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" class="txtshort formatMoney" onblur="invMoneySet('$thisI');countAll();" style="color:gray" title="�����෢Ʊ�����뵽���ݷ�Ʊ�����,ֻ���ڴ���ʾ"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" class="readOnlyTxtShort" style="color:gray" readonly="readonly"/>
	                                    <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][0][isSubsidy]" value="1"/>
	                                </td>
	                                <td width="20%">
	                                    <img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice($countI)"/>
	                                </td>
	                            </tr>
	                        </table>
	                    </td>
	                    <td valign="top">
                            <input class="txtshort" id="specialApplyNo$countI" name="expense[expensedetail][$countI][specialApplyNo]" title="�����ر�����" onclick="showSpecialApply($countI)" readonly="readonly"/>
	                    </td>
	                    <td valign="top">
	                    	<textarea name="expense[expensedetail][$countI][Remark]" id="remark$countI" class="txt"></textarea>
	                    </td>
	                </tr>
EOT;
			} else {
				$billTypeStr = $this->initBillType_d($billTypeArr, null, $v['invoiceType'], $v['isReplace']);//ģ��ʵ�����ַ���
				$str .= <<<EOT
                    </td>
	                    <td valign="top" colspan="4" class="innerTd">
	                        <table class="form_in_table" id="table_$countI">
	                            <tr id="tr_$thisI">
	                                <td width="30%">
	                                    <select id="select_$thisI" name="expense[expensedetail][$countI][expenseinv][0][BillTypeID]" style="width:90px"><option value="">��ѡ��Ʊ</option>$billTypeStr</select>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][0][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="invMoneySet('$thisI');countInvoiceMoney();" class="txtshort formatMoney"/>
	                                </td>
	                                <td width="25%">
	                                    <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort"/>
	                                    <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][0][isSubsidy]" value="0"/>
	                                </td>
	                                <td width="20%">
	                                    <img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice($countI)"/>
	                                </td>
	                            </tr>
	                        </table>
	                    </td>
	                    <td valign="top">
                            <input class="txtshort" id="specialApplyNo$countI" name="expense[expensedetail][$countI][specialApplyNo]" title="�����ر�����" onclick="showSpecialApply($countI)" readonly="readonly"/>
	                    </td>
	                    <td valign="top">
	                    	<textarea name="expense[expensedetail][$countI][Remark]" id="remark$countI" class="txt"></textarea>
	                    </td>
	                </tr>
EOT;
			}
		}

		return $str;
	}

	/**
	 * ��̯��Ϣģ�崦�� - ������Ⱦģ��
	 * @param $modelType
	 * @return string
	 */
	function initCostshareTempAdd_d($modelType) {
		$str = "";

		//��ȡģ����Ϣ
		$sql = "select id,templateName,contentId from cost_customtemplate where id = $modelType";
		$rs = $this->_db->getArray($sql);
		$modelArr = $rs[0];
		//add chenrf ���ӹر�״̬����
		//��ѯģ��С����
		$sql = "select
					c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.ParentCostType,c.invoiceType,
					c.invoiceTypeName,c.isReplace,c.isEqu,c.isSubsidy
				from
					cost_type c
				where c.CostTypeID in(" . $modelArr['contentId'] . ") and c.isNew = '1' and isClose = 0 order by c.ParentCostTypeID,c.orderNum,c.CostTypeID";
		$costTypeArr = $this->_db->getArray($sql);

		//��ȡ�������
		$rs = $this->getDatadicts ("HTBK");
		$moduleArr = $rs['HTBK'];
		$moduleStr = $this->initModule_d($moduleArr);//ģ��ʵ������������ַ���

		foreach ($costTypeArr as $k => $v) {
			$countI = $v['CostTypeID'];
			$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
			$thisI = $countI . "_0";

			$str .= <<<EOT
                <tr class="$trClass" id="trCostshare$v[CostTypeID]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="ɾ����̯" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[ParentCostType]
                        <input type="hidden" name="expense[expensecostshare][$countI][MainType]" value="$v[ParentCostType]"/>
                        <input type="hidden" name="expense[expensecostshare][$countI][MainTypeId]" value="$v[ParentCostTypeID]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[CostTypeName]
                        <input type="hidden" name="expense[expensecostshare][$countI][CostType]" id="costTypeCostshare$countI" value="$v[CostTypeName]"/>
                        <input type="hidden" name="expense[expensecostshare][$countI][CostTypeID]" id="costTypeIdCostshare$countI" value="$v[CostTypeID]"/>
                    </td>
                    <td valign="top" colspan="3" class="innerTd">
                        <table class="form_in_table" id="tableCostshare_$countI">
                            <tr id="trCostshare_$thisI">
                                <td width="49.5%">
                              		<input type="text" name="expense[expensecostshare][$countI][expenseinv][0][CostMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" id="costMoneyCostshare_$thisI" style="width:146px" class="txtmiddle formatMoney" onblur="countAllCostshare();"/>
                                </td>
                                <td width="32.5%">
                                    <select id="selectCostshare_$thisI" name="expense[expensecostshare][$countI][expenseinv][0][module]" style="width:90px;display:none;"><option value="">��ѡ����</option>$moduleStr</select>
                                    <input id="inputCostshare_$thisI" type="text" value=""  style="width:90px;text-align:center;background-color: #EEEEEE;border: 1px solid #C0C2CF;" readonly/>
                                </td>
                                <td width="18%">
                                    <img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="addModule($countI)"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top">
                    	<textarea name="expense[expensecostshare][$countI][Remark]" id="remarkCostshare$countI" class="txtlong"></textarea>
                    </td>
                </tr>
EOT;
		}

		return $str;
	}

	/**
	 * ģ�洦�� - �༭��Ⱦģ��
	 * @param $expenseArr
	 * @return mixed
	 */
	function initTempEdit_d($expenseArr) {

		//��ȡ��Ʊ����
		$sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
		$billTypeArr = $this->_db->getArray($sql);

		//��ѯģ��С����
		$sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type where isNew = '1'";
		$costTypeArr = $this->_db->getArray($sql);

		//ʵ�������÷�Ʊ��ϸ
		$expenseinvDao = new model_finance_expense_expenseinv();

		//ģ��ʵ�����ַ���
		$str = null;
		//�����ܽ��
		$countMoney = 0;
		foreach ($expenseArr['expensedetail'] as $k => $v) {
			$specialApplyNo = $v['specialApplyNo'];
			//���÷�������Id
			$countI = $v['CostTypeID'];
			//��ѯ����־�ڵĸ�����ý��
			$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
			$countMoney = bcadd($countMoney, bcmul($v['CostMoney'], $v['days'], 2), 2);
			$thisI = $countI . "_0";

			//��ȡƥ���������
			$thisCostType = $this->initExpenseEdit_d($costTypeArr, $v['CostTypeID']);

			$str .= <<<EOT
                <tr class="$trClass" id="tr$v[CostTypeID]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="ɾ������" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[MainType]
                        <input type="hidden" name="expense[expensedetail][$countI][MainType]" value="$v[MainType]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][MainTypeId]" value="$v[MainTypeId]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $thisCostType[name]
                        <input type="hidden" name="expense[expensedetail][$countI][costType]" id="costType$countI" value="$thisCostType[name]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][CostTypeID]" id="costTypeId$countI" value="$v[CostTypeID]"/>
                        <input type="hidden" name="expense[expensedetail][$countI][ID]" value="$v[ID]"/>
                        <input type="hidden" id="showDays$countI" value="$thisCostType[showDays]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$thisCostType[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$thisCostType[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$thisCostType[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$thisCostType[isEqu]"/>
                        <input type="hidden" id="isSubsidy$countI" value="$thisCostType[isSubsidy]"/>
                    </td>
	                <td valign="top" class="form_text_right">
EOT;
			//�����Ҫ��ʾ����������ʾ
			if ($thisCostType['showDays']) {
				$str .= <<<EOT
						<span>
							<input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" value="$v[CostMoney]" class="txtshort formatMoney" style="width:60px" onblur="detailSet($countI);countAll();setCostshareMoney($countI);"/>
							X
							����
							<input type="text" name="expense[expensedetail][$countI][days]" class="txtmin" id="days$countI" value="$v[days]" onblur="daysCheck(this);detailSet($countI);countAll();setCostshareMoney($countI);" onchange="setCostshareMoney($countI);"/>
						</span>
EOT;
			} else {
				$str .= <<<EOT
	                    <input type="text" name="expense[expensedetail][$countI][CostMoney]" id="costMoney$countI" value="$v[CostMoney]" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet($countI);countAll();setCostshareMoney($countI);"/>
						<input type="hidden" name="expense[expensedetail][$countI][days]" id="days$countI" value="$v[days]"/>
EOT;
			}
			$str .= <<<EOT
					</td>
                    <td colspan="4" class="innerTd">
                        <table class="form_in_table" id="table_$countI">
EOT;
			//��ȡ��Ʊ��Ϣ
			$expenseinvArr = $expenseinvDao->findAll(array('BillDetailID' => $v['ID'], 'BillNo' => $v['BillNo']));
			foreach ($expenseinvArr as $thisK => $thisV) {
				// �Ƿ���Ҫ��Ʊ
				if ($thisCostType['isSubsidy'] == 1 && $thisK == 0) {
					$billArr = $this->getBillArr_d($billTypeArr, $thisV['BillTypeID']);
					$str .= <<<EOT
	                    <tr id="tr_$thisI">
		                    <td width="30%">
                                <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
                                <input type="hidden" name="expense[expensedetail][$countI][expenseinv][0][BillTypeID]" value="$billArr[id]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[Amount]" class="txtshort formatMoney" style="color:gray" onblur="invMoneySet('$thisI');countAll();" title="�����෢Ʊ�����뵽���ݷ�Ʊ�����,ֻ���ڴ���ʾ"/>
	                            <input type="hidden" name="expense[expensedetail][$countI][expenseinv][$thisK][ID]" value="$thisV[ID]"/>
	                        </td>
	                        <td width="25%">
                                <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[invoiceNumber]" class="readOnlyTxtShort" style="color:gray" readonly="readonly"/>
                                <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][0][isSubsidy]" value="1"/>
	                        </td>
	                        <td width="20%">
                                <img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice($countI)"/>
	                        </td>
	                    </tr>
EOT;
				} else {
					$billTypeStr = $this->initBillType_d($billTypeArr, $thisV['BillTypeID'], $thisCostType['invoiceType'], $thisCostType['isReplace']);
					$thisI = $countI . "_" . $thisK;
					//ͼƬ��ʾ�ж�
					$imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
					//�����ж�
					$funClick = $thisK == 0 ? "add_lnvoice($countI)" : "delete_lnvoice($countI,this)";
					$invTitle = $thisK == 0 ? "�����" : "ɾ�����з�Ʊ";
					//��Ʊ����
					$str .= <<<EOT
	                    <tr id="tr_$thisI">
	                        <td width="30%">
	                            <select id="select_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][BillTypeID]" style="width:90px"><option value="">��ѡ��Ʊ</option>$billTypeStr</select>
	                        </td>
	                        <td width="25%">
	                            <input type="hidden" name="expense[expensedetail][$countI][expenseinv][$thisK][ID]" value="$thisV[ID]"/>
	                            <input type="text" id="invoiceMoney_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][Amount]" costTypeId="$v[CostTypeID]" rowCount="$thisI" value="$thisV[Amount]" onblur="invMoneySet('$thisI');countInvoiceMoney();countAll();" class="txtshort formatMoney"/>
	                        </td>
	                        <td width="25%">
	                            <input type="text" id="invoiceNumber_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" value="$thisV[invoiceNumber]" class="txtshort"/>
                                <input type="hidden" id="invIsSubsidy_$thisI" name="expense[expensedetail][$countI][expenseinv][$thisK][isSubsidy]" value="0"/>
	                        </td>
	                        <td width="20%">
	                            <img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
	                        </td>
	                    </tr>
EOT;
				}
			}

			//���ñ�ע���߶�
			$remarkHeight = (count($expenseinvArr) - 1) * 33 + 20 . "px";

			$str .= <<<EOT
                        </table>
                    </td>
                    <td valign="top">
                        <input class="txtshort" id="specialApplyNo$countI" name="expense[expensedetail][$countI][specialApplyNo]" value="$specialApplyNo" title="�����ر�����" onclick="showSpecialApply($countI)" readonly="readonly"/>
                    </td>
	                <td valign="top">
                    	<textarea name="expense[expensedetail][$countI][Remark]" id="remark$countI" style="height:$remarkHeight" class="txt">$v[Remark]</textarea>
                    </td>
                </tr>
EOT;
		}
		$rtArr['expensedetail'] = $str;
		$rtArr['countMoney'] = $countMoney;

		return $rtArr;
	}

	/**
	 * ��̯ģ�洦�� - �༭��Ⱦģ��
	 * @param $expenseArr
	 * @return mixed
	 */
	function initCostshareTempEdit_d($expenseArr) {
		//��ѯģ��С����
		$sql = "select CostTypeID as id,CostTypeName as name,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type where isNew = '1'";
		$costTypeArr = $this->_db->getArray($sql);

		//ģ��ʵ�����ַ���
		$str = null;
		//��ȡ�������
		$rs = $this->getDatadicts ("HTBK");
		$moduleArr = $rs['HTBK'];
		foreach ($expenseArr['expensecostshare'] as $k => $v) {
			$detail = $v['detail'];
			//���÷�������Id
			$countI = $v['CostTypeID'];
			//��ѯ����־�ڵĸ�����ý��
			$trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
			$thisI = $countI . "_0";

			//��ȡƥ���������
			$thisCostType = $this->initExpenseEdit_d($costTypeArr, $v['CostTypeID']);

			$str .= <<<EOT
                <tr class="$trClass" id="trCostshare$v[CostTypeID]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="ɾ����̯" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[MainType]
                        <input type="hidden" name="expense[expensecostshare][$countI][ID]" value="$v[id]"/>
                        <input type="hidden" name="expense[expensecostshare][$countI][MainType]" value="$v[MainType]"/>
                        <input type="hidden" name="expense[expensecostshare][$countI][MainTypeId]" value="$v[MainTypeId]"/>
                    </td>
                   	<td valign="top" class="form_text_right">
                        $v[CostType]
                        <input type="hidden" name="expense[expensecostshare][$countI][CostType]" id="costTypeCostshare$countI" value="$v[CostType]"/>
                        <input type="hidden" name="expense[expensecostshare][$countI][CostTypeID]" id="costTypeIdCostshare$countI" value="$v[CostTypeID]"/>
                    </td>
			        <td colspan="3" class="innerTd">
                        <table class="form_in_table" id="tableCostshare_$countI">
EOT;
			foreach ($detail as $thisK => $thisV) {
				$thisI = $countI . "_" . $thisK;
				//ͼƬ��ʾ�ж�
				$imgUrl = $thisK == 0 ? "images/add_item.png" : "images/removeline.png";
				//�����ж�
				$funClick = $thisK == 0 ? "addModule($countI)" : "deleteModule($countI,this)";
				$invTitle = $thisK == 0 ? "�����" : "ɾ��������Ϣ";
				$moduleStr = $this->initModule_d($moduleArr,$thisV[module]);//ģ��ʵ������������ַ���

                // ���õ��ݵķ��ù��������Ƿ񿪷ſɱ༭����Ȩ��
                $deptId = $expenseArr['CostBelongDeptId'];
                $datadictDao = new model_system_datadict_datadict();
                $configuratorDao = new model_system_configurator_configurator();
                $selectHide = $inputHide = '';
                $moduleName = $datadictDao->getDataNameByCode($thisV[module]);
                $data = $configuratorDao->checkDeptInConfig($deptId);
                $result = ($data)? 1 : 0;
                if($result == 1){
                    $inputHide = 'display:none;';
                }else{
                    $selectHide = 'display:none;';
                }

				$str .= <<<EOT
	            	<tr id="trCostshare_$thisI">
		            	<td width="49.5%">
		            	 	<input type="hidden" name="expense[expensecostshare][$countI][expenseinv][$thisK][ID]" value="$thisV[ID]"/>
		                	<input type="text" name="expense[expensecostshare][$countI][expenseinv][$thisK][CostMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" id="costMoneyCostshare_$thisI" value="$thisV[CostMoney]" style="width:146px" class="txtmiddle formatMoney" onblur="countAllCostshare();"/>
	                    </td>
	                    <td width="32.5%">
                        	<select id="selectCostshare_$thisI" name="expense[expensecostshare][$countI][expenseinv][$thisK][module]" style="width:90px;$selectHide"><option value="">��ѡ����</option>$moduleStr</select>
                        	<input id="inputCostshare_$thisI" type="text" value="$moduleName"  style="width:90px;text-align:center;background-color: #EEEEEE;border: 1px solid #C0C2CF;$inputHide" readonly/>
	                    </td>
                        <td width="18%">
                        	<img style="cursor:pointer;" src="$imgUrl" title="$invTitle" onclick="$funClick"/>
                        </td>
	                </tr>
EOT;
			}

			//���ñ�ע���߶�
			$remarkHeight = (count($detail) - 1) * 33 + 20 . "px";

			$str .= <<<EOT
                        </table>
                    </td>
	                <td valign="top">
                    	<textarea name="expense[expensecostshare][$countI][Remark]" id="remarkCostshare$countI" style="height:$remarkHeight" class="txtlong">$v[Remark]</textarea>
                    </td>
                </tr>
EOT;
		}
		$rtArr['expensecostshare'] = $str;

		return $rtArr;
	}

	/**
	 * ģ�崦�� - �鿴��Ⱦģ��
	 * @param $expensedetail
	 * @return array
	 */
	function initTempView_d($expensedetail) {
		//��������
		$rtArr = array();

		//��ȡ��Ʊ����
		$sql = "select id,name from bill_type";
		$billTypeArr = $this->_db->getArray($sql);

		//��ѯģ��С����
		$sql = "select CostTypeID as id,CostTypeName as name from cost_type";
		$costTypeArr = $this->_db->getArray($sql);

		//ʵ�������÷�Ʊ��ϸ
		$expenseinvDao = new model_finance_expense_expenseinv();

		//ģ��ʵ�����ַ���
		$str = null;
		//�����ܽ��
		$countMoney = 0;

		//��־λ
		$markArr = array();
		//��ͬ�м���
		$countArr = array();

		//��ͬ���ü���
		foreach ($expensedetail as $key => $val) {
			if ($val['CostMoney'] == 0) {
				continue;
			}
			//��ȡ��Ʊ��Ϣ
			$expensedetail[$key]['expenseinv'] = $expenseinvDao->findAll(array('BillDetailID' => $val['ID'], 'BillNo' => $val['BillNo']));

			//��Ʊ��Ϣ����
			$expensedetail[$key]['invLength'] = count($expensedetail[$key]['expenseinv']);

			if (isset($countArr[$val['MainTypeId']])) {
				$countArr[$val['MainTypeId']] += $expensedetail[$key]['invLength'];
			} else {
				$countArr[$val['MainTypeId']] = $expensedetail[$key]['invLength'];
			}
		}

		foreach ($expensedetail as $k => $v) {
			//�ر�������Ϣ
			$specialApplyNo = $v['specialApplyNo'];
			if ($v['CostMoney'] == 0) {
				continue;
			}
			$mailSize = $countArr[$v['MainTypeId']];

			//��ѯ����־�ڵĸ�����ý��
			$detailMoney = bcmul($v['CostMoney'], $v['days'], 2);
			$countMoney = bcadd($countMoney, $detailMoney, 2);

			//�����������ʾ
			if ($v['days'] > 1) {
				$costMoneyHtm = "<span class='formatMoney green' title='����:" . $v['CostMoney'] . " X ����:" . $v['days'] . "'>$detailMoney</span>";
			} else {
				$costMoneyHtm = "<span class='formatMoney'>$detailMoney</span>";
			}

			//��������ת��
			$thisCostType = $this->initBillView_d($costTypeArr, $v['CostTypeID']);

			$invSize = $v['invLength'];

			foreach ($v['expenseinv'] as $thisK => $thisV) {
				$blue = $thisV['Amount'] == 0 ? 'blue' : '';
				if ($thisK == 0) {
					$billType = $this->initBillView_d($billTypeArr, $thisV['BillTypeID']);
					if (!in_array($v['MainTypeId'], $markArr)) {
						$trClass = count($markArr) % 2 == 0 ? 'tr_odd' : 'tr_even';

						$str .= <<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$mailSize">
			                        $v[MainType]
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $thisCostType
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td><span class="formatMoney $blue">$thisV[Amount]</span></td>
				                <td><span class="$blue">$thisV[invoiceNumber]</span></td>
				          		<td valign="top" class="form_text_right" rowspan="$invSize">
									$specialApplyNo
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[Remark]
			                    </td>
				            </tr>
EOT;
						array_push($markArr, $v['MainTypeId']);
					} else {
						$str .= <<<EOT
			            	<tr class="$trClass">
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
			                        $thisCostType
			                    </td>
				                <td valign="top" rowspan="$invSize">
				                    $costMoneyHtm
				                </td>
				                <td>
									$billType
				                </td>
				                <td><span class="formatMoney $blue">$thisV[Amount]</span></td>
				                <td><span class="$blue">$thisV[invoiceNumber]</span></td>
				             	<td valign="top" class="form_text_right" rowspan="$invSize">
									$specialApplyNo
			                    </td>
			                    <td valign="top" class="form_text_right" rowspan="$invSize">
									$v[Remark]
			                    </td>
				            </tr>
EOT;
					}
				} else {
					$billType = $this->initBillView_d($billTypeArr, $thisV['BillTypeID']);
					$str .= <<<EOT
		            	<tr class="$trClass">
			                <td>
								$billType
			                </td>
			                <td><span class="formatMoney $blue">$thisV[Amount]</span></td>
			                <td><span class="$blue">$thisV[invoiceNumber]</span></td>
			            </tr>
EOT;
				}
			}
		}
		$rtArr['expensedetail'] = $str;
		$rtArr['countMoney'] = $countMoney;

		return $rtArr;
	}

	/**
	 * �������ʼ����optionѡ��
	 * @param $object
	 * @param null $thisVal
	 * @param null $defaultVal
	 * @param int $isReplace
	 * @return null|string
	 */
	function initBillType_d($object, $thisVal = null, $defaultVal = null, $isReplace = 1) {
		//    	echo $thisVal . "---".$defaultVal.'---'.$isReplace.'<br/>';
		$str = null;
		$title = $isReplace ? '�˷���������Ʊ' : '�˷��ò�������Ʊ';
		foreach ($object as $key => $val) {
			if ($thisVal == $val['id']) {
				$str .= '<option value="' . $val['id'] . '" selected="selected" title="' . $title . '">' . $val['name'] . '</option>';
			} elseif ($defaultVal == $val['id']) {
				if ($thisVal) {
					$str .= '<option value="' . $val['id'] . '" title="' . $title . '">' . $val['name'] . '</option>';
				} else {
					$str .= '<option value="' . $val['id'] . '" selected="selected" title="' . $title . '">' . $val['name'] . '</option>';
				}
			} else {
				if ($isReplace) {
					$str .= '<option value="' . $val['id'] . '" title="' . $title . '">' . $val['name'] . '</option>';
				}
			}
		}
		return $str;
	}

	/**
	 * ��������������ʼ����optionѡ��
	 * @param $object
	 * @param null $defaultVal
	 * @return null|string
	 */
	function initModule_d($object, $defaultVal = null) {
		$str = null;
		foreach ($object as $key => $val) {
			if ($val['dataCode'] == $defaultVal) {
				$str .= '<option value="' . $val['dataCode'] . '" selected="selected" title="' . $val['dataName'] . '">' . $val['dataName'] . '</option>';
			} else {
				$str .= '<option value="' . $val['dataCode'] . '" title="' . $val['dataName'] . '">' . $val['dataName'] . '</option>';
			}
		}
		return $str;
	}

	/**
	 * �鿴��Ʊֵ
	 * @param $object
	 * @param null $thisVal
	 * @return null
	 */
	function initBillView_d($object, $thisVal = null) {
		$str = null;
		foreach ($object as $key => $val) {
			if ($thisVal == $val['id']) {
				return $val['name'];
			}
		}
		return null;
	}

	/**
	 * ���ض�Ӧ�ķ�Ʊ����
	 * @param $object
	 * @param null $defaultVal
	 * @return array
	 */
	function getBillArr_d($object, $defaultVal = null) {
		if ($defaultVal) {
			$rtArr = array();
			foreach ($object as $key => $val) {
				if ($val['id'] == $defaultVal) {
					$rtArr = $val;
					break;
				}
			}
			return $rtArr;
		} else {
			return array(
				'name' => '',
				'id' => ''
			);
		}
	}

	/**
	 * ���ط�����������
	 * @param $object
	 * @param null $thisVal
	 * @return array|null
	 */
	function initExpenseEdit_d($object, $thisVal = null) {
		foreach ($object as $key => $val) {
			if ($thisVal == $val['id']) {
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

	/**
	 * ƥ���������
	 * @param $object
	 * @param $v
	 * @return array
	 */
	function fitCostType_d($object, $v) {
		foreach ($object as $key => $val) {
			if ($v['costTypeId'] == $val['budgetType']) {
				$v['costTypeId'] = $val['id'];
				$v['name'] = $val['name'];
				return $v;
			}
		}
		return $v;
	}

	/************************* �������Ͳ��� ************************/

	/**
	 * ��ȡ���ñ�
	 */
	function getCostType_d() {
		//ģ��ʵ�����ַ���
		$str = null;
		$imgLine = 'images/menu/tree_line.gif'; //ֱ��
		$imgMinus = 'images/menu/tree_minus.gif'; //��������
		$imgBlank = 'images/menu/tree_blank.gif'; //��֧����
		//update chenrf 20130425��ӹرչ���
		//��ѯģ��С����
		$sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type where CostTypeLeve=1 and isNew = '1' and isClose = 0 order by orderNum";

		$costTypeArr = $this->_db->getArray($sql);
		if ($costTypeArr) {

			foreach ($costTypeArr as $key => $val) {
				$str .= "<div class='box'><table  class='form_in_table'>";
				//�б�ɫ
				$trClass = 'tr_odd';

				$str .= <<<EOT
	            	<tr class="$trClass">
	                    <td class="form_text_right" valign="top">
		                    <img src="$imgMinus" id="$val[CostTypeID]" onclick="CostTypeShowAndHide($val[CostTypeID])"/>
	                        <font style="font-weight:bold;">$val[CostTypeName]</font>
	                    </td>
		            </tr>
EOT;
				//update chenrf 20130425��ӹرչ���
				//�������ݴ���
				$sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type where ParentCostTypeID ='" . $val['CostTypeID'] . "' and CostTypeLeve=2 and isNew = '1' and isClose = 0 order by orderNum";

				$costLv2Arr = $this->_db->getArray($sql);
				if ($costLv2Arr) {
					//��¼1����
					$lv1Cls = "ct_" . $val['CostTypeID'];
					foreach ($costLv2Arr as $lv2Key => $lv2Val) {
						//update chenrf 20130425��ӹرչ���
						//�������ݴ���
						$sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy from cost_type where ParentCostTypeID ='" . $lv2Val['CostTypeID'] . "' and CostTypeLeve=3 and isNew = '1' and isClose = 0 order by orderNum";

						$costLv3Arr = $this->_db->getArray($sql);
						//						print_r($costLv3Arr);
						//�������������
						if ($costLv3Arr) {
							$treeImg = $imgMinus;
							$chkHtml = "";
							$secLine = '<img src="' . $imgLine . '"/>';
						} else {
							$secLine = '';
							$treeImg = $imgBlank;
							$chkHtml = <<<EOT
								<input type="checkbox"
									id="chk$lv2Val[CostTypeID]"
									value="$lv2Val[CostTypeID]"
									name="$lv2Val[CostTypeName]"
									parentId="$val[CostTypeID]"
									parentName="$val[CostTypeName]"
									showDays="$lv2Val[showDays]"
									isReplace="$lv2Val[isReplace]"
									isEqu="$lv2Val[isEqu]"
									invoiceType="$lv2Val[invoiceType]"
									invoiceTypeName="$lv2Val[invoiceTypeName]"
									isSubsidy="$lv2Val[isSubsidy]"
									onclick="setCustomCostType($lv2Val[CostTypeID],this)"
								/>
EOT;
						}
						$str .= <<<EOT
			            	<tr class="$trClass $lv1Cls" isView="1">
			                    <td class="form_text_right" valign="top">
			                  		$secLine
			                    	<img src="$treeImg" id="$lv2Val[CostTypeID]" onclick="CostType2View($lv2Val[CostTypeID],this)"/>
									$chkHtml
			                        <span id="view$lv2Val[CostTypeID]">$lv2Val[CostTypeName]</span>
			                    </td>
				            </tr>
EOT;

						//��������
						if ($costLv3Arr) {
							//��¼1����
							$lv2Cls = "ct_" . $lv2Val['CostTypeID'];
							foreach ($costLv3Arr as $lv3Key => $lv3Val) {
								$str .= <<<EOT
					            	<tr class="$trClass $lv1Cls $lv2Cls" isView="1">
			                   			<td class="form_text_right" valign="top">
					                    	<img src="$imgLine"/>
					                    	<img src="$imgBlank"/>
				                    		<input type="checkbox"
				                    			id="chk$lv3Val[CostTypeID]"
				                    			value="$lv3Val[CostTypeID]"
												name="$lv3Val[CostTypeName]"
												parentId="$lv2Val[CostTypeID]"
												parentName="$lv2Val[CostTypeName]"
												showDays="$lv3Val[showDays]"
												isReplace="$lv3Val[isReplace]"
												isEqu="$lv3Val[isEqu]"
												invoiceType="$lv3Val[invoiceType]"
												invoiceTypeName="$lv3Val[invoiceTypeName]"
												isSubsidy="$lv3Val[isSubsidy]"
				                    			onclick="setCustomCostType($lv3Val[CostTypeID],this)"
			                    			/>
					                        <span id="view$lv3Val[CostTypeID]">$lv3Val[CostTypeName]</span>
					                    </td>
						            </tr>
EOT;
							}
						}
					}
				}
				$str .= "</table></div>";
			}
		}
		return $str;
	}

	/********************* ��ȡ��ǰ�ۺ�����Ϣ *********************/
	/**
	 * ��ȡ��ǰ�ۺ�����Ϣ
	 * @param $detailType
	 * @return array
	 */
	function getSaleDept_d($detailType) {
		//��������
		$rtArr = array();

		include(WEB_TOR . "includes/config.php");
		//��ǰ���ۺ�һ��
		if ($detailType == "4") {
			//��ǰ����
			$sourceArr = isset($expenseSaleDept) ? $expenseSaleDept : null;
		} elseif ($detailType == "5") {
			//�ۺ���
			$sourceArr = isset($expenseContractDept) ? $expenseContractDept : null;
		} else {
			$sourceArr = isset($expenseTrialProjectFeeDept) ? $expenseTrialProjectFeeDept : array('triProjectDeptId' => '', 'triProjectDeptName' => 'Ӫ����');
		}
		//������������
		foreach ($sourceArr['normalDept'] as $key => $val) {
			array_push($rtArr, array('value' => $key, 'text' => $val));
		}
		//����Ȩ�����鲿��
		if ($sourceArr['limitDept']) {
			foreach ($sourceArr['limitDept'] as $key => $val) {
				if ($_SESSION['DEPT_ID'] == $key) {
					array_push($rtArr, array('value' => $key, 'text' => $val));
				}
			}
		}
		return $rtArr;
	}

	/**
	 * �жϲ����Ƿ���Ҫʡ��
	 * @param $deptId
	 * @return int
	 */
	function deptIsNeedProvince_d($deptId) {
		include(WEB_TOR . "includes/config.php");
		//���ŷ�����Ҫʡ�ݵĲ���
		$expenseNeedProvinceDept = isset($expenseNeedProvinceDept) ? $expenseNeedProvinceDept : null;
		//����key����
		$keyArr = array_keys($expenseNeedProvinceDept);
		if (in_array($deptId, $keyArr)) {
			return 1;
		} else {
			return 0;
		}
	}

	/********************* �ⲿ���û�ȡ���ݽӿ� **********************/
	/**
	 * ������Ŀ��ȡ������ϸ
	 * @param $projectNo
	 * @return array
	 */
	function getFeeSum_d($projectNo) {
		$sql = "select
				sum(c.CostMoney) as CostMoney
			from(
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail_assistant a on l.BillNo = a.BillNo
					inner join cost_detail_project d on d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0
				AND l. STATUS <> '���'
				and l.ProjectNo = '$projectNo'
				union all
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail d on d.BillNo = l.BillNo
				WHERE
					l.isNew = 1
				AND l.isEffected = 1
				and l.ProjectNo = '$projectNo'
			) c";
		$rs = $this->_db->getArray($sql);
		if ($rs[0]['CostMoney']) {
			return $rs[0]['CostMoney'];
		} else {
			return 0;
		}
	}

	/**
	 * ��ȡĳ����Ŀ��ĳ�����
	 * @param $projectNo
	 * @param $costTypeIds
	 * @return int
	 */
	function getSomeFeeSum_d($projectNo, $costTypeIds) {
		if (!$costTypeIds) {
			return 0;
		}
		$sql = "select
				sum(c.CostMoney) as CostMoney
			from(
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail_assistant a on l.BillNo = a.BillNo
					inner join cost_detail_project d on d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0
				AND l. STATUS <> '���'
				and l.ProjectNo = '$projectNo' AND d.CostTypeID IN($costTypeIds)
				union all
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail d on d.BillNo = l.BillNo
				WHERE
					l.isNew = 1
				AND l.isEffected = 1
				and l.ProjectNo = '$projectNo' AND d.CostTypeID IN($costTypeIds)
			) c";
		$rs = $this->_db->getArray($sql);
		if ($rs[0]['CostMoney']) {
			return $rs[0]['CostMoney'];
		} else {
			return 0;
		}
	}

	/**
	 * ������Ŀ��ȡ������ϸ
	 * @param $projectNo
	 * @return array
	 */
	function getFeeDetail_d($projectNo) {
		$sql = "select
				sum(c.CostMoney) as CostMoney,
				t.CostTypeName,t.ParentCostType,t.ParentCostTypeID
			from(
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail_assistant a on l.BillNo = a.BillNo
					inner join cost_detail_project d on d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0
				AND l. STATUS <> '���'
				and l.ProjectNo = '$projectNo'
				union all
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney
				FROM
					cost_summary_list l
					inner join cost_detail d on d.BillNo = l.BillNo
				WHERE
					l.isNew = 1
				AND l.isEffected = 1
				and l.ProjectNo = '$projectNo'
			) c
			left join
			cost_type t on c.CostTypeID = t.CostTypeID
			group by t.CostTypeName";
		$rs = $this->_db->getArray($sql);
		if ($rs[0]['CostTypeName']) {
			return $rs;
		} else {
			return array();
		}
	}

    /**
     * @param $projectNo
     * @param $costTypeName
     * @return array|bool
     */
    function getFeeDetailGroupMonth_d($projectNo, $costTypeName) {
        $sql = "select
				DATE_FORMAT(InputDate,'%Y%m') AS yearMonth,
				sum(c.CostMoney) as actFee
			from(
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney,a.CostDateEnd AS InputDate
				FROM
					cost_summary_list l
					inner join cost_detail_assistant a on l.BillNo = a.BillNo
					inner join cost_detail_project d on d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0
				AND l. STATUS <> '���'
				and l.ProjectNo = '$projectNo'
				union all
				SELECT
					d.CostTypeID,ROUND((d.CostMoney*d.days),2) as CostMoney,l.CostDateBegin AS InputDate
				FROM
					cost_summary_list l
					inner join cost_detail d on d.BillNo = l.BillNo
				WHERE
					l.isNew = 1
				AND l.isEffected = 1
				and l.ProjectNo = '$projectNo'
			) c
			left join
			cost_type t on c.CostTypeID = t.CostTypeID
			WHERE t.CostTypeName = '$costTypeName'
			group by DATE_FORMAT(InputDate,'%Y%m')";
        $rs = $this->_db->getArray($sql);
        if ($rs[0]['yearMonth']) {
            return $rs;
        } else {
            return array();
        }
    }

	/**
	 * ������Ŀ��ȡ������ϸ - ���������Ŀ�������
	 * @param $projectCodeArr
	 * @return array
	 */
	function getFeeDetailByCodeArr_d($projectCodeArr) {
		$projectCodeCondition = util_jsonUtil::strBuild(implode(',', $projectCodeArr));
		$sql = "SELECT
				c.ProjectNo, SUM(c.CostMoney) AS CostMoney, c.CostTypeID
			FROM(
				SELECT
					l.ProjectNo, d.CostTypeID, ROUND((d.CostMoney*d.days),2) AS CostMoney
				FROM
					cost_summary_list l
					INNER JOIN cost_detail_assistant a ON l.BillNo = a.BillNo
					INNER JOIN cost_detail_project d ON d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0
				AND l. STATUS <> '���'
				AND l.ProjectNo IN($projectCodeCondition)
				UNION ALL
				SELECT
					l.ProjectNo, d.CostTypeID, ROUND((d.CostMoney*d.days),2) AS CostMoney
				FROM
					cost_summary_list l
					INNER JOIN cost_detail d ON d.BillNo = l.BillNo
				WHERE
					l.isNew = 1
				AND l.isEffected = 1
				AND l.ProjectNo IN($projectCodeCondition)
			) c
			GROUP BY c.ProjectNo, c.CostTypeID";
		$rs = $this->_db->getArray($sql);
		if ($rs[0]['CostTypeID']) {
			return $rs;
		} else {
			return array();
		}
	}

	/**
	 * ��ȡ���е���Ŀ���� - ����
     * @param $year null
     * @param $month null
	 * @param $projectCodeStr null
	 * @return array
	 */
	function getAllProjectFee_d($year = null, $month = null, $projectCodeStr = null) {
        // ����д������£������ɹ������ڵ�SQL��
        $periodSql = "";
        if ($year && $month) {
            $lastDate = date('Y-m-t', strtotime($year . '-' . $month . '-01'));
            $oldFormSql = " AND TO_DAYS(a.CostDateEnd) <= TO_DAYS('" . $lastDate . "')";
            $periodSql = " AND TO_DAYS(l.CostDateBegin) <= TO_DAYS('" . $lastDate . "')";
        }

		$projectSql = "";
		if ($projectCodeStr) {
			$projectSql = " AND l.ProjectNo IN(" . util_jsonUtil::strBuild($projectCodeStr) . ")";
		}

		$rs = $this->_db->getArray("SELECT
				c.projectNo,SUM(c.costMoney) AS costMoney
			FROM (
				SELECT
					l.ProjectNo AS projectNo,ROUND(d.CostMoney * d.days, 2) AS costMoney
				FROM
					cost_summary_list l
				INNER JOIN cost_detail_assistant a ON l.BillNo = a.BillNo
		        INNER JOIN cost_detail_project d ON d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0 $projectSql
					$oldFormSql  AND l.ProjectNo <> '' AND l. STATUS <> '���'
				UNION ALL
				SELECT
					l.ProjectNo AS projectNo,l.Amount AS costMoney
				FROM
					cost_summary_list l
				WHERE
					l.isNew = 1 AND l.isEffected = 1 $projectSql
					$periodSql AND l.ProjectNo <> ''
			) c
		GROUP BY c.projectNo");
		return $rs ? $rs : array();
	}

    /**
     * ��ȡĳЩ���͵ı������
     * @param $costTypeIds
     * @param $year null
     * @param $month null
	 * @param $projectCodeStr null
     * @return array
     */
    function getAllProjectSomeFee_d($costTypeIds, $year = null, $month = null, $projectCodeStr = null) {
		if (!$costTypeIds) {
			return array();
		}
        // ����д������£������ɹ������ڵ�SQL��
        $periodSql = "";
        if ($year && $month) {
            $lastDate = date('Y-m-t', strtotime($year . '-' . $month . '-01'));
            $oldFormSql = " AND TO_DAYS(a.CostDateEnd) <= TO_DAYS('" . $lastDate . "')";
            $periodSql = " AND TO_DAYS(l.CostDateBegin) <= TO_DAYS('" . $lastDate . "')";
        }

		$projectSql = "";
		if ($projectCodeStr) {
			$projectSql = " AND l.ProjectNo IN(" . util_jsonUtil::strBuild($projectCodeStr) . ")";
		}

		$rs = $this->_db->getArray("SELECT
				c.projectNo,SUM(c.costMoney) AS costMoney
			FROM (
				SELECT
					l.ProjectNo AS projectNo,ROUND(d.CostMoney * d.days, 2) AS costMoney
				FROM
					cost_summary_list l
				INNER JOIN cost_detail_assistant a ON l.BillNo = a.BillNo
		        INNER JOIN cost_detail_project d ON d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0 $projectSql AND d.CostTypeID IN($costTypeIds)
					$oldFormSql AND l.ProjectNo <> '' AND l. STATUS <> '���'
				UNION ALL
				SELECT
					l.ProjectNo AS projectNo,ROUND(d.CostMoney * d.days, 2) AS costMoney
				FROM
					cost_summary_list l
				INNER JOIN cost_detail d ON d.BillNo = l.BillNo
				WHERE
					l.isNew = 1 AND l.isEffected = 1 $projectSql AND d.CostTypeID IN($costTypeIds)
					$periodSql AND l.ProjectNo <> ''
			) c
		GROUP BY c.projectNo");
		return $rs ? $rs : array();
	}

    /**
     * ��ȡ������Ŀ�ľ���
     * @param null $year
     * @param null $month
     * @param null $projectNos
     * @return int
     */
    function getPeriodFee_d($year = null, $month = null, $projectNos = null) {
        // ����д������£������ɹ������ڵ�SQL��
        $periodSql = "";
        if ($year && $month) {
            $oldFormSql = " AND YEAR(a.CostDateEnd) = " . $year . " AND MONTH(a.CostDateEnd) = " . $month;
            $periodSql = " AND YEAR(l.CostDateBegin) = " . $year . " AND MONTH(l.CostDateBegin) = " . $month;
        }

        $projectSql = '';
        if ($projectNos) {
            $projectSql = " AND l.ProjectNo IN(" . util_jsonUtil::strBuild($projectNos) . ")";
        }

        $rs = $this->_db->getArray("SELECT
				SUM(c.costMoney) AS costMoney
			FROM (
				SELECT
					l.ProjectNo AS projectNo,ROUND(d.CostMoney * d.days, 2) AS costMoney
				FROM
					cost_summary_list l
				INNER JOIN cost_detail_assistant a ON l.BillNo = a.BillNo
		        INNER JOIN cost_detail_project d ON d.AssID = a.id
				WHERE
					l.isproject = 1 AND l.isNew = 0 AND l. STATUS <> '���'
					$oldFormSql $projectSql
				UNION ALL
				SELECT
					l.ProjectNo AS projectNo,l.Amount AS costMoney
				FROM
					cost_summary_list l
				WHERE
					l.isNew = 1 AND l.isEffected = 1
					$periodSql $projectSql
			) c");
        return $rs[0]['costMoney'] ? $rs[0]['costMoney'] : 0;
    }

	/**
	 * ��ȡ ʱ�� ��Χ�ڱ仯�ı���
	 * @param $beginTime
	 * @param $endTime
	 * @return array|bool
	 */
	function getChangeProjectCodeList_d($beginTime, $endTime) {
		if (!$beginTime || !$endTime) {
			return array();
		}
		$timeSql = " AND UNIX_TIMESTAMP(l.InputDate) BETWEEN $beginTime AND $endTime";

		$data = $this->_db->getArray("SELECT
				c.projectNo
			FROM (
				SELECT
					l.ProjectNo AS projectNo
				FROM
					cost_summary_list l
				WHERE
					l.isproject = 1 AND l.isNew = 0 AND l.ProjectNo <> '' AND l. STATUS <> '���'
					$timeSql
				UNION ALL
				SELECT
					l.ProjectNo AS projectNo
				FROM
					cost_summary_list l
				WHERE
					l.isNew = 1 AND l.isEffected = 1 AND l.ProjectNo <> ''
					$timeSql
			) c
		GROUP BY c.projectNo");

		$rs = array();
		foreach ($data as $v) {
			$rs[] = $v['projectNo'];
		}
		return $rs;
	}

    /**
     * PMS613 ��ȡ���ù�������Ϊϵͳ������ֻ��ѡ�ķ��óе���
     * @return string
     */
    function getFeemansForXtsSales(){
        // PMS613 ���ù�������Ϊϵͳ������ֻ��ѡ�ķ��óе���
        $otherDataDao = new model_common_otherdatas();
        $feemansIdForXtsSales = $otherDataDao->getConfig('limitFeeMansFor_xtsSales');
        if($feemansIdForXtsSales != ''){
            $feemansIdForXtsSalesArr = explode(",",rtrim($feemansIdForXtsSales,","));
            $feemansForXtsSalesStr = "";
            if(!empty($feemansIdForXtsSalesArr)){
                $userDao = new model_deptuser_user_user();
                foreach ($feemansIdForXtsSalesArr as $uid){
                    if(!empty($uid)){
                        $userInfo = $userDao->getUserName_d($uid);
                        $feemansForXtsSalesStr .= ($feemansForXtsSalesStr == "")? "{$uid}:{$userInfo['USER_NAME']}" : ",{$uid}:{$userInfo['USER_NAME']}";
                    }
                }
            }
            return $feemansForXtsSalesStr;
        }
    }

    /**
     * ��鱨�����ķ���С���Ƿ��ж�Ӧ�İ������õļ�¼
     *
     * @param string $userId
     * @param string $startDate
     * @param string $endDate
     * @param string $costTypeIds
     * @return array
     */
    function chkAliTripRecord($userId = "", $startDate = "", $endDate = "", $costTypeIds = ""){
        $configuratorDao = new model_system_configurator_configurator();
        $matchConfigItem = $configuratorDao->getConfigItems('ALSLFYMM','config_itemSub2','',array("config_itemSub2IN" => $costTypeIds));// 391,394,397
        $dateRange = array("CostDateBegin" => $startDate,"CostDateEnd" => $endDate);
        $resultArr = $aliCostTypeArr = $backArr = array();
        if(!empty($matchConfigItem)){// ֻ�з������ʹ��������ö���Ĳ�������
            foreach ($matchConfigItem as $val){
                $aliCostTypeArr[] = $val['config_item1'];
                $resultArr[$val['config_item1']] = array(
                    "aliCostType" => $val['config_item1'],
                    "expenseCostType" => $val['config_item2'],
                    "expenseCostTypeId" => $val['config_itemSub2']
                );
            }

            // ���ݴ�����û�ID�Լ����������ѯ��Ӧ�İ������÷��ü�¼,���ų�����Щ�����ڶ�Ӧ��ϵ�ķ������
            $aliDao = new model_finance_expense_alibusinesstrip();

            $dataRows = $aliDao->searchLocalAliDataForGrid_d($userId,$dateRange);

            if(!empty($dataRows)){// ���Ƶ��¼
                foreach ($dataRows as $row){
                    if(isset($resultArr[$row['category']])){
                        $backArr[] = $resultArr[$row['category']];
                    }
                }
            }
        }

        // �����ڵ���Ali��SDK֮ǰ��ʵ����һ�±��ص�util,��������
//        $jsonUtilObj = new util_jsonUtil();
//
//        $dataRows = $aliDao->getAliTripHotelOrder($userId,$dateRange);// �Ƶ��¼
//        $flightDataRows = $aliDao->getAliTripFlightOrder($userId,$dateRange);// ��Ʊ��¼
//        $trainDataRows = $aliDao->getAliTripTrainOrder($userId,$dateRange);// ��Ʊ��¼
//
//        $resultArr = util_jsonUtil::iconvGB2UTFArr($resultArr);
//        $backArr = array();
//        if(!empty($dataRows)){// ���Ƶ��¼
//            foreach ($dataRows as $row){
//                if(isset($resultArr[$row['category']])){
//                    $backArr[] = $resultArr[$row['category']];
//                }
//            }
//        }
//
//        if(!empty($flightDataRows)){// ����Ʊ��¼
//            foreach ($flightDataRows as $row){
//                if(isset($resultArr[$row['category']])){
//                    $backArr[] = $resultArr[$row['category']];
//                }
//            }
//        }
//        if(!empty($trainDataRows)){// ����Ʊ��¼
//            foreach ($trainDataRows as $row){
//                if(isset($resultArr[$row['category']])){
//                    $backArr[] = $resultArr[$row['category']];
//                }
//            }
//        }

        // ������������
        return $backArr;
    }

    /**
     * ��ȡ���б������д��ڵ���Ŀʡ����Ϣ
     * @return mixed
     */
    function getAllProProvince_d(){
        return $this->_db->getArray("select proProvince,proProvinceId from cost_summary_list where proProvince <> '' GROUP BY proProvince;");
    }
}