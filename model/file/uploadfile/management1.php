<?php
class model_file_uploadfile_management extends model_base {

	function __construct() {
		$this->tbl_name = "oa_uploadfile_manage";
		$this->sql_map = "file/uploadfile/managementSql.php";
		parent :: __construct();
	}

	/*
	 * $upload �������ĸ�����������
	 * ������
	 * $serviceId:ҵ�����id
	 * $serviceNo:ҵ��������
	 * $serviceType��ҵ��������ͣ�ͨ��ȡҵ����������
	 * $typeId������Id
	 * $typeName :��������
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
			$fileArr['serviceType'] = $upload['serviceType'];
			$fileArr['serviceId'] = $upload['serviceId'];
			$fileArr['serviceNo'] = $upload['serviceNo'];
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
			file_put_contents('mylog.txt',$id);
			return $fileArr;
		}
	}

	/**
	 * ����ҵ�����ͺ�ҵ��ID���ظ�����Ϣ
	 */
	function getFilesByObjId($serviceId, $serviceType) {
		if (empty ($serviceType) || empty ($serviceId)) {
			return null;
		}
		//return $this->findAll ( array ('serviceId' => $serviceId, 'serviceType' => $serviceType ), 'createTime desc', 'id,inDocument,originalName,newName', '1' );
		$this->searchArr = array (
			'serviceId' => $serviceId,
			'serviceType' => $serviceType
		);
		return $this->list_d();
	}

	/**
	 * ����ҵ�����ͺ�ҵ����ID���ظ�����Ϣ
	 */
	function getFilesByObjIds($serviceIds, $serviceType) {
		if (empty ($serviceType) || empty ($serviceIds)) {
			return null;
		}
		//return $this->findAll ( array ('serviceId' => $serviceId, 'serviceType' => $serviceType ), 'createTime desc', 'id,inDocument,originalName,newName', '1' );
		$this->searchArr = array (
			'serviceIds' => $serviceIds,
			'serviceType' => $serviceType
		);
		return $this->list_d();
	}

	/**
	 * ����ҵ�����ͺ�ҵ����뷵�ظ�����Ϣ
	 */
	function getFilesByObjNo($serviceNo, $serviceType) {
		//return $this->findAll ( array ('serviceId' => $serviceId, 'serviceType' => $serviceType ), 'createTime desc', 'id,inDocument,originalName,newName', '1' );
		$this->searchArr = array (
			'serviceNo' => $serviceNo,
			'serviceType' => $serviceType
		);
		return $this->list_d();
	}

	/**
	 * �����б��������ַ���
	 */
	function showFilelist($rows, $isShowDel) {
		if ($rows) {
			$str = "";
			foreach ($rows as $key => $val) {
				$title = "�������< $val[originalName] >���˸����� ".$val['createName'] ." �� " .$val['createTime'] ."�ϴ�" ;

				$str .= "
												<div id='fileDiv$val[id]'>
												<a href='?model=file_uploadfile_management&action=toDownFileById&fileId=$val[id]' taget='_blank' title='$title'>$val[originalName]</a>
													&nbsp;";
				if ($val['isTemp'] == 2) { //��ӱ��ɾ����־
					$str .= "<span style='color=gray'>[��ɾ��]</span>";
				}
				if ($isShowDel == true) {
					$str .= "<img  src='images/closeDiv.gif' onclick='delfileById($val[id])' title='���ɾ������'>";
				}
				$str .= "<br></div>";
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
		$delpath = UPLOADPATH . $serviceType . '/' . $filename;
		if (is_file($delpath)) {
			unlink($delpath);
		}
	}

	/**
	 * �����ļ������ļ�����������isUsingֵ��Ϊ0
	 */
	function changeIsUsing($filename, $docmentname) {
		$object = array (
			'isUsing' => '0'
		);
		return $this->update(array (
			'newName' => $filename,
			'inDocument' => $docmentname
		), $object);
	}

	/**
	 * �ļ�����
	 */
	function downFile($inDocument, $DOC_ID, $DOC_NAME) {
		$local = 'upload/' . $inDocument . '/' . $DOC_ID;
		$file = fopen($local, "r"); // ���ļ�
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header("Content-type: application/octet-stream");
		header("Accept-Ranges: bytes");
		header("Accept-Length: " . filesize($local));
		header("Content-Length: " . filesize($local)); //��������ļ���С
		header("Content-Disposition: attachment; filename=" . $DOC_NAME);
		$contents = "";
		while (!feof($file)) {
			echo $contents = fread($file, 8192);
		}
		fclose($file);
		exit ();
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

	/*
	 * $inDocument:��������
	 * $DOC_ID������������
	 * $DOC_NAME������ԭ����
	 */
	function downFile2($inDocument, $DOC_ID, $DOC_NAME) {
		/**   2010-10-07 �޸� **/
		//$local = 'upload/' . $inDocument . '/' . $DOC_ID;
		//����ע�ʹ���ĳ�
		$local = UPLOADPATH . $inDocument . '/' . $DOC_ID;echo $local;
		/**  2010-10-07 �޸Ľ��� **/
		if (file_exists($local)) {
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
		//$this->downFile2($file['serviceType'],$file['newName'],$file['originalName']);
	}

	/*
	 * ���ȫ������
	 * $typeΪ��������,���Զ���������,����
	 * $id ΪserviceId
	 * $filenameΪѹ������ļ���
	 */
	function downAllFileByIds($ids, $type,$filename) {

		//ѹ������ļ���,����·��
		$filename = UPLOADPATH  .$filename.'.zip';
		if(strpos($type,',') !== false){
			$arr = explode(',',$type);  //�������ַ���ֳ�����
			$dataList = array();
			foreach($arr as $k => $v ){
				$data = $this->getFilesByObjIds($ids, $v);
				$dataList = array_merge($dataList, $data);	//�ϲ�����������Ϣ
			}
		}else {
			$dataList = $this->getFilesByObjIds($ids, $type);
		}
		$zip = new ZipArchive();

		if ($zip->open($filename, ZIPARCHIVE :: OVERWRITE) !== TRUE) {
			exit("<script>alert('���ص��ļ�������');history.back();</script>");
		}
		foreach ($dataList as $val) {
			//$attachfile = UPLOADPATH .$val['serviceType'] . '/' . $val['newName']; //��ȡԭʼ�ļ�·��
			$attachfile=$val['uploadPath'].$val['newName'];
			if (file_exists($attachfile)) {
				$zip->addFile($attachfile, basename($val['originalName'])); //�ڶ��������Ƿ���ѹ�����е��ļ�����
			}else {
				echo "<script>alert('���ص��ļ�������');history.back();</script>";
			}
		}
			$zip->close(); //�ر�

		if (!file_exists($filename)) {
			exit("<script>alert('���ص��ļ�������');history.back();</script>");//��ʹ���������п���ʧ�ܡ�������
		}
		$flength = filesize($filename)+(1024-filesize($filename)%1024);//�ļ���С
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($filename));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($flength));
		@ readfile($filename);//���ش��ѹ���ļ�
		@ unlink($filename);//ɾ��ѹ�����ļ�
	}

	/*
	 * �ύ����ʱ�����ҵ������븽��������ϵ
	 */
	function updateFileAndObj($fileuploadIds, $objId, $objNo = '') {
		$fileuploadIdstr = implode(",", $fileuploadIds);
		$sql = "update " . $this->tbl_name . " set serviceId=" . $objId . ",serviceNo='" . $objNo . "' where id in(" . $fileuploadIdstr . ")";
		//echo $sql;
		$this->_db->query($sql);
	}

	/**
	 * ��ȡ��Ŀ������������Ŀ�����񸽼���
	 *
	 */
	function pageProject_d($objId, $type = "oa_rd_project") {
		if ($type == "oa_rd_project") {
			$projectId = $objId;
			//$projectFiles = parent::page_d ();
			//��ȡ��Ŀ�¼ƻ�
			$planDao = new model_rdproject_plan_rdplan();
			$planDao->searchArr = array (
				"projectId" => $projectId
			);
			$plans = $planDao->list_d();
			$planIdArr = array ();
			foreach ($plans as $key => $value) {
				array_push($planIdArr, $value['id']);
			}
			$planIds = implode($planIdArr, ",");
			//��ȡ��Ŀ������
			$taskDao = new model_rdproject_task_rdtask();
			$taskDao->searchArr = array (
				"projectId" => $projectId
			);
			$tasks = $taskDao->list_d("select_gridinfo");
			$taskIdArr = array ();
			foreach ($tasks as $key => $value) {
				array_push($taskIdArr, $value['id']);
			}
			$taskIds = implode($taskIdArr, ",");
			//$sql = "select * from oa_uploadfile_manage c where 1=1 and " . "((c.serviceType='oa_rd_project' and c.serviceId=" . $projectId . ") or (c.serviceType='oa_rd_task' and c.serviceId in(" . $taskIds . ")))";

			if (empty ($taskIds)) {
				$this->searchArr["onlyProject"] = $projectId;
			} else {
				unset ($this->searchArr['objTable']);
				$this->searchArr["start"] = 1;
				$this->searchArr["project"] = $projectId;
				if (!empty ($taskIds)) {
					$this->searchArr["task"] = $taskIds;
				}
				if (!empty ($planIds)) {
					$this->searchArr["plan"] = $planIds;
				}
				$this->searchArr["end"] = 1;
			}
		} else {
			$this->searchArr["objId"] = $objId;
			$this->searchArr["objTable"] = $type;
		}

		//print_r($this->searchArr);
		$rows = parent :: page_d();
		return $rows;
	}

	/**
	 * �ֽڽ��ƻ��㺯��
	 */
	function bkmg($s) {
		$c = array (
			'#CCCCCC',
			'#9F9F9F',
			'#0099CC',
			'#FF0000'
		); //�ֱ���bkmg�ַ�����ɫ
		if ($s >= 1073741824) {
			return number_format($s / 1073741824, 3) . "<font color='$c[3]'>G</font>";
		} else
			if ($s >= 1048576) {
				return number_format($s / 1048576, 2) . "<font color='$c[2]'>M</font>";
			} else
				if ($s >= 1024) {
					return number_format($s / 1024, 1) . "<font color='$c[1]'>K</font>";
				} else {
					return $s . "<font color='$c[0]'>B</font>";
				}
	}

	/*******************************������ʾ*********************************/
	function showlist($rows, $showpage) {
		if ($rows) {
			$str = "";
			$i = 0;
			foreach ($rows as $key => $rs) {
				$i++;
				$n = ($i +1) % 2;
				$tfilesize = $this->bkmg($rs['tFileSize']);
				$str .=<<<EOT
					<tr id="tr_$rs[id]" class="TableLine$n">
						<td><input type="checkbox" name="datacb" value="$rs[id]"  onClick="checkOne();"></td>
						<td height="25" align="center">$i</td>
						<td align="center"> $rs[serviceNo] </td>
						<td align="center" >$rs[serviceType] </td>
						<td align="center" >$rs[createName] </td>
						<td align="center" >$rs[createTime]</td>
						<td align="center" >$rs[originalName]</td>
						<td align="center" >$rs[newName]</td>
						<td align="center" >$tfilesize</td>
						<td>
							<p>
								<a href="?model=file_uploadfile_management&action=readInfo&id=$rs[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="�鿴< $rs[originalName] >��Ϣ" class="thickbox">�鿴</a>
								<a href="?model=file_uploadfile_management&action=toDownFile&newName=$rs[newName]&originalName=$rs[originalName]&inDocument=$rs[inDocument]" taget="_blank" title="����< $rs[originalName] >��Ϣ">����</a>
								<a href="javascript:delFile($rs[id])">ɾ��</a>
							</p>
					    </td>
					</tr>
EOT;
			}

		} else {
			$str = '<tr><td colspan="10" style="text-align:center;">���������Ϣ</td></tr>';
		}
		return $str . '<tr><td colspan="10" style="text-align:center;">' . $showpage->show(6) . '</td></tr>';
	}

	/**
	 * @desription �з������б�
	 * @param $rows
	 * @param $isView �Ƿ�鿴ҳ��
	 * @return return_type
	 * @date 2010-9-17 ����10:42:30
	 */
	function showRdList($rows, $isView = false) {
		if ($rows) {
			$i = 0;
			$str = "";
			$strDel = "";
			foreach ($rows as $key => $val) {
				if ($isView == false) {
					$strDel = '<a href="javascript:delFile(' . $val[id] . ')">ɾ��</a>';
				}
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$i++;
				$tfilesize = $this->bkmg($val['tFileSize']);
				$str .=<<<EOT
					<tr class="$classCss" pjId="$val[id]">
						<td>
							$i
						</td>
						<td>
							$val[typeName]
						</td>
						<td>
							$val[createName]
						</td>
						<td>
							$val[createTime]
						</td>
						<td>
							$val[originalName]
						</td>
						<td>
							$val[newName]
						</td>
						<td>
							$tfilesize
						</td>
						<td>
							<a href="?model=file_uploadfile_management&action=readInfo&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=700" title="�鿴< $val[originalName] >��Ϣ" class="thickbox">�鿴</a>
							<a href="?model=file_uploadfile_management&action=toDownFile&newName=$val[newName]&originalName=$val[originalName]&inDocument=$val[inDocument]" taget="_blank" title="����< $val[originalName] >��Ϣ">����</a>
							$strDel
						</td>

					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="50">������ؼ�¼</td></tr>';
		}
		return $str;
	}

	/**
	 * @desription ��Ӧ�̹������б�
	 * @param tags
	 * @date 2010-11-18 ����08:46:48
	 */
	function showSuppList($rows, $isView = false) {
		if ($rows) {
			$i = 0;
			$str = "";
			$strDel = "";
			foreach ($rows as $key => $val) {
				if ($isView == false) {
					$strDel = '<a href="javascript:delFile(' . $val[id] . ')">ɾ��</a>';
				}
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$i++;
				$tfilesize = $this->bkmg($val['tFileSize']);
				$str .=<<<EOT
					<tr class="$classCss" pjId="$val[id]">
						<td>
							$i
						</td>
						<td>
							$val[createName]
						</td>
						<td>
							$val[createTime]
						</td>
						<td>
							$val[originalName]
						</td>
						<td>
							$val[newName]
						</td>
						<td>
							$tfilesize
						</td>
						<td>
							<a href="?model=file_uploadfile_management&action=readInfo&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=700" title="�鿴< $val[originalName] >��Ϣ" class="thickbox">�鿴</a>
							<a href="?model=file_uploadfile_management&action=toDownFile&newName=$val[newName]&originalName=$val[originalName]&inDocument=$val[inDocument]" taget="_blank" title="����< $val[originalName] >��Ϣ">����</a>
							$strDel
						</td>

					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="50">������ؼ�¼</td></tr>';
		}
		return $str;
	}
}
?>
