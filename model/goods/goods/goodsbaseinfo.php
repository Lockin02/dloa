<?php

/**
 * @author Administrator
 * @Date 2012年3月1日 20:16:27
 * @version 1.0
 * @description:产品基本信息 Model层
 */
class model_goods_goods_goodsbaseinfo extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_goods_base_info";
        $this->sql_map = "goods/goods/goodsbaseinfoSql.php";
        parent::__construct();
    }

    /**
     * 添加对象
     */
    function add_d($object) {
        //处理数据字典字段
        $datadictDao = new model_system_datadict_datadict();
        $object['exeDeptName'] = $datadictDao->getDataNameByCode($object['exeDeptCode']);
        $object['goodsClass'] = $datadictDao->getDataNameByCode($object['goodsTypeCode']);
        $id = parent::add_d($object, true);
        $this->updateObjWithFile($id);
        //更新操作日志
        $logSettringDao = new model_syslog_setting_logsetting ();
        $logSettringDao->addObjLog($this->tbl_name, $id, $object);
        return $id;
    }

    /**
     * 根据主键修改对象
     */
    function edit_d($object) {
        if ($object ['isEncrypt'] !== "on") {
            $object ['isEncrypt'] = null;
        }
        if ($object ['isMature'] !== "on") {
            $object ['isMature'] = null;
        }
        $oldObj = $this->get_d($object ['id']);

        $datadictDao = new model_system_datadict_datadict();
        $object['exeDeptName'] = $datadictDao->getDataNameByCode($object['exeDeptCode']);
        $object['goodsClass'] = $datadictDao->getDataNameByCode($object['goodsTypeCode']);

        //更新操作日志
        $logSettringDao = new model_syslog_setting_logsetting ();
        $logSettringDao->compareModelObj($this->tbl_name, $oldObj, $object);

        //处理数据字典字段
//        $datadictDao = new model_system_datadict_datadict();
//        $object['exeDeptName'] = $datadictDao->getDataNameByCode($object['exeDeptCode']);
        return parent::edit_d($object);
    }

    /**
     *
     * 检查产品关联信息
     */
    function checkRelateInfo($id) {
        include_once("model/goods/goods/goodsRelationTableArr.php");
        $this->tbl_name = "oa_goods_base_info";
        $productinfo = $this->get_d($id);
        $msg = "";
        foreach ($goodsRelationTableArr as $objArr) {
            if (is_array($objArr)) {
                foreach ($objArr as $key => $value) {
                    $this->tbl_name = $key;
                    $num = $this->findCount(array($value ['id'] => $id));
                    if ($num > 0) {
                        $msg .= $num . "条【" . $value [0] . "】关联.</br>";
                    }
                }
            }
        }
        if (empty ($msg)) {
            $msg = "产品【" . $productinfo ['goodsName'] . "】没有被任何业务对象关联.";
        } else {
            $msg = "产品【" . $productinfo ['goodsName'] . "】已经被" . $msg . "</br>";
        }
        return $msg;
    }

    /**
     *
     * 更新产品相关的业务对象信息
     * @param  $productinfo
     * @param  $relationArr
     */
    function updateRelationsById($goodsbaseinfo, $relationArr) {
        try {
            $this->start_d();
            $this->edit_d($goodsbaseinfo);
            include_once("model/goods/goods/goodsRelationTableArr.php");
            foreach ($goodsRelationTableArr as $objArr) {
                if (is_array($objArr)) {
                    foreach ($objArr as $key => $value) {
                        if (in_array($key, $relationArr)) {
                            $sql = "update " . $key . " set ";
                            foreach ($value as $k => $v) {
                                if (!empty ($v) && !empty ($goodsbaseinfo [$k])) {
                                    $sql .= $v . "='" . $goodsbaseinfo [$k] . "',";
                                }
                            }
                            $sql = substr($sql, 0, -1);
                            $sql .= " where " . $value ['id'] . " =" . $goodsbaseinfo ['id'];
                            if (!empty ($goodsbaseinfo ['updateStartDate'])) {
                                $sql .= " and createTime >= cast('" . $goodsbaseinfo ['updateStartDate'] . "' as datetime)";
                            }
                            if (!empty ($goodsbaseinfo ['updateEndDate'])) {
                                $sql .= " and createTime <= cast('" . $goodsbaseinfo ['updateEndDate'] . "' as datetime)";
                            }
                            if (isset ($value ['condition'])) { //额外条件
                                $sql .= $value ['condition'];
                            }
                            //echo $sql;
                            $this->query($sql);
                        }
                    }
                } else {
                    throw new Exception ("更新失败,配置文件存在问题.");
                }
            }
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            //echo $e->message;
            throw $e;
        }
    }

    /**
     * 获取产品的信息,返回hash数组
     * @param $idArr
     * @return array
     */
    function getGoodsHashInfo_d($idArr) {
        // 产品类型处理
        $goodsTypeDao = new model_goods_goods_goodstype();
        $typeHash = $goodsTypeDao->getTopType_d();

        $hashArr = array();
        $this->searchArr = array(
            'idArr' => implode(',', $idArr)
        );
        $data = $this->list_d();
        foreach ($data as $v) {
            $v['proTypeId'] = $typeHash[$v['goodsTypeId']]['proTypeId'];
            $v['proType'] = $typeHash[$v['goodsTypeId']]['proType'];
            $v['proExeDeptId'] = $v['auditDeptCode'];
            $v['proExeDeptName'] = $v['auditDeptName'];
            $hashArr[$v['id']] = $v;
        }
        return $hashArr;
    }

    /**
     * 获取产品的信息
     * @param $id
     * @return array
     */
    function getProType_d($id) {
        // 获取执行部门信息
        $data = $this->getGoodsHashInfo_d(array($id));
        if ($data) {
            return $data[$id];
        } else {
            return array();
        }
    }
}