var exedeptArr = new Array();

// ֱ���ύ����
function toApp() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_trialproject_trialproject&action=edit&act=app";
}
// ���������б�
$(function() {
	// �ͻ�
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#customerType").val(data.TypeOne);
					$("#province").val(data.ProvId);// ����ʡ��Id
					$("#province").trigger("change");
					$("#provinceName").val(data.Prov);// ����ʡ��
					$("#city").val(data.CityId);// ����ID
					$("#cityName").val(data.City);// ��������
					$("#customerId").val(data.id);
					$("#areaPrincipal").val(data.AreaLeader);// ��������
					$("#areaPrincipalId").val(data.AreaLeaderId);// ��������Id
					$("#areaName").val(data.AreaName);// ��ͬ��������
					$("#areaCode").val(data.AreaId);// ��ͬ��������
					$("#address").val(data.Address);// �ͻ���ַ
				}
			}
		}
	});

	// ��Ʒ�嵥
	var isView = $("#isView").val();
	if(isView==1){
		var yxType = "view";
	}else{
		var yxType = "edit";
	}
	$("#productInfo").yxeditgrid({
		objName : 'trialproject[product]',
		url:'?model=projectmanagent_trialproject_trialprojectEqu&action=listJson',
        param:{
        	'trialprojectId' : $("#trialprojectId").val()
        },
        type : yxType,
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
            display : '��Ʒ��',
            name : 'newProLineName',
            tclass : 'readOnlyTxtNormal',
            readonly : true
        }, {
            display : '��Ʒ�߱���',
            name : 'newProLineCode',
			type : 'hidden'
        }, {
            display : 'ִ������',
            name : 'exeDeptName',
            tclass : 'readOnlyTxtNormal',
            readonly : true
        },
			{
			display : '��Ʒ����',
			name : 'conProductName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '��ƷId',
			name : 'conProductId',
			type : 'hidden'
		}, {
			display : '��Ʒ����',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '��Ʒ�߱��',
			name : 'exeDeptCode',
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'moneySix',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '���',
			name : 'money',
			tclass : 'txtshort',
			type : 'money'
//		}, {
//			display : '������',
//			name : 'warrantyPeriod',
//			tclass : 'txtshort'
		}, {
			display : '��������Id',
			name : 'license',
			type : 'hidden'
		}, {

			name : 'licenseButton',
			display : '��������',
			type : 'statictext',
			process : function(v, row) {
				if (row.license != "") {
					return "<a href='javascript:void(0)' onclick='showLicense(\""
							+ row.license + "\")'>��������</a>";
				}
			},
			type : 'hidden'
		}, {
			display : '��Ʒ����Id',
			name : 'deploy',
			type : 'hidden'
		}, {
			name : 'deployButton',
			display : '��Ʒ����',
			process : function(v, row) {
				if (row.deploy != "") {
					return "<a href='javascript:void(0)' onclick='showGoods(\""
							+ row.deploy
							+ "\",\""
							+ row.conProductName
							+ "\")'>��Ʒ����</a>";
				}
			}
		}],
		isAddOneRow:false,
		event : {
			'clickAddRow' : function(e, rowNum, g) {
				url = "?model=contract_contract_product&action=toProductIframe";
				var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");
				if (returnValue) {
					dataTip = $.ajax({
						type : "POST",
						url : "?model=goods_goods_goodsbaseinfo&action=getExeDeptCodeById",
						data : {
							"pid" : returnValue.goodsId
						},
						async : false,
						success : function(data) {

						}
					}).responseText;
					if($.inArray(dataTip,exedeptArr) == "-1" && exedeptArr.length != '0'){
                        alert("��ѡ��ͬһִ������Ĳ�Ʒ��");
                        g.removeRow(rowNum);
					}else{
						exedeptArr.push(dataTip);
					    g.setRowColValue(rowNum, "conProductId",returnValue.goodsId, true);
						g.setRowColValue(rowNum, "conProductName",returnValue.goodsName, true);
						g.setRowColValue(rowNum, "exeDeptCode",returnValue.exeDeptCode, true);
						g.setRowColValue(rowNum, "exeDeptName",returnValue.exeDeptName, true);
						g.setRowColValue(rowNum, "number",returnValue.number, true);
						g.setRowColValue(rowNum, "price", returnValue.price, true);
						g.setRowColValue(rowNum, "money", returnValue.money, true);
						g.setRowColValue(rowNum, "warrantyPeriod",returnValue.warrantyPeriod, true);
						g.setRowColValue(rowNum, "deploy", returnValue.cacheId,true);
						g.setRowColValue(rowNum, "license", returnValue.licenseId,true);
						var $tr=g.getRowByRowNum(rowNum);
						$tr.data("rowData",returnValue);
						//ѡ���Ʒ��̬��Ⱦ��������õ�
						getCacheInfo(returnValue.cacheId,rowNum);
					}
				} else {
					g.removeRow(rowNum);
				}

				return false;
			},
			'reloadData' : function(e){
				initCacheInfo();
			},
			'removeRow' : function(e, rowNum, rowData){
				if(typeof(rowData) != 'undefined'){
			    	$("#goodsDetail_" + rowData.deploy).remove();
				}
			}
		}
	});

});

//ʡ��
$(function (){
   var proId = $("#provinceId").val();
   var cityId = $("#cityId").val();
    $("#province").val(proId);//����ʡ��Id
    $("#province").trigger("change");
	$("#city").val(cityId);//����ID
	$("#city").trigger("change");

});

//�ж�����ʱ����
function timeInterval(){
     //��ʼʱ��
	 var beginDate = $("#beginDate").val();
	 //����ʱ��
	 var closeDate = $("#closeDate").val();
	 if(beginDate!='' && closeDate!=''){
	 	if(closeDate>=beginDate){
		   var days = daysBetween(beginDate,closeDate);
		   if(days > 31){
		       alert("������Ŀʱ�䲻�ó���һ���£�31�죩��");
		       $("#closeDate").val("");
		    }
	 	}else{
			alert("�������ڲ���С�ڿ�ʼ���ڣ�");
			$("#closeDate").val("");
	 	}
	 }
}

//�жϹ���
function timeIntervals(){
	var projectDays = $("#projectDays");
	if(projectDays.val()!=""){
		if(!(/^(\+|-)?\d+$/.test( projectDays.val() ))){
			alert("������������");
			projectDays.val("");
		}else{
			if(projectDays.val() < 0  || projectDays.val()>31){
				alert("������Ŀʱ�䲻�ó���һ���£�31�죩��");
				projectDays.val("");
			}
		}
	}
}

//�ύ��֤
function toSub(){
	$("form").bind("submit", function() {
	     //��ʼʱ��
		 var beginDate = $("#beginDate").val();
		 //����ʱ��
		 var closeDate = $("#closeDate").val();
		 //����
		 var projectDays = $("#projectDays").val();
		if(projectDays == "" && closeDate == "" && beginDate==""){
			alert("������д��Ŀ���ڻ��߹���!");
			return false;
		}
		var rowNum = $("#productInfo").yxeditgrid('getCurShowRowNum');
        if(rowNum == '0'){
            alert("��Ʒ�嵥����Ϊ��!");
            return false;
        }else{
        	return true;
        }
	});
}

$(function(){
	// �ύ��֤
	$("#form1").validationEngine({
	inlineValidation: false,
	success :  function(){
		   toSub();
		   $("#form1").submit();//������֤�����ύ���������Ҫ����������ΰ�ť�����ύ����bug

	},
	failure :false
	})
    /**
	 * ��֤��Ϣ
	 */
	validate({
		"projectName" : {
			required : true
		},
		"customerId" : {
			required : true
		},
		"projectDescribe" : {
			required : true
		},
		"executive" : {
			required : true
		},
		"budgetMoney" : {
			required : true
		}
	});
})


//�ص������Ʒ��Ϣ �� ����
function getCacheInfo(cacheId,rowNum){
	$.ajax({
	    type: "POST",
	    url: "?model=goods_goods_goodscache&action=getCacheConfig",
	    data: {"id" : cacheId },
	    async: false,
	    success: function(data){
	    	if(data != ""){
				$("#productInfo table tr[rowNum="+ rowNum + "]").after(data);
	    	}

		}
	});
//	$.ajax({
//	    type: "POST",
//	    url: "?model=goods_goods_goodscache&action=getCacheInRow",
//	    data: {"id" : cacheId },
//	    async: false,
//	    success: function(data){
//	    	if(data != ""){
//				$("#productInfo_cmp_showDeploy"+ rowNum + "").html(data);
//	    	}
//		}
//	});
}


//�ص������Ʒ��Ϣ - ����/�����
function getCacheInfoChange(cacheId,beforeCacheId,rowNum){
	$.ajax({
	    type: "POST",
	    url: "?model=goods_goods_goodscache&action=getCacheChange",
	    data: {"id" : cacheId , "beforeId" : beforeCacheId },
	    async: false,
	    success: function(data){
	    	if(data != ""){
				$("#productInfo table tr[rowNum="+ rowNum + "]").after(data);
	    	}
		}
	});
}

//����ҳ��ʱ��Ⱦ��Ʒ������Ϣ
function initCacheInfo(){
	//���������
	var thisGrid = $("#productInfo");

	var colObj = thisGrid.yxeditgrid("getCmpByCol", "deploy");
	colObj.each(function(i,n) {
		//�ж��Ƿ��б��ǰֵ
		var beforeDeployObj = $("#productInfo_cmp_beforeDeploy" + i);
		if(beforeDeployObj.length == 1){
			if(beforeDeployObj.val()){
				getCacheInfoChange(this.value,beforeDeployObj.val(),i);
			}else{
				getCacheInfo(this.value,i);
			}
		}else{
			getCacheInfo(this.value,i);
		}
	});
}


// ���㷽��
function countAll(rowNum) {
	var beforeStr = "productInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
			|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// ��ȡ��ǰ��
		thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// ��ȡ��ǰ����
		thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// ���㱾�н�� - ����˰
		thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney(beforeStr + "money" + rowNum, thisMoney);
	}
}


//��Ʒ�鿴����
function showGoods(thisVal,goodsName){

	url = "?model=goods_goods_properties&action=toChooseView"
		+ "&cacheId=" + thisVal
		+ "&goodsName=" + goodsName
	;

	var sheight = screen.height-300;
	var swidth = screen.width-200;
	var winoption ="dialogHeight:"+sheight+"px;dialogWidth:"+ swidth +"px;status:yes;scroll:yes;resizable:yes;center:yes";

//	showModalDialog(url, '',winoption);
	window.open(url,"", "width=900,height=500,top=200,left=200");

//	showThickboxWin("?model=goods_goods_properties&action=toChooseView"
//		+ "&cacheId=" + thisVal
//		+ "&goodsName=" + goodsName
//		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
}


//license�鿴����
function showLicense(thisVal){
	if( thisVal == 0 || thisVal=='' || thisVal=='undefined' ){
		alert('�������޼�����Ϣ��');
		return false;
	}
	url = "?model=yxlicense_license_tempKey&action=toViewRecord"
		+ "&id=" + thisVal
	;

	var sheight = screen.height-200;
	var swidth = screen.width-70;
	var winoption ="dialogHeight:"+sheight+"px;dialogWidth:"+ swidth +"px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '',winoption);
}
