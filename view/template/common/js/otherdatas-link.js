$(function() {

});

//�����¼����
function testSignIn(){
	showOpenWin($("#urlTxt").val());
};

//webservice����
function testWebService(){
	//��ʾ
	showLoading();
	$.ajax({
		type : "POST",
		url : "?model=common_otherdatas&action=linkWebService",
		success : function(msg) {
			if (msg == 'success') {
				$("#webShow").html('���³ɹ�');
			}else{
				$("#webShow").html(msg);
			}
			//����
			hideLoading();
		}
	});
};

//��������ͬ��
function esmproject(thisVal){
	//��ʾ
	showLoading();
	$.ajax({
		type : "POST",
		url : "?model=common_otherdatas&action=updateesmproject",
		data : {'thisVal' : thisVal},
		success : function(msg) {
			if (msg == 'success') {
				$("#esmprojectShow" + thisVal).html('���³ɹ�');
			}else{
				$("#esmprojectShow" + thisVal).html(msg);
			}
			//����
			hideLoading();
			$("#esmproject"+thisVal).hide();
		}
	});
}

//��ʾ
function showLoading(){
	$("#loading").show();
	$(".txt_btn_a").attr("disable",true);
}

//����
function hideLoading(){
	$("#loading").hide();
	$(".txt_btn_a").attr("disable",false);
}