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
		param : { 'currentId' :  $('#userId').val() },
		title : '我的资产列表',
		customCode : 'myAssetcardGrid',
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
			display : '资产需求申请id',
			name : 'requireId',
			sortable : true,
			hide : true
		}, {
			display : '资产需求申请编号',
			name : 'requireCode',
			width : '150',
			sortable : true,
			 process : function(v,row){
				 if(v != ""){
					 return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=asset_require_requirement&action=toViewTab&requireId=" + row.requireId + '&requireCode=' + v +"\")'>" + v + "</a>";
				 }
			 }
		}, {
			name : 'machineCode',
			display : '机器码',
			width : '150',
			sortable : true
		}, {
			name : 'spec',
			display : '规格型号',
			sortable : true
		}, {
			name : 'assetName',
			display : '资产名称',
			sortable : true,
			process : function(v,row){
				if(v == "手机" && (row.mobileBand != "" || row.mobileNetwork != "")){
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
			name : 'unit',
			display : '计量单位',
			hide : true,
			sortable : true
		}, {
			name : 'buyDate',
			display : '购置日期',
			sortable : true
		}, {
			name : 'userName',
			display : '使用人',
			sortable : true
		}, {
			name : 'useStatusName',
			display : '使用状态',
			sortable : true
		}, {
			name : 'changeTypeName',
			display : '变动方式',
			sortable : true
		}, {
			name : 'useOrgName',
			display : '使用部门名称',
			sortable : true
		}, {
			name : 'orgName',
			display : '所属部门名称',
			sortable : true
//			name : 'useProName',
//			display : '使用项目',
//			sortable : true
//		}, {
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			name : 'edit',
			text : '归还资产',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.useStatusCode == 'SYZT-SYZ') {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					//验证卡片是否已提交过归还,防止重复提交申请
					var isReturning = false;
					$.ajax({
						type : 'POST',
						url : '?model=asset_assetcard_assetcard&action=isReturning',
						data : {
							id : row.id
						},
					    async: false,
						success : function(data) {
							if(data == 1){
								alert("该资产卡片已提交过归还申请,请勿重复提交");
								isReturning = true;
							}
						}
					})
					if(isReturning){
						return false;
					}
					showThickboxWin("?model=asset_daily_return&action=toReturnAsset&assetId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '卡片编号',
			name : 'assetCode'
		}, {
			display : '机器码',
			name : 'machineCodeSer'
		}, {
			display : '资产名称',
			name : 'assetName'
		}]
	});
});