//$(document).ready(function(){
//	function focusin(event) {
//            $("textarea").previousSibling.style.display = "none";
//            $("textarea").parentNode.className += " focus";
//        }
//    function focusout(event) {
//    	alert(2)
//    }
//    $("textarea").focus(focusin);
//    $("textarea").blur(focusout);
//
//});
 //��ʼʱ�������ʱ�����֤
function timeCheck($t){
	var s = plusDateInfo('beginDate','closeDate');
	if(s < 0) {
		alert("��ʼʱ�䲻�ܱȽ���ʱ����");
		$t.value = "";
		return false;
	}
}
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
//������
$(function() {

	// ֧�����
	$("#signSubjectName").yxcombogrid_branch({
		hiddenId : 'signSubject',
		width : 400,
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

//label ����
function focusin(obj) {
    var target = document.getElementById(obj);
    var innerValueTemp = target.parentNode.children[0].innerHTML;
    var innerValue =  innerValueTemp.replace(/<br>/g,"");
    target.value = strTrim(innerValue);
    target.parentNode.children[0].style.display = "none";
    target.parentNode.className += " focus";
}
function focusout(obj) {
	var target = document.getElementById(obj);
    if(target.value === "") {
        target.parentNode.children[0].style.display = "inline";
    }
    target.parentNode.className = target.parentNode.className.replace(/\s+focus/, "");
}
