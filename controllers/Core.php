<?php

Class CoreController {
    private $_ViewPath = 'views/';
    private $_ViewPat = 'models/';

    private $_Variable = array();


    public function call($variable, $var){
        $this->_Variable[$variable] = $var;
    }

    public function home(){
        extract($this->_Variable);
        require $this->_ViewPath . 'header.php';
        require $this->_ViewPath . 'home.php';
        require $this->_ViewPath . 'footer.php';
//       require 'test.html';

    }
    
    public function login(){
        extract($this->_Variable);
        require $this->_ViewPath . 'login.php';
        require $this->_ViewPath . 'footer.php';

    }
     
    public function article(){
        extract($this->_Variable);
        require $this->_ViewPath . 'navbar.php';
        require $this->_ViewPath . 'header.php';
        require $this->_ViewPath . 'articles.php';
        require $this->_ViewPath . 'footer.php';
    }
    
    public function favoris(){
         extract($this->_Variable);
        require $this->_ViewPath . 'navbar.php';
        require $this->_ViewPath . 'header.php';
        require $this->_ViewPath . 'favoris.php';
        require $this->_ViewPath . 'footer.php';  
    }
    public function groupe(){
         extract($this->_Variable);
        require $this->_ViewPath . 'navbar.php';
        require $this->_ViewPath . 'header.php';
        require $this->_ViewPath . 'groupelist.php';
        require $this->_ViewPath . 'footer.php';  
    }
    public function event(){
         extract($this->_Variable);
        require $this->_ViewPath . 'navbar.php';
        require $this->_ViewPath . 'header.php';
        require $this->_ViewPath . 'event.php';
        require $this->_ViewPath . 'footer.php';  
    }
    
    public function profil(){
         extract($this->_Variable);
        require $this->_ViewPath . 'header.php';
        require $this->_ViewPath . 'profil.php';
        require $this->_ViewPath . 'footer.php';  
    }
    public function test(){
         extract($this->_Variable);
        require $this->_ViewPath . 'header.php';
        require $this->_ViewPath . 'test.php';
        require $this->_ViewPath . 'footer.php';  
    }
}