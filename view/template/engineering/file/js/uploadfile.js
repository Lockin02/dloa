$(document).ready(function() {

	var setting = {
		async : {
			enable : true,
			url : "?model=engineering_file_uploadfiletype&action=tree&projectId="
					+ serviceId,
			autoParam : ["id", "name=n"],
			otherParam : {
				"otherParam" : "zTreeAsyncTest"
			},
			dataFilter : filter
		},
		callback : {
			// beforeAsync : beforeAsync,
			beforeRemove : beforeRemove,
			onRemove : onRemove,
			beforeRename : beforeRename,
			onRename : onRename,
			onClick : zTreeOnClick,
			onAsyncSuccess : zTreeOnAsyncSuccess
		},
		view : {
			removeHoverDom : removeHoverDom,
			selectedMulti : false
		}

	};
	if (isView != 1) {
		setting.edit = {
			enable : true,
			showRemoveBtn : true,
			showRenameBtn : true,
			removeTitle : "ɾ���ڵ�",
			renameTitle : "�༭�ڵ�"
		}
		setting.view.addHoverDom = addHoverDom;
	}
	function filter(treeId, parentNode, childNodes) {
		if (!childNodes)
			return null;
		for (var i = 0, l = childNodes.length; i < l; i++) {
			childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
		}
		return childNodes;
	}
	// function beforeAsync(treeId, treeNode) {
	// return treeNode ? treeNode.level < 5 : true;
	// }
	function zTreeOnClick(event, treeId, treeNode) {
		var grid = $("#table").data('yxgrid_uploadfile_esm');
		if (treeNode.parentId == -1) {
			delete grid.options.param['typeId'];
		} else {
			grid.options.param['typeId'] = treeNode.id;
		}
		grid.reload();
	}

	// ɾ���ڵ�ǰ�¼�
	function beforeRemove(treeId, treeNode) {
		if (treeNode.parentId == -1) {
			alert("����ɾ�����ڵ�");
			return false;
		}
		var zTree = $.fn.zTree.getZTreeObj("tree");
		zTree.selectNode(treeNode);
		return confirm("ȷ��ɾ�� �ڵ� -- " + treeNode.name + " ��");
	}
	// ɾ���ڵ��¼�
	function onRemove(e, treeId, treeNode) {
		$.ajax({
					url : "?model=engineering_file_uploadfiletype&action=ajaxdeletes",
					type : 'POST',
					data : {
						id : treeNode.id
					}
				});
	}
	// �������ڵ�ǰ
	function beforeRename(treeId, treeNode, newName) {
		if (newName.length == 0) {
			alert("�ڵ����Ʋ���Ϊ��.");
			return false;
		}
		return true;
	}
	// �������ڵ�
	function onRename(e, treeId, treeNode) {
		$.ajax({
					url : "?model=engineering_file_uploadfiletype&action=saveType",
					type : 'POST',
					success : function(result, request) {
					},
					data : {
						'type[id]' : treeNode.id,
						'type[parentId]' : treeNode.parentId,
						'type[name]' : treeNode.name,
						'type[projectId]' : serviceId
					}
				});
	}
	// �����ڵ㰴ť
	function addHoverDom(treeId, treeNode) {
		var sObj = $("#" + treeNode.tId + "_span");
		if ($("#addBtn_" + treeNode.id).length > 0)
			return;
		var addStr = "<button type='button' class='add' id='addBtn_"
				+ treeNode.id
				+ "' title='�����ڵ�' onfocus='this.blur();'></button>";
		sObj.append(addStr);
		var btn = $("#addBtn_" + treeNode.id);
		if (btn)
			btn.bind("click", function() {
				var zTree = $.fn.zTree.getZTreeObj("tree");
				// ��̨����
				$.ajax({
					url : "?model=engineering_file_uploadfiletype&action=saveType",
					type : 'POST',
					data : {
						'type[parentId]' : treeNode.id,
						'type[name]' : "�½ڵ�",
						'type[projectId]' : serviceId
					},
					success : function(data) {
						if (treeNode.open == true) {
							zTree.addNodes(treeNode, {
										id : data,
										pId : treeNode.id,
										name : "�½ڵ�"
									});
						} else {
							var treeObj = $.fn.zTree.getZTreeObj("tree");
							treeObj.reAsyncChildNodes(treeNode, "refresh");
						}
					}
				});

			});
	};
	function removeHoverDom(treeId, treeNode) {
		$("#addBtn_" + treeNode.id).unbind().remove();

	};
	var firstAsy = true;// ��һ�μ��ص�ʱ��ˢ�¸��ڵ�
	// ���سɹ���ִ��
	function zTreeOnAsyncSuccess() {
		if (firstAsy) {
			var treeObj = $.fn.zTree.getZTreeObj("tree");
			var nodes = treeObj.getNodes();
			if (nodes.length > 0) {
				treeObj.reAsyncChildNodes(nodes[0], "refresh");
			}
		}
		firstAsy = false;
	}

	$.fn.zTree.init($("#tree"), setting);

	var param = {};
	if (serviceType && serviceType != "") {
		param.objTable = serviceType;
	}
	$("#table").yxgrid_uploadfile_esm({
				param : param,
				isDelAction : isView == 1 ? false : true
			});
	if (isView != 1 && $("#swfupload")[0]) {
		var uploadfile = createSWFUpload({
					"serviceType" : serviceType,
					"serviceId" : serviceId
				}, {
					upload_url : "swfupload/upload_esm.php",
					file_dialog_start_handler : function() {
						var treeObj = $.fn.zTree.getZTreeObj("tree");
						var selectNode = treeObj.getSelectedNodes();
						if (selectNode && selectNode[0]) {
							uploadfile.addPostParam("typeName",
									selectNode[0].name);
							uploadfile.addPostParam("typeId", selectNode[0].id);
						}
					},
					upload_complete_handler : function() {
						try {
							if (this.getStats().files_queued === 0) {
								document
										.getElementById(this.customSettings.cancelButtonId).disabled = true;
							} else {
								this.startUpload();
							}
							$("#table").yxgrid_uploadfile_esm('reload');
						} catch (ex) {
							this.debug(ex);
						}
					}
				});
	}

});
