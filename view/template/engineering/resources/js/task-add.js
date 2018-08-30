$(document).ready(function() {
	//渲染接收人
	$("#receiverName").yxselect_user({
		hiddenId : 'receiverId'
	});

	//获取省份数组并赋值给provinceArr
	provinceArr = getProvince();
	//把省份数组provinceArr赋值给proCode
	addDataToProvince(provinceArr,'proCode');
	//编辑时初始化省份
	var place = $("#place").val();
	$("#proCode").find("option[text='"+place+"']").attr("selected",true);
	
	$("#detailInfo").yxeditgrid({
		url : "?model=engineering_resources_resourceapplydet&action=listJsonForTask",
		param : {
			"mainId" : $("#applyId").val(),
			"isConfirm" : 1,//确认了的物料
			"isNotDetail" : 1//未处理完成的物料
		},
		objName : 'task[applydet]',
		tableClass : 'form_in_table',
		isAddAndDel : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			width : 80,
			type : "statictext"
		}, {
			display : '设备名称',
			name : 'resourceName',
			type : "statictext"
		}, {
			display : '单位',
			name : 'unit',
			width : 50,
			type : "statictext"
		}, {
			display : '申请数量',
			name : 'number',
			width : 50,
			type : "statictext"
		}, {
			display : '已下达数量',
			name : 'exeNumber',
			width : 60,
			type : "statictext"
		}, {
			display : '领用日期',
			name : 'planBeginDate',
			width : 70,
			type : "statictext"
		}, {
			display : '归还日期',
			name : 'planEndDate',
			width : 70,
			type : "statictext"
		}, {
			display : '使用天数',
			name : 'useDays',
			width : 50,
			type : 'hidden'
		}, {
			display : '设备折价',
			name : 'price',
			width : 80,
			type : "hidden"
		}, {
			display : '预计成本',
			name : 'amount',
			type : 'money',
			width : 80,
			type : "hidden"
		}, {
			display : '申请人备注',
			name : 'remark',
			type : "statictext"
		}, {
			display : '预计筹备天数反馈',
			name : 'feeBack',
			tclass : 'txtshort',
			width : 95
		}, {
			display : '需分配数量',
			name : 'needExeNum',
			width : 60,
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '本次分配数量',
			name : 'thisExeNum',
			width : 70,
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '任务分配',
			name : '',
			width : 60,
			type : "statictext",
			process : function(){
				return "<input type='button' class='txt_btn_a' value='任务分配'/>";
			},
			event : {
				click : function(e){
					var g = e.data.gird;
					var rowNum = e.data.rowNum;
					var needExeNumObj = g.getCmpByRowAndCol(rowNum,'needExeNum');//需分配数量
					var thisExeNumObj = g.getCmpByRowAndCol(rowNum,'thisExeNum');//本次分配数量
					if(needExeNumObj.val()*1 == thisExeNumObj.val()*1){
						easy_alert('此设备已经分配完成');
						return true;
					}
					//调用任务方法
					dealTask(rowNum);
				}
			}
		}],
		event : {
			'reloadData' : function(e,g,data){
				if(!data){
					alert('没有需要下达发货的设备');
					window.close();
				}
			}
		}
	});
	/**
	 * 验证信息
	 */
	validate({
		"receiverName" : {
			required : true
		}
	});
});

//任务分配
function dealTask(rowNum){
	var winObj = $("#dealTaskWin");
	//窗口打开
	winObj.window({
		title : '任务分配',
		height : 300,
		width : 400
	});

	//渲染设备情况
	if(winObj.children().length == 1){
		winObj.yxeditgrid('remove');
	}

	//获取设备信息
	var detailObj = $("#detailInfo");
	var resourceId = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"resourceId").val();
	var resourceName = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"resourceName").val();
	var needExeNum = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"needExeNum").val();
	var thisExeNum = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"thisExeNum").val();
	var lastNeedNum = needExeNum*1 - thisExeNum*1;

	//在这里添加一个分配数量获取
	var taskInfoObj = $("#taskInfo");
	var areaArr = [];
	var areaNumArr = {};
	//获取已分配信息
	if(taskInfoObj.children().length != 0 ){
		var resourceIdArr = taskInfoObj.yxeditgrid('getCmpByCol','resourceId');
		if(resourceIdArr.length != 0){
			resourceIdArr.each(function(){
				if(resourceId == this.value){//本设备
					var taskRowNum = $(this).data('rowNum');
					var areaId = taskInfoObj.yxeditgrid("getCmpByRowAndCol",taskRowNum,"areaId").val();
					if($.inArray(areaId,areaArr) != -1){
						areaNumArr[areaId] += taskInfoObj.yxeditgrid("getCmpByRowAndCol",taskRowNum,"number").val()*1;
					}else{
						areaArr.push(areaId);
						areaNumArr[areaId] = taskInfoObj.yxeditgrid("getCmpByRowAndCol",taskRowNum,"number").val()*1;
					}
				}
			});
		}
	}

	winObj.yxeditgrid({
		url : '?model=engineering_device_esmdevice&action=projectAreaJson',
		param : {
			'isList' : 1,
			'list_id' : resourceId,
			'areaNumArr' : areaNumArr
		},
		tableClass : 'form_in_table',
		height : 400,
		isAddAndDel : false,
		title : '设备：'+resourceName + ',剩余分配: ' + lastNeedNum,
		colModel : [{
			display : 'id',//这个id是区域id
			name : 'id',
			type : 'hidden'
		}, {
			display : '区域',
			name : 'Name',
			type : 'statictext'
		}, {
			display : '可用数量',
			name : 'surplus',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '发货数量',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function(e){
					var g = e.data.gird;
					var rowNum = e.data.rowNum;
					var surplus = g.getCmpByRowAndCol(rowNum,'surplus').val()*1;
					if(surplus < this.value*1){
						var areaName = g.getCmpByRowAndCol(rowNum,'Name').val();
						easy_alert('区域【'+areaName+'】库存不足');
						g.setRowColValue(rowNum,'number',0);
					}
					var countNum = 0;
					var numArr = g.getCmpByCol('number');
					numArr.each(function(){
						countNum += this.value*1;
					});
					$("#countNum").val(countNum);
				}
			}
		}],
		event : {
			'reloadData' : function(e,g,data){
				winObj.attr("style", "overflow-x:auto;overflow-y:auto;height:300px;");
				winObj.find('thead tr').eq(0).children().eq(0).append(
					' <input type="button" class="txt_btn_a" onclick="confirmDeal('+rowNum+');" value=" 确 认 "/>'
				);
				if(!data){
					winObj.find("tbody").append("<tr class='tr_even'><td colspan='4'>-- 没有相应库存 --</td></tr>");
				}else{
					winObj.find("tbody").append("<tr class='tr_count'><td colspan='3'></td><td><input id='countNum' class='readOnlyTxtShortCount' readonly='readonly'></td></tr>");
				}
			}
		}
	});
}

//确认分配
function confirmDeal(rowNum){
	//窗口打开
	var winObj = $("#dealTaskWin");

	//验证是否有分配
	var numArr = winObj.yxeditgrid('getCmpByCol','number');
	var isDeal = false;
	var dealNum = 0;//分配的数量
	if(numArr.length > 0){
		numArr.each(function(){
			if(this.value*1 != 0 && this.value != ""){
				isDeal = true;
				dealNum += this.value*1;
			}
		});
	}

	//申请列表取数
	var detailObj = $("#detailInfo");
	var needExeNumObj = detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'needExeNum');//需分配数量
	var needExeNum = needExeNumObj.val();

	//验证
	if(isDeal == false){
		easy_alert('没有分配信息');
	}else if(needExeNum*1 < dealNum){
		easy_alert('分配数量为: ' + dealNum + ",已超过需分配数量: " + needExeNum + ",请重新分配发货数量");
	}else{
		//任务表渲染
		var taskInfoObj = $("#taskInfo");
		if(taskInfoObj.children().length == 0){//如果还没有列表，初始化
			initTaskGrid(taskInfoObj);
		}

		//这部分开始正式赋值
		numArr.each(function(){
			if(this.value*1 != 0 && this.value != ""){
				var taskRowNum = $(this).data('rowNum');//分配记录行数

				//插入数据
				outJson = {
					//分配部分
					"areaName" : winObj.yxeditgrid('getCmpByRowAndCol',taskRowNum,'Name').val(),
					"areaId" : winObj.yxeditgrid('getCmpByRowAndCol',taskRowNum,'id').val(),
					"number" : winObj.yxeditgrid('getCmpByRowAndCol',taskRowNum,'number').val(),
					//设备部分
					"resourceId" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'resourceId').val(),
					"resourceName" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'resourceName').val(),
					"resourceTypeId" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'resourceTypeId').val(),
					"resourceTypeName" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'resourceTypeName').val(),
					"unit" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'unit').val(),
					"planBeginDate" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'planBeginDate').val(),
					"planEndDate" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'planEndDate').val(),
					"useDays" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'useDays').val(),
					"applyDetailId" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'id').val(),
					"remark" : detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'remark').val(),
					"applyDetailRowNum" : rowNum
				};
				var taskRowNum = taskInfoObj.yxeditgrid('getAllAddRowNum');
			    taskInfoObj.yxeditgrid('addRow',taskRowNum, outJson);
			}
		});

		var thisExeNumObj = detailObj.yxeditgrid('getCmpByRowAndCol',rowNum,'thisExeNum');//本次分配数量
		thisExeNumObj.val(thisExeNumObj.val()*1 + dealNum);

		//关闭窗口
		winObj.window('close');
	}
}

//提示信息
function easy_alert(msg){
	$.messager.alert('提示',msg);
}

//列表初始化
function initTaskGrid(taskInfoObj){
	taskInfoObj.yxeditgrid({
		objName : 'task[taskdetail]',
		tableClass : 'form_in_table',
		isAdd : false,
		isAddOneRow : false,
		colModel : [{
			display : '设备id',
			name : 'resourceId',
			type : 'hidden'
		}, {
			display : '分类id',
			name : 'resourceTypeId',
			type : 'hidden'
		}, {
			display : '发货区域',
			name : 'areaName',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : 'areaId',
			name : 'areaId',
			type : 'hidden'
		}, {
			display : 'applyDetailId',
			name : 'applyDetailId',
			type : 'hidden'
		}, {
			display : 'applyDetailRowNum',
			name : 'applyDetailRowNum',
			type : 'hidden'
		}, {
			display : '设备分类',
			name : 'resourceTypeName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			display : '设备名称',
			name : 'resourceName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '数量',
			name : 'number',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '领用日期',
			name : 'planBeginDate',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '归还日期',
			name : 'planEndDate',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '使用天数',
			name : 'useDays',
			width : 50,
			type : 'hidden'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txtmiddle'
		}],
		event : {
			'removeRow' : function(e, rowNum, row){
				//删除行的时候，取消分配的数量
				var thisExeNumObj = $("#detailInfo").yxeditgrid('getCmpByRowAndCol',row.applyDetailRowNum,'thisExeNum');//本次分配数量
				thisExeNumObj.val(thisExeNumObj.val()*1 - row.number*1);

				//如果是删除完,清楚此列表
				if(taskInfoObj.yxeditgrid('getCmpByCol','number').length == 0){
					taskInfoObj.yxeditgrid('remove');
				}
			}
		}
	});
}

//表单验证
function checkForm(){
	//任务表渲染
	var taskInfoObj = $("#taskInfo");
	if(taskInfoObj.children().length == 0){//如果还没有列表，初始化
		easy_alert('没有任务分配详情');
		return false;
	}else if(taskInfoObj.yxeditgrid('getCmpByCol','number').length == 0){
		easy_alert('没有任务分配详情');
		return false;
	}

	//申请数量检验 TODO
}

//获取省份
function getProvince() {
	var responseText = $.ajax({
		url : 'index1.php?model=system_procity_province&action=getProvinceNameArr',
		type : "POST",
		async : false
	}).responseText;
	var o = eval("(" + responseText + ")");
	return o;
}

/**
 * 添加省份数组添加到下拉框
 */
function addDataToProvince(data, selectId) {
	$("#" + selectId).append("<option ></option>");
	for (var i = 0, l = data.length; i < l; i++) {
		$("#" + selectId).append("<option title='" + data[i].text
				+ "' value='" + data[i].value + "'>" + data[i].text
				+ "</option>");
	}
}
/**
* 当省份改变时对，对esmproject[proCode]的title的值赋值给esmproject[proName]
*/
function setProName(){
	$('#place').val($("#proCode").find("option:selected").attr("title"));
}