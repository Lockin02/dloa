var show_page = function(page) {
	$("#chargeOrderGrid").yxgrid("reload");
};
$(function() {
	$("#chargeOrderGrid").yxgrid({
		model : 'projectmanagent_order_order',
		title : '�ѹرյĺ�ͬ',
		action : 'myOrderPageJson',
		param : {
			'states' : '3,9',
			"prinvipalId" : $("#userId").val()
		},
		isDelAction : false,
		isToolBar : false, //�Ƿ���ʾ������
		showcheckbox : false,
		customCode :'myordercharge',
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
			width : 210
		}, {
			name : 'orderTempCode',
			display : '��ʱ��ͬ��',
			sortable : true,
			width : 210
		}, {
			name : 'orderName',
			display : '��ͬ����',
			sortable : true,
			width : 210
		}, {
			name : 'customerName',
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
					name : 'objCode',
					display : 'ҵ����',
					width : 150
				}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id='
						+ row.id
						+ '&perm=view'
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
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
				window.open('?model=contract_common_allcontract&action=importCont&id='
								+ row.id
								+ '&type=oa_sale_order'
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		},{
			text : '�����ϴ�',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.id
				                      +'&type=oa_sale_order'
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		},{

			text : '�����ͬ',
			icon : 'add',

			action : function(row) {

				showThickboxWin('?model=projectmanagent_order_order&action=toShare&id='
				        + row.id
				        +"&skey="+row['skey_']
	                    +'&type=oa_sale_order'
				        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');

			}
		}],
		/**
		 * ��������
		 */
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
		sortname : "createTime",
		//��������ҳ����
		toAddConfig : {
			formHeight : 500,
			formWidth : 900
		},
		//���ñ༭ҳ����
		toEditConfig : {
			formHeight : 500,
			formWidth : 900
		},
		//���ò鿴ҳ����
		toViewConfig : {
			formHeight : 500,
			formWidth : 900
		}

	});
});