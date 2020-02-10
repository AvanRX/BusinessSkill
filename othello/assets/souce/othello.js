
/*オセロ盤の縦横の長さ(4以下じゃないとゲーム木の構築に時間がかかる上ブラウザによっては停止する)*/
var N=4;

/*マスの状態を表す定数値(.cssでidとして使用)*/
var EMPTY='empty';
var WHITE='white';
var BLACK='black';


function Initialize_Board(){

    //create objects at othello boards 
    var board={};

    for(var x=0;x<N;x++){
        for(var y=0;y<N;y++){
            //othello board insert empty
            board[[x,y]]=EMPTY;
        }
    }

    //x2 equal x divide (2^swiftbitcount)
    var x2=x>>1;
    var y2=y>>1;

    //center 4 cells 
    board[[x2-1,y2-1]]=WHITE;
    board[[x2-1,y2-0]]=BLACK;
    board[[x2-0,y2-1]]=BLACK;
    board[[x2-0,y2-0]]=WHITE;

    return board;
}

function drawBoard(board,player){
    var ss=[];

    ss.push('<table>');
    for(var y=-1;y<N;y++){
        ss.push('<tr>');
        for(var x=-1;x<N;x++){
            if(0<=y&&0<=x){
                ss.push('<td class="');
                ss.push("cell");
                ss.push(' ');
                ss.push(board[[x,y]]);
                ss.push('">');
                ss.push('<span class="disc"></span>');
                ss.push('</td>');
            }
            else if(0 <= x && y == -1){
                ss.push('<th>'+'abcdefgh'[x]+'</th>');
            }
            else if(x == -1 && 0 <= y){
                ss.push('<th>'+'12345678'[y]+'</th>');
            }
            else{
                ss.push('<th></th>');
            }
        }
        ss.push('</tr>');
    }
    ss.push('</table>');

    document.getElementById('board').innerHTML = ss.join('');
    document.getElementById('current_player_name').innerText=(player);
}

//board(array),player(string),passed(bool)
/*ゲーム中のある局面(=ゲーム木)を作る*/
function makeGameTree(board_,player_,wasPassed){
    return{
        board:board_,
        player:player_,
        moves:listPossibleMoves(board_,player_,wasPassed)
    };

}

/*攻撃できる手を列挙する*/
function listPossibleMoves(board,player,wasPassed){
    return completePassingMove(
        listAttackingMoves(board,player),
        board,
        player,
        wasPassed
    );
}


/*必要ならパスする手を補完する*/
function completePassingMove(attackingMoves,board,player,wasPassed){
    if(0<attackingMoves.length){
        return attackingMoves;
    }
    else if(!wasPassed){
        return[{
            "isPassingMove":true,
            "gameTree":makeGameTree(board,nextPlayer(player),true)
        }];
    }
    else{
        return[];
    }
}

/*攻撃できる手を列挙する*/
function listAttackingMoves(board,player){
    var moves=[];

    for(var x=0;x<N;x++){
        for(var y=0;y<N;y++){
            if(canAttack(board,x,y,player)){
                moves.push({
                    x:x,
                    y:y,
                    gameTree:makeGameTree(
                        makeAttackedBoard(board,x,y,player),
                        nextPlayer(player),
                        false
                    )
                });
            }
        }
    }

    return moves;
}

/*次の手番のプレイヤーを返す*/
function nextPlayer(player){
    return player==BLACK?WHITE:BLACK;
}

/*攻撃可能かどうか*/
function canAttack(board,x,y,player){
    return listVulnerableCells(board,x,y,player).length;
}

/*石を置いた後の盤面を作る*/
function makeAttackedBoard(board,x,y,player){
    var newBoard=JSON.parse(JSON.stringify(board));
    var vulnerableCells=listVulnerableCells(board,x,y,player);
    for(var i=0;i<vulnerableCells.length;i++){
        newBoard[vulnerableCells[i]]=player;
    }
    return newBoard;
}

/*石が置けるかどうかの判定=>マスが空・八方に敵の石が1つ以上ある・敵の石の向こうに自分の石がある*/
function listVulnerableCells(board,x,y,player){
    var vulnerableCells = [];

    if (board[[x, y]] != EMPTY)
      return vulnerableCells;
  
    var opponent = nextPlayer(player);
    for (var dx = -1; dx <= 1; dx++) {
      for (var dy = -1; dy <= 1; dy++) {
        if (dx == 0 && dy == 0)
          continue;
        for (var i = 1; i < N; i++) {
          var nx = x + i * dx;
          var ny = y + i * dy;
          if (nx < 0 || N <= nx || ny < 0 || N <= ny)
            break;
          var cell = board[[nx, ny]];
          if (cell == player && 2 <= i) {
            for (j = 0; j < i; j++)
              vulnerableCells.push([x + j * dx, y + j * dy]);
            break;
          }
          if (cell != opponent)
            break;
        }
      }
    }
  
    return vulnerableCells;
}

function setUpUIToChooseMove(gameTree){
    document.getElementById('message').innerText=("次の手を選択してください");
    gameTree.moves.forEach(function (cv,ind,arr){
        var btn = document.createElement('button');
        btn.type="button";
        btn.innerText=(makeLabelForMove(gameTree.moves[ind]));
        btn.onclick=function(){shiftToNewGameTree(cv.gameTree);}
        document.getElementById('console').appendChild(btn);
    });
}

function makeLabelForMove(move){
    if(move.isPassingMove)
        return 'Pass';
    else
        return 'abcdefgh'[move.x]+','+'12345678'[move.y];
}

function resetUI(){
    document.getElementById('console').innerHTML='';
    document.getElementById('message').innerHTML='';
}

function showWinner(board){
    var nt={};
    nt[BLACK]=0;
    nt[WHITE]=0;

    for(var x=0;x<N;x++){
        for(var y=0;y<N;y++){
            nt[board[[x,y]]]++;
        }
    }
    document.getElementById('message').innerText=(
        nt[BLACK] == nt[WHITE] ? 'The Game Ends in a Draw'
        : 'The Winner is ' + (nt[WHITE] < nt[BLACK] ? BLACK : WHITE)
    );
}

function setUpUIReset(){
    var btn=document.createElement('button');
    btn.type="button";
    btn.innerText=('start a new game');
    btn.onclick=(function(){resetGame()});
   document.getElementById('console').appendChild(btn);
}

function resetGame(){
    var board=Initialize_Board();
    var game_tree = makeGameTree(board,BLACK,false)
    shiftToNewGameTree(game_tree);
}

function shiftToNewGameTree(gameTree){
   drawBoard(gameTree['board'],gameTree['player'],gameTree['moves']);
    resetUI();

    if(gameTree.moves.length==0){
        showWinner(gameTree['board']);
        setUpUIReset();
    }
    else{
        setUpUIToChooseMove(gameTree);
    }
}

(window.onload=function(){
    resetGame();
})
