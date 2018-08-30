var show_page = function(page) {
	// $("#proTypeTree").yxtree("reload");
	$("#assetGrid").yxgrid("reload");
};

$(function() {
	var showcheckbox = $("#showcheckbox").val();
	var showButton = $("#showButton").val();

	var textArr = [];
	var valArr = [];
	var indexArr = [];
	var combogrid = window.dialogArguments[0];
	var opener = window.dialogArguments[1];
	var p = combogrid.options;
	var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
	var titleVal = "<b>卡片单选  : 请双击需要选择的卡片&nbsp;&nbsp;&nbsp;</b>";

	if (!showcheckbox) { // 如果是单选，则隐藏文本域
		if (eventStr.row_dblclick) {
			var dbclickFunLast = eventStr.row_dblclick;
			eventStr.row_dblclick = function(e, row, data) {
				var isReturn = dbclickFunLast(e, row, data);
				if (isReturn !== false) {
					window.returnValue = row.data('data');
					window.close();
				}
			};
		} else {
			eventStr.row_dblclick = function(e, row, data) {
				window.close();
			};
		}

	} else {// 多选
		titleVal = "<b>卡片多选  : 请勾选需要选择的卡片&nbsp;&nbsp;&nbsp;</b>";
		var rowCheckFunLast = function() {
		};
		if (eventStr.row_check) {
			rowCheckFunLast = eventStr.row_check;
		}
		eventStr.row_check = function(e, checkbox, row, rowData) {
			var el = combogrid.el;
			if (p.hiddenId) {
				if (checkbox.attr('checked')) {
					if (p.idArr.indexOf(rowData[p.valueCol]) == -1) {
						p.idArr.push(rowData[p.valueCol]);
						p.nameArr.push(rowData[p.nameCol]);
					}
				} else {
					// 如果值存在，删除数组项
					var index = p.idArr.indexOf(rowData[p.valueCol]);
					if (index != -1) {
						p.idArr.splice(index, 1);
						p.nameArr.splice(index, 1);
					}
				}
				p.nameStr = p.nameArr.toString();
				p.idStr = p.idArr.toString();
				if (p.isShowName == true) {
					opener.$("#" + el.attr('id')).val(p.nameStr);
				}
				opener.$("#" + p.hiddenId).val(p.idStr);
				// 选择后促发事件
				row.trigger('after_row_check', [checkbox, row, rowData]);
			}
			rowCheckFunLast(e, checkbox, row, rowData);
		}
		var checkIds = $("#checkIds").val();
		eventStr.row_success = function(e, rows, g) {
			for (var i = 0, l = rows.size(); i < l; i++) {
				var rowData = $(rows[i]).data('data');
				var v = rowData[combogrid.options.valueCol];
				if (v) {
					if (("," + checkIds + ",").indexOf("," + v + ",") != -1) {
						var checkbox = g.getCheckboxByRow(rows[i]);
						checkbox.trigger('click', [true]);
					}
				}
			}
		}
	}

	var gridOptions = combogrid.options.gridOptions;
	$("#assetGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		action : gridOptions.action,
		title : titleVal,
		isToolBar : true,
		isViewAction : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : showcheckbox,
		param : gridOptions.param,
		pageSize : 40,
		imSearch : true,// 即时搜索
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
//		buttonsEx : [{
//			text : '确认添加',
//			icon : 'add',
//			action : function(row, rows, idArr) {
//				if(row){
//					if(window.opener){
//						if(showCheckBox==true){
//							confirmAudit();
//							window.opener.setDates(rows);
//						}else{
//							confirmAudit();
//							window.opener.setDates(row);
//						}
//					}
//					window.close();
//				}else{
//					alert('请选择一行数据！');
//				}
//			}
//		}],

		searchitems : [{
					display : '资产名称',
					name : 'assetName'
				}, {
					display : '卡片编号',
					name : 'assetCode'
				}],
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// 把事件复制过来
		event : eventStr

	});
});