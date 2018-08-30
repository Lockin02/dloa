$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
	    	return true;
        }
    });

     /**验证项目名称 **/
     $("#managerName").formValidator({
         onshow:"请选择项目经理",
         onfocus:"请选择",
         oncorrect:"选择有效"
     }).inputValidator({
         min:2,
         max:50,
         onerror:"请选择项目经理"
     });
});