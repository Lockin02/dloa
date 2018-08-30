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
 * ���������־�Ƿ�����д
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
							if (confirm("����Ϊ" + executionDate
									+ "�Ĵ�������־����д���Ƿ�Ҫ�Ե�����־�����޸ģ�")) {
								window.location = "?model=produce_log_worklog&action=toEdit&id="
										+ data;
							}
						}
					}
				})
	}
}

/**
 * ��У��
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
						if (confirm('��������д��־�� �����ύ,�Ƿ���ת������־��')) {
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