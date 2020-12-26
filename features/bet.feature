@javascript
Feature:
  In order to play a game
  As a User
  I want to be able to bet

  @read-only
  Scenario: As a User I want to see bets list of my game
    Given I go to "/game/1"
    Then I wait 10 seconds until I see "Liste des paris"
    And I wait 10 seconds until I see "Pedro a parié 1 dé de 1"
    And I wait 10 seconds until I see "Bot 1 a parié 1 dé de 2"

  @read-only
  Scenario: As a User I want to see game board of my game
    Given I go to "/game/1"
    Then I wait 10 seconds until I see "Game de Pedro"
    And I wait 10 seconds until I see "Pedro"
    And I wait 10 seconds until I see "Dé"
    And I wait 10 seconds until I see "1"
    And I wait 10 seconds until I see "Valeur"
    And I wait 10 seconds until I see "3"
    And I wait 10 seconds until I see "Parier"
    And I wait 10 seconds until I see "Menteur"
    And I wait 10 seconds until I see "Bot 1"
    And I wait 10 seconds until I see "Bot 2"

  Scenario: As a User I want to see bet on my game
    Given I go to "/game/1"
    When I choose value "2" on Material UI select with id "dice-number-select-label"
    And I check if "dice-number-simple-select" field is equal to "2"
    And I check if "dice-value-simple-select" field is equal to "1"
    And I choose value "3" on Material UI select with id "dice-value-select-label"
    And I check if "dice-value-simple-select" field is equal to "3"
    And I wait 10 seconds until I see "Parier"
    And I press "bet-submit-button"
    Then I wait 10 seconds until I see "Pedro a parié 2 dés de 3"
    And I check if "dice-number-simple-select" field is equal to "2"
    And I check if "dice-value-simple-select" field is equal to "4"
    And I should not see "Parier" within 5 seconds