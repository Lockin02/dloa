// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".assConfigGrid").yxgrid("reload");
};
$(function() {
	$(".assConfigGrid").yxgrid({
		//如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model: 'engineering_assessment_assConfig',

		// action : 'pageJson',
		title: '考核指标配置',
		showcheckbox: true,
		//显示checkbox
		isToolBar: true,
		//显示列表上方的工具栏
		param: {
			id: $("#id").val()
		},
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			display: '项目',
			name: 'name',
			sortable: true
		},
		{
			display: '分值',
			name: 'score',
			sortable: true
		}],
		//扩展按钮
		buttonsEx: [],
		//扩展右键菜单
		menusEx: [],
		//快速搜索
		searchitems: [{
			display: '项目',
			name: 'name'
		}],
		// title : '客户信息',
		//业务对象名称
		boName: '项目',
		//默认搜索字段名
		sortname: "name",
		//默认搜索顺序
		sortorder: "ASC",
		//显示查看按钮
		isViewAction: false,
		//隐藏添加按钮
		isAddAction: true,
		//隐藏编辑按钮
		isEditAction: false,
		//隐藏删除按钮
		isDelAction: true,





		menusEx : [{
				name : 'edit',
				text : "编辑",
				icon : 'edit',
				action : function(row,rows,grid) {
							showThickboxWin("?model=engineering_assessment_assConfig&action=toEdit&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=400");
				}
			}],



		toAddConfig: {
			text: '新增',
			/**
			 * 默认点击新增按钮触发事件
			 */
			toAddFn: function(p, g) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth: p.formWidth;
				var h = c.formHeight ? c.formHeight: p.formHeight;
				var rowObj = g.getSelectedRow();
				showThickboxWin("?model=" + p.model + "&action=" + c.action + "&id=" + $("#id").val()
				//													+ c.plusUrl
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=" + 300 + "&width=" + 400);
			},
			/**
			 * 新增表单调用的后台方法
			 */
			action: 'toAdd',
			/**
			 * 追加的url
			 */
			plusUrl: '',
			/**
			 * 新增表单默认宽度
			 */
			formWidth: 0,
			/**
			 * 新增表单默认高度
			 */
			formHeight: 0
		},
//		//修改扩展信息
//		toEditConfig: {
//			text: '编辑',
//			/**
//			 * 默认点击新增按钮触发事件
//			 */
//			toAddFn: function(p, g) {
//				var c = p.toAddConfig;
//				var w = c.formWidth ? c.formWidth: p.formWidth;
//				var h = c.formHeight ? c.formHeight: p.formHeight;
//				var rowObj = g.getSelectedRow();
//				showThickboxWin("?model=" + p.model + "&action=" + c.toEdit + "&id=" + $("#id").val()
//				//													+ c.plusUrl
//				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=" + 300 + "&width=" + 400);
//			},
//			/**
//			 * 新增表单调用的后台方法
//			 */
//			action: 'toEdit',
//			/**
//			 * 追加的url
//			 */
//			plusUrl: '',
//			/**
//			 * 新增表单默认宽度
//			 */
//			formWidth: 400,
//			/**
//			 * 新增表单默认高度
//			 */
//			formHeight: 300
//		},

		buttonsEx : [{
			separator : true
		},{
			name : 'close',
			text : "关闭",
			icon : 'edit',
			action : function() {
				self.parent.tb_remove();
				if(self.parent.show_page)self.parent.show_page(1);
			}
		}]
	});

});