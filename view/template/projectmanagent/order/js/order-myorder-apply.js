var show_page = function(page) {
	$("#applyOrderGrid").yxgrid("reload");
};
$(function() {
	$("#applyOrderGrid").yxgrid({
		model : 'projectmanagent_order_order',
//		action : 'pageJsonMyProject',
		title : '�������ĺ�ͬ',
		param : { 'createId' : $('#UserId').val()},
//		isToolBar : false,
		isDelAction : false,
	    isToolBar : true, //�Ƿ���ʾ������
	    showcheckbox : false,
	    /**
		 * �Ƿ���ʾ��Ӱ�ť/�˵�
		 *
		 * @type Boolean
		 */
		isAddAction : true,
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
 					display : 'createId',
 					name : 'createId',
 					sortable : true,
 					hide : true
			  },{
					name : 'orderCode',
  					display : '��ͬ���',
  					sortable : true
              },{
					name : 'orderName',
  					display : '��ͬ����',
  					sortable : true
              },{
					name : 'customerName',
  					display : '�ͻ�����',
  					sortable : true
              },{
					name : 'customerType',
  					display : '�ͻ�����',
  					sortable : true,
  					datacode : 'KHLX'
              },{
					name : 'prinvipalName',
  					display : '��ͬ������',
  					sortable : true
              },{
					name : 'deliveryDate',
  					display : '��������',
  					sortable : true
              },{
					name : 'state',
  					display : '��ͬ״̬',
  					sortable : true,
  					process : function(v){
  						if( v == '0'){
  							return "δ�ύ";
  						}else if(v == '1'){
  							return "����";
  						}else if(v == '2'){
  							return "ִ����";
  						}else if(v == '3'){
  							return "�ر�";
  						}else if(v == '4'){
  							return "�����ɺ�ͬ";
  						}else if(v == '5'){
  							return "��ǩ��ͬ";
  						}
  					},
  					width : 90
              },{
					name : 'ExaStatus',
  					display : '����״̬',
  					sortable : true,
  					width : 90
              },{
					name : 'ExaDT',
  					display : '����ʱ��',
  					sortable : true
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
		   }
		   ,{
				text : '�༭',
				icon : 'edit',
	            showMenuFn : function (row){
				  if((row.ExaStatus=='����' || row.ExaStatus=='���') && (row.state == '0' || row.state == '1'|| row.state =='4' ||row.state == '5')){
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
	        }
	        ,{

			text : '¼���ͬ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '3') {
									return false;
								}
								return true;
							},


			action : function(row) {

				self.location = "?model=projectmanagent_order_order&action=toSales&id="
						+ row.id;

			}
		},{
				text : '�ύ����',
				icon : 'add',
	            showMenuFn : function (row){
				   if((row.ExaStatus=='����' || row.ExaStatus=='���') && (row.state == '0' || row.state == '1'|| row.state =='4' ||row.state == '5')){
				       return true;
				   }
				       return false;
				},
				action: function(row){
					location = 'controller/projectmanagent/order/ewf_index.php?actTo=ewfSelect&formName=���ۺ�ͬ����&examCode=oa_sale_order&billId='
							+ row.id
			        }
	        }
//	        ,{
//			text : 'ɾ��',
//			icon : 'delete',
//			 showMenuFn : function(row) {
//				 if ( row.ExaStatus == '���' || row.ExaStatus == '��������' || row.ExaStatus == '���'|| row.status == '3') {
//					 return false;
//				 }
//				 return true;
//			 },
//			action : function(row) {
//				if(confirm("ȷ��Ҫɾ����")){
//					showThickboxWin('?model=projectmanagent_order_order&action=deletesInfo&id='
//						+ row.id
//						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
//				}
//
//			}
//
//		}
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
		}],
         sortname : "createTime",
          //���ñ༭ҳ����
          toEditConfig : {
			formHeight : 500 ,
			formWidth : 900
          },
          //���ò鿴ҳ����
          toViewConfig : {
			formHeight : 500 ,
			formWidth : 900
          },

	     toAddConfig : {
			text : '�½�',
			icon : 'add',
			/**
			 * Ĭ�ϵ��������ť�����¼�
			 */

			toAddFn : function(p) {
               self.location ="?model=projectmanagent_order_order&action=toadd";
			}
		}

	});
});