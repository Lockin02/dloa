<?php


/**
 *
 * ��ʱ����model
 * @author chengl
 *
 */
class model_common_timeTask extends model_base {

	function __construct() {
		parent :: __construct();
		include_once (WEB_TOR."model/common/timeTaskRegister.php");
		if (isset ($timeTaskRegister)) {
			$this->timeTaskRegister = $timeTaskRegister;
		} else {
			return;
		}
	}

	/**
	 * ������ʱ����
	 */
	function startTimeTask() {
		//��ʹClient�Ͽ�(��ص������)��PHP�ű�Ҳ���Լ���ִ��.
		ignore_user_abort();
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);
		// ÿ��1��������
		$i = 0;
		//$this->email->send ( "ttt", "cc", array ("[" . myFrom . "]" => "���ֽ�ADMIN" ) );
		$timeTaskRegister = $this->timeTaskRegister;

		$cacheArr = array();

		do{
			foreach ($timeTaskRegister as $key => $val) {
				//���ö��󻺴�
				if(!isset($cacheArr[$key])){
					$class = $val[0];
					$cacheArr[$key] =  new $class ();
				}
				$method = $val[1];
				$interval = $val[2];
				$cacheArr[$key]-> $method ();
				if($i!=0){
					sleep ( $interval );
				}
				$i++;
			}
		}while ($this->application('hasTimeTask')==1);
	}

	function application()
	{
		$args = func_get_args(); //��ȡ�������
		if (count($args) >2 || count($args) < 1) return;
		$ssid = session_id(); //���浱ǰsession_id
		session_write_close(); //������ǰsession
		ob_start(); //��ֹȫ��session����header
		session_id("xxx"); //ע��ȫ��session_id
		session_start(); //����ȫ��session
		$key = $args[0];
		if (count($args) == 2) //����еڶ�����������ô��ʾд��ȫ��session
		{
			$re = ($_SESSION[$key] = $args[1]);
		}
		else // ���ֻ��һ����������ô���ظò�����Ӧ��value
		{
			$re = $_SESSION[$key];
		}
		session_write_close(); //����ȫ��session
		session_id($ssid); //����ע�����汻�жϵķ�ȫ��session
		session_start(); //���¿���
		ob_end_clean(); //�����ո�����session_start������һЩheader���
		return $re;
	}
}
?>