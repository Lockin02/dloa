/**
 * ѡ��ѡ��Ŀ
 */
function checkedItem(el, elId, ckType) {

}

$(function(){
	var thisVal ;

	//��ѡ��ֵ����
	$("input:radio", this).each(function() {
		if($(this).attr('checked') == true){
        	$(this).hide();
		}else{
			thisVal = $(this).val();
			$(this).parent().hide();
			$("#span_" + thisVal).hide();
		}
    });

    //��ѡ��ֵ����
    $("input:checkbox", this).each(function() {
		if($(this).attr('checked') == true){
        	$(this).hide();
		}else{
			thisVal = $(this).val();
			$(this).parent().hide();
			$("#span_" + thisVal).hide();
		}
    });

	//�ı���ֻ��
    $("input:text", this).each(function() {
        $(this).attr("readonly",true)
    });

	var areaHtml;
	//�ı���ֻ��
    $("textarea", this).each(function() {
    	areaHtml = "<pre>" + this.value +"</pre>";
        $(this).after(areaHtml).remove();
    });

    //��Ⱦ��ɺ���ʾ
    $("#settingInfo").show();

    $("input[id^='license']").each(function(){
    	//����ԭ��ť
		$(this).hide();
		var licenseId = $(this).attr('licenseid');
		if(licenseId != '0' && licenseId != "" && licenseId != undefined && licenseId != 'undefined'){
			var afterHtml = "<input type='button' class='txt_btn_a' value='��������' onclick='showLicense(\""+ licenseId + "\")'/>";
			$(this).after(afterHtml);
		}
    });
});

//license�鿴����
function showLicense(thisVal){
	if( thisVal == 0 || thisVal=='' || thisVal=='undefined' ){
		alert('�������޼�����Ϣ��');
		return false;
	}
	var url = "?model=yxlicense_license_tempKey&action=toViewRecord" + "&id=" + thisVal;
	var sheight = screen.height-200;
	var swidth = screen.width-70;
	var winoption ="dialogHeight:"+sheight+"px,dialogWidth:"+ swidth +"px,scrollbars=yes, resizable=yes";
	window.open(url, '',winoption);
}


/**
 * �鿴��ϸ
 */
function toViewTip(id) {
	showThickboxWin("?model=goods_goods_properties&action=toViewTip&id=" + id
        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
}

/**
 * �鿴��ϸ
 */
function toViewProductInfo(productId) {
    showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
    + productId
    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
}