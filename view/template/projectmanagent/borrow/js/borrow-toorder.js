$(function(){
   var isNum = $("#isNum").val();
   if(isNum == '0'){
        alert("�����õ���û�п���תΪ���۵�����");
        self.parent.tb_remove()
   }
});

function operate(){
           var operateType = $("input[name='operate']:checked").val();
           if(operateType == "�й�����ͬ"){
//              document.getElementById("orderTypeDis").style.display="none";
              document.getElementById("orderDis").style.display="";
           }else{
//              document.getElementById("orderTypeDis").style.display="";
              document.getElementById("orderDis").style.display="none";
           }
      }
function sub(){
     var operate = $("input[name='operate']:checked").val();
     var borrowId = $("#borrowId").val();
     if(operate == "�޹�����ͬ"){
          var orderType = $("input[name='orderType']:checked").val();
             showModalWin('?model=projectmanagent_borrow_borrow&action=ToOrder&borrowId='
	                             + borrowId
	                             + '&orderType='
	                             + orderType
								 +'&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
								 self.parent.tb_remove();
     }else if(operate == "�й�����ͬ"){
           var contractId = $("#contractId").val();
              if(contractId == ''){
                 alert("��ѡ���ͬ��")
	          }else{
	             showOpenWin('?model=projectmanagent_borrow_borrow&action=toOrderBecome'
		                             + '&contractId='
		                             + contractId
		                             + '&borrowId='
		                             + borrowId
									 +'&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
									 self.parent.tb_remove();
	         }
          }
}
     //���� ��ͬ
      $(function(){
           $("#contractCode").yxcombogrid_allcontract({
							hiddenId : 'id',
							searchName : 'contractCode',
							isShowButton:false,
							isDown : false,
							gridOptions : {
								showcheckbox : false,
								param : {'ExaStatusArr' : '���,δ����','states_t' : '0','prinvipalId' : $("#userId").val()},
								event : {
									'row_dblclick' : function(e, row, data) {
										$("#contractCode").val(data.contractCode);
										$("#contractId").val(data.id);
										$("#contractType").val(data.contractType);

									}
								}
							}
						});
      });