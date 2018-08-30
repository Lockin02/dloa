var show_page = function(page) {
	$("#trialplandetailGrid").yxgrid("reload");
};

//获取工作流类型数组
var planArr = [];
var defaultSet;

$(function() {
	$.ajax({
	    type: "POST",
	    url: "?model=hr_trialplan_trialplan&action=getMyPlans",
	    data: {"userAccount" : $("#userAccount").val() },
	    async: false,
	    success: function(data){
	    	if(planArr){
		   		planArr = eval( "(" + data + ")" );
				defaultSet = planArr[0].value;
	    	}
		}
	});

	$("#trialplandetailGrid").yxgrid({
		model : 'hr_trialplan_trialplandetail',
		title : '员工培训计划明细',
		param : {
			'memberId' : $("#userAccount").val()
		},
//		isAddAction : false,
//		isEditAction : false,
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
				hide : true
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
						case '4' : return '已关闭';break;
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
				width : 130
			}],
		toAddConfig : {
			plusUrl : "&planId="+ $("#planId").val() +"&userAccount=" + $("#userAccount").val()+"&userName=" + $("#userName").val(),
			formHeight : 500,
			formWidth : 900
		},
		toEditConfig : {
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		comboEx : [{
				text : '计划名称',
				key : 'planId',
				value : defaultSet,
				data : planArr
			}
		],

		//扩展右键
		menusEx : [{
			text : '关闭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if(confirm('确定要关闭这个任务吗？')){
					$.ajax({
					    type: "POST",
					    url: "?model=hr_trialplan_trialplandetail&action=close",
					    data: {"id" : row.id },
					    async: false,
					    success: function(data){
					   		if(data == "1"){
								alert('关闭成功');
								show_page();
					   	    }else{
								alert('关闭失败');
					   	    }
						}
					});
				}
			}
		}],
		searchitems : [{
			display : "任务名称",
			name : 'taskNameSearch'
		}],
		sortorder : 'ASC'
	});
});