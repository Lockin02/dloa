<?php

/**
 * @author huangzf
 * @Date 2012��3��11�� 15:15:40
 * @version 1.0
 * @description:��Ʒ������Ϣ Model��
 */
class model_goods_goods_goodstype extends model_treeNode
{

    function __construct() {
        $this->tbl_name = "oa_goods_type";
        $this->sql_map = "goods/goods/goodstypeSql.php";
        parent::__construct();
    }

    //�ж��Ƿ�����´β�Ʒ
    function isHaveSon_d($id) {
        return $this->find(array('parentId' => $id)) ? 1 : 0;
    }

    /**
     * ��������id��ȡ���ϼ���������Ϣ
     */
    function getTopType_d() {
        // ��ȡ�ϼ�����
        $topArr = $this->findAll(array('parentId' => -1), null, 'id,goodsType');
        $topHash = array();
        foreach ($topArr as $k => $v) {
            $topHash[$v['id']] = array(
                'proTypeId' => $v['id'],
                'proType' => $v['goodsType']
            );
        }
        unset($topArr);

        $data = $this->findAll(null, null, 'id,goodsType,parentName,parentId');
        foreach ($data as $k => $v) {
            if ($v['parentId'] != -1) {
                $topHash[$v['id']] = $topHash[$v['parentId']];
            }
        }
        return $topHash;
    }
}