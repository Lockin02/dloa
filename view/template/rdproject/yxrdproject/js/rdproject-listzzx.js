// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".rdprojectListZzxGrid").yxgrid("reload");
};
$(function() {
	$(".rdprojectListZzxGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ

		model : 'rdproject_yxrdproject_rdproject',
		action : 'myOrderPageJson',
		param : {
			'states' : '2',
			"orderPrincipalId" : $("#userId").val()
		},
		title : '��ִ�е��з���ͬ',
		showcheckbox : false,
		isToolBar : false,
		customCode : 'rdlistzzx',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'orderCode',
			display : '������ͬ��',
			sortable : true,
			width : 210,
			process : function(v,row){
                         if(row.isR == 1){
                             return "<font color = '#0000FF'>" +v;
                         }else if(row.isBecome == 1){
                             return "<font color = '#FF0000'>" +v;
                         }else{
                             return v;
                         }
  					}
		}, {
			name : 'orderTempCode',
			display : '��ʱ��ͬ��',
			sortable : true,
			width : 210,
			process : function(v,row){
                         if(row.isR == 1){
                             return "<font color = '#0000FF'>" +v;
                         }else if(row.isBecome == 1){
                             return "<font color = '#FF0000'>" +v;
                         }else{
                             return v;
                         }
  					}
		}, {
			name : 'orderName',
			display : '��ͬ����',
			sortable : true,
			width : 210,
			process : function(v,row){
                         if(row.isR == 1){
                             return "<font color = '#0000FF'>" +v;
                         }else if(row.isBecome == 1){
                             return "<font color = '#FF0000'>" +v;
                         }else{
                             return v;
                         }
  					}
		}, {
			name : 'cusName',
			display : '�ͻ�����',
			sortable : true,
			width : 150
		}, {
			name : 'orderTempMoney',
			display : 'Ԥ�ƺ�ͬ���',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'orderMoney',
			display : 'ǩԼ��ͬ���',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		},{
			name : 'applyedMoney',
  			display : '�����뿪Ʊ���',
  			sortable : false,
  			process : function(v){
  				return moneyFormat2(v);
  			}
        }, {
			name : 'invoiceMoney',
			display : '��Ʊ���',
			sortable : false,
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'incomeMoney',
			display : '���ս��',
			sortable : false,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'surOrderMoney',
			display : 'ǩԼ��ͬӦ���˿����',
			sortable : false,
			width : 120,
			process : function(v, row) {
				return "<font color = 'blue'>" + moneyFormat2(accSub(row.orderMoney,row.incomeMoney,2)) + "</font>"
			}
		}, {
			name : 'surincomeMoney',
			display : '����Ӧ���˿����',
			sortable : false,
			process : function(v, row) {
				return "<font color = 'blue'>" + moneyFormat2(accSub(row.invoiceMoney,row.incomeMoney,2)) + "</font>"
			}
		}, {
			name : 'areaName',
			display : '��������',
			sortable : true,
			width : 100
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
				}
			},
			width : 90
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 100
		}, {
			name : 'sign',
			display : '�Ƿ�ǩԼ',
			sortable : true,
			width : 70
		}, {
			name : 'orderstate',
			display : 'ֽ�ʺ�ͬ״̬',
			sortable : true,
			width : 100
		}, {
			name : 'parentOrder',
			display : '����ͬ����',
			sortable : true,
			hide : true
		}, {
					name : 'objCode',
					display : 'ҵ����',
					width : 150
				}],

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : 'ȡ���������',
			icon : 'back',
			showMenuFn : function(row) {
				if (row.isBecome == '1') {
					return true;
				}
				return false;
			},
			action: function(row){
                 if (window.confirm(("ȷ��Ҫȡ�����������"))) {
                 	$.ajax({
						type : "POST",
						url : "?model=rdproject_yxrdproject_rdproject&action=cancelBecome&id=" + row.id,
						success : function(msg) {
                               $(".rdprojectListZzxGrid").yxgrid("reload");
						}
					});
                 }
			}
		   },{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id="
							+ row.id + "&skey=" + row['skey_'])
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : 'תΪ��ʽ��ͬ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.orderCode == '' && row.ExaStatus == '���') {
					return true;
				} else {
					return false;

				}

			},
			action : function(row) {
				showOpenWin('?model=rdproject_yxrdproject_rdproject&action=toBecomeContract&id='
						+ row.id
						+ '&perm=edit'
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '��Ʊ����',
			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.orderCode == ''){
//				    if(row.orderTempMoney - row.applyedMoney > 0){
//				       return true;
//				    }else{
//				       return false;
//				    }
//				}else{
//				    if(row.orderMoney - row.applyedMoney > 0){
//				       return true;
//				    }else{
//				       return false;
//				    }
//				}
//			},
			action : function(row) {
				if (row.orderCode != "") {
					showModalWin(
							'?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]='
									+ row.id + '&invoiceapply[objCode]='
									+ row.orderCode
									+ '&invoiceapply[objType]=KPRK-07', 1);
				} else {
					showModalWin(
							'?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]='
									+ row.id + '&invoiceapply[objCode]='
									+ row.orderTempCode
									+ '&invoiceapply[objType]=KPRK-08', 1);
				}
			}
		}, {

			text : '�����ͬ',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������' || row.ExaStatus == '���������') {
					return false;
				}
				return true;
			},
			action : function(row) {
				location = '?model=rdproject_yxrdproject_rdproject&action=toChange&changer=changer&id='
						+ row.id + "&skey=" + row['skey_'];
			}
		}, {

			text : '�رպ�ͬ',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == '3' || row.ExaStatus == '��������'
						|| row.ExaStatus == '���������') {
					return false;
				}
				return true;
			},
			action : function(row) {
               alert("��ͬ�رչ�����ʱͣ�ã�����������ϵOA����Ա");
//				showThickboxWin('?model=rdproject_yxrdproject_rdproject&action=CloseOrder&id='
//						+ row.id
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');

			}
		}, {
			text : '����',
			icon : 'add',
//			showMenuFn : function (row){
//				   if(row.exportOrder == 1){
//				       return true;
//				   }
//				       return false;
//				},
			action : function(row) {
				window
						.open('?model=contract_common_allcontract&action=importCont&id='
								+ row.id
								+ '&type=oa_sale_rdproject'
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '�����ϴ�',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=projectmanagent_order_order&action=toUploadFile&id='
						+ row.id
						+ '&type=oa_sale_rdproject'
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		}, {

			text : '�˻�����',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isR == 1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=projectmanagent_return_return&action=toAdd&orderId='
						+ row.id + "&orderType=oa_sale_rdproject");
			}
		},{

			text : '�����ͬ',
			icon : 'add',

			action : function(row) {

				showThickboxWin('?model=projectmanagent_order_order&action=toShare&id='
				        + row.id
				        +"&skey="+row['skey_']
	                    +'&type=oa_sale_rdproject'
				        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');

			}
		}],
		// ��������
		searchitems : [{
			display : '��ͬ����',
			name : 'orderName'
		}, {
			display : '��ͬ���',
			name : 'orderCodeOrTempSearch'
		},{
			display : 'ҵ����',
			name : 'objCode'
		}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		sortname : "isBecome desc,ExaDT",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false

	});

});