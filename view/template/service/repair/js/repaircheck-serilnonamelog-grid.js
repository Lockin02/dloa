var show_page = function(page) {
	$("#serilnoNameLogGrid").yxgrid("reload");
};
$(function() {
	$("#serilnoNameLogGrid").yxgrid({
		model : 'service_repair_repaircheck',
		title : '���к���ʷά�޼�¼',
		param : {
			'repairUserCode' : $("#repairUserCode").val(),//����ά����Ա����
			'serilnoName' : $("#serilnoName").val(),//�������кŹ���
			'docStatus' : 'YWX'	//ֻ��ʾ��ά�޵ļ�¼		
		},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isShowCheckBox : false,
		showcheckbox : false,
		isOpButton : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					hide : true
				}, {
					name : 'docCode',
					display : '���ݱ��',
					width : 120
				}, {
					name : 'issuedTime',
					display : '�´�ʱ��',
					width : 120
				}, {
					name : 'serilnoName',
					display : '���к�',
					width : 200
				}, {
					name : 'prov',
					display : 'ʡ��',
					width : 60
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					width : 200
				}, {
					name : 'contactUserName',
					display : '�ͻ���ϵ��',
					width : 80
				}, {
					name : 'telephone',
					display : '��ϵ�绰'
				}, {
					name : 'isGurantee',
					display : '�Ƿ����',
					width : 80,
					process : function(v) {
						if (v == '0') {
							return "��";
						} else if (v == '1') {
							return "��";
						}
					}
				}, {
					name : 'troubleType',
					display : '��������',
					width : 100
				}, {
					name : 'troubleInfo',
					display : '��������',
					sortable : true,
					width : 200
				}, {
					name : 'checkInfo',
					display : '��⴦����',
					sortable : true,
					width : 150

				}, {
					name : 'finishTime',
					display : 'ά�����ʱ��',
					sortable : true

				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					width : 150
				}],
		toViewConfig : {
			action : 'toView',
			formWidth : 800,
			formHeight : 500
		},
		searchitems : [{
					display : '���ݱ��',
					name : 'docCode'
				}, {
					display : 'ʡ��',
					name : 'prov'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}, {
					display : '��������',
					name : 'productName'
				}, {
					display : '��������',
					name : 'troubleType'
				}]
	});
});