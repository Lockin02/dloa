var show_page = function(page) {
	$("#carriedforwardGrid").yxgrid("reload");
};

$(function() {
    $("#carriedforwardGrid").yxgrid({
        model : 'projectmanagent_carriedforward_carriedforward',
        title : '��ͬ��ת��',
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            name : 'saleCode',
            display : '��ͬ���',
            sortable : true,
            width : 180
        },{
            name : 'saleType',
            display : '��ͬ����',
            sortable : true,
            datacode : 'KPRK'
        },{
            name : 'outStockCode',
            display : '���ⵥ�ݱ��',
            sortable : true,
            width : 120
        }         ,{
            name : 'outStockType',
            display : '���ⵥ������',
            sortable : true,
            datacode : 'CKDLX',
            hide : true
        },{
            name : 'thisDate',
            display : '��������',
            sortable : true
        },{
            name : 'createName',
            display : '������',
            sortable : true
        }],
		searchitems : [{
			display : '��ͬ���',
			name : 'saleCodeSearch'
		}, {
			display : '���ⵥ��',
			name : 'outStockCodeSearch'
		}]
    });
});