<?php
function adodb_backtrace($ar, $print=true)
{
	$s = '';
	if (PHPVERSION() >= 4.3) {
		$MAXSTRLEN = 64;

		$s = '<pre align=left>';
		$traceArr = $ar;;
		array_shift($traceArr);
		$tabs = sizeof($traceArr)-1;
		foreach ($traceArr as $arr) {
			for ($i=0; $i < $tabs; $i++) $s .= ' &nbsp; ';
			$tabs -= 1;
			$s .= '<font face="Courier New,Courier">';
			if (isset($arr['class'])) $s .= $arr['class'].'.';
			foreach($arr['args'] as $v) {
				if (is_null($v)) $args[] = 'null';
				else if (is_array($v)) $args[] = 'Array['.sizeof($v).']';
				else if (is_object($v)) $args[] = 'Object:'.get_class($v);
				else if (is_bool($v)) $args[] = $v ? 'true' : 'false';
				else {
					$v = (string) @$v;
					$str = htmlspecialchars(substr($v,0,$MAXSTRLEN));
					if (strlen($v) > $MAXSTRLEN) $str .= '...';
					$args[] = $str;
				}
			}

			$s .= $arr['function'].'('.implode(', ',$args).')';
			$s .= sprintf("</font><font color=#808080 size=-1> # line %4d,".
  " file: <a href=\"file:/%s\">%s</a></font>",
			$arr['line'],$arr['file'],$arr['file']);
			$s .= "\n";
		}
		$s .= '</pre>';
		if ($print) print $s;


	}
	return $s;
}

function error_handler($errno, $errstr, $errfile, $errline, $errcontext) {
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$errors = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$errors = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$errors = "Fatal Error";
			break;
		default:
			$errors = "Unknown";
			break;
	}

	$outString = sprintf("PHP %s:  %s in %s on line %d", $errors, $errstr, $errfile, $errline);

	if (ini_get("display_errors"))
		printf ("<br />\n<b>%s</b>: %s in <b>%s</b> on line <b>%d</b><br /><br />\n", $errors, $errstr, $errfile, $errline);

	if (ini_get('log_errors'))
		error_log($outString);
    
	$logger = new Zend_Log();

	$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/logs/errors');
	$logger->addWriter($writer);
	$logger->alert($outString);
}

function exception_handler($exception) {
	// these are our templates
    $traceline = "#%s %s(%s): %s(%s)";
    $msg = "PHP Fatal error:  Uncaught exception '%s' with message '%s' in %s:%s\nStack trace:\n%s\n  thrown in %s on line %s";

    // alter your trace as you please, here
    $trace = $exception->getTrace();
    $key = 0;
    foreach ($trace as $key => $stackPoint) {
        // I'm converting arguments to their type
        // (prevents passwords from ever getting logged as anything other than 'string')
        $trace[$key]['args'] = array_map('gettype', $trace[$key]['args']);
    }

    // build your tracelines
    $result = array();
    foreach ($trace as $key => $stackPoint) {
        $result[] = sprintf(
            $traceline,
            $key,
            $stackPoint['file'],
            $stackPoint['line'],
            $stackPoint['function'],
            implode(', ', $stackPoint['args'])
        );
    }
    // trace always ends with {main}

    $result[] = '#' . ++$key . ' {main}';

    // write tracelines into main template
    $msg = sprintf(
        $msg,
        get_class($exception),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        implode("\n", $result),
        $exception->getFile(),
        $exception->getLine()
    );

    $msg .= "\n\n";

	$logger = new Zend_Log();
	$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/logs/exceptions');
	$logger->addWriter($writer);
	$logger->alert($msg);
}