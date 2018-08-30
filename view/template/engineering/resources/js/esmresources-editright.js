$(document).ready(function(){

	//初始化省份城市信息
	initCity();

	//单选办事处
	$("#officeName").yxcombogrid_office({
		hiddenId : 'officeId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//单选项目经理
	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		mode : 'check',
		isGetDept : [true, "depId", "depName"],
		formCode : 'esmManagerEdit'
	});

	//相关负责人初始化
	//大区经理
	$("#areaManager").yxselect_user({
		hiddenId : 'areaManagerId',
		formCode : 'esmAreaManager'
	});
	//技术经理
	$("#techManager").yxselect_user({
		hiddenId : 'techManagerId',
		formCode : 'esmTechManager'
	});
	//销售负责人
	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		formCode : 'esmSalesman'
	});
	//研发责任人
	$("#rdUser").yxselect_user({
		hiddenId : 'rdUserId',
		formCode : 'esmRdUser'
	});

	//工具类型渲染
	$("#toolType").yxcombogrid_datadict({
		height : 250,
		valueCol : 'dataName',
		hiddenId : 'toolTypeHidden',
		gridOptions : {
			isTitle : true,
			param : {"parentCode":"GCGJLX"},
			showcheckbox : true,
			event : {
				'row_dblclick' : function(e, row, data){

				}
			},
			// 快速搜索
			searchitems : [{
				display : '名称',
				name : 'dataName'
			}]
		}
	});

	/**
	 * 验证信息
	 */
	validate({
		"projectName" : {
			required : true,
			length : [0,100]
		}
	});

	//如果项目没有项目属性，可以设置项目属性
	var attributeHiddenObj = $("#attributeHidden");
	if(attributeHiddenObj.length == 1){
		if(attributeHiddenObj.val() == ""){
			var selectStr = '<select class="select" name="esmproject[attribute]" id="attribute"></select>';

			$("#attributeShow").html(selectStr);

			//获取数据字典信息
			var responseText = $.ajax({
				url : 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI',
				type : "POST",
				data : { "parentCode" : "GCXMSS", "expand1" :"1"},
				async : false
			}).responseText;

			var dataArr = eval("(" + responseText + ")");
			var optionsStr = "<option value=''></option>";
			for (var i = 0, l = dataArr.length; i < l; i++) {
				optionsStr += "<option title='" + dataArr[i].text
						+ "' value='" + dataArr[i].id + "'>" + dataArr[i].text
						+ "</option>";
			}
			$("#attribute").append(optionsStr);
		}
	}
});