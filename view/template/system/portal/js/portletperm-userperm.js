var show_page = function(page) {
	$("#permGrid").yxgrid("reload");
};

$(function() {
	var perms = [];
	var selectUserId = "";
	var selectRoleId = "";

	// 防止重叠
	var offset = $("#deptTree").offset();
	var showOffset = {
		top : offset.top,
		left : offset.left
	};
	var hideOffset = {
		top : 1000,
		left : 1000
	};
	var isFistClickDept = true;// 第一次点击部门tab标识
	var isFistClickJobs = true;// 第一次点击角色tab
	// $("#jobsTree").offset(hideOffset);
	$("#jobsTree").hide();
	// 点击组织机构tab
	$("#deptTab").bind('click', function() {
				if (!isFistClickDept && !isFistClickJobs) {
					$("#deptTree").show();
					$("#deptTree").offset(showOffset);
					$("#jobsTree").offset(hideOffset);
					$("#jobsTree").hide();
				} else {
					isFistClickDept = false;
				}
				selectRoleId = "";
			});

	$("#jobsTab").bind('click', function() {
		$("#jobsTree").show();
		$("#jobsTree").offset(showOffset);
		$("#deptTree").offset(hideOffset);
		$("#deptTree").hide();
		selectUserId = "";
		$("#deptTree").offset();
		// 如果是第一次点击，渲染角色树
		if (isFistClickJobs) {
			isFistClickJobs = false;
			$.fn.zTree.init($("#jobsTree"), {
				async : {
					enable : true,
					url : "?model=deptuser_jobs_jobs&action=listTreeJson&noIcon=1"
				},
				callback : {
					onClick : function(event, treeId, treeNode, clickFlag) {
						perms = [];
						selectRoleId = treeNode.id;

						var data = $.ajax({
							type : "POST",
							data : {
								selectRoleId : selectRoleId
							},
							url : '?model=system_portal_portletperm&action=getSelectRolePerms',
							async : false
						}).responseText;
						if (data) {
							data = eval(data);
							for (var i = 0; i < data.length; i++) {
								perms.push(data[i].portletId);
							}
						}
						var isFirstExpland = true;
						$.fn.zTree.init($("#portletTree"), {
							async : {
								enable : true,
								url : "?model=system_portal_portlettype&action=getPortletTree",
								autoParam : ['id']
							},
							check : {
								enable : true,
								autoCheckTrigger : true
							},
							callback : {
								onCheck : function(event, treeId, treeNode) {

									if (!treeNode.isParent) {
										if (treeNode.checked) {// 为子节点并且选中的情况
											if (perms.indexOf(treeNode['id']) == -1) {
												perms.push(treeNode['id']);
											}

										} else {
											// 如果值存在，删除数组项
											var index = perms
													.indexOf(treeNode['id']);
											if (index != -1) {
												perms.splice(index, 1);
											}
										}
									} else {
									}
								},
								onAsyncSuccess : function(event, treeId,
										treeNode) {
									var treeObj = $.fn.zTree
											.getZTreeObj("portletTree");
									treeNode.url = "";
									// if (isFirstExpland) {
									// treeObj.expandAll(true);
									// isFirstExpland=false;
									// }
									if (treeNode && treeNode.children) {
										for (var i = 0; i < treeNode.children.length; i++) {
											var node = treeNode.children[i];
											// if (node.getParentNode()
											// &&
											// node.getParentNode().checked)
											// {
											// treeObj.checkNode(node, true,
											// true);
											// }
											if (node.isParent == false) {
												if (perms.indexOf(node['id']) != -1) {
													// node.checked = true;
													treeObj.checkNode(node,
															true, true);
												}
											}
										}
									}
								}
							}
						});

						var permGrid = $("#permGrid").data('yxgrid');
						if (typeof(permGrid.options.param['userId']) != "undefined") {
							delete permGrid.options.param['userId'];
						}
						permGrid.options.param['roleId'] = selectRoleId;
						permGrid.reload();

					}
				}
			});

		}
	});

	$.fn.zTree.init($("#deptTree"), {
		async : {
			enable : true,
			url : "?model=deptuser_user_user&action=deptusertree&noIcon=1",
			autoParam : ['id', 'Depart_x', 'Dflag']
		},
		callback : {
			onClick : function(event, treeId, treeNode, clickFlag) {
				if (treeNode.type == 'user') {
					perms = [];
					selectUserId = treeNode.id;

					var data = $.ajax({
						type : "POST",
						data : {
							selectUserId : selectUserId
						},
						url : '?model=system_portal_portletperm&action=getSelectUserPerms',
						async : false
					}).responseText;
					if (data) {
						data = eval(data);
						for (var i = 0; i < data.length; i++) {
							perms.push(data[i].portletId);
						}
					}
					var isFirstExpland = true;
					$.fn.zTree.init($("#portletTree"), {
						async : {
							enable : true,
							url : "?model=system_portal_portlettype&action=getPortletTree",
							autoParam : ['id']
						},
						check : {
							enable : true,
							autoCheckTrigger : true
						},
						callback : {
							onCheck : function(event, treeId, treeNode) {

								if (!treeNode.isParent) {
									if (treeNode.checked) {// 为子节点并且选中的情况
										if (perms.indexOf(treeNode['id']) == -1) {
											perms.push(treeNode['id']);
										}

									} else {
										// 如果值存在，删除数组项
										var index = perms
												.indexOf(treeNode['id']);
										if (index != -1) {
											perms.splice(index, 1);
										}
									}
								} else {
									// if (treeNode.checked) {
									// var treeObj = $.fn.zTree
									// .getZTreeObj("portletTree");
									// treeObj.reAsyncChildNodes(treeNode,
									// "refresh");
									// }
								}
							},
							onAsyncSuccess : function(event, treeId, treeNode) {
								var treeObj = $.fn.zTree
										.getZTreeObj("portletTree");
								treeNode.url = "";
								// if (isFirstExpland) {
								// treeObj.expandAll(true);
								// isFirstExpland=false;
								// }
								if (treeNode && treeNode.children) {
									for (var i = 0; i < treeNode.children.length; i++) {
										var node = treeNode.children[i];
										// if (node.getParentNode()
										// && node.getParentNode().checked) {
										// treeObj.checkNode(node, true, true);
										// }
										if (node.isParent == false) {
											if (perms.indexOf(node['id']) != -1) {
												// node.checked = true;
												treeObj.checkNode(node, true,
														true);
											}
										}
									}
								}
							}
						}
					});

					var permGrid = $("#permGrid").data('yxgrid');
					if (typeof(permGrid.options.param['roleId']) != "undefined") {
						delete permGrid.options.param['roleId'];
					}
					permGrid.options.param['userId'] = selectUserId;
					permGrid.reload();
				}

			}
		}
	});

	$("#permGrid").yxgrid({
		model : 'system_portal_portletperm',
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		// 表单
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : 'portlet名称',
					name : 'portletName'
				}, {
					display : '用户名称',
					name : 'userName'
				}, {
					display : '角色名称',
					name : 'roleName'
				}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : 'portlet名称',
					name : 'portletName'
				}],
		buttonsEx : [{
			text : '保存',
			icon : "add",
			action : function() {
				if (perms.toString()) {
					$.ajax({
						url : '?model=system_portal_portletperm&action=savePerms',
						type : 'POST',
						data : {
							perms : perms.toString(),
							selectUserId : selectUserId,
							selectRoleId : selectRoleId
						},
						success : function() {
							$("#permGrid").yxgrid("reload");
							alert("保存成功.");
						}
					});
				} else {
					alert("请至少选择一个portlet.");
				}
			}
		}],
		sortorder : "DESC",
		sortname : "id",
		title : '授权信息'
	});
});