$(function() {

	var typeHtml = $("#typeHtml").html();
	switch (typeHtml) {
		case "����" :
			$("#type")
					.html("<input type='text' class='txt' name='money[deptName]' id='deptName' />"
							+ "<input type='hidden' name='money[deptId]' id='deptId' />"
							+ "<input type='hidden' name='money[controlType]' id='controlType' value='dept'>");
			$("#deptuserMoney")
					.html("<td class='form_text_left'>����Ա�����ý��</td>"
							+ "<td class='form_text_right'><input type='text' class='txt' name='money[deptuserMoney]' id='deptuserMoney' value='0'></td>");
			createFormatOnClick('deptuserMoney')
			$("#deptName").yxselect_dept({
						hiddenId : 'deptId'
					});
			break;
		case "��ɫ" :
			$("#type")
					.html("<input type='text' class='txt' name='money[roleName]' id='roleName' />"
							+ "<input type='hidden' name='money[roleId]' id='roleId' />"
							+ "<input type='hidden' name='money[controlType]' id='controlType' value='role'>");

			$("#roleName").yxcombogrid_jobs({
						hiddenId : 'roleId'
					});
			break;
		case "����" :
			$("#type")
					.html("<input type='text' class='txt' name='money[userName]' id='userName' />"
							+ "<input type='hidden' name='money[userId]' id='userId' />"
							+ "<input type='hidden' name='money[controlType]' id='controlType' value='user'>");
			$("#userName").yxselect_user({
						hiddenId : 'userId'
					});
			break;
	}
});

function subR() {
	var typeHtml = $("#typeHtml").html();
    switch (typeHtml){
        case "����" :
           	var subType = $.ajax({
                type : "POST",
				url : "?model=projectmanagent_borrow_money&action=ajaxCheckingDept",
				data : {
					name : $("#deptName").val()
				},
				async: false
	        }).responseText;
	        if(strTrim(subType) == '1'){
	        	if($("#deptName").val() == ''){
	        	   alert("����Ϊ�գ���ѡ����");
	        	   return false;
	        	}{
	        	   return true;
	        	}

	        }else{
	        	   alert("�ò������������ƽ��");
	               return false;
	        }
			break;
	    case "��ɫ" :
	        var subType = $.ajax({
                type : "POST",
				url : "?model=projectmanagent_borrow_money&action=ajaxCheckingRole",
				data : {
					name : $("#roleName").val()
				},
				async: false
	        }).responseText;
	        if(strTrim(subType) == '1'){
	        	if($("#roleName").val() == ''){
	        	   alert("��ɫΪ�գ���ѡ���ɫ");
	        	   return false;
	        	}else{
	        	   return true;
	        	}
	        }else{
	        	   alert("�ý�ɫ���������ƽ��");
	           return false;
	        }
	        break;
	    case "����" :
	        var subType = $.ajax({
                type : "POST",
				url : "?model=projectmanagent_borrow_money&action=ajaxCheckingUser",
				data : {
					name : $("#userName").val()
				},
				async: false
	        }).responseText;
	        if(strTrim(subType) == '1'){
	        	if($("#userName").val() == ''){
	        	   alert("Ա��Ϊ�գ���ѡ��Ա��");
	        	   return false;
	        	}else{
	        	   return true;
	        	}

	        }else{
	           alert("��Ա�����������ƽ��");
	           return false;
	        }
	        break;
    }

}