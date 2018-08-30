$(function (){
	quitTypeCodeArr = getData('YGZTLZ');
	addDataToSelect(quitTypeCodeArr, 'quitTypeCode');
});
$(function() {
	$("#leaveDeptName").yxselect_dept({
		hiddenId : 'deptId'
	});
	$("#leaveDeptNameS").yxselect_dept({
		hiddenId : 'deptIdS'
	});
	$("#leaveDeptNameT").yxselect_dept({
		hiddenId : 'deptIdT'
	});
    $("#leaveDeptNameF").yxselect_dept({
        hiddenId : 'deptIdF'
    });
validate({
	"xxx" : {
		required : true
	},
	"xxx" : {
		required : true
	}
});
});

$(function (){
	$("#companyName").yxcombogrid_branch({
		hiddenId : 'companyNameId',
		height : 200,
		width:250,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false
		}
	});
});