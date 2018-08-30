var show_page = function(page) {
	$("#registerGrid").yxgrid("reload");
};

$(function() {
	$("#registerGrid").yxgrid({
		model: 'outsourcing_vehicle_register',
		title: '车辆供应商-项目信息',
		action: 'projectList',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isViewAction: false,
		param: {
			'suppId': $("#suppId").val()
		},
		bodyAlign: 'center',
		showcheckbox: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'suppName',
			display: '供应商名称',
			sortable: true
		},{
			name: 'suppCode',
			display: '供应商编号',
			sortable: true,
			hide: true
		},{
			name: 'suppId',
			display: '供应商Id',
			sortable: true,
			hide: true
		},{
			name: 'projectName',
			display: '项目名称',
			sortable: true,
			width : 200
		},{
			name: 'projectCode',
			display: '项目编号',
			sortable: true,
			width : 200
		},{
			name: 'carNum',
			display: '车牌号',
			sortable: true,
			process: function(v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=outsourcing_vehicle_register&action=pageView"
						+ "&carNum=" + row.carNum
						+ "&allregisterId=" + row.allregisterId
						+ "&placeValuesBefore&TB_iframe=true&modal=false\",\"1\")'>" + v + "</a>";
			}
		},{
			name: 'useCarDate',
			display: '用车周期',
			sortable: true,
			process: function(v) {
				return v.substr(0, 7);
			}
		},{
			name: 'rentalProperty',
			display: '租车性质',
			sortable: true
		},{
			name: 'rentalPropertyCode',
			display: '租车性质编号',
			sortable: true,
			hide: true
		},{
			name: 'rentalContractCode',
			display: '租车合同编号',
			sortable: true
		},{
			name: 'rentalCarCost',
			display: '租车单价',
			sortable: true
		},{
			name: 'estimate',
			display: '评价',
			sortable: true,
			width : 300,
			align : 'left'
		}],
		menusEx: [{
			text: '查看',
			icon: 'view',
			action: function(row) {
				showModalWin("?model=outsourcing_vehicle_register&action=pageView"
						+ "&carNum=" + row.carNum
						+ "&allregisterId=" + row.allregisterId
						+ "&placeValuesBefore&TB_iframe=true&modal=false");
			}
		}],

		buttonsEx: [{
			name: 'excelOut',
			text: "导出",
			icon: 'excel',
			action: function(row) {
				showThickboxWin("?model=outsourcing_vehicle_register&action=toExcelOutProject"
					+ "&suppId=" + $("#suppId").val()
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
			}
		}],

		toAddConfig: {
			formWidth: 1000,
			formHeight: 300
		},
		toEditConfig: {
			formWidth: 1000,
			formHeight: 300,
			action: 'toEdit'
		},
		toViewConfig: {
			formWidth: 1000,
			formHeight: 500,
			action: 'toView'
		},

		searchitems: [{
			display: "车牌号",
			name: 'carNumber'
		},{
			display: "项目名称",
			name: 'projectNameSea'
		},{
			display: "项目编号",
			name: 'projectCodeE'
		},{
			display: "租车合同编号",
			name: 'rentalContractCodeE'
		}]
	});
});