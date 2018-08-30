<?php

/**
 * @author huangzf
 * @Date 2012年3月11日 15:15:40
 * @version 1.0
 * @description:产品分类信息 Model层
 */
class model_goods_goods_goodstype extends model_treeNode
{

    function __construct() {
        $this->tbl_name = "oa_goods_type";
        $this->sql_map = "goods/goods/goodstypeSql.php";
        parent::__construct();
    }

    //判断是否存在下次产品
    function isHaveSon_d($id) {
        return $this->find(array('parentId' => $id)) ? 1 : 0;
    }

    /**
     * 根据类型id获取最上级的类型信息
     */
    function getTopType_d() {
        // 获取上级类型
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