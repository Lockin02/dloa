var show_page = function(page) {
	$("#carriedforwardGrid").yxgrid("reload");
};

$(function() {
    $("#carriedforwardGrid").yxgrid({
        model : 'projectmanagent_carriedforward_carriedforward',
        title : '合同结转表',
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            name : 'saleCode',
            display : '合同编号',
            sortable : true,
            width : 180
        },{
            name : 'saleType',
            display : '合同类型',
            sortable : true,
            datacode : 'KPRK'
        },{
            name : 'outStockCode',
            display : '出库单据编号',
            sortable : true,
            width : 120
        }         ,{
            name : 'outStockType',
            display : '出库单据类型',
            sortable : true,
            datacode : 'CKDLX',
            hide : true
        },{
            name : 'thisDate',
            display : '勾稽日期',
            sortable : true
        },{
            name : 'createName',
            display : '勾稽人',
            sortable : true
        }],
		searchitems : [{
			display : '合同编号',
			name : 'saleCodeSearch'
		}, {
			display : '出库单号',
			name : 'outStockCodeSearch'
		}]
    });
});