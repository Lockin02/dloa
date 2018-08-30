$(document).ready(function() {
	$("#trialplantemdetail").yxeditgrid({
		url : '?model=hr_baseinfo_trialplantemdetail&action=listJson',
		tableClass : 'form_in_table',
		type : 'view',
		param : {
			'planId' : $("#id").val()
		},
		title : '详细任务',
		colModel : [{
			display : '任务类型',
			name : 'taskTypeName',
			tclass : 'txtmiddle',
			width : '10%'
		}, {
			display : '任务名称',
			name : 'taskName',
			width : '15%'
		}, {
			display : '任务描述',
			name : 'description',
			width : '20%'
		}, {
			display : '任务负责人',
			name : 'managerName',
			width : '10%'
		}, {
			display : '任务积分',
			name : 'taskScore',
			width : '10%'
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
			display : '任务性质',
			name : 'isNeed',
			process : function(v){
				if(v == '1'){
					return '必须';
				}else{
					return '可选';
				}
			},
			width : '10%'
		}, {
			display : '前置任务',
			name : 'beforeName',
			tclass : 'txt',
			readonly : true
		}, {
			display : '前置任务id',
			name : 'beforeId',
			type : 'hidden'
		}]
	})
})