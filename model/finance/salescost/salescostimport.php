<?php
class model_finance_salescost_salescostimport extends model_base {

    function __construct() {
        $this->tbl_name = "oa_sales_costrecord";
        $this->sql_map = "finance/salescost/salescostimportSql.php";
        parent::__construct ();
    }

    /**
     * 导入数据
     */
    function importSalesCostData_d(){
        set_time_limit(0);
        $service = $this->service;
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array();//结果数组
        $userArr = array();//用户数组
        $otherDataDao = new model_common_otherdatas();//其他信息查询
        $datadictArr = array();//数据字典数组
        $datadictDao = new model_system_datadict_datadict();
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
            spl_autoload_register("__autoload");
            if(is_array($excelData)){
                //行数组循环
                foreach($excelData as $key => $val){
                    $actNum = $key + 1;
                    if(empty($val[0]) && empty($val[1])){
                        continue;
                    }else{
                        //新增数组
                        $inArr = array();

                        // 月份
                        if(!empty($val[0])){
                            $belongMonth = $val[0];
                            $belongMonthArr = explode("-",$belongMonth);
                            if(count($belongMonthArr) == 2){
                                $inArr['belongMonth'] = $belongMonth;
                                $inArr['belongYear'] = $belongMonthArr[0];
                            }else{
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!所属月份必须为YYYY-MM的格式。</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }
                        }else{
                            $tempArr['docCode'] = '第' . $actNum .'行数据';
                            $tempArr['result'] = '<font color=red>导入失败!所属月份为空。</font>';
                            array_push($resultArr ,$tempArr);
                            continue;
                        }

                        // 归属大区
                        if(!empty($val[1])){
                            $salepersonDao = new model_system_saleperson_saleperson();
                            $exeDeptName = $val[1];
                            $exeDeptCode = $salepersonDao->find("exeDeptName = '{$exeDeptName}' and exeDeptCode <> '' and exeDeptCode is not null",null,'exeDeptCode');
                            if($exeDeptCode){
                                $inArr['exeDeptName'] = $exeDeptName;
                                $inArr['exeDeptCode'] = $exeDeptCode['exeDeptCode'];
                            }else{
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!无法匹配到对应归属大区。</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }
                        }else{
                            $tempArr['docCode'] = '第' . $actNum .'行数据';
                            $tempArr['result'] = '<font color=red>导入失败!归属大区为空。</font>';
                            array_push($resultArr ,$tempArr);
                            continue;
                        }

                        // 归属区域
                        if(!empty($val[2])){
                            $regionDao = new model_system_region_region();
                            $salesArea = $val[2];
                            $salesAreaObj = $regionDao->find(array("areaName"=>$salesArea));
                            if($salesAreaObj){
                                $inArr['salesArea'] = $salesArea;
                                $inArr['salesAreaId'] = $salesAreaObj['id'];
                            }else{
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!无法匹配到对应归属区域。</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }
                        }else{
                            $tempArr['docCode'] = '第' . $actNum .'行数据';
                            $tempArr['result'] = '<font color=red>导入失败!归属区域为空。</font>';
                            array_push($resultArr ,$tempArr);
                            continue;
                        }

                        // 费用承担人
                        if(!empty($val[3])){
                            $costMan = $val[3];
                            $sql = "select * from user where USER_NAME = '{$costMan}';";
                            $costManObj = $this->_db->getArray($sql);
                            if(!empty($costManObj)){
                                $inArr['costMan'] = $costMan;
                                $inArr['costManId'] = $costManObj[0]['USER_ID'];
                            }else{
                                $tempArr['docCode'] = '第' . $actNum .'行数据';
                                $tempArr['result'] = '<font color=red>导入失败!无法匹配到对应费用承担人。</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }
                        }else{
                            $tempArr['docCode'] = '第' . $actNum .'行数据';
                            $tempArr['result'] = '<font color=red>导入失败!费用承担人为空。</font>';
                            array_push($resultArr ,$tempArr);
                            continue;
                        }

                        // 其他费用
                        if(!empty($val[4])){
                            $costAmount = $val[4];
                            $inArr['costAmount'] = $costAmount;
                        }else{
                            $tempArr['docCode'] = '第'. $actNum .'行数据';
                            $tempArr['result'] = '<font color=red>导入失败!其他费用为空。</font>';
                            array_push($resultArr ,$tempArr);
                            continue;
                        }

                        // print_r($inArr);
                        $newId = parent::add_d($inArr ,true);
                        if($newId){
                            $tempArr['result'] = '导入成功';
                        }else{
                            $tempArr['result'] = '导入失败';
                        }
                        $tempArr['docCode'] = '第' . $actNum .'行数据';
                        array_push($resultArr ,$tempArr);
                    }
                }
            }
        }
        return $resultArr;
    }
}