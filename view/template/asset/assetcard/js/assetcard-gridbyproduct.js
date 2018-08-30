var show_page = function(page) {
	$("#assetcardGrid").yxgrid("reload");
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
	$("#assetcardGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		title : '固定资产卡片',
		param : {'productId':$('#productId').val()},
		customCode : 'assetcardGrid',
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
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
		}, {
			name : 'deprName',
			display : '折旧方式名称',
			sortable : true,
			hide : true
		}, {
			name : 'subName',
			display : '固定资产科目名称',
			sortable : true,
			hide : true
		}, {
			name : 'depSubName',
			display : '累计折旧科目名称',
			sortable : true,
			hide : true
		}, {
			name : 'origina',
			display : '购进原值',
			hide : true,
			sortable : true
		}, {
			name : 'buyDepr',
			display : '购进累计折旧',
			hide : true,
			sortable : true
		}, {
			name : 'beginTime',
			display : '开始使用日期',
			sortable : true,
			hide : true
		}, {
			name : 'estimateDay',
			display : '预计使用期间数',
			sortable : true,
			hide : true
		}, {
			name : 'alreadyDay',
			display : '已使用期间数',
			sortable : true,
			hide : true
		}, {
			name : 'depreciation',
			display : '累计折旧',
			hide : true,
			sortable : true
		}, {
			name : 'salvage',
			display : '预计净残值',
			hide : true,
			sortable : true
		}, {
			name : 'netValue',
			display : '净值',
			hide : true,
			sortable : true
		}, {
			name : 'version',
			display : '版本号',
			sortable : true
		}, {
			name : 'isDel',
			display : '是否被清理',
			hide : true,
			sortable : true,
				process : function(val) {
						if (val == "0") {
							return "未清理";
						}
						if (val == "1") {
							return "已清理";
						}
					}
		}, {
		    name : 'isScrap',
			display : '报废状态',
			hide : true,
			sortable : true,
				process : function(val) {
						if (val == "0") {
							return "未报废";
						}
						if (val == "1") {
							return "已报废";
						}
					}
		}],
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