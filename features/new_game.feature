Feature:
  In order to start a game
  As a User
  I want to see new game page

  @read-only
  Scenario: As a User I want to start a game
    Given I go to "/game/new"
    When I fill in "Pedro" for "game[creator]"
    And I select "3" from "game[numberOfPlayers]"
    And I press "Jouer !!"
    Then the response status code should be 200
    And the response should contain "Game de Pedro"
    And the response should contain "Pedro"
    And the response should contain "Bot 1"

  @read-only
  Scenario: As a User I want to start a game with 5 player
    Given I go to "/game/new"
    When I fill in "Pedro" for "game[creator]"
    And I select "5" from "game[numberOfPlayers]"
    And I press "Jouer !!"
    Then the response status code should be 200
    And the response should contain "Game de Pedro"
    And the response should contain "Pedro"
    And the response should contain "Bot 1"
    And the response should contain "Bot 2"
    And the response should contain "Bot 3"
    And the response should contain "Bot 4"