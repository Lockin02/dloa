function checkform() {
	if ($("#beginDate").val() == '') {
		alert("��ѡ��ʼ���ڣ�");
		return false;
	}
    if ($("#endDate").val() == '') {
		alert("��ѡ��������ڣ�");
		return false;
	}
	return true;
}

//��ѯ
function delLog() {
	if(checkform() == false){
		return false;
	}

	//��ѯ
    $.ajax({
        type: 'POST',
        data:{beginDate:$("#beginDate").val(),endDate:$("#endDate").val()},
        url: '?model=engineering_worklog_esmworklog&action=searchLog',
        success : function(data) {
			if(data == "0"){
				alert('û�п���ɾ������־��Ϣ');
			}else{
				if(confirm('ϵͳ��ѯ�� ' + data + ' ����־��¼��ȷ��ɾ����')){
					$("#loading").show();
					//ɾ��
				    $.ajax({
				        type: 'POST',
				        data:{beginDate:$("#beginDate").val(),endDate:$("#endDate").val()},
				        url: '?model=engineering_worklog_esmworklog&action=deleteLog',
				        success : function(data) {
							if(data == "1"){
								alert('ɾ���ɹ�');
								window.parent.show_page();
							}else if(data == "0"){
								alert('ɾ��ʧ��');
							}else{
								$("#button").after(data);
							}
							$("#loading").hide();
				        }
				    });
				}
			}
        }
    });
}