<?php
class model_engineering_file_uploadfile extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_uploadfile";
		$this->sql_map = "engineering/file/uploadfileSql.php";
		parent::__construct ();
	}

	/**
	 * $upload �������ĸ�����������
	 * ������
	 * $serviceId:ҵ�����id
	 * $serviceNo:ҵ��������
	 * $serviceType��ҵ��������ͣ�ͨ��ȡҵ����������
	 * $typeId������Id
	 * $typeName :��������
	 */
	function uploadFileAction($geyFile, $upload) {
		$file = $geyFile ['file'] ['tmp_name'];
		$file_name = $geyFile ['file'] ['name'];
		$file_size = $geyFile ['file'] ['size'];
		$uploadPath = UPLOADPATH_ESM . $upload ['serviceType'] . '/';
		if (! is_dir ( $uploadPath )) {
			mkdir ( $uploadPath );
		}
		include (WEB_TOR . 'includes/upload_class.php');
		$upfile = new NdUpload ();
		$upfile->nd_max_filesize = 1024 * 1024 * 50;
		$upfile->nd_arr_ext_accepted = array ('.zip', '.rar', '.jpg', '.gif', '.bmp', '.doc', '.chm', '.docx', '.txt', '.xls', '.xlsx', '.pdf','.psd' );
		$upfile->setDir ( $uploadPath );
		$upfile->copyFile ();
		$upfile->showError ();
		//		    print_r($geyFile);
		if ($upfile->getErrCode () != "6") {
			throw new exception ();
		}
		/*******���SQL����***********/
		$fileArr ['serviceType'] = $upload ['serviceType'];
		$fileArr ['serviceId'] = $upload ['serviceId'];
		$fileArr ['serviceNo'] = $upload ['serviceNo'];
		$fileArr ['typeId'] = $upload ['typeId'];
		$fileArr ['typeName'] = $upload ['typeName'];
		if (util_jsonUtil::is_utf8 ( $file_name )) {
			$file_name = util_jsonUtil::iconvUTF2GB ( $file_name );
		}
		$fileArr ['originalName'] = $file_name;
		$fileArr ['newName'] = $upfile->getFinalName ();
		$fileArr ['tFileSize'] = $upfile->nd_filesize;
		$fileArr ['uploadPath'] = $uploadPath;
		//$fileArr ['inDocument'] = $serviceType;
		$fileArr ['isUsing'] = 1;
		$id = $this->add_d ( $fileArr, true );
		$fileArr ['id'] = $id;
		return $fileArr;

		//		}
	}

	/**
	 * ����ҵ�����ͺ�ҵ��ID���ظ�����Ϣ
	 */
	function getFilesByObjId($serviceId, $serviceType) {
		//return $this->findAll ( array ('serviceId' => $serviceId, 'serviceType' => $serviceType ), 'createTime desc', 'id,inDocument,originalName,newName', '1' );
		$this->searchArr = array ('serviceId' => $serviceId, 'serviceType' => $serviceType );
		return $this->list_d ();
	}

	/**
	 * ����ҵ�����ͺ�ҵ����뷵�ظ�����Ϣ
	 */
	function getFilesByObjNo($serviceNo, $serviceType) {
		//return $this->findAll ( array ('serviceId' => $serviceId, 'serviceType' => $serviceType ), 'createTime desc', 'id,inDocument,originalName,newName', '1' );
		$this->searchArr = array ('serviceNo' => $serviceNo, 'serviceType' => $serviceType );
		return $this->list_d ();
	}

	/**
	 * �����б��������ַ���
	 */
	function showFilelist($rows, $isShowDel) {
		if ($rows) {
			$str = "";
			foreach ( $rows as $key => $val ) {
				$str .= "
				<div id='fileDiv$val[id]'>
				<a href='?model=file_uploadfile_management&action=toDownFileById&fileId=$val[id]' taget='_blank' title='����< $val[originalName] >'>$val[originalName]</a>
					&nbsp;";
				if ($val ['isTemp'] == 2) { //��ӱ��ɾ����־
					$str .= "<span style='color=gray'>[��ɾ��]</span>";
				}
				if ($isShowDel == true) {
					$str .= "<img  src='images/closeDiv.gif' onclick='delfileById($val[id])' title='���ɾ������'>";
				}
				$str .= "</div><br>";
			}
			return $str;
		} else {
			return '�����κθ���';
		}
	}

	/**
	 * �����ļ������ļ�����ɾ���ļ�
	 */
	function delFile($serviceType, $filename) {
		$delpath = UPLOADPATH_ESM . $serviceType . '/' . $filename;
		if (is_file ( $delpath )) {
			unlink ( $delpath );
		}
	}

	/**
	 * �����ļ������ļ�����������isUsingֵ��Ϊ0
	 */
	function changeIsUsing($filename, $docmentname) {
		$object = array ('isUsing' => '0' );
		return $this->update ( array ('newName' => $filename, 'inDocument' => $docmentname ), $object );
	}

	/**
	 * �ļ�����
	 */
	function downFile($inDocument, $DOC_ID, $DOC_NAME) {
		$local = 'upload/' . $inDocument . '/' . $DOC_ID;
		$file = fopen ( $local, "r" ); // ���ļ�
		header ( 'Cache-Control: pre-check=0, post-check=0, max-age=0' );
		header ( "Content-type: application/octet-stream" );
		header ( "Accept-Ranges: bytes" );
		header ( "Accept-Length: " . filesize ( $local ) );
		header ( "Content-Length: " . filesize ( $local ) ); //��������ļ���С
		header ( "Content-Disposition: attachment; filename=" . $DOC_NAME );
		$contents = "";
		while ( ! feof ( $file ) ) {
			echo $contents = fread ( $file, 8192 );
		}
		fclose ( $file );
		exit ();
	}

	/*
	 * $inDocument:��������
	 * $DOC_ID������������
	 * $DOC_NAME������ԭ����
	 */
	function downFile2($inDocument, $DOC_ID, $DOC_NAME) {
		/**   2010-10-07 �޸� **/
		//$local = 'upload/' . $inDocument . '/' . $DOC_ID;
		//����ע�ʹ���ĳ�
		$local = UPLOADPATH_ESM . $inDocument . '/' . $DOC_ID;
		/**  2010-10-07 �޸Ľ��� **/

		if (file_exists ( $local )) {
			header ( 'Content-Description: File Transfer' );
			header ( 'Content-Type: application/octet-stream' );
			header ( 'Content-Disposition: attachment; filename=' . basename ( $DOC_NAME ) );
			header ( 'Content-Transfer-Encoding: binary' );
			header ( 'Expires: 0' );
			header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header ( 'Pragma: public' );
			header ( 'Content-Length: ' . filesize ( $local ) );
			ob_clean ();
			flush ();
			readfile ( $local );
			exit ();
		} else {
			echo "<script>alert('���ص��ļ�������');history.back();</script>'";
		}
	}

	/*
	 * ͨ������id���ظ���
	 */
	function downFileById($id) {
		$file = $this->get_d ( $id );
		$this->downFile2 ( $file ['serviceType'], $file ['newName'], $file ['originalName'] );
	}
}
?>
