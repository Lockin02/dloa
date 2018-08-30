var show_page = function(page) {
	$("#carrecordsdetailGrid").yxgrid("reload");
};
$(function() {
	$("#carrecordsdetailGrid").yxgrid({
		model : 'carrental_records_carrecordsdetail',
		title : '租车明细',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'useDate',
				display : '使用日期',
				sortable : true,
				width : 80,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=carrental_records_carrecordsdetail&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'carNo',
				display : '车牌',
				sortable : true,
				width : 80,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=carrental_carinfo_carinfo&action=toView&id=" + row.carId + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'rentalTypeName',
				display : '租车性质',
				sortable : true
			}, {
				name : 'mileage',
				display : '里程数',
				sortable : true,
				width : 60
			}, {
				name : 'useHours',
				display : '使用时长',
				sortable : true,
				width : 80
			}, {
				name : 'travelFee',
				display : '乘车费',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'fuelFee',
				display : '油费',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'roadFee',
				display : '路桥费',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'parkingFee',
				display : '停车费',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'effectiveLog',
				display : '有效LOG',
				sortable : true,
				width : 60
			}, {
				name : 'useReson',
				display : '用途',
				sortable : true
			}, {
				name : 'createId',
				display : '创建人Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true
			}, {
				name : 'updateId',
				display : '修改人Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人名称',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "车牌",
			name : 'carNoSearch'
		}]
	});
});