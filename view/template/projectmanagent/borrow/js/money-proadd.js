$(function() {

	var typeHtml = $("#typeHtml").html();
	switch (typeHtml) {
		case "部门" :
			$("#type")
					.html("<input type='text' class='txt' name='money[deptName]' id='deptName' />"
							+ "<input type='hidden' name='money[deptId]' id='deptId' />"
							+ "<input type='hidden' name='money[controlType]' id='controlType' value='dept'>");
			$("#deptuserMoney")
					.html("<td class='form_text_left'>部门员工借用金额</td>"
							+ "<td class='form_text_right'><input type='text' class='txt' name='money[deptuserMoney]' id='deptuserMoney' value='0'></td>");
			createFormatOnClick('deptuserMoney')
			$("#deptName").yxselect_dept({
						hiddenId : 'deptId'
					});
			break;
		case "角色" :
			$("#type")
					.html("<input type='text' class='txt' name='money[roleName]' id='roleName' />"
							+ "<input type='hidden' name='money[roleId]' id='roleId' />"
							+ "<input type='hidden' name='money[controlType]' id='controlType' value='role'>");

			$("#roleName").yxcombogrid_jobs({
						hiddenId : 'roleId'
					});
			break;
		case "个人" :
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
        case "部门" :
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
	        	   alert("部门为空，请选择部门");
	        	   return false;
	        	}{
	        	   return true;
	        	}

	        }else{
	        	   alert("该部门已设置限制金额");
	               return false;
	        }
			break;
	    case "角色" :
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
	        	   alert("角色为空，请选择角色");
	        	   return false;
	        	}else{
	        	   return true;
	        	}
	        }else{
	        	   alert("该角色已设置限制金额");
	           return false;
	        }
	        break;
	    case "个人" :
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
	        	   alert("员工为空，请选择员工");
	        	   return false;
	        	}else{
	        	   return true;
	        	}

	        }else{
	           alert("该员工已设置限制金额");
	           return false;
	        }
	        break;
    }

}