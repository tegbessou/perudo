Feature: 
  In order to visit the home page
  As a User
  I want to see home page
  
  @read-only
  Scenario: As a User I want to see the home page
    Given I go to "/"
    Then the response status code should be 200
    And the response should contain "Prêt à jouer au Perudo ?"

  @read-only
  Scenario: As a User I want to start a game
    Given I go to "/"
    When I follow "Jouer"
    Then the response status code should be 200
    And the response should contain "Pseudo"

  @read-only
  Scenario: As a User I want to go back to the home page
    Given I go to "/game/new"
    When I follow "Perudo"
    Then the response status code should be 200
    And the response should contain "Prêt à jouer au Perudo ?"