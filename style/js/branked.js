/**
 *
 * @param ev
 * @param wordID
 * @param brLocation
 * @param timeLocation
 */
function updateBR(ev,wordID,brLocation,timeLocation){
    if (!brLocation || !timeLocation){
        alert('请求参数错误');
        return false;
    }
    brLocation = parseInt(brLocation-1);
    timeLocation = parseInt(timeLocation-1);
    var stemp = ev.parentNode.parentNode.children[brLocation].innerHTML;
    ev.parentNode.parentNode.children[brLocation].innerHTML='<img src="/style/images/loading.gif" width="40">';

    var xmlhttp;
    if (window.XMLHttpRequest){
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }else{
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function(){
        console.log(xmlhttp.status);
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            var res = JSON.parse(xmlhttp.responseText);

            if (res['newBR']) {
                ev.parentNode.parentNode.children[brLocation].innerHTML = res['newBR'];
            }else {
                ev.parentNode.parentNode.children[brLocation].innerHTML = stemp;
            }
            ev.parentNode.parentNode.children[timeLocation].innerHTML = res['newtime'];
        }
    }
    xmlhttp.open("GET","/api.php?op=updBr&wordID="+wordID,true);
    xmlhttp.send();

}

function autoUpdate(className){
    var updateDom = document.getElementsByClassName(className);
    var len = updateDom.length;

    for(i=0;i<len;i++){
        updateDom[i].click();
    }
}