var show_page = function(page) {
	$("#compensateGrid").yxsubgrid("reload");
};
$(function() {
	$("#compensateGrid").yxsubgrid({
		model : 'finance_compensate_compensate',
		title : '�⳥���б�',
		isOpButton : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
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
			name : 'deductMoney',
			display : '�����⳥���',
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
					case '0' : return '��ȷ��';break;
					case '1' : return '�����ȷ��';break;
					case '2' : return '���⳥ȷ��';break;
					case '4' : return '�����';break;
					case '5' : return '�ر�';break;
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
		//��һ��ȷ�ϡ��رղ˵�
		menusEx : [{
			text : 'ȷ�Ͻ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if ((row.formStatus == "0" || row.formStatus == "1") && (row.ExaStatus == '���ύ' || row.ExaStatus == "���")) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin("?model=finance_compensate_compensate&action=toEdit&id="
					+ row.id
					+ "&isConfirm=1"
					+ "&skey=" + row.skey_ ,1,700,1100,row.id);
			}
		},{
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.formStatus == "1" && (row.ExaStatus == '���ύ' || row.ExaStatus == "���")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/finance/compensate/ewf_compensate.php?actTo=ewfSelect&billId='
						+ row.id
						+ "&flowMoney=" + row.formMoney
						+ "&billDept=" + row.deptId
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '��������',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == "��������") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAuditedContract",
						data : {
							billId : row.id,
							examCode : 'oa_finance_compensate'
						},
						success : function(msg) {
							//alert(msg);exit;
							if (msg == '1') {
								alert('����ʧ�ܣ�ԭ��\n1.�ѳ�������,�����ظ�����\n2.�����Ѿ�����������Ϣ�����ܳ�������');
								return false;
							}else{
								$.ajax({
								    type: "GET",
								    url: 'controller/finance/compensate/ewf_compensate.php?actTo=delWork',
								    data: {"billId" : row.id },
								    async: false,
								    success: function(data){
								    	alert(data);
								    	show_page();
									}
								});
							}
						}
					});
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			text : 'ȷ���⳥',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dutyType == 'PCZTLX-02' && row.formStatus == '2') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=finance_compensate_compensate&action=toDeduct&id="
	                    + row.id
	                    + "&skey=" + row.skey_
	                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
			}
//		},{
//			text : 'ȡ���⳥ȷ��',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.formStatus == "3") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(confirm('ȷ�Ͻ��� ȡ���⳥ȷ�� ������')){
//					$.ajax({
//					    type: "POST",
//					    url: '?model=finance_compensate_compensate&action=ajaxUnComConfirm',
//					    data: {"id" : row.id},
//					    async: false,
//					    success: function(data){
//					    	if(data == "1"){
//								alert('ȡ��ȷ�ϳɹ�');
//					    	}else{
//					    		alert('ȡ��ȷ��ʧ��');
//					    	}
//					    	show_page();
//						}
//					});
//				}
//			}
//		},{
//			text : '�������',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.formStatus == "3") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(confirm('ȷ�Ͻ��� ������� ������')){
//					$.ajax({
//					    type: "POST",
//					    url: '?model=finance_compensate_compensate&action=ajaxAudit',
//					    data: {"id" : row.id},
//					    async: false,
//					    success: function(data){
//					    	if(data == "1"){
//								alert('��˳ɹ�');
//					    	}else{
//					    		alert('���ʧ��');
//					    	}
//					    	show_page();
//						}
//					});
//				}
//			}
//		},{
//			text : 'ȡ���������',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if(row.auditTime != ""){
//					var auditDate = row.auditTime.substring(0,10);
//					var thisDate = $("#thisDate").val();
//					if(DateDiff(auditDate,thisDate) > 1){
//						return false;
//					}
//				}
//				if (row.formStatus == "4") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(confirm('ȷ�Ͻ��� ȡ��������� ������')){
//					$.ajax({
//					    type: "POST",
//					    url: '?model=finance_compensate_compensate&action=ajaxUnAudit',
//					    data: {"id" : row.id},
//					    async: false,
//					    success: function(data){
//					    	if(data == "1"){
//								alert('ȡ����˳ɹ�');
//					    	}else{
//					    		alert('ȡ�����ʧ��');
//					    	}
//					    	show_page();
//						}
//					});
//				}
//			}
//		},{
//			text : '�ر�',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if ((row.formStatus == "0" || row.formStatus == "1") && (row.ExaStatus == '���ύ' || row.ExaStatus == "���")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(confirm('ȷ�Ͻ��� �ر� ������')){
//					$.ajax({
//					    type: "POST",
//					    url: '?model=finance_compensate_compensate&action=close',
//					    data: {"id" : row.id},
//					    async: false,
//					    success: function(data){
//					    	if(data == "1"){
//								alert('�رճɹ�');
//					    	}else{
//					    		alert('�ر�ʧ��');
//					    	}
//					    	show_page();
//						}
//					});
//				}
//			}
		}],
		buttonsEx : [{
			text : '��ӡ',
			icon : 'print',
			action : function(row,rows,idArr) {
				if(row){
					idStr = idArr.toString();
					showModalWin("?model=finance_compensate_compensate&action=toBatchPrintAlone&id=" + idStr ,1);
				}else{
					alert('������ѡ��һ�ŵ��ݴ�ӡ');
				}
			}
		}],
		//��������
		comboEx : [{
			text : '����״̬',
			key : 'formStatus',
			data : [{
				text : '��ȷ��',
				value : '0'
			}, {
				text : '�����ȷ��',
				value : '1'
			}, {
				text : '���⳥ȷ��',
				value : '2'
			}, {
				text : '�����',
				value : '4'
			}, {
				text : '�ر�',
				value : '5'
			}]
		},{
		     text:'����״̬',
		     key:'ExaStatus',
		     type : 'workFlow'
		},{
		     text:'�⳥����',
		     key:'dutyType',
		     datacode : 'PCZTLX'
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
		},{
			display : "�豸������",
			name : 'chargerNameSearch'
		}]
	});
});