var show_page = function(page) {
	$("#financialImportDetailGrid").yxsubgrid("reload");
};
$(function() {
	$("#financialImportDetailGrid").yxgrid({
		model : 'projectmanagent_order_order',
		action : 'financialImportDetailpageJson&id='+$("#conId").val()+'&tablename='+$("#tablename").val()+'&moneyType='+$("#moneyType").val(),
		title : '������ϸ',
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
					name : 'improtMonth',
					display : '�����·�',
					sortable : true,
					width : 100
				}, {
					name : 'moneyType',
					display : '������',
					sortable : true,
					width : 100
				}, {
					name : 'moneyNum',
					display : '������',
					sortable : true,
					width : 100
				}, {
					name : 'importName',
					display : '������',
					sortable : true,
					width : 100
				}, {
					name : 'importDate',
					display : '����ʱ��',
					sortable : true,
					width : 100
				}, {
					name : 'isUse',
					display : '�Ƿ���Ч',
					sortable : true,
					width : 60,
					process : function(v,row){
					   if(v == '0'){
					      return "��";
					   }else{
					      return "��";
					   }
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