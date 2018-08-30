var show_page = function() {
	$("#esmfileGrid").yxgrid("reload");
};
$(function() {
	// 关闭网页时加载的事件
	$(window).bind('beforeunload',function(){
		window.opener.loadGrid();
	});

	$("#tree").yxtree({
		url: '?model=engineering_file_esmfiletype&action=getTree&projectId=' + $("#projectId").val(),
		event: {
			node_click: function(event, treeId, treeNode) {
				var esmfileGrid = $("#esmfileGrid").data('yxgrid');
				esmfileGrid.options.param['typeId'] = treeNode.id;
				esmfileGrid.reload();
				$("#typeId").val(treeNode.id);
			}
		}
	});
	$("#esmfileGrid").yxgrid({
		model: 'engineering_file_esmfile',
		param: {
			projectId: $("#projectId").val()
		},
		title: '工程附件表',
		isViewAction: false,
		isAddAction: false,
		isDelAction: false,
		isEditAction: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'serviceNo',
			display: '业务编号',
			sortable: true,
			hide: true
		}, {
			name: 'serviceType',
			display: '业务类型',
			sortable: true,
			hide: true
		}, {
			name: 'typeName',
			display: '文件类型名称',
			sortable: true,
			width: '150'
		}, {
			name: 'originalName',
			display: '原始文件名',
			sortable: true,
			width: '150'

		}, {
			name: 'newName',
			display: '系统文件名',
			sortable: true,
			hide: true
		}, {
			name: 'inDocument',
			display: 'inDocument',
			sortable: true,
			hide: true
		}, {
			name: 'tFileSize',
			display: '文件大小',
			sortable: true,
			process: function(v) {
				if (v >= 1048576) {
					return moneyFormat2(v / 1048576) + "M";
				} else if (v >= 1024) {
					return moneyFormat2(v / 1024) + "K";
				} else {
					return moneyFormat2(v) + "B";
				}
			}
		}, {
			name: 'uploadPath',
			display: '附件路径',
			sortable: true,
			hide: true
		}, {
			name: 'isTemp',
			display: '是否临时文件',
			sortable: true,
			hide: true
		}, {
			name: 'styleThree',
			display: 'styleThree',
			sortable: true,
			hide: true
		}, {
			name: 'styleTwo',
			display: 'styleTwo',
			sortable: true,
			hide: true
		}, {
			name: 'styleOne',
			display: 'styleOne',
			sortable: true,
			hide: true
		}, {
			name: 'createId',
			display: '创建人Id',
			sortable: true,
			hide: true
		}, {
			name: 'createName',
			display: '创建人名称',
			sortable: true,
			width: '100'
		}, {
			name: 'createTime',
			display: '创建时间',
			sortable: true,
			width: 150
		}, {
			name: 'updateId',
			display: '修改人Id',
			sortable: true,
			hide: true
		}, {
			name: 'updateName',
			display: '修改人名称',
			sortable: true,
			hide: true
		}, {
			name: 'updateTime',
			display: '修改时间',
			sortable: true,
			hide: true
		}],

		buttonsEx: [{
			name: 'exportIn',
			text: "导入文档",
			icon: 'add',
			action: function(row, rows, grid) {
				if ($("#typeId").val()) {
					showThickboxWin("?model=engineering_file_esmfile&action=toUploadFile"
					+ "&projectId=" + $("#projectId").val()
					+ "&typeId=" + $("#typeId").val()
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("请选择文档类型");
				}
			}
		}],

		menusEx: [{
			text: '下载文档',
			icon: 'edit',
			action: function(row) {
				window.open(
					"?model=engineering_file_esmfile&action=toDownFileById&fileId=" + row.id,
					"", "width=200,height=200,top=200,left=200");

			}
		}, {
			text: '删除文档',
			icon: 'delete',
			action: function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type: "POST",
						url: "?model=engineering_file_esmfile&action=ajaxdelete",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page();
							} else {
								alert("删除失败! ");
							}
						}
					});
				}
			}
		}],

		searchitems: [{
			display: "业务类型",
			name: 'serviceTypeSch'
		}, {
			display: "文件类型名称",
			name: 'typeNameSch'
		}, {
			display: "原始文件名",
			name: 'originalNameSch'
		}]
	});
});