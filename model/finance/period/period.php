<?php

/**
 * @author Show
 * @Date 2011��5��21�� ������ 14:47:06
 * @version 1.0
 * @description:�������ڼ�� Model��
 */
class model_finance_period_period extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_finance_accountingperiod";
        $this->sql_map = "finance/period/periodSql.php";
        $this->_isSetCompany = 1; //���ù�˾Ȩ��
        parent::__construct();
    }

    /**
     * �������û������
     * @param $object
     * @return bool
     */
    function createPeriod_d($object)
    {
        try {
            $this->start_d();
            $this->createYearPeriod_d($object);
            $businessBelongID = (isset($object['thisBusinessBelong']) && $object['thisBusinessBelong'] != '')? $object['thisBusinessBelong'] : $_SESSION['USER_COM'];
            // ��֮ǰ�����ڵĵ�ǰ���ڸĳɷ�
            $this->update("thisYear < {$object['thisYear']} and isUsing = 1 and isCostUsing = 1 and businessBelong = '{$businessBelongID}'",
                array('isUsing' => 0, 'isCostUsing' => 0));
            // �����������ڵĵ�ǰ����
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
     * ��ѯ�����Ƿ��л���ڼ���Ŀ�����û�У��򴴽������ڵĻ����Ŀ
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
            //��ȡ��˾����
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
     * ����Ƿ�����һ�ڵĻ����Ϣ
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
     * ����Ƿ��Ѿ����ڻ������
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
     * ����Ƿ��ǲ����ʼ��
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
     * ����Ƿ��ǲ����ʼ��
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
     * ����Ƿ���ڲ�������
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
     * ���ص�ǰ������(����)
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
     * ���ص�ǰ�Ƿ��ڹ���̬
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
     * ��ȡ���µ�5������ڼ�
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

    /*********************���˲��ִ���***********************/

    /**
     * ����
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

            //�ı����״̬
            parent::edit_d($thisObj);

            //������һ�ڻ������
            $this->beginNewPeriod_d($id, $type, $businessBelong);

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ������
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

            //����Ƿ�����һ��
            $this->hasPrevious_d($id, $businessBelong);

            //�ı����״̬
            parent::edit_d($thisObj);

            //������һ�ڻ������
            $this->returnLastPeriod_d($id, $type, $businessBelong);

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }

    }

    /**
     * ����ʱ������һ���������
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
     * ������ʱ���˵���һ�������
     * @param $id
     * @param string $type
     */
    function returnLastPeriod_d($id, $type = 'stock',$businessBelong = '')
    {
        $businessBelongID = ($businessBelong == '')? $_SESSION['USER_COM'] : $businessBelong;
        //��ȡ��ǰ���������Ϣ
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

    /********************���˲��ִ���***********************/

    /**
     * ����
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
     * ������
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
     * �Ϸ�������У�飬Ҫ�����������С�ڵ���ϵͳ���ڣ�ͨ����֤����true
     * @param $inPeriod
     * @param $sysPeriod
     * @return bool
     */
    function checkPeriodAllow_d($inPeriod, $sysPeriod)
    {
        // ����ֵ��֤
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
     * �Ϸ�����У�飬ͨ����֤����true
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