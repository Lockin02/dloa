$(document).ready(function() {
	if($('#auditStatusHidden').val() != "WSH"){
		//���ó�ʼֵ
		setSelect('auditStatus');
	}
	var relDocType = $("#relDocType").val();
	var show = '';
	if(relDocType != 'ZJSQYDSL'){
		show = 'hidden';
	}
	//�ʼ�����
	$("#ereportequitem").yxeditgrid({
		objName : 'qualityereport[ereportequitem]',
		title : '������Ϣ��������',
		url : "?model=produce_quality_qualityereportequitem&action=listJson",
		param : {"mainId" : $("#id").val() },
		isAddAndDel : false,
		event : {
			'reloadData' : function(){
				//��ʼ�� ���ϸ�
				initConfirm();
				//����Ϊ�����黹ʱ��ʼ��
				if(relDocType != 'ZJSQYDSL' && relDocType != 'ZJSQYDSC'){
					initHHGH();
				}else if(relDocType == 'ZJSQYDSC'){
					initSC();
				}
			}
		},
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		}, {
			name : 'relItemId',
			display : 'relItemId',
			type : 'hidden'
		}, {
			name : 'productId',
			display : 'productId',
			type : 'hidden'
		}, {
			name : 'productCode',
			display : '���ϱ���',
			width : 80,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'productName',
			display : '��������',
			width : 130,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'pattern',
			display : '����ͺ�',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'supplierName',
			display : '��Ӧ��',
			width : 130,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'supportNum',
			display : '��������',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'thisCheckNum',
			display : '�����ʼ�����',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
            name: 'samplingNum',
            display: '�������',
            width : 70,
            tclass : 'readOnlyTxtMiddle',
            readonly : true
        }, {
			name : 'unitName',
			display : '��λ',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'supportTime',
			display : '����ʱ��',
			width : 130,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'relDocCode',
			display : 'Դ����',
			width : 90,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'purchaserName',
			display : '������',
			width : 90,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'priorityName',
			display : '�����̶�',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'qualitedNum',
			display : '�ϸ���',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'produceNum',
			display : '���ϸ���',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			event : {
				click : function(e){
					var rowNum = $(this).data("rowNum");
					var g = e.data.gird;
					var rowNum = e.data.rowNum;
					showThickboxWin("?model=produce_quality_serialno&action=toDealView"
						+"&relDocId="+ g.getCmpByRowAndCol(rowNum, 'relItemId').val()
						+"&relDocType=qualityEreport"
						+"&productId="+ g.getCmpByRowAndCol(rowNum, 'productId').val()
						+"&productCode="+ g.getCmpByRowAndCol(rowNum, 'productCode').val()
						+"&productName="+ g.getCmpByRowAndCol(rowNum, 'productName').val()
						+"&pattern="+ g.getCmpByRowAndCol(rowNum, 'pattern').val()
						+"&productNum=" + $(this).val()
						+"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
				}
		    },
			readonly : true
		}, {
			name : 'passNum',
			display : '������������',
			width : 70,
			event : {
				blur : function(){
					if(relDocType == 'ZJSQYDSL'){
						//�����ʼ���������
						countEquConfirmNum('passNum',$(this).data("rowNum"));
					}
				}
			},
			tclass : 'readOnlyTxtMiddle',
			readonly : true,
			type : show
		}, {
			name : 'receiveNum',
			display : '�ò���������',
			width : 70,
			event : {
				blur : function(){
					if(relDocType == 'ZJSQYDSL'){
						//�����ʼ���������
						countEquConfirmNum('receiveNum',$(this).data("rowNum"));
					}
				}
			},
			tclass : 'readOnlyTxtMiddle',
			readonly : true,
			type : show
		}, {
			name : 'backNum',
			display : '�˻�����',
			width : 70,
			event : {
				blur : function(){
					if(relDocType == 'ZJSQYDSL'){
						//�����ʼ���������
						countEquConfirmNum('backNum',$(this).data("rowNum"));
					}
				}
			},
			tclass : 'readOnlyTxtMiddle',
			readonly : true,
			type : show
		}, {
			name : 'remark',
			display : '��ע',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}]
	});

	//�ʼ�����
    var itemCols = getItemTable(relDocType);
	$("#itemTable").yxeditgrid({
		objName : 'qualityereport[items]',
		url : '?model=produce_quality_qualityereportitem&action=listJson',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		title : '�ʼ�����',
		colModel : itemCols,
        event : {
            'reloadData' : function(){
                //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
                if(relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH"){
                    //��ʼ�����ϸ񲿷�
                    showDetail();
                }
            }
        }
	});

	if(relDocType == 'ZJSQDLBF' && $('#auditStatusHidden').val() == 'YSH'){// PMS2386 ���ϱ����ʼ�����ʼ챨��ϸ��˵�,���Ϊ�ϸ��Ҳ��ɸ�
		$("#auditStatus").html('<option value="YSH" title="�ϸ�">�ϸ�</option>');
	}
});

/**
 * ��ʾ���ϸ���ϸ�б�
 */
function showDetail(){
    //��ȡ������Ŀ
    var dimensionNameArr = $("#itemTable").yxeditgrid("getCmpByCol", "dimensionName");
    var baseTitle = initFailureItemGrid(dimensionNameArr);
    $("#failureItem").yxeditgrid({
        url : '?model=produce_quality_failureitem&action=listJson',
        param : {
            mainId : $("#id").val()
        },
        type : 'view',
        title : '���ϸ�������Ϣ',
        colModel : baseTitle
    });
}

//��ʼ�����ϸ��б�����
function initFailureItemGrid(dimensionNameArr){
    //������Ŀ��ͷ
    var baseTitle = [{
        name : 'objId',
        display : 'objId',
        type : 'hidden'
    }, {
        name : 'objCode',
        display : 'objCode',
        type : 'hidden'
    }, {
        name : 'objType',
        display : 'objType',
        type : 'hidden'
    }, {
        name : 'objItemId',
        display : 'objItemId',
        type : 'hidden'
    }, {
        name : 'productId',
        display : 'productId',
        type : 'hidden'
    }, {
        name : 'productCode',
        tclass : 'readOnlyTxtMiddle',
        readonly : true,
        display : '���ϱ���',
        width : 80
    }, {
        name : 'productName',
        tclass : 'readOnlyTxtMiddle',
        readonly : true,
        display : '��������'
    }, {
        name : 'pattern',
        tclass : 'readOnlyTxtMiddle',
        readonly : true,
        display : '����ͺ�'
    }, {
        name : 'unitName',
        tclass : 'readOnlyTxtShort',
        readonly : true,
        display : '��λ'
    }, {
        name : 'serialNo',
        display : '���к�',
        validation : {
            required : true
        }
    }];

    //�����������
    dimensionNameArr.each(function(i){
        var resultNum = i + 1;
        baseTitle.push({
            name : 'result' + resultNum,
            display : $(this).val(),
            datacode : 'ZJJDJG',
            width : 80
        });
    });

    //�����������ݡ���ע����Ϣ
    baseTitle.push({
        name : 'resultName',
        display : '��������'
    });
    baseTitle.push({
        name : 'levelName',
        display : '��������'
    });
    baseTitle.push({
        name : 'remark',
        display : '��ע',
        tclass : 'txt',
        width : 120
    });
    return baseTitle;
}

//���ر���
function toRejectReport(){
    $('#templateInfo').dialog({
        title: '��д����ԭ��',
        width: 400,
        height: 200,
        modal: true
    }).dialog('open');
}

//ȷ�ϲ���
function rejectReport(){
    if(confirm('ȷ��Ҫ���ر�����')){
        $.ajax({
            type : "POST",
            url : "?model=produce_quality_qualityereport&action=rejectReport",
            data : {
                "id" : $("#id").val(),
                "reason" : $("#reason").val()
            },
            success : function(msg) {
                if (msg == 1) {
                    alert('���سɹ�');
                }else{
                    alert('����ʧ��');
                }
                opener.show_page();
                window.close();
            }
        });
    }
}

//��ȡ�ʼ����ݱ�ͷ
function getItemTable(relDocType){
    var colsArr = [{
        name : 'dimensionName',
        tclass : 'txt',
        display : '������Ŀ',
        validation : {
            required : true
        }
    }, {
        name : 'examTypeName',
        tclass : 'txt',
        display : '���鷽ʽ����'
    }];
    //����ǽ��ù黹
    // if(relDocType != "ZJSQYDGH" && relDocType == "ZJSQYDHH"){
        colsArr.push({
            name : 'crNum',
            width : 80,
            display : 'CR',
            process : function(v){
                if(v*1 != 0){
                    return "<span class='red'>" + v + "</span>";
                }else{
                    return v;
                }
            }
        });
        colsArr.push({
            name : 'maNum',
            width : 80,
            display : 'MA',
            process : function(v){
                if(v*1 != 0){
                    return "<span class='red'>" + v + "</span>";
                }else{
                    return v;
                }
            }
        });
        colsArr.push({
            name : 'miNum',
            width : 80,
            display : 'MI',
            process : function(v){
                if(v*1 != 0){
                    return "<span class='red'>" + v + "</span>";
                }else{
                    return v;
                }
            }
        });
        colsArr.push({
            name : 'qualitedNum',
            width : 80,
            display : '�ϸ�����'
        });
    // }
    colsArr.push({
        name : 'remark',
        width : 250,
        display : '��ע'
    });

    return colsArr;
}