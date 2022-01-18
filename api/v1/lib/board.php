<?php 

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


function chech_valid($token){

    global $mysqli;

    $sql = 'SELECT player as "pl" FROM players where token=?';
    $st = $mysqli->prepare($sql);
    $st->bind_param('s', $token );
    $st->execute();
    $res = $st->get_result();
    $x = $res->fetch_assoc();
    $x['pl'];
    echo $x['pl'];

    $sql1 = 'SELECT turn ass "tr" FROM game_status';
    $st1 = $mysqli->prepare($sql1);
    $st1->bind_param('s', $token );
    $st1->execute();
    $res1 = $st1->get_result();
    $y = $res1->fetch_assoc();
    $y['tr'];
    echo $y['tr'];

    $sql2 =  'SELECT state FROM game_status ORDER BY id DESC LIMIT 1';
    $st2 = $mysqli->prepare($sql2);
    $st2->execute();
    $res2 = $st2->get_result();
    $z = $res2->fetch_assoc();

    if($x['pl'] == $y['tr']){
        
    }
}



function 