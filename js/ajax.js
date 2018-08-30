/**
 * use ajax
 */
//create XMLHttpRequest object
var req;
function createReq(){

    // IE browser
    try {
        req = new ActiveXObject("Msxml2.XMLHTTP");
    } 
    catch (e) {
        req = false;
    }
    if (!req) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        } 
        catch (e2) {
            req = false;
        }
    }
    // other browsers
    if (!req) {
        try {
            req = new XMLHttpRequest()
        } 
        catch (e2) {
            req = false;
        }
    }
    return req;
}


