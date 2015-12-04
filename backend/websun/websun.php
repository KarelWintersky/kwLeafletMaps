<?php // websun 0.1.50

/*
 * http://webew.ru/articles/3609.webew
0.1.50 - fixed default paths handling (see __construct() )

0.1.49 - added preg_quote() in parse_cycle() for searching array's name

0.1.48 - $ can be used in templates_root direcory path
         (^ makes no sense, because it means templates_root path itself) 

0.1.47 - called back the possibility of spaces before variables,
         because it makes parsing of cycles harder:
         instead of [*|&..]array: we must catch [*|&..]\s*array,
         which (according to one of the tests) works ~10% slower;
         simple (non-cycle) variables and constants 
         (literally how it is described in comment to previous version)
         can still work - this unofficial possibility will be left

0.1.46 - spaces can be like {?* a = b *}, {?* a > b *}
         (especially convenient when using constants: {?*a = =CONST*})

0.1.45 - now spaces can be around &, | and variable names in ifs

0.1.44 - loosen parse_cycle() regexp
         to accept arrays with non-alphanumeric keys
         (for example, it may occur handy to use 
         some non-latin string as a key),
         now {%* anything here *} is accepted.
         Note: except of dots in "anything", which are
         still interpreted as key separators.

0.1.43 - some fixes in profiling:
         parse_template's time is not added to total
         instead of this, total time is calculated
         as a time of highest level parse_template run
         Note: profiling by itself may increase
         total run time up to 60% 

0.1.42 - loosen regexp at check_if_condition_part()
         from [$=]?[\w.\^]*+ to [^*=<>]*+
         now any variable name can be used in ifs,
         not just alphanumeric;
         such loose behaviour looks more flexible
         and parallels var_value() algorithm
         (which can interpret any variable name
         except of with dots)

0.1.41 - fixed find_and_parse_cycle():
         "array_name:" prefixed with & is also catched 
         (required for ifs like {?*array:key1&array:key2*})

0.1.40 - fixed error in check_if_condition_part()
         (pattern)

0.1.39 - fixed error in check_if_condition_part()
         (forgotten vars like array^COUNT>1 etc.)

0.1.38 - fixed error in if alternatives (see 0.1.37)
         introduced check_if_condition_part() method
         added possibility of "AND", not just "OR" in if alternatives:
         {?*var_1="one"|var_2="two"*} it is OR {*var_1="one"|var_2="two"*?}
         {?*var_1="one"&var_2="two"*} it is AND {*var_1="one"&var_2="two"*?}
         
         Fixed profiling!
         Now any profiling information is passed to the higher level -
         otherwise (as it previously was) each object instance (i.e. each
         call of nested template or module) produced it's own and private
         timesheet.

0.1.37 - fixes in var_value():
         parsing of | (if alternatives) now goes before 
         parsing of strings ("..")
         so things like {?*var_1="one"|var_2="two"*} ... {*var_1="one"|var_2="two"*?}
         are possible
         (but things like {?*var="one|two"*} are not)

0.1.36 - correct string literals parsing - var_value() fix 
         (important when passed to module)
         

0.1.35 - two literals comparison is accepted in if's now
         (previously only variable was allowed at the left)
         Needed to do so because of :^KEY parsing - those
         are converted to literals BEFORE ifs are processed
         Substituted ("[^"*][\w.:$|\^]*+) subpattern 
         with the ("[^"*]+"|[\w.:$|\^]*+) one

0.1.34 - fixed surpassing variables to child templates
         (now {* + *var1|var2* | *tpl1|tpl2* *} works)

0.1.33 - fixed parse_if (now {?*var1|var2*}..{*var1|var2*?} works)

0.1.32 - fixed getting template name from variable
         (now variable can be like {*var1|var2|"string"*}
         as everywhere else)

0.1.31 - fixed /r/n deletion from the end of the pattern
         
0.1.30 - fixed parse_cycle() function:
         regexp now catches {* *%array:subarray* | ... *}
         (previously missed it)

0.1.29 - fixed a misprint

0.1.28 - A bunch of little fixes.
         
         Added profiling mode (experimental
         for a while so not recommended to use
         hardly).
         
         Regexp for ifs speeded up by substituting
         loose [^<>=]* with strict [\w.:$\^]*+
         (boosted the usecase about 100 times)
         
         In parse_cycle - also
         ([\w$.]*) instead of ([-\w$.]*)
         (don't remember, what "-" was for).
         
         Constants are now handled in var_value
         (with all other stuff like ^COUNT etc.)
         instead of in get_var_or_string().

0.1.27 - "alt" version is main now
         
         added {* + *some* | >{*var*} *} - 
         templates right inside of the var

0.1.26 - fixed some things with $matches dealing in parsee_if

0.1.25 - version suffix changed "beta" (b) to "alternative" (alt)

0.1.25 - fixed replacement of array:foo - 
         Now it is more strict and occurs if only 
         symbols *=<>| precede array:foo
         ( =<> - for {*array:foo_1>array:foo_2*}, |
           - for {*array:foo_1|array:foo_2*}, 
           * - for just {*array:foo*} )


0.1.24 - (beta) rewriting regexps with no named subpatterns 
         for compatibility with old PHP versions
         (PCRE documentation is unclear and issues like
         new PHP and no support of named subpatterns occur)
         EXCEPT pattern for addvars method
0.1.23 - fixed ^KEY parsing a little (removed preg_quote and fixed regexp)

0.1.22 - now not only *array:foo* (and *array:^KEY*) is caught,
         but array:foo and array:^KEY as well
         Needed it because otherwise if clauses like {*array:a|array:b*}
         are not parsed. This required substitution of str_replace with 
         with preg_replace which had no visible affect on perfomance.

0.1.21 - fixed some in var_value() (substr sometimes returns FALSE not empty string)
         everything is mb* now
         
0.1.20 - fixed trimming \r\n at the end  
         of the template 
0.1.19 - websun_parse_template_path() fixed
         to set current templates directory 
         to the one of template specified
         
0.1.18 - KEY added
0.1.17 - fixed some in var_value() 

теперь по умолчанию корневой каталог шаблона - 
тот, в котором выполняется вызвавший функцию скрипт

добавлены ^COUNT (0.1.13)
добавлены if'ы: {*var_1|var_2|"строка"|1234(число)*}
в if'ах добавлено сравнение с переменными:
{?*a>b*}..{*a>b?*}
{?*a>"строка"*}..{*a>"строка"*?}
{?*a>3*}..{*a>3*?}
*/

/**
 *
 */
class websun {
	
	public $vars;
	public $templates_root_dir; // templates_root_dir указывать без закрывающего слэша!
	public $templates_current_dir;
	public $TIMES;
	private $profiling;
	private $predecessor; // объект шаблонизатора верхнего уровня, из которого делался вызов текущего

    /**
     * @param array $vars
     * @param bool $templates_root
     * @param bool $profiling
     * @param bool $predecessor
     */
    function __construct(
			$vars = array(), 
			$templates_root = FALSE, 
			$profiling = FALSE,
			$predecessor = FALSE
		) {
	
		$this->vars = $vars;
		
		if ($templates_root) // корневой каталог шаблонов
			$this->templates_root_dir = $this->template_real_path($templates_root);
		else { // если не указан, то принимается каталог файла, в котором вызван websun
			// С 0.50 - НЕ getcwd()! Т.к. текущий каталог - тот, откуда он запускается,
			// $this->templates_root_dir = getcwd();
			foreach (debug_backtrace() as $trace) {
				if (preg_match('/^websun_parse_template/', $trace['function'])) {
					$this->templates_root_dir = dirname($trace['file']);
					break;
				}
			}
			
			if (!$this->templates_root_dir) {
				foreach (debug_backtrace() as $trace) {
					if ($trace['class'] == 'websun') {
						$this->templates_root_dir = dirname($trace['file']);
						break;
					}
				}
			}
		}
		
		$this->templates_current_dir = $this->templates_root_dir . '/';
		$this->profiling = $profiling;
		
		$this->predecessor = $predecessor;
	}


    /**
     * @param $template
     * @return mixed
     */
    function parse_template($template) {
		if ($this->profiling)
			$start = microtime(1);
		
		$template = preg_replace('/ \\/\* (.*?) \*\\/ /sx', '', $template); /**ПЕРЕПИСАТЬ ПО JEFFREY FRIEDL'У !!!**/
		
		$template = str_replace('\\\\', "\x01", $template); 	// убираем двойные слэши
		$template = str_replace('\*', "\x02", $template);	// и экранированные звездочки
		
		$template = preg_replace_callback( // дописывающие модули
			'/
			{\*
			&(\w+)
			(?P<args>\([^*]*\))?
			\*}
			/x', 
			array($this, 'addvars'), 
			$template
			);
		
		$template = $this->find_and_parse_cycle($template);
		
		$template = $this->find_and_parse_if($template);
		
		$template = preg_replace_callback( // переменные, шаблоны и модули
				'/
				{\*
				(.*?)
				\*}
				/x', 
				/* подумать о том, чтобы вместо (.*?) 
				   использовать жадное, но более строгое
				   (
					(?:
						[^*]*+
						|
						\*(?!})
					 )+
				)
				*/
				array($this, 'parse_vars_templates_modules'), 
				$template
			);
		
		$template = str_replace("\x01", '\\\\', $template); // возвращаем двойные слэши обратно
		$template = str_replace("\x02", '*', $template); // а звездочки - уже без экранирования 
		
		if ($this->profiling AND !$this->predecessor) {
			$this->TIMES['_TOTAL'] = round(microtime(1) - $start, 4) . " s";
			// ksort($this->TIMES);
			echo '<pre>' . print_r($this->TIMES, 1) . '</pre>';
		}
		
		return $template;
	}
	
    /**
     * Дописывание массива переменных из шаблона (хак для Бурцева)
     * @param $matches
     * @return bool
     */
    function addvars($matches) {
		if ($this->profiling) 
			$start = microtime(1);
		
		$module_name = 'module_'.$matches[1];
		# ДОБАВИТЬ КЛАССЫ ПОТОМ
		$args = (isset($matches['args'])) 
			? explode(',', mb_substr($matches['args'], 1, -1) ) // убираем скобки
			: array();
		$this->vars = array_merge(
			$this->vars, 
			call_user_func_array($module_name, $args)
			); // call_user_func_array быстрее, чем call_user_func
		
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));
		
		return TRUE;
	}

    /**
     * @param $string
     * @return array|bool|int|mixed|null|string
     */
    function var_value($string) {
		
		if ($this->profiling)
			$start = microtime(1);
		
		if (mb_substr($string, 0, 1) == '=') { # константа
			$C = mb_substr($string, 1);
			$out = (defined($C)) ? constant($C) : '';
		}
		
		// можно делать if'ы:
		// {*var_1|var_2|"строка"|134*}
		// сработает первая часть, которая TRUE
		elseif (mb_strpos($string, '|') !== FALSE) {
			$f = __FUNCTION__;
			
			foreach (explode('|', $string) as $str) {
				// останавливаемся при первом же TRUE
				if ($val = $this->$f($str)) 
					break; 
			}
			
			$out = $val;
		}
		
		elseif ( # скалярная величина
			mb_substr($string, 0, 1) == '"'
			AND 
			mb_substr($string, -1) == '"'
		)
			$out = mb_substr($string, 1, -1);
		
		elseif (mb_strlen($string) AND !preg_match('/\D/', $string))
			# опять же скалярная величина (число)
				// (0.1.17 - numeric() вместо этого нельзя,
				// т.к. тогда могут быть неполадки с разворотом циклов
				// - точки с числами могут встать хитрым образом
				// и перепутаться с этим),
				// а is_int() нельзя, т.к. возвращает 
				// FALSE для строк типа '1'
			$out = $string;
			
		else {
			if (mb_substr($string, 0, 1) == '$') { 
				// глобальная переменная
				$string = mb_substr($string, 1);
				$value = $GLOBALS;
			}
			else 
				$value = $this->vars;
				
			// допустимы выражения типа {*var^COUNT*}
			// (вернет count($var)) )
			if (mb_substr($string, -6) == '^COUNT') {
				$string = mb_substr($string, 0, -6);
				$return_mode = 'count';
			}
			else
				$return_mode = FALSE; // default
			
			$rawkeys = explode('.', $string);
			$keys = array();
			foreach ($rawkeys as $v) { 
				if ($v !== '') 
					$keys[] = $v; 
			}
			// array_filter() использовать не получается, 
			// т.к. числовой индекс 0 она тоже считает за FALSE и убирает
			// поэтому нужно сравнение с учетом типа
			
			// пустая строка указывает на корневой массив
			foreach ($keys as $k) {
				if (is_array($value) AND isset($value[$k]) ) $value = $value[$k]; 
				else { $value = NULL; break; }
			}
			
			// в зависимости от $return_mode действуем по-разному:
			$out = (!$return_mode)
				// возвращаем значение переменной (обычный случай)
				? $value
				
				// возвращаем число элементов в массиве
				: ( is_array($value) ? count($value) : FALSE )
				
				;
		}
		
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));
		
		return $out;
	}

    /**
     * @param $template
     * @return mixed
     */
    function find_and_parse_cycle($template) {
		if ($this->profiling) 
			$start = microtime(1);
		// пришлось делать специальную функцию, чтобы реализовать рекурсию
		$out = preg_replace_callback(
			'/
			{%\* ([^*]*) \*}
			(.*?)
			{\* \1 \*%}
			/sx', 
			array($this, 'parse_cycle'), 
			$template
			);
		
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));
		
		return $out;
	}

    /**
     * @param $matches
     * @return bool|mixed
     */
    function parse_cycle($matches) {
		
		if ($this->profiling) 
			$start = microtime(1);
		
		$array_name = $matches[1];
		$array = $this->var_value($array_name);
		$array_name_quoted = preg_quote($array_name);
		
		if ( ! is_array($array) ) 
			return FALSE;
		
		$parsed = '';
		
		$dot = ( $array_name != '' AND $array_name != '$' ) 
		     ? '.' 
		     : '';
		
		foreach ($array as $key => $value) {
			$parsed .= preg_replace(
					array(// массив поиска
							"/(?<=[*=<>|&%])\s*$array_name_quoted\:\^KEY(?!\w)/",     
							"/(?<=[*=<>|&%])\s*$array_name_quoted\:/"
						), 
					array(// массив замены
							'"' . $key . '"',               // preg_quote для ключей нельзя, 
							$array_name . $dot . $key . '.' // т.к. в ключах бывает удобно
						),                                 // хранить некоторые данные,
					$matches[2]                           // а preg_quote слишком многое экранирует
			   );
		}
		$parsed = $this->find_and_parse_cycle($parsed);
		
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));
		
		return $parsed;
	}

    /**
     * @param $template
     * @return mixed
     */
    function find_and_parse_if($template) {
		
		if ($this->profiling)
			$start = microtime(1);
		
		$out = preg_replace_callback( 
				'/
				{ (\?\!?) \*([^*]*)\* } # открывающее условие
				(.*?)                   # тело if
				{\*\2\* \1}             # закрывающее условие
				/sx',
				array($this, 'parse_if'), 
				$template
			);
		
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));
		
		return $out;
	}

    /**
     * @param $matches
     * @return mixed|string
     */
    function parse_if($matches) {
		// 1 - ? или ?!
		// 2 - тело условия
		// 3 - тело if
		
		if ($this->profiling) 
			$start = microtime(1);
		
		$final_check = FALSE;
		
		$separator = (strpos($matches[2], '&'))
		           ? '&'  // "AND"
		           : '|'; // "OR"
		$parts = explode($separator, $matches[2]);
		$parts = array_map('trim', $parts); // убираем пробелы по краям
		
		$checks = array();
		
		foreach ($parts as $p) 
			$checks[] = $this->check_if_condition_part($p);
		
		if ($separator == '|') // режим "OR" 
			$final_check = in_array(TRUE, $checks);
		
		else // режим "AND"
			$final_check = !in_array(FALSE, $checks);
		
		$result = ($matches[1] == '?') 
			     ? $final_check 
			     : !$final_check ; 
			
		$parsed_if = ($result) 
			        ? $this->find_and_parse_if($matches[3]) 
			        : '' ;
		
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));
		
		return $parsed_if;
	}

    /**
     * @param $str
     * @return bool
     */
    function check_if_condition_part($str) {
		
		if ($this->profiling)
			$start = microtime(1);
		
		preg_match(
				'/^
				   (  
				   	"[^"*]+"       # строковый литерал
				   	
				   	|              # или
				   	
				   	[^*<>=]*+      # имя переменной
				   )  
				   
					(?: # если есть сравнение с чем-то:
						
						([=<>])  # знак сравнения 
						
						\s*
						
						(.*)     # то, с чем сравнивают
					)?
					
					$
				/x',
				$str,
				$matches
			);
		
		$left = $this->var_value(trim($matches[1]));
		
		if (!isset($matches[2]))
			$check = ($left == TRUE);
		
		else {
			
			$right = (isset($matches[3]))
					 ? $this->var_value($matches[3])
					 : FALSE ;
			
			switch($matches[2]) {
				case '=': $check = ($left == $right); break;
				case '>': $check = ($left > $right); break;
				case '<': $check = ($left < $right); break;
				default: $check = ($left == TRUE);
			}
		}
		
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));
		
		return $check;
	}

    /**
     * @param $matches
     * @return array|bool|int|mixed|null|string
     */
    function parse_vars_templates_modules($matches) {
		if ($this->profiling) 
			$start = microtime(1);
		
		// тут обрабатываем сразу всё - и переменные, и шаблоны, и модули
		$work = $matches[1]; 
		$work = trim($work); // убираем пробелы по краям
		
		if (mb_substr($work, 0, 1) == '@') { // модуль {* @name(arg1,arg2) | template *}
			$p = '/
				^
				([^(|]++) # 1 - имя модуля
				(?: \( ([^)]*+) \) \s* )? # 2 - аргументы
				(?: \| \s* (.*+) )? # 3 - это уже до конца 
				$                          # (именно .*+, а не .++)
				/x';
			if (preg_match( $p, mb_substr($work, 1), $m) ) {
				$tmp = $this->get_var_or_string($m[1]);
				$module_name = (mb_strpos($tmp, 'module_') === 0)
					? $tmp
					: "module_$tmp" ;
				$args = ( isset($m[2]) ) 
					? array_map( array($this, 'get_var_or_string'), explode(',', $m[2]) )
					: array();
				$subvars = call_user_func_array($module_name, $args);
				
				if ( isset($m[3]) )  // передали указание на шаблон
					$html = $this->call_template($m[3], $subvars);
				
				else 
					$html = $subvars; // шаблон не указан => модуль возвращает строку
			}
			else 
				$html = ''; // вызов модуля сделан некорректно
		}
		elseif (mb_substr($work, 0, 1) == '+') { 
			// шаблон - {* +*vars_var*|*tpl_var* *}
			// переменная как шаблон - {* +*var* | >*template_inside* *}
			$html = '';
			$parts = preg_split(
					'/(?<=[\*\s])\|(?=[\*\s])/', // вертикальная черта
					mb_substr($work, 1) // должна ловиться только как разделитель
					// между переменной и шаблоном, но не должна ловиться 
					// как разделитель внутри нотации переменой или шаблона
					// (например, {* + *var1|$GLOBAL* | *tpl1|tpl2* *}
				); 
			$parts = array_map('trim', $parts); // убираем пробелы по краям
			if ( !isset($parts[1]) ) { 	// если нет разделителя (|) - значит, 
							                  // передали только имя шаблона +template
							                  
				$html = $this->call_template($parts[0], $this->vars);
			}
			else {
				$varname_string = mb_substr($parts[0], 1, -1); // убираем звездочки
				// {* +*vars* | шаблон *} - простая передача переменной шаблону
				// {* +*?vars* | шаблон *} - подключение шаблона только в случае, если vars == TRUE
				// {* +*%vars* | шаблон *} - подключение шаблона не для самого vars, а для каждого его дочернего элемента 
				$indicator = mb_substr($varname_string, 0, 1);
				if ($indicator == '?') { 
					if ( $subvars = $this->var_value( mb_substr($varname_string, 1) ) )
						// 0.1.27 $html = $this->parse_child_template($tplname, $subvars);
						$html = $this->call_template($parts[1], $subvars);
				}
				elseif ($indicator == '%') {
					if ( $subvars = $this->var_value( mb_substr($varname_string, 1) ) ) {
						foreach ( $subvars as $row ) { 
							// 0.1.27 $html .= $this->parse_child_template($tplname, $row);
							$html .= $this->call_template($parts[1], $row);
						}
					}
				}
				else {
					$subvars = $this->var_value( $varname_string );
					// 0.1.27 $html = $this->parse_child_template($tplname, $subvars);
					$html = $this->call_template($parts[1], $subvars);
				}
			}
		}
		else 
			$html = $this->var_value($work); // переменная (+ константы - тут же)
			
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));

		return $html;
	}

    /**
     *
     * @param $template_notation
     * @param $vars
     * @return mixed
     */
    function call_template($template_notation, $vars) {
		if ($this->profiling) 
			$start = microtime(1);

		// $template_notation - либо путь к шаблону,
		// либо переменная, содержащая путь к шаблону,
		// либо шаблон прямо в переменной - если >*var*
		$c = __CLASS__; // нужен объект этого же класса - делаем
		$subobject = new $c(
				$vars, 
				$this->templates_root_dir,
				$this->profiling,
				$this
			);
		
		$template_notation = trim($template_notation);
		
		if (mb_substr($template_notation, 0, 1) == '>') { 
			// шаблон прямо в переменной
			$v = mb_substr($template_notation, 1);
			$subtemplate = $this->get_var_or_string($v);
			$subobject->templates_current_dir = $this->templates_current_dir;
		}
		else {
			$path = $this->get_var_or_string($template_notation);
			$subobject->templates_current_dir = pathinfo($this->template_real_path($path), PATHINFO_DIRNAME ) . '/';
			$subtemplate = $this->get_template($path);
		}
		
		$result = $subobject->parse_template($subtemplate);
		
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));
		
		return $result;
	}

    /**
     * @param $str
     * @return array|bool|int|mixed|null|string
     */
    function get_var_or_string($str) {
		// используется, в основном, 
		// для получения имён шаблонов и модулей
		if ($this->profiling) 
			$start = microtime(1);
		
		if ( mb_substr($str, 0, 1) == '*' AND mb_substr($str, -1) == '*')
			$out = $this->var_value( mb_substr($str, 1, -1) ); // если вокруг есть звездочки - значит, перменная
			
		else // нет звездочек - значит, обычная строка-литерал
			$out = ( mb_substr($str, 0, 1) == '"'  AND mb_substr($str, -1) == '"') // строка
			     ? mb_substr($str, 1, -1)
			     : $str ;
		
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));
		
		return $out;
	}

    /**
     * @param $tpl
     * @return bool|mixed
     */
    function get_template($tpl) {
		if ($this->profiling) 
			$start = microtime(1);
		
		if (!$tpl) return FALSE;
		$tpl_real_path = $this->template_real_path($tpl);
		// return rtrim(file_get_contents($tpl_real_path), "\r\n");

		// (убираем перенос строки, присутствующий в конце любого файла)
		$out = preg_replace(
				'/\r?\n$/',
				'',
				file_get_contents($tpl_real_path)
			);
		
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));
		
		return $out;
	}

    /**
     * Функция определяет реальный путь к шаблону в файловой системе
     * первый символ пути к шаблону определяет тип пути
     * если в начале адреса есть / - интерпретируем как абсолютный путь ФС
     * если второй символ пути - двоеточие (путь вида C:/ - Windows) -
     * также интепретируем как абсолютный путь ФС
     * если есть ^ - отталкиваемся от $templates_root_dir
     * если $ - от $_SERVER[DOCUMENT_ROOT]
     * во всех остальных случаях отталкиваемся от каталога текущего шаблона: templates_current_dir
     * @param $tpl
     * @return string
     */
    function template_real_path($tpl) {

		if ($this->profiling) 
			$start = microtime(1);
		
		$dir_indicator = mb_substr($tpl, 0, 1);
		
		$adjust_tpl_path = TRUE;
		
		if ($dir_indicator == '^') $dir = $this->templates_root_dir;
		elseif ($dir_indicator == '$') $dir = $_SERVER['DOCUMENT_ROOT'];
		elseif ($dir_indicator == '/') { $dir = ''; $adjust_tpl_path = FALSE; } // абсолютный путь для ФС 
		else {
			if (mb_substr($tpl, 1, 1) == ':') // Windows - указан абсолютный путь - вида С:/...
				$dir = '';
			else  
				$dir = $this->templates_current_dir;  
			
			$adjust_tpl_path = FALSE; // в обоих случаях строку к пути менять не надо
		}
		
		if ($adjust_tpl_path) $tpl = mb_substr($tpl, 1);
		
		$tpl_real_path = $dir . $tpl;
		
		if ($this->profiling) 
			$this->write_time(__FUNCTION__, $start, microtime(1));
		
		return $tpl_real_path;
	}

    /**
     * @param $method
     * @param $start
     * @param $end
     */
    function write_time($method, $start, $end) {
		
		//echo ($this->predecessor) . '<br>';

		if (!$this->predecessor)
			$time = &$this->TIMES;
		
		else
			$time = &$this->predecessor->TIMES ;
		
		if (!isset($time[$method]))
			$time[$method] = array(
					'n' => 0,
					'last' => 0,
					'total' => 0,
					'avg' => 0
				);
			
		$time[$method]['n'] += 1;
		$time[$method]['last'] = round($end - $start, 4);
		$time[$method]['total'] += $time[$method]['last'];
		$time[$method]['avg'] = round($time[$method]['total'] / $time[$method]['n'], 4) ;
	}
}

/**
 * Функция-обёртка для быстрого вызова класса
 * принимает шаблон в виде пути к нему
 * @param $data
 * @param $template_path
 * @param bool $templates_root_dir
 * @param bool $profiling
 * @return mixed
 */
function websun_parse_template_path(
		$data, 
		$template_path, 
		$templates_root_dir = FALSE,
		$profiling = FALSE
	) {

	$W = new websun($data, $templates_root_dir, $profiling);
	$tpl = $W->get_template($template_path);
	$W->templates_current_dir = pathinfo( $W->template_real_path($template_path), PATHINFO_DIRNAME ) . '/';
	$string = $W->parse_template($tpl);
	return $string;
}

/**
 * Функция-обёртка для быстрого вызова класса
 * принимает шаблон непосредственно в виде кода
 * @param $data
 * @param $template_code
 * @param bool $templates_root_dir
 * @param bool $profiling
 * @return mixed
 */
function websun_parse_template(
		$data, 
		$template_code, 
		$templates_root_dir = FALSE,
		$profiling = FALSE
	) {
	$W = new websun($data, $templates_root_dir, $profiling);
	$string = $W->parse_template($template_code);
	return $string;
}

