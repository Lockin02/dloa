<?php
/**
 * @author Show
 * @Date 2011年9月22日 星期四 10:30:00
 * @version 1.0
 * @description:到款邮件记录 Model层
 */
class model_finance_income_mailrecord extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_income_mailrecord";
		$this->sql_map = "finance/income/mailrecordSql.php";
		parent::__construct ();
    }

    /**
     * 到款记录邮寄
     */
    function mailTask_d(){
    	$sql = "select c.id ,c.sendIds ,c.copyIds ,c.content ,c.title,c.times from oa_finance_income_mailrecord c where c.status = 0 and DATE_FORMAT(now(),'%Y%m%d%h') - DATE_FORMAT(lastMailTime,'%Y%m%d%h') >= " . INCOMEMAIL;
		$rs = $this->listBySql($sql);
		if(is_array($rs)){
			$mailDao = new model_common_mail();
			$updateTime = date('Y-m-d H:i:s');
			foreach($rs as $key => $val){
				$mailDao->mailClear($val['title'],$val['sendIds'],$val['content'],$val['copyIds']);
				$val['times'] = $val['times'] + 1;
				$val['lastMailTime'] = $updateTime;
				$this->edit_d($val);
			}
		}
    }

    /**
     * 获取是否有定时任务运行
     */
    function getHasTimetask_d(){
		$timeTaskDao = new model_common_timeTask();
		return $timeTaskDao->application('hasTimeTask');
    }

    /**
     * 将关联的到款记录的到款邮件改成关闭状态
     */
    function closeMailrecordByIncomeId_d($incomeId,$status = 1){
		$this->update(array('incomeId' => $incomeId),array('status' => $status));
    }
}
?>