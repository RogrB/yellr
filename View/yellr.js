




// GAMMAL
// JS Valideringsfunksjoner:
function validerFornavn() {
    var regEx = /^[a-zA-ZøæåØÆÅ .\- ]{2,25}$/;
    var ok = regEx.test(document.skjema.fornavn.value);
    if (!ok) {
        document.getElementById("feilfornavn").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i fornavn, må være mellom 2 og 25 bokstaver";
        document.getElementById("feilfornavn").style.color = "red";
        return false;
    }
    document.getElementById("feilfornavn").innerHTML = "<i class='glyphicon glyphicon-ok'></i>";
    document.getElementById("feilfornavn").style.color = "green";
    return true;
}
function validerEtternavn() {
    var regEx = /^[a-zA-ZøæåØÆÅ .\- ]{2,25}$/;
    var ok = regEx.test(document.skjema.etternavn.value);
    if (!ok) {
        document.getElementById("feiletternavn").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i etternavn, må være mellom 2 og 25 bokstaver";
        document.getElementById("feiletternavn").style.color = "red";
        return false;
    }
    document.getElementById("feiletternavn").innerHTML = "<i class='glyphicon glyphicon-ok'></i>";
    document.getElementById("feiletternavn").style.color = "green";
    return true;
}
function validerEpost() {
    var regEx = /^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    var ok = regEx.test(document.skjema.epost.value);
    if (!ok) {
        document.getElementById("feilepost").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i Epost, må være gyldig epostadresse";
        document.getElementById("feilepost").style.color = "red";
        return false;
    }
    document.getElementById("feilepost").innerHTML = "<i class='glyphicon glyphicon-ok'></i>";
    document.getElementById("feilepost").style.color = "green";
    return true;
}
function validerNotat() {
    var regEx = /^[a-zA-ZøæåØÆÅ0-9 ,.\-@_%?! ]{2,256}$/;
    var ok = regEx.test(document.skjema.notat.value);
    if (!ok) {
        document.getElementById("feilnotat").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i Notat, må være må være mellom 2 og 256 bokstaver eller tall";
        document.getElementById("feilnotat").style.color = "red";
        return false;
    }
    document.getElementById("feilnotat").innerHTML = "<i class='glyphicon glyphicon-ok'></i>";
    document.getElementById("feilnotat").style.color = "green";
    return true;
}
function validerSpesial(type) {
    // Type input for å bestemme hvilken type regex som skal testes
    var regEx;
    if (type === "deltaker") {
        // For deltaker - regex felt = notat
        regEx = /^[a-zA-ZøæåØÆÅ0-9 ,.\-@_%? ]{2,256}$/;
    }
    else if (type === "publikum") {
        // For publikum - regex felt = epost
        regEx = /^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    }
    var ok = regEx.test(document.skjema.spesialfelt.value);
    if (!ok) {
        // Skriver ut riktig feilmelding avhengig av hvilken type spesialfelt (epost eller notat)
        if (type === "deltaker") {
            document.getElementById("feilspesial").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i Notat, må være må være mellom 2 og 256 bokstaver eller tall";
            document.getElementById("feilspesial").style.color = "red";
        }
        else if (type === "publikum") {
            document.getElementById("feilspesial").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i Epost, må være gyldig epostadresse";
            document.getElementById("feilspesial").style.color = "red";
        }
        return false;
    }
    document.getElementById("feilspesial").innerHTML = "<i class='glyphicon glyphicon-ok'></i>";
    document.getElementById("feilspesial").style.color = "green";
    return true;
}
function validerAktivitetNavn() {
    var regEx = /^[a-zA-ZøæåØÆÅ0-9 ,.\-@_%?! ]{2,50}$/;
    var ok = regEx.test(document.skjema.aktivitetnavn.value);
    if (!ok) {
        document.getElementById("feilaktivitetnavn").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i Aktivitetnavn, må være må være mellom 2 og 50 bokstaver eller tall";
        document.getElementById("feilaktivitetnavn").style.color = "red";
        return false;
    }
    document.getElementById("feilaktivitetnavn").innerHTML = "<i class='glyphicon glyphicon-ok'></i>";
    document.getElementById("feilaktivitetnavn").style.color = "green";
    return true;
}
function validerTid() {
    var regEx = /^[0-9][0-9][0-9][0-9]$/;
    var ok = regEx.test(document.skjema.tid.value);
    if (!ok) {
        document.getElementById("feiltid").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i Tid, må være må være 4 tall";
        document.getElementById("feiltid").style.color = "red";
        return false;
    }
    document.getElementById("feiltid").innerHTML = "<i class='glyphicon glyphicon-ok'></i>";
    document.getElementById("feiltid").style.color = "green";
    return true;
}
function validerPremie() {
    var regEx = /^[a-zA-ZøæåØÆÅ0-9 ,.\-@_%?! ]{2,50}$/;
    var ok = regEx.test(document.skjema.premie.value);
    if (!ok) {
        document.getElementById("feilpremie").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i Premie, må være må være mellom 2 og 50 bokstaver eller tall";
        document.getElementById("feilpremie").style.color = "red";
        return false;
    }
    document.getElementById("feilpremie").innerHTML = "<i class='glyphicon glyphicon-ok'></i>";
    document.getElementById("feilpremie").style.color = "green";
    return true;
}
function validerBeskrivelse() {
    var regEx = /^[a-zA-ZøæåØÆÅ0-9 ,.\-@_%?! ]{2,1024}$/;
    var ok = regEx.test(document.skjema.beskrivelse.value);
    if (!ok) {
        document.getElementById("feilbeskrivelse").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i Beskrivelse, må være må være mellom 2 og 1024 bokstaver eller tall";
        document.getElementById("feilbeskrivelse").style.color = "red";
        return false;
    }
    document.getElementById("feilbeskrivelse").innerHTML = "<i class='glyphicon glyphicon-ok'></i>";
    document.getElementById("feilbeskrivelse").style.color = "green";
    return true;
}
function validerBrukernavn() {
    var regEx = /^[a-zA-ZøæåØÆÅ0-9 ,.\-@_%?! ]{2,50}$/;
    var ok = regEx.test(document.skjema.brukernavn.value);
    if (!ok) {
        document.getElementById("feilbrukernavn").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i Brukernavn, må være må være mellom 2 og 50 bokstaver eller tall";
        document.getElementById("feilbrukernavn").style.color = "red";
        return false;
    }
    document.getElementById("feilbrukernavn").innerHTML = "";
    return true;
}
function validerPassord() {
    var regEx = /^[a-zA-ZøæåØÆÅ0-9 ,.\-@_%?! ]{2,50}$/;
    var ok = regEx.test(document.skjema.passord.value);
    if (!ok) {
        document.getElementById("feilpassord").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i Passord, må være må være mellom 2 og 50 bokstaver eller tall";
        document.getElementById("feilpassord").style.color = "red";
        return false;
    }
    document.getElementById("feilpassord").innerHTML = "";
    var pass1 = document.getElementById("passord").value;
    var pass2 = document.getElementById("passord2").value;    
    if (document.skjema.passord2.value !== "") {
        if (pass1 !== pass2) {
            document.getElementById("feilpassord2").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Passordene må være like";
            document.getElementById("feilpassord2").style.color = "red";
            return false;            
        }
    }    
    return true;
}
function validerPassord2() {
    var regEx = /^[a-zA-ZøæåØÆÅ0-9 ,.\-@_%?! ]{2,50}$/;
    var ok = regEx.test(document.skjema.passord2.value);
    if (!ok) {
        document.getElementById("feilpassord2").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Feil i Passord, må være må være mellom 2 og 50 bokstaver eller tall";
        document.getElementById("feilpassord2").style.color = "red";
        return false;
    }
    document.getElementById("feilpassord2").innerHTML = "";   
    var pass1 = document.getElementById("passord").value;
    var pass2 = document.getElementById("passord2").value;
    if (document.skjema.passord.value !== "") {
        if (pass1 !== pass2) {
            document.getElementById("feilpassord2").innerHTML = "<i class='glyphicon glyphicon-remove'></i> - Passordene må være like";
            document.getElementById("feilpassord2").style.color = "red";
            return false;            
        }
    }
    return true;
}
function validerPublikum() {
    var ok1 = validerFornavn();
    var ok2 = validerEtternavn();
    var ok3 = validerEpost();
    if (ok1 === false || ok2 === false || ok3 === false) {
        return false;
    } else {
        return true;
    }
}
function validerDeltaker() {
    var ok1 = validerFornavn();
    var ok2 = validerEtternavn();
    var ok3 = validerNotat();
    if (ok1 === false || ok2 === false || ok3 === false) {
        return false;
    } else {
        return true;
    }
}
function validerEditPerson(type) {
    var ok1 = validerFornavn();
    var ok2 = validerEtternavn();
    var ok3 = validerSpesial(type);
    if (ok1 === false || ok2 === false || ok3 === false) {
        return false;
    } else {
        return true;
    }
}
function validerAktivitet() {
    var ok1 = validerAktivitetNavn();
    var ok2 = validerTid();
    var ok3 = validerPremie();
    var ok4 = validerBeskrivelse();
    if (ok1 === false || ok2 === false || ok3 === false || ok4 === false) {
        return false;
    } else {
        return true;
    }
}
function validerAdmin() {
    var ok1 = validerBrukernavn();
    var ok2 = validerPassord();
    var ok3 = validerPassord2();
    if (ok1 === false || ok2 === false || ok3 === false) {
        return false;
    } else {
        return true;
    }
}

// Slide in animation JS
$(window).scroll(function() {
  $(".slideanim").each(function(){
    var pos = $(this).offset().top;

    var winTop = $(window).scrollTop();
    if (pos < winTop + 600) {
      $(this).addClass("slide");
    }
  });
});
