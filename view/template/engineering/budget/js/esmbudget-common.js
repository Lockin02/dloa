
//����ʱ�ύ���� -- ��������
function isUpdateFun(thisVal){
//	$("#isUpdate").val(thisVal);
}

/**
 * ������
 */
function amountCount() {
	var numberOne = $("#numberOne").val();
	var numberTwo = $("#numberTwo").val();
	var price = $("#price").val();
	if (price != "" && numberOne != "") {
		var sum = numberOne * price;
		if(numberTwo != ""){
			sum = sum * numberTwo;
		}
		setMoney('amount', sum);
	}
}


//���㹫ʽ
function countAll(rowNum) {
	//�ӱ�ǰ���ַ���
	var beforeStr = "importTable_cmp";
	//��ȡ��ǰ����
	price= $("#"+ beforeStr + "_price" + rowNum+"_v").val();

	//��ȡ����1
	numberObj = $("#"+ beforeStr + "_numberOne" + rowNum);
	//��ȡ����2
	numberTwoObj = $("#"+ beforeStr + "_numberTwo" + rowNum);

	if(price != ""){
		//����1����
		if(numberObj.val()== ""){
			numberObj.val(1);
		}

		//����2����
		if(numberTwoObj.val()== ""){
			numberTwoObj.val(1);
		}

		//��ȡ����1
		var amount = accMul(price,numberObj.val(),2);

		//��ȡ����2
		amount = accMul(amount,numberTwoObj.val(),2);

		//�����ܽ��
		setMoney(beforeStr + "_amount" + rowNum,amount);
	}else{
		numberObj.val("");
		numberTwoObj.val("");
		//�����ܽ��
		setMoney(beforeStr + "_amount" + rowNum,"");
	}
}

//���������ɱ�
function countPerson(rowNum){
	var objGrid = $("#importTable");

	//����
	var price = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val();
	if(price != ""){
		//����ϵ��
		var coefficient = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"coefficient").val();

		//����ϵ��
		var numberOne = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"numberOne").val();
		var numberTwo = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"numberTwo").val();

		if(numberOne != "" && numberTwo != ""){
			var budgetDay = accMul(numberOne,numberTwo,2); //����
			var budgetPeople = accMul(budgetDay,coefficient,2); //�ɱ�����
			var amount = accMul(budgetDay,price,2); //�ɱ�

			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetDay").val(budgetDay);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetPeople").val(budgetPeople);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"amount").val(amount);
		}
	}
}

//��������
function countDate(thisKey,rowNum){
	var objGrid = $("#importTable");
	//��������
	var planBeginDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"planBeginDate");
	//�뿪����
	var planEndDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"planEndDate");

	if(planBeginDateObj.val() != "" && planEndDateObj.val() != ""){
		//ʵ������
		var actDay = DateDiff(planBeginDateObj.val(),planEndDateObj.val()) + 1;
		if(actDay < 1){
			alert('�������ڲ���С���뿪����');
			//����ʵ������
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,thisKey).val('');
		}else{
			//����ʵ������
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"numberTwo").val(actDay);
		}
	}
}

//���������ɱ� -- ���ڱ༭ҳ��
function countPersonE(){
	//����
	var price = $("#price").val();
	if(price != ""){
		//����ϵ��
		var coefficient = $("#coefficient").val();

		//����ϵ��
		var numberOne = $("#numberOne").val();
		var numberTwo = $("#numberTwo").val();

		if(numberOne != "" && numberTwo != ""){
			var budgetDay = accMul(numberOne,numberTwo,2); //����
			var budgetPeople = accMul(budgetDay,coefficient,2); //�ɱ�����
			var amount = accMul(budgetDay,price,2); //�ɱ�

			setMoney("budgetDay",budgetDay);
			$("#budgetPeople").val(budgetPeople);
			setMoney("amount",amount);
		}
	}
}

//�������� -- ���ڱ༭ҳ��
function countDateE(thisDate){
	//��������
	var planBeginDateObj = $("#planBeginDate");
	var planEndDateObj = $("#planEndDate");

	if(planBeginDateObj.val() != "" && planEndDateObj.val() != ""){
		//ʵ������
		var actDay = DateDiff(planBeginDateObj.val(),planEndDateObj.val()) + 1;
		if(actDay < 1){
			alert('������Ŀ���ڲ��ܴ����뿪��Ŀ����');
			if(thisDate){
				$("#"+thisDate).val('');
			}
		}else{
			//����ʵ������
			$("#numberTwo").val(actDay);
		}
	}
}

//����ˢ��tab
function reloadTab(thisVal){
	var tt = window.parent.$("#tt");
	var tb=tt.tabs('getTab',thisVal);
	tb.panel('options').headerCls = tb.panel('options').thisUrl;
}

/********************** �ֳ�Ԥ�㲿�� *************************/

//�Զ������ѡ���� - ����ѡ��
function selectCostType2(){
	var objGrid = $("#importTable");
	var costTypeArr = objGrid.yxeditgrid("getCmpByCol", "budgetId");

	//��ǰ���ڷ�����������
	var costTypeIdArr = [];
	//��ǰ���ڷ��������ַ���
	var costTypeIds = '';

	if(costTypeArr.length > 0){
		//���浱ǰ���ڷ�������
		costTypeArr.each(function(i,n){
			//�ж��Ƿ���ɾ��
			if($("#esmbudget[budgets]_" + i +"_isDelTag").length == 0){
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
		$.ajax({
		    type: "POST",
		    url: "?model=finance_expense_expense&action=getCostType",
		    async: false,
		    success: function(data){
		   		if(data != ""){
					$("#costTypeInner").html("<div id='costTypeInner2'>" + data + "</div>")
					if(costTypeIds != ""){
						//��ֵ
						for(var i = 0; i < costTypeIdArr.length;i++){
							$("#chk" + costTypeIdArr[i]).attr('checked',true);
							$("#view" + costTypeIdArr[i]).attr('class','blue');
						}
					}
					//��ʱ�������򷽷�
					setTimeout(function(){
						initMasonry();
						if(checkExplorer() == 1){
							$("#costTypeInner2").height(580).css("overflow-y","scroll");
						}
					},200);
		   	    }else{
					alert('û���ҵ��Զ���ķ�������');
		   	    }

			}
		});
	}
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

//���¼���Ⱦ
function CostTypeShowAndHide(thisCostType){
	//���������
	var tblObj = $("table .ct_"+thisCostType + "[isView='1']");
	//������ǰ������״̬������ʾ
	if(tblObj.is(":hidden")){
		tblObj.show();
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//����������Ŀ�鿴
function CostType2View(thisCostType){
	//���������
	var tblObj = $("table .ct_"+thisCostType);
	//������ǰ������״̬������ʾ
	if(tblObj.is(":hidden")){
		tblObj.show();
		tblObj.attr('isView',1);
		$("#" + thisCostType).attr("src","images/menu/tree_minus.gif");
	}else{
		tblObj.hide();
		tblObj.attr('isView',0);
		$("#" + thisCostType).attr("src","images/menu/tree_plus.gif");
	}
	initMasonry();
}

//ѡ���Զ����������
function setCustomCostType(thisCostType,thisObj){
	//����ӱ����
	var objGrid = $("#importTable");
	//���Ҷ�Ӧ����������
	var findRowNum = findBudgetRowNum(thisCostType);

	if($(thisObj).attr('checked') == true){
		$("#view" + thisCostType).attr('class','blue');

		//�����ڵ�ǰ����
		if(findRowNum == -1){
			//����ѡ����
			var chkObj = $("#chk" + thisCostType);
			var chkName = chkObj.attr('name');  //��������
			var chkParentName = chkObj.attr('parentName'); //���ø���������
			var chkParentId = chkObj.attr('parentId'); //���ø�����id

			//���»�ȡ����
			var rowNum = objGrid.yxeditgrid("getAllAddRowNum");

			//������
			objGrid.yxeditgrid("addRow",rowNum);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetName").val(chkName);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetId").val(thisCostType);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"parentName").val(chkParentName);
			objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"parentId").val(chkParentId);
		}else{
			//ɾ��
//			objGrid.yxeditgrid("removeRow",findRowNum);
		}
	}else{
		$("#view" + thisCostType).attr('class','');
		//ɾ��
		objGrid.yxeditgrid("removeRow",findRowNum);
	}
}

//ȡ��ѡ��
function cancelCheck(rowNum){
	//����ӱ����
	var objGrid = $("#importTable");

	//��ȡ��ȡ���ķ�������
	var budgetId = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetId").val();

	//������ѡ�����û������ʱ��
	if($("#costTypeInner").html() != ""){
		var costTypeArr = objGrid.yxeditgrid("getCmpByCol", "budgetId");

		//��ǰ���ڷ�����������
		var costTypeIdArr = [];
		//��ǰ���ڷ��������ַ���
		var costTypeIds = '';

		if(costTypeArr.length > 0){
			//���浱ǰ���ڷ�������
			costTypeArr.each(function(i,n){
				//�ж��Ƿ���ɾ��
				if($("#esmbudget[budgets]_" + i +"_isDelTag").length == 0){
					costTypeIdArr.push(this.value);
				}
			});
		}

		//��������Ѿ���������ѡ�з����У���ȡ��ģ���ڵķ���ѡ��
		if(costTypeIdArr.indexOf(budgetId) == -1){
			$("#chk" + budgetId).attr('checked',false);
			$("#view" + budgetId).attr('class','');
		}
	}
}

//�������͸���
function copyBudget(rowNum){
	//����ӱ����
	var objGrid = $("#importTable");

	//���ƶ������ݻ�ȡ
	var budgetId = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetId").val();
	var budgetName = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"budgetName").val();
	var parentName = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"parentName").val();
	var parentId = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"parentId").val();

	//���»�ȡ����
	var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");

	//������
	objGrid.yxeditgrid("addRow",tbRowNum);
	objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum,"budgetName").val(budgetName);
	objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum,"budgetId").val(budgetId);
	objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum,"parentName").val(parentName);
	objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum,"parentId").val(parentId);
}

//���ݷ�������id��ѯ
function findBudgetRowNum(thisCostType){
	var rtVal = -1;
	//����ӱ����
	var objGrid = $("#importTable");
	//���»�ȡ����
	var cmps = objGrid.yxeditgrid("getCmpByCol", "budgetId");
	cmps.each(function(i,n){
		if(thisCostType == this.value){
			rtVal = $(this).data("rowNum");
		}
	});

	return rtVal;
}

//��ʼ������ģ��id
function initBudgetIds(){
	//����ӱ����
	var objGrid = $("#importTable");
	var costTypeArr = objGrid.yxeditgrid("getCmpByCol", "budgetId");

	//��ǰ���ڷ�����������
	var costTypeIdArr = [];
	//��ǰ���ڷ��������ַ���
	var costTypeIds = '';

	if(costTypeArr.length > 0){
		//���浱ǰ���ڷ�������
		costTypeArr.each(function(i,n){
			//�ж��Ƿ���ɾ��
			if($("#esmbudget[budgets]_" + i +"_isDelTag").length == 0){
				costTypeIdArr.push(this.value);
			}
		});
		//������ǰ���ڷ�������id��
		costTypeIds = costTypeIdArr.toString();
	}

	//�����������ظ�ֵ
	$("#costTypeIds").val(costTypeIds);
}

//�򿪱������
function openSavePage(){
	var costTypeIds = $("#costTypeIds").val();

	if(costTypeIds.length == "0"){
		alert('������ѡ��һ���������');
	}else{
		$("#tempTemplateName").val($("#templateName").val());
		$('#templateInfo').dialog({
		    title: '����ģ��',
		    width: 400,
		    height: 200,
   			modal: true
		}).dialog('open');
	}
}

//����ģ��
function saveTemplate(){
	//��ȡ��ǰ�еķ�������
	var objGrid = $("#importTable");
	var contentObjs = objGrid.yxeditgrid("getCmpByCol", "budgetName");
	var contentIdObjs = objGrid.yxeditgrid("getCmpByCol", "budgetId");

	var contentArr = [];
	var contentIdArr = [];
	//���浱ǰ���ڷ�������
	contentIdObjs.each(function(i,n){
		contentIdArr.push(this.value);
	});
	contentObjs.each(function(i,n){
		contentArr.push(this.value);
	});

	if(contentIdArr.length == 0){
		alert('û������ѡ��ֵ��������ѡ��һ���������');
	}else{
		var templateName= $("#tempTemplateName").val();
		var content = contentArr.toString();
		var contentId = contentIdArr.toString();
	    if(templateName){
			$.ajax({
			    type: "POST",
			    url: "?model=finance_expense_customtemplate&action=ajaxSave",
			    data : {"templateName" : templateName , "content" : content , "contentId" : contentId },
			    async: false,
			    success: function(data){
			   		if(data != ""){
			   			alert('ģ�屣��ɹ�');
						$("#templateName").val(templateName).yxcombogrid_expensemodel('reload');
						$("#templateId").val(data);
						$('#templateInfo').dialog('close');
			   	    }else{
						alert('ģ�屣��ʧ��');
						$('#templateInfo').dialog('close');
			   	    }
				}
			});
	    }else{
	    	if(strTrim(templateName) == ""){
				alert('�����뱨��ģ������');
	    	}
	    }
	}
}