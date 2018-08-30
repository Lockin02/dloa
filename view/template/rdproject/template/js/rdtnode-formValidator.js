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
/** 验证节点名称 * */
$("#nodeEl").formValidator({
	onshow : "请输入节点名称",
	onfocus : "节点名称至少2个字符，最多50个字符",
	oncorrect : "您输入的节点名称有效"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "节点名称两边不能为空"
	},
	onerror : "您输入的节点名称不合法，请重新输入"
});

});
