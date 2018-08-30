<?php
/**
 * @author HaoJin
 * @Date 2017��04��12�� ������ 11:00:59
 * @version 1.0
 * @description: ��Ŀ�ܴ�(oa_esm_baseinfo_budget)���Ʋ�
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
     * ��ȡͬһ���������ܴ�
     */
    function c_getAllWeeksJson(){
        $service = $this->service;
        $rows = array();
        $service->getParam($_REQUEST);


        $service->sort = "longWeekNo";
        $rows = $this->service->pageBySqlId('select_default');

        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * ��ת������ܴε�ҳ��
     */
    function c_toAddWeeks(){
        $this->view('toAddWeeks');
    }
    
    /*
     * ��ȡͬһ���������ܴ�
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
     * ��������ǰ�����ɵ��ܴ���Ϣ
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
                // ��ɾ������ݵ������ܴ�
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
     * ����Ƿ������ͬ��ݵ��ܴ�
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