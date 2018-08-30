var show_page = function(page) {
	$("#executeOrderGrid").yxgrid("reload");
};
$(function() {
	$("#executeOrderGrid").yxgrid({
		model : 'projectmanagent_order_order',
		action : 'myOrderPageJson',
		title : '��ִ�е����ۺ�ͬ',
		param : { 'states' : '2' , "prinvipalId" : $("#userId").val()},
		isDelAction : false,
		isToolBar : false, //�Ƿ���ʾ������
		showcheckbox : false,
		customCode : 'execute',
		//����Ϣ
		colModel :
			[
			  {
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
			  },{
					name : 'isBecome',
  					display : '�������',
  					sortable : true,
  					width : 50,
  					hide : true,
  					process : function(v){
                         if(v == 1){
                             return "<img src='images/icon/hred.gif' />" ;
                         }else{
                             return "<img src='images/icon/green.gif' />";
                         }
  					}
              },{
					name : 'ExaDT',
  					display : '����ʱ��',
  					sortable : true,
  					width : 100,
  					hide : true
              },{
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
              },{
					name : 'orderTempCode',
  					display : '��ʱ��ͬ��',
  					sortable : true,
  					width : 210,
  					process : function(v,row){
                         if(row.isR == 1){
                             return "<font color = '#0000FF'>" +v;
                         }else if(row.isBecome == 1 ){
                             return "<font color = '#FF0000'>" +v;
                         }else{
                             return v;
                         }
  					}
              },{
					name : 'isR',
  					display : 'isR',
  					sortable : true,
  					hide : true
              },{
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
              },{
                    name : 'customerName',
                    display : '�ͻ�����',
                    sortable : true,
                    width : 150
              },{
                    name : 'orderTempMoney',
                    display : 'Ԥ�ƺ�ͬ���',
                    sortable : true,
                    width : 100,
                    process : function(v){
  						return moneyFormat2(v);
  					}
              },{
                    name : 'orderMoney',
                    display : 'ǩԼ��ͬ���',
                    sortable : true,
                    width : 100,
                    process : function(v){
  						return moneyFormat2(v);
  					}
              },{
    				name : 'applyedMoney',
  					display : '�����뿪Ʊ���',
  					sortable : false,
  					width : 80,
  					process : function(v){
  						return moneyFormat2(v);
  					}
              },{
    				name : 'invoiceMoney',
  					display : '��Ʊ���',
  					sortable : false,
  					width : 80,
  					process : function(v){
  						return moneyFormat2(v);
  					}
              },{
    				name : 'incomeMoney',
  					display : '���ս��',
  					sortable : false,
  					process : function(v){
  						return moneyFormat2(v);
  					}
              },{
    				name : 'surOrderMoney',
  					display : 'ǩԼ��ͬӦ���˿����',
  					sortable : false,
  					width : 120,
  					process : function(v,row){
  						return "<font color = 'blue'>" + moneyFormat2(accSub(row.orderMoney,row.incomeMoney,2)) + "</font>"
  					}
              },{
    				name : 'surincomeMoney',
  					display : '����Ӧ���˿����',
  					sortable : false,
  					process : function(v,row){
  						return "<font color = 'blue'>" + moneyFormat2(accSub(row.invoiceMoney,row.incomeMoney,2)) + "</font>"
  					}
              },{
                    name : 'areaName',
                    display : '��������',
                    sortable : true,
                    width : 100
              },{
					name : 'state',
  					display : '��ͬ״̬',
  					sortable : true,
  					process : function(v){
  						if( v == '0'){
  							return "δ�ύ";
  						}else if(v == '1'){
  							return "������";
  						}else if(v == '2'){
  							return "ִ����";
  						}else if(v == '3'){
  							return "�ѹر�";
  						}else if(v == '4'){
  						    return "�����";
  						}
  					},
  					width : 90
              },{
					name : 'ExaStatus',
  					display : '����״̬',
  					sortable : true,
  					width : 100
              },{
    				name : 'sign',
  					display : '�Ƿ�ǩԼ',
  					sortable : true,
  					width : 70
              },{
    				name : 'orderstate',
  					display : 'ֽ�ʺ�ͬ״̬',
  					sortable : true,
  					width : 100
              },{
    				name : 'parentOrder',
  					display : '����ͬ����',
  					sortable : true,
  					hide : true
              }, {
					name : 'objCode',
					display : 'ҵ����',
					width : 150
				}
          ],
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
						url : "?model=projectmanagent_order_order&action=cancelBecome&id=" + row.id,
						success : function(msg) {
                                $("#executeOrderGrid").yxgrid("reload");
						}
					});
                 }
			}
		   },{
			text : '�鿴',
			icon : 'view',
			action: function(row){
                showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id='
						+ row.id
                        + '&perm=view'
                        + "&skey="+row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		   },{
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
			action: function(row){
                if(row.orderCode != ""){
                	showModalWin('?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]='
						+ row.id
						+ '&invoiceapply[objCode]=' + row.orderCode
                        + '&invoiceapply[objType]=KPRK-01');
                }else{
                	showModalWin('?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]='
						+ row.id
						+ '&invoiceapply[objCode]=' + row.orderTempCode
                        + '&invoiceapply[objType]=KPRK-02');
                }
			}
		   },{
			text : '��ͬ���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.orderCode == '' && row.ExaStatus == '���' ) {
					return true;
				}else {
				    return false;
				}

			},
			action : function(row) {
				showOpenWin('?model=projectmanagent_order_order&action=toSplit&id=' + row.id + "&skey="+row['skey_']);
			}
		},{
			text : 'תΪ��ʽ��ͬ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.orderCode == ''&& row.ExaStatus == '���' ) {
					return true;
				} else {
				    return false;

				}

			},
			action: function(row){
                showOpenWin('?model=projectmanagent_order_order&action=toBecomeOrder&id='
						+ row.id
                        + "&skey="+row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		},  {

			text : '�����ͬ',
			icon : 'edit',
            showMenuFn : function(row) {
				if (row.ExaStatus == '��������' || row.ExaStatus == '���������') {
					return false;
				}
				return true;
			},
			action : function(row) {
				     location='?model=projectmanagent_order_order&action=toChange&changer=changer&id='+ row.id + "&skey="+row['skey_'];
			}
		},{

			text : '�رպ�ͬ',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == '3' || row.ExaStatus == '��������' || row.ExaStatus == '���������') {
					return false;
				}
				return true;
			},

			action : function(row) {
                alert("��ͬ�رչ�����ʱͣ�ã�����������ϵOA����Ա");
//				showThickboxWin('?model=projectmanagent_order_order&action=CloseOrder&id='
//						+ row.id
//						+ "&skey="+row['skey_']
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');

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

			text : '�˻�����',
			icon : 'delete',
            showMenuFn : function(row) {
				if (row.isR == 1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				     showOpenWin ('?model=projectmanagent_return_return&action=toAdd&orderId='+ row.id +"&orderType=oa_sale_order");
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
		},{
			display : '��ͬ���',
			name : 'orderCodeOrTempSearch'
		},{
			display : 'ҵ����',
			name : 'objCode'
		}],
		sortname : "isBecome desc,ExaDT",
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