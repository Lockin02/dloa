var show_page = function(page) {
	$("#assetcardGrid").yxgrid("reload");
};
$(function() {
	//资产类别下拉过滤
	var typeDate = $.ajax({
		type : 'POST',
		url : "?model=asset_basic_directory&action=getSelection",
		async : false
	}).responseText;
	typeDate = eval("(" + typeDate + ")");
	var typeDate2 = [];
	if (typeDate) {
		for (var k = 0, kl = typeDate.length; k < kl; k++) {
			var o = {
				value : typeDate[k].value,
				text : typeDate[k].text
			};
			typeDate2.push(o);
		}
	}

//	//使用状态下拉过滤
//	var statusDate = $.ajax({
//		type : 'POST',
//		url : "?model=asset_basic_directory&action=getSelection",
//		async : false
//	}).responseText;
//	statusDate = eval("(" + statusDate + ")");
//	var statusDate2 = [];
//	if (statusDate) {
//		for (var k = 0, kl = statusDate.length; k < kl; k++) {
//			var o = {
//				value : statusDate[k].value,
//				text : statusDate[k].text
//			};
//			statusDate2.push(o);
//		}
//	}


	$("#assetcardGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		title : '固定资产卡片',
		customCode : 'assetcardGridView',
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		isAddAction : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'property',
			display : '资产属性',
			sortable : true,
			process : function(v){
				if( v=='0' ){
					return '固定资产';
				}else{
					return '低值耐用品'
				}
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
			name : 'brand',
			display : '品牌',
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
			name : 'englishName',
			display : '英文名称',
			hide : true,
			sortable : true
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
			display : '所属部门名称',
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
			name : 'belongTo',
			display : '归属资产',
			hide : true,
			sortable : true
		}, {
			name : 'belongToCode',
			display : '归属资产编码',
			sortable : true
		}, {
			name : 'isBelong',
			display : '是否附属',
			hide : true,
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
		comboEx : [{
			text : '资产属性',
			key : 'property',
			data : [{
				text : '固定资产',
				value : '0'
			}, {
				text : '低值耐用品',
				value : '1'
			}]
		}, {
			text : '资产类别',
			key : 'assetTypeId',
			data : typeDate2
		},{
			text : '使用状态',
			key : 'useStatusCode',
			datacode : 'SYZT'
		},{
			text : '是否附属',
			key : 'isBelong',
			value : '0',
			data : [{
				text : '否',
				value : '0'
			}, {
				text : '是',
				value : '1'
			}]
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
			text : '附属设备',
			icon : 'add',
			action : function(row) {
				window.location='?model=asset_assetcard_equip&action=page&assetId='
						+ row.id
						+ '&assetCode='
						+ row.assetCode
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900';
			}
		}, {
			text : '变动记录',
			icon : 'add',
			action : function(row) {
				window.location='?model=asset_change_assetchange&action=page&assetId='
						+ row.id
						+ '&assetCode='
						+ row.assetCode
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900';
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
		}, {
			display : '资产需求编号',
			name : 'requireCode'
		}]
	});
});