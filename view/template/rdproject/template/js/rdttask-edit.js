$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
			return false;
        },
        onsuccess : function(){
        	return true;
        }
    });

	$("#name").formValidator({
		onshow : "请输入任务名称",
		onfocus : "任务名称至少2个字符,最多50个字符",
		oncorrect : "您输入的任务名称可用"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "任务名称两边不能有空符号"
		},
		onerror : "你输入的任务名称,请确认"
	});

	$("#appraiseWorkload").formValidator({
		empty:true,
    	onshow:"可为空",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		type:"value",
		onerrormin:"你输入的值必须大于等于0.1",
		onerror:"请输入正确的值"
	});//.defaultPassed();

	$("#planDuration").formValidator({
		empty:true,
    	onshow:"可为空",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		type:"value",
		onerrormin:"你输入的值必须大于等于0.1",
		onerror:"请输入正确的值"
	});//.defaultPassed();
})