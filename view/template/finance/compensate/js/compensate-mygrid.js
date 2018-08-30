var show_page = function(page) {
	$("#compensateGrid").yxsubgrid("reload");
};
$(function() {
	$("#compensateGrid").yxsubgrid({
		model : 'finance_compensate_compensate',
		title : '�⳥���б�',
		param : {
			ExaStatus : '���',
			dutyType : 'PCZTLX-01',
			dutyObjId : $("#dutyObjId").val(),
			formStatusArr : '2,3,4'
		},
		isOpButton : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			width : 110,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=finance_compensate_compensate&action=toView&id="+row.id+"\",1,600,1000,"+row.id+")'>"+v+"</a>";
			}
		}, {
			name : 'formDate',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'objId',
			display : 'ҵ��id',
			sortable : true,
			hide : true
		}, {
			name : 'objType',
			display : 'ҵ������',
			sortable : true,
			width : 60,
			datacode : 'PCYWLX'
		}, {
			name : 'objCode',
			display : 'ҵ�񵥱��',
			sortable : true
		}, {
			name : 'dutyTypeName',
			display : '�⳥����',
			sortable : true,
			width : 60,
			process : function(v){
				return (v == "NULL")? "" : v;
			}
		}, {
			name : 'dutyObjName',
			display : '�⳥����',
			sortable : true
		}, {
			name : 'formMoney',
			display : '���ݽ��',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'compensateMoney',
			display : 'ȷ���⳥���',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'formStatus',
			display : '����״̬',
			sortable : true,
			process : function(v){
				switch(v){
					case '2' : return '���⳥ȷ��';break;
					case '3' : return '��ȷ��';break;
					case '4' : return '�����';break;
					default : return v;
				}
			},
			width : 70
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 70
		}, {
			name : 'ExaDT',
			display : '��������',
			sortable : true,
			width : 70
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
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
		}, {
			name : 'confirmName',
			display : '���ȷ����',
			sortable : true,
			width : 80
		}, {
			name : 'confirmTime',
			display : '���ȷ��ʱ��',
			sortable : true,
			width : 120
		}, {
			name : 'comConfirmName',
			display : '�⳥ȷ����',
			sortable : true,
			width : 80
		}, {
			name : 'comConfirmTime',
			display : '�⳥ȷ��ʱ��',
			sortable : true,
			width : 120
		}, {
//			name : 'auditorName',
//			display : '���������',
//			sortable : true,
//			width : 80
//		}, {
//			name : 'auditTime',
//			display : '���ʱ��',
//			sortable : true,
//			width : 120
//		}, {
			name : 'closerName',
			display : '�ر���',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'closeTime',
			display : '�ر�ʱ��',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'relDocType',
			display : 'Դ������',
			sortable : true,
			width : 60,
			datacode : 'PCYDLX'
		}, {
			name : 'relDocCode',
			display : 'Դ�����',
			sortable : true,
			width : 120
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=finance_compensate_compensatedetail&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productNo',
				display : '���ϱ��',
				width : 80
			},{
				name : 'productName',
				display : '��������',
				width : 140
			},{
				name : 'productModel',
				display : '����ͺ�',
				width : 140
			},{
				name : 'unitName',
				display : '��λ',
				width : 60
			},{
				name : 'number',
				display : '����',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				name : 'price',
				display : '����',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				name : 'money',
				display : '���',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				name : 'compensateRate',
				display : '�⳥����',
				process : function(v){
					return v + " %" ;
				},
				width : 80
			},{
				name : 'compensateMoney',
				display : '�⳥���',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}]
		},
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin(
					"?model=finance_compensate_compensate&action=toView&id=" + row[p.keyField]
					,1,600,1100,row.id);
			}
		},
		menusEx : [{
			name : 'edit',
			text : 'ȷ���⳥',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.formStatus == '2') {
					return true;
				} else{
					return false;
				}
			},
			action : function(row) {
				if (confirm('ȷ���⳥��')) {
					$.ajax({
						type : 'POST',
						url : '?model=finance_compensate_compensate&action=ajaxComConfirm',
						data : {
							id : row.id
						},
						success : function(data) {
							if (data == 1) {
								alert("ȷ�ϳɹ�");
								show_page();
							} else {
								alert("ȷ��ʧ��");
							}
						}
					});
				}
			}
		}],
		//��������
		comboEx : [{
			text : '����״̬',
			key : 'formStatus',
			value : '2',
			data : [{
				text : '���⳥ȷ��',
				value : '2'
			}, {
				text : '��ȷ��',
				value : '3'
			}, {
				text : '�����',
				value : '4'
			}]
		}],
		searchitems : [{
			display : "���ݱ��",
			name : 'formCodeSearch'
		},{
			display : "ҵ�񵥱��",
			name : 'objCodeSearch'
		},{
			display : "Դ�����",
			name : 'relDocCodeSearch'
		}]
	});
});