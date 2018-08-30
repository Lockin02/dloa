<?php
class model_finance_salescost_salescostimport extends model_base {

    function __construct() {
        $this->tbl_name = "oa_sales_costrecord";
        $this->sql_map = "finance/salescost/salescostimportSql.php";
        parent::__construct ();
    }

    /**
     * ��������
     */
    function importSalesCostData_d(){
        set_time_limit(0);
        $service = $this->service;
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array();//�������
        $userArr = array();//�û�����
        $otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
        $datadictArr = array();//�����ֵ�����
        $datadictDao = new model_system_datadict_datadict();
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
            spl_autoload_register("__autoload");
            if(is_array($excelData)){
                //������ѭ��
                foreach($excelData as $key => $val){
                    $actNum = $key + 1;
                    if(empty($val[0]) && empty($val[1])){
                        continue;
                    }else{
                        //��������
                        $inArr = array();

                        // �·�
                        if(!empty($val[0])){
                            $belongMonth = $val[0];
                            $belongMonthArr = explode("-",$belongMonth);
                            if(count($belongMonthArr) == 2){
                                $inArr['belongMonth'] = $belongMonth;
                                $inArr['belongYear'] = $belongMonthArr[0];
                            }else{
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!�����·ݱ���ΪYYYY-MM�ĸ�ʽ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }
                        }else{
                            $tempArr['docCode'] = '��' . $actNum .'������';
                            $tempArr['result'] = '<font color=red>����ʧ��!�����·�Ϊ�ա�</font>';
                            array_push($resultArr ,$tempArr);
                            continue;
                        }

                        // ��������
                        if(!empty($val[1])){
                            $salepersonDao = new model_system_saleperson_saleperson();
                            $exeDeptName = $val[1];
                            $exeDeptCode = $salepersonDao->find("exeDeptName = '{$exeDeptName}' and exeDeptCode <> '' and exeDeptCode is not null",null,'exeDeptCode');
                            if($exeDeptCode){
                                $inArr['exeDeptName'] = $exeDeptName;
                                $inArr['exeDeptCode'] = $exeDeptCode['exeDeptCode'];
                            }else{
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!�޷�ƥ�䵽��Ӧ����������</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }
                        }else{
                            $tempArr['docCode'] = '��' . $actNum .'������';
                            $tempArr['result'] = '<font color=red>����ʧ��!��������Ϊ�ա�</font>';
                            array_push($resultArr ,$tempArr);
                            continue;
                        }

                        // ��������
                        if(!empty($val[2])){
                            $regionDao = new model_system_region_region();
                            $salesArea = $val[2];
                            $salesAreaObj = $regionDao->find(array("areaName"=>$salesArea));
                            if($salesAreaObj){
                                $inArr['salesArea'] = $salesArea;
                                $inArr['salesAreaId'] = $salesAreaObj['id'];
                            }else{
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!�޷�ƥ�䵽��Ӧ��������</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }
                        }else{
                            $tempArr['docCode'] = '��' . $actNum .'������';
                            $tempArr['result'] = '<font color=red>����ʧ��!��������Ϊ�ա�</font>';
                            array_push($resultArr ,$tempArr);
                            continue;
                        }

                        // ���óе���
                        if(!empty($val[3])){
                            $costMan = $val[3];
                            $sql = "select * from user where USER_NAME = '{$costMan}';";
                            $costManObj = $this->_db->getArray($sql);
                            if(!empty($costManObj)){
                                $inArr['costMan'] = $costMan;
                                $inArr['costManId'] = $costManObj[0]['USER_ID'];
                            }else{
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!�޷�ƥ�䵽��Ӧ���óе��ˡ�</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }
                        }else{
                            $tempArr['docCode'] = '��' . $actNum .'������';
                            $tempArr['result'] = '<font color=red>����ʧ��!���óе���Ϊ�ա�</font>';
                            array_push($resultArr ,$tempArr);
                            continue;
                        }

                        // ��������
                        if(!empty($val[4])){
                            $costAmount = $val[4];
                            $inArr['costAmount'] = $costAmount;
                        }else{
                            $tempArr['docCode'] = '��'. $actNum .'������';
                            $tempArr['result'] = '<font color=red>����ʧ��!��������Ϊ�ա�</font>';
                            array_push($resultArr ,$tempArr);
                            continue;
                        }

                        // print_r($inArr);
                        $newId = parent::add_d($inArr ,true);
                        if($newId){
                            $tempArr['result'] = '����ɹ�';
                        }else{
                            $tempArr['result'] = '����ʧ��';
                        }
                        $tempArr['docCode'] = '��' . $actNum .'������';
                        array_push($resultArr ,$tempArr);
                    }
                }
            }
        }
        return $resultArr;
    }
}