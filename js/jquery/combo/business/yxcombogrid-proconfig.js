/**
 * �������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_proconfig', {
				options : {
					hiddenId : 'id',
					nameCol : 'configName',
					gridOptions : {
						model : 'stock_productinfo_configuration',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width : 130

								}, {
									display : '�������',
									name : 'configName',
									width : 100
								}, {
									display : '����ͺ�',
									name : 'configPattern',
									width : 100
								}, {
									display : '�������',
									name : 'configNum',
									width : 150
								}, {
									display : '˵��',
									name : 'explains',
									width : 100
								}],
						// ��������
						searchitems : [{
									display : '�������',
									name : 'configName'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);