<?php
/**
 * @author tse
 * @Date 2014年1月4日 9:24:00
 * @version 1.0
 * @description:工程附件表 Model层 
 */
class model_engineering_file_esmfile extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_file";
		$this->sql_map = "engineering/file/esmfileSql.php";
		parent::__construct ();
	}
	/**
	 * 上传附件
	 */
	function uploadFileAction($geyFile, $upload) {
		//设置这三个文件为全局变量，使其可以在引入的附件类中调用
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
			/*******组合SQL数组***********/
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
	 * 通过附件id下载附件
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
	 * 根据路径下载文件
	 */
	function downFileByPath($path, $DOC_ID,$DOC_NAME) {
		$local=$path.$DOC_ID;
		/**  2010-10-07 修改结束 **/
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
			echo "<script>alert('下载的文件不存在');history.back();</script>";
		}
	}
	
	/**
	 * 根据文件名和文件夹名删除文件
	 */
	function delFile($serviceType, $filename) {
		$delpath = UPLOADPATH . $serviceType . '/' . $filename;
		if (is_file($delpath)) {
			unlink($delpath);
		}
	}
	
}