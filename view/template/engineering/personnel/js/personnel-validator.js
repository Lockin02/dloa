var userLevelArr = [];//�����ȼ�����

/**
 * ������鵽����
 * @param {} code
 * @param {} selectId
 */
function addDataToSelectCus(data, selectId) {
	for (var i = 0, l = data.length; i < l; i++) {
		$("#" + selectId).append("<option value='" + data[i].levelName + "'>"
				+ data[i].levelName + "</option>");
	}
}

$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
        }
    });

     /**��֤Ա������ **/
     $("#userName").formValidator({
         onshow:"������Ա������",
         onfocus:"Ա����������2���ַ������50���ַ�",
         oncorrect:"OK!"
     }).inputValidator({
         min:2,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"Ա���������߲����пշ���"
         },
         onerror:"����������Ʋ��Ϸ�������������"
     });

     /**�������´���֤**/
     $("#officeName").formValidator({
     	 onshow:"��������´�����",
         onfocus:"���´�����2���ַ������50���ַ�",
         oncorrect:"OK!"
     }).inputValidator({
         min:2,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"���´��������߲����пշ���"
         },
         onerror:"������İ��´����Ϸ�������������"
     });

     // ��Ʒ������
	userLevelArr = getDataCus('','index1.php?model=engineering_assessment_assPeopleLevel&action=pageJson','levelName');
	if (!$("#contNumber").val()) {
		addDataToSelectCus(userLevelArr, 'userLevel','id','levelName');
	}

	// �ȼ�����ѡ��
	$("#userLevel").val(userLevel);
});

