//表单验证方法
function checkform(){
	if($("#inputExcel").val() =="" ){
		alert("请选择需要导入的EXCEL文件");
		return false;
	}

//	alert("当前功能未完成");
	$("#loading").show();

	return true;
}

//备注信息显示
function changeInfo(thisVal){
	if(thisVal == 0){
		$("#remarkInfo").html('<br/>
				<span id="remarkInfo" style="color:blue">
				<p style="text-indent: 2em">
					此导入功能对应<span class="red">设备预算</span>的excel模板，导入时会查询设备预算编号是否已存在，<br/>存在则更新，不存在则新增设备预算。导入功能只支持<span class="red">设备预算</span>导入。
				</span></p><br/><br/>
				注：<br/>
				<span class="red">1. 导入功能暂不支持单元格公式计算。</span><br/>
				<span class="red">2. 单元格内请勿带特殊符号。</span><br/>');
	}

	var spanId = 'span' + thisVal;
	var buttonId = 'button' + thisVal;

	$.each($("span[id^='span']"),function(i,n){
		if(this.id == spanId){
			this.className = 'green';
		}else{
			this.className = '';
		}
	});

	$.each($("input[id^='button']"),function(i,n){
		if(this.id == buttonId){
			this.className = 'txt_btn_a_green';
		}else{
			this.className = 'txt_btn_a';
		}
	});
}