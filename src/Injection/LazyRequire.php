<?php
namespace Lapaz\Aura\Di\Injection;
use Aura\Di\Injection\LazyInterface;
use Lapaz\PlainPhp\ScriptRunner;

/**
 * Returns the value of `require` when invoked (thereby requiring the file).
 */
class LazyRequire implements LazyInterface
{
    /**
     * The file to include.
     *
     * @var string|LazyInterface
     */
    protected $file;

    /**
     * Parameter variables passed to script file.
     *
     * @var array|LazyInterface
     */
    protected $params;

    /**
     * Constructor.
     *
     * @param string|LazyInterface $file The file to include.
     * @param array $params Parameter variables passed to script file.
     */
    public function __construct($file, $params = [])
    {
        $this->file = $file;
        $this->params = $params;
    }

    /**
     * Invokes the closure to require the file.
     *
     * @return mixed The return from the required file, if any.
     */
    public function __invoke()
    {
        $filename = $this->file;
        if ($filename instanceof LazyInterface) {
            $filename = $filename->__invoke();
        }

        $params = $this->params;
        if ($params instanceof LazyInterface) {
            $params = $params->__invoke();
        }
        foreach ($params as $k => $v) {
            while ($v instanceof LazyInterface) {
                $v = $v->__invoke();
            }
            $params[$k] = $v;
        }

        return ScriptRunner::which()->requires($filename)->with($params)->run();
    }
}
