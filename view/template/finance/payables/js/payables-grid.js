var show_page = function(page) {
	$("#payablesGrid").yxgrid("reload");
};

$(function() {
	$("#payablesGrid").yxgrid({
	      model : 'finance_payables_payables',
      	title : '应付付款单/应付预付款/应付退款单',
			//列信息
			colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
				        }                  ,{
           					name : 'payApplyId',
         					display : '关联付款单id',
         					sortable : true
                     }                  ,{
           					name : 'payApplyNo',
         					display : '关联付款单编号',
         					sortable : true
                     }                  ,{
           					name : 'formNo',
         					display : '单据号',
         					sortable : true
                     }                  ,{
           					name : 'formDate',
         					display : '单据日期',
         					sortable : true
                     }                  ,{
           					name : 'financeDate',
         					display : '财务日期',
         					sortable : true
                     }                  ,{
           					name : 'supplierId',
         					display : '供应商id',
         					sortable : true
                     }                  ,{
           					name : 'supplierCode',
         					display : '供应商编号',
         					sortable : true
                     }                  ,{
           					name : 'supplierName',
         					display : '供应商名称',
         					sortable : true
                     }                  ,{
           					name : 'payType',
         					display : '结算类型',
         					sortable : true
                     }                  ,{
           					name : 'payNo',
         					display : '结算号',
         					sortable : true
                     }                  ,{
           					name : 'cashsubject',
         					display : '现金类科目',
         					sortable : true
                     }                  ,{
           					name : 'bank',
         					display : '付款银行',
         					sortable : true
                     }                  ,{
           					name : 'account',
         					display : '帐号',
         					sortable : true
                     }                  ,{
           					name : 'currency',
         					display : '币别',
         					sortable : true
                     }                  ,{
           					name : 'rate',
         					display : '汇率',
         					sortable : true
                     }                  ,{
           					name : 'certificate',
         					display : '凭证字号',
         					sortable : true
                     }                  ,{
           					name : 'amount',
         					display : '单据金额',
         					sortable : true
                     }                  ,{
           					name : 'remark',
         					display : '备注',
         					sortable : true
                     }                  ,{
           					name : 'deptId',
         					display : '部门id',
         					sortable : true
                     }                  ,{
           					name : 'deptName',
         					display : '部门',
         					sortable : true
                     }                  ,{
           					name : 'salesman',
         					display : '业务员',
         					sortable : true
                     }                  ,{
           					name : 'salesmanId',
         					display : '业务员帐号',
         					sortable : true
                     }                  ,{
           					name : 'status',
         					display : '单据状态',
         					sortable : true
                     }                  ,{
           					name : 'ExaStatus',
         					display : '审批状态',
         					sortable : true
                     }                  ,{
           					name : 'ExaDT',
         					display : '审批时间',
         					sortable : true
                     }                  ,{
           					name : 'createId',
         					display : '创建人ID',
         					sortable : true
                     }                  ,{
           					name : 'createName',
         					display : '创建人名称',
         					sortable : true
                     }                  ,{
           					name : 'createTime',
         					display : '创建时间',
         					sortable : true
                     }                  ,{
           					name : 'updateId',
         					display : '修改人ID',
         					sortable : true
                     }                  ,{
           					name : 'updateName',
         					display : '修改人名称',
         					sortable : true
                     }                  ,{
           					name : 'updateTime',
         					display : '修改时间',
         					sortable : true
                     }         ]
 		});
 });