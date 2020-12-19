import React, {useState, useEffect, useContext, useCallback} from 'react'
import FormControl from '@material-ui/core/FormControl';
import Select from '@material-ui/core/Select';
import { createStyles, makeStyles } from '@material-ui/core/styles';
import InputLabel from '@material-ui/core/InputLabel';
import MenuItem from '@material-ui/core/MenuItem';
import {Button} from "@material-ui/core";
import BetsListContext from "../context/betsListContext";
import {getPagination, sendData} from "../api";
import {getDiceNumberOnGame, generateDiceValue} from "./utils";

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

// Upgrade this code
export function BetForm ({game, player}) {
  const { betsList, addBet } = useContext(BetsListContext);
  const {items: lastBet, load} = getPagination('/api/bets?game=' + game.id + '&itemsPerPage=1&order[id]=desc');
  const {item: bet, post, errors, hasErrors, pending} = sendData('/api/bets');
  const classes = useStyles();
  const [diceNumber, setDiceNumber] = useState(1);
  const [diceValue, setDiceValue] = useState(1);
  const [diceNumberOptions, setDiceNumberOptions] = useState([]);
  const [diceValueOptions, setDiceValueOptions] = useState([]);

  useEffect(() => {
    load();
  }, []);

  useEffect(() => {
    loadDiceNumberPossibility(game, lastBet);
    loadDiceValuePossibility(game, lastBet);
  }, [lastBet]);

  const loadDiceNumberPossibility = async (game, lastBet) => {
    if (lastBet.length === 0) {
      return;
    }
    let diceNumber = getDiceNumberOnGame(game)
    let startDiceNumber = 1;
    let startDiceValue = 1;
    if (lastBet.length > 0) {
      startDiceNumber = lastBet[0].diceNumber;
      startDiceValue = lastBet[0].diceValue;
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
    if (lastBet.length === 0) {
      return;
    }
    let lastBetDiceValue = 0;
    if (lastBet.length > 0) {
      lastBetDiceValue = lastBet[0].diceValue;
    }

    if (lastBetDiceValue === 6) {
      lastBetDiceValue = 0;
    }

    setDiceValue(lastBetDiceValue === 0 ? 1 : lastBetDiceValue + 1);
    setDiceValueOptions(generateDiceValue(lastBetDiceValue));
  };

  const onChangeDiceNumber = async (event, lastBet) => {
    let lastBetDiceNumber = lastBet[0].diceNumber;
    let lastBetDiceValue = lastBet[0].diceValue;

    setDiceNumber(event.target.value);
    setDiceValue(event.target.value <= lastBetDiceNumber ? lastBetDiceValue + 1: 1)
    setDiceValueOptions(generateDiceValue(event.target.value <= lastBetDiceNumber ? lastBetDiceValue : 0));
  };

  const onChangeDiceValue = (event) => {
    setDiceValue(event.target.value);
  };

  const submitBet = async (e, game) => {
    //Add error management
    //Clean cache when bet
    e.preventDefault();

    let body = {
      'game': '/api/games/' + game.id,
      'player': player['@id'],
      'diceNumber': diceNumber,
      'diceValue': diceValue,
    };
    //Create hooks to push request
    post(body);
    console.log(bet);
    if (hasErrors) {
      return;
    }

    load();
  }

  return <div>
    {console.log(betsList)}
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
          {!player['bot'] && player['myTurn'] && <Button type="submit" variant="contained" color="primary">Parier</Button>}
      </FormControl>
    </form>
  </div>
}