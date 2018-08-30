/**
 * Created by show on 2014/11/15.
 */
var exportExcel = function() {
    window.open(
        '?model=finance_invother_invother&action=exportExcel&beginYear=' + $("#beginYear").val() +
        "&beginMonth=" + $("#beginMonth").val() +
        "&endYear=" + $("#endYear").val() +
        "&endMonth=" + $("#endMonth").val()
        ,
        '其他发票导出',
        'width=200,height=200,top=200,left=200'
    );
};