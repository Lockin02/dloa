//��ʼ������
var beforeTaskArr = [];

$(document).ready(function() {
	$("#trialplantemdetail").yxeditgrid({
		url : '?model=hr_baseinfo_trialplantemdetail&action=listJson',
		objName : 'trialplantem[trialplantemdetail]',
		tableClass : 'form_in_table',
		param : {
			'planId' : $("#id").val()
		},
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
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'planId',
			name : 'planId',
			type : 'hidden',
			value : $("#id").val()
		}, {
			display : '<span class="blue">��������</span>',
			name : 'taskType',
			width : 100,
			type : 'select',
			datacode : 'HRSYRW',
			validation : {
				required : true
			}
		}, {
			display : '<span class="blue">��������</span>',
			name : 'taskName',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '<span class="blue">��������</span>',
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
			display : '<span class="blue">�������</span>',
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

					// ����
					url = "?model=hr_baseinfo_trialplantemdetailex&action=toSetRule&id=" + isRuleObj.val()
						+ "&trialplantemdetail_cmp_isRule"
						+ rowNum
						+ "&taskName="
						+ taskName
					;

					//Ϊ�˽��GOOGLE �������BUG������Ҫʹ�����´���
					var prevReturnValue = window.returnValue; // Save the current returnValue
					window.returnValue = undefined;
					var dlgReturnValue = window.showModalDialog(url, '',"dialogWidth:600px;dialogHeight:300px;");
					if (dlgReturnValue == undefined) // We don't know here if undefined is the real result...
					{
					    // So we take no chance, in case this is the Google Chrome bug
					    dlgReturnValue = window.returnValue;
					}
					window.returnValue = prevReturnValue; // Restore the original returnValue//��ֵ

					if(dlgReturnValue){
						isRuleObj.val(dlgReturnValue);
					}
				}
			},
			process : function(html,rowDate){
				if(rowDate){
					if(rowDate.isRule == "0" || rowDate.isRule == ""){
						return "<a href='javascript:void(0)'>�趨����</a>";
					}else{
						return "<a href='javascript:void(0)'>�޸Ĺ���</a>";
					}
				}else{
					return "<a href='javascript:void(0)'>�趨����</a>";
				}
			}
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


	/**
	 * ��֤��Ϣ
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

//��ʼ��ǰ������
function initBeforeTask(){
	//���������
	var thisGrid = $("#trialplantemdetail");
	var optionStr,beforeTaskName;

	//����������������
	var colObj = thisGrid.yxeditgrid("getCmpByCol", "taskName");
	colObj.each(function(i,n) {
		beforeTaskArr.push(this.value);
	});

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