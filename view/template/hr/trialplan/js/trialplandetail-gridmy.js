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
		action : 'myJson',
		title : '我的试用培训',
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
				name : 'managerName',
				display : '任务负责人',
				sortable : true,
				width : 90
			}, {
				name : 'managerId',
				display : '任务负责人id',
				sortable : true,
				hide : true
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
				width : 130,
				hide : true
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

		menusEx : [{
			name : 'edit',
			text : "启动任务",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.beforeName != ""){
					$.ajax({
					    type: "POST",
					    url: "?model=hr_trialplan_trialplandetail&action=isComplate",
					    data: {"taskName" : row.beforeName,"planId" : row.planId},
					    async: false,
					    success: function(data){
					   		if(data == '1'){
								if(confirm('确定要完成这个任务吗？')){
									$.ajax({
									    type: "POST",
									    url: "?model=hr_trialplan_trialplandetail&action=begin",
									    data: {"id" : row.id },
									    async: false,
									    success: function(data){
									   		if(data == '1'){
												alert('操作成功');
												show_page();
									   	    }else{
												alert('操作失败');
									   	    }
										}
									});
								}
					   	    }else{
								alert('前置任务未完成，不能启动此任务');
					   	    }
						}
					});
				}else{
					if(confirm('确定要启动这个任务吗？')){
						$.ajax({
						    type: "POST",
						    url: "?model=hr_trialplan_trialplandetail&action=begin",
						    data: {"id" : row.id },
						    async: false,
						    success: function(data){
						   		if(data != ""){
									alert('任务成功');
									show_page();
						   	    }else{
									alert('任务失败');
						   	    }
							}
						});
					}
				}
			}
		},{
			name : 'edit',
			text : "提交评分",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '1' && row.closeType == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=hr_trialplan_trialplandetail&action=toHandUp&id=" + row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			name : 'edit',
			text : "完成任务",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '1' && row.closeType == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('确定要完成这个任务吗？')){
					$.ajax({
					    type: "POST",
					    url: "?model=hr_trialplan_trialplandetail&action=complate",
					    data: {"id" : row.id },
					    async: false,
					    success: function(data){
					   		if(data != ""){
								alert('操作成功');
								show_page();
					   	    }else{
								alert('操作失败');
					   	    }
						}
					});
				}
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		comboEx : [{
				text : '计划名称',
				key : 'planId',
				value : defaultSet,
				data : planArr
			}
		],
		searchitems : [{
			display : "任务名称",
			name : 'taskNameSearch'
		}],
		sortorder : 'ASC'
	});
});