$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
            if ($("#planDateClose").val() < $("#planDateStart").val()) {
	        	alert('启动时间不能大于完成时间');
	            return false;
	        } else if($('#officeId').val() == ""){
				alert('所属办事处选择不正确，请重新选择');
	            return false;
	        }else{
	            return true;
	        }
        }
    });

     /**验证项目名称 **/
     $("#projectName").formValidator({
         onshow:"请输入项目名称",
         onfocus:"项目名称至少2个字符，最多50个字符",
         oncorrect:"您输入的项目名称有效"
     }).inputValidator({
         min:2,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"项目名称两边不能有空符号"
         },
         onerror:"您输入的名称不合法，请重新输入"
     });

     /**验证项目编号 **/
     $("#projectCode").formValidator({
         onshow:"请输入项目编号",
         onfocus:"项目编号至少2个字符，最多50个字符",
         oncorrect:"您输入的项目编号有效"
     }).inputValidator({
         min:2,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"项目编号两边不能有空符号"
         },
         onerror:"您输入的编号不合法，请重新输入"
     });

     $("#officeName").formValidator({
         onshow:"请选择所属办事处",
         onfocus:"请选择所属办事处"
     }).inputValidator({
         min:2,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"项目编号两边不能有空符号"
         },
         onerror:"所属办事处无效，请重新选择"
     });

     $("#planDateStart").formValidator({
	    onshow: "请选择计划启动时间",
	    onfocus: "请选择日期",
	    oncorrect: "你输入的日期合法"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
	}); //.defaultPassed();

	$("#planDateClose").formValidator({
	    onshow: "请选择计划完成时间",
	    onfocus: "请选择日期",
	    oncorrect: "你输入的日期合法"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
    }).compareValidator({
		desid : "planDateStart",
		operateor : ">=",
		onerror : "计划完成日期不能小于计划开始日期"
	}); // .defaultPassed();
});