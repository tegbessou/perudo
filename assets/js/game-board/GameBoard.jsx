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
    const [resultLiarAction, setResultLiarAction] = useState({players: null, isLiarAction: false});
    const [looser, setLooser] = useState(null);
    const addBet = (bets) => {
        setBetsList(bets);
    };
    const [currentPlayer, setCurrentPlayer] = useState({});
    const selectCurrentPlayer = (player) => {
        setCurrentPlayer(player);
    }

    const {
        response: players,
        loading: playerLoading,
    } = useFetch("/api/players?game=" + gameId);

    const {
        response: game,
        error,
        loading,
    } = useFetch("/api/games/" + gameId);

    useEffect(() => {
        if (currentPlayer.myTurn && currentPlayer.bot) {
            console.log('Le bot joue');
        }

        if (!playerLoading && resultLiarAction.players) {
            for (let i = 0; i < players['hydra:member'].length; i++) {
                if (players['hydra:member'][i].bot) {
                    players['hydra:member'][i] = {
                        ...players['hydra:member'][i],
                        dices: resultLiarAction.players[i].dices
                    }
                }
            }
        }

        if (!playerLoading && !resultLiarAction.players) {
            for (let i = 0; i < players['hydra:member'].length; i++) {
                if (players['hydra:member'][i].bot) {
                    players['hydra:member'][i] = {
                        ...players['hydra:member'][i],
                        dices: []
                    }
                }
            }
        }
    }, [betsList, currentPlayer, looser, resultLiarAction, players])

    return <div>
        <BetsListProvider value={{betsList, addBet}}>
            <CurrentPlayerProvider value={{currentPlayer, selectCurrentPlayer}}>
                {looser && <div>Le joueur {currentPlayer.pseudo} a dit menteur a {betsList[betsList.length - 1].player.pseudo} !<br /> Le perdant est {looser.pseudo}, il perd donc une vie. <br /> Le prochain tour commence dans 10 secondes !</div>}
                <div className="game-board-player-list">
                    {!loading && !playerLoading && !error && players['hydra:member'].map(player => <Player key={player.id} player={player} game={game} isCurrentPlayer={player.id===currentPlayer.id} setResultLiarAction={setResultLiarAction} resultLiarAction={resultLiarAction} looser={looser} setLooser={setLooser}/>)}
                </div>
                <div>
                    {!loading && !playerLoading && !error && <BetList game={game}/>}
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