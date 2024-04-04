<?php

/** @var \Modules\Base\Classes\Fetch\Rights $this */

$this->add_right("hosting", "hosting", "administrator", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "hosting", "manager", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "hosting", "supervisor", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "hosting", "staff", view:true, add:true, edit:true);
$this->add_right("hosting", "hosting", "registered", view:true, add:true);
$this->add_right("hosting", "hosting", "guest", view:true, );

$this->add_right("hosting", "accesskey", "administrator", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "accesskey", "manager", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "accesskey", "supervisor", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "accesskey", "staff", view:true, add:true, edit:true);
$this->add_right("hosting", "accesskey", "registered", view:true, add:true);
$this->add_right("hosting", "accesskey", "guest", view:true, );

$this->add_right("hosting", "package", "administrator", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "package", "manager", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "package", "supervisor", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "package", "staff", view:true, add:true, edit:true);
$this->add_right("hosting", "package", "registered", view:true, add:true);
$this->add_right("hosting", "package", "guest", view:true, );

$this->add_right("hosting", "server", "administrator", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "server", "manager", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "server", "supervisor", view:true, add:true, edit:true, delete:true);
$this->add_right("hosting", "server", "staff", view:true, add:true, edit:true);
$this->add_right("hosting", "server", "registered", view:true, add:true);
$this->add_right("hosting", "server", "guest", view:true, );
