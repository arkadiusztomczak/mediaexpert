<?php
require_once 'Database.php';
require_once 'ObjectOperations.php';

$db = new Database();

// Tworzenie obiektu
$object = new ObjectOperations($db->conn);
$object->number = '12345';
$object->status = 'active';
$object->create();
echo "Created object with ID: " . $object->id . "\n";

// Aktualizacja obiektu
$object->read($object->id);
$object->status = 'inactive';
$object->update();

// Usunięcie obiektu
// $object->delete();


?>