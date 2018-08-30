<?php
/**
 * @author tse
 * @Date 2014��1��4�� 9:24:00
 * @version 1.0
 * @description:���̸����� Model�� 
 */
class model_engineering_file_esmfile extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_file";
		$this->sql_map = "engineering/file/esmfileSql.php";
		parent::__construct ();
	}
	/**
	 * �ϴ�����
	 */
	function uploadFileAction($geyFile, $upload) {
		//�����������ļ�Ϊȫ�ֱ�����ʹ�����������ĸ������е���
		global $file;
		global $file_name;
		global $file_size;
		$file = $geyFile['file']['tmp_name'];
		$file_name = $geyFile['file']['name'];
		$file_size = $geyFile['file']['size'];
		$uploadPath = str_replace("\\","/",UPLOADPATH) . $upload['serviceType'] . '/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath);
		}
		include (WEB_TOR . 'includes/upload_class.php');
		$upfile = new NdUpload();
		$upfile->nd_max_filesize = 1024 * 1024 * 100;
		$upfile->nd_arr_ext_accepted = array (
				'.zip','.rar','.jpg','.gif','.bmp','.doc','.chm',
				'.docx','.txt','.xls','.xlsx','.pdf','.psd','.et',
				'.ppt','.png','.eml','.JEPG','.7z'
		);
		$upfile->setDir($uploadPath);
		$upfile->copyFile();
		$upfile->showError();
		$errorCode = '';
		if ($upfile->getErrCode() != 6) {
			throw new Exception($upfile->showError());
		}else{
			/*******���SQL����***********/
			$fileArr['projectId'] = $upload['projectId'];
			$fileArr['typeId'] = $upload['typeId'];
			$fileArr['typeName'] = $upload['typeName'];
			if (util_jsonUtil :: is_utf8($file_name)) {
				$file_name = util_jsonUtil :: iconvUTF2GB($file_name);
			}
			$fileArr['originalName'] = $file_name;
			$fileArr['newName'] = $upfile->getFinalName();
			$fileArr['tFileSize'] = $upfile->nd_filesize;
			$fileArr['uploadPath'] = $uploadPath;
			$fileArr['isUsing'] = 1;
			$id = $this->add_d($fileArr, true);
			$fileArr['id'] = $id;
			return $fileArr;
		}
	}
	
	/*
	 * ͨ������id���ظ���
	*/
	function downFileById($id) {
		$file = $this->get_d($id);
	
		$fileLocal = $file['uploadPath'].$file['newName'];
		if(fopen($fileLocal, "r")){
			$this->downFileByPath($file['uploadPath'], $file['newName'], $file['originalName']);
		}else{
			$local= SPECIALPATH.$file['serviceType'].'/';
			$this->downFileByPath($local, $file['newName'], $file['originalName']);
		}
// 		$this->downFile2($file['typeName'],$file['newName'],$file['originalName']);
	}
	
	/**
	 * ����·�������ļ�
	 */
	function downFileByPath($path, $DOC_ID,$DOC_NAME) {
		$local=$path.$DOC_ID;
		/**  2010-10-07 �޸Ľ��� **/
		if (fopen($local, "r")) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($DOC_NAME));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($local));
			ob_clean();
			flush();
			readfile($local);
			exit ();
		} else {
			echo "<script>alert('���ص��ļ�������');history.back();</script>";
		}
	}
	
	/**
	 * �����ļ������ļ�����ɾ���ļ�
	 */
	function delFile($serviceType, $filename) {
		$delpath = UPLOADPATH . $serviceType . '/' . $filename;
		if (is_file($delpath)) {
			unlink($delpath);
		}
	}
	
}