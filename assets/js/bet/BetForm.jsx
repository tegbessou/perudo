import React, {useState, useEffect, useContext} from 'react'
import FormControl from '@material-ui/core/FormControl';
import Select from '@material-ui/core/Select';
import { createStyles, makeStyles, Theme } from '@material-ui/core/styles';
import InputLabel from '@material-ui/core/InputLabel';
import MenuItem from '@material-ui/core/MenuItem';
import {Button} from "@material-ui/core";
import BetsListContext from "../context/betsListContext";

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

export function BetForm ({gameId, player}) {
  const classes = useStyles();
  const [diceNumber, setDiceNumber] = useState(1);
  const [diceValue, setDiceValue] = useState(1);
  const [diceNumberOptions, setDiceNumberOptions] = useState([]);
  const [diceValueOptions, setDiceValueOptions] = useState([]);
  const [gameIdResource] = useState(gameId);
  const { betsList, addBet } = useContext(BetsListContext);

  useEffect(() => {
    loadDiceNumberPossibility();
    loadDiceValuePossibility();
  }, [])

  const getDiceNumberOfGame = async () => {
    let game = await fetch('https://perudo.docker/api/games/' + gameIdResource).then(res => res.json());
    let diceNumber = 0;
    for (let i = 0; i < game.numberOfPlayers; i++) {
      diceNumber += game.players[i].numberOfDices;
    }

    return diceNumber;
  }

  const generatDiceValue = lastBetDiceValue => {
    let result = [];

    for (let i = lastBetDiceValue + 1; i <= 6; i++) {
      result.push({value: i, label: i});
    }

    return result;
  }

  const loadDiceNumberPossibility = async () => {
    let lastBet = await fetch('https://perudo.docker/api/bets?game=' + gameIdResource + '&itemsPerPage=1&order[id]=desc').then(res => res.json());
    let diceNumber = await getDiceNumberOfGame()
    let startDiceNumber = 1;
    let startDiceValue = 1;
    if (lastBet['hydra:member'].length > 0) {
      startDiceNumber = lastBet['hydra:member'][0].diceNumber;
      startDiceValue = lastBet['hydra:member'][0].diceValue;
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

  const loadDiceValuePossibility = async () => {
    let lastBet = await fetch('https://perudo.docker/api/bets?game=' + gameIdResource + '&itemsPerPage=1&order[id]=desc').then(res => res.json());
    let lastBetDiceValue = 0;
    if (lastBet['hydra:member'].length > 0) {
      lastBetDiceValue = lastBet['hydra:member'][0].diceValue;
    }

    if (lastBetDiceValue === 6) {
      lastBetDiceValue = 0;
    }

    setDiceValue(lastBetDiceValue === 0 ? 1 : lastBetDiceValue + 1);
    setDiceValueOptions(generatDiceValue(lastBetDiceValue));
  };

  const onChangeDiceNumber = async (event) => {
    let lastBet = await fetch('https://perudo.docker/api/bets?game=' + gameIdResource + '&itemsPerPage=1&order[id]=desc').then(res => res.json());
    let lastBetDiceNumber = lastBet['hydra:member'][0].diceNumber;
    let lastBetDiceValue = lastBet['hydra:member'][0].diceValue;

    if (event.target.value <= lastBetDiceNumber) {
      setDiceValue(lastBetDiceValue+1)
      setDiceValueOptions(generatDiceValue(lastBetDiceValue));

      return;
    }

    setDiceNumber(event.target.value);
    setDiceValue(1);
    setDiceValueOptions(generatDiceValue(0));
  };

  const onChangeDiceValue = async (event) => {
    setDiceValue(event.target.value);
  };

  const bets = async (evt) => {
    evt.preventDefault();
    const settings = {
      method: 'POST',
      headers: {
        'Accept': 'application/ld+json',
        'Content-Type': 'application/json',
      }
    };

    settings.body = JSON.stringify(
      {
        'game': '/api/games/' + gameId,
        'player': player['@id'],
        'diceNumber': diceNumber,
        'diceValue': diceValue,
      }
    );

    try {
      const fetchResponse = await fetch(`https://perudo.docker/api/bets`, settings).then(res => res.json());
      loadDiceNumberPossibility();
      loadDiceValuePossibility();
      addBet([...betsList, fetchResponse]);
    } catch (e) {
      return e;
    }
  }

  return <div>
    <form onSubmit={bets}>
      <FormControl className={classes.formControl}>
        <InputLabel id="dice-number-select-label">Dés</InputLabel>
        <Select
          value={diceNumber}
          labelId="dice-number-select-label"
          id="dice-number-simple-select"
          onChange={onChangeDiceNumber}
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