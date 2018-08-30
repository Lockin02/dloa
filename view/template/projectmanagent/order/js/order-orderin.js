var show_page = function(page) {
	$("#orderInGrid").yxgrid("reload");
};
$(function() {
	$("#orderInGrid").yxgrid({
		model : 'projectmanagent_order_order',
		action : 'orderContractInfoJson',
		title : '���ۺ�ͬ',
//		param : {
//			'appId' : $("#user").val()
//		},
		isDelAction : false,
		isToolBar : false, //�Ƿ���ʾ������
		showcheckbox : false,

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'ExaDT',
					display : '����ʱ��',
					sortable : true,
					width : 80
				}, {
					name : 'orderCode',
					display : '������ͬ��',
					sortable : true,
					width : 180
				}, {
					name : 'orderTempCode',
					display : '��ʱ��ͬ��',
					sortable : true,
					width : 180
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 100
				}, {
					name : 'orderName',
					display : '��ͬ����',
					sortable : true,
					width : 150
				}, {
					name : 'orderTempMoney',
					display : 'Ԥ�ƺ�ͬ���',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'orderMoney',
					display : 'ǩԼ��ͬ���',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true,
					width : 60
				}, {
					name : 'sign',
					display : '�Ƿ�ǩԼ',
					sortable : true,
					width : 70
				}, {
					name : 'areaName',
					display : '��������',
					sortable : true,
					width : 60
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
						} else if (v == '5') {
							return "�Ѳ��";
						}
					},
					width : 60
				}],
          comboEx : [
		 {
			text : '��ͬ״̬',
			key : 'state',
			data : [ {
				text : 'δ�ύ',
				value : '0'
			},{
				text : '������',
				value : '1'
			},{
				text : 'ִ����',
				value : '2'
			},{
				text : '�����',
				value : '4'
			},{
				text : '�ѹر�',
				value : '3'
			}  ]
		},{
			text : '����״̬',
			key : 'ExaStatus',
			data : [ {
				text : 'δ����',
				value : 'δ����'
			}, {
				text : '��������',
				value : '��������'
			},{
				text : '���',
				value : '���'
			},{
				text : '���',
				value : '���'
			}  ]
		}
		],
          menusEx : [{
			text : '�鿴',
			icon : 'view',
			action: function(row){
				     showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id=' + row.id + "&skey="+row['skey_']);
			}
		   }
			,{
			text : '����',
			icon : 'add',
			action: function(row){
				     window.open ('?model=contract_common_allcontract&action=importCont&id='
				                      + row.id
				                      +'&type=oa_sale_order'
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
			text : '��ɺ�ͬ',
			icon : 'edit',
			showMenuFn : function (row){
				   if(row.state == 2){
				       return true;
				   }
				       return false;
				},
			action: function(row){
				if (window.confirm(("ȷ��Ҫ�Ѻ�ͬ״̬��Ϊ ����ɡ� ״̬��"))) {
	                 	$.ajax({
							type : "POST",
							url : "?model=projectmanagent_order_order&action=completeOrder&id=" + row.id + "&type=oa_sale_order",
							success : function(msg) {
	                                $("#orderInGrid").yxgrid("reload");
							}
						});
	                 }
				}
		},{
			text : 'ִ�к�ͬ',
			icon : 'edit',
			showMenuFn : function (row){
			   if(row.state == 4){
			       return true;
			   }
			       return false;
			},
			action: function(row){
				if (window.confirm(("ȷ��Ҫ�Ѻ�ͬ״̬��Ϊ ��ִ���С� ״̬��"))) {
	                 	$.ajax({
							type : "POST",
							url : "?model=projectmanagent_order_order&action=exeOrder&id=" + row.id + "&type=oa_sale_order",
							success : function(msg) {
								   if(msg == '0'){
                                       alert("��ͬ��������ɣ���ѡ��������");
                                       $("#orderInGrid").yxgrid("reload");
								   }else{
								       $("#orderInGrid").yxgrid("reload");
								   }

							}
						});
	                 }
				}
		}],
			 /**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'orderName'
		},{
			display : '��ͬ���',
			name : 'orderCodeOrTempSearch'
		}],
		 sortname : "createTime",
          //��������ҳ����
          toAddConfig : {
			formHeight : 500 ,
			formWidth : 900
          },
          //���ñ༭ҳ����
          toEditConfig : {
			formHeight : 500 ,
			formWidth : 900
          },
          //���ò鿴ҳ����
          toViewConfig : {
			formHeight : 500 ,
			formWidth : 900
          }

	});
});