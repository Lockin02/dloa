function toSupport(){

	var userNoSearch = $.trim($("#userNoSearch").val());
	var userNameSearch = $.trim($("#userNameSearch").val());

	var provinceName = $.trim($("#provinceName").val());

	var cityName = $.trim($("#cityName").val());
	var companyNameSearch = $.trim($("#companyNameSearch").val());

	var deptNameSearch = $.trim($("#deptNameSearch").val());
	var deptNameSSearch = $.trim($("#deptNameSSearch").val());
	var deptNameTSearch = $.trim($("#deptNameTSearch").val());
    var deptNameFSearch = $.trim($("#deptNameFSearch").val());
	var highEducation = $.trim($("#highEducation").val());
	var highSchool = $.trim($("#highSchool").val());
	var functionName = $.trim($("#functionName").val());
	var sex = $.trim($("#sex").val());
	var jobNameSearch = $.trim($("#jobNameSearch").val());

	var regionNameSearch = $.trim($("#regionNameSearch").val());
	var employeesStateNameSearch = $.trim($("#employeesStateNameSearch").val());

	var personnelTypeNameSearch = $.trim($("#personnelTypeNameSearch").val());
	var positionNameSearch = $.trim($("#positionNameSearch").val());
	var personnelClassNameSearch = $.trim($("#personnelClassNameSearch").val());
	var entryDateBegin = $.trim($("#entryDateBegin").val());
	var entryDateEnd = $.trim($("#entryDateEnd").val());
	var becomeDateBegin = $.trim($("#becomeDateBegin").val());
	var becomeDateEnd = $.trim($("#becomeDateEnd").val());
	var createDateBegin = $.trim($("#createDateBegin").val());
	var createDateEnd = $.trim($("#createDateEnd").val());
	var positionNameSearch = $.trim($("#positionNameSearch").val());

	var school = $.trim($("#school").val());
	var content = $.trim($("#content").val());
	var company = $.trim($("#company").val());
	var workjob = $.trim($("#workjob").val());

	var certificates = $.trim($("#certificates").val());
	var certifying = $.trim($("#certifying").val());
	var certifyingDate = $.trim($("#certifyingDate").val());

	var searchProjectName = $.trim($("#searchProjectName").val());
	var projectContent= $.trim($("#projectContent").val());
	var beginDate = $.trim($("#beginDate").val());
	var closeDate = $.trim($("#closeDate").val());

	var responsibilities = $.trim($("#responsibilities").val());
	//主列表对象获取
	var listGrid = parent.$("#personnelGrid").data('yxgrid');
	//设置值以及传输列表参数
	setVal(listGrid,'userNoSearch',userNoSearch);
	setVal(listGrid,'staffNameSearch',userNameSearch);
	setVal(listGrid,'nativePlaceProSearch',provinceName);
	setVal(listGrid,'nativePlaceCitySearch',cityName);
	setVal(listGrid,'companyNameSearch',companyNameSearch);

	setVal(listGrid,'highEducation',highEducation);
	setVal(listGrid,'highSchool',highSchool);
	setVal(listGrid,'functionName',functionName);
	setVal(listGrid,'deptNameSearch',deptNameSearch);
	setVal(listGrid,'deptNameSSearch',deptNameSSearch);
    setVal(listGrid,'deptNameTSearch',deptNameTSearch);
    setVal(listGrid,'deptNameFSearch',deptNameFSearch);
	setVal(listGrid,'sex',sex);
	setVal(listGrid,'jobNameSearch',jobNameSearch);
	setVal(listGrid,'regionNameSearch',regionNameSearch);
	setVal(listGrid,'employeesState',employeesStateNameSearch);

	setVal(listGrid,'personnelType',personnelTypeNameSearch);
	setVal(listGrid,'position',positionNameSearch);
	setVal(listGrid,'personnelClass',personnelClassNameSearch);
	setVal(listGrid,'entryDateBegin',entryDateBegin);
	setVal(listGrid,'entryDateEnd',entryDateEnd);
	setVal(listGrid,'becomeDateBegin',becomeDateBegin);
	setVal(listGrid,'becomeDateEnd',becomeDateEnd);
	setVal(listGrid,'createDateBegin',createDateBegin);
	setVal(listGrid,'createDateEnd',createDateEnd);

	setVal(listGrid,'school',school);
	setVal(listGrid,'content',content);
	setVal(listGrid,'company',company);
	setVal(listGrid,'workjob',workjob);
	setVal(listGrid,'responsibilities',responsibilities);

	setVal(listGrid,'certificates',certificates);
	setVal(listGrid,'certifying',certifying);
	setVal(listGrid,'certifyingDate',certifyingDate);

	setVal(listGrid,'searchProjectName',searchProjectName);
	setVal(listGrid,'projectContent',projectContent);
	setVal(listGrid,'beginDate',beginDate);
	setVal(listGrid,'closeDate',closeDate);

	//刷新列表
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal){
	return obj.options.extParam[thisKey] = thisVal;
}

$(function(){
	//二级部门
	$("#deptNameSSearch").yxselect_dept({
		hiddenId : 'deptIdS'
	});
	//三级部门
	$("#deptNameTSearch").yxselect_dept({
		hiddenId : 'deptIdT'
	});
    //四级部门
    $("#deptNameFSearch").yxselect_dept({
        hiddenId : 'deptIdF'
    });
	//直属部门
	$("#deptNameSearch").yxselect_dept({
		hiddenId : 'deptId'
	});
	//职位选择
	$("#jobNameSearch").yxcombogrid_position({
		hiddenId : 'jobId',
		width:350
	});

	$("#regionId").bind('change', function() {
		$("#regionNameSearch").val($("#regionId").find("option:selected").text());
	});
	$("#companyName").bind('change', function() {
		$("#companyNameSearch").val($("#companyName").find("option:selected").text());
	});
});

//清空
function toClear(){
	$(".toClear").val('');
}