/**
 * ����������Ӧ�����
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outsuppvehicle', {
		options : {
			hiddenId : 'suppId',
			nameCol : 'suppName',
			width : 400,
			isFocusoutCheck : true,
			gridOptions : {
				showcheckbox : false,
				model : 'outsourcing_outsourcessupp_vehiclesupp',
				param : {
					"suppLevelNeq" : '0'
				},
				//����Ϣ
				colModel : [{
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
				},{
        			name : 'suppCode',
  					display : '��Ӧ�̱��',
  					width:80,
  					sortable : true
				},{
					name : 'suppName',
  					display : '�����Ӧ��',
  					width:260,
  					sortable : true
				},{
					name : 'linkmanName',
  					display : '��ϵ������',
  					width:80,
  					sortable : true
				},{
					name : 'linkmanPhone',
  					display : '��ϵ�˵绰',
  					width:100,
  					sortable : true
				}],
				// ��������
				searchitems : [{
					display : '�����Ӧ��',
					name : 'suppName'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);