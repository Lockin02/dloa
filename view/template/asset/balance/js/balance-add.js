$(function() {
	/* ѡ���ʲ���� */
	$("#assetName").yxcombogrid_asset({
		nameCol : 'assetName',
		hiddenId : 'assetId',
		gridOptions : {
			param : {
				"isDel" : "0",
				"isTemp" : "0",
				"isDeprf" : "0"
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#assetCode").val(data.assetCode);
					$("#origina").val(data.origina);
					$("#buyDepr").val(data.buyDepr);
					$("#beginTime").val(data.beginTime);
					$("#estimateDay").val(data.estimateDay);
					$("#alreadyDay").val(data.alreadyDay);
					$("#depreciation").val(data.depreciation);
					$("#salvage").val(data.salvage);
					$("#netValue").val(data.netValue);
					$("#deprName").val(data.deprName);
					$("#wirteDate").val(data.wirteDate);
//					$("#isDel").val(data.isDel);
					$("#assetName").val(data.assetName);
					// alert($("#deprName").val());
					// ���㱾���۾���
					// if ($("#deprName").val() == 'ƽ�����޷�') {
					// �ʲ����۾ɶ�
					var allDepr = data.origina - data.salvage;
					// ���۾ɶ�
					var m = allDepr / data.estimateDay;
					// ���۾���
					var rate = m / data.origina;
					// ʣ���۾ɶ�
					var remain = allDepr - m - data.depreciation;

					$("#deprRate").val(rate*100);
					$("#deprShould").val(m);

					//�ж�ʣ���۾ɶ�Ϊ����ʱ����ʣ���۾ɶֵΪ0�����ڼ����۾�Ϊ����ʣ���۾ɶ�Ϊ0��ֵ
					if(remain<0){
						$("#deprRemain").val('0');
						$("#depr").val(allDepr - data.depreciation);
					}else{
						$("#deprRemain").val(remain);
						$("#depr").val(m);
					}

					$("#initDepr").val(data.depreciation);
					$("#period").val(data.alreadyDay * 1 + 1);
					// ��ȡ��ǰ�۾����
					var myDate = new Date();
					var y = myDate.getYear();
					$("#years").val(y);
					// }

					// ������ʲ������۾�
//					var isD = $("#isDel").val();

					//�۾�����ʲ������۾�
					var sal = $("#salvage").val()*1;
					var net = $("#netValue").val()*1;

					// �����ʲ�����ʱ��ĵ��£������۾�
					var year = new Date().getYear();
					var month = new Date().getMonth() + 1;
					var wirteDate = $("#wirteDate").val();
					var y = wirteDate.substr(0, 4);
					var m = wirteDate.substr(5, 2);
					if (year == y && month == m) {
						alert("�ʲ�����ʱ��Ϊ" + wirteDate + ",�ʲ���ʱ�����۾ɣ�");
						$("#deprBtn").attr("disabled", "disabled");
					}else if(net <= sal){
						alert("���ʲ��Ѿ��۾��꣡");
						$("#deprBtn").attr("disabled", "disabled");
					}
//					else if(isD == "1"){
//						alert("���ʲ��ѱ�����");
//						$("#deprBtn").attr("disabled", "disabled");
//					}
					else {
						// alert("�ʲ������۾ɣ�");
						$("#deprBtn").removeAttr("disabled");
					}

				}
			}
		}
	});
});

// ��ת���б�ҳ��
function toList() {
//	if ($("#assetCode").val() == "" || $("#assetName").val() == "") {
//		alert("����ò�ѯ������");
//		return false;
//	}
	location = '?model=asset_balance_balance&action=page' + '&assetId='
			+ $("#assetId").val();
	return true;
}

function reload(){
	$("#deprBtn").removeAttr("disabled");
}

