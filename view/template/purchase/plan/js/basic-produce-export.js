$(function() {
		$("#batchNumb").yxcombogrid_batchnumb({
    	hiddenId : 'batchNumb',
    	nameCol:'batchNumb',
    	width:450,
    	height:300,
    	isFocusoutCheck : false,
		gridOptions : {
			title:'批次号',
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){}
			}
		}
    });
});


	function checkData(){
		var batchNumb=$("#batchNumb").val();
		if(batchNumb==""){
			alert("请选择批次号");
			return false;
		}
	}