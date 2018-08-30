var show_page = function(page) {
	$("#stockinfoGrid").yxgrid("reload");
};
$(function() {
	var titleName = $("#dataType").val() == 1? "��Ʒ���ϱ������Ϣ" : "��Ʒ��汨�������Ϣ";
	$("#stockinfoGrid").yxgrid({
		model : 'report_report_stockinfo',
		action : 'stockJson',
		param : {
			'dataType' : $("#dataType").val()
		},
		title : titleName,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'budgetTypeName',
					display : '�豸����',
					sortable : true
				}, {
					name : 'equCode',
					display : '�豸���',
					sortable : true,
					hide : true
				}, {
					name : 'equName',
					display : '�豸����',
					sortable : true,
					width : 180
				}, {
					name : 'linkequNames',
					display : '�����豸',
					sortable : true,
					width : 180
				}, {
					name : 'needNum',
					display : '��������',
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
					display : '����ͺ�',
					sortable : true
				}, {
					name : 'netWork',
					display : '֧������',
					sortable : true,
					width : 180
				}, {
					name : 'software',
					display : '֧�����',
					sortable : true,
					width : 180
				}, {
					name : 'remark',
					display : '��ע',
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
					display : "��������",
					name : 'budgetTypeName'
				}, {
					display : "�豸���",
					name : 'equCode'
				}, {
					display : "�豸����",
					name : 'equName'
				}, {
					display : "֧����·",
					name : 'netWork'
				}, {
					display : "֧�����",
					name : 'software'
				}]
	});
});