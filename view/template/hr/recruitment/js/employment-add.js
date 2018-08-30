$(document).ready(function() {
	healthStateCodeArr = getData('HRJKZK');
	addDataToSelect(healthStateCodeArr, 'healthStateCode');
	politicsStatusCodeArr = getData('HRZZMM');
	addDataToSelect(politicsStatusCodeArr, 'politicsStatusCode');
	highEducationArr = getData('HRJYXL');
	addDataToSelect(highEducationArr, 'highEducation');
	englishSkillArr = getData('HRYYDJ');
	addDataToSelect(englishSkillArr, 'englishSkill');
});

function checkIDCard(obj) {
	str = $(obj).val();
	if(isIdCardNo(str)){
		 $.ajax({
	         type : "POST",
	         url : "?model=hr_recruitment_employment&action=isSumbitForm",
	         data : {
	         	identityCard : str
	         },
	         success:function(msg) {
	            if(msg == 0) { //判断是否有职位申请
	                alert('已提交职位申请，请勿重复提交');
					$(obj).val('');
	            }
	         }
	     });
	} else {
		$(obj).val('');
	}
}