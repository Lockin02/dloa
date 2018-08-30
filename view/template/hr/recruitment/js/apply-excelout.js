$(document).ready(function () {
	$("#formManName").yxselect_user({
		hiddenId: 'formManId'
	});

	$("#deptName").yxselect_dept({
		hiddenId: 'deptId',
		event: {
			selectReturn: function (e, row) {
				$("#positionName").val('');
				$("#positionId").val('');
				$("#positionName").yxcombogrid_jobs('remove');
				$("#positionName").yxcombogrid_jobs({
					hiddenId: 'positionId',
					width: 280,
					gridOptions: {
						param: {
							deptId: row.dept.id
						}
					}
				});
			}
		}
	});

	$("#positionName").click(function () {
		if ($("#deptName").val() == '') {
			alert('请选择需求部门!');
			$(this).val('');
			return;
		}
	});
});