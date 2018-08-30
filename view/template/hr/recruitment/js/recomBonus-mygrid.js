var show_page = function(page) {
	$("#recomBonusGrid").yxgrid("reload");
};

$(function() {
	$("#recomBonusGrid").yxgrid({
		model : 'hr_recruitment_recomBonus',
		title : '内部推荐奖金',
		isAddAction : false,
		isOpButton:false,
		bodyAlign:'center',
		param : {
			formManId : $("#userid").val()
		},

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '单据编号',
			width:120,
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_recruitment_recomBonus&action=toView&id=" + row.id+"&skey="+row['skey_']
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800" +"\")'>" + v + "</a>";
			}
		},{
			name : 'formDate',
			display : '单据日期',
			width:70,
			sortable : true
		},{
			name : 'isRecommendName',
			display : '被荐人',
			width:70,
			sortable : true
		},{
			name : 'developPositionName',
			display : '职位小类',
			width:70,
			sortable : true
		},{
			name : 'jobName',
			display : '职位类型',
			sortable : true
		},{
			name : 'entryDate',
			display : '入职日期',
			width:70,
			sortable : true
		},{
			name : 'becomeDate',
			display : '转正日期',
			width:70,
			sortable : true
		},{
			name : 'beBecomDate',
			display : '预计转正日期',
			width:80,
			sortable : true
		},{
			name : 'recommendName',
			display : '推荐人',
			width:70,
			sortable : true
		},{
			name : 'stateC',
			display : '状态',
			width:70,
			sortable : true
		},{
			name : 'ExaStatus',
			display : '审批状态',
			width:70,
			sortable : true
		},{
			name : 'firstGrantDate',
			display : '第一次待发时间',
			sortable : true
		},{
			name : 'firstGrantBonus',
			display : '第一次待发奖金',
			sortable : true
		},{
			name : 'secondGrantDate',
			display : '第二次待发时间',
			sortable : true
		},{
			name : 'secondGrantBonus',
			display : '第二次待发奖金',
			sortable : true
		},{
			name : 'remark',
			display : '备注',
			sortable : true
		}],

		lockCol:['formCode','formDate','isRecommendName'],//锁定的列名

		comboEx : [{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '部门审批',
				value : '部门审批'
			},{
				text : '打回',
				value : '打回'
			},{
				text : '未审核',
				value : '未审批'
			},{
				text : '完成',
				value : '完成'
			},{
				text : 'ba',
				value : '完成'
			}]
		}],

		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if(row.ExaStatus == "保存" && row.stateC == '保存') {
					return true;
				} else {
					return false;
				}
			}
		},
		toViewConfig : {
			action : 'toView'
		},
		toDelConfig : {
			showMenuFn : function(row) {
				if(row.ExaStatus == "保存" && row.stateC == '保存') {
					return true;
				} else {
					return false;
				}
			}
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
			display : "职位小类",
			name : 'developPositionName'
		},{
			display : "职位类型",
			name : 'jobName'
		},{
			display : "入职日期",
			name : 'entryDate'
		},{
			display : "转正日期",
			name : 'becomeDate'
		},{
			display : "预计转正日期",
			name : 'beBecomDate'
		},{
			display : "推荐人",
			name : 'recommendName'
		},{
			display : "备注",
			name : 'remark'
		}],

		menusEx : [{
			text:'提交',
			icon:'edit',
			showMenuFn:function(row){
				if(row.stateC == "保存" && row.ExaStatus == '保存') {
					return true;
				}
				return false;
			},
			action:function(row,rows,grid) {
				if(row) {
					showThickboxWin("?model=hr_recruitment_recomBonus&action=submit&id=" + row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}]
	});
});