/**
 * ������ʱ��ͬ�������
 * create by can
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_orderTempCode', {
		options : {
			hiddenId : 'id',
			nameCol : 'orderTempCode',
			gridOptions : {
				model : 'projectmanagent_order_order',
				// ����Ϣ
				colModel :
			[
			  {
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
			  },{
    				name : 'sign',
  					display : '�Ƿ�ǩԼ',
  					sortable : true,
  					width : 50
              },{
    				name : 'orderstate',
  					display : 'ֽ�ʺ�ͬ״̬',
  					sortable : true,
  					width : 70
              },{
    				name : 'parentOrder',
  					display : '����ͬ����',
  					sortable : true,
  					hide : true
              },{
					name : 'orderCode',
  					display : '������ͬ��',
  					sortable : true
              },{
					name : 'orderTempCode',
  					display : '��ʱ��ͬ��',
  					sortable : true
              },{
					name : 'orderName',
  					display : '��ͬ����',
  					sortable : true
              },{
					name : 'prinvipalName',
  					display : '��ͬ������',
  					sortable : true
              },{
					name : 'deliveryDate',
  					display : '�ƻ���������',
  					sortable : true
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
  					width : 90
              }
          ],
				// ��������
				searchitems : [{
							display : '��ͬ���',
							name : 'orderCode'
						}, {
							display : '��ͬ����',
							name : 'orderName',
							isdefault : true
						}],
				// Ĭ�������ֶ���
				sortname : "updatetime",
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);