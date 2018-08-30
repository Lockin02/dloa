<?php


/**
 *
 * 定时任务model
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
	 * 开启定时任务
	 */
	function startTimeTask() {
		//即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
		ignore_user_abort();
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);
		// 每隔1分钟运行
		$i = 0;
		//$this->email->send ( "ttt", "cc", array ("[" . myFrom . "]" => "名字叫ADMIN" ) );
		$timeTaskRegister = $this->timeTaskRegister;

		$cacheArr = array();

		do{
			foreach ($timeTaskRegister as $key => $val) {
				//配置对象缓存
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
		$args = func_get_args(); //获取输入参数
		if (count($args) >2 || count($args) < 1) return;
		$ssid = session_id(); //保存当前session_id
		session_write_close(); //结束当前session
		ob_start(); //禁止全局session发送header
		session_id("xxx"); //注册全局session_id
		session_start(); //开启全局session
		$key = $args[0];
		if (count($args) == 2) //如果有第二个参数，那么表示写入全局session
		{
			$re = ($_SESSION[$key] = $args[1]);
		}
		else // 如果只有一个参数，那么返回该参数对应的value
		{
			$re = $_SESSION[$key];
		}
		session_write_close(); //结束全局session
		session_id($ssid); //重新注册上面被中断的非全局session
		session_start(); //重新开启
		ob_end_clean(); //抛弃刚刚由于session_start产生的一些header输出
		return $re;
	}
}
?>