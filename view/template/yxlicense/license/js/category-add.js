$(document).ready(function() {
	$("#categoryItem").yxeditgrid({
		objName : 'category[items]',
		isAddOneRow : true,
		title : '�ӱ���Ϣ',
		colModel : [{
					name : 'itemName',
					tclass : 'txt',
					display : '��ϸ����',
					sortable : true,
					validation : {
						required : true
					}
				}, {
					name : 'groupName',
					display : '������',
					tclass : 'txt',
					sortable : true
				}, {
					name : 'appendShow',
					display : '��չ��ʾ',
					width : 300,
					sortable : true
				}]
	});
	validate({
		"categoryName" : {
			required : true
		},
		"lineFeed" : {
			required : true
		}
	});
	
	//��ѡ������Ϊ����ʱ��ʾ��ѡ���ı�ѡ���
	$("#showType").change(function(){
		if($("#showType").find("option:selected").val()=='2'){
			$("#typeShow").show();
		}
		else
			$("#typeShow").hide();
	});
	
	//ѡ�����չ�ַ�ʽ-��ѡ/�ı�
	$("#type").change(function(){
		if($("#type").find("option:selected").val()=='1'){
			$("#type").val('1');
		}
		else
			$("#type").val('2');
	});
});

function changeShowType(){
	var showType = $("#showType").val();
	var itemTableObj = $("#categoryItem");
	if(showType == 2){
		itemTableObj.yxeditgrid("getCmpByCol","groupName").addClass('validate[required]');
	}else{
		itemTableObj.yxeditgrid("getCmpByCol","groupName").removeClass('validate[required]');
	}
}