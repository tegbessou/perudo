import React, {useContext, useEffect} from "react";
import CheckIcon from "@material-ui/icons/Check";
import green from "@material-ui/core/colors/green";
import Dice from "../components/Dice";
import BetForm from "../bet/BetForm";
import {Button} from "@material-ui/core";
import CurrentPlayerContext from "../context/currentPlayerContext";
import BetsListContext from "../context/betsListContext";

export default function Player ({player, game, isCurrentPlayer}) {
  const { selectCurrentPlayer } = useContext(CurrentPlayerContext);
  const { betsList } = useContext(BetsListContext);

  useEffect(() => {
    chooseCurrentPlayer(player);
  }, [betsList]);

  const chooseCurrentPlayer = () => {
    if (player.myTurn) {
      selectCurrentPlayer(player);
    }
  }

  return <div>
    <h4>{player && player["pseudo"]} {player && player && isCurrentPlayer && <CheckIcon style={{ color: green[500] }}/>}</h4>
    {player && !player["bot"] && player["dices"].map((dice, index) => <Dice key={index} color={player["diceColor"]} number={dice} />)}
    {player && !player["bot"] && <BetForm isCurrentPlayer={isCurrentPlayer} player={player} game={game}/>}
    {player && !player["bot"] && isCurrentPlayer && <Button variant="contained" color="primary">Menteur</Button>}
  </div>
}