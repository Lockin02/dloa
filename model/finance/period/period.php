<?php

/**
 * @author Show
 * @Date 2011年5月21日 星期六 14:47:06
 * @version 1.0
 * @description:财务会计期间表 Model层
 */
class model_finance_period_period extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_finance_accountingperiod";
        $this->sql_map = "finance/period/periodSql.php";
        $this->_isSetCompany = 1; //启用公司权限
        parent::__construct();
    }

    /**
     * 设置启用会计周期
     * @param $object
     * @return bool
     */
    function createPeriod_d($object)
    {
        try {
            $this->start_d();
            $this->createYearPeriod_d($object);
            $businessBelongID = (isset($object['thisBusinessBelong']) && $object['thisBusinessBelong'] != '')? $object['thisBusinessBelong'] : $_SESSION['USER_COM'];
            // 把之前周期内的当前周期改成否
            $this->update("thisYear < {$object['thisYear']} and isUsing = 1 and isCostUsing = 1 and businessBelong = '{$businessBelongID}'",
                array('isUsing' => 0, 'isCostUsing' => 0));
            // 更新新添周期的当前周期
            $this->update(array('thisYear' => $object['thisYear'], 'thisMonth' => $object['thisMonth'], 'businessBelong' => $businessBelongID),
                array('isCheckout' => 0, 'isUsing' => 1, 'isCostCheckout' => 0, 'isCostUsing' => 1, 'isFirst' => 1));
            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 查询年内是否有会计期间条目，如果没有，则创建本年内的会计条目
     * @param $object
     * @param bool|false $init
     * @return bool
     */
    function createYearPeriod_d($object, $init = false)
    {
        $businessBelongID = (isset($object['thisBusinessBelong']) && $object['thisBusinessBelong'] != '')? $object['thisBusinessBelong'] : $_SESSION['USER_COM'];
        $businessBelongName = (isset($object['thisBusinessBelongName']) && $object['thisBusinessBelongName'] != '')? $object['thisBusinessBelongName'] : $_SESSION['USER_COM_NAME'];
        $rs = $this->find(array('thisYear' => $object['thisYear'], 'businessBelong' => $businessBelongID), null, 'id');
        if (!$rs) {
            //获取公司名称
            $branchDao = new model_deptuser_branch_branch();
            $sql = 'INSERT INTO oa_finance_accountingperiod (periodNo,thisYear,thisMonth,isCheckout,isClosed,isCostCheckout,isCostClosed,thisDate,
            		formBelong,formBelongName,businessBelong,businessBelongName) VALUES ';
            for ($i = 1; $i <= 12; $i++) {
                $period = $object['thisYear'] . '.' . $i;
                $thisDate = $object['thisYear'] . '-' . $i . '-01';
                if ($i == 1) {
                    if ($object['thisMonth'] == 1) {
                        $sql .= " ('" . $period . "','" . $object['thisYear'] . "','" . $i . "',0,0,0,0,'" . $thisDate . "',
                            '" . $businessBelongID . "','" . $businessBelongName . "','" . $businessBelongID . "','" . $businessBelongName . "')";
                    } else {
                        if ($init) {
                            $sql .= " ('" . $period . "','" . $object['thisYear'] . "','" . $i . "',1,0,1,0,'" . $thisDate . "',
                            '" . $businessBelongID . "','" . $businessBelongName . "','" . $businessBelongID . "','" . $businessBelongName . "')";
                        } else {
                            $sql .= " ('" . $period . "','" . $object['thisYear'] . "','" . $i . "',0,0,0,0,'" . $thisDate . "',
                            '" . $businessBelongID . "','" . $businessBelongName . "','" . $businessBelongID . "','" . $businessBelongName . "')";
                        }
                    }
                } else if ($i < $object['thisMonth']) {
                    $sql .= " ,('" . $period . "','" . $object['thisYear'] . "','" . $i . "',0,0,0,0,'" . $thisDate . "',
                        '" . $businessBelongID . "','" . $businessBelongName . "','" . $businessBelongID . "','" . $businessBelongName . "')";
                } else {
                    if ($init) {
                        $sql .= " ,('" . $period . "','" . $object['thisYear'] . "','" . $i . "',1,0,1,0,'" . $thisDate . "',
                            '" . $businessBelongID . "','" . $businessBelongName . "','" . $businessBelongID . "','" . $businessBelongName . "')";
                    } else {
                        $sql .= " ,('" . $period . "','" . $object['thisYear'] . "','" . $i . "',0,0,0,0,'" . $thisDate . "',
                            '" . $businessBelongID . "','" . $businessBelongName . "','" . $businessBelongID . "','" . $businessBelongName . "')";
                    }
                }
            }
            $this->query($sql);
        }
        return true;
    }

    /**
     * 检测是否有上一期的会计信息
     * @param $id
     * @throws exception
     */
    function hasPrevious_d($id,$businessBelong = '')
    {
        $businessBelongID = ($businessBelong == '')? $_SESSION['USER_COM'] : $businessBelong;
        $rs = parent::get_d($id);
        if ($rs['thisMonth'] == 1) {
            $newYear = $rs['thisYear'] - 1;
            $isArr = $this->find(array('thisYear' => $newYear, 'thisMonth' => 12, 'businessBelong' => $businessBelongID));
        } else {
            $newMonth = $rs['thisMonth'] - 1;
            $isArr = $this->find(array('thisYear' => $rs['thisYear'], 'thisMonth' => $newMonth, 'businessBelong' => $businessBelongID));
        }

        if (!$isArr) {
            throw new exception;
        }
    }

    /**
     * 检测是否已经存在会计周期
     * @return bool|mixed
     */
    function isExistPeriod_d($year = '',$businessBelong = '')
    {
        if($year != ''&& $businessBelong != ''){
            return $this->find(array('businessBelong' => $businessBelong,'thisYear'=>$year), null, 'id');
        }else{
            return $this->find(array('businessBelong' => $_SESSION['USER_COM']), null, 'id');
        }
    }

    /**
     * 检测是否是财务初始期
     * @param $year
     * @param $month
     * @param string $type
     * @return bool|mixed
     */
    function isFirst_d($year, $month, $type = 'stock')
    {
        if ($type == 'stock') {
            return $this->find(array('thisYear' => $year, 'thisMonth' => $month, 'isFirst' => 1, 'isUsing' => 1, 'businessBelong' => $_SESSION['USER_COM']), null, 'id');
        } else {
            return $this->find(array('thisYear' => $year, 'thisMonth' => $month, 'isFirst' => 1, 'isCostUsing' => 1, 'businessBelong' => $_SESSION['USER_COM']), null, 'id');
        }
    }

    /**
     * 检测是否是财务初始期
     * @param $thisDate
     * @param string $type
     * @return bool|mixed
     */
    function isFirstDate_d($thisDate, $type = 'stock')
    {
        $dateArr = explode('-', $thisDate);
        $year = $dateArr[0];
        $month = $dateArr[1] * 1;
        if ($type == 'stock') {
            return $this->find(array('thisYear' => $year, 'thisMonth' => $month, 'isFirst' => 1, 'isUsing' => 1, 'businessBelong' => $_SESSION['USER_COM']), null, 'id');
        } else {
            return $this->find(array('thisYear' => $year, 'thisMonth' => $month, 'isFirst' => 1, 'isCostUsing' => 1, 'businessBelong' => $_SESSION['USER_COM']), null, 'id');
        }
    }

    /**
     * 检测是否大于财务周期
     * @param $thisDate
     * @param string $type
     * @return mixed
     */
    function isLaterPeriod_d($thisDate, $type = 'stock')
    {
        $this->searchArr['laterDate'] = $thisDate;
        if ($type == 'stock') {
            $this->searchArr['isUsing'] = 1;
        } else {
            $this->searchArr['isCostUsing'] = 1;
        }
        return $this->list_d();
    }

    /**
     * 返回当前财务期(数组)
     * @param int $type
     * @param string $thisType
     * @return bool|mixed
     */
    function rtThisPeriod_d($type = 1, $thisType = 'stock')
    {
        if ($thisType == 'stock') {
            $rs = $this->find(array('isUsing' => 1, 'businessBelong' => $_SESSION['USER_COM']), null, 'thisYear,thisMonth,thisDate');
        } else {
            $rs = $this->find(array('isCostUsing' => 1, 'businessBelong' => $_SESSION['USER_COM']), null, 'thisYear,thisMonth,thisDate');
        }
        if (empty($rs)) {
            if ($type == 1) {
                return array('thisYear' => date('Y'), 'thisMonth' => date('m') * 1, 'thisDate' => date('Y-m') . '-01',
                    'thisPeriod' => date('Y') . '.' . date('m') * 1
                );
            } else {
                return array('thisYear' => date('Y'), 'thisMonth' => date('m'), 'thisDate' => date('Y-m') . '-01',
                    'thisPeriod' => date('Y') . '.' . date('m') * 1
                );
            }
        } else {
            if ($type == 1) {
                $rs['thisPeriod'] = $rs['thisYear'] . '.' . $rs['thisMonth'];
                return $rs;
            } else {
                $rs['thisMonth'] = $rs['thisMonth'] > 9 ? $rs['thisMonth'] : '0' . $rs['thisMonth'];
                $rs['thisPeriod'] = $rs['thisYear'] . '.' . $rs['thisMonth'];
                return $rs;
            }
        }
    }

    /**
     * 返回当前是否处于关账态
     * @param string $type
     * @return bool|mixed
     */
    function isClosed_d($type = 'stock',$businessBelong = '')
    {
        $businessBelongID = ($businessBelong == '')? $_SESSION['USER_COM'] : $businessBelong;
        if ($type == 'stock') {
            return $this->find(array('isClosed' => 1, 'businessBelong' => $businessBelongID), null, 'id');
        } else {
            return $this->find(array('isCostClosed' => 1, 'businessBelong' => $businessBelongID), null, 'id');
        }
    }

    /**
     * 获取最新的5个会计期间
     * @param string $type
     * @return mixed
     */
    function getNextOneYearPeriod_d($type = 'stock')
    {
        $rs = $this->rtThisPeriod_d(1, $type);
        $periodInfo = $this->findSql("SELECT periodNo AS name,periodNo AS value FROM " . $this->tbl_name .
            " WHERE TO_DAYS(thisDate) >= TO_DAYS('" . $rs['thisDate'] . "') AND businessBelong = '" . $_SESSION['USER_COM'] . "' ORDER BY thisDate ASC LIMIT 12");
        $periodLength = count($periodInfo);
        if ($periodLength < 12) {
            $lastPeriod = $periodInfo[$periodLength - 1]['value'];
            $lastPeriodArr = explode('.', $lastPeriod);
            $thisYear = $lastPeriodArr[0];
            $thisMonth = $lastPeriodArr[1];
            while ($periodLength <= 12) {
                $thisMonth++;
                if ($thisMonth > 12) {
                    $thisYear++;
                    $thisMonth = 1;
                }
                $thiPeriod = $thisYear . '.' . $thisMonth;
                array_push($periodInfo, array(
                    'name' => $thiPeriod,
                    'value' => $thiPeriod
                ));
                $periodLength++;
            }
        }
        return $periodInfo;
    }

    /**
     * @param string $type
     * @return mixed
     */
    function getNextOnYearList_d($type = 'stock')
    {
        $periodInfo = $this->getNextOneYearPeriod_d($type);
        $newPeriodInfo = array();
        foreach ($periodInfo as $v) {
            $newPeriodInfo[$v['value']] = $v['value'];
        }
        return $newPeriodInfo;
    }

    /**
     * @param $periodDate
     * @return array
     */
    function periodChange($periodDate)
    {
        $timeStamp = strtotime($periodDate);
        return array(
            'thisPeriod' => date('Y.n', $timeStamp),
            'thisYear' => date('Y', $timeStamp),
            'thisMonth' => date('n', $timeStamp)
        );
    }

    /*********************结账部分处理***********************/

    /**
     * 结账
     * @param $id
     * @param string $type
     * @return bool
     */
    function checkout_d($id, $type = 'stock', $businessBelong = '')
    {
        $businessBelongID = ($businessBelong == '')? $_SESSION['USER_COM'] : $businessBelong;
        if ($type == 'stock') {
            $thisObj = array('id' => $id, 'isUsing' => 0, 'isCheckout' => 1, 'isClosed' => 0,
                'businessBelong' => $businessBelongID);
        } else {
            $thisObj = array('id' => $id, 'isCostUsing' => 0, 'isCostCheckout' => 1, 'isCostClosed' => 0,
                'businessBelong' => $businessBelongID);
        }

        try {
            $this->start_d();

            //改变结账状态
            parent::edit_d($thisObj);

            //进入下一期会计周期
            $this->beginNewPeriod_d($id, $type, $businessBelong);

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 反结账
     * @param $id
     * @param string $type
     * @return bool
     */
    function uncheckout_d($id, $type = 'stock',$businessBelong = '')
    {
        $businessBelongID = ($businessBelong == '')? $_SESSION['USER_COM'] : $businessBelong;
        if ($type == 'stock') {
            $thisObj = array('id' => $id, 'isUsing' => 0, 'isCheckout' => 0, 'isClosed' => 0,
                'businessBelong' => $businessBelongID);
        } else {
            $thisObj = array('id' => $id, 'isCostUsing' => 0, 'isCostCheckout' => 0, 'isCostClosed' => 0,
                'businessBelong' => $businessBelongID);
        }
        try {
            $this->start_d();

            //检测是否有上一期
            $this->hasPrevious_d($id, $businessBelong);

            //改变结账状态
            parent::edit_d($thisObj);

            //返回上一期会计周期
            $this->returnLastPeriod_d($id, $type, $businessBelong);

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }

    }

    /**
     * 结账时启用下一个会计周期
     * @param $id
     * @param string $type
     */
    function beginNewPeriod_d($id, $type = 'stock',$businessBelong = '')
    {
        $businessBelongID = ($businessBelong == '')? $_SESSION['USER_COM'] : $businessBelong;
        $branchDao = new model_deptuser_branch_branch();
        $branchArr = $branchDao->getByCode($businessBelongID);
        $businessBelongName = $branchArr['NameCN'];

        $rs = $this->get_d($id);
        if ($rs['thisMonth'] == 12) {
            $newYear = $rs['thisYear'] + 1;
            $this->createYearPeriod_d(array('thisYear' => $newYear,'thisBusinessBelong'=>$businessBelongID,'thisBusinessBelongName'=>$businessBelongName));
            if ($type == 'stock') {
                $this->update(array('thisYear' => $newYear, 'thisMonth' => 1, 'businessBelong' => $businessBelongID),
                    array('isUsing' => 1, 'isCheckout' => 0, 'isClosed' => 0));
            } else {
                $this->update(array('thisYear' => $newYear, 'thisMonth' => 1, 'businessBelong' => $businessBelongID),
                    array('isCostUsing' => 1, 'isCostCheckout' => 0, 'isCostClosed' => 0));
            }
        } else {
            $newMonth = $rs['thisMonth'] + 1;
            if ($type == 'stock') {
                $this->update(array('thisYear' => $rs['thisYear'], 'thisMonth' => $newMonth, 'businessBelong' => $businessBelongID),
                    array('isUsing' => 1, 'isCheckout' => 0, 'isClosed' => 0));
            } else {
                $this->update(array('thisYear' => $rs['thisYear'], 'thisMonth' => $newMonth, 'businessBelong' => $businessBelongID),
                    array('isCostUsing' => 1, 'isCostCheckout' => 0, 'isCostClosed' => 0));
            }
        }
    }

    /**
     * 反结账时回退到上一会计周期
     * @param $id
     * @param string $type
     */
    function returnLastPeriod_d($id, $type = 'stock',$businessBelong = '')
    {
        $businessBelongID = ($businessBelong == '')? $_SESSION['USER_COM'] : $businessBelong;
        //获取当前会计周期信息
        $rs = $this->get_d($id);
        if ($rs['thisMonth'] == 1) {
            $newYear = $rs['thisYear'] - 1;
            if ($type == 'stock') {
                $this->update(array('thisYear' => $newYear, 'thisMonth' => 12, 'businessBelong' => $businessBelongID),
                    array('isUsing' => 1, 'isCheckout' => 0, 'isClosed' => 0));
            } else {
                $this->update(array('thisYear' => $newYear, 'thisMonth' => 12, 'businessBelong' => $businessBelongID),
                    array('isCostUsing' => 1, 'isCostCheckout' => 0, 'isCostClosed' => 0));
            }
        } else {
            $newMonth = $rs['thisMonth'] - 1;
            if ($type == 'stock') {
                $this->update(array('thisYear' => $rs['thisYear'], 'thisMonth' => $newMonth, 'businessBelong' => $businessBelongID),
                    array('isUsing' => 1, 'isCheckout' => 0, 'isClosed' => 0));
            } else {
                $this->update(array('thisYear' => $rs['thisYear'], 'thisMonth' => $newMonth, 'businessBelong' => $businessBelongID),
                    array('isCostUsing' => 1, 'isCostCheckout' => 0, 'isCostClosed' => 0));
            }
        }
    }

    /********************关账部分处理***********************/

    /**
     * 关账
     * @param $id
     * @param string $type
     * @return mixed
     */
    function close_d($id, $type = 'stock',$businessBelong = '')
    {
        $businessBelongID = ($businessBelong == '')? $_SESSION['USER_COM'] : $businessBelong;
        if ($type == 'stock') {
            return $this->updateField(array('id' => $id, 'businessBelong' => $businessBelongID), 'isClosed', 1);
        } else {
            return $this->updateField(array('id' => $id, 'businessBelong' => $businessBelongID), 'isCostClosed', 1);
        }
    }

    /**
     * 反关账
     * @param $id
     * @param string $type
     * @return bool
     */
    function unclose_d($id, $type = 'stock',$businessBelong = '')
    {
        $businessBelongID = ($businessBelong == '')? $_SESSION['USER_COM'] : $businessBelong;
        if ($type == 'stock') {
            return $this->updateField(array('id' => $id, 'businessBelong' => $businessBelongID), 'isClosed', 0);
        } else {
            return $this->updateField(array('id' => $id, 'businessBelong' => $businessBelongID), 'isCostClosed', 0);
        }
    }

    /**
     * 合法周期内校验，要求输入的周期小于等于系统周期，通过验证返回true
     * @param $inPeriod
     * @param $sysPeriod
     * @return bool
     */
    function checkPeriodAllow_d($inPeriod, $sysPeriod)
    {
        // 输入值验证
        if (!$this->isPeriod_d($inPeriod)) {
            return false;
        }
        $inPeriodArr = explode(".", $inPeriod);
        $sysPeriodArr = explode(".", $sysPeriod);

        if ($inPeriodArr[0] < $sysPeriodArr[0]) {
            return false;
        } else if ($inPeriodArr[0] == $sysPeriodArr[0] && $inPeriodArr[1] < $sysPeriodArr[1]) {
            return false;
        }
        return true;
    }

    /**
     * 合法周期校验，通过验证返回true
     * @param $inPeriod
     * @return bool
     */
    function isPeriod_d($inPeriod)
    {
        $periodArr = explode(".", $inPeriod);
        if (count($periodArr) != 2) {
            return false;
        }

        if (!is_numeric($periodArr[0]) || $periodArr[0] <= 2010 || $periodArr[0] > 9999) {
            return false;
        }

        if (!is_numeric($periodArr[1]) || $periodArr[1] < 1 || $periodArr[1] > 12) {
            return false;
        }

        return true;
    }
}