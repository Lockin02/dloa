<?php

/**
 * @author Administrator
 * @Date 2012年3月8日 14:13:30
 * @version 1.0
 * @description:合同 产品清单 Model层
 */
class model_contract_contract_product extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_contract_product";
        $this->sql_map = "contract/contract/productSql.php";
        parent::__construct();
    }

    //解析json字符串
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

    //解析json字符串
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
     * 根据合同ID 获取从表数据
     */
    function getDetail_d($contractId) {
        $this->searchArr = array(
            'contractId' => $contractId, 'isDel' => 0, 'isTemp' => 0
        );
        $this->asc = false;
        return $this->list_d();
    }

    /**
     * 根据合同ID 获取产品线详细信息
     * @param $contractId
     * @return array
     */
    function getProductLineDetails_d($contractId) {
        // 产品数据提取
        $data = $this->getDetail_d($contractId);

        $contractMoney = 0;
        $rst = array();

        foreach ($data as $v) {
            // 产线数据合并
            $rst[$v['proTypeId']][$v['newProLineCode']] = isset($rst[$v['proTypeId']][$v['newProLineCode']]) ?
                bcadd($rst[$v['proTypeId']][$v['newProLineCode']], $v['money'], 2) : $v['money'];

            // 合同金额计算
            $contractMoney = bcadd($contractMoney, $v['money'], 2);
        }

        $start = $sumRate = 0; // 开始位和进度综合
        $rowLength = count($rst); // 产线长度
        foreach ($rst as $k => $v) {
            $start++; // 进位
            $detailLength = count($v); // 明细长度
            $detailStart = 0; // 明细位标

            foreach ($v as $ki => $vi) {
                $detailStart++; // 进位
                $thisRate = $this->getProportion($vi, $contractMoney, 2); // 计算本次进度

                // 产线数组格式重构
                $rst[$k][$ki] = array(
                    'productLine' => $ki,
                    'productLineMoney' => $vi,
                    'productLineRate' => $start == $rowLength && $detailLength == $detailStart ? bcsub(100, $sumRate, 2) : $thisRate,
                    'proTypeId' => $k
                );
                $sumRate = bcadd($sumRate, $thisRate, 2); // 当前总进度计算
            }
        }

        return $rst;
    }

    //计算合同占比
    function getProportion($proMoney, $conMoney, $scale = 10)
    {
        $exGrossTemp = bcdiv($proMoney, $conMoney, 10);
        $exGross = round(bcmul($exGrossTemp, '100', 9), 10);
        return $exGross;
    }

    /**
     * 根据合同ID 获取从表数据---获取变更临时记录用
     */
    function getDetailTemp_d($contractId) {
        $this->searchArr['contractId'] = $contractId;
        $this->searchArr['isDel'] = 0;
//		$this->searchArr['isTemp'] = 0;
        $this->asc = false;
        return $this->list_d();
    }

    /**
     * 根据合同ID 获取从表数据
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
     * 获取带有变更标识的合同产品信息
     * 1.变更审批中得查看获取数据 $isTemp=1
     * 2.变更审批完成后的查看获取数据 $isTemp=0
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
     * 获取合同从表条件
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
     * 变更数据处理
     */
    function dealArr_d($object) {
        //循环处理
        foreach ($object as $key => $val) {
            //如果是编辑处理
            if ($val['changeTips'] == '1' && $val['isTemp'] == '1' && isset($val['originalId'])) {
                //获取原产品清单
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

    //合同从表产品保存
    function ProSaveDelBatch($objs) {
        if (!is_array($objs)) {
            throw new Exception ("传入对象不是数组！");
        }
        $returnObjs = array();
        foreach ($objs as $key => $val) {
            $val = $this->addCreateInfo($val);
            $isDelTag = isset($val ['isDelTag']) ? $val ['isDelTag'] : NULL;
            if (empty ($val ['id']) && $isDelTag == 1) {

            } else if (empty ($val ['id'])) {
                $id = $this->add_d($val);
                $val ['id'] = $id;
                $val['isAddAction'] = true;//标识是新增的
                array_push($returnObjs, $val);
            } else if ($isDelTag == 1) {
                $this->deletes($val ['id']);
                $pid = $val ['id'];
                //同时删除物料
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
     * 根据合同id 获证整理添加 产线与类型的 产品数据
     */
    function getCostInfoProBycId($cid) {
        $this->searchArr['contractId'] = $cid;
        $this->searchArr['isDel'] = 0;

        $rows = $this->list_d();

        if ($rows) {
            //查找产品类型
            foreach ($rows as $key => $val) {
                $sql = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id = '" . $val['conProductId'] . "')";
                $goodsIdArr = $this->_db->getArray($sql);
                if ($goodsIdArr[0]['parentId'] != "-1") {
                    //判断如果不为根节点进行第二次查找
                    $sqlB = "select a.id as pid from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                        "where id = '" . $goodsIdArr[0]['id'] . "') b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                    $goodsIdArrB = $this->_db->getArray($sqlB);
                    $goodsTypeId = $goodsIdArrB[0]['pid'];
                } else {
                    $goodsTypeId = $goodsIdArr[0]['id'];
                }
                $rows[$key]['goodsTypeId'] = $goodsTypeId;
                //产品线
                $sqlf = "select exeDeptName,exeDeptCode from oa_goods_base_info where id = '" . $val['conProductId'] . "'";
                $exeDeptNameArr = $this->_db->getArray($sqlf);
                $rows[$key]['exeDeptName'] = $exeDeptNameArr[0]['exeDeptName'];
            }
            //重排KEY值
            $rows = array_values($rows);

            //变更数据处理
            $rows = $this->dealArr_d($rows);
        }

        return $rows;
    }

    /**
     * 获取产品信息
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

            // 初始化产品查询
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
     * 添加对象 (重写)
     */
    function add_d($object, $isAddInfo = false)
    {
        //处理数据字典字段
        $datadictDao = new model_system_datadict_datadict();
        if(isset($object['exeDeptId']) && $object['exeDeptId'] != ''){// 根据选中的执行区域ID重新匹配中文名 PMS2817
            $exeDeptName = $datadictDao->getDataNameByCode($object['exeDeptId']);
            if($exeDeptName && !empty($exeDeptName)){
                $object['exeDeptName'] = $exeDeptName;
            }
        }

        return parent::add_d($object, $isAddInfo);
    }

    /**
     * 根据主键修改对象 (重写)
     */
    function edit_d($object, $isEditInfo = false)
    {
        //处理数据字典字段
        $datadictDao = new model_system_datadict_datadict();
        if(isset($object['exeDeptId']) && $object['exeDeptId'] != ''){// 根据选中的执行区域ID重新匹配中文名 PMS2817
            $exeDeptName = $datadictDao->getDataNameByCode($object['exeDeptId']);
            if($exeDeptName && !empty($exeDeptName)){
                $object['exeDeptName'] = $exeDeptName;
            }
        }
        
        return parent::edit_d($object, $isEditInfo);
    }
}