<?php
use controller\classes\Session;
$page = $_SERVER['REQUEST_URI'];
?>
<div class="menu-icon-div" id="slide-down-button" ></div>
<div class="slide-down-div" >
    <div class="menu-div">
        <ul>
        	<li><a href="/" <?php echo ( $page === '/'?'class="active"':'' ); ?> >kezdőlap</a></li>
        	<?php
        	if( Session::isAdmin() ){
        	    echo '<li><a href="words_management" ' . ( $page === '/words_management'?'class="active"':'' ) . ' >szavak kezelése</a></li>';
        	}
        	?>
        	<li><a href="test" <?php echo ( $page === '/test'?'class="active"':'' ); ?>>teszt</a></li>
        	<li><a href="statistics" <?php echo ( $page === '/statistics'?'class="active"':'' ); ?> >statisztikák</a></li>
        	<li><a href="logout" <?php echo ( $page === '/logout'?'class="active"':'' ); ?> >kijelentkezés</a></li>
        </ul>
    </div>
</div>