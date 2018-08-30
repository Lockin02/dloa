<?php
/**
 * @author HuangHj
 * @Date 2018年05月23日 星期三 11:43:46
 * @version 1.0
 * @description:阿里商旅费用相关业务对接 Model层
 */
class model_finance_expense_alibusinesstrip extends model_base
{
    function __construct()
    {
        $this->tbl_name = "oa_alibusinesstrip_localrecord";
        $this->sql_map = "finance/expense/alibusinesstripSql.php";
        parent:: __construct();
    }

    /**
     * 获取相关的阿里商旅的酒店订单信息
     * @param string $costManId
     * @param array $dateRange
     * @return array|mixed
     */
    function getAliTripHotelOrder($costManId = '',$dateRange = array(),$needTranscoding = true){
        include_once INC_DIR."/taobaoSDK/TopSdk.php";
        date_default_timezone_set('Asia/Shanghai');

        // 拼接应用相关信息
        $c = new TopClient;
        $c->appkey = ALITRIP_APPKEY;
        $c->secretKey = ALITRIP_APPSECRET;
        $c->gatewayUrl = 'https://eco.taobao.com/router/rest';
        // 拼接企业相关信息
        $corpId = ALITRIP_CORPID;
        $sessionKey = ALITRIP_SESSIONKEY;

        // 实例化相关函数
        $rq = new OpenSearchRq;
        $req = new AlitripBtripHotelOrderSearchRequest;

        // 拼接查询条件
        $rq->corp_id=$corpId;
        if(!empty($costManId)){
            $sql = "select userNo,userAccount,staffName from oa_hr_personnel where userAccount = '{$costManId}';";
            $userInfo = $this->_db->get_one($sql);
            if($userInfo && isset($userInfo['userNo'])){
                $rq->user_id = $userInfo['userNo'];
            }
        }
        if(!empty($dateRange)){
            $rq->start_time = (isset($dateRange['CostDateBegin']))? $dateRange['CostDateBegin'] : '2016-01-01';
            $rq->end_time = (isset($dateRange['CostDateEnd']))? $dateRange['CostDateEnd'] : date("Y-m-d");
        }
        $rq->page="0";
        $rq->page_size="100";

        $req->setRq(json_encode($rq));
        $resp = $c->execute($req, $sessionKey);

        // 返回数据整合
        $result = array();
        if($resp->result->success && $resp->result->success == "true"){
            $orderList = $resp->result->hotel_order_list;
            $orderList = $this->objToArray($orderList);
            $resultArr = (!empty($orderList['open_hotel_order_rs']))? $orderList['open_hotel_order_rs'] : array();
            foreach ($resultArr as $key => $row){
                $price = 0;
                $priceInfoJson = (isset($row['price_info_list']['open_price_info']))? json_encode($row['price_info_list']['open_price_info']) : '';
                if(isset($row['price_info_list']['open_price_info'][0]) && count($row['price_info_list']['open_price_info']) > 1){
                    foreach ($row['price_info_list']['open_price_info'] as $priceInfo){
                        $price += ($priceInfo['type'] == 1)? $priceInfo['price'] : -$priceInfo['price'];
                    }
                }else{
                    $price = $row['price_info_list']['open_price_info']['price'];
                }

                if($price > 0){
                    $result[] = array(
                        "id" => "hotel_".$row['id'],
                        "useNname" => $row['guest'],
                        "status" => 5,
                        "beginDate" => date("Y-m-d",strtotime($row['check_in'])),
                        "endDate" => date("Y-m-d",strtotime($row['check_out'])),
                        "category" => ($needTranscoding)? util_jsonUtil::iconvGB2UTF("酒店") : "酒店",
                        "description" => "{$row['hotel_name']}-{$row['city']}",
                        "mainName" => $row['hotel_name'],
                        "priceInfoJson" => $priceInfoJson,
                        "createUser" => $row['user_name'],
                        "createUserId" => $row['user_id'],
                        "createTime" => $row['gmt_create'],
                        "cost" => $price
                    );
                }
            }
        }else{
            $errorMsg = util_jsonUtil::iconvUTF2GB($resp->sub_msg);
            $errCnt = "{$resp->sub_code}: {$errorMsg}";
            error_log(date("Y-m-d H:i:s")."【 AlitripBtripHotelOrderSearchRequest 】 call error:".$errCnt."\n", 3, 'aliTripApi_error.log');
        }

        return $result;
    }

    /**
     * 获取相关的阿里商旅的机票订单信息
     * @param string $costManId
     * @param array $dateRange
     * @param bool $needTranscoding
     * @return array
     */
    function getAliTripFlightOrder($costManId = '',$dateRange = array(),$needTranscoding = true){
        include_once INC_DIR."/taobaoSDK/TopSdk.php";
        date_default_timezone_set('Asia/Shanghai');

        // 拼接应用相关信息
        $c = new TopClient;
        $c->appkey = ALITRIP_APPKEY;
        $c->secretKey = ALITRIP_APPSECRET;
        $c->gatewayUrl = 'https://eco.taobao.com/router/rest';

        // 拼接企业相关信息
        $corpId = ALITRIP_CORPID;
        $sessionKey = ALITRIP_SESSIONKEY;

        // 需要过滤的用户的名称
        $userName = '';
        if(!empty($costManId)){
            $sql = "select userNo,userAccount,staffName from oa_hr_personnel where userAccount = '{$costManId}';";
            $userInfo = $this->_db->get_one($sql);
            if($userInfo && isset($userInfo['staffName'])){
                $userName = $userInfo['staffName'];
            }
        }
        $userName = ($needTranscoding)? util_jsonUtil::iconvGB2UTF($userName) : $userName;

        // 实例化相关函数
        $rq = new OpenSearchRq;
        $req = new AlitripBtripFlightOrderSearchRequest;

        $rq->corp_id=$corpId;
        if(!empty($dateRange)){
            $rq->start_time = (isset($dateRange['CostDateBegin']))? $dateRange['CostDateBegin'] : '2016-01-01';
            $rq->end_time = (isset($dateRange['CostDateEnd']))? $dateRange['CostDateEnd'] : date("Y-m-d");
        }
        $rq->page = 0;
        $rq->page_size="100";

        $keepSearch = true;
        $result = array();
        while ($keepSearch){
            $rq->page += 1;
            $req->setRq(json_encode($rq));
            $resp = $c->execute($req, $sessionKey);
            if($resp->result->success && $resp->result->success == "true"){
                $orderList = $resp->result->flight_order_list;
                $orderList = $this->objToArray($orderList);
                $resultArr = (!empty($orderList['open_flight_order_rs']))? $orderList['open_flight_order_rs'] : array();
                foreach ($resultArr as $key => $row){
                    $depPlace = isset($row['dep_airport'])? $row['dep_airport'] : $row['dep_city'];
                    $arrPlace = isset($row['arr_airport'])? $row['arr_airport'] : $row['arr_city'];
                    $passengerNameArr = explode(",",$row['passenger_name']);// 以登记人为人员的过滤条件
                    $usernameLimit = empty($userName)? true : in_array($userName,$passengerNameArr);
                    if($row['status'] == '5' && $usernameLimit){
                        $zhiStr = iconv('gbk','utf-8', '至');
                        $price = 0;
                        $priceInfoJson = (isset($row['price_info_list']['open_price_info']))? json_encode($row['price_info_list']['open_price_info']) : '';
                        if(isset($row['price_info_list']['open_price_info'][0]) && count($row['price_info_list']['open_price_info']) > 1){
                            foreach ($row['price_info_list']['open_price_info'] as $priceInfo){
                                $price += ($priceInfo['type'] == 1)? $priceInfo['price'] : -$priceInfo['price'];
                            }
                        }else{
                            $price = $row['price_info_list']['open_price_info']['price'];
                        }

                        if($price > 0){
                            $result[] = array(
                                "id" => "flight_".$row['id'],
                                "useNname" => $row['passenger_name'],
                                "status" => $row['status'],
                                "beginDate" => date("Y-m-d",strtotime($row['dep_date'])),
                                "endDate" => date("Y-m-d",strtotime($row['ret_date'])),
                                "category" => ($needTranscoding)? util_jsonUtil::iconvGB2UTF("机票") : "机票",
                                "description" => "{$row['flight_no']}-{$depPlace}{$zhiStr}{$arrPlace}",
                                "mainName" => $row['flight_no'],
                                "priceInfoJson" => $priceInfoJson,
                                "createUser" => $row['user_name'],
                                "createUserId" => $row['user_id'],
                                "createTime" => $row['gmt_create'],
                                "cost" => $price,
                            );
                        }
                    }
                }
                $keepSearch = (count($resultArr) > 0);
            }else{
                $errorMsg = util_jsonUtil::iconvUTF2GB($resp->sub_msg);
                $errCnt = "{$resp->sub_code}: {$errorMsg}";
                error_log(date("Y-m-d H:i:s")."【 AlitripBtripFlightOrderSearchRequest 】 call error:".$errCnt."\n", 3, 'aliTripApi_error.log');
                $keepSearch = false;
            }
        }
        return $result;
    }

    /**
     * 获取相关的阿里商旅的火车票订单信息
     * @param string $costManId
     * @param array $dateRange
     * @param bool $needTranscoding
     * @return array
     */
    function getAliTripTrainOrder($costManId = '',$dateRange = array(),$needTranscoding = true){
        include_once INC_DIR."/taobaoSDK/TopSdk.php";
        date_default_timezone_set('Asia/Shanghai');

        // 拼接应用相关信息
        $c = new TopClient;
        $c->appkey = ALITRIP_APPKEY;
        $c->secretKey = ALITRIP_APPSECRET;
        $c->gatewayUrl = 'https://eco.taobao.com/router/rest';

        // 拼接企业相关信息
        $corpId = ALITRIP_CORPID;
        $sessionKey = ALITRIP_SESSIONKEY;

        // 需要过滤的用户的名称
        $userName = '';
        if(!empty($costManId)){
            $sql = "select userNo,userAccount,staffName from oa_hr_personnel where userAccount = '{$costManId}';";
            $userInfo = $this->_db->get_one($sql);
            if($userInfo && isset($userInfo['staffName'])){
                $userName = $userInfo['staffName'];
            }
        }
        $userName = ($needTranscoding)? util_jsonUtil::iconvGB2UTF($userName) : $userName;

        // 实例化相关函数
        $rq = new OpenSearchRq;
        $req = new AlitripBtripTrainOrderSearchRequest;

        $rq->corp_id=$corpId;
        if(!empty($dateRange)){
            $rq->start_time = (isset($dateRange['CostDateBegin']))? $dateRange['CostDateBegin'] : '2016-01-01';
            $rq->end_time = (isset($dateRange['CostDateEnd']))? $dateRange['CostDateEnd'] : date("Y-m-d");
        }
        $rq->page = 0;
        $rq->page_size="100";

        $keepSearch = true;
        $result = array();
        while ($keepSearch){
            $rq->page += 1;
            $req->setRq(json_encode($rq));
            $resp = $c->execute($req, $sessionKey);
            if($resp->result->success && $resp->result->success == "true"){
                $orderList = $resp->result->train_order_list;
                $orderList = $this->objToArray($orderList);
                $resultArr = (!empty($orderList['open_train_order_rs']))? $orderList['open_train_order_rs'] : array();
                foreach ($resultArr as $key => $row){
                    $depPlace = isset($row['dep_station'])? $row['dep_station'] : $row['dep_city'];
                    $arrPlace = isset($row['arr_station'])? $row['arr_station'] : $row['arr_city'];
                    $passengerNameArr = explode(",",$row['rider_name']);// 以登记人为人员的过滤条件
                    $usernameLimit = empty($userName)? true : in_array($userName,$passengerNameArr);
                    if($row['status'] == '5' && $usernameLimit){
                        $zhiStr = iconv('gbk','utf-8', '至');
                        $price = 0;
                        $priceInfoJson = (isset($row['price_info_list']['open_price_info']))? json_encode($row['price_info_list']['open_price_info']) : '';
                        if(isset($row['price_info_list']['open_price_info'][0]) && count($row['price_info_list']['open_price_info']) > 1){
                            foreach ($row['price_info_list']['open_price_info'] as $priceInfo){
                                $price += ($priceInfo['type'] == 1)? $priceInfo['price'] : -$priceInfo['price'];
                            }
                        }else{
                            $price = $row['price_info_list']['open_price_info']['price'];
                        }

                        if($price > 0){
                            $result[] = array(
                                "id" => "train_".$row['id'],
                                "useNname" => $row['rider_name'],
                                "status" => $row['status'],
                                "beginDate" => date("Y-m-d",strtotime($row['dep_time'])),
                                "endDate" => date("Y-m-d",strtotime($row['arr_time'])),
                                "category" => ($needTranscoding)? util_jsonUtil::iconvGB2UTF("火车") : "火车",
                                "description" => "{$row['train_number']}-{$depPlace}{$zhiStr}{$arrPlace}",
                                "mainName" => $row['train_number'],
                                "priceInfoJson" => $priceInfoJson,
                                "createUser" => $row['user_name'],
                                "createUserId" => $row['user_id'],
                                "createTime" => $row['gmt_create'],
                                "cost" => $price,
                            );
                        }
                    }
                }
                $keepSearch = (count($resultArr) > 0);
            }else{
                $errorMsg = util_jsonUtil::iconvUTF2GB($resp->sub_msg);
                $errCnt = "{$resp->sub_code}: {$errorMsg}";
                error_log(date("Y-m-d H:i:s")."【 AlitripBtripTrainOrderSearchRequest 】 call error:".$errCnt."\n", 3, 'aliTripApi_error.log');
                $keepSearch = false;
            }
        }
        return $result;
    }

    /**
     * stdClass Object转array
     * @param $obj
     * @return array|void
     */
    function objToArray($obj) {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)$this->objToArray($v);
            }
        }

        return $obj;
    }

    /**
     * 保存阿里商旅的数据到本地数据库
     */
    function saveAliTripDateToLocal_d()
    {
        $jsonUtilObj = new util_jsonUtil();
        $dateStart = "2017-01-01";
        $lastUpdateDate = $this->_db->get_one("select DATE_FORMAT(max(load_time),'%Y-%m-%d') as lastLoadDate from oa_alibusinesstrip_localrecord;");
        if($lastUpdateDate && isset($lastUpdateDate['lastLoadDate'])){
            $dateStart = date("Y-m-d",strtotime("{$lastUpdateDate['lastLoadDate']} -1 day"));
        }

        $dateRange = array("CostDateBegin" => $dateStart,"CostDateEnd" => date("Y-m-d"));
        $dataRows = $this->getAliTripHotelOrder('',$dateRange);// 酒店记录
        $flightDataRows = $this->getAliTripFlightOrder('',$dateRange);// 机票记录
        $trainDataRows =  $this->getAliTripTrainOrder('',$dateRange);// 火车票记录
        if(!empty($flightDataRows)){// 合并机票记录
            foreach ($flightDataRows as $row){
                $dataRows[] = $row;
            }
        }
        if(!empty($trainDataRows)){// 合并火车票记录
            foreach ($trainDataRows as $row){
                $dataRows[] = $row;
            }
        }

        if(count($dataRows) > 0){
            $dataRows = util_jsonUtil::iconvUTF2GBArr($dataRows);
            foreach ($dataRows as $key => $row){
                $userName = explode(",",$row['useNname']);
                $userId = "";
                // 排除重复的记录
                $record = $this->find(array("recordId" => $row['id']));
                if($record && count($record) > 0){
                    unset($dataRows[$key]);
                }else{
                    // 获取用户的ID
                    foreach ($userName as $uName){
                        $userInfo = $this->_db->get_one("select userAccount,userNo,userName from oa_hr_personnel where userName = '{$uName}'");
                        if($userInfo && isset($userInfo['userAccount'])){
                            $userId .= ($userId == "")? $userInfo['userAccount'] : ",".$userInfo['userAccount'];
                        }
                    }
                    $dataRows[$key]['userId'] = $userId;

                    // 写入记录表
                    $insertSql = <<<EOT
            insert into oa_alibusinesstrip_localrecord set
            recordId = '{$row['id']}',
            type = '{$row['category']}',
            start_date = '{$row['beginDate']}',
            end_date = '{$row['endDate']}',
            mainName = '{$row['mainName']}',
            costAmount = '{$row['cost']}',
            payInfoJson = '{$row['priceInfoJson']}',
            record_man = '{$row['useNname']}',
            record_manId = '{$userId}',
            create_man = '{$row['createUser']}',
            create_manNo = '{$row['createUserId']}',
            create_time = '{$row['createTime']}',
            description = '{$row['description']}',
            status = '{$row['status']}',load_time=NOW();
EOT;
                $this->_db->query($insertSql);
                }
            }
        }
        // echo count($dataRows);
        // echo "<pre>";print_r($dataRows);exit();

        return true;
    }

    function searchLocalAliDataForGrid_d($userId = '',$dateRange = '')
    {
        $userCont = ($userId != '')? " and find_in_set('{$userId}',record_manId) > 0" : "";
        $dateRangeCont = (!empty($dateRange))? " and (start_date >= '{$dateRange['CostDateBegin']}' and end_date <= '{$dateRange['CostDateEnd']}')" : "";
        $recordData = $this->findAll("id > 0 {$userCont} {$dateRangeCont}","start_date");
        $backArray = array();
        if($recordData){
            foreach ($recordData as $row){
                $backArray[] = array(
                    "useNname" => $row['record_man'],
                    "beginDate" => date("Y-m-d",strtotime($row['start_date'])),
                    "endDate" => date("Y-m-d",strtotime($row['end_date'])),
                    "category" => $row['type'],
                    "description" => $row['description'],
                    "cost" => $row['costAmount'],
                );
            }
        }
        return $backArray;
    }

    /**
     * 输出html
     * @param $rows
     * @return string
     */
    function searchAliGridHtml_d($rows)
    {
        $headerStr = "<tr class=\"main_tr_header\"><th>序号</th><th>姓名</th><th>开始日期</th><th>结束日期</th><th>类别</th><th>金额</th><th>详细内容</th></tr>";
        $headerStr = util_jsonUtil::iconvGB2UTF($headerStr);
        if ($rows) {
            $html = '<table class="main_table"><thead>'.$headerStr.'</thead><tbody>';
            $i = 0;
            foreach ($rows as $v) {
                if($v['id'] != "totalSum"){
                    $i++;
                    $html .= "<tr class='tr_even'><td>$i</td>";
                }else{
                    $html .= "<tr class='tr_even'><td></td>";
                }
                $html .= "<td>$v[useNname]</td><td>$v[beginDate]</td><td>$v[endDate]</td><td>$v[category]</td><td class='formatMoney'>$v[cost]</td><td>$v[description]</td></tr>";
            }
            return $html . '</tbody></table>';
        } else {
            return util_jsonUtil::iconvGB2UTF('没有查询到数据');
        }
    }
}