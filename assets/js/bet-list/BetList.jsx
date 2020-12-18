import React, {useEffect, useContext, useState} from 'react'
import BetsListContext from "../context/betsListContext";

export function BetList ({game}) {
  const [loading, setLoading] = useState(false);
  const { betsList, addBet } = useContext(BetsListContext);

  useEffect(() => {
    loadBets();
  }, []);

  const loadBets = async () => {
    setLoading(true);
    let bets = await fetch('/api/bets?game=' + game).then(res => res.json());
    if (bets['hydra:member'].length > 0) {
      addBet(bets['hydra:member']);
    }
    setLoading(false);
  };

  return <div>
    <div className="card">
      <div className="card-body">
        <h5 className="card-title">Liste des paris</h5>
        {loading && "Chargement..."}
        {betsList.length > 0 && betsList.map(bet => <div key={bet.id}>{bet.player.pseudo} a parié {bet.diceNumber} dé{bet.diceNumber > 1 && 's'} de {bet.diceValue}</div>)}
      </div>
    </div>
  </div>
}