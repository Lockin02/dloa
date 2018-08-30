<?php

/**
 * Created on 2012-7-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_engineering_util_esmtoolutil
{

    //获取工程项目周次
    public static function getEsmWeekNo($date = day_date) {
        $timestamp = strtotime($date);
        if (date('m', $timestamp) == 12 && date('W', $timestamp) == 1) {
            $weekNo = date('y') . '53';
        } else {
            $weekNo = date('yW', $timestamp);
        }
        return $weekNo; //获取执行日期周次
    }

    //获取周的开始日期和结束日期
    public static function getWeekDate($week = null, $year = null) {
        //初始化对应值
        $week = empty($week) ? $week = date('W', strtotime(day_date)) : $week;
        $year = empty($year) ? date('Y') : $year;

        $timestamp = mktime(0, 0, 0, 1, 1, $year);
        $dayofweek = date("w", $timestamp);
        $distance = 0;
        if ($week != 1)
            $distance = ($week - 1) * 7 - $dayofweek + 1;
        $passed_seconds = $distance * 86400;
        $timestamp += $passed_seconds;
        $firt_date_of_week = date("Y-m-d", $timestamp);
        if ($week == 1)
            $distance = 7 - $dayofweek;
        else
            $distance = 6;
        $timestamp += $distance * 86400;
        $last_date_of_week = date("Y-m-d", $timestamp);
        return array('beginDate' => $firt_date_of_week, 'endDate' => $last_date_of_week);
    }

    //根据周次查询开始结束日期
    public static function findWeekDate($weekTimes, $isShort = false) {
        $year = substr($weekTimes, 0, 2);
        $newYear = $isShort == true ? '20' . substr($weekTimes, 0, 2) : substr($weekTimes, 0, 2);
        $week = substr($weekTimes, 2, 3);

        if ($week > 53) {
            $newYear = intval($newYear) + 1;
            $year = intval($year) + 1;
            $week = 1;
            $weekTimes = $year . '0' . $week;
        } elseif ($week == 0) {
            $newYear = $newYear - 1;
            $year = $year - 1;
            $week = 53;
            $weekTimes = $year . $week;
        }

        return array('year' => $newYear, 'week' => $week, 'weekTimes' => $weekTimes);
    }

    //传入日期，返回日期内的所有周
    public static function findWeekNo($beginDate, $endDate, $sort = 'asc') {
        $beginTimestamp = strtotime($beginDate);
        $endTimestamp = strtotime($endDate);
        $weekArr = array();

        if ($sort == "asc") {//升序
			if (date('y', strtotime($endDate)) == 16) {
				$endTimestamp = $endTimestamp + 7 * 86400;
			}
            for ($i = $beginTimestamp; $i <= $endTimestamp; $i += 86400) {
				if (date('m', $i) == 12 && date('W', $i) == 1) {
					$weekNo = date('y', $i) . 53;
				} else if (date('m', $i) == 1 && date('W', $i) == 53) {
					$weekNo = date('y', $i) - 1 . 53;
                } else if (date('m', $i) == 1 && date('W', $i) == 1) {
                    $weekNo = date('y', $i) - 1 . 53;
                } else {
					$weekNo = date('yW', $i);
				}
                !in_array($weekNo, $weekArr) && array_push($weekArr, $weekNo);

                if (date('m', $i) == 1 && date('W', $i) == 1) {
                    !in_array(date('y', $i) . '01', $weekArr) && array_push($weekArr, date('y', $i) . '01');
                }
            }
        } else {//降序
			if (date('y', strtotime($endDate)) == 16) {
				$endTimestamp = $endTimestamp + 7 * 86400;
			}
            for ($i = $endTimestamp; $i >= $beginTimestamp; $i -= 86400) {
                if (date('m', $i) == 12 && date('W', $i) == 1) {
                    $weekNo = date('y', $i) . 53;
                } else if (date('m', $i) == 1 && date('W', $i) == 53) {
					$weekNo = date('y', $i) - 1 . 53;
                } else if (date('m', $i) == 1 && date('W', $i) == 1) {
                    $weekNo = date('y', $i) - 1 . 53;
                } else {
                    $weekNo = date('yW', $i);
                }
                !in_array($weekNo, $weekArr) && array_push($weekArr, $weekNo);

                if (date('m', $i) == 1 && date('W', $i) == 1) {
                    !in_array(date('y', $i) . '01', $weekArr) && array_push($weekArr, date('y', $i) . '01');
                }
            }
        }

        return $weekArr;
    }

    //传入日期，返回天数
    public static function dateDiff($beginDate = null, $endDate = null) {
        if ($beginDate == '0000-00-00' || $beginDate == null) return 0;
        $beginDateStamp = strtotime($beginDate);
        $endDateStamp = $endDate && $endDate != '0000-00-00' ? strtotime($endDate) : strtotime(day_date); //结束日期时间戳
        return ($endDateStamp - $beginDateStamp) / 86400 - 1;
    }

    /********************************** 项目周报特殊处理 ******************************/
    /**
     * 获取周的开始日期和结束日期 - 不跨越
     * 处理逻辑为：当前周次跨越两个月时，将其中的本月部分放入到本周，下个月的部分放入到下一周
     * @param null $week
     * @param null $year
     * @return array
     */
    public static function getWeekDateUncrossMonth($week = null, $year = null) {
        // 初始化对应值
        $week = empty($week) ? $week = date('W', strtotime(day_date)) : $week;
        $year = empty($year) ? date('Y') : $year;

        $beginTimestamp = mktime(0, 0, 0, 1, 1, $year);
        $dayOfWeek = date("w", $beginTimestamp);
        $distance = 0;
        if ($week != 1)
            $distance = ($week - 1) * 7 - $dayOfWeek + 1;
        $passed_seconds = $distance * 86400;

        $beginTimestamp += $passed_seconds;
        $firstDateOfWeek = date("Y-m-d", $beginTimestamp);
        $month = date('m', $beginTimestamp); // 开始日期的月
        $day = date('d', $beginTimestamp); // 开始日期的日
        // 如果本周第一天大于1且小于14，则视为上一周为月底周,将本月的第一周定为本周第一天
        if ($day < 8 && $month > 1) {
            $firstDateOfWeek = date('Y-m-d', $beginTimestamp - ($day - 1) * 86400);
        }

        $distance = $week == 1 ? 7 - $dayOfWeek : $distance = 6;
        $endTimestamp = $beginTimestamp + $distance * 86400;
        $lastDateOfWeek = date("Y-m-d", $endTimestamp);
        $nextMonth = date('m', $endTimestamp); // 结束日期的月
        // 如果周的第一天和周的最后一天月份不一致，将周的最后一天定为本月的最后一天
        if ($month != $nextMonth) {
            $lastDateOfWeek = date("Y-m-d", mktime(23, 59, 59, $month, date("t", $beginTimestamp), $year));
        }

        return array('beginDate' => $firstDateOfWeek, 'endDate' => $lastDateOfWeek);
    }

	/**
	 * 获取周的开始日期和结束日期 - 不跨越
	 * 处理逻辑为：当前周次跨越两个月时，将其中的本月部分放入到本周，下个月的部分放入到下一周
	 * @param $begin
	 * @param $end
	 * @return array
	 */
	public static function getAllDays($begin, $end) {
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
     * @param $beginDate
     * @param $endDate
     * @param $sort
     * @return array
     */
    public static function findEsmRealWeekNo($beginDate, $endDate, $sort = 'asc') {
        // 查询已有年月
        $weekArr = self::findWeekNo($beginDate, $endDate);

        // 真实的周期
        $realWeekNo = array();

        // 日期处理，过滤掉不符合日期范围内的周次
        foreach ($weekArr as $v) {
            $weekDate = self::findWeekDate($v, true);
            $beginEndDate = self::getWeekDateUncrossMonth($weekDate['week'], $weekDate['year']);

            // 如果周次的结束日期大于开始日期，且周次开始日期小于结束日期
            if (strtotime($beginEndDate['endDate']) >= strtotime($beginDate) &&
                strtotime($beginEndDate['beginDate']) <= strtotime($endDate)) {
                $realWeekNo[] = $v;
            }
        }

        if ($sort == 'desc') {
            return array_reverse($realWeekNo);
        }

        return $realWeekNo;
    }

    /**
     *
     * 返回一个模拟的期间数据
     * @param $beginDate
     * @param $endDate
     * @param string $sort
     * @return array
     */
    public static function buildDateData($beginDate, $endDate, $sort = 'asc') {
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
}