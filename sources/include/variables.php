<?PHP

define('PMNL_VERSION', '0.8.6');
define('PMNL_HOMEPAGE', 'http://www.phpmynewsletter.com');

$pmnl_langues = array(
	'english' => 'english',
	'francais' => 'francais'
);

$pmnl_sgbd = array(
	'mysql' => 'mysql',
	'pgsql' => 'pgsql'
);

$pmnl_modules = array(
	'authentification' => array(
		'action' => 'authentification',
		'script' => 'authentification.php',
	),
	'commun' => array(
		'action' => 'commun',
		'script' => 'commun.php',
	),
	'maintenance' => array(
		'action' => 'maintenance',
		'script' => 'maintenance.php',
	),
	'subscribers' => array(
		'action' => 'subscribers',
		'script' => 'subscribers.php',
		'html' => 'subscribers.php',
	),
	'compose' => array(
		'action' => 'compose',
		'script' => 'compose.php',
		'html' => 'compose.php',
	),
	'archives' => array(
		'action' => 'archives',
		'script' => 'archives.php',
		'html' => 'archives.php',
	),
	'newsletterconf' => array(
		'action' => 'newsletterconf',
		'script' => 'newsletterconf.php',
		'html' => 'newsletterconf.php',
	),
	'lettres' => array(
		'action' => 'lettres',
		'script' => 'lettres.php',
		'html' => 'lettres.php',
	),
	'globalconf' => array(
		'action' => 'globalconf',
		'script' => 'globalconf.php',
		'html' => 'globalconf.php',
	),
);

$pmnl_locales = array(
	"utf-8",
	"cp037",
	"cp850",
	"cp863",
	"iso-8859-1",
	"iso-8859-3",
	"koi8-u",
	"windows-1250",
	"windows-1258",
	"cp1006",
	"cp852",
	"cp864",
	"iso-8859-10",
	"iso-8859-4",
	"mazovia",
	"windows-1251",
	"x-mac-ce",
	"cp1026",
	"cp855",
	"cp865",
	"iso-8859-11",
	"iso-8859-5",
	"nextstep",
	"windows-1252",
	"x-mac-cyrillic",
	"cp424",
	"cp856",
	"cp866",
	"iso-8859-13",
	"iso-8859-6",
	"windows-1253",
	"x-mac-greek",
	"cp437",
	"cp857",
	"cp869",
	"iso-8859-14",
	"iso-8859-7",
	"windows-1254",
	"x-mac-icelandic",
	"cp500",
	"cp860",
	"cp874",
	"iso-8859-15",
	"iso-8859-8",
	"turkish",
	"windows-1255",
	"x-mac-roman",
	"cp737",
	"cp861",
	"cp875",
	"iso-8859-16",
	"iso-8859-9",
	"us-ascii",
	"windows-1256",
	"zdingbat",
	"cp775",
	"cp862",
	"gsm0338",
	"iso-8859-2",
	"koi8-r",
	"us-ascii-quotes",
	"windows-1257"
);
sort($pmnl_locales);

?>