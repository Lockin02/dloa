var show_page = function(page) {
	$("#signinGrid").yxgrid("reload");
};

$(function() {
	$("#signinGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'pageJsons',
		param : {
			'states' : '1,2,3,4,5,6,7',
			'isTemp' : '0',
			'ExaStatus' : '���',
			'signStatusArr' : '0,2'
		},

		title : 'δǩ�պ�ͬ',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'contractSignin',
		buttonsEx : [{
			name : 'export',
			text : "����",
			icon : 'excel',
			action : function(row) {
				var searchConditionKey = "";
				var searchConditionVal = "";
				for (var t in $("#signinGrid").data('yxgrid').options.searchParam) {
					if (t != "") {
						searchConditionKey += t;
						searchConditionVal += $("#signinGrid")
								.data('yxgrid').options.searchParam[t];
					}
				}
				var state = $("#state").val();
				var ExaStatus = $("#ExaStatus").val();
				var contractType = $("#contractType").val();
				var beginDate = $("#signinGrid").data('yxgrid').options.extParam.beginDate;//��ʼʱ��
				var endDate = $("#signinGrid").data('yxgrid').options.extParam.endDate;//��ֹʱ��
				var ExaDT = $("#signinGrid").data('yxgrid').options.extParam.ExaDT;//����ʱ��
				var areaNameArr = $("#signinGrid").data('yxgrid').options.extParam.areaNameArr;//��������
				var orderCodeOrTempSearch = $("#signinGrid").data('yxgrid').options.extParam.orderCodeOrTempSearch;//��ͬ���
				var prinvipalName = $("#signinGrid").data('yxgrid').options.extParam.prinvipalName;//��ͬ������
				var customerName = $("#signinGrid").data('yxgrid').options.extParam.customerName;//�ͻ�����
				var customerType = $("#signinGrid").data('yxgrid').options.extParam.customerType;//�ͻ�����
				var orderNatureArr = $("#signinGrid").data('yxgrid').options.extParam.orderNatureArr;//��ͬ����
				var DeliveryStatusArr = $("#signinGrid").data('yxgrid').options.extParam.DeliveryStatusArr;//�Ƿ��з�����¼
				var i = 1;
				var colId = "";
				var colName = "";
				$("#signinGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})

			window.open("?model=contract_contract_contract&action=singInExportExcel&colId="
								+ colId + "&colName=" + colName+ "&state=" + state + "&ExaStatus=" + ExaStatus + "&contractType=" + contractType
								+ "&beginDate=" + beginDate + "&endDate=" + endDate + "&ExaDT=" + ExaDT
								+ "&areaNameArr=" + areaNameArr + "&orderCodeOrTempSearch=" + orderCodeOrTempSearch
								+ "&prinvipalName=" + prinvipalName + "&customerName=" + customerName
								+ "&customerType=" + customerType
								+ "&orderNatureArr=" + orderNatureArr
								+ "&DeliveryStatusArr=" + DeliveryStatusArr
								+ "&searchConditionKey="
								+ searchConditionKey
								+ "&searchConditionVal="
								+ searchConditionVal
								+ "&signStatusArr=0,2"
//								+ "&ExaStatus=���,���������"
//								+ "&states=2,3,4"
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		},{
			name : 'advancedsearch',
			text : "�߼�����",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=contract_contract_contract&action=search&gridName=signinGrid"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
			}
		}],
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=toViewTab&id='
							+ row.id + "&skey=" + row['skey_']);
			}
		},{
			text : 'ǩ�պ�ͬ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isAcquiring == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=signEditView&id='
							+ row.id + "&skey=" + row['skey_']);
			}
		},{
			text : '��ͬ�յ�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isAcquiring == '1') {
					return false;
				}
				return true;
			},
			action : function(row) {
			    if (window.confirm(("ȷ�����յ���?"))) {
					$.ajax({
						type : "POST",
						url : "?model=contract_contract_contract&action=ajaxAcquiring",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�յ��ɹ���');
								$("#signinGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}, {

			text : '���¸�������',
			icon : 'add',
			action : function(row) {

				showThickboxWin('?model=contract_contract_receiptplan&action=updatePlan&contractId='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1000');

			}
		}],

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'isAcquiring',
			display : '���յ�',
			sortable : true,
			process : function(v){
				if(v == "1"){
					return '<img title="���յ�" src="images/icon/ok3.png">';
				}
			},
			width : 50,
			align : 'center'
		}, {
			name : 'checkStatus',
			display : '������',
			sortable : true,
			process : function(v){
				if(v == "������"){
					return '<img title="������" src="images/icon/ok3.png">';
				}
			},
			width : 50,
			align : 'center'
			
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'contractType',
			display : '��ͬ����',
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'contractNatureName',
			display : '��ͬ����',
			sortable : true,
			width : 60
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 180,
			process : function(v, row) {
					return  '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id='
						+ row.id
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'+"<font color = '#4169E1'>" + v +"</font>"+'</a>';
			}
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 100
		}, {
			name : 'customerId',
			display : '�ͻ�Id',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'customerType',
			display : '�ͻ�����',
			sortable : true,
			datacode : 'KHLX',
			width : 70
		}, {
			name : 'contractName',
			display : '��ͬ����',
			sortable : true,
			width : 150
		}, {
			name : 'signStatus',
			display : 'ǩ��״̬',
			sortable : true,
			width : 80,
			process:function(v){
			    if(v=='0'){
			        return "δǩ��";
			    }else if(v=='1'){
			        return "��ǩ��";
			    }else if(v=='2'){
			        return "���δǩ��";
			    }
			}
		}, {
			name : 'contractMoney',
			display : '��ͬ���',
			sortable : true,
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'invoiceMoney',
			display : '��Ʊ���',
			sortable : true,
			width : 80,
			process : function(v, row) {
			  return moneyFormat2(v);
			}
		}, {
			name : 'surplusInvoiceMoney',
			display : 'ʣ�࿪Ʊ���',
			sortable : true,
			process : function(v, row) {
				return "<font color = 'blue'>" + v + "</font>"
			}
		}, {
			name : 'incomeMoney',
			display : '���ս��',
			width : 60,
			sortable : true,
			process : function(v, row) {
				return moneyFormat2(v);
			}
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 60
		}, {
			name : 'areaName',
			display : '��������',
			sortable : true,
			width : 60
		}, {
			name : 'areaPrincipal',
			display : '��������',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '��ͬ������',
			sortable : true,
			width : 80
		}, {
			name : 'state',
			display : '��ͬ״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ�ύ";
				} else if (v == '1') {
					return "������";
				} else if (v == '2') {
					return "ִ����";
				} else if (v == '3') {
					return "�ѹر�";
				} else if (v == '4') {
					return "�����";
				} else if (v == '5') {
					return "�Ѻϲ�";
				} else if (v == '6') {
					return "�Ѳ��";
				} else if (v == '7') {
					return "�쳣�ر�";
				}
			},
			width : 60
		}, {
			name : 'objCode',
			display : 'ҵ����',
			sortable : true,
			width : 120
		}, {
					name : 'signContractType',
					display : "ǩ�պ�ͬ����",
					sortable : true,
					datacode : 'HTLX',
					width : 60
		}, {
			name : 'isAcquiring',
			display : '�յ�״̬',
			sortable : true,
			width : 80,
			process:function(v,row){
			    if(v=='0'){
			    	if(row.isAcquiringDate != '')
			           return "<span style='color:red'>δ�յ�</span>";
			        else
			           return "δ�յ�"
			    }else if(v=='1'){
			        return "���յ�";
			    }
			}
		}, {
			name : 'ExaDTOne',
			display : '����ʱ��',
			sortable : true,
			width : 80
		}, {
			name : 'isAcquiringDate',
			display : '�յ�����',
			sortable : true,
			width : 80
		}, {
			name : 'signinDate',
			display : 'ǩ������',
			sortable : true,
			width : 80
		},{
			name : 'isExceed',
			display : '�Ƿ���',
			sortable : false,
			width : 80,
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (v == '1') {
					return "<span style='color:red'>�ѳ���</span>";
//					return "<img src='images/icon/icon073.gif' />";
				} else {
					return "δ����";
//					return "<img src='images/icon/green.gif' />";
				}
			}
		}],
		lockCol : ['conflag','exeStatus'],// ����������
		comboEx : [{
			text : 'ǩ��״̬',
			key : 'signStatus',
			data : [{
				text : 'δǩ��',
				value : '0'
			}, {
				text : '��ǩ��',
				value : '1'
			}]
		},{
			text : '����',
			key : 'contractType',
			data : [{
				text : '���ۺ�ͬ',
				value : 'HTLX-XSHT'
			}, {
				text : '���޺�ͬ',
				value : 'HTLX-FWHT'
			}, {
				text : '�����ͬ',
				value : 'HTLX-ZLHT'
			}, {
				text : '�з���ͬ',
				value : 'HTLX-YFHT'
			}]
		}, {
			text : '��ͬ״̬',
			key : 'state',
			data : [{
				text : '������',
				value : '1'
			}, {
				text : 'ִ����',
				value : '2'
			}, {
				text : '�����',
				value : '4'
			}, {
				text : '�ѹر�',
				value : '3'
			}, {
				text : '�Ѻϲ�',
				value : '5'
			}, {
				text : '�Ѳ��',
				value : '6'
			}, {
				text : '�쳣�ر�',
				value : '7'
			}]
		}, {
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : 'δ����',
				value : 'δ����'
			}, {
				text : '��������',
				value : '��������'
			}, {
				text : '���������',
				value : '���������'
			}, {
				text : '���',
				value : '���'
			}, {
				text : '���',
				value : '���'
			}]
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ���',
			name : 'contractCode'
		}, {
			display : '��ͬ����',
			name : 'contractName'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : 'ҵ����',
			name : 'objCode'
		}],
		sortname : "createTime"
});
});