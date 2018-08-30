var show_page = function(page) {
	$("#changeGrid").yxgrid("reload");
};
$(function() {
	$("#changeGrid").yxgrid({
		model : 'asset_change_assetchange',
		param : {'assetId':$('#assetId').val()},
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,

		title : '变动记录',

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '资产Id',
			name : 'acId',
			sortable : true,
			hide : true
		}, {
			name : 'businessCode',
			display : '业务单编号',
			sortable : true,
			process : function(v,row){
				if(row.businessId){
					switch (row.businessType) {
						case 'borrow' :
								return '<a href="#" onclick="javascript:window.open(\'?model=asset_daily_borrow&action=init&perm=view&id='+row.businessId+'\')">'+row.businessCode+'</a>';
							break;
						case 'allocation' :
								return '<a href="#" onclick="javascript:window.open(\'?model=asset_daily_allocation&action=init&perm=view&id='+row.businessId+'\')">'+row.businessCode+'</a>';
							break;
						case 'charge' :
								return '<a href="#" onclick="javascript:window.open(\'?model=asset_daily_charge&action=init&perm=view&id='+row.businessId+'\')">'+row.businessCode+'</a>';
							break;
						case 'return' :
								return '<a href="#" onclick="javascript:window.open(\'?model=asset_daily_return&action=init&perm=view&id='+row.businessId+'\')">'+row.businessCode+'</a>';
							break;
						case 'keep' :
								return '<a href="#" onclick="javascript:window.open(\'?model=asset_daily_keep&action=init&perm=view&id='+row.businessId+'\')">'+row.businessCode+'</a>';
							break;
						default :
							break;
					}
				}
			},
			width : 120
		}, {
			name : 'alterDate',
			display : '变动日期',
			sortable : true,
			width : 70
		}, {
			name : 'assetTypeName',
			display : '资产类别',
			sortable : true,
			width : 70
		}, {
			name : 'assetCode',
			display : '卡片编号',
			sortable : true,
			width : 160
		}, {
			name : 'assetName',
			display : '资产名称',
			sortable : true,
			width : 150
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
			sortable : true,
			width : 70
		}, {
			name : 'wirteDate',
			display : '入账日期',
			sortable : true,
			width : 70
		}, {
			name : 'useStatusId',
			display : '使用状态id',
			sortable : true,
			hide : true
		}, {
			name : 'useStatusName',
			display : '使用状态',
			sortable : true,
			width : 70
		}, {
			name : 'spec',
			display : '规格型号',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '备注',
			hide : true,
			sortable : true
		}, {
			name : 'subId',
			display : '固定资产科目id',
			sortable : true,
			hide : true
		}, {
			name : 'subName',
			display : '固定资产科目名称',
			sortable : true,
			hide : true
		}, {
			name : 'useOrgId',
			display : '使用部门id',
			sortable : true,
			hide : true
		}, {
			name : 'useOrgName',
			display : '使用部门名称',
			sortable : true,
			width : 80
		}, {
			name : 'orgId',
			display : '所属部门id',
			sortable : true,
			hide : true
		}, {
			name : 'orgName',
			display : '所属部门名称',
			sortable : true,
			width : 80
		}, {
			name : 'origina',
			display : '购进原值',
			hide : true,
			sortable : true
		}, {
			name : 'version',
			display : '版本号',
			sortable : true,
			width : 50
		}],
		buttonsEx : [{
			name : 'Review',
			text : "返回",
			icon : 'view',
			action : function() {
				history.back();
			}
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=asset_assetcard_assetcard&action=toViewDetail&perm=view&id='
						+ row.acId
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}]
	});
});