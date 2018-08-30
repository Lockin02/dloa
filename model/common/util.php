<?php
/**
 * @description: 常用函数类
 * @date 2010-9-21 上午10:10:49
 * @author oyzx
 * @version V1.0
 */
class model_common_util {

	/**
	 * @desription 构造函数
	 * @date 2010-9-21 上午10:13:27
	 */
	function __construct() {

	}

	/**
	 * @desription 2个数组合并
	 * @param $array1 数组1
	 * @param $array2 数组2
	 * @return $Rows 整合的数组
	 * @date 2010-9-21 上午10:03:43
	 */
	static function yx_array_merge($array1 = null, $array2 = null) {
		$Rows = null;
		if (is_array ( $array1 ) && is_array ( $array2 )) {
			$Rows = array_merge ( $array1, $array2 );
		} else if (is_array ( $array1 )) {
			$Rows = $array1;
		} else if (is_array ( $array2 )) {
			$Rows = $array2;
		}
		return $Rows;
	}

	/**
	 * 根据指定的字符个数，截取GBK字符串保存于数组中返回（中英混合）
	 *
	 * @param string $str 字符串 (可中英混合),编码必须为GBK
	 * @param int $count 字符个数
	 * @return array array[0]:截取的字符；array[1]:剩下的字符
	 */
	function subWordInArray($str, $count) {
		$tmpArray = array ();
		if ($count == 0) {
			$tmpArray [] = '';
			$tmpArray [] = $str;
			return $tmpArray;
		}
		$len = strlen ( $str );
		$tmp = '';
		$n = 0; //个数初始值
		for($i = 0; $i < $len; $i ++) {
			if (ord ( substr ( $str, $i, 1 ) ) > 0xa0) {
				$tmp .= substr ( $str, $i, 2 );
				$i ++;
			} else {
				$tmp .= substr ( $str, $i, 1 );
			}
			$n ++;
			if ($n == $count) {
				$tmpArray [] = $tmp;
				$tmpArray [] = substr ( $str, $i + 1 );
				break;
			}
		} //end for
		//判断$n<$count的情况
		if ($n < $count) {
			$tmpArray [0] = $str;
			$tmpArray [1] = '';
		}
		return $tmpArray;
	}

	function mb_str_split( $string,$split_length,$encoding='gbk' ){
		//设置字符集编码
		mb_internal_encoding($encoding);
		//初始化截取偏移量
		$offset=0;
		//如果剩余的字符串的长度大于零
		while(strlen($string)){
			//截取到的字符串
			$mb_strcut = mb_strcut( $string,0,$split_length );
			//截取到的字符串的长度
			$offset = strlen( $mb_strcut );
			//剩余的字符串
			$string = mb_strcut( $string,$offset );
			//返回一个数组元素
			$return[] = $mb_strcut;
		}
		$returnStr = implode ( "<br />", $return );
		//返回一个值
		return $returnStr;
	}

}
?>
