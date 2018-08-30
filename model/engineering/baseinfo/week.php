<?php
/**
 * @author HaoJin
 * @Date 2017年04月12日 星期三 11:00:59
 * @version 1.0
 * @description: 项目周次(oa_esm_baseinfo_budget) Model层
 */
class model_engineering_baseinfo_week extends model_base {

    function __construct() {
        $this->tbl_name = "oa_esm_baseinfo_week";
        $this->sql_map = "engineering/baseinfo/weekSql.php";
        parent::__construct ();
    }

    /**
     * 根据传入日期获取对应的周次信息
     *
     * @param $year
     * @param $beginDate
     * @param $endDate
     * @param $sort
     * @return array
     */
    function getAllWeeks($year,$beginDate,$endDate,$sort = "asc"){
        $datesArr = array();
        // 获取周次
        $esmtoolDao = new model_engineering_util_esmtoolutil();
        $weekArr = $esmtoolDao->findWeekNo($beginDate, $endDate);

        // 根据周次获取相应的开始与结束日期
        foreach ($weekArr as $wk => $wv){
            $arr = array();
            $arr['week'] = $wv;
            $weekNum = substr_replace($wv,"",0,2);
            $catch = $esmtoolDao->getWeekDateUncrossMonth($weekNum,$year);
            $arr['beginDate'] = $catch['beginDate'];
            $arr['beginDatetamp'] = strtotime($catch['beginDate']);
            $arr['endDate'] = $catch['endDate'];
            $arr['endDatetamp'] = strtotime($catch['endDate']);
            $datesArr[] = $arr;
        }

        // 重新整理数组格式
        $newArr = array();$catchEndDate = array();
        $yearSortStr = mb_substr($year,2,2);
        foreach ($datesArr as $k => $v){
            if(!in_array($v['endDate'],$catchEndDate) && date("Y",$v['endDatetamp']) == $year && date("m",$v['beginDatetamp']) >= mb_substr($beginDate,5,2)){
                $weekNum = ceil(substr_replace($v['week'],'',0,2));// 防止周次为单位数的时候缺少0导致后面周次排序出问题
                $weekNumFormat = ($weekNum < 10)? '0'.$weekNum : $weekNum;
                $catchEndDate[] = $v['endDate'];
                $weekNum = $yearSortStr.$weekNumFormat;
                $datesArr[$k]['weekNo'] = $weekNum;
                $datesArr[$k]['longWeekNo'] = $year.$weekNumFormat;
                $newArr[$weekNum] = $datesArr[$k];
            }
        }

        // 数组排序
        switch($sort){
            case 'desc':
                krsort($newArr);// 将序
                break;
            default:
                ksort($newArr);// 升序
                break;
        }
        return $newArr;
    }

    /**
     * 根据传入的日期区间获取该区间内所有的周次编号
     *
     * @param $beginDate
     * @param $endDate
     * @param string $sort
     * @return array
     */
    function findEsmRealWeekNo($beginDate,$endDate,$sort = "asc"){
        $startWeekNo = self::getWeekNoByDayTimes($beginDate);
        $endWeekNo = self::getWeekNoByDayTimes($endDate);
        $resultArr = array();

        if($startWeekNo != '' && $endWeekNo != ''){
            $sql = "select * from oa_esm_baseinfo_week where weekNo >= {$startWeekNo} and weekNo <= {$endWeekNo} order by weekNo ".$sort;
            $dateWeek = $this->_db->getArray($sql);
            if($dateWeek && !empty($dateWeek)){
                foreach ($dateWeek as $v){
                    $resultArr[] = $v['weekNo'];
                }
            }
        }
        return $resultArr;
    }

    /**
     * 根据传入时间戳获取对应的周次编号
     * 默认取当天的时间戳
     *
     * @param bool|string $date
     * @return string
     */
    function getWeekNoByDayTimes($date = day_date){
        $timestamp = strtotime($date);
        $dateWeek = $this->find("beginTime <= {$timestamp} and endTime >= {$timestamp}");
        return isset($dateWeek['weekNo'])? $dateWeek['weekNo'] : '';
    }

    /**
     * 根据传入的年份与周次获取该周的开始与结束日期
     * 默认取当天所属的周与年
     *
     * @param null $week [传入年份的周次,如第一周为1,第二周为2,第三周为3]
     * @param null $year
     * @return mixed
     */
    function getWeekRange($week = null, $year = null) {
        //初始化对应值
        $week = ($week < 10)? '0'.$week : $week;
        $year = empty($year) ? date('Y') : $year;
        $weekNo = empty($week) ? date('yW', strtotime(day_date)) : mb_substr($year,2,2).$week;
        $longWeekNo = empty($week) ? date('YW', strtotime(day_date)) : $year.$week;

        $weekArr = $this->find("weekNo = '{$weekNo}' and longWeekNo = '{$longWeekNo}'");
        $weekRange['beginDate'] = ($weekArr && !empty($weekArr))? $weekArr['beginDate'] : '';
        $weekRange['endDate'] = ($weekArr && !empty($weekArr))? $weekArr['endDate'] : '';
        return $weekRange;
    }

    /**
     * 根据传入的周次编号获取对应的年份以及该周次编号在此年内的周次
     *
     * @param $weekTimes 周次编号
     * @param bool $isShort [true: 年份缩写,false: 完整年份]
     * @return array
     */
    function findWeekDate($weekTimes, $isShort = false){
        $weekArr = $this->find("weekNo = '{$weekTimes}'");
        if($weekArr && !empty($weekArr)){
            if($isShort){
                $resultArr['year'] =  mb_substr($weekArr['weekNo'],0,2);
                $resultArr['week'] = ceil(mb_substr($weekArr['weekNo'],2,2));
                $resultArr['weekTimes'] = $weekTimes;
            }else{
                $resultArr['year'] =  mb_substr($weekArr['longWeekNo'],0,4);
                $resultArr['week'] = ceil(mb_substr($weekArr['weekNo'],2,2));
                $resultArr['weekTimes'] = $weekTimes;
            }
            return $resultArr;
        }else{
            return array();
        }
    }

    /**
     * 获取期间内所有日期
     *
     * @param $begin
     * @param $end
     * @return array
     */
    function getAllDays($begin, $end) {
        $allDays = array();
        $beginTime = strtotime($begin);
        $endTime = strtotime($end);

        // 去除全部
        for ($i = $beginTime; $i <= $endTime; $i += 86400) {
            $allDays[] = date('Y-m-d', $i);
        }
        return $allDays;
    }

    /**
     *
     * 返回一个模拟的期间数据
     * @param $beginDate
     * @param $endDate
     * @param string $sort
     * @return array
     */
    function buildDateData($beginDate, $endDate, $sort = 'asc') {
        $begin = strtotime($beginDate);
        $end = strtotime($endDate);
        $data = array();

        //升序
        if ($sort == "asc") {
            for ($i = $begin; $i <= $end; $i += 86400) {
                $thisDate = date('Y-m-d', $i);
                $data[] = array(
                    'id' => $thisDate,
                    'executionDate' => $thisDate,
                    'executionTimes' => $i
                );
            }
        } else {
            for ($i = $end; $i >= $begin; $i -= 86400) {
                $thisDate = date('Y-m-d', $i);
                $data[] = array(
                    'id' => $thisDate,
                    'executionDate' => $thisDate,
                    'executionTimes' => $i
                );
            }
        }
        return $data;
    }

    /**
     * 传入日期，返回天数
     *
     * @param null $beginDate
     * @param null $endDate
     * @return float|int
     */
    function dateDiff($beginDate = null, $endDate = null) {
        if ($beginDate == '0000-00-00' || $beginDate == null) return 0;
        $beginDateStamp = strtotime($beginDate);
        $endDateStamp = $endDate && $endDate != '0000-00-00' ? strtotime($endDate) : strtotime(day_date); //结束日期时间戳
        return ($endDateStamp - $beginDateStamp) / 86400 - 1;
    }
}