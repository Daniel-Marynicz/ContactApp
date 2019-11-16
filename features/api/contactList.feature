@api
Feature: Contacts list
  I want to be able list my contacts
  Background:
    Given there are following contacts
      |  name |
      |  1    |
      |  2    |
      |  3    |
      |  4    |
      |  5    |
      |  6    |
      |  7    |
      |  8    |
      |  9    |
      |  10   |
  Scenario: can all list contacts
    Then I send a "GET" request to "/api/contact"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON nodes should contain:
      | page       | 1 |
      | totalPages | 1 |
      | count      | 10 |
      | limit      | 100 |
    And the JSON node "contacts" should have 10 elements
  Scenario: can limit contacts
    Then I send a "GET" request to "/api/contact?limit=2&page=1"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "contacts" should have 2 elements
    And the JSON nodes should contain:
      | page       | 1 |
      | totalPages | 5 |
      | count      | 10 |
      | limit      | 2 |
      | contacts[0].name | 1 |
      | contacts[1].name | 2 |
  Scenario: can limit contacts on page 3
    Then I send a "GET" request to "/api/contact?limit=2&page=3"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "contacts" should have 2 elements
    And the JSON nodes should contain:
      | page       | 3 |
      | totalPages | 5 |
      | count      | 10 |
      | limit      | 2 |
      | contacts[0].name | 5 |
      | contacts[1].name | 6 |

  Scenario: can limit contacts on page 5
    Then I send a "GET" request to "/api/contact?limit=2&page=5"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "contacts" should have 2 elements
    And the JSON nodes should contain:
      | page       | 5 |
      | totalPages | 5 |
      | count      | 10 |
      | limit      | 2 |
      | contacts[0].name | 9  |
      | contacts[1].name | 10 |
