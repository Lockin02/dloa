var show_page = function(page) {
	$("#trialplandetailGrid").yxgrid("reload");
};
$(function() {
	$("#trialplandetailGrid").yxgrid({
		model : 'hr_trialplan_trialplandetail',
		action : 'myManageJson',
		title : '我的试用评价',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'taskName',
				display : '任务名称',
				sortable : true,
				width : 130
			}, {
				name : 'description',
				display : '任务描述',
				sortable : true,
				width : 150
			}, {
				name : 'managerName',
				display : '任务负责人',
				sortable : true,
				hide : true
			}, {
				name : 'managerId',
				display : '任务负责人id',
				sortable : true,
				hide : true
			}, {
				name : 'taskScore',
				display : '任务积分',
				sortable : true,
				width : 60
			}, {
				name : 'baseScore',
				display : '所得积分',
				sortable : true,
				width : 60
			}, {
				name : 'memberName',
				display : '任务执行人',
				sortable : true,
				width : 80
			}, {
				name : 'memberId',
				display : '任务执行人id',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				process : function(v){
					switch(v){
						case '0' : return '未启动';break;
						case '1' : return '执行中';break;
						case '2' : return '待审核';break;
						case '3' : return '已完成';break;
						default : return v;
					}
				},
				width : 60
			}, {
				name : 'handupDate',
				display : '提交日期',
				sortable : true,
				width : 80
			}, {
				name : 'score',
				display : '评分',
				sortable : true,
				width : 60
			}, {
				name : 'scoreDate',
				display : '评分日期',
				sortable : true,
				width : 80
			}, {
				name : 'scoreDesc',
				display : '评分说明',
				sortable : true,
				width : 130
			}, {
				name : 'beforeId',
				display : '前置任务id',
				sortable : true,
				hide : true
			}, {
				name : 'beforeName',
				display : '前置任务名称',
				sortable : true,
				width : 130,
				hide : true
			}],

		menusEx : [{
			name : 'edit',
			text : "审核",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '2') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=hr_trialplan_trialplandetail&action=toScore&id=" + row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
        //过滤数据
		comboEx:[{
		     text:'状态',
		     key:'status',
		     value : 2,
		     data : [{
					text : '未启动',
					value : '0'
				}, {
					text : '执行中',
					value : '1'
				}, {
					text : '待审核',
					value : '2'
				}, {
					text : '已审核',
					value : '3'
				}
			]
		}],
		searchitems : [{
			display : "任务名称",
			name : 'taskNameSearch'
		}],
		sortorder : 'ASC'
	});
});