<?php

require_once 'vendor/autoload.php';
ini_set(display_errors, 1);
error_reporting(E_ALL);

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader('templates');
$twig = new Environment($loader);

include 'connectToDb.php';
$db = (new DatabaseInterface('content_database'))->connect();

if(empty($_GET) && empty($_POST))
{
    echo $twig->render('index.html.twig', ['content' => fetchContent($db)]);
}

if(isset($_POST['action']) && $_POST['action'] == 'save')
{
    $new_row = $_POST['content'];
    $title = $db->getConnection()->real_escape_string($new_row['title']);
    $content = $db->getConnection()->real_escape_string($new_row['content']);
    $result = $db->runQuery("INSERT INTO content VALUES(NULL, '" . $title ."' ,'" . $content . "')");
    echo ($result ? json_encode(['success' => true, 'id' => $db->getConnection()->insert_id]) : json_encode(['success' => false]));
}
elseif(isset($_POST['action']) && $_POST['action'] == 'delete')
{
    $id = $_POST['id'];
    $result = $db->runQuery("DELETE FROM content WHERE `id`=" .  $id);
    echo ($result ? json_encode(['success' => true]) : json_encode(['success' => false]));
}

function fetchContent($db)
{
    $data = $db->runQuery("SELECT * FROM `content`");

    while($row = $data->fetch_assoc())
    {
        $rows[] = $row;
    }

    $db->disconnect();

    if(!empty($rows))
        return $rows;
    else
        return [];
}