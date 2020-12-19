import React from "react";

const CurrentPlayerContext = React.createContext({
  currentPlayer: {},
  selectCurrentPlayer: () => {},
});
export const CurrentPlayerProvider = CurrentPlayerContext.Provider;

export default CurrentPlayerContext;
