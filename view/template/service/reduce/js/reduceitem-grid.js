var show_page = function(page) {
	$("#reduceitemGrid").yxgrid("reload");
};
$(function() {
	$("#reduceitemGrid").yxgrid({
		model : 'service_reduce_reduceitem',
		title : 'ά�޷��ü����嵥',
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'productType',
					display : '���Ϸ���',
					hide : true,
					sortable : true
				}, {
					name : 'productCode',
					display : '���ϱ��',
					sortable : true
				}, {
					name : 'productName',
					display : '��������',
					sortable : true,
					width : 250
				}, {
					name : 'pattern',
					display : '����ͺ�',
					sortable : true
				}, {
					name : 'unitName',
					display : '��λ',
					sortable : true
				}, {
					name : 'serilnoName',
					display : '���к�',
					sortable : true
				}, {
					name : 'fittings',
					display : '�����Ϣ',
					sortable : true
				}, {
					name : 'cost',
					display : '��ȡ����',
					sortable : true
				}, {
					name : 'reduceCost',
					display : '�������',
					sortable : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true
				}],

		toEditConfig : {
			toEditFn : function(p) {
				action : showThickboxWin("?model=service_reduce_reduceitem&action=toEdit&id="
						+ row.id + "&skey=" + row['skey_'])
			}
		},
		toViewConfig : {
			toViewFn : function(p) {
				action : showThickboxWin("?model=service_reduce_reduceitem&action=toView&id="
						+ row.id + "&skey=" + row['skey_'])
			}
		}
	});
});