function toSearch() {
    var formCode = $.trim($("#formCode").val());
    var prePaidMonth = $.trim($("#prePaidMonth").val());
    var signCompany = $.trim($("#signCompany").val());

    var cooperationType = $.trim($("#cooperationType").val());
    var projectCode = $.trim($("#projectCode").val());
    var projectName = $.trim($("#projectName").val());

    var suppName = $.trim($("#suppName").val());
    var taxPoint = $.trim($("#taxPoint").val());
    var officeName = $.trim($("#officeName").val());

    var province = $.trim($("#province").val());
    var outContractCode = $.trim($("#outContractCode").val());
    var projectTime = $.trim($("#projectTime").val());

    var projectTypeName = $.trim($("#projectTypeName").val());
    var beginDateSta = $.trim($("#beginDateSta").val());
    var beginDateEnd = $.trim($("#beginDateEnd").val());
    var endDateSta = $.trim($("#endDateSta").val());
    var endDateEnd = $.trim($("#endDateEnd").val());

    var outBeginDateSta = $.trim($("#outBeginDateSta").val());
    var outBeginDateEnd = $.trim($("#outBeginDateEnd").val());
    var outEndDateSta = $.trim($("#outEndDateSta").val());
    var outEndDateEnd = $.trim($("#outEndDateEnd").val());
    var settlementType = $.trim($("#settlementType").val());

    var projectStatusName = $.trim($("#projectStatusName").val());
    var takeInvoiceDateSta = $.trim($("#takeInvoiceDateSta").val());
    var takeInvoiceDateEnd = $.trim($("#takeInvoiceDateEnd").val());
    var takePayDateSta = $.trim($("#takePayDateSta").val());
    var takePayDateEnd = $.trim($("#takePayDateEnd").val());

    //主列表对象获取
    var listGrid = parent.$("#prepaidGrid").data('yxgrid');
    //设置值以及传输列表参数
    setVal(listGrid, 'formCode', formCode);
    setVal(listGrid, 'prePaidMonth', prePaidMonth);
    setVal(listGrid, 'signCompany', signCompany);

    setVal(listGrid, 'cooperationType', cooperationType);
    setVal(listGrid, 'projectCode', projectCode);
    setVal(listGrid, 'projectName', projectName);

    setVal(listGrid, 'suppName', suppName);
    setVal(listGrid, 'taxPointSea', taxPoint);
    setVal(listGrid, 'officeName', officeName);

    setVal(listGrid, 'province', province);
    setVal(listGrid, 'outContractCode', outContractCode);
    setVal(listGrid, 'projectTime', projectTime);

    setVal(listGrid, 'projectTypeName', projectTypeName);
    setVal(listGrid, 'beginDateSta', beginDateSta);
    setVal(listGrid, 'beginDateEnd', beginDateEnd);
    setVal(listGrid, 'endDateSta', endDateSta);
    setVal(listGrid, 'endDateEnd', endDateEnd);

    setVal(listGrid, 'outBeginDateSta', outBeginDateSta);
    setVal(listGrid, 'outBeginDateEnd', outBeginDateEnd);
    setVal(listGrid, 'outEndDateSta', outEndDateSta);
    setVal(listGrid, 'outEndDateEnd', outEndDateEnd);
    setVal(listGrid, 'settlementType', settlementType);

    setVal(listGrid, 'projectStatusName', projectStatusName);
    setVal(listGrid, 'takeInvoiceDateSta', takeInvoiceDateSta);
    setVal(listGrid, 'takeInvoiceDateEnd', takeInvoiceDateEnd);
    setVal(listGrid, 'takePayDateSta', takePayDateSta);
    setVal(listGrid, 'takePayDateEnd', takePayDateEnd);

    //刷新列表
    listGrid.reload();
    closeFun();
}

function setVal(obj, thisKey, thisVal) {
    return obj.options.extParam[thisKey] = thisVal;
}

//清空
function toClear() {
    $(".txt").val('');
    $(".txtshort").val('');
}