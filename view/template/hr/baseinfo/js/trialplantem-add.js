$(document).ready(function() {
	$("#trialplantemdetail").yxeditgrid({
		objName : 'trialplantem[trialplantemdetail]',
		tableClass : 'form_in_table',
		title : '详细任务',
		event : {
			'removeRow' : function(){
				countAll();
			}
		},
		colModel : [{
			display : '<span class="blue">任务类型</span>',
			name : 'taskType',
			type : 'select',
			width : 100,
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
			html : "<a href='javascript:void(0)'>设置规则</a>"
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
//		}, {
//			display : '前置任务',
//			name : 'beforeName',
//			type : 'select',
//			options : [{
//				name : '--选择前置任务--',
//				value : ''
//			}]
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