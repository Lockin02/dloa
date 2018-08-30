var show_page = function(page) {
	$("#allocationitemGrid").yxgrid("reload");
};
function showSerialno(serialName) {
	showThickboxWin("index1.php?model=stock_allocation_allocation&action=toViewSerialno&serialnoName="
			+ serialName
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=400");
}
$(function() {
			$("#allocationitemGrid").yxgrid({
				model : 'stock_allocation_allocationitem',
				title : '归还物料清单',
				action : 'pageBackGridJson',
				param : {
					relDocIdIn : $("#relDocId").val()
				},
				isAddAction : false,
				isViewAction : false,
				isEditAction : false,
				isDelAction : false,
				showcheckbox : false,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'mainId',
							display : '调拨单id',
							sortable : true,
							hide : true
						}, {
							name : 'productId',
							display : '物料id',
							sortable : true,
							hide : true
						}, {
							name : 'productCode',
							display : '物料编号',
							sortable : true
						}, {
							name : 'productName',
							display : '物料名称',
							sortable : true
						}, {
							name : 'pattern',
							display : '规格型号',
							sortable : true
						}, {
							name : 'unitName',
							display : '单位',
							sortable : true
						}, {
							name : 'aidUnit',
							display : '辅助单位',
							sortable : true,
							hide : true
						}, {
							name : 'converRate',
							display : '换算率',
							sortable : true,
							hide : true
						}, {
							name : 'batchNum',
							display : '批次',
							sortable : true,
							hide : true
						}, {
							name : 'cost',
							display : '单位成本',
							sortable : true,
							process : function(v, row) {
								return moneyFormat2(v);
							}
						}, {
							name : 'subCost',
							display : '成本',
							sortable : true,
							process : function(v, row) {
								return moneyFormat2(v);
							}
						}, {
							name : 'allocatNum',
							display : '归还数量',
							sortable : true,
							process : function(v, row) {
								return '<a  href="#" onclick="showSerialno(\''
										+ row.serialnoName + '\')" >' + v
										+ '</a>';

							}
						}, {
							name : 'outEndDate',
							display : '归还日期',
							sortable : true
						}, {
							name : 'serialnoName',
							display : '序列号',
							sortable : true,
							hide : true
						}, {
							name : 'remark',
							display : '备注',
							sortable : true
						}],
				searchitems : [{
							display : '物料代码',
							name : 'productCode'
						}, {
							display : '物料名称',
							name : 'productName'
						}]
			});
		});