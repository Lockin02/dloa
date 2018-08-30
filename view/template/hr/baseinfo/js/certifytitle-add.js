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
		alertText : "* 该号码已存在",
		alertTextOk : "* 该号码可用"
	});
   /*
	validate({
				"orderNum" : {
					required : true,
					custom : 'onlyNumber'
				}
			});
   */  })