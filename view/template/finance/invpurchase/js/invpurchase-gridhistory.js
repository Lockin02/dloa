function getQueryStringPay(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null)
		return unescape(r[2]);
	return null;
}
var show_page = function(page) {
	$("#invpurchaseGrid").yxsubgrid("reload");
};
$(function() {
	var skey = "&skey=" + $("#skey").val();
	var gdbtable = getQueryStringPay('gdbtable');
	$("#invpurchaseGrid").yxsubgrid({
		model: 'finance_invpurchase_invpurchase',
		action : 'pageJsonHistory',
		title: '采购发票 -- 采购订单 :' + $("#objCode").val(),
        param : {'dcontractId' : $("#objId").val(),"gdbtable" : gdbtable},
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isAddAction : false,
		showcheckbox :false,
		//列信息
		colModel: [{
				display: 'id',
				name: 'id',
				sortable: true,
				hide: true,
				process : function(v,row){
					return v + "<input type='hidden' id='isBreak"+ row.id+"' value='unde'>";
				}
			},
			{
				name: 'objCode',
				display: '单据编号',
				sortable: true,
				width : 130,
				process : function(v,row){
					if(row.formType == "blue"){
						return v;
					}else{
						return "<span class='red'>"+ v +"</span>";
					}
				}
			},
			{
				name: 'objNo',
				display: '发票号码',
				sortable: true
			},
			{
				name: 'supplierName',
				display: '供应商名称',
				sortable: true,
				width : 150
			},
			{
				name: 'invType',
				display: '发票类型',
				sortable: true,
				width : 80,
				datacode : 'FPLX'
			},
			{
				name: 'taxRate',
				display: '税率(%)',
				sortable: true,
				width : 60
			},
			{
				name: 'formAssessment',
				display: '单据税额',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'amount',
				display: '总金额',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formCount',
				display: '价税合计',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formDate',
				display: '单据日期',
				sortable: true,
				width : 80
			},
			{
				name: 'payDate',
				display: '付款日期',
				sortable: true,
				width : 80
			},{
				name : 'purcontCode',
				display : '采购订单编号',
				width : 130,
				hide : true
			},
			{
				name: 'departments',
				display: '部门',
				sortable: true,
				width : 80
			},
			{
				name: 'salesman',
				display: '业务员',
				sortable: true,
				width : 80
			},
			{
				name: 'ExaStatus',
				display: '审核状态',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '已审核';
					}else{
						return '未审核';
					}
				}
			},
			{
				name: 'exaMan',
				display: '审核人',
				sortable: true,
				width : 80
			},
			{
				name: 'ExaDT',
				display: '审核日期',
				sortable: true,
				width : 80
			},
			{
				name: 'status',
				display: '钩稽状态',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '已钩稽';
					}else{
						return '未钩稽';
					}
				}
			},{
				name : 'createName',
				display : '创建人',
				width : 90,
				hide : true
			},
			{
				name: 'belongId',
				display: '所属原发票id',
				hide: true
			}
		],

		// 主从表格设置
		subGridOptions : {
			url : '?model=finance_invpurchase_invpurdetail&action=pageJson&gdbtable=' + gdbtable,// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [
				{
					paramId : 'invPurId',// 传递给后台的参数名称
					colId : 'id'// 获取主表行数据的列名称
				}
			],
			// 显示的列
			colModel : [{
					name : 'productNo',
					display : '物料编号',
					width : 80
				},{
					name : 'productName',
					display : '物料名称',
					width : 140
				},{
					name : 'productModel',
					display : '规格型号'
				},{
					name : 'unit',
					display : '单位',
				    width : 60
				}, {
				    name : 'number',
				    display : '数量',
				    width : 60
				},{
					name : 'price',
					display : '单价',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
					name : 'taxPrice',
					display : '含税单价',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'assessment',
				    display : '税额',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'amount',
				    display : '金额',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'allCount',
				    display : '价税合计',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'objCode',
				    display : '关联编号',
				    width : 120
				}
			]
		},
        buttonsEx : [
        	{
				text : '付款申请历史',
				icon : 'view',
				action : function(row) {
					location="?model=finance_payablesapply_payablesapply&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			},{
				text : '付款记录历史',
				icon : 'view',
				action : function(row) {
					location="?model=finance_payables_payables&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			}
			,{
				text : '采购发票记录',
				icon : 'edit',
				action : function(row) {
					location="?model=finance_invpurchase_invpurchase&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			}
			,{
				text : '收料记录',
				icon : 'view',
				action : function(row) {
					location="?model=purchase_arrival_arrival&action=toListByOrder"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			}
        ],
		menusEx : [
			{
				text: "查看",
				icon: 'view',
				action: function(row) {
					showThickboxWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.id
						+ "&skey=" + row.skey_+ "&gdbtable=" + gdbtable
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 550 + "&width=" + 800);
				}
			}
		],
        sortname : 'updateTime'
	});
});