/** 资产分类信息列表
 *  @linzx
 * */

var show_page = function(page) {
	$("#datadictList").yxgrid("reload");
};

$(function() {

	$("#datadictList").yxgrid({
		model : 'asset_basic_directory',
		title : '资产分类',
		isToolBar : true,
		//isViewAction : false,
		isEditAction : false,
		//isAddAction : false,
		//showcheckbox : false,
		sortname : 'id',
		sortorder : 'ASC',

		//增加按钮
		//		buttonsEx : [{
		//			name : 'Add',
		//			// hide : true,
		//			text : "初始化",
		//			icon : 'edit',
		//
		//			action : function(row) {
		//				location='?model=asset_basic_directory'
		//			}
		//		}],

		// 扩展右键菜单

		menusEx : [{
			text : '编辑',
			icon : 'edit',
			action : function(row) {
				showThickboxWin('?model=asset_basic_directory&action=toEdit&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=1000');
			}
		}],
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '资产类别编码',
			name : 'code',
			sortable : true
		}, {
			display : '资产类别',
			name : 'name',
			sortable : true
		}, {
//			display : '固定资产科目',
//			name : 'assetSubject',
//			sortable : true
//		}, {
			display : '使用年限',
			name : 'limitYears',
			sortable : true
		}, {
			display : '净残值率',
			name : 'salvage',
			sortable : true
		}, {
			display : '计量单位',
			name : 'unit',
			sortable : true
		}, {
//			display : '预设折旧方法Id',
//			name : 'deprId',
//			sortable : true,
//			hide : true
//		}, {
			display : '预设折旧方法',
			name : 'depr',
			sortable : true

		}, {
//			display : '折旧科目Id',
//			name : 'subId',
//			sortable : true,
//			hide : true
//		}, {
//			display : '折旧科目',
//			name : 'subName',
//			sortable : true,
//			hide : true
//		}, {
			display : '折旧状态',
			name : 'isDepr',
			sortable : true,
			width : 170,
			process : function(val) {
				if (val == "1") {
					return "由使用状态决定是否提折旧";
				}
				if (val == "2") {
					return "不管使用状态如何一定提折旧";
				}
				if (val == "3") {
					return "不管使用状态如何一定不提折旧";
				}
			}
		}],
		toAddConfig : {
			formWidth : 1000,
			formHeight : 300
		},
		toViewConfig : {
			formWidth : 1000,
			formHeight : 300
		},

		//		 扩展按钮
		buttonsEx : [{
			name : 'import',
			text : '导入',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_basic_directory&action=toImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=550");
			}
		}],
		/**
		 * 删除属性配置
		 */
		toDelConfig : {
			text : '删除',
			/**
			 * 默认点击删除按钮触发事件
			 */
			toDelFn : function(p, g) {
				var rowIds = g.getCheckedRowIds();
				var rowObj = g.getFirstSelectedRow();
				var key = "";
				if (rowObj) {
					var rowData = rowObj.data('data');
					if (rowData['skey_']) {
						key = rowData['skey_'];
					}
				}
				if (rowIds[0]) {
					if (window.confirm("确认要删除?")) {
						$.ajax({
							type : "POST",
							url : "?model=" + p.model + "&action="
									+ p.toDelConfig.action
									+ p.toDelConfig.plusUrl,
							data : {
								id : g.getCheckedRowIds().toString(),
								skey : key
							// 转换成以,隔开方式
							},
							success : function(msg) {
								if (msg == 1) {
									if (window.show_page != "undefined") {
										show_page();
									} else {
										g.reload();
									}
									alert('删除成功！');
								} else if (msg == 2) {
									alert('非法访问');
								} else if (msg != '') {
									alert('该分类已被引用，不允许删除！');
								} else {
									alert('删除失败，该对象可能已经被引用!');
								}
							}
						});
					}
				} else {
					alert('请选择一行记录！');
				}
			},
			/**
			 * 删除默认调用的后台方法
			 */
			action : 'ajaxdeletes',
			/**
			 * 追加的url
			 */
			plusUrl : ''
		},

		searchitems : [{
			display : '资产类别',
			name : 'name'
		}, {
			display : '资产类别编码',
			name : 'code'
		}, {
			display : '折旧科目',
			name : 'subName'
		}, {
			display : '固定资产科目',
			name : 'assetSubject'
		}],
		// 业务对象名称
		//	boName : '全部',
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序 降序
		sortorder : "DESC"

	});
});
