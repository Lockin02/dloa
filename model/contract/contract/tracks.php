<?php
/**
 * @author HaoJin
 * @Date 2016年12月8日 11:35:28
 * @version 1.0
 * @description:合同执行轨迹新表 Model层
 */

class model_contract_contract_tracks extends model_base
{
    function __construct()
    {
        $this->tbl_name = "oa_contract_schdl_record";
        $this->sql_map = "contract/contract/tracksSql.php";
        parent :: __construct();
    }

    /**
     * 添加合同进程数据记录
     * @param $obj
     * @return string
     */
    function addRecord($obj){
        $object = array();
        $object['contractId'] = isset($obj['contractId'])? $obj['contractId'] : '';//合同ID
        $object['contractCode'] = isset($obj['contractCode'])? $obj['contractCode'] : '';//合同编号
        $object['exePortion'] = isset($obj['exePortion'])? $obj['exePortion'] : '';//合同执行进度
        $object['schedule'] = isset($obj['schedule'])? $obj['schedule'] : '';//合同进程
        $object['modelName'] = isset($obj['modelName'])? $obj['modelName'] : '';//节点模块名
        $object['operationName'] = isset($obj['operationName'])? $obj['operationName'] : '';//节点操作名
        $object['result'] = isset($obj['result'])? $obj['result'] : '';//节点操作结果数据
        $object['recordTime'] = isset($obj['recordTime'])? $obj['recordTime'] : '';//节点记录日期
        $object['expand1'] = isset($obj['expand1'])? $obj['expand1'] : '';//扩展字段1
        $object['expand2'] = isset($obj['expand2'])? $obj['expand2'] : '';//扩展字段2
        $object['expand3'] = isset($obj['expand3'])? $obj['expand3'] : '';//扩展字段3
        $object['createTime'] = isset($obj['createTime'])? $obj['createTime'] : time();//记录添加时间
        $object['createId'] = isset($obj['createId'])? $obj['createId'] : $_SESSION['USER_ID'];//记录触发用户ID
        $object['remarks'] = isset($obj['remarks'])? $obj['remarks'] : '';//备注

        //插入主表信息
        $newId = parent :: add_d($object);
        return $newId;
    }

    /**
     * 添加合同进程数据记录
     * @param $contractId 合同ID
     * @param $modelName 所属执行板块名
     * （开始执行：'contractBegin',开票记录：'invoiceMoney',
     *      到款记录：'incomeMoney',签收纸质合同：'contractSignIn',合同执行结束：'contractComplete',合同关闭：'contractClose'）
     * @param string $searchType 查询类型 （max:相同记录中的最新一条，match:满足条件的所有记录）
     * @param string $matchIDParam 匹配ID的字段名 查询类型为match时使用
     * @param $advCondition
     * @return array
     */
    function getRecord($contractId,$modelName,$searchType = 'max',$matchIDParam = '',$advCondition = ''){
        $sql1 =<<<EOT
        SELECT
            id,contractId,exePortion,operationName,expand1,expand2,expand3
        FROM
            oa_contract_schdl_record
        WHERE
            contractId = '$contractId'
        AND modelName = '$modelName'
EOT;
        // 根据查询的类型选择查询语句
        $sql = '';
        switch($searchType){
            case 'max':
                $sql = $sql1.$advCondition." ORDER BY id DESC LIMIT 1;";
                break;
            case 'match':
                $sql = $sql1.$advCondition.';';
                break;
        }

        // 查询数据
        $searchData = $this->_db->getArray($sql);

        // 返回数据
        $backArr['msg'] = '';
        $backArr['data'] = array();
//        $backArr['sql'] = $sql;
        if(!empty($searchData)){
            $backArr['msg'] = 'ok';
            if($matchIDParam != ''){
                $backArr['data']['detail'] = array();
                foreach($searchData as $v){
                    $backArr['data']['detail'][$v[$matchIDParam]] = $v;
                }
            }else{
                $backArr['data'] = $searchData[0];
            }
        }
        return $backArr;
    }
}