<?php

/**
 * BI的新签月增量表
 * Class model_bi_exsummary_conproductMonth
 */
class model_bi_exsummary_conproductMonth extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_bi_conproduct_month";
        $this->sql_map = "bi/exsummary/conproductMonthSql.php";
        parent::__construct();
    }

    /**
     * 将BI上的新签数据更新到OA上
     */
    function updateDataFromBi(){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        // $currentData = $this->find("updateDate > 0");

        // 如果OA的数据非当天更新的,重新更新一遍数据
        // if(!$currentData || date("Ymd",$currentData['updateDate']) != date("Ymd")){
            // 清除原来的数据
            $this->delete();

            // 从BI获取新的数据并插入到本地
            $this->getBiData();
        //}
    }

    /**
     * 获取BI当天的最新数据
     * @return array
     */
    function getBiData(){
        $server = '172.16.1.176';
        $uid = 'dloa';
        $pwd = 'dloa176';
        $database = 'dl_oa';
        $todayTime = time();
        $conn =mssql_connect($server,$uid,$pwd) or die ("myssql connect failed!");
        mssql_select_db($database,$conn);
        $query ="select * from oa_d_conproduct_month order by contractDate desc";
        $resultRow =mssql_query($query);
        $num = 0;
        while($list=mssql_fetch_array($resultRow))
        {
            $row = array();
            $row['biId'] = $list['id'];$row['businessCode'] = $list['businessCode'];$row['comCode'] = $list['comCode'];$row['deptId'] = $list['deptId'];$row['contractType'] = $list['contractType'];$row['contractCode'] = $list['contractCode'];$row['contractId'] = $list['contractId'];$row['contractName'] = $list['contractName'];$row['conProductId'] = $list['conProductId'];$row['proLineCode'] = $list['proLineCode'];$row['productId'] = $list['productId'];$row['productName'] = $list['productName'];
            $row['price'] = $list['price'];$row['num'] = $list['num'];$row['areaCode'] = $list['areaCode'];$row['provinceId'] = $list['provinceId'];$row['cityId'] = $list['cityId'];$row['storeYear'] = $list['storeYear'];$row['storeMon'] = $list['storeMon'];$row['contractAllMoney'] = $list['contractAllMoney'];$row['contractMoney'] = $list['contractMoney'];$row['rateMoney'] = $list['rateMoney'];$row['serviceCost'] = $list['serviceCost'];$row['preCost'] = $list['preCost'];$row['cost'] = $list['cost'];$row['earnings'] = $list['earnings'];$row['incomeMoney'] = $list['incomeMoney'];
            $row['contractDate'] = $list['contractDate'];$row['contractPer'] = $list['contractPer'];$row['chanceCode'] = $list['chanceCode'];$row['customerType'] = $list['customerType'];$row['productTypeId'] = $list['productTypeId'];$row['prinvipalId'] = $list['prinvipalId'];$row['isSaleType'] = $list['isSaleType'];$row['surincomeMoney'] = $list['surincomeMoney'];$row['storeDate'] = $list['storeDate'];$row['updateDate'] = $todayTime;
            $result = $this->add_d($row);
            $num += ($result)? 1 : 0;
        }
        mssql_free_result($resultRow);
        mssql_close($conn);
        return $num;
    }
}