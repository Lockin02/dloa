var show_page = function(page) {
	$("#vehicleGrid").yxgrid("reload");
};
$(function() {
	$("#vehicleGrid").yxgrid({
		model : 'outsourcing_outsourcessupp_vehicle',
        title : '车辆供应商-车辆资源库',
        isAddAction:false,
		isEditAction:false,
		isDelAction:false,
		param:{'suppId' : $("#suppId").val()},
		bodyAlign:'center',
		showcheckbox:false,
		//列信息
			colModel : [{
 					display : 'id',
 						name : 'id',
 					sortable : true,
 						hide : true
			  },{
            			name : 'formBelong',
  					display : '数据归属公司',
  					sortable : true,
 						hide : true
              },{
    					name : 'formBelongName',
  					display : '数据归属公司CN',
  					sortable : true,
 						hide : true
              },{
    					name : 'businessBelong',
  					display : '业务归属公司',
  					sortable : true,
 						hide : true
              },{
    					name : 'businessBelongName',
  					display : '业务归属公司CN',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppCode',
  					display : '供应商编号',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppName',
  					display : '供应商名称',
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
			display : "车牌号",
			name : 'carNumber'
		},{
			display : "司机",
			name : 'driver'
		},{
			display : "身份证号",
			name : 'idNumber'
		}]
 	});
 });