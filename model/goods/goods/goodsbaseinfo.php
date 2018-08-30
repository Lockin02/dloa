<?php

/**
 * @author Administrator
 * @Date 2012��3��1�� 20:16:27
 * @version 1.0
 * @description:��Ʒ������Ϣ Model��
 */
class model_goods_goods_goodsbaseinfo extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_goods_base_info";
        $this->sql_map = "goods/goods/goodsbaseinfoSql.php";
        parent::__construct();
    }

    /**
     * ��Ӷ���
     */
    function add_d($object) {
        //���������ֵ��ֶ�
        $datadictDao = new model_system_datadict_datadict();
        $object['exeDeptName'] = $datadictDao->getDataNameByCode($object['exeDeptCode']);
        $object['goodsClass'] = $datadictDao->getDataNameByCode($object['goodsTypeCode']);
        $id = parent::add_d($object, true);
        $this->updateObjWithFile($id);
        //���²�����־
        $logSettringDao = new model_syslog_setting_logsetting ();
        $logSettringDao->addObjLog($this->tbl_name, $id, $object);
        return $id;
    }

    /**
     * ���������޸Ķ���
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

        //���²�����־
        $logSettringDao = new model_syslog_setting_logsetting ();
        $logSettringDao->compareModelObj($this->tbl_name, $oldObj, $object);

        //���������ֵ��ֶ�
//        $datadictDao = new model_system_datadict_datadict();
//        $object['exeDeptName'] = $datadictDao->getDataNameByCode($object['exeDeptCode']);
        return parent::edit_d($object);
    }

    /**
     *
     * ����Ʒ������Ϣ
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
                        $msg .= $num . "����" . $value [0] . "������.</br>";
                    }
                }
            }
        }
        if (empty ($msg)) {
            $msg = "��Ʒ��" . $productinfo ['goodsName'] . "��û�б��κ�ҵ��������.";
        } else {
            $msg = "��Ʒ��" . $productinfo ['goodsName'] . "���Ѿ���" . $msg . "</br>";
        }
        return $msg;
    }

    /**
     *
     * ���²�Ʒ��ص�ҵ�������Ϣ
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
                            if (isset ($value ['condition'])) { //��������
                                $sql .= $value ['condition'];
                            }
                            //echo $sql;
                            $this->query($sql);
                        }
                    }
                } else {
                    throw new Exception ("����ʧ��,�����ļ���������.");
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
     * ��ȡ��Ʒ����Ϣ,����hash����
     * @param $idArr
     * @return array
     */
    function getGoodsHashInfo_d($idArr) {
        // ��Ʒ���ʹ���
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
     * ��ȡ��Ʒ����Ϣ
     * @param $id
     * @return array
     */
    function getProType_d($id) {
        // ��ȡִ�в�����Ϣ
        $data = $this->getGoodsHashInfo_d(array($id));
        if ($data) {
            return $data[$id];
        } else {
            return array();
        }
    }
}