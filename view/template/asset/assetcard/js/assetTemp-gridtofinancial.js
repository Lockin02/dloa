var show_page = function(page) {
	$("#assetTempGrid").yxgrid("reload");
};
$(function() {
	$("#assetTempGrid").yxgrid({
		model : 'asset_assetcard_assetTemp',
//		param : {'isCreate': 0},
		title : '财务确认卡片列表',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : 'isCreate',
			name : 'isCreate',
			sortable : true,
			hide : true
		}, {
			name : 'assetCode',
			display : '卡片编号',
			sortable : true,
			width : '150'
		}, {
			name : 'assetName',
			display : '资产名称',
			sortable : true
		}, {
			name : 'machineCode',
			display : '机器码',
			sortable : true
		}, {
			name : 'assetTypeName',
			display : '资产类别',
			sortable : true,
			process : function(v,row){
				if(row.assetTypeName == "手机" && (row.mobileBand != "" || row.mobileNetwork != "")){
					return v+" <img src='images/icon/msg.png' style='width:14px;height:14px;' title='手机频段: " +
					row.mobileBand+"，手机网络:"+row.mobileNetwork+"'/>";
				}
				return v;
			}
		}, {
			name : 'mobileBand',
			display : '手机频段',
			sortable : true,
			hide : true
		}, {
			name : 'mobileNetwork',
			display : '手机网络',
			sortable : true,
			hide : true
		}, {
			name : 'buyDate',
			display : '购置日期',
			sortable : true
		}, {
			name : 'brand',
			display : '品牌',
			sortable : true
		}, {
			name : 'spec',
			display : '规格型号',
			sortable : true
		}, {
			name : 'deploy',
			display : '配置',
			sortable : true
		}, {
			name : 'belongMan',
			display : '归属人',
			sortable : true
		}, {
			name : 'assetSourceName',
			display : '资产来源名称',
			sortable : true
		}, {
			name : 'productName',
			display : '关联物料名称',
			sortable : true
		}, {
			name : 'agencyName',
			display : '所属区域名称',
			sortable : true
		}],

		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		toAddConfig : {
			text : '新增',
			/**
			 * 默认点击新增按钮触发事件
			 */
			toAddFn : function(p) {
				showThickboxWin("?model=asset_assetcard_assetTemp&action=toadd"
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=1000');
			}
		},
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetTemp&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}
//		, {
//			text : '确认资产属性',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if(row.isCreate=='0'){
//					return true;
//				}else{
//					return false;
//				}
//			},
//			action : function(row) {
//				window.open('?model=asset_assetcard_assetTemp&action=totype&id='
//						+ row.id
//						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
//			}
		//}
		, {
			text : '录入财务数据',
			icon : 'view',
			showMenuFn : function(row) {
				if(row.isCreate=='1'&&row.isFinancial=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				window.open('?model=asset_assetcard_assetCard&action=toCreat&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}],
		buttonsEx : [{
			name : 'import',
			text : '卡片记录导入',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_assetcard_assetTemp&action=toImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
		}],
		comboEx : [{
			text : '是否确认',
			key : 'isFinancial',
			data : [{
				text : '是',
				value : '1'
			}, {
				text : '否',
				value : '0'
			}],
			value : '0'
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '资产名称',
			name : 'assetName'
		}, {
	//			display : '使用人',
	//			name : 'userName'
	//		}, {
	//			display : '使用部门',
	//			name : 'useOrgName'
	//		}, {
			display : '行政区域',
			name : 'agencyName'
		}, {
			display : '所属人',
			name : 'belongMan'
		}, {
			display : '所属部门',
			name : 'orgName'
		}, {
			display : '卡片编号',
			name : 'assetCodeSearch'
//		}, {
//			display : '品牌',
//			name : 'brand'
		}]
	});
});