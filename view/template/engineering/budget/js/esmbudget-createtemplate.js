$(function() {
	alert('���ã��״�ʹ����������һ������ģ��');
	//��ʾ��������
	initExpense();
});

//�Զ������ѡ���� - ����ѡ��
function initExpense(){
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expense&action=getCostType",
	    async: false,
	    success: function(data){
	   		if(data != ""){
				$("#costTypeInner").html("<div id='costTypeInner2'>" + data + "</div>")
				//��ʱ�������򷽷�
				setTimeout(function(){
					initMasonry();
				},200);
	   	    }else{
				alert('û���ҵ��Զ���ķ�������');
	   	    }
		}
	});
}

//�ٲ�������
function initMasonry(){
	$('#costTypeInner').masonry({
		itemSelector: '.box'
	});
}

//ѡ���������
function setCustomCostType(thisCostType,thisObj){
	if($(thisObj).attr('checked') == true){
		$("#view" + thisCostType).attr('class','blue');
	}else{
		$("#view" + thisCostType).attr('class','');
	}
	//�ж������Ƿ���ڣ�������ɵ�������������
	var trObj = $("input[type='checkbox']:checked");
	var contentArr = [];
	var contentIdArr = [];
	var content = "";
	var contentId = ""
	trObj.each(function(i,n){
		contentArr.push($(this).attr("name"));
		contentIdArr.push(this.value);
	});
	if(contentArr.length > 0){
		content = contentArr.toString();
		contentId = contentIdArr.toString();
	}
	$("#contentView").text(content);
	$("#content").val(content);
	$("#contentId").val(contentId);
}

//���¼���Ⱦ
function CostTypeShowAndHide(thisCostType){
	//���������
	var tblObj = $("table .ct_"+thisCostType + "[isView='1']");
	//������ǰ������״̬������ʾ
	if(tblObj.is(":hidden")){
		tblObj.show();
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//����������Ŀ�鿴
function CostType2View(thisCostType){
	//���������
	var tblObj = $("table .ct_"+thisCostType);
	//������ǰ������״̬������ʾ
	if(tblObj.is(":hidden")){
		tblObj.show();
		tblObj.attr('isView',1);
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		tblObj.attr('isView',0);
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//�򿪱������
function openSavePage(){
	var content = $("#content").val();
	if(content == ""){
		alert('û���κ�ѡ��ֵ��������ѡ��һ���������');
	}else{
		$('#templateInfo').dialog({
		    title: '����ģ��',
		    width: 400,
		    height: 200,
   			modal: true,
		    closed: true
		}).dialog('open');
	}
}

//����ģ��
function saveTemplate(){
	var content = $("#content").val();
	var templateName= $("#templateName").val();
    if(templateName){
    	//ajax����ģ����Ϣ
    	var contentId = $("#contentId").val();

		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_customtemplate&action=ajaxSave",
		    data : {"templateName" : templateName , "content" : content , "contentId" : contentId },
		    async: false,
		    success: function(data){
		   		if(data != ""){
		   			alert('����ɹ�');
		   			location.reload();
		   	    }else{
					alert('����ʧ��');
		   	    }
			}
		});
    }else{
    	if(strTrim(templateName) == ""){
			alert('�����뱨��ģ������');
			$("#templateName").focus();
    	}
    }
}
