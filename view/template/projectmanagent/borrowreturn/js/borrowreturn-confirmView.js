
$(function() {
	// ��Ʒ�嵥
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
			display : '���ϱ��',
			name : 'productNo',
			type : 'statictext',
			isSubmit : true,
			readonly : true
		},{
			display : '��������',
			name : 'productName',
			type : 'statictext',
			isSubmit : true,
			readonly : true
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '����Id',
			type : 'statictext',
			isSubmit : true,
			type : 'hidden'
		}
		, {
			display : '����黹����',
			name : 'number',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '�����ʼ�����',
			name : 'qualityNum',
			type : 'statictext'
		}, {
			display : '�ϸ�����',
			name : 'qPassNum',
			type : 'statictext'
		}, {
			display : '���ϸ�����',
			name : 'qBackNum',
			type : 'statictext'
		}, {
			display : '�Ѵ�������',
			name : 'disposeNumber',
			type : 'statictext',
			isSubmit : true
		}, {
			display : '���δ�������',
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
						alert("�������ܴ���" + num + ",��С��0 ");
						var g = $(this).data("grid");
						g.setRowColValue(rowNum, "disposeNum",num, true);
					}

				}
			}
		}, {
			name : 'serialIdView',
			display : '���к�ID����',
			type : 'hidden'
		}, {
			name : 'serialNameView',
			display : '���к���������',
			type : 'hidden'
		}, {
			name : 'serialId',
			display : '���к�ID',
			type : 'hidden'
		}, {
			name : 'serialName',
			display : '���к�',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly',
			process : function($input, rowData, $tr , grid) {
				var borrowLimit = $("#borrowLimit").val();
				var inputId = $input.attr('id');
				var rownum = $input.data('rowNum');// �ڼ���
				var sid = grid.getCmpByRowAndCol(rownum, 'serialId').attr('id');
				var $img = $("<img src='images/add_snum.png' align='absmiddle'  title='ѡ�����к�'>");
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
	 * ��֤��Ϣ
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


// ѡ�����к�
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
          if(limit == '�ͻ�'){
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
    if(type == 'ȷ���⳥'){
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

 // ���������ֵ���
$(function() {
		// �𻵼���
		SHJBArr = getData('SHJB');
		addDataToSelect(SHJBArr, 'damageLevel');
		// �⳥��ʽ
		PCFSArr = getData('PCFS');
		addDataToSelect(PCFSArr, 'compensateType');
		// �⳥��ʽ
		CLJGArr = getData('CLJG');
		addDataToSelect(CLJGArr, 'resultType');

	});