/**
 * �����⳵�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_rentalcar', {
		options : {
			hiddenId : 'id',
			nameCol : 'formCode',
			width : 600,
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false,
				model : 'outsourcing_vehicle_rentalcar',
				param : {
					"ExaStatus" : "���"
				},
				//����Ϣ
				colModel : [{
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
				},{
        			name : 'formCode',
  					display : '�⳵���뵥��',
  					width:180,
  					sortable : true
				},{
					name : 'projectName',
  					display : '��Ŀ����',
  					width:260,
  					sortable : true
				},{
        			name : 'createName',
  					display : '������',
  					width:80,
  					sortable : true
				}],
				// ��������
				searchitems : [{
					display : '���뵥��',
					name : 'formCode'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);