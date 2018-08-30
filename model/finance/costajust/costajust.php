<?php

/**
 * @author Show
 * @Date 2011年5月31日 星期二 10:30:13
 * @version 1.0
 * @description:成本调整单 Model层 单据类型
 * 出库成本调整单(存在出库调整类型)
 * 入库成本调整单
 */
class model_finance_costajust_costajust extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_finance_costajust";
		$this->sql_map = "finance/costajust/costajustSql.php";
		parent::__construct();
	}

	/**
	 * 重写add
	 * @param $object
	 * @return bool
	 */
	function add_d($object) {
		$codeRuleDao = new model_common_codeRule();
		try {
			$this->start_d();

			$detail = $object['detail'];
			unset($object['detail']);

			//自动产生到款号
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
	 * 重写get_d
	 * @param $id
	 * @param string $getType
	 * @param bool $isInit
	 * @return bool|mixed
	 */
	function get_d($id, $getType = 'main', $isInit = false) {
		$rs = parent::get_d($id);

		//带从表
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
	 * 重写get_d
	 * @param $stockbalId
	 * @param string $getType
	 * @param bool $isInit
	 * @return bool|mixed
	 */
	function getByStockBal_d($stockbalId, $getType = 'main', $isInit = false) {
		$rs = $this->find(array('stockbalId' => $stockbalId));

		//带从表
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
	 * 重写edit_d
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
	 * 根据id串获取期初余额
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
	 * 批量新增单据
	 * @param $object
	 * @return bool
	 */
	function addInStockBal_d($object) {
		$stockBalIds = null;
		//余额对象
		$stockBalanceDao = new model_finance_stockbalance_stockbalance();
		try {
			$this->start_d();

			//生成余额调整单
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
			//修改出单状态
			$stockBalanceDao->updateIsDeal_d($stockBalIds);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 删除记录并且修改期初余额状态
	 * @param $id
	 * @param $stockbalId
	 * @return bool
	 */
	function deleteChange_d($id, $stockbalId) {
		try {
			$this->start_d();

			//修改出单状态
			$stockBalanceDao = new model_finance_stockbalance_stockbalance();
			$stockBalanceDao->updateIsDeal_d($stockbalId, 0);
			//删除记录
			$this->deletes($id);
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

    /**
     * 数据导入
     * @return array
     */
    function import_d() {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();

        // 仓库信息
        $stockDao = new model_stock_stockinfo_stockinfo();
        $stockInfo = $stockDao->getStockMap_d();

        // 物料信息
        $productinfoDao = new model_stock_productinfo_productinfo();

        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    $tempArr['docCode'] = '第' . $actNum . '条数据';

                    // 单据数据
                    $object = array();

                    if (empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])) {
                        continue;
                    } else {
                        if ($val[0]) {
                            $object['formType'] = $val[0] == '入库' ? 'CBTZ-01' : 'CBTZ-02';
                        } else {
                            $tempArr['result'] = '导入错误!未填写单据类型';
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
                            $tempArr['result'] = '导入错误!未填写单据日期';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($val[2]) {
                            if (isset($stockInfo[$val[2]])) {
                                $object['stockName'] = $val[2];
                                $object['stockId'] = $stockInfo[$val[2]];
                            } else {
                                $tempArr['result'] = '导入错误!不存在的仓库';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['result'] = '导入错误!未填写调整仓库';
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
                                $object['detail'][0]['remark'] = '导入';
                            } else {
                                $tempArr['result'] = '导入错误!物料不存在';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['result'] = '导入错误!未填写调整仓库';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($val[4]) {
                            $object['detail'][0]['money'] = $val[4];
                        } else {
                            $tempArr['result'] = '导入错误!未填写金额或者金额为0';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($this->add_d($object)) {
                            $tempArr['result'] = '导入成功!';
                        } else {
                            $tempArr['result'] = '导入失败!';
                        }
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("文件不存在可识别数据!");
            }
        } else {
            msg("上传文件类型不是EXCEL!");
        }
    }
}