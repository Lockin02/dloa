<?php
/**
 * @author Administrator
 * @Date 2017年11月03日 星期一 14:16:48
 * @version 1.0
 * @description:租车登记记录关联的临时报销信息 Model层
 */
class model_outsourcing_vehicle_rentalcar_expensetmp  extends model_base {

    public $_rentCarFeeName = array();
    function __construct() {
        $this->tbl_name = "oa_contract_rentcar_expensetmp";
        $this->sql_map = "outsourcing/vehicle/rentalcar/expensetmpSql.php";
        parent::__construct ();
    }

    /**
     * 添加租车报销临时记录
     * @description 先添加临时记录,在审批通过后用此记录来生成相应的报销单
     * @param $mainData
     * @param $detailData
     * @return bool|int|mixed|string
     */
    function addRecord($mainData,$detailData){
        // ini_set("display_errors",1);
        // 先检查是否存在主表ID
        $condition = " 1=1 ";
        IF($mainData['allregisterId'] != ''){
            $condition .= " and allregisterId = {$mainData['allregisterId']}";
        }

        IF($mainData['carNumber'] != '' || $mainData['carNumBase64'] != ''){
            $condition .= ($mainData['rentalPropertyCode'] == "ZCXZ-02")? " and FIND_IN_SET('{$mainData['carNumber']}',carNumber) > 0" : " and carNumBase64 = '{$mainData['carNumBase64']}'";
        }

        IF($mainData['payInfoId'] != ''){
            $condition .= " and payInfoId = {$mainData['payInfoId']}";
        }

        IF(isset($mainData['id']) && $mainData['id'] != '' && $mainData['id'] != '-'){
            $condition = " id = {$mainData['id']}";
            unset($mainData['id']);
            unset($mainData['carNumber']);
            unset($mainData['carNumBase64']);
        }
        $chkIssetRecord = $this->find($condition);

        try{
            $this->start_d();

            $mainRecordId = '';
            if($chkIssetRecord){// 已有填报记录
                // 更新主表数据
                $mainRecordId = $mainData['id'] = $chkIssetRecord['id'];
                $mainData = $this->addUpdateInfo($mainData);
                $this->updateById($mainData);

                $this->tbl_name = "oa_contract_rentcar_expensetmp_item";
                // 先清除原先明细的数据
                $this->delete(array("parentId" => $chkIssetRecord['id']));
                // 重新添加明细的数据
                foreach ($detailData as $k => $v){
                    $detailItem = $v;
                    $detailItem['parentId'] = $chkIssetRecord['id'];
                    $this->add_d($detailItem);
                }
            }else{
                $mainData = $this->addCreateInfo($mainData);
                // 先添加主表信息的数据
                $mainRecordId = $this->add_d($mainData);
                // 再添加明细信息的数据
                $this->tbl_name = "oa_contract_rentcar_expensetmp_item";
                if($mainRecordId){
                    foreach ($detailData as $k => $v){
                        $detailItem = $v;
                        $detailItem['parentId'] = $mainRecordId;
                        $this->add_d($detailItem);
                    }
                }
            }
            $this->tbl_name = "oa_contract_rentcar_expensetmp";
            $this->commit_d();
            return $mainRecordId;
        }catch(Exception $e){
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * 查询报销临时记录数据
     * @param string $id
     * @param string $allregisterId
     * @param string $carNums
     * @param string $payInfoId
     * @param bool $needDetail
     * @param int $isUseCurNumBase64
     * @param string $rentalProperty
     * @return mixed
     */
    function findExpenseTmpRecord($id = "",$allregisterId = "",$carNums = "",$payInfoId = "",$needDetail = false,$isUseCurNumBase64 = 1,$rentalProperty = '长租'){
        if($id != ""){
            $mainSql = "select * from oa_contract_rentcar_expensetmp where id = {$id};";
        }else{
            $mainSql = "select * from oa_contract_rentcar_expensetmp where 1=1 and rentalProperty = '{$rentalProperty}'";
            $extSql = "";
            if($allregisterId != ""){
                $extSql .= " AND allregisterId = '{$allregisterId}'";
            }

            if($carNums != ""){
//                $extSql .= ($isUseCurNumBase64)? " AND carNumBase64 = '{$carNums}'" : " AND FIND_IN_SET('{$carNums}',carNumber) > 0";// 如果不用车牌加密编码查询,则用用车牌号查询
                $extSql .= " AND FIND_IN_SET('{$carNums}',carNumber) > 0";
            }

            if($payInfoId != ""){
                $extSql .= " AND payInfoId = '{$payInfoId}'";
            }
            $mainSql .= $extSql;
        }

        $mainData = $this->_db->getArray($mainSql);
        if($mainData){
            $mainData = $mainData[0];
            if($needDetail){
                $getDetailSql = "select * from oa_contract_rentcar_expensetmp_item where parentId = {$mainData['id']}";
                $detailData = $this->_db->getArray($getDetailSql);
                $mainData['detail'] = ($detailData)? $detailData : array();
            }
        }
        return $mainData;
    }

    /**
     * 根据租车汇总ID获取所有关联的报销费用信息记录
     * @param $allregisterId
     * @return array|bool
     */
    function getExpenseTmpRecords($allregisterId){
        $mainSql = "select * from oa_contract_rentcar_expensetmp where allregisterId = {$allregisterId};";
        $mainData = $this->_db->getArray($mainSql);
        if($mainData && is_array($mainData)){
            foreach ($mainData as $k => $v){
                $getDetailSql = "select * from oa_contract_rentcar_expensetmp_item where parentId = {$v['id']}";
                $detailData = $this->_db->getArray($getDetailSql);
                $mainData[$k]['detail'] = ($detailData)? $detailData : array();
            }
        }
        return $mainData;
    }

    /**
     * 删除相关记录
     * @param $id
     * @return array
     */
    function deleteRecordById($id){
        $data = $this->findAll(" id in ({$id}) and isConfirm = 1");
        if($data){
            return array("result" => "fail","msg" => "含有已确认的费用项无法删除。");
        }else{
            // 删除主表
            $this->delete(" id in ({$id})");

            // 删除子表
            $sql = "delete from oa_contract_rentcar_expensetmp_item where parentId in ({$id});";
            $this->query($sql);
            return array("result" => "ok");
        }
    }
}