<?php
/**
 * 定时任务注册器
 */
$timeTaskRegister = array (
		//第一个参数：执行的类名
		//第二个参数：执行的方法名
		//第三个参数：睡眠时间 60指睡眠一分钟
		//第四个参数：是否只执行一次 1则只执行一次
		"income" => array(
			"model_finance_income_mailrecord","mailTask_d",120,1
		),
		"rdtask"=>array(
			"model_rdproject_task_rdtask","sendMailForExpiredTask",60,1
		)
);
?>