
//��֤ͳһ�����Ƿ����ͬһ����������
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
	   	   		alert('�Ѵ���ͬ������ͬ�ĸ������룬����ʹ�ô˸�������');
	   	   		thisRs = false;
			}
		}
	});

	return thisRs;
}