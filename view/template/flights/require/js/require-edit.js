//��ʼ��һЩ�ֶ�
var objName = 'require';
var initId = 'feeTbl_c';
var myUrl = '?model=flights_require_require&action=ajaxGet';
var actionType = 'edit';
var isCompanyReadonly = true; //��˾�Ƿ�ֻ��

$(document).ready(function() {
    //��ʼ����Ʊ����
    setTicketCheck();
    changeType($("#ticketTypeHidden").val());

    //��ʼ����ϸ
    initDetail();

	validate({
		"startPlace" : {
			required : true
		},
		"endPlace" : {
			required : true
		},
		"startDate" : {
			required : true
		},
		"outReason" : {
			required : true
		}
	});
	
	//��������ʱ��Σ��Զ����3Сʱ 
	$("#flyStartTime").bind("blur",function(){
		if($("#flyStartTime").val()<=21){
			var sum = accAdd($("#flyStartTime").val(),3);
			$("#flyEndTime").val(sum);
		}else if($("#flyStartTime").val()==22){
			$("#flyEndTime").val(1);
		}else if($("#flyStartTime").val()==23){
			$("#flyEndTime").val(2);
		}else if($("#flyStartTime").val()==24){
			$("#flyEndTime").val(3);
		}else{
			alert('����ֵ����')
			$("#flyStartTime").val('');
			$("#flyEndTime").val('');
		}
	});
});

//��ʼ���б�
function initDetail() {
    var itemTableObj = $("#itemTable");
    itemTableObj.yxeditgrid({
        url: "?model=flights_require_requiresuite&action=listJson",
        param: {
            "mainId": $("#id").val()
        },
        objName: 'require[items]',
        event: {
        	'reloadData': function(e) {
				var cardTypeArr   = itemTableObj.yxeditgrid("getCmpByCol", "cardType");
				if (cardTypeArr.length > 0) {
					cardTypeArr.each(function(){
               			var rowNum = $(this).data("rowNum");
	            		//��ȡ�����
						var validDateObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"validDate");
						var birthDateObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"birthDate");
						var nationObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"nation");
						var cardNoObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNo");
						var airNameObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airName");
						var employeeTypeObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"employeeType");

						//���ʹ���
						if($(this).val() != 'JPZJLX-01'){
							validDateObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false).attr('disabled',false).bind('focus',WdatePicker);
							birthDateObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false).attr('disabled',false).bind('focus',WdatePicker);
							nationObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false);
							cardNoObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false);

							//���÷�����֤����֤
							openDetailCheck(rowNum);
						}
						//�ⲿԱ�����ʹ���
						if(employeeTypeObj.val() == "YGLX-02"){
							airNameObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false).width(114);
							cardNoObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false);
						}
					});
				}
            }
        },
        colModel: [{
            name: 'id',
            display: 'id',
            width: 80,
            type: "hidden"
        },{
            name: 'employeeType',
            display: 'Ա������',
            width: 80,
            type: 'select',
			datacode : 'YGLX',
			event: {
            	change: function(e) {
               		var rowNum = $(this).data("rowNum");
            		//��ȡ�����
					var cardNoObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNo");
					var airNameObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airName");
					var airPhoneObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airPhone");

					//������մ���
					cardNoObj.val('');
					airNameObj.val('');
					airPhoneObj.val('');

					//���ʹ���
					if($(this).val() == 'YGLX-01'){
						$(airNameObj).yxselect_user();
						airNameObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true);
						cardNoObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true);
					}else{
						$(airNameObj).yxselect_user('remove');
						airNameObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false).width(114);
						cardNoObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false);
						}
                }
            }
		},{
            name: 'airName',
            display: '����',
            width: 80,
            readonly: true,
			validation : {
				required : true
			},
            process: function($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                var employeeTypeObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"employeeType");
                var cardNoObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNo");
                if(employeeTypeObj.val() == 'YGLX-01'){
                    $input.yxselect_user({
                        hiddenId: 'itemTable_cmp_airId' + rowNum,
                        formCode: 'flirequire',
                        event: {
                            select: function(e, obj) {
                            	if(!obj) return ;
    							//��ȡ���µ����ڵ�����
    							var personInfo = getPersonInfo(obj.val);
    							if (personInfo) {
    								itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airPhone").val(personInfo.mobile); //�ֻ�����
    								//����������ʾ
                	 				var cardType = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardType").val();
    								var cardNoObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNo");
    								var cardNoHiddenObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNoHidden");
                	 				if(cardType == 'JPZJLX-01'){
    				                	if($("#requireId").val() != obj.val){
    				                   		cardNoObj.val(formatIdCard(personInfo.identityCard));
    				                	}else{
    				                   		cardNoObj.val(personInfo.identityCard);
    				                	}
    			                   		cardNoHiddenObj.val(personInfo.identityCard);
                	 				}else{
    									cardNoObj.val('');
    									itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"birthDate").val('');
                	 				}
    							}

    							//���ñ�����֤
    							openCheck(rowNum);
    						},
    						clearReturn : function(){
            	 				itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum, "cardNo").val('');
    							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airPhone").val('');
    							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"validDate").val('');
    							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"birthDate").val('');
    							itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"nation").val('');
    							//�رձ�����֤
    							closeCheck(rowNum);
    						}
                        }
                    });
                }
            }
        },
        {
        	name : 'sex',
			display : '�˻����Ա�',
			width : 80,
			type : 'select',
			options : [{
				'name' : '��',
				'value' : '��'
			}, {
				'name' : 'Ů',
				'value' : 'Ů'
			}],
			validation : {
				required : true
			}
        },
        {
            name: 'airId',
            display: '��Ա���',
            width: 80,
            type: "hidden",
            readonly: true
        },
        {
			name : 'airPhone',
			display : '�ֻ�����',
			width : 80,
			validation : {
				required : true
			}
		}, {
            name: 'cardType',
            display: '֤������',
            width: 80,
            type: 'select',
			datacode : 'JPZJLX',
            event: {
            	change: function(e) {
               		var rowNum = $(this).data("rowNum");
            		//����տ���
					var cardNoObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"cardNo");
					var validDateObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"validDate");
					var birthDateObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"birthDate");
					var nationObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"nation");
					var employeeTypeObj = itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"employeeType");

					//������մ���
					cardNoObj.val('');
					validDateObj.val('');
					birthDateObj.val('');

					//���ʹ���
					if($(this).val() == 'JPZJLX-01'){
						validDateObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true).attr('disabled',true);
						birthDateObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true).attr('disabled',true);
						nationObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true);
						if(employeeTypeObj.val()=='YGLX-01'){
							cardNoObj.removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly',true);
						}

						//�رշ�����֤����֤
						closeDetailCheck(rowNum);
					}else{
						validDateObj.removeClass('readOnlyTxtNormal').addClass('txt validate[required]').attr('readonly',false).attr('disabled',false).bind('focus',WdatePicker);
						birthDateObj.removeClass('readOnlyTxtNormal').addClass('txt validate[required]').attr('readonly',false).attr('disabled',false).bind('focus',WdatePicker);
						nationObj.removeClass('readOnlyTxtNormal').addClass('txt validate[required]').attr('readonly',false);
						cardNoObj.removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly',false);

						//���ӱ�����ʱ��������֤
						if(itemTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"airName").val()!=""){
							//���÷�����֤����֤
							openDetailCheck(rowNum);
						}
					}
                }
            }
        },{
			name : 'cardNo',
			display : '֤������',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 120,
			validation : {
				required : true
			}
		}, {
			name : 'cardNoHidden',
			display : '֤������',
            type: "hidden"
		}, {
			name : 'validDate',
			readonly : true,
			display : '֤����Ч��',
			tclass : 'readOnlyTxtNormal',
			width : 80
		}, {
			name : 'birthDate',
			readonly : true,
			display : '��������',
			tclass : 'readOnlyTxtNormal',
			width : 80
		}, {
			name : 'nation',
			display : '����',
			readonly : true,
			tclass : 'readOnlyTxtNormal',
			width : 80
		}, {
			name : 'tourAgency',
			display : '���ÿͻ���',
			width : 80,
			type : 'select',
			datacode : 'CLKJG',
			emptyOption : true
		}, {
			name : 'tourCardNo',
			display : '���ÿͿ���',
			width : 80
		}]
    });
}