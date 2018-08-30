var show_page = function(page) {
	$("#assetcardGrid").yxgrid("reload");
};
$(function() {
	$("#assetcardGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		action : 'pageJsonOther',
		title : '已清理低值耐用品列表',
		param : {
			isDel : 1,
			useStatusCode : 'SYZT-YQL'
		},
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		isOpButton : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'assetCode',
			display : '卡片编号',
			width : '150',
			sortable : true,
			process : function(v,row){
				if(row.remark != ""){
					return v+" <img src='images/icon/msg.png' style='width:14px;height:14px;' title='备注 : "+row.remark+"'/>";
				}
				return v;
			}
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
			name : 'assetTypeId',
			display : '资产类别id',
			sortable : true,
			hide : true
		}, {
			name : 'assetTypeName',
			display : '资产类别',
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
			name : 'brand',
			display : '品牌',
			sortable : true
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
			name : 'englishName',
			display : '英文名称',
			hide : true,
			sortable : true
		}, {
			name : 'assetSource',
			display : '资产来源编号',
			hide : true,
			sortable : true
		}, {
			name : 'assetSourceName',
			display : '资产来源',
			sortable : true
		}, {
			name : 'unit',
			display : '单位',
			hide : true,
			sortable : true
		}, {
			name : 'buyDate',
			display : '购置日期',
			sortable : true
		}, {
			name : 'wirteDate',
			display : '入账日期',
			sortable : true
		}, {
			name : 'deploy',
			display : '配置',
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
			display : '使用部门',
			sortable : true
		}, {
			name : 'agencyName',
			display : '行政区域',
			sortable : true
		}, {
			name : 'orgId',
			display : '所属部门id',
			sortable : true,
			hide : true
		}, {
			name : 'orgName',
			display : '所属部门',
			sortable : true
		}, {
			name : 'belongManId',
			display : '所属人Id',
			sortable : true,
			hide : true
		}, {
			name : 'belongMan',
			display : '所属人',
			sortable : true
		}, {
			name : 'useProId',
			display : '使用项目Id',
			sortable : true,
			hide : true
		}, {
			name : 'useProName',
			display : '使用项目',
			hide : true,
			sortable : true
		}, {
			name : 'spec',
			display : '规格型号',
			sortable : true,
			hide : true
		}, {
			name : 'deprName',
			display : '折旧方式',
			sortable : true,
			hide : true
		}, {
			name : 'subName',
			display : '固定资产科目',
			sortable : true,
			hide : true
		}, {
			name : 'depSubName',
			display : '累计折旧科目',
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
			hide : true,
			sortable : true
		}, {
			name : 'belongTo',
			display : '归属资产',
			hide : true,
			sortable : true
		}, {
			name : 'belongToCode',
			display : '归属资产编码',
			width : '160',
			sortable : true
		}, {
			name : 'isBelong',
			display : '是否附属',
			width : '60',
			hide : true,
			sortable : true
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
		}, {
			name : 'remark',
			display : '备注'
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
		}, {
			display : '机器码',
			name : 'machineCodeSer'
		}, {
			display : '使用人',
			name : 'userName'
		}, {
			display : '使用部门',
			name : 'useOrgName'
		}, {
			display : '行政区域',
			name : 'agencyName'
		}, {
			display : '所属人',
			name : 'belongMan'
		}, {
			display : '所属部门',
			name : 'orgName'
		}, {
			display : '资产来源',
			name : 'assetSourceNameSer'
		}]
	});
});