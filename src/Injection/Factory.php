<?php
namespace Lapaz\Aura\Di\Injection;

use Aura\Di\Injection\LazyInterface;
use Lapaz\Aura\Di\Exception\InvalidModifier;
use Lapaz\PlainPhp\ScriptRunner;

class Factory extends \Aura\Di\Injection\Factory
{
    /**
     * @var callable|null
     */
    protected $modifier = null;

    /**
     * @var bool
     */
    protected $callableChecked = false;

    /**
     * @param callable|LazyInterface|null $modifier
     * @return static
     */
    public function modifiedBy($modifier)
    {
        $this->modifier = $modifier;
        return $this;
    }

    /**
     * @param string $filename
     * @param array $extraVariables
     * @return static
     */
    public function modifiedByScript($filename, $extraVariables = [])
    {
        return $this->modifiedBy(function ($object) use ($extraVariables, $filename) {
            ScriptRunner::which()->requires($filename)->binding($object)->with($extraVariables)->run();
        });
    }

    /**
     * @inheritDoc
     */
    public function __invoke()
    {
        $object = parent::__invoke();

        if ($this->modifier === null) {
            return $object;
        }

        $modifier = $this->modifier;
        if ($modifier instanceof LazyInterface) {
            $modifier = $modifier();
        }

        if (!$this->callableChecked) {
            if (!is_callable($modifier)) {
                throw new InvalidModifier("Specified modifier is not callable.");
            }
            $this->callableChecked = true;
        }

        call_user_func($modifier, $object);

        return $object;
    }
}
