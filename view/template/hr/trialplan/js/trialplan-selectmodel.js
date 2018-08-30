//��ʼ������
var beforeTaskArr = [];

$(document).ready(function() {

	//��Ⱦģ��ѡ��
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

//ģ��������Ⱦ
function initTemplate(planId){
	$("#trialplantemdetail").yxeditgrid({
		url : '?model=hr_baseinfo_trialplantemdetail&action=listJson',
		objName : 'trialplan[trialpalndetail]',
		tableClass : 'form_in_table',
		param : {
			'planId' : planId
		},
		isAdd : false,
		title : '��ϸ����',
		event : {
			'removeRow' : function(){
				countAll();
			},
			'reloadData' : function(){
				initBeforeTask();
			}
		},
		colModel : [{
			display : '��������',
			name : 'taskTypeName',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '��������',
			type : 'hidden',
			name : 'taskType'
		}, {
			display : '��������',
			name : 'taskName',
			tclass : 'readOnlyTxtMiddle',
			readonly : 'readonly'
		}, {
			display : '����id',
			name : 'taskId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'description',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : '��������',
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
			display : '��������id',
			name : 'managerId',
			type : 'hidden'
		}, {
			display : '�������',
			name : 'taskScore',
			tclass : 'readOnlyTxtShort',
			readonly : 'readonly'
		}, {
			display : '�Ƿ��л��ֹ���',
			name : 'isRule',
			type : 'hidden'
		}, {
			display : '���ֹ���',
			name : 'setRule',
			type : 'statictext',
			event : {
				'click' : function(e) {
					var rowNum = $(this).data("rowNum");
					// ��ȡisRule
					var isRuleObj = $("#trialplantemdetail_cmp_isRule" + rowNum);
					// ��ȡ��������
					var taskName = $("#trialplantemdetail_cmp_taskName" + rowNum).val();

					if(isRuleObj.val() != '' && isRuleObj.val() != '0'){
						// ����
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
					return 'û�����ù���';
				}else{
					return html;
				}
			},
			html : "<a href='javascript:void(0)'>�鿴����</a>",
			width : '10%'
		}, {
			display : '<span class="blue">��������</span>',
			name : 'isNeed',
			width : 80,
			type : 'select',
			options : [{
				name : '����',
				value : 1
			},{
				name : '��ѡ',
				value : 0
			}]
		}, {
			display : '<span class="blue">��ɷ�ʽ</span>',
			name : 'closeType',
			width : 80,
			type : 'select',
			options : [{
				name : '���',
				value : 0
			},{
				name : '����',
				value : 1
			}]
		}, {
			display : 'ǰ������',
			name : 'beforeName',
			type : 'select',
			options : [{
				name : '',
				value : ''
			}],
			event : {
				'change' : function(e){
					var rowNum = $(this).data("rowNum");
					// ��ȡǰ����������
					var beforeName = $("#trialplantemdetail_cmp_beforeName" + rowNum).val();
					// ��ȡ��������
					var taskName = $("#trialplantemdetail_cmp_taskName" + rowNum).val();
					if(beforeName == taskName){
						alert('�����Ա�������Ϊǰ������');
						$("#trialplantemdetail_cmp_beforeName" + rowNum).val('');
					}
				}
			}
		}, {
			display : 'ǰ������',
			name : 'beforeTaskName',
			type : 'hidden'
		}, {
			display : 'ǰ������id',
			name : 'beforeId',
			type : 'hidden'
		}]
	})
}

//��ʼ��ǰ������
function initBeforeTask(){
	//���������
	var thisGrid = $("#trialplantemdetail");
	var optionStr,beforeTaskName;

	if(beforeTaskArr.length == 0){
		//����������������
		var colObj = thisGrid.yxeditgrid("getCmpByCol", "taskName");
		colObj.each(function(i,n) {
			beforeTaskArr.push(this.value);
		});
	}
//	$.showDump(beforeTaskArr);
	//��ʼ��ǰ����������
	var colObj = thisGrid.yxeditgrid("getCmpByCol", "beforeName");
	colObj.each(function(i,n) {
		//��ȡ�ϴ�ѡ�е�Ĭ������
		beforeTaskName = $("#trialplantemdetail_cmp_beforeTaskName"+ i).val();
		//��������ѡ��
		optionStr = initSelect(beforeTaskArr,beforeTaskName);
		$(this).append(optionStr);
	});
}

//�����ʼ������
function initSelect(optionArr,thisVal){
	// �ַ���
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

//���㷽��
function countAll(){
	//�ӱ����
	var thisGrid = $("#trialplantemdetail");

	//���������������
//	var weightsAll = 0;
//	var cmps = thisGrid.yxeditgrid("getCmpByCol", "weights");
//	cmps.each(function(i) {
//		if(!thisGrid.yxeditgrid("isRowDel", i)){
//			weightsAll = accAdd(weightsAll, $(this).val(), 2);
//		}
//	});
//
//	$("#weightsAll").val(weightsAll);

	//�����������
	var scoreAll = 0;
	var cmps = thisGrid.yxeditgrid("getCmpByCol", "taskScore");
	cmps.each(function(i) {
		if(!thisGrid.yxeditgrid("isRowDel", i)){
			scoreAll = accAdd(scoreAll, $(this).val(), 2);
		}
	});

	$("#scoreAll").val(scoreAll);
}