<?php
/**
 * ��ʱ����ע����
 */
$timeTaskRegister = array (
		//��һ��������ִ�е�����
		//�ڶ���������ִ�еķ�����
		//������������˯��ʱ�� 60ָ˯��һ����
		//���ĸ��������Ƿ�ִֻ��һ�� 1��ִֻ��һ��
		"income" => array(
			"model_finance_income_mailrecord","mailTask_d",120,1
		),
		"rdtask"=>array(
			"model_rdproject_task_rdtask","sendMailForExpiredTask",60,1
		)
);
?>