<?php

 ! defined ( 'IN_INTER' ) && exit ( 'Fobbiden!' );
/**
 * ================================================================================
 * ���ļ��д�������˿�ʢ���루�������Ƽ����޹�˾Discuz!/UCenter�Ĵ��롣�������Э��Ĺ涨��
 * ����ֹ�� Discuz! / UCenter ��������κβ��ֻ������Է�չ�κ������汾���޸İ汾��������汾�������·ַ�����
 * ���ڴ��������£�
 * �������Ϊ����ѧϰ���о�����ں������˼���ԭ�����������ӯ��ΪĿ�ģ�ͬʱҲ�����ַ��������������/��˾��Ȩ�档
 * �����ַ�Ȩ�棬�뷢�ʼ���֪���ڱ��˽ӻ�֪ͨ��48Сʱ֮�ڽ�����Լ��������Ĵ�����г��ز�����
 * ͬʱ���ѵ����������ߺ�ʹ����ʹ����Щ����ʱ���Ǳ�����ķ��ɷ��գ������������ߺ�ʹ���ߵ�һ����Ϊ�뱾���޹ء�
 * 
 * Discuz!/UCenterͷ�ļ�ע�ͣ�   
 * (C)2001-2009 Comsenz Inc.
 * This is NOT a freeware, use is subject to license terms
 * ================================================================================
 * 
 * flashͷ���ϴ��࣬������UCenter
 * ���ļ��Ĳο������³����ڴ�һ����л��
 * - Comsenz UCenter {@link http://www.comsenz.com}
 * - Comsenz Discuz!NT {@link http://nt.discuz.net}
 *
 * @author Horse Luke<horseluke@126.com>      
 * @license Mixed License. See the description above. 
 * @version $Id: AvatarFlashUpload.php 156 2010-07-22 01:25:53Z horseluke@126.com $
 */

class Controller_AvatarFlashUpload extends Controller_Base
{
	
	/**
	 * ���캯����(ok)  
	 * 
	 */
	public function __construct ( )
	{
		parent :: __construct ( );
	}
	
	/**
	 * ��ȡ��ʾ�ϴ�flash�Ĵ���(ok)
	 * ��Դ��Ucenter��uc_avatar����
	 * �����ԣ�
	 * �߼�������Ϊ���������common�ࣻʵ�ʲ����л�����������ļ�/�����
	 * - Ucenter��ͷ���ϴ�flash�ļ���swf�ļ���
	 */
	public function showuploadAction ( )
	{
		$uid = common :: getgpc ( 'uid' , 'G' );
		if ( $uid === null || $uid == 0 )
		{
			return iconv ( 'gb2312' , 'utf-8' , '<div class="easyui-layout" fit="true">
				<div id="f" region="center" border="false" style="padding:10px;background:#fff;border:1px solid #ccc;vertical-align:middle; text-align:center;">
				����ϵ����Ա
				</div>
				<div region="south" border="false" style="text-align:center;height:50px;line-height:30px;vertical-align:middle; margin-top:5px;">
				<input type="button" onclick="$(\'#photos\').window(\'close\');" value="ȡ���ر�" /></div>
			</div>' );
		}
		$returnhtml = common :: getgpc ( 'returnhtml' , 'G' );
		if ( $returnhtml === null )
		{
			$returnhtml = 1;
		}
		
		$uc_input = urlencode ( common :: authcode ( 'uid=' . $uid . '&agent=' . md5 ( $_SERVER[ 'HTTP_USER_AGENT' ] ) . "&time=" . time ( ) , 'ENCODE' , $this -> config -> authkey ) );
		
		$uc_avatarflash = $this -> config -> uc_api . '/images/camera.swf?nt=1&inajax=1&input=' . $uc_input . '&agent=' . md5 ( $_SERVER[ 'HTTP_USER_AGENT' ] ) . '&ucapi=' . urlencode ( $this -> config -> uc_api . substr ( $_SERVER[ 'PHP_SELF' ] , strrpos ( $_SERVER[ 'PHP_SELF' ] , '/' ) ) ) . '&uploadSize=' . $this -> config -> uploadsize;
		if ( $returnhtml == 1 )
		{
			$result = "<script type='text/javascript'>
						function updateavatar() {
						    window.location.reload();
					   }
					   </script>";
			$result .= '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="450" height="253" id="mycamera" align="middle">
			<param name="allowScriptAccess" value="always" />
			<param name="scale" value="exactfit" />
			<param name="wmode" value="transparent" />
			<param name="quality" value="high" />
			<param name="bgcolor" value="#ffffff" />
			<param name="movie" value="' . $uc_avatarflash . '" />
			<param name="menu" value="false" />
			<embed src="' . $uc_avatarflash . '" quality="high" bgcolor="#ffffff" width="450" height="253" name="mycamera" align="middle" allowScriptAccess="always" allowFullScreen="false" scale="exactfit"  wmode="transparent" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
		</object>';
			return $result;
		} else
		{
			return array ( 
						'width' , 
						'450' , 
						'height' , 
						'253' , 
						'scale' , 
						'exactfit' , 
						'src' , 
						$uc_avatarflash , 
						'id' , 
						'mycamera' , 
						'name' , 
						'mycamera' , 
						'quality' , 
						'high' , 
						'bgcolor' , 
						'#ffffff' , 
						'wmode' , 
						'transparent' , 
						'menu' , 
						'false' , 
						'swLiveConnect' , 
						'true' , 
						'allowScriptAccess' , 
						'always' 
			);
		}
	}
	
	/**
	 * ͷ���ϴ���һ�����ϴ�ԭ�ļ�����ʱ�ļ��У�ok��
	 *
	 * @return string
	 */
	function uploadavatarAction ( )
	{
		header ( "Expires: 0" );
		header ( "Cache-Control: private, post-check=0, pre-check=0, max-age=0" , FALSE );
		header ( "Pragma: no-cache" );
		//header("Content-type: application/xml; charset=utf-8");
		$this -> init_input ( common :: getgpc ( 'agent' , 'G' ) );
		$uid = $this -> input ( 'uid' );
		if ( empty ( $uid ) )
		{
			return  - 1;
		}
		if ( empty ( $_FILES[ 'Filedata' ] ) )
		{
			return  - 3;
		}
		
		$imgext = strtolower ( '.' . common :: fileext ( $_FILES[ 'Filedata' ][ 'name' ] ) );
		if (  ! in_array ( $imgext , $this -> config -> imgtype ) )
		{
			unlink ( $_FILES[ 'Filedata' ][ 'tmp_name' ] );
			return  - 2;
		}
		
		if ( $_FILES[ 'Filedata' ][ 'size' ] > ( $this -> config -> uploadsize * 1024 ) )
		{
			unlink ( $_FILES[ 'Filedata' ][ 'tmp_name' ] );
			return 'Inage is TOO BIG, PLEASE UPLOAD NO MORE THAN ' . $this -> config -> uploadsize . 'KB';
		}
		
		list ( $width , $height , $type , $attr ) = getimagesize ( $_FILES[ 'Filedata' ][ 'tmp_name' ] );
		
		$filetype = $this -> config -> imgtype[ $type ];
		$tmpavatar = realpath ( $this -> config -> tmpdir ) . '/upload' . $uid . $filetype;
		file_exists ( $tmpavatar ) && unlink ( $tmpavatar );
		if ( is_uploaded_file ( $_FILES[ 'Filedata' ][ 'tmp_name' ] ) && move_uploaded_file ( $_FILES[ 'Filedata' ][ 'tmp_name' ] , $tmpavatar ) )
		{
			list ( $width , $height , $type , $attr ) = getimagesize ( $tmpavatar );
			if ( $width < 10 || $height < 10 || $type == 4 )
			{
				unlink ( $tmpavatar );
				return  - 2;
			}
		} else
		{
			unlink ( $_FILES[ 'Filedata' ][ 'tmp_name' ] );
			return  - 4;
		}
		
		$avatarurl = $this -> config -> uc_api . '/' . $this -> config -> tmpdir . '/upload' . $uid . $filetype;
		
		return $avatarurl;
	}
	
	/**
	 * ͷ���ϴ��ڶ������ϴ���ͷ��洢λ��
	 *
	 * @return string
	 */
	function rectavatarAction ( )
	{
		header ( "Expires: 0" );
		header ( "Cache-Control: private, post-check=0, pre-check=0, max-age=0" , FALSE );
		header ( "Pragma: no-cache" );
		header ( "Content-type: application/xml; charset=utf-8" );
		$this -> init_input ( common :: getgpc ( 'agent' ) );
		$uid = $this -> input ( 'uid' );
		if ( empty ( $uid ) || 0 == $uid )
		{
			return '<root><message type="error" value="-1" /></root>';
		}
		
		$avatarpath = $this -> get_avatar_path ( $uid );
		$avatarrealdir = realpath ( $this -> config -> avatardir . DIRECTORY_SEPARATOR . $avatarpath );
		if (  ! is_dir ( $avatarrealdir ) )
		{
			$this -> make_avatar_path ( $uid , realpath ( $this -> config -> avatardir ) );
		}
		$avatartype = common :: getgpc ( 'avatartype' , 'G' ) == 'real' ? 'real' : 'virtual';
		
		$avatarsize = array ( 
							1 => 'big' , 
							2 => 'middle' , 
							3 => 'small' 
		);
		
		$success = 1;
		
		foreach ( $avatarsize as $key => $size )
		{
			$avatarrealpath = realpath ( $this -> config -> avatardir ) . DIRECTORY_SEPARATOR . $this -> get_avatar_filepath ( $uid , $size , $avatartype );
			$avatarcontent = $this -> _flashdata_decode ( common :: getgpc ( 'avatar' . $key , 'P' ) );
			if (  ! $avatarcontent )
			{
				$success = 0;
				return '<root><message type="error" value="-2" /></root>';
				break;
			}
			$writebyte = file_put_contents ( $avatarrealpath , $avatarcontent , LOCK_EX );
			if ( $writebyte <= 0 )
			{
				$success = 0;
				return '<root><message type="error" value="-2" /></root>';
				break;
			}
			$avatarinfo = getimagesize ( $avatarrealpath );
			if (  ! $avatarinfo || $avatarinfo[ 2 ] == 4 )
			{
				$this -> clear_avatar_file ( $uid , $avatartype );
				$success = 0;
				break;
			}
		}
		
		//ԭuc bugfix  gif/png�ϴ�֮����ɾ��
		foreach ( $this -> config -> imgtype as $key => $imgtype )
		{
			$tmpavatar = realpath ( $this -> config -> tmpdir . '/upload' . $uid . $imgtype );
			file_exists ( $tmpavatar ) && unlink ( $tmpavatar );
		}
		
		if ( $success )
		{
			return '<?xml version="1.0" ?><root><face success="1"/></root>';
		} else
		{
			return '<?xml version="1.0"  encoding="gb2312"?><root><face success="0"/></root>';
		}
	}
	
	/**
	 * flash data decode
	 * ��Դ��Ucenter
	 * 
	 * @param string $s
	 * @return unknown
	 */
	protected function _flashdata_decode ( $s )
	{
		$r = '';
		$l = strlen ( $s );
		for ( $i = 0 ; $i < $l ; $i = $i + 2 )
		{
			$k1 = ord ( $s[ $i ] ) - 48;
			$k1 -= $k1 > 9 ? 7 : 0;
			$k2 = ord ( $s[ $i + 1 ] ) - 48;
			$k2 -= $k2 > 9 ? 7 : 0;
			$r .= chr ( $k1 << 4 | $k2 );
		}
		return $r;
	}
	
	/**
	 * ��ȡָ��uid��ͷ��淶���Ŀ¼��ʽ
	 * ��Դ��Ucenter base���get_home����
	 * 
	 * @param int $uid uid���
	 * @return string ͷ��淶���Ŀ¼��ʽ
	 */
	public function get_avatar_path ( $uid )
	{
		// $uid = sprintf("%09d", $uid);
		//$dir1 = substr($uid, 0, 3);
		// $dir2 = substr($uid, 3, 2);
		// $dir3 = substr($uid, 5, 2);
		return $uid; //$dir1.'/'.$dir2.'/'.$dir3;
	}
	
	/**
	 * ��ָ��Ŀ¼�ڣ�����uid����ָ����ͷ��淶���Ŀ¼
	 * ��Դ��Ucenter base���set_home����
	 * 
	 * @param int $uid uid���
	 * @param string $dir ��Ҫ���ĸ�Ŀ¼������
	 */
	public function make_avatar_path ( $uid , $dir = '.' )
	{
		// $uid = sprintf("%09d", $uid);
		//$dir1 = substr($uid, 0, 3);
		//$dir2 = substr($uid, 3, 2);
		// $dir3 = substr($uid, 5, 2);
		//!is_dir($dir.'/'.$dir1) && mkdir($dir.'/'.$dir1, 0777);
		//!is_dir($dir.'/'.$dir1.'/'.$dir2) && mkdir($dir.'/'.$dir1.'/'.$dir2, 0777);
		//!is_dir($dir.'/'.$dir1.'/'.$dir2.'/'.$dir3) && mkdir($dir.'/'.$dir1.'/'.$dir2.'/'.$dir3, 0777);
		 ! is_dir ( $dir . '/' . $uid ) && mkdir ( $dir . '/' . $uid , 0777 );
	
	}
	
	/**
	 * ��ȡָ��uid��ͷ���ļ��淶·��
	 * ��Դ��Ucenter base���get_avatar����
	 *
	 * @param int $uid
	 * @param string $size ͷ��ߴ磬��ѡΪ'big', 'middle', 'small'
	 * @param string $type ���ͣ���ѡΪreal����virtual
	 * @return unknown
	 */
	public function get_avatar_filepath ( $uid , $size = 'big' , $type = '' )
	{
		$size = in_array ( $size , array ( 
											'big' , 
											'middle' , 
											'small' 
		) ) ? $size : 'big';
		//$uid = abs ( intval ( $uid ) );
		//$uid = sprintf ( "%09d" , $uid );
		//$dir1 = substr ( $uid , 0 , 3 );
		//$dir2 = substr ( $uid , 3 , 2 );
		//$dir3 = substr ( $uid , 5 , 2 );
		$typeadd = $type == 'real' ? '_real' : '';
		return $uid . '/' . substr ( $uid ,  - 2 ) . $typeadd . "_avatar_$size.jpg";
	}
	
	/**
	 * һ�������ָ��uid�û��Ѿ��洢��ͷ��
	 *
	 * @param int $uid
	 */
	public function clear_avatar_file ( $uid )
	{
		$avatarsize = array ( 
							1 => 'big' , 
							2 => 'middle' , 
							3 => 'small' 
		);
		$avatartype = array ( 
							'real' , 
							'virtual' 
		);
		foreach ( $avatarsize as $size )
		{
			foreach ( $avatartype as $type )
			{
				$avatarrealpath = realpath ( $this -> config -> avatardir ) . DIRECTORY_SEPARATOR . $this -> get_avatar_filepath ( $uid , $size , $type );
				file_exists ( $avatarrealpath ) && unlink ( $avatarrealpath );
			}
		}
		return true;
	}

}