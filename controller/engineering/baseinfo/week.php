<?php
/**
 * @author HaoJin
 * @Date 2017年04月12日 星期三 11:00:59
 * @version 1.0
 * @description: 项目周次(oa_esm_baseinfo_budget)控制层
 */
class controller_engineering_baseinfo_week extends controller_base_action {
    function __construct() {
        $this->objName = "week";
        $this->objPath = "engineering_baseinfo";
        parent::__construct ();
    }

    function c_weekList(){
        $this->view("weekList");
    }

    /*
     * 获取同一年内所有周次
     */
    function c_getAllWeeksJson(){
        $service = $this->service;
        $rows = array();
        $service->getParam($_REQUEST);


        $service->sort = "longWeekNo";
        $rows = $this->service->pageBySqlId('select_default');

        $arr = array();
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 跳转到添加周次的页面
     */
    function c_toAddWeeks(){
        $this->view('toAddWeeks');
    }
    
    /*
     * 获取同一年内所有周次
     */
    function c_getRelativeWeeksJson(){
        $beginDate = isset($_REQUEST['beginDate'])? $_REQUEST['beginDate'] : '';
        $endDate = isset($_REQUEST['endDate'])? $_REQUEST['endDate'] : '';
        $year = isset($_REQUEST['year'])? $_REQUEST['year'] : (($beginDate != '')? mb_substr($beginDate,0,4) : '');
        $sort = isset($_REQUEST['sort'])? $_REQUEST['sort'] : 'asc';
        $beginDate = ($beginDate == '' && $year != '')? $year.'-01-01' : $beginDate;
        $endDate = ($endDate == '' && $year != '')? $year.'-12-31' : $endDate;
        $service = $this->service;
        $backArr = array();

        $row = $service->getAllWeeks($year,$beginDate,$endDate,$sort);
        $backArr['msg'] = ($row)? 'ok' : 'no';
        $backArr['data'] = $row;
        // echo"<pre>";print_r($backArr);
        echo json_encode($backArr);
    }

    /**
     * 批量保存前端生成的周次信息
     */
    function c_saveWeeks(){
        $weeksdata = array();
        $backResult['msg'] = '';
        $service = $this->service;
        $data = $_REQUEST[$this->objName];

        $year = isset($data['year'])? $data['year'] : '';
        $service->searchArr['yearsSearch'] = $year;
        $existData = $service->list_d();

        if(isset($data['allWeeks']) && $data['allWeeks'] != ''){
            $delResult = true;
            if(!empty($existData)){
                // 先删除该年份的所有周次
                $delResult = $service->delete("date_format(beginDate,'%Y') = {$year}");
            }

            if($delResult){
                $allWeeksdata = explode("\n",$data['allWeeks']);
                unset($allWeeksdata[0]);
                foreach ($allWeeksdata as $k => $v){
                    if($v != ''){
                        $arr = array();
                        $week = explode("|",$v);
                        $arr['longWeekNo'] = $week[1];
                        $arr['weekNo'] = $week[0];
                        $arr['beginDate'] = $week[2];
                        $arr['beginTime'] = strtotime($week[2]);
                        $arr['endDate'] = $week[3];
                        $arr['endTime'] = strtotime($week[3]);
                        $weeksdata[] = $arr;
                    }
                }
                if(!empty($weeksdata)){
//                     echo "<pre>";print_r($weeksdata);
                    $addResult = $service->addBatch_d($weeksdata);
                    $backResult['error'] = ($addResult)? "3" : "0";
                    $backResult['msg'] = ($addResult)? "ok" : "fail";
                }else{
                    $backResult['error'] = "2";
                    $backResult['msg'] = "fail";
                }
            }else{
                $backResult['error'] = "1";
                $backResult['msg'] = "fail";
            }
        }else{
            $backResult['error'] = "0";
            $backResult['msg'] = "fail";
        }
        echo json_encode($backResult);
    }

    /**
     * 检查是否存在相同年份的周次
     */
    function c_chkSameYearWeeks(){
        $service = $this->service;
        $year = isset($_REQUEST['year'])? $_REQUEST['year'] : '';
        $service->searchArr['yearsSearch'] = $year;
        $data = $service->list_d();
//        echo "<pre>";print_r($data);
        echo empty($data)? 0 : 1;
    }
}