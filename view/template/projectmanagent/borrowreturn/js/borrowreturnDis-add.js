$(function() {
	var equIdArr = $("#equIdArr").val();
	var productInfoObj = $("#productInfo");
	// 产品清单
	productInfoObj.yxeditgrid({
		objName : 'borrowreturnDis[product]',
		url : '?model=projectmanagent_borrowreturn_borrowreturnequ&action=listJsonReturn',
		tableClass : 'form_in_table',
		realDel : true,
		// type:'view',
		title : "物料清单",
		param : {
			'returnId' : $("#id").val(),
			'applyType' : $("#applyType").val()
		},
		event : {
			reloadData : function(event, g,data) {
				if(!data || data.length == 0){
//					alert('没有可下达归还的数量');
//					closeFun();
				}else{
					var applyType = $("#applyType").val();
					//如果是设备遗失，那么赔偿的数量是和下达数量相等的，此处做相应处理
					var productArr = g.getCmpByCol("disposeNum");
					productArr.each(function(){
						var rowNum = $(this).data('rowNum');//行号

						if(applyType == "JYGHSQLX-02"){//归还和赔偿的处理
							//如果是遗失,一次性处理
							g.getCmpByRowAndCol(rowNum,'disposeNum').attr("readonly",true).removeClass('txtshort').addClass('readOnlyTxtShort');
							g.getCmpByRowAndCol(rowNum,'outNum').attr("readonly",true).removeClass('txtshort').addClass('readOnlyTxtShort');
						}
					});
				}
			}
		},
		colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden',
            isSubmit : true,
            readonly : true
        }, {
            display : 'equId',
            name : 'equId',
            type : 'hidden',
            isSubmit : true,
            readonly : true
        }, {
            display : 'borrowequId',
            name : 'borrowequId',
            type : 'hidden',
            isSubmit : true
        }, {
            display : '物料编号',
            name : 'productNo',
            type : 'statictext',
            isSubmit : true,
            width : 70,
            readonly : true
        }, {
            display : '物料名称',
            name : 'productName',
            type : 'statictext',
            isSubmit : true,
            readonly : true
        }, {
            display : '物料Id',
            name : 'productId',
            type : 'hidden'
        }, {
            display : '规格型号',
            name : 'productModel',
            type : 'hidden'
        }, {
            display : '单位',
            name : 'unitName',
            type : 'hidden'
        }, {
            display : '物料Id',
            isSubmit : true,
            type : 'hidden'
        }, {
            display : '申请<br/>归还数量',
            name : 'number',
            type : 'statictext',
            width : 60,
            isSubmit : true
        }, {
//            display : '申请<br/>质检数量',
//            name : 'qualityNum',
//            width : 60,
//            type : 'statictext'
//        }, {
//            display : '合格数量',
//            name : 'qPassNum',
//            width : 50,
//            type : 'statictext'
//        }, {
//            display : '不合格数量',
//            name : 'qBackNum',
//            width : 60,
//            type : 'statictext'
//        }, {
            display : '已下达<br/>归还数量',
            name : 'disposeNumber',
            type : 'statictext',
            width : 60,
            isSubmit : true
        }, {
            display : '本次下达<br/>归还数量',
            name : 'disposeNum',
            tclass : 'readOnlyTxtShort',
            width : 60,
            readonly : true,
            validation : {
                required : true
            },
            event : {
                blur : function(e, rowNum) {
                    var rowNum = $(this).data("rowNum");
                    var thisGrid = $(this).data("grid");
                    var thisNumber = $(this).val()*1;
                    var disposeNumber = thisGrid.getCmpByRowAndCol(rowNum,'disposeNumber').val()*1;
                    var number = thisGrid.getCmpByRowAndCol(rowNum,'number').val()*1;
                    var num = number - disposeNumber;
                    if (thisNumber < 0 || thisNumber > num) {
                        alert("下达归还数量不能大于" + num + ",或小于0 ");
                        thisGrid.setRowColValue(rowNum, "disposeNum", num, true);
                    }
                }
            }
        }, {
            name : 'serialId',
            display : '归还序列号',
            type : 'hidden'
        }, {
            name : 'serialName',
            display : '归还序列号',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly'
        }, {
            display : '已下达<br/>出库数量',
            name : 'outedNum',
            type : 'statictext',
            width : 60,
            isSubmit : true
        }, {
            display : '本次下达<br/>出库数量',
            name : 'outNum',
            tclass : 'txtshort',
            width : 60,
            validation : {
                required : true
            }
        }, {
            name : 'serialOutId',
            display : '出库序列号ID',
            type : 'hidden'
        }, {
            name : 'serialOutName',
            display : '出库序列号',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            process : function($input, rowData, $tr, grid) {
                var borrowLimit = $("#borrowLimit").val();
                var inputId = $input.attr('id');
                var rownum = $input.data('rowNum');// 第几行
                var sid = grid.getCmpByRowAndCol(rownum, 'serialOutId').attr('id');
                var $img = $("<img src='images/add_snum.png' align='absmiddle'  title='选择序列号'>");
                $img.click(function(serialId, serialName, num, inputId, sid, borrowLimit, rownum) {
                    return function() {
                        serialNum(serialId, serialName, num, inputId, sid, borrowLimit, rownum);
                    }
                }(rowData.serialOutId, rowData.serialOutName, rowData.qBackNum, inputId, sid, rowData.qBackNum, rownum));
                $input.before($img)
            },
            event : {
                dblclick : function() {
                    var serial = $(this).val();
                    if (serial != "") {
                        showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialShow&serial='
                            + serial
                            + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
                    }
                }
            }
        }],
		isAddOneRow : false,
		isAdd : false
	});

	/**
	 * 验证信息
	 */
	validate({
		//主表无验证
	});

	//绑定表单验证
	$("form").submit(function(){
		var disposeNumArr = productInfoObj.yxeditgrid("getCmpByCol", "disposeNum");
		var rs = true;
		disposeNumArr.each(function(){
			var rowNum = $(this).data("rowNum");
			var outNum = productInfoObj.yxeditgrid("getCmpByRowAndCol",rowNum,"outNum").val();
			if(this.value *1 < outNum *1){
				rs = false;
				var productName = productInfoObj.yxeditgrid("getCmpByRowAndCol",rowNum,"productName").val();
				alert('物料【'+productName+'】的下达出库数量不能大于下达归还数量');
				return false;
			}
		});
        if(rs == false){
            return false;
        }

		return confirm('确定提交单据吗？');
	});

	//显示质检情况
	$("#showQualityReport").showQualityDetail({
		param : {
			"objId" : $("#id").val(),
			"objType" : 'ZJSQYDGH'
		}
	});
});

// 选择序列号
function serialNum(serialId, serialName, num, inputId, sid, borrowLimit, rownum) {
	showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialNumBorrowReturn&serialId='
			+ serialId
			+ '&serialName=' + serialName
			+ '&num=' + num
			+ '&amount=' + num
			+ '&inputId=' + inputId
			+ '&sid=' + sid
			+ '&borrowLimit=' + borrowLimit
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
}

function borrowView() {
    var borrowId = $("#borrowId").val();
    showModalWin("?model=projectmanagent_borrow_borrow&action=toViewTab&id=" + $("#borrowId").val(),1,borrowId);
}