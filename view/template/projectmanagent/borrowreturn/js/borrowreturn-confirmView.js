
$(function() {
	// 产品清单
	$("#productInfo").yxeditgrid({
		objName : 'borrowreturn[product]',
		url:'?model=projectmanagent_borrowreturn_borrowreturnequ&action=listJsonReturn',
		tableClass : 'form_in_table',
		realDel : true,
//		type:'view',
		param:{
        	'returnId' : $("#id").val()
        },
        event : {
			reloadData : function(event, g) {
				var rowCount = g.getCurRowNum();
				for (var i = 0; i < rowCount; i++) {
					var serialId = $("#productInfo_cmp_serialId" + i).val();
					var serialName = $("#productInfo_cmp_serialName" + i).val();
				  $("#productInfo_cmp_serialIdView" + i).val(serialId);
				  $("#productInfo_cmp_serialNameView" + i).val(serialName);
				  $("#productInfo_cmp_serialId" + i).val("");
				  $("#productInfo_cmp_serialName" + i).val("");
				}
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden',
			isSubmit : true,
			readonly : true
		},{
			display : 'equId',
			name : 'equId',
			type : 'hidden',
			isSubmit : true,
			readonly : true
		},{
			display : '物料编号',
			name : 'productNo',
			type : 'statictext',
			isSubmit : true,
			readonly : true
		},{
			display : '物料名称',
			name : 'productName',
			type : 'statictext',
			isSubmit : true,
			readonly : true
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '物料Id',
			type : 'statictext',
			isSubmit : true,
			type : 'hidden'
		}
		, {
			display : '申请归还数量',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '申请质检数量',
			name : 'qualityNum',
			type : 'statictext'
		}, {
			display : '合格数量',
			name : 'qPassNum',
			type : 'statictext'
		}, {
			display : '不合格数量',
			name : 'qBackNum',
			type : 'statictext'
		}, {
			display : '已处理数量',
			name : 'disposeNumber',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '本次处理数量',
			name : 'disposeNum',
			tclass : 'txtshort',
			validation : {
				required : true
			},
			event : {
				blur : function(e, rowNum, g) {
					var rowNum = $(this).data("rowNum");
					var thisNumber = $("#productInfo_cmp_disposeNum" + rowNum).val();
					var disposeNumber = $("#productInfo_cmp_disposeNumber" + rowNum).val();
					var maxNum = $("#productInfo_cmp_number" + rowNum) .val();
					var num = eval(maxNum) - eval(disposeNumber);
					if (thisNumber < 0 || eval(thisNumber) > num) {
						alert("数量不能大于" + num + ",或小于0 ");
						var g = $(this).data("grid");
						g.setRowColValue(rowNum, "disposeNum",num, true);
					}

				}
			}
		}, {
			name : 'serialIdView',
			display : '序列号ID冗余',
			type : 'hidden'
		}, {
			name : 'serialNameView',
			display : '序列号名称冗余',
			type : 'hidden'
		}, {
			name : 'serialId',
			display : '序列号ID',
			type : 'hidden'
		}, {
			name : 'serialName',
			display : '序列号',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly',
			process : function($input, rowData, $tr , grid) {
				var borrowLimit = $("#borrowLimit").val();
				var inputId = $input.attr('id');
				var rownum = $input.data('rowNum');// 第几行
				var sid = grid.getCmpByRowAndCol(rownum, 'serialId').attr('id');
				var $img = $("<img src='images/add_snum.png' align='absmiddle'  title='选择序列号'>");
				$img.click(function(serialId, serialName, num, inputId,sid,borrowLimit,rownum) {
							return function() {
								serialNum(serialId, serialName, num,inputId,sid,borrowLimit,rownum);
							}
						}(rowData.serialId, rowData.serialName,rowData.disposeNum, inputId,sid,borrowLimit,rownum));
				$input.before($img)
			},
			event : {
				dblclick  : function() {
					var serial = $(this).val();
					if(serial != ""){
						showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialShow&serial='
							+ serial
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
					}
				}
			}
		}],
		isAddOneRow:false,
		isAdd : false
	});

    /**
	 * 验证信息
	 */
	validate({
		"disposeIdea" : {
			required : true
		},
		"disposeType" : {
			required : true
		}
	});
});


// 选择序列号
function serialNum(serialId, serialName, num, inputId,sid,borrowLimit,rownum) {
	num = $("#productInfo_cmp_disposeNum" + rownum).val();
	if(num == undefined || num == ""){
	   num = "0";
	}
	showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialNumBorrowReturn&serialId='
			+ serialId
			+ '&serialName='
			+ serialName
			+ '&num='
			+ num
			+ '&amount='
			+ num
			+ '&inputId='
			+ inputId
			+ '&sid='
			+ sid
			+ '&borrowLimit='
			+ borrowLimit
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
}


 function borrowView(){
         var limit = $("#limit").val();
         var borrowId = $("#borrowId").val();
          if(limit == '客户'){
            showThickboxWin("?model=projectmanagent_borrow_borrow&action=toViewTab&id="
							+ borrowId
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=900&width=1100");
          }else{
             showThickboxWin("?model=projectmanagent_borrow_borrow&action=toViewTab&id="
							+ borrowId
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=900&width=1100");
          }
       }

 function disposeChange(obj){
    var type = strTrim(obj.value);
    if(type == '确认赔偿'){
       $("#compensateView").show();
       $('#damageLevel').addClass("validate[required]");
       $('#compensateType').addClass("validate[required]");
       $("#compensateView2").show();
       $("#resultType").addClass("validate[required]");
    }else{
       $("#compensateView").hide();
       $('#damageLevel').removeClass("validate[required]");
       $('#compensateType').removeClass("validate[required]");
       $("#compensateView2").show();
       $("#resultType").removeClass("validate[required]");
    }
  }

 // 加载数据字典项
$(function() {
		// 损坏级别
		SHJBArr = getData('SHJB');
		addDataToSelect(SHJBArr, 'damageLevel');
		// 赔偿方式
		PCFSArr = getData('PCFS');
		addDataToSelect(PCFSArr, 'compensateType');
		// 赔偿方式
		CLJGArr = getData('CLJG');
		addDataToSelect(CLJGArr, 'resultType');

	});