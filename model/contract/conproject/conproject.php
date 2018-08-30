<?php

/**
 * @author liub
 * @Date 2014��5��29�� 14:50:09
 * @version 1.0
 * @description:��ͬ��Ŀ�� Model��
 */
class model_contract_conproject_conproject extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_contract_project";
        $this->sql_map = "contract/conproject/conprojectSql.php";
        parent::__construct();

        //�·��ֶ�ת������
        $this->monthStrArr = array(
            "01" => "monthJan",
            "02" => "monthFeb",
            "03" => "monthMar",
            "04" => "monthApr",
            "05" => "monthMay",
            "06" => "monthJun",
            "07" => "monthJul",
            "08" => "monthAug",
            "09" => "monthSep",
            "10" => "monthOct",
            "11" => "monthNov",
            "12" => "monthDec"
        );
        $this->dateSection = $this->getSection();
    }

    /**
     * ��Ŀ������ɹ���
     *
     */
    function getProCode($contractCode, $module)
    {
//         $lineArr = array(
//             "GCSCX-01" => "1",
//             "GCSCX-02" => "2",
//             "GCSCX-06" => "3",
//             "GCSCX-04" => "4",
//             "GCSCX-08" => "5",
//             "GCSCX-09" => "6",
//         );
//         $billCode = $contractCode . $lineArr[$lineCode] . "4";
        // ��������������Ϊ���ְ��
        $moduleDatadict = $this->getDatadicts(array('HTBK'));
        $moduleDatadict = $moduleDatadict['HTBK'];
        $moduleCode = "";
        foreach ($moduleDatadict as $v) {
            if ($v['dataCode'] == $module) {
                $moduleCode = $v['expand1'];
                break;
            }
        }
        $billCode = $contractCode . $moduleCode . "4";

        $sql = "select max(RIGHT(c.projectCode,1)) as maxCode,substr(c.projectCode,1,length(c.projectCode)-1) as _maxbillCode
				from oa_contract_project c group by _maxbillCode having _maxbillCode='" . $billCode . "'";

        $resArr = $this->findSql($sql);
        $res = $resArr[0];
        if (is_array($res)) {
            $maxCode = $res['maxCode'];
            $newNum = $maxCode + 1;
            $billCode .= $newNum;
        } else {
            $billCode .= "0";
        }

        return $billCode;
    }


    /**
     * ������ ��Ʒ���ɺ�ͬ��Ŀ�ӿ�
     */
    function createProjectBySale_d($cid, $initTip = "0")
    {
        $conProDao = new model_contract_contract_product();
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($cid);
        $arr = $conProDao->getDetail_d($cid);
        if((strpos($conArr['customerTypeName'],"�ӹ�˾")!== false && $conArr['customerTypeName']!='�ӹ�˾-����') || strpos($conArr['customerTypeName'],"ĸ��˾")!== false || strpos($conArr['customerTypeName'],"����")!== false){
            return false;
        }
        foreach ($arr as $key => $val) {
            //�жϲ�Ʒ���
            $proId = $val['conProductId'];
            $Pid = $val['id'];
            if (!empty ($proId)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($proId))";
                //��һ�β��ң����˳������Ѿ��������������
                $goodsTypeA = $this->_db->getArray($sqlA);
                if ($goodsTypeA[0]['parentId'] != "-1" && $goodsTypeA[0]['id'] != "") {
                    $goodsId = $goodsTypeA[0]['id'];
                    //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
                    $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                        "where id in ($goodsId)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                    $goodsTypeB = $this->_db->getArray($sqlB);
                    $goodsTypeId = $goodsTypeB[0]['id'];
                } else {
                    $goodsTypeId = $goodsTypeA[0]['id'];
                }
            }
            //�ж��Ƿ�Ϊ�������Ʒ������������
            if ($goodsTypeId == isSell) {
                //��Ʒ��
                $sqlf = "select newProLineName,newProLineCode from oa_contract_product  where id = '" . $Pid . "'";
                $exeDeptNameArr = $this->_db->getArray($sqlf);
                $costInfoArr[] = array(
                    "productLine" => $exeDeptNameArr[0]['newProLineCode'],
                    "productLineName" => $exeDeptNameArr[0]['newProLineName'],
                    "confirmMoney" => $val['money']
                );
            }
        }
        //������ͬ��Ʒ�ߵĽ������
        $ResultArr = array();
        foreach ($costInfoArr as $value) {
            $line = $value['productLine'];
            $lineName = $value['productLineName'];
            $sum = $value['confirmMoney'];
            if (array_key_exists($line, $ResultArr)) {
                $ResultArr[$line] = bcadd($ResultArr[$line], $sum, 2);
            } else {
                $ResultArr[$line] = $sum;
            }
        }
        //����������Ŀ����
        $datadictDao = new model_system_datadict_datadict();
        $costDao = new model_contract_contract_cost();
//        $deptDao = new model_deptuser_dept_dept();
        $datadictDao = new model_system_datadict_datadict();

        $invoiceArr = $conDao->get_d($cid);
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        foreach ($typeArr as $key => $val) {
            foreach ($val as $k => $v) {
                $valArrs[] = $v['expand1'];
                $invoicTypeArr[$v['dataCode']] = $v['expand1'];
            }
        }
//        $invoiceCodeArr = explode(",", $invoiceArr['invoiceCode']);
//        $invoiceValueArr = explode(",", $invoiceArr['invoiceValue']);

        // ��Ϊ��Ʊ����ǰ�����һ������Ҫ��ʾ�����,���Կ�Ʊ�������ǰ��Ҫ��һ���յ�Ԫ��,�Ա��ڿ�Ʊ���͵�����˳����ƥ��
//        array_unshift($invoiceValueArr,'');

        $conDao = new model_contract_contract_contract();
        $conInvoiceArr = $conDao->makeInvoiceValueArr($invoiceArr);

        $rows = $conDao->getContractInfo($cid);
        //�����������ĺ�ͬ���������޳ɱ�
        //��Ʊ������
        $i = 0;
        $typeMoney = 0;
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// ��Ʊ���ͺ�������Ʊ���ĺ�ֱͬ���ú�ͬ������
                $typeMoney = isset($invoiceArr['contractMoney'])? $invoiceArr['contractMoney'] : 0;
            }else{
                foreach ($conInvoiceArr as $k => $v) {
                    if ($v >= 0) {
                        $rate = $invoicTypeArr[$k] / 100;
                        $rates = 1 + $rate;
                        $typeMoney += bcdiv($v, $rates, 8);
                    }
                }
            }
        }
//        foreach ($invoiceValueArr as $k => $v) {
//            if (!empty($v)) {
//                $rate = $valArrs[$i] / 100;
//                $rates = 1 + $rate;
//                $typeMoney += $v / $rates;
//            }
//            $i++;
//        }

        foreach ($ResultArr as $k => $v) {
            // ��Ʒ�࣬��ȡ��ͬ��Ʒִ������
            $rs = $conProDao->find(array('contractId' => $cid, 'newProLineCode' => $k,
                'proTypeId' => '11', 'isDel' => '0'), null, 'exeDeptId,exeDeptName');

            $officeid        = empty($rs['exeDeptId']) ? '' : $rs['exeDeptId']; //ִ������ID
            $officeName      = empty($rs['exeDeptName']) ? '' : $rs['exeDeptName']; //ִ������

            //���ݺ�ͬid����Ʒ�߱������ȷ��������
            $costArr = $costDao->findMoneyByLine($conArr['id'], $k);
            if (!empty($costArr)) {
                $confirmMoney = $costArr['confirmMoney'];
            }
            if ($conArr['currency'] != '�����') {
                $cM = $conArr['contractMoneyCur'];
            } else {
                $cM = $conArr['contractMoney'];
            }
            $proportion = $this->getProportion($v, $cM);
            $proMoney = ($proportion / 100) * $conArr['contractMoney'];
            $exGrossTemp = bcdiv(($typeMoney - $confirmMoney), $typeMoney, 4);
            $exGross = bcmul($exGrossTemp, '100', 2);

            //�ж��Ƿ��Ѵ�����Ŀ
            $isShowSql = "select * from oa_contract_project where contractId='".$conArr['id']."' and proLineCode='".$k."'";
            $isShow = $this->_db->getArray($isShowSql);


//            $costLimitNameArr = $deptDao->getDeptById($k);
//            $costLimitName = $costLimitNameArr['DEPT_NAME'];

            // �жϹ�����ͬ�Ƿ���ڲ���Ʊ�Ŀ�Ʊ����,
            $invoiceCodeArr = explode(",",$conArr['invoiceCode']);
            $isNoInvoiceCont = false;
            foreach ($invoiceCodeArr as $Arrk => $Arrv){
                if($Arrv == "HTBKP"){
                    $isNoInvoiceCont = true;
                }
            }

            $costLimitName = $datadictDao->getDataNameByCode($k);
            $temp['projectCode'] = $this->getProCode($conArr['contractCode'], $conArr['module']); //��Ŀ��
            $temp['projectName'] = $conArr['contractName']; //��Ŀ���ƣ����ú�ͬ����
            $temp['contractId'] = $conArr['id']; //��ͬid
            $temp['contractCode'] = $conArr['contractCode']; //��ͬ���
            $temp['contractName'] = $conArr['contractName']; //��ͬ����
            $temp['contractMoney'] = $conArr['contractMoney']; //��ͬ��
            $temp['proLineCode'] = $k; //��Ʒ�߱���
            $temp['proLineName'] = $costLimitName; //��Ʒ������
            $temp['proLineMoney'] = $v; //��Ʒ�߽��
            $temp['proportion'] = $proportion; //ռ�ȣ���Ʒ�߽��/��ͬ�
            $temp['schedule'] = ""; //��Ŀ����
            $temp['earnings'] = ""; //��Ŀ����
            $temp['earningsTypeName'] = ($isNoInvoiceCont)? "����Ʊ����" : $this->getEarningsType($cid); //����ȷ�Ϸ�ʽ1
            $temp['earningsTypeCode'] = ""; //����ȷ�Ϸ�ʽ����
            $temp['estimates'] = $confirmMoney; //����
            $temp['budget'] = " "; //Ԥ�㣨�ɱ���
            $temp['cost'] = ""; //����
            $temp['planBeginDate'] = ""; //Ԥ�ƿ�ʼʱ��
            $temp['planEndDate'] = ""; //Ԥ�ƽ���ʱ��
            $temp['actBeginDate'] = ""; //ʵ�ʿ�ʼʱ��
            $temp['actEndDate'] = ""; //ʵ�ʽ���ʱ��
            $temp['state'] = "0"; //��Ŀ״̬
            $temp['proMoney'] = $proMoney; //��ռ��ͬ��
            $temp['exgross'] = $exGross; //ë����
            $temp['module'] = $conArr['module']; //�����
            $temp['moduleName'] = $conArr['moduleName']; //�������
            $temp['areaCode'] = $conArr['areaCode']; //
            $temp['areaName'] = $conArr['areaName']; //1
            $temp['officeId'] = $officeid; //1
            $temp['officeName'] = $officeName; //1
            $temp['exeAreaId'] = $officeid; //1
            $temp['exeArea'] = $officeName; //1
            $temp['initTip'] = $initTip; //��ʼ����ʶ

            // ����ȷ�Ϸ�ʽ����
            switch ($temp['earningsTypeName']){
                case '����������':
                    $temp['earningsTypeCode'] = 'HSFS-FHJD';
                    break;
                case '����Ʊ����':
                    $temp['earningsTypeCode'] = 'HSFS-FPJD';
                    break;
                case '��������Ŀ����':
                    $temp['earningsTypeCode'] = 'HSFS-XMJD';
                    break;
                case '����Ʒ������':
                    $temp['earningsTypeCode'] = 'HSFS-CPZL';
                    break;
            }

            //������Ŀ
            if(empty($isShow)){
                $this->add_d($temp, true);
            }
        }

    }

    //�����ͬռ��
    function getProportion($proMoney, $conMoney, $scale = 10)
    {
        $exGrossTemp = bcdiv($proMoney, $conMoney, 10);
        $exGross = round(bcmul($exGrossTemp, '100', 9), 10);
        return $exGross;
    }


    /**
     * �������з� ������Ŀ�ӿ�
     * @param  array (
     *   "projectCode"=>"��Ŀ���",
     *   "projectName"=>"��Ŀ���",
     *   "state"=>"��Ŀ״̬"
     *   "contractId"=>"��ͬid",
     *   "esmProjectId"=>"����������Ŀid",
     *   "proLineCode"=>"��Ʒ�߱���",
     *   "proLineName"=>"��Ʒ������",
     *   "proportion"=>"ռ��",
     *   "estimates"=>"����",
     *   "planBeginDate"=>"Ԥ�ƿ�ʼʱ��",
     *   "planEndDate"=>"Ԥ�ƽ���ʱ��",
     * )
     */
    function createProjectBySer_d($arr, $initTip = "0")
    {
        //2017-07-17 pms 205 ȡ����������Ŀ���ɽӿ�
        return true;
//        $conDao = new model_contract_contract_contract();
//        $conArr = $conDao->get_d($arr['contractId']);
//
//        $invoiceArr = $conDao->get_d($arr['contractId']);
//
//        $dataDao = new model_system_datadict_datadict();
//        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
//
//        foreach ($typeArr as $key => $val) {
//            foreach ($val as $k => $v) {
//                $valArrs[] = $v['expand1'];
//            }
//        }
//        $invoiceCodeArr = explode(",", $invoiceArr['invoiceCode']);
//        $invoiceValueArr = explode(",", $invoiceArr['invoiceValue']);
//
//        //        $rows = $conDao->getContractInfo($arr['contractId']);
//        //�����������ĺ�ͬ���������޳ɱ�
//        //��Ʊ������
//        $i = 0;
//        $typeMoney = 0;
//        foreach ($invoiceValueArr as $k => $v) {
//            if (!empty($v)) {
//                $rate = $valArrs[$i] / 100;
//                $rates = 1 + $rate;
//                $typeMoney += $v / $rates;
//            }
//            $i++;
//        }
//
//        if (!empty($arr)) {
//
//            //���ݺ�ͬid����Ʒ�߱������ȷ��������
//            $costDao = new model_contract_contract_cost();
//            $costArr = $costDao->findMoneyByLine($conArr['id'], $arr['proLineCode']);
//            if (!empty($costArr)) {
//                $confirmMoney = $costArr['confirmMoney'];
//            }
//            $proportion = $arr['proportion'];
//            $proMoney = ($proportion / 100) * $conArr['contractMoney'];
//            $exGrossTemp = bcdiv(($typeMoney - $confirmMoney), $typeMoney, 4);
//            $exGross = bcmul($exGrossTemp, '100', 2);
//
//            $temp['projectCode'] = $arr['projectCode']; //��Ŀ��
//            $temp['projectName'] = $arr['projectName']; //��Ŀ����
//            $temp['state'] = $arr['state']; //��Ŀ״̬
//            $temp['contractId'] = $arr['contractId']; //��ͬid
//            $temp['esmProjectId'] = $arr['esmProjectId']; //��ͬid
//            $temp['contractCode'] = $conArr['contractCode']; //��ͬ���
//            $temp['contractName'] = $conArr['contractName']; //��ͬ����
//            $temp['contractMoney'] = $conArr['contractMoney']; //��ͬ��
//            $temp['proLineCode'] = $arr['proLineCode']; //��Ʒ�߱���
//            $temp['proLineName'] = $arr['proLineName']; //��Ʒ������
//            $temp['proportion'] = $arr['proportion']; //ռ��
//            $temp['schedule'] = $arr['schedule']; //��Ŀ����
//            $temp['earnings'] = ""; //��Ŀ����
//            $temp['earningsTypeName'] = ""; //����ȷ�Ϸ�ʽ
//            $temp['earningsTypeCode'] = ""; //����ȷ�Ϸ�ʽ����
//            $temp['estimates'] = $confirmMoney; //����
//            $temp['budget'] = $arr['budget']; //Ԥ�㣨�ɱ���
//            $temp['cost'] = $arr['cost']; //����
//            $temp['planBeginDate'] = $arr['planBeginDate']; //Ԥ�ƿ�ʼʱ��
//            $temp['planEndDate'] = $arr['planEndDate']; //Ԥ�ƽ���ʱ��
//            $temp['actBeginDate'] = $arr['actBeginDate']; //ʵ�ʿ�ʼʱ��
//            $temp['actEndDate'] = $arr['actEndDate']; //ʵ�ʽ���ʱ��
//            $temp['state'] = "0"; //��Ŀ״̬
//            $temp['proMoney'] = $proMoney; //��ռ��ͬ��
//            $temp['exgross'] = $exGross; //ë����
//
//            $temp['initTip'] = $initTip; //��ʼ����ʶ
//
//            //������Ŀ
//            $this->add_d($temp, true);
//        }
//        //���º�ͬ�ڵ� ���ȣ�ռ��ֵ
//        $this->updateProInfoByCid($arr['contractId']);
    }

    /**
     * ������ ������Ŀ���ȣ��������Ϣ
     * @param  $pid ����������Ŀid
     *   "cnotractId" => ��ͬid
     *   "esmProjectId"=>"����������Ŀid",
     *   "proportion"=>"ռ��",
     *   "schedule"=>"��Ŀ����",
     *   "estimates"=>"����",
     *   "budget"=>"Ԥ��",
     *   "cost"=>"����",
     *   "actBeginDate"=>"ʵ�ʿ�ʼʱ��",
     *   "actEndDate"=>"ʵ�ʽ���ʱ��",
     */
    function updateConProScheduleByEsmId($arr)
    {
        if (!empty($arr)) {
            $proportion = $arr['proportion'];
            $schedule = $arr['schedule'];
            $estimates = $arr['estimates'];
            $budget = $arr['budget'];
            $cost = $arr['cost'];
            $actBeginDate = $arr['actBeginDate'];
            $actEndDate = $arr['actEndDate'];
            $esmProjectId = $arr['esmProjectId'];


            $arr = array(
                "proportion" => $proportion,
                "schedule" => $schedule,
                "estimates" => $estimates,
                "budget" => $budget,
                "cost" => $cost,
                "actBeginDate" => $actBeginDate,
                "actEndDate" => $actEndDate
            );

            $condition = array("esmProjectId" => $esmProjectId);
            $this->update($condition, $arr);

            //��������
            //            $fsql = "select id from oa_contract_project where esmProjectId='".$esmProjectId."'";
            //            $ar = $this->_db->getArray($fsql);
            //            $this->updateEarningsById($ar[0]['id']);
            //���º�ͬ�ڵ� ���ȣ�ռ��ֵ
            $this->updateProInfoByCid($arr['contractId']);
        }
    }

    /**
     * ������ ������Ŀ���Ƚӿ�
     * @param  $cid ��ͬid
     */
    function updateConProScheduleByCid($cid)
    {
        $equDao = new model_contract_contract_equ();
        $productDao = new model_contract_contract_product();
        $equArr = $equDao->getDetail_d($cid);
        if (!empty($equArr)) {
            foreach ($equArr as $k => $v) {
                if (!empty($v['conProductId'])) {
                    $proId = $v['conProductId'];
                } else if (!empty($v['proId'])) {
                    $proId = $v['proId'];
                } else {
                    $proId = null;
                }
                if ($proId) {
                    $proArr = $productDao->get_d($proId);
                    $pId = $proArr['conProductId'];
                    //��Ʒ��
                    $sqlf = "select exeDeptName,exeDeptCode from oa_goods_base_info  where id = '" . $pId . "'";
                    $exeDeptNameArr = $this->_db->getArray($sqlf);
                    $costInfoArr[] = array(
                        "productLine" => $exeDeptNameArr[0]['exeDeptCode'],
                        "productLineName" => $exeDeptNameArr[0]['exeDeptName'],
                        "number" => $v['number'],
                        "executedNum" => $v['executedNum'],
                        "backNum" => $v['backNum']
                    );
                }
            }
        }
        //������ͬ��Ʒ�ߵĽ������
        $ResultArr = array();
        foreach ($costInfoArr as $value) {
            $line = $value['productLine'];
            $lineName = $value['productLineName'];
            $number = $value['number'];
            $exeNum = $value['executedNum'] - $value['backNum'];
            if (array_key_exists($line, $ResultArr)) {
                $ResultArr[$line]['num'] += $number;
                $ResultArr[$line]['exeNum'] += $exeNum;
            } else {
                $ResultArr[$line]['num'] = $number;
                $ResultArr[$line]['exeNum'] = $exeNum;
            }
        }
        //ѭ������ͬid�Ͳ�Ʒ�߱������ ��Ŀ����
        foreach ($ResultArr as $k => $v) {
            $scheduleTemp = bcdiv($v['exeNum'], $v['num'], 4);
            $schedule = bcmul($scheduleTemp, '100', 2);
            $tempSql = "update oa_contract_project set schedule = '" . $schedule . "' where contractId='" . $cid . "' and proLineCode='" . $k . "'";
            $this->_db->query($tempSql);
        }

        //��������
        //      $fsql = "select id from oa_contract_project where contractId='".$cid."' and proLineCode='".$k."'";
        //      $ar = $this->_db->getArray($fsql);
        //       $this->updateEarningsById($ar[0]['id']);

        //���º�ͬ�ڵ� ���ȣ�ռ��ֵ
        $this->updateProInfoByCid($cid);
    }


    /**
     * ��ִͬ��״������Ŀ���
     */
    function prolist($contractId)
    {
        $sql = "select c.projectCode ,c.proLineName ,c.proportion ,c.schedule ,c.earnings  ,c.estimates ,c.budget ,c.cost " .
            "from oa_contract_project c where contractId = '" . $contractId . "'";
        $rows = $this->_db->getArray($sql);
        if ($rows) {
            $i = 0; //�б��¼���
            $sNum = $i + 1;
            $str = ""; //���ص�ģ���ַ���
            foreach ($rows as $key => $val) {
                $str .= <<<EOT
                    <tr>
                        <td>$val[projectCode]</td>
                        <td>$val[proLineName]</td>
                        <td>$val[proportion]%</td>
                        <td>
                          <div style="width:auto;height:auto;border:1px solid #ccc;padding: 0px;">
                             <div style="width:$val[schedule]%;background:#66FF66;white-space:nowrap;padding: 0px;">
                             $val[schedule] %
                             </div>
                          </div>
                        </td>
                        <td>$val[estimates]</td>
                        <td>$val[budget]</td>
                        <td>$val[cost]</td>
                        <td>$val[earnings]</td>
                    </tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /*
     * ���ݺ�ͬid �����ܵĺ�ͬ����
     */
    function getConduleBycid($cid)
    {
        $sql = "select c.projectCode ,c.proLineName ,c.proportion ,c.schedule ,c.earnings  ,c.estimates ,c.budget ,c.cost " .
            "from oa_contract_project c where contractId = '" . $cid . "'";
        $rows = $this->_db->getArray($sql);
        if ($rows) {
            $condule = "";
            foreach ($rows as $key => $val) {
                $schedule = bcdiv($val['schedule'], '100', 2);
                $proportion = bcdiv($val['proportion'], '100', 2);
                $exGrossTemp = bcmul($schedule, $proportion, 4);
                $condule += $exGrossTemp;
            }
            $condule = bcmul($condule, '100', 2);
        }

        return $condule;
    }

    /**
     * ���ݺ�ͬid ���� ��ͬ�ڵ� ���������Ⱥ�ռ��
     */
    function updateProInfoByCid($cid)
    {
        $sql = "select c.projectCode ,c.proLineName ,c.proportion ,c.schedule ,c.earnings  ,c.estimates ,c.budget ,c.cost " .
            "from oa_contract_project c where contractId = '" . $cid . "'";
        $rows = $this->_db->getArray($sql);
        if ($rows) {
            $condule = "";
            foreach ($rows as $key => $val) {
                $schedule = bcdiv($val['schedule'], '100', 2);
                $proportion = bcdiv($val['proportion'], '100', 2);
                $exGrossTemp = bcmul($schedule, $proportion, 4);
                $condule += $exGrossTemp;
            }
            $condule = bcmul($condule, '100', 2); //����
        }
        $sql2 = "select sum(proportion) as proportion,contractId from oa_contract_project where contractId = '" . $cid . "' GROUP BY contractId";
        $arr = $this->_db->getArray($sql2);
        $proportion = $arr[0]['proportion']; //ռ��

        $conDao = new model_contract_contract_contract();
        $condition = array("id" => $cid);
        $valArr = array(
            "projectProcessAll" => $condule,
            "projectRate" => $proportion
        );
        $conDao->update($condition, $valArr);

    }


    /**
     * ���ݺ�ͬ��Ŀid ���º�ͬ��Ŀ����
     */
    function updateEarningsById($pid)
    {
        $dao = new model_contract_conproject_conprojectRecord();
        $conDao = new model_contract_contract_contract();
//        $arr = $dao->get_d($id);
//        $conArr = $conDao->get_d($arr['contractId']);
//        $pid = $arr['pid'];
        if (!empty($arr['earningsTypeCode'])) {
            switch ($arr['earningsTypeCode']) {
                case "HSFS-XMJD" :
                    $XMJD = $arr['schedule'] / 100;
                    $FPJD = bcdiv($conArr['invoiceMoney'], $conArr['contractMoney'], 2);
                    $moeny = bcdiv($arr['proMoney'], 1.06, 2); //˰������
                    if ($FPJD == 1) {
                        $JD = $XMJD;
                    } else if ($FPJD <= 0.98) {
                        if ($XMJD > 0.98) {
                            $JD = 0.98;
                        } else {
                            $JD = $XMJD;
                        }
                    } else if ($FPJD > 0.98 && $FPJD != 1) {
                        if ($FPJD >= $XMJD) {
                            $JD = $XMJD;
                        } else {
                            $JD = $FPJD;
                        }
                    }
                    $earnings = bcmul($JD, $moeny, 2);
                    break;
                case "HSFS-FPJD" :
                    $moeny = bcdiv($arr['proMoney'], 1.17, 2); //˰������
                    $JD = bcdiv($conArr['invoiceMoney'], $conArr['contractMoney'], 2);
                    $earnings = bcmul($JD, $moeny, 2);
                    break;
            }

            //			$earnings = bcmul($JD, $arr['proMoney'], 2);
            $sql = "update oa_contract_project set earnings = '" . $earnings . "' where id = '" . $pid . "'";
            $sql2 = "update oa_contract_project_record set earnings = '" . $earnings . "' where id = '" . $id . "'";
            $this->_db->query($sql);
            $this->_db->query($sql2);
        }
    }

    //

    /**
     * ȷ��������㷽ʽ
     */
    function incomeAccEdit_d($obj)
    {
        try {
            $this->start_d();
            $nameArr = array(
                "HSFS-GCJD" => "��������Ŀ����",
                "HSFS-FHJD" => "����������",
                "HSFS-FPJD" => "����Ʊ����",
                "HSFS-CPZL" => "����Ʒ������"
            );
            $code = $obj['earningsTypeCode'];
            $obj['earningsTypeName'] = $nameArr[$code];
//            $id = $obj['id'];
            $pid = $obj['id'];

            $updateValArr = array(
                "earningsTypeCode" => $obj['earningsTypeCode'],
                "earningsTypeName" => $nameArr[$code]
            );

            $this->update(array('id' => $pid), $updateValArr);
//            $recordDao = new model_contract_conproject_conprojectRecord();
//            $recordDao->update(array('id' => $id), $updateValArr);

            $this->updateEarningsById($pid);

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }

    }

    /**
     * Ȩ������
     * Ȩ�޷��ؽ������:
     * �������Ȩ�ޣ�����true
     * �����Ȩ��,����false
     */
    function initLimit($customSql = null)
    {
        //Ȩ������
        $limitArr = array();
        //Ȩ��ϵͳ
        if (isset ($this->this_limit['��Ʒ��Ȩ��']) && !empty ($this->this_limit['��Ʒ��Ȩ��']))
            $limitArr['proLine'] = $this->this_limit['��Ʒ��Ȩ��'];
        if (isset ($this->this_limit['ִ������Ȩ��']) && !empty ($this->this_limit['ִ������Ȩ��']))
            $limitArr['exeDept'] = $this->this_limit['ִ������Ȩ��'];
        if (isset ($this->this_limit['���Ȩ��']) && !empty ($this->this_limit['���Ȩ��']))
            $limitArr['module'] = $this->this_limit['���Ȩ��'];
        if (strstr($limitArr['proLine'], ';;') && strstr($limitArr['exeDept'], ';;') && strstr($limitArr['module'], ';;')) {
            return true;
        } else {
            if (!empty ($limitArr['proLine']) && !empty ($limitArr['exeDept']) && !empty ($limitArr['module'])) {
                //���û��Ȩ��
                $sqlStr = "sql: ";
                if (!empty($limitArr['proLine']) && strstr($limitArr['proLine'], ';;') == false) {
                    $sqlStr .= " and ( ";
                    $LimitArr = explode(",", $limitArr['proLine']);
                    foreach ($LimitArr as $k => $v) {
                        if ($k == 0) {
                            $sqlStr .= "FIND_IN_SET('$v',c.proLineCode)";
                        } else {
                            $sqlStr .= " or FIND_IN_SET('$v',c.proLineCode)";
                        }
                        $k++;
                    }
                    unset($limitArr['proLine']);
                    $sqlStr .= ")";
                }
                if (!empty($limitArr['exeDept']) && strstr($limitArr['exeDept'], ';;') == false) {
                    $sqlStr .= " and ( ";
                    $LimitArr = explode(",", $limitArr['exeDept']);
                    foreach ($LimitArr as $k => $v) {
                        if ($k == 0) {
                            $sqlStr .= "FIND_IN_SET('$v',c.officeId)";
                        } else {
                            $sqlStr .= " or FIND_IN_SET('$v',c.officeId)";
                        }
                        $k++;
                    }
                    unset($limitArr['exeDept']);
                    $sqlStr .= ")";
                }
                if (!empty($limitArr['module']) && strstr($limitArr['module'], ';;') == false) {
                    $sqlStr .= " and ( ";
                    $LimitArr = explode(",", $limitArr['module']);
                    foreach ($LimitArr as $k => $v) {
                        if ($k == 0) {
                            $sqlStr .= "FIND_IN_SET('$v',c.module)";
                        } else {
                            $sqlStr .= " or FIND_IN_SET('$v',c.module)";
                        }
                        $k++;
                    }
                    unset($limitArr['module']);
                    $sqlStr .= ")";
                }
                if ($customSql) {
                    $sqlStr .= $customSql;
                }
                $this->searchArr['mySearchCondition'] = $sqlStr;
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * ���ݺ�ͬID ��ȡʣ����ú�ͬռ��
     *
     */
    function getSurplusProportionByCid($cid)
    {
        $sql = "select sum(proportion) as proportion,contractId from oa_contract_project where contractId = '" . $cid . "' GROUP BY contractId";
        $arr = $this->_db->getArray($sql);

        if (!empty($arr)) {
            $v = 100 - $arr[0]['proportion'];
            return $v;
        } else {
            return "100";
        }
    }

    /**
     * ����id ��ȡ��Ŀid �� ռ��
     */
    function getProjectProByCid($cId)
    {
        $sql = "select * from oa_contract_project where contractId = '" . $cId . "'";
        $arr = $this->_db->getArray($sql);
        $rtnArr = "";
        foreach ($arr as $k => $v) {
            if (!empty($v['esmProjectId'])) {
                $proportion = $this->getAccBycid($v['contractId'], $v['proLineCode'], 17);
                $proportion = round(($proportion * $v['proportion']) / 100, 1);
                $type = "sale";
            } else {
                $proportion = round($this->getAccBycid($v['contractId'], $v['proLineCode'], 11), 1);
                $type = "esm";
            }
            $rtnArr[$k]['pId'] = $v['id'];
            $rtnArr[$k]['proportion'] = $proportion;
            $rtnArr[$k]['type'] = $type;
        }
        return $rtnArr;
    }

    /**
     * ���ݹ�����Ŀid  ���º�ͬ��Ŀ����
     * @param $eid ������Ŀid  $estimates ������
     */
    function updateEstimatesByEsmId($eid, $estimates)
    {
        if ($eid) {
            $condition = array("esmProjectId" => $eid);
            $arr = array("estimates" => $estimates);

            $this->update($condition, $arr);

            //������Ŀ�ڵ� Ԥ��ë����

            $conArr = $this->find(array("esmProjectId" => $eid));
            $exGrossTemp = bcdiv(($conArr['contractMoney'] - $estimates), $conArr['contractMoney'], 4);
            $exGross = bcmul($exGrossTemp, '100', 2);
            $this->update($condition, array("exgross" => $exGross));
        }
    }


    /**
     * ��ȡ��EXCEL������֮��������ݿ�
     * @param unknown $excelData
     */
    function importProInfo_d($excelData)
    {
        if (is_array($excelData)) {
            $arrinfo = array(); //������
            $conDao = new model_contract_contract_contract();
            foreach ($excelData as $v) {
                $contractCode = $v[0];
                if ($contractCode) {
                    //�жϺ�ͬ�Ƿ����
                    $isBe = $conDao->findContract($contractCode, "��ͬ��");
                } else {
                    continue;
                }
                if (empty ($isBe)) {
                    array_push($arrinfo, array(
                        "docCode" => $contractCode,
                        "result" => "����ʧ��,��ͬ��Ϣ������"
                    ));
                } else {
                    if (count($isBe) > 1) {
                        array_push($arrinfo, array(
                            "docCode" => $contractCode,
                            "result" => "����ʧ��,��ͬ���ظ���ʹ��ҵ���ŵ���"
                        ));
                    } else {
                        //���º�ͬ��Ϣ
                        $this->importCreateProject($isBe[0]['id']);
                        array_push($arrinfo, array(
                            "docCode" => $contractCode,
                            "result" => "����ɹ���"
                        ));
                    }
                }
            }
            return $arrinfo;
        } else {
            msg("�ļ������ڿ�ʶ������!");
        }
    }

    /**
     * ����ȷ�Ϸ�ʽ�����˱�ʶ ����
     */
    function importExtend_d($excelData)
    {
        if (is_array($excelData)) {
            $arrinfo = array(); //������
            $nameArr = array(
                "��������Ŀ����" => "HSFS-GCJD",
                "����������" => "HSFS-FHJD",
                "����Ʊ����" => "HSFS-FPJD",
                "����Ʒ������" => "HSFS-CPZL"
            );
            foreach ($excelData as $v) {
                $pCode = $v[0];
                $dataName = $v[1];
                if (empty($v[2])) {
                    $checkTip = "0";
                } else {
                    $checkTip = $v[2];
                }
                if ($pCode) {
                    //�ж���Ŀ�Ƿ����
                    $isBe = $this->findConProject($pCode);
                } else {
                    continue;
                }
                if (empty ($isBe)) {
                    array_push($arrinfo, array(
                        "docCode" => $pCode,
                        "result" => "����ʧ��,��Ŀ��Ϣ������"
                    ));
                } else {
                    if (count($isBe) > 1) {
                        array_push($arrinfo, array(
                            "docCode" => $pCode,
                            "result" => "����ʧ��,��Ŀ����ظ�������ϵ����Ա"
                        ));
                    } else {
                        if (!array_key_exists($dataName, $nameArr)) {
                            array_push($arrinfo, array(
                                "docCode" => $pCode,
                                "result" => "����ʧ��,����ȷ�Ϸ�ʽ����"
                            ));
                        } else {
                            //����
                            $sql = "update oa_contract_project set earningsTypeCode='" . $nameArr[$dataName] . "',
                            earningsTypeName='" . $dataName . "',checkTip='" . $checkTip . "' where id = '" . $isBe[0]['id'] . "'";
                            $this->_db->query($sql);

                            array_push($arrinfo, array(
                                "docCode" => $pCode,
                                "result" => "����ɹ���"
                            ));
                        }
                    }
                }
            }
            return $arrinfo;
        } else {
            msg("�ļ������ڿ�ʶ������!");
        }
    }

    /*
	 * �ж���Ŀ�Ƿ����
	 */
    function findConProject($pCode)
    {
        $sql = "select id from oa_contract_project where projectCode = '" . $pCode . "'";
        $cId = $this->_db->getArray($sql);
        return $cId;
    }

    /**
     * ���� ���³�ʼ����Ŀ��Ϣ
     */
    function importCreateProject($cid)
    {
        //��������Ŀ
        $this->createProjectBySale_d($cid, "1");
        //������������Ŀ����
        $this->updateConProScheduleByCid($cid);
        //��������Ŀ
        $esmDao = new model_engineering_project_esmproject();
        $esmArr = $esmDao->getProjectList_d(array($cid));
        if (is_array($esmArr))
            foreach ($esmArr as $esmVal) {
                $tempArr = array(
                    "projectCode" => $esmVal['projectCode'],
                    "projectName" => $esmVal['projectName'],
                    "state" => "GCXMZT01",
                    "contractId" => $esmVal['contractId'],
                    "esmProjectId" => $esmVal['id'],
                    "proLineCode" => $esmVal['productLine'],
                    "proLineName" => $esmVal['productLineName'],
                    "proportion" => $esmVal['workRate'],
                    "estimates" => $esmVal['estimates'],
                    "planBeginDate" => $esmVal['planBeginDate'],
                    "planEndDate" => $esmVal['planEndDate']
                );
                $this->createProjectBySer_d($tempArr, "1");
            }

    }


    /**
     * ��������
     */
    function storeHandle_d($row, $maxNum)
    {

        $row['pid'] = $row['id'];
        unset($row['id']);
        $row['version'] = $maxNum;
        $row['storeYear'] = date("Y");
        $row['storeMon'] = date("m");
        $row['isUse'] = "0";
        $row['storeDate'] = date("Y-m-d H:i:s");;
        $row['storeName'] = $_SESSION['USERNAME'];
        $row['storeNameId'] = $_SESSION['USER_ID'];

        $dao = new model_contract_conproject_conprojectRecord();
        $id = $dao->add_d($row);
        //��������
        //        $this->updateEarningsById($id);
        return $id;
    }

    /**
     * �ɱ༭���ӱ�����
     */
    function conProsubJson_d($pd)
    {
        $sql = "SELECT
					'����' as indexName,
					sum(if(c.storeMon=1,c.earnings,0)) as January,
					sum(if(c.storeMon=2,c.earnings,0)) as February,
					sum(if(c.storeMon=3,c.earnings,0)) as March,
					sum(if(c.storeMon=4,c.earnings,0)) as April,
					sum(if(c.storeMon=5,c.earnings,0)) as May,
					sum(if(c.storeMon=6,c.earnings,0)) as June,
					sum(if(c.storeMon=7,c.earnings,0)) as July,
					sum(if(c.storeMon=8,c.earnings,0)) as August,
					sum(if(c.storeMon=9,c.earnings,0)) as September,
					sum(if(c.storeMon=10,c.earnings,0)) as October,
					sum(if(c.storeMon=11,c.earnings,0)) as November,
					sum(if(c.storeMon=12,c.earnings,0)) as December
				FROM
					oa_contract_project_record c
				  where c.isUse='1' and c.pid='" . $pd . "' and c.storeYear = date_format(now(),'%Y')
				GROUP BY  storeYear
union ALL
SELECT
					'����' as indexName,
					sum(if(c.storeMon=1,c.cost,0)) as January,
					sum(if(c.storeMon=2,c.cost,0)) as February,
					sum(if(c.storeMon=3,c.cost,0)) as March,
					sum(if(c.storeMon=4,c.cost,0)) as April,
					sum(if(c.storeMon=5,c.cost,0)) as May,
					sum(if(c.storeMon=6,c.cost,0)) as June,
					sum(if(c.storeMon=7,c.cost,0)) as July,
					sum(if(c.storeMon=8,c.cost,0)) as August,
					sum(if(c.storeMon=9,c.cost,0)) as September,
					sum(if(c.storeMon=10,c.cost,0)) as October,
					sum(if(c.storeMon=11,c.cost,0)) as November,
					sum(if(c.storeMon=12,c.cost,0)) as December
				FROM
					oa_contract_project_record c
				  where c.isUse='1' and c.pid='" . $pd . "' and c.storeYear = date_format(now(),'%Y')
				GROUP BY  storeYear
";
        //			echo $pd;
        $rows = $this->_db->getArray($sql);
        //	print_R($rows);
        return $rows;
    }


    /**
     * ���ݺ�ͬid ������code ��ռ�ȣ� ��ȡ��ͬ�ڲ���ռ�Ƚ��
     */
    function getMoneyByProWorkRate($cid, $proLineCode, $workRate)
    {
        $conProDao = new model_contract_contract_product();
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($cid);
        $arr = $conProDao->getDetail_d($cid);
        foreach ($arr as $key => $val) {
            //�жϲ�Ʒ���
            $proId = $val['conProductId'];
            $pid = $val['id'];
            if (!empty ($proId)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($proId))";
                //��һ�β��ң����˳������Ѿ��������������
                $goodsTypeA = $this->_db->getArray($sqlA);
                if ($goodsTypeA[0]['parentId'] != "-1" && $goodsTypeA[0]['id'] != "") {
                    $goodsId = $goodsTypeA[0]['id'];
                    //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
                    $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                        "where id in ($goodsId)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                    $goodsTypeB = $this->_db->getArray($sqlB);
                    $goodsTypeId = $goodsTypeB[0]['id'];
                } else {
                    $goodsTypeId = $goodsTypeA[0]['id'];
                }
            }
            if ($goodsTypeId == '11') { //��������۲�Ʒ������ͳ��
                continue;
            }
            //��Ʒ��
            $sqlf = "select newProLineName,newProLineCode from oa_contract_product  where id = '" . $pid . "'";
            $exeDeptNameArr = $this->_db->getArray($sqlf);
            $costInfoArr[] = array(
                "productLine" => $exeDeptNameArr[0]['newProLineCode'],
                "productLineName" => $exeDeptNameArr[0]['newProLineName'],
                "confirmMoney" => $val['money']
            );

        }
        //������ͬ��Ʒ�ߵĽ������
        $ResultArr = array();
        foreach ($costInfoArr as $value) {
            $line = $value['productLine'];
            $lineName = $value['productLineName'];
            $sum = $value['confirmMoney'];
            if (array_key_exists($line, $ResultArr)) {
                $ResultArr[$line] = bcadd($ResultArr[$line], $sum, 2);
            } else {
                $ResultArr[$line] = $sum;
            }
        }
        //�����ܽ��
        $mon = $ResultArr[$proLineCode] * ($workRate / 100);
        //����
        $costDao = new model_contract_contract_cost();
        $costMoney = $costDao->findMoneyByLine($cid, $proLineCode, 0);
        $estimates = $costMoney['confirmMoney'] * ($workRate / 100);

        $retArr = array(
            'contractMoney' => $mon,
            'estimates' => $estimates);

        return $retArr;
    }


    /**
     * ���ݺ�ͬid ������code ����ȡ��ͬ�ڲ��� ���͸���
     */
    function getProMoneyByCid($cid)
    {
        $conProDao = new model_contract_contract_product();
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($cid);
        $arr = $conProDao->getDetail_d($cid);
        foreach ($arr as $key => $val) {
            //�жϲ�Ʒ���
            $proId = $val['conProductId'];
            $Pid = $val['id'];
            if (!empty ($proId)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($proId))";
                //��һ�β��ң����˳������Ѿ��������������
                $goodsTypeA = $this->_db->getArray($sqlA);
                if ($goodsTypeA[0]['parentId'] != "-1" && $goodsTypeA[0]['id'] != "") {
                    $goodsId = $goodsTypeA[0]['id'];
                    //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
                    $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                        "where id in ($goodsId)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                    $goodsTypeB = $this->_db->getArray($sqlB);
                    $goodsTypeId = $goodsTypeB[0]['id'];
                } else {
                    $goodsTypeId = $goodsTypeA[0]['id'];
                }
            }
            if ($goodsTypeId == '11') { //��������۲�Ʒ������ͳ��
                continue;
            }
            //��Ʒ��
            $sqlf = "select newProLineName,newProLineCode from oa_contract_product  where id = '" . $Pid . "'";
            $exeDeptNameArr = $this->_db->getArray($sqlf);
            $costInfoArr[] = array(
                "productLine" => $exeDeptNameArr[0]['newProLineCode'],
                "productLineName" => $exeDeptNameArr[0]['newProLineName'],
                "confirmMoney" => $val['money']
            );

        }
        //������ͬ��Ʒ�ߵĽ������
        $ResultArr = array();
        foreach ($costInfoArr as $value) {
            $line = $value['productLine'];
            $sum = $value['confirmMoney'];
            if (array_key_exists($line, $ResultArr)) {
                $ResultArr[$line]['contractMoney'] += $sum;
            } else {
                $ResultArr[$line]['contractMoney'] = $sum;
            }
        }
        //����
        $costDao = new model_contract_contract_cost();
        foreach ($ResultArr as $k => $v) {
            $costMoney = $costDao->findMoneyByLine($cid, $k, 0);
            $ResultArr[$k]['estimates'] = $costMoney['confirmMoney'];
        }
        return $ResultArr;
    }

    /**
     *  ���ݺ�ͬid����Ʒ�߱��룬�жϲ�Ʒ���ں�ͬ���Ƿ����
     * @param $cid ,$proLineCode
     *  return  0 - ���� 1 - ������
     */
    function getisExistByLine($cid, $proLineCode)
    {
        $conProDao = new model_contract_contract_product();
        $conDao = new model_contract_contract_contract();
        $arr = $conProDao->getDetail_d($cid);
        foreach ($arr as $key => $val) {
            //�жϲ�Ʒ���
            $proId = $val['conProductId'];
            if (!empty ($proId)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($proId))";
                //��һ�β��ң����˳������Ѿ��������������
                $goodsTypeA = $this->_db->getArray($sqlA);
                if ($goodsTypeA[0]['parentId'] != "-1" && $goodsTypeA[0]['id'] != "") {
                    $goodsId = $goodsTypeA[0]['id'];
                    //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
                    $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                        "where id in ($goodsId)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                    $goodsTypeB = $this->_db->getArray($sqlB);
                    $goodsTypeId = $goodsTypeB[0]['id'];
                } else {
                    $goodsTypeId = $goodsTypeA[0]['id'];
                }
            }
            //��Ʒ��
            $sqlf = "select exeDeptName,exeDeptCode from oa_goods_base_info  where id = '" . $proId . "'";
            $exeDeptNameArr = $this->_db->getArray($sqlf);
            $costInfoArr[] = array(
                "productLine" => $exeDeptNameArr[0]['exeDeptCode'],
                "productLineName" => $exeDeptNameArr[0]['exeDeptName'],
                "confirmMoney" => $val['money']
            );
        }
        //������ͬ��Ʒ�ߵĽ������
        $ResultArr = array();
        foreach ($costInfoArr as $value) {
            $line = $value['productLine'];
            $ResultArr[] = $line;
        }

        if (in_array($proLineCode, $ResultArr)) {
            return "0";
        } else {
            return "1";
        }

    }


    /**
     * ���ݺ�ͬid ��ȡ��Ŀ��Ҫ�����ʵʱ����
     */
    function getConPorjectNowInfoByCid($arr, $esmArr)
    {
        $reArr = array();
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($arr['contractId']);
        //��ȡʵʱ����ռ��
        if (!empty($arr['esmProjectId'])) {
            $proportion = $this->getAccBycid($arr['contractId'], $arr['proLineCode'], 17);
            $proportionTrue = round($proportion, 1);
            $proportion = round(($proportion * $arr['proportion']) / 100, 1);


        } else {
            $proportion = round($this->getAccBycid($arr['contractId'], $arr['proLineCode'], 11), 1);
            $proportionTrue = round($proportion, 1);
        }
        //�ۿ����
        $deductMoney = $conArr['deductMoney'] * $proportion / 100;
        $badMoney = $conArr['badMoney'] * $proportion / 100;

        //��Ŀ��ͬ��
        if (!empty($arr['esmProjectId'])) {
            //            $proMoney = $this->getAccMoneyBycid($arr['contractId'], $arr['proLineCode'],17);
            $proMoney = $esmArr['contractMoney'];
        } else {
            $proMoney = $this->getAccMoneyBycid($arr['contractId'], $arr['proLineCode'], 11);
        }
        //�����ͬ�ܺ�˰�� ���ΪС�� 0.xx  �������
        $txaRate = $this->getTxaRate($conArr);
        //��ȡ����
        $costDao = new model_contract_contract_cost ();
        if (!empty($arr['esmProjectId'])) {
//            $estimatesArr = $costDao->findMoneyByLine($arr['contractId'], $arr['proLineCode'], 0);
//            $estimates = $estimatesArr['confirmMoney'] * $arr['proportion'] / 100;
            $estimates = $esmArr['estimates'];
        } else {
            $estimatesArr = $costDao->findMoneyByLine($arr['contractId'], $arr['proLineCode'], 1);
            if ($conArr['contractType'] == "HTLX-ZLHT") {
                $days = abs($this->getChaBetweenTwoDate($conArr['beginDate'], $conArr['endDate'])); //��������
                $saleCostTemp = $estimatesArr['confirmMoney'] / 720;
                $estimates = bcmul($days, $saleCostTemp, 2);
            } else {
                $estimates = $estimatesArr['confirmMoney'];
            }
        }
        $rateMoney = ($proMoney - $deductMoney - $badMoney) / (1 + $txaRate); //��˰���- ���ڼ���
        $rateMoneyView = $proMoney / (1 + $txaRate); //��˰����û���ۿ�������ʾ��

        if (empty($estimates)) {
            $exgross = 0;
        } else {
            $exgross = bcdiv($rateMoney - $estimates, $rateMoney, 4); // Ԥ��ë����
        }
        //��������
        $tureFHJD = $this->getFHJD($arr);
        //����,����
        //����Ԥ��5�������밴˰ǰ����ı�־λ
        $preTip = 0;

        if (!empty($arr['earningsTypeCode'])) {
            switch ($arr['earningsTypeCode']) {
                case "HSFS-GCJD" :
                    $schedule = isset($esmArr['projectProcess']) ? $esmArr['projectProcess'] : "0.00";
                    $XMJD = $schedule / 100;
                    $FPJD = round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($deductMoney, $badMoney)), 6), 4);
                    if ($FPJD == 1) {
                        $JD = $XMJD;
                    } else if ($FPJD <= 0.98) {
                        if ($XMJD > 0.98) {
                            $JD = 0.98;
                            $preTip = 1;
                        } else {
                            $JD = $XMJD;
                        }
                    } else if ($FPJD > 0.98 && $FPJD != 1) {
                        if ($FPJD >= $XMJD) {
                            $JD = $XMJD;
                        } else {
                            $JD = $FPJD;
                        }
                    }
                    if ($JD > 1) $JD = 1;
                    if ($preTip == 1) { //2015-02-09 �վ���ȷ�ϸĺ�˰���Ԥ��
                        $earnings = bcmul($JD, $rateMoney, 2);
                    } else {
                        $earnings = bcmul($JD, $rateMoney, 2);
                    }

                    break;
                case "HSFS-FHJD" :
                    if ($conArr['DeliveryStatus'] == "TZFH") {
                        $schedule = "100";
                    } else {
                        $schedule = isset($tureFHJD) ? $tureFHJD : "0.00";
                    }
                    $XMJD = $schedule / 100;
                    $FPJD = round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($deductMoney, $badMoney)), 6), 4);
                    if ($FPJD == 1) {
                        $JD = $XMJD;
                    } else if ($FPJD <= 0.98) {
                        if ($XMJD > 0.98) {
                            $JD = 0.98;
                            $preTip = 1;
                        } else {
                            $JD = $XMJD;
                        }
                    } else if ($FPJD > 0.98 && $FPJD != 1) {
                        if ($FPJD >= $XMJD) {
                            $JD = $XMJD;
                        } else {
                            $JD = $FPJD;
                        }
                    }
                    if ($JD > 1) $JD = 1;
                    if ($preTip == 1) { //2015-02-09 �վ���ȷ�ϸĺ�˰���Ԥ��
                        $earnings = bcmul($JD, $rateMoney, 2);
                    } else {
                        $earnings = bcmul($JD, $rateMoney, 2);
                    }
                    break;
                case "HSFS-FPJD" :
                    $schedule = round(bcdiv($conArr['invoiceMoney'], $conArr['contractMoney'], 6) * 100, 2);
                    $scheduleT = round(bcdiv($conArr['invoiceMoney'], $conArr['contractMoney'], 6), 4);
                    if ($scheduleT > 1) $scheduleT = 1;
                    $earnings = bcmul($scheduleT, $rateMoney, 2);
                    break;
            }
        } else {
            $schedule = "0.00";
            $earnings = "0.00";
        }
        if ($schedule > 100) {
            $schedule = '100';
        }
        //Ԥ��Ӫ��
//        $reserveEarnings = null;
//        if($schedule < 98){
//        	$reserveEarnings = 0;
//        }else if($schedule > 98){
//        	$bcMoney = $proMoney-$deductMoney-$badMoney;
//        	$invoiceJD = round(bcdiv($conArr['invoiceMoney'],$bcMoney,6),4);
//        	echo $bcMoney;
////        	if()
//        }

        $reArr['proportion'] = $proportion; //ʵ��ռ��
        $reArr['proportionTrue'] = $proportionTrue; //��Ʒ��ռ��
        $reArr['proMoney'] = $proMoney; //��Ŀ��ͬ��
        $reArr['contractMoney'] = $conArr['contractMoney'];
        $reArr['txaRate'] = $txaRate * 100; //�ۺ�˰��
        $reArr['rateMoney'] = $rateMoneyView; //��˰���
        $reArr['exgross'] = $exgross * 100; // Ԥ��ë����
        $reArr['gross'] = $rateMoney - $estimates; //Ԥ��ë��
        $reArr['estimates'] = $estimates; //����

        $reArr['deductMoney'] = $deductMoney; //�ۿ�
        $reArr['badMoney'] = $badMoney; //����
        $reArr['module'] = $conArr['module']; //�����
        $reArr['moduleName'] = $conArr['moduleName']; //�������
        //������Ŀ��Ϣ
        if (!empty($arr['esmProjectId']) && !empty($esmArr)) {
            $reArr['budget'] = $esmArr['budgetAll']; //Ԥ��
            $reArr['cost'] = $esmArr['feeAll']; //����
            $reArr['planBeginDate'] = $esmArr['planBeginDate'];
            $reArr['planEndDate'] = $esmArr['planEndDate'];
            $reArr['actBeginDate'] = $esmArr['actBeginDate'];
            $reArr['actEndDate'] = $esmArr['actEndDate'];
            $reArr['schedule'] = $schedule; //����$esmArr['projectProcess']
            $reArr['earnings'] = $earnings; //����
            $reArr['officeId'] = $esmArr['productLine'];; //�������
            $reArr['officeName'] = $esmArr['productLineName'];; //��������
        } else {
            $reArr['budget'] = $this->getCost($arr['contractId'], $arr['proLineCode'], $conArr, 0);; //Ԥ��
            $reArr['cost'] = $this->getFeeAllByPro($arr); //����
            $reArr['planBeginDate'] = $arr['planBeginDate'];
            $reArr['planEndDate'] = $arr['planEndDate'];
            $reArr['actBeginDate'] = $arr['actBeginDate'];
            $reArr['actEndDate'] = $arr['actEndDate'];
            $reArr['schedule'] = $this->getSchedule($arr['contractId'], $conArr, $arr); //��Ŀ����
            $reArr['earnings'] = $earnings; //����

            // ��Ʒ�࣬��ȡ��ͬ��Ʒִ������
            $productDao = new model_contract_contract_product();
            $rs = $productDao->find(array('contractId' => $arr['contractId'], 'newProLineCode' => $arr['proLineCode'],
                'proTypeId' => '11', 'isDel' => '0'), null, 'exeDeptId,exeDeptName');
            $reArr['officeId'] = empty($rs['exeDeptId']) ? '' : $rs['exeDeptId']; //�������
            $reArr['officeName'] = empty($rs['exeDeptName']) ? '' : $rs['exeDeptName']; //��������
        }
        return $reArr;
    }

    /**
     * ��Ʒ���ܾ���
     */
    function getFeeAllByPro($pro){
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($pro['contractId']);
        $projectCode = $pro['projectCode'];
        $shipCost = $this->get_table_fields("oa_esm_project_shipcost","projectCode='$projectCode'","cost");
        $otherCost = $this->get_table_fields("oa_esm_project_othercost","projectCode='$projectCode'","cost");
        //��Ʊ���� costEstimates
        $FPJD = round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'],bcadd($conArr['deductMoney'],$conArr['badMoney'])), 6),4);
        if($conArr['DeliveryStatus'] == "YFH"){
            $cost = $shipCost;
        }else{
            $cost = $conArr['costEstimates'];
        }
        return round($FPJD*$cost + $otherCost + 0,2);// +0 �Ǹ���̯֧���ɱ�Ԥ��
    }

    //����ͬid�����߻�ȡʵʱ��������
    function getFHJD($row,$spcialArr = array())
    {
        $row['id'] = str_replace("c","",$row['id']);
        // �����Ҫɸѡ����,�ȶԶ�Ӧ�������ڵ���������ɸ��
        if(isset($spcialArr['needFilt']) && $spcialArr['needFilt']){
            $sql = "select deliverySchedule from oa_contract_project where id = {$row['id']};";
            $lastDeliverySchedule = $this->_db->getArray($sql);
            if(!empty($lastDeliverySchedule) && isset($lastDeliverySchedule[0]['deliverySchedule'])){
                return $lastDeliverySchedule[0]['deliverySchedule'];
            }
        }

        $cid = $row['contractId'];
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($cid);
        if($conArr['DeliveryStatus'] == 'TZFH'){
            return 100;
        }
        $proLine = !empty($row['proLineCode'])?$row['proLineCode']:$row['newProLine'];
        $sql = "select sum(e.executedNum*if(e.price is null or e.price=0,i.priCost,e.price))/sum(e.number*if(e.price is null or e.price=0,i.priCost,e.price))*100 as trueSchedule
            from oa_contract_equ e
            left join oa_contract_product p on e.conProductId=p.id or e.proId=p.id
            left join oa_stock_product_info i on e.productId=i.id
            where e.contractId='" . $cid . "' and e.isTemp=0 and e.isDel=0 and exeDeptId='" . $proLine . "'";
        $arr = $this->_db->getArray($sql);
        if (!empty($arr[0]['trueSchedule'])) {
            if(round($arr[0]['trueSchedule'], 4) > 100){
                $rtn = 100;
            }else{
                $rtn = round($arr[0]['trueSchedule'], 4);
            }
            return $rtn;
        } else { //ȥ�����������������ڼ�����ʷ���ݣ����ܻ����ͬ�����಻�ò��ߵ��½��Ȳ�������
            $sqlT = "select sum(e.executedNum*if(e.price is null or e.price=0,i.priCost,e.price))/sum(e.number*if(e.price is null or e.price=0,i.priCost,e.price))*100 as trueSchedule
            from oa_contract_equ e
            left join oa_contract_product p on e.conProductId=p.id or e.proId=p.id
            left join oa_stock_product_info i on e.productId=i.id
            where e.contractId='" . $cid . "' and e.isTemp=0 and e.isDel=0";
            $arrTemp = $this->_db->getArray($sqlT);

            if(round($arrTemp[0]['trueSchedule'], 4) > 100){
                $rtn = 100;
            }else{
                $rtn = round($arrTemp[0]['trueSchedule'], 4);
            }
            return $rtn;
        }
    }

    /*
	*
	*�������ܣ�����������YYYY-MM-DDΪ��ʽ�����ڣ�����
	*
	*/
    function getChaBetweenTwoDate($date1, $date2)
    {

        $Date_List_a1 = explode("-", $date1);
        $Date_List_a2 = explode("-", $date2);

        $d1 = mktime(0, 0, 0, $Date_List_a1[1], $Date_List_a1[2], $Date_List_a1[0]);

        $d2 = mktime(0, 0, 0, $Date_List_a2[1], $Date_List_a2[2], $Date_List_a2[0]);

        $Days = round(($d1 - $d2) / 3600 / 24);

        return $Days;
    }

    //��ȡ��ͬ�ۺ�˰��
    function getTxaRate($conArr)
    {
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        foreach ($typeArr as $key => $val) {
            foreach ($val as $k => $v) {
                $valArrs[] = $v['expand1'];
                $invoicTypeArr[$v['dataCode']] = $v['expand1'];
            }
        }

        $conDao = new model_contract_contract_contract();
        $conInvoiceArr = $conDao->makeInvoiceValueArr($conArr);

        $rate = 0; //�ۺ�˰��
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// ������ͬ���ڲ���Ʊ�Ŀ�Ʊ����,ֱ�ӷŻ�0
                $rate = 0;
            }else{
                //�����������ĺ�ͬ���������޳ɱ�
                //��Ʊ������
                $typeMoney = 0;
                $cMoney = 0; //ȡʵ�ʿ�Ʊ��������㣬������ֱ��ȡ��ͬ��
                foreach ($conInvoiceArr as $k => $v) {
                    if ($v >=0) {
                        $rate = $invoicTypeArr[$k] / 100;
                        $rates = 1 + $rate;
                        $typeMoney += bcdiv($v, $rates, 8);
                        $cMoney += $v;
                    }
                }

                //������ǻ���࿪Ʊ����ֱ�ӷ��ظÿ�Ʊ˰��
                if (count($conInvoiceArr) > 1) {
                    $typeMoney = number_format($typeMoney, 0, '', '');
                    $rate = round(bcsub(bcdiv($cMoney, $typeMoney, 8), 1, 8), 4);
                }
            }
        }

        return $rate;
    }


    //���ݺ�ͬid,��Ʒ�߻�ȡռ�� ��ȡ��ز���ռ��
    function getAccBycid($cid, $proLineCode, $proType, $scale = 4, $type = 0)
    {
        error_reporting(E_ALL || ~E_NOTICE);
        $conProDao = new model_contract_contract_product();
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($cid);
        $arr = $conProDao->getDetail_d($cid);
        $costInfoArr = array();
        foreach ($arr as $key => $val) {
            //�жϲ�Ʒ���
            $proId = $val['conProductId'];
            $Pid = $val['id'];
            if (!empty ($proId)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($proId))";
                //��һ�β��ң����˳������Ѿ��������������
                $goodsTypeA = $this->_db->getArray($sqlA);
                if ($goodsTypeA[0]['parentId'] != "-1" && $goodsTypeA[0]['id'] != "") {
                    $goodsId = $goodsTypeA[0]['id'];
                    //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
                    $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                        "where id in ($goodsId)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                    $goodsTypeB = $this->_db->getArray($sqlB);
                    $goodsTypeId = $goodsTypeB[0]['id'];
                } else {
                    $goodsTypeId = $goodsTypeA[0]['id'];
                }
            }
            //�з���������������Ʒ�ڼ��㣬�Ľ���ͳ�����ݣ���ʱ���
            if ($proType == '17') {
                if ($goodsTypeId == '17' || $goodsTypeId == '18') {
                    //��Ʒ��
                    $sqlf = "select newProLineName,newProLineCode from oa_contract_product  where id = '" . $Pid . "'";
                    $exeDeptNameArr = $this->_db->getArray($sqlf);
                    $costInfoArr[] = array(
                        "productLine" => $exeDeptNameArr[0]['newProLineCode'],
                        "productLineName" => $exeDeptNameArr[0]['newProLineName'],
                        "confirmMoney" => $val['money']
                    );
                }
            } else {
                if ($goodsTypeId == $proType) {
                    //��Ʒ��
                    $sqlf = "select newProLineName,newProLineCode from oa_contract_product  where id = '" . $Pid . "'";
                    $exeDeptNameArr = $this->_db->getArray($sqlf);
                    $costInfoArr[] = array(
                        "productLine" => $exeDeptNameArr[0]['newProLineCode'],
                        "productLineName" => $exeDeptNameArr[0]['newProLineName'],
                        "confirmMoney" => $val['money']
                    );
                }
            }
        }

        //������ͬ��Ʒ�ߵĽ������
        $rst = array();
        foreach ($costInfoArr as $v) {
            $rst[$v['productLine']] = isset($rst[$v['productLine']]) ?
                bcadd($rst[$v['productLine']], $v['confirmMoney'], 2) : $v['confirmMoney'];
        }

        // ��������ڲ��߽�ֱ�ӷ���0
        if ($type != 2 && !isset($rst[$proLineCode])) {
            return 0;
        }

        // ��ͬ���
        $contractMoney = $conArr['currency'] != '�����' ? $conArr['contractMoneyCur'] : $conArr['contractMoney'];

        // ���������ж�����ʲô����
        switch ($type) {
            case 1 : // ���ز��߽��
                return $rst[$proLineCode];
            default : // ���ز���ռ��
                return $this->getProportion($rst[$proLineCode], $contractMoney, $scale);
        }
    }

    // ���ݺ�ͬid,��Ʒ�߻�ȡռ�� ��ȡ��ز��ߺ�ͬ��
    function getAccMoneyBycid($cid, $proLineCode, $proType)
    {
        $conProDao = new model_contract_contract_product();
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($cid);
        $arr = $conProDao->getDetail_d($cid);
        foreach ($arr as $key => $val) {
            //�жϲ�Ʒ���
            $proId = $val['conProductId'];
            $Pid = $val['id'];
            if (!empty ($proId)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($proId))";
                //��һ�β��ң����˳������Ѿ��������������
                $goodsTypeA = $this->_db->getArray($sqlA);
                if ($goodsTypeA[0]['parentId'] != "-1" && $goodsTypeA[0]['id'] != "") {
                    $goodsId = $goodsTypeA[0]['id'];
                    //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
                    $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                        "where id in ($goodsId)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                    $goodsTypeB = $this->_db->getArray($sqlB);
                    $goodsTypeId = $goodsTypeB[0]['id'];
                } else {
                    $goodsTypeId = $goodsTypeA[0]['id'];
                }
            }
            if ($goodsTypeId == $proType) {
                //��Ʒ��
                $sqlf = "select newProLineName,newProLineCode from oa_contract_product  where id = '" . $Pid . "'";
                $exeDeptNameArr = $this->_db->getArray($sqlf);
                $costInfoArr[] = array(
                    "productLine" => $exeDeptNameArr[0]['newProLineCode'],
                    "productLineName" => $exeDeptNameArr[0]['newProLineName'],
                    "confirmMoney" => $val['money']
                );
            }
        }
        //������ͬ��Ʒ�ߵĽ������
        $ResultArr = array();
        foreach ($costInfoArr as $value) {
            $line = $value['productLine'];
            $sum = $value['confirmMoney'];
            if (array_key_exists($line, $ResultArr)) {
                $ResultArr[$line] = bcadd($ResultArr[$line], $sum, 2);
            } else {
                $ResultArr[$line] = $sum;
            }
        }

        if (!empty($ResultArr[$proLineCode])) {
            if ($conArr['currency'] != '�����') {
                return $ResultArr[$proLineCode] * $conArr['rate'];
            } else {
                return $ResultArr[$proLineCode];
            }
        } else {
            return "0";
        }

    }

    /**
     * ������루���µ������룩
     */
    function addFinalceMoneyExecelAlone_d($objNameArr, $infoArr, $normType)
    {
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $excelData = array();
        $fileType = $_FILES["inputExcel"]["type"];
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $upexcel = new model_contract_common_allcontract();
            $excelData = $upexcel->upExcelData($filename, $temp_name);
            spl_autoload_register('__autoload'); //�ı������ķ�ʽ
            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr[$rNum][$fieldName] = $row[$index];
                    }
                }
                $arrinfo = array(); //������
                foreach ($objectArr as $k => $v) {
                    //�ϲ���Ϣ
                    $arr = array_merge($v, $infoArr);
                    //�ж���Ŀ�Ƿ���� �� �Ƿ�����������Ŀ
                    $isBe = $this->findContract($v['projectCode']);
                    if (empty ($isBe)) {
                        array_push($arrinfo, array(
                            "docCode" => $v['projectCode'],
                            "result" => "����ʧ��,��������������Ŀ��Ϣ"
                        ));
                    } else {
                        //��ӵ�����Ϣ
                        //$this->addfinancialInfo($arr, $isBe);
                        //$this->updateCost($isBe[0]['id']); //������������Ŀ��������
                        $this->updateById(array('id' => $isBe[0]['id'], 'cost' => $v['money']));
                        array_push($arrinfo, array(
                            "docCode" => $v['projectCode'],
                            "result" => "����ɹ���"
                        ));
                    }
                }
                if ($arrinfo) {
                    echo util_excelUtil :: finalceResult($arrinfo, "������", array(
                        "��Ŀ���",
                        "���"
                    ));
                }
            } else {
                echo "�ļ������ڿ�ʶ������!";
            }
        } else {
            echo "�ϴ��ļ����Ͳ���EXCEL!";
        }

    }

    /*
    * �ж���Ŀ�Ƿ����
    */
    function findContract($contractCode)
    {
        $sql = "select id from oa_contract_project where projectCode = '" . $contractCode . "' and esmProjectId is null";
        $cId = $this->_db->getArray($sql);
        return $cId;
    }

    /*
    * ������루���µ������룩 ��ӷ���
    */
    function addfinancialInfo($row, $conInfo)
    {
        //�ж������Ƿ�Ϊ�ظ����������
        $findSql = "select id from oa_contract_project_financeMoney where projectId='" . $conInfo[0]['id'] . "' and importMonth='" . $row['importMonth'] . "' and moneyType='" . $row['moneyType'] . "'";
        $findId = $this->_db->getArray($findSql);
        if (!empty ($findId)) {
            //����ʷ�������ݵ� ʹ�ñ�־λ��Ϊ 1
            foreach ($findId as $k => $v) {
                $updateSql = "update oa_contract_project_financeMoney set isUse=1 where id=" . $v['id'] . "";
                $this->query($updateSql);
            }
        }
        $files = "projectId,projectCode,importMonth,moneyType,moneyNum,importName,importNameId,importDate";
        $values = "'" . $conInfo[0]['id'] . "','" . $row['projectCode'] . "','" . $row['importMonth'] . "','" . $row['moneyType'] . "','" . $row['money'] . "','" . $row['importName'] . "','" . $row['importNameId'] . "','" . $row['importDate'] . "'";
        $addSql = "insert into oa_contract_project_financeMoney (" . $files . ") values (" . $values . ")";
        $this->query($addSql);
    }

    /**
     * �����������������
     */
    function updateCost($cId)
    {
        $sql = "SELECT  projectId, sum( IF (moneyType = 'salesCost',moneyNum,0)	) AS salesCost
                FROM
                    oa_contract_project_financeMoney
                WHERE
                    isUse = 0
                AND
                    projectId = " . $cId . "
                GROUP BY
                    projectId ";
        $infoArr = $this->_db->getArray($sql);
        $salesCost = $infoArr[0]['salesCost']; //�ɱ�

        //��������ֵ
        $updateSql = "update oa_contract_project set cost=" . $salesCost . " where id=" . $cId . "";
        $this->query($updateSql);
    }

    /**
     * ���Ұ��µ���������Ƿ��ظ�����
     */
    function getFimancialImport_d($importMonth, $importSub)
    {
        $month = date("Y") . $importMonth;
        $findSql = "select count(id) as num from oa_contract_project_financeMoney where importMonth='" . $month . "' and moneyType='" . $importSub . "'";
        $findId = $this->_db->getArray($findSql);
        if ($findId[0]['num'] == '0') {
            return 0;
        } else {
            return 1;
        }
    }


    /**
     * ��ȡ����������ϸ��Ϣ
     */
    function getFinancialDetailInfo($conId, $tablename, $moneyType)
    {
        $sql = "SELECT
								LEFT(c.importMonth,4) as year,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'01') ,c.moneyNum,0)) as January,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'02') ,c.moneyNum,0)) as February,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'03') ,c.moneyNum,0)) as March,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'04') ,c.moneyNum,0)) as April,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'05') ,c.moneyNum,0)) as May,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'06') ,c.moneyNum,0)) as June,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'07') ,c.moneyNum,0)) as July,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'08') ,c.moneyNum,0)) as August,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'09') ,c.moneyNum,0)) as September,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'10') ,c.moneyNum,0)) as October,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'11') ,c.moneyNum,0)) as November,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'12') ,c.moneyNum,0)) as December
							FROM
								oa_contract_project_financeMoney c

							  where c.isUse=0 and c.projectId=" . $conId . "  and moneyType='" . $moneyType . "'

							GROUP BY LEFT(c.importMonth,4)";
        //	echo $sql;
        $rows = $this->_db->getArray($sql);

        return $rows;
    }


    function getFinancialImportDetailInfo($conId, $tablename, $moneyType)
    {
        $sql = "select concat(LEFT(c.importMonth,4),'��',RIGHT(c.importMonth,2),'��') as improtMonth,
								       (case c.moneyType when 'salesCost' then '����ȷ������'
								                          end)  as moneyType,
								       c.moneyNum,c.moneyNum,c.importDate,c.importName,c.isUse
								  from oa_contract_project_financeMoney c  where c.projectId=" . $conId . "  and moneyType='" . $moneyType . "'";
        //		$rows = $this->_db->getArray($sql);
        return $sql;
    }


    /**
     *  ��Ŀ�����ܱ�������Դ
     */
    function lineProjectData($param)
    {
        $endYear = substr($param['endMonth'], 0, 4);
        $endMon = substr($param['endMonth'], -2);

        $startYear = substr($param['startMonth'], 0, 4);
        $startMonTemp = substr($param['startMonth'], -2);
        if ($startMonTemp != '01') {
            $startMon = $startMonTemp - 1;
        } else {
            $startYear = $startYear - 1;
            $startMon = "12";
        }
        // ��λ����Ԫ/��Ԫת����
        if ($param['unit'] == '2') {
            $unit = " / 10000";
        } else {
            $unit = "";
        }
        //��Ʒ��Ȩ��
        $limitStr = $this->proLineDateLimit();
        $limitStrTar = $this->proLineDateLimitTar();
        //----ʵ��
        //��ѯ�ֶδ���
        $parentCodeArr = explode(",", $param['parentCode']);
        if (is_array($parentCodeArr)) {
            $fieldName = $parentCodeArr[0]; // ͳ���ֶ���
            $fieldCode = $parentCodeArr[1]; // ͳ���ֶα���
            unset($parentCodeArr[0]); // ȥ����һ,���������ֶΣ�����д������������ԭ����ʱ��ô����
            unset($parentCodeArr[1]);
            $sqlContent = "";
            foreach ($parentCodeArr as $v) {
                if ($v == 'exgrossTrue') { //ë�����ر�����ʱ��ô�㣬�������ɼ��㹫ʽ����
                    $sqlContent .= "CONCAT(round((sum(if(storeYear = '" . $endYear . "' and storeMon='" . $endMon . "',
                            if(grossTrue is null or grossTrue='',0,grossTrue),0))
                          -sum(if(storeYear = '" . $startYear . "' and storeMon='" . $startMon . "',
                            if(grossTrue is null or grossTrue='',0,grossTrue),0))) /
                            (sum(if(storeYear = '" . $endYear . "' and storeMon='" . $endMon . "',
                            if(earnings is null or earnings='',0,earnings),0))
                          -sum(if(storeYear = '" . $startYear . "' and storeMon='" . $startMon . "',
                            if(earnings is null or earnings='',0,earnings),0)))*100,2),'%') as " . $v . "imp,";
                } else {
                    $sqlContent .= "round((sum(if(storeYear = '" . $endYear . "' and storeMon='" . $endMon . "',
                            if($v is null or $v='',0,$v),0))
                          -sum(if(storeYear = '" . $startYear . "' and storeMon='" . $startMon . "',
                            if($v is null or $v='',0,$v),0)))" . $unit . ",2)
                     as " . $v . "imp,";
                }
                if ($param['presentation'] == '2') { //ͳ�Ʒ�ʽ-����
                    $nNum = '0';
                    $ey = substr($param['endMonth'], 0, 4);
                    $em = substr($param['endMonth'], -2);

                    $sy = substr($param['startMonth'], 0, 4);
                    $sm = substr($param['startMonth'], -2);
                    for ($i = $sm; $i <= $em; $i++) {
                        if (strlen($i) == '1') {
                            $i = "0" . $i;
                        }
                        if ($i != '01') {
                            $Bi = $i - 1;
                            $By = $ey;
                        } else {
                            $By = $sy - 1;
                            $Bi = "12";
                        }
                        if ($v == 'exgrossTrue') { //ë�����ر�����ʱ��ô�㣬�������ɼ��㹫ʽ����
                            $sqlContent .= "CONCAT(round((sum(if(storeYear = '" . $sy . "' and storeMon='" . $i . "',
                                    if(grossTrue is null or grossTrue='',0,grossTrue),0))
                                  -sum(if(storeYear = '" . $By . "' and storeMon='" . $Bi . "',
                                    if(grossTrue is null or grossTrue='',0,grossTrue),0))) /
                                    (sum(if(storeYear = '" . $sy . "' and storeMon='" . $i . "',
                                    if(earnings is null or earnings='',0,earnings),0))
                                  -sum(if(storeYear = '" . $By . "' and storeMon='" . $Bi . "',
                                    if(earnings is null or earnings='',0,earnings),0)))*100,2),'%')as " . $v . "" . $nNum . ",";

                        } else {
                            $sqlContent .= "round((sum(if(storeYear = '" . $sy . "' and storeMon='" . $i . "',
                                    if($v is null or $v='',0,$v),0))
                                  -sum(if(storeYear = '" . $By . "' and storeMon='" . $Bi . "',
                                    if($v is null or $v='',0,$v),0)))" . $unit . ",2)
                             as " . $v . "" . $nNum . ",";
                        }
                        $nNum++;
                    }
                }
            }
            $sqlContent = rtrim(trim($sqlContent), ',');
            $sql = "select $fieldCode,$fieldName,
                    $sqlContent
                    from oa_contract_project_record c
                    left join oa_system_datadict d on c.$fieldCode = d.dataCode
                    where c.isUse=1 and c.checkTip=0 and c.earningsTypeCode <> '' $limitStr
                    group by c.$fieldCode ORDER BY d.orderNum";
            $arr = $this->_db->getArray($sql);
        }

        //-----Ŀ��
        $cq = "round((";
        for ($i = $startMonTemp; $i <= $endMon; $i++) {
            if (strlen($i) == '1') {
                $i = "0" . $i;
            }
            $str = $this->monthStrArr[$i];
            $cq .= "$str +";
        }
        $cq = rtrim($cq, '+');
        $cq .= ")" . $unit . ") as sumRs";
        $targetSql = "SELECT indicatorsCode,setCode,
                    $cq
                FROM oa_system_indicators
                WHERE objCode = '" . $param['objCode'] . "' $limitStrTar";
        $targetArrTemp = $this->_db->getArray($targetSql);
        $targetArr = array();
        //Ŀ�� ë�������⴦��
        if (in_array("exgrossTrue", $parentCodeArr)) {
            $parentCodeArrTrue = $parentCodeArr;
            if (!in_array("grossTrue", $parentCodeArr)) {
                array_push($parentCodeArr, "grossTrue");
            }
            if (!in_array("earnings", $parentCodeArr)) {
                array_push($parentCodeArr, "earnings");
            }
        }
        foreach ($targetArrTemp as $tv) {
            $tempKey = $tv['setCode'];
            $tempKeyV = $tv['indicatorsCode'] . "tar";
            if (in_array($tv['indicatorsCode'], $parentCodeArr)) {
                $targetArr[$tempKey][$tempKeyV] = $tv['sumRs'];
            }
        }
        foreach ($targetArr as $key => $val) {
            if (array_key_exists("exgrossTruetar", $val)) {
                $targetArr[$key]['exgrossTruetar'] = round($val['grossTruetar'] / $val['earningstar'] * 100, 2) . "%";
            }
            if (!in_array("grossTrue", $parentCodeArrTrue)) {
                unset($targetArr[$key]['grossTruetar']);
            }
            if (!in_array("earnings", $parentCodeArrTrue)) {
                unset($targetArr[$key]['earningstar']);
            }
        }
        //����ʵ�ʺ�Ŀ������
        foreach ($arr as $k => $v) {
            if (array_key_exists($v[$fieldCode], $targetArr)) {
                $tk = $v[$fieldCode];
                $arr[$k] = array_merge($v, $targetArr[$tk]);
            }
        }
        return $arr;
    }

    //��ƷӪ��--��ʱ
    function contractProData($param)
    {
        $sql = "select * from (
       select
 p.id as id,p.exeDeptName,p.conProductName,sum(e.money) as moneyall,sum(e.number) as num,sum(e.executedNum) as exeNum,
 (sum(e.executedNum)/sum(e.number)) as schedule,round((sum(e.executedNum)/sum(e.number)) * sum(e.money),2) as income,
 GROUP_CONCAT(p.contractId) as conIdStr

from oa_contract_equ e
  left join oa_contract_product p on e.conProductId=p.id
 where e.isTemp=0 and e.isDel=0 and e.conProductId <> '' and exeDeptId <> '' and p.proTypeId = 11
 group by p.exeDeptName,p.conProductName) c";

        return $sql;
    }

    //���ܱ� ����Ȩ�޴���
    function proLineDateLimit()
    {
        if (isset ($this->this_limit['��Ʒ��Ȩ��']) && !empty ($this->this_limit['��Ʒ��Ȩ��']))
            $limitStr = $this->this_limit['��Ʒ��Ȩ��'];
        if (strstr($limitStr, ';;')) {
            return " ";
        } else {
            if (empty ($limitStr)) {
                return " and 1=0";
            } else {
                //���û��Ȩ��
                $i = 0;
                $sqlStr = " and ( ";
                $k = 0;
                if (!empty($limitStr)) {
                    $LimitArr = explode(",", $limitStr);
                    foreach ($LimitArr as $k => $v) {
                        if ($k == 0) {
                            $sqlStr .= "FIND_IN_SET('$v',proLineCode)";
                        } else {
                            $sqlStr .= "or FIND_IN_SET('$v',proLineCode)";
                        }
                        $k++;
                    }
                }
                $sqlStr .= ")";

                return $sqlStr;
            }
        }
    }

    //��ʱ
    function proLineDateLimit_tem()
    {
        if (isset ($this->this_limit['��Ʒ��Ȩ��']) && !empty ($this->this_limit['��Ʒ��Ȩ��']))
            $limitStr = $this->this_limit['��Ʒ��Ȩ��'];
        if (strstr($limitStr, ';;')) {
            return " ";
        } else {
            if (empty ($limitStr)) {
                return " and 1=0";
            } else {
                //���û��Ȩ��
                $i = 0;
                $sqlStr = " and ( ";
                $k = 0;
                if (!empty($limitStr)) {
                    $LimitArr = explode(",", $limitStr);
                    foreach ($LimitArr as $k => $v) {
                        if ($k == 0) {
                            $sqlStr .= "FIND_IN_SET('$v',exeDeptId)";
                        } else {
                            $sqlStr .= "or FIND_IN_SET('$v',exeDeptId)";
                        }
                        $k++;
                    }
                }
                $sqlStr .= ")";

                return $sqlStr;
            }
        }
    }

    function proLineDateLimitTar()
    {
        if (isset ($this->this_limit['��Ʒ��Ȩ��']) && !empty ($this->this_limit['��Ʒ��Ȩ��']))
            $limitStr = $this->this_limit['��Ʒ��Ȩ��'];
        if (strstr($limitStr, ';;')) {
            return " ";
        } else {
            if (empty ($limitStr)) {
                return " and 1=0";
            } else {
                //���û��Ȩ��
                $i = 0;
                $sqlStr = " and setCode in ( ";
                $k = 0;
                if (!empty($limitStr)) {
                    $LimitArr = explode(",", $limitStr);
                    $sqlStrTemp = "";
                    foreach ($LimitArr as $k => $v) {
                        if ($k == 0) {
                            $sqlStrTemp .= "'$v',";
                        }
                    }
                }
                $sqlStrTemp = rtrim($sqlStrTemp, ",");
                $sqlStr .= $sqlStrTemp . ")";

                return $sqlStr;
            }
        }
    }

    /********************ͼ��******************************************************/

    /**
     * ����ǰ��¼�˻�ȡ�����ڵ�ͳ������
     */
    function getSection()
    {
        $recordDao = new model_contract_gridreport_gridrecord();
        $arr = $recordDao->getRecordInfo();
        foreach ($arr as $v) {
            if ($v['colName'] == 'startMonth') {
                $startMonth = $v['colValue'];
            }
            if ($v['colName'] == 'endMonth') {
                $endMonth = $v['colValue'];
            }
        }
        if (empty($startMonth)) {
            $startMonth = date("Y-01");
        }
        if (empty($endMonth)) {
            $endMonth = date("Y-m");
        }
        return array("startMonth" => $startMonth, "endMonth" => $endMonth);
    }

    /**
     *   echarts Json
     */
    function conProEchartsJson()
    {
        $endYear = substr($this->dateSection['endMonth'], 0, 4);
        $endMon = substr($this->dateSection['endMonth'], -2);

        $startYear = substr($this->dateSection['startMonth'], 0, 4);
        $startMonTemp = substr($this->dateSection['startMonth'], -2);
        //��Ʒ��Ȩ��
        $limitStr = $this->proLineDateLimit();
        //��Ŀ����ͳ��
        $sql = "select proLineName,count(id) as num from oa_contract_project
           where 1=1  and checkTip=0 $limitStr
           GROUP BY proLineCode;";

        $arr = $this->_db->getArray($sql);
        $outArr = array();
        foreach ($arr as $v) {
            $yAxisArr[] = $v['proLineName'];
            $seriesArr[] = $v['num'];
        }
        $outArr['yAxis'] = $yAxisArr;
        $outArr['series'] = $seriesArr;
        return $outArr;
    }

    function conProEchartsPieJson()
    {
        //��Ʒ��Ȩ��
        $limitStr = $this->proLineDateLimit();
        //��Ŀ����ͳ��
        $sql = "select
                    sum(if(esmProjectId is null or esmProjectId = '0',1,0)) as serNum,
                    sum(if(esmProjectId is null or esmProjectId = '0',0,1)) as saleNum
                 from oa_contract_project where 1=1  and checkTip=0 $limitStr
                group by state";
        $arr = $this->_db->getArray($sql);
        $outArr = array();
        $yAxisArr = array("������", "������");
        $seriesArr = array(
            array(
                "value" => $arr[0]['saleNum'],
                "name" => "������",
            ),
            array(
                "value" => $arr[0]['serNum'],
                "name" => "������"
            )
        );
        $outArr['yAxis'] = $yAxisArr;
        $outArr['series'] = $seriesArr;
        return $outArr;
    }

    //Ӫ��״��
    function conProRevenueChartJson()
    {
        //��Ʒ��Ȩ��
        $limitStr = $this->proLineDateLimit();
        $limitStrTar = $this->proLineDateLimitTar();
        $endYear = substr($this->dateSection['endMonth'], 0, 4);
        $endMon = substr($this->dateSection['endMonth'], -2);

        $startYear = substr($this->dateSection['startMonth'], 0, 4);
        $startMonTemp = substr($this->dateSection['startMonth'], -2);
        if ($startMonTemp != '01') {
            $startMon = $startMonTemp - 1;
        } else {
            $startYear = $startYear - 1;
            $startMon = "12";
        }
        //Ӫ�նԱ� --ʵ��
        $sqlContent = "(sum(if(storeYear = '" . $endYear . "' and storeMon='" . $endMon . "',
                            if(earnings is null or earnings='',0,earnings),0))
                          -sum(if(storeYear = '" . $startYear . "' and storeMon='" . $startMon . "',
                            if(earnings is null or earnings='',0,earnings),0)))
                     as num";

        $sql = "select prolineCode,if(prolineName='�з��ۺϲ�','�����Ż���ʦ��Ŀ',prolineName) as prolineName,
                    CASE
                    when prolineName='�����Ǳ���ҵ��' then '1'
                    when prolineName='���������'     then '2'
                    when prolineName='ͨ�ŷ�����ҵ��' then '3'
                    when prolineName='�з��ۺϲ�'     then '4'
                    when prolineName='����ר��'       then '5'
                    when prolineName='����ר��'       then '6'
                    else '0' end ordNum,
                    $sqlContent
                   FROM oa_contract_project_record
                   where isUse=1  and checkTip=0 and proLineCode <> '' $limitStr
                 group by proLineCode ORDER BY ordNum";
        $arr = $this->_db->getArray($sql);
        //---Ŀ��
        $cq = "round(";
        for ($i = $startMonTemp; $i <= $endMon; $i++) {
            if (strlen($i) == '1') {
                $i = "0" . $i;
            }
            $str = $this->monthStrArr[$i];
            $cq .= "$str +";
        }
        $cq = rtrim($cq, '+');
        $cq .= ") as sumRs";
        $targetSql = "SELECT indicatorsCode,setCode,
                    $cq
                FROM oa_system_indicators
                WHERE objCode = 'productLine' and indicatorsCode='earnings' $limitStrTar";
        $targetArrTemp = $this->_db->getArray($targetSql);
        foreach ($targetArrTemp as $v) {
            $tarKey = $v['setCode'];
            $tarVal = $v['sumRs'];
            $targetArr[$tarKey] = $tarVal;
        }
        foreach ($arr as $v) {
            $proLineCode = $v['prolineCode'];
            $xAxisArr[] = $v['prolineName'];
            $seriesTrueArr[] = round($v['num'] / 10000);
            if (array_key_exists($proLineCode, $targetArr)) {
                $seriesTarArr[] = round($targetArr[$proLineCode] / 10000);
            } else {
                $seriesTarArr[] = "0";
            }
        }
        $outArr['xAxis'] = $xAxisArr;
        $outArr['seriesTrue'] = $seriesTrueArr;
        $outArr['seriesTar'] = $seriesTarArr;
        return $outArr;
    }

    //ë��״��
    function conProGrossChartJson()
    {
        //��Ʒ��Ȩ��
        $limitStr = $this->proLineDateLimit();
        $limitStrTar = $this->proLineDateLimitTar();
        $endYear = substr($this->dateSection['endMonth'], 0, 4);
        $endMon = substr($this->dateSection['endMonth'], -2);

        $startYear = substr($this->dateSection['startMonth'], 0, 4);
        $startMonTemp = substr($this->dateSection['startMonth'], -2);
        if ($startMonTemp != '01') {
            $startMon = $startMonTemp - 1;
        } else {
            $startYear = $startYear - 1;
            $startMon = "12";
        }
        //---ʵ��
        $sqlContent = "sum(if(storeYear = '" . $endYear . "' and storeMon='" . $endMon . "',
                            if(grossTrue is null or grossTrue='',0,grossTrue),0))
                          -sum(if(storeYear = '" . $startYear . "' and storeMon='" . $startMon . "',
                            if(grossTrue is null or grossTrue='',0,grossTrue),0))
                     as num";

        $sql = "select prolineCode,if(prolineName='�з��ۺϲ�','�����Ż���ʦ��Ŀ',prolineName) as prolineName,
                    CASE
                    when prolineName='�����Ǳ���ҵ��' then '1'
                    when prolineName='���������'     then '2'
                    when prolineName='ͨ�ŷ�����ҵ��' then '3'
                    when prolineName='�з��ۺϲ�'     then '4'
                    when prolineName='����ר��'       then '5'
                    when prolineName='����ר��'       then '6'
                    else '0' end ordNum,
                    $sqlContent
                   FROM oa_contract_project_record
                   where isUse=1  and checkTip=0 and proLineCode <> '' $limitStr
                 group by proLineCode ORDER BY ordNum";
        $arr = $this->_db->getArray($sql);
        //---Ŀ��
        $cq = "(";
        for ($i = $startMonTemp; $i <= $endMon; $i++) {
            if (strlen($i) == '1') {
                $i = "0" . $i;
            }
            $str = $this->monthStrArr[$i];
            $cq .= "$str +";
        }
        $cq = rtrim($cq, '+');
        $cq .= ") as sumRs";
        $targetSql = "SELECT indicatorsCode,setCode,
                    $cq
                FROM oa_system_indicators
                WHERE objCode = 'productLine' and indicatorsCode='grossTrue' $limitStrTar";
        $targetArrTemp = $this->_db->getArray($targetSql);
        foreach ($targetArrTemp as $v) {
            $tarKey = $v['setCode'];
            $tarVal = $v['sumRs'];
            $targetArr[$tarKey] = $tarVal;
        }
        foreach ($arr as $v) {
            $proLineCode = $v['prolineCode'];
            $xAxisArr[] = $v['prolineName'];
            $seriesTrueArr[] = round($v['num'] / 10000);
            if (array_key_exists($proLineCode, $targetArr)) {
                $seriesTarArr[] = round($targetArr[$proLineCode] / 10000);
            } else {
                $seriesTarArr[] = "0";
            }
        }

        $outArr['xAxis'] = $xAxisArr;
        $outArr['seriesTrue'] = $seriesTrueArr;
        $outArr['seriesTar'] = $seriesTarArr;
        return $outArr;
    }

    function conProRateGrossChartJson()
    {
        //��Ʒ��Ȩ��
        $limitStr = $this->proLineDateLimit();
        $endYear = substr($this->dateSection['endMonth'], 0, 4);
        $endMon = substr($this->dateSection['endMonth'], -2);

        $startYear = substr($this->dateSection['startMonth'], 0, 4);
        $startMonTemp = substr($this->dateSection['startMonth'], -2);
        if ($startMonTemp != '01') {
            $startMon = $startMonTemp - 1;
        } else {
            $startYear = $startYear - 1;
            $startMon = "12";
        }

        $sqlContent = "";
        $sqlContent .= "round((sum(if(storeYear = '" . $endYear . "' and storeMon='" . $endMon . "',
                            if(grossTrue is null or grossTrue='',0,grossTrue),0))
                          -sum(if(storeYear = '" . $startYear . "' and storeMon='" . $startMon . "',
                            if(grossTrue is null or grossTrue='',0,grossTrue),0))) /
                            (sum(if(storeYear = '" . $endYear . "' and storeMon='" . $endMon . "',
                            if(earnings is null or earnings='',0,earnings),0))
                          -sum(if(storeYear = '" . $startYear . "' and storeMon='" . $startMon . "',
                            if(earnings is null or earnings='',0,earnings),0)))*100,2) as exgrossTrue,
                        100-round((sum(if(storeYear = '" . $endYear . "' and storeMon='" . $endMon . "',
                            if(estimates is null or estimates='',0,estimates),0))
                          -sum(if(storeYear = '" . $startYear . "' and storeMon='" . $startMon . "',
                            if(estimates is null or estimates='',0,estimates),0))) /
                            (sum(if(storeYear = '" . $endYear . "' and storeMon='" . $endMon . "',
                            if(rateMoney is null or rateMoney='' or estimates='0',0,rateMoney),0))
                          -sum(if(storeYear = '" . $startYear . "' and storeMon='" . $startMon . "',
                            if(rateMoney is null or rateMoney='' or estimates='0',0,rateMoney),0)))*100,2) as exgross
                            ";
        $sql = "select prolineCode,if(prolineName='�з��ۺϲ�','�����Ż���ʦ��Ŀ',prolineName) as prolineName,
                    $sqlContent
                   FROM  oa_contract_project_record
                   where isUse=1  and checkTip=0 and pid in (
                   select a.pid from (
                    select * from  oa_contract_project_record a where isUse=1 and checkTip=0 and storeYear = '" . $endYear . "' and storeMon='" . $endMon . "'
                    )a
                     left join
                    (select * from  oa_contract_project_record
                                       where isUse=1   and storeYear = '" . $startYear . "' and storeMon='" . $startMon . "')b
                    on a.pid=b.pid

                    where b.id is null or a.schedule-b.schedule != 0 or a.estimates-b.estimates != 0
                   )  $limitStr
                 group by proLineCode";

        $sqlB = "select prolineCode,if(prolineName='�з��ۺϲ�','�����Ż���ʦ��Ŀ',prolineName) as prolineName,
                    $sqlContent
                   FROM  oa_contract_project_record
                   where isUse=1  and checkTip=0  and proLineCode <> '' $limitStr
                 group by proLineCode";

        $arr = $this->_db->getArray($sql);
        $arrExTr = $this->_db->getArray($sqlB);
        $outArr = array();
        foreach ($arr as $v) {
            if (empty($v['exgross'])) $exgross = "0"; else $exgross = $v['exgross'];
//            if(empty($v['exgrossTrue'])) $exgrossTrue="0"; else $exgrossTrue=$v['exgrossTrue'];
            $xAxisArr[] = $v['prolineName'];
            $seriesGSArr[] = $exgross;
//            $seriesSJArr[] = $exgrossTrue;
        }
        foreach ($arrExTr as $va) {
            if (empty($v['exgrossTrue'])) $exgrossTrue = "0"; else $exgrossTrue = $va['exgrossTrue'];
//            $xAxisArr[] = $v['prolineName'];
//            $seriesGSArr[] = $exgross;
            $seriesSJArr[] = $exgrossTrue;
        }
        $outArr['xAxis'] = $xAxisArr;
        $outArr['seriesGS'] = $seriesGSArr;
        $outArr['seriesSJ'] = $seriesSJArr;
        return $outArr;
    }

    //��Ŀ�����ֲ�
    function conProNumMapChartJson()
    {
        $sql = "select c.contractProvince,r.proLineName,count(c.id) as num from oa_contract_project r
            left join oa_contract_contract c on r.contractId=c.id
            GROUP BY r.proLineName,c.contractProvince";

        $arr = $this->_db->getArray($sql);

        $outArr = array();
        $legendArr = array();
        foreach ($arr as $v) {
            if (!in_array($v['proLineName'], $legendArr)) {
                $legendArr[] = $v['proLineName'];
            }
        }
        $outArr = array();
        $maxNum = "0";
        foreach ($legendArr as $val) {
            foreach ($arr as $v) {
                if ($v['proLineName'] == $val) {
                    $dataArr[$val][] = array(
                        "name" => $v['contractProvince'],
                        "value" => intval($v['num'])
                    );
                }
                if ($v['num'] > $maxNum) {
                    $maxNum = $v['num'];
                }
            }
        }
        $outArr['legend'] = $legendArr;
        $outArr['series'] = $dataArr;
        $outArr['maxNum'] = $maxNum;
        return $outArr;
    }

    //��ƷӪ�ջ��� ����sql
    function getProSql()
    {
        $limitStr = $this->proLineDateLimit_tem();
        $endYear = substr($this->dateSection['endMonth'], 0, 4);
        $endMon = substr($this->dateSection['endMonth'], -2);

        $startYear = substr($this->dateSection['startMonth'], 0, 4);
        $startMonTemp = substr($this->dateSection['startMonth'], -2);
        if ($startMonTemp != '01') {
            $startMon = $startMonTemp - 1;
        } else {
            $startYear = $startYear - 1;
            $startMon = "12";
        }

        $sql = "select * from (
       select
   a.exeDeptName,a.exeDeptId,a.conProductName,
   round(sum(if((a.srzb*b.sr) is not null,(a.srzb*b.sr),0)),2) as sr,
   round(sum(if((a.cbzb*b.cb) is not null,(a.cbzb*b.cb),0)),2) as cb

from
(
SELECT
	p.id,
	p.contractId,
	p.conProductId,
	p.conProductName,
	p.number,
	p.money,
	c.contractMoney,
  p.proType,
	p.exeDeptName,
	p.exeDeptId,
	ROUND(p.money / c.contractMoney, 2) AS srzb,
  e.ma,
  c.costEstimates,
  ROUND(e.ma / c.costEstimates, 2) AS cbzb
FROM
	oa_contract_product p
LEFT JOIN oa_contract_contract c ON p.contractId = c.id
left join (
select contractId,conProductId,conProductName,sum(money) as ma
from oa_contract_equ where isDel=0 and isTemp=0 and conProductId is not null and conProductId <> 0
GROUP BY conProductId
)e on p.id=e.conProductId
WHERE
	p.isTemp = 0
AND p.isDel = 0
AND p.conProductId <> ''
AND p.exeDeptName <> ''
and p.proTypeId = '11'
)a
left join
(
select contractCode,contractId,prolineCode,prolineName,
                    round((sum(if(storeYear = '$endYear' and storeMon='$endMon',
                            if(earnings is null or earnings='',0,earnings),0))
                          -sum(if(storeYear = '$startYear' and storeMon='$startMon',
                            if(earnings is null or earnings='',0,earnings),0))))
                     as sr,
                     round((sum(if(storeYear = '$endYear' and storeMon='$endMon',
                            if(estimates is null or estimates='',0,estimates),0))
                          -sum(if(storeYear = '$startYear' and storeMon='$startMon',
                            if(estimates is null or estimates='',0,estimates),0))))
                     as cb
                   FROM oa_contract_project_record
                   where isUse=1  and checkTip=0
                 group by contractId,prolineCode

)b on a.contractId=b.contractId
 group by a.exeDeptName,a.conProductId) c where 1=1 $limitStr";

        return $sql;
    }

    /**
     *  ����ռ�ȣ�Ȼ���ȡͳ�Ʊ����� �õ�������뼰�ɱ�����ʱ��������
     */
    function handleProRow($row)
    {
        $limitStr = $this->proLineDateLimit();
        $endYear = substr($this->dateSection['endMonth'], 0, 4);
        $endMon = substr($this->dateSection['endMonth'], -2);

        $startYear = substr($this->dateSection['startMonth'], 0, 4);
        $startMonTemp = substr($this->dateSection['startMonth'], -2);
        if ($startMonTemp != '01') {
            $startMon = $startMonTemp - 1;
        } else {
            $startYear = $startYear - 1;
            $startMon = "12";
        }
        $sql = "select prolineCode,prolineName,

sum(if(storeYear = '$endYear' and storeMon='$endMon',
                            if(earnings is null or earnings='',0,earnings),0))
                          -sum(if(storeYear = '$startYear' and storeMon='$startMon',
                            if(earnings is null or earnings='',0,earnings),0)) as sr,
sum(if(storeYear = '$endYear' and storeMon='$endMon',
                            if(grossTrue is null or grossTrue='',0,grossTrue),0))
                          -sum(if(storeYear = '$startYear' and storeMon='$startMon',
                            if(grossTrue is null or grossTrue='',0,grossTrue),0)) as cb


                   FROM oa_contract_project_record
                   where isUse=1 and checkTip=0 and earningsTypeCode <> '' $limitStr
                 group by proLineCode";
        $reRow = $this->_db->getArray($sql);

        $srAll = "";
        $sbAll = "";
        foreach ($row as $k => $v) {
            $srAll += $v['sr'];
            $sbAll += $v['cb'];
            foreach ($reRow as $rev) {
                if ($v['exeDeptId'] == $rev['prolineCode']) {
                    $row[$k]['reSR'] = $rev['sr'];
                    $row[$k]['reCB'] = $rev['cb'];
                }
            }
        }

        foreach ($row as $k => $v) {
            $row[$k]['earnings'] = round($v['sr'] / $srAll * $v['reSR'], 2);
            $row[$k]['gross'] = round($v['cb'] / $srAll * $v['reCB'], 2);
            $row[$k]['exgross'] = (1 - round(round($v['cb'] / $srAll * $v['reCB'], 2) / round($v['sr'] / $srAll * $v['reSR'], 2), 4)) * 100;
        }

        return $row;
    }

    /**
     * ��Ŀ�ſ� �鿴����
     */
    function getProView($cid,$spcialArr = array())
    {
        $pArr = $this->get_d($cid);
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($pArr['contractId']);
        // �����Ҫɸѡ����,�ȶԶ�Ӧ�Ľ�����ɸ��
        if(isset($spcialArr['needFilt']) && $spcialArr['needFilt']){
            if(isset($spcialArr['saveDateRange'])){
                $startDate = $spcialArr['saveDateRange'][0];
                $endDate = $spcialArr['saveDateRange'][1];
                $sql = "select sum(IF(c.isRed = 1,-c.invoiceMoney,c.invoiceMoney)) as invoiceMoney
                  from oa_finance_invoice c where 1=1 
                  and(( c.objId = '{$pArr['contractId']}')) 
                  and(( c.objType in ('KPRK-12'))) 
                  and date_format(c.invoiceTime,'%Y%m%d') between date_format('{$startDate}','%Y%m%d') and date_format('{$endDate}','%Y%m%d')";
                $noCountInvoiceMoneyObj = $this->_db->getArray($sql);
                $noCountInvoiceMoney = (!empty($noCountInvoiceMoneyObj) && isset($noCountInvoiceMoneyObj[0]['invoiceMoney']))? $noCountInvoiceMoneyObj[0]['invoiceMoney'] : 0;
                $conArr['invoiceMoney'] = bcsub($conArr['invoiceMoney'],$noCountInvoiceMoney,2);// ��ȥ�������ڵĿ�Ʊ���
                // $conArr['uninvoiceMoney'] = bcadd($conArr['uninvoiceMoney'],$noCountInvoiceMoney,2);// δ��Ʊ�����ϱ������ڵĿ�Ʊ���
            }
        }

        $obj['contractStartDate'] = $conArr['beginDate'];
        $obj['contractEndDate'] = $conArr['endDate'];
        $obj['otherCost'] = $this->getPotherCost($pArr['projectCode']);//�������
        $proportion = $this->getAccBycid($pArr['contractId'], $pArr['proLineCode'], 11);
        $obj['workRate'] = round($proportion, 2);
        $obj['feeCostbx'] = $this->getFeeCostBx($conArr,$obj['workRate']);//����֧���ɱ�
        //��װ����
        //������Ϣ
        $obj['areaName'] = $conArr['areaName'];
        $obj['proLineName'] = $pArr['proLineName'];
        $obj['projectName'] = $conArr['contractName'];
        $obj['projectCode'] = $pArr['projectCode'];
        $obj['managerName'] = $conArr['prinvipalName'];
        $obj['productLineName'] = $this->getProLineName($pArr['contractId'], $pArr['proLineCode']); //ִ������
//        $obj['status'] = $this->getProStatus($pArr['contractId'], $pArr['proLineCode'], $conArr); //��Ŀ״̬
        $obj['country'] = $conArr['contractCountry'];
        $obj['province'] = $conArr['contractProvince'];
        $obj['city'] = $conArr['contractCity'];

        // �жϹ�����ͬ�Ƿ���ڲ���Ʊ�Ŀ�Ʊ����,
        $invoiceCodeArr = explode(",",$conArr['invoiceCode']);
        $isNoInvoiceCont = false;
        foreach ($invoiceCodeArr as $Arrk => $Arrv){
            if($Arrv == "HTBKP"){
                $isNoInvoiceCont = true;
            }
        }

        //��Ŀʵʱ״��
        if($isNoInvoiceCont || ($conArr['contractMoney'] === $conArr['uninvoiceMoney'] || $conArr['contractMoney']-$conArr['deductMoney']-$conArr['uninvoiceMoney'] <= 0)){
            $obj['invoiceExe'] = 100;
            $obj['invoiceExejs'] = 100;
        }else{
            $obj['invoiceExe'] =  round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 4)*100;//��Ʊ����
            $obj['invoiceExejs'] =  round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 9)*100;//��Ʊ����-����
        }

        $obj['estimates'] = $this->getCost($pArr['contractId'], $pArr['proLineCode'], $conArr, 0,$pArr['esmProjectId']); //��Ŀ����

        if ($conArr['contractType'] == 'HTLX-ZLHT') {
            $days = abs($this->getChaBetweenTwoDate($conArr['beginDate'], $conArr['endDate'])); //��������
            $obj['estimates'] = round(bcmul($days, bcdiv($obj['estimates'], 720, 9), 9), 2);
        }

//        $obj['DeliverySchedule'] = $this->getDelSul($pArr['contractId'], $pArr['proLineCode'],$obj['estimates'],$conArr);//��������
        $obj['DeliverySchedule'] = $this->getFHJD($pArr,$spcialArr);//��������
        $obj['estimatesExgross'] = $this->getCost($pArr['contractId'], $pArr['proLineCode'], $conArr, 1,$pArr['esmProjectId']); //����ë����
        $obj['earningsType'] = $pArr['earningsTypeName']; //����ȷ�Ϸ�ʽ
        $obj['budgetAll'] = $this->getCost($pArr['contractId'], $pArr['proLineCode'], $conArr, 0,$pArr['esmProjectId']); //��ǰ���� * ��Ʒ�ɱ� + ����/֧�����ñ�ŵ���ط��ã�Ŀǰû�к�һ���֣�

        $obj['schedule'] = $this->getSchedule($pArr['contractId'], $conArr, $pArr,0,$spcialArr); //��Ŀ����
        $obj['shipDate'] = $conArr['deliveryDate']; //������������
        $obj['planShipDate'] = $this->getShipInfo($pArr['contractId']); //Ԥ�ƽ�������
        $obj['actShipDate'] = $conArr['outstockDate']; //ʵ�ʽ�������
        $obj['proMoneyRate'] = $this->getCost($pArr['contractId'], $pArr['proLineCode'], $conArr, 3,$pArr['esmProjectId']); //˰����Ŀ���
        $obj['revenue'] = $this->getSchedule($pArr['contractId'], $conArr, $pArr,1,$spcialArr) ; //��ĿӪ��;

        $obj['shipCostT'] = $this->getFinalCost($pArr['projectCode'],$obj['revenue'],$obj['earningsType'],$conArr,$obj['DeliverySchedule'],$obj['estimates'],2);//���ᷢ���ɱ�

        $obj['finalExgross'] = $this->getFinalCost($pArr['projectCode'],$obj['revenue'],$obj['earningsType'],$conArr,$obj['DeliverySchedule'],$obj['estimates'],1);//��ǰë����

        //��ͬ�տ�״��
        $obj['contractMoney'] = $conArr['contractMoney'];
        $obj['projectMoney'] = $this->getAccMoneyBycid($pArr['contractId'], $pArr['proLineCode'], 11); //��Ŀ��ͬ��
        $obj['invoiceMoney'] = $conArr['invoiceMoney'];
        // $obj['objDeduct'] = round(($conArr['deductMoney']+$conArr['badMoney']) * $proportion/100,2);
        $obj['objDeduct'] = round(($conArr['deductMoney']) * $proportion/100,2);
        $obj['objBad'] = round(($conArr['badMoney']) * $proportion/100,2);
        $obj['unInvoiceMoney'] = $conArr['uninvoiceMoney'];// - $conArr['invoiceMoney'];//����Ʊ���ֱ�ӵ��ں�ͬ��Ĳ���Ʊ���
        $obj['incomeMoney'] = $conArr['incomeMoney'];
        if($conArr['contractMoney']-$conArr['deductMoney']-$conArr['uninvoiceMoney'] <= 0){
            $obj['incomeExe'] = 100;
        }else{
            $obj['incomeExe'] = round(bcdiv($conArr['incomeMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['badMoney'],9),9), 9), 4)*100;
        }

        // ���޺�ͬ����
        $date1 = strtotime($conArr['beginDate']);
        $date2 = strtotime($conArr['endDate']);
        $date3 = strtotime(date("Y-m-d"));
        $allDays = ($date2 - $date1) / 86400 + 1;
        $finishDays = ($date3 - $date1) / 86400 + 1;
        $rentPerc = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);

        $obj['unIncomeMoney'] = $conArr['contractMoney'] - $conArr['incomeMoney'];
        $obj['txaRate'] = $this->getTxaRate($conArr); //�ۺ�˰��
        $obj['txaRateView'] = $obj['txaRate'] * 100; //�ۺ�˰��
        //��ͬ��Ϣ
        $obj['customerName'] = $conArr['customerName'];
        $obj['customerTypeName'] = $conArr['customerTypeName'];
        $obj['areaName'] = $conArr['areaName'];
        $obj['contractCode'] = $conArr['contractCode'];

        $obj['salesman'] = $conArr['prinvipalName'];
        $obj['ExaDTOne'] = $conArr['ExaDTOne'];
        $obj['linkman'] = $this->getLinkman($conArr['customerId']);

        $obj['status'] = $this->getProStatusEx($obj['DeliverySchedule'], $obj['invoiceExejs'],$obj['earningsType'],$rentPerc); //��Ŀ״̬

        if($obj['earningsType'] == '����������' || $obj['earningsType'] == '����Ʒ������'){
            $obj['shipCost'] = $obj['shipCostT'];
        }else{
            if($obj['schedule'] == 0){
                $obj['shipCost'] = 0;//�����ɱ�
            }else if($obj['schedule'] > 0 && $obj['schedule']<100){
                $tmpCost = $obj['DeliverySchedule']==100?$obj['shipCostT']:$obj['estimates'];
                // $tmpInvoice = bcdiv($conArr['invoiceMoney'],bcsub(bcsub($conArr['contractMoney'],$conArr['deductMoney'],2),$conArr['uninvoiceMoney'],2),10) * 100;
                // $tmpInvoice = $conArr['invoiceMoney']/$conArr['contractMoney']*100;
                $processPerc = $obj['schedule'];
                if($obj['earningsType'] == '����Ʊ����'){
                    $obj['shipCost'] = round($processPerc / 100 * $tmpCost,2);//�����ɱ�
                }else{
                    $obj['shipCost'] = round($processPerc / 100 * $tmpCost,2);//�����ɱ�
                }

            }else{
                $obj['shipCost'] = $obj['DeliverySchedule']<100?$obj['estimates']:$obj['shipCostT'];//�����ɱ�
            }
        }
        $obj['finalCost'] = $obj['shipCost'] + $obj['feeCostbx'] + $obj['otherCost'];//��Ŀ����

//        $obj['finalGross'] = $this->getSchedule($pArr['contractId'], $conArr, $pArr,2) - $obj['otherCost'] - $obj['feeCostbx']-$obj['shipCost']; //��Ŀë��
        $obj['finalGross'] = $obj['revenue'] - $obj['otherCost'] - $obj['feeCostbx']-$obj['shipCost']; //��Ŀë��

        $obj['budgetExgross'] = round(($obj['finalGross']/$obj['revenue']),4)*100; //��ǰë���� (PMS449 ��Ϊ��Ŀë��/��ĿӪ��)

        $obj['feeSchedule'] = round($obj['finalCost']/$obj['estimates'],2)*100;
        $obj['reserveEarnings'] = $this->reserveEarnings($conArr,$obj['txaRate'],$obj['schedule'],$pArr,$obj['earningsType']);
        $obj['equCost'] = $this->getCost($pArr['contractId'], $pArr['proLineCode'], $conArr, 0,$pArr['esmProjectId']);
        $obj['rateMoney'] = $this->getCost($pArr['contractId'], $pArr['proLineCode'], $conArr, 3);
        $obj['moduleName'] = $pArr['moduleName'];
        return $obj;
    }
    //�����ɱ�
    function getPotherCost($objCode){
        $otherSql = "select cost from oa_esm_project_othercost where projectCode = '".$objCode."'";
        $otherCostArr = $this->_db->getArray($otherSql);
        return $otherCostArr[0]['cost'];
    }
    //����֧���ɱ�
    function getFeeCostBx($conArr,$workRate){

        $otherDatasDao = new model_common_otherdatas();
        $configVal = $otherDatasDao->getConfig('engineering_budget_payables');
        $configArr = explode(",",$configVal);
        //ѭ��ƴװ ������
        $notType = "";
        foreach($configArr as $v){
            $notType .= "'".$v."',";
        }
        $notType = rtrim($notType,",");

        if(empty($conArr['id'])){
            return 0;
        }
        $sql = "select  sum(costMoney) as costMoney FROM oa_finance_cost c WHERE 1 and left(c.inPeriod, 4) >=2017 and costTypeName not in (".$notType.") and  c.auditStatus = 1 AND c.isTemp = 0 AND c.isDel = 0 and c.contractId='".$conArr['id']."'";
        $result = $this->_db->get_one($sql);
        return round($result['costMoney'] * $workRate / 100,2);
    }

    /**
     * ����֧��ͳ��
     * @param $conArr
     * @return float|int
     */
    function getFeeCostCount($cid,$extSql = "",$type = "main"){
        $otherDatasDao = new model_common_otherdatas();
        $configVal = $otherDatasDao->getConfig('engineering_budget_payables');
        $configArr = explode(",",$configVal);
        //ѭ��ƴװ ������
        $notType = "";
        foreach($configArr as $v){
            $notType .= "'".$v."',";
        }
        $notType = rtrim($notType,",");

        $sql = ($type != 'main')? "SELECT
                id,contractId,parentTypeId,parentTypeName,costTypeId,costTypeName,inPeriod,sum(costMoney) as costMoney
            FROM
                oa_finance_cost
            WHERE
                1
            AND LEFT (inPeriod, 4) >= 2017
            AND costTypeName NOT IN (".$notType.")
            AND auditStatus = 1
            AND isTemp = 0
            AND isDel = 0
            AND contractId = {$cid} {$extSql} group by costTypeName,inPeriod;" : "SELECT
                id,contractId,parentTypeId,parentTypeName,costTypeId,costTypeName,inPeriod,sum(costMoney) as costMoney
            FROM
                oa_finance_cost
            WHERE
                1
            AND LEFT (inPeriod, 4) >= 2017
            AND costTypeName NOT IN (".$notType.")
            AND auditStatus = 1
            AND isTemp = 0
            AND isDel = 0
            AND contractId = {$cid} {$extSql} group by costTypeId;";

        $result = $this->_db->getArray($sql);
        return $result;
    }

    function getShipCost($schedule,$invoiceExe,$DeliverySchedule,$shipCostT,$estimates,$earningsType,$finalCost,$conArr){
        if($earningsType == '����������' || $earningsType == '����Ʒ������'){
            return $shipCostT;
        }
        if($schedule == 0){
            return 0;//�����ɱ�
        }else if($schedule > 0 && $schedule<100){
            $tmpCost = $DeliverySchedule==100?$shipCostT:$estimates;
            // $tmpInvoice = $conArr['invoiceMoney']/$conArr['contractMoney']*100;
            // $tmpInvoice = bcdiv($conArr['invoiceMoney'],bcsub(bcsub($conArr['contractMoney'],$conArr['deductMoney'],2),$conArr['uninvoiceMoney'],2),5) * 100;
            if($earningsType == '����Ʊ����'){
                return  round($schedule / 100 * $tmpCost,2);//�����ɱ�
            }else{
                return  round($schedule / 100 * $tmpCost,2);//�����ɱ�
            }
        }else{
            return  $DeliverySchedule<100?$estimates:$shipCostT;//�����ɱ�
        }
    }
    //�ͻ���һ����ϵ��
    function getLinkman($cusId){
        if(empty($cusId)){
            return "";
        }
        $sql = "select linkmanName from oa_customer_linkman where customerId = '$cusId'";
        $linkmanArr = $this->_db->getArray($sql);
        return $linkmanArr[0]['linkmanName'];
    }
    //��������
    function getDelSul($cid, $lineCode,$estimates,$conArr){
        $pidStrSql = "select GROUP_CONCAT(id) as pidStr from oa_contract_product where contractId='$cid' and newProLineCode='$lineCode'";
        $pIdStr = $this->_db->getArray($pidStrSql);

        if ($conArr['DeliveryStatus'] == "TZFH") {
            return 100;
        }

        if(!empty($pIdStr[0]['pidStr'])){
            $sql = "select sum(executedNum * price) as allCost from oa_contract_equ where 1=1 and contractId=$cid and (conProductId in (" . $pIdStr[0]['pidStr'] . ") or proId in(" . $pIdStr[0]['pidStr'] . ")) and isTemp=0 and isDel=0 GROUP BY contractId";
            $costArr = $this->_db->getArray($sql);
            if(round($costArr[0]['allCost']/$estimates*100,2) > 100){
                return 100;
            }
            return round($costArr[0]['allCost']/$estimates*100,2);
        }else{
            return 0.00;
        }

    }
    //��Ŀ����1
    function getFinalCost($pCode,$revenue,$earningsType,$conArr,$DeliverySchedule,$estimates,$type=0){
        $invoiceExe = bcdiv($conArr['invoiceMoney'],bcsub(bcsub($conArr['contractMoney'],$conArr['deductMoney'],2),$conArr['uninvoiceMoney'],2),5) * 100;
        // $invoiceExe = $conArr['invoiceMoney']/$conArr['contractMoney']*100;
        $sql = "select cost from oa_esm_project_shipcost where projectCode = '$pCode'";
        $fCostArr = $this->_db->getArray($sql);
        $processPerc = ($invoiceExe > $DeliverySchedule)? $DeliverySchedule : $invoiceExe;
        if($earningsType == '����Ʊ����'){
            if($DeliverySchedule < 100){
                $fCost = round($estimates * $processPerc / 100,2);
            }else{
                $fCost = round($fCostArr[0]['cost'] * $processPerc / 100,2);
            }
        }else{
            $fCost = $fCostArr[0]['cost'];
        }
    	if($type==0){
    		return   $fCost;
    	}else if($type==1){
            return  round(1-($fCost/$revenue),2)*100;
    	}else if($type==2){
            return  $fCostArr[0]['cost'];
        }else{
    		return false;
    	}

    }

    //��ȡִ������
    function getProLineName($cid, $lineCode)
    {
        $sql = "select exeDeptName from oa_contract_product where contractId='$cid' and newProLineCode='$lineCode' LIMIT 1";
        $linArr = $this->_db->getArray($sql);
        return $linArr[0]['exeDeptName'];
    }

    //��ȡ��Ŀ״̬
    function getProStatus($cid, $lineCode, $conArr)
    {
        $pidStrSql = "select GROUP_CONCAT(id) as pidStr from oa_contract_product where contractId='$cid' and newProLineCode='$lineCode'";
        $pIdStr = $this->_db->getArray($pidStrSql);
        $sql = "select if(sum(number)-sum(executedNum)+sum(backNum)>0,0,1) as stu from oa_contract_equ where 1=1 and contractId=$cid and (conProductId in (" . $pIdStr[0]['pidStr'] . ") or proId in(" . $pIdStr[0]['pidStr'] . ")) and isTemp=0 and isDel=0 GROUP BY contractId";
        //����״̬ 0 δ��� 1 �����
        $shipStr = $this->_db->getArray($sql);
        $shipStatus = $shipStr[0]['stu'];
        //�жϺ�ͬ��Ʊ 0 δ��� 1 �����
        if ($conArr['contractMoney'] - $conArr['invoiceMoney'] > 0) {
            $invoiceStatus = "0";
        } else {
            $invoiceStatus = "1";
        }
        //�ж���Ŀ״̬
        if ($shipStr == "1" && $invoiceStatus == "1") {
            return "�ر�";
        } else if ($shipStr == "1") {
            return "�깤";
        } else {
            return "�ڽ�";
        }
    }
    function getProStatusEx($DeliverySchedule, $invoiceExe ,$earningsType, $rentPerc = 0){
        if($earningsType == '����������'){
            if($DeliverySchedule >= 100){
                if($invoiceExe >= 100){
                    return "�ر�";
                }else{
                    return "�깤";
                }
            }else{
                return "�ڽ�";
            }
        }else if($earningsType == '����Ʒ������'){
            if($rentPerc >= 100){
                if($invoiceExe >= 100){
                    return "�ر�";
                }else{
                    return "�깤";
                }
            }else{
                return "�ڽ�";
            }
        }else{
            if($invoiceExe >= 100){
                if($DeliverySchedule >= 100){
                    return "�ر�";
                }else{
                    return "�ڽ�";// PMS 643 ����ɿ�Ʊ����ɷ���ʱ�����Ϊ�깤, ��PMS 730 ��Ϊ���ڽ�
                }
            }else{
                //2017-07-10 PMS 2748 ��Ӵ�����
                if($DeliverySchedule >= 100){
                    return "�깤";
                }
                return "�ڽ�";
            }
        }
    }

    //���㡢ë����
    function getCost($cid, $lineCode, $conArr, $type,$esmProjectId = 0)
    {
        $extParam = " ";
        if(empty($esmProjectId)){
            $extParam = " and issale=1";
        }
        $txaRate = $this->getTxaRate($conArr); //�ۺ�˰��
        $costSql = "select confirmMoney from oa_contract_cost where contractId='".$conArr['id']."' and productLine='".$lineCode."' $extParam";
        $costArr = $this->_db->getArray($costSql);
        $cost = $costArr[0]['confirmMoney']; // ����

        $proMoney = $this->getAccMoneyBycid($cid, $lineCode, 11); //��Ŀ��ͬ��
        $estimatesExgross = (1 - bcdiv($cost, round($proMoney / (1 + $txaRate),4), 4)) * 100; //����ë����
        $budgetExgross =    (1 - bcdiv($cost, ($proMoney / (1 + $txaRate)), 4)) * 100; //��ǰë����
        if ($type == 0) {
            return $cost;
        } else if ($type == 1) {
            return $estimatesExgross;
        } else if ($type == 2) {
            return $budgetExgross;
        } else if ($type == 3) {
            return round($proMoney / (1 + $txaRate),2);
        } else {
            return 0;
        }
    }

    //����ȷ�ϰ�ʱ
    function getEarningsType($cid)
    {
        $sql = "select goodsTypeStr,contractType from oa_contract_contract where id ='$cid' ";
        $arr = $this->_db->getArray($sql);
        $goodsArr = explode(",", $arr[0]['goodsTypeStr']);
        if($arr[0]['contractType'] == 'HTLX-ZLHT' && in_array("11", $goodsArr)){
            return "����Ʒ������";
        } else if (in_array("11", $goodsArr) && in_array("17", $goodsArr)) {
            return "����������";
        } else if (in_array("11", $goodsArr) && !in_array("17", $goodsArr)) {
            return "����Ʊ����";
        } else if (!in_array("11", $goodsArr) && in_array("17", $goodsArr)) {
            return "��������Ŀ����";
        } else {
            return "";
        }
    }

    //��Ŀ����
    function getSchedule($cid, $conArr, $arr,$t="0",$spcialArr = array())
    {
//        if($conArr['contractMoney']-$conArr['deductMoney']-$conArr['uninvoiceMoney'] <= 0 && $t != "1"){
//            return 100;
//        }

        $type =  !empty($arr['earningsTypeName'])?$arr['earningsTypeName']:$arr['incomeTypeName'];
        //��������
        $proLine = !empty($arr['proLineCode'])?$arr['proLineCode']:$arr['newProLine'];
        $estimates = $this->getCost($cid, $proLine, $conArr, 0); //��Ŀ����
        $tureFHJD = $this->getFHJD($arr,$spcialArr);

        // �жϹ�����ͬ�Ƿ���ڲ���Ʊ�Ŀ�Ʊ����,
        $invoiceCodeArr = explode(",",$conArr['invoiceCode']);
        $isNoInvoiceCont = false;
        foreach ($invoiceCodeArr as $Arrk => $Arrv){
            if($Arrv == "HTBKP"){
                $isNoInvoiceCont = true;
            }
        }

        $proMoney = $this->getAccMoneyBycid($cid, $proLine, 11); //��Ŀ��ͬ��
        $txaRate = $this->getTxaRate($conArr); //�ۺ�˰��
        $rateMoney = ($proMoney - $conArr['deductMoney'] - $conArr['badMoney']) / (1 + $txaRate); //��˰���- ���ڼ���
        if (!empty($type)) {
            switch ($type) {
                case "��������Ŀ����" :
                    $schedule = "0.00"; //��Ʒ����Ŀ����Զ�������������
                    break;
                case "����������" :
                    if ($conArr['DeliveryStatus'] == "TZFH") {
                        $schedule = "100";
                    } else {
                        $schedule = isset($tureFHJD) ? $tureFHJD : "0.00";
                    }
                    break;
                case "����Ʊ����" :
                    $invSchedule = $shipSchedule = 0;
                    // ��Ʊ����
                    if ($conArr['DeliveryStatus'] == "TZFH") {
                        $shipSchedule = "100";
                    } else {
                        $shipSchedule = isset($tureFHJD) ? $tureFHJD : "0.00";
                    }

                    // ������ڲ���Ʊ��ͬ,�Ұ���Ʊ���ȹ����,Ĭ�Ͻ���Ϊ100
                    if($isNoInvoiceCont || ($conArr['contractMoney'] - ($conArr['uninvoiceMoney'] + $conArr['deductMoney']) == 0)){
                        $invSchedule = 100;
                        $scheduleT = 100;
                    }else{
                        $invSchedule = round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 9)*100;//��Ʊ����
                        $scheduleT = round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['uninvoiceMoney'],9),9), 9), 9);//��Ʊ����
                    }

                    // PMS 696 ����Ʊ���ȼ������Ҫȡ��Ʊ�뷢�������е���Сֵ
                    $schedule = ($shipSchedule >= $invSchedule)? $invSchedule : $shipSchedule;

                    if ($scheduleT > 1) $scheduleT = 1;
                    $earnings = bcmul($scheduleT, $rateMoney, 2);
                    break;
                case "����Ʒ������" :
                    // ���޺�ͬ����
                    $date1 = strtotime($conArr['beginDate']);
                    $date2 = strtotime($conArr['endDate']);
                    $date3 = strtotime(date("Y-m-d"));
                    $allDays = ($date2 - $date1) / 86400 + 1;
                    $finishDays = ($date3 - $date1) / 86400 + 1;
                    $schedule = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);
                    break;
            }
        } else {
            $schedule = "0.00";
        }
        if ($schedule > 100) {
            $schedule = '100';
        }
        $proportion = $this->getAccBycid($arr['contractId'], $proLine, 11)/100;
        $proportion = sprintf("%.10f", $proportion);
        if($t=="0"){
            return round($schedule,4);
        }else if($t=="1"){
            $schedule = round($schedule,4);
            //2017-7-17 pms201 ��ӹ��򣬲�Ʒ���쳣�رյĺ�ͬ����Ϊ0
            if($conArr['state'] == '7'){
                return 0;
            }
            if($conArr['contractMoney'] - ($conArr['uninvoiceMoney'] + $conArr['deductMoney']) == 0){
                $FPJD = 1;
            }else{
                $FPJD = round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['badMoney'])), 6), 9);
            }
            if($FPJD < 1){
                $proschedule = $this->getSchedule($arr['contractId'], $conArr, $arr); //��Ŀ����
                $reserveEarnings = $this->reserveEarnings($conArr,$txaRate,$proschedule,$arr,$type);
                $culSchedule = bcdiv(bcmul(bcdiv($proMoney,(1+$txaRate),6),$schedule,5),100,5) - round(bcmul(bcdiv($conArr['deductMoney'],(1+$txaRate),5),$proportion,5),5) -$reserveEarnings;
                $culSchedule = ($culSchedule <= 0)? 0 : $culSchedule;
                return round($culSchedule,4);
            }
            $culSchedule = bcdiv((bcmul((bcdiv($proMoney,(1+$txaRate),3)),$schedule,6)),100,5) - round(bcmul(bcdiv($conArr['deductMoney'],(1+$txaRate),5),$proportion,5),5);
            $culSchedule = ($culSchedule <= 0)? 0 : $culSchedule;
            return round($culSchedule,4);
        }else if($t=="2"){
            $culSchedule = round(bcdiv(bcmul(bcdiv($proMoney,(1+$txaRate),5),$schedule,5),100,3),5);
            $culSchedule = ($culSchedule <= 0)? 0 : $culSchedule;
            return round($culSchedule,4);
        }else{
            return round($schedule,4);
        }

    }

    //������Ϣ
    function getShipInfo($cid)
    {
        $sql = "select shipPlanDate from oa_stock_outplan where docId='$cid' ORDER BY id desc limit 1";
        $arr = $this->_db->getArray($sql);
        return $arr[0]['shipPlanDate'];
    }

    //��������汾
    function confirmIncome_d()
    {
        ini_set("memory_limit", "100M");
        $sql = "select * from oa_contract_project where esmProjectId is null or esmProjectId = '' or esmProjectId = 0";
        $proData = $this->_db->getArray($sql);

        // ѭ����ȡ��Ŀ����
        if ($proData) {
            $conDao = new model_contract_contract_contract();
            foreach ($proData as $k => $v) {
                $thisDate = date('Y-m-01');
                $conArr = $conDao->get_d($v['contractId']);
                $proMoney = $this->getAccMoneyBycid($v['contractId'], $v['proLineCode'], 11); //��Ŀ��ͬ��
                $proportion = $this->getProportion($proMoney, $conArr['contractMoney']);
                $earningsType = $v['earningsTypeName'];
                $schedule = $this->getSchedule($v['contractId'], $conArr, $v); //��Ŀ����
                //��װ��ʱ��������
                $deletSql = "delete from oa_contract_project_income where version='$thisDate' and contractId='" . $v['contractId'] . "' and projectId='" . $v['id'] . "'";
                $this->_db->query($deletSql);
                $insertSql = "INSERT INTO oa_contract_project_income " .
                    "(version,contractId,projectId,proMoney,proportion,earningsType,schedule,invoiceMoney,deductMoney,revenue,icMoney) " .
                    "VALUES (" .
                    "'$thisDate'," . $v['contractId'] . "," . $v['id'] . ", $proMoney,$proportion,'$earningsType',$schedule, " . $conArr['invoiceMoney'] . "," . $conArr['deductMoney'] . ",0, 0)";
                $this->_db->query($insertSql);
            }
            return 1;
        }
        return 0;
    }

    //���³ɱ��汾
    function confirmCost_d()
    {
        ini_set("memory_limit", "100M");
        $sql = "select * from oa_contract_project where esmProjectId is null or esmProjectId = '' or esmProjectId = 0";
        $proData = $this->_db->getArray($sql);

        // ѭ����ȡ��Ŀ����
        if ($proData) {
            $conDao = new model_contract_contract_contract();
            foreach ($proData as $k => $v) {
                $thisDate = date('Y-m-01');
                $conArr = $conDao->get_d($v['contractId']);
                $cost = $this->getCost($v['contractId'], $v['proLineCode'], $conArr, 0);
                if (empty($cost)) {
                    $cost = 0;
                }
                //��װ��ʱ��������
                $deletSql = "delete from oa_contract_project_cost where version='$thisDate' and contractId='" . $v['contractId'] . "' and projectId='" . $v['id'] . "'";
                $this->_db->query($deletSql);
                $insertSql = "INSERT INTO oa_contract_project_cost " .
                    "(`version`, `contractId`, `projectId`, `materialCost`, `payCost`) " .
                    "VALUES " .
                    "('$thisDate'," . $v['contractId'] . "," . $v['id'] . ", $cost, 0)";
                $this->_db->query($insertSql);
            }
            return 1;
        }
        return 0;
    }

    function getIncomeList($pid,$condition = '',$isMax = true)
    {
//        $sql = "select * from oa_contract_project_income where projectId = '$pid'";
        $sql = "select * from oa_contract_project_record where pid = '{$pid}' {$condition} order by version desc;";
        $rows = $this->_db->getArray($sql);

        $backArr = array();
        if($isMax && $rows){
            $backArr[] = $rows[0];
        }else{
            $backArr = $rows;
        }
        return $backArr;
    }

    function getCostList($pid)
    {
//        $sql = "select * from oa_contract_project_cost where projectId = '$pid'";
        $sql = "SELECT
            pc.*, cp.projectCode, ps.cost as shipcost, po.cost as othercost
        FROM
            oa_contract_project_cost pc
        LEFT JOIN oa_contract_project cp ON pc.projectId = cp.id
        LEFT JOIN oa_esm_project_shipcost ps ON cp.projectCode = ps.projectCode
        LEFT JOIN oa_esm_project_othercost po ON cp.projectCode = po.projectCode
        WHERE
            cp.id = '{$pid}';";
        return $this->_db->getArray($sql);
    }

    function getEquList($cid, $lineCode)
    {
        $pidStrSql = "select GROUP_CONCAT(id) as pidStr from oa_contract_product where contractId='$cid' and newProLineCode='$lineCode' and isDel=0 and isTemp=0";
        $pIdStr = $this->_db->getArray($pidStrSql);
        $sql = "select * from oa_contract_equ where contractId='$cid' and (conProductId in (" . $pIdStr[0]['pidStr'] . ") or proId in(" . $pIdStr[0]['pidStr'] . ")) and isTemp=0 and isDel=0 ";
        return $this->_db->getArray($sql);
    }

    /**
     * ������Ŀ��Ż�ȡ��Ŀ��Ϣ
     * @param $code
     * @return array
     */
    function getProjectInfoByCode_d($code) {
        $sql = "select
                'con' as projectType,concat('con', cast(c.id as char(10))) as id,p.id as projectId,p.projectCode,
                p.projectCode as number,p.projectName as name,
                p.projectName,prinvipalName as managerName,null AS description,prinvipalId AS managerId,
                c.prinvipalDeptId as deptId,c.prinvipalDept as deptName
            from oa_contract_project p
            LEFT JOIN oa_contract_contract c ON p.contractId = c.id
            where p.esmProjectId is null AND c.state = 2 AND p.projectCode = '" . $code . "'";
        return $this->_db->get_one($sql);
    }

    /**
     *  ���²�Ʒ��Ŀ����ֵ
     */
    function updateSaleProjectVal_d($projectCode = ''){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');	//�����ڴ�

        $sql = "SELECT * FROM oa_contract_project where (esmProjectId is null or esmProjectId = '')";
        if (!is_array($projectCode) && $projectCode != '') {
            $sql .= " AND projectCode = '$projectCode'";
        }
        $rows = $this->_db->getArray($sql);
        $conDao = new model_contract_contract_contract();
        $productDao = new model_contract_contract_product();

        $esmdeadlineDao = new model_engineering_baseinfo_esmdeadline();
        $thisMonthData = $esmdeadlineDao->getCurrentSaveDateRange();
        $spcialArr = array();
        if(!empty($thisMonthData) && isset($thisMonthData['startDate']) && isset($thisMonthData['endDate']) && (isset($thisMonthData["inRange"]) && $thisMonthData["inRange"] == "1")){
            $spcialArr['needFilt'] = true;
            $spcialArr['saveDateRange'] = array($thisMonthData['startDate'],$thisMonthData['endDate']);
        }else{
            $spcialArr['needFilt'] = false;
        }

        $processNum = 0;
        foreach($rows as $k => $v){
            $conArr = $conDao->get_d($v['contractId']);
            $projectRow = $this->getProView($v['id'],$spcialArr);
            // ��Ʒ�࣬��ȡ��ͬ��Ʒִ������
            $rs = $productDao->find(array('contractId' => $v['contractId'], 'newProLineCode' => $v['proLineCode'],
                'proTypeId' => '11', 'isDel' => '0'), null, 'exeDeptId,exeDeptName');

            // �жϹ�����ͬ�Ƿ���ڲ���Ʊ�Ŀ�Ʊ����,
            $invoiceCodeArr = explode(",",$conArr['invoiceCode']);
            $isNoInvoiceCont = false;
            foreach ($invoiceCodeArr as $Arrk => $Arrv){
                if($Arrv == "HTBKP"){
                    $isNoInvoiceCont = true;
                }
            }

            $officeid        = empty($rs['exeDeptId']) ? '' : $rs['exeDeptId']; //ִ������ID
            $officeName      = empty($rs['exeDeptName']) ? '' : $rs['exeDeptName']; //ִ������
            $proLineName     = $v['proLineName'];//����
            $proLinecode     = $v['proLineCode'];//����ID
            $areaName        = $conArr['areaName'];//s��������
            $areaCode        = $conArr['areaCode'];//��������ID
            $contractstatus  = $conArr['state'];//��ͬ״̬
            $status          = $projectRow['status']; //��Ŀ״̬
            $contractMoney   = $conArr['contractMoney'];//��ͬ���
            $projectMoney    = $this->getAccMoneyBycid($v['contractId'], $v['proLineCode'], 11);//��Ŀ���
            $point           = $projectRow['txaRate']; //˰��
            $deductMoney     = $projectRow['objDeduct'];//�ۿ�
            $badMoney        = $projectRow['objBad'];//����
            $proschedule     = ($isNoInvoiceCont)? 100 : $projectRow['schedule'];//����
            $revenue         = $projectRow['revenue'];//Ӫ��
            $reserveEarnings = $projectRow['reserveEarnings'];//Ԥ��Ӫ��
            $budgetAll       = $projectRow['estimates'];//Ԥ��
            $estimates       = $projectRow['estimates'];//����
            $cost            = $projectRow['finalCost'];//�ܳɱ�
            $reCost          = $projectRow['feeCostbx'];//����֧���ɱ�
            $shipCost        = $projectRow['shipCost'];//�����ɱ�
            $otherCost       = $projectRow['otherCost'];//�����ɱ�
            $deliverySchedule= $projectRow['DeliverySchedule'];//��������
            $invoiceMoney    = $projectRow['invoiceMoney'];// ��Ʊ���
            $unInvoiceMoney  = $projectRow['unInvoiceMoney'];// ����Ʊ���
            $rateMoney       = $projectRow['rateMoney'];// ˰���ͬ��
            $txaRate         = $projectRow['txaRate'];// �ۺ�˰��
            $exgross         = $projectRow['budgetExgross'];// ��ǰë����

            $update = "update oa_contract_project set ".
                        "officeId='".$officeid."',officeName='".$officeName."',proLineCode='".$proLinecode."',proLineName='".$proLineName."'".
                        ",exeAreaId='".$officeid."',exeArea='".$officeName."',areaName='".$areaName."',areaCode='".$areaCode."'".
                        ",contractMoney='".$contractMoney."',proMoney='".$projectMoney."'".
                        ",point='".$point."',badMoney='".$badMoney."',deductMoney='".$deductMoney."',schedule='".$proschedule."',proschedule='".$proschedule."',proStatus='".$status."'".
                        ",revenue='".$revenue."',feeAll='".$cost."',cost='".$cost."',deliverySchedule='".$deliverySchedule."'".
                        ",budget='".$budgetAll."',estimates='".$estimates."'".
                        ",reserveEarnings='".$reserveEarnings."',reCost='".$reCost."',shipCost='".$shipCost."',otherCost='".$otherCost."' ".
                        ",invoiceMoney='".$invoiceMoney."',unInvoiceMoney='".$unInvoiceMoney."',contractstatus ='".$contractstatus."'".
                        ",rateMoney='".$rateMoney."',txaRate='".$txaRate."'".",exgross='".$exgross."'".
                        " where id='".$v['id']."'" ;

            $this->_db->query($update);
            $processNum += 1;
        }

        // ��־д��
        $now = time();
        $thisMonth = date('n', $now);// ��ǰ��
        $logDao = new model_engineering_baseinfo_esmlog();
        $logDao->addLog_d(-1, '���²�Ʒ��Ŀ��������', $processNum . '|' . $thisMonth);
    }

    /**
     * ��ȡ��ͬ��Ŀ����
     * @param $contractId
     * @return int|string
     */
    function getContractProjectProcess_d($contractId) {
        // ��Ʒ��ȡ
        $productDao = new model_contract_contract_product();
        $productLines = $productDao->getProductLineDetails_d($contractId);

        $proRate = 0; // ��Ʒռ��
        $process = 0; // ��Ʒ�����ܽ���
        foreach ($productLines as $k => $v) {
            if ($k != 17) {
                foreach ($v as $ki => $vi) {
                    // ��ǰ���߽���
                    $proLineProcess = $this->getFHJD(array(
                        'contractId' => $contractId,
                        'productLine' => $vi['productLine']
                    ));

                    // ռ�ȵ���
                    $proRate = bcadd($proRate, $vi['productLineRate'], 2);

                    // ���ȵ���
                    $process = bcadd($process, round(bcmul($proLineProcess, bcdiv($vi['productLineRate'], 100, 4), 4), 2), 2);
                }
            }
        }
        return round(bcmul(bcdiv($process, $proRate, 4), 100, 4), 2);
    }

    //Ԥ��Ӫ�ռ���
    function reserveEarnings($conArr,$txaRate,$proschedule,$pArr,$invoiceType){
        $proLine = !empty($pArr['proLineCode'])?$pArr['proLineCode']:$pArr['newProLine'];
        $proportion = round($this->getAccBycid($pArr['contractId'], $proLine, 11), 1);
        $FPJD = round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($conArr['deductMoney'], $conArr['badMoney'],9),9), 9), 9);

        $point = 0;
        if($proschedule>98){
            if($FPJD>=1){
                $point = 0;
            }elseif($FPJD<0.98){
                $point = 0.02;
            }else{
                $proschedule = $proschedule /100;
                $point = 1-min($FPJD,$proschedule);
            }
        }
        $deductMoney = round($conArr['deductMoney'] * $proportion/100,2);
        if(!empty($pArr['newProLine'])){
            $pArr['proLineCode'] = $pArr['newProLine'];
        }
        $rateMoney = $this->getCost($pArr['contractId'], $pArr['proLineCode'], $conArr, 3); //˰����Ŀ���
        if($invoiceType == '����Ʊ����'){
            return 0;
        }
        return round(($rateMoney-$deductMoney/(1+$txaRate))*$point,2);
    }
    //���뽻���ɱ�
    function getShipCostByimport($pCode){
        $sql = "select cost from oa_esm_project_shipcost where projectCode = '$pCode'";
        $fCostArr = $this->_db->getArray($sql);
        return   $fCostArr[0]['cost'];
    }
    function getOtherCostByimport($pCode){
        $sql = "select cost from oa_esm_project_othercost where projectCode = '$pCode'";
        $fCostArr = $this->_db->getArray($sql);
        return   $fCostArr[0]['cost'];
    }

    /**
     * ���ܱ��ȡ����ֵ
     */
    function getCurIncomeByPro($pro){
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($pro['contractId']);
        $txaRate = $this->getTxaRate($conArr);
        return round($conArr['invoiceMoney'] / (1+$txaRate),2);
    }

    /**
     * ��ȡ���ܱ��ȡ��ĿӦ��ֵ
     */
    function getCurIncomeByProNew($pro){
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($pro['contractId']);
        return $this->getSchedule($pro['contractId'], $conArr, $pro,1);
    }

    /**
     * ������Ŀid��ȡ��Ŀ��ϸӳ���
     * @param $projectIds
     * @return array
     */
    function getMapByIds_d($projectIds) {
        $this->searchArr = array('ids' => $projectIds);
        $data = $this->list_d();

        $rst = array();
        if (!empty($data)) {
            foreach ($data as $v) {
                $v['curIncome'] = $this->getCurIncomeByProNew($v);
                $v['feeAll'] = $this->getFeeAllByPro($v);
                $rst[$v['id']] = $v;
            }
        }

        return $rst;
    }

    /**
     * ��ȡ��Ŀ�켣�������Ϣ
     * @param $proId
     */
    function getTrackAndTime($proId){
        $trackArr = array();
        $conDao = new model_contract_contract_contract();
        $pArrBaseInfo = $this->get_d($proId);
        $pArrMainInfo = $this->getProView($proId);
        $contractId = isset($pArrBaseInfo['contractId'])? $pArrBaseInfo['contractId'] : '';
        $contract = $conDao->get_d($contractId);
        $contractMoney = $contract['contractMoney'] - $contract['deductMoney'] - $contract['badMoney']; //��ȥ�ۿ���˵ĺ�ͬ���
        $trackArr['baseInfo']['projectId'] = $proId;
        $trackArr['baseInfo']['contractId'] = $contractId;
        $trackArr['baseInfo']['contractMoney'] = $contractMoney;

        //$minOutDateSql = "select min(auditDate) as auditDate from oa_stock_outstock where docStatus = 'YSH' and docType = 'CKSALES' and contractType = 'oa_contract_contract' AND contractId = '{$contractId}'";// ��Ϊ��������auditDate�ǿ����ֶ��޸ĵĲ�׼ȷ,����YSH���ݵ�����������
        $minOutDateSql = "select DATE_FORMAT(min(updateTime),\"%Y-%m-%d\") as auditDate from oa_stock_outstock where docStatus = 'YSH' and docType = 'CKSALES' and contractType = 'oa_contract_contract' AND contractId = '{$contractId}'";
        $minOutDate = $this->_db->getArray($minOutDateSql);

        $minInvoiceDateSql = "select min(invoiceTime) as invoiceTime from oa_finance_invoice where objType in ('KPRK-12') and objId = '{$contractId}';";
        $minInvoiceDate = $this->_db->getArray($minInvoiceDateSql);

        //��ȡ���п�Ʊ��¼
        $invoiceDao = new model_finance_invoice_invoice();
        $invoices = $invoiceDao->getInvoices_d($contractId, "KPRK-12");
        $allInvoiceMoney = 0;

        $invoiceCompleteDate = '';
        foreach ($invoices as $key => $val) {
            if ($val['isRed'] == 1) {
                $val['invoiceMoney'] = -$val['invoiceMoney'];
            }
            $allInvoiceMoney += $val['invoiceMoney'];
            $allInvoiceMoney += $contract['uninvoiceMoney'];
            if ($allInvoiceMoney >= $contractMoney) {
                $invoiceCompleteDate = $val['invoiceTime'];
            }
        }
        // ��ȡ��Ʊ��¼��Ϣ ��end��

        $changeDatesSql = "select ExaDT as changeDate from oa_contract_changlog where objType='contract' and ExaDT <> '' and objId='{$contractId}' group by ExaDT";
        $changeDates = $this->_db->getArray($changeDatesSql);

        $trackArr['dateInfo'] = array();
        $createDate = ($pArrMainInfo['ExaDTOne'] != "0000-00-00")? $pArrMainInfo['ExaDTOne'] : '';// ��Ŀ��������
        $trackArr['dateInfo'][] = array("key"=>"createDate","time"=>$createDate);
        $shipFirstDate = ($minOutDate && $minOutDate[0]['auditDate'] != "0000-00-00")? $minOutDate[0]['auditDate'] : '';// �״η�������
        $trackArr['dateInfo'][] = array("key"=>"shipFirstDate","time"=>$shipFirstDate);
        $shipFinishDate = ($pArrMainInfo['actShipDate'] == "0000-00-00")? '' : $pArrMainInfo['actShipDate'];// ��ɷ�������
        $trackArr['dateInfo'][] = array("key"=>"shipFinishDate","time"=>$shipFinishDate);
        $firstInvoiceDate = ($minInvoiceDate && $minInvoiceDate[0]['invoiceTime'] != '0000-00-00')? $minInvoiceDate[0]['invoiceTime'] : '';// �״ο�Ʊ����
        $trackArr['dateInfo'][] = array("key"=>"firstInvoiceDate","time"=>$firstInvoiceDate);
        $invoiceCompleteDate = ($invoiceCompleteDate != "0000-00-00")? $invoiceCompleteDate : '';
        $trackArr['dateInfo'][] = array("key"=>"invoiceCompleteDate","time"=>$invoiceCompleteDate);
        if($changeDates){
            foreach ($changeDates as $v){
                $trackArr['dateInfo'][] = array("key"=>"changeDate","time"=>$v['changeDate']);
            }
        }

        // ���������� ��start��
        function cmp($a, $b)
        {
            // if (strtotime($a['time']) == $b['time']) return 0;
            if (strtotime($a['time']) - strtotime($b['time']) == 0) {
                return ($a['sort']) < $b['sort'] ? -1 : 1;
            }
            return (strtotime($a['time']) < strtotime($b['time'])) ? -1 : 1;
        }

        usort($trackArr['dateInfo'], "cmp");
        // ���������� ��end��

        return $trackArr;
    }

    /**
     * ��Ʒ��Ŀ���ݰ汾�Զ����Ʋ�����
     */
    function autoSaveConprojectVersion_d(){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');	//�����ڴ�
        $logDao = new model_engineering_baseinfo_esmlog();

        // ��ȡ��ǰʱ��ڵ�
        $today = date("Y-m-d");
        $thisMonth = date('m');
        $thisDay = date('d');

        // ��鵱ǰ�����Ƿ����ڴ�����(ֻ�ڱ��������һ��������)
        $chkMon = $thisMonth * 1;
        $chkDay = $thisDay * 1;
        $isUpdateTime = false;
        $sql = "select id,month,saveDayForPro from oa_esm_baseinfo_log_deadline where month = {$chkMon} and saveDayForPro = {$chkDay};";
        $chkArr= $this->_db->getArray($sql);
        if($chkArr && count($chkArr) > 0){
            $isUpdateTime = true;
        }

        if($isUpdateTime){
            // �ȸ�������
            $conprojectRecordDao = new model_contract_conproject_conprojectRecord();
            $data = $conprojectRecordDao->getConProjectInfo_d();
            $dataLength = count($data);
            $dataKeyLength = $dataLength - 1;

            // �汾���ݻ�ȡ
            $versionInfo = $conprojectRecordDao->getVersionInfo_d();

            $version = $versionInfo['version'];
            $versionInfo['version'] = $versionTmp = $versionInfo['version']."001";
            $isRealSave = false;
            foreach ($data as $k => $v) {
                // �����ж�
                $isRealSave = ($k == $dataKeyLength);

                // ���벢�ҽ����ݲ����
                $conprojectRecordDao->saveSingleRecord_d($v, $versionInfo, $isRealSave);
            }

            if($isRealSave){
                $chkSql = "select count(id) as num from oa_contract_project_record where version = {$versionTmp};";
                $chkArr = $this->_db->getArray($chkSql);
                if($chkArr && $chkArr[0]['num'] == $dataLength){// ����汾���ݸ��³ɹ��������ʱ�汾��¼
                    $updateSql = "update oa_contract_project_record set version = $version where version = {$versionTmp};";
                    $this->_db->query($updateSql);

                    // ��־д��
                    $thisMonth = date('m');
                    $logDao->addLog_d(-1, '��Ʒ��Ŀ���ݰ汾�Զ�����', $dataLength .'|' . $thisMonth);
                }else{// ����汾���ݸ��²�������ɾ����ʱ�汾��¼
                    $delSql = "delete from oa_contract_project_record where version = {$versionTmp};";
                    $this->_db->query($delSql);
                }
            }
        }
    }

    /**
     * ��Ʒ��Ŀ���ݰ汾�Ը���
     */
    function autoUpdateConprojectVersion_d(){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');	//�����ڴ�
        $logDao = new model_engineering_baseinfo_esmlog();

        // ��ȡ��ǰʱ��ڵ�
        $today = date("Y-m-d");
        $thisMonth = date('m');
        $thisDay = date('d');

        // ��鵱ǰ�����Ƿ����ڴ�����(ֻ�ڱ��������һ��������)
        $chkMon = $thisMonth * 1;
        $chkDay = $thisDay * 1;
        $isUpdateTime = false;
        $sql = "select id,month,saveDayForPro from oa_esm_baseinfo_log_deadline where month = {$chkMon} and saveDayForPro = {$chkDay};";
        $chkArr= $this->_db->getArray($sql);
        if($chkArr && count($chkArr) > 0){
            $isUpdateTime = true;
        }

        if($isUpdateTime){
            // ��Ŀ�����Ƿ��н���������¼��
            $chkSql1 = "select id from oa_esm_log where operationType = '���²�Ʒ��Ŀ��������' and DATE_FORMAT(operationTime, '%Y-%m-%d') = '{$today}' limit 1";
            $firstChkArr= $this->_db->getArray($chkSql1);
            $firstChkPass = false;

            if(!empty($firstChkArr) && isset($firstChkArr[0]['id']) && $firstChkArr[0]['id'] > 0){
                $firstChkPass = true;
            }else{// ���ûִ��,����ִ��һ��
                $this->updateSaleProjectVal_d();
                $firstRechkArr= $this->_db->getArray($chkSql1);
                if(!empty($firstRechkArr) && isset($firstRechkArr[0]['id']) && $firstRechkArr[0]['id'] > 0){
                    $firstChkPass = true;
                }
            }

            if($firstChkPass){// ���ǰ������������,��������
                $conprojectRecordDao = new model_contract_conproject_conprojectRecord();
                // ��Ŀ�����Ƿ��н���������¼��
                $chkSql2 = "select id from oa_esm_log where operationType = '��Ʒ��Ŀ���ݰ汾�Զ�����' and DATE_FORMAT(operationTime, '%Y-%m-%d') = '{$today}' limit 1";
                $secondChkArr= $this->_db->getArray($chkSql2);
                $secondChkPass = false;

                if(!empty($secondChkArr) && isset($secondChkArr[0]['id']) && $secondChkArr[0]['id'] > 0){
                    $secondChkPass = true;
                }else{// ���ûִ��,����ִ��һ��
                    $this->autoSaveConprojectVersion_d();
                    $secondChkArr= $this->_db->getArray($chkSql2);
                    if(!empty($secondChkArr) && isset($secondChkArr[0]['id']) && $secondChkArr[0]['id'] > 0){
                        $secondChkPass = true;
                    }
                }

                // �°汾����������ѱ���,��ִ�е�ǰʹ�ð汾�ĸ���
                if($secondChkPass){
                    $nowVersion = $conprojectRecordDao->getVersionInfo_d();
                    $nowVersionCode = $nowVersion['maxVersion'];
                    $storeYearMonth = date('Y-m',strtotime("$today -1 month"));// �浵����Ϊ��ǰ�·ݵ���һ����
                    $conprojectRecordDao->setUsing_d($nowVersionCode, $storeYearMonth);

                    $chkSql = "select count(id) as num from oa_contract_project_record where version = {$nowVersionCode};";
                    $chkArr = $this->_db->getArray($chkSql);
                    $dataCount = 0;

                    if($chkArr && isset($chkArr[0]['num']) && $chkArr[0]['num'] != ''){
                        $dataCount = $chkArr[0]['num'];
                    }

                    // ��־д��
                    $logDao->addLog_d(-1, '��Ʒ��Ŀ���ݰ汾�Զ�����', $dataCount . '|' . $thisMonth);
                }else{
                    // ��־д��(���ݸ��³���)
//                    $logDao->addLog_d(-1, '��Ʒ��Ŀ���ݰ汾�Զ�����(fail)', '-1|' . $thisMonth);
                }

            }else{
                // ��־д��(ǰ������������)
//                $logDao->addLog_d(-1, '��Ʒ��Ŀ���ݰ汾�Զ�����(fail)', '0|' . $thisMonth);
            }
        }
    }
}