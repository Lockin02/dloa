<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
?>
<HTML>
<HEAD>
<TITLE>HTML编辑器</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
</HEAD>
<STYLE>
BODY    {margin: 0pt; padding: 0pt; border: none}
IFRAME    {width: 100%; height: 100%; border: none}
</STYLE>
<SCRIPT language="jscript">
var format = "HTML";
var initHTML = "";
var edit;
var RangeType;
function setFocus() {
    textEdit.focus();
}
function selectRange(){
    edit = textEdit.document.selection.createRange();
    RangeType = textEdit.document.selection.type;
}
function checkeState(){
    if(format == "HTML") return true;
    if(format == "ABC"){
        alert("操作无效，现在是HTML状态！请切换到普通状态，才能进行编辑操作！");
    }
    if(format == "PREVIEW"){
        alert("操作无效，现在是预览状态！请切换到普通状态，才能进行编辑操作！");
    }
    return false;
}
function execCommand(command) {    
    if (format == "HTML"){
        setFocus();
        selectRange();    
        if ((command == "Undo") || (command == "Redo"))
            document.execCommand(command);
        else{
            if (arguments[1]==null)                
                edit.execCommand(command);
            else
                edit.execCommand(command, false, arguments[1]);}
        textEdit.focus();
        if (RangeType != "Control") edit.select();
    }else{
        if(format == "ABC"){
        alert("操作无效，现在是HTML状态！请切换到普通状态，才能进行编辑操作！");
        }
        if(format == "PREVIEW"){
        alert("操作无效，现在是预览状态！请切换到普通状态，才能进行编辑操作！");
        }
    }    
}

var borderShown = 0
function sBorders(){
    if(checkeState()){
        allTables = textEdit.document.body.getElementsByTagName("TABLE");
        for(i=0; i < allTables.length; i++){
            if(!borderShown)
                allTables[i].runtimeStyle.borderTop = allTables[i].runtimeStyle.borderLeft = "1px dotted #BFBFBF";
            else
                allTables[i].runtimeStyle.cssText = '';
            allRows = allTables[i].rows;
            for(y=0; y < allRows.length; y++){
                allCells = allRows[y].cells;
                for(x=0; x < allCells.length; x++)
                    if(!borderShown)
                        allCells[x].runtimeStyle.borderRight = allCells[x].runtimeStyle.borderBottom = "1px dotted #BFBFBF";
                    else
                        allCells[x].runtimeStyle.cssText = '';
            }
        }
        borderShown = borderShown ? 0 : 1;
        if(!borderShown)
            textEdit.document.body.innerHTML = textEdit.document.body.innerHTML;
    }
}

function isTableSelected(){
    textEdit.focus()
    if(textEdit.document.selection.type == "Control"){
         var oControlRange = textEdit.document.selection.createRange();
        if(oControlRange(0).tagName.toUpperCase() == "TABLE"){
            selectedTable = textEdit.document.selection.createRange()(0);
            return true;
        }
    }
}
function isCursorInTableCell(){
    textEdit.focus();
    if(document.selection.type != "Control"){
        var elem = document.selection.createRange().parentElement();
        while(elem.tagName.toUpperCase() != "TD" && elem.tagName.toUpperCase() != "TH"){
            elem = elem.parentElement
            if(elem == null)break
        }
        if(elem){
            selectedTD = elem
            selectedTR = selectedTD.parentElement
            selectedTBODY =  selectedTR.parentElement
            selectedTable = selectedTBODY.parentElement
            return true
        }
    }
}

function ModifyTable(sNewTable){
    if(checkeState()){
        if(isTableSelected() || isCursorInTableCell()){
            res = showModalDialog('tablemod.php?langtype=cn', selectedTable, 'dialogWidth: 360px; dialogHeight: 210px; center: yes; resizable: no; scroll: no; status: no;');
            if(res){
                if(res.width)
                    selectedTable.width = res.width;
                else
                    selectedTable.removeAttribute('width',0);
                if(res.cellPadding)
                    selectedTable.cellPadding = res.cellPadding;
                else
                    selectedTable.removeAttribute('cellPadding',0);
                if(res.bgColor)
                    selectedTable.bgColor = res.bgColor;
                else
                    selectedTable.removeAttribute('bgColor',0);
                if(res.background)
                    selectedTable.background = res.background;
                else
                    selectedTable.removeAttribute('background',0);
                if(res.cellSpacing)
                    selectedTable.cellSpacing = res.cellSpacing;
                else
                    selectedTable.removeAttribute('cellSpacing',0);
                if(res.border)
                    selectedTable.border = res.border;
                else
                    selectedTable.removeAttribute('border',0);
            }
        }
    }
}

function ModifyCell(){
    if(checkeState()){
        if(isCursorInTableCell()){
            res = showModalDialog('cell.php?langtype=cn', selectedTD, 'dialogWidth: 370px; dialogHeight: 210px; center: yes; resizable: no; scroll: no; status: no;');
            if(res){
                selectedTD.colSpan = res.colSpan;
                selectedTD.rowSpan = res.rowSpan;
                if(res.width)
                    selectedTD.width = res.width;
                else
                    selectedTD.removeAttribute('width',0);
                if(res.height)
                    selectedTD.height = res.height;
                else
                    selectedTD.removeAttribute('height',0);
                if(res.bgColor)
                    selectedTD.bgColor = res.bgColor;
                else
                    selectedTD.removeAttribute('bgColor',0);
                if(res.background)
                    selectedTD.background = res.background;
                else
                    selectedTD.removeAttribute('background',0);
                if(res.align && !res.align.match(/^None$/i))
                    selectedTD.align = res.align;
                else
                    selectedTD.removeAttribute('align',0);
                if(res.vAlign && !res.vAlign.match(/^None$/i))
                    selectedTD.vAlign = res.vAlign;
                else
                    selectedTD.removeAttribute('vAlign',0);
            }
        }
        else{
            alert("修改单元格属性前，需将光标停留在需要修改的单元格中！");
        }
    }
}

function insrowabove(){
    if(checkeState()){
        if(isCursorInTableCell()){
            var numCols = 0
            allCells = selectedTR.cells
            for(var i=0;i<allCells.length;i++) 
                numCols = numCols + allCells[i].getAttribute('colSpan')
            var newTR = selectedTable.insertRow(selectedTR.rowIndex)
            for(i = 0; i < numCols; i++){
                newTD = newTR.insertCell()
                newTD.innerHTML = " "
            }
        }
    }
}
function insrowbelow(){
    if(checkeState()){
        if(isCursorInTableCell()){
            var numCols = 0
            allCells = selectedTR.cells
            for(var i=0;i<allCells.length;i++)
                numCols = numCols + allCells[i].getAttribute('colSpan')
            var newTR = selectedTable.insertRow(selectedTR.rowIndex+1)
            for (i = 0; i < numCols; i++){
                newTD = newTR.insertCell()
                newTD.innerHTML = " "
            }
        }
    }
}
function deleterow(){
    if(checkeState()){
        if(isCursorInTableCell())
            selectedTable.deleteRow(selectedTR.rowIndex)
    }
}
function about()
{
    var helpmess;
    helpmess="HTML在线编辑器v1.0.0 \r\n\r\n";
    alert(helpmess);

}
function deletecol(){
    if(checkeState()){
        if(isCursorInTableCell()){
            moveFromEnd = (selectedTR.cells.length-1) - (selectedTD.cellIndex)
            allRows = selectedTable.rows
            for(var i=0;i<allRows.length;i++){
                endOfRow = allRows[i].cells.length - 1
                position = endOfRow - moveFromEnd
                if(position < 0)
                    position = 0
                allCellsInRow = allRows[i].cells
                if(allCellsInRow[position].colSpan > 1)
                    allCellsInRow[position].colSpan = allCellsInRow[position].colSpan - 1
                else
                    allRows[i].deleteCell(position)
            }
        }
    }
}
</script>
<body>
</body>
</HTML>