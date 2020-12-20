import {render, unmountComponentAtNode} from "react-dom";
import React, {useEffect, useState} from "react";
import useFetch from "../hooks/useFetch";
import Alert from "../components/Alert"
import BetList from "../bet-list/BetList";
import Player from "../player/Player";
import {BetsListProvider} from "../context/betsListContext";
import {CurrentPlayerProvider} from "../context/currentPlayerContext";
import Snackbar from "@material-ui/core/Snackbar";

const GameBoard = React.memo(({gameId}) => {
    const [betsList, setBetsList] = useState([]);
    const addBet = (bets) => {
        setBetsList(bets);
    };
    const [currentPlayer, setCurrentPlayer] = useState({});
    const selectCurrentPlayer = (player) => {
        setCurrentPlayer(player);
    }

    const {
        response: game,
        error,
        loading,
    } = useFetch("/api/games/" + gameId);

    return <div>
        <BetsListProvider value={{betsList, addBet}}>
            <CurrentPlayerProvider value={{currentPlayer, selectCurrentPlayer}}>
                <div className="game-board-player-list">
                    {!loading && !error && game.players.map(player => <Player key={player.id} player={player} game={game} isCurrentPlayer={player.id===currentPlayer.id}/>)}
                </div>
                <div>
                    {!loading && !error && <BetList game={game}/>}
                </div>
                <Snackbar open={error}>
                    <Alert severity="error">
                        {error && "Not found"}
                    </Alert>
                </Snackbar>
            </CurrentPlayerProvider>
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

customElements.define("game-board", GameBoardElement)