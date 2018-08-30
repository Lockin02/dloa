$(document).ready(function() {
	$("#scoredetail").yxeditgrid({
		objName : 'score[detailvals]',
		url : '?model=hr_certifyapply_scoredetail&action=listJson',
		param : {
			"scoreId" : $("#id").val()
		},
		tableClass : 'form_in_table',
		title : '������ϸ',
		isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'ģ��Id',
			name : 'modeId',
			type : 'hidden'
		}, {
			display : '��Ϊģ��id',
			name : 'moduleId',
			type : 'hidden'
		}, {
			display : '��Ϊģ��',
			name : 'moduleName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '��ΪҪ��id',
			name : 'detailId',
			type : 'hidden'
		}, {
			display : '��ΪҪ��',
			name : 'detailName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : 'Ȩ��(%)',
			name : 'weights',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '����',
			name : 'score',
			event : {
				"blur" : function(){
					countAll();
				}
			},
			validation : {
				required : true
			}
		}]
	});

	validate();
})

//����Ȩ�ط���
function countAll(){
	//�ܷ�
	var allScore = 0;
	var weights = 0;
	var thisScore = 0;

	$("input[id^='scoredetail_cmp_score']").each(function(i,n){
		weights = $("#scoredetail_cmp_weights" + i).val();
		if(this.value == ""){
			thisScore = 0;
		}else{
			thisScore = this.value;
		}
		allScore = accAdd(allScore,accDiv(accMul(thisScore,weights),100,2),2);
	});

	$("#score").val(allScore);
}