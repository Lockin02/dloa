<?php

/**
 * �����۾�
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
     * ��д��������
     * @param $object
     * @return bool|int|mixed
     */
    function add_d($object)
    {
        // �ʲ���̯
        $assetShare = $object['assetShare'];
        unset($object['assetShare']);

        // ʵ����Model
        $assetShareDao = new model_bi_deptFee_assetShare();

        try {
            $this->start_d();

            // ������չ��Ϣ
            $object['information'] = $this->buildInfo_d($assetShare);
            $object['thisTime'] = strtotime($object['thisYear'] . '-' . $object['thisMonth'] . - '1');

            // ����
            $id = parent::add_d($object);

            // ����ӳ���ȡ
            $deptMappingDao = new model_bi_deptFee_deptMapping();
            $deptMapping = $deptMappingDao->getMapping_d('�۾ɼ���̯');

            // ѭ������һ������ֶ�
            foreach ($assetShare as $k => $v) {
                $assetShare[$k]['deprId'] = $id;

                if (isset($deptMapping[$v['deptName']])) {
                    $assetShare[$k] = array_merge($assetShare[$k], $deptMapping[$v['deptName']]);
                } else {
                    $assetShare[$k] = array_merge($assetShare[$k],
                        array('business' => '', 'secondDept' => '', 'thirdDept' => '', 'fourthDept' => ''));
                }
            }

            // ���Ӵӱ���Ϣ
            $assetShareDao->saveDelBatch($assetShare);

            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��д�༭����
     * @param $object
     * @return bool|mixed
     */
    function edit_d($object)
    {
        // �ʲ���̯
        $assetShare = $object['assetShare'];
        unset($object['assetShare']);

        // ʵ����Model
        $assetShareDao = new model_bi_deptFee_assetShare();

        try {
            $this->start_d();

            // ������չ��Ϣ
            $object['information'] = $this->buildInfo_d($assetShare);
            $object['thisTime'] = strtotime($object['thisYear'] . '-' . $object['thisMonth'] . - '1');

            // ����
            parent::edit_d($object);

            // ɾ��ԭ�ӱ��¼
            $assetShareDao->delete(array('deprId' => $object['id']));

            // ����ӳ���ȡ
            $deptMappingDao = new model_bi_deptFee_deptMapping();
            $deptMapping = $deptMappingDao->getMapping_d('�۾ɼ���̯');

            // ѭ������һ������ֶ�
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
     * ������չ��Ϣ
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