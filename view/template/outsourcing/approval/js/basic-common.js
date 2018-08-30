/**
 * ��д�ӱ��ͷ
 */
function tableHead(){
	var trHTML =  '';
	var detailRows = ['���޿�ʼ����','���޽�������','����','���������ɱ�����(Ԫ/��)','���������ɱ�','�������(Ԫ/��)','����۸�'];
	var detailArr = ['��������','�۸�Ա�'];
	var trObj = $("#itemTable tr:eq(0)");
	var tdArr = trObj.children();
	var mark = 1;
	var m = 0;
	tdArr.each(function(i,n){
		if($.inArray($(this).text(),detailRows) != -1){
			if(mark == 1&&m<2){
				if(m==0){
					$(this).attr("colSpan",3).text(detailArr[m]);
				}else{
					$(this).attr("colSpan",4).text(detailArr[m]);
				}
				mark = 1;
				m++;
			}else{
				$(this).remove();
				mark = 0;
			}
		}else{
			$(this).attr("rowSpan",2);
		}
	});

	trHTML+='<tr class="main_tr_header">';
	for(m=0;m<detailRows.length;m++){
		trHTML+='<th><div class="divChangeLine" style="min-width:60px;">'+detailRows[m]+'</div></th>';
	}
	trHTML+='</tr>';
	trObj.after(trHTML);
}

//����������
function outsourType(){
	var outsourcing = $("#outsourcing").val();
	if(outsourcing == "HTWBFS-02"){
		$("#personrental").show();
		$("#projectrental").hide();
		itemDetail();
	}else{
		$("#personrental").hide();
		$("#projectrental").show();
//		$("#projectrentalTr1").attr("style",'');
//		$("#projectrentalTr2").attr("style",'');
		$("#projectrentalTr1").show();
		$("#projectrentalTr2").show();
		initProjectRental();
	}
}

//��������
function countDate(thisKey,rowNum){
	var objGrid = $("#itemTable");
	//��������
	var planBeginDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"beginDate");
	//�뿪����
	var planEndDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"endDate");

	if(planBeginDateObj.val() != "" && planEndDateObj.val() != ""){
		//ʵ������
		var actDay = DateDiff(planBeginDateObj.val(),planEndDateObj.val()) + 1;

		//����ʵ������
		objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"totalDay").val(actDay);
	}
}
//���������ɱ�
function countPerson(rowNum){
	var objGrid = $("#itemTable");

	//����
	var price = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"inBudgetPrice").val();
	if(price != ""){
		var numberTwo = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"totalDay").val();

		if( numberTwo != ""){
			var amount = accMul(numberTwo,price,2); //�ɱ�
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"selfPrice").val(amount);
		}
	}
}


//������������ɱ�
function countPersonOut(rowNum){
	var objGrid = $("#itemTable");

	//����
	var price = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"outBudgetPrice").val();
	if(price != ""){
		var numberTwo = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"totalDay").val();

		if( numberTwo != ""){
			var amount = accMul(numberTwo,price,2); //�ɱ�
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"rentalPrice").val(amount);
		}
	}
}

//����֤���� - ��Ϊ��Ŀ���ʱ,��Ҫ��д��Ŀ���
function checkForm(){
	return true;
}



//��������ģ��
function saveTemplate(){
	var costTypeArr = $("#projectRentalTbody tr");
	if(costTypeArr.length == 0)
		alert('û����ϸ��');
	else{
		var temArr = [];
		costTypeArr.each(function(i,n){
			var rowNum = $(this).attr('rowNum');//�к�

			//����parentֵ
			var parent = $("#parent"+rowNum).val();

			var costTypeObj = $("#costType"+rowNum);
			var costType = '';
			var costTypeName = '';
			if(costTypeObj.length > 0){
				costType = costTypeObj.val();
			}else{
				costTypeName = $("#costTypeName"+rowNum).val();
			}

			temArr[rowNum] = {parent : parent,costType:costType,costTypeName:costTypeName};
		});

		$.ajax({
		    type: "POST",
		    url: "?model=contract_outsourcing_outtemplate&action=saveTemplate",
		    data: {obj : temArr},
		    success: function(data){
				if(data == "1"){
					alert('����ɹ�');
				}else{
					alert('����ʧ��');
				}
			}
		});
	}
}


/********************** �����ְ����� *************************/

//�Զ������ѡ���� - ����ѡ��
function selectCostType2(){

	//��ȡ��ǰ�еķ�������
	var costTypeArr = $("#invbody input[id^='costTypeId']");
	//��ǰ���ڷ�����������
	var costTypeIdArr = [];
	//��ǰ���ڷ��������ַ���
	var costTypeIds = '';

	if(costTypeArr.length > 0){
		//���浱ǰ���ڷ�������
		costTypeArr.each(function(i,n){
			//�ж��Ƿ���ɾ��
			if($("#isDelTag_" + this.value).length == 0){
				costTypeIdArr.push(this.value);
			}
		});
		//������ǰ���ڷ�������id��
		costTypeIds = costTypeIdArr.toString();
	}

	//�����������ظ�ֵ
	$("#costTypeIds").val(costTypeIds);

	//��һ�μ���
	var isFirst = false;

	if($("#costTypeInner").html() == ""){
		isFirst = true;
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_expense&action=getCostType",
		    async: false,
		    success: function(data){
		   		if(data != ""){
					$("#costTypeInner").html("<div id='costTypeInner2'>" + data + "</div>")
//					if(costTypeIds != ""){
//						//��ֵ
//						for(var i = 0; i < costTypeIdArr.length;i++){
//							$("#chk" + costTypeIdArr[i]).attr('checked',true);
//							$("#view" + costTypeIdArr[i]).attr('class','blue');
//						}
//					}
		   	    }else{
					alert('û���ҵ��Զ���ķ�������');
		   	    }

			}
		});
	}
	$("#costTypeInner").dialog({
		title : '������������',
		height : 600,
		width : 1000
	});
	//��ʱ�������򷽷�
	setTimeout(function(){
		initMasonry();
		if(checkExplorer() == 1){
			$("#costTypeInner2").height(560).css("overflow-y","scroll");
		}
	},200);
}


//�ٲ�������
function initMasonry(){
	$('#costTypeInner2').masonry({
		itemSelector: '.box'
	});
}

//�ж������
function checkExplorer(){
	var Sys = {};
    var ua = navigator.userAgent.toLowerCase();
    window.ActiveXObject ? Sys.ie = ua.match(/msie ([\d.]+)/)[1] :
    document.getBoxObjectFor ? Sys.firefox = ua.match(/firefox\/([\d.]+)/)[1] :
    window.MessageEvent && !document.getBoxObjectFor ? Sys.chrome = ua.match(/chrome\/([\d.]+)/)[1] :
    window.opera ? Sys.opera = ua.match(/opera.([\d.]+)/)[1] :
    window.openDatabase ? Sys.safari = ua.match(/version\/([\d.]+)/)[1] : 0;

    if(Sys.ie){
		return 1;
    }
}

//ѡ���Զ����������
function setCustomCostType(thisCostType,thisObj){
	if($(thisObj).attr('checked') == true){
		$("#view" + thisCostType).attr('class','blue');
		//����ѡ����
		var chkObj = $("#chk" + thisCostType);
		var chkName = chkObj.attr('name');  //��������
		var chkParentName = chkObj.attr('parentName'); //���ø���������
		var chkParentId = chkObj.attr('parentId'); //���ø�����id
		var projectRentalRowNum=$("#projectRentalRowNum").val();
		var rowNum=projectRentalRowNum*1;
		var str ='<tr id="tr'+rowNum+'" rowNum="'+rowNum+'">'+
                    '<td><img src="images/removeline.png" onclick="delProjectRentalRow('+rowNum+');" title="ɾ����"/></td>'+
                    '<td><input name="basic[projectRental]['+rowNum+'][parentName]" id="" value="'+chkParentName+'" style="width:55px;" class="rimless_textB"  title="'+chkParentName+'"/>'+
						'<input type="hidden" name="basic[projectRental]['+rowNum+'][parentId]" id="" value="'+chkParentId+'"/>'+
					'</td>'+
                    '<td>'+
						'<input name="basic[projectRental]['+rowNum+'][costType]" id="" value="'+chkName+'" class="rimless_textB" style="width:65px;"  title="'+chkName+'"/>'+
						'<input type="hidden" name="basic[projectRental]['+rowNum+'][costTypeId]" id="" value="'+chkParentId+'"/>'+
						'<input type="hidden" name="basic[projectRental]['+rowNum+'][isCustom]" id="" value="1"/>'+
					'</td>'+
					'<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier1][price]" id="supplier1_price'+rowNum+'" onblur="countDetail('+rowNum+',1);" class="rimless_textB formatMoney" style="width:85px;" value=""></td>'+
	                '<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier1][number]" id="supplier1_number'+rowNum+'" onblur="countDetail('+rowNum+',1);" class="rimless_textB" style="width:35px;" value=""></td>'+
	                '<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier1][period]" id="supplier1_period'+rowNum+'" onblur="countDetail('+rowNum+',1);" class="rimless_textB" style="width:35px;" value=""></td>'+
	                '<td class="amountTd"><input name="basic[projectRental]['+rowNum+'][supplier1][amount]" id="supplier1_amount'+rowNum+'" class="rimless_textB formatMoney" style="width:65px;" value=""></td>'+
	                '<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier2][price]" id="supplier2_price'+rowNum+'" onblur="countDetail('+rowNum+',2,1);" class="rimless_textB formatMoney" style="width:65px;"></td>'+
	                '<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier2][number]" id="supplier2_number'+rowNum+'" onblur="countDetail('+rowNum+',2);" class="rimless_textB" style="width:35px;"></td>'+
	                '<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier2][period]" id="supplier2_period'+rowNum+'" onblur="countDetail('+rowNum+',2);" class="rimless_textB" style="width:35px;"></td>'+
	                '<td class="amountTd"><input name="basic[projectRental]['+rowNum+'][supplier2][amount]" id="supplier2_amount'+rowNum+'" class="rimless_textB formatMoney" style="width:65px;"></td>'+
	                '<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier3][price]" id="supplier3_price'+rowNum+'" onblur="countDetail('+rowNum+',3,1);" class="rimless_textB formatMoney" style="width:65px;"></td>'+
	                '<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier3][number]" id="supplier3_number'+rowNum+'" onblur="countDetail('+rowNum+',3);" class="rimless_textB" style="width:35px;"></td>'+
	                '<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier3][period]" id="supplier3_period'+rowNum+'" onblur="countDetail('+rowNum+',3);" class="rimless_textB" style="width:35px;"></td>'+
	                '<td class="amountTd"><input name="basic[projectRental]['+rowNum+'][supplier3][amount]" id="supplier3_amount'+rowNum+'" class="rimless_textB formatMoney" style="width:65px;"></td>'+
	                '<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier4][price]" id="supplier4_price'+rowNum+'" onblur="countDetail('+rowNum+',4,1);" class="rimless_textB formatMoney" style="width:65px;"></td>'+
	                '<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier4][number]" id="supplier4_number'+rowNum+'" onblur="countDetail('+rowNum+',4);" class="rimless_textB" style="width:35px;"></td>'+
	                '<td class="detailTd"><input name="basic[projectRental]['+rowNum+'][supplier4][period]" id="supplier4_period'+rowNum+'" onblur="countDetail('+rowNum+',4);" class="rimless_textB" style="width:35px;"></td>'+
	                '<td class="amountTd"><input name="basic[projectRental]['+rowNum+'][supplier4][amount]" id="supplier4_amount'+rowNum+'" class="rimless_textB formatMoney" style="width:65px;"></td>'+
	                '<td>'+
	                	'<input name="basic[projectRental]['+rowNum+'][remark]" class="rimless_textB" style="width:80px;"/>'+
						'<input type="hidden" name="basic[projectRental]['+rowNum+'][isDetail]" id="" value="1"/>'+
					'</td>'+
                '</tr>';
         $("#appendHtml").before(str);
         //ǧ��λ��Ⱦ
		 formatProjectRentalMoney();
         $("#projectRentalRowNum").val(rowNum*1+1);
	}else{
		$("#view" + thisCostType).attr('class','');
	}
}