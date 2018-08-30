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
    	onshow:"请输入任务当前完成率",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		max:100,
		type:"value",
		onerrormin:"你输入的值必须大于等于0.1",
		onerror:"必须在0.1至100之间，请重新输入"
	});//.defaultPassed();
})
