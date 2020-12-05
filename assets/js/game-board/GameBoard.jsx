import {render, unmountComponentAtNode} from 'react-dom'
import React, {useEffect} from 'react'
import {useFetch} from './hooks';
import {Dice} from "../components/Dice";

function GameBoard ({uuid}) {
    const {item: gameBoard, load, loading, players} = useFetch('/api/game_models/' + uuid)

    useEffect(() => {
        load()
    }, [])

    return <div>
        {loading && 'Chargement...'}
        {players.map(player => <Player key={player['uuid']} player={player} />)}
        <button onClick={load}>Charger GameModel</button>
    </div>
}

function Player ({player}) {
    return <div>
        <h4>{player['pseudo']}</h4>
        {!player['bot'] && player['dices'].map((dice, index) => <Dice key={index} color={player['diceColor']} number={dice} />)}
    </div>
}

class GameBoardElement extends HTMLElement {
    connectedCallback() {
        const uuid = this.dataset.game;
        render(<GameBoard uuid={uuid}/>, this)
    }

    disconnectedCallback() {
        unmountComponentAtNode(this)
    }
}

customElements.define('game-board', GameBoardElement)