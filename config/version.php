<?php

if(empty($tag)) {
    $tag = config('app.version');
}


if(empty($hash)) {
    $hash = '';
}

return [
    'tag' => $tag,
    'hash' => $hash,
    'string' => sprintf('%s',$tag),
];

