var show_page = function(page) {
	$("#assitemGrid").yxgrid("reload");
};
$(function() {
			$("#assitemGrid").yxgrid({
						model : 'goods_goods_assitem',
						title : '���������ϵ',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'itemName',
									display : '����������',
									sortable : true
								}, {
									name : 'propertiesName',
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