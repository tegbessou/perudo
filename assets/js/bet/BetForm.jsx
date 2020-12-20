import React, {useState, useEffect, useContext, useCallback} from "react"
import FormControl from "@material-ui/core/FormControl";
import Select from "@material-ui/core/Select";
import useFetch from "../hooks/useFetch";
import { createStyles, makeStyles } from "@material-ui/core/styles";
import InputLabel from "@material-ui/core/InputLabel";
import MenuItem from "@material-ui/core/MenuItem";
import {Button} from "@material-ui/core";
import BetsListContext from "../context/betsListContext";
import {getDiceNumberOnGame, generateDiceValue} from "./utils";
import postApi from "../api/PostAPi";
import Snackbar from "@material-ui/core/Snackbar";
import Alert from "../components/Alert";
import CurrentPlayerContext from "../context/currentPlayerContext";
import fetchApi from "../api/FetchAPi";

const useStyles = makeStyles((theme) =>
  createStyles({
    formControl: {
      margin: theme.spacing(1),
      minWidth: 120,
    },
    selectEmpty: {
      marginTop: theme.spacing(2),
    },
  }),
);

export default function BetForm ({game, player, isCurrentPlayer}) {
  const { betsList, addBet } = useContext(BetsListContext);
  const { selectCurrentPlayer } = useContext(CurrentPlayerContext);
  const classes = useStyles();
  const [diceNumber, setDiceNumber] = useState(1);
  const [diceValue, setDiceValue] = useState(1);
  const [diceNumberOptions, setDiceNumberOptions] = useState([]);
  const [diceValueOptions, setDiceValueOptions] = useState([]);
  const [error, setError] = useState(null);

  const {
    response: lastBet,
    loading,
  } = useFetch("/api/bets?game={game.id}&itemsPerPage=1&order[id]=desc");

  useEffect(() => {
      loadDiceNumberPossibility(game, lastBet);
      loadDiceValuePossibility(game, lastBet)
    },
    [loading]
  )

  const loadDiceNumberPossibility = async (game, lastBet) => {
    if (loading) {
      return;
    }
    let diceNumber = getDiceNumberOnGame(game)
    let startDiceNumber = 1;
    let startDiceValue = 1;
    if (lastBet["hydra:member"].length > 0) {
      startDiceNumber = lastBet["hydra:member"][0].diceNumber;
      startDiceValue = lastBet["hydra:member"][0].diceValue;
    }
    let result = [];

    if (startDiceValue === 6) {
      startDiceNumber += 1;
    }

    for (let i = startDiceNumber; i <= diceNumber; i++) {
        result.push({value: i, label: i});
    }
    setDiceNumber(startDiceNumber);
    setDiceNumberOptions(result);
  };

  const loadDiceValuePossibility = async (game, lastBet) => {
    if (loading) {
      return;
    }
    let lastBetDiceValue = 0;
    if (lastBet["hydra:member"].length > 0) {
      lastBetDiceValue = lastBet["hydra:member"][0].diceValue;
    }

    if (lastBetDiceValue === 6) {
      lastBetDiceValue = 0;
    }

    setDiceValue(lastBetDiceValue === 0 ? 1 : lastBetDiceValue + 1);
    setDiceValueOptions(generateDiceValue(lastBetDiceValue));
  };

  const onChangeDiceNumber = async (event, lastBet) => {
    let lastBetDiceNumber = lastBet["hydra:member"][0].diceNumber;
    let lastBetDiceValue = lastBet["hydra:member"][0].diceValue;

    setDiceNumber(event.target.value);
    setDiceValue(event.target.value <= lastBetDiceNumber ? lastBetDiceValue + 1: 1)
    setDiceValueOptions(generateDiceValue(event.target.value <= lastBetDiceNumber ? lastBetDiceValue : 0));
  };

  const onChangeDiceValue = (event) => {
    setDiceValue(event.target.value);
  };

  const submitBet = async (e, game) => {
    e.preventDefault();

    try {
      const fetchResponse = await postApi(
        "/api/bets", {
        body:
          {
            "game": "/api/games/" + game.id,
            "player": player["@id"],
            "diceNumber": diceNumber,
            "diceValue": diceValue,
          },
        method: "POST",
      });
      if (fetchResponse["@context"] === "/api/contexts/ConstraintViolationList") {
        throw fetchResponse["hydra:description"];
      }
      loadDiceNumberPossibility(game, {"hydra:member" : [fetchResponse]});
      loadDiceValuePossibility(game, {"hydra:member" : [fetchResponse]});
      addBet([...betsList, fetchResponse]);
      fetchApi("/api/players?game={game.id}&myTurn=true", {}, selectCurrentPlayer, true);
    } catch (e) {
      setError(e);
    }
  }

  return <div>
    <form onSubmit={(e) => {submitBet(e, game, lastBet)}}>
      <FormControl className={classes.formControl}>
        <InputLabel id="dice-number-select-label">Dés</InputLabel>
        <Select
          value={diceNumber}
          labelId="dice-number-select-label"
          id="dice-number-simple-select"
          onChange={(event) => {onChangeDiceNumber(event, lastBet)}}
        >
          {diceNumberOptions.map((option) => <MenuItem key={option.value} value={option.value}>{option.label}</MenuItem>)}
        </Select>
      </FormControl>
      <FormControl className={classes.formControl}>
        <InputLabel id="dice-value-select-label">Valeur</InputLabel>
        <Select
          value={diceValue}
          labelId="dice-value-select-label"
          id="dice-value-simple-select"
          onChange={onChangeDiceValue}
        >
          {diceValueOptions.map((option) => <MenuItem key={option.value} value={option.value}>{option.label}</MenuItem>)}
        </Select>
      </FormControl>
      <FormControl className={classes.formControl}>
          {!player["bot"] && isCurrentPlayer && <Button id="bet-submit-button" type="submit" variant="contained" color="primary">Parier</Button>}
      </FormControl>
    </form>
    <Snackbar open={error !== null}>
      <Alert severity="error">
        {error}
      </Alert>
    </Snackbar>
  </div>
}