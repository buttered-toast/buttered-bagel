<?php namespace tools\debug; defined('ABSPATH') || exit;

class EL {
    private $values;

    private $opening_tag = "---- Error log start ----\r\n";

    private $closeing_tag = "\r\n---- Error log end ----\r\n\r\n";

    public function __construct ($values) {
        $this->values = $values;

        return $this;
    }
    
    private function output ($type) {
        self::createLogFolder();
        error_log($this->opening_tag, 3,  BUBA_BDIR . '/temp/log.txt');
        $this->loop($type);
        error_log($this->closeing_tag, 3,  BUBA_BDIR . '/temp/log.txt');
    }

    private function createLogFolder () {
        if (is_dir(BUBA_BDIR . '/temp')) return;
        mkdir(BUBA_BDIR . '/temp');
        $file = fopen(BUBA_BDIR . '/temp/index.php', 'w');
        fwrite($file, '<?php');
        fclose($file);
    }

    private function loop ($type) {
        foreach ($this->values as $value) {
            if ($type === 'var_dump') {
                ob_start();
                $type($value);
                error_log(ob_get_clean(), 3, BUBA_BDIR . '/temp/log.txt');
            } else error_log($type($value, true), 3, BUBA_BDIR . '/temp/log.txt');
            error_log("\r\n\r\n", 3, BUBA_BDIR . '/temp/log.txt');
        }
    }

    public function clear () {
        if (file_exists(BUBA_BDIR . '/temp/index.php')) unlink(BUBA_BDIR . '/temp/index.php');
        if (file_exists(BUBA_BDIR . '/temp/log.txt')) unlink(BUBA_BDIR . '/temp/log.txt');
        if (is_dir(BUBA_BDIR . '/temp')) rmdir(BUBA_BDIR . '/temp');
    }
    
    public function print () {
        $this->output('print_r');
    }
    
    public function export () {
        $this->output('var_export');
    }

    public function dump () {
        $this->output('var_dump');
    }
}