<?php
/**
 * @author Administrator
 * @Date 2017��11��03�� ����һ 14:16:48
 * @version 1.0
 * @description:�⳵�ǼǼ�¼��������ʱ������Ϣ Model��
 */
class model_outsourcing_vehicle_rentalcar_expensetmp  extends model_base {

    public $_rentCarFeeName = array();
    function __construct() {
        $this->tbl_name = "oa_contract_rentcar_expensetmp";
        $this->sql_map = "outsourcing/vehicle/rentalcar/expensetmpSql.php";
        parent::__construct ();
    }

    /**
     * ����⳵������ʱ��¼
     * @description �������ʱ��¼,������ͨ�����ô˼�¼��������Ӧ�ı�����
     * @param $mainData
     * @param $detailData
     * @return bool|int|mixed|string
     */
    function addRecord($mainData,$detailData){
        // ini_set("display_errors",1);
        // �ȼ���Ƿ��������ID
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
            if($chkIssetRecord){// �������¼
                // ������������
                $mainRecordId = $mainData['id'] = $chkIssetRecord['id'];
                $mainData = $this->addUpdateInfo($mainData);
                $this->updateById($mainData);

                $this->tbl_name = "oa_contract_rentcar_expensetmp_item";
                // �����ԭ����ϸ������
                $this->delete(array("parentId" => $chkIssetRecord['id']));
                // ���������ϸ������
                foreach ($detailData as $k => $v){
                    $detailItem = $v;
                    $detailItem['parentId'] = $chkIssetRecord['id'];
                    $this->add_d($detailItem);
                }
            }else{
                $mainData = $this->addCreateInfo($mainData);
                // �����������Ϣ������
                $mainRecordId = $this->add_d($mainData);
                // �������ϸ��Ϣ������
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
     * ��ѯ������ʱ��¼����
     * @param string $id
     * @param string $allregisterId
     * @param string $carNums
     * @param string $payInfoId
     * @param bool $needDetail
     * @param int $isUseCurNumBase64
     * @param string $rentalProperty
     * @return mixed
     */
    function findExpenseTmpRecord($id = "",$allregisterId = "",$carNums = "",$payInfoId = "",$needDetail = false,$isUseCurNumBase64 = 1,$rentalProperty = '����'){
        if($id != ""){
            $mainSql = "select * from oa_contract_rentcar_expensetmp where id = {$id};";
        }else{
            $mainSql = "select * from oa_contract_rentcar_expensetmp where 1=1 and rentalProperty = '{$rentalProperty}'";
            $extSql = "";
            if($allregisterId != ""){
                $extSql .= " AND allregisterId = '{$allregisterId}'";
            }

            if($carNums != ""){
//                $extSql .= ($isUseCurNumBase64)? " AND carNumBase64 = '{$carNums}'" : " AND FIND_IN_SET('{$carNums}',carNumber) > 0";// ������ó��Ƽ��ܱ����ѯ,�����ó��ƺŲ�ѯ
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
     * �����⳵����ID��ȡ���й����ı���������Ϣ��¼
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
     * ɾ����ؼ�¼
     * @param $id
     * @return array
     */
    function deleteRecordById($id){
        $data = $this->findAll(" id in ({$id}) and isConfirm = 1");
        if($data){
            return array("result" => "fail","msg" => "������ȷ�ϵķ������޷�ɾ����");
        }else{
            // ɾ������
            $this->delete(" id in ({$id})");

            // ɾ���ӱ�
            $sql = "delete from oa_contract_rentcar_expensetmp_item where parentId in ({$id});";
            $this->query($sql);
            return array("result" => "ok");
        }
    }
}