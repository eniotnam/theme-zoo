
$('.popup').hide();
$('.validate').hide();
$('select').hide();
$('.ani').hide();
$('#choose').on('click',function(){
    var IdUser = $('select').val();
    var IdUserCouple = ($('#id_couple').val())?$('#id_couple').val():null;
    var IdAnimals = $('#id_animals').val();
    //    console.log("user: "+IdUser+" couple: "+IdUserCouple+" animals "+IdAnimals);
    if (IdUser == "") {
        document.getElementById("txtHint").innerHTML="aucune personne sélectionné";
        return;
    } 
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementById("txtHint").innerHTML=this.responseText;
        }
    }
    xmlhttp.open("GET","getuser.php?a="+IdAnimals+"&b="+IdUser+"&c="+IdUserCouple,true);
    xmlhttp.send();
    $('.popup').hide();
    $('.validate').show();
});

function poppup(animals){
    var animal = animals.replace(' ','');
    var anima = animals.replace(/(é)/g,'e');
    var anima = anima.replace(/(è)/g,'e');

    verif(anima);
    $('.popup').show();
    $('h1').html(animals);
   
    $(".imageanimal").attr('src', 'img/'+anima+'c.png');
    var s =setTimeout(function(){
        if($('#id_animals').val()){
            $('select').show();
            $('.ani').show();
            $('#alert').html("");
        }
        else{
            $('select').hide();
            $('.ani').hide();
            $('#alert').html("<span style='color:red'>Cette animal est déjà pris</span>");
         
        }
    } ,500);
}

var t= setInterval(function(){
    couple = $('#txtHint').text();
    animals = $('#animals').html().replace(' ','');
    anima = animals.replace('è','e');
    anima = animals.replace(/(é)/g,'e');
    if(couple){
        if( $('#txtHint').text() && $('#txtHint').text() != "" && (couple.match("[a-z]")) ) {

            $(".imageanimal").attr('src', 'img/'+anima+'_couple.png');
        }
        else{
            $(".imageanimal").attr('src', 'img/'+anima+'c.png');
        }
    }

},500);


function closeit(){
    $('.popup').hide();
}
function verif(animals) {

    if (animals=="") {
        document.getElementById("txt2").innerHTML="";
        return;
    } 
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementById("txt2").innerHTML=this.responseText;
        }
    }
    xmlhttp.open("GET","getuser.php?r="+animals,true);
    xmlhttp.send();
}
function form(){
    var s =setTimeout(function(){
        if($('#id_animals').val()){
            $('.select').html( "<select name='users' onchange='showUser(this.value)'><option value=''>Votre nom:</option><?php foreach($user as $person){?><option value='<?php echo $person['id']?>'><?php echo $person['name'].'  '.$person['lastname']; ?></option> <?php  } ?> </select>" );

            $('.button').html("<div  id='choose' class='col-3 col-lg-2 ani' >VALIDER</div>");
        }
        else{
            $('.select').html("<span style='color:red'>Cette animal est déjà pris</span>");
            $('.button').html("");
        }
    } ,1000);
}
function showUser(str) {
    if (str=="") {
        document.getElementById("txtHint").innerHTML="";
        return;
    } 
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementById("txtHint").innerHTML=this.responseText;
        }
    }

    xmlhttp.open("GET","getuser.php?q="+str,true);
    xmlhttp.send();

}