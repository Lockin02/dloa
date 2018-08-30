<?php

/**
 * @author Administrator
 * @Date 2012��3��8�� 14:13:30
 * @version 1.0
 * @description:��ͬ ��Ʒ�嵥 Model��
 */
class model_contract_contract_product extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_contract_product";
        $this->sql_map = "contract/contract/productSql.php";
        parent::__construct();
    }

    //����json�ַ���
    function resolve_d($id) {
        $obj = $this->find(array('id' => $id), null, 'id,deploy');

//		print_r($obj);
        $goodsCacheDao = new model_goods_goods_goodscache();
        $newArr = $goodsCacheDao->changeToProduct_d($obj['deploy']);
        if (is_array($newArr) && count($newArr)) {
            return $newArr;
        } else {
            return 0;
        }
    }

    //����json�ַ���
    function newResolve_d($deploy) {
        $goodsCacheDao = new model_goods_goods_goodscache();
        $newArr = $goodsCacheDao->changeToProduct_d($deploy);
        if (is_array($newArr) && count($newArr)) {
            return $newArr;
        } else {
            return 0;
        }
    }

    /**
     * ���ݺ�ͬID ��ȡ�ӱ�����
     */
    function getDetail_d($contractId) {
        $this->searchArr = array(
            'contractId' => $contractId, 'isDel' => 0, 'isTemp' => 0
        );
        $this->asc = false;
        return $this->list_d();
    }

    /**
     * ���ݺ�ͬID ��ȡ��Ʒ����ϸ��Ϣ
     * @param $contractId
     * @return array
     */
    function getProductLineDetails_d($contractId) {
        // ��Ʒ������ȡ
        $data = $this->getDetail_d($contractId);

        $contractMoney = 0;
        $rst = array();

        foreach ($data as $v) {
            // �������ݺϲ�
            $rst[$v['proTypeId']][$v['newProLineCode']] = isset($rst[$v['proTypeId']][$v['newProLineCode']]) ?
                bcadd($rst[$v['proTypeId']][$v['newProLineCode']], $v['money'], 2) : $v['money'];

            // ��ͬ������
            $contractMoney = bcadd($contractMoney, $v['money'], 2);
        }

        $start = $sumRate = 0; // ��ʼλ�ͽ����ۺ�
        $rowLength = count($rst); // ���߳���
        foreach ($rst as $k => $v) {
            $start++; // ��λ
            $detailLength = count($v); // ��ϸ����
            $detailStart = 0; // ��ϸλ��

            foreach ($v as $ki => $vi) {
                $detailStart++; // ��λ
                $thisRate = $this->getProportion($vi, $contractMoney, 2); // ���㱾�ν���

                // ���������ʽ�ع�
                $rst[$k][$ki] = array(
                    'productLine' => $ki,
                    'productLineMoney' => $vi,
                    'productLineRate' => $start == $rowLength && $detailLength == $detailStart ? bcsub(100, $sumRate, 2) : $thisRate,
                    'proTypeId' => $k
                );
                $sumRate = bcadd($sumRate, $thisRate, 2); // ��ǰ�ܽ��ȼ���
            }
        }

        return $rst;
    }

    //�����ͬռ��
    function getProportion($proMoney, $conMoney, $scale = 10)
    {
        $exGrossTemp = bcdiv($proMoney, $conMoney, 10);
        $exGross = round(bcmul($exGrossTemp, '100', 9), 10);
        return $exGross;
    }

    /**
     * ���ݺ�ͬID ��ȡ�ӱ�����---��ȡ�����ʱ��¼��
     */
    function getDetailTemp_d($contractId) {
        $this->searchArr['contractId'] = $contractId;
        $this->searchArr['isDel'] = 0;
//		$this->searchArr['isTemp'] = 0;
        $this->asc = false;
        return $this->list_d();
    }

    /**
     * ���ݺ�ͬID ��ȡ�ӱ�����
     */
    function getDetailWithTemp_d($contractId, $isSubAppChange) {
        if ($isSubAppChange == '1') {
            $this->searchArr ['contractId'] = $contractId;
//		$this->searchArr ['isDel'] = 0;
//		$this->searchArr ['isTemp'] = 0;
        } else {
            $this->searchArr ['contractId'] = $contractId;
//		$this->searchArr ['isDel'] = 0;
            $this->searchArr ['isTemp'] = 0;
        }
        $this->asc = false;
        return $this->list_d();
    }
    function getPro_d($cid,$line) {
        $this->searchArr['contractId'] = $cid;
        $this->searchArr['newProLineCode'] = $line;
		$this->searchArr['isTemp'] = 0;
		$this->searchArr['isDel'] = 0;
        $this->asc = false;
        return $this->list_d();
    }

    /**
     * ��ȡ���б����ʶ�ĺ�ͬ��Ʒ��Ϣ
     * 1.��������еò鿴��ȡ���� $isTemp=1
     * 2.���������ɺ�Ĳ鿴��ȡ���� $isTemp=0
     */
    function getChangeProductList_d($contractId, $isTemp) {
        $condition = $this->getChangeCondition_d($contractId, $isTemp);
        $sql = "select c.id ,c.conProductName,c.changeTips ," .
            "c.conProductId ,c.conProductCode ,c.conProductDes ," .
            "c.contractId ,c.contractCode ,c.contractName ,c.version ," .
            "c.number ,c.remark ,c.price ,c.unitName ,c.money ," .
            "c.warrantyPeriod ,c.license ,c.deploy ,c.backNum  ," .
            "c.issuedShipNum ,c.executedNum ,c.onWayNum ,c.purchasedNum ," .
            "c.issuedPurNum ,c.issuedProNum ,c.uniqueCode ,c.productLine ," .
            "c.productLineName ,c.isTemp ,c.originalId ,c.isDel ,c.isCon ," .
            "c.isConfig ,c.isNeedDelivery  from oa_contract_product c $condition";
        return $this->findSql($sql);
    }

    /**
     * ��ȡ��ͬ�ӱ�����
     */
    function getChangeCondition_d($contractId, $isTemp) {
        $ExaStatus = AUDITED;
        $isTemp = ($isTemp == 1 ? 1 : 0);
        if ($isTemp == 1) {
            $ExaStatus = '';
        }
        $condition = "where c.isTemp=$isTemp" .
            " and c.isDel=0 and c.contractId=$contractId or (" .
            " c.id in(select distinct(d.detailId) from oa_contract_changedetail d" .
            " where d.parentId =(select max(id) from oa_contract_changlog l where l.ExaStatus='$ExaStatus'" .
            " and objId=$contractId)))";
        return $condition;
    }

    /**
     * ������ݴ���
     */
    function dealArr_d($object) {
        //ѭ������
        foreach ($object as $key => $val) {
            //����Ǳ༭����
            if ($val['changeTips'] == '1' && $val['isTemp'] == '1' && isset($val['originalId'])) {
                //��ȡԭ��Ʒ�嵥
                $orgArr = $this->find(array('id' => $val['originalId']));
                if (!empty($orgArr['deploy'])) {
                    $object[$key]['beforeDeploy'] = $orgArr['deploy'];
                }
            } else if ($val['changeTips'] == '1' && empty($val['isTemp'])) {
                //$productArr = $this->getLastTempProduct($val['id']);
                $changelogDao = new model_common_changeLog();
                $details = $changelogDao->getLastDetails("contract", $val['contractId'], "contract", "product", $val['id']);
                $deploy = $object[$key]['beforeDeploy'];
                foreach ($details as $v) {
                    if ($v['changeField'] == "deploy") {
                        $deploy = $v['oldValue'];
                        break;
                    }
                }
                //echo $deploy;
                if (!empty($deploy)) {
                    $object[$key]['beforeDeploy'] = $deploy;
                }
            }
        }
//		print_r($object);
        return $object;
    }

    //��ͬ�ӱ��Ʒ����
    function ProSaveDelBatch($objs) {
        if (!is_array($objs)) {
            throw new Exception ("������������飡");
        }
        $returnObjs = array();
        foreach ($objs as $key => $val) {
            $val = $this->addCreateInfo($val);
            $isDelTag = isset($val ['isDelTag']) ? $val ['isDelTag'] : NULL;
            if (empty ($val ['id']) && $isDelTag == 1) {

            } else if (empty ($val ['id'])) {
                $id = $this->add_d($val);
                $val ['id'] = $id;
                $val['isAddAction'] = true;//��ʶ��������
                array_push($returnObjs, $val);
            } else if ($isDelTag == 1) {
                $this->deletes($val ['id']);
                $pid = $val ['id'];
                //ͬʱɾ������
                $dsql = "delete from oa_contract_equ where conProductId = '$pid'";
                $this->_db->query($dsql);
            } else {
                $this->edit_d($val);
                array_push($returnObjs, $val);
            }
        }
        return $returnObjs;
    }


    /**
     * ���ݺ�ͬid ��֤������� ���������͵� ��Ʒ����
     */
    function getCostInfoProBycId($cid) {
        $this->searchArr['contractId'] = $cid;
        $this->searchArr['isDel'] = 0;

        $rows = $this->list_d();

        if ($rows) {
            //���Ҳ�Ʒ����
            foreach ($rows as $key => $val) {
                $sql = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id = '" . $val['conProductId'] . "')";
                $goodsIdArr = $this->_db->getArray($sql);
                if ($goodsIdArr[0]['parentId'] != "-1") {
                    //�ж������Ϊ���ڵ���еڶ��β���
                    $sqlB = "select a.id as pid from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                        "where id = '" . $goodsIdArr[0]['id'] . "') b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                    $goodsIdArrB = $this->_db->getArray($sqlB);
                    $goodsTypeId = $goodsIdArrB[0]['pid'];
                } else {
                    $goodsTypeId = $goodsIdArr[0]['id'];
                }
                $rows[$key]['goodsTypeId'] = $goodsTypeId;
                //��Ʒ��
                $sqlf = "select exeDeptName,exeDeptCode from oa_goods_base_info where id = '" . $val['conProductId'] . "'";
                $exeDeptNameArr = $this->_db->getArray($sqlf);
                $rows[$key]['exeDeptName'] = $exeDeptNameArr[0]['exeDeptName'];
            }
            //����KEYֵ
            $rows = array_values($rows);

            //������ݴ���
            $rows = $this->dealArr_d($rows);
        }

        return $rows;
    }

    /**
     * ��ȡ��Ʒ��Ϣ
     * @param $data
     * @return mixed
     */
    function dealProduct_d($data) {
        if ($data) {
            $productIdArr = array();
            foreach ($data as $k => $v) {
                if (!in_array($v['conProductId'], $productIdArr)) {
                    $productIdArr[] = $v['conProductId'];
                }
            }

            // ��ʼ����Ʒ��ѯ
            $goodsDao = new model_goods_goods_goodsbaseinfo();
            $goodsInfo = $goodsDao->getGoodsHashInfo_d($productIdArr);

            foreach ($data as $k => $v) {
                $data[$k]['proExeDeptName'] = $goodsInfo[$v['conProductId']]['auditDeptName'];
                $data[$k]['proExeDeptId'] = $goodsInfo[$v['conProductId']]['auditDeptCode'];
                $data[$k]['newExeDeptCode'] = $goodsInfo[$v['conProductId']]['exeDeptCode'];
                $data[$k]['newExeDeptName'] = $goodsInfo[$v['conProductId']]['exeDeptName'];
            }
        }
        return $data;
    }

    /**
     * ��Ӷ��� (��д)
     */
    function add_d($object, $isAddInfo = false)
    {
        //���������ֵ��ֶ�
        $datadictDao = new model_system_datadict_datadict();
        if(isset($object['exeDeptId']) && $object['exeDeptId'] != ''){// ����ѡ�е�ִ������ID����ƥ�������� PMS2817
            $exeDeptName = $datadictDao->getDataNameByCode($object['exeDeptId']);
            if($exeDeptName && !empty($exeDeptName)){
                $object['exeDeptName'] = $exeDeptName;
            }
        }

        return parent::add_d($object, $isAddInfo);
    }

    /**
     * ���������޸Ķ��� (��д)
     */
    function edit_d($object, $isEditInfo = false)
    {
        //���������ֵ��ֶ�
        $datadictDao = new model_system_datadict_datadict();
        if(isset($object['exeDeptId']) && $object['exeDeptId'] != ''){// ����ѡ�е�ִ������ID����ƥ�������� PMS2817
            $exeDeptName = $datadictDao->getDataNameByCode($object['exeDeptId']);
            if($exeDeptName && !empty($exeDeptName)){
                $object['exeDeptName'] = $exeDeptName;
            }
        }
        
        return parent::edit_d($object, $isEditInfo);
    }
}