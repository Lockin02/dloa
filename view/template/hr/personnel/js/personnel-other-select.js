

function selectAll(){
	oEl = event.srcElement;
      for(i = 0;i < document.all.length; i++)
     {
         if(oEl.checked){
             document.all(i).checked = true;
         }else{
             document.all(i).checked = false;
     	}
     }
}

function selectModelAll(key){
	if($("#"+key).attr('checked')==true){
		$("input[id^='"+key+"_']").attr('checked',true);
	}else{
		$("input[id^='"+key+"_']").attr('checked',false);
	}
}

function selectRadio(val){
	$(".clear").attr('style','visibility:hidden;');
	$("#"+val+"Table").attr("style",'visibility:none;');
}


$(function() {
selectModelAll('education');
$("#saveButton").click(function() {
				if ($("input:checked").size() == 0) {
					alert("请至少选择一个导出对象.");
					return false;
				}
					$("#form1").submit();
			});
});