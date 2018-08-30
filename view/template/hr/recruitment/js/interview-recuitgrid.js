var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [
//        {
//			name : 'view',
//			text : "高级查询",
//			icon : 'view',
//			action : function() {
//				alert('功能暂未开发完成');
//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
//			}
//        }
    ];

	//表头按钮数组
	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recruitment_interview&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_recruitment_interview&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
			}
		}
	});

	$("#interviewGrid").yxgrid({
		model : 'hr_recruitment_interview',
		title : '面试记录',
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		param : {
			recuitid : $("#userid").val()
		},
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formCode',
				display : '单据编号',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_interview&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
				}
			}, {
				name : 'formDate',
				display : '单据日期',
				sortable : true
			}, {
				name : 'userName',
				display : '姓名',
				sortable : true
			}, {
				name : 'sexy',
				display : '性别',
				sortable : true,
				width : 70
			}, {
				name : 'positionsName',
				display : '应聘岗位',
				sortable : true
			}, {
				name : 'deptState',
				display : '部门确认状态',
				sortable : true,
				process : function(v){
					if(v==1)
						return "已确认";
					else
					    return "未确认";
				}
			}, {
				name : 'hrState',
				display : '人力资源确认状态',
				sortable : true,
				process : function(v){
					if(v==1)
						return "已确认";
					else
					    return "未确认";
				}
			}, {
				name : 'stateC',
				display : '状态'
			}],
		toViewConfig : {
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showOpenWin("?model=hr_recruitment_interview&action=recuitview&id=" + + rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		buttonsEx : buttonsArr,
		searchitems : [{
			display : '姓名',
			name : 'userNameSearch'
		}, {
			display : '用人部门',
			name : 'deptNamSearche'
		}]
	});
});