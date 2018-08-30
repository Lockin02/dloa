
//    //开始时间与结束时间差验证
//function timeCheck($t){
//	var s = plusDateInfo('beginDate','closeDate');
//	if(s < 0) {
//		alert("开始时间不能比结束时间晚！");
//		$t.value = "";
//		return false;
//	}
$(function(){
     // 应聘职位
   	YPZWArr = getData('YPZW');
	addDataToSelect(YPZWArr, 'post');
	
	 // 外语水平
	WYSPArr = getData('WYSP');
	addDataToSelect(WYSPArr, 'languageGrade');
	 // 计算机水品
	JSJSPArr = getData('JSJSP');
	addDataToSelect(JSJSPArr, 'computerGrade');

	/**
	 * 验证信息
	 */
	validate({
		"applicantName" : {
			required : true
		}
	});
})
