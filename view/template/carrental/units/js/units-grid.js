var show_page = function(page) {
		$("#unitsGrid").yxgrid("reload");
};
$(function() {

	// 初始化表头按钮数组
	buttonsArr = [{
		// 导入EXCEL文件按钮
		name : 'import',
		text : "导入EXCEL",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=carrental_units_units&action=toUploadExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
		}

	}];

	$("#unitsGrid").yxgrid({
		model : 'carrental_units_units',
		isViewAction : false,
       	title : '租车单位',
		//列信息
					colModel : [{
 								display : 'id',
 								name : 'id',
 								sortable : true,
 								hide : true
					        },{
            					name : 'unitName',
  					display : '单位名称',
  					sortable : true,
  					width : 150
              },{
    					name : 'unitCode',
  					display : '单位编号',
  					sortable : true,
  					hide : true
              },{
    					name : 'address',
  					display : '单位地址',
  					sortable : true,
  					width : 200
              },{
    				name : 'unitNature',
  					display : '单位性质',
  					sortable : true,
  					datacode : "DWXZ"
          },{
					name : 'countryName',
  					display : '国家',
  					sortable : true,
  					hide : true
              },{
    					name : 'countryCode',
  					display : '国家编码',
  					sortable : true,
  					hide : true
              },{
    					name : 'provinceName',
  					display : '所属省份',
  					sortable : true
              },{
    					name : 'provinceCode',
  					display : '省份编码',
  					sortable : true,
  					hide : true
              },{
    					name : 'cityName',
  					display : '城市',
  					sortable : true
              },{
    					name : 'cityCode',
  					display : '城市编码',
  					sortable : true,
  					hide : true
              },{
    					name : 'linkMan',
  					display : '联系人',
  					sortable : true
              },{
    					name : 'linkPhone',
  					display : '联系电话',
  					sortable : true,
  					hide : true
              },{
    					name : 'remark',
  					display : '备注说明',
  					sortable : true,
  					hide : true
              },{
    					name : 'createId',
  					display : '创建人Id',
  					sortable : true,
  					hide : true
              },{
    					name : 'createName',
  					display : '录入人',
  					sortable : true
              },{
    					name : 'createTime',
  					display : '录入时间',
  					sortable : true,
  					width : 150
              },{
    					name : 'updateId',
  					display : '修改人Id',
  					sortable : true,
  					hide : true
              },{
    					name : 'updateName',
  					display : '修改人名称',
  					sortable : true,
  					hide : true
              },{
    					name : 'updateTime',
  					display : '修改时间',
  					sortable : true,
  					hide : true
              }],
			menusEx : [{
					text : '查看',
				icon : 'view',
				action : function(row, rows, grid) {
						if (row) {
							showThickboxWin("?model=carrental_units_units&action=viewTab&id="
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
						}
				}
			},{
			text : '新增车辆',
			icon : 'business',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=carrental_carinfo_carinfo&action=toUnitsAdd&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&&width=800");
				} else {
					alert("请选中一条数据");
				}
			}
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		buttonsEx : buttonsArr,
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '单位名称',
			name : 'unitName'
		}, {
			display : '联系人',
			name : 'linkMan'
		}]
 		});
 });