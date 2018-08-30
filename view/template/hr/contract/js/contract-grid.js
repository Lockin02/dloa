var show_page = function(page) {
	$("#contractGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [];

	//表头按钮数组
	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_contract_contract&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	excelUpdateArr = {
		name : 'exportIn',
		text : "导入更新",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_contract_contract&action=toExcelUpdate"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	excelOutSelect = {
		name : 'excelOutAllArr',
		text : "自定义导出信息",
		icon : 'excel',
		action : function() {
			if($("#totalSize").val() < 1) {
				alert("没有可导出的记录");
			}else{
				document.getElementById("form2").submit();
			}
		}
	}

	excelOutArr2 = {
		name : 'exportOut',
		text : "高级查询并导出",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_contract_contract&action=toExcelOut"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_contract_contract&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelUpdateArr);
				buttonsArr.push(excelOutArr2);
				buttonsArr.push(excelOutSelect);
			}
		}
	});

	$("#contractGrid").yxgrid({
		model : 'hr_contract_contract',
		title : '合同信息',
		isAddAction : true,
		isEditAction : true,
		isOpButton : false,
		bodyAlign : 'center',
		event : {
			'afterload' : function(data,g) {
				$("#listSql").val(g.listSql);
				$("#totalSize").val(g.totalSize);
			}
		},
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			width:90
		},{
			name : 'userName',
			display : '员工姓名',
			sortable : true,
			width:65
		},{
			name : 'conNo',
			display : '合同编号',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_contract_contract&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
		},{
			name : 'conName',
			display : '合同名称',
			sortable : true
		},{
			name : 'conTypeName',
			display : '合同类型',
			sortable : true
		},{
			name : 'conStateName',
			display : '合同状态',
			sortable : true,
			width:65
		},{
			name : 'beginDate',
			display : '合同开始时间',
			sortable : true
		},{
			name : 'closeDate',
			display : '合同结束时间',
			sortable : true
		},{
			name : 'conNumName',
			display : '合同次数',
			sortable : true
		},{
			name : 'conContent',
			display : '合同内容',
			sortable : true
		},{
			name : 'fileExist',
			display : '是否有附件',
			process : function(row ,v) {
				if(v['files'] == 0) {
					return v = "否";
				} else {
					return v = "是";
				}
			},
			width:65
		}],

		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toAddConfig : {
			action : 'toAdd',
			formWidth : '800',
			formHeight : '500'
		},
		toViewConfig : {
			action : 'toView'
		},

		// 默认搜索字段名
		sortname : "userNo",
		// 默认搜索顺序
		sortorder : "asc",

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '员工姓名',
			name : 'userName'
		},{
			display : '员工编号',
			name : 'userNo'
		},{
			display : '合同编号',
			name : 'conNo'
		},{
			display : '合同名称',
			name : 'conName'
		},{
			display : '合同类型',
			name : 'conTypeName'
		},{
			display : '合同状态',
			name : 'conStateName'
		}]
	});
});