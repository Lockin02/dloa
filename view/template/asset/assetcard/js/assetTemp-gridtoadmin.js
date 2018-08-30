var show_page = function(page) {
	$("#assetTempGrid").yxgrid("reload");
};
$(function() {
	$("#assetTempGrid").yxgrid({
		model : 'asset_assetcard_assetTemp',
//		param : {'isCreate': 0},
		action : 'adminJson',
		title : '行政确认卡片列表',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
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
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetTemp&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '确认资产属性',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.isCreate=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				window.open('?model=asset_assetcard_assetTemp&action=totype&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}],
		comboEx : [{
			text : '是否确认',
			key : 'isCreate',
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
//		}, {
//			display : '品牌',
//			name : 'brand'
		}]
	});
});