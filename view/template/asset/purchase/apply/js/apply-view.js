// 添加需求审批记录表的方法
function xq_approval(){
    var itemType = $("#xq_itemType").val();
    var pid = $("#xq_pid").val();
    var relDocId = $("#xq_relDocId").val();
    var isChange = $("#isChange").val();
    var gdbtable = getQueryString('gdbtable');
    if ($("#isChange").length == 0) {//是否带出变更审批意见
        var isChange = 0;
    } else {
        var isChange = $("#isChange").val();
    }
    if ($("#appFormName").length == 0) {
        var appFormName = "";
    } else {
        var appFormName = $("#appFormName").val();
    }
    if ($("#isPrint").length == 0) {//是否带出变更审批意见
        var isPrint = 0;
    } else {
        var isPrint = $("#isPrint").val();
    }

    $.post("?model=common_approvalView&action=getXqApproval", {
        pid : pid,
        relDocId : relDocId,
        itemtype : itemType,
        isChange : isChange,
        gdbtable : gdbtable,
        isPrint : isPrint
    }, function(data) {
        if (data == 0) { //没有审批意见时，赋值为空行
            var datahtml = "<tr><td></td></tr>";
        }
        if (data != 0) {
            if(isPrint == "1"){
                var $html = $('<table width="100%"  class="form_in_table" id="approvalTable">'
                    + '<thead>'
                    + '<tr  > '
                    + '<td width="100%" colspan="6" class="form_header"><B>'
                    + appFormName
                    + '需求审批记录</B></td>'
                    + '</tr>'
                    + '<tr class="main_tr_header">'
                    + '<th width="12%">步骤名</th>'
                    + '<th width="12%">审批人</th>'
                    + '<th width="18%" nowrap="nowrap">审批日期</th>'
                    + '<th width="10%">审批结果</th>'
                    + '<th>审批意见</th>'
                    + '</tr>' + '</thead>');
            }else{
                var $html = $('<table width="100%"  class="form_in_table" id="approvalTable">'
                    + '<thead>'
                    + '<tr  > '
                    + '<td width="100%" colspan="6" class="form_header"><B>'
                    + appFormName
                    + '需求审批记录</B></td>'
                    + '</tr>'
                    + '<tr class="main_tr_header">'
                    + '<th width="12%">序号</th>'
                    + '<th width="12%">步骤名</th>'
                    + '<th width="12%">审批人</th>'
                    + '<th width="18%" nowrap="nowrap">审批日期</th>'
                    + '<th width="10%">审批结果</th>'
                    + '<th>审批意见</th>'
                    + '</tr>' + '</thead>');
            }
            var $html2 = $('</table>');
            if (data == 0) {
                var $tr = $(datahtml);
            } else {
                var $tr = $(data);
            }
            $html.append($tr);
            $html.append($html2);
            $("#xq_approvalView").append($html);
        }
    });
}

$(document).ready(function() {

	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			applyId : $("#applyId").val()
		},
		colModel : [{
			display : '物料名称',
			name : 'productName',
			type : 'statictext',
			process : function(v,row){
				if(v==''){
					return row.inputProductName;
				}else{
					return v
				}
			}
		}, {
			display : '规格',
			name : 'pattem'
		}, {
			display : '申请数量',
			name : 'applyAmount',
			tclass : 'txtshort'
		}, {
			display : '预计金额',
			name : 'amounts',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '单位',
			name : 'unitName',
			tclass : 'txtshort'
		}, {
			display : '希望交货日期',
			name : 'dateHope',
			type : 'date'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	})

    // 添加需求审批记录
    if(typeof($("#extra_approvalItems").val()) != 'undefined' &&  $("#extra_approvalItems").val() == 'xq'){
        xq_approval();
    }

	// 根据采购类型来判断是否显示部分的字段
//	 alert($("#purchaseType").text());
	if ($("#purchaseType").text() != "计划内 ") {
		$("#hiddenA").hide();
		// $("#hiddenB").hide();
	}

	// 根据采购种类为“研发类”时来显示部分字段（采购分类、重大专项名称、募集资金项目、其它研发项目）
//	 alert($("#purchCategory").text());
	if ($("#purchCategory").text() == "研发类") {
		$("#hiddenC").hide();
	} else {
		$("#hiddenD").hide();
		$("#hiddenE").hide();
	}
	// 判断是否显示关闭按钮
	// alert($("#showBtn").val());
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
	}
});