$(function() {
	$("#batchNumb").bind("change",function(){
		if($.trim($(this).val())!=""){
			$("#historyApply").html("");
			var batchNumb=$.trim($(this).val());
			$.post("?model=purchase_plan_equipment&action=getBatchEqu", {
				batchNumb : batchNumb
			}, function(data) {
				if(data!=0){
					$("#historyApply").append(data);
				}
			});
		}
	});
		$("#batchNumb").yxcombogrid_batchnumb({
    	hiddenId : 'batchNumb',
    	nameCol:'batchNumb',
    	width:450,
    	height:300,
    	isFocusoutCheck : false,
		gridOptions : {
			title:'���κ�',
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#historyApply").html("");
					$.post("?model=purchase_plan_equipment&action=getBatchEqu", {
						batchNumb : data.batchNumb
					}, function(data) {
						if(data!=0){
							$("#historyApply").append(data);
						}
					});

			  	}
			}
		}
    });
});
//ȥ���ո�
function setBatchNumb(obj){
	var batchNumb=$.trim($(obj).val());
	$(obj).val(batchNumb);
}