var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};
$(function() {

		//��ͷ��ť����
	var buttonsArr = [
//        {
//			name : 'view',
//			text : "�߼���ѯ",
//			icon : 'view',
//			action : function() {
//				showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900');
//			}
//        }
    ];
    //��ͷ��ť����
	var excelInArr = {
		name : 'exportIn',
		text : "������Ա��Ϣ",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_supplier_personnel&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	var excelOut = {
		name : 'exportOut',
		text : "������Ա��Ϣ",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_supplier_personnel&action=toExcellOut"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	};
//	$.ajax({
//		type : 'POST',
//		url : '?model=hr_personnel_personnel&action=getLimits',
//		data : {
//			'limitName' : '����Ȩ��'
//		},
//		async : false,
//		success : function(data) {
//			if (data ==1) {
				buttonsArr.push(excelInArr);
				buttonsArr.push(excelOut);
//			}
//		}
//	});

	$("#personnelGrid").yxgrid({
		model : 'outsourcing_supplier_personnel',
		title : '��Ӧ����Ա��Ϣ',
		isAddAction:true,
		isDelAction:false,
		bodyAlign:'center',
		showcheckbox:false,
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
		}, {
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
			action : 'toListAdd',
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

		buttonsEx : buttonsArr,

									// ��չ�Ҽ��˵�

		menusEx : [{
				text : 'ɾ��',
				icon : 'delete',
				action : function(row, rows, grid) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_supplier_personnel&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									$("#personnelGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			}],
		searchitems : [{
							display : "�����Ӧ��",
							name : 'suppName'
						},{
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
							display : "��������",
							name : 'skillTypeName'
						},{
							display : "���̾����о�",
							name : 'tradeList'
						},{
							display : "��������",
							name : 'certifyList'
						}]
	});
});