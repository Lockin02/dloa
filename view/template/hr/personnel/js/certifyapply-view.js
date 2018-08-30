$(document).ready(function() {
	//显示申请表
	if($("#id").val() != ""){
		$("#applyView").show();
	}

	//显示评价表
	if($("#cassessId").val() != ""){
		$("#cassessView").show();
	}

	//显示换算表
	if($("#scoreId").val() != ""){
		$("#scoreView").show();
	}
})

//申请表
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

//查看得分换算表
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

//查看评价表
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