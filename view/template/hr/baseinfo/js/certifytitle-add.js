$(document).ready(function() {
	baseGradeCodeArr = getData('HRRZZD');
	addDataToSelect(baseGradeCodeArr, 'baseGrade');
	baseLevelCodeArr = getData('HRRZJB');
	addDataToSelect(baseLevelCodeArr, 'baseLevel');
	careerDirectionCodeArr = getData('HRZYFZ');
	addDataToSelect(careerDirectionCodeArr, 'careerDirection');
	var url = "?model=hr_baseinfo_certifytitle&action=checkRepeat";
	$("#titleName").ajaxCheck({
		url : url,
		alertText : "* �ú����Ѵ���",
		alertTextOk : "* �ú������"
	});
   /*
	validate({
				"orderNum" : {
					required : true,
					custom : 'onlyNumber'
				}
			});
   */  })