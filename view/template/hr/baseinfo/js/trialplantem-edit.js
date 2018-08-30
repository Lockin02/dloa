//初始化数组
var beforeTaskArr = [];

$(document).ready(function() {
	$("#trialplantemdetail").yxeditgrid({
		url : '?model=hr_baseinfo_trialplantemdetail&action=listJson',
		objName : 'trialplantem[trialplantemdetail]',
		tableClass : 'form_in_table',
		param : {
			'planId' : $("#id").val()
		},
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
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'planId',
			name : 'planId',
			type : 'hidden',
			value : $("#id").val()
		}, {
			display : '<span class="blue">任务类型</span>',
			name : 'taskType',
			width : 100,
			type : 'select',
			datacode : 'HRSYRW',
			validation : {
				required : true
			}
		}, {
			display : '<span class="blue">任务名称</span>',
			name : 'taskName',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '<span class="blue">任务描述</span>',
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
			display : '<span class="blue">任务积分</span>',
			name : 'taskScore',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			event : {
				'blur' : function(){
					countAll();
				}
			}
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

					// 弹窗
					url = "?model=hr_baseinfo_trialplantemdetailex&action=toSetRule&id=" + isRuleObj.val()
						+ "&trialplantemdetail_cmp_isRule"
						+ rowNum
						+ "&taskName="
						+ taskName
					;

					//为了解决GOOGLE 浏览器的BUG，所以要使用以下代码
					var prevReturnValue = window.returnValue; // Save the current returnValue
					window.returnValue = undefined;
					var dlgReturnValue = window.showModalDialog(url, '',"dialogWidth:600px;dialogHeight:300px;");
					if (dlgReturnValue == undefined) // We don't know here if undefined is the real result...
					{
					    // So we take no chance, in case this is the Google Chrome bug
					    dlgReturnValue = window.returnValue;
					}
					window.returnValue = prevReturnValue; // Restore the original returnValue//赋值

					if(dlgReturnValue){
						isRuleObj.val(dlgReturnValue);
					}
				}
			},
			process : function(html,rowDate){
				if(rowDate){
					if(rowDate.isRule == "0" || rowDate.isRule == ""){
						return "<a href='javascript:void(0)'>设定规则</a>";
					}else{
						return "<a href='javascript:void(0)'>修改规则</a>";
					}
				}else{
					return "<a href='javascript:void(0)'>设定规则</a>";
				}
			}
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


	/**
	 * 验证信息
	 */
	validate({
		"planName" : {
			required : true
		},
		"description" : {
			required : true
		}
	});
})

//初始话前置任务
function initBeforeTask(){
	//缓存表格对象
	var thisGrid = $("#trialplantemdetail");
	var optionStr,beforeTaskName;

	//构建任务名称数组
	var colObj = thisGrid.yxeditgrid("getCmpByCol", "taskName");
	colObj.each(function(i,n) {
		beforeTaskArr.push(this.value);
	});

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