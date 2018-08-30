$(document).ready(function() {
	$("#scoredetail").yxeditgrid({
		objName : 'score[detailvals]',
		url : '?model=hr_certifyapply_scoredetail&action=listJson',
		param : {
			"scoreId" : $("#id").val()
		},
		tableClass : 'form_in_table',
		title : '评分明细',
		isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '模版Id',
			name : 'modeId',
			type : 'hidden'
		}, {
			display : '行为模块id',
			name : 'moduleId',
			type : 'hidden'
		}, {
			display : '行为模块',
			name : 'moduleName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '行为要项id',
			name : 'detailId',
			type : 'hidden'
		}, {
			display : '行为要项',
			name : 'detailName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '权重(%)',
			name : 'weights',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '评分',
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

//计算权重分数
function countAll(){
	//总分
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