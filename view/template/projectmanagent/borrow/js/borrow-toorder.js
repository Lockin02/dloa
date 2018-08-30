$(function(){
   var isNum = $("#isNum").val();
   if(isNum == '0'){
        alert("借试用单据没有可以转为销售的物料");
        self.parent.tb_remove()
   }
});

function operate(){
           var operateType = $("input[name='operate']:checked").val();
           if(operateType == "有关联合同"){
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
     if(operate == "无关联合同"){
          var orderType = $("input[name='orderType']:checked").val();
             showModalWin('?model=projectmanagent_borrow_borrow&action=ToOrder&borrowId='
	                             + borrowId
	                             + '&orderType='
	                             + orderType
								 +'&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
								 self.parent.tb_remove();
     }else if(operate == "有关联合同"){
           var contractId = $("#contractId").val();
              if(contractId == ''){
                 alert("请选择合同！")
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
     //加载 合同
      $(function(){
           $("#contractCode").yxcombogrid_allcontract({
							hiddenId : 'id',
							searchName : 'contractCode',
							isShowButton:false,
							isDown : false,
							gridOptions : {
								showcheckbox : false,
								param : {'ExaStatusArr' : '完成,未审批','states_t' : '0','prinvipalId' : $("#userId").val()},
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