//保存发票类型变更
function saveBillType(){
	//改动前的类型
	var billTypeId = $("#BillTypeID").val();

	//新类型
	var newBillTypeId = $("#newBillTypeID").val();
	var newBillType = $("#newBillTypeID").find("option:selected").text();

	//发票号码
	var BillNo = $("#BillNo").val();

	if(billTypeId == newBillTypeId){
		alert('没有变更对应类型');
		return false;
	}

	//当前存在发票类型
	var orgBillTypes = $("#orgBillTypes").val();
	var orgBillTypesArr = orgBillTypes.split(',');

	//是否是合并
	var isMerge = 0;

	//判断新值是否已存在
	if(jQuery.inArray(newBillTypeId,orgBillTypesArr) != '-1'){
		if(!confirm('发票信息中已存在【'+newBillType+'】,修改后会将对应发票金额合并，不能重新拆分，确认修改吗？')){
			return false;
		}
		isMerge = 1;
	}

	//异步修改
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expenseinv&action=editBillTypeID",
	    data: {"billTypeId" : billTypeId , 'newBillTypeId' : newBillTypeId , 'BillNo' : BillNo},
	    async: false,
	    success: function(data){
	   		if(data == "1"){
				alert('更新成功');
				parent.show_pageInv(billTypeId,newBillTypeId,newBillType,isMerge);
				parent.tb_remove();
	   	    }else{
				alert('修改失败');
	   	    }
		}
	});
}
