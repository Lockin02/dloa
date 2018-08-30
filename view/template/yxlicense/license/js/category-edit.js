var show_page = function(page) {
	$("#categoryItem").yxgrid("reload");
};
$(document).ready(function() {
	var showType = $("#showTypeHidden").val();
	if(showType == 1){
		$("#showType").find("option[text='�б���ʾ']").attr("selected",true);
	}else if(showType == 2){
		$("#showType").find("option[text='������ʾ']").attr("selected",true);
		$("#typeShow").show();
	}else if(showType == 3){
		$("#showType").find("option[text='����ʾ']").attr("selected",true);
	}else if(showType == 4){
		$("#showType").find("option[text='ֱ������']").attr("selected",true);
	}else if(showType == 5){
		$("#showType").find("option[text='��д���']").attr("selected",true);
	}

	var type = $("#typeHidden").val();
	if(type == 1){
		$("#type").find("option[text='��ѡ']").attr("selected",true);
	}else if(type == 2){
		$("#type").find("option[text='�ı�']").attr("selected",true);
	}
	var categoryId = $("#id").val();
	$("#categoryItem").yxeditgrid( {
		objName : 'category[items]',
		url : '?model=yxlicense_license_categoryitem&action=listJson',
		title : '�ӱ���Ϣ',
		param : {
			categoryId : categoryId,
			'dir' : 'ASC'
		},
		colModel : [{
			name : 'id',
			tclass : 'txt',
			display : 'id',
			sortable : true,
			type : "hidden"
		},{
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
	
	if($("#isHideTitleValue").val() == '1') {
		$("#isHideTitle").attr("checked","checked");
	};
	
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

//����
function changeShowType(){
	var showType = $("#showType").val();
	var itemTableObj = $("#categoryItem");
	if(showType == 2){
		itemTableObj.yxeditgrid("getCmpByCol","groupName").addClass('validate[required]');
	}else{
		itemTableObj.yxeditgrid("getCmpByCol","groupName").removeClass('validate[required]');
	}
}