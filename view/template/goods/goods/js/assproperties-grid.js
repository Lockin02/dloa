var show_page = function(page) {
	$("#asspropertiesGrid").yxgrid("reload");
};
$(function() {
			$("#asspropertiesGrid").yxgrid({
						model : 'goods_goods_assproperties',
						title : '���Բ��ɼ��Թ�ϵ',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'proTypeName',
									display : '��������',
									sortable : true
								}],

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "�����ֶ�",
									name : 'XXX'
								}]
					});
		});