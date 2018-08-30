$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
		onsuccess : function(msg) {
			var ifSubmit = true;
			var temp = $('#rowNum').val();
			var noRows = $('#noRows').val();
			if( noRows == 1 ){
				alert( "未填写发货设备详细信息，不允许下达！" )
				ifSubmit = false;
			}
			for(var i=1;i<=temp;i++){
				if( $('#number' + i).val() == '' ){
					alert( '设备数量不完整，请确认输入！' );
					return false;
				}
			}
			return ifSubmit;
		}

    });
/** 仓库 * */
$("#issuedDeptName").formValidator({
	onshow : "请输入下单部门",
	onfocus : "下单部门至少2个字符，最多50个字符",
	oncorrect : "您输入的下单部门有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "下单部门两边不能为空"
	},
	onerror : "您输入的下单部门不合法，请重新输入"
});
/** 仓库 * */
$("#execDeptName").formValidator({
	onshow : "请输入执行部门",
	onfocus : "执行部门至少2个字符，最多50个字符",
	oncorrect : "您输入的执行部门有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "执行部门两边不能为空"
	},
	onerror : "您输入的执行部门不合法，请重新输入"
});
/** 计划发货日期 * */
 $("#referDate").formValidator({
	    onshow: "请选择交货日期",
	    onfocus: "请选择日期",
	    oncorrect: "你输入的日期合法"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
	});

});
