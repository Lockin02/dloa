/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_carrecords', {
				options : {
					hiddenId : 'carId',
					nameCol : 'carNo',
					gridOptions : {
						showcheckbox : true,
						model : 'carrental_carinfo_carinfo',
						// ����Ϣ
						colModel : [{
									display : '����id',
									name : 'carId',
									hide: true
								},  {
									display : '���ƺ�',
									name : 'carNo'
								},{
									display : '�����ͺ�',
                    				name : 'carType'
                              	},{
                              		display : '��������',
                    				name : 'limitedSeating'
                              },{
									display : '��λid',
									name : 'unitsId',
									hide : true
								}, {
									display : '��λ����',
									name : 'unitsName',
									width : 130
								}],
						// ��������
						searchitems : [{
									display : '���ƺ�',
									name : 'carNo'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);