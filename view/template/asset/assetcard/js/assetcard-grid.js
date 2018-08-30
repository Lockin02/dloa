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
	//资产类别下拉过滤
	var typeData = $.ajax({
		type : 'POST',
		url : "?model=asset_basic_directory&action=getSelection",
		async : false
	}).responseText;
	typeData = eval("(" + typeData + ")");
	var typeData2 = [];
	if (typeData) {
		for (var k = 0, kl = typeData.length; k < kl; k++) {
			var o = {
				value : typeData[k].value,
				text : typeData[k].text
			};
			typeData2.push(o);
		}
	}
	//行政区域下拉过滤
	var agencyData = $.ajax({
		type : 'POST',
		url : "?model=asset_basic_agency&action=getSelection",
		async : false
	}).responseText;
	agencyData = eval("(" + agencyData + ")");
	var agencyData2 = [];
	if (agencyData) {
		for (var k = 0, kl = agencyData.length; k < kl; k++) {
			var o = {
				value : agencyData[k].value,
				text : agencyData[k].text
			};
			agencyData2.push(o);
		}
	}
	buttonsArr = [{
			text : "重置",
			icon : 'delete',
			action : function(row) {
				var listGrid = $("#assetcardGrid").data('yxgrid');
				listGrid.options.extParam = {};
				$("#caseListWrap tr").attr('style',
				"background-color: rgb(255, 255, 255)");
				listGrid.reload();
			}
		},{
			name : 'import',
			text : '所属人批量更新',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_assetcard_assetcard&action=toUpdateBelongMan"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
	},{
		name : 'add',
		text : '批量更新',
		icon : 'add',
		action : function(row, rows, grid) {
			showModalWin("?model=asset_assetcard_assetcard&action=toUpdateCard",1,'update');
		}
	}]
	exportArr = {
	        name: 'excOut',
	        text: "导出",
			icon : 'excel',
			items : [{
				name : 'export',
				text : "卡片信息",
				icon : 'excel',
				action : function(row) {
					var colId = "";
					var colName = "";
					$("#assetcardGrid_hTable").children("thead").children("tr")
							.children("th").each(function() {
								if ($(this).css("display") != "none"
										&& $(this).attr("colId") != undefined) {
									if ($(this).attr("colId") != 'test') {
										colName += $(this).children("div").html()
												+ ",";
										colId += $(this).attr("colId") + ",";
									}
								}
							})
					window.open("?model=asset_assetcard_assetcard&action=exportExcel&colId="
									+ colId + "&colName=" + colName)
				}
			},{
				name : 'export',
				text : "卡片信息(CSV)",
				icon : 'excel',
				action : function(row) {
					var colId = "";
					var colName = "";
					$("#assetcardGrid_hTable").children("thead").children("tr")
							.children("th").each(function() {
								if ($(this).css("display") != "none"
										&& $(this).attr("colId") != undefined) {
									if ($(this).attr("colId") != 'test') {
										colName += $(this).children("div").html()
												+ ",";
										colId += $(this).attr("colId") + ",";
									}
								}
							})
					window.open("?model=asset_assetcard_assetcard&action=exportCSV&colId="
									+ colId + "&colName=" + colName)
				}
			},{
				name : 'export',
				text : "盘点信息",
				icon : 'excel',
				action : function(row) {
					window.open(
						"?model=asset_assetcard_assetcard&action=exportCheckExcel");
				}
			},{
				name : 'export',
				text : "盘点信息(CSV)",
				icon : 'excel',
				action : function(row) {
					window.open(
						"?model=asset_assetcard_assetcard&action=exportCheckCSV");
				}
			}]
		}
	importArr = {
			name : 'import',
			text : "卡片信息导入",
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_assetcard_assetcard&action=toImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
		}
	$.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=getLimits',
		data : {
			'limitName' : '卡片导出权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(exportArr);
			}
		}
	});
	$.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=getLimits',
		data : {
			'limitName' : '卡片导入权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(importArr);
			}
		}
	});
	//修改财务数据权限
	var financialLimit = false;
	$.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=getLimits',
		data : {
			'limitName' : '修改财务数据权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				financialLimit = true;
			}
		}
	});
	//卡片删除权限
	var deleteLimit = false;
	$.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=getLimits',
		data : {
			'limitName' : '卡片删除权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				deleteLimit = true;
			}
		}
	});

	$("#assetcardGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		title : '固定资产卡片',
		customCode : 'assetcardGrid',
		leftLayout : true,
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		isOpButton : false,
		event : {
			'afterload' : function(data, g) {
				$("#listSql").val(g.listSql);
			}
		},
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
			width : '150',
			sortable : true,
			process : function(v,row){
				if(row.remark != ""){
					return v+" <img src='images/icon/msg.png' style='width:14px;height:14px;' title='备注 : "+row.remark+"'/>";
				}
				return v;
			}
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
		}, {
			name : 'remark',
			display : '备注'
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
			text : '行政区域',
			key : 'agencyCode',
			data : agencyData2
		}, {
			text : '资产类别',
			key : 'assetTypeId',
			data : typeData2
		},{
			text : '使用状态',
			key : 'useStatusCodeArr',
			value : 'SYZT-XZ,SYZT-SYZ,SYZT-DBF,SYZT-DTK,SYZT-YTK,SYZT-WCS,SYZT-YQL,SYZT-YCZ,SYZT-WXZ,SYZT-QT',
			data : [{
						text : '闲置',
						value : 'SYZT-XZ'
					}, {
						text : '使用中',
						value : 'SYZT-SYZ'
					}, {
						text : '待报废',
						value : 'SYZT-DBF'
					}, {
						text : '已报废',
						value : 'SYZT-YBF'
					}, {
						text : '待退库',
						value : 'SYZT-DTK'
					}, {
						text : '已退库',
						value : 'SYZT-YTK'
					}, {
						text : '未出售',
						value : 'SYZT-WCS'
					}, {
						text : '已清理',
						value : 'SYZT-YQL'
					}, {
						text : '已出租',
						value : 'SYZT-YCZ'
					}, {
						text : '维修中',
						value : 'SYZT-WXZ'
					}, {
						text : '其它',
						value : 'SYZT-QT'
					}, {
						text : '非报废',
						value : 'SYZT-XZ,SYZT-SYZ,SYZT-DBF,SYZT-DTK,SYZT-YTK,SYZT-WCS,SYZT-YQL,SYZT-YCZ,SYZT-WXZ,SYZT-QT'
					}]
		}, {
			text : '资产来源',
			key : 'assetSource',
			data : [{
				text : '购买',
				value : 'ZCLY-GM'
			}, {
				text : '赠送',
				value : 'ZCLY-ZS'
			}, {
				text : '租赁',
				value : 'ZCLY-ZL'
			}]
		}, {
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
		buttonsEx : buttonsArr,
		//		 扩展按钮
		toAddConfig : {
			text : '新增',
			/**
			 * 默认点击新增按钮触发事件
			 */
			toAddFn : function(p) {
				showThickboxWin("?model=asset_assetcard_assetcard&action=toadd"
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=1000');
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
		}, {
			text : '编辑',
			icon : 'edit',
//			应班长需要，去掉限制2015.6.19
//			showMenuFn : function(row) {
//				if(row.version==1&&row.isBelong=='0'&&row.useStatusCode=='SYZT-XZ'){
//					return true;
//				}else{
//					return false;
//				}
//			},
			action : function(row) {
				showThickboxWin('?model=asset_assetcard_assetcard&action=toEditByAdmin&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=550&width=900');
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.version == 1 && row.isBelong == '0' && row.useStatusCode == 'SYZT-XZ' && deleteLimit){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				if (confirm('确定要删除该卡片？')) {
					$.ajax({
						type : 'POST',
						url : '?model=asset_assetcard_assetcard&action=ajaxdeletes&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if (data == 0) {
								alert('删除失败');
							} else {
								alert("删除成功");
								show_page();
							}
							return false;
						}
					});
				}
			}
		}, {
			text : '附属设备',
			icon : 'add',
			showMenuFn : function(row) {
				//待报废，已报废，待退库，已退库卡片不允许更改附属设备
				if(row.isBelong=='0' && (row.useStatusCode!='SYZT-DBF' && row.useStatusCode!='SYZT-YBF' && 
						row.useStatusCode!='SYZT-DTK' && row.useStatusCode!='SYZT-YTK')){
					return true;
				}else{
					return false;
				}
			},
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
			showMenuFn : function(row) {
				if(row.isBelong=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				window.location='?model=asset_change_assetchange&action=page&assetId='
						+ row.id
						+ '&assetCode='
						+ row.assetCode
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900';
			}
		}, {
			text : '变动',
			icon : 'edit',
//			应班长需要，去掉限制2015.6.19
//			showMenuFn : function(row) {
//				//待报废，已报废，待退库，已退库卡片不允许变动
//				if((isRelated(row.id)==1 || row.useStatusCode=='SYZT-XZ')&&row.isBelong=='0' && 
//						(row.useStatusCode!='SYZT-DBF' && row.useStatusCode!='SYZT-YBF' && 
//						 row.useStatusCode!='SYZT-DTK' && row.useStatusCode!='SYZT-YTK')){
//					return true;
//				}else{
//					return false;
//				}
//			},
			action : function(row) {
				showThickboxWin('?model=asset_assetcard_assetcard&action=tochange&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '资产调拨',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.useStatusCode=='SYZT-XZ'&&row.isBelong=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=asset_daily_allocation&action=toAddByCard&assetId='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '资产报废',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.useStatusCode=='SYZT-XZ'&&row.isBelong=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=asset_disposal_scrap&action=toAddByCard&assetId='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '修改财务数据',
			icon : 'edit',
			showMenuFn : function(row) {
				//待报废，已报废，待退库，已退库卡片不允许修改财务数据
				if(row.useStatusCode=='SYZT-DBF' || row.useStatusCode=='SYZT-YBF' || 
						row.useStatusCode=='SYZT-DTK' || row.useStatusCode=='SYZT-YTK')
					return false;
				return financialLimit;
			},
			action : function(row) {
				showThickboxWin('?model=asset_assetcard_assetcard&action=toEditfinancial&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=400');
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
			display : '规格型号',
			name : 'spec'
		}, {
			display : '使用人',
			name : 'userName'
		}, {
			display : '使用部门',
			name : 'useOrgName'
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
		}],
		
		// 高级搜索
		advSearchOptions : {
			modelName : 'assetcardInfo',
			// 选择字段后进行重置值操作
			selectFn : function($valInput) {
			},
			searchConfig : [{
				name : '购置日期',
				value : 'c.buyDate',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
										dateFmt : 'yyyy-MM-dd'
									});
					});
				}
			},
			{
				name : '开始使用日期',
				value : 'c.BeginTime',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
										dateFmt : 'yyyy-MM-dd'
									});
						});
				}
			},
			{	
				name : '入账日期',
				value : 'c.wirteDate',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
										dateFmt : 'yyyy-MM-dd'
									});
						});
				}
			},
			{
				name : '资产属性',
				value : 'c.property',
				type : 'select',
				options : [{
							'dataName' : '固定资产',
							'dataCode' : '0'
						}, {
							'dataName' : '低值耐用品',
							'dataCode' : '1'
						}]
			},
			{
				name : '资产类别',
				value : 'c.assetTypeName'
			},
			{
				name : '卡片编号',
				value : 'c.assetCode'
			},
			{
				name : '机器码',
				value : 'c.machineCodeSer'
			},
			{
				name : '品牌',
				value : 'c.brand'
			},
			{
				name : '规格型号',
				value : 'c.spec'
			},
			{
				name : '配置',
				value : 'c.deploy'
			},
			{
				name : '使用人',
				value : 'c.userName'
			},
			{
				name : '使用状态',
				value : 'c.useStatusName'
			},
			{
				name : '变动方式',
				value : 'c.changeTypeName'
			},
			{
				name : '使用部门名称',
				value : 'c.useOrgName'
			},
			{
				name : '行政区域',
				value : 'c.agencyName'
			},
			{
				name : '所属部门名称',
				value : 'c.orgName'
			},
			{
				name : '所属人',
				value : 'c.belongMan'
			},
			{
				name : '归属资产编码',
				value : 'c.belongToCode'
			},
			{
				name : '资产来源',
				value : 'c.assetSource',
				type : 'select',
				options : [{
							'dataName' : '购买',
							'dataCode' : 'ZCLY-GM'
						}, {
							'dataName' : '赠送',
							'dataCode' : 'ZCLY-ZS'
						}, {
							'dataName' : '租赁',
							'dataCode' : 'ZCLY-ZL'
						}]
			}]
		}
	});
});