//ȫѡ
function checkAllBtn(){
	var checkAll = $("#checkAll").attr("checked");
	$("input[name='hiddenAction']").attr("checked",checkAll);
}

//��ӡ
function print(){
	var printObj = $("input[name='hiddenAction']:checked");
	if(printObj.length < 1){
		alert('��ѡ����Ҫ��ӡ����');
		return false;
	}else{
		$("input[name='hiddenAction']").each(function(){
			if($(this).attr("id") != "checkAll"){
				if($(this).attr("checked") == false){
					$("#tr" + $(this).val()).css("display","none");
				}
			}
		});
		$(".hiddenAction").css("display","none");
		prn_preview('table1','table1');
	}
}