// ���ڼ�¼�ϴ��ɹ��ĸ�����
var successNum = 0;

/**
 * �ϴ��ɹ���ִ���¼�
 * file�������ļ�����
 * serverData�����������ص�����
 */
function uploadComplete(file, serverData) {
    console.log(this);
	// �ϴ��ɹ�����
	var obj = eval("(" + serverData + ")");
	if (obj.errorCode && obj.errorCode != "") {
		alert(data.errorMsg);
	} else {
		var fileNamePre = this.fileNamePre;
		var fileListIdObj = $("#" + this.fileListId);
		var download_url = this.download_url + "&" + this.download_params + "=" + obj.id;
		if (fileListIdObj.children().size() == 0) {
			fileListIdObj.empty();
		}
		// ��Ӹ���������ʾ  By LiuB  2014-11-2
		var typeNameView = obj.typeName ? "�� " + obj.typeName + " ��" : "";

		fileListIdObj.append("<div class='upload' id='fileDiv"
		+ obj.id
		+ "'><a title='�������' href='"
		+ download_url
		+ "'>"
		+ typeNameView
		+ file.name
		+ "</a>&nbsp;<img src='images/closeDiv.gif' onclick='delfileById("
		+ obj.id + ")' title='���ɾ������'><div>");
		// ���ø���������ҵ��ģ���ύ
		successNum++;
		if (!fileNamePre) {
			fileListIdObj.append("<input type='hidden' name='fileuploadIds["
			+ successNum + "]' id='hiddenFile_" + obj.id + "' value='" + obj.id + "'/>");
		} else {
			fileListIdObj.append("<input type='hidden' name='" + fileNamePre
			+ "[" + successNum + "]' id='hiddenFile_" + obj.id + "' value='" + obj.id + "'/>");
		}
	}
}

// ȫ����������
var uploadConfig = {};

/**
 * ���ݸ���idִ��ɾ����������
 * fileId
 */
function delfileById(fileId) {
	if (confirm("ȷ��ɾ����")) {
		// ��̨ͬ���ж�,add by chengl 2011-05-17
		if (!uploadConfig.isSyn2Server) {
			var delFiledIdObj = $('#delFilesId');
			if (delFiledIdObj.val()) {

			} else {
				var $delFilesId = $("<input type='hidden' name='delFilesId' id='delFilesId' value=''>")
				$delFilesId.val(fileId);
				$("form").append($delFilesId);
				// $(this).after($delFilesId);
			}
			uploadConfig.delFilesArr.push(fileId);
			delFiledIdObj.val(uploadConfig.delFilesArr);
			$('#fileDiv' + fileId).remove();
		} else {
			$.post(uploadConfig.delete_url, {
				id: fileId
			}, function(data) {
				if (data == 1) {
					$('#fileDiv' + fileId).remove();
					var fileListId = uploadConfig.fileListId;
					$("input[value='" + fileId + "']",
						$("#" + fileListId)).remove();
					alert('ɾ�������ɹ���');
					var hiddenFile = $("#hiddenFile_" + fileId);
					if (hiddenFile.length == 1) {
						hiddenFile.remove();
					}
				}
			});
		}
	}
}

// �����ϴ� TODO
function createUpload(thisObj, formData, setting) {
	try {
		if (setting && setting.isSyn2Server == false) {
			delete formData.serviceId;
		}

		// �ϴ����Ĭ������
		var defaultSetting = {
			auto: true, // �Զ��ϴ�
			fileTypeExts: '*.*', // �����ϴ��������ͣ����ﲻ���ƣ�������̨����
			multi: true, // ������
			buttonText: '�ϴ�����',
			formData: formData, // �ϴ�����
			fileSizeLimit: 102400, // ������С
			showUploadedPercent: true,
			showUploadedSize: true,
			removeTimeout: 3000,
			uploader: '?model=file_uploadfile_management&action=toAddFile',

			// �¼�����
			onUploadComplete: uploadComplete,

			// �Զ���Ĳ��� - ����
			download_url: "index1.php?model=file_uploadfile_management&action=toDownFileById",
			download_params: "fileId", //����ʱ����Ĳ��� - �ļ�id

			// �Զ���Ĳ��� - ɾ��
			delete_url: "index1.php?model=file_uploadfile_management&action=ajaxdelete",

			// �Ƿ����̨ͬ�������false��
			// 1.��ֱ�ӴӺ�̨ɾ����������
			// 2.�ϴ����������̹������󣬱����ʱ����й���
			isSyn2Server: true,

			fileNamePre: '',
			fileListId: 'uploadFileList',
			delFilesArr : []
		};

		// ������չ����
		if (setting) $.extend(defaultSetting, setting);

		// ��ʼ��
		var upload = thisObj.Huploadify(defaultSetting);

		// ����һ��ȫ������
		uploadConfig = defaultSetting;

		//�ӱ���ʹ�� - �Զ���ȡ��ظ�����Ϣ
		if (setting && setting.ajaxList) {
			var serviceId = setting.formData.serviceId;
			var serviceType = setting.formData.serviceType;
			var fileNamePre = setting.fileNamePre ? setting.fileNamePre : "";

			// ��������ֵ�ʱ��ż���
			if (parseInt(serviceId) == serviceId) {
				var fileListDiv = $("#" + setting.fileListId);
				$.ajax({
					url: '?model=file_uploadfile_management&action=getFilelist',
					type: "POST",
					data: {
						serviceId: serviceId,
						serviceType: serviceType
					},
					success: function(data) {
						fileListDiv.append(data);

						if (data != "�����κθ���" && fileNamePre != "") {
							// ���������Ѿ����ڵ��ļ�id
							$.ajax({
								url: '?model=file_uploadfile_management&action=getFileListJson',
								type: "POST",
								data: {
									serviceId: serviceId,
									serviceType: serviceType
								},
								dataType: 'json',
								success: function(data) {
									for (var i = 0; i < data.length; i++) {
										fileListDiv.append("<input type='hidden' name='" + fileNamePre
										+ "[" + i + "]' id='hiddenFile_" + data[i].id + "' value='" + data[i].id + "'/>");
									}
								}
							});
						}
					}
				});
			}
		}
	} catch (e) {
		alert(e)
	}
	return upload;
}