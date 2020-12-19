import React from "react";
import CheckIcon from "@material-ui/icons/Check";
import green from "@material-ui/core/colors/green";
import {Dice} from "../components/Dice";
import {BetForm} from "../bet/BetForm";
import {Button} from "@material-ui/core";

export function Player ({player, game}) {
    return <div>
      <h4>{player && player['pseudo']} {player && player.myTurn && <CheckIcon style={{ color: green[500] }}/>}</h4>
      {player && !player['bot'] && player['dices'].map((dice, index) => <Dice key={index} color={player['diceColor']} number={dice} />)}
      {player && !player['bot'] && <BetForm player={player} game={game}/>}
      {player && !player['bot'] && player['myTurn'] && <Button variant="contained" color="primary">Menteur</Button>}
    </div>
}