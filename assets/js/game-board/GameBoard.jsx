import {render, unmountComponentAtNode} from 'react-dom'
import React, {useState, useEffect, useContext} from 'react'
import {useFetch} from '../hooks/hooks';
import {Dice} from "../components/Dice";
import {BetForm} from "../bet/BetForm";
import {BetList} from "../bet-list/BetList";
import {Button} from "@material-ui/core";
import CheckIcon from '@material-ui/icons/Check';
import green from "@material-ui/core/colors/green";
import BetsListContext, {BetsListProvider} from "../context/betsListContext";

const GameBoard = React.memo(({gameId}) => {
    const {item, load, loading, players} = useFetch('/api/games/' + gameId);
    const [betsList, setBetsList] = useState([]);
    const addBet = (bets) => {
        setBetsList(bets);
    }

    const style = {
        float: 'left',
    }

    useEffect(() => {
        load()
    }, [betsList])

    return <div>
        {console.log(item)}
        <BetsListProvider value={{betsList, addBet}}>
            <div style={style}>
                {loading && 'Chargement...'}
                {players.map(player => <Player key={player.id} player={player} gameId={gameId}/>)}
            </div>
            <div>
                <BetList game={gameId}/>
            </div>
        </BetsListProvider>
    </div>
})

//Extract player in other component
const Player = React.memo(({player, gameId}) => {
    return <div>
        <h4>{player && player['pseudo']} {player && player.myTurn && <CheckIcon style={{ color: green[500] }}/>}</h4>
        {player && !player['bot'] && player['dices'].map((dice, index) => <Dice key={index} color={player['diceColor']} number={dice} />)}
        {player && !player['bot'] && <BetForm player={player} gameId={gameId}/>}
        {player && !player['bot'] && player['myTurn'] && <Button variant="contained" color="primary">Menteur</Button>}
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