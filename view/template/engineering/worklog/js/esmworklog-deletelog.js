function checkform() {
	if ($("#beginDate").val() == '') {
		alert("请选择开始日期！");
		return false;
	}
    if ($("#endDate").val() == '') {
		alert("请选择结束日期！");
		return false;
	}
	return true;
}

//查询
function delLog() {
	if(checkform() == false){
		return false;
	}

	//查询
    $.ajax({
        type: 'POST',
        data:{beginDate:$("#beginDate").val(),endDate:$("#endDate").val()},
        url: '?model=engineering_worklog_esmworklog&action=searchLog',
        success : function(data) {
			if(data == "0"){
				alert('没有可以删除的日志信息');
			}else{
				if(confirm('系统查询出 ' + data + ' 条日志记录，确认删除吗？')){
					$("#loading").show();
					//删除
				    $.ajax({
				        type: 'POST',
				        data:{beginDate:$("#beginDate").val(),endDate:$("#endDate").val()},
				        url: '?model=engineering_worklog_esmworklog&action=deleteLog',
				        success : function(data) {
							if(data == "1"){
								alert('删除成功');
								window.parent.show_page();
							}else if(data == "0"){
								alert('删除失败');
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