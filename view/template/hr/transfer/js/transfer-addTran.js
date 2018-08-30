$(document).ready(function() {

	//经办人
	$("#managerName").yxselect_user({
		hiddenId : 'managerId'
	});

	//调动前三级部门
	$("#preDeptNameT").yxselect_dept({
		hiddenId :'preDeptIdTId'
	});

	//调动后二级部门
	$("#afterDeptNameS").yxselect_dept({
		hiddenId :'afterDeptNameSId'
	});

	//调动后三级部门
	$("#afterDeptNameT").yxselect_dept({
		hiddenId : 'afterDeptNameTId'
	});

})