/**
 * Created by show on 14-6-9.
 */
$(function(){
    $("form").submit(function(){
        if($("#workloadDone").val()*1 > $("#workloadCount").val()*1){
            alert('������ֻ�ܱ�С��������д');
            return false;
        }
    });
});