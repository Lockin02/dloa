var show_page = function(page) {
	$("#recommendGrid").yxgrid("reload");
};

$(function() {
	$("#recommendGrid").yxgrid({
		model : 'hr_recruitment_recommend',
		title : '内部推荐',
		isDelAction:false,
		isAddAction:false,
		isEditAction:false,
		showcheckbox : false,
		isOpButton:false,
		bodyAlign:'center',
		action : 'myHelpPageJson',
		param:{
			stateArr:'2,3,4,5,6'
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
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_recommend&action=toTabPage&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700\",1)'>" + v + "</a>";
			}
		},{
			name : 'formDate',
			display : '单据日期',
			sortable : true,
			width : 60
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
			name : 'recruitManName',
			display : '主负责人',
			sortable : true,
			width : 60
		},{
			name : 'recommendName',
			display : '推荐人',
			sortable : true,
			width : 60
		},{//状态转到后台处理
			name : 'stateC',
			display : '状态',
			width : 60
		},{
			name : 'isBonus',
			display : '是否发放奖金',
			sortable : true,
			process : function(v){
				if(v == 1) {
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
			width : 60
		},{
			name : 'bonusProprotion',
			display : '已发比例',
			sortable : true,
			width : 60
		},{
			name : 'recommendReason',
			display : '推荐评价',
			width : 300,
			sortable : true
		}],

		lockCol:['formCode','formDate','isRecommendName'],//锁定的列名

		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_recruitment_NULL&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'XXX',
				display : '从表字段'
			}]
		},

		comboEx : [{
			text : '状态',
			key : 'state',
			data : [{
				text : '保存',
				value : '0'
			},{
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
				text : '已入职',
				value : '5'
			},{
				text : '关闭',
				value : '6'
			}]
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=hr_recruitment_recommend&action=toTabPage&id=" + get[p.keyField],'1');
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
			display : "推荐职位",
			name : 'positionName'
		},{
			display : "推荐人",
			name : 'recommendName'
		},{
			display : "主负责人",
			name : 'recruitManName'
		},{
			display : "推荐评价",
			name : 'recommendReason'
		},{
			display : "打回理由",
			name : 'closeRemark'
		}]
	});
});