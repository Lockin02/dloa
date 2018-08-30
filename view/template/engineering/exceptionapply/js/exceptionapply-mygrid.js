var show_page = function(page) {
	$("#exceptionapplyGrid").yxgrid("reload");
};
$(function() {

	//��ͷ��ť����
	buttonsArr = [{
			name : 'add',
			text : "����",
			icon : 'add',
			items : [{
				text : '�����',
				icon : 'add',
				action : function(v){
					showThickboxWin('?model=engineering_exceptionapply_exceptionapply&action=toAdd'
						+ "&applyType=GCYCSQ-01"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
				}
			},{
				text : '������',
				icon : 'add',
				action : function(v){
					showThickboxWin('?model=engineering_exceptionapply_exceptionapply&action=toAdd'
						+ "&applyType=GCYCSQ-02"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
				}
			},{
				text : '�빺��',
				icon : 'add',
				action : function(v){
					showThickboxWin('?model=engineering_exceptionapply_exceptionapply&action=toAdd'
						+ "&applyType=GCYCSQ-03"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
				}
			},{
				text : '�⳵��',
				icon : 'add',
				action : function(v){
					showThickboxWin('?model=engineering_exceptionapply_exceptionapply&action=toAdd'
						+ "&applyType=GCYCSQ-04"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
				}
			}]
		}
	];

	$("#exceptionapplyGrid").yxgrid({
		model : 'engineering_exceptionapply_exceptionapply',
		title : '���̳�Ȩ������',
		isDelAction : false,
		isAddAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formNo',
				display : '���뵥��',
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_exceptionapply_exceptionapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=480&width=800\")'>" + v + "</a>";
				},
				width : 80
			}, {
				name : 'applyTypeName',
				display : '��������',
				sortable : true,
				width : 65
			}, {
				name : 'applyDate',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'applyMoney',
				display : '������',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'useRangeName',
				display : '���÷�Χ',
				sortable : true,
				width : 80
			}, {
				name : 'projectCode',
				display : '������Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '������Ŀ',
				sortable : true,
				width : 120
			}, {
				name : 'applyReson',
				display : '����ԭ��',
				sortable : true,
				width : 120
			}, {
				name : 'products',
				display : '���',
				sortable : true,
				hide : true
			}, {
				name : 'rentalType',
				display : '�⳵����',
				sortable : true,
				hide : true
			}, {
				name : 'rentalTypeName',
				display : '�⳵��������',
				sortable : true,
				hide : true
			}, {
				name : 'area',
				display : 'ʹ������',
				sortable : true,
				hide : true
			}, {
				name : 'carNumber',
				display : '������',
				sortable : true,
				hide : true
			}, {
				name : 'remark',
				display : '��ע��Ϣ',
				sortable : true,
				width : 120
			}, {
				name : 'ExaStatus',
				display : '���״̬',
				sortable : true,
				width : 80
			}, {
				name : 'ExaDT',
				display : '�������',
				sortable : true,
				width : 80
			}, {
				name : 'createName',
				display : '������',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				width : 120,
				hide : true
			}, {
				name : 'updateName',
				display : '�޸���',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				hide : true
			}],
		buttonsEx : buttonsArr,
		menusEx : [{
//			text : '�ύ����',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				showThickboxWin('controller/engineering/exceptionapply/ewf_index.php?actTo=ewfSelect&billId='
//					+ row.id
//					+ "&flowMoney=" + row.applyMoney
//					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//			}
//		},{
			text: "ɾ��",
			icon: 'delete',
			showMenuFn : function(row){
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action: function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_exceptionapply_exceptionapply&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page(1);
							}else{
								alert("ɾ��ʧ��! ");
							}
						}
					});
				}
			}
		}],
		toEditConfig : {
			action : 'toEdit',
			formHeight : 480,
			showMenuFn : function(row){
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			}
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 480
		},
        //��������
		comboEx:[{
		     text:'��������',
		     key:'applyType',
		     datacode : 'GCYCSQ'
		},{
		     text:'���״̬',
		     key:'ExaStatus',
		     data : [{
		     	'text' : '���',
		     	'value' : '���'
		     },{
		     	'text' : '�����',
		     	'value' : '�����'
		     },{
		     	'text' : '���ύ',
		     	'value' : '���ύ'
		     },{
		     	'text' : '���',
		     	'value' : '���'
		     }]
		}],
		searchitems : [{
			display : "���뵥��",
			name : 'formNoSearch'
		},{
			display : "��������",
			name : 'applyDateSearch'
		},{
			display : "������Ŀ",
			name : 'projectNameSearch'
		},{
			display : "������",
			name : 'applyMoney'
		}],
		sortname : 'createTime'
	});
});