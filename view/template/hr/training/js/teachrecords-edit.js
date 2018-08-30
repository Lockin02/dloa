$(document).ready(function() {
	$("#orgDeptName").yxselect_dept({
		hiddenId : 'orgDeptId'
	});
});

function checkLength(obj) {
	var v = obj.value;
	var reg = /^\d*\.(\d{2})$/gi;
	if (reg.test(obj.value))
	{
		return false;
	}
		return true;
}

function checkLengths(obj) {
	var v = obj.value;
	obj.value = v.replace(/[^\d\.]/g,'')
}