var show_page = function(page) {
	$("#carriedforwardGrid").yxgrid("reload");
};

$(function() {
    $("#carriedforwardGrid").yxgrid({
        model : 'finance_carriedforward_carriedforward',
        title : '合同结转表',
        isAddAction : false,
        isEditAction : false,
        showcheckbox : false,
        isDelAction : false,
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            name : 'customerName',
            display : '客户',
            sortable : true,
            width : 130
        },{
            name : 'saleId',
            display : '合同id',
            sortable : true,
            hide : true
        },{
            name : 'saleCode',
            display : '合同编号',
            sortable : true,
            width : 130
        },{
            name : 'rObjCode',
            display : '业务编号',
            sortable : true,
            width : 110
        },{
            name : 'invoiceNo',
            display : '发票号',
            sortable : true
        },{
            name : 'productModel',
            display : '产品类型',
            sortable : true,
            datacode : 'CWCPLX',
            width : 80
        },{
            name : 'invoiceId',
            display : '发票id',
            sortable : true,
            hide : true
        },{
            name : 'outStockId',
            display : '出库单据id',
            sortable : true,
            hide : true
        },{
            name : 'outStockCode',
            display : '出库单据编号',
            sortable : true
        },{
            name : 'productName',
            display : '物料名称',
            sortable : true,
            width : 120
        },{
            name : 'outStockType',
            display : '出库单据类型',
            sortable : true,
            datacode : 'CKDLX',
            width : 80,
            hide : true
        },{
            name : 'subCost',
            display : '成本',
            sortable : true,
            process : function (v){
				return moneyFormat2(v);
            }
        },{
            name : 'carryMoney',
            display : '结转金额',
            sortable : true,
            process : function (v){
				return moneyFormat2(v);
            }
        },{
            name : 'outStockDetailId',
            display : '出库物料记录id',
            sortable : true,
            hide : true
        }         ,{
            name : 'carryRate',
            display : '结转比例(%)',
            sortable : true,
            width : 80
        },{
            name : 'createName',
            display : '钩稽人名称',
            sortable : true,
            hide : true
        },{
            name : 'periodNo',
            display : '勾稽财务期',
            sortable : true,
            width : 80
        },{
            name : 'status',
            display : '状态',
            sortable : true,
            hide : true,
            width : 80
        },{
            name : 'carryType',
            display : '结转类型',
            sortable : true,
            width : 80,
            process : function (v){
				if(v == 0){
					return '开票结转';
				}else{
					return '出库结转';
				}
            }
        },{
            name : 'createName',
            display : '钩稽人',
            sortable : true
        }],
        menusEx:[
		   {
		     text:'删除',
		     icon:'delete',
		     action:function(row,rows,grid){
		        if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=finance_carriedforward_carriedforward&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#carriedforwardGrid").yxgrid("reload");
							}
						}
					});
				}
		     }
		   }

        ],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同编号',
			name : 'saleCodeSearch'
		},{
			display : '出库单号',
			name : 'outStockCodeSearch'
		},{
			display : '客户名称',
			name : 'customerName'
		}],
        comboEx : [ {
			text : '结转类型',
			key : 'carryType',
			data : [ {
				text : '开票结转',
				value : 0
			}, {
				text : ' 出库结转',
				value : 1
			}]
		}]
    });
});