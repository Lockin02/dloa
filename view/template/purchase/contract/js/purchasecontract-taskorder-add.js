
function myload(){
	//�Բɹ���ͬ�������豸����������֤������ܳ����ɹ�������豸��������������
	$(".amount").bind("change",function(){
		var thisVal = parseInt( $(this).val() );
		var nextVal = parseInt( $(this).next().val() );
		if(isNaN(this.value.replace(/,|\s/g,''))){
			alert('����������');
				$(this).attr("value",nextVal);
		}
		if(thisVal>nextVal){
			alert("�벻Ҫ���������������� "+nextVal);
			$(this).attr("value",nextVal);
		}else if(thisVal<1){
			alert("�벻Ҫ����0����");
				$(this).attr("value",thisVal);
			$(this).attr("value",nextVal);
		}
	});

}


/**********************ɾ����̬��*************************/
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 2; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i-1;
		}
		sumAllMoney();
	}
}

//���ύʱ��������Ϣ��У��
function checkAllData(){
	var booble=true;
	$("input.amount").each(function(){
		if ($(this).val()==""||$(this).val()==0) {
			alert("����������,����Ϊ�ջ���С��1");
			$(this).focus();
			booble=false;
			return false;
		}
	});

	return booble;

}

