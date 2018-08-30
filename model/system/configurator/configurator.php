<?php
/**
 * @author HaoJin
 * @Date 2017��4��20�� ������ 10:40:10
 * @version 1.0
 * @description:���ö� Model��
 */
class model_system_configurator_configurator extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_system_configurator";
        $this->sql_map = "system/configurator/configuratorSql.php";
        parent:: __construct();
    }

    /**
     * ���ݴ���Ĳ���ID����Ƿ��ڱ�����̯��������
     *
     * @param $deptId
     * @return array|bool
     */
    function checkDeptInConfig($deptId){
        $this->searchArr['deptIdIn'] = $deptId;
        $this->searchArr['configuratorCode'] = 'BXFT_CONFIG1';
        $data = $this->pageBySqlId('list_items');
        return $data;
    }

    /**
     * �������߱�������
     *
     * @param $expenseTypeId
     * @param $costDepartIds
     * @param $costTypeIds
     * @return array|bool
     */
    function checkBxLimitConfig($expenseTypeId,$costDepartIds,$costTypeIds,$costTypeNames){
        $costDepartIdsArr = explode(",",$costDepartIds);
        $costTypeIdsArr = explode(",",$costTypeIds);
        $costDepartIdsSql = $costTypeIdsSql = '';

        if(is_array($costDepartIdsArr) && !empty($costDepartIdsArr)){
            $costDepartIdsSql = " and (";
            foreach ($costDepartIdsArr as $k => $deptId){
                $costDepartIdsSql .= ($k == 0)? " FIND_IN_SET({$deptId},i.belongDeptIds)" : " or FIND_IN_SET({$deptId},i.belongDeptIds)";
            }
            $costDepartIdsSql .= ")";
        }

        if(is_array($costTypeIdsArr) && !empty($costTypeIdsArr)){
            $costTypeIdsSql = " and (";
            foreach ($costTypeIdsArr as $k => $costTypeId){
                $costTypeIdsSql .= ($k == 0)? " FIND_IN_SET({$costTypeId},i.config_itemSub2)" : " or FIND_IN_SET({$costTypeId},i.config_itemSub2)";
            }
            $costTypeIdsSql .= ")";
        }

        $sql = "select i.id,i.config_item2 from oa_system_configurator_item i left join oa_system_configurator c on c.id = i.mainId where c.configuratorCode = 'FWBXXZ' AND i.config_itemSub1 = {$expenseTypeId} {$costDepartIdsSql} {$costTypeIdsSql};";

        $data = $this->_db->getArray($sql);
        $limitTypeNames = '';
        if($data){
            $costTypeNamesArr = explode(",",$costTypeNames);
            $costTypeNamesArr = util_jsonUtil::iconvUTF2GBArr($costTypeNamesArr);
            $costTypeNamesChkArr = explode(",",$data[0]['config_item2']);

            if(is_array($costTypeNamesChkArr)){
                foreach ($costTypeNamesArr as $typeName){
                    if(in_array($typeName,$costTypeNamesChkArr)){
                        $limitTypeNames .= ($limitTypeNames == '')? $typeName : ",".$typeName;
                    }
                }
            }
        }

        $backData = array(
            "result" => ($limitTypeNames == '')? "0" : "1",
            "data" => $limitTypeNames
        );
        return $backData;
    }

    /**
     * ��ȡ��������Ϣ
     *
     * @param string $configCode
     * @param string $matchField
     * @param string $matchVal
     * @param array $extSimpleCondition
     * @return array|bool
     */
    function getConfigItems($configCode = '',$matchField = '',$matchVal = '',$extSimpleCondition = array()){
        $this->searchArr['configuratorCode'] = $configCode;

        // �������� (��Ҫ��ѯӳ������������Ӧ���òŻ����ű�)
        if(!empty($extSimpleCondition)){
            $this->getParam ( $extSimpleCondition );
        }

        if($matchField != '' && $matchVal != ''){
            $mySearchCondition = "sql: and ( 1=1 ";
                $mySearchCondition .= " and ((i.{$matchField} = '{$matchVal}') or FIND_IN_SET('{$matchVal}',i.{$matchField}) > 0)";
            $mySearchCondition .= ")";
            $this->searchArr['mySearchCondition'] = $mySearchCondition;
        }

        $data = $this->pageBySqlId('list_items');
        return $data;
    }
}