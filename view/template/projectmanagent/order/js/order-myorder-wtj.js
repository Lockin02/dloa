var show_page = function(page) {
	$("#orderWtjGrid").yxgrid("reload");
};
$(function() {
	$("#orderWtjGrid").yxgrid({
		model : 'projectmanagent_order_order',
        action : 'myPageJson',
		title : '���������ۺ�ͬ',

		param : {
			'ExaStatusArr' : '��������,���' ,  "prinvipalId" : $("#userId").val()
		},

		isDelAction : false,
	    isToolBar : true, //�Ƿ���ʾ������
	    showcheckbox : false,
	    isViewAction : false,
	    customCode : 'wtj',
	    /**
		 * �Ƿ���ʾ��Ӱ�ť/�˵�
		 *
		 * @type Boolean
		 */
		isAddAction : false,
		/**
		 * �Ƿ���ʾ�鿴��ť/�˵�
		 *
		 * @type Boolean
		 */
		isViewAction : false,
		/**
		 * �Ƿ���ʾ�޸İ�ť/�˵�
		 *
		 * @type Boolean
		 */
		isEditAction : false,
		//����Ϣ
		colModel :
			[
			  {
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
			  },{
					name : 'orderCode',
  					display : '������ͬ��',
  					sortable : true,
  					width : 210
              },{
					name : 'orderTempCode',
  					display : '��ʱ��ͬ��',
  					sortable : true,
  					width : 210
              },{
					name : 'orderName',
  					display : '��ͬ����',
  					sortable : true,
  					width : 210
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
          menusEx : [
          	{
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
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
					if (row.ExaStatus == 'δ����' || row.ExaStatus == '���') {
						return true;
					}
					return false;
				},
			action: function(row){
                showOpenWin('?model=projectmanagent_order_order&action=init&id='
						+ row.id
                        + '&perm=edit'
                        + "&skey="+row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		   },{
				text : '�ύ����',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.ExaStatus == '����' || row.ExaStatus == '���') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (row) {
						showThickboxWin('controller/projectmanagent/order/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
					}
				}
			},{
				text : '�������',
				icon : 'view',
	            showMenuFn : function (row) {
	               if (row.ExaStatus=='����'){
	                   return false;
	               }
	                   return true;
	            },
				action : function(row) {

					showThickboxWin('controller/projectmanagent/order/readview.php?itemtype=oa_sale_order&pid='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			}, {
			text : 'ɾ��',
			icon : 'delete',
			 showMenuFn : function(row) {
				 if ( row.ExaStatus == '����' || row.ExaStatus == '���' ) {
				 	if( row.orderCode == "" || row.orderTempCode == ""){
						return true;
				 	}
				 }
				 return false;
			 },
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_order_order&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#orderWtjGrid").yxgrid("reload");
							}else{
								alert('ɾ��ʧ��! ');
							}
						}
					});
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
		   }
		],
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