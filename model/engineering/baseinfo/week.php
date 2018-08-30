<?php
/**
 * @author HaoJin
 * @Date 2017��04��12�� ������ 11:00:59
 * @version 1.0
 * @description: ��Ŀ�ܴ�(oa_esm_baseinfo_budget) Model��
 */
class model_engineering_baseinfo_week extends model_base {

    function __construct() {
        $this->tbl_name = "oa_esm_baseinfo_week";
        $this->sql_map = "engineering/baseinfo/weekSql.php";
        parent::__construct ();
    }

    /**
     * ���ݴ������ڻ�ȡ��Ӧ���ܴ���Ϣ
     *
     * @param $year
     * @param $beginDate
     * @param $endDate
     * @param $sort
     * @return array
     */
    function getAllWeeks($year,$beginDate,$endDate,$sort = "asc"){
        $datesArr = array();
        // ��ȡ�ܴ�
        $esmtoolDao = new model_engineering_util_esmtoolutil();
        $weekArr = $esmtoolDao->findWeekNo($beginDate, $endDate);

        // �����ܴλ�ȡ��Ӧ�Ŀ�ʼ���������
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

        // �������������ʽ
        $newArr = array();$catchEndDate = array();
        $yearSortStr = mb_substr($year,2,2);
        foreach ($datesArr as $k => $v){
            if(!in_array($v['endDate'],$catchEndDate) && date("Y",$v['endDatetamp']) == $year && date("m",$v['beginDatetamp']) >= mb_substr($beginDate,5,2)){
                $weekNum = ceil(substr_replace($v['week'],'',0,2));// ��ֹ�ܴ�Ϊ��λ����ʱ��ȱ��0���º����ܴ����������
                $weekNumFormat = ($weekNum < 10)? '0'.$weekNum : $weekNum;
                $catchEndDate[] = $v['endDate'];
                $weekNum = $yearSortStr.$weekNumFormat;
                $datesArr[$k]['weekNo'] = $weekNum;
                $datesArr[$k]['longWeekNo'] = $year.$weekNumFormat;
                $newArr[$weekNum] = $datesArr[$k];
            }
        }

        // ��������
        switch($sort){
            case 'desc':
                krsort($newArr);// ����
                break;
            default:
                ksort($newArr);// ����
                break;
        }
        return $newArr;
    }

    /**
     * ���ݴ�������������ȡ�����������е��ܴα��
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
     * ���ݴ���ʱ�����ȡ��Ӧ���ܴα��
     * Ĭ��ȡ�����ʱ���
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
     * ���ݴ����������ܴλ�ȡ���ܵĿ�ʼ���������
     * Ĭ��ȡ����������������
     *
     * @param null $week [������ݵ��ܴ�,���һ��Ϊ1,�ڶ���Ϊ2,������Ϊ3]
     * @param null $year
     * @return mixed
     */
    function getWeekRange($week = null, $year = null) {
        //��ʼ����Ӧֵ
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
     * ���ݴ�����ܴα�Ż�ȡ��Ӧ������Լ����ܴα���ڴ����ڵ��ܴ�
     *
     * @param $weekTimes �ܴα��
     * @param bool $isShort [true: �����д,false: �������]
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
     * ��ȡ�ڼ�����������
     *
     * @param $begin
     * @param $end
     * @return array
     */
    function getAllDays($begin, $end) {
        $allDays = array();
        $beginTime = strtotime($begin);
        $endTime = strtotime($end);

        // ȥ��ȫ��
        for ($i = $beginTime; $i <= $endTime; $i += 86400) {
            $allDays[] = date('Y-m-d', $i);
        }
        return $allDays;
    }

    /**
     *
     * ����һ��ģ����ڼ�����
     * @param $beginDate
     * @param $endDate
     * @param string $sort
     * @return array
     */
    function buildDateData($beginDate, $endDate, $sort = 'asc') {
        $begin = strtotime($beginDate);
        $end = strtotime($endDate);
        $data = array();

        //����
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
     * �������ڣ���������
     *
     * @param null $beginDate
     * @param null $endDate
     * @return float|int
     */
    function dateDiff($beginDate = null, $endDate = null) {
        if ($beginDate == '0000-00-00' || $beginDate == null) return 0;
        $beginDateStamp = strtotime($beginDate);
        $endDateStamp = $endDate && $endDate != '0000-00-00' ? strtotime($endDate) : strtotime(day_date); //��������ʱ���
        return ($endDateStamp - $beginDateStamp) / 86400 - 1;
    }
}