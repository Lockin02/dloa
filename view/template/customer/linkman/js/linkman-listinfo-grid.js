(function($) {
	$.woo.yxgrid.subclass('woo.yxgrid_linkman', {
		options : {
			model : 'customer_linkman_linkman',
//            action : 'linkmanPageJson',
		isViewAction : false,
		isEditAction : false,

      buttonsEx : [{
				// 导入EXCEL文件按钮
				name : 'import',
				text : "导入EXCEL",
				icon : 'excel',
				action : function(row) {
					showThickboxWin("?model=customer_linkman_linkman&action=toUplod"
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=270&width=500");
				}
			}],
       menusEx : [
		{
			text : '查看',
			icon : 'view',

			action : function(row) {
				showThickboxWin('?model=customer_linkman_linkman&action=init&id='
						+ row.id
						+ '&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '编辑',
			icon : 'edit',
			action : function(row) {
				showThickboxWin('?model=customer_linkman_linkman&action=init&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}],
		toAddConfig : {
			formWidth : 900,
			formHeight : 500
		},

		// 表单
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '客户',
			name : 'customerName',
			sortable : true,
			width : 150
		}, {
			display : '区域',
			name : 'areaName',
			sortable : true,
			width : 80
		}, {
			display : '姓名',
			name : 'linkmanName',
			sortable : true
		}, {
			display : '电话号码',
			name : 'phone',
			sortable : true
		}, {
			display : '手机号码',
			name : 'mobile',
			sortable : true
		}, {
			display : 'QQ',
			name : 'QQ',
			sortable : true
		}, {
			display : 'MSN',
			name : 'MSN',
			sortable : true,
			width : 150
		}, {
			display : 'email',
			name : 'email',
			sortable : true,
			width : 150
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [
			{
			display : '客户名称',
			name : 'customerName'
		},{
			display : '姓名',
			name : 'linkmanName'
		}],
		sortorder : "ASC",
		title : '所有客户联系人'
		}
	});
})(jQuery);