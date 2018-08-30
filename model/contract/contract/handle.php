<?php
/**
 * @author dloa
 * @Date 2015年1月20日 14:09:51
 * @version 1.0
 * @description:合同操作记录表 Model层
 */
class model_contract_contract_handle extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_contract_handle";
        $this->sql_map = "contract/contract/handleSql.php";
        parent::__construct();

        //操作步骤
        $this->stepArr = array(
            "合同录入" => "HTLR",
            "合同编辑" => "HTBJ",
            "提交成本确认" => "TJCBQR",
            "物料确认" => "WLQR",
            "打回至销售" => "DHZXS",
            "销售确认物料" => "XSWLQR",
            "打回物料确认" => "DHWLQR",
            "服务成本确认" => "FWCBQR",
            "执行部门审核" => "ZXBMSH",
            "执行部门审核打回" => "ZXBMSHDH",
            "提交审批" => "TJSP",
            "审批通过" => "SPTG",
            "免审通过" => "MSTG",
            "审批打回" => "SPDH",
            "合同关闭" => "HTGB",
            "合同变更" => "HTBG",
            "物料确认变更" => "WLQRBG"
        );
        //大节点划分
        $this->suppStepArr = array(
            "HTLR" => array("HTLR","HTBJ","DHZXS","SPDH"),
            "CPQR" => array("WLQR","XSWLQR","DHWLQR","FWCBQR","ZXBMSHDH","TJCBQR","ZXBMSH","HTBG"),
            "HTSP" => array("TJSP","SPTG")
        );
    }


    /**
     *  操作记录插入方法
     *   array(
     *      "cid"=> xxx,
     *      "stepName"=> xxx,
     *      "isChange"=> xxx,
     *      "stepInfo"=> xxx,
     *   )
     */
    function handleAdd_d($arr) {
        $cid = $arr['cid'];
        $stepName = $arr['stepName'];
        $isChange = $arr['isChange']; //是否是变更数据，每次变更值递增
        $stepInfo = $arr['stepInfo'];
        $remark = isset($arr['remark'])? $arr['remark'] : '';

        //变更序号
        $isChange = $this->getChangeNum($cid, $isChange);
        //操作序号
        $handleNum = $this->getHandleNum($cid, $isChange);
        $row = array(
            "contractId" => $cid,
            "handleNum" => $handleNum,
            "stepName" => $stepName,
            "stepCode" => $this->stepArr[$stepName],
            "handleId" => $_SESSION['USER_ID'],
            "handleName" => $_SESSION['USERNAME'],
            "handleDate" => date('Y-m-d  H:i:s'),
            "isChange" => $isChange,
            "stepInfo" => $stepInfo,
            "remark" => $remark
        );
        $this->add_d($row);
    }

    //获取操作序号
    function getHandleNum($cid, $isChange) {
        $sql = "select if(max(handleNum) is null,0,max(handleNum))+1 as num  from oa_contract_handle where contractId='".$cid."' and isChange='".$isChange."'";
        $arr = $this->_db->getArray($sql);
        if(empty($arr)){
            return "0";
        }else{
            return $arr[0]['num'];
        }
    }
    //获取变更序号
    function getChangeNum($cid, $isChange) {
        if($isChange == 1){
            $sql = "select max(isChange)+1 as num from oa_contract_handle where contractId = '".$cid."' GROUP BY contractId";
            $arr = $this->_db->getArray($sql);
        }else if($isChange == 2){
            $sql = "select max(isChange) as num from oa_contract_handle where contractId = '".$cid."' GROUP BY contractId";
            $arr = $this->_db->getArray($sql);
        }else{
            return "0";
        }
        if(empty($arr)){
            return "0";
        }else{
            return $arr[0]['num'];
        }
    }

    /**
     * 根据合同id 判断是否 有记录
     */
    function getIsCon($cid){
        $sql = "select max(isChange) as num from oa_contract_handle where contractId = ".$cid." GROUP BY contractId";
        $arr = $this->_db->getArray($sql);
        if(empty($arr)){
            return 'null';
        }else{
            return $arr[0]['num'];
        }
    }

    /**
     * 动态构建 流程图tab
     *
     */
    function getTabHtml($num,$cid){
        $htmlStr = "";
        if($num != 0){
            for($i=$num;$i>=0;$i--){
                if($i != 0){
                    $htmlStr.= "<div title='第 ".$i." 次变更' headerCls='?model=contract_contract_contract&action=handleView&num=".$i."&cid=".$cid."'></div>";
                }else{
                    $htmlStr.= "<div title='合同建立' headerCls='?model=contract_contract_contract&action=handleView&num=".$i."&cid=".$cid."'></div>";
                }
            }
        }else{
            $htmlStr = "<div title='合同建立' headerCls='?model=contract_contract_contract&action=handleView&num=0&cid=".$cid."'></div>";
        }
        return $htmlStr;
    }

    /**
     * 流程图数据替换
     */
    function handleHtmStr($num,$cid){
        // 先纠正错误数据
        $this->handleDateChk($num,$cid);

        $sql = "select stepName,stepCode,handleId,handleName,remark,UNIX_TIMESTAMP(handleDate) AS ordDate,date_format(handleDate,'%Y-%m-%d') as handleDate,stepInfo,handleNum from oa_contract_handle where contractId = '".$cid."' and isChange='".$num."'";
        $arr = $this->_db->getArray($sql);
        //获取合同预设流程数组
        $predictArr = $this->getpredictArr($num,$cid);
        //获取后续未操作流程步骤
        $suppArr = $this->getSuppArr($num,$cid);
        //整理数组
        $handleArr = array();
        $wlC=0;
        $cpqrArr = unserialize(CBQRARR);
        $cbsharr = unserialize(CBSHARR);
        $dataDao = new model_system_datadict_datadict();

        foreach($arr as $k =>$v){
            switch($v['stepCode']){
                case 'TJCBQR' :
                    if($num == 0){
                        $handleArr[] =array("ordNum"=>$v['handleNum']."0".$v['ordDate'],"str"=> "
                        合同录入->>成本确认: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }else{
                        $handleArr[] =array("ordNum"=>$v['handleNum']."0".$v['ordDate'],"str"=> "
                        合同变更->>成本确认: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    } break;
                case 'DHZXS' :
                    if($num == 0){
                        $handleArr[] =array("ordNum"=>$v['handleNum']."0".$v['ordDate'],"str"=> "
                        成本确认-x合同录入: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }else{
                        $handleArr[] =array("ordNum"=>$v['handleNum']."0".$v['ordDate'],"str"=> "
                        成本确认-x合同变更: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    } break;
                case 'WLQR' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."1".$v['ordDate'],"str"=> "
                    成本确认->>销售确认: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                case 'DHWLQR' :
                    if($arr[$k-1]['stepCode'] == "WLQRBG"){
                        $handleArr[] =array("ordNum"=>$v['handleNum']."1".$v['ordDate'],"str"=> "
                    销售确认-x物料确认变更: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }else{
                        $handleArr[] =array("ordNum"=>$v['handleNum']."1".$v['ordDate'],"str"=> "
                    销售确认-x成本确认: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }
                case 'FWCBQR' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    成本确认->>成本审核:".$dataDao->getDataNameByCode($v['stepInfo'])." ".$v['handleName']." ".$v['handleDate']." 成本确认");break;
                case 'XSWLQR' :
                    if($wlC==1){
                        $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'].$v['ordDate'],"str"=> "
                    销售确认->>合同审批: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }else{
                        $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    销售确认->>合同审批: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }
                case 'ZXBMSHDH' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    成本审核-x成本确认: ".$dataDao->getDataNameByCode($v['stepInfo'])." ".$v['handleName']." ".$v['handleDate']." 审核打回");break;
                case 'ZXBMSH' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    成本审核->>合同审批:".$dataDao->getDataNameByCode($v['stepInfo'])." ".$v['handleName']." ".$v['handleDate']." 审核");break;
                case 'SPTG' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    合同审批->>合同执行: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                case 'MSTG' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    销售确认->>合同执行: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                case 'SPDH' :
                    if($num == 0){
                        $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                        合同审批-x合同录入: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }else{
                        $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                        合同审批-x合同变更: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    } break;
                case 'HTBG' :
                    if($v['stepInfo'] == '无需审批'){
                        $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    合同变更->>合同执行: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    };break;
                case 'WLQRBG' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    物料确认变更->>销售确认: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");$wlC=1;break;
            }
        }
        $htmlStr = "";
//        $predictArr

        //处理权重数组
        foreach($handleArr as $va){
            $ordArr[] = $va['ordNum'];
        }
        asort($ordArr);
        $newArr = array();
        foreach($ordArr as $key => $val){
            $newArr[$key] = $handleArr[$key]['str'];
        }
        $handleArr = array_merge($newArr,$suppArr);
        foreach($handleArr as $v){
            $htmlStr .= $v;
        }
        return $htmlStr;
    }

    //合同预设流程数组
    function getpredictArr($num,$cid){
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($cid);
        //合同录入
        if($num == 0){
            $predictArr['t0'] = "合同录入-->>成本确认: 等待提交";
        }else{
            $predictArr['t0'] = "合同变更-->>成本确认: 等待提交";
        }
        //成本确认
        $productLineStr =  rtrim($conArr['exeDeptStr'], ',');
        $exeDeptArr = explode(",",$productLineStr);
        $exeDeptArr = array_flip($exeDeptArr);
        $exeDeptArr = array_flip($exeDeptArr);
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDataNameByCode("GCSCX");
        $cpqrArr = unserialize(CBQRARR);
        foreach($exeDeptArr as $v){
            $predictArr['t1'] .= "
            成本确认-->>成本审核: ".$dataDao->getDataNameByCode($v)." $cpqrArr[$v] 未确认
            ";
            $predictArr['t2'] .= "
            成本审核-->>合同审批: ".$dataDao->getDataNameByCode($v)." $cpqrArr[$v] 未审核
            ";
        }
        //合同审批
        $predictArr['t3'] .= "
            合同审批-->>合同执行:  合同未审批
            ";

        return $predictArr;
    }

    /**
     * 检查是否有含有同一张合同内比最新步骤的处理时间还要大的数据,如果有则更新为这些问题步骤的上一步的时间
     * @param $num
     * @param $cid
     */
    function handleDateChk($num,$cid){
        $sql = "select id from oa_contract_handle where contractId=$cid and isChange=$num order by handleNum desc limit 0,1";
        $lastArr = $this->_db->getArray($sql);
        if($lastArr){
            $lastId = $lastArr[0]['id'];
            $chkSql = "
            select a.* from oa_contract_handle a left join 
            (
            select * from oa_contract_handle where id = {$lastId}
            )b on a.contractId = b.contractId
            where a.handleDate > b.handleDate and a.isChange=$num
            ";
            $errorArr = $this->_db->getArray($chkSql);

            if($errorArr){
                $errorIds = "";
                $prevHandleNum = ($errorArr[0]['handleNum'] > 1)? ($errorArr[0]['handleNum'] - 1):  $errorArr[0]['handleNum'];// 问题数据的上一步的处理步骤数
                foreach ($errorArr as $v){
                    $errorIds .= ($errorIds == "")? $v['id'] : ",".$v['id'];
                }
                $updateSql = "
                    UPDATE oa_contract_handle a
                    LEFT JOIN oa_contract_handle b ON a.contractId = b.contractId
                    SET a.handleDate = date_add(b.handleDate, interval 10 second)
                    WHERE
                        b.handleNum = {$prevHandleNum}
                    AND b.contractId = {$cid}
                    AND b.isChange = {$num}
                    AND a.id IN ({$errorIds});
                ";
                $this->_db->query($updateSql);
            }
        }
    }

    //处理未操作流程数组(下一步)
    function getSuppArr($num,$cid){
        //获取当前已执行的步骤
        $sql = "select * from oa_contract_handle where contractId=$cid and isChange=$num order by handleNum desc limit 0,4";
        $arr = $this->_db->getArray($sql);
        $stepCode = $arr[0]['stepCode'];//当前步骤编码
        $prevObj = $arr[1];
        $prevTwoObj = $arr[2];
        //人为划分节点数组判断 当前步骤属于哪个大节点
        foreach($this->suppStepArr as $k=>$v){
            if(in_array($stepCode,$v)){
                $stepNow = $k;
                break;
            }
        }
        $conDao = new model_contract_contract_contract();
        if($num != 0){
            $tid=$conDao->findChangeId($cid);
            $conArr = $conDao->get_d($tid);
        }else{
            $conArr = $conDao->get_d($cid);
        }
        $suppArr=array();
        $cpqrArr = unserialize(CBQRARR);
        $cbsharr = unserialize(CBSHARR);
        $dataDao = new model_system_datadict_datadict();
        $needStr = true;
        // 如果是交付变更被物料确认打回的,流程结束,不需要有未处理流程了
        if($stepCode == "DHWLQR" && $prevObj['stepCode'] == 'WLQRBG'){
            $needStr = false;
        }
        if($needStr){
            switch($stepCode){
            case "HTLR" :
                $suppArr['s'] = "
                合同录入-->>成本确认: 等待提交"; break;
            case "HTBJ" :
                $suppArr['s'] = "
                合同录入-->>成本确认: 等待提交"; break;
            case "DHZXS" :
                $suppArr['s'] = "
                合同录入-->>成本确认: 等待提交"; break;
            case "SPDH" :
                $suppArr['s'] = "
                合同录入-->>成本确认: 等待提交"; break;
            case "WLQRBG" :
                $suppArr['s'] = "
                销售确认-->>合同执行: 等待销售 (".$conArr['prinvipalName'].") 确认物料"; break;
            case "TJCBQR" :
                //成本确认
//                $productLineStr =  rtrim($conArr['exeDeptStr'], ',');
//                $exeDeptArr = explode(",",$productLineStr);
//                $exeDeptArr = array_flip($exeDeptArr);
//                $exeDeptArr = array_flip($exeDeptArr);
//                foreach($exeDeptArr as $v){
//                    $suppArr['t1'] .= "
//            成本确认-->>成本审核: ".$dataDao->getDataNameByCode($v)." $cpqrArr[$v] 未确认
//            ";
//                    $suppArr['t2'] .= "
//            成本审核-->>合同审批: ".$dataDao->getDataNameByCode($v)." $cbsharr[$v] 未审核
//            ";
//                };break;
                $suppArr['s'] = "
                成本确认-->>销售确认: 等待确认物料"; break;

            case "WLQR" :
                $suppArr['s'] = "
                销售确认-->>合同审批: 等待销售 (".$conArr['prinvipalName'].") 确认物料";
                $productLineStr = $conDao->getProLineStr($cid,'17');
//                if(!empty($productLineStr)){
//                    $exeDeptArr = explode(",",$productLineStr);
//                    $exeDeptArr = array_flip($exeDeptArr);
//                    $exeDeptArr = array_flip($exeDeptArr);
//                    $dataDao = new model_system_datadict_datadict();
//                    $typeArr = $dataDao->getDataNameByCode("GCSCX");
//                    $cpqrArr = unserialize(CBQRARR);
//                    foreach($exeDeptArr as $v){
//                        $suppArr['s1'] .= "
//            成本确认-->>成本审核: ".$dataDao->getDataNameByCode($v)." $cpqrArr[$v] 未确认
//            ";
//                        $suppArr['s1'] .= "
//            成本审核-->>合同审批: ".$dataDao->getDataNameByCode($v)." $cpqrArr[$v] 未审核
//            ";
//                    };
//                }
                break;
            case "DHWLQR" :
                    $suppArr['s'] = "
                成本确认-->>销售确认: 等待确认物料"; break;
            case "XSWLQR" :
                if($num != 0){
                    $shsql="select * from oa_contract_cost where contractId=".$tid." and ExaState!=1";
                }else{
                    $shsql="select * from oa_contract_cost where contractId=".$cid." and ExaState!=1";
                }
                //查询审核明细

                $shArr = $this->_db->getArray($shsql);
                if(!empty($shArr)){
                    foreach($shArr as $val){
                        $productLine = $val['productLine'];
                        if($val['issale'] == 1){
                            $suppArr['s'] .= "
                        合同审批-->>提交审批:".$val['productLineName']." ".$cbsharr[$productLine]." 未审核";
                        }else{
                            $suppArr['s'] .= "
                        成本审核-->>提交审批:".$val['productLineName']." ".$cbsharr[$productLine]." 未审核";
                        }
                        break;
                    }
                }else{
                    $suppArr['s'] = "
                    成本审核-->>提交审批: 等待成本审核";
                }
                break;
            case "FWCBQR" :
                $code = $arr[0]['stepInfo'];
                $suppArr['s'] = "
                成本审核-->>合同审批: ".$dataDao->getDataNameByCode($code)." ".$cbsharr[$code]." 未审核"; break;
            case "ZXBMSH" :
                $suppArr['s'] = "
                合同审批-->>合同执行: 等待提交审批"; break;
            case "ZXBMSHDH" :
                $suppArr['s'] = "
                成本确认-->>成本审核: 等待提交审核"; break;
            case "TJSP" :
                $userDao = new model_deptuser_user_user();
                //审批数据
                if($num != 0){
                    $appSql = "select p.* from wf_task t left join flow_step_partent p on t.task=p.Wf_task_ID
                     where t.Pid=".$tid." and t.code='oa_contract_contract' and Result = '';";
                }else{
                    $appSql = "select p.* from wf_task t left join flow_step_partent p on t.task=p.Wf_task_ID
                     where t.Pid=".$cid." and t.code='oa_contract_contract' and Result = '';";
                }
                $appArr = $this->_db->getArray($appSql);
                if(!empty($appArr)){
                    foreach($appArr as $val){
                        $userNameArr = $userDao->getUserById($val['User']);
                        if($val['Result']=='ok'){
                            $suppArr['s'] .= "
                            合同审批-->>合同执行: ".$userNameArr['USER_NAME']." ".date('Y-m-d',strtotime($val['Endtime']))." 审批完成 "; break;
                        }else if($val['Result'] != 'no'){
                            $suppArr['s'] .= "
                            合同审批-->>合同执行: ".$userNameArr['USER_NAME']." 未审批 "; break;
                        }else if($prevObj['stepCode'] == 'FWCBQR' && $prevTwoObj['stepCode'] == 'TJCBQR' && $num == 0){// 如果是新增,且上一步是提交本确认的,且没提交审批流,那就是交付还没确认
                            $suppArr['s'] = "
                            成本确认-->>销售确认: 等待确认物料"; break;
                        }else if($prevObj['stepCode'] == 'FWCBQR' && $num == 0){// 如果是新增,且上一步是服务成本确认,且提交了审批流的,那就是等待审批通过
                            $suppArr['s'] = "
                            成本审核-->>合同执行: 等待审批通过"; break;
                        }
                    }
                }else if($prevObj['stepCode'] == 'FWCBQR' && $num == 0){// 如果是新增,且上一步是服务成本确认,且没提交审批流,那就是销售物料还没确认
                    $suppArr['s'] = "
                            销售确认-->>合同审批: 等待销售确认"; break;
                }else{
                    $suppArr['s'] = "
                    合同审批-->>合同执行: 等待审批通过"; break;
                }
        }
        }
        return $suppArr;
    }


}

?>