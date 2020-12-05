@javascript
Feature:
  In order to start a game
  As a User
  I want to see new game page

  @read-only
  Scenario: As a User I want to start a game
    Given I go to "/game/new"
    When I fill in "Pedro" for "game[creator]"
    And I select "blue" from "game[creatorColor]"
    And I select "3" from "game[numberOfPlayers]"
    And I press "Jouer !!"
    Then the response should contain "Game de Pedro"
    And I wait 10 seconds until I see Pedro
    And I wait 10 seconds until I see "Bot 1"

  @read-only @javascript
  Scenario: As a User I want to start a game with 5 player
    Given I go to "/game/new"
    When I fill in "Pedro" for "game[creator]"
    And I select "green" from "game[creatorColor]"
    And I select "6" from "game[numberOfPlayers]"
    And I press "Jouer !!"
    Then the response should contain "Game de Pedro"
    And I wait 10 seconds until I see Pedro
    And I wait 10 seconds until I see "Bot 1"
    And I wait 10 seconds until I see "Bot 2"
    And I wait 10 seconds until I see "Bot 3"
    And I wait 10 seconds until I see "Bot 4"
    And I wait 10 seconds until I see "Bot 5"