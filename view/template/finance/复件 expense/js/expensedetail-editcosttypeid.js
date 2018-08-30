//判断所选类型是否可行
function checkCanSel(thisObj){
	var childrenObjs = $("#newCostTypeID").find("option[parentId='"+ thisObj.value +"']");
	if(childrenObjs.length > 0){
		alert('该项存在子类型，不能进行选择');
		var costTypeId = $("#CostTypeID").val();
		$("#newCostTypeID").val(costTypeId);
		return false;
	}
	return true;
}


//保存发票类型变更
function saveCostType(){
	//改动前的类型
	var costTypeId = $("#CostTypeID").val();
	var mainType = $("#mainType").val();
	var mainTypeName = $("#mainTypeName").val();
	//新类型
	var newCostObj = $("#newCostTypeID");
	var newCostTypeId = newCostObj.val();
	var newCostType = newCostObj.find("option:selected").text();
	var newMainTypeId = newCostObj.find("option:selected").attr("parentId");
	var newMainType = newCostObj.find("option:selected").attr("parentName");

	//发票号码
	var BillNo = $("#BillNo").val();

	if(costTypeId == newCostTypeId){
		alert('没有变更对应类型');
		return false;
	}

	//当前存在费用类型
	var orgCostTypes = $("#orgCostTypes").val();
	var orgCostTypesArr = orgCostTypes.split(',');

	//判断新值是否已存在
	if(jQuery.inArray(newCostTypeId,orgCostTypesArr) != '-1'){
		alert('费用类型中已存在【'+newCostType+'】,不能修改成该项信息');
		return false;
	}

	//当前存在父费用类型
	var mainTypes = $("#mainTypes").val();
	var mainTypesArr = mainTypes.split(',');

	//判断是否要刷新
	var isReload = 0;

	//判断新值是否已存在
	if(jQuery.inArray(newMainTypeId,mainTypesArr) == '-1'){
		isReload = 1;
	}

	//异步修改
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expensedetail&action=editCostTypeID",
	    data: {
	    	"costTypeId" : costTypeId ,
	    	'newCostTypeId' : newCostTypeId ,
	    	'BillNo' : BillNo,
	    	'newMainTypeId' : newMainTypeId,
	    	'newMainType' : newMainType
	    },
	    async: false,
	    success: function(data){
	   		if(data == "1"){
				alert('更新成功');
				parent.show_pageDetail();
				parent.tb_remove();
	   	    }else{
				alert('修改失败');
	   	    }
		}
	});
}