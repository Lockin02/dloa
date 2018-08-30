$(function() {
	// 单选合同
	$("#contractName").yxcombogrid_contract({
				hiddenId : 'contractId',
				gridOptions : {
					showcheckbox : false,
					param:{"contStatus":"1"},
					event : {
						'row_dblclick' : function(e, row, data) {
							$.post("?model=purchase_plan_basic&action=addList",{
								contId:data.id,
								contNumber:data.contNumber
							},function(data){
								$("#equList").html("");
								$("#equList").append(data);
//								$("input.amount").each(function(){
//						   		if($(this).val()<1){
////						   			alert("请输入正确的数量,不能为空或者小于1");
//									$(this).attr("value"," ");
//									$(this).focus();
//						   		}
// 								  });
							});
						},
						'row_click' : function() {
							// alert(123)
						},
						'row_rclick' : function() {
							// alert(222)
						}
					}
				}
			});
});