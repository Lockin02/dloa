$(document).ready(function() {
	$("#trialplantemdetail").yxeditgrid({
		objName : 'trialplantem[trialplantemdetail]',
		tableClass : 'form_in_table',
		title : '��ϸ����',
		event : {
			'removeRow' : function(){
				countAll();
			}
		},
		colModel : [{
			display : '<span class="blue">��������</span>',
			name : 'taskType',
			type : 'select',
			width : 100,
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
			html : "<a href='javascript:void(0)'>���ù���</a>"
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
//		}, {
//			display : 'ǰ������',
//			name : 'beforeName',
//			type : 'select',
//			options : [{
//				name : '--ѡ��ǰ������--',
//				value : ''
//			}]
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