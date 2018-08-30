$(function() {
	//��ʼ��
	initList(0);
	//���ض�����ť
    var bt = $('#toolBackTop');
    $(window).scroll(function() {
        var st = $(window).scrollTop();
        if(st > 30){                        
        	bt.show();                        
        }else{
            bt.hide();
        }
    });
 });

//��ѯ���������ֵ��Ʒ�б�
function initList(type){
	var year = $("#year").val();
	var month = $("#month").val();
	var day = $("#day").val();
	//��ѯʱ,ʹ��������֤
	if(type != '0'){
		if(year != '' && !isNum(year)){
			alert("(����)������������!");
			$("#year").focus();
			return false;
		}
		if(month != '' && !isNum(month)){
			alert("(����)������������!");
			$("#month").focus();
			return false;
		}
		if(day != '' && !isNum(day)){
			alert("(����)������������!");
			$("#day").focus();
			return false;
		}
	}
    showLoading(); // ��ʾ����ͼ��
	var objGrid = $("#assetCardGrid");
    //��������
    $.ajax({
        url : '?model=asset_assetcard_assetcard&action=searchCleanLowValueGoods',
        data : {
        	dateType : $("#dateType").val(),
        	year : year,
        	month : month,
        	day : day,
        	assetCode : $("#assetCode").val(),
        	assetName : $("#assetName").val()
        },
        type : 'POST',
        async : false,
        success : function(data){
            if(objGrid.html() != ""){
                objGrid.empty();
            }
            objGrid.html(data);
            hideLoading(); // ���ؼ���ͼ��
        }
    });
}

//��ʾloading
function showLoading(){
	$("#loading").show();
}

//����loading
function hideLoading(){
	$("#loading").hide();
}

//ȫѡ
function checkAll(){
	$("input[type='checkbox']").each(function(){
		$(this).attr("checked",$("#checkAll").attr("checked"));
	});
}

//�����������
function emptySearch(){
	$("#year").val('');
	$("#month").val('');
	$("#day").val('');
	$("#assetCode").val('');
	$("#assetName").val('');
}

//����ѡ�ĵ�ֵ����Ʒ
function cleanCard(){
	//����id����
	var ids = [];
	$("input[type='checkbox']:checked").each(function(){
		id = $(this).val();
		if(id != ""){
			ids.push(id);
		}
	});
	if(ids.length > 0){
		//��id����ת�����Զ��Ÿ������ַ���
		ids = ids.join(",");
		if (confirm('ȷ��Ҫ������ѡ�ĵ�ֵ����Ʒ��')){
			$.ajax({
				type : 'POST',
				url : '?model=asset_assetcard_assetcard&action=ajaxCleanLowValueGoods',
				data : {
					id : ids
				},
				success : function(data) {
					if (data == 1) {
						alert("����ɹ�");
					    //�����б�
						initList();
					} else {
						alert("����ʧ��");
					}
				}
			});
		}
	}else{
		alert("������ѡ��һ������");
	}
}

//�鿴�ʲ���Ƭ��ϸ
function searchDetail(assetId){
	window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
			+ assetId
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
}