<?php
/**
 * @author dloa
 * @Date 2015��1��20�� 14:09:51
 * @version 1.0
 * @description:��ͬ������¼�� Model��
 */
class model_contract_contract_handle extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_contract_handle";
        $this->sql_map = "contract/contract/handleSql.php";
        parent::__construct();

        //��������
        $this->stepArr = array(
            "��ͬ¼��" => "HTLR",
            "��ͬ�༭" => "HTBJ",
            "�ύ�ɱ�ȷ��" => "TJCBQR",
            "����ȷ��" => "WLQR",
            "���������" => "DHZXS",
            "����ȷ������" => "XSWLQR",
            "�������ȷ��" => "DHWLQR",
            "����ɱ�ȷ��" => "FWCBQR",
            "ִ�в������" => "ZXBMSH",
            "ִ�в�����˴��" => "ZXBMSHDH",
            "�ύ����" => "TJSP",
            "����ͨ��" => "SPTG",
            "����ͨ��" => "MSTG",
            "�������" => "SPDH",
            "��ͬ�ر�" => "HTGB",
            "��ͬ���" => "HTBG",
            "����ȷ�ϱ��" => "WLQRBG"
        );
        //��ڵ㻮��
        $this->suppStepArr = array(
            "HTLR" => array("HTLR","HTBJ","DHZXS","SPDH"),
            "CPQR" => array("WLQR","XSWLQR","DHWLQR","FWCBQR","ZXBMSHDH","TJCBQR","ZXBMSH","HTBG"),
            "HTSP" => array("TJSP","SPTG")
        );
    }


    /**
     *  ������¼���뷽��
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
        $isChange = $arr['isChange']; //�Ƿ��Ǳ�����ݣ�ÿ�α��ֵ����
        $stepInfo = $arr['stepInfo'];
        $remark = isset($arr['remark'])? $arr['remark'] : '';

        //������
        $isChange = $this->getChangeNum($cid, $isChange);
        //�������
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

    //��ȡ�������
    function getHandleNum($cid, $isChange) {
        $sql = "select if(max(handleNum) is null,0,max(handleNum))+1 as num  from oa_contract_handle where contractId='".$cid."' and isChange='".$isChange."'";
        $arr = $this->_db->getArray($sql);
        if(empty($arr)){
            return "0";
        }else{
            return $arr[0]['num'];
        }
    }
    //��ȡ������
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
     * ���ݺ�ͬid �ж��Ƿ� �м�¼
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
     * ��̬���� ����ͼtab
     *
     */
    function getTabHtml($num,$cid){
        $htmlStr = "";
        if($num != 0){
            for($i=$num;$i>=0;$i--){
                if($i != 0){
                    $htmlStr.= "<div title='�� ".$i." �α��' headerCls='?model=contract_contract_contract&action=handleView&num=".$i."&cid=".$cid."'></div>";
                }else{
                    $htmlStr.= "<div title='��ͬ����' headerCls='?model=contract_contract_contract&action=handleView&num=".$i."&cid=".$cid."'></div>";
                }
            }
        }else{
            $htmlStr = "<div title='��ͬ����' headerCls='?model=contract_contract_contract&action=handleView&num=0&cid=".$cid."'></div>";
        }
        return $htmlStr;
    }

    /**
     * ����ͼ�����滻
     */
    function handleHtmStr($num,$cid){
        // �Ⱦ�����������
        $this->handleDateChk($num,$cid);

        $sql = "select stepName,stepCode,handleId,handleName,remark,UNIX_TIMESTAMP(handleDate) AS ordDate,date_format(handleDate,'%Y-%m-%d') as handleDate,stepInfo,handleNum from oa_contract_handle where contractId = '".$cid."' and isChange='".$num."'";
        $arr = $this->_db->getArray($sql);
        //��ȡ��ͬԤ����������
        $predictArr = $this->getpredictArr($num,$cid);
        //��ȡ����δ�������̲���
        $suppArr = $this->getSuppArr($num,$cid);
        //��������
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
                        ��ͬ¼��->>�ɱ�ȷ��: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }else{
                        $handleArr[] =array("ordNum"=>$v['handleNum']."0".$v['ordDate'],"str"=> "
                        ��ͬ���->>�ɱ�ȷ��: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    } break;
                case 'DHZXS' :
                    if($num == 0){
                        $handleArr[] =array("ordNum"=>$v['handleNum']."0".$v['ordDate'],"str"=> "
                        �ɱ�ȷ��-x��ͬ¼��: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }else{
                        $handleArr[] =array("ordNum"=>$v['handleNum']."0".$v['ordDate'],"str"=> "
                        �ɱ�ȷ��-x��ͬ���: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    } break;
                case 'WLQR' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."1".$v['ordDate'],"str"=> "
                    �ɱ�ȷ��->>����ȷ��: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                case 'DHWLQR' :
                    if($arr[$k-1]['stepCode'] == "WLQRBG"){
                        $handleArr[] =array("ordNum"=>$v['handleNum']."1".$v['ordDate'],"str"=> "
                    ����ȷ��-x����ȷ�ϱ��: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }else{
                        $handleArr[] =array("ordNum"=>$v['handleNum']."1".$v['ordDate'],"str"=> "
                    ����ȷ��-x�ɱ�ȷ��: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }
                case 'FWCBQR' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    �ɱ�ȷ��->>�ɱ����:".$dataDao->getDataNameByCode($v['stepInfo'])." ".$v['handleName']." ".$v['handleDate']." �ɱ�ȷ��");break;
                case 'XSWLQR' :
                    if($wlC==1){
                        $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'].$v['ordDate'],"str"=> "
                    ����ȷ��->>��ͬ����: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }else{
                        $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    ����ȷ��->>��ͬ����: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }
                case 'ZXBMSHDH' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    �ɱ����-x�ɱ�ȷ��: ".$dataDao->getDataNameByCode($v['stepInfo'])." ".$v['handleName']." ".$v['handleDate']." ��˴��");break;
                case 'ZXBMSH' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    �ɱ����->>��ͬ����:".$dataDao->getDataNameByCode($v['stepInfo'])." ".$v['handleName']." ".$v['handleDate']." ���");break;
                case 'SPTG' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    ��ͬ����->>��ִͬ��: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                case 'MSTG' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    ����ȷ��->>��ִͬ��: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                case 'SPDH' :
                    if($num == 0){
                        $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                        ��ͬ����-x��ͬ¼��: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    }else{
                        $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                        ��ͬ����-x��ͬ���: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    } break;
                case 'HTBG' :
                    if($v['stepInfo'] == '��������'){
                        $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    ��ͬ���->>��ִͬ��: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");break;
                    };break;
                case 'WLQRBG' :
                    $handleArr[] =array("ordNum"=>$v['handleNum']."2".$v['ordDate'],"str"=> "
                    ����ȷ�ϱ��->>����ȷ��: ".$v['handleName']." ".$v['handleDate']."  ".$v['stepName']."");$wlC=1;break;
            }
        }
        $htmlStr = "";
//        $predictArr

        //����Ȩ������
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

    //��ͬԤ����������
    function getpredictArr($num,$cid){
        $conDao = new model_contract_contract_contract();
        $conArr = $conDao->get_d($cid);
        //��ͬ¼��
        if($num == 0){
            $predictArr['t0'] = "��ͬ¼��-->>�ɱ�ȷ��: �ȴ��ύ";
        }else{
            $predictArr['t0'] = "��ͬ���-->>�ɱ�ȷ��: �ȴ��ύ";
        }
        //�ɱ�ȷ��
        $productLineStr =  rtrim($conArr['exeDeptStr'], ',');
        $exeDeptArr = explode(",",$productLineStr);
        $exeDeptArr = array_flip($exeDeptArr);
        $exeDeptArr = array_flip($exeDeptArr);
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDataNameByCode("GCSCX");
        $cpqrArr = unserialize(CBQRARR);
        foreach($exeDeptArr as $v){
            $predictArr['t1'] .= "
            �ɱ�ȷ��-->>�ɱ����: ".$dataDao->getDataNameByCode($v)." $cpqrArr[$v] δȷ��
            ";
            $predictArr['t2'] .= "
            �ɱ����-->>��ͬ����: ".$dataDao->getDataNameByCode($v)." $cpqrArr[$v] δ���
            ";
        }
        //��ͬ����
        $predictArr['t3'] .= "
            ��ͬ����-->>��ִͬ��:  ��ͬδ����
            ";

        return $predictArr;
    }

    /**
     * ����Ƿ��к���ͬһ�ź�ͬ�ڱ����²���Ĵ���ʱ�仹Ҫ�������,����������Ϊ��Щ���ⲽ�����һ����ʱ��
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
                $prevHandleNum = ($errorArr[0]['handleNum'] > 1)? ($errorArr[0]['handleNum'] - 1):  $errorArr[0]['handleNum'];// �������ݵ���һ���Ĵ�������
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

    //����δ������������(��һ��)
    function getSuppArr($num,$cid){
        //��ȡ��ǰ��ִ�еĲ���
        $sql = "select * from oa_contract_handle where contractId=$cid and isChange=$num order by handleNum desc limit 0,4";
        $arr = $this->_db->getArray($sql);
        $stepCode = $arr[0]['stepCode'];//��ǰ�������
        $prevObj = $arr[1];
        $prevTwoObj = $arr[2];
        //��Ϊ���ֽڵ������ж� ��ǰ���������ĸ���ڵ�
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
        // ����ǽ������������ȷ�ϴ�ص�,���̽���,����Ҫ��δ����������
        if($stepCode == "DHWLQR" && $prevObj['stepCode'] == 'WLQRBG'){
            $needStr = false;
        }
        if($needStr){
            switch($stepCode){
            case "HTLR" :
                $suppArr['s'] = "
                ��ͬ¼��-->>�ɱ�ȷ��: �ȴ��ύ"; break;
            case "HTBJ" :
                $suppArr['s'] = "
                ��ͬ¼��-->>�ɱ�ȷ��: �ȴ��ύ"; break;
            case "DHZXS" :
                $suppArr['s'] = "
                ��ͬ¼��-->>�ɱ�ȷ��: �ȴ��ύ"; break;
            case "SPDH" :
                $suppArr['s'] = "
                ��ͬ¼��-->>�ɱ�ȷ��: �ȴ��ύ"; break;
            case "WLQRBG" :
                $suppArr['s'] = "
                ����ȷ��-->>��ִͬ��: �ȴ����� (".$conArr['prinvipalName'].") ȷ������"; break;
            case "TJCBQR" :
                //�ɱ�ȷ��
//                $productLineStr =  rtrim($conArr['exeDeptStr'], ',');
//                $exeDeptArr = explode(",",$productLineStr);
//                $exeDeptArr = array_flip($exeDeptArr);
//                $exeDeptArr = array_flip($exeDeptArr);
//                foreach($exeDeptArr as $v){
//                    $suppArr['t1'] .= "
//            �ɱ�ȷ��-->>�ɱ����: ".$dataDao->getDataNameByCode($v)." $cpqrArr[$v] δȷ��
//            ";
//                    $suppArr['t2'] .= "
//            �ɱ����-->>��ͬ����: ".$dataDao->getDataNameByCode($v)." $cbsharr[$v] δ���
//            ";
//                };break;
                $suppArr['s'] = "
                �ɱ�ȷ��-->>����ȷ��: �ȴ�ȷ������"; break;

            case "WLQR" :
                $suppArr['s'] = "
                ����ȷ��-->>��ͬ����: �ȴ����� (".$conArr['prinvipalName'].") ȷ������";
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
//            �ɱ�ȷ��-->>�ɱ����: ".$dataDao->getDataNameByCode($v)." $cpqrArr[$v] δȷ��
//            ";
//                        $suppArr['s1'] .= "
//            �ɱ����-->>��ͬ����: ".$dataDao->getDataNameByCode($v)." $cpqrArr[$v] δ���
//            ";
//                    };
//                }
                break;
            case "DHWLQR" :
                    $suppArr['s'] = "
                �ɱ�ȷ��-->>����ȷ��: �ȴ�ȷ������"; break;
            case "XSWLQR" :
                if($num != 0){
                    $shsql="select * from oa_contract_cost where contractId=".$tid." and ExaState!=1";
                }else{
                    $shsql="select * from oa_contract_cost where contractId=".$cid." and ExaState!=1";
                }
                //��ѯ�����ϸ

                $shArr = $this->_db->getArray($shsql);
                if(!empty($shArr)){
                    foreach($shArr as $val){
                        $productLine = $val['productLine'];
                        if($val['issale'] == 1){
                            $suppArr['s'] .= "
                        ��ͬ����-->>�ύ����:".$val['productLineName']." ".$cbsharr[$productLine]." δ���";
                        }else{
                            $suppArr['s'] .= "
                        �ɱ����-->>�ύ����:".$val['productLineName']." ".$cbsharr[$productLine]." δ���";
                        }
                        break;
                    }
                }else{
                    $suppArr['s'] = "
                    �ɱ����-->>�ύ����: �ȴ��ɱ����";
                }
                break;
            case "FWCBQR" :
                $code = $arr[0]['stepInfo'];
                $suppArr['s'] = "
                �ɱ����-->>��ͬ����: ".$dataDao->getDataNameByCode($code)." ".$cbsharr[$code]." δ���"; break;
            case "ZXBMSH" :
                $suppArr['s'] = "
                ��ͬ����-->>��ִͬ��: �ȴ��ύ����"; break;
            case "ZXBMSHDH" :
                $suppArr['s'] = "
                �ɱ�ȷ��-->>�ɱ����: �ȴ��ύ���"; break;
            case "TJSP" :
                $userDao = new model_deptuser_user_user();
                //��������
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
                            ��ͬ����-->>��ִͬ��: ".$userNameArr['USER_NAME']." ".date('Y-m-d',strtotime($val['Endtime']))." ������� "; break;
                        }else if($val['Result'] != 'no'){
                            $suppArr['s'] .= "
                            ��ͬ����-->>��ִͬ��: ".$userNameArr['USER_NAME']." δ���� "; break;
                        }else if($prevObj['stepCode'] == 'FWCBQR' && $prevTwoObj['stepCode'] == 'TJCBQR' && $num == 0){// ���������,����һ�����ύ��ȷ�ϵ�,��û�ύ������,�Ǿ��ǽ�����ûȷ��
                            $suppArr['s'] = "
                            �ɱ�ȷ��-->>����ȷ��: �ȴ�ȷ������"; break;
                        }else if($prevObj['stepCode'] == 'FWCBQR' && $num == 0){// ���������,����һ���Ƿ���ɱ�ȷ��,���ύ����������,�Ǿ��ǵȴ�����ͨ��
                            $suppArr['s'] = "
                            �ɱ����-->>��ִͬ��: �ȴ�����ͨ��"; break;
                        }
                    }
                }else if($prevObj['stepCode'] == 'FWCBQR' && $num == 0){// ���������,����һ���Ƿ���ɱ�ȷ��,��û�ύ������,�Ǿ����������ϻ�ûȷ��
                    $suppArr['s'] = "
                            ����ȷ��-->>��ͬ����: �ȴ�����ȷ��"; break;
                }else{
                    $suppArr['s'] = "
                    ��ͬ����-->>��ִͬ��: �ȴ�����ͨ��"; break;
                }
        }
        }
        return $suppArr;
    }


}

?>