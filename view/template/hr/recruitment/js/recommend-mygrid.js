var show_page = function(page) {
	$("#recommendGrid").yxgrid("reload");
};

$(function() {
	$("#recommendGrid").yxgrid({
		model : 'hr_recruitment_recommend',
		action : 'myPageJson',
		title : '内部推荐',
		isAddAction : false,
		isDelAction : false,
		isOpButton : false,
		bodyAlign:'center',

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
			width:120,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_recommend&action=toTabPage&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700\",1)'>"
					+ v + "</a>";
			}
		},{
			name : 'formDate',
			display : '单据日期',
			width:80,
			sortable : true
		},{
			name : 'isRecommendName',
			display : '被荐人',
			width:80,
			sortable : true
		},{
			name : 'positionName',
			display : '推荐职位',
			sortable : true
		},{
			name : 'recruitManName',
			display : '主负责人',
			width:80,
			sortable : true
		},{
			name : 'recommendName',
			display : '推荐人',
			width:80,
			sortable : true
		},{// 状态转到后台处理
			name : 'stateC',
			width:80,
			display : '状态'
		},{
			name : 'hrJobName',
			display : '录用职位',
			sortable : true
		},{
			name : 'isBonus',
			display : '是否发放了奖金',
			sortable : true,
			process : function(v) {
				if (v == 1) {
					return "是";
				} else {
					return "否";
				}
			}
		},{
			name : 'bonus',
			display : '奖金额',
			sortable : true
		},{
			name : 'bonusProprotion',
			display : '已发比例',
			sortable : true
		},{
			name : 'recommendReason',
			display : '推荐评价',
			width : 300,
			sortable : true
		},{
			name : 'closeRemark',
			display : '打回理由',
			width : 300,
			sortable : true
		}],

		lockCol:['formCode','formDate','isRecommendName'],//锁定的列名

		menusEx : [{
			text : '提交审核',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == 0) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
//				location = "?model=hr_recruitment_recommend&action=change&id=" + row.id + "&state=1";
				if (window.confirm(("您好，新OA已上线，跳转到新OA重新提交申请?"))) {
					location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
				}
			}
		},{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_recommend&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								g.reload();
							}
						}
					});
				}
			}
		}],

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

		buttonsEx : [{
			text : "新增",
			icon : 'add',
			action : function(row) {
				alert("您好，新OA已上线，请到新OA提交申请。谢谢！");
				return false;
				showThickboxWin("?model=hr_recruitment_recommend&action=addBefore"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
			}
		}],

		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if (row.state == 0) {
					return true;
				} else {
					return false;
				}
			},
            toEditFn : function(p, g) {
				if (window.confirm(("您好，新OA已上线，跳转到新OA重新提交申请?"))) {
					location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
				}
            }
		},
		toViewConfig : {
			action : 'toView'
		},

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