/**
 * �����⳵��ͬ��Ϣ
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_rentcarcontract', {
		options : {
			hiddenId : 'id',
			nameCol : 'orderCode',
			width : 600,
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false,
				model : 'outsourcing_contract_rentcar',
				action : 'getRentcarInformation',
				//����Ϣ
				colModel : [{
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
				},{
        			name : 'orderCode',
  					display : '�⳵��ͬ���',
  					width:100,
  					sortable : true
				},{
					name : 'projectName',
  					display : '��Ŀ����',
  					width:275,
  					sortable : true
				},{
					name : 'suppName',
  					display : '��Ӧ������',
  					width:150,
  					sortable : true
				}],
				// ��������
				searchitems : [{
					display : '�⳵��ͬ���',
					name : 'orderCode'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);