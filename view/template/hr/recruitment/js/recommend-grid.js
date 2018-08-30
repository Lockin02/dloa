var show_page = function(page) {
	$("#recommendGrid").yxgrid("reload");
};

$(function() {
	$("#recommendGrid").yxgrid({
		model : 'hr_recruitment_recommend',
		title : '内部推荐',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		isOpButton : false,
		bodyAlign:'center',
		param : {
			groupBy : 'c.id',
			stateArr : '1,2,3,4,5,6,8,9'
		},

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_recommend&action=toTabPage&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700\",1)'>"
						+ v + "</a>";
			}
		},{
			name : 'formDate',
			display : '单据日期',
			sortable : true,
			width : 80
		},{
			name : 'isRecommendName',
			display : '被荐人',
			sortable : true,
			width : 60
		},{
			name : 'positionName',
			display : '推荐职位',
			sortable : true
		},{
			name : 'recommendName',
			display : '推荐人',
			sortable : true,
			width : 60
		},{// 状态转到后台处理
			name : 'stateC',
			display : '状态',
			width : 60
		},{
			name : 'hrJobName',
			display : '录用职位',
			sortable : true
		},{
			name : 'becomeDate',
			display : '预计转正时间',
			sortable : true,
			width : 80
		},{
			name : 'realBecomeDate',
			display : '实际转正时间',
			sortable : true,
			width : 80
		},{
			name : 'quitDate',
			display : '离职时间',
			sortable : true,
			width : 80
		},{
			name : 'recruitManName',
			display : '主负责人',
			sortable : true,
			width : 60
		},{
			name : 'assistManName',
			display : '协助人',
			sortable : true,
			width : 150
		},{
			name : 'isBonus',
			display : '是否发放奖金',
			sortable : true,
			process : function(v) {
				if (v == 1) {
					return "是";
				} else {
					return "否";
				}
			},
			width : 80
		},{
			name : 'bonus',
			display : '奖金额',
			sortable : true,
			width : 80
		},{
			name : 'bonusProprotion',
			display : '已发比例',
			sortable : true,
			width : 60
		},{
			name : 'recommendReason',
			display : '推荐评价',
			width : 300,
			sortable : true,
			align : 'left'
		},{
			name : 'closeRemark',
			display : '打回理由',
			width : 300,
			sortable : true,
			align : 'left'
		}],

		lockCol:['formCode','formDate','isRecommendName'],//锁定的列名

		comboEx : [{
			text : '状态',
			key : 'state',
			data : [{
				text : '未审核',
				value : '1'
			},{
				text : '已分配',
				value : '2'
			},{
				text : '不通过',
				value : '3'
			},{
				text : '面试中',
				value : '4'
			},{
				text : '待入职',
				value : '8'
			},{
				text : '已入职',
				value : '5'
			},{
				text : '放弃入职',
				value : '9'
			},{
				text : '关闭',
				value : '6'
			}]
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=hr_recruitment_recommend&action=toTabPage&id=" + get[p.keyField]+"&stateC="+get.stateC,'1');
				}
			}
		},

		//扩展菜单
		buttonsEx : [{
			name : 'excelOut',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=hr_recruitment_recommend&action=toExcelOut"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
			}
		}],

		//右键菜单
		menusEx : [{
			text : '分配负责人',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin("?model=hr_recruitment_recommend&action=toGive&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
			}
		},{
			text : '修改负责人',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == 2) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin("?model=hr_recruitment_recommend&action=toChangeHead&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
			}
		},{
			text : '反馈推荐人',
			icon : 'add',
			// showMenuFn : function(row) {
			// 	if (row.state == 1 || row.state == 2) {
			// 		return true;
			// 	} else {
			// 		return false;
			// 	}
			// },
			action : function(row) {
				showThickboxWin("?model=hr_recruitment_recommend&action=toCheck&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
			}
		},{
			text : '转发邮件',
			icon : 'add',
			action : function(row) {
				showThickboxWin("?model=hr_recruitment_recommend&action=toForwardMail&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
			}
		}],

		searchitems : [{
			display : "单据编号",
			name : 'formCode'
		},{
			display : "单据日期",
			name : 'formDate'
		},{
			display : "被荐人",
			name : 'isRecommendName'
		},{
			display : "推荐职位",
			name : 'positionName'
		},{
			display : "推荐人",
			name : 'recommendName'
		},{
			display : "主负责人",
			name : 'recruitManName'
		},{
			display : "协助人",
			name : 'assistManName'
		},{
			display : "推荐评价",
			name : 'recommendReason'
		},{
			display : "打回理由",
			name : 'closeRemark'
		}]
	});
});