<?php

/**
 * Created on 2012-7-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_engineering_util_esmtoolutil
{

    //��ȡ������Ŀ�ܴ�
    public static function getEsmWeekNo($date = day_date) {
        $timestamp = strtotime($date);
        if (date('m', $timestamp) == 12 && date('W', $timestamp) == 1) {
            $weekNo = date('y') . '53';
        } else {
            $weekNo = date('yW', $timestamp);
        }
        return $weekNo; //��ȡִ�������ܴ�
    }

    //��ȡ�ܵĿ�ʼ���ںͽ�������
    public static function getWeekDate($week = null, $year = null) {
        //��ʼ����Ӧֵ
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

    //�����ܴβ�ѯ��ʼ��������
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

    //�������ڣ����������ڵ�������
    public static function findWeekNo($beginDate, $endDate, $sort = 'asc') {
        $beginTimestamp = strtotime($beginDate);
        $endTimestamp = strtotime($endDate);
        $weekArr = array();

        if ($sort == "asc") {//����
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
        } else {//����
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

    //�������ڣ���������
    public static function dateDiff($beginDate = null, $endDate = null) {
        if ($beginDate == '0000-00-00' || $beginDate == null) return 0;
        $beginDateStamp = strtotime($beginDate);
        $endDateStamp = $endDate && $endDate != '0000-00-00' ? strtotime($endDate) : strtotime(day_date); //��������ʱ���
        return ($endDateStamp - $beginDateStamp) / 86400 - 1;
    }

    /********************************** ��Ŀ�ܱ����⴦�� ******************************/
    /**
     * ��ȡ�ܵĿ�ʼ���ںͽ������� - ����Խ
     * �����߼�Ϊ����ǰ�ܴο�Խ������ʱ�������еı��²��ַ��뵽���ܣ��¸��µĲ��ַ��뵽��һ��
     * @param null $week
     * @param null $year
     * @return array
     */
    public static function getWeekDateUncrossMonth($week = null, $year = null) {
        // ��ʼ����Ӧֵ
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
        $month = date('m', $beginTimestamp); // ��ʼ���ڵ���
        $day = date('d', $beginTimestamp); // ��ʼ���ڵ���
        // ������ܵ�һ�����1��С��14������Ϊ��һ��Ϊ�µ���,�����µĵ�һ�ܶ�Ϊ���ܵ�һ��
        if ($day < 8 && $month > 1) {
            $firstDateOfWeek = date('Y-m-d', $beginTimestamp - ($day - 1) * 86400);
        }

        $distance = $week == 1 ? 7 - $dayOfWeek : $distance = 6;
        $endTimestamp = $beginTimestamp + $distance * 86400;
        $lastDateOfWeek = date("Y-m-d", $endTimestamp);
        $nextMonth = date('m', $endTimestamp); // �������ڵ���
        // ����ܵĵ�һ����ܵ����һ���·ݲ�һ�£����ܵ����һ�춨Ϊ���µ����һ��
        if ($month != $nextMonth) {
            $lastDateOfWeek = date("Y-m-d", mktime(23, 59, 59, $month, date("t", $beginTimestamp), $year));
        }

        return array('beginDate' => $firstDateOfWeek, 'endDate' => $lastDateOfWeek);
    }

	/**
	 * ��ȡ�ܵĿ�ʼ���ںͽ������� - ����Խ
	 * �����߼�Ϊ����ǰ�ܴο�Խ������ʱ�������еı��²��ַ��뵽���ܣ��¸��µĲ��ַ��뵽��һ��
	 * @param $begin
	 * @param $end
	 * @return array
	 */
	public static function getAllDays($begin, $end) {
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
     * @param $beginDate
     * @param $endDate
     * @param $sort
     * @return array
     */
    public static function findEsmRealWeekNo($beginDate, $endDate, $sort = 'asc') {
        // ��ѯ��������
        $weekArr = self::findWeekNo($beginDate, $endDate);

        // ��ʵ������
        $realWeekNo = array();

        // ���ڴ������˵����������ڷ�Χ�ڵ��ܴ�
        foreach ($weekArr as $v) {
            $weekDate = self::findWeekDate($v, true);
            $beginEndDate = self::getWeekDateUncrossMonth($weekDate['week'], $weekDate['year']);

            // ����ܴεĽ������ڴ��ڿ�ʼ���ڣ����ܴο�ʼ����С�ڽ�������
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
     * ����һ��ģ����ڼ�����
     * @param $beginDate
     * @param $endDate
     * @param string $sort
     * @return array
     */
    public static function buildDateData($beginDate, $endDate, $sort = 'asc') {
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
}