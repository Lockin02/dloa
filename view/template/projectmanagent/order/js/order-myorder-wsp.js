var show_page = function(page) {
	$("#orderWspGrid").yxgrid("reload");
};
$(function() {
	$("#orderWspGrid").yxgrid({
		model : 'projectmanagent_order_order',
		action : 'wspOrderJson',
		title : 'δ�����ĺ�ͬ',
		param : {
			'ExaStatus' : 'δ����'
		},

		isDelAction : false,
		isToolBar : false, //�Ƿ���ʾ������
		showcheckbox : false,
        customCode : 'wsp',
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
					width : 80,
					hide : true
				},{
					name : 'tablename',
					display : '��ͬ����',
					sortable : true,
					width : 60,
					process : function(v) {
						if (v == 'oa_sale_order') {
							return "���ۺ�ͬ";
						} else if (v == 'oa_sale_service') {
							return "�����ͬ";
						} else if (v == 'oa_sale_lease') {
							return "���޺�ͬ";
						} else if (v == 'oa_sale_rdproject') {
							return "�з���ͬ";
						}
					}
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
				}, {
					name : 'objCode',
					display : 'ҵ����',
					width : 150
				}],
          comboEx : [ {
			text : '��ͬ����',
			key : 'tablename',
			data : [ {
				text : '���ۺ�ͬ',
				value : 'oa_sale_order'
			}, {
				text : '���޺�ͬ',
				value : 'oa_sale_lease'
			},{
				text : '�����ͬ',
				value : 'oa_sale_service'
			},{
				text : '�з���ͬ',
				value : 'oa_sale_rdproject'
			}  ]
		}],
          menusEx : [{
			text : '�鿴',
			icon : 'view',
			action: function(row){
				  if(row.tablename == 'oa_sale_order'){
				     showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id=' + row.orgid + "&skey="+row['skey_']);
				  } else if (row.tablename == 'oa_sale_service'){
				     showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id=" + row.orgid + "&skey="+row['skey_'] )
                  } else if (row.tablename == 'oa_sale_lease'){
                     showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id=" + row.orgid + "&skey="+row['skey_'])
                  } else if (row.tablename == 'oa_sale_rdproject') {
                     showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=" + row.orgid + "&skey="+row['skey_'])
                  }

			}
		   },{
				text : '�������',
				icon : 'view',
	            showMenuFn : function (row) {
	               if (row.ExaStatus=='����'){
	                   return true;
	               }
	                   return true;
	            },
				action : function(row) {
                     if(row.tablename == 'oa_sale_order'){
				         showThickboxWin('controller/projectmanagent/order/readview.php?itemtype=oa_sale_order&pid='
							+ row.orgid
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
					  } else if (row.tablename == 'oa_sale_service'){
					     showThickboxWin('controller/engineering/serviceContract/readview.php?itemtype=oa_sale_service&pid='
							+ row.orgid
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
	                  } else if (row.tablename == 'oa_sale_lease'){
	                     showThickboxWin('controller/contract/rental/readview.php?itemtype=oa_sale_lease&pid='
							+ row.orgid
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
	                  } else if (row.tablename == 'oa_sale_rdproject') {
	                     showThickboxWin('controller/rdproject/yxrdproject/readview.php?itemtype=oa_sale_rdproject&pid='
							+ row.orgid
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
	                  }

				}
			},{
			text : '����',
			icon : 'add',
//			showMenuFn : function (row){
//				   if(row.exportOrder == 1){
//				       return true;
//				   }
//				       return false;
//				},
			action: function(row){
				     window.open ('?model=contract_common_allcontract&action=importCont&id='
				                      + row.orgid
				                      +'&type='
				                      +row.tablename
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		   },{
			text : '�����ϴ�',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.orgid
				                      +'&type='
				                      +row.tablename
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
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
		}, {
					name : 'objCode',
					display : 'ҵ����',
					width : 150
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