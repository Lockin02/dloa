$().ready(function(){
	$.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
        }
    });

    $("#memberName").formValidator({
        onshow: "长度在1~20之间,中文字符10个，英文字符20个。",
        onfocus: "长度在1~20之间,中文字符10个，英文字符20个。",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
		max: 20,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "简称两边不能有空符号"
        },
        onerror: "不能为空"
    }); //.defaultPassed();

//    $("#phone").formValidator({
//    	empty:true,
//    	onshow:"可以为空",
//    	onfocus:"请输入正确的电话号码",
//    	oncorrect:"OK",
//    	onempty:"可以为空"
//    }).regexValidator({
//    	regexp:"^[[0-9]{3}-|\[0-9]{4}-]?([0-9]{8}|[0-9]{7})?$",
//    	onerror:"你输入的电话号码格式不正确"
//	});
//
//    $("#mobile").formValidator({
//    	empty:true,
//    	onshow:"可以为空",
//    	onfocus:"请输入正确的手机号码",
//    	oncorrect:"OK",
//    	onempty:"可以为空"
//    }).inputValidator({
//    	min:11
//    	,max:11,
//    	onerror:"手机号码必须是11位的,请确认"
//    }).regexValidator({
//    	regexp:"mobile",
//    	datatype:"enum",
//    	onerror:"你输入的手机号码格式不正确"
//	});
//
//    $("#email").formValidator({
//    	empty:true,
//    	onshow:"可为空",
//    	onfocus:"请输入正确的Email地址",
//    	oncorrect:"OK",
//    	onempty:"可以为空"
//	}).inputValidator({
//		min:6,
//		max:100,
//		onerror:"你输入的邮箱长度非法,请确认"
//	}).regexValidator({
//		regexp:"^([\\w-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([\\w-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?)$",
//		onerror:"你输入的邮箱格式不正确"
//	});
})