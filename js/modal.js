document.addEventListener("DOMContentLoaded",function(){

const modal=document.getElementById("reportModal");
const reportContent=document.getElementById("reportContent");
const close=document.querySelector(".close");

document.querySelectorAll(".viewBtn").forEach(function(button){

button.addEventListener("click",function(){

let reportId=this.getAttribute("data-id");

fetch("view_report.php?id="+reportId)

.then(function(response){

return response.text();

})

.then(function(data){

reportContent.innerHTML=data;
modal.style.display="block";

})

.catch(function(error){

console.log(error);

});

});

});

close.addEventListener("click",function(){

modal.style.display="none";
reportContent.innerHTML="";

});

window.addEventListener("click",function(e){

if(e.target==modal){

modal.style.display="none";
reportContent.innerHTML="";

}

});

});