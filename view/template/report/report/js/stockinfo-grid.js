var show_page = function(page) {
	$("#stockinfoGrid").yxgrid("reload");
};
$(function() {
	var titleName = $("#dataType").val() == 1? "产品辅料表基本信息" : "产品库存报表基本信息";
	$("#stockinfoGrid").yxgrid({
		model : 'report_report_stockinfo',
		action : 'stockJson',
		param : {
			'dataType' : $("#dataType").val()
		},
		title : titleName,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'budgetTypeName',
					display : '设备类型',
					sortable : true
				}, {
					name : 'equCode',
					display : '设备编号',
					sortable : true,
					hide : true
				}, {
					name : 'equName',
					display : '设备名称',
					sortable : true,
					width : 180
				}, {
					name : 'linkequNames',
					display : '关联设备',
					sortable : true,
					width : 180
				}, {
					name : 'needNum',
					display : '需求数量',
					sortable : true,
					width : 50,
					process : function(v, row) {
						return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=report_report_stockinfo&action=numViewTab&cids='
								+ row.cids
								+ "&bids="
								+ row.bids
								+ "&pids="
								+ row.pids
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
					}
				}, {
					name : 'pattern',
					display : '规格型号',
					sortable : true
				}, {
					name : 'netWork',
					display : '支持网络',
					sortable : true,
					width : 180
				}, {
					name : 'software',
					display : '支持软件',
					sortable : true,
					width : 180
				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					width : 200
				}],
		toAddConfig : {
			toAddFn : function(p) {
					var c = p.toAddConfig;
					var w = c.formWidth ? c.formWidth : p.formWidth;
					var h = c.formHeight ? c.formHeight : p.formHeight;
					showThickboxWin("?model="
							+ p.model
							+ "&action="
							+ c.action
							+ c.plusUrl
							+ "&dataType="
							+ $("#dataType").val()
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ h + "&width=" + w);
				},
			formWidth : 900,
			formHeight : 500
		},

		toEditConfig : {
			action : 'toEdit',
			formWidth : 900,
			formHeight : 500
		},
		toViewConfig : {
			action : 'toView',
			formWidth : 900,
			formHeight : 500
		},
		searchitems : [{
					display : "分类名称",
					name : 'budgetTypeName'
				}, {
					display : "设备编号",
					name : 'equCode'
				}, {
					display : "设备名称",
					name : 'equName'
				}, {
					display : "支持网路",
					name : 'netWork'
				}, {
					display : "支持软件",
					name : 'software'
				}]
	});
});