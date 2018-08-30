
//新增时提交审批 -- 独立新增
function isUpdateFun(thisVal){
//	$("#isUpdate").val(thisVal);
}

/**
 * 金额计算
 */
function amountCount() {
	var numberOne = $("#numberOne").val();
	var numberTwo = $("#numberTwo").val();
	var price = $("#price").val();
	if (price != "" && numberOne != "") {
		var sum = numberOne * price;
		if(numberTwo != ""){
			sum = sum * numberTwo;
		}
		setMoney('amount', sum);
	}
}


//计算公式
function countAll(rowNum) {
	//从表前置字符串
	var beforeStr = "importTable_cmp";
	//获取当前单价
	price= $("#"+ beforeStr + "_price" + rowNum+"_v").val();

	//获取数量1
	numberObj = $("#"+ beforeStr + "_numberOne" + rowNum);
	//获取数量2
	numberTwoObj = $("#"+ beforeStr + "_numberTwo" + rowNum);

	if(price != ""){
		//数量1处理
		if(numberObj.val()== ""){
			numberObj.val(1);
		}

		//数量2处理
		if(numberTwoObj.val()== ""){
			numberTwoObj.val(1);
		}

		//获取数量1
		var amount = accMul(price,numberObj.val(),2);

		//获取数量2
		amount = accMul(amount,numberTwoObj.val(),2);

		//计算总金额
		setMoney(beforeStr + "_amount" + rowNum,amount);
	}else{
		numberObj.val("");
		numberTwoObj.val("");
		//计算总金额
		setMoney(beforeStr + "_amount" + rowNum,"");
	}
}

//计算人力成本
function countPerson(rowNum){
	var objGrid = $("#importTable");

	//单价
	var price = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val();
	if(price != ""){
		//人力系数
		var coefficient = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"coefficient").val();

		//人力系数
		var numberOne = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"numberOne").val();
		var numberTwo = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"numberTwo").val();

		if(numberOne != "" && numberTwo != ""){
			var budgetDay = accMul(numberOne,numberTwo,2); //人天
			var budgetPeople = accMul(budgetDay,coefficient,2); //成本天数
			var amount = accMul(budgetDay,price,2); //成本

			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetDay").val(budgetDay);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetPeople").val(budgetPeople);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"amount").val(amount);
		}
	}
}

//计算天数
function countDate(thisKey,rowNum){
	var objGrid = $("#importTable");
	//加入日期
	var planBeginDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"planBeginDate");
	//离开日期
	var planEndDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"planEndDate");

	if(planBeginDateObj.val() != "" && planEndDateObj.val() != ""){
		//实际天数
		var actDay = DateDiff(planBeginDateObj.val(),planEndDateObj.val()) + 1;
		if(actDay < 1){
			alert('加入日期不能小于离开日期');
			//设置实际天数
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,thisKey).val('');
		}else{
			//设置实际天数
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"numberTwo").val(actDay);
		}
	}
}

//计算人力成本 -- 用于编辑页面
function countPersonE(){
	//单价
	var price = $("#price").val();
	if(price != ""){
		//人力系数
		var coefficient = $("#coefficient").val();

		//人力系数
		var numberOne = $("#numberOne").val();
		var numberTwo = $("#numberTwo").val();

		if(numberOne != "" && numberTwo != ""){
			var budgetDay = accMul(numberOne,numberTwo,2); //人天
			var budgetPeople = accMul(budgetDay,coefficient,2); //成本天数
			var amount = accMul(budgetDay,price,2); //成本

			setMoney("budgetDay",budgetDay);
			$("#budgetPeople").val(budgetPeople);
			setMoney("amount",amount);
		}
	}
}

//计算天数 -- 用于编辑页面
function countDateE(thisDate){
	//加入日期
	var planBeginDateObj = $("#planBeginDate");
	var planEndDateObj = $("#planEndDate");

	if(planBeginDateObj.val() != "" && planEndDateObj.val() != ""){
		//实际天数
		var actDay = DateDiff(planBeginDateObj.val(),planEndDateObj.val()) + 1;
		if(actDay < 1){
			alert('加入项目日期不能大于离开项目日期');
			if(thisDate){
				$("#"+thisDate).val('');
			}
		}else{
			//设置实际天数
			$("#numberTwo").val(actDay);
		}
	}
}

//重新刷新tab
function reloadTab(thisVal){
	var tt = window.parent.$("#tt");
	var tb=tt.tabs('getTab',thisVal);
	tb.panel('options').headerCls = tb.panel('options').thisUrl;
}

/********************** 现场预算部分 *************************/

//自定义费用选择功能 - 弹出选择
function selectCostType2(){
	var objGrid = $("#importTable");
	var costTypeArr = objGrid.yxeditgrid("getCmpByCol", "budgetId");

	//当前存在费用类型数组
	var costTypeIdArr = [];
	//当前存在费用类型字符串
	var costTypeIds = '';

	if(costTypeArr.length > 0){
		//缓存当前存在费用类型
		costTypeArr.each(function(i,n){
			//判断是否已删除
			if($("#esmbudget[budgets]_" + i +"_isDelTag").length == 0){
				costTypeIdArr.push(this.value);
			}
		});
		//构建当前存在费用类型id串
		costTypeIds = costTypeIdArr.toString();
	}

	//费用类型隐藏赋值
	$("#costTypeIds").val(costTypeIds);

	//第一次加载
	var isFirst = false;

	if($("#costTypeInner").html() == ""){
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_expense&action=getCostType",
		    async: false,
		    success: function(data){
		   		if(data != ""){
					$("#costTypeInner").html("<div id='costTypeInner2'>" + data + "</div>")
					if(costTypeIds != ""){
						//设值
						for(var i = 0; i < costTypeIdArr.length;i++){
							$("#chk" + costTypeIdArr[i]).attr('checked',true);
							$("#view" + costTypeIdArr[i]).attr('class','blue');
						}
					}
					//延时调用排序方法
					setTimeout(function(){
						initMasonry();
						if(checkExplorer() == 1){
							$("#costTypeInner2").height(580).css("overflow-y","scroll");
						}
					},200);
		   	    }else{
					alert('没有找到自定义的费用类型');
		   	    }

			}
		});
	}
}


//瀑布流排序
function initMasonry(){
	$('#costTypeInner2').masonry({
		itemSelector: '.box'
	});
}

//判断浏览器
function checkExplorer(){
	var Sys = {};
    var ua = navigator.userAgent.toLowerCase();
    window.ActiveXObject ? Sys.ie = ua.match(/msie ([\d.]+)/)[1] :
    document.getBoxObjectFor ? Sys.firefox = ua.match(/firefox\/([\d.]+)/)[1] :
    window.MessageEvent && !document.getBoxObjectFor ? Sys.chrome = ua.match(/chrome\/([\d.]+)/)[1] :
    window.opera ? Sys.opera = ua.match(/opera.([\d.]+)/)[1] :
    window.openDatabase ? Sys.safari = ua.match(/version\/([\d.]+)/)[1] : 0;

    if(Sys.ie){
		return 1;
    }
}

//上下级渲染
function CostTypeShowAndHide(thisCostType){
	//缓存表格对象
	var tblObj = $("table .ct_"+thisCostType + "[isView='1']");
	//如果表格当前是隐藏状态，则显示
	if(tblObj.is(":hidden")){
		tblObj.show();
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//三级费用项目查看
function CostType2View(thisCostType){
	//缓存表格对象
	var tblObj = $("table .ct_"+thisCostType);
	//如果表格当前是隐藏状态，则显示
	if(tblObj.is(":hidden")){
		tblObj.show();
		tblObj.attr('isView',1);
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		tblObj.attr('isView',0);
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//选择自定义费用类型
function setCustomCostType(thisCostType,thisObj){
	//缓存从表对象
	var objGrid = $("#importTable");
	//查找对应费用所在行
	var findRowNum = findBudgetRowNum(thisCostType);

	if($(thisObj).attr('checked') == true){
		$("#view" + thisCostType).attr('class','blue');

		//不存在当前行中
		if(findRowNum == -1){
			//缓存选择项
			var chkObj = $("#chk" + thisCostType);
			var chkName = chkObj.attr('name');  //费用名称
			var chkParentName = chkObj.attr('parentName'); //费用父类型名称
			var chkParentId = chkObj.attr('parentId'); //费用父类型id

			//重新获取行数
			var rowNum = objGrid.yxeditgrid("getAllAddRowNum");

			//新增行
			objGrid.yxeditgrid("addRow",rowNum);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetName").val(chkName);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetId").val(thisCostType);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"parentName").val(chkParentName);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"parentId").val(chkParentId);
		}else{
			//删除
//			objGrid.yxeditgrid("removeRow",findRowNum);
		}
	}else{
		$("#view" + thisCostType).attr('class','');
		//删除
		objGrid.yxeditgrid("removeRow",findRowNum);
	}
}

//取消选中
function cancelCheck(rowNum){
	//缓存从表对象
	var objGrid = $("#importTable");

	//获取被取消的费用类型
	var budgetId = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetId").val();

	//当费用选择界面没出来的时候
	if($("#costTypeInner").html() != ""){
		var costTypeArr = objGrid.yxeditgrid("getCmpByCol", "budgetId");

		//当前存在费用类型数组
		var costTypeIdArr = [];
		//当前存在费用类型字符串
		var costTypeIds = '';

		if(costTypeArr.length > 0){
			//缓存当前存在费用类型
			costTypeArr.each(function(i,n){
				//判断是否已删除
				if($("#esmbudget[budgets]_" + i +"_isDelTag").length == 0){
					costTypeIdArr.push(this.value);
				}
			});
		}

		//如果费用已经不存在已选中费用中，则取消模板内的费用选择
		if(costTypeIdArr.indexOf(budgetId) == -1){
			$("#chk" + budgetId).attr('checked',false);
			$("#view" + budgetId).attr('class','');
		}
	}
}

//费用类型复制
function copyBudget(rowNum){
	//缓存从表对象
	var objGrid = $("#importTable");

	//复制对象内容获取
	var budgetId = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetId").val();
	var budgetName = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetName").val();
	var parentName = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"parentName").val();
	var parentId = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"parentId").val();

	//重新获取行数
	var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");

	//新增行
	objGrid.yxeditgrid("addRow",tbRowNum);
	objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum,"budgetName").val(budgetName);
	objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum,"budgetId").val(budgetId);
	objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum,"parentName").val(parentName);
	objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum,"parentId").val(parentId);
}

//根据费用类型id查询
function findBudgetRowNum(thisCostType){
	var rtVal = -1;
	//缓存从表对象
	var objGrid = $("#importTable");
	//重新获取行数
	var cmps = objGrid.yxeditgrid("getCmpByCol", "budgetId");
	cmps.each(function(i,n){
		if(thisCostType == this.value){
			rtVal = $(this).data("rowNum");
		}
	});

	return rtVal;
}

//初始化费用模板id
function initBudgetIds(){
	//缓存从表对象
	var objGrid = $("#importTable");
	var costTypeArr = objGrid.yxeditgrid("getCmpByCol", "budgetId");

	//当前存在费用类型数组
	var costTypeIdArr = [];
	//当前存在费用类型字符串
	var costTypeIds = '';

	if(costTypeArr.length > 0){
		//缓存当前存在费用类型
		costTypeArr.each(function(i,n){
			//判断是否已删除
			if($("#esmbudget[budgets]_" + i +"_isDelTag").length == 0){
				costTypeIdArr.push(this.value);
			}
		});
		//构建当前存在费用类型id串
		costTypeIds = costTypeIdArr.toString();
	}

	//费用类型隐藏赋值
	$("#costTypeIds").val(costTypeIds);
}

//打开保存界面
function openSavePage(){
	var costTypeIds = $("#costTypeIds").val();

	if(costTypeIds.length == "0"){
		alert('请至少选择一项费用类型');
	}else{
		$("#tempTemplateName").val($("#templateName").val());
		$('#templateInfo').dialog({
		    title: '保存模板',
		    width: 400,
		    height: 200,
   			modal: true
		}).dialog('open');
	}
}

//保存模板
function saveTemplate(){
	//获取当前有的费用类型
	var objGrid = $("#importTable");
	var contentObjs = objGrid.yxeditgrid("getCmpByCol", "budgetName");
	var contentIdObjs = objGrid.yxeditgrid("getCmpByCol", "budgetId");

	var contentArr = [];
	var contentIdArr = [];
	//缓存当前存在费用类型
	contentIdObjs.each(function(i,n){
		contentIdArr.push(this.value);
	});
	contentObjs.each(function(i,n){
		contentArr.push(this.value);
	});

	if(contentIdArr.length == 0){
		alert('没有任务选中值，请至少选择一项费用类型');
	}else{
		var templateName= $("#tempTemplateName").val();
		var content = contentArr.toString();
		var contentId = contentIdArr.toString();
	    if(templateName){
			$.ajax({
			    type: "POST",
			    url: "?model=finance_expense_customtemplate&action=ajaxSave",
			    data : {"templateName" : templateName , "content" : content , "contentId" : contentId },
			    async: false,
			    success: function(data){
			   		if(data != ""){
			   			alert('模板保存成功');
						$("#templateName").val(templateName).yxcombogrid_expensemodel('reload');
						$("#templateId").val(data);
						$('#templateInfo').dialog('close');
			   	    }else{
						alert('模板保存失败');
						$('#templateInfo').dialog('close');
			   	    }
				}
			});
	    }else{
	    	if(strTrim(templateName) == ""){
				alert('请输入报销模板名称');
	    	}
	    }
	}
}