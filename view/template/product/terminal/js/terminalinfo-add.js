$(function() {
     validate({
        "terminalName": {
            required: true
        },
        "productName": {
            required: true
        }
    });
    $("#materialsName").yxcombogrid_product({
        showcheckbox : true,
        nameCol: 'productName',
        hiddenId: 'materialsId',
        gridOptions: {
            event: {
                'row_click': function(e, row, data) {

                }
            }
        }
    });
    versionStatusArr = getData('BBZT');
    addDataToSelect(versionStatusArr, 'versionStatus');
    $("#versionStatus").val($("#versionStatus").attr("val"));
    $("#productName").yxcombogrid_terminalProduct({
        nameCol: 'productName',
        hiddenId: 'productId',
        gridOptions: {
            event: {
                'after_row_check': function(e, row, data) {
                    createInfoTypeRadio();
                }
            }
        }
    });

    createInfoTypeRadio();
});

/**
 * 根据多个产品动态创建终端分类单选组
 *
 * @param {}
 *            productIds
 */
var typeValArr = [];
function createInfoTypeRadio() {

    typeValArr = $("#typeIds").val().split(",");
    $("#typeDiv").empty();
    var productIds = $("#productId").val();
    if (productIds) {
        $.ajax({
            url: "?model=product_terminal_terminaltype&action=listJson&productIds="
                    + productIds,
            success: function(data) {
                data = eval("(" + data + ")");
                for (var i = 0; i < data.length; i++) {
                    var d = data[i];
                    if (!$("#group" + d.productId)[0]) {
                        var group = $("<div id='group" + d.productId
                                + "'></div>").append(d.productName + "：")
                                .appendTo($("#typeDiv"));
                    }
                    $("<input type='radio' class='typeRadio' name='radio"
                            + d.productId + "' value='" + d.id + "'/>")
                            .appendTo($("#group" + d.productId))
                            .after(d.typeName + "&nbsp;").attr("typeName",
                            d.typeName).click(function() {
                        var typeIdArr = [], typeNameArr = [];
                        typeValArr = [];
                        $(".typeRadio").each(function() {
                            if ($(this).attr("checked")) {
                                var typeName = $(this).attr("typeName")
                                typeIdArr.push($(this).val());
                                typeNameArr.push(typeName);
                                typeValArr.push($(this).val());
                            }
                            $("#typeIds").val(typeIdArr.toString());
                            $("#typeNames").val(typeNameArr.toString());
                        });

                    });
                    $(".typeRadio").each(function() {
                        var v = $(this).val();
                        if (typeValArr.indexOf(v) != -1) {
                            $(this).attr("checked", "checked");
                        }
                    })
                }
            }
        });
    }
}

/********************add chenrf 20130530**************************/

var deviceData=getData('SBLX');
var osData=getData('CZXT');
var netWorkData=getData('ZCWL');
/**
 * 生成下拉框
 * @param id
 * @param data
 * @returns {String}
 */
function setOption(id,data){
	if(data.length<=0||!$.isArray(data))
		return '';
	var option = $("<option value=''>...请选择...</option>");
	option.appendTo($("#"+id));
	for(i=0;i<data.length;i++){
		var option = $("<option></option>");
		option.val(data[i].dataCode);
		option.text(data[i].dataName);
		option.appendTo($("#"+id));
	}
}
/**
 * 下拉表单发送变化时赋值给隐藏值
 */
function changeOption($obj,$hiObj){
	$hiObj.val($obj.find("option:selected").text());
}
$(function(){
	setOption('deviceType',deviceData);
	setOption('os',osData);
	setOption('supportNetwork',netWorkData);
	$("#deviceType").change(function(){
		changeOption($(this),$("#deviceTypeHidden"));
	});
	$("#os").change(function(){
		changeOption($(this),$("#osHidden"));
	});
	$("#supportNetwork").change(function(){
		changeOption($(this),$("#supportNetworkHidden"));
	});
	$("#formalVersion").focus(function(){
		$("#point").css('display','');
	});
	$("#formalVersion").blur(function(){
		$("#point").css('display','none');
	});
	/*$("#formalVersion").mouseover(function(){
		$.validationEngine.buildPrompt(this,"请按照选择的所属产品顺序，正确填写版本号",null);
	});*/
});




