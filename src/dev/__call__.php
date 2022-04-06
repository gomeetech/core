<?php
function __call__($args = [])
{

    if (isset($args[1])) {
        $a = $args;
        $f = $args[1];
        array_shift($a);
        array_shift($a);
        if ($f != 'test_speed' && isset($a[0]) && is_callable($a[0])) {
            $f2 = $a[0];
            array_shift($a);
            for ($i = 0; $i < count($a); $i++) {
                $a[$i] = str_replace('+', ' ', $a[$i]);
            }
            $param = [call_user_func_array($f2, $a)];
        } else {
            $param = $a;
        }

        if (is_callable($f)) {
            return call_user_func_array($f, $param);
        } else {
            switch ($f) {
                case "make":
                case 'create':
                    if (isset($param[0])) {
                        $t = array_shift($param);
                        $p = get_args_params($param);
                        $args = array_merge([$p['params']], $p['args']);
                        if ($t == 'table') {
                            call_user_func_array("create_{$t}", $args);
                        } elseif (in_array(strtolower($t), ['service', 'provider', 'serviceproviders'])) {
                            call_user_func_array("create_provider", $args);
                        }
                    }
                    break;

                case 'alter':
                    if (isset($param[0])) {
                        $t = array_shift($param);
                        $p = get_args_params($param);
                        $args = array_merge([$p['params']], $p['args']);
                        if ($t == 'table') {
                            call_user_func_array("alter_{$t}", $args);
                        } 
                    }
                    break;
            }
        }
    }
}
