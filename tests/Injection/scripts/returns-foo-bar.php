<?php
/** @var integer $foo */
/** @var integer $bar */

assert(isset($foo));
assert(isset($bar));

return [$foo, $bar];
