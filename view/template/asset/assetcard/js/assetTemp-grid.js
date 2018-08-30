var show_page = function(page) {
	$("#assetTempGrid").yxgrid("reload");
};
$(function() {
	//初始化按钮
	var buttonsArr = [];
	importArr = {
			name : 'import',
			text : '卡片记录导入',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_assetcard_assetTemp&action=toImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
		}
	//获取卡片导入权限
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
	$("#assetTempGrid").yxgrid({
		model : 'asset_assetcard_assetTemp',
		title : '卡片新增',
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
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
//			name : 'englishName',
//			display : '英文名称',
//			sortable : true
//		}, {
			name : 'machineCode',
			display : '机器码',
			sortable : true
		}, {
//			name : 'assetTypeId',
//			display : '资产类别id',
//			sortable : true
//		}, {
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
//			name : 'unit',
//			display : '计量单位',
//			sortable : true
//		}, {
			name : 'buyDate',
			display : '购置日期',
			sortable : true
		}, {
//			name : 'place',
//			display : '存放地点',
//			sortable : true
//		}, {
			name : 'brand',
			display : '品牌',
			sortable : true
		}, {
//			name : 'useType',
//			display : '用途',
//			sortable : true
//		}, {
			name : 'spec',
			display : '规格型号',
			sortable : true
		}, {
			name : 'deploy',
			display : '配置',
			sortable : true
		}, {
//			name : 'origin',
//			display : '产地',
//			sortable : true
//		}, {
//			name : 'supplierName',
//			display : '供应商名称',
//			sortable : true
//		}, {
//			name : 'supplierId',
//			display : '供应商id',
//			sortable : true
//		}, {
//			name : 'manufacturers',
//			display : '制造商',
//			sortable : true
//		}, {
//			name : 'remark',
//			display : '备注',
//			sortable : true
//		}, {
////			name : 'assetabbrev',
////			display : '资产名称缩写',
////			sortable : true
////		}, {
//			name : 'belongManId',
//			display : '归属人Id',
//			sortable : true
//		}, {
			name : 'belongMan',
			display : '归属人',
			sortable : true
		}, {
//			name : 'userId',
//			display : '使用人Id',
//			sortable : true
//		}, {
//			name : 'userName',
//			display : '使用人名称',
//			sortable : true
//		}, {
//			name : 'useOrgId',
//			display : '使用部门id',
//			sortable : true
//		}, {
//			name : 'useOrgName',
//			display : '使用部门名称',
//			sortable : true
//		}, {
//			name : 'useProId',
//			display : '使用项目Id',
//			sortable : true
//		}, {
//			name : 'useProCode',
//			display : '使用项目编码',
//			sortable : true
//		}, {
//			name : 'useProName',
//			display : '使用项目名称',
//			sortable : true
//		}, {
//			name : 'orgId',
//			display : '所属部门id',
//			sortable : true
//		}, {
//			name : 'orgName',
//			display : '所属部门名称',
//			sortable : true
//		}, {
//			name : 'companyCode',
//			display : '所属公司编码',
//			sortable : true
//		}, {
//			name : 'companyName',
//			display : '所属公司名称',
//			sortable : true
//		}, {
//			name : 'belongErea',
//			display : '所属区域',
//			sortable : true
//		}, {
//			name : 'assetSourse',
//			display : '资产来源',
//			sortable : true
//		}, {
			name : 'assetSourceName',
			display : '资产来源名称',
			sortable : true
		}, {
//			name : 'productId',
//			display : '关联物料Id',
//			sortable : true
//		}, {
//			name : 'productCode',
//			display : '关联物料编码',
//			sortable : true
//		}, {
			name : 'productName',
			display : '关联物料名称',
			sortable : true
		}, {
//			name : 'agencyCode',
//			display : '所属区域编码',
//			sortable : true
//		}, {
			name : 'agencyName',
			display : '行政区域',
			sortable : true
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
		}],
		buttonsEx : buttonsArr,
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
		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.isCreate=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=asset_assetcard_assetTemp&action=init&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.isCreate=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				if (confirm('确定要删除该卡片？')) {
					$.ajax({
						type : 'POST',
						url : '?model=asset_assetcard_assetTemp&action=ajaxdeletes&skey='
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
		}],
//		comboEx : [{
//			text : '是否确认',
//			key : 'isCreate',
//			data : [{
//				text : '是',
//				value : '1'
//			}, {
//				text : '否',
//				value : '0'
//			}],
//			value : '0'
//		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '资产名称',
			name : 'assetName'
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
			display : '卡片编号',
			name : 'assetCodeSearch'
		}, {
			display : '机器码',
			name : 'machineCode'
		}, {
			display : '资产来源',
			name : 'assetSourceName'
		}]
	});
});