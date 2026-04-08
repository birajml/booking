function validateForm(){
    let d = document.querySelector("input[name='date']").value;
    if(d==""){
        alert("Select date");
        return false;
    }
    return true;
}