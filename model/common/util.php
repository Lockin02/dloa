<?php
/**
 * @description: ���ú�����
 * @date 2010-9-21 ����10:10:49
 * @author oyzx
 * @version V1.0
 */
class model_common_util {

	/**
	 * @desription ���캯��
	 * @date 2010-9-21 ����10:13:27
	 */
	function __construct() {

	}

	/**
	 * @desription 2������ϲ�
	 * @param $array1 ����1
	 * @param $array2 ����2
	 * @return $Rows ���ϵ�����
	 * @date 2010-9-21 ����10:03:43
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
	 * ����ָ�����ַ���������ȡGBK�ַ��������������з��أ���Ӣ��ϣ�
	 *
	 * @param string $str �ַ��� (����Ӣ���),�������ΪGBK
	 * @param int $count �ַ�����
	 * @return array array[0]:��ȡ���ַ���array[1]:ʣ�µ��ַ�
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
		$n = 0; //������ʼֵ
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
		//�ж�$n<$count�����
		if ($n < $count) {
			$tmpArray [0] = $str;
			$tmpArray [1] = '';
		}
		return $tmpArray;
	}

	function mb_str_split( $string,$split_length,$encoding='gbk' ){
		//�����ַ�������
		mb_internal_encoding($encoding);
		//��ʼ����ȡƫ����
		$offset=0;
		//���ʣ����ַ����ĳ��ȴ�����
		while(strlen($string)){
			//��ȡ�����ַ���
			$mb_strcut = mb_strcut( $string,0,$split_length );
			//��ȡ�����ַ����ĳ���
			$offset = strlen( $mb_strcut );
			//ʣ����ַ���
			$string = mb_strcut( $string,$offset );
			//����һ������Ԫ��
			$return[] = $mb_strcut;
		}
		$returnStr = implode ( "<br />", $return );
		//����һ��ֵ
		return $returnStr;
	}

}
?>
