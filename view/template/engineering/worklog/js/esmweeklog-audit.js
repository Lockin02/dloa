var scoreLevelArr;

$(function() {
	//工作日志
	$("#esmweeklogTable").yxeditgrid({
		url : '?model=engineering_worklog_esmworklog&action=listJson',
		type : 'view',
		param : {
			weekId : $("#weekId").val()
		},
		tableClass : 'form_in_table',
		colModel : [ {
				display : '执行日期',
				name : 'executionDate',
				width : 80
			}, {
				display : '所在地',
				name : 'provinceCity',
				width : 80
			}, {
				display : '工作状态',
				name : 'workStatus',
				width : 60,
				datacode : 'GXRYZT'
			}, {
				display : '项目编号',
				name : 'projectCode',
				width : 130
			},{
				display : '项目名称',
				name : 'projectName',
				width : 200
			},{
				display : '任务名称',
				name : 'activityName',
				width : 150
			},{
				display : '完成量',
				name : 'workloadDay',
				width : 70,
				process : function(v,row){
					return v + " " + row.workloadUnitName ;
				}
			}, {
				display : '费用',
				name : 'costMoney',
				width : 70,
				process : function(v,row){
					if(v * 1 == 0 || v == ''){
						return v;
					}else{
						return "<a href='javascript:void(0)' onclick='viewCost(\"" + row.id + "\",1)' title='点击查看费用'>" + moneyFormat2(v) + "</a>";
					}
				}
			}, {
				display : '工作描述',
				name : 'description'
			}
		]
	});

	//考核内容渲染
	var indexTblObj = $("#indexTbl");
	if(indexTblObj.html() == ""){
		indexTblObj.html('周报对应项目没有配置考核指标，请现在项目中进行配置');
		$("#submitBtn").attr('disabled',true);
	}

	//初始化分值缓存
	scoreLevelArr = $("input[id^='score']");

	//做一个初始化
	changeOption(0);
});

//指标修改事件
function changeOption(thisNo){
	var thisOption = $("#option"+thisNo).find("option:selected");

	//选择项赋值
	$("#optionId" + thisNo).val(thisOption.attr("optionId"));
	$("#optionName" + thisNo).val(thisOption.attr("optionName"));

	var allScore = 0;
	//总分值计算
	$("select[id^='option']").each(function(i,n){
		allScore = accAdd(allScore,this.value,0);
	});

	var thisLevel ;
	scoreLevelArr.each(function(i,n){
		if(allScore >= this.value*1){
			thisLevel = $(this).attr("levelName");
			return false;
		}
	});
	$("#rsScore").val(allScore);
	$("#rsLevel").val(thisLevel);
	$("#scoreShow").html(allScore);
}

//打回日志
function backLog(){
	if(confirm('确认打回周报吗？')){
		$.ajax({
		    type: "POST",
		    url: "?model=engineering_worklog_esmweeklog&action=backLog",
		    data: { 'id' : $("#weekId").val() },
		    async: false,
		    success: function(data){
		   		if(data == '1'){
					alert('打回成功');
					window.opener.show_page(1);
					window.close();
		   	    }else{
					alert('打回失败');
					window.opener.show_page(1);
					window.close();
		   	    }
			}
		});
	}
}

//进入查看费用页面
function viewCost(worklogId){
	var url = "?model=engineering_worklog_esmworklog&action=toView&id=" + worklogId;
	var height = 800;
	var width = 1150;
	window.open(url, "查看日志信息",
	'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
			+ width + ',height=' + height);
}