<?php 
if (!defined('Access')) {
    die('Direct access not permitted');
}

function show_board($input)
{
    header('Content-type: application/json');
    print json_encode(read_board(), JSON_PRETTY_PRINT);
}

function read_board()
{
    global $mysqli;
    $sql = 'select * from Board';
    $st = $mysqli->prepare($sql);
    $st->execute();
    $res = $st->get_result();
    return ($res->fetch_all(MYSQLI_ASSOC));
}

function reset_board()
{
    global $mysqli;
    $sql = 'call reset_game()';
    $mysqli->query($sql);
}

function piece_list()
{
    global $mysqli;
    $sql = 'SELECT pieces_id from pieces where available=true';
    $st = $mysqli->prepare($sql);
    $st->execute();
    $res = $st->get_result();
    print json_encode($res->fetch_all(), JSON_PRETTY_PRINT);
}


function check_playerToken($move){

    global $mysqli;

    $sql = 'SELECT player as "pl" FROM players where token=?';
    $st = $mysqli->prepare($sql);
    $st->bind_param('s', $_COOKIE['tokenC']);
    $st->execute();
    $res = $st->get_result();
    $x = $res->fetch_assoc();
    if (mysqli_num_rows($res) < 1) {
        header("HTTP/1.1 400 Bad Request");
        exit;
    }

    if($move == "place"){
        place($x['pl']);
    }
    //
}

function place($x)
{
    global $mysqli;
    $sql2 =  'SELECT state,turn,piece FROM game_status ORDER BY id DESC LIMIT 1';
    $st2 = $mysqli->prepare($sql2);
    $st2->execute();
    $res2 = $st2->get_result();
    $z = $res2->fetch_assoc();

    if(!($x == $z['turn'] && $z['state']=='place' )){
        print json_encode(['errormesg' => "Not the turn for that move"]);
        exit;
    }

    $sql =  'SELECT 1 FROM Board where x = ? and y = ? and pieceID is not null';
    $st = $mysqli->prepare($sql);
    $st->bind_param('ii', $GLOBALS['input']['x'],$GLOBALS['input']['y']);
    $st->execute();
    $res = $st->get_result();
    if (mysqli_num_rows($res) == 1) {
        header("HTTP/1.1 400 Bad Request");
        exit;
    }

    $sql =  'call placepiece(?,?,?)';
    $st = $mysqli->prepare($sql);
    $st->bind_param('iii', $GLOBALS['input']['x'],$GLOBALS['input']['y'],$z['piece']);
    $st->execute();
    

}