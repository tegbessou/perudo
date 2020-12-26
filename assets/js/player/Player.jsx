import React, {useContext, useEffect, useState} from "react";
import CheckIcon from "@material-ui/icons/Check";
import green from "@material-ui/core/colors/green";
import Dice from "../components/Dice";
import BetForm from "../bet/BetForm";
import {Button} from "@material-ui/core";
import CurrentPlayerContext, {CurrentPlayerProvider} from "../context/currentPlayerContext";
import BetsListContext from "../context/betsListContext";
import postApi from "../api/PostAPi";
import Snackbar from "@material-ui/core/Snackbar";
import Alert from "../components/Alert";
import fetchApi from "../api/FetchAPi";

export default function Player ({player, game, isCurrentPlayer, resultLiarAction, setResultLiarAction, looser, setLooser}) {
  const { selectCurrentPlayer } = useContext(CurrentPlayerContext);
  const [liarError, setLiarError] = useState({isLiarError: false, content: ""});
  const [disabledLiarButton, setDisabledLiarButton] = useState(false);
  const [resetDiceValue, setResetDiceValue] = useState(false);
  const { betsList, addBet } = useContext(BetsListContext);

  useEffect(() => {
    chooseCurrentPlayer(player);
    if (resultLiarAction.isLiarAction && looser) {
      setTimeout(async () => {
        await postApi("/api/games/" + game.id + "/new_turn", {
          method: "POST",
          body: {
            "looserId": looser.id
          }
        });
        fetchApi('/api/players/' + looser.id, {}, selectCurrentPlayer);
        setDisabledLiarButton(false);
        setLooser(null);
        setResultLiarAction({isLiarAction: false, player: {}});
        addBet([]);
        setResetDiceValue(true);
      }, 10000);
    }
  }, [betsList, resultLiarAction.isLiarAction, game, looser]);

  const chooseCurrentPlayer = () => {
    if (player.myTurn) {
      selectCurrentPlayer(player);
    }
  }

  const liarAction = async () => {
    setResetDiceValue(false);
    setDisabledLiarButton(true);
    const response = await postApi("/api/players/" + player.id + "/tell_liar", {
      method: "POST",
      body: {}
    });

    if (response["@type"] === "hydra:Error") {
      setLiarError({isLiarError: true, content: response['hydra:description']});
      setDisabledLiarButton(false);

      return;
    }
    const liarPlayers = JSON.parse(response.players);
    await fetchApi('/api/players/' + response.looser, {}, setLooser);
    setResultLiarAction({isLiarAction: true, players: liarPlayers});
  }

  const handleClose = () => {
    setLiarError({isLiarError: false, content: ""});
  }

  return <div>
    <h4>{player && player["pseudo"]} {player && player && isCurrentPlayer && <CheckIcon style={{ color: green[500] }}/>}</h4>
    {player && player["dices"] && player["dices"].map((dice, index) => <Dice key={index} color={player["diceColor"]} number={dice} />)}
    {player && !player["bot"] && <BetForm resetDiceValue={resetDiceValue} isCurrentPlayer={isCurrentPlayer} player={player} game={game}/>}
    {player && !player["bot"] && isCurrentPlayer && <Button id="liar-button" disabled={disabledLiarButton} onClick={liarAction} variant="contained" color="primary">Menteur</Button>}
    <Snackbar open={liarError.isLiarError} autoHideDuration={6000} onClose={handleClose}>
      <Alert severity="error">
        {liarError.isLiarError && liarError.content}
      </Alert>
    </Snackbar>
  </div>
}