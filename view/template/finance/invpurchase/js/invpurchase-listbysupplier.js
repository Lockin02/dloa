var show_page = function(page) {
	$("#invpurchaseGrid").yxsubgrid("reload");
};
$(function() {
	$("#invpurchaseGrid").yxsubgrid({
		model: 'finance_invpurchase_invpurchase',
		title: '采购发票',
        param : {'supplierId' : $("#supplierId").val()},
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
				hide: true
			},
			{
				name: 'objCode',
				display: '发票编号',
				sortable: true,
				width : 160,
				process : function(v,row){
					if(row.formType == "blue"){
						return v;
					}else{
						return "<span class='red'>"+ v +"</span>";
					}
				}
			},
			{
				name: 'supplierName',
				display: '供应商名称',
				sortable: true,
				width : 170
			},
			{
				name: 'departments',
				display: '部门',
				sortable: true
			},
			{
				name: 'salesman',
				display: '业务员',
				sortable: true
			},
			{
				name: 'objNo',
				display: '发票号码',
				sortable: true,
				width : 130
			},
			{
				name: 'amount',
				display: '总金额',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				}
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
			},
			{
				name: 'status',
				display: '状态',
				sortable: true,
				datacode : 'CGFPZT',
				width : 60
			},{
				name : 'purcontCode',
				display : '采购订单编号',
				width : 130
			},{
				name : 'createName',
				display : '创建人',
				width : 90,
				hide : true
			},
			{
				name: 'belongId',
				display: '所属原发票id',
				process : function(v){
					if(v != ""){
						return "<span id='sBelongId"+ v + "'>" + v + "</span>";
					}
				},
				hide: true
			}
		],

		// 主从表格设置
		subGridOptions : {
			url : '?model=finance_invpurchase_invpurdetail&action=pageJson',// 获取从表数据url
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
					display : '物料编号'
				},{
					name : 'productName',
					display : '物料名称',
					width : 160
				},{
					name : 'productModel',
					display : '规格型号'
				},{
					name : 'unit',
					display : '单位'
				}, {
				    name : 'number',
				    display : '数量'
				},{
					name : 'price',
					display : '单价',
					process : function(v){
						return moneyFormat2(v);
					}
				},{
				    name : 'amount',
				    display : '金额',
					process : function(v){
						return moneyFormat2(v);
					}
				}
			]
		},
		menusEx : [
			{
				text: "查看",
				icon: 'view',
				action: function(row) {
					showThickboxWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 550 + "&width=" + 800);
				}
			}
		],
        sortname : 'updateTime'
	});
});