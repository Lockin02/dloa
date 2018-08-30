var show_page = function(page) {
	$("#conprojectListGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [{
		text : "合同项目导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=contract_conproject_conproject&action=toExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	}],
	LBDC = {
		name : 'export',
		text : "列表数据导出",
		icon : 'excel',
		action : function(row) {
			var searchConditionKey = "";
			var searchConditionVal = "";
			for (var t in $("#conprojectListGrid").data('yxgrid').options.searchParam) {
				if (t != "") {
					searchConditionKey += t;
					searchConditionVal += $("#conprojectListGrid")
							.data('yxgrid').options.searchParam[t];
				}
			}
			var i = 1;
			var colId = "";
			var colName = "";
			$("#conprojectListGrid_hTable").children("thead").children("tr")
					.children("th").each(function() {
						if ($(this).css("display") != "none"
								&& $(this).attr("colId") != undefined) {
							colName += $(this).children("div").html() + ",";
							colId += $(this).attr("colId") + ",";
							i++;
						}
					})
			var searchSql = $("#conprojectListGrid").data('yxgrid').getAdvSql();
			var searchArr = [];
			searchArr[0] = searchSql;
			searchArr[1] = searchConditionKey;
			searchArr[2] = searchConditionVal;

			window
					.open("?model=contract_conproject_conproject&action=exportExcel&colId="
							+ colId
							+ "&colName="
							+ colName
							+ "&searchConditionKey="
							+ searchConditionKey
							+ "&searchConditionVal=" + searchConditionVal)
		}
	}
	$.ajax({
		type : 'POST',
		url : '?model=contract_conproject_conproject&action=getLimits',
		data : {
			'limitName' : '列表导出'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(LBDC);
			}
		}
	});

	$("#conprojectListGrid").yxgrid({
		model : 'contract_conproject_conproject',
		action : 'conprojectJson',
		customCode : 'conprojectList',
		title : '合同项目表',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : 'esmProjectId',
			name : 'esmProjectId',
			sortable : true,
			hide : true
		}, {
			name : 'contractId',
			display : '合同id',
			sortable : true,
			hide : true
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 120,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>"
						+ row.contractCode
						+ "</font>" + '</a>';
			}
		}, {
			name : 'contractMoney',
			display : '合同金额',
			sortable : true,
			hide : true
		}, {
			name : 'projectCode',
			display : '项目号',
			sortable : true,
			width : 150,
			process : function(v, row) {
				if(row.esmProjectId != ''){
					var skey = "";
				    $.ajax({
					    type: "POST",
					    url: "?model=engineering_project_esmproject&action=md5RowAjax",
					    data: { "id" : row.esmProjectId },
					    async: false,
					    success: function(data){
					   	   skey = data;
						}
					});
				    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=engineering_project_esmproject&action=viewTab&id='
						+ row.esmProjectId
						+'&skey='
						+ skey
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>"
						+ v
						+ "</font>" + '</a>';
				}else{
				    return v;

				}
			}
		}, {
			name : 'proLineCode',
			display : '产品线编码',
			sortable : true,
			hide : true
		}, {
			name : 'proLineName',
			display : '产品线名称',
			sortable : true
		}, {
			name : 'proportion',
			display : '占比',
			sortable : true,
			width : 50,
			process : function(v) {
				return v + "%";
			}
		}, {
			name : 'proMoney',
			display : '所占合同额',
			sortable : true,
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'status',
			display : '项目状态',
			sortable : true,
			width : 50,
			datacode : 'GCXMZT'
		}, {
			name : 'schedule',
			display : '项目进度',
			sortable : true,
			process : function(v) {
				var v = formatProgress(v);
				return v;
			}
		}, {
			name : 'estimates',
			display : '概算',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'exgross',
			display : '预计毛利率',
			sortable : true,
			process : function(v) {
				if(v)
				 return v + "%";
				else
				 return "-";
			}
		}, {
			name : 'budget',
			display : '预算',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'cost',
			display : '决算',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'earnings',
			display : '收入',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'earningsTypeName',
			display : '收入确认方式',
			width : 80,
			sortable : true,
			hide : true
		}, {
			name : 'earningsTypeCode',
			display : '收入确认方式编码',
			sortable : true,
			hide : true
		}, {
			name : 'planBeginDate',
			display : '预计开始日期',
			sortable : true,
			hide : true
		}, {
			name : 'planEndDate',
			display : '预计结束日期',
			sortable : true,
			hide : true
		}, {
			name : 'actBeginDate',
			display : '实际开始日期',
			sortable : true,
			hide : true
		}, {
			name : 'actEndDate',
			display : '实际结束日期',
			sortable : true,
			hide : true
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "合同编号",
			name : 'contractCode'
		}, {
			display : "项目编号",
			name : 'projectCode'
		}, {
			display : "产品线名称",
			name : 'proLineName'
		}],
		menusEx : [{
			text : '确认收入核算方式',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=contract_conproject_conproject&action=incomeAcc&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600');
			}
		}],
		comboEx : [{
			text : '产品线',
			key : 'proLineCode',
			datacode : 'GCSCX'
		},{
			text : '项目状态',
			key : 'status',
			datacode : 'GCXMZT'
		}],
		buttonsEx : buttonsArr
	});
});

//用于列表进度显示
function formatProgress(value) {
	if (value) {
		var s = '<div style="width:auto;height:auto;border:1px solid #ccc;padding: 0px;">'
				+ '<div style="width:'
				+ value
				+ '%;background:#66FF66;white-space:nowrap;padding: 0px;">'
				+ value + '%' + '</div>'
		'</div>';
		return s;
	} else {
		return '';
	}
}