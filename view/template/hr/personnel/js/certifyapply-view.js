$(document).ready(function() {
	//��ʾ�����
	if($("#id").val() != ""){
		$("#applyView").show();
	}

	//��ʾ���۱�
	if($("#cassessId").val() != ""){
		$("#cassessView").show();
	}

	//��ʾ�����
	if($("#scoreId").val() != ""){
		$("#scoreView").show();
	}
})

//�����
function showApply(){
	var skey = "";
	$.ajax({
	    type: "POST",
	    url: "?model=hr_personnel_certifyapply&action=md5RowAjax",
	    data: { "id" : $("#id").val() },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showOpenWin("?model=hr_personnel_certifyapply&action=toViewApply&id=" + $("#id").val() + '&skey=' + skey );
}

//�鿴�÷ֻ����
function showScore(){
	var skey = "";
	$.ajax({
	    type: "POST",
	    url: "?model=hr_certifyapply_cassess&action=md5RowAjax",
	    data: { "id" : $("#cassessId").val() },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showOpenWin("?model=hr_certifyapply_cassess&action=toViewScore&id=" + $("#cassessId").val() + '&skey=' + skey );
}

//�鿴���۱�
function showCassess(){
	var skey = "";
	$.ajax({
	    type: "POST",
	    url: "?model=hr_certifyapply_cassess&action=md5RowAjax",
	    data: { "id" : $("#cassessId").val() },
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	showOpenWin("?model=hr_certifyapply_cassess&action=toView&id=" + $("#cassessId").val() + '&skey=' + skey );
}