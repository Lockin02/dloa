var show_page = function(page) {
	$("#changeList").yxgrid("reload");

};

$(function() {
	$("#changeList").yxgrid({
		model : 'asset_basic_change',
		param : {
			isDel : "0"
		},
		isEditAction : false,
		isDelAction : false,
		title : '变动方式',
		isToolBar : true,
		showcheckbox : false,
		sortname : 'id',
		sortorder : 'ASC',
		menusEx : [{

			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSysType == "1") {
					return false;
				} else {
					return true;
				}

			},
			action : function(row) {

				showThickboxWin('?model=asset_basic_change&action=init&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700');
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isSysType == "1") {
					return false;
				} else {
					return true;
				}

			},
			action : function(row) {
				if (window.confirm(("确定删除吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_basic_change&action=deletes&id="
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功')
								$("#changeList").yxgrid("reload");
							} else {
								alert('删除失败，该对象可能已经被引用!')
							}
						}
					});
				}
			}
		}],

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '编码',
			name : 'code',
			sortable : true,
			align : 'center'
		}, {
			display : '变动方式名称',
			name : 'name',
			width : 170,
			sortable : true

		}, {
//			name : 'vouchers',
//			display : '凭证字',
//			width : 70,
//			sortable : true,
//			hide : true
//		}, {
//			name : 'subName',
//			display : '对方科目名称',
//			width : 170,
//			sortable : true,
//			hide : true
//		}, {
//			name : 'subcode',
//			display : '对方科目编码',
//			width : 170,
//			sortable : true,
//			hide : true
//		}, {
			display : '类型',
			name : 'type',
			sortable : true,
			width : 70,
			process : function(val) {
				if (val == "0") {
					return "增加";
				} else {
					return "减少";
				}
			}

		}, {
			name : 'digest',
			display : '摘要',
			width : 170,
			sortable : true

		}, {
			name : 'isSysType',
			display : '录入方式',
			width : 85,
			sortable : true,
			process : function(val) {
				if (val == "1") {
					return "系统";
				} else {
					return "手动";
				}
			}
		}, {
//			name : 'isDel',
//			display : '是否删除',
//			width : 70,
//			sortable : true,
//			hide : true
//		}, {
			name : 'createName',
			display : '创建人',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '创建人id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建日期',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '修改人',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '修改人id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改日期',
			sortable : true,
			hide : true
		}],
		//		 扩展按钮
		buttonsEx : [{
			name : 'import',
			text : '导入',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_basic_change&action=toImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=550");
			}
		}],
		toAddConfig : {
			formWidth : 700,
			formHeight : 400
		},
		toViewConfig : {
			formWidth : 700,
			formHeight : 400
		},

		// 默认搜索顺序
		sortorder : "ASC"
			// 扩展按钮

	});
});