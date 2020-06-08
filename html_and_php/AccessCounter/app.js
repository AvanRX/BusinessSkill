
window.onload=()=>{
    fetch("getCounter.php")
    .then((res)=>{return(res.json())})
    .then((json)=>{
        if(json["status"]){
            const count=document.querySelector("#count");
            count.innerHTML=json["count"];
        }
        else{
            alert("ERROR BY API");
        }
    })
    .catch((error)=>{alert("ERROR BY COMMUNICATION")});
}

document.querySelector("#btn-reload").addEventListener("click",()=>{
    location.reload();
});