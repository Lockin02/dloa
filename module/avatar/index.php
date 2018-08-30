<?php
/**
 * ��������ļ������ļ����ļ��������Ҹġ�
 * �ο������³����ڴ�һ����л��
 * - Comsenz UCenter {@link http://www.comsenz.com}
 *
 * @author Horse Luke<horseluke@126.com>
 * @license the Apache License, Version 2.0 (the "License"). {@link http://www.apache.org/licenses/LICENSE-2.0}
 * @version $Id: upload.php 156 2010-07-22 01:25:53Z horseluke@126.com $
 */

//������
$config = array ( 
				'tmpdir' => 'tempUpload' ,  //��ʱ�ļ��У�����ڱ��ļ���λ�ö��ԣ�����ͷ�ͽ�β�벻Ҫ�ӷ�б��
				'avatardir' => '../../attachment/avatars' ,  //�洢ͷ����ļ��У�����ڱ��ļ���λ�ö��ԣ�����ͷ�ͽ�β�벻Ҫ�ӷ�б��
				'authkey' => 'safsdfsda5643dgsdfgrew' ,  //ͨѶ��Կ��������д������ű��޷����У�
				'debug' => true ,  //����debug��¼��
				'uploadsize' => 1024 ,  //�ϴ�ͼƬ�ļ������ֵ����λ��KB
				'imgtype' => array ( 
										1 => '.gif' , 
										2 => '.jpg' , 
										3 => '.png' 
				)  //�����ϴ������ͣ������޸Ĵ˴����ã����������ȫ�������⣡
);
//�ű�������
//�������п�ʼ
define ( 'IN_INTER' , true );
define ( 'SYSTEM_PATH' , dirname ( __FILE__ ) . '/Lib' );

//���������
if ( true === $config[ 'debug' ] )
{
	set_exception_handler ( array ( 
									'Inter_Error' , 
									'exception_handler' 
	) );
	set_error_handler ( array ( 
								'Inter_Error' , 
								'error_handler' 
	) , E_ALL );
	Inter_Error :: $conf[ 'debugMode' ] = false;
	Inter_Error :: $conf[ 'logType' ] = 'simple';
	Inter_Error :: $conf[ 'logDir' ] = dirname ( __FILE__ ) . '/Log';

	//Inter_Error::$conf['logDir'] = 'R:\TEMP';
} else
{
	error_reporting ( 0 );
}

//��ȡ��������
if (  ! isset ( $_GET[ 'a' ] ) || empty ( $_GET[ 'a' ] ) ||  ! is_string ( $_GET[ 'a' ] ) )
{
	$action = 'showuploadAction';
} else
{
	$action = $_GET[ 'a' ] . 'Action';
}

//��Ϊ�������ֻ��һ��������������ֱ��ʵ������
$controller = new Controller_AvatarFlashUpload ( );
$controller -> config -> set ( $config );
//���иýű�����ַ�������ű����ƣ����洢��config��
$controller -> config -> uc_api = ( isset ( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] == 'on' ? 'https' : 'http' ) . '://' . $_SERVER[ 'HTTP_HOST' ] . ( $_SERVER[ 'SERVER_PORT' ] == '80' ? '' : ':' . $_SERVER[ 'SERVER_PORT' ] ) . substr ( $_SERVER[ 'SCRIPT_NAME' ] , 0 , strrpos ( $_SERVER[ 'SCRIPT_NAME' ] , '/' ) );

//���п�����ָ���Ķ���


if ( method_exists ( $controller , $action ) )
{
	/*
    if(method_exists($controller, $action.'Before')){
        $controller->$action.'Before';
    }
    */
	$result = $controller -> $action ( );
	/*
    if(method_exists($controller, $action.'After')){
        $controller->$action.'After';
    }
    */
	if ( is_array ( $result ) )
	{
		echo json_encode ( $result );
	} else
	{
		echo $result;
	}
} else
{
	exit ( 'NO ACTION FOUND!' );
}

/**
 * php 5.0�������Զ�����ģʽ
 * ����php�汾����5.0�������ڴ��ڵ���5.1.2�������û�п���spl_autoload_register����ʹ�ô�__autoload����ִ��֮��
 * �����������ڣ�
 * - ����SYSTEM_PATH��������Ŀ¼
 * @param string $classname ��ѭ��������������class����
 */
function __autoload ( $classname )
{
	$path = SYSTEM_PATH . DIRECTORY_SEPARATOR . str_replace ( '_' , DIRECTORY_SEPARATOR , $classname ) . '.php';
	require ( $path );
}