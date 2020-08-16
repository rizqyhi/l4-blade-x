<?php
$title = 'my title'
?>

<card :title="Spatie\BladeX\Laravel\Str::after(ucfirst($title), 'My ')">
    {{{ $title }}}
</card>
