$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		objName : 'qualitytask[items]',
		url : '?model=produce_quality_qualitytaskitem&action=editItemJson',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		title : '�ʼ�������ϸ',
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		}, {
			name : 'productCode',
			display : '���ϱ��'
		}, {
			name : 'productName',
			display : '��������'
		}, {
			name : 'pattern',
			tclass : 'txt',
			tclass : 'readOnlyTxtItem',
			display : '�ͺ�'
		}, {
			name : 'unitName',
			tclass : 'readOnlyTxtItem',
			display : '��λ'
		}, {
			name : 'checkTypeName',
			display : '�ʼ췽ʽ'
		}, {
			name : 'assignNum',
			display : '�´�����',
			process : function(v,row){
				return Number(v);
			}
		}, {
			name : 'checkedNum',
			display : '���ʼ�����',
			process : function(v,row){
				return (row.realCheckNum >= 0 && row.realCheckNum != '')? row.realCheckNum : Number(v);
			}
		}, {
			name : 'standardNum',
			display : '�ϸ�����',
			process : function(v,row){
				return (row.checkStatus == "")? "-" : Number(v);
			}
		}, {
            name : 'unStandardNum',
            display : '���ϸ�����',
            process : function(v,row){
            	var produceNum = 0;
				if(row.realCheckNum >= 0 && row.realCheckNum != ''){
					produceNum = row.realCheckNum-row.standardNum;
				}else{
					produceNum = row.checkedNum-row.standardNum;
				}
                return (row.checkStatus == "")? "-" : produceNum;
            }
        },{
            name : 'qualitedRate',
            display : '�ϸ���',
            process : function(v,row){
                if(v!=""){
                    var str = (row.checkStatus == "")? "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=produce_quality_qualityereport&action=toItempage&type=task&sourceId=" + row.id  +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>-</a>" :
					"<a href='javascript:void(0)' onclick='showOpenWin(\"?model=produce_quality_qualityereport&action=toItempage&type=task&sourceId=" + row.id  +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
					return str;
                }else{
                    return v;
                }
            }
        },{
			name : 'checkStatus',
			display : '����״̬',
			process : function(v) {
				switch(v){
					case "YJY" : return "�Ѽ���"; break;
					case "" : return "δ����"; break;
//					case "YBCBG" : return "�ѱ��汨��"; break;
//					case "BH" : return "����"; break;
					case "BFJY" : return "���ּ���"; break;
					default : return "�Ƿ�״̬";
				}
			}
		}]
	})
})