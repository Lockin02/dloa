// 用于记录上传成功的附件数
var successNum = 0;

/**
 * 上传成功后执行事件
 * file：本地文件对象
 * serverData：服务器返回的数据
 */
function uploadComplete(file, serverData) {
    console.log(this);
	// 上传成功处理
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
		// 添加附件类型显示  By LiuB  2014-11-2
		var typeNameView = obj.typeName ? "【 " + obj.typeName + " 】" : "";

		fileListIdObj.append("<div class='upload' id='fileDiv"
		+ obj.id
		+ "'><a title='点击下载' href='"
		+ download_url
		+ "'>"
		+ typeNameView
		+ file.name
		+ "</a>&nbsp;<img src='images/closeDiv.gif' onclick='delfileById("
		+ obj.id + ")' title='点击删除附件'><div>");
		// 设置附件隐藏域供业务模块提交
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

// 全局配置属性
var uploadConfig = {};

/**
 * 根据附件id执行删除附件操作
 * fileId
 */
function delfileById(fileId) {
	if (confirm("确认删除？")) {
		// 后台同步判断,add by chengl 2011-05-17
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
					alert('删除附件成功！');
					var hiddenFile = $("#hiddenFile_" + fileId);
					if (hiddenFile.length == 1) {
						hiddenFile.remove();
					}
				}
			});
		}
	}
}

// 附件上传 TODO
function createUpload(thisObj, formData, setting) {
	try {
		if (setting && setting.isSyn2Server == false) {
			delete formData.serviceId;
		}

		// 上传插件默认属性
		var defaultSetting = {
			auto: true, // 自动上传
			fileTypeExts: '*.*', // 允许上传附件类型，这里不控制，交给后台处理
			multi: true, // 允许多个
			buttonText: '上传附件',
			formData: formData, // 上传参数
			fileSizeLimit: 102400, // 附件大小
			showUploadedPercent: true,
			showUploadedSize: true,
			removeTimeout: 3000,
			uploader: '?model=file_uploadfile_management&action=toAddFile',

			// 事件处理
			onUploadComplete: uploadComplete,

			// 自定义的部分 - 下载
			download_url: "index1.php?model=file_uploadfile_management&action=toDownFileById",
			download_params: "fileId", //下载时传入的参数 - 文件id

			// 自定义的部分 - 删除
			delete_url: "index1.php?model=file_uploadfile_management&action=ajaxdelete",

			// 是否与后台同步，如果false，
			// 1.则不直接从后台删除附件数据
			// 2.上传附件不立刻关联对象，保存的时候进行关联
			isSyn2Server: true,

			fileNamePre: '',
			fileListId: 'uploadFileList',
			delFilesArr : []
		};

		// 加载扩展属性
		if (setting) $.extend(defaultSetting, setting);

		// 初始化
		var upload = thisObj.Huploadify(defaultSetting);

		// 放置一个全局属性
		uploadConfig = defaultSetting;

		//从表附件使用 - 自动获取相关附件信息
		if (setting && setting.ajaxList) {
			var serviceId = setting.formData.serviceId;
			var serviceType = setting.formData.serviceType;
			var fileNamePre = setting.fileNamePre ? setting.fileNamePre : "";

			// 如果是数字的时候才加载
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

						if (data != "暂无任何附件" && fileNamePre != "") {
							// 这里请求已经存在的文件id
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