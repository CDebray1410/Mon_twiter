let changeInput = function()
{

    var buttonTelephone = document.getElementById("telephone");

    buttonTelephone.onclick = function()
    {
        document.getElementById("input").innerHTML = "<label for='telephone'>Votre téléphone ou <button id='email'>adresse mail</button></label>" +
                                                                 "<input class='u-full-width' type='tel' placeholder='0669567123' id='telephone' name='telephone' maxlength='10' minlength='10' pattern='0[0-9]{9}' required>"; 
                                                                
        var buttonEmail = document.getElementById("email");

        buttonEmail.onclick = function()
        {
            document.getElementById("input").innerHTML = "<label for='email'>Votre adresse mail ou <button id='telephone'>téléphone</button></label>" +
                                                                     "<input class='u-full-width' type='email' placeholder='exemple@gmail.com' id='email' name='email' required>";                                                            
            changeInput();
        }
    }
}
changeInput();