$(function(){
	$("select.myExecuteTask").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		var checkValue="&skey="+$("#check"+hidevalue).val();
		switch(selvalue){
			//�鿴
			case "view": parent.location='index1.php?model=purchase_task_basic&action=read&id='+hidevalue+'&contNumber='+hidevale2+checkValue;break;
			//�����ɹ�����
			case "export": location='index1.php?model=purchase_task_basic&action=exportTask&id='+hidevalue;break;
			//���
			case "finish":
				if(confirm('ȷ�������')){location='index1.php?model=purchase_task_basic&action=end&id='+hidevalue};
				break;
			//�ر�
			case "close":
				 $.ajax({//�ж��Ƿ��ѽ��п���
				    type: "POST",
				    url: "?model=purchase_task_basic&action=isSubClose",
				    data: { id:hidevalue
				    	},
				    async: false,
				    success: function(msg){
				   	   if(msg==1){
				   	   	parent.location='index1.php?model=purchase_task_basic&action=toClose&id='+hidevalue;
				   	   }else{
				   	   		alert("�òɹ��������ύ�ر�����");
				   	   }
					}
				});
				break;

			//������
			case "feedback": parent.location='index1.php?model=purchase_task_basic&action=toFeedBack&id='+hidevalue+checkValue;break;
			case "":break;
			default : break;
		}
		$(this).val("");
	})
	/**
	 * ��ʼ��ʱʱ�������
	 */
	$.each($("table[id^='table']"),function(){
		$(this).hide();
	})

	/**
	 * �󶨵���������ͼƬ
	 */
	var thistitle;
	$("img[id^='changeTab']").bind("click",function(){
		var thistitle = $(this).attr("title");
		if($(this).attr("src") == "images/collapsed.gif"){
			$("#table" + thistitle).show();
			$("#inputDiv" + thistitle).hide();
			$(this).attr("src","images/expanded.gif");
		}else{
			$("#table" + thistitle).hide();
			$("#inputDiv" + thistitle).show();
			$(this).attr("src","images/collapsed.gif");
		}
	})

	/**
	 * ������������ͼƬ
	 */
	var thissrc ;
	$("#changeImage").bind("click",function(){
		thissrc = $(this).attr("src");
		if($(this).attr("src")=="images/collapsed.gif"){
			$(this).attr("src","images/expanded.gif");
		}else{
			$(this).attr("src","images/collapsed.gif");
		}
		$.each($("img[id^='changeTab']"),function(i,n){
			if($(this).attr("src")==thissrc)
				$(this).trigger("click");
		})
	})

	/**
	 * ��DIV
	 */
	var imgId ;
	$("div[id^='inputDiv']").bind("click",function(){
		imgId = $(this).attr("title");
		$("#changeTab" + imgId).trigger("click");
		$(this).hide();
	})
});