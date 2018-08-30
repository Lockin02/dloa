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
			removeTitle : "删除节点",
			renameTitle : "编辑节点"
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

	// 删除节点前事件
	function beforeRemove(treeId, treeNode) {
		if (treeNode.parentId == -1) {
			alert("不能删除根节点");
			return false;
		}
		var zTree = $.fn.zTree.getZTreeObj("tree");
		zTree.selectNode(treeNode);
		return confirm("确认删除 节点 -- " + treeNode.name + " 吗？");
	}
	// 删除节点事件
	function onRemove(e, treeId, treeNode) {
		$.ajax({
					url : "?model=engineering_file_uploadfiletype&action=ajaxdeletes",
					type : 'POST',
					data : {
						id : treeNode.id
					}
				});
	}
	// 重命名节点前
	function beforeRename(treeId, treeNode, newName) {
		if (newName.length == 0) {
			alert("节点名称不能为空.");
			return false;
		}
		return true;
	}
	// 重命名节点
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
	// 新增节点按钮
	function addHoverDom(treeId, treeNode) {
		var sObj = $("#" + treeNode.tId + "_span");
		if ($("#addBtn_" + treeNode.id).length > 0)
			return;
		var addStr = "<button type='button' class='add' id='addBtn_"
				+ treeNode.id
				+ "' title='新增节点' onfocus='this.blur();'></button>";
		sObj.append(addStr);
		var btn = $("#addBtn_" + treeNode.id);
		if (btn)
			btn.bind("click", function() {
				var zTree = $.fn.zTree.getZTreeObj("tree");
				// 后台处理
				$.ajax({
					url : "?model=engineering_file_uploadfiletype&action=saveType",
					type : 'POST',
					data : {
						'type[parentId]' : treeNode.id,
						'type[name]' : "新节点",
						'type[projectId]' : serviceId
					},
					success : function(data) {
						if (treeNode.open == true) {
							zTree.addNodes(treeNode, {
										id : data,
										pId : treeNode.id,
										name : "新节点"
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
	var firstAsy = true;// 第一次加载的时候刷新根节点
	// 加载成功后执行
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
