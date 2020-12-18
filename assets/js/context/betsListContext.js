import React from 'react';

const BetsListContext = React.createContext({
  'betsList': [],
  'addBet': () => {},
});
export const BetsListProvider = BetsListContext.Provider;
export const BetsListConsumer = BetsListContext.Consumer;

export default BetsListContext;