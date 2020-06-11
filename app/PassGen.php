<?php
namespace App;
use Exception;
class PassGenBadPattern extends Exception { };

class PassGen {
    
    private $par_orig;
    private $par;
    private $mapping;
    private $reverse_mapping;
    private $type;
    private $par_name;
    private $template;
    const A_AND_N = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function __construct(array $par, array $mapping) {
	$this->par_orig = $par;
	$this->mapping = $mapping;
	$this->reverse_mapping = array_flip($mapping);
    }

    private function parsePattern($key) {
	$this->type = NULL;
	$this->template = NULL;
	$this->par_name = $this->reverse_mapping[$key];
	if (preg_match('/^\$\{([NPC]):(.+)\}$/', $this->par_orig[$key], $m)) {
	    $this->type = $m[1];
	    $this->template = $m[2];
	}
    }

    public function gen() {
	$this->par = $this->par_orig;
        //reduce error reporting
        error_reporting("E_ALL & ~E_DEPRECATED");
	/* generate password and PIN number */
	foreach ($this->par as $key => $val) {
	    $this->parsePattern($key);
	    switch ($this->type) {
		case 'P':
		    $this->par[$key] = $this->genP();
		    break;
		case 'N':
		    $this->par[$key] = $this->genN();
		    break;
		default:
	    }
	}

	/* to make duplication of existing field */
	foreach ($this->par as $key => $val) {
	    $this->parsePattern($key);
	    switch ($this->type) {
		case 'C':
		    $this->par[$key] = $this->genC();
		    break;
		default:
	    }
	}

	return $this->par;
    }

    private function genP() {
	if (!preg_match('/^\d+$/', $this->template) || $this->template <= 0) {
	    throw new PassGenBadPattern($this->par_name . ':' . $this->template);
	};

	$p = self::A_AND_N;
	do {
	    for ($s = '', $i = 0; $i < $this->template; $i++) {
	        $s .= $p[rand(0, strlen(self::A_AND_N) - 1)];
	    }
	} while (!preg_match('/\d+/', $s));

	return $s;
    }

    private function genN() {
	$s = '';

	if (preg_match('/^\d+$/', $this->template)) {
	    if ($this->template < 1) {
		throw new PassGenBadPattern($this->par_name . ':' . $this->template);
	    }
	    $i = $this->template;
	    $this->template = '[1-9]';
	    while (--$i) {
		$this->template .= '[0-9]';
	    }
	}

	if (!preg_match('/^((\[([\d(,\d)?]+)\])+|(\[\d-\d\])+)+$/', $this->template)) {
	    throw new PassGenBadPattern($this->par_name . ':' . $this->template);
	};
	if (!preg_match_all('/\[([-,\d]+)\]/', $this->template, $m)) {
	    throw new PassGenBadPattern($this->par_name . ':' . $this->template);
	};

	foreach ($m[1] as $p) {
	    if (preg_match('/-/', $p)) {
		$s2 = explode('-', $p);
		$s .= rand($s2[0], $s2[1]);
	    } elseif (preg_match('/,/', $p)) {
		$s2 = '';
		$p2 = explode(',', $p);
		foreach ($p2 as $p3) {
		    $s2 .= $p3;
		}
		$s .= $s2[rand(0, strlen($s2) - 1)];
	    } elseif (preg_match('/^\d$/', $p)) {
		$s .= $p;
	    } else {
		throw new PassGenBadPattern($this->par_name . ':' . $this->template);
	    }
	}
	return $s;
    }

    private function genC() {
	if (!array_key_exists($this->template, $this->mapping) ||
	    !array_key_exists($this->mapping[$this->template], $this->par)) {
	    throw new PassGenBadPattern($this->par_name . ':' . $this->template);
	}
	return $this->par[$this->mapping[$this->template]];
    }
};

?>
