<?php
$alert_succes = <<<ASCC
You have successfully added a new species to the database. You may want to add
a new <a href="addfollowed.php">individual</a>.
ASCC;
$alert_danger = <<<ADGR
Something went wrong. Try again adding an individual or, if you are aboslutely
sure the error does not come from you, contact our webmasters.
ADGR;
$title_head = "Add a new species";
$title = "<h1>Add a new species</h1>";

$paragraph_form = <<<PAF
<p>Fill this form to compete our database.</p>
<p>Each entry helps us to offer a higher quality service.</p>
PAF;

$species = "Name of species*";
$kingdom = "Kingdom*";
$phylum = "Phylum*";
$class = "Class*";
$order = "Order*";
$family = "Family*";
$genus = "Genus*";
$status = "pwet*status";
$submit = "Submit";
