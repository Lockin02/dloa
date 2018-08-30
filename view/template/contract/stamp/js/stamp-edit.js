
//验证统一批次是否存在同一个盖章类型
function checkRepeat(){
	var id = $("#id").val();
	var contractId = $("#contractId").val();
	var contractType = $("#contractType").val();
	var stampType = $("#stampType").val();
	var batchNo = $("#batchNo").val();

	var thisRs = true;
	$.ajax({
	    type: "POST",
	    url: "?model=contract_stamp_stamp&action=checkRepeat",
	    data: {
    		"noId" : id ,
    		"contractId" : contractId,
    		"contractType" : contractType,
    		"stampType" : stampType,
    		"batchNo" : batchNo
		},
	    async: false,
	    success: function(data){
	   		if(data == 1){
	   	   		alert('已存在同批次相同的盖章申请，不能使用此盖章类型');
	   	   		thisRs = false;
			}
		}
	});

	return thisRs;
}