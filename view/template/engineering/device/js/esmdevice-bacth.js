// �����������
function changeInfo(thisVal){
    $("span[id^='remarkInfo']").each(function(){
        var selectdVal = $(this).attr('val');
        if(selectdVal == thisVal){
            $(this).show();
            $("#" + selectdVal).addClass('green');
        }else{
            $(this).hide();
            $("#" + selectdVal).removeClass('green');
        }
    });
}

//��֤����
function checkForm(){
	if($("#searchText").val() == ""){
		alert("��ѯ���ݲ���Ϊ��");
		return false;
	}
}