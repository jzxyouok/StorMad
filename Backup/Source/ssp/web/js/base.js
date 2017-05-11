// JavaScript Document
/*
设置页面高宽
*/
(function(){
function setBodyHeight(gt,head){	
try{
  var objGt=document.getElementById(gt);
  var viewH=window.innerHeight;
  var objHead=document.getElementById(head);
  if(objGt.clientHeight+objHead.clientHeight<viewH){
	 document.body.style.height=viewH+'px'; 
  }
  else{
	   var winH=objHead.clientHeight+objGt.clientHeight+40; 
  document.body.style.height=winH+'px'; 
   }  
}
catch(err){
	console.log(err)
 } 
}
window.onload=function(){setBodyHeight('gt','head');setBodyHeight('gt2','head2');}
window.onresize = function () { setBodyHeight('gt','head');setBodyHeight('gt2','head2');} 
})()
 