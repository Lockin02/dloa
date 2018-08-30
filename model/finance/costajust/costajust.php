<?php

/**
 * @author Show
 * @Date 2011��5��31�� ���ڶ� 10:30:13
 * @version 1.0
 * @description:�ɱ������� Model�� ��������
 * ����ɱ�������(���ڳ����������)
 * ���ɱ�������
 */
class model_finance_costajust_costajust extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_finance_costajust";
		$this->sql_map = "finance/costajust/costajustSql.php";
		parent::__construct();
	}

	/**
	 * ��дadd
	 * @param $object
	 * @return bool
	 */
	function add_d($object) {
		$codeRuleDao = new model_common_codeRule();
		try {
			$this->start_d();

			$detail = $object['detail'];
			unset($object['detail']);

			//�Զ����������
			$object['formNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DLTZ');

			$newId = parent:: add_d($object, true);

			if (!empty($detail)) {
				$costajustDetailDao = new model_finance_costajust_detail();
				$costajustDetailDao->createBatch($detail, array('costajustId' => $newId), 'productId');
			}

			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��дget_d
	 * @param $id
	 * @param string $getType
	 * @param bool $isInit
	 * @return bool|mixed
	 */
	function get_d($id, $getType = 'main', $isInit = false) {
		$rs = parent::get_d($id);

		//���ӱ�
		if ($getType != 'main') {
			$costajustDetailDao = new model_finance_costajust_detail();
			$rs['detail'] = $costajustDetailDao->getDetail($id);
			if ($isInit == 'view') {
				$rs['detail'] = $costajustDetailDao->initView($rs['detail']);
			} else if ($isInit == 'edit') {
				$rs['detail'] = $costajustDetailDao->initEdit($rs['detail']);
			}
		}

		return $rs;
	}

	/**
	 * ��дget_d
	 * @param $stockbalId
	 * @param string $getType
	 * @param bool $isInit
	 * @return bool|mixed
	 */
	function getByStockBal_d($stockbalId, $getType = 'main', $isInit = false) {
		$rs = $this->find(array('stockbalId' => $stockbalId));

		//���ӱ�
		if ($getType != 'main') {
			$costajustDetailDao = new model_finance_costajust_detail();
			$rs['detail'] = $costajustDetailDao->getDetail($rs['id']);
			if ($isInit == 'view') {
				$rs['detail'] = $costajustDetailDao->initView($rs['detail']);
			} else if ($isInit == 'edit') {
				$rs['detail'] = $costajustDetailDao->initEdit($rs['detail']);
			}
		}

		return $rs;
	}

	/**
	 * ��дedit_d
	 * @param $object
	 * @return bool
	 */
	function edit_d($object) {
		try {
			$this->start_d();

			$detail = $object['detail'];
			unset($object['detail']);

			parent::edit_d($object, true);


			$payablesDetailDao = new model_finance_costajust_detail();
			$payablesDetailDao->deleteDetail($object['id']);
			if (!empty($detail)) {
				$payablesDetailDao->createBatch($detail, array('costajustId' => $object['id']), 'productId');
			}

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ����id����ȡ�ڳ����
	 * @param $ids
	 * @return mixed|null|string
	 */
	function getStockBalance_d($ids) {
		$stockBalanceDao = new model_finance_stockbalance_stockbalance();
		$rs = $stockBalanceDao->getStockBalance_d($ids);
		$rs = $stockBalanceDao->showStockBalance($rs);
		return $rs;
	}

	/**
	 * ������������
	 * @param $object
	 * @return bool
	 */
	function addInStockBal_d($object) {
		$stockBalIds = null;
		//������
		$stockBalanceDao = new model_finance_stockbalance_stockbalance();
		try {
			$this->start_d();

			//������������
			foreach ($object as $key => $val) {
				$val['formType'] = 'CBTZ-01';
				$val['detail'][1]['money'] = $val['detail'][1]['ajustAmount'] - $val['detail'][1]['balanceAmount'];
				unset($val['detail'][1]['balanceAmount']);
				unset($val['detail'][1]['ajustAmount']);

				if (!$rs = $this->find(array('stockbalId' => $val['stockbalId']), null, 'id')) {
					$this->add_d($val);
				} else {
					$val['id'] = $rs['id'];
					$this->edit_d($val);
				}

				if ($stockBalIds) {
					$stockBalIds .= ',' . $val['stockbalId'];
				} else {
					$stockBalIds = $val['stockbalId'];
				}
			}
			//�޸ĳ���״̬
			$stockBalanceDao->updateIsDeal_d($stockBalIds);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ɾ����¼�����޸��ڳ����״̬
	 * @param $id
	 * @param $stockbalId
	 * @return bool
	 */
	function deleteChange_d($id, $stockbalId) {
		try {
			$this->start_d();

			//�޸ĳ���״̬
			$stockBalanceDao = new model_finance_stockbalance_stockbalance();
			$stockBalanceDao->updateIsDeal_d($stockbalId, 0);
			//ɾ����¼
			$this->deletes($id);
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

    /**
     * ���ݵ���
     * @return array
     */
    function import_d() {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();

        // �ֿ���Ϣ
        $stockDao = new model_stock_stockinfo_stockinfo();
        $stockInfo = $stockDao->getStockMap_d();

        // ������Ϣ
        $productinfoDao = new model_stock_productinfo_productinfo();

        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    $tempArr['docCode'] = '��' . $actNum . '������';

                    // ��������
                    $object = array();

                    if (empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])) {
                        continue;
                    } else {
                        if ($val[0]) {
                            $object['formType'] = $val[0] == '���' ? 'CBTZ-01' : 'CBTZ-02';
                        } else {
                            $tempArr['result'] = '�������!δ��д��������';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($val[1]) {
                            if (!is_numeric($val[1])) {
                                $object['formDate'] = $val[1];
                            } else {
                                $object['formDate'] = util_excelUtil::exceltimtetophp($val[1]);
                            }
                        } else {
                            $tempArr['result'] = '�������!δ��д��������';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($val[2]) {
                            if (isset($stockInfo[$val[2]])) {
                                $object['stockName'] = $val[2];
                                $object['stockId'] = $stockInfo[$val[2]];
                            } else {
                                $tempArr['result'] = '�������!�����ڵĲֿ�';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['result'] = '�������!δ��д�����ֿ�';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($val[3]) {
                            $productInfo = $productinfoDao->find(array('productCode' => $val[3]), null,
                                'id,productName,pattern');
                            if ($productInfo) {
                                $object['detail'][0]['productNo'] = $val[3];
                                $object['detail'][0]['productId'] = $productInfo['id'];
                                $object['detail'][0]['productName'] = $productInfo['productName'];
                                $object['detail'][0]['productModel'] = $productInfo['pattern'];
                                $object['detail'][0]['remark'] = '����';
                            } else {
                                $tempArr['result'] = '�������!���ϲ�����';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['result'] = '�������!δ��д�����ֿ�';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($val[4]) {
                            $object['detail'][0]['money'] = $val[4];
                        } else {
                            $tempArr['result'] = '�������!δ��д�����߽��Ϊ0';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($this->add_d($object)) {
                            $tempArr['result'] = '����ɹ�!';
                        } else {
                            $tempArr['result'] = '����ʧ��!';
                        }
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }
}