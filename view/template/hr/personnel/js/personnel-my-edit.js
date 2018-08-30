$(document).ready(function() {

		//性别
		$('select[name="personnel[sex]"] option').each(function() {
			if( $(this).val() == $("#sex").val() ){
				$(this).attr("selected","selected'");
			}
		});
		//婚姻状况
		$('select[name="personnel[maritalStatusName]"] option').each(function() {
			if( $(this).val() == $("#maritalStatusName").val() ){
				$(this).attr("selected","selected'");
			}
		});
		//生育状况
		$('select[name="personnel[birthStatus]"] option').each(function() {
			if( $(this).val() == $("#birthStatus").val() ){
				$(this).attr("selected","selected'");
			}
		});
		//户籍类型
		$('select[name="personnel[householdType]"] option').each(function() {
			if( $(this).val() == $("#householdType").val() ){
				$(this).attr("selected","selected'");
			}
		});
		//集体户口
		$('select[name="personnel[collectResidence]"] option').each(function() {
			if( $(this).val() == $("#collectResidence").val() ){
				$(this).attr("selected","selected'");
			}
		});
		//是否有过往病史
		if($("#isYes").attr("checked")){
			$("#medicalHistory").show();
		}else{
			$("#medicalHistory").hide();
		}
		validate({
					"birthdate" : {
						required : true
					},
					"city" : {
						required : true
					},
					"nation" : {
						required : true
					},
					"highEducation" : {
						required : true
					},
					"highSchool" : {
						required : true
					},
					"maritalStatusName2" : {
						required : true
					},
					"birthStatus2" : {
						required : true
					},
					"englishSkill" : {
						required : true
					},
					"archivesLocation" : {
						required : true
					},
					"city2" : {
						required : true
					}
	 		});
});
	//是否有过往病史
	function changeRadio(){
		if($("#isYes").attr("checked")){
			$("#medicalHistory").show();
		}else{
			$("#medicalHistory").hide();
		}
	}
//计算年龄
  function   getAge()
  {
  		var str=$("#birthdate").val();
        var   r   =   str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
        if(r==null)return   false;
        var   d=   new   Date(r[1],   r[3]-1,   r[4]);
        if   (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4])
        {
              var   Y   =   new   Date().getFullYear();
             $("#age").val(Y-r[1]);
        }
  }

  function checkIDCard (obj)
{	str=$(obj).val();
	var isIDCard1 = new Object();
	var isIDCard2 = new Object();

	//身份证正则表达式(15位)
	isIDCard1=/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/;

	//身份证正则表达式(18位)
	isIDCard2=/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[A-Z])$/;

	if (isIDCard1.test(str)||isIDCard2.test(str))
	 {
		return true;
	}else{
		alert("请重新输入正确的身份证码！");
		$(obj).val("");
		return false;
	}

}
