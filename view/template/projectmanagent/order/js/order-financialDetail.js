var show_page = function(page) {
	$("#financialdetailGrid").yxsubgrid("reload");
};
$(function() {
	$("#financialdetailGrid").yxgrid({
		model : 'projectmanagent_order_order',
		action : 'financialdetailpageJson&id='+$("#conId").val()+'&tablename='+$("#tablename").val()+'&moneyType='+$("#moneyType").val(),
		title : '�����ϸ',
		isDelAction : false,
		isToolBar : false, // �Ƿ���ʾ������
		showcheckbox : false,
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
					width : 60
				}, {
					name : 'February',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'March',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'April',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'May',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'June',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'July',
					display : '���·�',
					width : 60
				}, {
					name : 'August',
					display : '���·�',
					width : 60
				}, {
					name : 'September',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'October',
					display : 'ʮ�·�',
					sortable : true,
					width : 60
				}, {
					name : 'November',
					display : 'ʮһ�·�',
					sortable : true,
					width : 60
				}, {
					name : 'December',
					display : 'ʮ���·�',
					sortable : true,
					width : 60
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