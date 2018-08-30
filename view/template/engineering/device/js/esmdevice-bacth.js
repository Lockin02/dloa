// 变更操作类型
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

//验证必填
function checkForm(){
	if($("#searchText").val() == ""){
		alert("查询内容不能为空");
		return false;
	}
}