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

     /**��֤��Ŀ���� **/
     $("#managerName").formValidator({
         onshow:"��ѡ����Ŀ����",
         onfocus:"��ѡ��",
         oncorrect:"ѡ����Ч"
     }).inputValidator({
         min:2,
         max:50,
         onerror:"��ѡ����Ŀ����"
     });
});