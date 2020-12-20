import React, {useEffect, useContext} from "react"
import BetsListContext from "../context/betsListContext";
import useFetch from "../hooks/useFetch";

export default function BetList ({game}) {
  const { betsList, addBet } = useContext(BetsListContext);

  const {
    response: bets,
    loading,
  } = useFetch("/api/bets?game=" + game.id);

  useEffect(() => {
    loadBets();
  }, [loading]);

  const loadBets = async () => {
    if (loading) {
      return;
    }

    if (bets["hydra:member"].length > 0) {
      addBet(bets["hydra:member"]);
    }
  };

  return <div>
    <div className="card">
      <div className="card-body">
        <h5 className="card-title">Liste des paris</h5>
        {loading && "Chargement..."}
        {betsList.length > 0 && betsList.map(bet => <div key={bet.id}>{bet.player.pseudo} a parié {bet.diceNumber} dé{bet.diceNumber > 1 && "s"} de {bet.diceValue}</div>)}
      </div>
    </div>
  </div>
}