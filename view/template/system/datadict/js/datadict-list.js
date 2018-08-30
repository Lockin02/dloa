/** 产品类型信息列表* */

var show_page = function(page) {
	$("#datadictList").yxgrid("reload");
    $("#tree").yxgrid("reload");
};

$(function() {
	$("#tree").yxtree({

	url : '?model=system_datadict_datadict&action=getChildren',
	event : {
		"node_click" : function(event, treeId, treeNode) {
			var datadictList = $("#datadictList").data('yxgrid');
			datadictList.options.param['parentId']=treeNode.id;

			datadictList.reload();
			$("#parentId").val(treeNode.id);


		}
	}
	});

	$("#datadictList").yxgrid({

		model : 'system_datadict_datadict',
		action:'DatadictPageJson',
        /**
			 * 是否显示修改按钮/菜单
			 *
			 * @type Boolean
			 */
			isEditAction : false,
		title : '数据字典',
		isToolBar : true,
		isViewAction : false,
		showcheckbox : true,
        sortname : 'id',
		sortorder : 'ASC',
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "初始化",
			icon : 'edit',

			action : function(row) {
				location='?model=system_datadict_datadict'
			}
		}],
       //新增
		 toAddConfig : {
				toAddFn : function(p ,treeNode , treeId) {
			//	alert(treeNode.data('data')['id']);
					var c = p.toAddConfig;
					var w = c.formWidth ? c.formWidth : p.formWidth;
					var h = c.formHeight ? c.formHeight : p.formHeight;
					showThickboxWin("?model="
							+ p.model
							+ "&action="
							+ c.action
							+ c.plusUrl
							+ "&parentId=" +$("#parentId").val(treeNode.id)
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ h + "&width=" + w);
				}
			},

        // 扩展右键菜单

		menusEx : [{
			text : '编辑',
			icon : 'edit',
			action : function(row) {
				showThickboxWin('?model=system_datadict_datadict&action=init&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700');
			}
		}, {
			text : '启用',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isUse == "1") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("确认启用?")) {
					$.ajax({
						type : "POST",
						url : "?model=system_datadict_datadict&action=ajaxUseStatus",
						data : {
							id : row.id,
							useStatus : '0'
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('启用成功！');
							} else {
								alert('启用失败!');
							}
						}
					});
				}
			}
		}, {
			text : '停用',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isUse == "0" || row.isUse == "") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("确认停用?")) {
					$.ajax({
						type : "POST",
						url : "?model=system_datadict_datadict&action=ajaxUseStatus",
						data : {
							id : row.id,
							useStatus : '1'
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('操作成功！');
							} else {
								alert('操作失败!');
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
			display: '所属板块',
			name: 'module',
			width: 100,
			sortable: true,
			datacode: 'HTBK'
		}, {
			display : '名称',
			name : 'dataName',
			sortable : true,
			width : 150
		}, {
			display : '编码',
			name : 'dataCode',
			sortable : true,
			width : 150
		}, {
			display : '上级',
			name : 'parentName',
			sortable : true,
			width : 150
		}, {
			display : '序号',
			name : 'orderNum',
			sortable : true
		}, {
			display : '备注',
			name : 'remark',
			sortable : true,
			width : 200
		}, {
			name : 'isUse',
			display : '是否启用',
			sortable : true,
			process:function(v){
			   if(v == '0' || v == ''){
			      return "启用";
			   }else if(v == '1'){
			      return "关闭";
			   }
			}
		}],

		searchitems : [{
			display : '名称',
			name : 'dataName'
		},{
			display : '上级',
			name : 'parentName'
		}]

	});
});
