$().ready(function(){
	$.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        	return false;
        },
        onsuccess: function() {
        }
    });


    $("#effortRate").formValidator({
    	onshow:"����������ǰ�����",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		max:100,
		type:"value",
		onerrormin:"�������ֵ������ڵ���0.1",
		onerror:"������0.1��100֮�䣬����������"
	});//.defaultPassed();
})
