//隐藏区域设置
function mulSelectSet(thisObj){
	thisObj.next().find("input").each(function(i,n){
		if($(this).attr('class') == 'combo-text validatebox-text'){
			$("#"+ thisObj.attr('id') + "Hidden").val(this.value);
		}
	});
}

//设值多选值 -- 初始化赋值
function mulSelectInit(thisObj){
	//初始化对应内容
	var objVal = $("#"+ thisObj.attr('id') + "Hidden").val();
	if(objVal != "" ){
		thisObj.combobox("setValues",objVal.split(','));
	}
}

//初始化建议补充方式信息
function initPCC(){
	//获取建议补充方式
	var addModeNameArr = $('#addModeNameHidden').val().split(",");
	var str;
	//建议补充方式渲染
	var addModeNameObj = $('#addModeName');
	addModeNameObj.combobox({
		url:'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI&parentCode=HRBCFS',
		multiple:true,
		valueField:'text',
		textField:'text',
		editable : false,
		formatter: function(obj){
			//判断 如果没有初始化数组中，则选中
			if(addModeNameArr.indexOf(obj.text) == -1){
				str = "<input type='checkbox' id='addModeName_"+ obj.text +"' value='"+ obj.text +"'/> " + obj.text;
			}else{
				str = "<input type='checkbox' id='addModeName_"+ obj.text +"' value='"+ obj.text +"' checked='checked'/> " + obj.text;
			}
			return str;
		},
		onSelect : function(obj){
			//checkbox设值
			$("#addModeName_" + obj.text).attr('checked',true);
			//设置对象下的选中项
			mulSelectSet(addModeNameObj);
		},
		onUnselect : function(obj){
			//checkbox设值
			$("#addModeName_" + obj.text).attr('checked',false);
			//设置隐藏域
			mulSelectSet(addModeNameObj);
		}
	});

	//客户类型初始化赋值
	mulSelectInit(addModeNameObj);
}

//初始化建议补充方式信息
function initLevel(data){
	//获取建议补充方式
	var positionLevelArr = $('#positionLevelHidden').val().split(",");
	var str;
	//建议补充方式渲染
	var positionLevelObj = $('#positionLevel');
	var dataArr=[{"positionLevel":"初级"},{"positionLevel":"中级"},{"positionLevel":"高级"}];

	if(data){  //add chenrf
		dataArr = data;
	}

	positionLevelObj.combobox({
		data : dataArr,
		multiple:true,
		editable : false,
		valueField:'positionLevel',
		textField:'positionLevel',
		formatter: function(obj){
			//判断 如果没有初始化数组中，则选中
			if(positionLevelArr.indexOf(obj.positionLevel) == -1){
				str = "<input type='checkbox' id='positionLevel_"+ obj.positionLevel +"' value='"+ obj.positionLevel +"'/> " + obj.positionLevel;
			}else{
				str = "<input type='checkbox' id='positionLevel_"+ obj.positionLevel +"' value='"+ obj.positionLevel +"' checked='checked'/> " + obj.positionLevel;
			}
			return str;
		},
		onSelect : function(obj){
			//checkbox设值
			$("#positionLevel_" + obj.positionLevel).attr('checked',true);
			//设置对象下的选中项
			mulSelectSet(positionLevelObj);
		},
		onUnselect : function(obj){
			//checkbox设值
			$("#positionLevel_" + obj.positionLevel).attr('checked',false);
			//设置隐藏域
			mulSelectSet(positionLevelObj);
		}
	});

	//客户类型初始化赋值
	mulSelectInit(positionLevelObj);

	//去除网络和设备的必填属性
	$("#networkSpan").css("color" ,"");
	$("#network").removeClass("validate[required]");
	$("#deviceSpan").css("color" ,"");
	$("#device").removeClass("validate[required]");
}

/*************add chenrf 20130508************************************/
//选择网优类型职位时，加载数据字典内容
function initLevelWY(){
	var dataArr=[];
	var data=$.ajax({
		url:'?model=hr_basicinfo_level&action=listJson&sort=personLevel&dir=ASC&status=0',
		type:'post',
		dataType:'json',
		async:false
	}).responseText;
	data = eval("("+data+")");

	for(i = 0 ;i < data.length ;i++) {
		dataArr.push({"positionLevel":data[i].personLevel})
	}
	initLevel(dataArr);
	//将网络和设备设置为必填
	$("#networkSpan").css("color" ,"blue");
	$("#network").addClass("validate[required]");
	$("#deviceSpan").css("color" ,"blue");
	$("#device").addClass("validate[required]");
}

$(function(){
	var $postType = $("#postType");
	if('YPZW-WY' == $postType.val()) {
		initLevelWY();
	} else {
		initLevel();
	}
	//职位类型改变触发事件
	$postType.change(function(){
		$('#positionLevelHidden').val('');
		$('#positionLevel').val('');
		if($(this).val() == 'YPZW-WY') { //选择网优类型
			initLevelWY();
		} else {
			initLevel();
		}
	});

	//指定导师
	$("#tutor").yxselect_user({
		hiddenId : 'tutorId'
	});

	//学历触发事件
	$("#education").change(function(){
		var edicationName=($(this).find('option:selected').text())
		$("input[name='apply[educationName]']").val(edicationName);
	});

	//归属区域
	$("#useAreaId").change(function(){
		$("#useAreaName").val($(this).find("option:selected").text());
	});
});

//add chenrf
//提交校验数据
function checkData(){
	if($("#addTypeCode").val() == "") {
		alert("请输入增员类型");
		return false;
	} else if ($("#addTypeCode").val() == "ZYLXLZ") {
		if($("#leaveManName").val() == "") {
			alert("请输入离职/换岗人姓名");
			return false;
		}
	} else if ($("#positionLevelHidden").val() == "") {
		alert("请选择级别");
		return false;
	} else if ($("#addModeNameHidden").val() == "") {
		alert("请选择建议补充方式");
		return false;
	} else if ($("#postType").val() == "") {
		alert("请选择职位类型");
		return false;
	} else if ($("#employmentTypeCode").val() == "") {
		alert("请选择用工类型");
		return false;
	} else if ($("#applyReason").val() == "") {
		alert("请输入需求原因");
		return false;
	} else if ($("#postType").val() == 'YPZW-WY' && $("#province").val() == '') {
		alert("请选择工作省市");
		return false;
	} else if ($("#oldNeedNum").val() > 0) {
		if ($("#needNum").val() > $("#oldNeedNum").val() || $("#needNum").val() < 1) {
			alert("需求人数不能增加或者小于1");
			return false;
		}
	} else {
		return true;
	}
}

$(function(){
	validate({
		"deptName" : {
			required : true
		},
		"positionName" : {
			required : true
		},
		"postType" : {
			required : true
		},
		"needNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"workPlace" : {
			required : true
		},
		"hopeDate" : {
			required : true
		},
		"applyReason" : {
			required : true
		},
		"resumeToName" : {
			required : true
		},
		"addTypeCode" : {
			required: true
		},
		"employmentTypeCode" : {
			required : true
		},
		"wageRange" : {
			required : true
		},
		"regionId" : {
			required : true
		},
		"workDuty" : {
			required : true
		},
		"jobRequire" : {
			required : true
		},
		"workArrange" : {
			required : true
		},
		"assessmentIndex" : {
			required : true
		},
		"tutor" : {
			required : true
		},
		"computerConfiguration" : {
			required : true
		}
	});

	$("#plan").yxcombogrid_plan({
		gridOptions:{
			param:{
				'state':'2'
			},
			event:{
				row_dblclick:function(e,row,data){
					$("#objId").val(data.id);
					$("#deptName").val(data.deptName);
					$("#deptId").val(data.deptId);
					$("#postType").val(data.postType);//职位类型
					$("#positionName").val(data.positionName);
					$("#positionId").val(data.positionId);
					$("#developPositionName").val(data.developPositionName);

					$("#positionLevelHidden").val(data.positionLevel);   //级别
					if('YPZW-WY' == data.postType){
						initLevelWY();
					} else {
						initLevel();
					}

					$("#positionLevelHidden").val(data.positionLevel);
					$("input[type='radio'][name='apply[isEmergency]'][value='"+data.isEmergency+"']")[0].checked=true;//是否紧急
					$("#needNum").val(data.needNum);
					$("#hopeDate").val(data.hopeDate);
					$("#workPlace").val(data.workPlace);
					$("#wageRange").val(data.wageRange);

					$("#addModeNameHidden").val(data.addMode);  //建议补充方式
					initPCC();

					$("#addTypeCode").val(data.addTypeCode);//增员类型
					if('ZYLXLZ'==data.addTypeCode){
						$("#leaveManName").attr('readonly',false);
						$("#leaveManName").removeClass('readOnlyText');
						$("#leaveManName").addClass('txt');
					} else {
						$("#leaveManName").attr('readonly',true);
						$("#leaveManName").removeClass('txt');
						$("#leaveManName").addClass('readOnlyText');
					}
					$("#leaveManName").val(data.leaveManName);
					$("#employmentTypeCode").val(data.employmentTypeCode);//用工类型
					$("#useAreaName").val(data.useAreaName);
					$("#regionId").val(data.useAreaId);       //归属中心
					$("#projectType").val(data.projectType);//项目类型
					$("#projectGroup").val(data.projectGroup);
					$("#projectGroupId").val(data.projectGroupId);
					$("#projectCode").val(data.projectCode);
					if(typeof(data.sex) != 'undefined' && $.trim(data.sex) != '') {
						$("input[type='radio'][name='apply[sex]'][value='"+data.sex+"']")[0].checked = true;//性别
					}
					$("#age").val(data.age);
					$("#maritalStatus").val(data.maritalStatus);//婚姻
					$("#education").val(data.education);//学历
					$("#professionalRequire").val(data.professionalRequire);
					$("#workExperiernce").val(data.workExperiernce);
					$("#resumeToId").val(data.resumeToId);
					$("#resumeToName").val(data.resumeToName);
					$("#applyReason").val(data.applyReason);
					$("#jobRequire").val(data.jobRequire);
					$("#workDuty").val(data.workDuty);
					$("#workArrange").val(data.workArrange);
					$("#assessmentIndex").val(data.assessmentIndex);
					$("#uploadfileList").val(data.uploadfileList);
					$("#network").val(data.network);
					$("#device").val(data.device);
				}
			}
		}
	});
});
