$(function() {
	validate({
		"planEndDate" : {
			required : true
		},
		"executionDate" : {
			required : true
		},
		"workloadDay" : {
			required : true
		},
		"effortRate" : {
			required : true
		}
	});
});

/**
 * 检查任务日志是否已填写
 * 
 * @param taskId
 * @param executionDate
 * @returns
 */
function checkExsitLog(executionDate) {
	if (executionDate != "") {
		$
				.ajax({
					url : '?model=produce_log_worklog&action=checkExsitLog',
					type : "POST",
					async : false,
					data : {
						produceTaskId : $("#produceTaskId").val(),
						executionDate : executionDate
					},
					success : function(data) {
						if (data > 0) {
							if (confirm("日期为" + executionDate
									+ "的此任务日志已填写，是否要对当天日志进行修改？")) {
								window.location = "?model=produce_log_worklog&action=toEdit&id="
										+ data;
							}
						}
					}
				})
	}
}

/**
 * 表单校验
 */
function checkform() {
	var checkResult = true;
	$
			.ajax({
				type : "POST",
				data : {
					executionDate : $("#executionDate").val(),
					produceTaskId : $("#produceTaskId").val()
				},
				async : false,
				url : "?model=produce_log_worklog&action=checkExistLog",
				success : function(result) {
					if (result > 0) {
						if (confirm('当日已填写日志， 不可提交,是否跳转到此日志？')) {
							window.location = "?model=produce_log_worklog&action=toEdit&id="
									+ result;
						} else {
							checkResult = false;
						}
					}
				}
			});
	return checkResult;
}