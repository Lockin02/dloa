
//    //��ʼʱ�������ʱ�����֤
//function timeCheck($t){
//	var s = plusDateInfo('beginDate','closeDate');
//	if(s < 0) {
//		alert("��ʼʱ�䲻�ܱȽ���ʱ����");
//		$t.value = "";
//		return false;
//	}
$(function(){
     // ӦƸְλ
   	YPZWArr = getData('YPZW');
	addDataToSelect(YPZWArr, 'post');
	
	 // ����ˮƽ
	WYSPArr = getData('WYSP');
	addDataToSelect(WYSPArr, 'languageGrade');
	 // �����ˮƷ
	JSJSPArr = getData('JSJSP');
	addDataToSelect(JSJSPArr, 'computerGrade');

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"applicantName" : {
			required : true
		}
	});
})
