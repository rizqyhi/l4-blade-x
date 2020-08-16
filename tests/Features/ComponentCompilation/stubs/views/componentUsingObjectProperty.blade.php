<?php
    $person = new class
    {
        public $name = 'John';
    };
?>

<card :title="$person->name">
    My content
</card>
