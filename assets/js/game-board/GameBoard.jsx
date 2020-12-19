import {render, unmountComponentAtNode} from 'react-dom'
import React, {useState, useEffect} from 'react'
import {get} from '../api/index';
import {Alert} from '../components/Alert'
import {BetList} from "../bet-list/BetList";
import {Player} from "../player/Player";
import {BetsListProvider} from "../context/betsListContext";
import Snackbar from '@material-ui/core/Snackbar';

const GameBoard = React.memo(({gameId}) => {
    const {item: game, loading, error, hasError, load} = get('/api/games/' + gameId);
    const [betsList, setBetsList] = useState([]);
    const addBet = (bets) => {
        setBetsList(bets);
    }

    useEffect(() => {
        load();
    }, [betsList])

    return <div>
        <BetsListProvider value={{betsList, addBet}}>
            <div className='game-board-player-list'>
                {!loading && !hasError && game.players.map(player => <Player key={player.id} player={player} gameId={gameId}/>)}
            </div>
            <div>
                {!loading && !hasError && <BetList game={gameId}/>}
            </div>
            <Snackbar open={hasError}>
                <Alert severity="error">
                    {hasError && error}
                </Alert>
            </Snackbar>
        </BetsListProvider>
    </div>
})

class GameBoardElement extends HTMLElement {
    connectedCallback() {
        const gameId = this.dataset.game;
        render(<GameBoard gameId={gameId}/>, this)
    }

    disconnectedCallback() {
        unmountComponentAtNode(this)
    }
}

customElements.define('game-board', GameBoardElement)