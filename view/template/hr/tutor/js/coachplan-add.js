$(document).ready(function() {

	//��ϸ
	$("#infoATable").yxeditgrid({
		objName : 'coachplan[infoA]',
		isAddOneRow : true,
		colModel : [{
			display : 'month',
			name : 'containMonth',
			type : 'hidden',
			value : '1'
		}, {
			display : '����Ŀ��',
			name : 'fosterGoal',
			width : '50%',
			validation : {
				required : true
			}
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			width : '50%',
			validation : {
				required : true
			}
		}]
	});

	//��ϸ
	$("#infoBTable").yxeditgrid({
		objName : 'coachplan[infoB]',
		isAddOneRow : true,
		colModel : [{
			display : 'month',
			name : 'containMonth',
			type : 'hidden',
			value : '2'
		}, {
			display : '����Ŀ��',
			name : 'fosterGoal',
			width : '50%'
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			width : '50%'
		}]
	});
	//��ϸ
	$("#infoCTable").yxeditgrid({
		objName : 'coachplan[infoC]',
		isAddOneRow : true,
		colModel : [{
			display : 'month',
			name : 'containMonth',
			type : 'hidden',
			value : '3'
		}, {
			display : '����Ŀ��',
			name : 'fosterGoal',
			width : '50%'
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			width : '50%'
		}]
	});
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"goal" : {
			required : true
		}
	});
})

 	   //����
function toSubmit(){
	document.getElementById('form1').action = "?model=hr_tutor_coachplan&action=add&actType=audit";
}
//function sub() {
//	var rowNum = $("#infoATable").yxeditgrid('getCurShowRowNum');
//	alert(rowNum)
//	if (rowNum == '0') {
//		alert("����д����һ���µĸ����ƻ�");
//		return false;
//	}
//
//	$("input[type='button']").attr("disabled", true);
//	$("input[type='submit']").attr("disabled", true);
//	return false;
//
//}