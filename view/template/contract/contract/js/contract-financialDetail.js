var show_page = function(page) {
	$("#financialdetailGrid").yxsubgrid("reload");
};
$(function() {
	$("#financialdetailGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'financialdetailpageJson&id='+$("#conId").val()+'&tablename='+$("#tablename").val()+'&moneyType='+$("#moneyType").val(),
		title : '�����ϸ',
		isDelAction : false,
		isToolBar : false, // �Ƿ���ʾ������
		showcheckbox : false,
		isOpButton:false,
		customCode : 'financialdetail',
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'year',
					display : '���',
					sortable : true,
					width : 60
				}, {
					name : 'January',
					display : 'һ�·�',
					sortable : true,
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'February',
					display : '���·�',
					sortable : true,
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'March',
					display : '���·�',
					sortable : true,
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'April',
					display : '���·�',
					sortable : true,
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'May',
					display : '���·�',
					sortable : true,
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'June',
					display : '���·�',
					sortable : true,
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'July',
					display : '���·�',
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'August',
					display : '���·�',
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'September',
					display : '���·�',
					sortable : true,
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'October',
					display : 'ʮ�·�',
					sortable : true,
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'November',
					display : 'ʮһ�·�',
					sortable : true,
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'December',
					display : 'ʮ���·�',
					sortable : true,
					width : 60,
					process : function(v) {
						return moneyFormat2(v);
					}
				}],
		sortname : "createTime",
		// ��������ҳ����
		toAddConfig : {
			formHeight : 500,
			formWidth : 900
		},
		// ���ñ༭ҳ����
		toEditConfig : {
			formHeight : 500,
			formWidth : 900
		},
		// ���ò鿴ҳ����
		toViewConfig : {
			formHeight : 500,
			formWidth : 900
		}

	});

});