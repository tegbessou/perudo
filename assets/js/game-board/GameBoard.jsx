import {render, unmountComponentAtNode} from 'react-dom'
import React, {useEffect} from 'react'
import {useFetch} from './hooks';
import {Dice} from "../components/Dice";

function GameBoard ({uuid}) {
    const {item: gameBoard, load, loading, players} = useFetch('/api/games/' + uuid)

    useEffect(() => {
        load()
    }, [])

    return <div>
        {loading && 'Chargement...'}
        {players.map(player => <Player key={player.id} player={player} />)}
    </div>
}

function Player ({player}) {
    return <div>
        <h4>{player['pseudo']}</h4>
        {!player['bot'] && player['dices'].map((dice, index) => <Dice key={index} color={player['diceColor']} number={dice} />)}
        {player['myTurn'] && <h4>A moi de jouer</h4>}
        {!player['bot'] && player['myTurn'] && <button className="btn btn-primary">Lier</button>}
        {!player['bot'] && player['myTurn'] && <button className="btn btn-primary">Bet</button>}
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