
//����һ����ѯ����
function getInvpurchase(){
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=getRepeatArr",
	    async: false,
	    success: function(data){
	   		if(data == "1"){
				$("#shareBody").html('û�з�������������');
	   	    }else{
	   	    	data = "<tr><td>���</td><td>�ɹ���Ʊid</td><td>�ɹ���Ʊ��</td><td>����id</td><td>���ϱ���</td><td>����</td>" +
	   	    			"<td>Դ����</td><td>��ͬ���</td><td>����</td></tr>" + data;
				$("#shareBody").html(data);
	   	    }
		}
	});
}


//��ȡ����
function getCount(){
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=getCount",
	    async: false,
	    success: function(data){
	   		if(data != "0"){
				$("#countNum").html('��¼����Ϊ��' + data);
	   	    }else{
				$("#countNum").html('û�з�������������');
	   	    }
		}
	});
}

//ҳ��ˢ��
function show_page(){
	getInvpurchase();
}

//����������Ϣ
function updateOther(){
	var mark = false;
	//����֤�������
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=getCount",
	    async: false,
	    success: function(data){
	   		if(data != "0"){
				$("#updateInfo").html('����δ�������!���ܽ��иò���');
	   	    }else{
				updateFun();
	   	    }
		}
	});
}

//ʵ�ʸ��µķ���
function updateFun(){
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=updateOther",
	    async: false,
	    success: function(data){
	   		if(data == "1"){
	   			$("#updateInfo").html('�������');
	   	    }else{
	   	    	$("#updateInfo").html(data);
	   	    }
		}
	});
}