//����֤����
function checkform(){
	if($("#inputExcel").val() =="" ){
		alert("��ѡ����Ҫ�����EXCEL�ļ�");
		return false;
	}

//	alert("��ǰ����δ���");
	$("#loading").show();

	return true;
}

//��ע��Ϣ��ʾ
function changeInfo(thisVal){
	if(thisVal == 0){
		$("#remarkInfo").html('<br/>
				<span id="remarkInfo" style="color:blue">
				<p style="text-indent: 2em">
					�˵��빦�ܶ�Ӧ<span class="red">�豸Ԥ��</span>��excelģ�壬����ʱ���ѯ�豸Ԥ�����Ƿ��Ѵ��ڣ�<br/>��������£��������������豸Ԥ�㡣���빦��ֻ֧��<span class="red">�豸Ԥ��</span>���롣
				</span></p><br/><br/>
				ע��<br/>
				<span class="red">1. ���빦���ݲ�֧�ֵ�Ԫ��ʽ���㡣</span><br/>
				<span class="red">2. ��Ԫ���������������š�</span><br/>');
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