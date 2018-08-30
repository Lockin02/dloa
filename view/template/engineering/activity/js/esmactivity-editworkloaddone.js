/**
 * Created by show on 14-6-9.
 */
$(function(){
    $("form").submit(function(){
        if($("#workloadDone").val()*1 > $("#workloadCount").val()*1){
            alert('工作量只能变小！重新填写');
            return false;
        }
    });
});