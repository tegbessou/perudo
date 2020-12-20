export function getDiceNumberOnGame(game) {
  let diceNumber = 0;
  for (let i = 0; i < game.numberOfPlayers; i++) {
    diceNumber += game.players[i].numberOfDices;
  }

  return diceNumber;
}

export function generateDiceValue(diceValue) {
  const result = [];

  for (let i = diceValue + 1; i <= 6; i++) {
    result.push({ value: i, label: i });
  }

  return result;
}
