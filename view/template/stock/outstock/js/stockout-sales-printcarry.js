//全选
function checkAllBtn(){
	var checkAll = $("#checkAll").attr("checked");
	$("input[name='hiddenAction']").attr("checked",checkAll);
}

//打印
function print(){
	var printObj = $("input[name='hiddenAction']:checked");
	if(printObj.length < 1){
		alert('请选择需要打印的项');
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