/**
 * �����ʲ�����js
 */

$(function() {
			$("#sellTable").yxeditgrid({
				isAdd : false,
				objName : 'sell[item]',
				url:'?model=asset_assetcard_assetcard&action=getScrapListJson&assetIdArr=' + $("#assetId").val(),
				event : {
					removeRow : function(t, rowNum, rowData) {
						countAmount();
					},
					reloadData : function(){
						countAmount();
					}
				},
				colModel : [{
					display : '��Ƭ���',
					name : 'assetCode',
					readonly : true
				}, {
					display : '�ʲ�����',
					name : 'assetName',
					readonly : true
				}, {
					display : '�ʲ�Id',
					name : 'assetId',
					process : function($input,row){
							var assetId = row.id;
							$input.val(assetId);
						},
					type : 'hidden',
					readonly : true
				}, {
					display:'Ӣ������',
					name : 'englishName',
					tclass : 'txtshort',
					readonly:true
				}, {
					display:'��������',
					name : 'buyDate',
					type:'date',
					tclass : 'txtshort',
					readonly:true
				}, {
					display:'����ͺ�',
					name : 'spec',
					tclass : 'txtshort',
					readonly:true
				}, {
					display:'�����豸',
					name : 'equip',
					type:'statictext',
					process : function(e, data) {
						if (data) {
							var $href = $("<a>��ϸ</a>");
							$href.attr("href", "#");
							$href.click(function() {
								window.open('?model=asset_assetcard_equip&action=toPage&assetCode='
												+ data.assetCode);
							})
							return $href;
						} else {
							return '<a href="#" >��ϸ</a>';
						}
					}
				},{
					display:'�Ѿ�ʹ���ڼ���',//��ʹ���ڼ���alreadyDay
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly:true
				},{
					display:'�۳�����',//����
					name : 'deptName',
					tclass : 'txtshort'
				}, {
					display:'�۳�ǰ��;',//��;useType
					name : 'beforeUse',
					tclass : 'txtshort',
					readonly:true
				}, {
					display:'���۾ɽ��',//�ۼ��۾�depreciation
					name : 'depreciation',
					tclass : 'txtshort',
					type : 'money',
					readonly:true
				},{
					display:'�����ֵ',//Ԥ�ƾ���ֵsalvage
					name : 'salvage',
					tclass : 'txtshort',
					type : 'money',
					readonly:true
				}
//				, {
//					display:'���۾ɶ�',//��������������ġ�
//					name : 'monthDepr',
//					tclass : 'txtshort'
//				}
				, {
					display:'��ע',//�������ɸ�
					name : 'remark',
					tclass : 'txt'
				}]
			});
//ѡ����Ա���
	$("#seller").yxselect_user({
		hiddenId : 'sellerId',
		isGetDept:[true,"deptId","deptName"]
	});

 /**
	 * ��֤��Ϣ
	 */
	validate( {"billNo" : {
			required : true
		},"seller" : {
			required : true
		},"donationDate" : {
			required : true,
			custom : [ 'date' ]
//		},"sellNum" : {
//            required : true,
//            custom : [ 'onlyNumber' ]
        },"sellAmount" : {
            required : true,
            custom : [ 'money' ]
        }
	});
});
// ���ݴӱ�Ĳ�ֵ��̬����Ӧ���ܽ��
function countAmount() {
	// ��ȡ��ǰ����������Ƭ���ʲ���
	var curRowNum = $("#sellTable").yxeditgrid("getCurShowRowNum");
	$("#sellNum").val(curRowNum);

	var rowAmountVa = 0;
	var cmps = $("#sellTable").yxeditgrid("getCmpByCol", "salvage");
	cmps.each(function() {
				rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
			});
	$("#sellAmount").val(rowAmountVa);
	$("#sellAmount_v").val(moneyFormat2(rowAmountVa));
	return false;
}
/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_disposal_sell&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}


