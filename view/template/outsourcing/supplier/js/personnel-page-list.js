var show_page = function(page) {
	$("#pageGrid").yxgrid("reload");
};
$(function() {
	var suppCode=$("#suppCode").val();
	$("#pageGrid").yxgrid({
		model : 'outsourcing_supplier_personnel',
		title : '��Ӧ����Ա��Ϣ',
		param:{'suppCode':suppCode},
		bodyAlign:'center',
		showcheckbox:false,
		isDelAction:false,
		//��ͷ����
		buttonsEx : [ {
							name : 'expport',
							text : "����",
							icon : 'excel',
							action : function(row) {
								showThickboxWin("?model=outsourcing_supplier_personnel&action=toExcelExport"
										+ "&placeValuesBefore&TB_iframe=true&modal=false&height=650&width=950")

							}
						},{
							name : 'expport',
							text : "����",
							icon : 'excel',
							action : function(row) {
								showThickboxWin("?model=outsourcing_supplier_personnel&action=toExcelImport"
										 + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")

							}
						}],
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'suppCode',
			display : '��Ӧ�̱��',
			width:70,
			sortable : true,
			hide : true
		}, {
			name : 'suppName',
			display : '�����Ӧ��',
			width:150,
			sortable : true
		},   {
			name : 'userName',
			display : '����',
			width:70,
			sortable : true
		},   {
			name : 'userAccount',
			display : 'OA�˺�',
			width:70,
			sortable : true
		},{
			name : 'age',
			display : '����',
			width:50,
			sortable : true
		}, {
			name : 'mobile',
			display : '��ϵ�绰',
			width:100,
			sortable : true
		}, {
			name : 'email',
			display : '����',
			width:120,
			sortable : true
		}, {
			name : 'highEducationName',
			display : 'ѧ��',
			width:70,
			sortable : true
		}, {
			name : 'highSchool',
			display : '��ҵѧУ',
			width:120,
			sortable : true
		}, {
			name : 'professionalName',
			display : 'רҵ',
			width:90,
			sortable : true
		}, {
			name : 'identityCard',
			display : '���֤��',
			width:150,
			sortable : true
		}, {
			name : 'workBeginDate',
			display : '��ʼ����ʱ��',
			width:90,
			sortable : true
		}, {
			name : 'workYears',
			display : '�������Ź�������',
			width:80,
			sortable : true
		},  {
			name : 'tradeList',
			display : '���̾����о�',
			width:150,
			sortable : true
		}, {
			name : 'certifyList',
			display : '��������',
			width:150,
			sortable : true
		}, {
			name : 'remark',
			display : '�����������',
			width:200,
			align:'left',
			sortable : true
		}],
		lockCol:['userName','suppName'],//����������
		toAddConfig : {
			action : 'toAdd&suppId='+$("#suppId").val(),
			formWidth : 800,
			formHeight : 500
		},

		toEditConfig : {
			action : 'toEdit',
			formWidth : 800,
			formHeight : 500
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
							display : "����",
							name : 'userName'
						},{
							display : "��ϵ�绰",
							name : 'mobile'
						},{
							display : "����",
							name : 'email'
						},{
							display : "��ҵѧУ",
							name : 'highSchool'
						},{
							display : "רҵ",
							name : 'professionalName'
						},{
							display : "���֤��",
							name : 'identityCard'
						},{
							display : "���̾����о�",
							name : 'tradeList'
						},{
							display : "��������",
							name : 'certifyList'
						}]
	});
});