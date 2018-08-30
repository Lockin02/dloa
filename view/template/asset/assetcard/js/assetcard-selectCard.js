var show_page = function(page) {
	// $("#proTypeTree").yxtree("reload");
	$("#assetGrid").yxgrid("reload");
};

$(function() {
	var titleVal = "<b>卡片多选  : 请勾选需要选择的卡片&nbsp;&nbsp;&nbsp;</b>";
	var showType = $("#showType").val();
	var agencyCode = $("#agencyCode").val();
	var deptId = $("#deptId").val();
	var param = '';
//不同页面，过滤参数param不同
	if(showType=='borrow'||showType=='charge'){
		param = {
				'useStatusCode' : 'SYZT-XZ',
				'agencyCode' : agencyCode,
				'machineCodeSearch':'0',
				'belongTo' : '0',
				'isScrap':'0'
			};
	}else if(showType=='allocation'){
		param = {
				'useStatusCode' : 'SYZT-XZ',
				'belongTo' : '0',
				'machineCodeSearch':'0',
				'isScrap' : '0'
			};
		if(deptId){
			param.orgId=deptId;
		}
		if(agencyCode){
			param.agencyCode=agencyCode;
		}
	}else if(showType=='sell'){
		param = {
				'isSell' : '0'
			};
	}else if(showType=='scrap' || showType=='requireout'){
		param = {
			'useStatusCode' : 'SYZT-XZ',
			'isScrap' : '0'
			};
	}else{
		param = {
				'isScrap':'0'
			};
	}
	$("#assetGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		title : titleVal,
		isToolBar : true,
		isViewAction : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : true,
		param : param,
		colModel : [{
					name : 'property',
					display : '资产属性',
					width : '70',
					process : function(v){
						if(v==0){
							return '固定资产'
						}else if(v==1){
							return '低值耐用品'
						}
					},
					sortable : true
				}, {
					name : 'assetTypeName',
					display : '资产类别',
					width : '70',
					sortable : true
				}, {
					name : 'id',
					display : '资产Id',
					hide : true,
					sortable : true
				}, {
					name : 'assetCode',
					display : '卡片编号',
					width : '160',
					sortable : true
				}, {
					name : 'machineCode',
					display : '机身码',
					width : '100',
					sortable : true
				}, {
					name : 'assetName',
					display : '资产名称',
					width : '120',
					sortable : true
				}, {
					name : 'useStatusCode',
					display : '使用状态编码',
					hide : true,
					sortable : true
				}, {
					name : 'useStatusName',
					display : '使用状态',
					width : '70',
					sortable : true
				}, {
					name : 'spec',
					display : '规格型号',
					sortable : true
				}, {
					name : 'unit',
					display : '计量单位',
					hide : true,
					sortable : true
				}, {
					name : 'account',
					display : '购进原值',
					hide : true,
					sortable : true
				}, {
					name : 'buyDate',
					display : '购置日期',
					sortable : true
				}, {
					name : 'orgId',
					display : '所属部门id',
					hide : true,
					sortable : true
				}, {
					name : 'orgName',
					display : '所属部门',
					sortable : true
				},{
					name : 'deploy',
					display : '配置',
					sortable : true
				}],
		menusEx : [{
			name : 'view',
			text : "查看",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_assetcard_assetcard&action=init&perm=view&id="
						+ row.id
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}],
        buttonsEx : [{
			name : 'Add',
			text : "确认选择",
			icon : 'add',
			action: function(row,rows,idArr ) {
				if(row){
					if(window.opener){
						window.opener.setDatas(rows);
					}
					//关闭窗口
					window.close();
				}else{
					alert('请选择一行数据');
				}
			}
        }],
		searchitems : [{
					display : '资产名称',
					name : 'assetName'
				}, {
					display : '卡片编号',
					name : 'assetCode'
				}],
		sortorder : 'DESC'
	});
});