<?php

/**
 * 部门折旧
 * Class model_bi_deptFee_assetDepr
 */
class model_bi_deptFee_assetDepr extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_bi_asset_depreciation";
        $this->sql_map = "bi/deptFee/assetDeprSql.php";
        parent::__construct();
    }

    /**
     * 重写新增方法
     * @param $object
     * @return bool|int|mixed
     */
    function add_d($object)
    {
        // 资产分摊
        $assetShare = $object['assetShare'];
        unset($object['assetShare']);

        // 实例化Model
        $assetShareDao = new model_bi_deptFee_assetShare();

        try {
            $this->start_d();

            // 构建扩展信息
            $object['information'] = $this->buildInfo_d($assetShare);
            $object['thisTime'] = strtotime($object['thisYear'] . '-' . $object['thisMonth'] . - '1');

            // 新增
            $id = parent::add_d($object);

            // 部门映射获取
            $deptMappingDao = new model_bi_deptFee_deptMapping();
            $deptMapping = $deptMappingDao->getMapping_d('折旧及分摊');

            // 循环加入一下相关字段
            foreach ($assetShare as $k => $v) {
                $assetShare[$k]['deprId'] = $id;

                if (isset($deptMapping[$v['deptName']])) {
                    $assetShare[$k] = array_merge($assetShare[$k], $deptMapping[$v['deptName']]);
                } else {
                    $assetShare[$k] = array_merge($assetShare[$k],
                        array('business' => '', 'secondDept' => '', 'thirdDept' => '', 'fourthDept' => ''));
                }
            }

            // 增加从表信息
            $assetShareDao->saveDelBatch($assetShare);

            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 重写编辑方法
     * @param $object
     * @return bool|mixed
     */
    function edit_d($object)
    {
        // 资产分摊
        $assetShare = $object['assetShare'];
        unset($object['assetShare']);

        // 实例化Model
        $assetShareDao = new model_bi_deptFee_assetShare();

        try {
            $this->start_d();

            // 构建扩展信息
            $object['information'] = $this->buildInfo_d($assetShare);
            $object['thisTime'] = strtotime($object['thisYear'] . '-' . $object['thisMonth'] . - '1');

            // 新增
            parent::edit_d($object);

            // 删除原从表记录
            $assetShareDao->delete(array('deprId' => $object['id']));

            // 部门映射获取
            $deptMappingDao = new model_bi_deptFee_deptMapping();
            $deptMapping = $deptMappingDao->getMapping_d('折旧及分摊');

            // 循环加入一下相关字段
            foreach ($assetShare as $k => $v) {
                $assetShare[$k]['deprId'] = $object['id'];

                if (isset($deptMapping[$v['deptName']])) {
                    $assetShare[$k] = array_merge($assetShare[$k], $deptMapping[$v['deptName']]);
                } else {
                    $assetShare[$k] = array_merge($assetShare[$k],
                        array('business' => '', 'secondDept' => '', 'thirdDept' => '', 'fourthDept' => ''));
                }
            }
            $assetShareDao->saveDelBatch($assetShare);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 构建扩展信息
     * @param $assetShare
     * @return string
     */
    function buildInfo_d($assetShare)
    {
        $informationArr = array();
        foreach ($assetShare as $v) {
            $informationArr[] = $v['deptName'] . ":" . number_format($v['feeIn'], 2);
        }
        return implode(',', $informationArr);
    }
}