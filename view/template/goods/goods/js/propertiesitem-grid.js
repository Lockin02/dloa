var show_page = function(page) {
	$("#propertiesitemGrid").yxgrid("reload");
};
$(function() {
			$("#propertiesitemGrid").yxgrid({
						model : 'goods_goods_propertiesitem',
						title : '����������',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'itemContent',
									display : 'ֵ����',
									sortable : true
								}, {
									name : 'isNeed',
									display : '�Ƿ��ѡ',
									sortable : true
								}, {
									name : 'isDefault',
									display : '�Ƿ�Ĭ��',
									sortable : true
								}, {
									name : 'productCode',
									display : '��Ӧ���ϱ��',
									sortable : true
								}, {
									name : 'productName',
									display : '��Ӧ��������',
									sortable : true
								}, {
									name : 'pattern',
									display : '��Ӧ�����ͺ�',
									sortable : true
								}, {
									name : 'proNum',
									display : '��Ӧ��������',
									sortable : true
								}, {
									name : 'status',
									display : '״̬',
									sortable : true
								}, {
									name : 'remakr',
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