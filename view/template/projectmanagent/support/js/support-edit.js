//������
$(function() {

	// ֧�����
	$("#signSubjectName").yxcombogrid_branch({
		hiddenId : 'signSubject',
		width : 300,
		gridOptions : {
			showcheckbox : false
		}
	});
    //���齻����Ա
	$("#exchangeName").yxselect_user({
						hiddenId : 'exchangeId'
//						isGetDept:[true,"depId","depName"]
					});
})

$(function() {
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"signSubjectName" : {
			required : true
		},
		"recentExDate" : {
			required : true
		},
		"exDate" : {
			required : true
		},
		"exchangeName" : {
			required : true
		},
		"linkman" : {
			required : true
		},
		"contact" : {
			required : true
		},
		"AClocation" : {
			required : true
		}
	});
});
 //��ʼʱ�������ʱ�����֤
function timeCheck($t){
	var s = plusDateInfo('beginDate','closeDate');
	if(s < 0) {
		alert("��ʼʱ�䲻�ܱȽ���ʱ����");
		$t.value = "";
		return false;
	}
}