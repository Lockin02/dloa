<?php

/**
 * @author Administrator
 * @Date 2012-07-31 14:36:06
 * @version 1.0
 * @description:商机产品清单 Model层
 */
class model_projectmanagent_chance_product extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_sale_chance_product";
        $this->sql_map = "projectmanagent/chance/productSql.php";
        parent:: __construct();
    }

    /**
     * @param $id
     * @return array|int
     */
    function resolve_d($id) {
        $obj = $this->find(array('id' => $id), null, 'id,deploy');
        $goodsCacheDao = new model_goods_goods_goodscache();
        $newArr = $goodsCacheDao->changeToProduct_d($obj['deploy']);
        if (is_array($newArr) && count($newArr)) {
            return $newArr;
        } else {
            return 0;
        }
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
                $data[$k]['proTypeId'] = $goodsInfo[$v['conProductId']]['proTypeId'];
                $data[$k]['proType'] = $goodsInfo[$v['conProductId']]['proType'];
                $data[$k]['newExeDeptCode'] = $goodsInfo[$v['conProductId']]['exeDeptCode'];
                $data[$k]['newExeDeptName'] = $goodsInfo[$v['conProductId']]['exeDeptName'];
            }
        }
        return $data;
    }
}