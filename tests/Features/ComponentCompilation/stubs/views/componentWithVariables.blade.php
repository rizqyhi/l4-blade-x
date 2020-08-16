

<?php
    $user = 'John';
?>

<card :title="$user">
    My content
</card>


<?php
    $user = new class {
        public $name = 'Jane';
    };
?>

<card :title="$user->name">
    My content
</card>

<?php
    $user = new class {
        public $name = 'Jane';
    };
?>

<card :title="['>']">
    My content
</card>

<card :title="['>suffix']">
    My content
</card>

<card :title="['>suffix']">
    My content
</card>

<card :title="['prefix>suffix']">
    My content
</card>

<card :title="['prefix > suffix']">
    My content
</card>

