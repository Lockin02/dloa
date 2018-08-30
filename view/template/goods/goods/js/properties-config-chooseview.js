/**
 * 选择选项目
 */
function checkedItem(el, elId, ckType) {

}

$(function(){
	var thisVal ;

	//单选框值隐藏
	$("input:radio", this).each(function() {
		if($(this).attr('checked') == true){
        	$(this).hide();
		}else{
			thisVal = $(this).val();
			$(this).parent().hide();
			$("#span_" + thisVal).hide();
		}
    });

    //复选框值隐藏
    $("input:checkbox", this).each(function() {
		if($(this).attr('checked') == true){
        	$(this).hide();
		}else{
			thisVal = $(this).val();
			$(this).parent().hide();
			$("#span_" + thisVal).hide();
		}
    });

	//文本框只读
    $("input:text", this).each(function() {
        $(this).attr("readonly",true)
    });

	var areaHtml;
	//文本框只读
    $("textarea", this).each(function() {
    	areaHtml = "<pre>" + this.value +"</pre>";
        $(this).after(areaHtml).remove();
    });

    //渲染完成后显示
    $("#settingInfo").show();

    $("input[id^='license']").each(function(){
    	//隐藏原按钮
		$(this).hide();
		var licenseId = $(this).attr('licenseid');
		if(licenseId != '0' && licenseId != "" && licenseId != undefined && licenseId != 'undefined'){
			var afterHtml = "<input type='button' class='txt_btn_a' value='加密配置' onclick='showLicense(\""+ licenseId + "\")'/>";
			$(this).after(afterHtml);
		}
    });
});

//license查看方法
function showLicense(thisVal){
	if( thisVal == 0 || thisVal=='' || thisVal=='undefined' ){
		alert('该物料无加密信息！');
		return false;
	}
	var url = "?model=yxlicense_license_tempKey&action=toViewRecord" + "&id=" + thisVal;
	var sheight = screen.height-200;
	var swidth = screen.width-70;
	var winoption ="dialogHeight:"+sheight+"px,dialogWidth:"+ swidth +"px,scrollbars=yes, resizable=yes";
	window.open(url, '',winoption);
}


/**
 * 查看明细
 */
function toViewTip(id) {
	showThickboxWin("?model=goods_goods_properties&action=toViewTip&id=" + id
        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=650");
}

/**
 * 查看明细
 */
function toViewProductInfo(productId) {
    showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
    + productId
    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
}