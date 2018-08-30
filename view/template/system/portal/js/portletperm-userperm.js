var show_page = function(page) {
	$("#permGrid").yxgrid("reload");
};

$(function() {
	var perms = [];
	var selectUserId = "";
	var selectRoleId = "";

	// ��ֹ�ص�
	var offset = $("#deptTree").offset();
	var showOffset = {
		top : offset.top,
		left : offset.left
	};
	var hideOffset = {
		top : 1000,
		left : 1000
	};
	var isFistClickDept = true;// ��һ�ε������tab��ʶ
	var isFistClickJobs = true;// ��һ�ε����ɫtab
	// $("#jobsTree").offset(hideOffset);
	$("#jobsTree").hide();
	// �����֯����tab
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
		// ����ǵ�һ�ε������Ⱦ��ɫ��
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
										if (treeNode.checked) {// Ϊ�ӽڵ㲢��ѡ�е����
											if (perms.indexOf(treeNode['id']) == -1) {
												perms.push(treeNode['id']);
											}

										} else {
											// ���ֵ���ڣ�ɾ��������
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
									if (treeNode.checked) {// Ϊ�ӽڵ㲢��ѡ�е����
										if (perms.indexOf(treeNode['id']) == -1) {
											perms.push(treeNode['id']);
										}

									} else {
										// ���ֵ���ڣ�ɾ��������
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
		// ��
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : 'portlet����',
					name : 'portletName'
				}, {
					display : '�û�����',
					name : 'userName'
				}, {
					display : '��ɫ����',
					name : 'roleName'
				}],
		/**
		 * ��������
		 */
		searchitems : [{
					display : 'portlet����',
					name : 'portletName'
				}],
		buttonsEx : [{
			text : '����',
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
							alert("����ɹ�.");
						}
					});
				} else {
					alert("������ѡ��һ��portlet.");
				}
			}
		}],
		sortorder : "DESC",
		sortname : "id",
		title : '��Ȩ��Ϣ'
	});
});