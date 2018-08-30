var versionStatusArr;
var langName = "functionName";
$(function() {
    versionStatusArr = getDataObj("BBZT");
    var data = getData("BBZT");
    addDataToSelect(data, "versionSelect");

    // 搜索处理
    $("#searchBt").click(function() {
        langName = $("#lang").val();
        if ($('#isView').val() == 1) {
            searchPrdouctTerminal();
        } else {
            alert('你没此权限，请开启权限后再作些操作!');
        }
    });
    $.ajax({
        url: "?model=product_terminal_product&action=listJson",
        success: function(data) {
            data = eval("(" + data + ")");
            for (var i = 0; i < data.length; i++) {
                var p = data[i];
                var $option = $("<option>" + p.productName
                        + "</option>").val(p.id);
                $("#productSelect").append($option);
            }
        }
    });
    $("#productSelect").change(function() {
        $("#versionSelect").val("");
        $("#infoName").val("");
        $("#functionName").val("");
        if ($('#isView').val() == 1) {
            searchPrdouctTerminal();
        } else {
            alert('你没此权限，请开启权限后再作些操作!');
        }
    });

    $(document).click(function() {
        blurTd();
    });

    $("#versionView").change(function() {
        $("#versionSelect").val("");
        $("#infoName").val("");
        $("#functionName").val("");
        if ($('#isView').val() == 1) {
            searchPrdouctTerminal();
        } else {
            alert('你没此权限，请开启权限后再作些操作!');
        }
    });

    versionData();
});
 //版本数据
function versionData(){
	$("#versionView").empty();
	$optionInit = $("<option>" + "即时版本"
	            + "</option>").val(0);
	$("#versionView").append($optionInit);
	$.ajax({
	        url: "?model=product_terminal_terminalfunction&action=versionJson",
	        success: function(data) {
	            data = eval("(" + data + ")");
	            for (var i = 0; i < data.length; i++) {
	                var p = data[i];
	                var $option = $("<option>" + p.version
	                        + "</option>").val(p.version);
	                $("#versionView").append($option);
	            }
	        }
	    });
}
function searchPrdouctTerminal() {
    var productId = $("#productSelect").val();
    if (productId) {
        var version = $("#versionSelect").val();
        var infoName = $("#infoName").val();
        var functionName = $("#functionName").val();
        $("#detail").empty();
        var $table = $("<table class='main_table' style='word-wrap:break-word'></table>")
                .appendTo($("#detail"));
        var $th1 = $("<tr><td rowspan='2'>Phone Model</td></tr>")
                .appendTo($table);
        var $th2 = $("<tr></tr>").appendTo($table);
        // 获取产品下选择的终端信息
        var versionNum = $("#versionView").val();
      if(versionNum == '0'){//判断是即时版本还是历史版本
         var data = $.ajax({
            url: "?model=product_terminal_terminalfunction&action=listJson",
            async: false,
            data: {
                productId: productId
            }
        }).responseText;
      }else{
          var data = $.ajax({
            url: "?model=product_terminal_terminalfunction&action=listJsonVersion",
            async: false,
            data: {
                productId: productId,
                version : versionNum
            }
        }).responseText;
      }

        data = eval("(" + data + ")");
        var tfValueObj = {};
        for (var i = 0; i < data.length; i++) {
            var d = data[i];
            tfValueObj[d.terminalId + "-" + d.functionId] = [d.id,
                d.functionTip];
        }
        var data = {
            productId: productId,
            versionStatus: version,
            terminalName: infoName
        };
        data[langName] = functionName;
        // 获取产品下的终端信息
        $.ajax({
            url: "?model=product_terminal_product&action=listJsonDetail",
            data: data,
            success: function(data) {
                data = eval("(" + data + ")");
                var terminaltypes = data['terminaltypes'];// 终端
                var functiontypes = data['functiontypes'];// 功能项
                var tableWidth = 150;
                var $tr1 = $("<tr><td width='" + tableWidth
                        + "px'>版本状态</td></tr>").appendTo($table);
                var $tr2 = $("<tr><td width='" + tableWidth
                        + "px'>版本号</td></tr>").appendTo($table);
                var tdNum = 1;// 统计列数
                var cloWidth = 80;

                for (var i = 0; i < terminaltypes.length; i++) {
                    var teminaltype = terminaltypes[i];
                    var teminals = teminaltype['infos'];
                    if (teminals.length > 0) {
                        var $td = $("<td colspan='" + teminals.length + "'>"
                                + teminaltype.typeName + "</td>")
                                .appendTo($th1);
                        for (var j = 0; j < teminals.length; j++) {
                            var teminal = teminals[j];
                            tableWidth += cloWidth;
                            var $td = $("<td id='td" + (tdNum + j)
                                    + "' style='width:" + cloWidth
                                    + "px;white-space:normal'>"
                                    + teminal.terminalName + "</td>")
                                    .appendTo($th2).data("terminalId",
                                    teminal.id);
                            var $td1 = $("<td>"
                                    + versionStatusArr[teminal.versionStatus]
                                    + "</td>").appendTo($tr1);
                            var $td2 = $("<td>" + teminal.formalVersion
                                    + "</td>").appendTo($tr2);
                        }
                        tdNum += teminals.length;
                    }
                }
                $table.width(tableWidth);
                for (var i = 0; i < functiontypes.length; i++) {
                    var functiontype = functiontypes[i];
                    var functioninfos = functiontype['infos'];
                    var $tr1 = $("<tr style='text-align:left;background-color:#6699CC'><td colspan='"
                            + tdNum
                            + "'>"
                            + functiontype.typeName
                            + "</td></tr>").appendTo($table);
                    for (var j = 0; j < functioninfos.length; j++) {
                        var functioninfo = functioninfos[j];
                        var $tr2 = $("<tr><td>" + functioninfo[langName]
                                + "</td></tr>").appendTo($table);
                        for (var k = 0; k < tdNum - 1; k++) {
                            var terminalId = $("#td" + (k + 1))
                                    .data("terminalId");
                            td0 = "<td style='color:red'></td>";
//                            td0 = "<td style='color:red' class='" + j + k + "' id='terminalinfoTD" + terminalId + functioninfo.id + "'></td>";
                            var $td = $(td0);
                            var td2 = $td.appendTo($tr2);
                            $td.data("terminalId", terminalId);
                            $td.data("functionId", functioninfo.id);
//                            $td.attr('terminalId', terminalId);
                            var tfValue = tfValueObj[terminalId + "-"
                                    + functioninfo.id];
                            if (tfValue) {
                                $td.data("tfId", tfValue[0]);
                                $td.data("functionTip", tfValue[1]);
                                $td.html(tfValue[1]);
                            }
                            $td.dblclick(function() {
                                if ($('#isEdit').val() == 1) {
                                    var v = $(this).html();
                                    $(this).empty();
                                    blurTd();
                                    var $input = $("<input class='tinput' type='text' size='5'></input>")
                                            .appendTo($(this)).val(v).click(
                                            function() {
                                                return false;
                                            }).focus();
                                }
                            });

                            //add for zhudc start
                            $td.mousedown(function(e) {
                                if (e.which == 3) {
                                    var v = $(this).html();
                                    var $input = $("<input class='tdinput' type='hidden' readonly size='5'></input>")
                                            .appendTo($(this)).val(v).click(
                                            function() {
                                                return false;
                                            });
                                    $(".tdinput").each(function(i) {
                                        var p = $(this).parent();
                                        $('#terminalId').val(p.data("terminalId"));
                                        $(this).remove();
                                    });
                                    $("#rightMenu").css({'top': e.pageY - 25 + 'px', 'left': e.pageX - 61 + 'px'});
                                    $("#rightMenu").css("position", "absolute").slideDown();
                                    $("#rightMenu").removeClass();
                                }
                            });
                            //add for zhudc end
                        }
                    }
                }
            }
        });
    }
}

function blurTd() {
    $(".tinput").each(function(i) {
        var v = $(this).val();
        var p = $(this).parent();
        var terminalId = p.data("terminalId");
        var functionId = p.data("functionId");
        var tfId = p.data("tfId");
        $(this).remove();
        p.html(v);
        $.ajax({
            url: "?model=product_terminal_terminalfunction&action=save",
            type: "POST",
            data: {
                "terminalfunction[terminalId]": terminalId,
                "terminalfunction[functionId]": functionId,
                "terminalfunction[productId]": $("#productSelect")
                        .val(),
                "terminalfunction[id]": tfId,
                "terminalfunction[functionTip]": v
            }
        });
    });
}

//保存版本
function saveVersion(){
   $.ajax({
		type : "POST",
		url : "?model=product_terminal_terminalfunction&action=saveVersion",
//		data : {
//			id : row.id
//		},
		success : function(msg) {
			if (msg == 1) {
				alert('保存成功！');
				versionData();
			}
		}
	});
    }