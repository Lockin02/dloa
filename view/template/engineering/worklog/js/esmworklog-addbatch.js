var provinceArr; //�����ʡ������
var cityArr; //����ĳ�������
var cityCache = {};

$(document).ready(function() {
	//ʡ��
	provinceArr = getProvince();

	//�����Ĭ��
	var provinceId = $("#provinceId").val() + "";
	if(provinceId != ""){
		cityArr = getCity(provinceId);

		//���������Ϣ
		cityCache[provinceId] = cityArr;
	}

	//��ʼ����־
	initWorklog();

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"executionDate" : {
			required : true
		},
		"workStatus" : {
			required : true
		}
	});

    // ��ȡ��ֹ��ʾ
    $.ajax({
        url: "?model=engineering_baseinfo_esmdeadline&action=getTips",
        success: function(data) {
            if (data != "") {
                $("#showTips").html(data);
            }
        }
    });
});

//��ʼ����־
function initWorklog(){
	//�������ݱ�
	var importTableObj = $("#importTable");

	importTableObj.yxeditgrid({
		objName : 'esmworklog[detail]',
		event : {
			'reloadData' : function(e){

			}
		},
		title : '��־��ϸ',
		colModel : [{
			display : '��Ŀid',
			name : 'projectId',
			type : 'hidden'
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			width : 130,
			readonly : true,
			process : function($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_esmproject({
					nameCol : 'projectName',
					hiddenId : 'importTable_cmp_projectId' + rowNum,
					isDown : true,
					height : 250,
					gridOptions : {
						action : 'logProjectJson',
						param : {'selectstatus' : 'GCXMZT02'},
						isTitle : true,
						event : {
							row_dblclick : function(e, row, data) {
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"projectCode").val(data.projectCode);

								//ʡ�ݺͳ���
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"provinceId").val(data.provinceId);
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"province").val(data.province);
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"city").val(data.city);
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"projectEndDate").val(data.planEndDate);
								importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"maxLogDay").val(data.maxLogDay);
								//������е���Ϣ
								changeCity(rowNum,data.provinceId,data.cityId);

								//���
								clearActivityBatch(rowNum);

								//������Ⱦ
								initActivityBatch(data.id,rowNum);
							}
						}
					},
					event : {
						'clear' : function(){
							clearActivityBatch(rowNum);
						}
					}
				});
			},
			validation : {
				required : true
			}
		}, {
			display : '��ĿԤ�ƽ�������',
			name : 'projectEndDate',
			type : 'hidden'
		}, {
			display : '����id',
			name : 'activityId',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'activityName',
			width : 130,
			readonly : true,
			validation : {
				required : true
			}
		}, {
			display : '����Ԥ�ƽ�������',
			name : 'activityEndDate',
			type : 'hidden'
		}, {
			display : 'ʡ��',
			name : 'provinceId',
			type : 'select',
			width : 70,
			options : provinceArr,
			value : $("#provinceId").val(),
			event : {
				change : function(){
					var rowNum = $(this).data("rowNum");//�к�

					//ʡ�ݼ���
					var province = $(this).find("option:selected").text();
					importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"province").val(province);

					//������е���Ϣ
					changeCity(rowNum,$(this).val());
				}
			}
		}, {
			display : 'ʡ��',
			name : 'province',
			type : 'hidden',
			value : $("#province").val()
		}, {
			display : '����',
			name : 'cityId',
			type : 'select',
			width : 70,
			options : cityArr,
			value : $("#cityId").val(),
			event : {
				change : function(){
                    var rowNum = $(this).data("rowNum");//�к�

					//ʡ�ݼ���
					var city = $(this).find("option:selected").text();
					importTableObj.yxeditgrid("getCmpByRowAndCol",rowNum,"city").val(city);
				}
			}
		}, {
			display : '����',
			name : 'city',
			type : 'hidden',
			value : $("#city").val()
		}, {
			display : '����',
			name : 'country',
			type : 'hidden',
			value : '�й�'
		}, {
			display : '����id',
			name : 'countryId',
			type : 'hidden',
			value : '1'
		}, {
			display : '�����',
			name : 'workloadDay',
			width : 70,
			event : {
				blur : function(){
                    var rowNum = $(this).data("rowNum");//�к�
					calTaskProcessBatch(rowNum,$(this).val());
				}
			}
		}, {
			display : '��λ',
			name : 'workloadUnitView',
            tclass : 'readOnlyTxtShort',
            readonly : true,
			width : 80
		}, {
			display : '��λ',
			name : 'workloadUnit',
			width : 80,
			type : 'hidden'
		}, {
			display : '��Ŀ��������',
			name : 'maxLogDay',
			type : 'hidden'
		}, {
			display : '����',
			name : 'workProcess',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 80
		}, {
			display : '�����������',
			name : 'thisActivityProcess',
			type : 'hidden'
		}, {
			display : '������Ŀ����',
			name : 'thisProjectProcess',
			type : 'hidden'
		}, {
			display : 'Ͷ�빤������(%)',
			name : 'inWorkRate',
			validation : {
				required : true
			},
			width : 90,
			event : {
				blur : function(){
					if(isNaN($(this).val()) || ($(this).val()*1 > 100 || $(this).val()*1 < 0)){
						alert('������ 0 �� 100 ���ڵ�����');
						$(this).val('');
					}
				}
			}
		}, {
			display : '����������',
			name : 'description',
			width : 160
		}, {
			display : '��ע',
			name : 'problem',
			width : 120
		}]
	})
}