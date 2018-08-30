var show_page = function(page) {
	$("#accesspriceGrid").yxgrid("reload");
};
$(function() {
			$("#accesspriceGrid").yxgrid({
						model : 'service_accessprice_accessprice',
						title : '������۸��',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'productCode',
									display : '���ϱ��',
									sortable : true
								}, {
									name : 'productName',
									display : '��������',
									sortable : true,
									width : 200
								}, {
									name : 'warranty',
									display : '������',
									sortable : true
								}, {
									name : 'pattern',
									display : '����ͺ�',
									sortable : true
								}, {
									name : 'unitName',
									display : '��λ',
									sortable : true
								}, {
									name : 'lowPrice',
									display : '��ͼ�',
									sortable : true,
									process : function(v, row) {
										return moneyFormat2(v);
									}
								}, {
									name : 'highPrice',
									display : '��߼�',
									sortable : true,
									process : function(v, row) {
										return moneyFormat2(v);
									}
								}, {
									name : 'strartDate',
									display : '���ÿ�ʼ����',
									sortable : true
								}, {
									name : 'endDate',
									display : '���ý�������',
									sortable : true
								}, {
									name : 'remark',
									display : '��ע',
									sortable : true,
									hide : true
								}, {
									name : 'createName',
									display : '������',
									sortable : true,
									hide : true
								}, {
									name : 'createId',
									display : '������id',
									sortable : true,
									hide : true
								}, {
									name : 'createTime',
									display : '��������',
									sortable : true,
									hide : true
								}, {
									name : 'updateName',
									display : '�޸���',
									sortable : true,
									hide : true
								}, {
									name : 'updateId',
									display : '�޸���id',
									sortable : true,
									hide : true
								}, {
									name : 'updateTime',
									display : '�޸�����',
									sortable : true,
									hide : true
								}],
						// ���ӱ������
						// subGridOptions : {
						// url :
						// '?model=service_accessprice_NULL&action=pageJson',
						// param : {
						// paramId : 'mainId',
						// colId : 'id'
						// },
						// colModel : {
						// name : 'XXX',
						// display : '�ӱ��ֶ�'
						// }
						// },

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : '���ϱ��',
									name : 'productCode'
								}, {
									display : '��������',
									name : 'productName'
								}]
					});
		});