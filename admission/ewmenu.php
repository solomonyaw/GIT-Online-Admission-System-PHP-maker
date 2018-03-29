<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(4, "mci_homepage", $Language->MenuPhrase("4", "MenuText"), "index.php", -1, "", IsLoggedIn(), FALSE, TRUE, "");
$RootMenu->AddMenuItem(1, "mi_students", $Language->MenuPhrase("1", "MenuText"), "studentslist.php", -1, "", AllowListMenu('{F31DB578-461D-4551-B52B-112914F68329}students'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(6, "mi_programs", $Language->MenuPhrase("6", "MenuText"), "programslist.php", -1, "", AllowListMenu('{F31DB578-461D-4551-B52B-112914F68329}programs'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(5, "mi_application", $Language->MenuPhrase("5", "MenuText"), "applicationlist.php", 6, "", AllowListMenu('{F31DB578-461D-4551-B52B-112914F68329}application'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(2, "mi_userlevelpermissions", $Language->MenuPhrase("2", "MenuText"), "userlevelpermissionslist.php", -1, "", IsAdmin(), FALSE, FALSE, "");
$RootMenu->AddMenuItem(3, "mi_userlevels", $Language->MenuPhrase("3", "MenuText"), "userlevelslist.php", -1, "", IsAdmin(), FALSE, FALSE, "");
$RootMenu->AddMenuItem(7, "mi_status", $Language->MenuPhrase("7", "MenuText"), "statuslist.php", -1, "", AllowListMenu('{F31DB578-461D-4551-B52B-112914F68329}status'), FALSE, FALSE, "");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
