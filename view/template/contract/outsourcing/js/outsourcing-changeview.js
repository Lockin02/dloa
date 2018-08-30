$(function(){
	var thisObj ;
	$.ajax({
	    type: "POST",
	    url: "?model=common_changeLog&action=getChangeInformation",
	    data: {"tempId" : $("#pid").val() , "logObj" : "outsourcing"},
	    async: false,
	    success: function(data){
	   		if(data){
	   			data = eval("(" + data + ")");
				for(var i = 0; i < data.length ; i++){
					thisObj = $("#" + data[i]['changeField']);
						if(thisObj.attr("class") == "formatMoney"){
							thisObj.html( moneyFormat2(data[i]['oldValue']) + " => " + moneyFormat2(data[i]['newValue']));
						}
						else{
							thisObj.html(data[i]['oldValue'] + ' => ' +  data[i]['newValue']);
						}
						thisObj.attr('style','color:red');
				}
	   		}
		}
	});

	if($("#isNeedStamp").val() == 'ÊÇ' && $("#isStamp").val() == '·ñ'){
		$("#isNeedStampView").show();
	}

	if($("#isNeedRestamp").val() == 'ÊÇ'){
		$("#isNeedRestampView").show();
	}

});