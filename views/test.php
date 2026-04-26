<?php
session_start();

?>

<head>

   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css"  href="css/fonts.css">
</head>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script type="text/javascript">

    $('.id').hide();
    $('#listarticle').hide();
    $('#listcategorie').hide();
    $('#listuser').hide();
    $('#listadm').hide();
    function hideall(){
        $('#tableaudebord').hide();
        $('#listarticle').hide();
        $('#listcategorie').hide();
        $('#listuser').hide();

        $('#listadm').hide();
    }
    function Showarticle(){
        hideall();
        $('#listarticle').show();

    }
    function Showcategorie(){
        hideall();
        $('#listcategorie').show();

    }
    function Showuser(){
        hideall();
        $('#listuser').show();

    }
    function Showadm(){
        hideall();
        $('#listadm').show();

    }

    function addInput(name,type,holder,id){
        var div = document.getElementById(id);
        var input = document.createElement("input");
        input.name = name;
        input.type =type;
        input.placeholder =holder;
        div.appendChild(input);
    }
    function addbloc(){
        addpic();

    }
    function addpic() {
        addInput("pic[]","file","image","box");
        addtexte();
    }
    function addslider() {
        addInput("slider[]","file","photo","slider");
    }
    function addtexte() {
        addInput("texte[]","textarea","texte de l'image","box");
    }
</script>

