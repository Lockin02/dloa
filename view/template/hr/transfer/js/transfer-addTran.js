$(document).ready(function() {

	//������
	$("#managerName").yxselect_user({
		hiddenId : 'managerId'
	});

	//����ǰ��������
	$("#preDeptNameT").yxselect_dept({
		hiddenId :'preDeptIdTId'
	});

	//�������������
	$("#afterDeptNameS").yxselect_dept({
		hiddenId :'afterDeptNameSId'
	});

	//��������������
	$("#afterDeptNameT").yxselect_dept({
		hiddenId : 'afterDeptNameTId'
	});

})