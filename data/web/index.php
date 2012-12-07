<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__ . '/views',
));

$app->before(function () use ($app)
{
	try {
		$app['db'] = new PDO(
			'mysql:dbname=poblaciones;host=localhost',
			'root',
			'magd',
			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
		);
		$app['db']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		throw new Exception('No ha sido posible conectar a BD');
	}
});

$app->get('/tree', function (Request $request) use ($app)
{
	$node = $request->query->get('node', 'root');

	if($node === 'root')
	{
		$sql = "SELECT id,nombre FROM comunidad ORDER BY nombre ASC";
		$sth = $app['db']->prepare($sql);
		$leaf = 'false';
		$cls = 'folder';
	}
	else
	{
		$parts = explode('_', $node);
		if(count($parts) == 1)
		{
			$sql = "SELECT CONCAT_WS('_',com_id,prov_id) AS id,nombre
					FROM provincia
					WHERE com_id = :idcomunidad
					ORDER BY nombre ASC";
			$sth = $app['db']->prepare($sql);
			$sth->bindValue(':idcomunidad',intval($parts[0]), PDO::PARAM_INT);
			$leaf = 'false';
			$cls = 'folder';
		}
		else
		{
			$sql = "SELECT CONCAT_WS('_',com_id,prov_id,loc_id) AS id,nombre
					FROM localidad
					WHERE com_id = :idcomunidad
					AND prov_id = :idprovincia
					ORDER BY nombre ASC";
			$sth = $app['db']->prepare($sql);
			$sth->bindValue(':idcomunidad',intval($parts[0]), PDO::PARAM_INT);
			$sth->bindValue(':idprovincia',intval($parts[1]), PDO::PARAM_INT);
			$leaf = 'true';
			$cls = 'file';
		}
	}

	$sth->execute();
	$items = $sth->fetchAll();

	$response = new Response(
		$app['twig']->render('items.json.twig',
			array(
				'items' => $items,
				'leaf' => $leaf,
				'cls' => $cls,
			)
		), 200
	);

	return $response;
});

$app->after(function() use ($app)
{
	$app['db'] = null;
});

$app->run();