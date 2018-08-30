var show_page = function(page) {
	$("#rentalcarGrid").yxgrid("reload");
};
$(function() {
	var buttonsArr = [];
	var excelOut = {
		name : 'excelOut',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			window.open("?model=outsourcing_vehicle_rentalcar&action=excelOut"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=40&width=60");
		}
	};
	var excelOutCustom = {
		name : 'exportOut',
		text : "自定义导出",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_vehicle_rentalcar&action=toExcelOutCustom"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=outsourcing_vehicle_rentalcar&action=getLimits',
		data : {
			'limitName' : '导出权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOut);
				buttonsArr.push(excelOutCustom);
			}
		}
	});

	$("#rentalcarGrid").yxgrid({
		model : 'outsourcing_vehicle_rentalcar',
		param : {'stateArr' : "5','6','7"},
		title : '租车的申请与受理',
		bodyAlign : 'center',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			width : 160,
			process: function(v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=outsourcing_vehicle_rentalcar&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name : 'state',
			display : '单据状态',
			sortable : true,
			width : 60,
			process : function (v) {
				switch (v) {
					case '0' : return '保存';break;
					case '1' : return '审批中';break;
					case '2' : return '审批完成';break;
					case '3' : return '打回';break;
					case '4' : return '接口人审核';break;
					case '5' : return '待确认';break;
					case '6' : return '完成';break;
					case '7' : return '打回';break;
					default : return '';
				}
			}
		},{
//			name : 'ExaStatus',
//			display : '审批状态',
//			sortable : true,
//			width : 60,
//			process : function (v) {
//				if (v == 0) {
//					return '未提交';
//				}
//				return v;
//			}
//		},{
			name : 'projectCode',
			display : '项目编号',
			sortable : true,
			width : 200
		},{
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width : 200
		},{
			name : 'projectType',
			display : '项目类型',
			sortable : true,
			width : 60
		},{
			name : 'rentalProperty',
			display : '租车性质',
			sortable : true,
			width : 60
		},{
			name : 'createName',
			display : '申请人',
			sortable : true,
			width : 80
		},{
			name : 'createTime',
			display : '申请时间',
			sortable : true,
			width : 130
		},{
			name : 'applicantPhone',
			display : '申请人电话',
			sortable : true,
			width : 80
		},{
			name : 'province',
			display : '用车地点',
			sortable : true,
			process : function (v ,row) {
				return v + "-" + row.city;
			}
		},{
			name : 'useCarAmount',
			display : '用车数量',
			sortable : true,
			width : 50
		},{
			name : 'expectStartDate',
			display : '预计开始用车时间',
			sortable : true
		},{
			name : 'useCycle',
			display : '用车周期',
			sortable : true
		}],

		buttonsEx : buttonsArr,

		menusEx : [{
			text : '确认供应商',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '5') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=outsourcing_vehicle_rentalcar&action=toAffirmEdit&id=' + row.id ,1);
			}
		},{
			text : '打回',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == '5') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=outsourcing_vehicle_rentalcar&action=toBack&id=' + row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=900");
			}
		},{
			text : '打回原因',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.backReason != '') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=outsourcing_vehicle_rentalcar&action=toBackReason&id=' + row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=900");
			}
		},{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_rentalcar&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx : [{
			text: "单据状态",
			key: 'state',
			data : [{
				text : '待确认',
				value : '5'
			},{
				text : '打回',
				value : '7'
			},{
				text : '完成',
				value : '6'
			}]
		},{
			text: "租车性质",
			key: 'rentalPropertyCode',
			datacode : 'GCZCXZ'
		}],

		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_vehicle_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '从表字段'
					}]
		},

		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_rentalcar&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},
		searchitems : [{
			display : "单据编号",
			name : 'formCode'
		},{
			display : "项目编号",
			name : 'projectCode'
		},{
			display : "项目名称",
			name : 'projectName'
		},{
			display : "项目类型",
			name : 'projectType'
		},{
			display : "申请人",
			name : 'createNameSea'
		},{
			display : "申请时间",
			name : 'createTimeSea'
		},{
			display : "申请电话",
			name : 'applicantPhone'
		},{
			display : "用车地点",
			name : 'useCarPlace'
		},{
			display : "用车数量",
			name : 'useCarAmountSea'
		},{
			display : "预计开始用车时间",
			name : 'expectStartDateSea'
		},{
			display : "用车周期",
			name : 'useCycleSea'
		}]
 	});
 });