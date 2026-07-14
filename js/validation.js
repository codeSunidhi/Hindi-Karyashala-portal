function validateForm()
{

    let ic = document.getElementById("ic_no").value.trim();

    let password = document.getElementById("password").value.trim();

    let role = document.getElementById("role").value;

    let error = document.getElementById("error");

    error.innerHTML="";

    if(ic=="")
    {
        error.innerHTML="Please enter IC Number";
        return false;
    }

    if(ic.length!=4)
    {
        error.innerHTML="IC Number must be 4 digits";
        return false;
    }

    if(password=="")
    {
        error.innerHTML="Please enter Password";
        return false;
    }

    if(role=="")
    {
        error.innerHTML="Please select Login Role";
        return false;
    }

    return true;

}