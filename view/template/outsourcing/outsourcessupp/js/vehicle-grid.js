var show_page = function(page) {
	$("#vehicleGrid").yxgrid("reload");
};
$(function() {
	$("#vehicleGrid").yxgrid({
		isDelAction : false,
		model : 'outsourcing_outsourcessupp_vehicle',
		title : '车辆供应商-车辆资源库',
        bodyAlign : 'center',
        showcheckbox:false,
		//列信息
			colModel : [{
 					display : 'id',
 						name : 'id',
 					sortable : true,
 						hide : true
			  },{
    					name : 'suppId',
  					display : '供应商id',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppCode',
  					display : '供应商编号',
  					sortable : true,
						width : 70,
					process : function(v,row){
							return "<a href='#' onclick='showModalWin(\"?model=outsourcing_outsourcessupp_vehiclesupp&action=toViewTab&id=" + row.suppId +"\",1)'>" + v + "</a>";
					}
              },{
    					name : 'suppName',
  					display : '供应商名称',
						width : 150,
  					sortable : true
              },{
    					name : 'suppCategoryName',
  					display : '供应商类型名称',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppCategory',
  					display : '供应商类型',
  					sortable : true,
 						hide : true,
 						hide : true
              },{
    					name : 'suppLevel',
  					display : '供应商级别',
  					sortable : true,
 						hide : true
              },{
    					name : 'place',
  					display : '地点',
  					sortable : true
              },{
    					name : 'carNumber',
  					display : '车牌号',
  					sortable : true
              },{
    					name : 'carModel',
  					display : '车型',
  					sortable : true
              },{
    					name : 'brand',
  					display : '品牌',
  					sortable : true
              },{
    					name : 'displacement',
  					display : '排量',
  					sortable : true
              },{
    					name : 'powerSupply',
  					display : '车辆供电情况',
  					sortable : true
              },{
    					name : 'oilWear',
  					display : '综合油耗',
  					sortable : true
              },{
    					name : 'buyDate',
  					display : '购车日期',
  					sortable : true
              },{
    					name : 'driver',
  					display : '司机',
  					sortable : true
              },{
    					name : 'phoneNum',
  					display : '联系电话',
  					sortable : true
              },{
    					name : 'idNumber',
  					display : '身份证号',
  					sortable : true
              },{
    					name : 'drivingLicence',
  					display : '驾驶证',
  					sortable : true
              },{
    					name : 'vehicleLicense',
  					display : '行驶证',
  					sortable : true
              },{
    					name : 'insurance',
  					display : '保险',
  					sortable : true
              },{
    					name : 'annualExam',
  					display : '年审',
  					sortable : true
              },{
    					name : 'rentPrice',
  					display : '租车单价',
  					sortable : true,
  					process : function (v) {
  						return moneyFormat2(v);
  					}
              }],
		lockCol:['suppCode','suppName'],//锁定的列名
		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_outsourcessupp_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '从表字段'
					}]
		},

		buttonsEx : [{
			name : 'exportIn',
			text : "导入",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_outsourcessupp_vehicle&action=toExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600")
			}
		},{
			name : 'exportOut',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_outsourcessupp_vehicle&action=toExcelOutCustom"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
			}
		}],
		menusEx : [{
			text : '删除',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=outsourcing_outsourcessupp_vehicle&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#vehiclesuppGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}],

		toAddConfig : {
			formWidth : 1000,
			formHeight : 300
		},
		toEditConfig : {
			formWidth : 1000,
			formHeight : 300,
			action : 'toEdit'
		},
		toViewConfig : {
			formWidth : 1000,
			formHeight : 300,
			action : 'toView'
		},
		searchitems : [{
			display : "供应商编号",
			name : 'suppCodeSea'
		},{
			display : "供应商名称",
			name : 'suppName'
		},{
			display : "地点",
			name : 'place'
		},{
			display : "车牌号",
			name : 'carNumber'
		},{
			display : "车型",
			name : 'carModel'
		},{
			display : "品牌",
			name : 'brand'
		},{
			display : "排量",
			name : 'displacement'
		},{
			display : "车辆供电情况",
			name : 'powerSupply'
		},{
			display : "综合油耗",
			name : 'oilWear'
		},{
			display : "购车日期",
			name : 'buyDateSea'
		},{
			display : "司机",
			name : 'driver'
		},{
			display : "联系电话",
			name : 'phoneNum'
		},{
			display : "身份证号",
			name : 'idNumber'
		},{
			display : "驾驶证",
			name : 'drivingLicence'
		},{
			display : "行驶证",
			name : 'vehicleLicense'
		},{
			display : "保险",
			name : 'insurance'
		},{
			display : "年审",
			name : 'annualExam'
		}]
	});
});