@javascript
Feature:
  In order to play a game
  As a User
  I want to be able to tell "liar"

  Scenario: As a User I want to see bets list of my game
    Given I go to "/game/1"
    When I wait 10 seconds until I see "Liste des paris"
    And I wait 10 seconds until I see "Pedro a parié 1 dé de 1"
    And I wait 10 seconds until I see "Bot 1 a parié 1 dé de 2"
    And I wait 10 seconds until I see "Menteur"
    And I should not see "Le joueur Pedro a dit menteur a Bot 1 !" within 5 seconds
    And I should not see "Le perdant est Pedro, il perd donc une vie." within 5 seconds
    And I should not see "Le prochain tour commence dans 10 secondes !" within 5 seconds
    And I press "liar-button"
    Then I wait 5 seconds until I see "Le joueur Pedro a dit menteur a Bot 1 !"
    And I wait 5 seconds until I see "Le perdant est Pedro, il perd donc une vie."
    And I wait 5 seconds until I see "Le prochain tour commence dans 10 secondes !"
    And I wait 5 seconds for "span[data-title-test='dice-one-purple']" element
    And I wait 5 seconds for "span[data-title-test='dice-one-green']" element
    And I wait 15 seconds until I don't see "Pedro a parié 1 dé de 1"
    And I check if "dice-number-simple-select" field is equal to "1"
    And I check if "dice-value-simple-select" field is equal to "1"