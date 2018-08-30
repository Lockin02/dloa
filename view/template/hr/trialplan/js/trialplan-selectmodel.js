//初始化数组
var beforeTaskArr = [];

$(document).ready(function() {

	//渲染模板选择
	$("#planName").yxcombogrid_trialplantem({
		hiddenId :  'planId',
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#trialplantemdetail").yxeditgrid('remove');
					initTemplate(data.id);
					$("#scoreAll").val(data.scoreAll);
					$("#baseScore").val(data.baseScore);
					beforeTaskArr = [];
				}
			}
		}
	});

	validate({
		"planName" : {
			required : true
		}
	});
});

//模板内容渲染
function initTemplate(planId){
	$("#trialplantemdetail").yxeditgrid({
		url : '?model=hr_baseinfo_trialplantemdetail&action=listJson',
		objName : 'trialplan[trialpalndetail]',
		tableClass : 'form_in_table',
		param : {
			'planId' : planId
		},
		isAdd : false,
		title : '详细任务',
		event : {
			'removeRow' : function(){
				countAll();
			},
			'reloadData' : function(){
				initBeforeTask();
			}
		},
		colModel : [{
			display : '任务类型',
			name : 'taskTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '任务类型',
			type : 'hidden',
			name : 'taskType'
		}, {
			display : '任务名称',
			name : 'taskName',
			tclass : 'readOnlyTxtMiddle',
			readonly : 'readonly'
		}, {
			display : '任务id',
			name : 'taskId',
			type : 'hidden'
		}, {
			display : '任务描述',
			name : 'description',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : '任务负责人',
			name : 'managerName',
			tclass : 'txtmiddle',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxselect_user({
					hiddenId : 'trialplantemdetail_cmp_managerId' + rowNum,
					formCode : 'trialplan'
				});
			}
		}, {
			display : '任务负责人id',
			name : 'managerId',
			type : 'hidden'
		}, {
			display : '任务积分',
			name : 'taskScore',
			tclass : 'readOnlyTxtShort',
			readonly : 'readonly'
		}, {
			display : '是否有积分规则',
			name : 'isRule',
			type : 'hidden'
		}, {
			display : '积分规则',
			name : 'setRule',
			type : 'statictext',
			event : {
				'click' : function(e) {
					var rowNum = $(this).data("rowNum");
					// 获取isRule
					var isRuleObj = $("#trialplantemdetail_cmp_isRule" + rowNum);
					// 获取任务名称
					var taskName = $("#trialplantemdetail_cmp_taskName" + rowNum).val();

					if(isRuleObj.val() != '' && isRuleObj.val() != '0'){
						// 弹窗
						url = "?model=hr_baseinfo_trialplantemdetailex&action=toViewRule&id=" + isRuleObj.val()
							+ "&trialplantemdetail_cmp_isRule"
							+ rowNum
							+ "&taskName="
							+ taskName
						;
						var returnValue = showModalDialog(url, '',"dialogWidth:600px;dialogHeight:300px;");
					}
				}
			},
			process : function(html,rowDate){
				if(rowDate.isRule == "0" || rowDate.isRule == ""){
					return '没有配置规则';
				}else{
					return html;
				}
			},
			html : "<a href='javascript:void(0)'>查看规则</a>",
			width : '10%'
		}, {
			display : '<span class="blue">任务性质</span>',
			name : 'isNeed',
			width : 80,
			type : 'select',
			options : [{
				name : '必须',
				value : 1
			},{
				name : '可选',
				value : 0
			}]
		}, {
			display : '<span class="blue">完成方式</span>',
			name : 'closeType',
			width : 80,
			type : 'select',
			options : [{
				name : '审核',
				value : 0
			},{
				name : '立即',
				value : 1
			}]
		}, {
			display : '前置任务',
			name : 'beforeName',
			type : 'select',
			options : [{
				name : '',
				value : ''
			}],
			event : {
				'change' : function(e){
					var rowNum = $(this).data("rowNum");
					// 获取前置任务名称
					var beforeName = $("#trialplantemdetail_cmp_beforeName" + rowNum).val();
					// 获取任务名称
					var taskName = $("#trialplantemdetail_cmp_taskName" + rowNum).val();
					if(beforeName == taskName){
						alert('不能以本任务作为前置任务！');
						$("#trialplantemdetail_cmp_beforeName" + rowNum).val('');
					}
				}
			}
		}, {
			display : '前置任务',
			name : 'beforeTaskName',
			type : 'hidden'
		}, {
			display : '前置任务id',
			name : 'beforeId',
			type : 'hidden'
		}]
	})
}

//初始话前置任务
function initBeforeTask(){
	//缓存表格对象
	var thisGrid = $("#trialplantemdetail");
	var optionStr,beforeTaskName;

	if(beforeTaskArr.length == 0){
		//构建任务名称数组
		var colObj = thisGrid.yxeditgrid("getCmpByCol", "taskName");
		colObj.each(function(i,n) {
			beforeTaskArr.push(this.value);
		});
	}
//	$.showDump(beforeTaskArr);
	//初始化前置任务下拉
	var colObj = thisGrid.yxeditgrid("getCmpByCol", "beforeName");
	colObj.each(function(i,n) {
		//获取上次选中的默认任务
		beforeTaskName = $("#trialplantemdetail_cmp_beforeTaskName"+ i).val();
		//构建下拉选项
		optionStr = initSelect(beforeTaskArr,beforeTaskName);
		$(this).append(optionStr);
	});
}

//数组初始化方法
function initSelect(optionArr,thisVal){
	// 字符串
	var str = "";
	if(thisVal){
		for(var i =0;i<optionArr.length;i++){
			if(thisVal == optionArr[i]){
				str += "<option selected>" + optionArr[i] + "</option>";
			}else{
				str += "<option>" + optionArr[i] + "</option>";
			}
		}
	}else{
		for(var i =0;i<optionArr.length;i++){
			str += "<option>" + optionArr[i] + "</option>";
		}
	}
	return str;
}

//计算方法
function countAll(){
	//从表对象
	var thisGrid = $("#trialplantemdetail");

	//计算任务比例部分
//	var weightsAll = 0;
//	var cmps = thisGrid.yxeditgrid("getCmpByCol", "weights");
//	cmps.each(function(i) {
//		if(!thisGrid.yxeditgrid("isRowDel", i)){
//			weightsAll = accAdd(weightsAll, $(this).val(), 2);
//		}
//	});
//
//	$("#weightsAll").val(weightsAll);

	//计算任务积分
	var scoreAll = 0;
	var cmps = thisGrid.yxeditgrid("getCmpByCol", "taskScore");
	cmps.each(function(i) {
		if(!thisGrid.yxeditgrid("isRowDel", i)){
			scoreAll = accAdd(scoreAll, $(this).val(), 2);
		}
	});

	$("#scoreAll").val(scoreAll);
}