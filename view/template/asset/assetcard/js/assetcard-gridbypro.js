var show_page = function(page) {
	$("#assetbyproGrid").yxgrid("reload");
};
function isRelated( assetId ){
	var equNum = 1;
	 $.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=isRelated',
		data : {
			id : assetId
		},
	    async: false,
		success : function(data) {
			equNum = data;
			return false;
		}
	})
	return equNum;
}
$(function() {
	$("#assetbyproGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		action : 'pageByProJson',
		param : { 'projectId' :  $('#projectId').val() },
		title : '项目设备列表',
		customCode : 'assetcardGrid',
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'assetTypeName',
			display : '资产类别',
			sortable : true
		}, {
			name : 'assetCode',
			display : '卡片编号',
			sortable : true
		}, {
			name : 'assetName',
			display : '资产名称',
			sortable : true
		}, {
			name : 'englishName',
			display : '英文名称',
			hide : true,
			sortable : true
		}, {
			name : 'assetTypeId',
			display : '资产类别id',
			sortable : true,
			hide : true
		}, {
			name : 'unit',
			display : '计量单位',
			hide : true,
			sortable : true
		}, {
			name : 'buyDate',
			display : '购置日期',
			sortable : true
		}, {
			name : 'userId',
			display : '使用人id',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : '使用人',
			sortable : true
		}, {
			name : 'useStatusCode',
			display : '使用状态编码',
			sortable : true
		}, {
			name : 'useStatusName',
			display : '使用状态',
			sortable : true
		}, {
			name : 'changeTypeCode',
			display : '变动方式编码',
			sortable : true,
			hide : true
		}, {
			name : 'changeTypeName',
			display : '变动方式',
			sortable : true
		}, {
			name : 'useOrgId',
			display : '使用部门id',
			sortable : true,
			hide : true
		}, {
			name : 'useOrgName',
			display : '使用部门名称',
			sortable : true
		}, {
			name : 'orgId',
			display : '所属部门id',
			sortable : true,
			hide : true
		}, {
			name : 'orgName',
			display : '所属部门名称',
			sortable : true
		}, {
			name : 'useProId',
			display : '使用项目Id',
			sortable : true
		}, {
			name : 'useProName',
			display : '使用项目',
			sortable : true
		}, {
			name : 'spec',
			display : '规格型号',
			sortable : true,
			hide : true
		}],
		toAddConfig : {
			text : '新增',
			/**
			 * 默认点击新增按钮触发事件
			 */
			toAddFn : function(p) {
				showThickboxWin("?model=asset_assetcard_assetcard&action=toadd"
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
			}
		},
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '卡片编号',
			name : 'assetCode'
		}, {
			display : '资产名称',
			name : 'assetName'
		}]
	});
});