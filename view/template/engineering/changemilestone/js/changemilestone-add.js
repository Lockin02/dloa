$(document).ready(function(){
	$("#preMilestoneName").yxcombogrid_milestonechange({
		hiddenId : 'changePreId',
		gridOptions : {
			param  : {
				'projectId' : $("#projectId").val(),
				'changeId' : $("#changeId").val()
			},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#changePreId").val(data.milestoneId)
				}
			}
		}
	});
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"milestoneName" : {
			required : true,
			length : [0,100]
		},
		"planBeginDate" : {
			required : true,
			custom : ['date']
		},
		"planEndDate" : {
			required : true,
			custom : ['date']
		}
	});
})

//��ʼ�������ɲ���֤
function timeCheck($t){
	var s = plusDateInfo('planBeginDate','planEndDate');
	if(s <= 0) {
		alert("�ƻ���ʼʱ�䲻�ܱȽӻ����ʱ����");
		$t.value = "";
		return false;
	}
}

//ʵ�ʿ�ʼ��ʵ�ʽ����Ƚ�
function actTimeCheck($t){
	var s = plusDateInfo('actBeginDate','actEndDate');
	if(s <= 0) {
		alert("ʵ�ʿ�ʼ���ڲ��ܱ�ʵ�ʽ���������");
		$t.value = "";
		return false;
	}
}